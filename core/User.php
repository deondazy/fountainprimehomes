<?php

namespace Okoye\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class User extends Base
{
    public function __construct($table = null)
    {
        ($table) ? $this->table = $table : parent::__construct('users');
    }

    /**
     * Logs in a user
     *
     * @param string $username User supplied username.
     * @param string $password User supplied password.
     * @param bool $remember Keep user logged in after session? Default to false.
     *
     */
    public function login($email, $password, $remeber = true)
    {
        $email    = Util::escape(strtolower($email));
        $user     = $this->getByEmail($email);
        $password = trim($password);

        if (!$user) {
            $this->error[] = "Email and password combination is incorrect";
            return false;
        }

        if ($password != $user->password) {
            $this->error[] = "Email and password combination is incorrect";
            return false;
        }

        if ($user->is_active != 1) {
            $this->error[] = "Account has not been activated";
            return false;
        }

        $sessiondata = $this->addSession($user->id);

        if (!$sessiondata) {
            $this->error[] = 'System error: Unable to login';
            return false;
        }

        $this->update(['last_login' => date('Y-m-d H:i:s')], $user->id);

        $return['message'] = 'Login successful';
        $return['user_id'] = $user->id;
        $return['hash']    = $sessiondata['hash'];
        $return['expire']  = $sessiondata['expire'];

        return $return;
    }

    /**
     * Creates a new user, adds them to database
     *
     * @param string $email
     * @param string $password
     * @param string $repeatpassword
     * @param array  $params
     * @param bool $sendmail = NULL
     *
     * @return bool
     */
    public function register($email, $password, array $params = [], $sendmail = NULL)
    {
        // if ($password !== $repeatpassword) {
        //     $this->error[] = "Password mismatch";
        //     return false;
        // }

        // Password length
        if (strlen($password) < 8) {
            $this->error[] = "Password is too short, minimum of 8 characters required";
            return false;
        }

        // Validate email
        if (!Util::isEmail($email)) {
            $this->error[] = "Email address is invalid";
            return false;
        }

        if ($this->getByEmail($email)) {
            $this->error[] = "Email is taken";
            return false;
        }

        $addUser = $this->addUser($email, $password, $params, $sendmail);

        if (!$addUser) {
            $this->error[] = "Unable to register";
            return false;
        }

        return $addUser;
    }

    /**
     * Logs out the session, identified by hash
     *
     * @param string $hash
     *
     * @return bool
     */
    public function logout($hash)
    {
        if (strlen($hash) < 40) {
            return false;
        }

        return $this->deleteSession($hash);
    }

    /**
     * Creates an activation entry and sends email to user
     *
     * @param int $uid
     * @param string $email
     * @param string $type
     * @param boolean $sendmail = NULL
     *
     * @return boolean
     */
    protected function addRequest($user_id, $email, $type, &$sendmail)
    {
        global $config;

        if ($type != "activation" && $type != "reset") {
            $this->error[] = "Error: Invalid request";
            return false;
        }

        $query = Database::instance()->query(
            "SELECT id, expire
            FROM {$config->database_tables->requests}
            WHERE user_id = ?
            AND type = ?"
        );
        $query->execute([$user_id, $type]);

        if (Database::instance()->rowCount() > 0) {
            $row = Database::instance()->fetchOne();

            $expiredate = $row->expire;
            $currentdate = time();

            if ($currentdate < $expiredate) {
                $this->error[] = "A reset request already exists.";
                return false;
            }

            $this->deleteRequest($row->id);
        }

        if ($type == "activation" && $this->getById($user_id)->is_active == 1) {
            $this->error[] = "Account is already activated.";
            return false;
        }

        $key = Util::getRandomString(20);
        $expire = $config->site->request_expire;

        $query = Database::instance()->query(
            "INSERT INTO {$config->database_tables->requests}
            (user_id, request_key, expire, type)
            VALUES (?, ?, ?, ?)"
        );

        if (!$query->execute([$user_id, $key, $expire, $type])) {
            $this->error[] = "System Error: Request failed";

            return false;
        }

        $request_id = Database::instance()->lastInsertId();

        if ($sendmail === true) {
            // Check configuration for SMTP parameters
            $mail = new PHPMailer;

            $mail->CharSet = $config->mail->charset;
            if ($config->mail->use_smtp) {
                $mail->isSMTP();
                $mail->Host = $config->mail->smtp_host;
                $mail->SMTPAuth = $config->mail->smtp_auth;
                if (!is_null($config->mail->smtp_auth)) {
                    $mail->Username = $config->mail->smtp_username;
                    $mail->Password = $config->mail->smtp_password;
                }
                $mail->Port = $config->mail->smtp_port;

                if (!is_null($config->mail->smtp_security)) {
                    $mail->SMTPSecure = $config->mail->smtp_security;
                }
            }

            $mail->From = $config->site->security_email;
            $mail->FromName = $config->site->name;
            $mail->addAddress($email);
            $mail->isHTML(true);

            if ($type == "activation") {
                $mail->Subject = sprintf('%s - Activate account', $config->site->name);

                $emailBody = file_get_contents(__DIR__ . '/../emails/activate.html');
                $emailBody = str_replace('{code}', $key, $emailBody);

                $mail->Body = $emailBody;

                $mail->AltBody = sprintf('Hello, ' . "\n\n" . 'To be able to log in to your account you first need to activate your account by visiting the following link :' . "\n" . '%1$s/%2$s?key=%3$s' . "\n\n" . 'If you did not sign up on %1$s recently then this message was sent in error, please ignore it.', $config->site->url, 'activate', $key);
            } else {
                $mail->Subject = sprintf('%s - Password reset request', $config->site->name);

                $mail->Body = sprintf('Hello,<br/><br/>To reset your password click the following link :<br/><br/><strong><a href="%1$s/%2$s?key=%3$s">%1$s/%2$s?key=%3$s</a></strong><br/><br/>If you did not request a password reset key on %1$s recently then this message was sent in error, please ignore it.', $config->site->url, 'reset', $key);

                $mail->AltBody = sprintf('Hello, ' . "\n\n" . 'To reset your password please visiting the following link :' . "\n" . '%1$s/%2$s?key=%3$s' . "\n\n" . 'If you did not request a password reset key on %1$s recently then this message was sent in error, please ignore it.', $config->site->url, 'reset', $key);
            }

            if (!$mail->send()) {
                $this->deleteRequest($request_id);
                $this->error[] = 'System error: Unable to send email';

                return false;
            }
        }

        return true;
    }

    /**
     * Returns request data if key is valid
     *
     * @param string $key
     * @param string $type
     *
     * @return array $return
     */
    public function getRequest($key, $type)
    {
        global $config;

        $query = Database::instance()->query("SELECT id, user_id, expire FROM {$config->database_tables->requests} WHERE request_key = ? AND type = ?");
        $query->execute([$key, $type]);

        if ($query->rowCount() == 0) {
            $this->error[] = 'Incorrect key';

            return false;
        }

        $row = Database::instance()->fetchOne();

        $expiredate = $row->expire;
        $currentdate = time();

        if ($currentdate > $expiredate) {
            $this->deleteRequest($row->id);
            $this->error[] = 'Key expired';

            return false;
        }

        $return['error'] = false;
        $return['id'] = $row->id;
        $return['user_id'] = $row->user_id;

        return $return;
    }

    /**
     * Deletes request from database
     *
     * @param int $id
     *
     * @return boolean
     */
    protected function deleteRequest($id)
    {
        global $config;

        $query = Database::instance()->query("DELETE FROM {$config->database_tables->requests} WHERE id = ?");

        return $query->execute([$id]);
    }

    /**
     * Creates a reset key for an email address and sends email
     *
     * @param string $email
     *
     * @return array $return
     */
    public function requestReset($email, $sendmail = NULL)
    {
        if (!Util::isEmail($email)) {
            $this->error[] = "Invalid email";
            return false;
        }

        $query = Database::instance()->query("SELECT id FROM {$this->table} WHERE email = ?");
        $query->execute([$email]);

        if (Database::instance()->rowCount() == 0) {
            $this->error[] = "Email is not found";
            return false;
        }

        $addRequest = $this->addRequest(Database::instance()->fetchOne()->id, $email, "reset", $sendmail);

        if (!$addRequest) {
            $this->error[] = "Request failed";
            return false;
        }
        return true;
    }

    /**
     * Allows a user to reset their password after requesting a reset key.
     *
     * @param string $key
     * @param string $password
     * @param string $repeatpassword
     * @param string $captcha = NULL
     *
     * @return array $return
     */
    public function resetPass($key, $password, $repeatpassword)
    {
        if (strlen($key) < 6) {
            $this->error[] = "Invalid reset key";
            return false;
        }

        if ($password !== $repeatpassword) {
            // Passwords don't match
            $this->error[] = "Password don't match";
            return false;
        }

        $data = $this->getRequest($key, "reset");

        if ($data['error'] == 1) {
            $this->error[] = "Request error";
            return false;
        }

        $user = $this->getById($data['user_id']);

        if (!$user) {
            $this->deleteRequest($data['id']);
            $this->error[] = "System error: Unkwon user";
            return false;
        }

        if ($password == $user->password) {
            $this->error[] = "New password is the same as old password";
            return false;
        }

        $query = Database::instance()->query("UPDATE {$this->table} SET password = ? WHERE id = ?");
        $query->execute([$password, $data['user_id']]);

        if ($query->rowCount() == 0) {
            $this->error[] = "System error: Reset failed";
            return false;
        }

        $this->deleteRequest($data['id']);

        return true;
    }

    /**
     * Adds a new user to database
     *
     * @param string $email      -- email
     * @param string $password   -- password
     * @param array $params      -- additional params
     *
     * @return int $userId
     */
    protected function addUser($email, $password, array $params = [], &$sendmail = true)
    {
        $query = Database::instance()->query("INSERT INTO {$this->table} VALUES ()");

        if (!$query->execute()) {
            $this->error[] = "System Error: Query execution failed";
            return false;
        }

        $userId = Database::instance()->lastInsertId();
        $email = Util::escape(strtolower($email));

        if ($sendmail) {
            $addRequest = $this->addRequest($userId, $email, "activation", $sendmail);

            if (!$addRequest) {
                $query = Database::instance()->query("DELETE FROM {$this->table} WHERE id = ?");
                $query->execute([$userId]);

                $this->error[] = 'Request failed';

                return false;
            }
            $isActive = 0;
        } else {
            $isActive = 1;
        }

        //$password = PASSWORD_HASH($password, PASSWORD_DEFAULT);

        if (is_array($params) && count($params) > 0) {
            $customParamsQueryArray = [];

            foreach ($params as $paramKey => $paramValue) {
                $customParamsQueryArray[] = array('value' => $paramKey . ' = ?');
            }

            $setParams = ', ' . implode(', ', array_map(function ($entry) {
                return $entry['value'];
            }, $customParamsQueryArray));
        } else {
            $setParams = '';
        }

        $query = Database::instance()->query("UPDATE {$this->table} SET email = ?, password = ?, is_active = ? {$setParams} WHERE id = ?");

        $bindParams = array_values(array_merge([$email, $password, $isActive], $params, [$userId]));

        if (!$query->execute($bindParams)) {
            $query = Database::instance()->query("DELETE FROM {$this->table} WHERE id = ?");
            $query->execute([$userId]);
            $this->error[] = "System Error: Unable to add a user";

            return false;
        }

        return $userId;
    }

    /**
     * Gets ID for a given email address and returns an array
     *
     * @param string $email
     *
     * @return array $userId
     */
    public function getUserId($email)
    {
        $query = Database::instance()->query("SELECT `id` FROM {$this->table} WHERE `email` = ?");
        $query->execute([$email]);

        if (Database::instance()->rowCount() == 0) {
            return false;
        }

        return Database::instance()->fetchOne()->id;
    }

    /**
     * Retrieve the id of logged user.
     *
     * @return int
     */
    public function currentUserId()
    {
        global $config;

        $hash = isset($_COOKIE[$config->cookie->login['name']]) ? $_COOKIE[$config->cookie->login['name']] : '';

        $query = Database::instance()->query("SELECT user_id FROM {$config->database_tables->sessions} WHERE hash = ?");
        $query->execute([$hash]);

        if ($query->rowCount() == 0) {
            return false;
        }

        return Database::instance()->fetchOne()->user_id;
    }

    /**
     * Activates a user's account
     *
     * @param string $key
     *
     * @return array $return
     */
    public function activate($key)
    {
        if (strlen($key) < 20) {
            $this->error[] = "Invalid activation key";
            return false;
        }

        $getRequest = $this->getRequest($key, "activation");

        if (!$getRequest) {
            return false;
        }

        if ($getRequest['error'] == 1) {
            $this->error[] = "Request error";

            return false;
        }

        if ($this->getById($getRequest['user_id'])->is_active == 1) {
            $this->deleteRequest($getRequest['id']);
            $this->error = "Request error: Account is already active";

            return false;
        }

        $return['id'] = $this->getById($getRequest['user_id'])->id;

        $query = Database::instance()->query("UPDATE {$this->table} SET is_active = :is_active WHERE id = :id");
        $query->execute([':is_active' => 1, ':id' => $getRequest['user_id']]);

        $this->deleteRequest($getRequest['id']);

        $return['error'] = false;
        $return['message'] = "Account activated";

        return $return;
    }

    /**
     * Checks if a user is logged.
     *
     * @return bool
     */
    public function isLogged()
    {
        global $config;

        /*if ($this->get('2fa', $this->currentUserId())) {
            $_2fa_real = md5($this->get('email', $this->currentUserId()).$config->cookie->_2fa['secret']);
            return (isset($_COOKIE[$config->cookie->_2fa['name']]) && $_2fa_real == $_COOKIE[$config->cookie->_2fa['name']]);
        } else {*/
        return (isset($_COOKIE[$config->cookie->login['name']]) && $this->checkSession($_COOKIE[$config->cookie->login['name']]));
        //}
    }

    public function addCookie($hash, $expire)
    {
        global $config;

        $cookieName = $config->cookie->login['name'];
        $cookieVal  = trim($hash);
        $cookieExp  = trim($expire);
        $cookiePath = preg_replace('|https?://[^/]+|i', '', $config->site->url . '/');

        // Set cookie for the logged user
        return setcookie($cookieName, $cookieVal, $cookieExp, $cookiePath);
    }

    public function add2faCookie($email)
    {
        global $config;

        $cookieName = $config->cookie->_2fa['name'];
        $cookieVal  = md5($email . $config->cookie->_2fa['secret']);
        $cookieExp  = trim($config->cookie->_2fa['expire']);
        $cookiePath = preg_replace('|https?://[^/]+|i', '', $config->site->url . '/');

        // Set cookie for the logged user
        return setcookie($cookieName, $cookieVal, $cookieExp, $cookiePath);
    }

    /**
     * Creates a session for a specified user_id
     *
     * @param int $user_id
     * @param boolean $remember
     *
     * @return array $data
     */
    protected function addSession($user_id)
    {
        global $config;

        $ip = $this->getIp();
        $user = $this->getById($user_id);

        if (!$user) {
            return false;
        }

        $data['hash'] = sha1($config->site->key . microtime());
        $agent = $_SERVER['HTTP_USER_AGENT'];

        $this->deleteExistingSessions($user_id);

        $data['expire'] = $config->cookie->login['expire'];

        $data['cookie_value'] = sha1($data['hash'] . $config->site->key);

        $query = Database::instance()->query("INSERT INTO {$config->database_tables->sessions} (user_id, hash, expire_date, ip, agent, cookie_value) VALUES (?, ?, ?, ?, ?, ?)");

        if (!$query->execute(array($user_id, $data['hash'], $data['expire'], $ip, $agent, $data['cookie_value']))) {
            return false;
        }

        return $data;
    }

    /**
     * Checks if a session is valid
     *
     * @param string $hash
     *
     * @return bool
     */
    public function checkSession($hash)
    {
        global $config;

        $ip = $this->getIp();
        $table_session = $config->database_tables->sessions;

        Database::instance()->query("SELECT `id`, `user_id`, `hash`, `expire_date`, `ip`, `agent`, `cookie_value` FROM {$table_session} WHERE `hash` = :hash");
        Database::instance()->bind(':hash', $hash);
        Database::instance()->execute();

        if (Database::instance()->rowCount() == 0) {
            return false;
        }

        $result      = Database::instance()->fetchOne();
        $sessionId   = $result->id;
        $userId      = $result->user_id;
        $expireDate  = $result->expire_date;
        $currentDate = time();
        $dbIp        = $result->ip;
        $dbAgent     = $result->agent;
        $dbCookie    = $result->cookie_value;

        if ($currentDate > $expireDate) {
            $this->deleteExistingSessions($userId);

            return false;
        }

        /*if ($ip != $dbIp) {
            return false;
        }*/

        if ($dbCookie == sha1($hash . $config->site->key)) {
            return true;
        }

        return false;
    }

    /**
     * Removes all existing sessions for a given user_id
     *
     * @param int $user_id
     *
     * @return bool
     */
    protected function deleteExistingSessions($user_id)
    {
        global $config;

        $table_session = $config->database_tables->sessions;

        Database::instance()->query("DELETE FROM {$table_session} WHERE user_id = :user_id");
        Database::instance()->bind(':user_id', $user_id);
        Database::instance()->execute();

        $loginCookie = $config->cookie->login['name'];
        $_2faCookie = $config->cookie->_2fa['name'];
        $cookiePath = preg_replace('|https?://[^/]+|i', '', $config->site->url . '/');

        // Set cookie for the logged user
        setcookie($loginCookie, '', time() - 7000, $cookiePath);
        setcookie($_2faCookie, '', time() - 7000, $cookiePath);

        return Database::instance()->rowCount() == 1;
    }

    /**
     * Removes all existing sessions for a given hash
     *
     * @param int $user_id
     *
     * @return bool
     */
    protected function deleteSession($hash)
    {
        global $config;

        $table_session = $config->database_tables->sessions;

        Database::instance()->query("DELETE FROM {$table_session} WHERE hash = :hash");
        Database::instance()->bind(':hash', $hash);
        Database::instance()->execute();

        $loginCookie = $config->cookie->login['name'];
        $_2faCookie = $config->cookie->_2fa['name'];
        $cookiePath = preg_replace('|https?://[^/]+|i', '', $config->site->url . '/');

        // Set cookie for the logged user
        setcookie($loginCookie, '', time() - 7000, $cookiePath);
        setcookie($_2faCookie, '', time() - 7000, $cookiePath);

        return Database::instance()->rowCount() == 1;
    }

    /**
     * Get a user data by hash
     *
     * @param int $hash
     *
     * @return bool
     */
    public function getByHash($hash)
    {
        global $config;

        $table_session = $config->database_tables->sessions;

        Database::instance()->query("SELECT user_id FROM {$table_session} WHERE hash = :hash");
        Database::instance()->bind(':hash', $hash);
        Database::instance()->execute();

        $uid = Database::instance()->fetchOne()->user_id;

        return $this->getByID($uid);
    }

    /**
     * Checks if Email is verified
     *
     * @return bool
     */
    public function isEmailVerified($user_id)
    {
        return (bool) $this->get('is_email_verified', $user_id);
    }

    /**
     * Checks if user is an administrator.
     *
     * @return bool
     */
    public function isAdmin($user_id)
    {
        return (bool) $this->get('admin', $user_id);
    }

    /**
     * Gets user data by user_id
     *
     * @param string $user_id
     *
     * @return bool|object
     */
    public function getById($user_id)
    {
        Database::instance()->query("SELECT * FROM {$this->table} WHERE id = :uid");
        Database::instance()->bind(':uid', $user_id);
        Database::instance()->execute();

        if (Database::instance()->rowCount() == 0) {
            return false;
        }

        return Database::instance()->fetchAll()[0];
    }

    /**
     * Gets user data by username
     *
     * @param string $username
     *
     * @return bool|object
     */
    public function getByName($username)
    {
        Database::instance()->query("SELECT * FROM {$this->table} WHERE username = :name");
        Database::instance()->bind(':name', $username);
        Database::instance()->execute();

        if (Database::instance()->rowCount() == 0) {
            return false;
        }

        return Database::instance()->fetchAll()[0];
    }

    /**
     * Gets user data by email
     *
     * @param string $email
     *
     * @return bool|object
     */
    public function getByEmail($email)
    {
        Database::instance()->query("SELECT * FROM {$this->table} WHERE email = :email");
        Database::instance()->bind(':email', $email);
        Database::instance()->execute();


        if (Database::instance()->rowCount() == 0) {
            return false;
        }

        return Database::instance()->fetchAll()[0];
    }

    /**
     * Find user by coin address
     *
     * @param string $address
     *
     * @return null|object
     */
    public function findByAddress($address)
    {
        $query = Database::instance()->query(
            "SELECT * FROM {$this->table} WHERE btc_address = :address OR eth_address = :address"
        );
        $query->bindValue(':address', $address);
        $query->execute();
        if ($query->fetchColumn() > 0) {
            return Database::instance()->fetchAll()[0];
        }
        return null;
    }

    /**
     * Get user IP Address
     *
     * @return string
     */
    public function getIp()
    {
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } elseif (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        }

        return $ipaddress;
    }

    /**
     * Get user country from IP Address
     */
    public function getCountry()
    {
        $ip = $this->getIp(); // This will contain the ip of the request

        // You can use a more sophisticated method to retrieve the content of a webpage with php using a library or something
        // We will retrieve quickly with the file_get_contents
        $dataArray = json_decode(Util::urlGetContents("http://www.geoplugin.net/json.gp?ip=" . $ip));

        // outputs something like (obviously with the data of your IP) :

        // geoplugin_countryCode => "DE",
        // geoplugin_countryName => "Germany"
        // geoplugin_continentCode => "EU"

        if (!is_null($dataArray->geoplugin_countryName)) {
            return $dataArray->geoplugin_countryName;
        }

        return 'United States';
    }

    public function avatar($id)
    {
        global $site;

        if (!empty($this->get('avatar', $id))) {
            return $this->get('avatar', $id);
        }

        return $site->url . '/assets/images/user/generic.jpg';
    }

    public function getExperiences($id)
    {
        $userExperienceClass = new UserExperience;

        return $userExperienceClass->getAll("user_id = {$id}", 'create_date DESC');
    }

    public function getEducation($id)
    {
        $userEducationClass = new UserEducation;

        return $userEducationClass->getAll("user_id = {$id}", 'create_date DESC');
    }
}

<?php
/**
 * The Okoye Bootstrap File
 *
 */

// Define the app version
define('OKOYE_VERSION', '1.0.0');

// Define the DATABASE version
define('OKOYE_DB_VERSION', '1'); // Increment on every DB change.

// Define required PHP version
define('OKOYE_PHP', '7.4');

// Define installation root path
define('OKOYE_ROOT', dirname(__FILE__));

// Compare PHP versions against our required version
if (!version_compare(OKOYE_PHP, '7.4', '>=')) {
    exit(
        'OKOYE require PHP '.OKOYE_PHP.' or higher, you currently have PHP '.PHP_VERSION
    );
}

// Increase error reporting to E_ALL
error_reporting(E_ALL);

// Set default timezone, we'll base off of this later
// date_default_timezone_set('UTC');

// Require Autoloader
require_once OKOYE_ROOT . '/vendor/autoload.php';

// Use our own exception handler
//Okoye\Core\Exception\OkoyeException::handle();

// Require the configuration file
require OKOYE_ROOT . '/config.php';

if ($config->debug->on) {
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    ini_set('error_log', $config->debug->logPath);
}

// Get Database configuration details
$db = $config->database;

// Connect to the Database
try {
    Okoye\Core\Database::instance()->connect("mysql:host=$db->host;dbname=$db->name", $db->user, $db->password);
} catch (Okoye\Core\Exception\DatabaseException $e) {
    if ($config->debug->on) {
        echo $e->getMessage();
    }
    $log = new Okoye\Core\Log($config);
    $log->error($e->getMessage());
}

// All good! create needed objects
$user = new Okoye\Core\User;
$site = $config->site;
$log  = new Okoye\Core\Log($config);

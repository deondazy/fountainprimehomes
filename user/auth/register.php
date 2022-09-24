<?php 
if (!session_id()) {
    session_start();
}

require __DIR__ . '/../../bootstrap.php';

$page = 'Register';

// DO SIGN UP
if ("POST" == $_SERVER["REQUEST_METHOD"]) {
    $firstName = Util::escape($_POST['fname']);
    $lastName  = Util::escape($_POST['lname']);
    $email     = Util::escape($_POST["email"]);
    $password  = trim($_POST["password"]);
    $timeNow   = time();

    $register = $user->register($email, $password, 
    [
        "first_name"    => $firstName,
        "last_name"     => $lastName,
        "register_date" => $timeNow,
    ], true);

    if ($register) {
        Util::redirect($site->url . '/success/');
    } else {
        foreach ($user->error as $error) {
            Util::flash('error', $error, 'alert alert-danger alert-styled-left');
        }

        Util::redirect($site->url . '/sign-up/');
    }
}

include OKOYE_ROOT . '/header.php';
?>

<section class="headings">
            <div class="text-heading text-center">
                <div class="container">
                    <h1><?php echo $page; ?></h1>
                </div>
            </div>
        </section>
        <!-- END SECTION HEADINGS -->

        <!-- START SECTION LOGIN -->
        <div id="login">
            <div class="login">
                <?php Util::flash('error'); ?>
                <?php Util::flash('success'); ?>
                
                <form method="post">
                    <div class="form-group">
                        <label for="fname">First name</label>
                        <input type="text" class="form-control" name="fname" id="fname" autofocus>
                    </div>
                    
                    <div class="form-group">
                        <label for="lname">Last name</label>
                        <input type="text" class="form-control" name="lname" id="lname">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>

                    <div class="fl-wrap filter-tags clearfix add_bottom_30">
                        <div class="checkboxes float-left">
                            <div class="filter-tags-wrap">
                                <input id="check-b" type="checkbox" name="check">
                                <label for="check-b">I accept the <a target="_blank" href="<?php echo $site->url; ?>/terms/">Terms and Conditions</a></label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="submit" class="btn_1 rounded full-width">Login</button>
                    
                    <div class="text-center add_top_10">Already have an account? <strong><a href="<?php echo $site->url; ?>/login/">Login!</a></strong></div>
                </form>
            </div>
        </div>
        <!-- END SECTION LOGIN -->

<?php include OKOYE_ROOT . '/footer.php';
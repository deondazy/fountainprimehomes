<?php 
if (!session_id()) {
    session_start();
}

use Okoye\Core\Util;

require __DIR__ . '/../../bootstrap.php';

$page = 'Reset Password';

// DO PASSWORD RESET
if ("POST" == $_SERVER["REQUEST_METHOD"]) {
    $email = Util::escape($_POST["email"]);

    $reset = $user->requestReset($email, true);

    if ($reset) {
        Util::flash('success', 'Password recovery information has been successfully sent to your mail.', 'alert alert-success alert-styled-left');
        Util::redirect($site->url . '/forgot-password/');
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

                <div class="alert alert-warning">Enter your registered email and follow the instructions sent to you to reset your password.</div>

                <form method="post">
                    <div class="form-group">
                        <a class="rounded float-right" id="cancel" href="<?php echo $site->url; ?>/login/">Cancel</a>
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" autofocus>
                    </div>


                    <button type="submit" id="submit" class="btn_1 rounded full-width">Reset Password</button>
                    
                </form>
            </div>
        </div>
        <!-- END SECTION LOGIN -->

<?php include OKOYE_ROOT . '/footer.php';
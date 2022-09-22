<?php 
if (!session_id()) {
    session_start();
}

require __DIR__ . '/../../bootstrap.php';

$page = 'Login';

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
                <form method="post">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>

                    <div class="fl-wrap filter-tags clearfix add_bottom_30">
                        <div class="checkboxes float-left">
                            <div class="filter-tags-wrap">
                                <input id="check-b" type="checkbox" name="check">
                                <label for="check-b">Remember me</label>
                            </div>
                        </div>

                        <div class="float-right mt-1"><a id="forgot" href="<?php echo $site->url; ?>/forgot-password/">Forgot Password?</a></div>
                    </div>

                    <button type="submit" id="submit" class="btn_1 rounded full-width">Login</button>
                    
                    <div class="text-center add_top_10">New to <?php echo $site->name; ?>? <strong><a href="<?php echo $site->url; ?>/register/">Create an account!</a></strong></div>
                </form>
            </div>
        </div>
        <!-- END SECTION LOGIN -->

<?php include OKOYE_ROOT . '/footer.php';
<?php 
if (!session_id()) {
    session_start();
}

use Okoye\Core\Util;

require __DIR__ . '/../../bootstrap.php';

$page = 'Register';

// DO SIGN UP
if ("POST" == $_SERVER["REQUEST_METHOD"]) {
    $firstName = Util::escape($_POST['fname']);
    $lastName  = Util::escape($_POST['lname']);
    $email     = Util::escape($_POST["email"]);
    $phone     = Util::escape($_POST["phone"]);
    $country   = Util::escape($_POST["country"]);
    $password  = trim($_POST["password"]);
    $timeNow   = time();

    $register = $user->register($email, $password, 
    [
        "first_name"    => $firstName,
        "last_name"     => $lastName,
        "phone"         => $phone,
        "country"       => $country,
        "register_date" => $timeNow,
    ], false);

    if ($register) {
        Util::redirect($site->url . '/success/');
    } else {
        foreach ($user->error as $error) {
            Util::flash('error', $error, 'alert alert-danger alert-styled-left');
        }

        Util::redirect($site->url . '/register/');
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
<div id="register">
    <div class="register">
        <?php Util::flash('error'); ?>
        <?php Util::flash('success'); ?>

        <form method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fname">First name</label>
                        <input type="text" class="form-control" name="fname" id="fname" autofocus required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lname">Last name</label>
                        <input type="text" class="form-control" name="lname" id="lname" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" class="form-control" name="phone" id="phone" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" autocomplete="new-password" required>
            </div>

            <div class="form-group">
                <label for="country">Country</label>
                <select class="form-control" name="country" id="country" required>
                    <option>Select country</option>
                    <?php foreach ($countryClass->getAll() as $country): ?>
                        <option value="<?php echo $country->id; ?>"><?php echo $country->nicename; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="fl-wrap filter-tags clearfix add_bottom_30">
                <div class="checkboxes float-left">
                    <div class="filter-tags-wrap">
                        <input id="check-b" type="checkbox" name="check" required>
                        <label for="check-b">I accept the <a target="_blank" href="<?php echo $site->url; ?>/terms/">Terms and Conditions</a></label>
                    </div>
                </div>
            </div>

            <button type="submit" id="submit" class="btn_1 rounded full-width">Create account</button>
            
            <div class="text-center add_top_10">Already have an account? <strong><a href="<?php echo $site->url; ?>/login/">Login!</a></strong></div>
        </form>
    </div>
</div>
<!-- END SECTION LOGIN -->

<?php include OKOYE_ROOT . '/footer.php';
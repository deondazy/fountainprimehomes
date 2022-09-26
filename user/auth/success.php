<?php 
if (!session_id()) {
    session_start();
}

use Okoye\Core\Util;

require __DIR__ . '/../../bootstrap.php';

$page = 'Registered Successfully!';

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
        <div class="alert alert-info">
            <p>You have successfully registered. Please check your email for a verification link.</p>
        </div>
    </div>
</div>
<!-- END SECTION LOGIN -->

<?php include OKOYE_ROOT . '/footer.php';

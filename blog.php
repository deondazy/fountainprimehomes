<?php
require __DIR__ . '/bootstrap.php';

$page = 'Blog';

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

<!-- START SECTION ABOUT -->
<section class="about-us fh">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12">
                <p class="text-center">Content will be pulled from Admin Blog entries</p>
            </div>
        </div>
    </div>
</section>
<!-- END SECTION ABOUT -->

<?php include OKOYE_ROOT . '/footer.php';
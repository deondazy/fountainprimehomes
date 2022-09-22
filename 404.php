<?php
require __DIR__ . '/bootstrap.php';

$page = '404 - Not Found';

include OKOYE_ROOT . '/header.php';
?>

<!-- START SECTION 404 -->
<section class="notfound pt-0">
    <div class="container">               
        <div class="top-headings text-center">
            <img src="<?php echo $site->url; ?>/images/bg/error-404.jpg" alt="Page 404">
            <h3 class="text-center">Page Not Found!</h3>
            <p class="text-center">Oops! Looks Like Something Going RRRong! We can't seem to find the page you're looking for make sure that you have typed the correct URL</p>
        </div>
        <div class="port-info">
            <a href="<?php echo $site->url; ?>/" class="btn btn-primary btn-lg">Go To Home</a>
        </div>
    </div>
</section>
<!-- END SECTION 404 -->

<?php include OKOYE_ROOT . '/footer.php';

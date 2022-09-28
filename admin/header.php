<?php
if (!session_id()) {
    session_start();
}

use Okoye\Core\Site;
use Okoye\Core\Util; 

if (!$user->isLogged()) {
    Util::redirect($site->url . '/login/?refurl=' . urlencode($_SERVER['REQUEST_URI']));
}

if (!$user->isAdmin($user->currentUserId())) {
    Util::redirect($site->url . '/office/');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pageName; ?> - <?php echo $site->name; ?> - Admin</title>

    <!-- FAVICON -->
    <!-- <link rel="apple-touch-icon" sizes="57x57" href="<?php echo $site->url; ?>/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo $site->url; ?>/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo $site->url; ?>/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $site->url; ?>/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo $site->url; ?>/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $site->url; ?>/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo $site->url; ?>/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $site->url; ?>/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $site->url; ?>/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo $site->url; ?>/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $site->url; ?>/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo $site->url; ?>/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $site->url; ?>/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?php echo $site->url; ?>/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo $site->url; ?>/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff"> -->

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="<?php echo $site->url; ?>/admin/assets/css/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $site->url; ?>/admin/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $site->url; ?>/admin/assets/css/core.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $site->url; ?>/admin/assets/css/components.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $site->url; ?>/admin/assets/css/colors.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $site->url; ?>/admin/assets/css/animate.min.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="<?php echo $site->url; ?>/admin/assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="<?php echo $site->url; ?>/admin/assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $site->url; ?>/admin/assets/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo $site->url; ?>/admin/assets/js/plugins/loaders/blockui.min.js"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="<?php echo $site->url; ?>/admin/assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="<?php echo $site->url; ?>/admin/assets/js/plugins/forms/styling/switchery.min.js"></script>
    <script type="text/javascript" src="<?php echo $site->url; ?>/admin/assets/js/plugins/forms/styling/switch.min.js"></script>

    <script type="text/javascript" src="<?php echo $site->url; ?>/admin/assets/js/core/app.min.js"></script>
    <script type="text/javascript" src="<?php echo $site->url; ?>/admin/assets/js/pages/office.js"></script>
    <script type="text/javascript" src="<?php echo $site->url; ?>/office/js/Chart.min.js"></script>
    <!-- /theme JS files -->
</head>

<body>
    <!-- Main navbar -->
    <div class="navbar navbar-inverse">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?php echo $site->url; ?>/">
                <img src="<?php echo $site->url; ?>/images/icons/logo1.png" alt="<?php echo $site->name; ?>" width="200">
            </a>

            <ul class="nav navbar-nav visible-xs-block">
                <li><a href="<?php echo $site->url; ?>/office/">Office</a></li>
                <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
                <li><a href="<?php echo $site->url; ?>/office/logout/"><i class="icon-switch2"></i></a></li>
            </ul>
        </div>

        <div class="navbar-collapse collapse" id="navbar-mobile">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?php echo $site->url; ?>/office/">Office</a></li>
                <li><a title="Logout" href="<?php echo $site->url; ?>/office/logout/"><i class="icon-switch2"></i></a></li>
            </ul>
        </div>
    </div>
    <!-- /main navbar -->

    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <?php include __DIR__ . '/menu.php'; ?>

            <!-- Main content -->
            <div class="content-wrapper">

                <!-- Page header -->
                <div class="page-header page-header-default">
                    <div class="page-header-content clearfix">
                        <div class="page-title pull-left">
                            <h1><span class="badge badge-success">ADMIN</span> <?php echo $pageName; ?></h1>
                        </div>
                        <div class="pull-right" style="padding:32px 0">
                            <small class="text-muted">Date registered: <?php echo Util::formatDate('F d, Y', $user->get('register_date', $user->currentUserId())); ?></small><br>
                        </div>
                    </div>
                </div>
                <!-- /page header -->


                <!-- Content area -->
                <div class="content">

                    <!-- row -->
                    <div class="row">

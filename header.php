<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="<?php echo $site->desc; ?>">
    <meta name="author" content="">
    <title><?php echo $page; ?> - <?php echo $site->name; ?></title>
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $site->url; ?>/favicon.ico">
    <link rel="stylesheet" href="<?php echo $site->url; ?>/css/jquery-ui.css">
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,300i,400,400i%7CMontserrat:600,800" rel="stylesheet">
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="<?php echo $site->url; ?>/font/flaticon.css">
    <link rel="stylesheet" href="<?php echo $site->url; ?>/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="<?php echo $site->url; ?>/css/fontawesome-5-all.min.css">
    <link rel="stylesheet" href="<?php echo $site->url; ?>/css/font-awesome.min.css">
    <!-- ARCHIVES CSS -->
    <link rel="stylesheet" href="<?php echo $site->url; ?>/css/animate.css">
    <link rel="stylesheet" href="<?php echo $site->url; ?>/css/aos.css">
    <link rel="stylesheet" href="<?php echo $site->url; ?>/css/aos2.css">
    <link rel="stylesheet" href="<?php echo $site->url; ?>/css/magnific-popup.css">
    <link rel="stylesheet" href="<?php echo $site->url; ?>/css/lightcase.css">
    <link rel="stylesheet" href="<?php echo $site->url; ?>/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo $site->url; ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $site->url; ?>/css/menu.css">
    <link rel="stylesheet" href="<?php echo $site->url; ?>/css/slick.css">
    <link rel="stylesheet" href="<?php echo $site->url; ?>/css/styles.css">
    <link rel="stylesheet" href="<?php echo $site->url; ?>/css/maps.css">
    <link rel="stylesheet" id="color" href="<?php echo $site->url; ?>/css/default.css">
</head>

<body class="<?php echo ($page == 'Home') ? 'homepage-4' : 'inner-pages hd-white'; ?>">
    <!-- Wrapper -->
    <div id="wrapper">
        <!-- START SECTION HEADINGS -->
        <!-- Header Container
        ================================================== -->
        <header id="header-container" <?php echo ($page == 'Home') ? 'class="header head-tr"' : ''; ?> <?php echo ($page != 'Home') ? 'style="background-color: var(--white);"' : ''; ?>>
            <!-- Header -->
            <div id="header" class="<?php echo ($page == 'Home') ? 'head-tr bottom' : ''; ?>">
                <div class="container container-header">
                    <!-- Left Side Content -->
                    <div class="left-side">
                        <!-- Logo -->
                        <div id="logo">
                            <a href="<?php echo $site->url; ?>/"><img src="<?php echo $site->url; ?>/images/icons/logo1.png" data-sticky-logo="" alt=""></a>
                        </div>
                        <!-- Mobile Navigation -->
                        <div class="mmenu-trigger">
                            <button class="hamburger hamburger--collapse" type="button">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                        </div>
                        <!-- Main Navigation -->
                        <nav id="navigation" class="style-1 <?php echo ($page == 'Home') ? 'head-tr' : ''; ?>">
                            <ul id="responsive">
                                <li><a href="<?php echo $site->url; ?>/">Home</a>
                                </li>
                                <li><a href="<?php echo $site->url; ?>/apartments/">Apartments</a>
                                </li>
                                <li><a href="<?php echo $site->url; ?>/facilities/">Facilities</a>

                                </li>
                                <li><a href="<?php echo $site->url; ?>/about/">About</a>
                                </li>
                                <li><a href="<?php echo $site->url; ?>/blog/">Blog</a>
                                </li>
                                <li><a href="<?php echo $site->url; ?>/contact-us/">Contact</a></li>
                            </ul>
                        </nav>
                        <!-- Main Navigation / End -->
                    </div>
                    <!-- Left Side Content / End -->

                    <?php if (!$user->isLogged()) : ?>
                    <!-- Right Side Content / End -->
                    <div class="right-side d-none d-none d-lg-none d-xl-flex">
                        <!-- Header Widget -->
                        <div class="header-widget">
                            <a href="<?php echo $site->url; ?>/login/" class="button border mr-4">Login</a>
                            <a href="<?php echo $site->url; ?>/register/" class="button border" style="background-color: var(--red);color:var(--white) !important;">Create account</a>
                        </div>
                        <!-- Header Widget / End -->
                    </div>
                    <!-- Right Side Content / End -->

                    <?php else : ?>

                    <!-- Right Side Content / End -->
                        <div class="header-user-menu user-menu add">
                            <div class="header-user-name">
                                <span><img src="images/testimonials/ts-1.jpg" alt=""></span> Hi, <?php echo ucfirst($user->get('first_name', $user->currentUserId())); ?>!
                            </div>
                            <ul>
                                <li><a href="<?php echo $site->url; ?>/profile/edit/"> Edit profile</a></li>
                                <li><a href="<?php echo $site->url; ?>/profile/change-password/"> Change Password</a></li>
                                <li><a href="<?php echo $site->url; ?>/auth/logout/">Log Out</a></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <!-- Right Side Content / End -->

                    <div class="right-side d-none d-none d-lg-none d-xl-flex sign ml-0">
                        <!-- Header Widget -->
                        <!-- <div class="header-widget sign-in">
                            <div class="show-reg-form modal-open"><a href="<?php echo $site->url; ?>/login/">Login</a></div>
                        </div> -->
                        <!-- Header Widget / End -->
                    </div>
                    <!-- Right Side Content / End -->

                </div>
            </div>
            <!-- Header / End -->

        </header>
        <div class="clearfix"></div>
        <!-- Header Container / End -->
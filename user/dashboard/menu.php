<?php

use Okoye\Core\Site;
use Okoye\Core\User;
use Okoye\Core\Database;

/**
 * Member Menu array
 *
 * 0: Name of the menu item
 * 1: Filename of the menu item
 * 2: Icon for the menu item
 */

$memberMenu[] = ['Dashboard', '', 'icon-meter-fast'];
$memberMenu[] = ['Profile', 'profile/', 'icon-user'];
?>

<!-- Main sidebar -->
<div class="sidebar sidebar-main">
    <div class="sidebar-content">
        <!-- User menu -->
        <div class="sidebar-user">
            <div class="category-content">
                <div class="media">
                    <div class="media-body">
                        <div class="sidebar-avatar text-center">
                            <?php if (empty($user->get('avatar', $user->currentUserId()))) : ?>
                                <img style="border:5px solid #4CAF50;" width="90" class="img-circle" src="https://ui-avatars.com/api/?name=<?php echo ucfirst($user->get('first_name', $user->currentUserId())); ?>+<?php echo ucfirst($user->get('last_name', $user->currentUserId())); ?>&font-size=0.33">
                            <?php else : ?>
                                <img width="90" height="90" class="img-circle" src="<?php echo $user->get('avatar', $user->currentUserId()); ?>" style="height:50px;width:50px;">
                            <?php endif; ?>
                        </div>
                        <div class="text-center mt-15">
                            <h3 style="font-size:16px;" class="sidebar-name">
                                <?php echo ucfirst($user->get('first_name', $user->currentUserId())); ?> <?php echo ucfirst($user->get('last_name', $user->currentUserId())); ?>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->

        <!-- Main navigation -->
        <div class="sidebar-category sidebar-category-visible">
            <div class="category-content no-padding">
                <ul class="navigation navigation-main navigation-accordion">

                    <!-- Menu start -->
                    <?php
                    $url = $site->url . '/user/dashboard/';
                    foreach ($memberMenu as $menu) {
                        if ($file == $menu[1]) {
                            echo "<li class=\"active\">";
                        } else {
                            echo "<li>";
                        }
                        echo "<a href=\"{$url}{$menu[1]}\">";
                        echo "<i class=\"{$menu[2]}\"></i> <span>{$menu[0]}</span>";
                        echo  "</a>";

                        // foreach ($member_submenu as $parent => $submenu_item) {
                        //     if ($parent == $menu[1]) {
                        //         echo "<ul>";
                        //         foreach ($submenu_item as $sub) {
                        //             echo "<li>";
                        //             echo "<a href=\"{$url}{$sub[1]}\">{$sub[0]}</a>";
                        //             echo "</li>";
                        //         }
                        //         echo "</ul>";
                        //     }
                        // }
                        echo "</li>";
                    }
                    ?>
                    <!-- Menu end -->

                    <?php echo "<li><a href=\"{$site->url}/logout/\"><i class=\"icon-switch\"></i> <span>Logout</span></a></li>";
                    ?>
                    <!-- /main -->
                </ul>
            </div>
        </div>
        <!-- /main navigation -->
    </div>
</div>
<!-- /main sidebar -->

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

$memberMenu[1] = ['Overview', '', 'icon-meter-fast'];
$memberMenu[2] = ['Users', 'users/', 'icon-users'];
    $member_submenu['users/'][1] = ['All users', 'users/'];
    $member_submenu['users/'][2] = ['Add new', 'user/add/'];
$memberMenu[3] = ['Posts', 'posts/', 'icon-pen'];
    $member_submenu['posts/'][1] = ['All posts', 'posts/'];
    $member_submenu['posts/'][2] = ['Add new', 'post/add/'];
    $member_submenu['posts/'][3] = ['Categories', 'post/categories/'];
    $member_submenu['posts/'][4] = ['Tags', 'post/tags/'];
?>

<!-- Main sidebar -->
<div class="sidebar sidebar-main">
    <div class="sidebar-content">
        <!-- User menu -->
        <div class="sidebar-user">
            <div class="category-content">
                <div class="media">
                    <div class="media-body">
                        <div class="sidebar-avatar pull-left">
                            <?php if (empty($user->get('avatar', $user->currentUserId()))) : ?>
                                <img width="45" class="img-circle" src="https://ui-avatars.com/api/?name=<?php echo ucfirst($user->get('first_name', $user->currentUserId())); ?>+<?php echo ucfirst($user->get('last_name', $user->currentUserId())); ?>&font-size=0.33">
                            <?php else : ?>
                                <img width="50" height="50" class="img-circle" src="<?php echo $user->get('avatar', $user->currentUserId()); ?>" style="height:50px;width:50px;">
                            <?php endif; ?>
                        </div>
                        <div style="float: left;padding-left: 10px;padding-top:0;">
                            <div style="font-size:16px;" class="sidebar-name text-bold"><?php echo ucfirst($user->get('first_name', $user->currentUserId())); ?> <?php echo ucfirst($user->get('last_name', $user->currentUserId())); ?></div>
                            <small class="sidebar-email text-muted">
                                <?php echo $user->get('email', $user->currentUserId()); ?>
                            </small>
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
                    $url = $site->url . '/admin/';
                    foreach ($memberMenu as $menu) {
                        if ($file == $menu[1]) {
                            echo "<li class=\"active\">";
                        } else {
                            echo "<li>";
                        }
                        echo "<a href=\"{$url}{$menu[1]}\">";
                        echo "<i class=\"{$menu[2]}\"></i> <span>{$menu[0]}</span>";
                        echo  "</a>";

                        foreach ($member_submenu as $parent => $submenu_item) {
                            if ($parent == $menu[1]) {
                                echo "<ul>";
                                foreach ($submenu_item as $sub) {
                                    echo "<li>";
                                    echo "<a href=\"{$url}{$sub[1]}\">{$sub[0]}</a>";
                                    echo "</li>";
                                }
                                echo "</ul>";
                            }
                        }
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

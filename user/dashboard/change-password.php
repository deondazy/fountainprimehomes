<?php 
if (!session_id()) {
    session_start();
}

use Okoye\Core\Util;

require __DIR__ . '/../../bootstrap.php';

$file = 'change-password/';
$pageName = 'Change password';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Set variables from POST data
    $currentPassword = trim($_POST['currentPassword']);
    $newPassword     = trim($_POST['newPassword']);
    $confirmPassword = trim($_POST['confirmPassword']);
    
    $userId = $user->currentUserId();

    // Get current user password
    $userPassword = $user->get('password', $userId);

    // Make current password is not empty
    if (empty($currentPassword)) {
        Util::flash('error', 'Current password is required', 'alert alert-danger');
        Util::redirect($site->url . '/user/dashboard/change-password');
    } 
    
    // Validate current password
    if (!password_verify($currentPassword, $userPassword)) {
        Util::flash('error', 'Current password is incorrect', 'alert alert-danger');
        Util::redirect($site->url . '/user/dashboard/change-password');
    }

    // Make new password is not empty
    if (empty($newPassword)) {
        Util::flash('error', 'New password is required', 'alert alert-danger');
        Util::redirect($site->url . '/user/dashboard/change-password');
    }

    // Make confirm password is not empty
    if (empty($confirmPassword)) {
        Util::flash('error', 'Confirm password is required', 'alert alert-danger');
        Util::redirect($site->url . '/user/dashboard/change-password');
    }

    // Make new password and confirm password match
    if ($newPassword !== $confirmPassword) {
        Util::flash('error', 'New password and confirm password do not match', 'alert alert-danger');
        Util::redirect($site->url . '/user/dashboard/change-password');
    }

    // Update user password
    if ($user->update(['password' => password_hash($newPassword, PASSWORD_DEFAULT)], $userId)) {
        Util::flash('success', 'Password changed successfully', 'alert alert-success');
        Util::redirect($site->url . '/user/dashboard/change-password');
    }
    
    // There was an error updating user password
    Util::flash('error', 'Password could not be changed', 'alert alert-danger');
    Util::redirect($site->url . '/user/dashboard/change-password');
}

include OKOYE_ROOT . '/user/dashboard/header.php'; ?>

<div class="col-lg-12">
    <?php Util::flash('success'); ?>
    <?php Util::flash('error'); ?>

    <div class="panel panel-body">
        <form method="post">
            <div class="form-group">
                <label for="currentPassword">Current password</label>
                <input type="password" class="form-control" name="currentPassword" id="currentPassword" required>
            </div>
            <div class="form-group">
                <label for="newPassword">New password</label>
                <input type="password" class="form-control" name="newPassword" id="newPassword" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm password</label>
                <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" required>
            </div>
            <button type="submit" class="btn btn-primary">Change password</button>
        </form>
    </div>
</div>
<?php include OKOYE_ROOT . '/user/dashboard/footer.php';

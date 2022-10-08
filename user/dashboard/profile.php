<?php 
if (!session_id()) {
    session_start();
}

use Okoye\Core\Util;

require __DIR__ . '/../../bootstrap.php';

$file = 'profile/';
$pageName = 'Profile';

// Get current user id
$userId = $user->currentUserId();

// Get current user data
$userData = $user->getAll("id = {$userId}")[0];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = Util::escape($_POST['firstName']);
    $lastName = Util::escape($_POST['lastName']);
    $address = Util::escape($_POST['address']);
    $city = Util::escape($_POST['city']);
    $state = Util::escape($_POST['state']);
    $country = Util::escape($_POST['country']);
    $avatar = ($_FILES['avatar']['error'] == 0) ? $_FILES['avatar'] : null;

    // var_dump($avatar, $avatar['size']);
    // exit;

    // First name validation
    if (empty($firstName)) {
        $error = 'First name is required';
    } elseif (strlen($firstName) < 3) {
        $error = 'First name must be at least 3 characters';
    } elseif (strlen($firstName) > 20) {
        $error = 'First name must be less than 20 characters';
    }

    // Last name validation
    if (empty($lastName)) {
        $error = 'Last name is required';
    } elseif (strlen($lastName) < 3) {
        $error = 'Last name must be at least 3 characters';
    } elseif (strlen($lastName) > 20) {
        $error = 'Last name must be less than 20 characters';
    }

    // Address validation
    if (empty($address)) {
        $error = 'Address is required';
    } elseif (strlen($address) < 3) {
        $error = 'Address must be at least 3 characters';
    } elseif (strlen($address) > 50) {
        $error = 'Address must be less than 50 characters';
    }

    // City validation
    if (empty($city)) {
        $error = 'City is required';
    } elseif (strlen($city) < 3) {
        $error = 'City must be at least 3 characters';
    } elseif (strlen($city) > 20) {
        $error = 'City must be less than 20 characters';
    }

    // State validation
    if (empty($state)) {
        $error = 'State is required';
    } elseif (strlen($state) < 3) {
        $error = 'State must be at least 3 characters';
    } elseif (strlen($state) > 20) {
        $error = 'State must be less than 20 characters';
    }

    // Country validation
    if (empty($country)) {
        $error = 'Country is required';
    }

    // Avatar must be a valid png, jpg or jpeg file
    if (!empty($avatar)) {
        $avatarExt = pathinfo($avatar['name'], PATHINFO_EXTENSION);
        if (!in_array($avatarExt, ['png', 'jpg', 'jpeg'])) {
            $error = 'Only png, jpg or jpeg are allowed for avatar';
        }
    }

    // Avatar must be less than 2MB
    if (!empty($avatar)) {
        if ($avatar['size'] > 2000000) {
            $error = 'Avatar must be less than 2MB';
        }
    }

    // if there is error, end the script and display error
    if (isset($error)) {
        Util::flash('error', $error, 'alert alert-danger');
        Util::redirect($site->url . '/user/dashboard/profile');
    }

    // Upload avatar
    if (!empty($avatar)) {

        // If user already has an avatar, delete it
        if (!empty($userData->avatar)) {
            unlink(OKOYE_ROOT . '/user/dashboard/uploads/avatars/' . $userData->avatar);
        }

        // Upload new avatar
        $avatarExt = pathinfo($avatar['name'], PATHINFO_EXTENSION);
        $avatarName = uniqid() . '.' . $avatarExt;
        $avatarPath = OKOYE_ROOT . '/user/dashboard/uploads/avatars/' . $avatarName;
        Util::compressImage($avatar['tmp_name'], $avatarPath, 75);
    }

    // User data to be updated
    $userData = [
        'first_name' => $firstName,
        'last_name'  => $lastName,
        'address'    => $address,
        'city'       => $city,
        'state'      => $state,
        'country'    => $country,
        'avatar'     => (!empty($avatar)) ? $avatarName : null,
    ];

    // Update user data
    if ($user->update($userData, $userId)) {
        Util::flash('success', 'Profile updated successfully', 'alert alert-success');
        Util::redirect($site->url . '/user/dashboard/profile');
    }

    Util::flash('error', 'Nothing to update', 'alert alert-danger');
    Util::redirect($site->url . '/user/dashboard/profile');
}

include OKOYE_ROOT . '/user/dashboard/header.php'; ?>

<div class="col-lg-12">
    <?php Util::flash('success'); ?>
    <?php Util::flash('error'); ?>

    <div class="panel panel-body">
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" value="<?php echo $userData->email; ?>" disabled>
                        <span class="help-block">You cannot change your email address</span>
                    </div>

                    <div class="col-md-6">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" value="<?php echo $userData->phone; ?>" disabled>
                        <span class="help-block">You cannot change your phone number</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="firstName">First name</label>
                        <input type="text" class="form-control" name="firstName" id="firstName" value="<?php echo $userData->first_name; ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label for="lastName">Last name</label>
                        <input type="text" class="form-control" name="lastName" id="lastName" value="<?php echo $userData->last_name; ?>" required>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" name="address" id="address" value="<?php echo $userData->address; ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label for="city">City</label>
                        <input type="text" class="form-control" name="city" id="city" value="<?php echo $userData->city; ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="state">State</label>
                        <input type="text" class="form-control" name="state" id="state" value="<?php echo $userData->state; ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label for="country">Country</label>
                        <select class="form-control" name="country" id="country" required>
                            <?php foreach ($countryClass->getAll() as $country): ?>
                                <option value="<?php echo $country->id; ?>" <?php echo $userData->country == $country->id ? 'selected' : ''; ?>><?php echo $country->nicename; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <div class="pull-left mr-20"><img src="<?php echo $user->avatar($userId); ?>" width="90" height="90" class="img-circle" alt="Avatar"></div>

                        <label for="avatar">Avatar</label>
                        <input type="file" name="avatar" id="avatar" accept="image/png, image/jpg, image/jpeg">
                        <span class="help-block">Upload a new avatar. Only PNG & JPG allowed. Max: 2MB</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update profile</button>
        </form>

    </div>
</div>

<?php include OKOYE_ROOT . '/user/dashboard/footer.php';

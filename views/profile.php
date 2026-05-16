<?php require 'views/header.php'; ?>

<div class="container">
    <div class="profile-wrap">

        <h2>My Profile</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <!-- Profile picture -->
        <div class="profile-pic-wrap">
            <?php if (!empty($user['profile_picture'])): ?>
                <img src="<?= htmlspecialchars($user['profile_picture']) ?>"
                     alt="Profile" class="profile-pic">
            <?php else: ?>
                <div class="profile-pic-placeholder">&#128100;</div>
            <?php endif; ?>

            <form method="POST" action="index.php?page=profile"
                  enctype="multipart/form-data" class="form" style="margin-top:10px">
                <input type="hidden" name="action" value="upload_picture">
                <div class="field-row">
                    <input type="file" name="profile_picture" accept="image/*">
                    <button type="submit" class="btn btn-sm">Upload</button>
                </div>
            </form>
        </div>

        <!-- Update info -->
        <div class="card">
            <h3>Personal Information</h3>
            <form method="POST" action="index.php?page=profile"
                  class="form" id="profile-form" novalidate>
                <input type="hidden" name="action" value="update_info">

                <div class="field-row">
                    <div class="field">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name"
                               value="<?= htmlspecialchars($user['name']) ?>" required>
                        <span class="js-error" id="err-name"></span>
                    </div>
                    <div class="field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email"
                               value="<?= htmlspecialchars($user['email']) ?>" required>
                        <span class="js-error" id="err-email"></span>
                    </div>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone"
                               value="<?= htmlspecialchars($user['phone']) ?>" required>
                        <span class="js-error" id="err-phone"></span>
                    </div>
                    <div class="field">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address"
                               value="<?= htmlspecialchars($user['address']) ?>" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>

        <!-- Change password -->
        <div class="card" style="margin-top:20px">
            <h3>Change Password</h3>
            <form method="POST" action="index.php?page=profile"
                  class="form" id="password-form" novalidate>
                <input type="hidden" name="action" value="change_password">

                <div class="field">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password"
                           name="current_password" placeholder="Enter current password">
                </div>
                <div class="field-row">
                    <div class="field">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password"
                               name="new_password" placeholder="Min 8 characters">
                        <span class="js-error" id="err-newpass"></span>
                    </div>
                    <div class="field">
                        <label for="confirm_password">Confirm New</label>
                        <input type="password" id="confirm_password"
                               name="confirm_password" placeholder="Repeat new password">
                        <span class="js-error" id="err-confirm"></span>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Change Password</button>
            </form>
        </div>

    </div>
</div>

<?php require 'views/footer.php'; ?>
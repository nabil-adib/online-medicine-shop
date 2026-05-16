<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'views/header.php';
?>

<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <h2><i class="fas fa-user-circle"></i> My Profile</h2>
            <p>Manage your personal information and address</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <div class="profile-content">
            <div class="profile-avatar">
                <?php if (!empty($user['profile_picture']) && file_exists($user['profile_picture'])): ?>
                    <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile" id="avatarImg">
                <?php else: ?>
                    <div class="avatar-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                <?php endif; ?>
                <form method="POST" enctype="multipart/form-data" class="avatar-upload">
                    <input type="hidden" name="action" value="upload_picture">
                    <label class="upload-label">
                        <i class="fas fa-camera"></i>
                        <input type="file" name="profile_picture" accept="image/*" hidden onchange="this.form.submit()">
                    </label>
                </form>
            </div>
            
            <div class="profile-form">
                <h3>Personal Information</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="update_info">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Shipping Address</label>
                        <textarea name="address" rows="3" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Update Profile</button>
                </form>
                
                <hr>
                
                <h3>Change Password</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="change_password">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" placeholder="Min 8 characters" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn-primary">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'views/footer.php'; ?>
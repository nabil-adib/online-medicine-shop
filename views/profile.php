<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'views/header.php';
?>

<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <h2>My Profile</h2>
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
                    <div class="avatar-placeholder"></div>
                <?php endif; ?>
                <form method="POST" enctype="multipart/form-data" class="avatar-upload">
                    <input type="hidden" name="action" value="upload_picture">
                    <label class="upload-label">Upload Photo
                        <input type="file" name="profile_picture" accept="image/*" hidden onchange="this.form.submit()">
                    </label>
                </form>
            </div>
            
            <div class="profile-form">
                <h3>Personal Information</h3>
                <form method="POST"  id="updateProfileForm">
                    <input type="hidden" name="action" value="update_info">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" id="address" rows="3" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Update Profile</button>
                </form>
                
                <hr>
                
                <h3>Change Password</h3>
                <form method="POST" id="changePasswordForm">
                    <input type="hidden" name="action" value="change_password">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" id="current_password" required>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" id="new_password" placeholder="Min 8 characters" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" required>
                    </div>
                    <button type="submit" class="btn-primary">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // Profile form validation
    document.getElementById('updateProfileForm')?.addEventListener('submit', function(e) {
        const name = document.getElementById('name')?.value;
        
        if (name && !validateName(name)) {
            e.preventDefault();
            alert('Name cannot contain numbers. Please use only letters and spaces.');
            return false;
        }
    });

    // Password change form validation
    document.getElementById('changePasswordForm')?.addEventListener('submit', function(e) {
        const newPassword = document.getElementById('new_password')?.value;
        const confirmPassword = document.getElementById('confirm_password')?.value;
        
        if (newPassword && !validatePassword(newPassword)) {
            e.preventDefault();
            alert('Password must be at least 8 characters');
            return false;
        }
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match');
            return false;
        }
    });
</script>
<?php require 'views/footer.php'; ?>
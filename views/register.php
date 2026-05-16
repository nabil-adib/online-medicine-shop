<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PharmaQuick</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/assets/style.css">
</head>
<body class="auth-page">
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h2>Create Account</h2>
            <p>Join PharmaQuick for seamless medicine delivery</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form method="POST" class="auth-form" id="registerForm">
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Full Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($old_name); ?>" placeholder="John Doe" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Phone</label>
                    <input type="tel" name="phone" value="<?php echo htmlspecialchars($old_phone); ?>" placeholder="01712345678" required>
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($old_email); ?>" placeholder="customer@example.com" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" placeholder="Min 8 characters" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Repeat password" required>
            </div>
            <div class="form-group">
                <label><i class="fas fa-map-marker-alt"></i> Shipping Address</label>
                <textarea name="address" rows="3" placeholder="Your delivery address" required><?php echo htmlspecialchars($old_address); ?></textarea>
            </div>
            <div class="form-group">
                <label><i class="fas fa-user-tag"></i> Register As</label>
                <select name="role" required>
                    <option value="customer" <?php echo $old_role === 'customer' ? 'selected' : ''; ?>>Customer</option>
                    <option value="vendor" <?php echo $old_role === 'vendor' ? 'selected' : ''; ?>>Vendor</option>
                </select>
            </div>
            <button type="submit" class="auth-btn">Create Account</button>
        </form>
        <div class="auth-footer">
            Already have an account? <a href="index.php?page=login">Sign In</a>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm')?.addEventListener('submit', function(e) {
    const name = this.querySelector('[name="name"]').value;
    const email = this.querySelector('[name="email"]').value;
    const phone = this.querySelector('[name="phone"]').value;
    const password = this.querySelector('[name="password"]').value;
    const confirm = this.querySelector('[name="confirm_password"]').value;
    
    if (!name || !email || !phone || !password || !confirm) {
        e.preventDefault();
        alert('Please fill in all fields');
        return false;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email address');
        return false;
    }
    
    const phoneRegex = /^\d{7,15}$/;
    if (!phoneRegex.test(phone)) {
        e.preventDefault();
        alert('Phone number must be 7-15 digits');
        return false;
    }
    
    if (password.length < 8) {
        e.preventDefault();
        alert('Password must be at least 8 characters');
        return false;
    }
    
    if (password !== confirm) {
        e.preventDefault();
        alert('Passwords do not match');
        return false;
    }
});
</script>
</body>
</html>
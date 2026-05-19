<?php 
// Start session if not already active
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Document metadata and configuration -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PharmaQuick</title>
    
    <!-- External CSS stylesheet -->
    <link rel="stylesheet" href="public/assets/style.css">
    <script src="public/js/ajax.js"></script>
</head>
<body class="auth-page">

<!-- Authentication Container - Wrapper for registration form -->
<div class="auth-container">
    <div class="auth-card">
        
        <!-- Header Section with Logo and Welcome Message -->
        <div class="auth-header">
            <div class="auth-icon">
                <img src="public/assets/pictures/logo.png" alt="PharmaQuick Logo">
            </div>
            <h2>Create Account</h2>
            <p>Join PharmaQuick for seamless medicine delivery</p>
        </div>
        
        <!-- Display Error Message (if exists) -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <!-- Display Success Message (if exists) -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <!-- Registration Form Section -->
        <form method="POST" class="auth-form" id="registerForm">
            
            <!-- Row 1: Full Name and Phone Number (Two Columns) -->
            <div class="form-row">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($old_name); ?>" placeholder="John Doe" required>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($old_phone); ?>" placeholder="01712345678" required>
                </div>
            </div>
            
            <!-- Email Address Field -->
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($old_email); ?>" placeholder="customer@example.com" required>
            </div>
            
            <!-- Password Field -->
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" id="password" placeholder="Min 8 characters" required>
            </div>
            
            <!-- Confirm Password Field -->
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Repeat password" required>
            </div>
            
            <!-- Address Textarea Field -->
            <div class="form-group">
                <label>Address</label>
                <textarea name="address" id="address" rows="3" placeholder="Address" required><?php echo htmlspecialchars($old_address); ?></textarea>
            </div>
            
            <!-- Role Selection Dropdown (Customer or Vendor) -->
            <div class="form-group">
                <label>Register As</label>
                <select name="role" id="role" required>
                    <option value="customer" <?php echo $old_role === 'customer' ? 'selected' : ''; ?>>Customer</option>
                    <option value="vendor" <?php echo $old_role === 'vendor' ? 'selected' : ''; ?>>Vendor (Pharmacy Owner)</option>
                </select>
            </div>
            
            <!-- Register Submit Button -->
            <button type="submit" class="auth-btn">Create Account</button>
        </form>
        
        <!-- Login Link for Existing Users -->
        <div class="auth-footer">
            Already have an account? <a href="index.php?page=login">Sign In</a>
        </div>
    </div>
</div>

<!-- Client-side Form Validation Script -->
<script>
// Add submit event listener to validate all fields before submission
document.getElementById('registerForm')?.addEventListener('submit', function(e) {
    // Get all form field values
    const name = document.getElementById('name')?.value;
    const email = document.getElementById('email')?.value;
    const phone = document.getElementById('phone')?.value;
    const password = document.getElementById('password')?.value;
    const confirm = document.getElementById('confirm_password')?.value;
    
    // Check if all required fields are filled
    if (!name || !email || !phone || !password || !confirm) {
        e.preventDefault();  // Prevent form submission
        alert('Please fill in all fields');
        return false;
    }
    
    // Validate name (no numbers allowed)
    if (!validateName(name)) {
        e.preventDefault();
        alert('Name cannot contain numbers. Please use only letters and spaces.');
        return false;
    }
    
    // Validate email format
    if (!validateEmail(email)) {
        e.preventDefault();
        alert('Please enter a valid email address');
        return false;
    }
    
    // Validate phone number (exactly 11 digits)
    if (!validatePhone(phone)) {
        e.preventDefault();
        alert('Phone number must be exactly 11 digits');
        return false;
    }
    
    // Validate password length (minimum 8 characters)
    if (!validatePassword(password)) {
        e.preventDefault();
        alert('Password must be at least 8 characters');
        return false;
    }
    
    // Check if password and confirmation match
    if (password !== confirm) {
        e.preventDefault();
        alert('Passwords do not match');
        return false;
    }
});
</script>

</body>
</html>
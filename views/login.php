<?php
// Get remembered email from cookie if "Remember Me" was checked previously
$prefill_email = $_COOKIE['remember_user'] ?? '';

// Get registration success message from session (if user just registered)
$success_message = $_SESSION['registration_success'] ?? '';

// Clear the session message after reading to prevent showing again on refresh
unset($_SESSION['registration_success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Document metadata and configuration -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PharmaQuick</title>
    
    <!-- External CSS stylesheet -->
    <link rel="stylesheet" href="public/assets/style.css">
    <script src="public/js/ajax.js"></script>
</head>
<body class="auth-page">

<!-- Authentication Container - Wrapper for login form -->
<div class="auth-container">
    <div class="auth-card">
        
        <!-- Header Section with Logo and Welcome Message -->
        <div class="auth-header">
            <div class="auth-icon">
                <img src="public/assets/pictures/logo.png" alt="PharmaQuick Logo">
            </div>
            <h2>Welcome Back</h2>
            <p>Access your health dashboard</p>
        </div>
        
        <!-- Display Registration Success Message (if exists) -->
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <!-- Display Login Error Message (if exists) -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <!-- Login Form Section -->
        <form method="POST" class="auth-form" id="loginForm">
            
            <!-- Email Input Field -->
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="you@example.com" 
                       value="<?php echo htmlspecialchars($prefill_email); ?>" 
                       required autofocus>
            </div>
            
            <!-- Password Input Field -->
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••" required>
            </div>
            
            <!-- Remember Me Checkbox Section -->
            <div class="form-group" style="flex-direction: row; align-items: center; justify-content: flex-start; gap: 0.5rem; margin-top: 1rem;">
                <input type="checkbox" name="remember" id="remember" style="width: auto; margin: 0;"
                    <?php echo !empty($prefill_email) ? 'checked' : ''; ?>>
                <label for="remember" style="margin: 0; text-transform: none; font-size: 0.875rem; cursor: pointer;">Remember Me</label>
            </div>
            
            <!-- Login Submit Button -->
            <button type="submit" class="auth-btn">Login</button>
        </form>
        
        <!-- Registration Link for New Users -->
        <div class="auth-footer">
            Don't have an account? <a href="index.php?page=register">Create Account</a>
        </div>
    </div>
</div>

<!-- Client-side Form Validation Script -->
<script>
// Add submit event listener to validate form before submission
document.getElementById('loginForm')?.addEventListener('submit', function(e) {
    // Get email and password field values
    const email = this.querySelector('[name="email"]').value;
    const password = this.querySelector('[name="password"]').value;
    
    // Check if both fields are filled
    if (!email || !password) {
        e.preventDefault();  // Prevent form submission
        alert('Please fill in all fields');
        return false;
    }
});
</script>

</body>
</html>
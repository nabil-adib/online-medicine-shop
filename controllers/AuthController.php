<?php

// Registration controller - handles new user signup
function register_ctrl($conn) {
    $error = '';
    $success = '';
    
    // Preserve form data for repopulation on error
    $old_name = '';
    $old_email = '';
    $old_address = '';
    $old_phone = '';
    $old_role = 'customer';
    
    // Process form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_name = trim($_POST['name'] ?? '');
        $user_email = trim($_POST['email'] ?? '');
        $user_password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        $user_address = trim($_POST['address'] ?? '');
        $user_phone = trim($_POST['phone'] ?? '');
        $user_role = trim($_POST['role'] ?? 'customer');
        
        // Store for form repopulation
        $old_name = $user_name;
        $old_email = $user_email;
        $old_address = $user_address;
        $old_phone = $user_phone;
        $old_role = $user_role;
        
        // Validation checks
        if ($user_name === '' || $user_email === '' || $user_password === '' ||
            $user_address === '' || $user_phone === '') {
            $error = 'All fields are required.';
        } elseif (!validate_name($user_name)) {
            $error = 'Name cannot contain numbers. Please use only letters and spaces.';
        } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } elseif (strlen($user_password) < 8) {
            $error = 'Password must be at least 8 characters.';
        } elseif ($user_password !== $confirm) {
            $error = 'Passwords do not match.';
        } elseif (!preg_match('/^\d{11}$/', $user_phone)) {
            $error = 'Phone number must be exactly 11 digits.';
        } elseif (email_exists($conn, $user_email)) {
            $error = 'This email is already registered.';
        } else {
            // Hash password and create user
            $password_hash = password_hash($user_password, PASSWORD_DEFAULT);
            $ok = create_user(
                $conn,
                htmlspecialchars($user_name),  // Prevent XSS attacks
                $user_email,
                $password_hash,
                htmlspecialchars($user_address),
                $user_phone,
                $user_role
            );
            
            if ($ok) {
                $_SESSION['registration_success'] = "Account created successfully! Please login with your credentials.";
                header('Location: index.php?page=login');
                exit;
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
    
    require 'views/register.php';
}

// Login controller - authenticates user and starts session
function login_ctrl($conn) {
    $error = '';
    
    // Pre-fill email if "Remember Me" cookie exists
    $prefill_email = $_COOKIE['remember_user'] ?? '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_email = trim($_POST['email'] ?? '');
        $user_password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);  // Check if remember me is checked
        
        if ($user_email === '' || $user_password === '') {
            $error = 'Please fill in both fields.';
        } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {
            $user = get_user_by_email($conn, $user_email);
            // Verify password against stored hash
            if ($user && password_verify($user_password, $user['password_hash'])) {
                // Set session variables for logged-in user
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_phone'] = $user['phone'];
                $_SESSION['user_address'] = $user['address'];
                $_SESSION['profile_picture'] = $user['profile_picture'];
                
                // Set persistent cookie for 30 days if "Remember Me" is enabled
                if ($remember) {
                    setcookie('remember_user', $user_email, time() + 86400 * 30, '/');
                } else {
                    setcookie('remember_user', '', time() - 3600, '/');  // Delete cookie
                }
                
                // Redirect based on user role
                switch ($user['role']) {
                    case 'admin':
                        header('Location: index.php?page=admin&action=dashboard');
                        break;
                    case 'vendor':
                        header('Location: index.php?page=vendor_home'); 
                        break;
                    case 'customer':
                    default:
                        header('Location: index.php?page=home');
                        break;
                }
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        }
    }
    
    // Pass prefilled email to login view for form autofill
    require 'views/login.php';
}

// Logout controller - destroys session and clears remember me cookie
function logout_ctrl($conn) {
    $_SESSION = [];  // Clear all session variables
    session_destroy();  // Destroy the session
    setcookie('remember_user', '', time() - 3600, '/');  // Remove remember me cookie
    header('Location: index.php?page=login');
    exit;
}

// Authentication middleware - ensures user is logged in
function require_login()
{
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header("Location: index.php?page=login");
        exit();
    }
}

// Admin authorization middleware - requires login + admin role
function require_admin()
{
    require_login();
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        header("Location: index.php?page=home");
        exit();
    }
}

// Customer authorization middleware - requires login + customer role
function require_customer()
{
    require_login();
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'customer') {
        header("Location: index.php?page=admin/dashboard");
        exit();
    }
}
?>
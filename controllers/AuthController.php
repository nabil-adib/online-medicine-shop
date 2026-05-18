<?php
function register_ctrl($conn) {
    $error = '';
    $success = '';
    
    $old_name = '';
    $old_email = '';
    $old_address = '';
    $old_phone = '';
    $old_role = 'customer';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_name = trim($_POST['name'] ?? '');
        $user_email = trim($_POST['email'] ?? '');
        $user_password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        $user_address = trim($_POST['address'] ?? '');
        $user_phone = trim($_POST['phone'] ?? '');
        $user_role = trim($_POST['role'] ?? 'customer');
        
        $old_name = $user_name;
        $old_email = $user_email;
        $old_address = $user_address;
        $old_phone = $user_phone;
        $old_role = $user_role;
        
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
        } elseif (!preg_match('/^\d{7,15}$/', $user_phone)) {
            $error = 'Phone must be 7-15 digits.';
        } elseif (email_exists($conn, $user_email)) {
            $error = 'This email is already registered.';
        } else {
            $password_hash = password_hash($user_password, PASSWORD_DEFAULT);
            $ok = create_user(
                $conn,
                htmlspecialchars($user_name),
                $user_email,
                $password_hash,
                htmlspecialchars($user_address),
                $user_phone,
                $user_role
            );
            
            if ($ok) {
                $_SESSION['registration_success'] = "Account created successfully! Please login.";
                header('Location: index.php?page=login');
                exit;
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    }
    
    require 'views/register.php';
}

function login_ctrl($conn) {
    $error = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_email = trim($_POST['email'] ?? '');
        $user_password = $_POST['password'] ?? '';
        
        if ($user_email === '' || $user_password === '') {
            $error = 'Please fill in both fields.';
        } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {
            $user = get_user_by_email($conn, $user_email);
            if ($user && password_verify($user_password, $user['password_hash'])) {
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_phone'] = $user['phone'];
                $_SESSION['user_address'] = $user['address'];
                $_SESSION['profile_picture'] = $user['profile_picture'];
                
                // Role-based redirect using index.php routing
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
    
    require 'views/login.php';
}

function logout_ctrl($conn) {
    $_SESSION = [];
    session_destroy();
    header('Location: index.php?page=login');
    exit;
}
function require_login()

{

    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {

        header("Location: index.php?page=login");

        exit();

    }

}



function require_admin()

{

    require_login();



    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {

        header("Location: index.php?page=home");

        exit();

    }

}



function require_customer()

{

    require_login();



    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'customer') {

        header("Location: index.php?page=admin/dashboard");

        exit();

    }

}
?>
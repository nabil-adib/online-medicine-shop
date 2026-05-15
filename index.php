<?php
session_start();

require 'config/db.php';
require 'models/User.php';
require 'models/Medicine.php';
require 'controllers/AuthController.php';
require 'controllers/HomeController.php';
require 'controllers/MedicineController.php';
require 'controllers/ProfileController.php';

// Auto-login via remember_token cookie
if (!isset($_SESSION['logged_in']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $user  = get_user_by_token($conn, $token);
    if ($user) {
        $_SESSION['logged_in']       = true;
        $_SESSION['user_id']         = $user['id'];
        $_SESSION['user_name']       = $user['name'];
        $_SESSION['user_email']      = $user['email'];
        $_SESSION['user_role']       = $user['role'];
        $_SESSION['user_phone']      = $user['phone'];
        $_SESSION['user_address']    = $user['address'];
        $_SESSION['profile_picture'] = $user['profile_picture'];
    }
}

$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'register':
        register_ctrl($conn);
        break;
    case 'login':
        login_ctrl($conn);
        break;
    case 'logout':
        logout_ctrl();
        break;
    case 'profile':
        profile_ctrl($conn);
        break;
    case 'browse':
        browse_ctrl($conn);
        break;
    case 'home':
    default:
        home_ctrl($conn);
        break;
}
?>
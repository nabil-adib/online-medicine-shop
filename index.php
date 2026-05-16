<?php
session_start();

require 'config/db.php';
require 'models/User.php';
require 'models/Medicine.php';
require 'controllers/AuthController.php';
require 'controllers/HomeController.php';
require 'controllers/ProfileController.php';

$page = $_GET['page'] ?? 'home';

// Public pages that don't require login
$public_pages = ['login', 'register'];

if (!isset($_SESSION['logged_in']) && !in_array($page, $public_pages)) {
    header('Location: index.php?page=login');
    exit;
}

switch ($page) {
    case 'register':
        register_ctrl($conn);
        break;
    case 'login':
        login_ctrl($conn);
        break;
    case 'logout':
        logout_ctrl($conn);
        break;
    case 'profile':
        profile_ctrl($conn);
        break;
    case 'home':
        home_ctrl($conn);
        break;
    default:
        home_ctrl($conn);
        break;
}
?>
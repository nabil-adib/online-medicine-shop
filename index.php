<?php
session_start();

require 'config/db.php';
require 'models/User.php';
require 'models/Medicine.php';
require 'models/Order.php';
require 'models/Category.php';
require 'models/Customer.php';
require 'controllers/AuthController.php';
require 'controllers/HomeController.php';
require 'controllers/ProfileController.php';
require 'controllers/adminController.php';
require 'controllers/OrderController.php';
require 'controllers/AjaxController.php';
require 'controllers/CartApiController.php';
require 'controllers/VendorController.php';  // ADD THIS LINE

$page = $_GET['page'] ?? 'home';

// Handle AJAX requests
if ($page === 'ajax') {
    handleAjaxRequest($conn);
    exit;
}

// Handle Cart API requests
if ($page === 'api_cart') {
    $action = $_GET['action'] ?? '';
    handleCartApi($conn, $action);
    exit;
}

// Public pages that don't require login
$public_pages = ['login', 'register'];

if (!isset($_SESSION['logged_in']) && !in_array($page, $public_pages)) {
    header('Location: index.php?page=login');
    exit;
}

// Page routing
switch ($page) {
    // Auth pages
    case 'register':
        register_ctrl($conn);
        break;
    case 'login':
        login_ctrl($conn);
        break;
    case 'logout':
        logout_ctrl($conn);
        break;
    
    // Customer pages
    case 'profile':
        profile_ctrl($conn);
        break;
    case 'cart':
        cart_ctrl($conn);
        break;
    case 'checkout':
        checkoutCtrl($conn);
        break;
    case 'orders':
        customer_orders_ctrl($conn);
        break;
    case 'home':
        home_ctrl($conn);
        break;
    
    // Admin pages
    case 'admin':
        admin_ctrl($conn);
        break;
    
    // Vendor pages - Using VendorController functions
    case 'vendor_home':
        vendor_home_ctrl($conn);
        break;
        
    case 'vendor_cart':
        vendor_cart_ctrl($conn);
        break;
        
    case 'vendor_checkout':
        vendor_checkout_ctrl($conn);
        break;
        
    case 'vendor_invoice':
        vendor_invoice_ctrl($conn);
        break;
        
    case 'vendor_orders':
        vendor_orders_ctrl($conn);
        break;
        
    case 'vendor_payment':
        vendor_payment_ctrl($conn);
        break;
    
    default:
        login_ctrl($conn);
        break;
}
?>
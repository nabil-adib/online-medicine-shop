<?php
// Start session to maintain user login state
session_start();

// ==================== INCLUDE REQUIRED FILES ====================
// Database configuration
require 'config/db.php';

// Model files (database interactions)
require 'models/User.php';
require 'models/Medicine.php';
require 'models/Order.php';
require 'models/Category.php';
require 'models/Customer.php';

// Controller files (business logic)
require 'controllers/AuthController.php';
require 'controllers/HomeController.php';
require 'controllers/ProfileController.php';
require 'controllers/adminController.php';
require 'controllers/OrderController.php';
require 'controllers/AjaxController.php';
require 'controllers/CartApiController.php';
require 'controllers/VendorController.php';

// Get the requested page from URL parameter (default to 'home')
$page = $_GET['page'] ?? 'home';

// ==================== HANDLE AJAX REQUESTS ====================
// Check if this is an AJAX request
if ($page === 'ajax') {
    handleAjaxRequest($conn);
    exit;  // Stop further execution
}

// ==================== HANDLE CART API REQUESTS ====================
// Check if this is a Cart API request
if ($page === 'api_cart') {
    $action = $_GET['action'] ?? '';
    handleCartApi($conn, $action);
    exit;  // Stop further execution
}

// ==================== PUBLIC PAGES DEFINITION ====================
// Pages that can be accessed without logging in
$public_pages = ['login', 'register'];

// ==================== AUTHENTICATION CHECK ====================
// Redirect to login if user is not logged in and trying to access restricted page
if (!isset($_SESSION['logged_in']) && !in_array($page, $public_pages)) {
    header('Location: index.php?page=login');
    exit;
}

// ==================== PAGE ROUTING ====================
// Route requests to appropriate controller functions based on page parameter
switch ($page) {
    
    // ========== AUTHENTICATION PAGES ==========
    case 'register':
        register_ctrl($conn);  // Handle user registration
        break;
        
    case 'login':
        login_ctrl($conn);     // Handle user login
        break;
        
    case 'logout':
        logout_ctrl($conn);    // Handle user logout
        break;
    
    // ========== CUSTOMER PAGES ==========
    case 'profile':
        profile_ctrl($conn);           // View/edit user profile
        break;
        
    case 'cart':
        cart_ctrl($conn);              // View shopping cart
        break;
        
    case 'checkout':
        checkoutCtrl($conn);           // Process order checkout
        break;
        
    case 'orders':
        customer_orders_ctrl($conn);   // View customer order history
        break;
        
    case 'home':
        home_ctrl($conn);              // Display homepage with medicines
        break;
    
    // ========== ADMIN PAGES ==========
    case 'admin':
        admin_ctrl($conn);             // Admin dashboard and management
        break;
    
    // ========== VENDOR PAGES ==========
    case 'vendor_home':
        vendor_home_ctrl($conn);       // Vendor dashboard
        break;
        
    case 'vendor_medicines':
        vendor_medicines_ctrl($conn);  // Vendor medicine management
        break;
    
    // ========== DEFAULT ROUTE ==========
    default:
        login_ctrl($conn);             // Redirect to login for unknown pages
        break;
}
?>
<?php

// Load all required model files for database operations
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Customer.php';
require_once __DIR__ . '/../models/Medicine.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Vendor.php';

// Main admin controller function - handles all admin panel actions
function admin_ctrl($conn)
{
    // Verify admin authentication - redirect if not logged in as admin
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: index.php?page=login');
        exit;
    }

    // Determine which action to perform based on URL parameter
    $action = $_GET['action'] ?? 'dashboard';

    // Route to appropriate handler based on action
    switch ($action) {

        // DASHBOARD - Display admin home with statistics
        case 'dashboard':
            $categoriesCount = get_total_categories($conn);
            $medicinesCount  = get_total_medicines($conn);
            $customersCount  = get_total_customers($conn);
            $ordersCount     = count(get_pending_orders($conn));
            $recentOrders = get_pending_orders($conn);
            require 'views/admin/dashboard.php';
            break;

        // CATEGORIES - Show all product categories
        case 'categories':
            $error = '';
            $categories = get_all_categories($conn);
            $editing = null;
            require 'views/admin/categories.php';
            break;

        // ADD CATEGORY - Process new category creation
        case 'add_category':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $error = '';
                $name = trim($_POST['name'] ?? '');
                $type = trim($_POST['category_type'] ?? '');
                
                // Validate input fields
                if ($name === '' || $type === '') {
                    $error = 'Please fill in both fields.';
                } elseif (strlen($name) < 3) {
                    $error = 'Category name must be at least 3 characters.';
                } else {
                    add_category($conn, $name, $type);
                    header('Location: index.php?page=admin&action=categories&msg=added');
                    exit;
                }
                
                $categories = get_all_categories($conn);
                require 'views/admin/categories.php';
            }
            break;

        // EDIT CATEGORY - Load category data into edit form
        case 'edit_category':
            if (isset($_GET['id'])) {
                $error = '';
                $id = $_GET['id'];
                $editing = get_category_by_id($conn, $id);
                $categories = get_all_categories($conn);
                require 'views/admin/categories.php';
            }
            break;

        // UPDATE CATEGORY - Save modified category data
        case 'update_category':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $error = '';
                $id = $_GET['id'];
                $name = trim($_POST['name'] ?? '');
                $type = trim($_POST['category_type'] ?? '');
                
                if ($name === '' || $type === '') {
                    $error = 'Please fill in both fields.';
                } elseif (strlen($name) < 3) {
                    $error = 'Category name must be at least 3 characters.';
                } else {
                    update_category($conn, $id, $name, $type);
                    header('Location: index.php?page=admin&action=categories&msg=updated');
                    exit;
                }
                
                $editing = ['id' => $id, 'name' => $name, 'category_type' => $type];
                $categories = get_all_categories($conn);
                require 'views/admin/categories.php';
            }
            break;

        // DELETE CATEGORY - Remove category only if no medicines depend on it
        case 'delete_category':
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                // Prevent deletion if category contains medicines (foreign key constraint)
                if (check_medicines_in_category($conn, $id)) {
                    header('Location: index.php?page=admin&action=categories&msg=blocked');
                    exit;
                }
                delete_category($conn, $id);
            }
            header('Location: index.php?page=admin&action=categories&msg=deleted');
            exit;

        // MEDICINES - Display all medicines with category filter
        case 'medicines':
            $error = '';
            $medicines = get_all_medicines($conn);
            $categories = get_all_categories($conn);
            $editing = null;
            require 'views/admin/medicines.php';
            break;

        // ADD MEDICINE - Process new medicine creation with optional image upload
        case 'add_medicine':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $error = '';
                $name = trim($_POST['name'] ?? '');
                $vendor_name = trim($_POST['vendor_name'] ?? '');
                $price = trim($_POST['price'] ?? '');
                $availability = trim($_POST['availability'] ?? '');
                $category_id = $_POST['category_id'] ?? '';
                $description = trim($_POST['description'] ?? '');
                
                // Validate required fields and numeric values
                if ($name === '' || $vendor_name === '' || $price === '' || $availability === '' || $category_id === '') {
                    $error = 'Please fill in all required fields.';
                } elseif ($price <= 0) {
                    $error = 'Price must be greater than 0.';
                } elseif ($availability <= 0) {
                    $error = 'Availability must be greater than 0.';
                } else {
                    // Handle optional image upload with timestamp to avoid filename conflicts
                    $imagePath = '';
                    if (!empty($_FILES['image_path']['name'])) {
                        $imagePath = 'public/uploads/' . time() . '_' . $_FILES['image_path']['name'];
                        move_uploaded_file($_FILES['image_path']['tmp_name'], $imagePath);
                    }
                    
                    $data = [
                        'name' => $name,
                        'category_id' => $category_id,
                        'vendor_name' => $vendor_name,
                        'price' => $price,
                        'availability' => $availability,
                        'description' => $description,
                        'image_path' => $imagePath
                    ];
                    
                    add_medicine($conn, $data);
                    header('Location: index.php?page=admin&action=medicines&msg=added');
                    exit;
                }
                
                $medicines = get_all_medicines($conn);
                $categories = get_all_categories($conn);
                require 'views/admin/medicines.php';
            }
            break;

        // EDIT MEDICINE - Load medicine data into edit form
        case 'edit_medicine':
            if (isset($_GET['id'])) {
                $error = '';
                $id = $_GET['id'];
                $editing = get_medicine_by_id($conn, $id);
                $categories = get_all_categories($conn);
                $medicines = get_all_medicines($conn);
                require 'views/admin/medicines.php';
            }
            break;

        // UPDATE MEDICINE - Save modified medicine data
        case 'update_medicine':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $error = '';
                $id = $_GET['id'];
                $name = trim($_POST['name'] ?? '');
                $vendor_name = trim($_POST['vendor_name'] ?? '');
                $price = trim($_POST['price'] ?? '');
                $availability = trim($_POST['availability'] ?? '');
                $category_id = $_POST['category_id'] ?? '';
                $description = trim($_POST['description'] ?? '');
                
                if ($name === '' || $vendor_name === '' || $price === '' || $availability === '' || $category_id === '') {
                    $error = 'Please fill in all required fields.';
                } elseif ($price <= 0) {
                    $error = 'Price must be greater than 0.';
                } elseif ($availability <= 0) {
                    $error = 'Availability must be greater than 0.';
                } else {
                    // Preserve old image or upload new one
                    $imagePath = $_POST['old_image'] ?? '';
                    if (!empty($_FILES['image_path']['name'])) {
                        $imagePath = 'public/uploads/' . time() . '_' . $_FILES['image_path']['name'];
                        move_uploaded_file($_FILES['image_path']['tmp_name'], $imagePath);
                    }
                    
                    $data = [
                        'name' => $name,
                        'category_id' => $category_id,
                        'vendor_name' => $vendor_name,
                        'price' => $price,
                        'availability' => $availability,
                        'description' => $description,
                        'image_path' => $imagePath
                    ];
                    
                    update_medicine($conn, $id, $data);
                    header('Location: index.php?page=admin&action=medicines&msg=updated');
                    exit;
                }
                
                $editing = [
                    'id' => $id,
                    'name' => $name,
                    'vendor_name' => $vendor_name,
                    'price' => $price,
                    'availability' => $availability,
                    'category_id' => $category_id,
                    'description' => $description,
                    'image_path' => $_POST['old_image'] ?? ''
                ];
                $categories = get_all_categories($conn);
                $medicines = get_all_medicines($conn);
                require 'views/admin/medicines.php';
            }
            break;

        // DELETE MEDICINE - Remove medicine if not referenced in orders/carts
        case 'delete_medicine':
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                // Check if medicine is used in any pending order or cart before deletion
                if (can_delete_medicine($conn, $id)) {
                    delete_medicine($conn, $id);
                    header('Location: index.php?page=admin&action=medicines&msg=deleted');
                } else {
                    $error = "Cannot delete this medicine. It is in customer carts or pending orders.";
                    $medicines = get_all_medicines($conn);
                    $categories = get_all_categories($conn);
                    $editing = null;
                    require 'views/admin/medicines.php';
                }
                exit;
            }
            break;

        // CUSTOMERS - Display all registered customers
        case 'customers':
            $customers = get_all_customers($conn);
            require 'views/admin/customers.php';
            break;

        // DELETE CUSTOMER - Remove customer and all related data (cascading)
        case 'delete_customer':
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                delete_customer($conn, $id);
            }
            header('Location: index.php?page=admin&action=customers&msg=deleted');
            exit;

        // ORDERS - Display all purchase requests (pending, accepted, rejected)
        case 'orders':
            $orders = get_all_orders($conn);
            require 'views/admin/orders.php';
            break;

        // UPDATE ORDER STATUS - AJAX endpoint for approving/rejecting orders
        case 'update_order_status':
            header('Content-Type: application/json');
            $order_id = $_GET['order_id'] ?? 0;
            $status = $_GET['status'] ?? '';
            // Only allow valid status values
            if ($order_id && in_array($status, ['accepted', 'rejected'])) {
                $result = update_order_status($conn, $order_id, $status);
                echo json_encode(['success' => $result]);
            } else {
                echo json_encode(['success' => false]);
            }
            exit;
            break;

        // HISTORY - Display completed orders with full details
        case 'history':
            $orders = get_completed_orders_with_details($conn);
            require 'views/admin/history.php';
            break;
        
        // VENDORS - Display all suppliers
        case 'vendors':
            $vendors = get_all_vendors($conn);
            if ($vendors === null) {
                $vendors = [];
            }
            $error = '';
            $success = '';
            require 'views/admin/vendors.php';
            break;

        // ADD VENDOR - Process new vendor registration
        case 'add_vendor':
            require_once __DIR__ . '/../models/Vendor.php';
            $error = '';
            $old_name = '';
            $old_email = '';
            $old_address = '';
            $old_phone = '';
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = trim($_POST['name'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';
                $confirm_password = $_POST['confirm_password'] ?? '';
                $address = trim($_POST['address'] ?? '');
                $phone = trim($_POST['phone'] ?? '');
                
                $old_name = $name;
                $old_email = $email;
                $old_address = $address;
                $old_phone = $phone;
                
                // Comprehensive validation
                if ($name === '' || $email === '' || $password === '' || $address === '' || $phone === '') {
                    $error = 'All fields are required.';
                } elseif (!validate_name($name)) {
                    $error = 'Name cannot contain numbers. Please use only letters and spaces.';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Please enter a valid email address.';
                } elseif (strlen($password) < 8) {
                    $error = 'Password must be at least 8 characters.';
                } elseif ($password !== $confirm_password) {
                    $error = 'Passwords do not match.';
                } elseif (!preg_match('/^\d{7,15}$/', $phone)) {
                    $error = 'Phone must be 7-15 digits.';
                } elseif (email_exists($conn, $email)) { 
                    $error = 'This email is already registered. Please use a different email address.';
                } else {
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $ok = add_vendor($conn, htmlspecialchars($name), $email, $password_hash, htmlspecialchars($address), $phone);
                    
                    if ($ok) {
                        header('Location: index.php?page=admin&action=vendors&msg=added');
                        exit;
                    } else {
                        $error = 'Failed to add vendor. Please try again.';
                    }
                }
            }
            
            $vendors = get_all_vendors($conn);
            require 'views/admin/vendors.php';
            break;

        // EDIT VENDOR - Load vendor data into edit form
        case 'edit_vendor':            
            if (isset($_GET['id'])) {
                $error = '';
                $id = $_GET['id'];
                $editing = get_vendor_by_id($conn, $id);
                
                if (!$editing) {
                    header('Location: index.php?page=admin&action=vendors&msg=notfound');
                    exit;
                }
                $vendors = get_all_vendors($conn);
                    if ($vendors === null) {
                        $vendors = [];
                }

                require 'views/admin/vendors.php';
            }
            break;
        
            // UPDATE VENDOR - Save modified vendor information
        case 'update_vendor':            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $error = '';
                $id = $_GET['id'];
                $name = trim($_POST['name'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $address = trim($_POST['address'] ?? '');
                $phone = trim($_POST['phone'] ?? '');
                
                // Get the current vendor data
                $current_vendor = get_vendor_by_id($conn, $id);
                
                if ($name === '' || $email === '' || $address === '' || $phone === '') {
                    $error = 'All fields are required.';
                } elseif (!validate_name($name)) {
                    $error = 'Name cannot contain numbers. Please use only letters and spaces.';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Please enter a valid email address.';
                } elseif (!preg_match('/^\d{7,15}$/', $phone)) {
                    $error = 'Phone must be 7-15 digits.';
                } elseif ($email !== $current_vendor['email'] && email_exists($conn, $email)) { 
                    // Only check if email has actually changed
                    $error = 'This email is already registered. Please use a different email address.';
                } else {
                    $ok = update_vendor($conn, $id, htmlspecialchars($name), $email, htmlspecialchars($address), $phone);
                    
                    if ($ok) {
                        header('Location: index.php?page=admin&action=vendors&msg=updated');
                        exit;
                    } else {
                        $error = 'Failed to update vendor. Please try again.';
                    }
                }
                
                $editing = ['id' => $id, 'name' => $name, 'email' => $email, 'address' => $address, 'phone' => $phone];
                $vendors = get_all_vendors($conn);
                if ($vendors === null) {
                    $vendors = [];
                }
                require 'views/admin/vendors.php';
            }
            break;

        // DELETE VENDOR - Remove vendor from system
        case 'delete_vendor':
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $deleted = delete_vendor($conn, $id);
                if ($deleted) {
                    header('Location: index.php?page=admin&action=vendors&msg=deleted');
                } else {
                    // Vendor has associated medicines - cannot delete
                    header('Location: index.php?page=admin&action=vendors&msg=blocked');
                }
                exit;
            }
            header('Location: index.php?page=admin&action=vendors');
            exit;
        
        // DEFAULT - Redirect to dashboard if action not recognized
        default:
            header('Location: index.php?page=admin');
            exit;
    }
}

?>
<?php

require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Customer.php';
require_once __DIR__ . '/../models/Medicine.php';
require_once __DIR__ . '/../models/Order.php';


// =========================
// ADMIN MAIN CONTROLLER
// =========================

function admin_ctrl($conn)
{
    // admin protection
     // admin protection
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: index.php?page=login');
        exit;
    }

    // which admin page
    $action = $_GET['action'] ?? 'dashboard';

    switch ($action) {


        // =========================
        // DASHBOARD
        // =========================
        case 'dashboard':

            $categoriesCount = get_total_categories($conn);
            $medicinesCount  = get_total_medicines($conn);
            $customersCount  = get_total_customers($conn);
            $ordersCount     = count(get_all_orders($conn));

            $recentOrders = get_pending_orders($conn);

            require 'views/admin/dashboard.php';
            break;


        // =========================
        // CATEGORIES PAGE
        // =========================
        case 'categories':
            $error = '';
            $categories = get_all_categories($conn);
            $editing = null;
            require 'views/admin/categories.php';
            break;

        // =========================
        // ADD CATEGORY
        // =========================
        case 'add_category':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $error = '';
                $name = trim($_POST['name'] ?? '');
                $type = trim($_POST['category_type'] ?? '');
                
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

        // =========================
        // EDIT CATEGORY
        // =========================
        case 'edit_category':
            if (isset($_GET['id'])) {
                $error = '';
                $id = $_GET['id'];
                $editing = get_category_by_id($conn, $id);
                $categories = get_all_categories($conn);
                require 'views/admin/categories.php';
            }
            break;

        // =========================
        // UPDATE CATEGORY
        // =========================
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

        // =========================
        // DELETE CATEGORY
        // =========================
        case 'delete_category':

            if (isset($_GET['id'])) {

                $id = $_GET['id'];

                // block delete if medicines exist
                if (check_medicines_in_category($conn, $id)) {

                    header('Location: index.php?page=admin&action=categories&msg=blocked');
                    exit;
                }

                delete_category($conn, $id);
            }

            header('Location: index.php?page=admin&action=categories&msg=deleted');
            exit;


        // =========================
        // MEDICINES PAGE
        // =========================
        case 'medicines':
            $error = '';
            $medicines = get_all_medicines($conn);
            $categories = get_all_categories($conn);
            $editing = null;
            require 'views/admin/medicines.php';
            break;

        // =========================
        // ADD MEDICINE
        // =========================
        case 'add_medicine':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $error = '';
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
                    // Image upload (optional)
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

        // =========================
        // EDIT MEDICINE
        // =========================
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

        // =========================
        // UPDATE MEDICINE
        // =========================
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


        // =========================
        // DELETE MEDICINE
        // =========================
        case 'delete_medicine':

            if (isset($_GET['id'])) {

                delete_medicine($conn, $_GET['id']);
            }

            header('Location: index.php?page=admin&action=medicines&msg=deleted');
            exit;

        // =========================
        // CUSTOMERS
        // =========================
        case 'customers':

            $customers = get_all_customers($conn);

            require 'views/admin/customers.php';
            break;


        // =========================
        // DELETE CUSTOMER
        // =========================
        case 'delete_customer':

            if (isset($_GET['id'])) {

                $id = $_GET['id'];

                // delete_customer() already handles all related data
                delete_customer($conn, $id);
            }

            header('Location: index.php?page=admin&action=customers&msg=deleted');
            exit;


        // =========================
        // ORDERS (ALL PURCHASE REQUESTS)
        // =========================
        case 'orders':
            $orders = get_all_orders($conn);
            require 'views/admin/orders.php';
            break;

        // =========================
        // UPDATE ORDER STATUS (AJAX) - FIXED
        // =========================
        case 'update_order_status':
            header('Content-Type: application/json');
            
            $order_id = $_GET['order_id'] ?? 0;
            $status = $_GET['status'] ?? '';
            
            if ($order_id && in_array($status, ['accepted', 'rejected'])) {
                $result = update_order_status($conn, $order_id, $status);
                echo json_encode(['success' => $result]);
            } else {
                echo json_encode(['success' => false]);
            }
            exit;
            break;

            
        // =========================
        // PURCHASE HISTORY (COMPLETED ORDERS WITH DETAILS)
        // =========================
        case 'history':

            // Get all completed orders (accepted) with full details
            $orders = get_completed_orders_with_details($conn);

            require 'views/admin/history.php';
            break;

        // =========================
        // DEFAULT
        // =========================
        default:

            header('Location: index.php?page=admin');
            exit;
    }
}



?>
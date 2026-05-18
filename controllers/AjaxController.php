<?php

function handleAjaxRequest($conn) {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Unauthorized']);
        exit;
    }
    
    $ajax_type = $_GET['type'] ?? '';
    
    // Vendor medicine management
    if ($_SESSION['user_role'] === 'vendor') {
        switch ($action) {
            case 'add_medicine':
                return handle_vendor_add_medicine($conn);
            case 'edit_medicine':
                return handle_vendor_edit_medicine($conn);
            case 'delete_medicine':
                return handle_vendor_delete_medicine($conn);
        }
    }

    // Admin/General AJAX operations
    $q = trim($_GET['q'] ?? '');
    
    switch ($action ?: $_GET['type'] ?? '') {
        case 'update_order_status':
            if ($_SESSION['user_role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['success' => false, 'error' => 'Forbidden']);
                exit;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            $order_id = $input['order_id'] ?? $_POST['order_id'] ?? null;
            $status = $input['status'] ?? $_POST['status'] ?? null;
            
            if (!$order_id || !$status) {
                echo json_encode(['success' => false, 'error' => 'Missing order_id or status']);
                exit;
            }
            
            $allowed_statuses = ['accepted', 'rejected'];
            if (!in_array($status, $allowed_statuses)) {
                echo json_encode(['success' => false, 'error' => 'Invalid status value']);
                exit;
            }
            
            if (!is_numeric($order_id) || $order_id <= 0) {
                echo json_encode(['success' => false, 'error' => 'Invalid order ID']);
                exit;
            }
            
            $result = update_order_status($conn, (int)$order_id, $status);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Order status updated']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Database update failed']);
            }
            exit;
            break;
            
        case 'search_medicines':
            $medicines = search_medicines($conn, $q, $_GET['vendor'] ?? '', $_GET['genre'] ?? '');
            echo json_encode(['success' => true, 'medicines' => $medicines]);
            exit;
            break;
            
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid AJAX type']);
            exit;
    }
}
function handle_vendor_add_medicine($conn) {
    if ($_SESSION['user_role'] !== 'vendor') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }

    $name = trim($_POST['name'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $availability = intval($_POST['availability'] ?? 0);
    $vendor_name = $_SESSION['user_name'];

    if (empty($name) || $category_id <= 0 || $price <= 0 || $availability < 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
        exit;
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO medicines (name, category_id, description, price, availability, vendor_name) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sisdis", $name, $category_id, $description, $price, $availability, $vendor_name);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Medicine added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add medicine']);
    }
    exit;
}

function handle_vendor_edit_medicine($conn) {
    if ($_SESSION['user_role'] !== 'vendor') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }

    $medicine_id = intval($_POST['medicine_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $availability = intval($_POST['availability'] ?? 0);
    $vendor_name = $_SESSION['user_name'];

    if ($medicine_id <= 0 || empty($name) || $category_id <= 0 || $price <= 0 || $availability < 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
        exit;
    }

    // Verify ownership
    $check = mysqli_prepare($conn, "SELECT id FROM medicines WHERE id = ? AND vendor_name = ?");
    mysqli_stmt_bind_param($check, "is", $medicine_id, $vendor_name);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);
    
    if (mysqli_num_rows($result) === 0) {
        echo json_encode(['success' => false, 'message' => 'Medicine not found or unauthorized']);
        exit;
    }

    $stmt = mysqli_prepare($conn, "UPDATE medicines SET name = ?, category_id = ?, description = ?, price = ?, availability = ? WHERE id = ? AND vendor_name = ?");
    mysqli_stmt_bind_param($stmt, "sisdiss", $name, $category_id, $description, $price, $availability, $medicine_id, $vendor_name);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Medicine updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update medicine']);
    }
    exit;
}

function handle_vendor_delete_medicine($conn) {
    if ($_SESSION['user_role'] !== 'vendor') {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }

    $medicine_id = intval($_POST['medicine_id'] ?? 0);
    $vendor_name = $_SESSION['user_name'];

    if ($medicine_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid medicine ID']);
        exit;
    }

    // Verify ownership
    $check = mysqli_prepare($conn, "SELECT id FROM medicines WHERE id = ? AND vendor_name = ?");
    mysqli_stmt_bind_param($check, "is", $medicine_id, $vendor_name);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);
    
    if (mysqli_num_rows($result) === 0) {
        echo json_encode(['success' => false, 'message' => 'Medicine not found or unauthorized']);
        exit;
    }

    $stmt = mysqli_prepare($conn, "DELETE FROM medicines WHERE id = ? AND vendor_name = ?");
    mysqli_stmt_bind_param($stmt, "is", $medicine_id, $vendor_name);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Medicine deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete medicine']);
    }
    exit;
}
?>
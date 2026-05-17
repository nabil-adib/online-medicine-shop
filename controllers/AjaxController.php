<?php

function handleAjaxRequest($conn) {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Unauthorized']);
        exit;
    }
    
    $ajax_type = $_GET['type'] ?? '';
    $q = trim($_GET['q'] ?? '');
    
    switch ($ajax_type) {
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
?>
<?php

function handleCartApi($conn, $action) {
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
        echo json_encode(["success" => false, "message" => "Please login to use the cart.", "data" => new stdClass()]);
        exit;
    }
    
    $user_id = $_SESSION['user_id'];

    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $medicine_id = (int)($_POST['medicine_id'] ?? 0);
        $cart_quantity = (int)($_POST['quantity'] ?? 1);
        
        if ($medicine_id > 0 && $cart_quantity > 0) {
            if (add_to_cart($conn, $user_id, $medicine_id, $cart_quantity)) {
                echo json_encode(["success" => true, "message" => "Item added to cart", "data" => new stdClass()]);
                exit;
            }
        }
        echo json_encode(["success" => false, "message" => "Failed to add item.", "data" => new stdClass()]);
        exit;
    }

    if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $cart_id = (int)($_POST['cart_id'] ?? 0);
        $cart_quantity = (int)($_POST['quantity'] ?? 0);
        
        if ($cart_id > 0 && $cart_quantity > 0) {
            if (update_cart_quantity($conn, $cart_id, $user_id, $cart_quantity)) {
                echo json_encode(["success" => true, "message" => "Cart updated", "data" => new stdClass()]);
                exit;
            }
        }
        echo json_encode(["success" => false, "message" => "Invalid quantity.", "data" => new stdClass()]);
        exit;
    }

    if ($action === 'remove' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $cart_id = (int)($_POST['cart_id'] ?? 0);
        
        if ($cart_id > 0) {
            if (remove_cart_item($conn, $cart_id, $user_id)) {
                echo json_encode(["success" => true, "message" => "Item removed", "data" => new stdClass()]);
                exit;
            }
        }
        echo json_encode(["success" => false, "message" => "Failed to remove item.", "data" => new stdClass()]);
        exit;
    }
    
    echo json_encode(["success" => false, "message" => "Invalid action", "data" => new stdClass()]);
    exit;
}
?>
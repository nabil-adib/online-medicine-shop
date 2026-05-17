<?php

function cartApiCtrl($conn, $action) {
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
}

function checkoutCtrl($conn) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
        header('Location: index.php?page=login');
        exit;
    }
    
    $user_id = $_SESSION['user_id'];
    $cart_items = get_cart_items($conn, $user_id);
    $error = "";
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $shipping_address = trim($_POST['shipping_address'] ?? '');
        $payment_method = $_POST['payment_method'] ?? '';
        
        if (empty($shipping_address) || empty($payment_method)) {
            $error = "Address and Payment Method are required.";
        } elseif (empty($cart_items)) {
            $error = "Your cart is empty.";
        } else {
            $order_total = 0;
            foreach ($cart_items as $item) {
                $order_total += ($item['medicine_price'] * $item['cart_quantity']);
            }
            
            $order_id = create_order($conn, $user_id, $order_total, $shipping_address, $payment_method);
            
            if ($order_id) {
                $transaction_id = 'TXN' . time() . rand(100, 999); 
                add_payment($conn, $order_id, $order_total, $payment_method, $transaction_id);
                
                foreach ($cart_items as $item) {
                    add_order_item($conn, $order_id, $item['medicine_id'], $item['cart_quantity'], $item['medicine_price']);
                    update_medicine_stock($conn, $item['medicine_id'], $item['cart_quantity']);
                }
                
                clear_cart($conn, $user_id);
                header("Location: index.php?page=cart&order_id=" . $order_id);
                exit;
            } else {
                $error = "Failed to place order. Please try again.";
            }
        }
    }
    require 'views/checkout.php';
}

function cart_ctrl($conn) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
        header('Location: index.php?page=login');
        exit;
    }
    require 'views/cart.php';
}

function customer_orders_ctrl($conn) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
        header('Location: index.php?page=login');
        exit;
    }
    
    $user_id = $_SESSION['user_id'];
    $orders = get_customer_orders($conn, $user_id);
    
    foreach ($orders as &$order) {
        $order['items'] = get_order_items($conn, $order['id']);
    }
    
    require 'views/customer_orders.php';
}
?>
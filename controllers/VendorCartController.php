<?php

session_start();

include '../models/CartModel.php';
include '../models/OrderModel.php';

if (
    !isset($_SESSION['logged_in']) ||
    $_SESSION['user_role'] != 'vendor'
) {
    header("Location: ../views/login.php");
    exit();
}

$action = $_GET['action'] ?? '';

switch ($action) {

    case 'add':

        header('Content-Type: application/json');

        $user_id = $_SESSION['user_id'];

        $medicine_id = intval($_POST['medicine_id'] ?? 0);

        $quantity = intval($_POST['quantity'] ?? 0);


        if ($medicine_id <= 0) {

            echo json_encode([
                "success" => false,
                "message" => "Invalid medicine"
            ]);

            exit();
        }

        if ($quantity <= 0) {

            echo json_encode([
                "success" => false,
                "message" => "Quantity must be greater than 0"
            ]);

            exit();
        }

        $result = CartModel::addToCart(
            $user_id,
            $medicine_id,
            $quantity
        );

        if ($result) {

            echo json_encode([
                "success" => true,
                "message" => "Item added to cart"
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" => "Failed to add item"
            ]);
        }

        break;


    //UPDATE CART
    case 'update':

        header('Content-Type: application/json');

        $cart_id = intval($_POST['cart_id'] ?? 0);

        $quantity = intval($_POST['quantity'] ?? 0);

        if ($cart_id <= 0 || $quantity <= 0) {

            echo json_encode([
                "success" => false,
                "message" => "Invalid cart data"
            ]);

            exit();
        }

        $result = CartModel::updateCart(
            $cart_id,
            $quantity
        );

        if ($result) {

            echo json_encode([
                "success" => true,
                "message" => "Cart updated"
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" => "Update failed"
            ]);
        }

        break;


    
    //REMOVE CART ITEM
    
    case 'remove':

        header('Content-Type: application/json');

        $cart_id = intval($_POST['cart_id'] ?? 0);

        if ($cart_id <= 0) {

            echo json_encode([
                "success" => false,
                "message" => "Invalid cart item"
            ]);

            exit();
        }

        $result = CartModel::removeCartItem($cart_id);

        if ($result) {

            echo json_encode([
                "success" => true,
                "message" => "Item removed"
            ]);

        } else {

            echo json_encode([
                "success" => false,
                "message" => "Remove failed"
            ]);
        }

        break;


    
    //CHECKOUT INVOICE PAGE
    
    case 'checkout':

        include '../views/vendor_invoice.php';

        break;


    
    //FINAL ORDER + PAYMENT
    
    case 'payment':

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {

            header("Location: ../views/vendor_cart.php");
            exit();
        }

        $user_id = $_SESSION['user_id'];

        $shipping_address = trim(
            $_POST['shipping_address'] ?? ''
        );

        $payment_method = trim(
            $_POST['payment_method'] ?? ''
        );

        $total_amount = floatval(
            $_POST['total_amount'] ?? 0
        );

        
        //VALIDATION
        

        if (empty($shipping_address)) {

            die("Shipping address required");
        }

        if ($total_amount <= 0) {

            die("Invalid total amount");
        }

        $allowed_methods = [
            'Credit Card',
            'bKash',
            'Nagad',
            'Bank Transfer',
            'Cash on Delivery'
        ];

        if (!in_array($payment_method, $allowed_methods)) {

            die("Invalid payment method");
        }

        
        //GET CART ITEMS
        

        $cart_items = CartModel::getCartItems($user_id);

        if ($cart_items->num_rows <= 0) {

            die("Cart is empty");
        }

        
        //CREATE ORDER
        

        $order_id = OrderModel::createOrder(
            $user_id,
            $total_amount,
            $shipping_address,
            $payment_method
        );

        
        //CREATE ORDER ITEMS
        

        while ($item = $cart_items->fetch_assoc()) {

            OrderModel::createOrderItem(
                $order_id,
                $item['medicine_id'],
                $item['quantity'],
                $item['price']
            );
        }

        
        //CREATE PAYMENT
        

        OrderModel::createPayment(
            $order_id,
            $total_amount,
            $payment_method
        );

 
        //CLEAR CART


        CartModel::clearCart($user_id);

    
        //REDIRECT SUCCESS PAGE
        

        header("Location: ../views/vendor_orders.php");

        exit();

        break;


    
    //DEFAULT  CART PAGE
    
    default:

        include '../views/vendor_cart.php';

        break;
}
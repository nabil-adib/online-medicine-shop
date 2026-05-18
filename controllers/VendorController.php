<?php

function vendor_home_ctrl($conn) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'vendor') {
        header('Location: index.php?page=login');
        exit;
    }
    
    // Get medicines for this vendor
    $vendor_name = $_SESSION['user_name'];
    $stmt = mysqli_prepare($conn, "SELECT m.*, c.name AS category_name FROM medicines m JOIN categories c ON m.category_id = c.id WHERE m.vendor_name = ? ORDER BY m.id DESC");
    mysqli_stmt_bind_param($stmt, "s", $vendor_name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $medicines = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $medicines[] = $row;
    }
    
    // Get categories
    $categories = get_all_categories($conn);
    
    require 'views/vendor_home.php';
}

function vendor_cart_ctrl($conn) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'vendor') {
        header('Location: index.php?page=login');
        exit;
    }
    require 'views/vendor_cart.php';
}

function vendor_checkout_ctrl($conn) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'vendor') {
        header('Location: index.php?page=login');
        exit;
    }
    require 'views/vendor_checkout.php';
}

function vendor_orders_ctrl($conn) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'vendor') {
        header('Location: index.php?page=login');
        exit;
    }
    
    // Get orders that contain medicines from this vendor
    $vendor_name = $_SESSION['user_name'];
    $stmt = mysqli_prepare($conn, "
        SELECT DISTINCT o.*, u.name AS customer_name 
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN medicines m ON oi.medicine_id = m.id
        JOIN users u ON o.user_id = u.id
        WHERE m.vendor_name = ?
        ORDER BY o.order_date DESC
    ");
    mysqli_stmt_bind_param($stmt, "s", $vendor_name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $orders = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
    
    require 'views/vendor_orders.php';
}

function vendor_invoice_ctrl($conn) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'vendor') {
        header('Location: index.php?page=login');
        exit;
    }
    require 'views/vendor_invoice.php';
}

function vendor_payment_ctrl($conn) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'vendor') {
        header('Location: index.php?page=login');
        exit;
    }
    require 'views/vendor_payment.php';
}
?>
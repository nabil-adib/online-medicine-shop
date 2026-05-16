<?php
// ==========================================
// CART MANAGEMENT
// ==========================================

function get_cart_items($conn, $user_id) {
    $stmt = mysqli_prepare($conn, "
        SELECT c.id as cart_id, c.medicine_id, c.quantity as cart_quantity,
               m.name as medicine_name, m.vendor_name, m.price as medicine_price, m.availability as medicine_stock
        FROM cart c
        JOIN medicines m ON c.medicine_id = m.id
        WHERE c.user_id = ? ORDER BY c.added_at DESC
    ");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $items;
}

function get_cart_item_by_medicine($conn, $user_id, $medicine_id) {
    $stmt = mysqli_prepare($conn, "SELECT id, quantity FROM cart WHERE user_id = ? AND medicine_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $medicine_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row;
}

function add_to_cart($conn, $user_id, $medicine_id, $cart_quantity) {
    $existing = get_cart_item_by_medicine($conn, $user_id, $medicine_id);
    if ($existing) {
        $new_quantity = $existing['quantity'] + $cart_quantity;
        $stmt = mysqli_prepare($conn, "UPDATE cart SET quantity = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $new_quantity, $existing['id']);
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO cart (user_id, medicine_id, quantity) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iii", $user_id, $medicine_id, $cart_quantity);
    }
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function update_cart_quantity($conn, $cart_id, $user_id, $cart_quantity) {
    $stmt = mysqli_prepare($conn, "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "iii", $cart_quantity, $cart_id, $user_id);
    return mysqli_stmt_execute($stmt);
}

function remove_cart_item($conn, $cart_id, $user_id) {
    $stmt = mysqli_prepare($conn, "DELETE FROM cart WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $cart_id, $user_id);
    return mysqli_stmt_execute($stmt);
}

function clear_cart($conn, $user_id) {
    $stmt = mysqli_prepare($conn, "DELETE FROM cart WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    return mysqli_stmt_execute($stmt);
}

// ==========================================
// CHECKOUT & ORDERS
// ==========================================

function create_order($conn, $user_id, $order_total, $shipping_address, $payment_method) {
    $order_status = 'pending'; 
    $stmt = mysqli_prepare($conn, "INSERT INTO orders (user_id, total_amount, shipping_address, status, payment_method) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "idsss", $user_id, $order_total, $shipping_address, $order_status, $payment_method);
    
    if (mysqli_stmt_execute($stmt)) {
        $order_id = mysqli_stmt_insert_id($stmt);
        mysqli_stmt_close($stmt);
        return $order_id;
    }
    return false;
}

function add_order_item($conn, $order_id, $medicine_id, $cart_quantity, $medicine_price) {
    $stmt = mysqli_prepare($conn, "INSERT INTO order_items (order_id, medicine_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iiid", $order_id, $medicine_id, $cart_quantity, $medicine_price);
    return mysqli_stmt_execute($stmt);
}

function add_payment($conn, $order_id, $payment_amount, $payment_method, $transaction_id) {
    $stmt = mysqli_prepare($conn, "INSERT INTO payments (order_id, amount, payment_method, transaction_id) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "idss", $order_id, $payment_amount, $payment_method, $transaction_id);
    return mysqli_stmt_execute($stmt);
}

function update_medicine_stock($conn, $medicine_id, $cart_quantity) {
    $stmt = mysqli_prepare($conn, "UPDATE medicines SET availability = availability - ? WHERE id = ? AND availability >= ?");
    mysqli_stmt_bind_param($stmt, "iii", $cart_quantity, $medicine_id, $cart_quantity);
    $success = mysqli_stmt_execute($stmt);
    $rows_affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return ($success && $rows_affected > 0);
}
?>
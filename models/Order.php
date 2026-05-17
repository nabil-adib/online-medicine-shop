<?php


// =========================
// ORDER MANAGEMENT
// =========================


// TOTAL ORDERS
function get_all_orders($conn)
{
    $stmt = mysqli_prepare($conn,
        "SELECT o.*, u.name AS customer_name
         FROM orders o
         JOIN users u ON o.user_id = u.id
         ORDER BY o.order_date DESC"
    );

    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $data;
}



// PENDING ORDERS (for dashboard)
function get_pending_orders($conn)
{
    $stmt = mysqli_prepare($conn,
        "SELECT o.id, u.name AS customer_name, o.status, o.order_date
         FROM orders o
         JOIN users u ON o.user_id = u.id
         WHERE o.status = 'pending'
         ORDER BY o.order_date DESC"
    );

    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $data;
}



// TOTAL PENDING ORDERS
function get_total_pending_orders($conn)
{
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM orders WHERE status = 'pending'");
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row['total'];
}



// GET COMPLETED ORDERS (ACCEPTED ONLY)
function get_completed_orders($conn)
{
    $stmt = mysqli_prepare($conn,
        "SELECT o.*, u.name AS customer_name, u.email
         FROM orders o
         JOIN users u ON o.user_id = u.id
         WHERE o.status = 'accepted'
         ORDER BY o.order_date DESC"
    );

    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $data;
}



// GET SINGLE ORDER ITEMS (VERY IMPORTANT)
function get_order_items($conn, $order_id)
{
    $stmt = mysqli_prepare($conn,
        "SELECT oi.*, m.name AS medicine_name
         FROM order_items oi
         JOIN medicines m ON oi.medicine_id = m.id
         WHERE oi.order_id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $order_id);

    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);

    $items = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $items[] = $row;
    }

    mysqli_stmt_close($stmt);

    return $items;
}



// UPDATE ORDER STATUS (AJAX SUPPORT)
function update_order_status($conn, $order_id, $status)
{
    $stmt = mysqli_prepare($conn,
        "UPDATE orders SET status = ? WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "si", $status, $order_id);

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}

function get_rejected_orders($conn)
{
    $stmt = mysqli_prepare(
        $conn,
        "SELECT o.*, u.name AS customer_name, u.email
         FROM orders o
         JOIN users u ON o.user_id = u.id
         WHERE o.status = 'rejected'
         ORDER BY o.order_date DESC"
    );

    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }

    mysqli_stmt_close($stmt);

    return $data;
}

function get_order_by_id($conn, $order_id)
{
    $stmt = mysqli_prepare(
        $conn,
        "SELECT o.*, u.name AS customer_name, u.email, u.contact
         FROM orders o
         JOIN users u ON o.user_id = u.id
         WHERE o.id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $order_id);

    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($res);

    mysqli_stmt_close($stmt);

    return $row;
}

function get_completed_orders_with_details($conn)
{
    $query = "
        SELECT 
            o.id AS order_id,
            o.user_id,
            o.total_amount,
            o.shipping_address,
            o.status,
            o.payment_method,
            o.order_date,
            u.name AS customer_name,
            u.email AS customer_email,
            u.phone AS customer_phone,
            m.id AS medicine_id,
            m.name AS medicine_name,
            m.price AS medicine_price,
            oi.quantity,
            oi.unit_price,
            (oi.quantity * oi.unit_price) AS subtotal
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN order_items oi ON o.id = oi.order_id
        JOIN medicines m ON oi.medicine_id = m.id
        WHERE o.status = 'accepted'
        ORDER BY o.order_date DESC, o.id, oi.id
    ";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    
    $orders = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $order_id = $row['order_id'];
        
        if (!isset($orders[$order_id])) {
            $orders[$order_id] = [
                'order_id' => $row['order_id'],
                'user_id' => $row['user_id'],
                'customer_name' => $row['customer_name'],
                'customer_email' => $row['customer_email'],
                'customer_phone' => $row['customer_phone'],
                'total_amount' => $row['total_amount'],
                'shipping_address' => $row['shipping_address'],
                'status' => $row['status'],
                'payment_method' => $row['payment_method'],
                'order_date' => $row['order_date'],
                'items' => []
            ];
        }
        
        $orders[$order_id]['items'][] = [
            'medicine_id' => $row['medicine_id'],
            'medicine_name' => $row['medicine_name'],
            'quantity' => $row['quantity'],
            'unit_price' => $row['unit_price'],
            'subtotal' => $row['subtotal']
        ];
    }
    
    return array_values($orders);
}

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
<?php

// TOTAL CUSTOMERS
// Returns the count of all users with role = 'customer'
function get_total_customers($conn)
{
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM users WHERE role = 'customer'");
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row['total'];
}



// GET ALL CUSTOMERS
// Retrieves all customer records, ordered by most recent first
function get_all_customers($conn)
{
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE role = 'customer' ORDER BY id DESC");
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $data;
}



// GET CUSTOMER BY ID
// Fetches a single customer using their user ID, ensuring role is 'customer'
function get_customer_by_id($conn, $id)
{
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ? AND role = 'customer'");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row;
}



// DELETE CUSTOMER (CASCADE SAFE)
function delete_customer($conn, $id)
{
    // 1. delete cart items
    $stmt1 = mysqli_prepare($conn, "DELETE FROM cart WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt1, "i", $id);
    mysqli_stmt_execute($stmt1);
    mysqli_stmt_close($stmt1);

    // 2. get all orders of this user
    $stmt2 = mysqli_prepare($conn, "SELECT id FROM orders WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt2, "i", $id);
    mysqli_stmt_execute($stmt2);
    $result = mysqli_stmt_get_result($stmt2);
    mysqli_stmt_close($stmt2);

    // 3. delete payments, order_items, and orders one by one
    while ($order = mysqli_fetch_assoc($result)) {
        $oid = $order['id'];

        // Delete payments first (to satisfy foreign key constraint)
        $stmt_payment = mysqli_prepare($conn, "DELETE FROM payments WHERE order_id = ?");
        mysqli_stmt_bind_param($stmt_payment, "i", $oid);
        mysqli_stmt_execute($stmt_payment);
        mysqli_stmt_close($stmt_payment);

        // Delete order items
        $stmt3 = mysqli_prepare($conn, "DELETE FROM order_items WHERE order_id = ?");
        mysqli_stmt_bind_param($stmt3, "i", $oid);
        mysqli_stmt_execute($stmt3);
        mysqli_stmt_close($stmt3);

        // Delete order
        $stmt4 = mysqli_prepare($conn, "DELETE FROM orders WHERE id = ?");
        mysqli_stmt_bind_param($stmt4, "i", $oid);
        mysqli_stmt_execute($stmt4);
        mysqli_stmt_close($stmt4);
    }

    // 4. delete user
    $stmt5 = mysqli_prepare($conn, "DELETE FROM users WHERE id = ? AND role = 'customer'");
    mysqli_stmt_bind_param($stmt5, "i", $id);
    $result = mysqli_stmt_execute($stmt5);
    mysqli_stmt_close($stmt5);

    return $result;
}

// GET CUSTOMER ORDERS
// Retrieves all orders placed by a specific customer, with customer name joined
function get_customer_orders($conn, $customer_id)
{
    $stmt = mysqli_prepare(
        $conn,
        "SELECT o.*, u.name AS customer_name
         FROM orders o
         JOIN users u ON u.id = o.user_id
         WHERE o.user_id = ?
         ORDER BY o.order_date DESC"
    );

    mysqli_stmt_bind_param($stmt, "i", $customer_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $data;
}

// GET ALL PURCHASE HISTORY
// Returns all completed/approved orders (status = 'accepted') with customer details
function get_all_purchase_history($conn)
{
    $stmt = mysqli_prepare(
        $conn,
        "SELECT o.*, u.name AS customer_name, u.email
         FROM orders o
         JOIN users u ON u.id = o.user_id
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

?>
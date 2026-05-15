<?php
// Admin DB functions

function authAdmin($conn, $username, $password) {
    $stmt = mysqli_prepare($conn, "SELECT id, username, password FROM admins WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return ($row && password_verify($password, $row['password'])) ? $row : false;
}

//Category Management
function getTotalCategories($conn) {
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM categories");
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row['total'];
}

function getAllCategories($conn) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM categories");
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $data;
}

function addCategory($conn, $name, $type) {
    $stmt = mysqli_prepare($conn, "INSERT INTO categories (name, category_type) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, 'ss', $name, $type);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

function updateCategory($conn, $id, $name, $type) {
    $stmt = mysqli_prepare($conn, "UPDATE categories SET name = ?, category_type = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'ssi', $name, $type, $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

function deleteCategory($conn, $id) {
    $stmt = mysqli_prepare($conn, "DELETE FROM categories WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

function checkMedicinesInCategory($conn, $category_id) {
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM medicines WHERE category_id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $category_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row['total'] > 0;
}

//Medicine Management
function getTotalMedicines($conn) {
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM medicines");
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row['total'];
}

function getAllMedicines($conn) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM medicines");
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $data;
}

function getMedicineById($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM medicines WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row;
}

function addMedicine($conn, $data) {
    $stmt = mysqli_prepare($conn, "INSERT INTO medicines 
                                    (name, category_id, vendor_name, price, availability, description, image_path) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt,'sisidss', $data['name'], $data['category_id'], $data['vendor_name'], 
                            $data['price'], $data['availibility'], $data['description'], $data['image_path']);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

function updateMedicine($conn, $id, $data) {
    $stmt = mysqli_prepare($conn, "UPDATE medicines 
                                    SET name=?, category_id=?, vendor_name=?, price=?, availability=?, description=?, image_path=? 
                                    WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'sisidssi', $data['name'], $data['category_id'], $data['vendor_name'], 
                            $data['price'], $data['availability'], $data['description'], $data['image_path'], $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

function deleteMedicine($conn, $id) {
    $stmt = mysqli_prepare($conn, "DELETE FROM medicines WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

//Customer Management
function getTotalCustomers($conn) {
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM users WHERE role = 'customer'");
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row['total'];
}

function getAllCustomers($conn) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE role = 'customer'");
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $data;
}

function deleteCustomer($conn, $id) {
    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ? AND role = 'customer'");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

//Order Management
function getAllOrders($conn) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM orders ORDER BY created_at DESC");
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $data;
}

function getTotalPendingOrders($conn) {
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM orders WHERE status = 'pending'");
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row['total'];
}

function getPendingOrders($conn)
{
    $stmt = mysqli_prepare($conn, "SELECT o.id, u.name AS customer_name, o.status, o.order_date FROM orders o 
                                JOIN users u ON o.user_id = u.id WHERE o.status = 'pending' ORDER BY o.order_date DESC");
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $orders = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $orders[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $orders;
}

function updateOrderStatus($conn, $order_id, $status) {
    $stmt = mysqli_prepare($conn, "UPDATE orders SET status = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'si', $status, $order_id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

function getCompletedOrders($conn) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM orders WHERE status = 'accepted'");
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

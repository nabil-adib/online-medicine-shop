<?php

// =========================
// VENDOR MANAGEMENT
// =========================

// TOTAL VENDORS
function get_total_vendors($conn)
{
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM users WHERE role = 'vendor'");
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row['total'];
}

// GET ALL VENDORS
function get_all_vendors($conn)
{
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE role = 'vendor' ORDER BY id DESC");
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $data;
}

// GET VENDOR BY ID
function get_vendor_by_id($conn, $id)
{
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ? AND role = 'vendor'");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row;
}

// ADD VENDOR
function add_vendor($conn, $name, $email, $password_hash, $address, $phone)
{
    $role = 'vendor';
    $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password_hash, address, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssssss', $name, $email, $password_hash, $address, $phone, $role);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// UPDATE VENDOR
function update_vendor($conn, $id, $name, $email, $address, $phone)
{
    $stmt = mysqli_prepare($conn, "UPDATE users SET name = ?, email = ?, address = ?, phone = ? WHERE id = ? AND role = 'vendor'");
    mysqli_stmt_bind_param($stmt, 'ssssi', $name, $email, $address, $phone, $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// DELETE VENDOR
function delete_vendor($conn, $id)
{
    // First, get vendor name to check if they have medicines
    $vendor = get_vendor_by_id($conn, $id);
    if (!$vendor) {
        return false;
    }
    
    // Check if vendor has any medicines
    $stmt_check = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM medicines WHERE vendor_name = ?");
    mysqli_stmt_bind_param($stmt_check, 's', $vendor['name']);
    mysqli_stmt_execute($stmt_check);
    $res = mysqli_stmt_get_result($stmt_check);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt_check);
    
    if ($row['total'] > 0) {
        return false; // Cannot delete vendor with medicines
    }
    
    // Delete vendor
    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ? AND role = 'vendor'");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// CHECK VENDOR EMAIL EXISTS
function vendor_email_exists($conn, $email, $exclude_id = null)
{
    if ($exclude_id) {
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? AND role = 'vendor' AND id != ?");
        mysqli_stmt_bind_param($stmt, 'si', $email, $exclude_id);
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? AND role = 'vendor'");
        mysqli_stmt_bind_param($stmt, 's', $email);
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $exists = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    return $exists;
}
?>
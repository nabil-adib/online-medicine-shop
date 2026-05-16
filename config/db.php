<?php
$host = 'localhost';
$db = 'online_medicine_shop';
$user = 'root';
$pass = '';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8');

// Create admin user if no users exist
$check = mysqli_query($conn, "SELECT id FROM users LIMIT 1");
if ($check && mysqli_num_rows($check) === 0) {
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password_hash, role, address, phone) VALUES (?, ?, ?, ?, ?, ?)");
    $admin_name = 'Admin User';
    $admin_email = 'admin@medishop.com';
    $admin_role = 'admin';
    $admin_address = 'Admin Office';
    $admin_phone = '1234567890';
    mysqli_stmt_bind_param($stmt, 'ssssss', $admin_name, $admin_email, $hash, $admin_role, $admin_address, $admin_phone);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
?>
<?php
// Database connection parameters
$host = 'localhost';
$db   = 'online_medicine_shop';
$user = 'root';
$pass = '';

// Establish MySQL connection using mysqli
$conn = mysqli_connect($host, $user, $pass, $db);

// Terminate script if connection fails
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

// Set UTF-8 encoding for proper character handling
mysqli_set_charset($conn, 'utf8');

// Check if users table is empty
$check = mysqli_query($conn, "SELECT id FROM users LIMIT 1");
if ($check && mysqli_num_rows($check) === 0) {
    // Hash default password (admin123) before storage
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    // Prepared statement prevents SQL injection
    $stmt = mysqli_prepare($conn, "INSERT INTO users (email, password_hash) VALUES ('admin@mail.com', ?)");
    mysqli_stmt_bind_param($stmt, 's', $hash);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
?>
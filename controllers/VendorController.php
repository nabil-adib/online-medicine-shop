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

function vendor_medicines_ctrl($conn) {
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
    
    require 'views/vendor_medicines.php';
}
?>
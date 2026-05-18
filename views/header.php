<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaQuick - Online Medicine Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/assets/style.css">
</head>
<body>
<nav class="navbar">
    <div class="nav-container">
        <div class="logo">
            <i class="fas fa-hand-holding-medical"></i>
            <span>PharmaQuick</span>
        </div>
        <div class="nav-links">
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['user_role'] === 'admin'): ?>
                <!-- ADMIN NAVIGATION -->
                <a href="index.php?page=admin&action=dashboard"><i class="fas fa-chart-line"></i> Dashboard</a>
                <a href="index.php?page=admin&action=categories"><i class="fas fa-tags"></i> Categories</a>
                <a href="index.php?page=admin&action=medicines"><i class="fas fa-pills"></i> Medicines</a>
                <a href="index.php?page=admin&action=customers"><i class="fas fa-users"></i> Customers</a>
                <a href="index.php?page=admin&action=orders"><i class="fas fa-shopping-cart"></i> Orders</a>
                <a href="index.php?page=admin&action=history"><i class="fas fa-history"></i> History</a>
                <a href="index.php?page=profile"><i class="fas fa-user"></i> Profile</a>
                <a href="index.php?page=logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                
            <?php elseif (isset($_SESSION['logged_in']) && $_SESSION['user_role'] === 'vendor'): ?>
                <!-- VENDOR NAVIGATION - Only existing vendor pages -->
                <a href="index.php?page=vendor_home"><i class="fas fa-home"></i> Dashboard</a>
                <a href="index.php?page=vendor_cart"><i class="fas fa-cart-shopping"></i> Cart</a>
                <a href="index.php?page=vendor_orders"><i class="fas fa-shopping-cart"></i> Orders</a>
                <a href="index.php?page=vendor_invoice"><i class="fas fa-file-invoice"></i> Invoice</a>
                <a href="index.php?page=profile"><i class="fas fa-user"></i> Profile</a>
                <a href="index.php?page=logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                                
            <?php elseif (isset($_SESSION['logged_in']) && $_SESSION['user_role'] === 'customer'): ?>
                <!-- CUSTOMER NAVIGATION -->
                <a href="index.php?page=home"><i class="fas fa-home"></i> Home</a>
                <a href="index.php?page=cart"><i class="fas fa-shopping-cart"></i> Cart</a>
                <a href="index.php?page=orders"><i class="fas fa-box"></i> Orders</a>
                <a href="index.php?page=profile"><i class="fas fa-user"></i> Profile</a>
                <a href="index.php?page=logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                
            <?php else: ?>
                <!-- GUEST NAVIGATION -->
                <a href="index.php?page=home"><i class="fas fa-home"></i> Home</a>
                <a href="index.php?page=login">Login</a>
                <a href="index.php?page=register" class="register-btn">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<main class="main-content">
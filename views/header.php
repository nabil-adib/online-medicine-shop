<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Document metadata and configuration -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaQuick - Online Medicine Shop</title>
    
    <!-- External CSS and JavaScript files -->
    <link rel="stylesheet" href="public/assets/style.css">
    <script src="public/js/ajax.js"></script>
</head>
<body>

<!-- Navigation Bar Section -->
<nav class="navbar">
    <div class="nav-container">
        
        <!-- Logo and Brand Name -->
        <div class="logo">
            <img src="public/assets/pictures/logo.png" alt="PharmaQuick Logo" class="logo-img">
            <span>PharmaQuick</span>
        </div>
        
        <!-- Navigation Links Container -->
        <div class="nav-links">
            
            <!-- ADMIN NAVIGATION MENU -->
            <!-- Displayed when user is logged in as admin -->
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['user_role'] === 'admin'): ?>
                <a href="index.php?page=admin&action=dashboard">Dashboard</a>
                <a href="index.php?page=admin&action=categories">Categories</a>
                <a href="index.php?page=admin&action=medicines">Medicines</a>
                <a href="index.php?page=admin&action=customers">Customers</a>
                <a href="index.php?page=admin&action=orders">Orders</a>
                <a href="index.php?page=admin&action=history">History</a>
                <a href="index.php?page=profile">Profile</a>
                <a href="index.php?page=logout" class="logout-btn">Logout</a>
                
            <!-- VENDOR NAVIGATION MENU -->
            <!-- Displayed when user is logged in as vendor - Limited access -->
            <?php elseif (isset($_SESSION['logged_in']) && $_SESSION['user_role'] === 'vendor'): ?>
                <a href="index.php?page=vendor_home"><i class="fas fa-home"></i> Dashboard</a>
                <a href="index.php?page=vendor_medicines"><i class="fas fa-pills"></i> Medicines</a>
                <a href="index.php?page=profile"><i class="fas fa-user"></i> Profile</a>
                <a href="index.php?page=logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                                    
            <!-- CUSTOMER NAVIGATION MENU -->
            <!-- Displayed when user is logged in as regular customer -->
            <?php elseif (isset($_SESSION['logged_in']) && $_SESSION['user_role'] === 'customer'): ?>
                <a href="index.php?page=home">Home</a>
                <a href="index.php?page=cart">Cart</a>
                <a href="index.php?page=orders">Orders</a>
                <a href="index.php?page=profile">Profile</a>
                <a href="index.php?page=logout" class="logout-btn"> Logout</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Main Content Area - Page content will be inserted here -->
<main class="main-content">
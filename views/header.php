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
            <a href="index.php?page=home"><i class="fas fa-home"></i> Home</a>
            <a href="#"><i class="fas fa-shopping-cart"></i> Cart</a>
            <a href="#"><i class="fas fa-box"></i> Orders</a>
            <?php if (isset($_SESSION['logged_in'])): ?>
                <a href="index.php?page=profile"><i class="fas fa-user"></i> Profile</a>
                <a href="index.php?page=logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php else: ?>
                <a href="index.php?page=login">Login</a>
                <a href="index.php?page=register" class="register-btn">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<main class="main-content">
<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MediShop</title>
<link rel="stylesheet" href="public/assets/style.css">
</head>
<body>

<nav class="navbar">
    <a href="index.php?page=home" class="nav-brand">&#128138; MediShop</a>

    <div class="nav-links">
        <a href="index.php?page=home">Home</a>
        <a href="index.php?page=browse">Medicines</a>

        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>

            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <a href="index.php?page=admin">Admin Panel</a>
            <?php else: ?>
                <a href="index.php?page=cart">&#128722; Cart</a>
                <a href="index.php?page=orders">My Orders</a>
            <?php endif; ?>

            <a href="index.php?page=profile">
                <?php if (!empty($_SESSION['profile_picture'])): ?>
                    <img src="<?= htmlspecialchars($_SESSION['profile_picture']) ?>"
                         alt="pic" class="nav-avatar">
                <?php endif; ?>
                <?= htmlspecialchars($_SESSION['user_name']) ?>
            </a>
            <a href="index.php?page=logout" class="btn btn-sm">Logout</a>

        <?php else: ?>
            <a href="index.php?page=login">Login</a>
            <a href="index.php?page=register" class="btn btn-sm">Register</a>
        <?php endif; ?>
    </div>
</nav>
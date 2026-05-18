<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - PharmaQuick</title>
    <?php if (isset($pageCSS)) : ?>
    <link rel="stylesheet" href="public/assets/admin/<?= $pageCSS ?>">
    <?php endif; ?>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f4f7fb;
        color: #1f2937;
        line-height: 1.6;
    }

    /* ADMIN NAVBAR & FOOTER*/

    .navbar {
        background: white;
        border-bottom: 1px solid #e5e7eb;
        position: sticky;
        top: 0;
        z-index: 50;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .nav-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 64px;
    }


    .logo {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .logo-img {
        height: 40px;
        width: auto;
        object-fit: contain;
    }

    .logo span {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e40af;
    }
    .nav-links {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .nav-links a {
        text-decoration: none;
        color: #4b5563;
        font-weight: 500;
        transition: color 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }


    .nav-links a:hover {
        color: #2563eb;
    }

    .logout-btn {
        color: #dc2626 !important;
    }

    .logout-btn:hover {
        color: #b91c1c !important;
    }

    /* FOOTER */
    .admin-footer {
        text-align: center;
        padding: 1.5rem;
        color: #6b7280;
        font-size: 0.875rem;
        border-top: 1px solid #e5e7eb;
        background: white;
    }

    </style>
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <div class="logo">
            <img src="public/assets/pictures/logo.png" alt="PharmaQuick Logo" class="logo-img">
            <span>PharmaQuick</span>
        </div>
        <div class="nav-links">
            <a href="index.php?page=admin&action=dashboard">Dashboard</a>
            <a href="index.php?page=admin&action=categories"> Categories</a>
            <a href="index.php?page=admin&action=medicines">Medicines</a>
            <a href="index.php?page=admin&action=customers">Customers</a>
            <a href="index.php?page=admin&action=vendors">Vendors</a>
            <a href="index.php?page=admin&action=orders">Orders</a>
            <a href="index.php?page=admin&action=history">History</a>
            <a href="index.php?page=profile">Profile</a>
            <a href="index.php?page=logout" class="logout-btn">Logout</a>
        </div>
    </div>
</nav>
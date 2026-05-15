<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Medicine Shop - Admin Panel</title>

    <?php if (isset($pageCSS)) : ?>
        <link rel="stylesheet" href="/Online_Medicine_Shop/public/assets/admin/<?= $pageCSS ?>">
    <?php endif; ?>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        /* NAVBAR */
        .navbar {
            background: #2563eb;
            color: white;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            position: relative;
        }

        /* LEFT BRAND */
        .brand {
            font-weight: bold;
            font-size: 18px;
        }

        /* CENTER NAV */
        .nav-links {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin: 0 12px;
            font-size: 14px;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        /* RIGHT PROFILE */
        .profile {
            margin-left: auto;
            display: flex;
            align-items: center;
        }

        .profile img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #1abc9c;
            cursor: pointer;
        }

        /* CONTENT */
        .container {
            padding: 20px;
        }

    </style>
</head>

<body>

<div class="navbar">
    <div class="brand">
        Medicine Shop Admin
    </div>

    <div class="nav-links">
        <a href="index.php?page=admin/dashboard">Dashboard</a>
        <a href="index.php?page=admin/categories">Categories</a>
        <a href="index.php?page=admin/medicines">Medicines</a>
        <a href="index.php?page=admin/customers">Customers</a>
        <a href="index.php?page=admin/orders">Orders</a>
    </div>

    <!-- Profile button -->
    <div class="profile">
        <?php
            $profilePic = $_SESSION['profile_picture'] 
                ?? '/Online_Medicine_Shop/public/uploads/default-user.png';
        ?>
        <a href="index.php?page=profile">
            <img src="<?= $profilePic; ?>" alt="Profile">
        </a>
    </div>

</div>

<div class="container">
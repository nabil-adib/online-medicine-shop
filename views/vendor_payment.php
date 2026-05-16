<?php

session_start();

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: vendor_cart.php");
    exit();
}

$total_amount = $_POST['total_amount'] ?? 0;

$shipping_address = trim(
    $_POST['shipping_address'] ?? ''
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Vendor Payment</title>

    <link rel="stylesheet"
          href="../public/assets/style.css">

</head>

<body>

<h2>Select Payment Method</h2>



</body>
</html>
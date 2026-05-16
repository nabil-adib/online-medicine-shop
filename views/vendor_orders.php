<?php

session_start();

require_once '../models/OrderModel.php';

$user_id = $_SESSION['user_id'];

$orders = OrderModel::getOrders($user_id);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vendor Orders</title>
</head>


</html>
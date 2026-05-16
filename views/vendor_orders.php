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

<body>

<h2>Successful Orders</h2>

<table border="1" cellpadding="10">

<tr>
    <th>Order ID</th>
    <th>Total</th>
    <th>Status</th>
    <th>Date</th>
</tr>

<?php while($order = $orders->fetch_assoc()): ?>

<tr>

<td><?= $order['id'] ?></td>

<td><?= $order['total_amount'] ?></td>

<td><?= $order['status'] ?></td>

<td><?= $order['order_date'] ?></td>

</tr>

<?php endwhile; ?>

</table>

</body>
</html>
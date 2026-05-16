<?php

session_start();

require_once '../models/CartModel.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$cart_items = CartModel::getCartItems($user_id);

$total = 0;

?>

<!DOCTYPE html>
<html>

<head>

    <title>Vendor Invoice</title>

    <link rel="stylesheet"
          href="../public/assets/style.css">

</head>

<body>

<h2>Invoice</h2>

<table border="1" cellpadding="10">

<tr>
    <th>Medicine</th>
    <th>Vendor</th>
    <th>Unit Price</th>
    <th>Quantity</th>
    <th>Subtotal</th>
</tr>

<?php while($item = $cart_items->fetch_assoc()):

    $subtotal = $item['price'] * $item['quantity'];

    $total += $subtotal;
?>

<tr>

<td><?= htmlspecialchars($item['name']) ?></td>

<td><?= htmlspecialchars($item['vendor_name']) ?></td>

<td>৳<?= $item['price'] ?></td>

<td><?= $item['quantity'] ?></td>

<td>৳<?= $subtotal ?></td>

</tr>

<?php endwhile; ?>

</table>

<h3>Total Amount: ৳<?= $total ?></h3>

<br>

<form
method="POST"
action="vendor_payment.php"
>

<input
type="hidden"
name="total_amount"
value="<?= $total ?>"
>

<label>Shipping Address</label>

<br>

<textarea
name="shipping_address"
required
rows="4"
cols="40"
><?= htmlspecialchars($_SESSION['user_address'] ?? '') ?></textarea>

<br><br>

<button type="submit">
Proceed To Payment
</button>

<a href="vendor_cart.php">
    Cancel
</a>

</form>

</body>
</html>
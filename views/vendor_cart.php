<?php

session_start();

require_once '../models/CartModel.php';

$user_id = $_SESSION['user_id'];

$cart_items = CartModel::getCartItems($user_id);

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vendor Cart</title>

    <link rel="stylesheet"
          href="../public/assets/style.css">
</head>

<body>

<h2>Vendor Cart</h2>

<table border="1" cellpadding="10">

<tr>
    <th>Medicine</th>
    <th>Vendor</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Subtotal</th>
    <th>Action</th>
</tr>

<?php while($item = $cart_items->fetch_assoc()):

$subtotal = $item['price'] * $item['quantity'];

$total += $subtotal;
?>

<tr>

<td><?= htmlspecialchars($item['name']) ?></td>

<td><?= htmlspecialchars($item['vendor_name']) ?></td>

<td><?= $item['price'] ?></td>

<td>

<input
type="number"
value="<?= $item['quantity'] ?>"
min="1"
class="quantity"
data-id="<?= $item['cart_id'] ?>"
>

</td>

<td><?= $subtotal ?></td>

<td>

<button
class="remove-btn"
data-id="<?= $item['cart_id'] ?>"
>
Remove
</button>

</td>

</tr>

<?php endwhile; ?>

</table>

<h3>Total: ৳<?= $total ?></h3>

<a href="../controllers/VendorCartController.php?action=checkout">
    Proceed Checkout
</a>

<script src="../public/js/vendor_cart.js"></script>

</body>
</html>
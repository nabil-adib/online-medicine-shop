<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>

<body>

<h2>Checkout</h2>

<form
method="POST"
action="../controllers/VendorCartController.php?action=payment"
>

<label>Shipping Address</label>

<textarea
name="shipping_address"
required
><?= $_SESSION['user_address'] ?? '' ?></textarea>

<br><br>



<br><br>

<label>Total Amount</label>

<input
type="number"
step="0.01"
name="total_amount"
required
>

<br><br>

<button type="submit">
Confirm Purchase
</button>

</form>

</body>
</html>
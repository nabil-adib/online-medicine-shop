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

<form
method="POST"
action="../controllers/VendorCartController.php?action=payment"
id="payment-form"
>

<input
type="hidden"
name="total_amount"
value="<?= $total_amount ?>"
>

<input
type="hidden"
name="shipping_address"
value="<?= htmlspecialchars($shipping_address) ?>"
>

<label>

<input
type="radio"
name="payment_method"
value="Credit Card"
required
>

Credit Card

</label>

<br><br>

<label>

<input
type="radio"
name="payment_method"
value="bKash"
required
>

bKash

</label>

<br><br>

<label>

<input
type="radio"
name="payment_method"
value="Nagad"
required
>

Nagad

</label>

<br><br>

<label>

<input
type="radio"
name="payment_method"
value="Bank Transfer"
required
>

Bank Transfer

</label>

<br><br>

<label>

<input
type="radio"
name="payment_method"
value="Cash on Delivery"
required
>

Cash on Delivery

</label>

<br><br>

<h3>
Total Payable: ৳<?= $total_amount ?>
</h3>

<button type="submit">
Confirm Order
</button>

</form>



</body>
</html>
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



</body>
</html>
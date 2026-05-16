<?php

require_once __DIR__ . '/../config/db.php';

class CartModel {

    public static function getCartItems($user_id) {
        global $conn;

        $sql = "SELECT cart.id AS cart_id,
                       cart.quantity,
                       medicines.id AS medicine_id,
                       medicines.name,
                       medicines.vendor_name,
                       medicines.price,
                       medicines.availability,
                       medicines.image_path
                FROM cart
                JOIN medicines ON cart.medicine_id = medicines.id
                WHERE cart.user_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function addToCart($user_id, $medicine_id, $quantity) {
        global $conn;

        $check_sql = "SELECT * FROM cart WHERE user_id=? AND medicine_id=?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("ii", $user_id, $medicine_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $update_sql = "UPDATE cart
                           SET quantity = quantity + ?
                           WHERE user_id=? AND medicine_id=?";

            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("iii", $quantity, $user_id, $medicine_id);

            return $stmt->execute();

        } else {

            $insert_sql = "INSERT INTO cart(user_id, medicine_id, quantity)
                           VALUES(?,?,?)";

            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("iii", $user_id, $medicine_id, $quantity);

            return $stmt->execute();
        }
    }

    public static function updateCart($cart_id, $quantity) {
        global $conn;

        $sql = "UPDATE cart SET quantity=? WHERE id=?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $quantity, $cart_id);

        return $stmt->execute();
    }

    public static function removeCartItem($cart_id) {
        global $conn;

        $sql = "DELETE FROM cart WHERE id=?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $cart_id);

        return $stmt->execute();
    }

    public static function clearCart($user_id) {
        global $conn;

        $sql = "DELETE FROM cart WHERE user_id=?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        return $stmt->execute();
    }
}
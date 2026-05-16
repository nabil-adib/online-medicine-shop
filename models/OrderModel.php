<?php

require_once __DIR__ . '/../config/db.php';

class OrderModel {

    public static function createOrder(
        $user_id,
        $total_amount,
        $shipping_address,
        $payment_method
    ) {

        global $conn;

        $status = "pending";

        $sql = "INSERT INTO orders
                (user_id, total_amount, shipping_address, status, payment_method)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "idsss",
            $user_id,
            $total_amount,
            $shipping_address,
            $status,
            $payment_method
        );

        $stmt->execute();

        return $conn->insert_id;
    }

    public static function createOrderItem(
        $order_id,
        $medicine_id,
        $quantity,
        $unit_price
    ) {

        global $conn;

        $sql = "INSERT INTO order_items
                (order_id, medicine_id, quantity, unit_price)
                VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "iiid",
            $order_id,
            $medicine_id,
            $quantity,
            $unit_price
        );

        return $stmt->execute();
    }

    public static function createPayment(
        $order_id,
        $amount,
        $payment_method
    ) {

        global $conn;

        $transaction_id = uniqid("TXN");

        $sql = "INSERT INTO payments
                (order_id, amount, payment_method, transaction_id)
                VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "idss",
            $order_id,
            $amount,
            $payment_method,
            $transaction_id
        );

        return $stmt->execute();
    }

    public static function getOrders($user_id) {

        global $conn;

        $sql = "SELECT * FROM orders
                WHERE user_id=?
                ORDER BY id DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        return $stmt->get_result();
    }
}
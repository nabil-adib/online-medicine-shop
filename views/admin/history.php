<?php 

$pageCSS = 'history.css';

require_once __DIR__ . '/../../controllers/AuthController.php';
require_admin();

include __DIR__ . '/header.php';

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../models/admin.php';

?>

<div class="container">

    <h2>Purchase History</h2>
    <p>View all completed orders</p>

    <div class="dashboard-cards">

    <div class="card">
        <h4>Completed Orders</h4>
        <p><?= count($orders) ?></p>
    </div>

    <div class="card">
        <h4>Total Revenue</h4>
        <p>৳ <?= number_format(array_sum(array_column($orders, 'total_amount')), 2) ?></p>
    </div>

    </div>

    <hr>

    <?php if (empty($orders)): ?>
        <p>No completed orders yet.</p>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="order-box">
                <div class="order-header">
                    <h3>Order #<?= $order['order_id'] ?></h3>
                    <p class="order-date">Date: <?= date('d M Y, h:i A', strtotime($order['order_date'])) ?></p>
                </div>
                
                <div class="customer-info">
                    <h4>Customer Information</h4>
                    <p><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($order['customer_phone'] ?? 'N/A') ?></p>
                    <p><strong>Address:</strong> <?= htmlspecialchars($order['shipping_address']) ?></p>
                    <p><strong>Payment Method:</strong> <?= ucfirst(str_replace('_', ' ', $order['payment_method'])) ?></p>
                </div>
                
                <h4>Medicines Ordered</h4>
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Medicine Name</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['items'] as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['medicine_name']) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td>৳ <?= number_format($item['unit_price'], 2) ?></td>
                                <td>৳ <?= number_format($item['subtotal'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="total-row">
                            <td colspan="3"><strong>Grand Total</strong></td>
                            <td><strong>৳ <?= number_format($order['total_amount'], 2) ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr class="order-separator">
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/footer.php'; ?>
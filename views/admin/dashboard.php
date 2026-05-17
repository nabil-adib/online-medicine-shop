<?php 
$pageCSS = 'dashboard.css';
include __DIR__ . '/header.php';
?>

<div class="container">
    <h2>Dashboard</h2>

    <div class="dashboard-cards">
        <div class="card">
            <h4>Categories</h4>
            <p><?= $categoriesCount ?? 0 ?></p>
        </div>
        <div class="card">
            <h4>Medicines</h4>
            <p><?= $medicinesCount ?? 0 ?></p>
        </div>
        <div class="card">
            <h4>Customers</h4>
            <p><?= $customersCount ?? 0 ?></p>
        </div>
        <div class="card">
            <h4>Pending Orders</h4>
            <p><?= $ordersCount ?? 0 ?></p>
        </div>
    </div>

    <hr>

    <h3>Pending Orders</h3>

    <table class="dashboard-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($recentOrders)): ?>
                <?php foreach ($recentOrders as $order): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($order['id']) ?></td>
                        <td><?= htmlspecialchars($order['customer_name']) ?></td>
                        <td><?= htmlspecialchars($order['status']) ?></td>
                        <td><?= htmlspecialchars($order['order_date']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="empty">No pending orders</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/footer.php'; ?>
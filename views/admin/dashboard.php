<?php 
// Include page-specific CSS and header template
$pageCSS = 'dashboard.css';
include __DIR__ . '/header.php';
?>

<div class="container">
    <h2>Dashboard</h2>
    
    <!-- Statistics Overview Cards Section -->
    <div class="dashboard-cards">
        <!-- Display total number of categories -->
        <div class="card">
            <h4>Categories</h4>
            <p><?= $categoriesCount ?? 0 ?></p>
        </div>
        
        <!-- Display total number of medicines -->
        <div class="card">
            <h4>Medicines</h4>
            <p><?= $medicinesCount ?? 0 ?></p>
        </div>
        
        <!-- Display total number of customers -->
        <div class="card">
            <h4>Customers</h4>
            <p><?= $customersCount ?? 0 ?></p>
        </div>
        
        <!-- Display total number of pending orders -->
        <div class="card">
            <h4>Pending Orders</h4>
            <p><?= $ordersCount ?? 0 ?></p>
        </div>
    </div>

    <!-- Horizontal separator line -->
    <hr>

    <!-- Recent Pending Orders List Section -->
    <h3>Pending Orders</h3>

    <!-- Table to display recent pending orders -->
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
            <!-- Check if there are any recent orders -->
            <?php if (!empty($recentOrders)): ?>
                <!-- Loop through each order and display details -->
                <?php foreach ($recentOrders as $order): ?>
                    <tr>
                        <!-- Display order ID with # prefix -->
                        <td>#<?= htmlspecialchars($order['id']) ?></td>
                        <!-- Display customer name (escape for security) -->
                        <td><?= htmlspecialchars($order['customer_name']) ?></td>
                        <!-- Display order status (escape for security) -->
                        <td><?= htmlspecialchars($order['status']) ?></td>
                        <!-- Display order date (escape for security) -->
                        <td><?= htmlspecialchars($order['order_date']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Show message when no pending orders exist -->
                <tr>
                    <td colspan="4" class="empty">No pending orders</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Include footer template -->
<?php include __DIR__ . '/footer.php'; ?>
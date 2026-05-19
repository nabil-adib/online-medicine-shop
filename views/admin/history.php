<?php 
// Include page-specific CSS
$pageCSS = 'history.css';

// Require authentication and admin access
require_once __DIR__ . '/../../controllers/AuthController.php';
require_admin();

// Include header template
include __DIR__ . '/header.php';

// Load admin model functions
require_once __DIR__ . '/../../models/admin.php';
?>

<div class="container">

    <!-- Page Header Section -->
    <h2>Purchase History</h2>
    <p>View all completed orders</p>

    <!-- Statistics Summary Cards -->
    <div class="dashboard-cards">
        <!-- Display total number of completed orders -->
        <div class="card">
            <h4>Completed Orders</h4>
            <p><?= count($orders) ?></p>
        </div>

        <!-- Calculate and display total revenue from all orders -->
        <div class="card">
            <h4>Total Revenue</h4>
            <p>৳ <?= number_format(array_sum(array_column($orders, 'total_amount')), 2) ?></p>
        </div>
    </div>

    <!-- Horizontal separator line -->
    <hr>

    <!-- Check if there are any completed orders -->
    <?php if (empty($orders)): ?>
        <!-- Display message when no orders exist -->
        <p>No completed orders yet.</p>
    <?php else: ?>
        <!-- Loop through each order and display details -->
        <?php foreach ($orders as $order): ?>
            <!-- Individual Order Container -->
            <div class="order-box">
                
                <!-- Order Header Section with ID and Date -->
                <div class="order-header">
                    <h3>Order #<?= $order['order_id'] ?></h3>
                    <p class="order-date">Date: <?= date('d M Y, h:i A', strtotime($order['order_date'])) ?></p>
                </div>
                
                <!-- Customer Information Section -->
                <div class="customer-info">
                    <h4>Customer Information</h4>
                    <p><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($order['customer_phone'] ?? 'N/A') ?></p>
                    <p><strong>Address:</strong> <?= htmlspecialchars($order['shipping_address']) ?></p>
                    <p><strong>Payment Method:</strong> <?= ucfirst(str_replace('_', ' ', $order['payment_method'])) ?></p>
                </div>
                
                <!-- Order Items Table Section -->
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
                        <!-- Loop through each medicine in the order -->
                        <?php foreach ($order['items'] as $item): ?>
                            <tr>
                                <!-- Medicine name with HTML escaping -->
                                <td><?= htmlspecialchars($item['medicine_name']) ?></td>
                                <!-- Quantity ordered -->
                                <td><?= $item['quantity'] ?></td>
                                <!-- Unit price formatted with 2 decimals -->
                                <td>৳ <?= number_format($item['unit_price'], 2) ?></td>
                                <!-- Subtotal calculation result -->
                                <td>৳ <?= number_format($item['subtotal'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <!-- Grand Total Row -->
                        <tr class="total-row">
                            <td colspan="3"><strong>Grand Total</strong></td>
                            <td><strong>৳ <?= number_format($order['total_amount'], 2) ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Separator between orders -->
            <hr class="order-separator">
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Include footer template -->
<?php include __DIR__ . '/footer.php'; ?>
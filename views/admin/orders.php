<?php
// Include page-specific CSS and header template
$pageCSS = 'orders.css';
include __DIR__ . '/header.php';
?>

<div class="container">
    <h2>Order Management</h2>
    
    <!-- Orders Table Section - Display all purchase requests -->
    <div class="card">
        <h3>All Purchase Requests</h3>
        
        <!-- Table to display all orders with their details and status -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Shipping Address</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Check if there are any orders in the database -->
                <?php if (!empty($orders)): ?>
                    <!-- Loop through each order and display row -->
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <!-- Order ID -->
                            <td><?= $o['id'] ?></td>
                            <!-- Customer name with HTML escaping -->
                            <td><?= htmlspecialchars($o['customer_name']) ?></td>
                            <!-- Shipping address with fallback for null values -->
                            <td><?= htmlspecialchars($o['shipping_address'] ?? 'N/A') ?></td>
                            <!-- Total amount formatted with 2 decimal places -->
                            <td><?= number_format($o['total_amount'], 2) ?></td>
                            <!-- Order date -->
                            <td><?= $o['order_date'] ?></td>
                            <!-- Order status with dynamic CSS class -->
                            <td>
                                <span class="status <?= $o['status'] ?>">
                                    <?= ucfirst($o['status']) ?>
                                </span>
                            </td>
                            <!-- Action buttons based on order status -->
                            <td>
                                <!-- Show Accept/Reject buttons only for pending orders -->
                                <?php if ($o['status'] === 'pending'): ?>
                                    <button class="btn accept" onclick="updateStatus(<?= $o['id'] ?>, 'accepted')">
                                        Accept
                                    </button>
                                    <button class="btn reject" onclick="updateStatus(<?= $o['id'] ?>, 'rejected')">
                                        Reject
                                    </button>
                                <?php else: ?>
                                    <!-- Show dash for non-pending orders -->
                                    <span class="no-action">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                <?php else: ?>
                    <!-- Show message when no orders exist -->
                    <tr>
                        <td colspan="7" class="empty">No orders found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- AJAX Function for Updating Order Status -->
<script>
function updateStatus(orderId, status) {
    // Send asynchronous request to update order status
    fetch('index.php?page=admin&action=update_order_status&order_id=' + encodeURIComponent(orderId) + '&status=' + encodeURIComponent(status), {
        credentials: 'same-origin'  // Include session cookies for authentication
    })
    .then(function(response) { 
        // Parse JSON response from server
        return response.json(); 
    })
    .then(function(data) { 
        // Handle successful response
        if (data.success) {
            location.reload();  // Reload page to show updated status
        } else {
            alert('Update failed');
        }
    })
    .catch(function(error) { 
        // Handle network or parsing errors
        console.error('Error:', error); 
        alert('Failed to update order status');
    });
}
</script>

<!-- Include footer template -->
<?php include __DIR__ . '/footer.php'; ?>
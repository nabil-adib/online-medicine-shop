<?php

$pageCSS = 'orders.css';
include __DIR__ . '/header.php';

?>

<div class="container">

    <h2>Order Management</h2>


    <!-- =========================
         ORDERS TABLE
    ========================== -->

    <div class="card">

        <h3>All Purchase Requests</h3>

        <table>

            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Shipping Address</th>
                <th>Total</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php if (!empty($orders)): ?>

                <?php foreach ($orders as $o): ?>

                    <tr>

                        <td><?= $o['id'] ?></td>

                        <!-- comes from JOIN users -->
                        <td><?= htmlspecialchars($o['customer_name']) ?></td>

                        <td><?= htmlspecialchars($o['shipping_address'] ?? 'N/A') ?></td>

                        <td><?= number_format($o['total_amount'], 2) ?></td>

                        <td><?= $o['order_date'] ?></td>

                        <td>
                            <span class="status <?= $o['status'] ?>">
                                <?= ucfirst($o['status']) ?>
                            </span>
                        </td>

                        <td>

                            <button class="btn accept"
                                    onclick="updateStatus(<?= $o['id'] ?>, 'accepted')">
                                Accept
                            </button>

                            <button class="btn reject"
                                    onclick="updateStatus(<?= $o['id'] ?>, 'rejected')">
                                Reject
                            </button>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="7" class="empty">No orders found</td>
                </tr>

            <?php endif; ?>

        </table>

    </div>

</div>


<!-- =========================
     AJAX
========================= -->

<script>
function updateStatus(orderId, status) {
    fetch('index.php?page=admin&action=update_order_status&order_id=' + encodeURIComponent(orderId) + '&status=' + encodeURIComponent(status), {
        credentials: 'same-origin'
    })
    .then(function(response) { 
        return response.json(); 
    })
    .then(function(data) { 
        if (data.success) {
            location.reload();
        } else {
            alert('Update failed');
        }
    })
    .catch(function(error) { 
        console.error('Error:', error); 
        alert('Failed to update order status');
    });
}
</script>

<?php include __DIR__ . '/footer.php'; ?>
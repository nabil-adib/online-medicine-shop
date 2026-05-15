<?php 
$pageCSS = 'dashboard.css';
include __DIR__ . '/../header.php'; 

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controllers/adminController.php'; 
require_once __DIR__ . '/../../models/admin.php'; 
// (put getPendingOrders + count functions inside this file)
?>

<h2>Dashboard</h2>

<div class="dashboard-cards">

    <div class="card">
        <h4>Categories</h4>
        <p><?= getTotalCategories($conn) ?></p>
    </div>

    <div class="card">
        <h4>Medicines</h4>
        <p><?= getTotalMedicines($conn) ?></p>
    </div>

    <div class="card">
        <h4>Customers</h4>
        <p><?= getTotalCustomers($conn) ?></p>
    </div>

    <div class="card">
        <h4>Orders</h4>
        <p><?= getTotalPendingOrders($conn) ?></p>
    </div>

</div>

<hr>

<h3>Pending Orders</h3>

<table class="dashboard-table">

    <tr>
        <th>Order ID</th>
        <th>Customer</th>
        <th>Status</th>
        <th>Date</th>
    </tr>

    <?php 
    $orders = getPendingOrders($conn);

    if (!empty($orders)): 
        foreach ($orders as $order): 
    ?>
        <tr>
            <td>#<?= htmlspecialchars($order['id']) ?></td>
            <td><?= htmlspecialchars($order['customer_name']) ?></td>
            <td><?= htmlspecialchars($order['status']) ?></td>
            <td><?= htmlspecialchars($order['created_at']) ?></td>
        </tr>
    <?php 
        endforeach;
    else: 
    ?>
        <tr>
            <td colspan="4">No pending orders</td>
        </tr>
    <?php endif; ?>

</table>

<?php include __DIR__ . '/../footer.php'; ?>
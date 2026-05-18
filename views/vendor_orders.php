<?php
require 'views/header.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'vendor') {
    header('Location: index.php?page=login');
    exit;
}

// Get orders that contain medicines from this vendor
$vendor_name = $_SESSION['user_name'];
$stmt = mysqli_prepare($conn, "
    SELECT DISTINCT o.*, u.name AS customer_name 
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN medicines m ON oi.medicine_id = m.id
    JOIN users u ON o.user_id = u.id
    WHERE m.vendor_name = ?
    ORDER BY o.order_date DESC
");
mysqli_stmt_bind_param($stmt, "s", $vendor_name);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}
?>

<div class="container mx-auto px-4 py-8 max-w-6xl">
    <h1 class="text-2xl font-bold mb-6">My Orders</h1>
    
    <?php if (empty($orders)): ?>
        <div class="bg-white rounded-xl shadow-sm border p-12 text-center">
            <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">No orders yet for your pharmacy.</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Order ID</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Customer</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Date</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Total</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($orders as $order): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">#<?= $order['id'] ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td class="px-6 py-4"><?= date('M d, Y', strtotime($order['order_date'])) ?></td>
                            <td class="px-6 py-4 font-medium">৳<?= number_format($order['total_amount'], 2) ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                    <?= $order['status'] === 'accepted' ? 'bg-green-100 text-green-700' : 
                                       ($order['status'] === 'rejected' ? 'bg-red-100 text-red-700' : 
                                       'bg-yellow-100 text-yellow-700') ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <button onclick="viewOrderDetails(<?= $order['id'] ?>)" class="text-blue-600 hover:text-blue-800">
                                    View Details
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
function viewOrderDetails(orderId) {
    window.location.href = 'index.php?page=admin&action=orders';
}
</script>

<?php require 'views/footer.php'; ?>

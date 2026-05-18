<?php
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header('Location: index.php?page=login');
    exit;
}
require 'views/header.php';
?>

<div class="container mx-auto px-4 py-8 max-w-5xl">
    <h1 class="text-2xl font-bold mb-6">My Orders</h1>
    
    <?php if (empty($orders)): ?>
        <div class="bg-white rounded-xl shadow-sm border p-12 text-center">
            <p class="text-gray-500">You haven't placed any orders yet.</p>
            <a href="index.php?page=home" class="inline-block mt-4 text-blue-600 hover:underline">Start Shopping →</a>
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach ($orders as $order): ?>
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center flex-wrap gap-4">
                        <div>
                            <span class="text-sm text-gray-500">Order #</span>
                            <span class="font-bold text-gray-900"><?= $order['id'] ?></span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Date: </span>
                            <span><?= date('M d, Y', strtotime($order['order_date'])) ?></span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Total: </span>
                            <span class="font-bold text-blue-600">৳<?= number_format($order['total_amount'], 2) ?></span>
                        </div>
                        <div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                <?= $order['status'] === 'accepted' ? 'bg-green-100 text-green-700' : 
                                   ($order['status'] === 'rejected' ? 'bg-red-100 text-red-700' : 
                                   'bg-yellow-100 text-yellow-700') ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-3">
                            <?php foreach ($order['items'] as $item): ?>
                                <div class="flex justify-between items-center border-b pb-3">
                                    <div>
                                        <p class="font-medium"><?= htmlspecialchars($item['medicine_name']) ?></p>
                                        <p class="text-sm text-gray-500">Quantity: <?= $item['quantity'] ?></p>
                                    </div>
                                    <p class="font-medium">৳<?= number_format($item['unit_price'] * $item['quantity'], 2) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-sm"><strong>Shipping Address:</strong> <?= htmlspecialchars($order['shipping_address']) ?></p>
                            <p class="text-sm"><strong>Payment Method:</strong> <?= ucfirst(str_replace('_', ' ', $order['payment_method'])) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require 'views/footer.php'; ?>
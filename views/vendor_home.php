<?php
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'vendor') {
    header('Location: index.php?page=login');
    exit;
}
require 'views/header.php';
?>

<div class="container">
    <!-- Welcome Banner -->
    <div style="background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); border-radius: 1rem; color: white; padding: 2rem; margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: bold; margin-bottom: 0.5rem;">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h1>
        <p style="color: #bfdbfe;">Vendor Dashboard - Manage your pharmacy products and orders</p>
    </div>
    
    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; border: 1px solid #e5e7eb; text-align: center;">
            <i class="fas fa-pills" style="font-size: 2rem; color: #2563eb; margin-bottom: 0.5rem;"></i>
            <h3 style="font-size: 1.875rem; font-weight: bold; margin: 0.5rem 0;"><?= count($medicines ?? []) ?></h3>
            <p style="color: #6b7280;">Your Medicines</p>
        </div>
        
        <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; border: 1px solid #e5e7eb; text-align: center;">
            <i class="fas fa-tags" style="font-size: 2rem; color: #8b5cf6; margin-bottom: 0.5rem;"></i>
            <h3 style="font-size: 1.875rem; font-weight: bold; margin: 0.5rem 0;"><?= count($categories ?? []) ?></h3>
            <p style="color: #6b7280;">Categories Available</p>
        </div>
        
        <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; border: 1px solid #e5e7eb; text-align: center;">
            <i class="fas fa-shopping-cart" style="font-size: 2rem; color: #10b981; margin-bottom: 0.5rem;"></i>
            <h3 style="font-size: 1.875rem; font-weight: bold; margin: 0.5rem 0;">0</h3>
            <p style="color: #6b7280;">Orders Received</p>
            <a href="index.php?page=vendor_orders" style="display: inline-block; margin-top: 1rem; color: #2563eb; text-decoration: none;">View Orders →</a>
        </div>
    </div>
    
    <!-- Your Products Section -->
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; border: 1px solid #e5e7eb;">
        <h3 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem;">Your Products</h3>
        <?php if (!empty($medicines)): ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="padding: 0.75rem; text-align: left; border-bottom: 1px solid #e5e7eb;">Name</th>
                            <th style="padding: 0.75rem; text-align: left; border-bottom: 1px solid #e5e7eb;">Category</th>
                            <th style="padding: 0.75rem; text-align: left; border-bottom: 1px solid #e5e7eb;">Price</th>
                            <th style="padding: 0.75rem; text-align: left; border-bottom: 1px solid #e5e7eb;">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($medicines, 0, 10) as $med): ?>
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 0.75rem;"><?= htmlspecialchars($med['name']) ?></td>
                                <td style="padding: 0.75rem;"><?= htmlspecialchars($med['category_name']) ?></td>
                                <td style="padding: 0.75rem;">৳<?= number_format($med['price'], 2) ?></td>
                                <td style="padding: 0.75rem;"><?= $med['availability'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p style="text-align: center; padding: 3rem; color: #9ca3af;">
                <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                No products found. Medicines are managed by admin.
            </p>
        <?php endif; ?>
    </div>
</div>

<?php require 'views/footer.php'; ?>
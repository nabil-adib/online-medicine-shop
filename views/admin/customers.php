<?php
// Include page-specific CSS and header template
$pageCSS = 'customers.css';
include __DIR__ . '/header.php';
?>

<div class="container">
    <h2>Customer Management</h2>

    <!-- Display success message after customer deletion -->
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="success-msg">
            Customer deleted successfully.
        </div>
    <?php endif; ?>

    <!-- Customers List Section -->
    <div class="card">
        <h3>All Customers</h3>
        
        <!-- Table to display all registered customers -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Check if customers exist in the database -->
                <?php if (!empty($customers)): ?>
                    <!-- Loop through each customer and display their information -->
                    <?php foreach ($customers as $c): ?>
                        <tr>
                            <!-- Display customer ID -->
                            <td><?= $c['id'] ?></td>
                            <!-- Display customer name (escape HTML for security) -->
                            <td><?= htmlspecialchars($c['name']) ?></td>
                            <!-- Display customer email (escape HTML for security) -->
                            <td><?= htmlspecialchars($c['email']) ?></td>
                            <!-- Action buttons for customer management -->
                            <td>
                                <!-- Delete customer link with confirmation dialog -->
                                <a href="index.php?page=admin&action=delete_customer&id=<?= $c['id'] ?>"
                                   onclick="return confirm('Delete this customer? This will remove cart, orders, and account.')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                <?php else: ?>
                    <!-- Show message when no customers are found -->
                    <tr>
                        <td colspan="4" class="empty">
                            No customers found
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Include footer template -->
<?php include __DIR__ . '/footer.php'; ?>
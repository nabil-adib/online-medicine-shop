<?php

$pageCSS = 'customers.css';
include __DIR__ . '/header.php';

?>

<div class="container">

    <h2>Customer Management</h2>

    <!-- =========================
         SUCCESS MESSAGE
    ========================== -->

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="success-msg">
            Customer deleted successfully.
        </div>
    <?php endif; ?>


    <!-- =========================
         CUSTOMER TABLE
    ========================== -->

    <div class="card">

        <h3>All Customers</h3>

        <table>

            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>

            <?php if (!empty($customers)): ?>

                <?php foreach ($customers as $c): ?>

                    <tr>

                        <td><?= $c['id'] ?></td>

                        <td><?= htmlspecialchars($c['name']) ?></td>

                        <td><?= htmlspecialchars($c['email']) ?></td>

                        <td>
                            <!-- DELETE CUSTOMER -->
                            <a href="index.php?page=admin&action=delete_customer&id=<?= $c['id'] ?>"
                               onclick="return confirm('Delete this customer? This will remove cart, orders, and account.')">
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="4" class="empty">
                        No customers found
                    </td>
                </tr>

            <?php endif; ?>

        </table>

    </div>

</div>

<?php include __DIR__ . '/footer.php'; ?>
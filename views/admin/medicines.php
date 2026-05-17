<?php
$pageCSS = 'medicines.css';
include __DIR__ . '/header.php';
$isEdit = !empty($editing);
?>

<div class="container">
    <h2>Medicine Management</h2>

    <!-- Display error message -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Success message -->
    <?php if (isset($_GET['msg'])): ?>
        <?php
        $messages = [
            'added'   => 'Medicine added successfully.',
            'updated' => 'Medicine updated successfully.',
            'deleted' => 'Medicine deleted successfully.'
        ];
        $msg = $messages[$_GET['msg']] ?? '';
        ?>
        <?php if ($msg): ?>
            <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Form -->
    <div class="card">
        <h3><?= $isEdit ? 'Edit Medicine' : 'Add Medicine' ?></h3>

        <form method="POST" enctype="multipart/form-data" id="medicineForm"
              action="index.php?page=admin&action=<?= $isEdit ? 'update_medicine&id=' . $editing['id'] : 'add_medicine' ?>">

            <?php if ($isEdit): ?>
                <input type="hidden" name="old_image" value="<?= htmlspecialchars($editing['image_path'] ?? '') ?>">
            <?php endif; ?>

            <div class="row">
                <div class="form-group">
                    <label>Medicine Name</label>
                    <input type="text" name="name" id="medicine_name"
                           value="<?= htmlspecialchars($editing['name'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" id="category_id">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"
                                <?= (isset($editing['category_id']) && $editing['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Vendor Name</label>
                    <input type="text" name="vendor_name" id="vendor_name"
                           value="<?= htmlspecialchars($editing['vendor_name'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Price</label>
                    <input type="number" name="price" id="price" step="0.01"
                           value="<?= htmlspecialchars($editing['price'] ?? '') ?>">
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="availability" id="availability"
                           value="<?= htmlspecialchars($editing['availability'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Image</label>
                    <input type="file" name="image_path" id="image_path">
                    <?php if ($isEdit && !empty($editing['image_path'])): ?>
                        <small>Current: <?= htmlspecialchars($editing['image_path']) ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group full">
                <label>Description</label>
                <textarea name="description" id="description"><?= htmlspecialchars($editing['description'] ?? '') ?></textarea>
            </div>

            <div class="buttons">
                <?php if ($isEdit): ?>
                    <a href="index.php?page=admin&action=medicines" class="cancel-btn">Cancel</a>
                    <button type="submit" class="btn">Update Medicine</button>
                <?php else: ?>
                    <button type="submit" class="btn">Add Medicine</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card">
        <h3>All Medicines</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Vendor</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($medicines)): ?>
                        <?php foreach ($medicines as $m): ?>
                            <tr>
                                <td><?= $m['id'] ?></td>
                                <td>
                                    <?php if (!empty($m['image_path'])): ?>
                                        <img src="<?= htmlspecialchars($m['image_path']) ?>" class="img">
                                    <?php else: ?>
                                        No Image
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($m['name']) ?></td>
                                <td><?= htmlspecialchars($m['category_name']) ?></td>
                                <td><?= htmlspecialchars($m['vendor_name']) ?></td>
                                <td>$<?= number_format($m['price'], 2) ?></td>
                                <td><?= $m['availability'] ?></td>
                                <td>
                                    <a href="index.php?page=admin&action=edit_medicine&id=<?= $m['id'] ?>">Edit</a>
                                    |
                                    <a href="index.php?page=admin&action=delete_medicine&id=<?= $m['id'] ?>"
                                       onclick="return confirm('Delete this medicine?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="empty">No medicines found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.getElementById('medicineForm').addEventListener('submit', function(e) {
    const name = document.getElementById('medicine_name').value.trim();
    const vendorName = document.getElementById('vendor_name').value.trim();
    const price = document.getElementById('price').value;
    const availability = document.getElementById('availability').value;
    const category = document.getElementById('category_id').value;
    
    if (name === '') {
        alert('Medicine name cannot be empty');
        e.preventDefault();
        return false;
    }
    
    if (vendorName === '') {
        alert('Vendor name cannot be empty');
        e.preventDefault();
        return false;
    }
    
    if (category === '') {
        alert('Please select a category');
        e.preventDefault();
        return false;
    }
    
    if (price <= 0) {
        alert('Price must be greater than 0');
        e.preventDefault();
        return false;
    }
    
    if (availability <= 0) {
        alert('Availability must be greater than 0');
        e.preventDefault();
        return false;
    }
    
    return true;
});
</script>

<?php include __DIR__ . '/footer.php'; ?>
<?php
// Include page-specific CSS and header template
$pageCSS = 'medicines.css';
include __DIR__ . '/header.php';

// Check if we're in edit mode (editing existing medicine)
$isEdit = !empty($editing);
?>

<div class="container">
    <h2>Medicine Management</h2>

    <!-- Display validation or database error message -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Handle success messages from URL parameters -->
    <?php if (isset($_GET['msg'])): ?>
        <?php
        // Define success message texts for different actions
        $messages = [
            'added'   => 'Medicine added successfully.',
            'updated' => 'Medicine updated successfully.',
            'deleted' => 'Medicine deleted successfully.'
        ];
        $msg = $messages[$_GET['msg']] ?? '';
        ?>
        <!-- Display success alert if message exists -->
        <?php if ($msg): ?>
            <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
    <?php endif; ?>
    
    <!-- Display error when medicine cannot be deleted due to dependencies -->
    <?php if (isset($_GET['error']) && $_GET['error'] == 'cannot_delete'): ?>
        <div class="alert alert-error">
            Cannot delete this medicine. It is in customer carts or pending orders.
        </div>
    <?php endif; ?>
    
    <!-- Medicine Form Section - Add or Edit mode -->
    <div class="card">
        <h3><?= $isEdit ? 'Edit Medicine' : 'Add Medicine' ?></h3>

        <!-- Form for adding or updating medicine with file upload support -->
        <form method="POST" enctype="multipart/form-data" id="medicineForm"
              action="index.php?page=admin&action=<?= $isEdit ? 'update_medicine&id=' . $editing['id'] : 'add_medicine' ?>">

            <!-- Store old image path when editing (for deletion if replaced) -->
            <?php if ($isEdit): ?>
                <input type="hidden" name="old_image" value="<?= htmlspecialchars($editing['image_path'] ?? '') ?>">
            <?php endif; ?>

            <!-- Row 1: Medicine Name and Category -->
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
                        <!-- Populate categories dropdown from database -->
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"
                                <?= (isset($editing['category_id']) && $editing['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Row 2: Vendor Name and Price -->
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

            <!-- Row 3: Stock Availability and Image Upload -->
            <div class="row">
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="availability" id="availability"
                           value="<?= htmlspecialchars($editing['availability'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Image</label>
                    <input type="file" name="image_path" id="image_path">
                    <!-- Show current image filename when editing -->
                    <?php if ($isEdit && !empty($editing['image_path'])): ?>
                        <small>Current: <?= htmlspecialchars($editing['image_path']) ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Full-width Description Field -->
            <div class="form-group full">
                <label>Description</label>
                <textarea name="description" id="description"><?= htmlspecialchars($editing['description'] ?? '') ?></textarea>
            </div>

            <!-- Form action buttons (changes based on edit mode) -->
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

    <!-- Medicines List Table Section -->
    <div class="card">
        <h3>All Medicines</h3>
        <div class="table-responsive">
            <!-- Table to display all medicines with their details -->
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
                    <!-- Check if medicines exist in the database -->
                    <?php if (!empty($medicines)): ?>
                        <!-- Loop through each medicine and display -->
                        <?php foreach ($medicines as $m): ?>
                            <tr>
                                <!-- Medicine ID -->
                                <td><?= $m['id'] ?></td>
                                <!-- Medicine image thumbnail -->
                                <td>
                                    <?php if (!empty($m['image_path'])): ?>
                                        <img src="<?= htmlspecialchars($m['image_path']) ?>" class="img">
                                    <?php else: ?>
                                        No Image
                                    <?php endif; ?>
                                </td>
                                <!-- Medicine name with HTML escaping -->
                                <td><?= htmlspecialchars($m['name']) ?></td>
                                <!-- Category name -->
                                <td><?= htmlspecialchars($m['category_name']) ?></td>
                                <!-- Vendor name -->
                                <td><?= htmlspecialchars($m['vendor_name']) ?></td>
                                <!-- Price formatted with 2 decimal places -->
                                <td>$<?= number_format($m['price'], 2) ?></td>
                                <!-- Available stock quantity -->
                                <td><?= $m['availability'] ?></td>
                                <!-- Action buttons for Edit and Delete -->
                                <td>
                                    <a href="index.php?page=admin&action=edit_medicine&id=<?= $m['id'] ?>">Edit</a>
                                    |
                                    <a href="index.php?page=admin&action=delete_medicine&id=<?= $m['id'] ?>"
                                       onclick="return confirm('Delete this medicine?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Show message when no medicines exist -->
                        <tr>
                            <td colspan="8" class="empty">No medicines found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Client-side form validation script -->
<script>
// Add submit event listener for comprehensive form validation
document.getElementById('medicineForm').addEventListener('submit', function(e) {
    // Get all form field values
    const name = document.getElementById('medicine_name').value.trim();
    const vendorName = document.getElementById('vendor_name').value.trim();
    const price = document.getElementById('price').value;
    const availability = document.getElementById('availability').value;
    const category = document.getElementById('category_id').value;
    
    // Validate medicine name is not empty
    if (name === '') {
        alert('Medicine name cannot be empty');
        e.preventDefault();
        return false;
    }
    
    // Validate vendor name is not empty
    if (vendorName === '') {
        alert('Vendor name cannot be empty');
        e.preventDefault();
        return false;
    }
    
    // Validate category is selected
    if (category === '') {
        alert('Please select a category');
        e.preventDefault();
        return false;
    }
    
    // Validate price is positive number
    if (price <= 0) {
        alert('Price must be greater than 0');
        e.preventDefault();
        return false;
    }
    
    // Validate stock availability is positive number
    if (availability <= 0) {
        alert('Availability must be greater than 0');
        e.preventDefault();
        return false;
    }
    
    return true;
});
</script>

<!-- Include footer template -->
<?php include __DIR__ . '/footer.php'; ?>
<?php
// Include page-specific CSS and header template
$pageCSS = 'categories.css';
include __DIR__ . '/header.php';
$isEdit = !empty($editing);
?>

<div class="container">
    <h2>Category Management</h2>

    <!-- Display error message if any -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Handle and display success/error messages from URL parameters -->
    <?php if (isset($_GET['msg'])): ?>
        <?php
        // Define message types and their corresponding text
        $messages = [
            'added'   => ['type' => 'success', 'text' => 'Category added successfully.'],
            'updated' => ['type' => 'success', 'text' => 'Category updated successfully.'],
            'deleted' => ['type' => 'success', 'text' => 'Category deleted successfully.'],
            'blocked' => ['type' => 'error',   'text' => 'Cannot delete category. Medicines exist under this category.']
        ];
        $msgData = $messages[$_GET['msg']] ?? null;
        ?>
        <!-- Display the appropriate alert message -->
        <?php if ($msgData): ?>
            <div class="alert alert-<?= $msgData['type'] ?>">
                <?= htmlspecialchars($msgData['text']) ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Category Form Section - Add or Edit mode -->
    <div class="card">
        <h3><?= $isEdit ? 'Edit Category' : 'Add New Category' ?></h3>

        <!-- Form for adding or updating category -->
        <form method="POST" id="categoryForm"
              action="index.php?page=admin&action=<?= $isEdit ? 'update_category&id=' . $editing['id'] : 'add_category' ?>">

            <!-- Category name input field -->
            <div class="form-group">
                <label>Category Name</label>
                <input type="text"
                       name="name"
                       id="name"
                       placeholder="Enter category name"
                       value="<?= htmlspecialchars($editing['name'] ?? '') ?>">
            </div>

            <!-- Category type dropdown selection -->
            <div class="form-group">
                <label>Category Type</label>
                <select name="category_type" id="category_type">
                    <option value="">Select Type</option>
                    <option value="solid" <?= (($editing['category_type'] ?? '') == 'solid') ? 'selected' : '' ?>>Solid</option>
                    <option value="liquid" <?= (($editing['category_type'] ?? '') == 'liquid') ? 'selected' : '' ?>>Liquid</option>
                </select>
            </div>

            <!-- Form action buttons - changes based on edit mode -->
            <div class="form-buttons">
                <?php if ($isEdit): ?>
                    <a href="index.php?page=admin&action=categories" class="cancel-btn">Cancel</a>
                    <button type="submit" class="submit-btn">Update Category</button>
                <?php else: ?>
                    <button type="submit" class="submit-btn">Add Category</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Categories List Table Section -->
    <div class="card">
        <h3>All Categories</h3>
        <!-- Table to display all existing categories -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop through categories and display each row -->
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= $category['id'] ?></td>
                            <td><?= htmlspecialchars($category['name']) ?></td>
                            <td><?= ucfirst($category['category_type']) ?></td>
                            <td>
                                <!-- Edit and Delete action links -->
                                <a href="index.php?page=admin&action=edit_category&id=<?= $category['id'] ?>">Edit</a>
                                |
                                <a href="index.php?page=admin&action=delete_category&id=<?= $category['id'] ?>"
                                   onclick="return confirm('Are you sure to delete this category?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Show message when no categories exist -->
                    <tr>
                        <td colspan="4">No categories found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Client-side form validation script -->
<script>
// Add submit event listener for form validation before submission
document.getElementById('categoryForm').addEventListener('submit', function(e) {
    // Get form field values and trim whitespace
    const name = document.getElementById('name').value.trim();
    const categoryType = document.getElementById('category_type').value;
    
    // Validate category name is not empty
    if (name === '') {
        alert('Category name cannot be empty');
        e.preventDefault();
        return false;
    }
    
    // Validate category type is selected
    if (categoryType === '') {
        alert('Please select category type');
        e.preventDefault();
        return false;
    }
    
    return true;
});
</script>

<!-- Include footer template -->
<?php include __DIR__ . '/footer.php'; ?>
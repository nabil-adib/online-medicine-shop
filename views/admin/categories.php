<?php
$pageCSS = 'categories.css';
include __DIR__ . '/header.php';
$isEdit = !empty($editing);
?>

<div class="container">
    <h2>Category Management</h2>

    <!-- Display error message -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Success message -->
    <?php if (isset($_GET['msg'])): ?>
        <?php
        $messages = [
            'added'   => ['type' => 'success', 'text' => 'Category added successfully.'],
            'updated' => ['type' => 'success', 'text' => 'Category updated successfully.'],
            'deleted' => ['type' => 'success', 'text' => 'Category deleted successfully.'],
            'blocked' => ['type' => 'error',   'text' => 'Cannot delete category. Medicines exist under this category.']
        ];
        $msgData = $messages[$_GET['msg']] ?? null;
        ?>
        <?php if ($msgData): ?>
            <div class="alert alert-<?= $msgData['type'] ?>">
                <?= htmlspecialchars($msgData['text']) ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Form -->
    <div class="card">
        <h3><?= $isEdit ? 'Edit Category' : 'Add New Category' ?></h3>

        <form method="POST" id="categoryForm"
              action="index.php?page=admin&action=<?= $isEdit ? 'update_category&id=' . $editing['id'] : 'add_category' ?>">

            <div class="form-group">
                <label>Category Name</label>
                <input type="text"
                       name="name"
                       id="name"
                       placeholder="Enter category name"
                       value="<?= htmlspecialchars($editing['name'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Category Type</label>
                <select name="category_type" id="category_type">
                    <option value="">Select Type</option>
                    <option value="solid" <?= (($editing['category_type'] ?? '') == 'solid') ? 'selected' : '' ?>>Solid</option>
                    <option value="liquid" <?= (($editing['category_type'] ?? '') == 'liquid') ? 'selected' : '' ?>>Liquid</option>
                </select>
            </div>

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

    <!-- Table -->
    <div class="card">
        <h3>All Categories</h3>
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
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= $category['id'] ?></td>
                            <td><?= htmlspecialchars($category['name']) ?></td>
                            <td><?= ucfirst($category['category_type']) ?></td>
                            <td>
                                <a href="index.php?page=admin&action=edit_category&id=<?= $category['id'] ?>">Edit</a>
                                |
                                <a href="index.php?page=admin&action=delete_category&id=<?= $category['id'] ?>"
                                   onclick="return confirm('Are you sure to delete this category?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No categories found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('categoryForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const categoryType = document.getElementById('category_type').value;
    
    if (name === '') {
        alert('Category name cannot be empty');
        e.preventDefault();
        return false;
    }
    
    if (categoryType === '') {
        alert('Please select category type');
        e.preventDefault();
        return false;
    }
    
    return true;
});
</script>

<?php include __DIR__ . '/footer.php'; ?>
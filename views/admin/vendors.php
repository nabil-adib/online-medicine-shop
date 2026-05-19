<?php
// Include page-specific CSS and header template
$pageCSS = 'vendors.css';
include __DIR__ . '/header.php';

// Check if we're in edit mode (editing existing vendor)
$isEdit = !empty($editing);
?>

<div class="container">
    <h2>Vendor Management</h2>

    <!-- Display validation or database error message -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Handle success/error messages from URL parameters -->
    <?php if (isset($_GET['msg'])): ?>
        <?php
        // Define message types and their corresponding text
        $messages = [
            'added'    => ['type' => 'success', 'text' => 'Vendor added successfully.'],
            'updated'  => ['type' => 'success', 'text' => 'Vendor updated successfully.'],
            'deleted'  => ['type' => 'success', 'text' => 'Vendor deleted successfully.'],
            'blocked'  => ['type' => 'error',   'text' => 'Cannot delete vendor. Vendor has medicines in the system.'],
            'notfound' => ['type' => 'error',   'text' => 'Vendor not found.']
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

    <!-- Vendor Form Section - Add or Edit mode -->
    <div class="card">
        <h3><?= $isEdit ? 'Edit Vendor' : 'Add New Vendor' ?></h3>

        <!-- Form for adding or updating vendor information -->
        <form method="POST" id="vendorForm"
              action="index.php?page=admin&action=<?= $isEdit ? 'update_vendor&id=' . $editing['id'] : 'add_vendor' ?>">

            <!-- Row 1: Full Name and Phone Number -->
            <div class="form-row">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" id="name"
                           value="<?= htmlspecialchars($editing['name'] ?? $old_name ?? '') ?>"
                           placeholder="Enter vendor name" required>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" id="phone"
                           value="<?= htmlspecialchars($editing['phone'] ?? $old_phone ?? '') ?>"
                           placeholder="01712345678" required>
                </div>
            </div>

            <!-- Email Address Field -->
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" id="email"
                       value="<?= htmlspecialchars($editing['email'] ?? $old_email ?? '') ?>"
                       placeholder="vendor@example.com" required>
            </div>

            <!-- Password Fields - Only show when adding new vendor (not during edit) -->
            <?php if (!$isEdit): ?>
            <div class="form-row">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password"
                           placeholder="Min 8 characters" required>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password"
                           placeholder="Repeat password" required>
                </div>
            </div>
            <?php endif; ?>

            <!-- Vendor Address Field -->
            <div class="form-group">
                <label> Address</label>
                <textarea name="address" id="address" rows="3"
                          placeholder="Vendor's full address" required><?= htmlspecialchars($editing['address'] ?? $old_address ?? '') ?></textarea>
            </div>

            <!-- Form action buttons (changes based on edit mode) -->
            <div class="form-buttons">
                <?php if ($isEdit): ?>
                    <a href="index.php?page=admin&action=vendors" class="cancel-btn">Cancel</a>
                    <button type="submit" class="submit-btn">Update Vendor</button>
                <?php else: ?>
                    <button type="submit" class="submit-btn">Add Vendor</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Vendors List Table Section -->
    <div class="card">
        <h3>All Vendors <span class="badge"><?= count($vendors) ?> Total</span></h3>

        <div class="table-responsive">
            <!-- Table to display all registered vendors -->
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Joined Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Check if vendors exist in the database -->
                    <?php if (!empty($vendors)): ?>
                        <!-- Loop through each vendor and display their information -->
                        <?php foreach ($vendors as $v): ?>
                            <tr>
                                <!-- Vendor ID -->
                                <td><?= $v['id'] ?></td>
                                <!-- Vendor name with HTML escaping -->
                                <td><?= htmlspecialchars($v['name']) ?></td>
                                <!-- Vendor email with HTML escaping -->
                                <td><?= htmlspecialchars($v['email']) ?></td>
                                <!-- Vendor phone number -->
                                <td><?= htmlspecialchars($v['phone']) ?></td>
                                <!-- Vendor address (truncated to 50 characters) -->
                                <td><?= htmlspecialchars(substr($v['address'], 0, 50)) . (strlen($v['address']) > 50 ? '...' : '') ?></td>
                                <!-- Formatted join date -->
                                <td><?= date('M d, Y', strtotime($v['created_at'])) ?></td>
                                <!-- Action buttons for Edit and Delete -->
                                <td class="actions">
                                    <a href="index.php?page=admin&action=edit_vendor&id=<?= $v['id'] ?>" class="edit-btn"> Edit</a>
                                    <a href="index.php?page=admin&action=delete_vendor&id=<?= $v['id'] ?>" 
                                        class="delete-btn" onclick="return confirm('Delete this vendor? This will remove the vendor account.')">
                                        Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Show message when no vendors exist -->
                        <tr>
                            <td colspan="7" class="empty">No vendors found.</td>
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
document.getElementById('vendorForm')?.addEventListener('submit', function(e) {
    // Get form field values
    const name = document.getElementById('name')?.value;
    const email = document.getElementById('email')?.value;
    const phone = document.getElementById('phone')?.value;
    
    // Get password fields only when adding new vendor
    <?php if (!$isEdit): ?>
    const password = document.getElementById('password')?.value;
    const confirm = document.getElementById('confirm_password')?.value;
    <?php endif; ?>
    
    // Check if required fields are filled
    if (!name || !email || !phone) {
        e.preventDefault();
        alert('Please fill in all required fields');
        return false;
    }
    
    // Name validation - prevent numbers in name field
    if (/\d/.test(name)) {
        e.preventDefault();
        alert('Name cannot contain numbers. Please use only letters and spaces.');
        return false;
    }
    
    // Email format validation using regex
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email address');
        return false;
    }
    
    // Phone number validation (7-15 digits only)
    const phoneRegex = /^\d{7,15}$/;
    if (!phoneRegex.test(phone)) {
        e.preventDefault();
        alert('Phone number must be 7-15 digits');
        return false;
    }
    
    // Password validation for new vendors only
    <?php if (!$isEdit): ?>
    // Check minimum password length
    if (password.length < 8) {
        e.preventDefault();
        alert('Password must be at least 8 characters');
        return false;
    }
    
    // Check if passwords match
    if (password !== confirm) {
        e.preventDefault();
        alert('Passwords do not match');
        return false;
    }
    <?php endif; ?>
    
    return true;
});
</script>

<!-- Include footer template -->
<?php include __DIR__ . '/footer.php'; ?>
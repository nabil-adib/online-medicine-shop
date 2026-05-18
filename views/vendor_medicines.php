<?php
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'vendor') {
    header('Location: index.php?page=login');
    exit;
}
require 'views/header.php';
?>

<div class="container">
    <!-- Page Header -->
    <div style="background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); border-radius: 1rem; color: white; padding: 2rem; margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: bold; margin-bottom: 0.5rem;">
            <i class="fas fa-pills"></i> Manage Medicines
        </h1>
        <p style="color: #bfdbfe;">Add and edit your pharmacy products</p>
    </div>

    <!-- Add Medicine Form Section -->
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; border: 1px solid #e5e7eb; margin-bottom: 2rem;">
        <h2 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem;">
            <i class="fas fa-plus-circle"></i> Add New Medicine
        </h2>
        
        <form method="POST" action="index.php?page=ajax" id="addMedicineForm" style="display: grid; gap: 1rem; max-width: 600px;">
            <input type="hidden" name="action" value="add_medicine">

            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Medicine Name *</label>
                <input type="text" name="name" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Category *</label>
                <select name="category_id" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    <option value="">Select Category</option>
                    <?php foreach ($categories ?? [] as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['id']) ?>">
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Description</label>
                <textarea name="description" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; height: 100px; resize: vertical;"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Price (৳) *</label>
                    <input type="number" name="price" required step="0.01" min="0" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Stock Quantity *</label>
                    <input type="number" name="availability" required min="0" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>
            </div>

            <button type="submit" style="background: #2563eb; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer;">
                <i class="fas fa-check"></i> Add Medicine
            </button>
        </form>
    </div>

    <!-- Your Medicines List -->
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; border: 1px solid #e5e7eb;">
        <h2 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem;">
            <i class="fas fa-list"></i> Your Medicines
        </h2>

        <?php if (!empty($medicines)): ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f3f4f6;">
                            <th style="padding: 0.75rem; text-align: left; border-bottom: 1px solid #e5e7eb;">Name</th>
                            <th style="padding: 0.75rem; text-align: left; border-bottom: 1px solid #e5e7eb;">Category</th>
                            <th style="padding: 0.75rem; text-align: left; border-bottom: 1px solid #e5e7eb;">Price</th>
                            <th style="padding: 0.75rem; text-align: left; border-bottom: 1px solid #e5e7eb;">Stock</th>
                            <th style="padding: 0.75rem; text-align: left; border-bottom: 1px solid #e5e7eb;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($medicines as $med): ?>
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 0.75rem;"><?= htmlspecialchars($med['name']) ?></td>
                                <td style="padding: 0.75rem;"><?= htmlspecialchars($med['category_name'] ?? 'N/A') ?></td>
                                <td style="padding: 0.75rem;">৳<?= number_format($med['price'], 2) ?></td>
                                <td style="padding: 0.75rem;">
                                    <span style="<?= $med['availability'] > 10 ? 'color: #10b981;' : 'color: #f97316;' ?>">
                                        <?= $med['availability'] ?>
                                    </span>
                                </td>
                                <td style="padding: 0.75rem;">
                                    <button class="edit-btn" onclick="editMedicine(<?= $med['id'] ?>, '<?= htmlspecialchars(addslashes($med['name'])) ?>', <?= $med['category_id'] ?>, '<?= htmlspecialchars(addslashes($med['description'] ?? '')) ?>', <?= $med['price'] ?>, <?= $med['availability'] ?>)" style="background: #3b82f6; color: white; padding: 0.5rem 0.75rem; border: none; border-radius: 0.375rem; cursor: pointer; margin-right: 0.5rem;">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="delete-btn" onclick="deleteMedicine(<?= $med['id'] ?>)" style="background: #ef4444; color: white; padding: 0.5rem 0.75rem; border: none; border-radius: 0.375rem; cursor: pointer;">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p style="text-align: center; padding: 3rem; color: #9ca3af;">
                <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                No medicines added yet. Add your first medicine above!
            </p>
        <?php endif; ?>
    </div>

    <!-- Edit Medicine Modal -->
    <div id="editModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: white; border-radius: 0.75rem; padding: 2rem; max-width: 600px; width: 90%;">
            <h3 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem;">Edit Medicine</h3>
            
            <form id="editMedicineForm" method="POST" action="index.php?page=ajax" style="display: grid; gap: 1rem;">
                <input type="hidden" name="action" value="edit_medicine">
                <input type="hidden" name="medicine_id" id="editMedicineId">

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Medicine Name *</label>
                    <input type="text" name="name" id="editName" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Category *</label>
                    <select name="category_id" id="editCategory" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                        <option value="">Select Category</option>
                        <?php foreach ($categories ?? [] as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['id']) ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Description</label>
                    <textarea name="description" id="editDescription" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; height: 100px; resize: vertical;"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Price (৳) *</label>
                        <input type="number" name="price" id="editPrice" required step="0.01" min="0" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Stock Quantity *</label>
                        <input type="number" name="availability" id="editAvailability" required min="0" style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <button type="submit" style="background: #2563eb; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <button type="button" onclick="closeEditModal()" style="background: #e5e7eb; color: #374151; padding: 0.75rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer;">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editMedicine(id, name, categoryId, description, price, availability) {
    document.getElementById('editMedicineId').value = id;
    document.getElementById('editName').value = name;
    document.getElementById('editCategory').value = categoryId;
    document.getElementById('editDescription').value = description;
    document.getElementById('editPrice').value = price;
    document.getElementById('editAvailability').value = availability;
    document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function deleteMedicine(id) {
    if (confirm('Are you sure you want to delete this medicine?')) {
        const formData = new FormData();
        formData.append('action', 'delete_medicine');
        formData.append('medicine_id', id);
        
        fetch('index.php?page=ajax', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Medicine deleted successfully');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to delete'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting medicine');
        });
    }
}

// Handle form submission
document.getElementById('addMedicineForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('index.php?page=ajax', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Medicine added successfully');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to add medicine'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding medicine');
    });
});

document.getElementById('editMedicineForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('index.php?page=ajax', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Medicine updated successfully');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to update medicine'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating medicine');
    });
});

window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}
</script>

<?php require 'views/footer.php'; ?>

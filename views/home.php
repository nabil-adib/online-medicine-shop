<?php require 'views/header.php'; ?>

<div class="container">

    <!-- Search bar -->
    <section class="search-section">
        <h2>Find Your Medicine</h2>
        <div class="search-bar">
            <input type="text" id="search-q"
                   placeholder="Medicine name...">
            <input type="text" id="search-vendor"
                   placeholder="Vendor name...">
            <input type="text" id="search-genre"
                   placeholder="Category...">
            <button onclick="do_search()" class="btn btn-primary">Search</button>
        </div>
    </section>

    <!-- Categories -->
    <section class="section">
        <h2>Browse by Category</h2>
        <div class="category-grid">
            <?php foreach ($categories as $cat): ?>
                <a href="index.php?page=browse&category_id=<?= $cat['id'] ?>"
                   class="cat-card">
                    <div class="cat-icon">
                        <?= $cat['category_type'] === 'liquid' ? '&#128138;' : '&#128138;' ?>
                    </div>
                    <strong><?= htmlspecialchars($cat['name']) ?></strong>
                    <span class="cat-type"><?= htmlspecialchars($cat['category_type']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Medicine cards -->
    <section class="section">
        <h2>All Medicines</h2>
        <div class="medicine-grid" id="medicine-grid">
            <?php foreach ($medicines as $med): ?>
                <div class="med-card">
                    <?php if (!empty($med['medicine_image'])): ?>
                        <img src="<?= htmlspecialchars($med['medicine_image']) ?>"
                             alt="<?= htmlspecialchars($med['medicine_name']) ?>">
                    <?php else: ?>
                        <div class="med-img-placeholder">&#128138;</div>
                    <?php endif; ?>

                    <div class="med-body">
                        <h4><?= htmlspecialchars($med['medicine_name']) ?></h4>
                        <p class="med-vendor">by <?= htmlspecialchars($med['vendor_name']) ?></p>
                        <p class="med-category"><?= htmlspecialchars($med['category_name']) ?>
                            &bull; <?= htmlspecialchars($med['category_type']) ?></p>
                        <p class="med-price">&#2547; <?= number_format($med['medicine_price'], 2) ?></p>

                        <?php if ($med['availability'] && $med['medicine_stock'] > 0): ?>
                            <span class="badge badge-success">In Stock (<?= $med['medicine_stock'] ?>)</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Out of Stock</span>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'customer'): ?>
                            <a href="index.php?page=cart&action=add&medicine_id=<?= $med['id'] ?>"
                               class="btn btn-primary btn-block" style="margin-top:10px">
                               Add to Cart
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($medicines)): ?>
                <p class="no-result">No medicines found.</p>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php require 'views/footer.php'; ?>
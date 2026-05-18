<?php
require 'views/header.php';
?>

<!-- HERO SECTION -->
<section class="hero">
    <div class="hero-content">
        <h1>Your Health, Delivered Fast.</h1>
        <p>
            Browse certified medicines, filter by category & type,
            and get doorstep delivery.
        </p>

        <!-- SEARCH BAR -->
        <div class="search-container">
            <input
                type="text"
                id="searchInput"
                class="search-input"
                placeholder="Search medicines..."
                onkeyup="renderMedicines()"
            >
            <button class="search-btn" onclick="renderMedicines()">
                Search
            </button>
        </div>
    </div>
</section>

<!-- MAIN CONTAINER -->
<div class="container">
    <div class="main-layout">

        <!-- LEFT SIDEBAR FILTERS -->
        <aside class="filters-sidebar">
            <div class="filter-section">
                <h3>Categories</h3>
                <div id="categoryList" class="category-list"></div>
            </div>

            <div class="filter-section">
                <h3>Form Factor</h3>
                <label class="filter-checkbox">
                    <input type="checkbox" id="filterSolid" onchange="applyFilters()">
                    <span>💊 Solid (Tablets/Capsules)</span>
                </label>
                <label class="filter-checkbox">
                    <input type="checkbox" id="filterLiquid" onchange="applyFilters()">
                    <span>🧪 Liquid (Syrups/Solutions)</span>
                </label>
            </div>
        </aside>

        <!-- PRODUCTS SECTION -->
        <section class="products-section">
            <div class="products-header">
                <h2>All Medicines</h2>
                <span id="productCount" class="product-count">0 products</span>
            </div>
            <div id="productGrid" class="products-grid"></div>
        </section>

    </div>
</div>

<script>
let allMedicines = [];
let activeCategory = '';
let activeFilters = { solid: false, liquid: false };

function loadMedicines() {
    fetch('public/api/medicines.php')
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                console.log("API failed");
                return;
            }
            allMedicines = data.medicines;
            renderCategories();
            renderMedicines();
        })
        .catch(err => console.log(err));
}

function renderCategories() {
    let categories = [];
    allMedicines.forEach(m => {
        if (!categories.includes(m.category_name)) {
            categories.push(m.category_name);
        }
    });

    let html = '';
    categories.forEach(cat => {
        html += `
            <label class="filter-checkbox">
                <input type="radio" name="category" onclick="setCategory('${cat}')">
                <span>${cat}</span>
            </label>
        `;
    });
    html += `<button class="btn-primary" style="width:100%; margin-top:10px" onclick="clearCategory()">Clear Filter</button>`;
    document.getElementById('categoryList').innerHTML = html;
}

function setCategory(cat) {
    activeCategory = cat;
    renderMedicines();
}

function clearCategory() {
    activeCategory = '';
    renderMedicines();
}

function applyFilters() {
    activeFilters.solid = document.getElementById('filterSolid').checked;
    activeFilters.liquid = document.getElementById('filterLiquid').checked;
    renderMedicines();
}

function addToCart(medicineId) {
    const formData = new URLSearchParams();
    formData.append('medicine_id', medicineId);
    formData.append('quantity', 1);

    fetch('index.php?page=api_cart&action=add', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Item added to cart successfully!');
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Failed to add to cart');
    });
}

function renderMedicines() {
    let grid = document.getElementById('productGrid');
    let count = document.getElementById('productCount');
    let search = document.getElementById('searchInput').value.toLowerCase();

    let filtered = allMedicines.filter(med => {
        let matchSearch = med.name.toLowerCase().includes(search) || med.vendor_name.toLowerCase().includes(search);
        let matchCategory = activeCategory === '' || med.category_name === activeCategory;
        let matchType = true;
        
        if (activeFilters.solid || activeFilters.liquid) {
            matchType = false;
            if (activeFilters.solid && med.category_type === 'solid') matchType = true;
            if (activeFilters.liquid && med.category_type === 'liquid') matchType = true;
        }
        return matchSearch && matchCategory && matchType;
    });

    count.innerText = filtered.length + " products";

    if (filtered.length === 0) {
        grid.innerHTML = `<div class="no-results"><i class="fas fa-box-open"></i><p>No medicines found</p></div>`;
        return;
    }

    let html = '';
    filtered.forEach(med => {
        html += `
            <div class="product-card">
                <div class="product-icon">
                    <i class="fas fa-pills"></i>
                    <span class="product-type-badge">${med.category_type === 'solid' ? '💊 Tablet' : '🧴 Syrup'}</span>
                </div>
                <div class="product-category">${med.category_name}</div>
                <h3 class="product-name">${med.name}</h3>
                <div class="product-vendor">${med.vendor_name}</div>
                <div class="product-price">৳ ${med.price}</div>
                <div class="product-stock">Stock: ${med.availability}</div>
                <button class="add-to-cart-btn" onclick="addToCart(${med.id})">
                    <i class="fas fa-cart-plus"></i> Add To Cart
                </button>
            </div>
        `;
    });
    grid.innerHTML = html;
}

document.addEventListener('DOMContentLoaded', () => {
    loadMedicines();
});
</script>

<?php
require 'views/footer.php';
?>
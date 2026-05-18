<?php
// Include the header template
require 'views/header.php';
?>

<!-- HERO SECTION - Main banner area -->
<section class="hero">
    <div class="hero-content">
        <h1>Your Health, Delivered Fast.</h1>
        <p>
            Browse certified medicines, filter by category & type,
            and get doorstep delivery.
        </p>

        <!-- SEARCH BAR - Medicine search input field -->
        <div class="search-container">
            <input
                type="text"
                id="searchInput"
                class="search-input"
                placeholder="Search medicines by name..."
                onkeyup="searchMedicines()"
            >
            <button class="search-btn" onclick="searchMedicines()">
                Search
            </button>
        </div>
    </div>
</section>

<!-- MAIN CONTAINER - Content wrapper -->
<div class="container">
    <div class="main-layout">

        <!-- LEFT SIDEBAR FILTERS - Filtering options section -->
        <aside class="filters-sidebar">
            
            <!-- Vendor Name Filter -->
            <div class="filter-section">
                <h3>Vendor Name</h3>
                <input 
                    type="text" 
                    id="vendorFilter" 
                    class="search-input" 
                    placeholder="Filter by vendor..."
                    onkeyup="searchMedicines()"
                    style="width: 100%; padding: 0.5rem;"
                >
            </div>

            <!-- Categories Filter Section -->
            <div class="filter-section">
                <h3>Categories</h3>
                <div id="categoryList" class="category-list">Loading categories...</div>
            </div>

            <!-- Form Factor Filter (Solid/Liquid) -->
            <div class="filter-section">
                <h3>Form Factor</h3>
                <label class="filter-checkbox">
                    <input type="checkbox" id="filterSolid" onchange="searchMedicines()">
                    <span>💊 Solid (Tablets/Capsules)</span>
                </label>
                <label class="filter-checkbox">
                    <input type="checkbox" id="filterLiquid" onchange="searchMedicines()">
                    <span>🧪 Liquid (Syrups/Solutions)</span>
                </label>
            </div>
            
            <!-- Reset Filters Button -->
            <div class="filter-section">
                <button class="btn-primary" style="width:100%" onclick="resetFilters()">
                    Reset All Filters
                </button>
            </div>
        </aside>

        <!-- PRODUCTS SECTION - Medicine display area -->
        <section class="products-section">
            <div class="products-header">
                <h2>All Medicines</h2>
                <span id="productCount" class="product-count">0 products</span>
            </div>
            <div id="productGrid" class="products-grid">Loading medicines...</div>
        </section>

    </div>
</div>

<script>

let allMedicines = [];      // Store all fetched medicines
let activeCategory = '';     // Currently selected category filter
let categoriesList = [];     // Store unique categories extracted from medicines


// Load medicines using AJAX GET request with all active filters
function searchMedicines() {
    // Get filter values from DOM elements
    let search = document.getElementById('searchInput').value;
    let vendor = document.getElementById('vendorFilter').value;
    let genre = activeCategory;
    let type = '';
    
    // Determine form factor filter selection
    let solidChecked = document.getElementById('filterSolid').checked;
    let liquidChecked = document.getElementById('filterLiquid').checked;
    
    if (solidChecked && liquidChecked) {
        type = 'all';
    } else if (solidChecked) {
        type = 'solid';
    } else if (liquidChecked) {
        type = 'liquid';
    }
    
    // Build API URL with query parameters
    let url = 'public/api/search.php?';
    if (search) url += 'q=' + encodeURIComponent(search) + '&';
    if (vendor) url += 'vendor=' + encodeURIComponent(vendor) + '&';
    if (genre) url += 'genre=' + encodeURIComponent(genre) + '&';
    if (type) url += 'type=' + encodeURIComponent(type);
    
    // Remove trailing ampersand if exists
    if (url.endsWith('&')) {
        url = url.slice(0, -1);
    }
    
    // Show loading state while fetching
    document.getElementById('productGrid').innerHTML = '<div class="no-results">Loading medicines...</div>';
    
    // AJAX GET request to fetch medicines
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allMedicines = data.medicines;
                
                // Extract and render categories only once (if empty)
                if (categoriesList.length === 0) {
                    extractCategories();
                    renderCategories();
                }
                
                renderMedicines();  // Display medicines on page
            } else {
                document.getElementById('productGrid').innerHTML = '<div class="no-results">Failed to load medicines</div>';
            }
        })
        .catch(error => {
            console.log('Error:', error);
            document.getElementById('productGrid').innerHTML = '<div class="no-results">Error connecting to server</div>';
        });
}


// Extract unique categories from medicines array
function extractCategories() {
    categoriesList = [];
    for (let i = 0; i < allMedicines.length; i++) {
        let cat = allMedicines[i].category_name;
        if (cat && !categoriesList.includes(cat)) {
            categoriesList.push(cat);
        }
    }
}


// Render categories as radio buttons (called only once)
function renderCategories() {
    if (categoriesList.length === 0) {
        document.getElementById('categoryList').innerHTML = '<p>No categories</p>';
        return;
    }
    
    let html = '';
    for (let i = 0; i < categoriesList.length; i++) {
        html += `
            <label class="filter-checkbox">
                <input type="radio" name="category" onclick="setCategory('${categoriesList[i]}')">
                <span>${escapeHtml(categoriesList[i])}</span>
            </label>
        `;
    }
    html += `<button class="btn-primary" style="width:100%; margin-top:10px" onclick="clearCategory()">Clear Category</button>`;
    
    document.getElementById('categoryList').innerHTML = html;
}


// Set selected category and trigger search
function setCategory(cat) {
    activeCategory = cat;
    searchMedicines();
}

// Clear selected category
function clearCategory() {
    activeCategory = '';
    const radios = document.querySelectorAll('input[name="category"]');
    for (let i = 0; i < radios.length; i++) {
        radios[i].checked = false;
    }
    searchMedicines();
}


// Reset all filters to default state
function resetFilters() {
    // Reset category selection
    activeCategory = '';
    const radios = document.querySelectorAll('input[name="category"]');
    for (let i = 0; i < radios.length; i++) {
        radios[i].checked = false;
    }
    
    // Reset form factor checkboxes
    document.getElementById('filterSolid').checked = false;
    document.getElementById('filterLiquid').checked = false;
    
    // Reset search input fields
    document.getElementById('searchInput').value = '';
    document.getElementById('vendorFilter').value = '';
    
    // Reload medicines with no filters
    searchMedicines();
}


// Display medicines in product grid
function renderMedicines() {
    let grid = document.getElementById('productGrid');
    let count = document.getElementById('productCount');
    
    count.innerText = allMedicines.length + " products";
    
    if (allMedicines.length === 0) {
        grid.innerHTML = '<div class="no-results">No medicines found<br>Try adjusting your filters</div>';
        return;
    }
    
    let html = '';
    for (let i = 0; i < allMedicines.length; i++) {
        let med = allMedicines[i];
        
        html += `
            <div class="product-card">
                <div class="product-icon">
                    ${med.image_path && med.image_path !== '' ? 
                        `<img src="${escapeHtml(med.image_path)}" alt="${escapeHtml(med.name)}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 0.75rem;">` : 
                        `<div style="font-size: 3rem;">💊</div>`
                    }
                    <span class="product-type-badge">${med.category_type === 'solid' ? '💊 Tablet' : '🧴 Syrup'}</span>
                </div>
                <div class="product-category">${escapeHtml(med.category_name)}</div>
                <h3 class="product-name">${escapeHtml(med.name)}</h3>
                <div class="product-vendor">${escapeHtml(med.vendor_name)}</div>
                <div class="product-price">৳ ${parseFloat(med.price).toFixed(2)}</div>
                <div class="product-stock ${med.availability > 0 ? 'in-stock' : 'out-of-stock'}">
                    ${med.availability > 0 ? 'In Stock: ' + med.availability : 'Out of Stock'}
                </div>
                ${med.availability > 0 ? 
                    `<button class="add-to-cart-btn" onclick="addToCart(${med.id})">
                        Add To Cart
                    </button>` : 
                    `<button class="add-to-cart-btn" disabled style="background: #9ca3af; cursor: not-allowed;">
                        Out of Stock
                    </button>`
                }
            </div>
        `;
    }
    
    grid.innerHTML = html;
}


// Add medicine to cart via AJAX POST request
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
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to add to cart');
    });
}


// Escape HTML to prevent XSS attacks
function escapeHtml(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}


// Load medicines when page finishes loading
document.addEventListener('DOMContentLoaded', function() {
    searchMedicines();
});
</script>

<?php
// Include the footer template
require 'views/footer.php';
?>
<?php
require 'header.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'vendor') {
    header('Location: index.php?page=login');
    exit;
}
?>

<link rel="stylesheet" href="public/assets/vendor_orders.css">

<div class="vendor-orders-container">

    <div class="page-header">
        <h1><i class="fas fa-boxes"></i> My Orders</h1>
        <div class="header-stats">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-box"></i></div>
                <div class="stat-value">0</div>
                <div class="stat-label">Total Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-value">0</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-value">0</div>
                <div class="stat-label">Completed</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                <div class="stat-value">$0.00</div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>
    </div>
    
    <div class="filters-section">
        <div class="filter-group">
            <label>Filter by Status:</label>
            <select id="statusFilter" onchange="filterOrders()">
                <option value="">All Orders</option>
                <option value="pending">Pending</option>
                <option value="accepted">Accepted</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Sort by:</label>
            <select id="sortFilter" onchange="filterOrders()">
                <option value="recent">Most Recent</option>
                <option value="oldest">Oldest</option>
                <option value="amount-high">Highest Amount</option>
                <option value="amount-low">Lowest Amount</option>
            </select>
        </div>
    </div>
    
    <div class="orders-table-wrapper">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Items</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="ordersTableBody">
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>No orders yet</p>
                            <p style="font-size: 0.875rem;">Orders will appear here when customers place them</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="pagination" id="paginationContainer" style="display: none;">
        <button onclick="previousPage()">&laquo; Previous</button>
        <span id="pageInfo"></span>
        <button onclick="nextPage()">Next &raquo;</button>
    </div>
</div>

<div id="orderModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span>Order Details</span>
            <button class="modal-close" onclick="closeOrderModal()">&times;</button>
        </div>
        <div id="orderDetails"></div>
    </div>
</div>

<script>
    function filterOrders() {
        console.log('Filter orders');
    }
    
    function viewOrder(orderId) {
        console.log('View order:', orderId);
        document.getElementById('orderModal').classList.add('active');
    }
    
    function closeOrderModal() {
        document.getElementById('orderModal').classList.remove('active');
    }
    
    function updateOrderStatus(orderId, newStatus) {
        console.log('Update order status:', orderId, newStatus);
    }
    
    function previousPage() {
        console.log('Previous page');
    }
    
    function nextPage() {
        console.log('Next page');
    }
    
    window.onclick = function(event) {
        const modal = document.getElementById('orderModal');
        if (event.target === modal) {
            modal.classList.remove('active');
        }
    }
</script>

<?php require 'footer.php'; ?>

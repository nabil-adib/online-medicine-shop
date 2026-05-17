<?php
require 'header.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'vendor') {
    header('Location: index.php?page=login');
    exit;
}

$user_id = $_SESSION['user_id'];
?>

<link rel="stylesheet" href="public/assets/vendor_cart.css">

<div class="vendor-container">
    <div class="page-header">
        <h2><i class="fas fa-shopping-cart"></i> My Cart</h2>
        <div style="display: flex; gap: 1rem;">
            <div class="stat-box">
                <div class="stat-value" id="itemCount">0</div>
                <div class="stat-label">Items</div>
            </div>
        </div>
    </div>
    
    <div class="cart-content">
        <div class="cart-items" id="cartItemsList">
            <div class="empty-state">
                <i class="fas fa-shopping-cart"></i>
                <p>Your cart is empty</p>
                <p style="font-size: 0.875rem;">Start adding medicines to your cart!</p>
            </div>
        </div>
        
        <div class="summary-box">
            <div class="summary-title">Order Summary</div>
            <div class="summary-row">
                <span>Subtotal</span>
                <span id="summarySubtotal">$0.00</span>
            </div>
            <div class="summary-row">
                <span>Shipping</span>
                <span>Free</span>
            </div>
            <div class="summary-row">
                <span>Tax</span>
                <span id="summaryTax">$0.00</span>
            </div>
            <div class="summary-row total">
                <span>Total</span>
                <span id="summaryTotal">$0.00</span>
            </div>
            
            <button class="checkout-btn" id="checkoutBtn" onclick="proceedToCheckout()">
                Proceed to Checkout
            </button>
            <a href="index.php?page=home" class="continue-shopping">Continue Shopping</a>
        </div>
    </div>
</div>

<script>
    function loadCart() {
        updateCartUI();
    }
    
    function updateCart(cartId) {
        console.log('Update cart item:', cartId);
    }
    
    function removeCart(cartId) {
        console.log('Remove cart item:', cartId);
        
    }
    
    function proceedToCheckout() {
        const total = document.getElementById('summaryTotal').textContent;
        if (total === '$0.00') {
            alert('Your cart is empty!');
            return;
        }
        window.location.href = 'index.php?action=checkout';
    }
    
    function updateCartUI() {

    }
    
    loadCart();
</script>

<?php require 'footer.php'; ?>

<?php
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header('Location: index.php?page=login');
    exit;
}
$cart_items = get_cart_items($conn, $_SESSION['user_id']);
$user = get_user_by_id($conn, $_SESSION['user_id']);
$order_total = 0;
foreach ($cart_items as $item) {
    $order_total += ($item['medicine_price'] * $item['cart_quantity']);
}
?>
<link rel="stylesheet" href="public/assets/order_css/checkout.css">
<?php require 'views/header.php'; ?>

<div class="checkout-container">
    <div class="checkout-section">
        
        <div class="checkout-header">
            <h2>Complete Order</h2>
            <a href="index.php?page=cart" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Cart
            </a>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=checkout" class="checkout-form">
            
            <div class="form-column">
                <h3>Shipping Information</h3>
                <div class="form-fields">
                    <div class="form-field">
                        <label>Full Name</label>
                        <input type="text" class="readonly-input" value="<?= htmlspecialchars($user['name'] ?? '') ?>" readonly>
                    </div>
                    <div class="form-field">
                        <label>Email</label>
                        <input type="text" class="readonly-input" value="<?= htmlspecialchars($user['email'] ?? '') ?>" readonly>
                    </div>
                    <div class="form-field">
                        <label>Phone</label>
                        <input type="text" class="readonly-input" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" readonly>
                    </div>
                    <div class="form-field">
                        <label>Shipping Address</label>
                        <textarea name="shipping_address" class="address-box" rows="3" required><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                    </div>
                </div>

                <h3>Payment Method</h3>
                <div class="payment-methods-grid">
                    <label class="payment-option-card">
                        <input type="radio" name="payment_method" value="Credit Card" required>
                        <i class="fas fa-credit-card"></i>
                        <span>Credit Card</span>
                    </label>
                    <label class="payment-option-card">
                        <input type="radio" name="payment_method" value="bKash">
                        <i class="fas fa-mobile-alt"></i>
                        <span>bKash</span>
                    </label>
                    <label class="payment-option-card">
                        <input type="radio" name="payment_method" value="Bank Transfer">
                        <i class="fas fa-university"></i>
                        <span>Bank Transfer</span>
                    </label>
                    <label class="payment-option-card">
                        <input type="radio" name="payment_method" value="Cash on Delivery" checked>
                        <i class="fas fa-truck"></i>
                        <span>Cash on Delivery</span>
                    </label>
                </div>
            </div>

            <div class="order-summary-column">
                <div class="order-summary">
                    <h3>Order Summary</h3>
                    
                    <div class="order-items">
                        <?php foreach ($cart_items as $item): 
                            $subtotal = $item['medicine_price'] * $item['cart_quantity'];
                        ?>
                        <div class="order-item">
                            <span><?= $item['cart_quantity'] ?>x <?= htmlspecialchars($item['medicine_name']) ?></span>
                            <span class="item-price">৳<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="summary-details">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>৳<?= number_format($order_total, 2) ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Delivery Fee</span>
                            <span>Free</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total to Pay</span>
                            <span class="total-amount">৳<?= number_format($order_total, 2) ?></span>
                        </div>
                    </div>
                    
                    <button type="submit" class="confirm-btn">
                        <i class="fas fa-check-circle"></i> Confirm Purchase
                    </button>
                </div>
            </div>
            
        </form>
    </div>
</div>

<?php require 'views/footer.php'; ?>
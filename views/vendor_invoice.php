<?php
require 'header.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'vendor') {
    header('Location: index.php?page=login');
    exit;
}
?>

<link rel="stylesheet" href="public/assets/vendor_invoice.css">

<div class="invoice-container">
    <form method="POST" action="controllers/VendorCartController.php?action=payment">
        <div class="invoice-wrapper">
            <div class="invoice-header">
                <div class="invoice-logo">
                    <i class="fas fa-hand-holding-medical"></i>
                    PharmaQuick
                </div>
                <div class="invoice-status">
                    <i class="fas fa-hourglass-half"></i> PENDING APPROVAL
                </div>
            </div>
            
            <div class="invoice-section">
                <div class="section-content">
                    <div class="info-block">
                        <div class="section-title">From</div>
                        <div class="info-label">Vendor</div>
                        <div class="info-value"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Vendor') ?></div>
                    </div>
                    <div class="info-block">
                        <div class="section-title">Invoice Details</div>
                        <div class="info-label">Invoice Date</div>
                        <div class="info-value"><?= date('M d, Y') ?></div>
                    </div>
                </div>
            </div>
            
            <div class="invoice-section">
                <div class="section-title">Shipping Address</div>
                <div class="form-group">
                    <label>Enter Shipping Address</label>
                    <textarea name="shipping_address" rows="3" required placeholder="Street, City, State, Postal Code..."></textarea>
                </div>
            </div>
            
            <div class="invoice-section">
                <div class="section-title">Order Items</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Medicine Name</th>
                            <th>Vendor</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody id="cartItems">
                        <tr>
                            <td colspan="5" style="text-align: center; color: #9ca3af; padding: 2rem;">
                                <i class="fas fa-inbox" style="font-size: 2rem;"></i>
                                <p>No items in cart</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="summary-section">
                <div class="summary-box">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="subtotalAmount">$0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <div class="summary-row">
                        <span>Tax (10%)</span>
                        <span id="taxAmount">$0.00</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total Amount</span>
                        <span id="totalAmount">$0.00</span>
                    </div>
                    <input type="hidden" name="total_amount" id="totalAmountInput" value="0">
                </div>
            </div>
            
            <div class="payment-methods">
                <h3><i class="fas fa-credit-card"></i> Select Payment Method</h3>
                <div class="payment-grid">
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="Credit Card" required>
                        <label style="cursor: pointer;">
                            <i class="fas fa-credit-card"></i>
                            <span>Credit Card</span>
                        </label>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="bKash">
                        <label style="cursor: pointer;">
                            <i class="fas fa-mobile-alt"></i>
                            <span>bKash</span>
                        </label>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="Nagad">
                        <label style="cursor: pointer;">
                            <i class="fas fa-mobile-alt"></i>
                            <span>Nagad</span>
                        </label>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="Bank Transfer">
                        <label style="cursor: pointer;">
                            <i class="fas fa-university"></i>
                            <span>Bank Transfer</span>
                        </label>
                    </label>
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="Cash on Delivery" checked>
                        <label style="cursor: pointer;">
                            <i class="fas fa-truck"></i>
                            <span>Cash on Delivery</span>
                        </label>
                    </label>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="index.php?page=home" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check-circle"></i> Confirm & Pay
                </button>
            </div>
        </div>
    </form>
</div>

<?php require 'footer.php'; ?>

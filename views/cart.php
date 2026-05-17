<?php
if (!isset($_SESSION['user_id'])) { 
    header('Location: index.php?page=login'); 
    exit; 
}
$cart_items = get_cart_items($conn, $_SESSION['user_id']);
$order_total = 0;
?>
<link rel="stylesheet" href="public/assets/order_css/cart.css">
<?php require 'views/header.php'; ?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>Shopping Basket</h2>
        <a href="index.php?page=home" style="color: #2563eb; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-arrow-left"></i> Continue Shopping
        </a>
    </div>
    
    <?php if (isset($_GET['order_id'])): ?>
    <div class="alert alert-success" style="background: #d1fae5; color: #065f46; border: 1px solid #10b981; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; text-align: center;">
        <i class="fas fa-check-circle" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
        <h3 style="font-weight: bold; margin: 0.5rem 0;">Order Confirmed!</h3>
        <p>Your order #ORD-<?= htmlspecialchars($_GET['order_id']) ?> has been placed successfully.</p>
        <a href="index.php?page=orders" style="display: inline-block; margin-top: 0.75rem; background: #059669; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; text-decoration: none;">View My Orders</a>
    </div>
    <?php endif; ?>
    
    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 2rem;">
        <div>
            <table id="cartTable">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Vendor</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="cartItemsList">
                    <?php if (empty($cart_items)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 3rem; color: #9ca3af;">
                                <i class="fas fa-shopping-cart" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                                Your basket is empty.
                                <br>
                                <a href="index.php?page=home" style="display: inline-block; margin-top: 1rem; color: #2563eb;">Start Shopping →</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cart_items as $item): 
                            $subtotal = $item['medicine_price'] * $item['cart_quantity'];
                            $order_total += $subtotal;
                        ?>
                        <tr id="card_<?= $item['cart_id'] ?>">
                            <td>
                                <strong><?= htmlspecialchars($item['medicine_name']) ?></strong>
                                <div style="font-size: 0.75rem; color: #6b7280;" id="price_<?= $item['cart_id'] ?>" data-unit-price="<?= $item['medicine_price'] ?>">Unit: ৳<?= number_format($item['medicine_price'], 2) ?></div>
                            </td>
                            <td><?= htmlspecialchars($item['vendor_name']) ?></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <button type="button" onclick="updateCartQuantity(<?= $item['cart_id'] ?>, 'decr')" style="width: 30px; height: 30px; border: 1px solid #cbd5e1; background: white; border-radius: 6px; cursor: pointer;">−</button>
                                    <input type="number" id="qty_<?= $item['cart_id'] ?>" value="<?= $item['cart_quantity'] ?>" min="1" max="<?= $item['medicine_stock'] ?>" class="qty-input" style="width: 60px;" readonly>
                                    <button type="button" onclick="updateCartQuantity(<?= $item['cart_id'] ?>, 'incr')" style="width: 30px; height: 30px; border: 1px solid #cbd5e1; background: white; border-radius: 6px; cursor: pointer;">+</button>
                                </div>
                            </td>
                            <td>৳<?= number_format($item['medicine_price'], 2) ?></td>
                            <td class="item-subtotal" id="subtotal_<?= $item['cart_id'] ?>">৳<?= number_format($subtotal, 2) ?></td>
                            <td>
                                <button type="button" onclick="removeCartItem(<?= $item['cart_id'] ?>)" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 1rem;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="summary-box" style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); height: fit-content; position: sticky; top: 100px;">
            <div class="summary-title" style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem; color: #1f2937;">Order Summary</div>
            <div class="summary-row" style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.875rem; color: #6b7280;">
                <span>Subtotal</span>
                <span id="summarySubtotal">৳<?= number_format($order_total, 2) ?></span>
            </div>
            <div class="summary-row" style="display: flex; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.875rem; color: #6b7280;">
                <span>Delivery Fee</span>
                <span>Free</span>
            </div>
            <div class="summary-row total" style="display: flex; justify-content: space-between; border-top: 1px solid #e5e7eb; padding-top: 1rem; margin-top: 0.5rem; font-weight: bold; color: #1f2937; font-size: 1.125rem;">
                <span>Total</span>
                <span id="summaryTotal">৳<?= number_format($order_total, 2) ?></span>
            </div>
            
            <div id="checkoutBtnWrapper" class="cart-total-container" style="margin-top: 1.5rem;">
                <?php if (!empty($cart_items)): ?>
                    <a href="index.php?page=checkout" style="display: inline-block; background: #2563eb; color: white; padding: 0.75rem 1.5rem; border-radius: 0.75rem; text-decoration: none; font-weight: bold; text-align: center; width: 100%;">
                        Proceed to Checkout
                    </a>
                <?php else: ?>
                    <button disabled style="background: #d1d5db; color: #6b7280; padding: 0.75rem 1.5rem; border-radius: 0.75rem; border: none; cursor: not-allowed; width: 100%;">Proceed to Checkout</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="public/js/app.js"></script>

<?php require 'views/footer.php'; ?>

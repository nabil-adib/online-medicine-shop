<?php
if (!isset($_SESSION['user_id'])) { 
    header('Location: index.php?page=login'); 
    exit; 
}
$cart_items = get_cart_items($conn, $_SESSION['user_id']);
$order_total = 0;
?>
<section class="py-12 px-4 max-w-5xl mx-auto font-sans text-gray-900">
    <h2 class="text-2xl font-bold mb-8">Shopping Basket</h2>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-4" id="cartItemsList">
            <?php if (empty($cart_items)): ?>
                <div class="bg-white p-6 rounded-2xl shadow-sm border flex items-center justify-center h-48 text-gray-400 italic">
                    Your basket is empty. Start shopping!
                </div>
            <?php else: ?>
                <?php foreach ($cart_items as $item): 
                    $subtotal = $item['medicine_price'] * $item['cart_quantity'];
                    $order_total += $subtotal;
                ?>
                <div class="bg-white p-4 rounded-2xl shadow-sm border flex items-center gap-4 transition hover:shadow-md" id="card_<?= $item['cart_id'] ?>">
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 shrink-0">
                        <i class="fas fa-pills text-xl"></i>
                    </div>
                    
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900" id="price_<?= $item['cart_id'] ?>" data-unit-price="<?= $item['medicine_price'] ?>"><?= htmlspecialchars($item['medicine_name']) ?></h4>
                        <p class="text-xs text-gray-400">By <?= htmlspecialchars($item['vendor_name']) ?></p>
                    </div>
                    
                    <div class="flex items-center gap-3 border border-gray-200 rounded-full px-3 py-1 bg-white">
                        <button type="button" onclick="var input = document.getElementById('qty_<?= $item['cart_id'] ?>'); var val = parseInt(input.value); if(val > 1) { input.value = val - 1; updateCart(<?= $item['cart_id'] ?>); } else { removeCart(<?= $item['cart_id'] ?>); }" class="text-gray-500 hover:text-black font-bold focus:outline-none">&minus;</button>
                        
                        <input type="number" id="qty_<?= $item['cart_id'] ?>" value="<?= $item['cart_quantity'] ?>" min="1" max="<?= $item['medicine_stock'] ?>" class="font-bold text-sm w-8 text-center bg-transparent border-none focus:outline-none text-gray-700" readonly>
                        
                        <button type="button" onclick="var input = document.getElementById('qty_<?= $item['cart_id'] ?>'); var val = parseInt(input.value); if(val < parseInt(input.getAttribute('max'))) { input.value = val + 1; updateCart(<?= $item['cart_id'] ?>); } else { alert('Cannot exceed available stock!'); }" class="text-gray-500 hover:text-black font-bold focus:outline-none">&plus;</button>
                    </div>
                    
                    <div class="w-20 text-right">
                        <span class="font-bold text-blue-600 item-subtotal" id="subtotal_<?= $item['cart_id'] ?>">$<?= number_format($subtotal, 2) ?></span>
                    </div>
                    
                    <button type="button" onclick="removeCart(<?= $item['cart_id'] ?>)" class="text-red-400 hover:text-red-600 px-2 transition">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="bg-white p-8 rounded-2xl shadow-sm border h-fit sticky top-24">
            <h3 class="font-bold text-lg mb-6">Summary</h3>
            <div class="space-y-4 mb-6">
                <div class="flex justify-between text-sm text-gray-600"><span>Subtotal</span><span id="summarySubtotal">$<?= number_format($order_total, 2) ?></span></div>
                <div class="flex justify-between text-sm text-gray-600"><span>Tax</span><span>$2.00</span></div>
                <div class="flex justify-between font-bold text-xl pt-4 border-t border-gray-100">
                    <span>Total</span>
                    <span class="text-gray-950" id="summaryTotal">$<?= number_format($order_total > 0 ? $order_total + 2 : 0, 2) ?></span>
                </div>
            </div>
            
            <div id="checkoutBtnWrapper">
                <?php if (!empty($cart_items)): ?>
                    <a href="index.php?page=checkout" id="checkoutBtn" class="block w-full text-center bg-blue-600 text-white py-3 rounded-xl font-bold text-sm hover:bg-blue-700 transition shadow-md">Proceed to Checkout</a>
                <?php else: ?>
                    <button disabled class="w-full bg-gray-200 text-gray-400 py-3 rounded-xl font-bold text-sm cursor-not-allowed">Proceed to Checkout</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<section class="py-12 px-4 max-w-5xl mx-auto font-sans text-gray-900 relative">
    <div class="bg-white p-8 md:p-10 rounded-3xl shadow-sm border">
        
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Complete Order</h2>
            <div class="flex gap-2">
                <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
                <div class="w-3 h-3 bg-gray-200 rounded-full"></div>
                <div class="w-3 h-3 bg-gray-200 rounded-full"></div>
            </div>
        </div>

        <?php if (!empty($error)): ?>
            <div class="text-red-500 mb-4 font-bold text-sm text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=checkout" class="grid grid-cols-1 md:grid-cols-2 gap-10">
            
            <div>
                <h3 class="font-bold mb-4 uppercase text-xs text-gray-400 tracking-widest">Shipping Information</h3>
                <div class="space-y-4 mb-8">
                    <div>
                        <label class="text-xs block mb-1 text-gray-600">Full Name</label>
                        <input type="text" class="w-full border border-gray-200 p-3 rounded-lg focus:outline-none focus:border-blue-500" value="Current User" readonly>
                    </div>
                    <div>
                        <label class="text-xs block mb-1 text-gray-600">Address</label>
                        <textarea name="shipping_address" class="w-full border border-gray-200 p-3 rounded-lg focus:outline-none focus:border-blue-500" rows="3" required><?= htmlspecialchars($_SESSION['user_address'] ?? '') ?></textarea>
                    </div>
                </div>

                <h3 class="font-bold mb-4 uppercase text-xs text-gray-400 tracking-widest">Payment Method</h3>
                <div class="grid grid-cols-2 gap-3">
                    <label class="border p-3 rounded-xl cursor-pointer hover:border-blue-500 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 flex items-center text-sm">
                        <input type="radio" name="payment_method" value="Credit Card" class="hidden" required>
                        <i class="fas fa-credit-card mr-2 text-blue-600"></i> Card
                    </label>
                    <label class="border p-3 rounded-xl cursor-pointer hover:border-blue-500 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 flex items-center text-sm">
                        <input type="radio" name="payment_method" value="bKash" class="hidden">
                        <i class="fas fa-mobile-alt mr-2 text-blue-600"></i> bKash
                    </label>
                    <label class="border p-3 rounded-xl cursor-pointer hover:border-blue-500 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 flex items-center text-sm">
                        <input type="radio" name="payment_method" value="Bank Transfer" class="hidden">
                        <i class="fas fa-university mr-2 text-blue-600"></i> Bank
                    </label>
                    <label class="border p-3 rounded-xl cursor-pointer hover:border-blue-500 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 flex items-center text-sm">
                        <input type="radio" name="payment_method" value="Cash on Delivery" class="hidden" checked>
                        <i class="fas fa-truck mr-2 text-blue-600"></i> COD
                    </label>
                </div>
            </div>

            <div>
                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                    <h3 class="font-bold mb-6 text-center text-gray-800">Invoice Summary</h3>
                    
                    <div class="space-y-4 mb-6 border-b border-gray-200 pb-6">
                        <?php 
                        $order_total = 0; 
                        foreach ($cart_items as $item): 
                            $subtotal = $item['medicine_price'] * $item['cart_quantity'];
                            $order_total += $subtotal;
                        ?>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span><?= $item['cart_quantity'] ?>x <?= htmlspecialchars($item['medicine_name']) ?></span>
                            <span class="font-medium text-gray-900">$<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm text-gray-600"><span>Subtotal</span><span>$<?= number_format($order_total, 2) ?></span></div>
                        <div class="flex justify-between text-sm text-gray-600"><span>Shipping</span><span>Free</span></div>
                        <div class="flex justify-between font-bold text-lg pt-4 text-gray-900">
                            <span>Total to Pay</span>
                            <span>$<?= number_format($order_total, 2) ?></span>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-[#10b981] text-white py-3 rounded-lg font-bold mt-8 shadow-md hover:bg-[#059669] transition">
                        Confirm Purchase
                    </button>
                </div>
            </div>
            
        </form>
    </div>

    <?php if (isset($_GET['order_id'])): ?>
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl p-10 max-w-sm w-full text-center shadow-2xl">
            <div class="w-20 h-20 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl">
                <i class="fas fa-check"></i>
            </div>
            <h2 class="text-2xl font-bold mb-2">Order Confirmed!</h2>
            <p class="text-gray-500 mb-8 text-sm leading-relaxed">
                Your order #ORD-<?= htmlspecialchars($_GET['order_id']) ?> has been placed successfully and is pending admin approval.
            </p>
            <a href="index.php?page=cart" class="block w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition">
                Back to Home
            </a>
        </div>
    </div>
    <?php endif; ?>
</section>
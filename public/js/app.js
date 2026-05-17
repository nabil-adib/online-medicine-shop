function addToCart(medicineId, quantity = 1) {
    const formData = new URLSearchParams();
    formData.append('medicine_id', medicineId);
    formData.append('quantity', quantity);

    fetch('index.php?page=api_cart&action=add', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload(); // Reload to show updated cart
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(err => console.error('Error:', err));
}

function updateCartQuantity(cartId, action) {
    const inputEl = document.getElementById('qty_' + cartId);
    if (!inputEl) return;
    
    let newQty = parseInt(inputEl.value);
    if (action === 'incr') {
        const max = parseInt(inputEl.getAttribute('max'));
        if (newQty < max) newQty++;
        else {
            alert('Cannot exceed available stock!');
            return;
        }
    } else if (action === 'decr') {
        if (newQty > 1) newQty--;
        else {
            removeCartItem(cartId);
            return;
        }
    }
    
    inputEl.value = newQty;
    
    const formData = new URLSearchParams();
    formData.append('cart_id', cartId);
    formData.append('quantity', newQty);

    fetch('index.php?page=api_cart&action=update', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const priceEl = document.getElementById('price_' + cartId);
            const subtotalEl = document.getElementById('subtotal_' + cartId);
            
            if (priceEl && subtotalEl) {
                const unitPrice = parseFloat(priceEl.getAttribute('data-unit-price'));
                const newSubtotal = unitPrice * newQty;
                subtotalEl.innerText = '৳' + newSubtotal.toFixed(2);
            }
            recalculateCartSummary();
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(err => console.error('Error:', err));
}

function removeCartItem(cartId) {
    if (!confirm("Remove this item from cart?")) return;

    const formData = new URLSearchParams();
    formData.append('cart_id', cartId);

    fetch('index.php?page=api_cart&action=remove', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const row = document.getElementById('card_' + cartId);
            if (row) row.remove();
            recalculateCartSummary();
            
            // Check if table is empty
            const tbody = document.getElementById('cartItemsList');
            if (tbody && tbody.children.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 3rem; color: #9ca3af;">
                            <i class="fas fa-shopping-cart" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                            Your basket is empty.
                            <br>
                            <a href="index.php?page=home" style="display: inline-block; margin-top: 1rem; color: #2563eb;">Start Shopping →</a>
                        </td>
                    </tr>
                `;
            }
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(err => console.error('Error:', err));
}

function recalculateCartSummary() {
    const itemSubtotals = document.querySelectorAll('.item-subtotal');
    let dynamicSubtotal = 0;

    itemSubtotals.forEach(el => {
        const raw = el.innerText.replace('৳', '').trim();
        const parsed = parseFloat(raw);
        if (!isNaN(parsed)) dynamicSubtotal += parsed;
    });

    const summarySubtotalEl = document.getElementById('summarySubtotal');
    const summaryTotalEl = document.getElementById('summaryTotal');
    const checkoutWrapper = document.getElementById('checkoutBtnWrapper');

    if (!summarySubtotalEl || !summaryTotalEl) return;

    if (dynamicSubtotal === 0) {
        summarySubtotalEl.innerText = '৳0.00';
        summaryTotalEl.innerText = '৳0.00';
        if (checkoutWrapper) {
            checkoutWrapper.innerHTML = `<button disabled style="background: #d1d5db; color: #6b7280; padding: 0.75rem 1.5rem; border-radius: 0.75rem; border: none; cursor: not-allowed; width: 100%;">Proceed to Checkout</button>`;
        }
    } else {
        summarySubtotalEl.innerText = '৳' + dynamicSubtotal.toFixed(2);
        summaryTotalEl.innerText = '৳' + dynamicSubtotal.toFixed(2);
    }
}
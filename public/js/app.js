function addToCart(medicineId, quantity) {
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
        } else {
            alert("Error: " + data.message);
        }
    });
}

function updateCart(cartId) {
    const inputEl = document.getElementById('qty_' + cartId);
    if (!inputEl) { console.error('qty input not found for cartId:', cartId); return; }

    const newQty = parseInt(inputEl.value);
    console.log('[updateCart] cartId:', cartId, '| newQty:', newQty);

    const formData = new URLSearchParams();
    formData.append('cart_id', cartId);
    formData.append('quantity', newQty);

    fetch('index.php?page=api_cart&action=update', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('[updateCart] server response:', data);

        if (data.success) {
            const priceEl    = document.getElementById('price_' + cartId);
            const subtotalEl = document.getElementById('subtotal_' + cartId);

            console.log('[updateCart] priceEl:', priceEl, '| subtotalEl:', subtotalEl);

            if (priceEl && subtotalEl) {
                const unitPrice   = parseFloat(priceEl.getAttribute('data-unit-price'));
                const newSubtotal = unitPrice * newQty;
                console.log('[updateCart] unitPrice:', unitPrice, '| newSubtotal:', newSubtotal);
                subtotalEl.innerText = '$' + newSubtotal.toFixed(2);
            } else {
                console.error('[updateCart] Could not find price or subtotal element for cartId:', cartId);
            }

            recalculateCartSummary();
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(err => console.error('[updateCart] fetch error:', err));
}

function removeCart(cartId) {
    if (!confirm("Remove this item?")) return;

    const formData = new URLSearchParams();
    formData.append('cart_id', cartId);

    fetch('index.php?page=api_cart&action=remove', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('[removeCart] server response:', data);

        if (data.success) {
            const card = document.getElementById('card_' + cartId);
            if (card) card.remove();
            recalculateCartSummary();
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(err => console.error('[removeCart] fetch error:', err));
}

function recalculateCartSummary() {
    const itemSubtotals = document.querySelectorAll('.item-subtotal');
    let dynamicSubtotal = 0;

    itemSubtotals.forEach(el => {
        const raw    = el.innerText.replace('$', '').trim();
        const parsed = parseFloat(raw);
        console.log('[recalculate] item subtotal raw:', raw, '| parsed:', parsed);
        if (!isNaN(parsed)) dynamicSubtotal += parsed;
    });

    console.log('[recalculate] dynamicSubtotal:', dynamicSubtotal);

    const summarySubtotalEl = document.getElementById('summarySubtotal');
    const summaryTotalEl    = document.getElementById('summaryTotal');
    const containerList     = document.getElementById('cartItemsList');
    const checkoutWrapper   = document.getElementById('checkoutBtnWrapper');

    console.log('[recalculate] summarySubtotalEl:', summarySubtotalEl, '| summaryTotalEl:', summaryTotalEl);

    if (!summarySubtotalEl || !summaryTotalEl) {
        console.error('[recalculate] Summary elements not found in DOM!');
        return;
    }

    if (dynamicSubtotal === 0) {
        if (containerList) {
            containerList.innerHTML = `
                <div class="bg-white p-6 rounded-2xl shadow-sm border flex items-center justify-center h-48 text-gray-400 italic">
                    Your basket is empty. Start shopping!
                </div>
            `;
        }

        summarySubtotalEl.innerText = '$0.00';
        summaryTotalEl.innerText    = '$0.00';

        if (checkoutWrapper) {
            checkoutWrapper.innerHTML = `<button disabled class="w-full bg-gray-200 text-gray-400 py-3 rounded-xl font-bold text-sm cursor-not-allowed">Proceed to Checkout</button>`;
        }
    } else {
        summarySubtotalEl.innerText = '$' + dynamicSubtotal.toFixed(2);
        summaryTotalEl.innerText    = '$' + (dynamicSubtotal + 2.00).toFixed(2);
        console.log('[recalculate] Updated → subtotal:', dynamicSubtotal.toFixed(2), '| total:', (dynamicSubtotal + 2.00).toFixed(2));
    }
}
// This file is for additional AJAX functionality
// The main search functionality is already in home.php

function addToCart(medicineId) {
    fetch('api/add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ medicine_id: medicineId, quantity: 1 })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Added to cart successfully!');
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to add to cart');
        });
}

// Utility function for form validation
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePhone(phone) {
    const re = /^\d{7,15}$/;
    return re.test(phone);
}

function validatePassword(password) {
    return password.length >= 8;
}
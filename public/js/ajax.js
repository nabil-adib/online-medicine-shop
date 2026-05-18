// This file is for additional AJAX functionality
// The main search functionality is already in home.php

// Sends an asynchronous request to add a medicine to the shopping cart
function addToCart(medicineId) {
    // Use Fetch API to send POST request to server endpoint
    fetch('api/add_to_cart.php', {
        method: 'POST',  // HTTP method for creating/updating data
        headers: {
            'Content-Type': 'application/json',  // Indicates JSON payload
        },
        body: JSON.stringify({
            medicine_id: medicineId,  // ID of medicine being added
            quantity: 1               // Default quantity set to 1
        })
    })
        // Parse JSON response from server
        .then(response => response.json())
        // Handle the response data
        .then(data => {
            if (data.success) {
                alert('Added to cart successfully!');  // Success confirmation
            } else {
                alert('Error: ' + data.message);      // Display error from server
            }
        })
        // Handle network or parsing errors
        .catch(error => {
            console.error('Error:', error);  // Log error to browser console
            alert('Failed to add to cart');  // User-friendly error message
        });
}

// Utility function for form validation
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;  // Basic email regex pattern
    return re.test(email);
}

function validatePhone(phone) {
    const re = /^\d{11}$/;  // Matches exactly 11 numeric digits
    return re.test(phone);
}

function validatePassword(password) {
    return password.length >= 8;  // Minimum 8 characters for security
}

function validateName(name) {
    return !/\d/.test(name);  // Returns false if any digit is found
}
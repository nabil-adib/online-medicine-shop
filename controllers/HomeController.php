<?php

// Home page controller - loads and displays main store content
function home_ctrl($conn) {
    // Fetch all product categories for navigation/filtering
    $categories = get_all_categories($conn);
    
    // Fetch all medicines to display on homepage
    $medicines = get_all_medicines($conn);
    
    // Load the home view which displays categories and medicines
    require 'views/home.php';
}
?>
<?php

// Browse page controller - displays medicines filtered by category
function browse_ctrl($conn) {
    // Fetch all categories for the filter sidebar/navigation
    $categories  = get_all_categories($conn);
    
    // Get category ID from URL parameter, default to 0 (show all)
    $category_id = intval($_GET['category_id'] ?? 0);
 
    // Fetch medicines based on category filter
    if ($category_id > 0) {
        // Show only medicines from selected category
        $medicines = get_medicines_by_category($conn, $category_id);
    } else {
        // Show all medicines (no filter applied)
        $medicines = get_all_medicines($conn);
    }
 
    // Load the browse view with categories and filtered medicines
    require 'views/browse.php';
}
?>
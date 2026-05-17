<?php
function browse_ctrl($conn) {
    $categories  = get_all_categories($conn);
    $category_id = intval($_GET['category_id'] ?? 0);
 
    if ($category_id > 0) {
        $medicines = get_medicines_by_category($conn, $category_id);
    } else {
        $medicines = get_all_medicines($conn);
    }
 
    require 'views/browse.php';
}
?>
 
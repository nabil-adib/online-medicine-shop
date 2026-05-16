<?php
function home_ctrl($conn) {
    $categories = get_all_categories($conn);
    $medicines = get_all_medicines($conn);
    require 'views/home.php';
}
?>
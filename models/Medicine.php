<?php
function get_all_categories($conn) {
    $result = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");
    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
    return $categories;
}

function get_all_medicines($conn) {
    $stmt = mysqli_prepare($conn, "SELECT m.*, c.name as category_name, c.category_type 
                                   FROM medicines m 
                                   JOIN categories c ON m.category_id = c.id 
                                   ORDER BY m.id DESC");
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $medicines = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $medicines[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $medicines;
}

function search_medicines($conn, $search = '', $vendor = '', $genre = '', $type = '') {
    $sql = "SELECT m.*, c.name as category_name, c.category_type 
            FROM medicines m 
            JOIN categories c ON m.category_id = c.id 
            WHERE 1=1";
    $params = [];
    $types = "";
    
    if (!empty($search)) {
        $sql .= " AND m.name LIKE ?";
        $params[] = "%$search%";
        $types .= "s";
    }
    
    if (!empty($vendor)) {
        $sql .= " AND m.vendor_name LIKE ?";
        $params[] = "%$vendor%";
        $types .= "s";
    }
    
    if (!empty($genre)) {
        $sql .= " AND c.name LIKE ?";
        $params[] = "%$genre%";
        $types .= "s";
    }
    
    if (!empty($type) && $type !== 'all') {
        $sql .= " AND c.category_type = ?";
        $params[] = $type;
        $types .= "s";
    }
    
    $sql .= " ORDER BY m.id DESC";
    
    $stmt = mysqli_prepare($conn, $sql);
    if (!empty($params)) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $medicines = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $medicines[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $medicines;
}
?>
<?php

// =========================
// MEDICINE MANAGEMENT
// =========================


// TOTAL MEDICINES
// Returns the total count of all medicines in the database
function get_total_medicines($conn)
{
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM medicines");
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row['total'];
}



// GET ALL MEDICINES (with category join)
// Retrieves all medicines with their associated category name and type
function get_all_medicines($conn)
{
    $stmt = mysqli_prepare(
        $conn,
        "SELECT m.*, c.name AS category_name, c.category_type
         FROM medicines m
         JOIN categories c ON m.category_id = c.id
         ORDER BY m.id ASC"
    );

    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $data = [];

    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }

    mysqli_stmt_close($stmt);

    return $data;
}



// GET MEDICINE BY ID
// Fetches a single medicine with its category information using primary key
function get_medicine_by_id($conn, $id)
{
    $stmt = mysqli_prepare(
        $conn,
        "SELECT m.*, c.name AS category_name, c.category_type
         FROM medicines m
         JOIN categories c ON m.category_id = c.id
         WHERE m.id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    return $row;
}



// GET MEDICINES BY CATEGORY
// Retrieves all medicines belonging to a specific category ID
function get_medicines_by_category($conn, $category_id)
{
    $stmt = mysqli_prepare(
        $conn,
        "SELECT m.*, c.name AS category_name, c.category_type
         FROM medicines m
         JOIN categories c ON m.category_id = c.id
         WHERE m.category_id = ?
         ORDER BY m.id DESC"
    );

    mysqli_stmt_bind_param($stmt, "i", $category_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }

    mysqli_stmt_close($stmt);
    return $data;
}



// SEARCH MEDICINES
// Supports filtering by keyword, vendor name, genre (category name), and category type
function search_medicines($conn, $search = '', $vendor = '', $genre = '', $type = '') {
    $sql = "SELECT m.*, c.name as category_name, c.category_type 
            FROM medicines m 
            JOIN categories c ON m.category_id = c.id
            WHERE 1=1";  // Base condition allows dynamic appending of filters
    $params = [];
    $types = "";
    
    // Filter by medicine name or description keyword
    if (!empty($search)) {
        $sql .= " AND m.name LIKE ?";
        $params[] = "%$search%";
        $types .= "s";
    }
    
    // Filter by supplier/vendor name
    if (!empty($vendor)) {
        $sql .= " AND m.vendor_name LIKE ?";
        $params[] = "%$vendor%";
        $types .= "s";
    }
    
    // Filter by category name (genre)
    if (!empty($genre)) {
        $sql .= " AND c.name LIKE ?";
        $params[] = "%$genre%";
        $types .= "s";
    }
    
    // Filter by category type (e.g., tablets, syrups, capsules)
    if (!empty($type) && $type !== 'all') {
        $sql .= " AND c.category_type = ?";
        $params[] = $type;
        $types .= "s";
    }
    
    $sql .= " ORDER BY m.id ASC";
    
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        error_log("SQL Prepare Error: " . mysqli_error($conn));
        return [];
    }
    
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



// ADD MEDICINE
// Inserts a new medicine record with all its attributes
function add_medicine($conn, $data)
{
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO medicines
        (name, category_id, vendor_name, price, availability, description, image_path)
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    // "sisidss" = string, integer, string, integer, double, string, string
    mysqli_stmt_bind_param(
        $stmt,
        "sisidss",
        $data['name'],
        $data['category_id'],
        $data['vendor_name'],
        $data['price'],
        $data['availability'],
        $data['description'],
        $data['image_path']
    );

    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}



// UPDATE MEDICINE
// Modifies an existing medicine's information
function update_medicine($conn, $id, $data)
{
    $stmt = mysqli_prepare(
        $conn,
        "UPDATE medicines
         SET name=?, category_id=?, vendor_name=?, price=?, availability=?, description=?, image_path=?
         WHERE id=?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sisidssi",
        $data['name'],
        $data['category_id'],
        $data['vendor_name'],
        $data['price'],
        $data['availability'],
        $data['description'],
        $data['image_path'],
        $id
    );

    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// CAN DELETE MEDICINE
// Checks if medicine is safe to delete (not in any cart or pending order)
// Returns false if medicine is referenced elsewhere to maintain data integrity
function can_delete_medicine($conn, $medicine_id) {
    // Check if medicine exists in any customer's cart
    $cart_check = mysqli_query($conn, "SELECT COUNT(*) FROM cart WHERE medicine_id = $medicine_id");
    $cart_count = mysqli_fetch_row($cart_check)[0];
    
    if ($cart_count > 0) {
        return false;
    }
    
    // Check if medicine is part of any pending order (not yet accepted/rejected)
    $order_check = mysqli_query($conn, "SELECT COUNT(*) FROM order_items oi 
                                        JOIN orders o ON oi.order_id = o.id 
                                        WHERE oi.medicine_id = $medicine_id AND o.status = 'pending'");
    $order_count = mysqli_fetch_row($order_check)[0];
    
    if ($order_count > 0) {
        return false;
    }
    
    return true;
}

// DELETE MEDICINE
// Permanently removes a medicine from the database
function delete_medicine($conn, $id)
{
    $stmt = mysqli_prepare($conn, "DELETE FROM medicines WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

?>
<?php

// =========================
// MEDICINE MANAGEMENT
// =========================


// TOTAL MEDICINES
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
// SEARCH MEDICINES - Updated to handle all filters
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



// ADD MEDICINE
function add_medicine($conn, $data)
{
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO medicines
        (name, category_id, vendor_name, price, availability, description, image_path)
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

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



// DELETE MEDICINE
function delete_medicine($conn, $id)
{
    $stmt = mysqli_prepare($conn, "DELETE FROM medicines WHERE id = ?");

    mysqli_stmt_bind_param($stmt, "i", $id);

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}

?>
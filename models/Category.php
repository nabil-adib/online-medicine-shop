<?php


// Returns the total count of categories in the database
function get_total_categories($conn)
{
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM categories");
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row['total'];
}



// Retrieves all categories ordered by ID ascending
function get_all_categories($conn)
{
    $stmt = mysqli_prepare($conn, "SELECT * FROM categories ORDER BY id ASC");
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $categories = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $categories[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $categories;
}



// Fetches a single category using its primary key
function get_category_by_id($conn, $id)
{
    $stmt = mysqli_prepare($conn, "SELECT * FROM categories WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);  // "i" = integer type
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $category = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $category;
}




// Inserts a new category with name and type (e.g., tablets, syrups, etc.)
function add_category($conn, $name, $type)
{
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO categories(name, category_type) VALUES(?, ?)"
    );
    mysqli_stmt_bind_param($stmt, "ss", $name, $type);  // "ss" = two strings
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}




// Modifies an existing category's name and type
function update_category($conn, $id, $name, $type)
{
    $stmt = mysqli_prepare(
        $conn,
        "UPDATE categories SET name = ?, category_type = ? WHERE id = ?"
    );
    mysqli_stmt_bind_param($stmt, "ssi", $name, $type, $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}



// Removes a category permanently from the database
function delete_category($conn, $id)
{
    $stmt = mysqli_prepare($conn, "DELETE FROM categories WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}




// Returns true if any medicines belong to this category
// Used to prevent deletion of non-empty categories (foreign key constraint)
function check_medicines_in_category($conn, $category_id)
{
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM medicines WHERE category_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $category_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
    return $row['total'] > 0;  // Returns true if count > 0
}

?>
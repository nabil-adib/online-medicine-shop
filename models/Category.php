<?php
// =========================
// TOTAL CATEGORIES
// =========================

function get_total_categories($conn)
{
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM categories");

    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($res);

    mysqli_stmt_close($stmt);

    return $row['total'];
}



// =========================
// GET ALL CATEGORIES
// =========================

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



// =========================
// GET CATEGORY BY ID
// =========================

function get_category_by_id($conn, $id)
{
    $stmt = mysqli_prepare($conn, "SELECT * FROM categories WHERE id = ?");

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);

    $category = mysqli_fetch_assoc($res);

    mysqli_stmt_close($stmt);

    return $category;
}



// =========================
// ADD CATEGORY
// =========================

function add_category($conn, $name, $type)
{
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO categories(name, category_type)
         VALUES(?, ?)"
    );

    mysqli_stmt_bind_param($stmt, "ss", $name, $type);

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}



// =========================
// UPDATE CATEGORY
// =========================

function update_category($conn, $id, $name, $type)
{
    $stmt = mysqli_prepare(
        $conn,
        "UPDATE categories
         SET name = ?, category_type = ?
         WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "ssi", $name, $type, $id);

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}



// =========================
// DELETE CATEGORY
// =========================

function delete_category($conn, $id)
{
    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM categories
         WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);

    $result = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    return $result;
}



// =========================
// CHECK MEDICINES IN CATEGORY
// =========================

function check_medicines_in_category($conn, $category_id)
{
    $stmt = mysqli_prepare(
        $conn,
        "SELECT COUNT(*) AS total
         FROM medicines
         WHERE category_id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $category_id);

    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($res);

    mysqli_stmt_close($stmt);

    return $row['total'] > 0;
}

?>
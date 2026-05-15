<?php
function get_user_by_email($conn, $user_email) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, 's', $user_email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user   = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $user;
}

function get_user_by_id($conn, $user_id) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user   = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $user;
}

function get_user_by_token($conn, $token) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE remember_token = ?");
    mysqli_stmt_bind_param($stmt, 's', $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user   = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $user;
}

function email_exists($conn, $user_email) {
    $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, 's', $user_email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $exists = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    return $exists;
}

function create_user($conn, $user_name, $user_email, $password_hash, $user_address, $user_phone, $user_role) {

$stmt = mysqli_prepare($conn,
"INSERT INTO users (name, email, password_hash, address, phone, role)
VALUES (?, ?, ?, ?, ?, ?)"
);

mysqli_stmt_bind_param($stmt, 'ssssss',$user_name, $user_email, $password_hash,$user_address, $user_phone, $user_role);
return mysqli_stmt_execute($stmt);
}

function update_user_info($conn, $user_id, $user_name, $user_email, $user_address, $user_phone) {
    $stmt = mysqli_prepare($conn,
        "UPDATE users SET name = ?, email = ?, address = ?, phone = ? WHERE id = ?"
    );
    mysqli_stmt_bind_param($stmt, 'ssssi',
        $user_name, $user_email, $user_address, $user_phone, $user_id
    );
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function update_user_password($conn, $user_id, $password_hash) {
    $stmt = mysqli_prepare($conn, "UPDATE users SET password_hash = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'si', $password_hash, $user_id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function update_profile_picture($conn, $user_id, $profile_picture) {
    $stmt = mysqli_prepare($conn, "UPDATE users SET profile_picture = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'si', $profile_picture, $user_id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function set_remember_token($conn, $user_id, $token) {
    $stmt = mysqli_prepare($conn, "UPDATE users SET remember_token = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'si', $token, $user_id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function clear_remember_token($conn, $user_id) {
    $stmt = mysqli_prepare($conn, "UPDATE users SET remember_token = NULL WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}
?>
<?php
function auth_admin($conn, $username, $password) {
    $stmt = mysqli_prepare($conn, "SELECT id, username, password FROM admins WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return ($row && password_verify($password, $row['password'])) ? $row : false;
}

?>

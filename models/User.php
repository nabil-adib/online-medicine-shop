<?php

// GET USER BY EMAIL
// Retrieves a complete user record using email as lookup (used for login)
function get_user_by_email($conn, $user_email) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, 's', $user_email);  // 's' = string parameter
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $user;  // Returns null if user not found
}

// GET USER BY ID
// Retrieves a complete user record using primary key ID
function get_user_by_id($conn, $user_id) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $user_id);  // 'i' = integer parameter
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $user;
}

// EMAIL EXISTS
// Checks if an email address is already registered (prevents duplicate emails)
function email_exists($conn, $user_email) {
    $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, 's', $user_email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);  // Stores result for num_rows to work
    $exists = mysqli_stmt_num_rows($stmt) > 0;  // Returns true if at least one row found
    mysqli_stmt_close($stmt);
    return $exists;
}

// CREATE USER
// Inserts a new user record during registration
function create_user($conn, $user_name, $user_email, $password_hash, $user_address, $user_phone, $user_role) {
    $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password_hash, address, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'ssssss', $user_name, $user_email, $password_hash, $user_address, $user_phone, $user_role);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $result;  // Returns true on success
}

// UPDATE USER INFO
// Updates user profile information with email conflict checking
function update_user_info($conn, $user_id, $user_name, $user_email, $user_address, $user_phone) {
    // First, retrieve current email for comparison
    $current_email_query = mysqli_prepare($conn, "SELECT email FROM users WHERE id = ?");
    mysqli_stmt_bind_param($current_email_query, 'i', $user_id);
    mysqli_stmt_execute($current_email_query);
    $result = mysqli_stmt_get_result($current_email_query);
    $current_user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($current_email_query);
    
    // If email is being changed to an email already used by another user
    if ($current_user['email'] !== $user_email && email_exists($conn, $user_email)) {
        return 'email_exists';  // Return string instead of boolean to indicate conflict type
    }
    
    // Proceed with update if no conflict
    $stmt = mysqli_prepare($conn, "UPDATE users SET name = ?, email = ?, address = ?, phone = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'ssssi', $user_name, $user_email, $user_address, $user_phone, $user_id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    return $ok ? true : false;
}

// UPDATE USER PASSWORD
// Securely updates the password hash for a user account
function update_user_password($conn, $user_id, $password_hash) {
    $stmt = mysqli_prepare($conn, "UPDATE users SET password_hash = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'si', $password_hash, $user_id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

// UPDATE PROFILE PICTURE
// Stores the file path of user's uploaded profile image
function update_profile_picture($conn, $user_id, $profile_picture) {
    $stmt = mysqli_prepare($conn, "UPDATE users SET profile_picture = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'si', $profile_picture, $user_id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

// VALIDATE NAME
// Ensures name contains no numeric characters (letters and spaces only)
function validate_name($name) {
    if (preg_match('/[0-9]/', $name)) {  // Check for presence of any digit
        return false;
    }
    return true;
}

// VALIDATE PHONE
// Verifies phone number is exactly 11 digits with no other characters
function validate_phone($phone) {
    return preg_match('/^\d{11}$/', $phone);  // Returns 1 for valid, 0 for invalid
}

?>
<?php
function profile_ctrl($conn) {
    if (!isset($_SESSION['logged_in'])) {
        header('Location: index.php?page=login');
        exit;
    }
    
    $error = '';
    $success = '';
    $user = get_user_by_id($conn, $_SESSION['user_id']);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? 'update_info';
        
        if ($action === 'update_info') {
            $user_name = trim($_POST['name'] ?? '');
            $user_email = trim($_POST['email'] ?? '');
            $user_address = trim($_POST['address'] ?? '');
            $user_phone = trim($_POST['phone'] ?? '');
            
            if ($user_name === '' || $user_email === '' ||
                $user_address === '' || $user_phone === '') {
                $error = 'All fields are required.';
            } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Please enter a valid email address.';
            } elseif (!preg_match('/^\d{7,15}$/', $user_phone)) {
                $error = 'Phone must be 7-15 digits.';
            } else {
                $ok = update_user_info(
                    $conn,
                    $_SESSION['user_id'],
                    htmlspecialchars($user_name),
                    $user_email,
                    htmlspecialchars($user_address),
                    $user_phone
                );
                
                if ($ok) {
                    $_SESSION['user_name'] = htmlspecialchars($user_name);
                    $_SESSION['user_email'] = $user_email;
                    $_SESSION['user_address'] = htmlspecialchars($user_address);
                    $_SESSION['user_phone'] = $user_phone;
                    $success = 'Profile updated successfully.';
                    $user = get_user_by_id($conn, $_SESSION['user_id']);
                } else {
                    $error = 'Update failed. Please try again.';
                }
            }
        }
        
        if ($action === 'change_password') {
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            if ($current_password === '' || $new_password === '' || $confirm_password === '') {
                $error = 'All password fields are required.';
            } elseif (!password_verify($current_password, $user['password_hash'])) {
                $error = 'Current password is incorrect.';
            } elseif (strlen($new_password) < 8) {
                $error = 'New password must be at least 8 characters.';
            } elseif ($new_password !== $confirm_password) {
                $error = 'New passwords do not match.';
            } else {
                $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $ok = update_user_password($conn, $_SESSION['user_id'], $password_hash);
                if ($ok) {
                    $success = 'Password changed successfully.';
                } else {
                    $error = 'Failed to change password.';
                }
            }
        }
        
        if ($action === 'upload_picture') {
            if (empty($_FILES['profile_picture']['name'])) {
                $error = 'Please select an image to upload.';
            } else {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $max_size = 2 * 1024 * 1024;
                $file_tmp = $_FILES['profile_picture']['tmp_name'];
                $file_size = $_FILES['profile_picture']['size'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $file_mime = finfo_file($finfo, $file_tmp);
                finfo_close($finfo);
                $file_ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
                
                if (!in_array($file_mime, $allowed_types)) {
                    $error = 'Only JPG, PNG, and GIF images are allowed.';
                } elseif ($file_size > $max_size) {
                    $error = 'Image size must be under 2MB.';
                } else {
                    if (!is_dir('public/uploads')) {
                        mkdir('public/uploads', 0777, true);
                    }
                    $new_filename = 'profile_' . $_SESSION['user_id'] . '_' . time() . '.' . $file_ext;
                    $upload_path = 'public/uploads/' . $new_filename;
                    
                    if (move_uploaded_file($file_tmp, $upload_path)) {
                        $ok = update_profile_picture($conn, $_SESSION['user_id'], $upload_path);
                        if ($ok) {
                            $_SESSION['profile_picture'] = $upload_path;
                            $success = 'Profile picture updated.';
                            $user = get_user_by_id($conn, $_SESSION['user_id']);
                        } else {
                            $error = 'Failed to save picture path.';
                        }
                    } else {
                        $error = 'Failed to upload image.';
                    }
                }
            }
        }
    }
    
    require 'views/profile.php';
}
?>
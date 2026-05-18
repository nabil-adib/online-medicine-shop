<?php
// This file can be deprecated since we now use index.php?page=ajax&type=search_medicines
// But keep for backward compatibility
header('Content-Type: application/json');
session_start();

require_once '../../config/db.php';
require_once '../../models/Medicine.php';

if (!isset($_SESSION['logged_in'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$medicines = get_all_medicines($conn);
echo json_encode(['success' => true, 'medicines' => $medicines]);
?>
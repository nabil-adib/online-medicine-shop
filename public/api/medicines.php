<?php
header('Content-Type: application/json');
session_start();

require_once '../../config/db.php';
require_once '../../models/Medicine.php';

if (!isset($_SESSION['logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$medicines = get_all_medicines($conn);
echo json_encode(['success' => true, 'medicines' => $medicines]);
?>
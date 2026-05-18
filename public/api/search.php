<?php
header('Content-Type: application/json');
session_start();

require_once '../../config/db.php';
require_once '../../models/Medicine.php';

if (!isset($_SESSION['logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get all filter parameters
$search = $_GET['search'] ?? '';
$vendor = $_GET['vendor'] ?? '';
$genre = $_GET['genre'] ?? '';
$type = $_GET['type'] ?? '';

// Call the search function with all parameters
$medicines = search_medicines($conn, $search, $vendor, $genre, $type);
echo json_encode(['success' => true, 'medicines' => $medicines]);
?>
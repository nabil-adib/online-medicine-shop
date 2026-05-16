<?php
header('Content-Type: application/json');
session_start();

require_once '../../config/db.php';
require_once '../../models/Medicine.php';

if (!isset($_SESSION['logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$name = $_GET['name'] ?? '';
$vendor = $_GET['vendor'] ?? '';
$genre = $_GET['genre'] ?? '';

$medicines = search_medicines($conn, $name, $vendor, $genre);
echo json_encode(['success' => true, 'medicines' => $medicines]);
?>
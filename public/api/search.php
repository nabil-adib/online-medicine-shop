<?php
// Set response format to JSON for API endpoint
header('Content-Type: application/json');

// Start or resume existing session for authentication check
session_start();

// Load database connection and medicine model functions
require_once '../../config/db.php';
require_once '../../models/Medicine.php';

// Verify user is logged in before processing search request
if (!isset($_SESSION['logged_in'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Retrieve and sanitize filter parameters from query string
$search = $_GET['q'] ?? '';     // 'q' parameter matches the JavaScript AJAX request
$vendor = $_GET['vendor'] ?? '';  // Filter by vendor/supplier name
$genre = $_GET['genre'] ?? '';    // Filter by category name
$type = $_GET['type'] ?? '';      // Filter by category type (tablets, syrups, etc.)

// Execute search with applied filters
$medicines = search_medicines($conn, $search, $vendor, $genre, $type);

// Handle potential database errors
if ($medicines === false || $medicines === null) {
    echo json_encode(['success' => false, 'message' => 'Database error', 'medicines' => []]);
    exit;
}

// Return successful response with filtered medicine data
echo json_encode(['success' => true, 'medicines' => $medicines]);
?>
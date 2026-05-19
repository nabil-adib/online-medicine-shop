<?php
// Set response format to JSON for API endpoint
header('Content-Type: application/json');

// Start or resume existing session to check authentication
session_start();

// Load database connection and medicine model functions
require_once '../../config/db.php';
require_once '../../models/Medicine.php';

// Verify user is logged in before allowing access to medicine data
if (!isset($_SESSION['logged_in'])) {
    http_response_code(401);  // HTTP 401 Unauthorized status code
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Fetch all medicines from database using the model function
$medicines = get_all_medicines($conn);

// Return successful response with medicine data as JSON
echo json_encode(['success' => true, 'medicines' => $medicines]);
?>
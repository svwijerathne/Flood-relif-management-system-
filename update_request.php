<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
require_once 'db_config.php';

// Ensure data exists before processing
$request_id = isset($_POST['id']) ? intval($_POST['id']) : 0; 
$new_status = $_POST['status'] ?? '';

if ($request_id > 0 && !empty($new_status)) {
    // Check if the connection is still alive
    if ($conn->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE requests SET status = ? WHERE id = ?");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("si", $new_status, $request_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Execute failed: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data: ID=' . $request_id . ', Status=' . $new_status]);
}

$conn->close();
?>
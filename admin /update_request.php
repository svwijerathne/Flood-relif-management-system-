<?php
header('Content-Type: application/json');
$conn = new mysqli('localhost', 'root', '', 'floodRelif');

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB Connection Failed']);
    exit;
}

$request_id = $_POST['request_id'] ?? '';
$new_status = $_POST['status'] ?? '';

// Update the database securely using a prepared statement!
$stmt = $conn->prepare("UPDATE userRequests SET status = ? WHERE request_id = ?");
$stmt->bind_param("si", $new_status, $request_id); // "si" = string, integer

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update database.']);
}

$conn->close();
?>
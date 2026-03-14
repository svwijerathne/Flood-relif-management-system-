<?php
header('Content-Type: application/json');
require_once 'db_config.php';

// Fetch all requests ordered by newest first
$result = $conn->query("SELECT * FROM requests ORDER BY created_at DESC");

$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}

echo json_encode($requests);
$conn->close();
?>
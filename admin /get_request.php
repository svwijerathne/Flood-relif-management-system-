<?php
header('Content-Type: application/json');
$conn = new mysqli('localhost', 'root', '', 'floodRelif');

if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

// Fetch all requests ordered by newest first
$result = $conn->query("SELECT * FROM userRequests ORDER BY request_date DESC");

$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}

echo json_encode($requests);
$conn->close();
?>
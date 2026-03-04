<?php
header('Content-Type: application/json');

// Database details from your phpMyAdmin screenshot
$host = 'localhost';
$db_user = 'root'; 
$db_pass = ''; 
$db_name = 'floodRelif'; // Check if 'R' is capital in phpMyAdmin!

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB Connection Failed']);
    exit;
}

$email = $_POST['email'] ?? '';
$password = $_POST['pw'] ?? '';

// Check Admin Table
$stmt = $conn->prepare("SELECT pw FROM adminCredentials WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if ($password === $row['pw']) {
        echo json_encode(['status' => 'success', 'redirect' => 'admin_page.html']);
        exit;
    }
}
// 2. Check User Table
$stmt2 = $conn->prepare("SELECT pw FROM userCredentials WHERE email = ?");
$stmt2->bind_param("s", $email);
$stmt2->execute();
$res2 = $stmt2->get_result();

if ($row = $res2->fetch_assoc()) {
    if ($password === $row['pw']) {
        echo json_encode(['status' => 'success', 'redirect' => 'user_request.html']);
        exit;
    }
}

echo json_encode(['status' => 'error', 'message' => 'Invalid Credentials']);
?>
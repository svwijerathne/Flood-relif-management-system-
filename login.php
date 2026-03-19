<?php
session_start(); 
header('Content-Type: application/json');
require_once 'db_config.php';

$email = $_POST['email'] ?? '';
$password = $_POST['pw'] ?? ''; 

// Select id and full_name so we can store them in the session
$stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    
    if ($password === $row['password']) {
        
        // 1. STORE DATA IN SESSION
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['full_name'] = $row['full_name'];
        $_SESSION['role'] = $row['role'];

        // 2. DETERMINE REDIRECT
        $redirect = ($row['role'] === 'admin') ? 'admin_page.html' : 'view_request.php';
        
        echo json_encode(['status' => 'success', 'redirect' => $redirect]);
        exit;
    }
}

echo json_encode(['status' => 'error', 'message' => 'Invalid Credentials']);
?>
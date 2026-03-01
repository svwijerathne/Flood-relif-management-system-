<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Please login using the form.");
}

$conn = new mysqli("localhost", "root", "", "flood_relief_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    
    // Since DB password is plain text
    if ($password == $user['password']) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];

        header("Location: submit_request.html");
        exit();

    } else {
        echo "❌ Invalid password!";
    }
} else {
    echo "❌ User not found!";
}

$stmt->close();
$conn->close();
?>
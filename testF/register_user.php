<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "flood_relief_system";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$full_name = $conn->real_escape_string($_POST['full_name']);
$email = $conn->real_escape_string($_POST['email']);
$password = $conn->real_escape_string($_POST['password']);  // No hashing
$role = $conn->real_escape_string($_POST['role']);

// Check if email already exists
$check_email = "SELECT id FROM users WHERE email='$email'";
$result = $conn->query($check_email);

if ($result->num_rows > 0) {
    echo "❌ Email already registered.<br>";
    echo "<a href='register.html'>Go back</a>";
    exit;
}

// Insert user (Plain text password)
$sql = "INSERT INTO users (full_name, email, password, role) 
        VALUES ('$full_name', '$email', '$password', '$role')";

if ($conn->query($sql) === TRUE) {
    echo "✅ User registered successfully!<br>";
    echo "<a href='register.html'>Register another user</a>";
} else {
    echo "❌ Error: " . $conn->error;
}

$conn->close();
?>
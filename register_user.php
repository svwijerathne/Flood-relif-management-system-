<?php
require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize basic user details
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']); 

    // Check if email already exists
    $check_email = "SELECT id FROM users WHERE email='$email'";
    $result = $conn->query($check_email);

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already registered.'); 
        window.location.href='register.html';</script>";
        exit;
    }

    // Insert user - the 'role' column will automatically fill with your default value
    $sql = "INSERT INTO users (full_name, email, password) 
            VALUES ('$full_name', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to login page immediately on success
        header("Location: login.html");
        exit(); 
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
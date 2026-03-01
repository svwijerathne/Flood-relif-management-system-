<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "flood_relief_system";
$conn = new mysqli("localhost", "root", "", "flood_relief_system");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data and sanitize
$district_name = $conn->real_escape_string($_POST['district_name']);
$severity_level = $conn->real_escape_string($_POST['severity_level']);

// Check if district already exists
$check_sql = "SELECT id FROM regions WHERE district_name='$district_name'";
$result = $conn->query($check_sql);

if ($result->num_rows > 0) {
    echo "❌ District already exists.<br>";
    echo "<a href='add_region.html'>Go back</a>";
    exit;
}

// Insert into regions table
$sql = "INSERT INTO regions (district_name, severity_level) 
        VALUES ('$district_name', '$severity_level')";

if ($conn->query($sql) === TRUE) {
    echo "✅ Region added successfully!<br>";
    echo "<a href='add_region.html'>Add another region</a>";
} else {
    echo "❌ Error: " . $conn->error;
}

$conn->close();
?>
<?php
session_start();

// Check form submission
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Please submit the form.");
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Please login first.");
}

$user_id = $_SESSION['user_id'];

// Database connection
$conn = new mysqli("localhost", "root", "", "flood_relief_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data safely
$contact_person = $_POST['contact_person'];
$contact_number = $_POST['contact_number'];
$family_members = $_POST['family_members'];
$region_id = $_POST['region_id'];
$divisional_secretariat = $_POST['divisional_secretariat'];
$gn_division = $_POST['gn_division'];
$relief_type = $_POST['relief_type'];
$address = $_POST['address'];
$severity = $_POST['severity'];
$description = $_POST['description'];

// Optional: Check if region_id exists in regions table
$region_check = $conn->prepare("SELECT id FROM regions WHERE id=?");
$region_check->bind_param("i", $region_id);
$region_check->execute();
$res = $region_check->get_result();
if ($res->num_rows == 0) {
    die("❌ Invalid region selected.");
}

// Insert request
$sql = "INSERT INTO requests 
(user_id, region_id, divisional_secretariat, gn_division, relief_type,
contact_person, contact_number, address, family_members, severity, description) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iissssssiss", $user_id, $region_id, $divisional_secretariat, $gn_division,
    $relief_type, $contact_person, $contact_number, $address, $family_members, $severity, $description);

if ($stmt->execute()) {
    echo "✅ Request submitted successfully!<br>";
    echo "<a href='submit_request.html'>Submit Another</a>";
} else {
    echo "❌ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
<?php
session_start();
// Enable error reporting to catch any database issues
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Logic for User ID (Sends NULL to DB if it's a guest)
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

    // 2. Collect and Clean POST data
    $contact_person = $_POST['contact_person'];
    $contact_number = $_POST['contact_number'];
    $family_members = intval($_POST['family_members']);
    $region_id = intval($_POST['region_id']);
    $div_sec = $_POST['divisional_secretariat'];
    $gn_div = $_POST['gn_division'];
    $relief_type = $_POST['relief_type'];
    $address = $_POST['address'];
    $severity = $_POST['severity'];
    $description = !empty($_POST['description']) ? $_POST['description'] : null;

    // 3. Prepare SQL with 11 placeholders
    $sql = "INSERT INTO requests 
    (user_id, region_id, divisional_secretariat, gn_division, relief_type,
    contact_person, contact_number, address, family_members, severity, description) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // "iissssssiss" matches the column types: i=int, s=string
    $stmt->bind_param("iissssssiss", 
        $user_id, 
        $region_id, 
        $div_sec, 
        $gn_div, 
        $relief_type, 
        $contact_person, 
        $contact_number, 
        $address, 
        $family_members, 
        $severity, 
        $description
    );

    if ($stmt->execute()) {
    // Redirect back to request.php with a success status
    header("Location: request.php?status=success");
    exit();
} else {
    // Redirect back with an error status
    header("Location: request.php?status=error");
    exit();
}
    
    $stmt->close();
}
$conn->close();
?>
<?php
require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $sev = $_POST['severity'];
    
    $stmt = $conn->prepare("UPDATE regions SET severity_level = ? WHERE id = ?");
    $stmt->bind_param("si", $sev, $id);
    
    if ($stmt->execute()) {
        // Redirect with success status
        header("Location: manage_reigon.php?status=success");
    } else {
        // Redirect with error status
        header("Location: manage_reigon.php?status=error");
    }
    exit();
}
?>
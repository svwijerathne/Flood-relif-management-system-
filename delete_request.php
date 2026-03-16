<?php
session_start();
require_once 'db_config.php';

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM requests WHERE id = ? AND user_id = ? AND status = 'Pending'");
    $stmt->bind_param("ii", $id, $user_id);
    
    if ($stmt->execute()) {
        header("Location: view_request.php?msg=Deleted");
    } else {
        echo "Error deleting record.";
    }
}
?>
<?php
session_start();
require_once 'db_config.php';

// Admin Security Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied.");
}

$user_id = intval($_GET['id']);

// 1. Fetch User Info
$user_query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user = $user_query->get_result()->fetch_assoc();

if (!$user) { die("User not found."); }

// 2. Fetch User's Requests (Requirement 3.1)
$requests_query = $conn->prepare("SELECT * FROM requests WHERE user_id = ? ORDER BY created_at DESC");
$requests_query->bind_param("i", $user_id);
$requests_query->execute();
$requests = $requests_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Summary Report | <?php echo $user['full_name']; ?></title>
    <link rel="stylesheet" href="stylesAdmin.css">
    <style>
        .report-box { background: white; padding: 40px; border-radius: 15px; max-width: 800px; margin: 30px auto; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .user-info { display: flex; justify-content: space-between; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
        .print-btn { background: #2c3e50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; float: right; }
        @media print { .print-btn, .back-link { display: none; } .report-box { box-shadow: none; margin: 0; width: 100%; } }
    </style>
</head>
<body>
    <div class="report-box">
        <button class="print-btn" onclick="window.print()">Print Report</button>
        <a href="admin_users.php" class="back-link">← Back to Users</a>
        
        <h1 style="color: #04326a;">User Summary Report</h1>
        <p>Generated on: <?php echo date('Y-m-d H:i'); ?></p>

        <div class="user-info">
            <div>
                <h3>Personal Details</h3>
                <p><strong>Full Name:</strong> <?php echo $user['full_name']; ?></p>
                <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                <p><strong>Joined Date:</strong> <?php echo $user['created_at']; ?></p>
            </div>
            <div style="text-align: right;">
                <p><strong>User ID:</strong> #USR-<?php echo $user['id']; ?></p>
                <p><strong>Status:</strong> Active Registered User</p>
            </div>
        </div>

        <h3>Relief Request History</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Severity</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while($req = $requests->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $req['id']; ?></td>
                    <td><?php echo $req['relief_type']; ?></td>
                    <td><?php echo $req['severity']; ?></td>
                    <td><?php echo $req['status']; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($req['created_at'])); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
session_start();
require_once 'db_config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$logged_user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'] ?? "User"; // Matches login.php session
$role = $_SESSION['role'] ?? "User";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Dashboard | Flood Relief</title>
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-blue: #007bff;
            --dark-blue: #04223f;
            --bg-gray: #f4f7f6;
        }

        body { font-family: 'Segoe UI', sans-serif; background: var(--bg-gray); margin: 0; display: flex; }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width); height: 100vh; background: var(--dark-blue);
            color: white; position: fixed; display: flex; flex-direction: column;
        }

        .user-section { padding: 30px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .user-section .avatar { 
            width: 50px; height: 50px; background: var(--primary-blue); 
            border-radius: 50%; margin: 0 auto 10px; display: flex; 
            align-items: center; justify-content: center; font-weight: bold; font-size: 20px;
        }
        .user-name { font-weight: 600; font-size: 14px; display: block; }
        .user-role { font-size: 12px; color: #bdc3c7; text-transform: uppercase; }

        .sidebar-menu { list-style: none; padding: 20px 0; margin: 0; }
        .sidebar-menu li a {
            display: block; padding: 15px 25px; color: #ecf0f1; text-decoration: none; transition: 0.3s;
        }
        .sidebar-menu li a:hover, .sidebar-menu li a.active { background: #34495e; border-left: 4px solid var(--primary-blue); }
        .logout-btn { margin-top: auto; background: #e74c3c; padding: 15px; text-align: center; color: white; text-decoration: none; }

        /* Main Content */
        .main-content { margin-left: var(--sidebar-width); padding: 40px; width: 100%; }
        
        /* Relief Type Cards */
        .card-container { display: flex; gap: 20px; margin-bottom: 40px; }
        .relief-card {
    background: white;
    flex: 1; 
    min-width: 240px; /* Made wider */
    max-width: 300px; /* Prevents them from getting too skinny */
    border-radius: 12px;
    padding: 20px; /* More padding for better framing */
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    text-align: center;
}

.relief-card img { 
    width: 100%; 
    height: 180px; /* Increased height so PNGs are larger */
    object-fit: contain; /* Changed to contain so PNGs don't crop */
    background: #f9f9f9; /* Light bg to highlight the PNG shapes */
    border-radius: 8px;
}
        .relief-card h4 { margin: 5px 0; color: #2c3e50; font-size: 14px; }

        /* Request History Table */
        .history-container { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { color: #7f8c8d; font-size: 12px; text-transform: uppercase; }
        
        .status-badge { 
            padding: 5px 12px; border-radius: 15px; font-size: 11px; font-weight: bold;
            background: #f1f2f6; text-transform: uppercase;
        }
        .status-pending { background: #ffeaa7; color: #d6a316; }
        .status-approved { background: #55efc4; color: #00b894; }
    </style>
</head>
<body>

<nav class="sidebar">
    <div class="user-section">
        <div class="avatar"><?php echo strtoupper(substr($full_name, 0, 1)); ?></div>
        <span class="user-name"><?php echo htmlspecialchars($full_name); ?></span>
        <span class="user-role"><?php echo htmlspecialchars($role); ?></span>
    </div>
    <ul class="sidebar-menu">
        <li><a href="view_request.php" class="active">View My Requests</a></li>
        <li><a href="request.php"> New Request</a></li>
    </ul>
    <a href="index.html" class="logout-btn">Logout</a>
</nav>

<main class="main-content">
    <h3>Available Relief Services</h3>
    <div class="card-container">
        <div class="relief-card">
            <img src="image2.png" alt="Medical">
            <h4>Medical Aid</h4>
        </div>
        <div class="relief-card">
            <img src="image3.png" alt="Food">
            <h4>Food Rations</h4>
        </div>
        <div class="relief-card">
            <img src="image4.png" alt="Water">
            <h4>Clean Water</h4>
        </div>
        <div class="relief-card">
            <img src="image5.png" alt="Shelter">
            <h4>Emergency Shelter</h4>
        </div>
    </div>

    <div class="history-container">
        <h3>My Request Status</h3>
        <?php
        $sql = "SELECT r.*, reg.district_name FROM requests r 
                JOIN regions reg ON r.region_id = reg.id 
                WHERE r.user_id = ? ORDER BY r.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $logged_user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Find the table loop in your view_request.php and update it to this:
echo "<table><thead><tr>
        <th>Ref ID</th><th>District</th><th>Type</th><th>Severity</th><th>Status</th><th>Date</th><th>Action</th>
    </tr></thead><tbody>";
while($row = $result->fetch_assoc()) {
    $status = strtolower($row['status']);
    $badgeClass = ($status == 'pending') ? 'status-pending' : (($status == 'approved') ? 'status-approved' : '');
    
    echo "<tr>
        <td>#".$row['id']."</td>
        <td>".$row['district_name']."</td>
        <td>".$row['relief_type']."</td>
        <td>".$row['severity']."</td>
        <td><span class='status-badge $badgeClass'>".ucfirst($row['status'])."</span></td>
        <td>".date('Y-m-d', strtotime($row['created_at']))."</td>
        
<td>";
if($status == 'pending') {

    echo "<a href='edit_request.php?id=".$row['id']."' 
             style='color: #0056b3; text-decoration: none; font-weight: 600; margin-right: 15px;'>Edit</a>";
             
    echo "<a href='delete_request.php?id=".$row['id']."' 
             style='color: #d9534f; text-decoration: none; font-weight: 600;' 
             onclick='return confirm(\"Are you certain you wish to permanently delete this relief request?\")'>Delete</a>";
} else {
    echo "<span style='color:gray; font-size:12px;'>-</span>";
}
echo "</td>;</tr>";
}
echo "</tbody></table>";
        } else {
            echo "<p style='text-align:center; color:#95a5a6;'>You have no submitted requests.</p>";
        }
        $conn->close();
        ?>
    </div>
</main>

</body>
</html>
<?php
session_start();
require_once 'db_config.php';

// Simple Admin Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied.");
}

// Handle Delete User (Requirement 3.3)
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM users WHERE id = $id AND role != 'admin'");
    header("Location: admin_users.php?msg=UserDeleted");
}

$result = $conn->query("SELECT id, full_name, email, role, created_at FROM users WHERE role = 'user'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="stylesAdmin.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo-box">
            <i class="fa-solid fa-hand-holding-heart protection-icon"></i>
            <span class="brand-flood">FLOOD </span><span class="brand-relief">RELIEF</span>
        </div>
        <ul>
            <li><a href="admin_page.html">Dashboard</a></li>
            <li><a href="admin_users.php">Manage Users</a></li>
            <li style="margin-top: 50px;"><a href="login.html" style="color: #ff6b6b;">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h2>Registered Users</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Joined Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['full_name']; ?></td>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['created_at']; ?></td>
    <td>
        <a href="user_details.php?id=<?php echo $row['id']; ?>" 
           style="color: #007bff; text-decoration: none; margin-right: 15px; font-weight: bold;">View Report</a>
           
        <a href="admin_users.php?delete_id=<?php echo $row['id']; ?>" 
           onclick="return confirm('Permanently delete this user?')" 
           style="color: #e74c3c; text-decoration: none; font-weight: bold;">Delete</a>
    </td>
</tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
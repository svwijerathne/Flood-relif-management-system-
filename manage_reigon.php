<?php
require_once 'db_config.php';
$result = $conn->query("SELECT * FROM regions");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Region Severity | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="stylesAdmin.css">
</head>
<body class="lightMode">
    <div class="sidebar">
        <div class="logo-box" style="padding:20px;">
            <i class="fa-solid fa-hand-holding-heart protection-icon"></i>
            <span class="brand-flood">FLOOD </span><span class="brand-relief">RELIEF</span>
        </div>
        <ul>
            <li><a href="admin_page.html">Dashboard</a></li>
            <li><a href="admin_users.php">Manage Users</a></li>
            <li><a href="admin_reports.php">System Reports</a></li>
            <li><a href="manage_reigon.php">Region Severity</a></li>
            <li><a href="map.html">Map Control</a></li>
            <li style="margin-top: 50px;"><a href="login.html" style="color: #ff6b6b;">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Update District Severity</h2>
        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>District Name</th>
                        <th>Current Severity Level</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo $row['district_name']; ?></strong></td>
                        <form action="update_severity.php" method="POST">
                            <td>
                                <select name="severity" class="admin-dropdown" style="width: 200px;">
                                    <option value="Low" <?php if($row['severity_level']=='Low') echo 'selected'; ?>>Low</option>
                                    <option value="Medium" <?php if($row['severity_level']=='Medium') echo 'selected'; ?>>Medium</option>
                                    <option value="High" <?php if($row['severity_level']=='High') echo 'selected'; ?>>High</option>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="summary-btn" style="margin-top:0;">Update</button>
                            </td>
                        </form>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
    // Check the URL for the status parameter
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    if (status === 'success') {
        showNotification("Successfully updated!", "#2ecc71");
    } else if (status === 'error') {
        showNotification("Your request was unsuccessful.", "#e74c3c");
    }

    function showNotification(message, color) {
        // Create the notification element
        const toast = document.createElement("div");
        toast.innerHTML = message;
        toast.style.position = "fixed";
        toast.style.top = "20px";
        toast.style.right = "20px";
        toast.style.backgroundColor = color;
        toast.style.color = "white";
        toast.style.padding = "15px 25px";
        toast.style.borderRadius = "8px";
        toast.style.boxShadow = "0 4px 12px rgba(0,0,0,0.15)";
        toast.style.zIndex = "1000";
        toast.style.fontFamily = "sans-serif";
        toast.style.fontWeight = "bold";
        toast.style.transition = "opacity 0.5s ease";

        document.body.appendChild(toast);

        // Remove the message after 3 seconds
        setTimeout(() => {
            toast.style.opacity = "0";
            setTimeout(() => toast.remove(), 500);
        }, 3000);

        // Clean the URL so the message doesn't pop up again on refresh
        window.history.replaceState({}, document.title, window.location.pathname);
    }
</script>
</body>
</html>
<?php
session_start();
require_once 'db_config.php';

// 1. Define all 25 Districts
$districts_list = [
    "Ampara", "Anuradhapura", "Badulla", "Batticaloa", "Colombo", "Galle", "Gampaha", 
    "Hambantota", "Jaffna", "Kalutara", "Kandy", "Kegalle", "Kilinochchi", "Kurunegala", 
    "Mannar", "Matale", "Matara", "Monaragala", "Mullaitivu", "Nuwara Eliya", 
    "Polonnaruwa", "Puttalam", "Ratnapura", "Trincomalee", "Vavuniya"
];

// 2. Capture Filters
$filter_district = $_GET['district'] ?? '';
$filter_type = $_GET['type'] ?? '';
$filter_severity = $_GET['severity'] ?? '';
$search_name = $_GET['search'] ?? '';

// 3. Build Query
$where_clauses = ["1=1"];
if ($filter_district) $where_clauses[] = "reg.district_name = '$filter_district'";
if ($filter_type) $where_clauses[] = "r.relief_type = '$filter_type'";
if ($filter_severity) $where_clauses[] = "r.severity = '$filter_severity'";
if ($search_name) $where_clauses[] = "r.contact_person LIKE '%$search_name%'";

$where_sql = implode(" AND ", $where_clauses);

$sql = "SELECT r.*, reg.district_name 
        FROM requests r 
        JOIN regions reg ON r.region_id = reg.id 
        WHERE $where_sql";

$report_data = $conn->query($sql);
$rows = [];
$high_sev = 0; $food_req = 0; $med_req = 0;

while($row = $report_data->fetch_assoc()){
    if($row['severity'] == 'High') $high_sev++;
    if($row['relief_type'] == 'Food') $food_req++;
    if($row['relief_type'] == 'Medicine') $med_req++;
    $rows[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Reports | FloodRelief</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="stylesAdmin.css">
    <style>
        /* CSS to hide non-essential UI during printing */
        @media print {
            .sidebar, .no-print, .full-width-controls { display: none !important; }
            .main-content { margin-left: 0 !important; width: 100% !important; padding: 0; }
        }
    </style>
</head>
<body class="lightMode">
    <div class="sidebar no-print">
        <div class="logo-box">
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
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>System Summary Reports</h2>
            <button class="summary-btn no-print" onclick="window.print()">
                <i class="fa-solid fa-print"></i> Print Summary Report
            </button>
        </div>

        <div class="stats-container">
            <div class="stat-card total"><h3>High Severity</h3><p><?php echo $high_sev; ?></p></div>
            <div class="stat-card pending"><h3>Food Requests</h3><p><?php echo $food_req; ?></p></div>
            <div class="stat-card approved"><h3>Medicine</h3><p><?php echo $med_req; ?></p></div>
        </div>

        <form method="GET" class="full-width-controls no-print" style="background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #eee;">
    
    <div style="position: relative; margin-bottom: 15px;">
        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #666;"></i>
        <input type="text" name="search" placeholder="Search by Contact Name or Request ID..." 
               value="<?php echo htmlspecialchars($search_name); ?>" 
               style="width: 100%; padding: 12px 12px 12px 45px; border-radius: 8px; border: 1px solid #ddd; font-size: 14px;">
    </div>

    <div class="filter-row" style="display: flex; gap: 10px; flex-wrap: wrap;">
        <select name="district" class="admin-dropdown" style="flex: 1; min-width: 150px;">
            <option value="">All Districts</option>
            <?php foreach($districts_list as $dist): ?>
                <option value="<?php echo $dist; ?>" <?php if($filter_district == $dist) echo 'selected'; ?>>
                    <?php echo $dist; ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <select name="type" class="admin-dropdown" style="flex: 1; min-width: 150px;">
            <option value="">All Aid Types</option>
            <option value="Food" <?php if($filter_type=='Food') echo 'selected'; ?>>Food</option>
            <option value="Medicine" <?php if($filter_type=='Medicine') echo 'selected'; ?>>Medicine</option>
            <option value="Water" <?php if($filter_type=='Water') echo 'selected'; ?>>Water</option>
        </select>

        <select name="severity" class="admin-dropdown" style="flex: 1; min-width: 150px;">
            <option value="">All Severities</option>
            <option value="High" <?php if($filter_severity=='High') echo 'selected'; ?>>High</option>
            <option value="Medium" <?php if($filter_severity=='Medium') echo 'selected'; ?>>Medium</option>
            <option value="Low" <?php if($filter_severity=='Low') echo 'selected'; ?>>Low</option>
        </select>

        <button type="submit" class="summary-btn" style="margin: 0; padding: 0 25px; height: 45px;">
            Apply Filters
        </button>
    </div>
</form>

        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>District</th>
                        <th>Aid Type</th>
                        <th>Severity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($rows) > 0): foreach($rows as $r): ?>
                    <tr>
                        <td>#<?php echo $r['id']; ?></td>
                        <td><?php echo $r['contact_person']; ?></td>
                        <td><?php echo $r['district_name']; ?></td>
                        <td><?php echo $r['relief_type']; ?></td>
                        <td>
                            <span class="severity-text-<?php echo $r['severity']; ?>">
                                <?php echo $r['severity']; ?>
                            </span>
                        </td>
                        <td><?php echo $r['status']; ?></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="6" style="text-align:center;">No records found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
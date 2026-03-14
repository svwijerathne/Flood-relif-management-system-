<?php
header('Content-Type: application/json');
// Since everything is in one folder, just connect directly
require_once 'db_config.php'; 

// Join regions with requests to count "Delivered" items specifically
$sql = "SELECT 
            rg.district_name, 
            rg.severity_level, 
            COUNT(CASE WHEN rq.status = 'Delivered' THEN 1 END) as reliefs_sent,
            COUNT(rq.id) as total_requests
        FROM regions rg
        LEFT JOIN requests rq ON rg.id = rq.region_id
        GROUP BY rg.id";

$result = $conn->query($sql);

$stats = [];
while($row = $result->fetch_assoc()) {
    $stats[$row['district_name']] = [
        "severity" => strtolower($row['severity_level']), // for map color
        "sent" => (int)$row['reliefs_sent'],             // reliefs already sent
        "total" => (int)$row['total_requests']           // total demand
    ];
}

echo json_encode($stats);
$conn->close();
?>
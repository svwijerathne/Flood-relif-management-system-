<?php
$conn = new mysqli("localhost", "root", "", "flood_relief_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Join with regions table to show district names
$sql = "SELECT r.id, r.contact_person, r.contact_number, r.family_members,
reg.district_name, r.relief_type, r.severity, r.status
FROM requests r
JOIN regions reg ON r.region_id = reg.id
ORDER BY r.created_at DESC";

$result = $conn->query($sql);

echo "<h2>All Submitted Requests</h2>";

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>
    <tr>
        <th>ID</th>
        <th>Contact Person</th>
        <th>Phone</th>
        <th>Family Members</th>
        <th>District</th>
        <th>Relief Type</th>
        <th>Severity</th>
        <th>Status</th>
    </tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>".$row['id']."</td>
        <td>".$row['contact_person']."</td>
        <td>".$row['contact_number']."</td>
        <td>".$row['family_members']."</td>
        <td>".$row['district_name']."</td>
        <td>".$row['relief_type']."</td>
        <td>".$row['severity']."</td>
        <td>".$row['status']."</td>
        </tr>";
    }
    
    echo "</table>";
} else {
    echo "No requests found.";
}

$conn->close();
?>
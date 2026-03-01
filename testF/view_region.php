<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "flood_relief_system";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM regions";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
<title>View Regions</title>
<style>
table {
border-collapse: collapse;
width: 60%;
}
th, td {
border: 1px solid black;
padding: 8px;
text-align: left;
}
th {
background-color: #f2f2f2;
}
</style>
</head>
<body>

<h2>Regions List</h2>

<table>
<tr>
<th>ID</th>
<th>District Name</th>
<th>Severity Level</th>
</tr>

<?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>".$row["id"]."</td>";
        echo "<td>".$row["district_name"]."</td>";
        echo "<td>".$row["severity_level"]."</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'>No Data Found</td></tr>";
}
?>

</table>

</body>
</html>

<?php
$conn->close();
?>
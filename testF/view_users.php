<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "flood_relief_system";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
<title>View Users</title>
<style>
table {
border-collapse: collapse;
width: 70%;
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

<h2>Users List</h2>

<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Password</th>
<th>Role</th>
<th>Created At</th>
</tr>

<?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>".$row["id"]."</td>";
    echo "<td>".$row["full_name"]."</td>";
    echo "<td>".$row["email"]."</td>";
    echo "<td>".$row["password"]."</td>";
    echo "<td>".$row["role"]."</td>";
    echo "<td>".$row["created_at"]."</td>";
    echo "</tr>";
}
} else {
    echo "<tr><td colspan='4'>No Data Found</td></tr>";
}
?>

</table>

</body>
</html>

<?php
$conn->close();
?>
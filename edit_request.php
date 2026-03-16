<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.html"); exit(); }

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Fetch the existing data
$stmt = $conn->prepare("SELECT * FROM requests WHERE id = ? AND user_id = ? AND status = 'Pending'");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) { die("Request not found or already processed."); }

// Handle the update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['relief_type'];
    $address = $_POST['address'];
    $severity = $_POST['severity'];
    $desc = $_POST['description'];
    
    $upd = $conn->prepare("UPDATE requests SET relief_type=?, address=?, severity=?, description=? WHERE id=? AND user_id=?");
    $upd->bind_param("ssssii", $type, $address, $severity, $desc, $id, $user_id);
    
    if ($upd->execute()) {
        header("Location: view_request.php?msg=Updated");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Request</title>
    <link rel="stylesheet" href="styles_request.css">
</head>
<body>
    <div class="container">
        <h2>Update Your Request</h2>
        <form method="POST">
            <label>GN Division:</label>
<input type="text" name="gn_division" value="<?php echo $data['gn_division']; ?>" required>

<label>Number of Family Members:</label>
<input type="number" name="family_members" value="<?php echo $data['family_members']; ?>" required>

<label>Contact Number:</label>
<input type="text" name="contact_no" value="<?php echo $data['contact_no']; ?>" required>
            <label>Relief Type:</label>
            <select name="relief_type">
                <option <?php if($data['relief_type'] == 'Food') echo 'selected'; ?>>Food</option>
                <option <?php if($data['relief_type'] == 'Water') echo 'selected'; ?>>Water</option>
                <option <?php if($data['relief_type'] == 'Medicine') echo 'selected'; ?>>Medicine</option>
                <option <?php if($data['relief_type'] == 'Shelter') echo 'selected'; ?>>Shelter</option>
            </select>

            <label>Address:</label>
            <textarea name="address" required><?php echo $data['address']; ?></textarea>

            <label>Severity:</label>
            <select name="severity">
                <option <?php if($data['severity'] == 'Low') echo 'selected'; ?>>Low</option>
                <option <?php if($data['severity'] == 'Medium') echo 'selected'; ?>>Medium</option>
                <option <?php if($data['severity'] == 'High') echo 'selected'; ?>>High</option>
            </select>

            <label>Description:</label>
            <textarea name="description"><?php echo $data['description']; ?></textarea>

            <button type="submit" class="btn-primary">Save Changes</button>
            <a href="view_request.php" style="display:block; text-align:center; margin-top:10px;">Cancel</a>
        </form>
    </div>
</body>
</html>
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
    <title>Submit Request | Flood Relief System</title>
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-blue: #007bff;
            --dark-bg: #04223f;
            --light-bg: #f4f7f6;
        }

        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--light-bg); margin: 0; display: flex; }

        /* Sidebar Styling (Matches view_request.php) */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--dark-bg);
            color: white;
            position: fixed;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .user-profile {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: #04223f;
        }
        .user-profile .avatar {
            width: 60px; height: 60px; background: var(--primary-blue);
            border-radius: 50%; margin: 0 auto 10px; display: flex;
            align-items: center; justify-content: center; font-size: 24px; font-weight: bold;
        }
        .user-profile .name { font-size: 1.1em; font-weight: 600; display: block; }
        .user-profile .role-badge { 
            font-size: 0.75em; text-transform: uppercase; background: #34495e; 
            padding: 2px 8px; border-radius: 10px; color: #bdc3c7;
        }

        .sidebar-menu { list-style: none; padding: 20px 0; margin: 0; flex-grow: 1; }
        .sidebar-menu li a {
            display: block; padding: 15px 25px; color: #ecf0f1; text-decoration: none; transition: 0.3s;
        }
        .sidebar-menu li a:hover, .sidebar-menu li a.active {
            background: #34495e; color: white; border-left: 4px solid var(--primary-blue);
        }

        .logout-btn { background: #e74c3c; padding: 15px; text-align: center; color: white; text-decoration: none; font-weight: bold; }

        /* Main Content Layout */
        .main-content { margin-left: var(--sidebar-width); padding: 40px; width: calc(100% - var(--sidebar-width)); }
        
        /* Form Styling */
        .container { 
            max-width: 800px; 
            background: white; 
            padding: 35px; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.08); 
            margin :0 auto;
        }
        h2 { margin-top: 0; color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 15px; }
        
        form label { display: block; margin-top: 15px; font-weight: 600; color: #34495e; margin-bottom: 5px; }
        form input, form select, form textarea {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-size: 14px;
        }
        form input[readonly] { background-color: #f8f9fa; color: #6c757d; cursor: not-allowed; }
        textarea { height: 100px; resize: vertical; }

        .btn-primary {
            margin-top: 25px; background: var(--primary-blue); color: white; border: none;
            padding: 15px 30px; border-radius: 6px; font-size: 16px; font-weight: bold;
            cursor: pointer; transition: 0.3s; width: 100%;
        }
        .btn-primary:hover { background: #0056b3; }
    </style>
</head>
<body>

<nav class="sidebar">
    <div class="user-profile">
        <div class="avatar"><?php echo strtoupper(substr($full_name, 0, 1)); ?></div>
        <span class="name"><?php echo htmlspecialchars($full_name); ?></span>
        <span class="role-badge"><?php echo htmlspecialchars($role); ?></span>
    </div>
    
    <ul class="sidebar-menu">
        <li><a href="view_request.php"> My Requests</a></li>
        <li><a href="request.php" class="active">New Request</a></li>
    </ul>
    
    <a href="index.html" class="logout-btn">Logout</a>
</nav>

<main class="main-content">
    <div class="container">
    <?php
    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'success') {
            echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; border: 1px solid #c3e6cb;'>
                    <strong>Submission Successful!</strong> Your relief request has been recorded and is awaiting review.
                  </div>";
        } elseif ($_GET['status'] == 'error') {
            echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; border: 1px solid #f5c6cb;'>
                    <strong>Submission Failed.</strong> There was a technical error processing your request. Please try again.
                  </div>";
        }
    }
    ?>
        <h2>Submit Flood Relief Request</h2>
        
        <form action="submit_request.php" method="POST">
            <label for="contact_person">Contact Person Full Name:</label>
            <input type="text" id="contact_person" name="contact_person" 
                value="<?php echo htmlspecialchars($full_name); ?>" readonly>

            <label for="contact_number">Phone Number:</label>
            <input type="text" id="contact_number" name="contact_number" required placeholder="07XXXXXXXX">

            <label for="family_members">Number of Family Members:</label>
            <input type="number" id="family_members" name="family_members" required min="1">

            <label for="region_id">District:</label>
            <select id="region_id" name="region_id" required>
            <option value="">--Select District--</option>
            <option value="1">Ampara</option>
            <option value="2">Anuradhapura</option>
            <option value="3">Badulla</option>
            <option value="4">Batticaloa</option>
            <option value="5">Colombo</option>
            <option value="6">Galle</option>
            <option value="7">Gampaha</option>
            <option value="8">Hambantota</option>
            <option value="9">Jaffna</option>
            <option value="10">Kalutara</option>
            <option value="11">Kandy</option>
            <option value="12">Kegalle</option>
            <option value="13">Kilinochchi</option>
            <option value="14">Kurunegala</option>
            <option value="15">Mannar</option>
            <option value="16">Matale</option>
            <option value="17">Matara</option>
            <option value="18">Monaragala</option>
            <option value="19">Mullaitivu</option>
            <option value="20">Nuwara Eliya</option>
            <option value="21">Polonnaruwa</option>
            <option value="22">Puttalama</option>
            <option value="23">Ratnapura</option>
            <option value="24">Trincomalee</option>
            <option value="25">Vavuniya</option>


        </select>

            <label for="divisional_secretariat">Divisional Secretariat:</label>
            <input type="text" id="divisional_secretariat" name="divisional_secretariat" required>

            <label for="gn_division">GN Division:</label>
            <input type="text" id="gn_division" name="gn_division" required>

            <label for="relief_type">Relief Type:</label>
            <select id="relief_type" name="relief_type" required>
                <option value="">--Select Relief Type--</option>
                <option value="Food">Food</option>
                <option value="Water">Water</option>
                <option value="Medicine">Medicine</option>
                <option value="Shelter">Shelter</option>
            </select>

            <label for="address">Full Address:</label>
            <textarea id="address" name="address" required></textarea>

            <label for="severity">Severity Level:</label>
            <select id="severity" name="severity" required>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>

            <label for="description">Additional Details (optional):</label>
            <textarea id="description" name="description"></textarea>

            <button type="submit" class="btn-primary">Submit Request</button>
        </form>
    </div>
</main>

</body>
</html>
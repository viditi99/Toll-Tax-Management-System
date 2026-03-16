<?php
session_start();
include('db.php');

// Check admin login
if(!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Update Query
if (isset($_POST['update'])) {
    $vehicle = $_POST['vehicle_type'];
    $rate = $_POST['rate'];

    $conn->query("UPDATE toll_rates SET rate='$rate' WHERE vehicle_type='$vehicle'");
    echo "<script>alert('Rate Updated Successfully');</script>";
}

// Fetch all vehicles
$vehicles = $conn->query("SELECT * FROM toll_rates");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Set Toll Rates | Admin</title>

<style>
    body {
        font-family: Arial, sans-serif;
        background: #f5f7fa;
        margin: 0;
        padding: 0;
    }
    .navbar {
        background-color: #2f4f4f;
        color: white;
        padding: 15px 20px;
        text-align: center;
        font-size: 24px;
        font-weight: bold;
    }
    .logout {
        background: crimson;
        color: white;
        padding: 8px 15px;
        text-decoration: none;
        border-radius: 5px;
        float: right;
        margin-top: -40px;
    }
    .container {
        display: flex;
    }
    .sidebar {
        width: 220px;
        background: #3b3b3b;
        height: 100vh;
        padding-top: 20px;
    }
    .sidebar a {
        display: block;
        color: white;
        text-decoration: none;
        padding: 12px 20px;
        margin: 5px 0;
    }
    .sidebar a:hover {
        background: #556b2f;
    }
    .main {
        flex: 1;
        padding: 30px;
    }

    .form-box {
        background: white;
        width: 450px;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        margin: 0 auto;
    }

    h2 {
        color: #2f4f4f;
        text-align: center;
        margin-bottom: 20px;
    }

    label {
        font-weight: 600;
        display: block;
        margin-bottom: 8px;
    }

    select, input {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 16px;
    }
    button {
        background: #2f4f4f;
        color: white;
        width: 100%;
        padding: 12px;
        border: none;
        font-size: 16px;
        border-radius: 6px;
        cursor: pointer;
    }
    button:hover {
        background: #213838;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 25px;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background: #556b2f;
        color: white;
    }
</style>
</head>
<body>

<div class="navbar">
    Set Toll Rates
    <a href="logout.php" class="logout">Logout</a>
</div>

<div class="container">
    <div class="sidebar">
        <a href="admin_dashboard.php">🏠 Dashboard</a>
        <a href="manage_user.php">👥 Manage Users</a>
        <a href="manage_pass.php">🚗 Manage Passes</a>
        <a href="revenue_report.php">📊 Revenue Reports</a>
        <a href="view_issues.php">💬 Issues</a>
        <a href="set_rates.php" style="background:#556b2f">💰 Set Toll Rates</a>
    </div>

    <div class="main">

        <div class="form-box">
            <h2>Update Toll Rate</h2>

            <form method="POST">
                <label>Select Vehicle:</label>
                <select name="vehicle_type" required>
                    <option value="">-- Choose Vehicle --</option>
                    <?php while ($row = $vehicles->fetch_assoc()) { ?>
                        <option value="<?= $row['vehicle_type'] ?>">
                            <?= $row['vehicle_type'] ?>
                        </option>
                    <?php } ?>
                </select>

                <label>Enter New Rate (₹):</label>
                <input type="number" step="100" name="rate" required>

                <button type="submit" name="update">Update Rate</button>
            </form>
        </div>

    </div>
</div>

</body>
</html>

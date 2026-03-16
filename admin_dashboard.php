<?php
session_start();
include('db.php');
if(!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
$userCount = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$revenue = $conn->query("SELECT SUM(amount) AS total FROM passes")->fetch_assoc()['total'];
$revenue = $revenue ? $revenue : 0;
$active = $conn->query("SELECT COUNT(*) AS total FROM passes WHERE status='Active'")->fetch_assoc()['total'];
$expired = $conn->query("SELECT COUNT(*) AS total FROM passes WHERE status='Expired'")->fetch_assoc()['total'];
$issues = $conn->query("
    SELECT i.*, u.name AS user_name
    FROM issues i 
    JOIN users u ON i.user_id = u.id
    ORDER BY i.issue_id DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Toll Tax System</title>
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
            padding: 20px;
        }
        .cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .card {
            background: white;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 20px;
            flex: 1;
            min-width: 220px;
            text-align: center;
        }
        h3 {
            color: #2f4f4f;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #556b2f;
            color: white;
        }
        .logout {
            background: crimson;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
            float: right;
        }
        .logout:hover {
            background: darkred;
        }
    </style>
</head>
<body>

    <div class="navbar">
        Admin Dashboard - Toll Tax System
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <div class="container">
        <div class="sidebar">
            <a href="admin_dashboard.php">🏠 Dashboard</a>
            <a href="manage_user.php">👥 Manage Users</a>
            <a href="manage_pass.php">🚗 Manage Passes</a>
            <a href="revenue_report.php">📊 Revenue Reports</a>
            <?php
        $newIssues = $conn->query("SELECT COUNT(*) AS total FROM issues WHERE status='Pending'")
                         ->fetch_assoc()['total'];
    ?>

    <a href="view_issues.php">
        💬 Issues 
        <?php if($newIssues > 0) { ?>
            <span style="
                background:red;
                color:white;
                padding:3px 8px;
                border-radius:10px;
                font-size:12px;
                margin-left:5px;
            ">
                <?php echo $newIssues; ?>
            </span>
        <?php } ?>
    </a>
            
            <a href="set_rates.php">💰 Set Toll Rates</a>
        </div>

        <div class="main">
            <h2>Overview</h2>
            <div class="cards">
                <div class="card">
                    <h3>Total Users</h3>
                    <p><?php echo $userCount; ?></p>
                </div>
                <div class="card">
                    <h3>Total Revenue</h3>
                    <p>₹<?php echo number_format($revenue, 2); ?></p>
                </div>
                <div class="card">
                    <h3>Active Passes</h3>
                    <p><?php echo $active; ?></p>
                </div>
                <div class="card">
                    <h3>Expired Passes</h3>
                    <p><?php echo $expired; ?></p>
                </div>
            </div>

            <h2>Recent Issues</h2>
            <table>
                <tr>
                    <th>Issue Type</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                <?php while($row = $issues->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['issue_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo $row['date_time']; ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>
</html>


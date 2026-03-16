<?php
session_start();
include('db.php');

if(!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all issues with user data
$issues = $conn->query("
    SELECT i.*, u.name AS user_name
    FROM issues i 
    LEFT JOIN users u ON i.user_id = u.id
    ORDER BY i.issue_id DESC
");


// Handle status update safely
if (isset($_POST['update_status'])) {
    $issue_id = intval($_POST['issue_id']);
    $new_status = $conn->real_escape_string($_POST['status']);

    $conn->query("UPDATE issues SET status='$new_status' WHERE issue_id='$issue_id'");
    header("Location: view_issues.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin | View Issues</title>

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
    font-size: 22px;
    font-weight: bold;
}
.container {
    width: 90%;
    margin: 30px auto;
    background: white;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    border-radius: 10px;
}
h2 {
    color: #2f4f4f;
    margin-bottom: 20px;
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    border-bottom: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}
th {
    background: #556b2f;
    color: white;
}
form {
    display: inline-block;
}
select, button {
    padding: 5px 8px;
    border-radius: 5px;
    border: 1px solid #ccc;
}
button {
    background: #2f4f4f;
    color: white;
    border: none;
    cursor: pointer;
}
button:hover {
    background: #556b2f;
}

/* Status Colors */
.status-pending {
    color: red;
    font-weight: bold;
}
.status-resolved {
    color: green;
    font-weight: bold;
}
</style>
</head>
<body>

<div class="navbar">All Reported Issues</div>

<div class="container">
    <h2>Reported Issues</h2>

    <table>
        <tr>
            <th>Issue ID</th>
            <th>User</th>
            <th>Type</th>
            <th>Description</th>
            <th>Date & Time</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php while($row = $issues->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['issue_id']; ?></td>
            <td><?php echo htmlspecialchars($row['user_name']); ?></td>
            <td><?php echo htmlspecialchars($row['issue_type']); ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td><?php echo $row['date_time']; ?></td>

            <td class="status-<?php echo strtolower($row['status']); ?>">
                <?php echo htmlspecialchars($row['status']); ?>
            </td>

            <td>
                <form method="POST">
                    <input type="hidden" name="issue_id" value="<?php echo $row['issue_id']; ?>">

                    <select name="status">
                        <option value="Pending" <?php if($row['status']=="Pending") echo "selected"; ?>>
                            Pending
                        </option>

                        <option value="Resolved" <?php if($row['status']=="Resolved") echo "selected"; ?>>
                            Resolved
                        </option>
                    </select>

                    <button type="submit" name="update_status">Update</button>
                </form>
            </td>
        </tr>
        <?php } ?>

    </table>
    <div style="text-align:center; margin-top:20px;">
    <a href="admin_dashboard.php" 
       style="
           background:#2f4f4f;
           color:white;
           padding:10px 18px;
           text-decoration:none;
           border-radius:6px;
           font-weight:bold;
       ">
       ⬅ Back to Dashboard
    </a>
</div>

</div>

</body>
</html>

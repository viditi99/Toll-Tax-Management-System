<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('db.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Delete pass securely
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM passes WHERE pass_id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_pass.php?msg=Pass+Deleted+Successfully");
    exit();
}

// Fetch all passes along with user info
$sql = "
    SELECT 
        p.pass_id, 
        p.user_id, 
        p.pass_type, 
        p.amount, 
        p.start_date, 
        p.expiry_date, 
        p.status, 
        p.payment_method,
        u.name AS user_name, 
        u.email AS user_email, 
        u.vehicle_number
    FROM passes p
    JOIN users u ON p.user_id = u.id
    ORDER BY p.pass_id DESC
";
$result = $conn->query($sql);
if (!$result) {
    die('Query failed: ' . $conn->error);
}

// Safe print helper
function safe($val) {
    return htmlspecialchars($val ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Passes | Admin Panel</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body { background-color: #f5f6fa; font-family: Arial, sans-serif; }
    .container { margin-top: 40px; }
    h2 { color: #2f4f4f; text-align: center; margin-bottom: 30px; }
    .table th { background-color: #2f4f4f; color: white; }
    .btn-danger { background-color: #b22222; border: none; }
    .btn-danger:hover { background-color: #8b0000; }
    .btn-secondary { background-color: #556b2f; border: none; }
    .btn-secondary:hover { background-color: #3b5323; }
    .badge { font-size: 0.9em; }
  </style>
</head>
<body>
<div class="container">
  <h2>🚗 Manage Passes</h2>

  <?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success text-center">
      <?= safe($_GET['msg']); ?>
    </div>
  <?php endif; ?>

  <table class="table table-bordered table-striped text-center align-middle">
    <thead>
      <tr>
        <th>Pass ID</th>
        <th>User Name</th>
        <th>Email</th>
        <th>Vehicle Number</th>
        <th>Pass Type</th>
        <th>Amount (₹)</th>
        <th>Payment Method</th>
        <th>Start Date</th>
        <th>Expiry Date</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= safe($row['pass_id']); ?></td>
          <td><?= safe($row['user_name']); ?></td>
          <td><?= safe($row['user_email']); ?></td>
          <td><?= safe($row['vehicle_number']); ?></td>
          <td><?= safe($row['pass_type']); ?></td>
          <td><?= number_format((float)$row['amount'], 2); ?></td>
          <td><?= safe($row['payment_method']); ?></td>
          <td><?= safe($row['start_date']); ?></td>
          <td><?= safe($row['expiry_date']); ?></td>
          <td>
            <?php if ($row['status'] === 'Active'): ?>
              <span class="badge bg-success">Active</span>
            <?php elseif ($row['status'] === 'Expired'): ?>
              <span class="badge bg-danger">Expired</span>
            <?php else: ?>
              <span class="badge bg-secondary"><?= safe($row['status']); ?></span>
            <?php endif; ?>
          </td>
          <td>
            <a href="manage_pass.php?delete_id=<?= safe($row['pass_id']); ?>" 
               class="btn btn-danger btn-sm"
               onclick="return confirm('Are you sure you want to delete this pass?');">
              Delete
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <div class="text-center mt-3">
    <a href="admin_dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
  </div>
</div>
</body>
</html>

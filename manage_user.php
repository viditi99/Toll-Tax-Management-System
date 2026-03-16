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

// Delete user (use prepared statement)
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_user.php?msg=User+Deleted+Successfully");
    exit();
}

// Fetch all users
$result = $conn->query("SELECT id, name, email, vehicle_number, role FROM users ORDER BY id DESC");
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Helper function to safely escape output
function safe($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Users | Admin Panel</title>
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
  </style>
</head>
<body>
<div class="container">
  <h2>👥 Manage Users</h2>

  <?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success text-center">
      <?= safe($_GET['msg']); ?>
    </div>
  <?php endif; ?>

  <table class="table table-bordered table-striped text-center align-middle">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Vehicle Number</th>
        <th>Role</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= safe($row['id']); ?></td>
          <td><?= safe($row['name']); ?></td>
          <td><?= safe($row['email']); ?></td>
          <td><?= safe($row['vehicle_number']); ?></td>
          <td><?= safe($row['role']); ?></td>
          <td>
            <a href="manage_user.php?delete_id=<?= safe($row['id']); ?>" 
               class="btn btn-danger btn-sm"
               onclick="return confirm('Are you sure you want to delete this user?');">
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

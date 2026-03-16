<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('db.php');

// Check admin login
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch total revenue
$totalRevenueResult = $conn->query("SELECT SUM(amount) AS total FROM passes");
$totalRevenue = $totalRevenueResult->fetch_assoc()['total'] ?? 0;

// Fetch monthly revenue
$monthlyRevenue = $conn->query("
    SELECT 
        DATE_FORMAT(start_date, '%Y-%m') AS month,
        SUM(amount) AS total
    FROM passes
    GROUP BY DATE_FORMAT(start_date, '%Y-%m')
    ORDER BY month DESC
");

// Fetch payment method breakdown
$paymentMethods = $conn->query("
    SELECT payment_method, SUM(amount) AS total 
    FROM passes 
    GROUP BY payment_method
");

function safe($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Revenue Reports | Admin Panel</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body { background-color: #f5f6fa; font-family: Arial, sans-serif; }
    .container { margin-top: 40px; }
    h2, h3 { color: #2f4f4f; text-align: center; margin-bottom: 25px; }
    .card { box-shadow: 0 4px 10px rgba(0,0,0,0.1); border: none; border-radius: 10px; }
    .card-header { background-color: #2f4f4f; color: white; font-weight: bold; }
    .btn-secondary { background-color: #556b2f; border: none; }
    .btn-secondary:hover { background-color: #3b5323; }
  </style>
</head>
<body>
<div class="container">
  <h2>📊 Revenue Reports</h2>

  <div class="row mb-4">
    <div class="col-md-4 offset-md-4">
      <div class="card text-center">
        <div class="card-header">💰 Total Revenue</div>
        <div class="card-body">
          <h3>₹<?= number_format($totalRevenue, 2); ?></h3>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Monthly Revenue -->
    <div class="col-md-6 mb-4">
      <div class="card">
        <div class="card-header text-center">📅 Monthly Revenue</div>
        <div class="card-body">
          <table class="table table-bordered table-striped text-center align-middle">
            <thead>
              <tr>
                <th>Month</th>
                <th>Total Revenue (₹)</th>
              </tr>
            </thead>
            <tbody>
              <?php while($row = $monthlyRevenue->fetch_assoc()): ?>
                <tr>
                  <td><?= safe($row['month']); ?></td>
                  <td><?= number_format((float)$row['total'], 2); ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Payment Method Revenue -->
    <div class="col-md-6 mb-4">
      <div class="card">
        <div class="card-header text-center">💳 Payment Method Breakdown</div>
        <div class="card-body">
          <table class="table table-bordered table-striped text-center align-middle">
            <thead>
              <tr>
                <th>Payment Method</th>
                <th>Total Amount (₹)</th>
              </tr>
            </thead>
            <tbody>
              <?php while($row = $paymentMethods->fetch_assoc()): ?>
                <tr>
                  <td><?= safe($row['payment_method']); ?></td>
                  <td><?= number_format((float)$row['total'], 2); ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="text-center mt-3">
    <a href="admin_dashboard.php" class="btn btn-secondary">⬅ Back to Dashboard</a>
  </div>
</div>
</body>
</html>

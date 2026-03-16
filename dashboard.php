<?php
session_start();
include('db.php');

if(!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}

$id = $_SESSION['user_id'];

$conn->query("UPDATE passes SET status='Expired' WHERE expiry_date < CURDATE()");

$sql = "SELECT * FROM passes WHERE user_id='$id' ORDER BY pass_id DESC LIMIT 1";
$result = $conn->query($sql);
$pass = $result->fetch_assoc();

$isNewPass = false;
if ($pass) {
    $purchaseDate = strtotime($pass['start_date']);
    $now = strtotime(date('Y-m-d'));
    if (($now - $purchaseDate) <= 86400) {
        $isNewPass = true;
    }
}

/* ✅ EXPIRY ALERT LOGIC */
$expiryAlert = false;

if ($pass && $pass['status'] == 'Active') {

    $today = strtotime(date('Y-m-d'));
    $expiry = strtotime($pass['expiry_date']);

    $daysLeft = ($expiry - $today) / (60*60*24);

    if($daysLeft == 1){
        $expiryAlert = true;
    }
}

$totalPasses = $conn->query("SELECT COUNT(*) as total FROM passes WHERE user_id='$id'")->fetch_assoc()['total'];
$activePasses = $conn->query("SELECT COUNT(*) as active FROM passes WHERE user_id='$id' AND status='Active'")->fetch_assoc()['active'];
$expiredPasses = $conn->query("SELECT COUNT(*) as expired FROM passes WHERE user_id='$id' AND status='Expired'")->fetch_assoc()['expired'];

$userQuery = $conn->query("SELECT name, email, vehicle_number FROM users WHERE id='$id'");
$user = $userQuery->fetch_assoc();

$rates = $conn->query("SELECT * FROM toll_rates ORDER BY vehicle_type ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>User Dashboard - Toll Tax System</title>

<style>

* {margin:0; padding:0; box-sizing:border-box; font-family:"Poppins", sans-serif;}

body {
display:flex;
min-height:100vh;
background:#fff8f5;
}

.sidebar {
width:250px;
background:linear-gradient(135deg,#ff7e5f,#feb47b);
color:white;
padding:20px;
position:fixed;
height:100%;
border-top-right-radius:20px;
border-bottom-right-radius:20px;
box-shadow:3px 0 10px rgba(0,0,0,0.1);
}

.sidebar h2 {
text-align:center;
margin-bottom:30px;
}

.sidebar ul {
list-style:none;
}

.sidebar ul li {
padding:12px;
margin:8px 0;
border-radius:10px;
cursor:pointer;
transition:0.3s;
}

.sidebar ul li:hover,
.sidebar ul li.active {
background:rgba(255,255,255,0.25);
}

.main-content {
margin-left:270px;
flex-grow:1;
padding:30px;
}

header {
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:25px;
}

.header-title {
font-size:1.5rem;
font-weight:600;
color:#ff7e5f;
}

.header-actions a {
text-decoration:none;
color:white;
background:#ff7e5f;
padding:8px 16px;
border-radius:8px;
margin-left:10px;
}

.stats {
display:grid;
grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
gap:15px;
margin-bottom:30px;
}

.stat-box {
background:white;
border-radius:12px;
padding:20px;
box-shadow:0 4px 12px rgba(0,0,0,0.05);
text-align:center;
}

.stat-box h3 {
font-size:2rem;
color:#ff7e5f;
}

.card {
background:white;
padding:25px;
border-radius:15px;
box-shadow:0 4px 12px rgba(0,0,0,0.05);
margin-bottom:20px;
}

.status-active {
color:green;
font-weight:bold;
}

.status-expired {
color:red;
font-weight:bold;
}

.highlight {
background:#fff5e6;
border:2px solid #ff7e5f;
}

.download-btn{
background:linear-gradient(135deg,#ff7e5f,#feb47b);
color:white;
border:none;
padding:10px 18px;
border-radius:8px;
cursor:pointer;
margin-top:10px;
}

.qr-pass{
margin-top:15px;
text-align:center;
}

.qr-pass img{
width:150px;
}

</style>
</head>

<body>

<div class="sidebar">
<h2>🚗 Toll System</h2>
<ul>
<li class="active">🏠 Dashboard</li>
<li onclick="location.href='buy_pass.php'">💳 Book / Pay Toll</li>
<li onclick="location.href='report_issue.php'">⚠️ Report Problem</li>
<li onclick="location.href='logout.php'">🚪 Logout</li>
</ul>
</div>

<div class="main-content">

<header>
<div class="header-title">
Welcome, <?php echo htmlspecialchars($user['name']); ?> 👋
</div>

<div class="header-actions">
<a href="logout.php">Logout</a>
</div>
</header>

<!-- PROFILE -->

<div class="card">
<h3>👤 Profile Information</h3>

<p><b>Name:</b> <?= $user['name']; ?></p>
<p><b>Email:</b> <?= $user['email']; ?></p>
<p><b>Vehicle Number:</b> <?= $user['vehicle_number']; ?></p>

</div>

<!-- STATS -->

<div class="stats">

<div class="stat-box">
<h3><?= $totalPasses; ?></h3>
<p>Total Passes</p>
</div>

<div class="stat-box">
<h3><?= $activePasses; ?></h3>
<p>Active Passes</p>
</div>

<div class="stat-box">
<h3><?= $expiredPasses; ?></h3>
<p>Expired Passes</p>
</div>

</div>

<?php if($activePasses > 0) { ?>

<div class="card" style="background:#fff3eb;border-left:5px solid #ff7e5f;">

<h3>⚠ Active Pass Notice</h3>

<p>You already have an <b>Active Toll Pass</b>.</p>

<p>You can purchase a new pass after <b><?= $pass['expiry_date']; ?></b>.</p>

</div>

<?php } ?>

<?php if($expiryAlert){ ?>

<div style="background:#fff3cd;border-left:6px solid #ff7e5f;padding:15px;margin-bottom:20px;border-radius:8px;">

⚠ Your <b><?= $pass['pass_type']; ?></b> pass will expire on 
<b><?= date("d F", strtotime($pass['expiry_date'])); ?></b>.

<br>

<a href="buy_pass.php" style="color:#ff7e5f;font-weight:bold;">
Renew Now
</a>

</div>

<?php } ?>

<!-- LATEST PASS -->

<div class="card <?= $isNewPass ? 'highlight' : ''; ?>">

<h3>🎫 Latest Pass Details</h3>

<?php if($pass){ ?>

<p><b>Type:</b> <?= $pass['pass_type']; ?></p>

<p><b>Start Date:</b> <?= $pass['start_date']; ?></p>

<p><b>Expiry Date:</b> <?= $pass['expiry_date']; ?></p>

<p><b>Status:</b>

<span class="<?= ($pass['status']=='Active') ? 'status-active' : 'status-expired'; ?>">

<?= $pass['status']; ?>

</span>

</p>

<?php if(!empty($pass['qr_code'])){ ?>

<div class="qr-pass">

<p><b>Your Toll Pass QR</b></p>

<img src="<?= $pass['qr_code']; ?>">

<br>

<a href="<?= $pass['qr_code']; ?>" download>

<button class="download-btn">⬇ Download Pass</button>

</a>

</div>

<?php } ?>

<?php } else { ?>

<p>No pass found. <a href="buy_pass.php">Book Now</a></p>

<?php } ?>

</div>

</div>

</body>
</html>

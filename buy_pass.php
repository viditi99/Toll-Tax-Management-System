<?php
session_start();
include('db.php');
include('phpqrcode/qrlib.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['user_id'];
$userQuery = $conn->query("SELECT name, vehicle_number FROM users WHERE id='$id'");
$user = $userQuery->fetch_assoc();

$passRates = [
    "Weekly" => 200,
    "Monthly" => 700,
    "Yearly" => 8000
];

if (isset($_POST['buy'])) {

 // Update expired passes first
    $conn->query("UPDATE passes SET status='Expired' WHERE expiry_date < CURDATE()");

    // Check if active pass exists
    $check = $conn->query("SELECT * FROM passes WHERE user_id='$id' AND status='Active'");

    if($check->num_rows > 0){
        echo "<script>alert('You already have an active pass. Please wait until it expires.'); window.location='dashboard.php';</script>";
        exit();
    }

    $vehicle = $_POST['vehicle_no'];
    $owner = $_POST['owner_name'];
    $pass_type = $_POST['pass_type'];
    $method = $_POST['payment_method'];

    $start = date('Y-m-d');

    $amount = $passRates[$pass_type];

    if ($pass_type == "Weekly")
        $expiry = date('Y-m-d', strtotime("+7 days"));
    elseif ($pass_type == "Monthly")
        $expiry = date('Y-m-d', strtotime("+30 days"));
    else
        $expiry = date('Y-m-d', strtotime("+365 days"));

    // Generate Pass ID
    $pass_id = "PASS" . rand(10000,99999);

    // QR verification link
    $qrData = "http://localhost/tolltaxproject/verify_pass.php?pass_id=".$pass_id;

    $qrFile = "qrcodes/".$pass_id.".png";

    // Generate QR Code
    QRcode::png($qrData, $qrFile, 'L', 5);

    $sql = "INSERT INTO passes 
    (user_id, pass_id, vehicle_no, owner_name, pass_type, amount, start_date, expiry_date, status, payment_method, qr_code)
    VALUES 
    ('$id','$pass_id','$vehicle','$owner','$pass_type','$amount','$start','$expiry','Active','$method','$qrFile')";

    if ($conn->query($sql)) {

        $success = true;

    } else {

        echo "<script>alert('Database Error: ".$conn->error."');</script>";

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Buy Toll Pass</title>

<style>

body{
font-family:Poppins,sans-serif;
background:#faf4ed;
padding:30px;
}

form{
background:white;
width:380px;
margin:auto;
padding:25px;
border-radius:12px;
box-shadow:0 4px 12px rgba(0,0,0,0.1);
}

label{
font-weight:500;
margin-top:12px;
display:block;
}

input,select,button{
width:100%;
padding:10px;
margin-top:6px;
border-radius:8px;
border:1px solid #ccc;
}

button{
background:#ff7e5f;
color:white;
font-weight:bold;
border:none;
cursor:pointer;
}

#amountDisplay{
margin-top:10px;
padding:10px;
background:#fff5e8;
text-align:center;
border-radius:8px;
color:#e86b42;
font-weight:bold;
}

.passBox{
background:white;
width:380px;
margin:20px auto;
padding:20px;
border-radius:10px;
text-align:center;
box-shadow:0 4px 12px rgba(0,0,0,0.1);
}
.back-btn{
width:auto;
background:#ff7e5f;
color:white;
border:none;
padding:10px 18px;
border-radius:8px;
font-size:14px;
cursor:pointer;
transition:0.3s;
}

.back-btn:hover{
background:#ff7e5f;
}

</style>

<script>

const passRates = {
"Weekly":200,
"Monthly":700,
"Yearly":8000
};

function updateAmount(){

const type=document.getElementById("pass_type").value;

const amount=passRates[type] || 0;

document.getElementById("amountDisplay").innerText="Pass Amount: ₹"+amount;

}

</script>

</head>

<body>

<h2 style="text-align:center;color:#ff7e5f;">💳 Buy Toll Pass</h2>

<form method="POST">

<label>Vehicle Number:</label>
<input type="text" name="vehicle_no"
value="<?= htmlspecialchars($user['vehicle_number']); ?>" readonly>


<label>Owner Name:</label>
<input type="text" name="owner_name"
value="<?= htmlspecialchars($user['name']); ?>" readonly>


<label>Select Pass Type:</label>
<select name="pass_type" id="pass_type" onchange="updateAmount()" required>

<option value="">-- Select Pass Type --</option>
<option value="Weekly">Weekly</option>
<option value="Monthly">Monthly</option>
<option value="Yearly">Yearly</option>

</select>

<div id="amountDisplay">Pass Amount: ₹0</div>

<label>Select Payment Method:</label>

<select name="payment_method" required>

<option value="">-- Select Payment Method --</option>
<option value="UPI">UPI</option>
<option value="Credit Card">Credit Card</option>
<option value="Debit Card">Debit Card</option>
<option value="Net Banking">Net Banking</option>

</select>

<button type="submit" name="buy">Pay & Buy Pass</button>

</form>

<?php if(isset($success)){ ?>

<div class="passBox">

<h3 style="color:green;">Pass Generated Successfully</h3>

<p><b>Pass ID:</b> <?php echo $pass_id; ?></p>

<p><b>Vehicle:</b> <?php echo $vehicle; ?></p>

<p><b>Owner:</b> <?php echo $owner; ?></p>

<p><b>Valid Till:</b> <?php echo $expiry; ?></p>

<img src="<?php echo $qrFile; ?>" width="200">

<p>Scan this QR at Toll Booth</p>

</div>

<?php } ?>

<div style="text-align:center;margin-bottom:15px;">
  <button type="button" class="back-btn" onclick="window.location.href='dashboard.php'">
  ⬅ Back to Dashboard
  </button>
</div>

</body>
</html>

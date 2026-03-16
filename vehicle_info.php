<?php
session_start();
include('db.php');
$id = $_SESSION['user_id'];

$user = $conn->query("SELECT * FROM users WHERE id='$id'")->fetch_assoc();
if(isset($_POST['update'])){
  $vehicle = $_POST['vehicle_no'];
  $conn->query("UPDATE users SET vehicle_no='$vehicle' WHERE id='$id'");
  echo "<script>alert('Vehicle info updated!'); window.location='dashboard.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Vehicle Info</title>
<style>
body {font-family:Poppins; background:#f8f9fa; padding:40px;}
form {
  background:white; width:350px; margin:auto; padding:25px;
  border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1);
}
input, button {width:100%; padding:10px; margin-top:10px; border:1px solid #ccc; border-radius:8px;}
button {background:#ff7e5f; color:white; border:none; font-weight:bold; cursor:pointer;}
button:hover {background:#e86441;}
</style>
</head>
<body>
<h2 style="text-align:center;">🚘 Update Vehicle Info</h2>
<form method="POST">
  <label>Vehicle Number:</label>
  <input type="text" name="vehicle_no" value="<?php echo $user['vehicle_no']; ?>" required>
  <button type="submit" name="update">Update</button>
</form>
</body>
</html>

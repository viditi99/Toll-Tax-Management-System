<?php
include('db.php');

$pass_id = $_GET['pass_id'];

$sql = "SELECT * FROM passes WHERE pass_id='$pass_id'";
$result = $conn->query($sql);

if($result->num_rows > 0){

$row = $result->fetch_assoc();

$today = date('Y-m-d');

if($today <= $row['expiry_date']){
$status = "VALID PASS";
$color = "green";
}else{
$status = "EXPIRED PASS";
$color = "red";
}

echo "<h2 style='color:$color;'>$status</h2>";
echo "Vehicle No: ".$row['vehicle_no']."<br>";
echo "Owner: ".$row['owner_name']."<br>";
echo "Expiry Date: ".$row['expiry_date'];

}
else{
echo "Invalid Pass";
}
?>

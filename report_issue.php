<?php
session_start();
include('db.php');
$id = $_SESSION['user_id'];

if(isset($_POST['submit'])){
  $type = $_POST['issue_type'];
  $desc = $_POST['description'];
  $conn->query("INSERT INTO issues (user_id, issue_type, description, status)
                VALUES ('$id', '$type', '$desc', 'Pending')");
  echo "<script>alert('Issue reported successfully!'); window.location='dashboard.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Report Issue</title>
<style>
body {font-family:Poppins; background:#f4f6f8; padding:30px;}
form {
  background:white; width:400px; margin:auto; padding:25px;
  border-radius:10px; box-shadow:0 3px 10px rgba(0,0,0,0.1);
}
select, textarea, button {width:100%; padding:10px; margin-top:10px; border:1px solid #ccc; border-radius:8px;}
button {background:#ff7e5f; color:white; border:none; font-weight:bold; cursor:pointer;}
button:hover {background:#e86441;}
</style>
</head>
<body>
<h2 style="text-align:center;">⚠️ Report an Issue</h2>
<form method="POST">
  <label>Issue Type:</label>
  <select name="issue_type" required>
    <option value="Payment Issue">Payment Issue</option>
    <option value="Pass Not Working">Pass Not Working</option>
    <option value="Other">Other</option>
  </select>

  <label>Description:</label>
  <textarea name="description" rows="4" placeholder="Describe the issue..." required></textarea>

  <button type="submit" name="submit">Submit</button>
</form>
</body>
</html>
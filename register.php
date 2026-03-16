<?php
require 'db.php';

$err = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $vehicle = trim($_POST['vehicle_number']);
    $password = $_POST['password'];

    if(!$name || !$email || !$vehicle || !$password){
        $err = 'Please fill all fields.';
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name,email,vehicle_number,password) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss",$name,$email,$vehicle,$password);
        if($stmt->execute()){
            $success = "Registered successfully. <a href='login.php'>Login</a>";
            $stmt->close();
        } else {
            $err = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register | Toll Pass System</title>

<!-- Google Font & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css">

<style>
/* ===== BASE STYLE ===== */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Quicksand", sans-serif;
}

body {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background: linear-gradient(135deg, #a100ff, #fbc2eb);
  overflow: hidden;
}

/* ===== FORM CONTAINER ===== */
.register-container {
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.25);
  padding: 40px 45px;
  border-radius: 25px;
  width: 380px;
  color: white;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
  text-align: center;
  animation: fadeIn 1s ease;
}

/* ===== HEADINGS ===== */
.register-container h3 {
  margin-bottom: 10px;
  font-size: 26px;
  letter-spacing: 1px;
  color: #fff;
}

.register-container p {
  font-size: 13px;
  margin-bottom: 25px;
  color: #f6e8ff;
}

/* ===== FORM ===== */
form {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.input-group {
  position: relative;
}

.input-group i {
  position: absolute;
  top: 12px;
  left: 12px;
  color: #a100ff;
  font-size: 18px;
}

input {
  width: 100%;
  padding: 12px 40px;
  border: none;
  border-radius: 10px;
  background: rgba(255,255,255,0.9);
  color: #333;
  font-size: 15px;
  transition: 0.3s;
}

input:focus {
  outline: none;
  box-shadow: 0 0 8px rgba(161,0,255,0.7);
}

/* ===== BUTTON ===== */
.btn {
  background: linear-gradient(90deg, #ff758c, #ff7eb3);
  color: white;
  border: none;
  padding: 12px;
  border-radius: 10px;
  cursor: pointer;
  font-size: 16px;
  transition: 0.3s;
}

.btn:hover {
  transform: scale(1.05);
  background: linear-gradient(90deg, #ff7eb3, #ff758c);
}

/* ===== ALERTS ===== */
.alert {
  padding: 12px;
  margin-bottom: 15px;
  border-radius: 10px;
  font-size: 14px;
  text-align: left;
}

.alert-success {
  background: rgba(40,167,69,0.15);
  border-left: 5px solid #28a745;
  color: #d4edda;
}

.alert-danger {
  background: rgba(220,53,69,0.15);
  border-left: 5px solid #dc3545;
  color: #f8d7da;
}

/* ===== LOGIN LINK ===== */
.login-link {
  margin-top: 20px;
  font-size: 14px;
  color: #f1f1f1;
}

.login-link a {
  color: #ffb6f1;
  text-decoration: none;
  font-weight: 600;
}

.login-link a:hover {
  text-decoration: underline;
}

/* ===== ANIMATION ===== */
@keyframes fadeIn {
  from {opacity: 0; transform: translateY(-10px);}
  to {opacity: 1; transform: translateY(0);}
}

/* ===== RESPONSIVE ===== */
@media (max-width: 480px) {
  .register-container {
    width: 90%;
    padding: 30px;
  }
}
</style>
</head>
<body>

<div class="register-container">
  <h3>🚗 Create Account</h3>
  <p>Register to manage your Toll Pass easily.</p>

  <?php if($err): ?>
    <div class="alert alert-danger"><?= $err ?></div>
  <?php elseif($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="input-group">
      <i class="ri-user-line"></i>
      <input name="name" placeholder="Full Name" required>
    </div>
    <div class="input-group">
      <i class="ri-mail-line"></i>
      <input name="email" type="email" placeholder="Email Address" required>
    </div>
    <div class="input-group">
      <i class="ri-car-line"></i>
      <input name="vehicle_number" placeholder="Vehicle Number" required>
    </div>
    <div class="input-group">
      <i class="ri-lock-2-line"></i>
      <input name="password" type="password" placeholder="Password" required>
    </div>
    <button class="btn" name="register">Register</button>
  </form>

  <div class="login-link">
    Already have an account? <a href="login.php">Login</a>
  </div>
</div>

<?php require 'footer.php'; ?>
</body>
</html>

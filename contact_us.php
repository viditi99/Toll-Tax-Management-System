<?php
include('db.php'); 

$success = "";
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($message)) {
        $error = "⚠️ Please fill all fields before submitting.";
    } else {
        $stmt = $conn->prepare("INSERT INTO contact_us (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
            $success = "✅ Thank you, $name! Your message has been sent successfully.";
        } else {
            $error = "❌ Something went wrong. Please try again later.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us - Toll Tax Management System</title>
<style>
    body {
        background: #f0f4f7;
        font-family: "Poppins", sans-serif;
        color: #333;
    }
    .contact-container {
        max-width: 600px;
        margin: 50px auto;
        background: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        color: #004d40;
        margin-bottom: 20px;
    }
    label {
        font-weight: 500;
        display: block;
        margin-bottom: 5px;
    }
    input, textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 15px;
        font-size: 15px;
    }
    button {
        width: 100%;
        background-color: #00796b;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
    }
    button:hover {
        background-color: #004d40;
    }
    .msg {
        text-align: center;
        padding: 10px;
        font-weight: 500;
        border-radius: 5px;
        margin-bottom: 15px;
    }
    .success {
        background: #d4edda;
        color: #155724;
    }
    .error {
        background: #f8d7da;
        color: #721c24;
    }
</style>
</head>
<body>

<div class="contact-container">
    <h2>📞 Contact Us</h2>

    <?php if($success): ?>
        <div class="msg success"><?= $success ?></div>
    <?php elseif($error): ?>
        <div class="msg error"><?= $error ?></div>
    <?php endif; ?>
    <?php if(!$success): ?>
        <form method="POST">
            <label for="name">Full Name:</label>
            <input type="text" name="name" id="name" placeholder="Enter your name" required>

            <label for="email">Email Address:</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>

            <label for="message">Message:</label>
            <textarea name="message" id="message" rows="4" placeholder="Type your message here..." required></textarea>

            <button type="submit">Send Message</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
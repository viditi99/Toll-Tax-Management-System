<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('db.php'); 

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Fetch admin from admins table
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if admin exists
    if ($result->num_rows === 1) {

        $admin = $result->fetch_assoc();

        if ($admin['password'] === $password) {

            // Save admin session
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];

            header("Location: admin_dashboard.php");
            exit();

        } else {
            $error = "❌ Incorrect password!";
        }

    } else {
        $error = "❌ Admin not found!";
    }
}
?>  
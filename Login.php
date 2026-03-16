<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toll Tax Management - Login</title>
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #a8e6cf, #dcedc1);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: #ffffff; 
            padding: 40px 50px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            width: 380px;
            text-align: center;
            transition: 0.3s ease-in-out;
        }

        .login-container:hover {
            box-shadow: 0 20px 45px rgba(0,0,0,0.15);
        }

        h1 {
            margin-bottom: 25px;
            font-size: 28px;
            color: #43a047; 
        }

        .tab-buttons {
            display: flex;
            margin-bottom: 25px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #c8e6c9;
        }

        .tab-buttons button {
            flex: 1;
            padding: 12px;
            border: none;
            background: #c8e6c9; 
            color: #2e7d32;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            transition: 0.3s;
        }

        .tab-buttons button.active {
            background: #43a047; 
            color: #fff;
        }

        .login-form {
            display: none;
            flex-direction: column;
        }

        .login-form.active { display: flex; }

        .login-form input {
            padding: 14px;
            margin-bottom: 20px;
            border: 1px solid #c8e6c9;
            border-radius: 10px;
            background: #e8f5e9; 
            color: #2e7d32;
            font-size: 14px;
            transition: 0.3s;
        }

        .login-form input:focus {
            outline: none;
            border-color: #43a047;
            background: #ffffff;
        }

        .login-form input::placeholder { color: #555; }

        .login-form button {
            padding: 14px;
            background: #43a047; 
            border: none;
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
            font-size: 15px;
            transition: 0.3s;
            color: #fff;
        }

        .login-form button:hover { background: #2e7d32; }

        .login-container .register {
            margin-top: 18px;
            font-size: 14px;
            text-align: center;
            color: #555;
        }

        .login-container .register a {
            color: #43a047;
            text-decoration: none;
            font-weight: bold;
        }

        .login-container .register a:hover { text-decoration: underline; }

        @media (max-width: 400px) {
            .login-container { width: 90%; padding: 30px; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Toll Tax Management</h1>
        <div class="tab-buttons">
            <button id="userTab" class="active">User Login</button>
            <button id="adminTab">Admin Login</button>
        </div>
        <form id="userForm" class="login-form active" action="user_login.php" method="POST">
            <input type="text" name="email" placeholder="Enter your email address" required>
            <input type="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Login as User</button>
        </form>
        <form id="adminForm" class="login-form" action="admin_login.php" method="POST">
    <input type="text" name="username" placeholder="Enter your username" required>
    <input type="password" name="password" placeholder="Enter your password" required>
    <button type="submit">Login as Admin</button>
</form>
        <div class="register">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>
    <script>
        const userTab = document.getElementById('userTab');
        const adminTab = document.getElementById('adminTab');
        const userForm = document.getElementById('userForm');
        const adminForm = document.getElementById('adminForm');
        userTab.addEventListener('click', () => {
            userTab.classList.add('active');
            adminTab.classList.remove('active');
            userForm.classList.add('active');
            adminForm.classList.remove('active');
        });
        adminTab.addEventListener('click', () => {
            adminTab.classList.add('active');
            userTab.classList.remove('active');
            adminForm.classList.add('active');
            userForm.classList.remove('active');
        });
    </script>
</body>
</html>

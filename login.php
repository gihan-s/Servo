<?php
session_start();
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
    <style>
        body.login-bg {
            background: url('assets/img/landingBG.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
    </style>
</head>
<body class="login-bg">
    <div class="login-modal">
        <button class="close-btn fa-solid fa-xmark" onclick="window.location.href='index.php'"></button>
        <div class="login-modal-content">
            <div class="login-modal-header">
                <h2>Log in</h2>
                <p>Don't have an account? <a href="#">Sign up</a></p>
            </div>
            <form action="includes/login_process.php" method="POST" class="login-form">
                <label for="username">Your email</label>
                <input type="text" id="username" name="username" required placeholder="Enter your email">
                <label for="password">Your password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                    <span class="toggle-password" onclick="togglePasswordView()">Show</span>
                </div>
                <div class="forgot-link">
                    <a href="#">Forgot your password?</a>
                </div>
                <button type="submit" class="login-btn">Log in</button>
            </form>
        </div>
    </div>
    <script>
        function togglePasswordView() {
            var pwd = document.getElementById('password');
            var toggle = document.querySelector('.toggle-password');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                toggle.textContent = 'Hide';
            } else {
                pwd.type = 'password';
                toggle.textContent = 'Show';
            }
        }
    </script>
</body>
</html>

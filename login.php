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

    <!-- <link rel="stylesheet" href="styles.css"> -->
    <link rel="stylesheet" href="assets/css/login.css">
    <!-- <style>
        body.login-bg {
            background: url('assets/img/landingBG.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
    </style> -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/elements.css">
    <link rel="stylesheet" href="assets/css/GridTemplates.css">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">

    <script src="assets/js/elements.js" defer></script>
</head>
<body>
    <div class="login-modal">
        <button class="close-btn fa-solid fa-xmark" onclick="window.location.href='index.php'"></button>
        <div class="login-modal-content">
            <div class="login-modal-header">
                <h2>Log in to Servo</h2>
            </div>
            <br>
            <form action="includes/login_process.php" method="POST" class="login-form">
                <div class="text-container">
                    <div class="label text-label">Email</div>
                    <input type="text" id="username" name="username" required class="text-field">
                </div>
                <br>
                <div class="text-container">
                    <div class="label text-label">Password</div>
                    <input type="password" id="password" name="password" required class="text-field">
                    <span class="toggle-password" onclick="togglePasswordView()">Show</span>
                </div>
                <div class="forgot-link">
                    <a href="#">Forgot your password?</a>
                </div>
                <center><button type="submit" class="button outline">Log in as client</button></center>
            </form>
            <br>
            <div class="signup-link">
                <p>Don't have an account? <a href="signup.php">Sign up</a></p>
            </div>
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

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

    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/elements.css">
    <link rel="stylesheet" href="assets/css/GridTemplates.css">
    <link rel="stylesheet" href="assets/css/login.css">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">

    <!-- <script src="assets/js/elements.js" defer></script> -->
</head>
<body>
    <button class="home-btn" onclick="window.location.href='index.php'">Home</button>
    <div class="main-section">
        <img src="assets/img/logo.png" alt="Servo">
        <div class="login-header">
            <h2>Log in to continue</h2>
        </div>
        <form class="login-form" method="POST" action="client-login.php">
            <!-- add a toggle for provider and client -->
            <div class="toggle-section user-change">
                <div class="toggle-button active"><i class="fa-solid fa-user" style="padding-right: 10px"></i>Client</div>
                <div class="toggle-button"><i class="fa-solid fa-user-helmet-safety" style="padding-right: 10px"></i>Provider</div>
            </div>
            <div class="input-group">
                <i class="fa-solid fa-at"></i>
                <input type="email" id="email" name="email" placeholder="Email" aria-label="email" required>
            </div>
            <div class="input-group">
                <i class="fa-solid fa-key"></i>
                <input type="password" id="password" name="password" placeholder="Password" aria-label="password" minlength="8" required>
                <i class="fa-solid fa-eye toggle-password" id="togglePassword" tabindex="0" style="cursor:pointer" onclick="togglePasswordView(event)"></i>
            </div>
            <button type="submit" class="button">Continue as Client</button>
        </form>
    </div>
    <script src="assets/js/login.js" defer></script>
        <style>
        .home-btn {
            position: fixed;
            top: 20px;
            right: 30px;
            padding: 10px 24px;
            background: #008500;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            transition: background 0.2s;
        }
        .home-btn:hover {
            background: #006800;
        }
        </style>
</body>
</html>

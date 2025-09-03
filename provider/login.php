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

    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/elements.css">
    <link rel="stylesheet" href="../assets/css/GridTemplates.css">
    <link rel="stylesheet" href="../assets/css/login.css">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">

    <!-- <script src="assets/js/elements.js" defer></script> -->
     <style>
        .toggle-section {
			display: flex;
			justify-content: center;
			margin: 10px auto;
			margin-bottom: 40px;
			border: 1px solid #33333380;
			border-radius: 10px;
			padding: 5px;
		}

		.toggle-button {
			flex: 1;
			padding: 10px;
			cursor: pointer;
			text-align: center;
			border-radius: 10px;
			font-weight: 600;
			font-size: 16px;
			color: #333;
			transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
		}

		.toggle-button i{
			color: #333;
		}

		.toggle-button.active {
			background-color: #008500;
			color: white;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
		}

		.toggle-button.active i{
			color: white;
		}

     </style>
</head>
<body>
    <div class="main-section">
        <img src="../assets/img/logo.png" alt="Servo">
        <div class="login-header">
            <button class="back-btn fa-solid fa-arrow-left" onclick="window.location.href='../index.php'"></button>
            <h2>Log in to continue</h2>
        </div>
        <form class="login-form" method="POST" action="provider-login.php">
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
            <button type="submit" class="button">Continue as Provider</button>
        </form>
    </div>
    <script>
        // hide and show password
        function togglePasswordView(e) {
            var pwd = document.getElementById('password');
            var toggle = document.getElementById('togglePassword');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                toggle.classList.remove('fa-eye');
                toggle.classList.add('fa-eye-slash');
            } else {
                pwd.type = 'password';
                toggle.classList.remove('fa-eye-slash');
                toggle.classList.add('fa-eye');
            }
            if(e) e.preventDefault();
        }
        // form validation
        const form = document.querySelector('.login-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                // email validation
                const email = document.getElementById('email').value;
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(email)) {
                    alert('Please enter a valid email address.');
                    e.preventDefault();
                    return;
                }
                // password validation (must contain a number and a special character)
                const password = document.getElementById('password').value;
                const passwordPattern = /^(?=.*[0-9])(?=.*[!@#$%^&*])/;
                if (!passwordPattern.test(password)) {
                    alert('Password must contain at least one number and one special character.');
                    e.preventDefault();
                    return;
                }
          });
        }
    </script>
</body>
</html>

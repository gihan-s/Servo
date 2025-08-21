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
        <button class="close-btn" onclick="window.location.href='index.php'">&times;</button>
        <div class="login-modal-content">
            <div class="login-modal-header">
                <div class="avatar-circle"></div>
                <h2>Log in</h2>
                <p>Don't have an account? <a href="#">Sign up</a></p>
            </div>
            <div class="social-login">
                <button class="social-btn google-btn"><img src="https://img.icons8.com/color/24/000000/google-logo.png"/> Log in with Google</button>
                <button class="social-btn fb-btn"><img src="https://img.icons8.com/color/24/000000/facebook-new.png"/> Log in with Facebook</button>
            </div>
            <div class="divider"><span>OR</span></div>
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form action="includes/login_process.php" method="POST" class="login-form">
                <label for="username">Your email</label>
                <input type="text" id="username" name="username" required placeholder="Enter your email">
                <label for="password">Your password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                    <span class="toggle-password" onclick="togglePassword()">&#128065; Hide</span>
                </div>
                <div class="forgot-link">
                    <a href="#">Forgot your password?</a>
                </div>
                <button type="submit" class="login-btn">Log in</button>
            </form>
        </div>
    </div>
    <script>
        function togglePassword() {
            var pwd = document.getElementById('password');
            var toggle = document.querySelector('.toggle-password');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                toggle.textContent = '🙈 Show';
            } else {
                pwd.type = 'password';
                toggle.textContent = '👁️ Hide';
            }
        }
    </script>
</body>
</html>

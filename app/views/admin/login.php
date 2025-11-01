<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$baseURL = "../../../";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servo | Admin Login</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/../assets/css/elementStyles.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/../assets/css/gridTemplates.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/../assets/css/login.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
</head>

<body>
    <button class="button home-btn" onclick="window.location.href='/'">Home</button>
    <div class="main-section">
        <img src="<?= BASE_URL ?>/../assets/img/logo.png" alt="Servo">
        <?php
        $loginError = $_SESSION['login_error'] ?? null;
        ?>

        <?php if ($loginError): ?>
            <div
                style="background:#ffe8e8; color:#7a0b0b; border:1px solid #f5b5b5; padding:10px 14px; border-radius:6px; width:100%; max-width:420px; margin:0 auto 18px auto; font-size:14px; line-height:1.4; box-shadow:0 1px 2px rgba(0,0,0,.06);">
                <?= htmlspecialchars($loginError) ?>
            </div>
        <?php endif; ?>
        <div class="login-header">
            <h2>Admin Panel</h2>
        </div>

        <form class="login-form" method="POST" action="<?= BASE_URL ?>/login/authenticate">

            <div class="input-group">
                <i class="fa-solid fa-user"></i>
                <input type="username" id="username" name="username" placeholder="Username" aria-label="username" required>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-key"></i>
                <input type="password" id="password" name="password" placeholder="Password" aria-label="password" required>

                <i class="fa-solid fa-eye toggle-password" id="togglePassword" tabindex="0" onclick="togglePasswordView(event)"></i>
            </div>

            <button type="submit" class="button" id="submitBtn">Login as Admin</button>
        </form>
    </div>
    <script src="<?= BASE_URL ?>/../assets/js/login.js" defer></script>
</body>

</html>


<style>
    .login-header h2{
        font-size: 1.2em;
        color: #686868ff;
    }
</style>

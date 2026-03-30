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
    <link rel="stylesheet" href="<?= BASE_URL ?>/../assets/css/login-inline.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <button class="button home-btn" onclick="window.location.href='/'">Home</button>
    <div class="main-section">
        <img src="<?= BASE_URL ?>/../assets/img/logo.png" alt="Servo">
        <?php
        $loginError = $_SESSION['login_error'] ?? null;
        ?>

        <?php if ($loginError): ?>
            <div class="login-error-message">
                <?= htmlspecialchars($loginError) ?>
            </div>
        <?php endif; ?>
        <div class="login-header admin-login-header">
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

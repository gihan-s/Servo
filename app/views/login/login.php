<?php
// Reuse existing login UI with minimal changes: post to /login with type param
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/elements.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/GridTemplates.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/login.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
</head>
<body>
    <button class="button home-btn" onclick="window.location.href='<?= BASE_URL ?>/'">Home</button>
    <div class="main-section">
        <img src="<?= BASE_URL ?>/assets/img/logo.png" alt="Servo">
        <?php
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $loginType = $_SESSION['login_type'] ?? 'client';
        $loginError = $_SESSION['login_error'] ?? null; unset($_SESSION['login_error']);
        if (!empty($_SESSION['reg_pending_notice'])): ?>
            <div style="background:#fff8e1; color:#754c00; border:1px solid #f0d48a; padding:10px 14px; border-radius:6px; width:100%; max-width:420px; margin:0 auto 12px auto; font-size:14px; line-height:1.4; box-shadow:0 1px 2px rgba(0,0,0,.06);">
                <strong>Registration received.</strong><br />You will get an email after approved by an admin.
            </div>
        <?php unset($_SESSION['reg_pending_notice']); endif; ?>
        <?php if ($loginError): ?>
            <div style="background:#ffe8e8; color:#7a0b0b; border:1px solid #f5b5b5; padding:10px 14px; border-radius:6px; width:100%; max-width:420px; margin:0 auto 18px auto; font-size:14px; line-height:1.4; box-shadow:0 1px 2px rgba(0,0,0,.06);">
                <?= htmlspecialchars($loginError) ?>
            </div>
        <?php endif; ?>
        <div class="login-header">
            <h2>Log in to continue</h2>
        </div>
        <form class="login-form" method="POST" action="<?= BASE_URL ?>/login">
            <div class="toggle-section user-change">
                <div class="toggle-button <?= $loginType==='client' ? 'active':'' ?>" data-type="client"><i class="fa-solid fa-user"></i>Client</div>
                <div class="toggle-button <?= $loginType==='provider' ? 'active':'' ?>" data-type="provider"><i class="fa-solid fa-user-helmet-safety"></i>Provider</div>
            </div>
            <input type="hidden" name="type" id="loginType" value="<?= htmlspecialchars($loginType) ?>" />
            <div class="input-group">
                <i class="fa-solid fa-at"></i>
                <input type="email" id="email" name="email" placeholder="Email" aria-label="email" required>
            </div>
            <div class="input-group">
                <i class="fa-solid fa-key"></i>
                <input type="password" id="password" name="password" placeholder="Password" aria-label="password" minlength="8" required>
                <i class="fa-solid fa-eye toggle-password" id="togglePassword" tabindex="0"></i>
            </div>
            <div style="text-align:center; margin: 18px 0 8px 0; font-size:1rem; color:#444;">
                Don't have an account? <a href="<?= BASE_URL ?>/register" style="color:#14a800; font-weight:bold; text-decoration:none;">Register</a>
            </div>
            <button type="submit" class="button" id="submitBtn">Continue as Client</button>
        </form>
    </div>
    <script src="<?= BASE_URL ?>/assets/js/login.js" defer></script>
    <script>
        document.querySelectorAll('.user-change .toggle-button').forEach(function(btn){
            btn.addEventListener('click', function(){
                document.querySelectorAll('.user-change .toggle-button').forEach(b=>b.classList.remove('active'));
                btn.classList.add('active');
                var type = btn.getAttribute('data-type');
                document.getElementById('loginType').value = type;
                document.getElementById('submitBtn').textContent = type === 'provider' ? 'Continue as Provider' : 'Continue as Client';
            });
        });
        (function(){
             var restored = document.getElementById('loginType').value;
             document.getElementById('submitBtn').textContent = restored === 'provider' ? 'Continue as Provider' : 'Continue as Client';
        })();
    document.getElementById('togglePassword').addEventListener('click', function(){
        const input = document.getElementById('password');
        input.type = input.type === 'password' ? 'text' : 'password';
    });
    </script>
</body>
</html>



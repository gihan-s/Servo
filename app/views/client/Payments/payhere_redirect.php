<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to PayHere...</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .redirect-box { background: #fff; border-radius: 18px; padding: 48px 40px; text-align: center; max-width: 400px; width: 100%; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .spinner { width: 52px; height: 52px; border: 4px solid #dcfce7; border-top-color: #008500; border-radius: 50%; animation: spin .8s linear infinite; margin: 0 auto 20px; }
        @keyframes spin { to { transform: rotate(360deg); } }
        h1 { font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 8px; }
        p { font-size: 14px; color: #6b7280; }
        .sandbox-badge { display: inline-block; background: #fef3c7; color: #92400e; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 99px; border: 1px solid #fde68a; margin-top: 16px; letter-spacing: .04em; }
    </style>
</head>
<body>
<div class="redirect-box">
    <div class="spinner"></div>
    <h1>Redirecting to PayHere…</h1>
    <p>Please wait. You are being securely redirected to the PayHere payment gateway.</p>
    <?php if (PAYHERE_SANDBOX): ?>
        <div class="sandbox-badge">SANDBOX MODE</div>
    <?php endif; ?>
    <form id="payhere_form" method="POST" action="<?= htmlspecialchars($checkoutUrl, ENT_QUOTES, 'UTF-8') ?>">
        <?php foreach ($fields as $key => $value): ?>
            <input type="hidden" name="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>" value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>">
        <?php endforeach; ?>
    </form>
    <script>document.getElementById('payhere_form').submit();</script>
</div>
</body>
</html>

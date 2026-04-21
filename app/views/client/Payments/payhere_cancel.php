<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled — Servo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #fef2f2; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 24px; }
        .card { background: #fff; border-radius: 20px; padding: 48px 40px; max-width: 440px; width: 100%; text-align: center; box-shadow: 0 8px 32px rgba(220,38,38,0.08); }
        .icon-circle { width: 80px; height: 80px; border-radius: 50%; background: #fef2f2; border: 3px solid #fca5a5; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
        .icon-circle i { font-size: 36px; color: #dc2626; }
        h1 { font-size: 24px; font-weight: 800; color: #111827; margin-bottom: 8px; }
        .subtitle { font-size: 14px; color: #6b7280; margin-bottom: 28px; line-height: 1.6; }
        .actions { display: flex; gap: 10px; justify-content: center; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; }
        .btn-primary { background: #008500; color: #fff; }
        .btn-primary:hover { background: #006600; }
        .btn-outline { background: #fff; color: #374151; border: 1px solid #d1d5db; }
        .btn-outline:hover { background: #f9fafb; }
        .sandbox-badge { display: inline-block; background: #fef3c7; color: #92400e; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 99px; border: 1px solid #fde68a; margin-bottom: 16px; letter-spacing: .04em; }
    </style>
</head>
<body>
<div class="card">
    <?php if (defined('PAYHERE_SANDBOX') && PAYHERE_SANDBOX): ?>
        <div class="sandbox-badge">SANDBOX MODE</div>
    <?php endif; ?>

    <div class="icon-circle">
        <i class="fa-solid fa-circle-xmark"></i>
    </div>
    <h1>Payment Cancelled</h1>
    <p class="subtitle">
        You cancelled the payment process. Your project is still in <strong>Approved</strong> status.<br>
        You can try again from your projects page whenever you are ready.
    </p>

    <div class="actions">
        <a href="<?= BASE_URL ?>/projects" class="btn btn-primary">
            <i class="fa-solid fa-briefcase"></i> Back to Projects
        </a>
        <a href="<?= BASE_URL ?>/payments" class="btn btn-outline">
            <i class="fa-solid fa-receipt"></i> Payments
        </a>
    </div>
</div>
</body>
</html>

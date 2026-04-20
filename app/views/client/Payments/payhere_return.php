<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful — Servo</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/serviceProjects.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f0fdf4; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 24px; }
        .card { background: #fff; border-radius: 20px; padding: 48px 40px; max-width: 480px; width: 100%; text-align: center; box-shadow: 0 8px 32px rgba(0,133,0,0.10); }
        .icon-circle { width: 80px; height: 80px; border-radius: 50%; background: #f0fdf4; border: 3px solid #86efac; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
        .icon-circle i { font-size: 36px; color: #16a34a; }
        h1 { font-size: 24px; font-weight: 800; color: #111827; margin-bottom: 8px; }
        .subtitle { font-size: 14px; color: #6b7280; margin-bottom: 28px; line-height: 1.6; }
        .order-summary { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px 20px; text-align: left; margin-bottom: 28px; }
        .order-row { display: flex; justify-content: space-between; align-items: center; font-size: 13px; padding: 5px 0; }
        .order-row:not(:last-child) { border-bottom: 1px solid #f3f4f6; }
        .order-label { color: #6b7280; font-weight: 500; }
        .order-value { color: #111827; font-weight: 600; }
        .order-value.highlight { color: #15803d; font-size: 16px; font-weight: 800; }
        .notice { background: #fef9c3; border: 1px solid #fde047; border-radius: 10px; padding: 10px 14px; font-size: 12px; color: #713f12; margin-bottom: 24px; text-align: left; }
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
        <i class="fa-solid fa-circle-check"></i>
    </div>
    <h1>Payment Successful!</h1>
    <p class="subtitle">Thank you! Your payment has been received.<br>Your project will move to <strong>Ongoing</strong> shortly after confirmation.</p>

    <?php if ($order): ?>
    <div class="order-summary">
        <div class="order-row">
            <span class="order-label">Order ID</span>
            <span class="order-value"><?= htmlspecialchars($order['order_id'], ENT_QUOTES, 'UTF-8') ?></span>
        </div>
        <div class="order-row">
            <span class="order-label">Project</span>
            <span class="order-value"><?= htmlspecialchars($order['title'] ?? '—', ENT_QUOTES, 'UTF-8') ?></span>
        </div>
        <div class="order-row">
            <span class="order-label">Amount Paid</span>
            <span class="order-value highlight">LKR <?= htmlspecialchars(number_format((float)($order['amount'] ?? 0), 2), ENT_QUOTES, 'UTF-8') ?></span>
        </div>
        <div class="order-row">
            <span class="order-label">Currency</span>
            <span class="order-value"><?= htmlspecialchars($order['currency'] ?? 'LKR', ENT_QUOTES, 'UTF-8') ?></span>
        </div>
    </div>
    <?php endif; ?>

    <div class="notice">
        <i class="fa-solid fa-circle-info"></i>
        <strong> Note:</strong> Payment confirmation is processed by our server. If your project doesn't update within a few minutes, please contact support.
    </div>

    <div class="actions">
        <a href="<?= BASE_URL ?>/projects" class="btn btn-primary">
            <i class="fa-solid fa-briefcase"></i> My Projects
        </a>
        <a href="<?= BASE_URL ?>/payments" class="btn btn-outline">
            <i class="fa-solid fa-receipt"></i> Payments
        </a>
    </div>
</div>
</body>
</html>

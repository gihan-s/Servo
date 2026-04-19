<?php
$status       = $invoice['Status'] ?? 'Pending';
$isPaid       = $status === 'Paid';
$isRefunded   = in_array($status, ['Refunded', 'Refund Requested'], true);
$isRequested  = $status === 'Refund Requested';
$isCancelled  = $status === 'Cancelled';
$isAwaiting   = $status === 'Awaiting';
$isPending    = in_array($status, ['Pending', 'Hold'], true);

$modeBadge = match (true) {
    $isPaid       => ['class' => 'status-paid',      'label' => 'Paid'],
    $isRequested  => ['class' => 'status-refunded',  'label' => 'Refund Requested'],
    $isRefunded   => ['class' => 'status-refunded',  'label' => 'Refunded'],
    $isCancelled  => ['class' => 'status-refunded',  'label' => 'Cancelled'],
    $isAwaiting   => ['class' => 'status-pending',   'label' => 'Awaiting Payment'],
    default       => ['class' => 'status-pending',   'label' => 'Pending'],
};

$invoiceNum  = 'INV-' . (int) $invoice['Payment_ID'];
$issueDate   = !empty($invoice['Hold_Time'])   ? date('M d, Y', strtotime($invoice['Hold_Time']))   : (!empty($invoice['Started_At']) ? date('M d, Y', strtotime($invoice['Started_At'])) : '—');
$paidDate    = !empty($invoice['Paid_Time'])   ? date('M d, Y', strtotime($invoice['Paid_Time']))   : '—';
$dueDate     = !empty($invoice['Est_Delivery']) ? date('M d, Y', strtotime($invoice['Est_Delivery'])) : '—';
$amount      = (float) ($invoice['Amount'] ?? 0);
$commission  = (float) ($invoice['Commission'] ?? 0);
$subtotal    = max(0, $amount - $commission);

$clientName  = !empty($invoice['Client_Name'])   ? $invoice['Client_Name']   : '—';
$clientEmail = !empty($invoice['Client_Email'])  ? $invoice['Client_Email']  : '—';
$providerName  = !empty($invoice['Provider_Name'])  ? $invoice['Provider_Name']  : '—';
$providerEmail = !empty($invoice['Provider_Email']) ? $invoice['Provider_Email'] : '—';

$projectTitle = $invoice['Project_Title'] ?? '—';
$description  = $invoice['Description'] ?? '';
$postType     = $invoice['Post_Type'] ?? '—';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Servo | Invoice <?= htmlspecialchars($invoiceNum) ?></title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/elementStyles.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/payments.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

  <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

  <div class="main-content">
    <section class="service-requests">
      <div class="invoice-wrapper">
        <div class="invoice-back">
          <a href="<?= BASE_URL ?>/payments" class="invoice-back-link">
            <i class="fa-solid fa-arrow-left"></i> Back to Payments
          </a>
        </div>

        <article class="invoice-card">
          <header class="invoice-header">
            <div class="invoice-brand">
              <div class="invoice-brand-logo">Servo</div>
              <div class="invoice-brand-sub">Payment Receipt</div>
            </div>
            <div class="invoice-meta">
              <div class="status-badge <?= htmlspecialchars($modeBadge['class']) ?>"><?= htmlspecialchars($modeBadge['label']) ?></div>
              <h2 class="invoice-number"><?= htmlspecialchars($invoiceNum) ?></h2>
              <div class="invoice-meta-row"><span>Issued</span><span><?= htmlspecialchars($issueDate) ?></span></div>
              <?php if ($isPaid): ?>
                <div class="invoice-meta-row"><span>Paid</span><span><?= htmlspecialchars($paidDate) ?></span></div>
              <?php elseif ($isPending || $isAwaiting): ?>
                <div class="invoice-meta-row"><span>Est. Delivery</span><span><?= htmlspecialchars($dueDate) ?></span></div>
              <?php elseif ($isRefunded): ?>
                <div class="invoice-meta-row"><span><?= $isRequested ? 'Requested' : 'Refunded' ?></span><span><?= htmlspecialchars($paidDate !== '—' ? $paidDate : $issueDate) ?></span></div>
              <?php endif; ?>
            </div>
          </header>

          <?php if ($isRequested): ?>
            <div class="invoice-notice invoice-notice-warning">
              <i class="fa-solid fa-clock-rotate-left"></i>
              <span>Your refund request is pending admin approval. You'll be notified once it's processed.</span>
            </div>
          <?php elseif ($isAwaiting): ?>
            <div class="invoice-notice invoice-notice-info">
              <i class="fa-solid fa-circle-info"></i>
              <span>This project has been accepted. Fund this invoice to start the work.</span>
            </div>
          <?php elseif ($isCancelled): ?>
            <div class="invoice-notice invoice-notice-danger">
              <i class="fa-solid fa-ban"></i>
              <span>This invoice was cancelled.</span>
            </div>
          <?php endif; ?>

          <section class="invoice-parties">
            <div class="invoice-party">
              <div class="invoice-party-label">Billed To</div>
              <div class="invoice-party-name"><?= htmlspecialchars($clientName) ?></div>
              <div class="invoice-party-sub"><?= htmlspecialchars($clientEmail) ?></div>
            </div>
            <div class="invoice-party">
              <div class="invoice-party-label">Provider</div>
              <div class="invoice-party-name"><?= htmlspecialchars($providerName) ?></div>
              <div class="invoice-party-sub"><?= htmlspecialchars($providerEmail) ?></div>
            </div>
          </section>

          <section class="invoice-lines">
            <div class="invoice-lines-head">
              <div>Description</div>
              <div>Type</div>
              <div class="invoice-col-right">Amount</div>
            </div>
            <div class="invoice-lines-row">
              <div>
                <div class="invoice-line-title"><?= htmlspecialchars($projectTitle) ?></div>
                <?php if ($description !== ''): ?>
                  <div class="invoice-line-desc"><?= htmlspecialchars($description) ?></div>
                <?php endif; ?>
              </div>
              <div><?= htmlspecialchars($postType) ?></div>
              <div class="invoice-col-right">$<?= number_format($subtotal, 2) ?></div>
            </div>
          </section>

          <section class="invoice-totals">
            <div class="invoice-total-row">
              <span>Subtotal</span>
              <span>$<?= number_format($subtotal, 2) ?></span>
            </div>
            <div class="invoice-total-row">
              <span>Platform Fee (10%)</span>
              <span>$<?= number_format($commission, 2) ?></span>
            </div>
            <div class="invoice-total-row invoice-total-grand">
              <span>Total</span>
              <span>$<?= number_format($amount, 2) ?></span>
            </div>
          </section>

          <footer class="invoice-footer">
            <div class="invoice-footer-note">
              Questions about this invoice? Contact <a href="mailto:support@servo.local">support@servo.local</a>.
            </div>
            <div class="invoice-footer-actions">
              <a href="<?= BASE_URL ?>/payments" class="action-btn btn-secondary">
                <i class="fa-solid fa-list"></i> Back
              </a>
              <?php if ($isPending || $isAwaiting): ?>
                <form method="post" action="<?= BASE_URL ?>/payments/pay/<?= (int) $invoice['Payment_ID'] ?>" class="inline-action-form">
                  <button type="submit" class="action-btn btn-primary">
                    <i class="fa-solid fa-credit-card"></i> Pay Now
                  </button>
                </form>
                <form method="post" action="<?= BASE_URL ?>/payments/cancel/<?= (int) $invoice['Payment_ID'] ?>" class="inline-action-form" data-confirm="Cancel this payment and project? This cannot be undone.">
                  <button type="submit" class="action-btn btn-danger">
                    <i class="fa-solid fa-ban"></i> Cancel
                  </button>
                </form>
              <?php elseif ($isPaid): ?>
                <form method="post" action="<?= BASE_URL ?>/payments/refund/<?= (int) $invoice['Payment_ID'] ?>" class="inline-action-form" data-confirm="Request a refund for this payment? It will go to admin for approval.">
                  <button type="submit" class="action-btn btn-outline">
                    <i class="fa-solid fa-rotate-left"></i> Request Refund
                  </button>
                </form>
              <?php endif; ?>
            </div>
          </footer>
        </article>
      </div>
    </section>
  </div>

  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>

  <script>
    document.querySelectorAll('form.inline-action-form[data-confirm]').forEach(form => {
      form.addEventListener('submit', e => {
        if (!window.confirm(form.dataset.confirm)) e.preventDefault();
      });
    });
  </script>
</body>

</html>

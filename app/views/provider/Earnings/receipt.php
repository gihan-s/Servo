<?php
$status       = $receipt['Status'] ?? 'Pending';
$isPaid       = $status === 'Paid';
$isRefunded   = in_array($status, ['Refunded', 'Refund Requested'], true);
$isRequested  = $status === 'Refund Requested';
$isCancelled  = $status === 'Cancelled';
$isAwaiting   = $status === 'Awaiting';
$isPending    = in_array($status, ['Pending', 'Hold', 'Processing'], true);

$modeBadge = match (true) {
    $isPaid       => ['class' => 'status-paid',      'label' => 'Paid'],
    $isRequested  => ['class' => 'status-refunded',  'label' => 'Refund Requested'],
    $isRefunded   => ['class' => 'status-refunded',  'label' => 'Refunded'],
    $isCancelled  => ['class' => 'status-refunded',  'label' => 'Cancelled'],
    $isAwaiting   => ['class' => 'status-pending',   'label' => 'Awaiting Payment'],
    default       => ['class' => 'status-pending',   'label' => 'Pending'],
};

$receiptNum  = 'RCT-' . (int) $receipt['Payment_ID'];
$issueDate   = !empty($receipt['Hold_Time'])   ? date('M d, Y', strtotime($receipt['Hold_Time']))   : (!empty($receipt['Started_At']) ? date('M d, Y', strtotime($receipt['Started_At'])) : '—');
$paidDate    = !empty($receipt['Paid_Time'])   ? date('M d, Y', strtotime($receipt['Paid_Time']))   : '—';
$dueDate     = !empty($receipt['Est_Delivery']) ? date('M d, Y', strtotime($receipt['Est_Delivery'])) : '—';
$amount      = (float) ($receipt['Amount'] ?? 0);
$commission  = (float) ($receipt['Commission'] ?? 0);
$net         = max(0, $amount - $commission);

$clientName    = !empty($receipt['Client_Name'])    ? trim($receipt['Client_Name'])    : '—';
$clientEmail   = !empty($receipt['Client_Email'])   ? $receipt['Client_Email']         : '—';
$providerName  = !empty($receipt['Provider_Name'])  ? trim($receipt['Provider_Name'])  : '—';
$providerEmail = !empty($receipt['Provider_Email']) ? $receipt['Provider_Email']       : '—';

$projectTitle = $receipt['Project_Title'] ?? '—';
$description  = $receipt['Description']   ?? '';
$postType     = $receipt['Post_Type']     ?? '—';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Servo | Earnings Receipt <?= htmlspecialchars($receiptNum) ?></title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/elementStyles.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/payments.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/earnings.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

  <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>

  <div class="main-content">
    <section class="service-requests">
      <div class="invoice-wrapper">
        <div class="invoice-back">
          <a href="<?= BASE_URL ?>/earnings" class="invoice-back-link">
            <i class="fa-solid fa-arrow-left"></i> Back to Earnings
          </a>
        </div>

        <article class="invoice-card">
          <header class="invoice-header">
            <div class="invoice-brand">
              <div class="invoice-brand-logo">Servo</div>
              <div class="invoice-brand-sub">Earnings Receipt</div>
            </div>
            <div class="invoice-meta">
              <div class="status-badge <?= htmlspecialchars($modeBadge['class']) ?>"><?= htmlspecialchars($modeBadge['label']) ?></div>
              <h2 class="invoice-number"><?= htmlspecialchars($receiptNum) ?></h2>
              <div class="invoice-meta-row"><span>Issued</span><span><?= htmlspecialchars($issueDate) ?></span></div>
              <?php if ($isPaid): ?>
                <div class="invoice-meta-row"><span>Paid out</span><span><?= htmlspecialchars($paidDate) ?></span></div>
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
              <span>The client has requested a refund. Pending admin approval.</span>
            </div>
          <?php elseif ($isAwaiting): ?>
            <div class="invoice-notice invoice-notice-info">
              <i class="fa-solid fa-circle-info"></i>
              <span>Awaiting client payment. Earnings will be released once funded and the project is completed.</span>
            </div>
          <?php elseif ($isPending): ?>
            <div class="invoice-notice invoice-notice-info">
              <i class="fa-solid fa-hourglass-half"></i>
              <span>Payment is held. Net earnings will be released once the project clears.</span>
            </div>
          <?php elseif ($isCancelled): ?>
            <div class="invoice-notice invoice-notice-danger">
              <i class="fa-solid fa-ban"></i>
              <span>This transaction was cancelled.</span>
            </div>
          <?php elseif ($isRefunded): ?>
            <div class="invoice-notice invoice-notice-warning">
              <i class="fa-solid fa-rotate-left"></i>
              <span>This payment was refunded to the client.</span>
            </div>
          <?php endif; ?>

          <section class="invoice-parties">
            <div class="invoice-party">
              <div class="invoice-party-label">Client</div>
              <div class="invoice-party-name"><?= htmlspecialchars($clientName) ?></div>
              <div class="invoice-party-sub"><?= htmlspecialchars($clientEmail) ?></div>
            </div>
            <div class="invoice-party">
              <div class="invoice-party-label">Your account</div>
              <div class="invoice-party-name"><?= htmlspecialchars($providerName) ?></div>
              <div class="invoice-party-sub"><?= htmlspecialchars($providerEmail) ?></div>
            </div>
          </section>

          <section class="invoice-lines">
            <div class="invoice-lines-head">
              <div>Description</div>
              <div>Type</div>
              <div class="invoice-col-right">Gross Amount</div>
            </div>
            <div class="invoice-lines-row">
              <div>
                <div class="invoice-line-title"><?= htmlspecialchars($projectTitle) ?></div>
                <?php if ($description !== ''): ?>
                  <div class="invoice-line-desc"><?= htmlspecialchars($description) ?></div>
                <?php endif; ?>
              </div>
              <div><?= htmlspecialchars($postType) ?></div>
              <div class="invoice-col-right">$<?= number_format($amount, 2) ?></div>
            </div>
          </section>

          <section class="invoice-totals">
            <div class="invoice-total-row">
              <span>Gross Amount</span>
              <span>$<?= number_format($amount, 2) ?></span>
            </div>
            <div class="invoice-total-row">
              <span>Platform Fee (10%)</span>
              <span style="color:#b91c1c;">-$<?= number_format($commission, 2) ?></span>
            </div>
            <div class="invoice-total-row invoice-total-grand">
              <span>Net Earnings</span>
              <span style="color:#008500;">$<?= number_format($net, 2) ?></span>
            </div>
          </section>

          <footer class="invoice-footer">
            <div class="invoice-footer-note">
              Questions about your earnings? Contact <a href="mailto:support@servo.local">support@servo.local</a>.
            </div>
            <div class="invoice-footer-actions">
              <a href="<?= BASE_URL ?>/earnings" class="action-btn btn-secondary">
                <i class="fa-solid fa-list"></i> Back to Earnings
              </a>
            </div>
          </footer>
        </article>
      </div>
    </section>
  </div>

  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</body>

</html>

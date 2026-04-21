<?php
$currentTab = $_GET['tab'] ?? 'completed';
$currentSearch = trim((string) ($_GET['q'] ?? ''));
$currentSort = $_GET['sort'] ?? 'recent';
$currentPage = max(1, (int) ($_GET['page'] ?? 1));

$sortOptions = [
    'recent'      => 'Most Recent',
    'oldest'      => 'Oldest First',
    'amount-desc' => 'Amount: High to Low',
    'amount-asc'  => 'Amount: Low to High',
];

$tabs = [
    'completed' => ['label' => 'Completed Payments', 'items' => $completedPayments, 'total' => $completedTotal, 'pages' => $completedPages],
    'refunded'  => ['label' => 'Refunded Payments',  'items' => $refundedPayments,  'total' => $refundedTotal,  'pages' => $refundedPages],
];
if (!isset($tabs[$currentTab])) $currentTab = 'completed';

function paymentsUrl(array $overrides = []): string
{
    $params = array_merge([
      'tab'  => $_GET['tab']  ?? 'completed',
        'q'    => $_GET['q']    ?? '',
        'sort' => $_GET['sort'] ?? 'recent',
        'page' => $_GET['page'] ?? 1,
    ], $overrides);
    $params = array_filter($params, fn($v) => $v !== '' && $v !== null);
    return BASE_URL . '/payments' . (empty($params) ? '' : '?' . http_build_query($params));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Servo | Payments</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/elementStyles.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/payments.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

  <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>
  <div class="main-content">
    <?php if (!empty($_SESSION['flash'])):
      $flash = $_SESSION['flash'];
      unset($_SESSION['flash']);
    ?>
      <div class="payments-flash payments-flash-<?= htmlspecialchars($flash['type']) ?>">
        <i class="fa-solid <?= $flash['type'] === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation' ?>"></i>
        <span><?= htmlspecialchars($flash['message']) ?></span>
      </div>
    <?php endif; ?>
    <section class="service-requests">
      <div class="header-requests">
        <div class="header-top" style="display:flex; justify-content: space-between; align-items: center;">
          <h1>My Payments</h1>
        </div>

        <form class="search-header" method="get" action="<?= BASE_URL ?>/payments" id="payments-toolbar">
          <input type="hidden" name="tab" value="<?= htmlspecialchars($currentTab) ?>">
          <div class="search-button">
            <input type="text" name="q" value="<?= htmlspecialchars($currentSearch) ?>" placeholder="Search my payments...">
            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
          </div>
          <div class="advance-search">
            <div class="sort-selection">
              <div class="selection-input-field">
                <input type="selection-input" id="selection-input" name="sort-display"
                  value="<?= htmlspecialchars($sortOptions[$currentSort] ?? $sortOptions['recent']) ?>" disabled>
                <i class="fa-solid fa-chevron-down"></i>
              </div>
              <div class="selection-options" id="selection-options">
                <?php foreach ($sortOptions as $key => $label): ?>
                  <div class="opt" data-sort="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          <input type="hidden" name="sort" id="sort-hidden" value="<?= htmlspecialchars($currentSort) ?>">
        </form>

        <div class="report-bar">
          <div class="report-bar-inner">
            <div class="report-dates">
              <div class="report-date-field">
                <label for="client-report-start">From</label>
                <input type="date" id="client-report-start" class="report-date-input">
              </div>
              <div class="report-date-field">
                <label for="client-report-end">To</label>
                <input type="date" id="client-report-end" class="report-date-input">
              </div>
            </div>
            <div class="report-btns">
              <button class="report-btn report-btn-secondary" id="client-btn-preview"><i class="fa-solid fa-eye"></i> Preview</button>
            </div>
          </div>
          <div class="report-preview-bar" id="client-report-preview" style="display:none;">
            <div class="rpt-stat"><span class="rpt-label">Total Paid</span><span class="rpt-value" id="crpt-total">LKR 0.00</span></div>
            <div class="rpt-stat"><span class="rpt-label">Pending</span><span class="rpt-value rpt-pending" id="crpt-pending">LKR 0.00</span></div>
            <div class="rpt-stat"><span class="rpt-label">Refunded</span><span class="rpt-value rpt-refunded" id="crpt-refunded">LKR 0.00</span></div>
            <div class="rpt-stat"><span class="rpt-label">Transactions</span><span class="rpt-value" id="crpt-count">0</span></div>
          </div>
        </div>

        <div class="container-changer">
          <?php foreach ($tabs as $key => $info): ?>
            <a href="<?= htmlspecialchars(paymentsUrl(['tab' => $key, 'page' => 1])) ?>"
              class="buttons<?= $currentTab === $key ? ' active' : '' ?>"
              id="<?= htmlspecialchars($key) ?>-payments-tab">
              <?= htmlspecialchars($info['label']) ?>
              <span class="tab-count">(<?= (int) $info['total'] ?>)</span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="request-content">
        <?php $activeItems = $tabs[$currentTab]['items']; $activePages = $tabs[$currentTab]['pages']; ?>

        <?php if ($currentTab === 'awaiting'): ?>
          <div class="awaiting-payments active requests-section">
            <p class="section-note">Accepted requests awaiting your payment. Fund these to start the project work.</p>
            <div class="item-list">
              <?php if (empty($activeItems)): ?>
                <div class="search-item" style="text-align: center; padding: 40px; color: #64748b;">
                  No awaiting payments found<?= $currentSearch !== '' ? ' for "' . htmlspecialchars($currentSearch) . '"' : '' ?>.
                </div>
              <?php else: ?>
                <?php foreach ($activeItems as $item):
                  $acceptedDate = !empty($item['Started_At']) ? date('d M Y', strtotime($item['Started_At'])) : '—';
                  $priceUnit = !empty($item['Price_Type']) ? strtolower(substr($item['Price_Type'], 0, 2)) : '—';
                  $rate = 'LKR ' . number_format((float) $item['Requesting_Price'], 0) . '/' . $priceUnit;
                  $estimatedTotal = (float) $item['Amount'];
                  $estDelivery = !empty($item['Est_Delivery']) ? date('d M Y', strtotime($item['Est_Delivery'])) : '—';
                  $providerName = !empty($item['Provider_Name']) ? $item['Provider_Name'] : '—';
                ?>
                  <div class="search-item" data-status="awaiting" data-payment-id="<?= (int) $item['Payment_ID'] ?>">
                    <div class="status-badge status-pending">Awaiting Payment</div>
                    <div class="item-head">
                      <div class="item-main-dets">
                        <div class="item-title"><?= htmlspecialchars($item['Project_Title'] ?? '—') ?></div>
                        <div class="item-district">
                          <span>Accepted: <?= $acceptedDate ?></span>
                          <span>Rate: <?= $rate ?></span>
                        </div>
                      </div>
                      <div class="post-actions">
                        <?php $providerId = (int) ($item['Provider_ID'] ?? 0); ?>
                        <a class="action-btn btn-outline" href="<?= BASE_URL ?>/messages<?= $providerId ? '?user=' . $providerId : '' ?>"><i class="fa-solid fa-message"></i> Message</a>
                        <form method="post" action="<?= BASE_URL ?>/payments/pay/<?= (int) $item['Payment_ID'] ?>" class="inline-action-form">
                          <button type="submit" class="action-btn btn-primary"><i class="fa-solid fa-credit-card"></i> Pay Now</button>
                        </form>
                        <form method="post" action="<?= BASE_URL ?>/payments/cancel/<?= (int) $item['Payment_ID'] ?>" class="inline-action-form" data-confirm="Cancel this payment and project? This cannot be undone.">
                          <button type="submit" class="action-btn btn-danger"><i class="fa-solid fa-ban"></i> Cancel</button>
                        </form>
                      </div>
                    </div>
                    <div class="item-middle">
                      <div><i class="fa-solid fa-calendar-day"></i> Est. Delivery Date: <?= $estDelivery ?></div>
                      <div><i class="fa-solid fa-dollar-sign"></i> Estimated Total: LKR <?= number_format($estimatedTotal, 2) ?></div>
                    </div>
                    <div class="post-description"><?= htmlspecialchars($item['Description'] ?? '') ?></div>
                    <div class="post-footer">
                      <div class="post-details">
                        <div class="detail-item"><span class="detail-label">Amount</span><span class="detail-value budget-amount">LKR <?= number_format($estimatedTotal, 2) ?></span></div>
                        <div class="detail-item"><span class="detail-label">Provider</span><span class="detail-value"><?= htmlspecialchars($providerName) ?></span></div>
                        <div class="detail-item"><span class="detail-label">Type</span><span class="detail-value"><?= htmlspecialchars($item['Post_Type'] ?? '—') ?></span></div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>

        <?php elseif ($currentTab === 'pending'): ?>
          <div class="pending-payments active requests-section">
            <div class="item-list">
              <?php if (empty($activeItems)): ?>
                <div class="search-item" style="text-align: center; padding: 40px; color: #64748b;">
                  No pending payments found<?= $currentSearch !== '' ? ' for "' . htmlspecialchars($currentSearch) . '"' : '' ?>.
                </div>
              <?php else: ?>
                <?php foreach ($activeItems as $payment):
                  $invoiceDate = !empty($payment['Hold_Time']) ? date('M d, Y', strtotime($payment['Hold_Time'])) : '—';
                  $isCancelled = ($payment['Project_Status'] ?? '') === 'Cancelled';
                  $invoiceNum = 'INV-' . $payment['Payment_ID'];
                  $dueDate = !empty($payment['Due_Date']) ? date('M d, Y', strtotime($payment['Due_Date'])) : '—';
                  $method = $payment['Method'] ?? '—';
                  $projectName = $payment['Project_Name'] ?? '—';
                ?>
                  <div class="search-item" data-payment-id="<?= (int) $payment['Payment_ID'] ?>">
                    <div class="status-badge status-pending">Pending</div>
                    <?php if ($isCancelled): ?>
                      <span class="payment-type-label label-cancellation-penalty"><i class="fa-solid fa-triangle-exclamation"></i> Cancellation Penalty</span>
                    <?php else: ?>
                      <span class="payment-type-label label-completed-project"><i class="fa-solid fa-circle-check"></i> Completed Project</span>
                    <?php endif; ?>
                    <div class="post-header">
                      <div class="post-meta">
                        <div class="post-date"><i class="fas fa-calendar"></i><span>Invoice Date: <?= $invoiceDate ?></span></div>
                      </div>
                      <div class="post-actions">
                        <form method="post" action="<?= BASE_URL ?>/payments/pay/<?= (int) $payment['Payment_ID'] ?>" class="inline-action-form">
                          <button type="submit" class="action-btn btn-primary"><i class="fas fa-credit-card"></i>Pay Now</button>
                        </form>
                        <a class="action-btn btn-secondary" href="<?= BASE_URL ?>/payments/invoice/<?= (int) $payment['Payment_ID'] ?>"><i class="fas fa-eye"></i>View Invoice</a>
                        <form method="post" action="<?= BASE_URL ?>/payments/cancel/<?= (int) $payment['Payment_ID'] ?>" class="inline-action-form" data-confirm="Cancel this payment and project? This cannot be undone.">
                          <button type="submit" class="action-btn btn-danger"><i class="fas fa-ban"></i>Cancel</button>
                        </form>
                      </div>
                    </div>
                    <h3 class="post-title">Invoice #<?= htmlspecialchars($invoiceNum) ?> &bull; <?= htmlspecialchars($payment['Project_Title'] ?? '—') ?></h3>
                    <div class="post-description"><?= htmlspecialchars($payment['Description'] ?? '') ?></div>
                    <div class="post-footer">
                      <div class="post-details">
                        <div class="detail-item"><span class="detail-label">Amount</span><span class="detail-value budget-amount">LKR <?= number_format((float) $payment['Amount'], 2) ?></span></div>
                        <div class="detail-item"><span class="detail-label">Method</span><span class="detail-value"><?= htmlspecialchars($method) ?></span></div>
                        <div class="detail-item"><span class="detail-label">Due Date</span><span class="detail-value"><?= $dueDate ?></span></div>
                        <div class="detail-item"><span class="detail-label">Project</span><span class="detail-value"><?= htmlspecialchars($projectName) ?></span></div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>

        <?php elseif ($currentTab === 'completed'): ?>
          <div class="completed-payments active requests-section">
            <div class="item-list">
              <?php if (empty($activeItems)): ?>
                <div class="search-item" style="text-align: center; padding: 40px; color: #64748b;">
                  No completed payments found<?= $currentSearch !== '' ? ' for "' . htmlspecialchars($currentSearch) . '"' : '' ?>.
                </div>
              <?php else: ?>
                <?php foreach ($activeItems as $payment):
                  $paidDate = !empty($payment['Paid_Time']) ? date('M d, Y', strtotime($payment['Paid_Time'])) : '—';
                  $isCancelled = ($payment['Project_Status'] ?? '') === 'Cancelled';
                  $invoiceNum = 'INV-' . $payment['Payment_ID'];
                  $method = $payment['Method'] ?? '—';
                  $txnId = $payment['Txn_ID'] ?? '—';
                  $projectName = $payment['Project_Name'] ?? '—';
                ?>
                  <div class="search-item" data-payment-id="<?= (int) $payment['Payment_ID'] ?>">
                    <div class="status-badge status-paid">Paid</div>
                    <?php if ($isCancelled): ?>
                      <span class="payment-type-label label-cancellation-penalty"><i class="fa-solid fa-triangle-exclamation"></i> Cancellation Penalty</span>
                    <?php else: ?>
                      <span class="payment-type-label label-completed-project"><i class="fa-solid fa-circle-check"></i> Completed Project</span>
                    <?php endif; ?>
                    <div class="post-header">
                      <div class="post-meta">
                        <div class="post-date"><i class="fas fa-calendar"></i><span>Paid on <?= $paidDate ?></span></div>
                      </div>
                      <div class="post-actions">
                        <a class="action-btn btn-primary" href="<?= BASE_URL ?>/payments/invoice/<?= (int) $payment['Payment_ID'] ?>"><i class="fas fa-file"></i>Receipt</a>
                        <form method="post" action="<?= BASE_URL ?>/payments/refund/<?= (int) $payment['Payment_ID'] ?>" class="inline-action-form" data-confirm="Request a refund for this payment? It will go to admin for approval.">
                          <button type="submit" class="action-btn btn-outline"><i class="fas fa-rotate-left"></i>Refund</button>
                        </form>
                      </div>
                    </div>
                    <h3 class="post-title">Invoice #<?= htmlspecialchars($invoiceNum) ?> &bull; <?= htmlspecialchars($payment['Project_Title'] ?? '—') ?></h3>
                    <div class="post-description"><?= htmlspecialchars($payment['Description'] ?? '') ?></div>
                    <div class="post-footer">
                      <div class="post-details">
                        <div class="detail-item"><span class="detail-label">Amount</span><span class="detail-value budget-amount">LKR <?= number_format((float) $payment['Amount'], 2) ?></span></div>
                        <div class="detail-item"><span class="detail-label">Method</span><span class="detail-value"><?= htmlspecialchars($method) ?></span></div>
                        <div class="detail-item"><span class="detail-label">Txn ID</span><span class="detail-value"><?= htmlspecialchars($txnId) ?></span></div>
                        <div class="detail-item"><span class="detail-label">Project</span><span class="detail-value"><?= htmlspecialchars($projectName) ?></span></div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>

        <?php elseif ($currentTab === 'refunded'): ?>
          <div class="refunded-payments active requests-section">
            <div class="item-list">
              <?php if (empty($activeItems)): ?>
                <div class="search-item" style="text-align: center; padding: 40px; color: #64748b;">
                  No refunded payments found<?= $currentSearch !== '' ? ' for "' . htmlspecialchars($currentSearch) . '"' : '' ?>.
                </div>
              <?php else: ?>
                <?php foreach ($activeItems as $payment):
                  $refundAnchor = $payment['Paid_Time'] ?? $payment['Hold_Time'] ?? null;
                  $refundDate = !empty($refundAnchor) ? date('M d, Y', strtotime($refundAnchor)) : '—';
                  $invoiceNum = 'INV-' . $payment['Payment_ID'];
                  $method = $payment['Method'] ?? '—';
                  $refundId = $payment['Refund_ID'] ?? '—';
                  $projectName = $payment['Project_Name'] ?? '—';
                  $isRequested = ($payment['Status'] ?? '') === 'Refund Requested';
                  $badgeLabel = $isRequested ? 'Refund Requested' : 'Refunded';
                  $dateLabel = $isRequested ? 'Requested on' : 'Refunded on';
                ?>
                  <div class="search-item" data-payment-id="<?= (int) $payment['Payment_ID'] ?>">
                    <div class="status-badge status-refunded"><?= $badgeLabel ?></div>
                    <div class="post-header">
                      <div class="post-meta">
                        <div class="post-date"><i class="fas fa-calendar"></i><span><?= $dateLabel ?> <?= $refundDate ?></span></div>
                      </div>
                      <div class="post-actions">
                        <a class="action-btn btn-secondary" href="<?= BASE_URL ?>/payments/invoice/<?= (int) $payment['Payment_ID'] ?>"><i class="fas fa-eye"></i>Details</a>
                        <a class="action-btn btn-outline" href="mailto:support@servo.local?subject=Refund%20Inquiry%20<?= htmlspecialchars('INV-' . (int) $payment['Payment_ID']) ?>"><i class="fas fa-circle-info"></i>Support</a>
                      </div>
                    </div>
                    <h3 class="post-title">Invoice #<?= htmlspecialchars($invoiceNum) ?> &bull; <?= htmlspecialchars($payment['Project_Title'] ?? '—') ?></h3>
                    <div class="post-description"><?= htmlspecialchars($payment['Description'] ?? '') ?></div>
                    <div class="post-footer">
                      <div class="post-details">
                        <div class="detail-item"><span class="detail-label">Amount</span><span class="detail-value budget-amount">LKR <?= number_format((float) $payment['Amount'], 2) ?></span></div>
                        <div class="detail-item"><span class="detail-label">Method</span><span class="detail-value"><?= htmlspecialchars($method) ?></span></div>
                        <div class="detail-item"><span class="detail-label">Refund ID</span><span class="detail-value"><?= htmlspecialchars($refundId) ?></span></div>
                        <div class="detail-item"><span class="detail-label">Project</span><span class="detail-value"><?= htmlspecialchars($projectName) ?></span></div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        <?php endif; ?>

        <?php if ($activePages > 1): ?>
          <div class="pagination" aria-label="Pagination">
            <a class="page-btn prev<?= $currentPage <= 1 ? ' disabled' : '' ?>"
              href="<?= $currentPage <= 1 ? '#' : htmlspecialchars(paymentsUrl(['page' => $currentPage - 1])) ?>"
              <?= $currentPage <= 1 ? 'aria-disabled="true"' : '' ?>>
              <i class="fa-solid fa-chevron-left"></i>
            </a>
            <?php for ($p = 1; $p <= $activePages; $p++): ?>
              <a class="page-btn<?= $p === $currentPage ? ' active' : '' ?>"
                href="<?= htmlspecialchars(paymentsUrl(['page' => $p])) ?>"><?= $p ?></a>
            <?php endfor; ?>
            <a class="page-btn next<?= $currentPage >= $activePages ? ' disabled' : '' ?>"
              href="<?= $currentPage >= $activePages ? '#' : htmlspecialchars(paymentsUrl(['page' => $currentPage + 1])) ?>"
              <?= $currentPage >= $activePages ? 'aria-disabled="true"' : '' ?>>
              <i class="fa-solid fa-chevron-right"></i>
            </a>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </div>

  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const toolbar = document.getElementById('payments-toolbar');
      const sortHidden = document.getElementById('sort-hidden');
      const sortDisplay = document.getElementById('selection-input');
      document.querySelectorAll('form.inline-action-form[data-confirm]').forEach(form => {
        form.addEventListener('submit', e => {
          if (!window.confirm(form.dataset.confirm)) e.preventDefault();
        });
      });

      document.querySelectorAll('#selection-options .opt').forEach(opt => {
        opt.addEventListener('click', () => {
          sortHidden.value = opt.dataset.sort;
          sortDisplay.value = opt.textContent.trim();
          toolbar.submit();
        });
      });

      const today = new Date();
      const thirtyDaysAgo = new Date(today);
      thirtyDaysAgo.setDate(today.getDate() - 30);
      document.getElementById('client-report-start').value = thirtyDaysAgo.toISOString().split('T')[0];
      document.getElementById('client-report-end').value = today.toISOString().split('T')[0];

      const previewBtn = document.getElementById('client-btn-preview');
      previewBtn.addEventListener('click', async function (e) {
        e.preventDefault();
        const startDate = document.getElementById('client-report-start').value;
        const endDate = document.getElementById('client-report-end').value;
        if (!startDate || !endDate) { alert('Please select both start and end dates.'); return; }
        if (new Date(startDate) > new Date(endDate)) { alert('Start date must be before end date.'); return; }

        const fmt = n => 'LKR ' + Number(n).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        const originalLabel = previewBtn.innerHTML;
        previewBtn.disabled = true;
        previewBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Loading...';

        try {
          const url = '<?= BASE_URL ?>/payments/report?from=' + encodeURIComponent(startDate) + '&to=' + encodeURIComponent(endDate);
          const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
          const data = await res.json();
          if (!res.ok) { alert(data.error || 'Failed to load report.'); return; }

          document.getElementById('crpt-total').textContent    = fmt(data.total);
          document.getElementById('crpt-pending').textContent  = fmt(data.pending);
          document.getElementById('crpt-refunded').textContent = fmt(data.refunded);
          document.getElementById('crpt-count').textContent    = data.count;
          document.getElementById('client-report-preview').style.display = 'flex';
        } catch (err) {
          alert('Network error fetching report.');
        } finally {
          previewBtn.disabled = false;
          previewBtn.innerHTML = originalLabel;
        }
      });
    });
  </script>
  <script src="<?= BASE_URL ?>/assets/js/cardList.js" defer></script>
</body>

</html>

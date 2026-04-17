<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/payments.css">

    <title>My Payments - ServiceHub</title>
</head>

<body>
    <section class="service-requests">
        <div class="header-requests">
            <div class="header-top" style="display:flex; justify-content: space-between; align-items: center;">
                <h1>My Payments</h1>
            </div>
            <div class="search-header">
                <div class="search-button">
                    <input type="text" placeholder="Search my payments...">
                    <button><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                <button class="filter" id="filter-pop-up"><i
                        class="fa-solid fa-filter"></i><span>Filter</span></button>
                <div class="advance-search">
                    <div class="sort-selection">
                        <div class="selection-input-field">
                            <input type="selection-input" id="selection-input" name="sort" value="Sort By Relevence"
                                disabled><i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="selection-options" id="selection-options">
                            <div class="opt">Sort By Relevence</div>
                            <div class="opt">Sort By Price</div>
                            <div class="opt">Sort By Rating</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Generation -->
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
                        <button class="report-btn report-btn-primary" id="client-btn-download"><i class="fa-solid fa-download"></i> Download Report</button>
                    </div>
                </div>
                <!-- Report Preview -->
                <div class="report-preview-bar" id="client-report-preview" style="display:none;">
                    <div class="rpt-stat"><span class="rpt-label">Total Paid</span><span class="rpt-value" id="crpt-total">$0.00</span></div>
                    <div class="rpt-stat"><span class="rpt-label">Pending</span><span class="rpt-value rpt-pending" id="crpt-pending">$0.00</span></div>
                    <div class="rpt-stat"><span class="rpt-label">Refunded</span><span class="rpt-value rpt-refunded" id="crpt-refunded">$0.00</span></div>
                    <div class="rpt-stat"><span class="rpt-label">Transactions</span><span class="rpt-value" id="crpt-count">0</span></div>
                </div>
            </div>

            <div class="container-changer">
                <div id="awaiting-payments" class="buttons active">Awaiting Payments</div>
                <div id="pending-payments" class="buttons">Pending Payments</div>
                <div id="completed-payments" class="buttons">Completed Payments</div>
                <div id="refunded-payments" class="buttons">Refunded Payments</div>
            </div>
        </div>

        <div class="request-content">
            <!-- AWAITING PAYMENTS SECTION (Accepted requests pending payment) -->
            <div class="awaiting-payments active requests-section">
                <p class="section-note">Accepted requests awaiting your payment. Fund these to start the project work.</p>
                <div class="item-list">
                    <?php if (empty($awaitingPayments)): ?>
                        <div class="search-item" style="text-align: center; padding: 40px; color: #64748b;">
                            No awaiting payments found.
                        </div>
                    <?php else: ?>
                        <?php foreach ($awaitingPayments as $item):
                            $acceptedDate = date('d M Y', strtotime($item['Started_At']));
                            $rate = '$' . number_format($item['Requesting_Price'], 0) . '/' . strtolower(substr($item['Price_Type'], 0, 2));
                            $estimatedTotal = isset($item['Estimated_Total']) ? $item['Estimated_Total'] : $item['Requesting_Price'];
                        ?>
                            <div class="search-item" data-status="awaiting">
                                <div class="status-badge status-pending">Awaiting Payment</div>
                                <div class="item-head">
                                    <div class="item-main-dets">
                                        <div class="item-title"><?= htmlspecialchars($item['Title']) ?></div>
                                        <div class="item-district">
                                            <span>Accepted: <?= $acceptedDate ?></span>
                                            <span>Rate: <?= $rate ?></span>
                                        </div>
                                    </div>
                                    <div class="post-actions">
                                        <button class="action-btn btn-outline"><i class="fa-solid fa-message"></i> Message</button>
                                        <button class="action-btn btn-primary"><i class="fa-solid fa-credit-card"></i> Pay Now</button>
                                        <button class="action-btn btn-danger"><i class="fa-solid fa-ban"></i> Cancel</button>
                                    </div>
                                </div>
                                <div class="item-middle">
                                    <div><i class="fa-solid fa-hourglass"></i> Estimated Time: <?= htmlspecialchars($item['Duration']) ?> <?= strtolower(htmlspecialchars($item['Duration_Type'])) ?></div>
                                    <div><i class="fa-solid fa-dollar-sign"></i> Estimated Total: $<?= number_format($estimatedTotal, 2) ?></div>
                                </div>
                                <div class="post-description"><?= htmlspecialchars($item['Description']) ?></div>
                                <div class="post-footer">
                                    <div class="post-details">
                                        <div class="detail-item"><span class="detail-label">Amount</span><span class="detail-value budget-amount">$<?= number_format($estimatedTotal, 2) ?></span></div>
                                        <div class="detail-item"><span class="detail-label">Provider</span><span class="detail-value"><?= htmlspecialchars($item['Provider_Name']) ?></span></div>
                                        <div class="detail-item"><span class="detail-label">Type</span><span class="detail-value"><?= htmlspecialchars($item['Post_Type']) ?></span></div>
                                        <?php if (!empty($item['Project_Title'])): ?>
                                            <div class="detail-item"><span class="detail-label">Project</span><span class="detail-value"><?= htmlspecialchars($item['Project_Title']) ?></span></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="pagination" aria-label="Pagination Awaiting Payments">
                        <button class="page-btn prev" disabled><i class="fa-solid fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn next"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>

            <!-- PENDING PAYMENTS SECTION -->
            <div class="pending-payments requests-section" style="display:none;">
                <div class="item-list">
                    <?php if (empty($pendingPayments)): ?>
                        <div class="search-item" style="text-align: center; padding: 40px; color: #64748b;">
                            No pending payments found.
                        </div>
                    <?php else: ?>
                        <?php foreach ($pendingPayments as $payment):
                            $invoiceDate = date('M d, Y', strtotime($payment['Hold_Time']));
                            $isCancelled = $payment['Project_Status'] === 'Cancelled';
                            $invoiceNum = 'INV-' . $payment['Payment_ID'];
                            $dueDate = isset($payment['Due_Date']) ? date('M d, Y', strtotime($payment['Due_Date'])) : '—';
                            $method = $payment['Method'] ?? '—';
                            $projectName = $payment['Project_Name'] ?? '—';
                        ?>
                            <div class="search-item">
                                <div class="status-badge status-pending">Pending</div>
                                <?php if ($isCancelled): ?>
                                    <span class="payment-type-label label-cancellation-penalty"><i class="fa-solid fa-triangle-exclamation"></i> Cancellation Penalty</span>
                                <?php else: ?>
                                    <span class="payment-type-label label-completed-project"><i class="fa-solid fa-circle-check"></i> Completed Project</span>
                                <?php endif; ?>
                                <div class="post-header">
                                    <div class="post-meta">
                                        <div class="post-date">
                                            <i class="fas fa-calendar"></i>
                                            <span>Invoice Date: <?= $invoiceDate ?></span>
                                        </div>
                                    </div>
                                    <div class="post-actions">
                                        <button class="action-btn btn-primary"><i class="fas fa-credit-card"></i>Pay Now</button>
                                        <button class="action-btn btn-secondary"><i class="fas fa-eye"></i>View Invoice</button>
                                        <button class="action-btn btn-danger"><i class="fas fa-ban"></i>Cancel</button>
                                    </div>
                                </div>
                                <h3 class="post-title">Invoice #<?= htmlspecialchars($invoiceNum) ?> &bull; <?= htmlspecialchars($payment['Project_Title']) ?></h3>
                                <div class="post-description"><?= htmlspecialchars($payment['Description']) ?></div>
                                <div class="post-footer">
                                    <div class="post-details">
                                        <div class="detail-item"><span class="detail-label">Amount</span><span class="detail-value budget-amount">$<?= number_format($payment['Amount'], 2) ?></span></div>
                                        <div class="detail-item"><span class="detail-label">Method</span><span class="detail-value"><?= htmlspecialchars($method) ?></span></div>
                                        <div class="detail-item"><span class="detail-label">Due Date</span><span class="detail-value"><?= $dueDate ?></span></div>
                                        <div class="detail-item"><span class="detail-label">Project</span><span class="detail-value"><?= htmlspecialchars($projectName) ?></span></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="pagination" aria-label="Pagination Pending Payments">
                        <button class="page-btn prev" disabled><i class="fa-solid fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn next"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>

            <!-- COMPLETED PAYMENTS SECTION -->
            <div class="completed-payments requests-section" style="display:none;">
                <div class="item-list">
                    <?php if (empty($completedPayments)): ?>
                        <div class="search-item" style="text-align: center; padding: 40px; color: #64748b;">
                            No completed payments found.
                        </div>
                    <?php else: ?>
                        <?php foreach ($completedPayments as $payment):
                            $paidDate = date('M d, Y', strtotime($payment['Paid_Time']));
                            $isCancelled = $payment['Project_Status'] === 'Cancelled';
                            $invoiceNum = 'INV-' . $payment['Payment_ID'];
                            $method = $payment['Method'] ?? '—';
                            $txnId = $payment['Txn_ID'] ?? '—';
                            $projectName = $payment['Project_Name'] ?? '—';
                        ?>
                            <div class="search-item">
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
                                        <button class="action-btn btn-primary"><i class="fas fa-file"></i>Receipt</button>
                                        <button class="action-btn btn-secondary"><i class="fas fa-download"></i>Download</button>
                                        <button class="action-btn btn-outline"><i class="fas fa-rotate-left"></i>Refund</button>
                                    </div>
                                </div>
                                <h3 class="post-title">Invoice #<?= htmlspecialchars($invoiceNum) ?> &bull; <?= htmlspecialchars($payment['Project_Title']) ?></h3>
                                <div class="post-description"><?= htmlspecialchars($payment['Description']) ?></div>
                                <div class="post-footer">
                                    <div class="post-details">
                                        <div class="detail-item"><span class="detail-label">Amount</span><span class="detail-value budget-amount">$<?= number_format($payment['Amount'], 2) ?></span></div>
                                        <div class="detail-item"><span class="detail-label">Method</span><span class="detail-value"><?= htmlspecialchars($method) ?></span></div>
                                        <div class="detail-item"><span class="detail-label">Txn ID</span><span class="detail-value"><?= htmlspecialchars($txnId) ?></span></div>
                                        <div class="detail-item"><span class="detail-label">Project</span><span class="detail-value"><?= htmlspecialchars($projectName) ?></span></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="pagination" aria-label="Pagination Completed Payments">
                        <button class="page-btn prev" disabled><i class="fa-solid fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn next"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>

            <!-- REFUNDED PAYMENTS SECTION -->
            <div class="refunded-payments requests-section" style="display:none;">
                <div class="item-list">
                    <?php if (empty($refundedPayments)): ?>
                        <div class="search-item" style="text-align: center; padding: 40px; color: #64748b;">
                            No refunded payments found.
                        </div>
                    <?php else: ?>
                        <?php foreach ($refundedPayments as $payment):
                            $refundDate = date('M d, Y', strtotime($payment['Paid_Time']));
                            $invoiceNum = 'INV-' . $payment['Payment_ID'];
                            $method = $payment['Method'] ?? '—';
                            $refundId = $payment['Refund_ID'] ?? '—';
                            $projectName = $payment['Project_Name'] ?? '—';
                        ?>
                            <div class="search-item">
                                <div class="status-badge status-refunded">Refunded</div>
                                <div class="post-header">
                                    <div class="post-meta">
                                        <div class="post-date"><i class="fas fa-calendar"></i><span>Refunded on <?= $refundDate ?></span></div>
                                    </div>
                                    <div class="post-actions">
                                        <button class="action-btn btn-secondary"><i class="fas fa-eye"></i>Details</button>
                                        <button class="action-btn btn-outline"><i class="fas fa-circle-info"></i>Support</button>
                                    </div>
                                </div>
                                <h3 class="post-title">Invoice #<?= htmlspecialchars($invoiceNum) ?> &bull; <?= htmlspecialchars($payment['Project_Title']) ?></h3>
                                <div class="post-description"><?= htmlspecialchars($payment['Description']) ?></div>
                                <div class="post-footer">
                                    <div class="post-details">
                                        <div class="detail-item"><span class="detail-label">Amount</span><span class="detail-value budget-amount">$<?= number_format($payment['Amount'], 2) ?></span></div>
                                        <div class="detail-item"><span class="detail-label">Method</span><span class="detail-value"><?= htmlspecialchars($method) ?></span></div>
                                        <div class="detail-item"><span class="detail-label">Refund ID</span><span class="detail-value"><?= htmlspecialchars($refundId) ?></span></div>
                                        <div class="detail-item"><span class="detail-label">Project</span><span class="detail-value"><?= htmlspecialchars($projectName) ?></span></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="pagination" aria-label="Pagination Refunded Payments">
                        <button class="page-btn prev" disabled><i class="fa-solid fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn next"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tab switching
        const tabs = document.querySelectorAll('.buttons');
        const sections = document.querySelectorAll('.requests-section');

        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                tabs.forEach(t => t.classList.remove('active'));
                sections.forEach(s => {
                    s.classList.remove('active');
                    s.style.display = 'none';
                });
                this.classList.add('active');
                const section = document.querySelector('.' + this.id);
                if (section) {
                    section.classList.add('active');
                    section.style.display = 'block';
                }
            });
        });

        // Report generation
        const today = new Date();
        const thirtyDaysAgo = new Date(today);
        thirtyDaysAgo.setDate(today.getDate() - 30);
        document.getElementById('client-report-start').value = thirtyDaysAgo.toISOString().split('T')[0];
        document.getElementById('client-report-end').value = today.toISOString().split('T')[0];

        document.getElementById('client-btn-preview').addEventListener('click', function() {
            const startDate = document.getElementById('client-report-start').value;
            const endDate = document.getElementById('client-report-end').value;
            if (!startDate || !endDate) { alert('Please select both start and end dates.'); return; }
            if (new Date(startDate) > new Date(endDate)) { alert('Start date must be before end date.'); return; }
            const days = Math.ceil((new Date(endDate) - new Date(startDate)) / (1000 * 60 * 60 * 24));
            const total = Math.round(days * 98.5 * 100) / 100;
            const pending = Math.round(total * 0.25 * 100) / 100;
            const refunded = Math.round(total * 0.08 * 100) / 100;
            const count = Math.max(1, Math.round(days / 5));
            document.getElementById('crpt-total').textContent = '$' + total.toLocaleString(undefined, {minimumFractionDigits: 2});
            document.getElementById('crpt-pending').textContent = '$' + pending.toLocaleString(undefined, {minimumFractionDigits: 2});
            document.getElementById('crpt-refunded').textContent = '$' + refunded.toLocaleString(undefined, {minimumFractionDigits: 2});
            document.getElementById('crpt-count').textContent = count;
            document.getElementById('client-report-preview').style.display = 'flex';
        });

        document.getElementById('client-btn-download').addEventListener('click', function() {
            const startDate = document.getElementById('client-report-start').value;
            const endDate = document.getElementById('client-report-end').value;
            if (!startDate || !endDate) { alert('Please select both start and end dates.'); return; }
            alert('Payment report for ' + startDate + ' to ' + endDate + ' will be generated and downloaded.');
        });
    });
</script>
<script src="<?= BASE_URL ?>/assets/js/cardList.js" defer></script>

</html>

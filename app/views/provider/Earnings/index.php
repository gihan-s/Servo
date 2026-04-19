<?php
$allTransactions = $allTransactions ?? [];
$initialBatch    = 20;
$renderTxnRow    = function (array $txn) {
    $date           = $txn['Paid_Time'] ?? $txn['Hold_Time'] ?? null;
    $formattedDate  = $date ? date('M d, Y', strtotime($date)) : '—';
    $invoiceNum     = 'INV-' . $txn['Payment_ID'];
    $isCancelled    = ($txn['Project_Status'] ?? '') === 'Cancelled';
    $statusRaw      = $txn['Status'] ?? 'Pending';
    $statusClass    = strtolower(str_replace(' ', '-', $statusRaw));
    $isCompleted    = $statusRaw === 'Paid';
    $receiptUrl     = BASE_URL . '/earnings/receipt/' . (int) $txn['Payment_ID'];
    $clientName     = trim((string) ($txn['Client_Name'] ?? '')) ?: '—';
    $projectTitle   = (string) ($txn['Project_Title'] ?? '—');
?>
    <tr>
        <td>
            <div class="transaction-project"><?= htmlspecialchars($projectTitle) ?></div>
            <div class="transaction-client">Invoice #<?= htmlspecialchars($invoiceNum) ?></div>
            <?php if ($isCancelled): ?>
                <span class="payment-type-label label-cancellation-penalty"><i class="fa-solid fa-triangle-exclamation"></i> Cancellation Penalty</span>
            <?php else: ?>
                <span class="payment-type-label label-completed-project"><i class="fa-solid fa-circle-check"></i> Completed Project</span>
            <?php endif; ?>
        </td>
        <td><?= $formattedDate ?></td>
        <td><?= htmlspecialchars($clientName) ?></td>
        <td>
            <div class="transaction-amount">$<?= number_format((float) $txn['Amount'] - (float) $txn['Commission'], 2) ?></div>
            <div class="transaction-fee">Fee: $<?= number_format((float) $txn['Commission'], 2) ?></div>
        </td>
        <td><span class="transaction-status status-<?= $statusClass ?>"><?= htmlspecialchars($statusRaw) ?></span></td>
        <td style="text-align: right;">
            <a href="<?= $receiptUrl ?>" class="ghost-btn">
                <?php if ($isCompleted): ?>
                    <i class="fa-solid fa-receipt"></i> Receipt
                <?php else: ?>
                    <i class="fa-solid fa-eye"></i> View
                <?php endif; ?>
            </a>
        </td>
    </tr>
<?php
};
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Earnings | Provider Dashboard</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/elementStyles.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/earnings.css" />

</head>

<body>
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>
    <main class="dashboard-wrapper">
        <header class="dashboard">
            <h1>Earnings</h1>
            <div class="subtitle">Track your income and view transaction history.</div>
        </header>

        <!-- Earnings Metrics -->
        <section class="metrics-grid" aria-label="Earnings overview">
            <div class="metric-card">
                <div class="metric-icon" style="background:#ecfdf5; color:#008500;">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="metric-title">Total Earnings</div>
                <div class="metric-value">$<?= number_format($totalEarnings, 0) ?></div>
                <div class="metric-delta <?= $earningsChange >= 0 ? 'delta-up' : 'delta-down' ?>">
                    <i class="fa-solid fa-arrow-<?= $earningsChange >= 0 ? 'up' : 'down' ?>"></i> <?= abs($earningsChange) ?>% from last month
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background:#fefce8; color:#b45309;">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="metric-title">Pending Payout</div>
                <div class="metric-value">$<?= number_format($pendingPayout, 0) ?></div>
                <div class="metric-delta" style="color:#b45309;">
                    <i class="fa-solid fa-hourglass"></i> Awaiting clearance
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background:#eff6ff; color:#1d4ed8;">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="metric-title">Avg. Project Value</div>
                <div class="metric-value">$<?= number_format($avgProjectValue, 0) ?></div>
                <div class="metric-delta delta-up">
                    <i class="fa-solid fa-arrow-up"></i> Per project
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background:#fae8ff; color:#a855f7;">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="metric-title">Completed Projects</div>
                <div class="metric-value"><?= $completedProjects ?></div>
                <div class="metric-delta delta-up">
                    <i class="fa-solid fa-arrow-up"></i> <?= $completedThisMonth ?> this month
                </div>
            </div>
        </section>

        <!-- Earnings Chart -->
        <section class="chart-container">
            <div class="card chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Earnings Overview</h3>
                    <div class="period-selector">
                        <button class="period-btn active" data-period="1M">1M</button>
                        <button class="period-btn" data-period="3M">3M</button>
                        <button class="period-btn" data-period="6M">6M</button>
                        <button class="period-btn" data-period="1Y">1Y</button>
                        <button class="period-btn" data-period="All">All</button>
                    </div>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background:#008500;"></div>
                        <span>Earnings</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background:#e2e8f0;"></div>
                        <span>Platform Fees</span>
                    </div>
                </div>
                <div class="chart-area">
                    <canvas id="earningsChart"></canvas>
                </div>
            </div>
        </section>

        <!-- Report Generation -->
        <section class="report-section">
            <div class="card">
                <div class="report-header">
                    <h3 class="chart-title">Generate Earnings Report</h3>
                </div>
                <div class="report-controls">
                    <div class="date-range">
                        <div class="date-field">
                            <label for="report-start">From</label>
                            <input type="date" id="report-start" class="date-input">
                        </div>
                        <div class="date-field">
                            <label for="report-end">To</label>
                            <input type="date" id="report-end" class="date-input">
                        </div>
                    </div>
                    <div class="report-actions">
                        <button class="primary-btn" id="btn-preview-report"><i class="fa-solid fa-eye"></i> Preview</button>
                    </div>
                </div>
                <!-- Report Preview Area -->
                <div class="report-preview" id="report-preview" style="display:none;">
                    <div class="report-summary-grid">
                        <div class="report-stat">
                            <div class="report-stat-label">Total Earnings</div>
                            <div class="report-stat-value" id="rpt-total">$0.00</div>
                        </div>
                        <div class="report-stat">
                            <div class="report-stat-label">Platform Fees</div>
                            <div class="report-stat-value" id="rpt-fees" style="color:#b91c1c;">$0.00</div>
                        </div>
                        <div class="report-stat">
                            <div class="report-stat-label">Net Earnings</div>
                            <div class="report-stat-value" id="rpt-net" style="color:#008500;">$0.00</div>
                        </div>
                        <div class="report-stat">
                            <div class="report-stat-label">Transactions</div>
                            <div class="report-stat-value" id="rpt-count">0</div>
                        </div>
                    </div>
                    <div class="report-preview-error" id="rpt-error" style="display:none;"></div>
                </div>
            </div>
        </section>

        <!-- Recent Transactions -->
        <section class="transactions-section">
            <div class="section-header">
                <h2>Recent Transactions</h2>
                <div class="section-actions">
                    <button class="link-btn" id="btn-view-all" type="button">
                        <i class="fa-solid fa-arrow-right"></i> View All
                    </button>
                </div>
            </div>
            <div class="card" style="padding: 0; overflow: hidden;">
                <table class="transactions-table">
                    <thead>
                        <tr>
                            <th>Project / Invoice</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($transactions)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px; color: #64748b;">
                                    No transactions found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($transactions as $txn) $renderTxnRow($txn); ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- All Transactions Modal -->
    <div class="earnings-modal" id="all-transactions-modal" aria-hidden="true">
        <div class="earnings-modal-backdrop" data-modal-close></div>
        <div class="earnings-modal-content" role="dialog" aria-labelledby="all-txn-title" aria-modal="true">
            <div class="earnings-modal-header">
                <h3 id="all-txn-title">All Transactions (<?= count($allTransactions) ?>)</h3>
                <button class="earnings-modal-close" data-modal-close aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="earnings-modal-body" id="all-txn-scroll">
                <table class="transactions-table">
                    <thead>
                        <tr>
                            <th>Project / Invoice</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="all-txn-body">
                        <?php if (empty($allTransactions)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px; color: #64748b;">
                                    No transactions found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php
                            foreach ($allTransactions as $i => $txn):
                                if ($i >= $initialBatch) break;
                                $renderTxnRow($txn);
                            endforeach;
                            ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="earnings-modal-sentinel" id="all-txn-sentinel" style="height:1px;"></div>
                <div class="earnings-modal-footer-note" id="all-txn-end" style="display:none;">
                    End of transactions.
                </div>
            </div>
        </div>
    </div>

    <!-- Remaining transaction rows pre-rendered, revealed on scroll -->
    <template id="all-txn-remaining">
        <?php if (!empty($allTransactions)):
            foreach ($allTransactions as $i => $txn):
                if ($i < $initialBatch) continue;
                $renderTxnRow($txn);
            endforeach;
        endif; ?>
    </template>

    <?php require_once __DIR__ . '/../../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Chart Data from PHP ---
            const chartData = <?= json_encode($chartData) ?>;

            const ctx = document.getElementById('earningsChart').getContext('2d');
            let earningsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData['1M'].labels,
                    datasets: [
                        {
                            label: 'Earnings',
                            data: chartData['1M'].earnings,
                            backgroundColor: '#008500',
                            borderRadius: 6,
                            barPercentage: 0.6
                        },
                        {
                            label: 'Platform Fees',
                            data: chartData['1M'].fees,
                            backgroundColor: '#e2e8f0',
                            borderRadius: 6,
                            barPercentage: 0.6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': $' + context.raw.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) { return '$' + value.toLocaleString(); }
                            },
                            grid: { color: '#f1f5f9' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });

            // Period selector
            document.querySelectorAll('.period-btn').forEach(button => {
                button.addEventListener('click', function() {
                    document.querySelectorAll('.period-btn').forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    const period = this.dataset.period;
                    const data = chartData[period];
                    earningsChart.data.labels = data.labels;
                    earningsChart.data.datasets[0].data = data.earnings;
                    earningsChart.data.datasets[1].data = data.fees;
                    earningsChart.update();
                });
            });

            // --- Report Generation ---
            const today = new Date();
            const thirtyDaysAgo = new Date(today);
            thirtyDaysAgo.setDate(today.getDate() - 30);
            document.getElementById('report-start').value = thirtyDaysAgo.toISOString().split('T')[0];
            document.getElementById('report-end').value = today.toISOString().split('T')[0];

            const fmtCurrency = n => '$' + Number(n || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});

            document.getElementById('btn-preview-report').addEventListener('click', function() {
                const startDate = document.getElementById('report-start').value;
                const endDate = document.getElementById('report-end').value;
                const errBox = document.getElementById('rpt-error');
                const preview = document.getElementById('report-preview');
                errBox.style.display = 'none';
                errBox.textContent = '';

                if (!startDate || !endDate) {
                    alert('Please select both start and end dates.');
                    return;
                }
                if (new Date(startDate) > new Date(endDate)) {
                    alert('Start date must be before end date.');
                    return;
                }

                const url = '<?= BASE_URL ?>/earnings/report?from=' + encodeURIComponent(startDate) + '&to=' + encodeURIComponent(endDate);
                fetch(url, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' })
                    .then(r => r.json().then(j => ({ ok: r.ok, body: j })))
                    .then(({ ok, body }) => {
                        if (!ok) {
                            preview.style.display = 'block';
                            errBox.style.display = 'block';
                            errBox.textContent = body.error || 'Failed to load report.';
                            return;
                        }
                        document.getElementById('rpt-total').textContent = fmtCurrency(body.gross);
                        document.getElementById('rpt-fees').textContent  = '-' + fmtCurrency(body.commission);
                        document.getElementById('rpt-net').textContent   = fmtCurrency(body.net);
                        document.getElementById('rpt-count').textContent = Number(body.count || 0).toLocaleString();
                        preview.style.display = 'block';
                    })
                    .catch(() => {
                        preview.style.display = 'block';
                        errBox.style.display = 'block';
                        errBox.textContent = 'Network error while loading report.';
                    });
            });

            // --- View All Modal with Infinite Scroll ---
            const modal          = document.getElementById('all-transactions-modal');
            const viewAllBtn     = document.getElementById('btn-view-all');
            const txnBody        = document.getElementById('all-txn-body');
            const scrollBox      = document.getElementById('all-txn-scroll');
            const endNote        = document.getElementById('all-txn-end');
            const remainingTpl   = document.getElementById('all-txn-remaining');
            const batchSize      = 20;

            const remainingRows = remainingTpl && remainingTpl.content
                ? Array.from(remainingTpl.content.querySelectorAll('tr'))
                : [];
            let revealedCount = 0;

            function openModal() {
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }
            function closeModal() {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }

            function appendNextBatch() {
                if (revealedCount >= remainingRows.length) {
                    if (remainingRows.length > 0) endNote.style.display = 'block';
                    return;
                }
                const next = remainingRows.slice(revealedCount, revealedCount + batchSize);
                next.forEach(row => txnBody.appendChild(row.cloneNode(true)));
                revealedCount += next.length;
                if (revealedCount >= remainingRows.length) {
                    endNote.style.display = 'block';
                }
            }

            if (viewAllBtn) {
                viewAllBtn.addEventListener('click', openModal);
            }

            modal.querySelectorAll('[data-modal-close]').forEach(el => {
                el.addEventListener('click', closeModal);
            });

            document.addEventListener('keydown', e => {
                if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
            });

            scrollBox.addEventListener('scroll', () => {
                const nearBottom = scrollBox.scrollTop + scrollBox.clientHeight >= scrollBox.scrollHeight - 80;
                if (nearBottom) appendNextBatch();
            });

            // If the initial rendered batch doesn't fill the scrollbox, reveal more immediately.
            if (remainingRows.length === 0) {
                endNote.style.display = 'block';
            }
        });
    </script>
</body>

</html>

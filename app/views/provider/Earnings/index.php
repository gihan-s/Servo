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
                        <button class="ghost-btn" id="btn-preview-report"><i class="fa-solid fa-eye"></i> Preview</button>
                        <button class="primary-btn" id="btn-download-report"><i class="fa-solid fa-download"></i> Download PDF</button>
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
                </div>
            </div>
        </section>

        <!-- Recent Transactions -->
        <section class="transactions-section">
            <div class="section-header">
                <h2>Recent Transactions</h2>
                <div class="section-actions">
                    <button class="ghost-btn"><i class="fa-solid fa-download"></i> Export CSV</button>
                    <button class="link-btn"><i class="fa-solid fa-arrow-right"></i> View All</button>
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
                            <?php foreach ($transactions as $txn):
                                $date = $txn['Paid_Time'] ?? $txn['Hold_Time'];
                                $formattedDate = $date ? date('M d, Y', strtotime($date)) : '—';
                                $invoiceNum = 'INV-' . $txn['Payment_ID'];
                                $isCancelled = $txn['Project_Status'] === 'Cancelled';
                                $statusClass = strtolower($txn['Status']);
                            ?>
                                <tr>
                                    <td>
                                        <div class="transaction-project"><?= htmlspecialchars($txn['Project_Title']) ?></div>
                                        <div class="transaction-client">Invoice #<?= htmlspecialchars($invoiceNum) ?></div>
                                        <?php if ($isCancelled): ?>
                                            <span class="payment-type-label label-cancellation-penalty"><i class="fa-solid fa-triangle-exclamation"></i> Cancellation Penalty</span>
                                        <?php else: ?>
                                            <span class="payment-type-label label-completed-project"><i class="fa-solid fa-circle-check"></i> Completed Project</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $formattedDate ?></td>
                                    <td><?= htmlspecialchars($txn['Client_Name']) ?></td>
                                    <td>
                                        <div class="transaction-amount">$<?= number_format($txn['Amount'], 2) ?></div>
                                        <div class="transaction-fee">Fee: $<?= number_format($txn['Commission'], 2) ?></div>
                                    </td>
                                    <td><span class="transaction-status status-<?= $statusClass ?>"><?= htmlspecialchars($txn['Status']) ?></span></td>
                                    <td style="text-align: right;">
                                        <?php if ($txn['Status'] === 'Completed'): ?>
                                            <button class="ghost-btn"><i class="fa-solid fa-receipt"></i> Receipt</button>
                                        <?php else: ?>
                                            <button class="ghost-btn"><i class="fa-solid fa-eye"></i> View</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Payout Methods -->
        <!--
        <section class="payout-section">
            <div class="section-header">
                <h2>Payout Methods</h2>
                <button class="primary-btn"><i class="fa-solid fa-plus"></i> Add Method</button>
            </div>
            <div class="payout-methods">
                <div class="payout-method active">
                    <div class="method-icon" style="background:#eff6ff; color:#1d4ed8;">
                        <i class="fa-brands fa-paypal"></i>
                    </div>
                    <div class="method-details">
                        <div class="method-name">PayPal</div>
                        <div class="method-info">provider@devstudiolabs.com</div>
                    </div>
                    <div class="method-action">Primary</div>
                </div>
                <div class="payout-method">
                    <div class="method-icon" style="background:#ecfdf5; color:#008500;">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <div class="method-details">
                        <div class="method-name">Bank Transfer</div>
                        <div class="method-info">**** 4829 • Chase Bank</div>
                    </div>
                    <div class="method-action">Set as Primary</div>
                </div>
                <div class="payout-method">
                    <div class="method-icon" style="background:#fef3c7; color:#b45309;">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                    <div class="method-details">
                        <div class="method-name">Direct Card</div>
                        <div class="method-info">**** 6372 • Visa</div>
                    </div>
                    <div class="method-action">Set as Primary</div>
                </div>
            </div>
        </section>
        -->
        <!-- Tax Information -->
        <!--
        <section class="tax-section">
            <div class="section-header">
                <h2>Tax Information</h2>
                <button class="ghost-btn"><i class="fa-solid fa-download"></i> Tax Documents</button>
            </div>
            <div class="card">
                <div class="tax-summary">
                    <div class="tax-card">
                        <div class="tax-value">$4,285.00</div>
                        <div class="tax-label">Total Platform Fees (10%)</div>
                    </div>
                    <div class="tax-card">
                        <div class="tax-value">$6,427.50</div>
                        <div class="tax-label">Estimated Tax (15%)</div>
                    </div>
                    <div class="tax-card">
                        <div class="tax-value">$32,137.50</div>
                        <div class="tax-label">Net Income After Tax</div>
                    </div>
                </div>
                <div style="font-size: 13px; color: #64748b; text-align: center;">
                    <i class="fa-solid fa-circle-info"></i>
                    These are estimates for informational purposes. Consult a tax professional for accurate tax calculations.
                </div>
            </div>
        </section>
        -->
        <!-- Next Payout -->
         <!--
        <section class="payout-section">
            <div class="section-header">
                <h2>Next Payout</h2>
                <button class="primary-btn"><i class="fa-solid fa-arrow-down"></i> Request Early Payout</button>
            </div>
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                    <div>
                        <div style="font-size: 24px; font-weight: 700; color: #008500; margin-bottom: 4px;">$8,250.00</div>
                        <div style="font-size: 14px; color: #64748b;">Scheduled for September 15, 2025</div>
                    </div>
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <div style="text-align: right;">
                            <div style="font-size: 14px; font-weight: 600; color: #111827;">PayPal</div>
                            <div style="font-size: 13px; color: #64748b;">provider@devstudiolabs.com</div>
                        </div>
                        <div style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; background: #eff6ff; color: #1d4ed8; border-radius: 12px; font-size: 20px;">
                            <i class="fa-brands fa-paypal"></i>
                        </div>
                    </div>
                </div>
                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                        <div>
                            <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;">Pending Balance</div>
                            <div style="font-size: 16px; font-weight: 600; color: #111827;">$8,250.00</div>
                        </div>
                        <div>
                            <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;">Platform Fee (10%)</div>
                            <div style="font-size: 16px; font-weight: 600; color: #b91c1c;">-$825.00</div>
                        </div>
                        <div>
                            <div style="font-size: 13px; color: #64748b; margin-bottom: 4px;">Net Payout</div>
                            <div style="font-size: 16px; font-weight: 600; color: #008500;">$7,425.00</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        -->
    </main>

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

            document.getElementById('btn-preview-report').addEventListener('click', function() {
                const startDate = document.getElementById('report-start').value;
                const endDate = document.getElementById('report-end').value;
                if (!startDate || !endDate) {
                    alert('Please select both start and end dates.');
                    return;
                }
                if (new Date(startDate) > new Date(endDate)) {
                    alert('Start date must be before end date.');
                    return;
                }
                // Mock report data based on date range
                const days = Math.ceil((new Date(endDate) - new Date(startDate)) / (1000 * 60 * 60 * 24));
                const total = Math.round(days * 142.83 * 100) / 100;
                const fees = Math.round(total * 0.1 * 100) / 100;
                const net = Math.round((total - fees) * 100) / 100;
                const count = Math.max(1, Math.round(days / 7));

                document.getElementById('rpt-total').textContent = '$' + total.toLocaleString(undefined, {minimumFractionDigits: 2});
                document.getElementById('rpt-fees').textContent = '-$' + fees.toLocaleString(undefined, {minimumFractionDigits: 2});
                document.getElementById('rpt-net').textContent = '$' + net.toLocaleString(undefined, {minimumFractionDigits: 2});
                document.getElementById('rpt-count').textContent = count;
                document.getElementById('report-preview').style.display = 'block';
            });

            document.getElementById('btn-download-report').addEventListener('click', function() {
                const startDate = document.getElementById('report-start').value;
                const endDate = document.getElementById('report-end').value;
                if (!startDate || !endDate) {
                    alert('Please select both start and end dates.');
                    return;
                }
                alert('Report for ' + startDate + ' to ' + endDate + ' will be generated and downloaded.');
            });
        });
    </script>
</body>

</html>

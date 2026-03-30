<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Earnings | Provider Dashboard</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/earnings.css" />
    
</head>

<body>
    <?php // Use filesystem path for includes (BASE_URL is for URLs, not filesystem)
    require_once __DIR__ . '/../../includes/navbar.php'; ?>
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
                <div class="metric-value">$42,850</div>
                <div class="metric-delta delta-up">
                    <i class="fa-solid fa-arrow-up"></i> 18% from last month
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background:#fefce8; color:#b45309;">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="metric-title">Pending Payout</div>
                <div class="metric-value">$8,250</div>
                <div class="metric-delta" style="color:#b45309;">
                    <i class="fa-solid fa-hourglass"></i> Next payout: Sep 15
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background:#eff6ff; color:#1d4ed8;">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="metric-title">Avg. Project Value</div>
                <div class="metric-value">$3,570</div>
                <div class="metric-delta delta-up">
                    <i class="fa-solid fa-arrow-up"></i> 12% increase
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background:#fae8ff; color:#a855f7;">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="metric-title">Completed Projects</div>
                <div class="metric-value">12</div>
                <div class="metric-delta delta-up">
                    <i class="fa-solid fa-arrow-up"></i> 3 this month
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
                        <tr>
                            <td>
                                <div class="transaction-project">E-commerce Platform</div>
                                <div class="transaction-client">Invoice #INV-10452</div>
                                <span class="payment-type-label label-completed-project"><i class="fa-solid fa-circle-check"></i> Completed Project</span>
                            </td>
                            <td>Sep 02, 2025</td>
                            <td>TechCorp Inc</td>
                            <td>
                                <div class="transaction-amount">$4,200.00</div>
                                <div class="transaction-fee">Fee: $420.00</div>
                            </td>
                            <td><span class="transaction-status status-completed">Completed</span></td>
                            <td style="text-align: right;">
                                <button class="ghost-btn"><i class="fa-solid fa-receipt"></i> Receipt</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="transaction-project">Analytics Dashboard</div>
                                <div class="transaction-client">Invoice #INV-10398</div>
                                <span class="payment-type-label label-completed-project"><i class="fa-solid fa-circle-check"></i> Completed Project</span>
                            </td>
                            <td>Aug 28, 2025</td>
                            <td>DataSolutions LLC</td>
                            <td>
                                <div class="transaction-amount">$3,500.00</div>
                                <div class="transaction-fee">Fee: $350.00</div>
                            </td>
                            <td><span class="transaction-status status-completed">Completed</span></td>
                            <td style="text-align: right;">
                                <button class="ghost-btn"><i class="fa-solid fa-receipt"></i> Receipt</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="transaction-project">Mobile App UI/UX</div>
                                <div class="transaction-client">Invoice #INV-10375</div>
                                <span class="payment-type-label label-cancellation-penalty"><i class="fa-solid fa-triangle-exclamation"></i> Cancellation Penalty</span>
                            </td>
                            <td>Aug 22, 2025</td>
                            <td>FitnessPlus</td>
                            <td>
                                <div class="transaction-amount">$2,800.00</div>
                                <div class="transaction-fee">Fee: $280.00</div>
                            </td>
                            <td><span class="transaction-status status-pending">Pending</span></td>
                            <td style="text-align: right;">
                                <button class="ghost-btn"><i class="fa-solid fa-eye"></i> View</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="transaction-project">CRM Integration</div>
                                <div class="transaction-client">Invoice #INV-10321</div>
                                <span class="payment-type-label label-completed-project"><i class="fa-solid fa-circle-check"></i> Completed Project</span>
                            </td>
                            <td>Aug 15, 2025</td>
                            <td>SalesForce Pro</td>
                            <td>
                                <div class="transaction-amount">$5,100.00</div>
                                <div class="transaction-fee">Fee: $510.00</div>
                            </td>
                            <td><span class="transaction-status status-processing">Processing</span></td>
                            <td style="text-align: right;">
                                <button class="ghost-btn"><i class="fa-solid fa-eye"></i> View</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="transaction-project">WordPress E-commerce</div>
                                <div class="transaction-client">Invoice #INV-10294</div>
                                <span class="payment-type-label label-completed-project"><i class="fa-solid fa-circle-check"></i> Completed Project</span>
                            </td>
                            <td>Aug 08, 2025</td>
                            <td>RetailTech</td>
                            <td>
                                <div class="transaction-amount">$2,400.00</div>
                                <div class="transaction-fee">Fee: $240.00</div>
                            </td>
                            <td><span class="transaction-status status-completed">Completed</span></td>
                            <td style="text-align: right;">
                                <button class="ghost-btn"><i class="fa-solid fa-receipt"></i> Receipt</button>
                            </td>
                        </tr>
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
            // --- Chart Data per period ---
            const chartData = {
                '1M': {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    earnings: [3200, 4800, 2100, 4200],
                    fees: [320, 480, 210, 420]
                },
                '3M': {
                    labels: ['Jul', 'Aug', 'Sep'],
                    earnings: [9800, 12400, 14300],
                    fees: [980, 1240, 1430]
                },
                '6M': {
                    labels: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                    earnings: [6200, 7800, 8500, 9800, 12400, 14300],
                    fees: [620, 780, 850, 980, 1240, 1430]
                },
                '1Y': {
                    labels: ['Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                    earnings: [3100, 4200, 5500, 4800, 5200, 6100, 6200, 7800, 8500, 9800, 12400, 14300],
                    fees: [310, 420, 550, 480, 520, 610, 620, 780, 850, 980, 1240, 1430]
                },
                'All': {
                    labels: ['Q1 24', 'Q2 24', 'Q3 24', 'Q4 24', 'Q1 25', 'Q2 25', 'Q3 25'],
                    earnings: [8200, 11500, 14200, 13700, 16100, 22500, 36500],
                    fees: [820, 1150, 1420, 1370, 1610, 2250, 3650]
                }
            };

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


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Earnings | Provider Dashboard</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css" />
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css" />
    <style>
        /* Layout */
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #f8fafc;
            margin: 0;
        }

        .dashboard-wrapper {
            max-width: 1280px;
            margin: 0 auto;
            padding: 40px 24px 80px;
        }

        header.dashboard{
            padding: 2rem;
            margin-bottom: 2rem;
            border-radius: 1rem;
            position: relative;
            z-index: -1;
        }

        h1 {
            font-size: 32px;
            color: #111827;
            font-weight: 700;
        }

        .subtitle {
            color: #475569;
        }

        /* Reusable Cards */
        .card,
        .earning-card,
        .metric-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 24px 24px 28px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, .05), 0 2px 4px -1px rgba(0, 0, 0, .04);
            transition: all .3s cubic-bezier(.4, 0, .2, 1);
        }

        .card::before,
        .earning-card::before,
        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #008500;
        }

        .card:hover,
        .earning-card:hover,
        .metric-card:hover {
            transform: translateY(-4px);
            border-color: #008500;
            box-shadow: 0 16px 24px -6px rgba(0, 0, 0, .12), 0 8px 12px -6px rgba(0, 0, 0, .08);
        }

        /* Metrics Grid */
        .metrics-grid {
            display: grid;
            gap: 24px;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            margin-bottom: 40px;
        }

        .metric-card {
            padding: 22px 22px 26px;
        }

        .metric-icon {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eff6ff;
            color: #1d4ed8;
            border-radius: 12px;
            font-size: 18px;
            margin-bottom: 18px;
        }

        .metric-title {
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .5px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 4px;
        }

        .metric-value {
            font-size: 28px;
            font-weight: 800;
            color: #111827;
            line-height: 1.1;
        }

        .metric-delta {
            margin-top: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .delta-up {
            color: #008500;
        }

        .delta-down {
            color: #b91c1c;
        }

        /* Period Selector */
        .period-selector {
            display: flex;
            gap: 8px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .period-btn {
            background: #fff;
            border: 1px solid #e5e7eb;
            color: #374151;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all .25s ease;
        }

        .period-btn:hover {
            border-color: #008500;
            color: #008500;
        }

        .period-btn.active {
            background: #008500;
            border-color: #008500;
            color: #fff;
        }

        /* Earnings Chart */
        .chart-container {
            margin-bottom: 40px;
        }

        .chart-card {
            padding: 24px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .chart-title {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        .chart-legend {
            display: flex;
            gap: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #64748b;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .chart-placeholder {
            height: 300px;
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 14px;
        }

        /* Transactions Table */
        .transactions-section {
            margin-bottom: 40px;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 8px 0 20px;
        }

        .section-header h2 {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            color: #111827;
        }

        .section-actions {
            display: flex;
            gap: 12px;
        }

        .transactions-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 14px;
        }

        .transactions-table th {
            background: #f1f5f9;
            text-align: left;
            font-size: 12px;
            letter-spacing: .5px;
            text-transform: uppercase;
            color: #475569;
            padding: 14px 20px;
            font-weight: 600;
        }

        .transactions-table td {
            padding: 16px 20px;
            border-top: 1px solid #e5e7eb;
        }

        .transaction-project {
            font-weight: 600;
            color: #111827;
        }

        .transaction-client {
            font-size: 13px;
            color: #64748b;
            margin-top: 4px;
        }

        .transaction-amount {
            font-weight: 700;
            color: #008500;
        }

        .transaction-fee {
            color: #64748b;
            font-size: 12px;
        }

        .transaction-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .5px;
            text-transform: uppercase;
            display: inline-block;
        }

        .status-completed {
            background: #dcfce7;
            color: #008500;
        }

        .status-pending {
            background: #fef3c7;
            color: #b45309;
        }

        .status-processing {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-failed {
            background: #fee2e2;
            color: #b91c1c;
        }

        /* Payout Methods */
        .payout-section {
            margin-bottom: 40px;
        }

        .payout-methods {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }

        .payout-method {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s ease;
        }

        .payout-method:hover {
            border-color: #008500;
        }

        .payout-method.active {
            border-color: #008500;
            background: #f0fdf4;
        }

        .method-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eff6ff;
            color: #1d4ed8;
            border-radius: 12px;
            font-size: 20px;
        }

        .method-details {
            flex: 1;
        }

        .method-name {
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
        }

        .method-info {
            font-size: 13px;
            color: #64748b;
        }

        .method-action {
            font-size: 12px;
            color: #008500;
            font-weight: 600;
            cursor: pointer;
        }

        /* Buttons */
        .primary-btn {
            background: #008500;
            border: 1px solid #008500;
            color: #fff;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all .25s ease;
        }

        .primary-btn:hover {
            box-shadow: 0 6px 18px -4px rgba(0, 133, 0, .4);
            transform: translateY(-2px);
        }

        .ghost-btn {
            background: #fff;
            border: 1px solid #e5e7eb;
            color: #374151;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all .25s ease;
        }

        .ghost-btn:hover {
            border-color: #008500;
            color: #008500;
        }

        .link-btn {
            background: #fff;
            border: 1px solid #e5e7eb;
            color: #374151;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            gap: 6px;
            align-items: center;
            transition: all .25s ease;
        }

        .link-btn:hover {
            border-color: #008500;
            color: #008500;
        }

        /* Tax Section */
        .tax-section {
            margin-bottom: 40px;
        }

        .tax-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .tax-card {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }

        .tax-value {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .tax-label {
            font-size: 13px;
            color: #64748b;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .chart-header {
                flex-direction: column;
                gap: 16px;
                align-items: flex-start;
            }
            
            .transactions-table {
                display: block;
                overflow-x: auto;
            }
            
            .payout-methods {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .dashboard-wrapper {
                padding: 32px 18px 72px;
            }
            
            .metrics-grid {
                grid-template-columns: 1fr;
            }
            
            .section-header {
                flex-direction: column;
                gap: 16px;
                align-items: flex-start;
            }
            
            .tax-summary {
                grid-template-columns: 1fr;
            }
        }
    </style>
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
                        <button class="period-btn active">1M</button>
                        <button class="period-btn">3M</button>
                        <button class="period-btn">6M</button>
                        <button class="period-btn">1Y</button>
                        <button class="period-btn">All</button>
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
                <div class="chart-placeholder">
                    <div style="text-align: center;">
                        <i class="fa-regular fa-chart-bar" style="font-size: 48px; margin-bottom: 16px; display: block;"></i>
                        Earnings chart visualization<br>
                        <span style="font-size: 12px;">(Interactive chart would be implemented with a charting library)</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recent Transactions -->
        <section class="transactions-section">
            <div class="section-header">
                <h2>Recent Transactions</h2>
                <div class="section-actions">
                    <button class="ghost-btn"><i class="fa-regular fa-download"></i> Export CSV</button>
                    <button class="link-btn"><i class="fa-regular fa-arrow-right"></i> View All</button>
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
                            </td>
                            <td>Sep 02, 2025</td>
                            <td>TechCorp Inc</td>
                            <td>
                                <div class="transaction-amount">$4,200.00</div>
                                <div class="transaction-fee">Fee: $420.00</div>
                            </td>
                            <td><span class="transaction-status status-completed">Completed</span></td>
                            <td style="text-align: right;">
                                <button class="ghost-btn"><i class="fa-regular fa-receipt"></i> Receipt</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="transaction-project">Analytics Dashboard</div>
                                <div class="transaction-client">Invoice #INV-10398</div>
                            </td>
                            <td>Aug 28, 2025</td>
                            <td>DataSolutions LLC</td>
                            <td>
                                <div class="transaction-amount">$3,500.00</div>
                                <div class="transaction-fee">Fee: $350.00</div>
                            </td>
                            <td><span class="transaction-status status-completed">Completed</span></td>
                            <td style="text-align: right;">
                                <button class="ghost-btn"><i class="fa-regular fa-receipt"></i> Receipt</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="transaction-project">Mobile App UI/UX</div>
                                <div class="transaction-client">Invoice #INV-10375</div>
                            </td>
                            <td>Aug 22, 2025</td>
                            <td>FitnessPlus</td>
                            <td>
                                <div class="transaction-amount">$2,800.00</div>
                                <div class="transaction-fee">Fee: $280.00</div>
                            </td>
                            <td><span class="transaction-status status-pending">Pending</span></td>
                            <td style="text-align: right;">
                                <button class="ghost-btn"><i class="fa-regular fa-eye"></i> View</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="transaction-project">CRM Integration</div>
                                <div class="transaction-client">Invoice #INV-10321</div>
                            </td>
                            <td>Aug 15, 2025</td>
                            <td>SalesForce Pro</td>
                            <td>
                                <div class="transaction-amount">$5,100.00</div>
                                <div class="transaction-fee">Fee: $510.00</div>
                            </td>
                            <td><span class="transaction-status status-processing">Processing</span></td>
                            <td style="text-align: right;">
                                <button class="ghost-btn"><i class="fa-regular fa-eye"></i> View</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="transaction-project">WordPress E-commerce</div>
                                <div class="transaction-client">Invoice #INV-10294</div>
                            </td>
                            <td>Aug 08, 2025</td>
                            <td>RetailTech</td>
                            <td>
                                <div class="transaction-amount">$2,400.00</div>
                                <div class="transaction-fee">Fee: $240.00</div>
                            </td>
                            <td><span class="transaction-status status-completed">Completed</span></td>
                            <td style="text-align: right;">
                                <button class="ghost-btn"><i class="fa-regular fa-receipt"></i> Receipt</button>
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
                <button class="primary-btn"><i class="fa-regular fa-plus"></i> Add Method</button>
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
                        <i class="fa-regular fa-building-columns"></i>
                    </div>
                    <div class="method-details">
                        <div class="method-name">Bank Transfer</div>
                        <div class="method-info">**** 4829 • Chase Bank</div>
                    </div>
                    <div class="method-action">Set as Primary</div>
                </div>
                <div class="payout-method">
                    <div class="method-icon" style="background:#fef3c7; color:#b45309;">
                        <i class="fa-regular fa-credit-card"></i>
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
                <button class="ghost-btn"><i class="fa-regular fa-download"></i> Tax Documents</button>
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
                    <i class="fa-regular fa-circle-info"></i> 
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
                <button class="primary-btn"><i class="fa-regular fa-arrow-down"></i> Request Early Payout</button>
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

    <script>
        // Period selector functionality
        document.addEventListener('DOMContentLoaded', function() {
            const periodButtons = document.querySelectorAll('.period-btn');
            
            periodButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    periodButtons.forEach(btn => btn.classList.remove('active'));
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // In a real application, you would update the chart data here
                    console.log('Selected period:', this.textContent);
                });
            });
            
            // Payout method selection
            const payoutMethods = document.querySelectorAll('.payout-method');
            
            payoutMethods.forEach(method => {
                method.addEventListener('click', function() {
                    if (this.classList.contains('active')) return;
                    
                    // Remove active class from all methods
                    payoutMethods.forEach(m => m.classList.remove('active'));
                    // Add active class to clicked method
                    this.classList.add('active');
                    
                    // Update method actions
                    payoutMethods.forEach(m => {
                        const action = m.querySelector('.method-action');
                        if (m === this) {
                            action.textContent = 'Primary';
                        } else {
                            action.textContent = 'Set as Primary';
                        }
                    });
                    
                    console.log('Selected payout method:', this.querySelector('.method-name').textContent);
                });
            });
        });
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Earnings | Provider Dashboard</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/provider-earnings.css" />
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css" />
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

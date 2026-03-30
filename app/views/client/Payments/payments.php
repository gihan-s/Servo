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
                    <!-- Awaiting Payment 1 -->
                    <div class="search-item" data-status="awaiting">
                        <div class="status-badge status-pending">Awaiting Payment</div>
                        <div class="item-head">
                            <div class="item-main-dets">
                                <div class="item-title">3D Asset Pack Creation</div>
                                <div class="item-district">
                                    <span>Accepted: 15 Aug 2025</span>
                                    <span>Rate: $90/hr</span>
                                </div>
                            </div>
                            <div class="post-actions">
                                <button class="action-btn btn-outline"><i class="fa-solid fa-message"></i> Message</button>
                                <button class="action-btn btn-primary"><i class="fa-solid fa-credit-card"></i> Pay Now</button>
                                <button class="action-btn btn-danger"><i class="fa-solid fa-ban"></i> Cancel</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-solid fa-hourglass"></i> Estimated Time: 6 days</div>
                            <div><i class="fa-solid fa-dollar-sign"></i> Estimated Total: $4,320.00</div>
                        </div>
                        <div class="post-description">Creating 15 optimized low-poly environment props for prototype. Payment required before work begins.</div>
                        <div class="post-footer">
                            <div class="post-details">
                                <div class="detail-item"><span class="detail-label">Amount</span><span class="detail-value budget-amount">$4,320.00</span></div>
                                <div class="detail-item"><span class="detail-label">Provider</span><span class="detail-value">DevStudio Labs</span></div>
                                <div class="detail-item"><span class="detail-label">Type</span><span class="detail-value">Bid Request</span></div>
                                <div class="detail-item"><span class="detail-label">Project</span><span class="detail-value">Game Assets</span></div>
                            </div>
                        </div>
                    </div>
                    <!-- Awaiting Payment 2 -->
                    <div class="search-item" data-status="awaiting">
                        <div class="status-badge status-pending">Awaiting Payment</div>
                        <div class="item-head">
                            <div class="item-main-dets">
                                <div class="item-title">Brand Identity Development</div>
                                <div class="item-district">
                                    <span>Accepted: 01 Sep 2025</span>
                                    <span>Rate: $75/hr</span>
                                </div>
                            </div>
                            <div class="post-actions">
                                <button class="action-btn btn-outline"><i class="fa-solid fa-message"></i> Message</button>
                                <button class="action-btn btn-primary"><i class="fa-solid fa-credit-card"></i> Pay Now</button>
                                <button class="action-btn btn-danger"><i class="fa-solid fa-ban"></i> Cancel</button>
                            </div>
                        </div>
                        <div class="item-middle">
                            <div><i class="fa-solid fa-hourglass"></i> Estimated Time: 10 days</div>
                            <div><i class="fa-solid fa-dollar-sign"></i> Estimated Total: $6,000.00</div>
                        </div>
                        <div class="post-description">Developing a comprehensive brand identity including logo, color palette, and typography. Milestone payment needed to proceed.</div>
                        <div class="post-footer">
                            <div class="post-details">
                                <div class="detail-item"><span class="detail-label">Amount</span><span class="detail-value budget-amount">$6,000.00</span></div>
                                <div class="detail-item"><span class="detail-label">Provider</span><span class="detail-value">UXPro Studio</span></div>
                                <div class="detail-item"><span class="detail-label">Type</span><span class="detail-value">Direct Request</span></div>
                                <div class="detail-item"><span class="detail-label">Project</span><span class="detail-value">Brand Suite</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="pagination" aria-label="Pagination Awaiting Payments">
                        <button class="page-btn prev" disabled><i class="fa-solid fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn next"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>

            <!-- PENDING PAYMENTS SECTION -->
            <div class="pending-payments requests-section" style="display:none;">
                <div class="item-list">
                    <!-- Pending Payment 1 -->
                    <div class="search-item">
                        <div class="status-badge status-pending">Pending</div>
                        <span class="payment-type-label label-completed-project"><i class="fa-solid fa-circle-check"></i> Completed Project</span>
                        <div class="post-header">
                            <div class="post-meta">
                                <div class="post-date">
                                    <i class="fas fa-calendar"></i>
                                    <span>Invoice Date: Sep 02, 2025</span>
                                </div>
                            </div>
                            <div class="post-actions">
                                <button class="action-btn btn-primary"><i class="fas fa-credit-card"></i>Pay Now</button>
                                <button class="action-btn btn-secondary"><i class="fas fa-eye"></i>View Invoice</button>
                                <button class="action-btn btn-danger"><i class="fas fa-ban"></i>Cancel</button>
                            </div>
                        </div>
                        <h3 class="post-title">Invoice #INV-10452 • Development Sprint 3</h3>
                        <div class="post-description">Payment for sprint 3 covering implementation of authentication module, profile settings page, and database optimization tasks as agreed in the project milestone plan.</div>
                        <div class="post-footer">
                            <div class="post-details">
                                <div class="detail-item"><span class="detail-label">Amount</span><span class="detail-value budget-amount">$1,200.00</span></div>
                                <div class="detail-item"><span class="detail-label">Method</span><span class="detail-value">Card (Visa)</span></div>
                                <div class="detail-item"><span class="detail-label">Due Date</span><span class="detail-value">Sep 15, 2025</span></div>
                                <div class="detail-item"><span class="detail-label">Project</span><span class="detail-value">E-Commerce App</span></div>
                            </div>
                        </div>
                    </div>
                    <!-- Pending Payment 2 -->
                    <div class="search-item">
                        <div class="status-badge status-pending">Pending</div>
                        <span class="payment-type-label label-cancellation-penalty"><i class="fa-solid fa-triangle-exclamation"></i> Cancellation Penalty</span>
                        <div class="post-header">
                            <div class="post-meta">
                                <div class="post-date"><i class="fas fa-calendar"></i><span>Invoice Date: Sep 05, 2025</span></div>
                            </div>
                            <div class="post-actions">
                                <button class="action-btn btn-primary"><i class="fas fa-credit-card"></i>Pay Now</button>
                                <button class="action-btn btn-secondary"><i class="fas fa-eye"></i>View Invoice</button>
                                <button class="action-btn btn-danger"><i class="fas fa-ban"></i>Cancel</button>
                            </div>
                        </div>
                        <h3 class="post-title">Invoice #INV-10463 • UI Design Phase</h3>
                        <div class="post-description">Cancellation penalty for UI/UX design project that was terminated after initial milestone. Penalty as per service agreement terms.</div>
                        <div class="post-footer">
                            <div class="post-details">
                                <div class="detail-item"><span class="detail-label">Amount</span><span class="detail-value budget-amount">$680.00</span></div>
                                <div class="detail-item"><span class="detail-label">Method</span><span class="detail-value">PayPal</span></div>
                                <div class="detail-item"><span class="detail-label">Due Date</span><span class="detail-value">Sep 18, 2025</span></div>
                                <div class="detail-item"><span class="detail-label">Project</span><span class="detail-value">Mobile Fitness App</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="pagination" aria-label="Pagination Pending Payments">
                        <button class="page-btn prev" disabled><i class="fa-solid fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn next"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
            <!-- COMPLETED PAYMENTS SECTION -->
            <div class="completed-payments requests-section" style="display:none;">
                <div class="item-list">
                    <!-- Completed Payment 1 -->
                    <div class="search-item">
                        <div class="status-badge status-paid">Paid</div>
                        <span class="payment-type-label label-completed-project"><i class="fa-solid fa-circle-check"></i> Completed Project</span>
                        <div class="post-header">
                            <div class="post-meta"><div class="post-date"><i class="fas fa-calendar"></i><span>Paid on Aug 28, 2025</span></div></div>
                            <div class="post-actions">
                                <button class="action-btn btn-primary"><i class="fas fa-file"></i>Receipt</button>
                                <button class="action-btn btn-secondary"><i class="fas fa-download"></i>Download</button>
                                <button class="action-btn btn-outline"><i class="fas fa-rotate-left"></i>Refund</button>
                            </div>
                        </div>
                        <h3 class="post-title">Invoice #INV-10398 • Logo & Brand Pack</h3>
                        <div class="post-description">Final payment for brand identity delivery including vector logo assets, color guide and typography scale for marketing usage.</div>
                        <div class="post-footer"><div class="post-details">
                            <div class="detail-item"><span class="detail-label">Amount</span><span class="detail-value budget-amount">$950.00</span></div>
                            <div class="detail-item"><span class="detail-label">Method</span><span class="detail-value">Stripe</span></div>
                            <div class="detail-item"><span class="detail-label">Txn ID</span><span class="detail-value">TXN78C92</span></div>
                            <div class="detail-item"><span class="detail-label">Project</span><span class="detail-value">Brand Suite</span></div>
                        </div></div>
                    </div>
                    <!-- Completed Payment 2 -->
                    <div class="search-item">
                        <div class="status-badge status-paid">Paid</div>
                        <span class="payment-type-label label-cancellation-penalty"><i class="fa-solid fa-triangle-exclamation"></i> Cancellation Penalty</span>
                        <div class="post-header">
                            <div class="post-meta"><div class="post-date"><i class="fas fa-calendar"></i><span>Paid on Aug 22, 2025</span></div></div>
                            <div class="post-actions">
                                <button class="action-btn btn-primary"><i class="fas fa-file"></i>Receipt</button>
                                <button class="action-btn btn-secondary"><i class="fas fa-download"></i>Download</button>
                                <button class="action-btn btn-outline"><i class="fas fa-rotate-left"></i>Refund</button>
                            </div>
                        </div>
                        <h3 class="post-title">Invoice #INV-10374 • Analytics Dashboard</h3>
                        <div class="post-description">Cancellation penalty payment for analytics dashboard module per contract terms after early termination.</div>
                        <div class="post-footer"><div class="post-details">
                            <div class="detail-item"><span class="detail-label">Amount</span><span class="detail-value budget-amount">$1,480.00</span></div>
                            <div class="detail-item"><span class="detail-label">Method</span><span class="detail-value">Card (Mastercard)</span></div>
                            <div class="detail-item"><span class="detail-label">Txn ID</span><span class="detail-value">TXN65B11</span></div>
                            <div class="detail-item"><span class="detail-label">Project</span><span class="detail-value">BI Platform</span></div>
                        </div></div>
                    </div>
                    <div class="pagination" aria-label="Pagination Completed Payments">
                        <button class="page-btn prev" disabled><i class="fa-solid fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn next"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
            <!-- REFUNDED PAYMENTS SECTION -->
            <div class="refunded-payments requests-section" style="display:none;">
                <div class="item-list">
                    <!-- Refunded Payment 1 -->
                    <div class="search-item">
                        <div class="status-badge status-refunded">Refunded</div>
                        <div class="post-header">
                            <div class="post-meta"><div class="post-date"><i class="fas fa-calendar"></i><span>Refunded on Aug 30, 2025</span></div></div>
                            <div class="post-actions">
                                <button class="action-btn btn-secondary"><i class="fas fa-eye"></i>Details</button>
                                <button class="action-btn btn-outline"><i class="fas fa-circle-info"></i>Support</button>
                            </div>
                        </div>
                        <h3 class="post-title">Invoice #INV-10321 • QA Testing Cycle</h3>
                        <div class="post-description">Refund issued due to scope change after partial QA cycle execution. Remaining tasks were descoped and credited back.</div>
                        <div class="post-footer"><div class="post-details">
                            <div class="detail-item"><span class="detail-label">Amount</span><span class="detail-value budget-amount">$420.00</span></div>
                            <div class="detail-item"><span class="detail-label">Method</span><span class="detail-value">Stripe</span></div>
                            <div class="detail-item"><span class="detail-label">Refund ID</span><span class="detail-value">RFN9021</span></div>
                            <div class="detail-item"><span class="detail-label">Project</span><span class="detail-value">Platform QA</span></div>
                        </div></div>
                    </div>
                    <!-- Refunded Payment 2 -->
                    <div class="search-item">
                        <div class="status-badge status-refunded">Refunded</div>
                        <div class="post-header">
                            <div class="post-meta"><div class="post-date"><i class="fas fa-calendar"></i><span>Refunded on Aug 12, 2025</span></div></div>
                            <div class="post-actions">
                                <button class="action-btn btn-secondary"><i class="fas fa-eye"></i>Details</button>
                                <button class="action-btn btn-outline"><i class="fas fa-circle-info"></i>Support</button>
                            </div>
                        </div>
                        <h3 class="post-title">Invoice #INV-10294 • Initial Wireframes</h3>
                        <div class="post-description">Original design direction changed after stakeholder review. Early milestone payment was reversed and credited to account balance.</div>
                        <div class="post-footer"><div class="post-details">
                            <div class="detail-item"><span class="detail-label">Amount</span><span class="detail-value budget-amount">$300.00</span></div>
                            <div class="detail-item"><span class="detail-label">Method</span><span class="detail-value">Card (Visa)</span></div>
                            <div class="detail-item"><span class="detail-label">Refund ID</span><span class="detail-value">RFN8810</span></div>
                            <div class="detail-item"><span class="detail-label">Project</span><span class="detail-value">Design System</span></div>
                        </div></div>
                    </div>
                    <div class="pagination" aria-label="Pagination Refunded Payments">
                        <button class="page-btn prev" disabled><i class="fa-solid fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
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



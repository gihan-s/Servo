<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">

    <style>
        /* Client-Side Job Management Interface */
        

        .buttons {
            padding: 12px 24px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            border: none;
            background: transparent;
        }

        .buttons.active {
            color: white;
        }

        .buttons:not(.active):hover {
            background: #f3f4f6;
        }

        .item-list {
            display: grid;
            gap: 24px;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
        }

        .search-item {
            background: white;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #e5e7eb;
            position: relative;
            overflow: hidden;
        }

    .search-item::before { content:none; }

        .search-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: #008500;
        }

        /* Status Indicators */
        .status-badge {
            position: absolute;
            bottom: 16px; /* moved to bottom */
            right: 16px;
            top: auto; /* override previous top positioning */
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-active {
            background: #dcfce7;
            color: #008500;
            border: 1px solid #bbf7d0;
        }

        .status-draft {
            background: #fef3c7;
            color: #d97706;
            border: 1px solid #fed7aa;
        }

        .status-expired {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        /* Payment Status Variants */
        .status-pending { background:#fef3c7; color:#b45309; border:1px solid #fde68a; }
        .status-paid { background:#dcfce7; color:#047857; border:1px solid #bbf7d0; }
        .status-refunded { background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd; }
        .status-failed { background:#fee2e2; color:#b91c1c; border:1px solid #fecaca; }

        /* Payment Action Buttons */
        .btn-primary { background:#fff; color:#008500; border:2px solid #008500; }
        .btn-primary:hover { background:#008500; color:#fff; transform:translateY(-1px);}        
        .btn-secondary { background:#f3f4f6; color:#374151; border:2px solid #e5e7eb; }
        .btn-secondary:hover { background:#e5e7eb; border-color:#d1d5db; transform:translateY(-1px);}        
        .btn-danger { background:#fff; color:#b91c1c; border:2px solid #dc2626; }
        .btn-danger:hover { background:#dc2626; color:#fff; transform:translateY(-1px);}        
        .btn-outline { background:#fff; color:#0369a1; border:2px solid #0ea5e9; }
        .btn-outline:hover { background:#0ea5e9; color:#fff; transform:translateY(-1px);}        

        /* Section gradient accents for payment types */
    .pending-payments .search-item::before,
    .completed-payments .search-item::before,
    .refunded-payments .search-item::before { content:none; }

        /* Post Header Section */
        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .post-meta {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .post-date {
            color: #6b7280;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .post-actions {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            gap: 6px;
            text-transform: none;
        }

        .btn-edit {
            background: white;
            color: #008500;
            border: 2px solid #008500;
        }

        .btn-edit:hover {
            background: #008500;
            color: white;
            transform: translateY(-1px);
        }

        .btn-view {
            background: #f3f4f6;
            color: #374151;
            border: 2px solid #e5e7eb;
        }

        .btn-view:hover {
            background: #e5e7eb;
            border-color: #d1d5db;
            transform: translateY(-1px);
        }

        .btn-delete {
            background: white;
            color: #dc2626;
            border: 2px solid #dc2626;
        }

        .btn-delete:hover {
            background: #dc2626;
            color: white;
            transform: translateY(-1px);
        }

        /* Post Content Section */
        .post-title {
            font-size: 22px;
            font-weight: 700;
            color: #008500;
            margin-bottom: 16px;
            line-height: 1.4;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .post-title:hover {
            color: #006600;
            text-decoration: underline;
        }

        .post-description {
            color: #4b5563;
            line-height: 1.7;
            margin-bottom: 20px;
            font-size: 15px;
        }

        .post-skills {
            margin-bottom: 24px;
        }

        .skills-label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 10px;
            display: block;
        }

        .skills-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .skill-tag {
            background: #f1f5f9;
            color: #475569;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .skill-tag:hover {
            background: #008500;
            color: white;
            border-color: #008500;
            transform: translateY(-1px);
        }

        /* Pagination Component */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin: 40px auto 10px;
            flex-wrap: wrap;
        }
        .pagination .page-btn {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            color: #374151;
            min-width: 40px;
            height: 40px;
            padding: 0 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .25s ease;
        }
        .pagination .page-btn:hover {
            border-color: #008500;
            color: #008500;
            transform: translateY(-2px);
        }
        .pagination .page-btn.active {
            background: linear-gradient(135deg,#008500,#006600);
            color: #ffffff;
            border: 1px solid #008500;
            box-shadow: 0 4px 12px rgba(0, 133, 0, 0.25);
        }
        .pagination .page-btn.prev, .pagination .page-btn.next { padding: 0 18px; }
        @media (max-width:600px){
            .pagination .page-btn { min-width: 36px; height: 36px; font-size: 13px; }
        }

        /* Post Footer Section */
        .post-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 1px solid #f3f4f6;
        }

        .post-details {
            display: flex;
            gap: 32px;
        }

        .detail-item {
            text-align: center;
        }

        .detail-label {
            display: block;
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
        }

        .budget-amount {
            color: #008500;
            font-size: 18px;
        }

        .proposals-count {
            color: #3b82f6;
        }

        .project-level {
            background: #dcfce7;
            color: #008500;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .project-duration {
            background: #fef3c7;
            color: #92400e;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .engagement-stats {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-top: 8px;
            font-size: 12px;
            color: #6b7280;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Section-specific styling */
    .draft-section .search-item::before,
    .expired-section .search-item::before { content:none; }

        /* Responsive Design */
        @media (max-width: 768px) {
            .post-header {
                flex-direction: column;
                gap: 16px;
            }

            .post-details {
                flex-direction: column;
                gap: 16px;
            }

            .search-header {
                flex-direction: column;
            }

            .search-button {
                min-width: auto;
            }
        }
        
        
        .post-job-btn {
            background: linear-gradient(135deg, #008500, #006600);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px rgba(0, 133, 0, 0.3);
        }
        
        .post-job-btn:hover {
            background: linear-gradient(135deg, #006600, #004400);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 133, 0, 0.4);
        }
        
        .post-job-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(0, 133, 0, 0.3);
        }
    </style>

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
                    <button><i class="fa-light fa-magnifying-glass"></i></button>
                </div>
                <button class="filter" id="filter-pop-up"><i
                        class="fa-light fa-filter-list"></i><span>Filter</span></button>
                <div class="advance-search">
                    <div class="sort-selection">
                        <div class="selection-input-field">
                            <input type="selection-input" id="selection-input" name="sort" value="Sort By Relevence"
                                disabled><i class="fa-light fa-chevron-down"></i>
                        </div>
                        <div class="selection-options" id="selection-options">
                            <div class="opt">Sort By Relevence</div>
                            <div class="opt">Sort By Price</div>
                            <div class="opt">Sort By Rating</div>
                        </div>
                    </div>
                </div>
                
            </div>


            <div class="container-changer">
                <div id="pending-payments" class="buttons active">Pending Payments</div>
                <div id="completed-payments" class="buttons">Completed Payments</div>
                <div id="refunded-payments" class="buttons">Refunded Payments</div>
            </div>
        </div>

        <div class="request-content">
            <!-- PENDING PAYMENTS SECTION -->
            <div class="pending-payments active requests-section">
                <div class="item-list">
                    <!-- Pending Payment 1 -->
                    <div class="search-item">
                        <div class="status-badge status-pending">Pending</div>
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
                        <div class="post-description">UI/UX design deliverables for mobile dashboard screens, component library refinement and accessibility adjustments for phase 1 rollout.</div>
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
                        <button class="page-btn prev" disabled><i class="fa-regular fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn next"><i class="fa-regular fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
            <!-- COMPLETED PAYMENTS SECTION -->
            <div class="completed-payments requests-section" style="display:none;">
                <div class="item-list">
                    <!-- Completed Payment 1 -->
                    <div class="search-item">
                        <div class="status-badge status-paid">Paid</div>
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
                        <div class="post-header">
                            <div class="post-meta"><div class="post-date"><i class="fas fa-calendar"></i><span>Paid on Aug 22, 2025</span></div></div>
                            <div class="post-actions">
                                <button class="action-btn btn-primary"><i class="fas fa-file"></i>Receipt</button>
                                <button class="action-btn btn-secondary"><i class="fas fa-download"></i>Download</button>
                                <button class="action-btn btn-outline"><i class="fas fa-rotate-left"></i>Refund</button>
                            </div>
                        </div>
                        <h3 class="post-title">Invoice #INV-10374 • Analytics Dashboard</h3>
                        <div class="post-description">Completion payment for analytics dashboard module (KPI widgets, export function, caching layer) per milestone 4 acceptance.</div>
                        <div class="post-footer"><div class="post-details">
                            <div class="detail-item"><span class="detail-label">Amount</span><span class="detail-value budget-amount">$1,480.00</span></div>
                            <div class="detail-item"><span class="detail-label">Method</span><span class="detail-value">Card (Mastercard)</span></div>
                            <div class="detail-item"><span class="detail-label">Txn ID</span><span class="detail-value">TXN65B11</span></div>
                            <div class="detail-item"><span class="detail-label">Project</span><span class="detail-value">BI Platform</span></div>
                        </div></div>
                    </div>
                    <div class="pagination" aria-label="Pagination Completed Payments">
                        <button class="page-btn prev" disabled><i class="fa-regular fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn next"><i class="fa-regular fa-chevron-right"></i></button>
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
                        <button class="page-btn prev" disabled><i class="fa-regular fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn next"><i class="fa-regular fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>
<script>
    // Tab switching functionality for client-side job management
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.buttons');
        const sections = document.querySelectorAll('.requests-section');

        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                // Remove active class from all tabs and sections
                tabs.forEach(t => t.classList.remove('active'));
                sections.forEach(s => {
                    s.classList.remove('active');
                    s.style.display = 'none';
                });

                // Add active class to clicked tab
                this.classList.add('active');

                // Show corresponding section based on tab ID
                let sectionClass = '';
                if (this.id === 'pending-payments') {
                    sectionClass = 'pending-payments';
                } else if (this.id === 'completed-payments') {
                    sectionClass = 'completed-payments';
                } else if (this.id === 'refunded-payments') {
                    sectionClass = 'refunded-payments';
                }

                const section = document.querySelector('.' + sectionClass);
                if (section) {
                    section.classList.add('active');
                    section.style.display = 'block';
                }
            });
        });
    });
</script>
<script src="<?= BASE_URL ?>/assets/js/cardList.js" defer></script>

</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments - ServiceHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Page-specific styles only. Global styles are now in styles.css. */
        
        /* Breadcrumb */
        .breadcrumb {
            background-color: var(--secondary);
            padding: 1rem 5%;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .breadcrumb-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }
        
        .breadcrumb a {
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .breadcrumb span {
            color: var(--gray);
        }
        
        /* Main Content */
        .main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 5%;
        }
        
        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .page-title {
            font-size: 2rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        
        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        /* Stats Cards */
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background-color: var(--light);
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .stat-icon.blue {
            background-color: #e3ecff;
            color: var(--primary);
        }
        
        .stat-icon.green {
            background-color: #e6f7ee;
            color: var(--success);
        }
        
        .stat-icon.orange {
            background-color: #fff8e6;
            color: var(--warning);
        }
        
        .stat-icon.purple {
            background-color: #f0e6ff;
            color: #6a4bff;
        }
        
        .stat-info h3 {
            font-size: 1.8rem;
            margin-bottom: 0.2rem;
        }
        
        .stat-info p {
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Filter Tabs */
        .filter-tabs {
            display: flex;
            background-color: var(--light);
            border-radius: 10px;
            padding: 0.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            overflow-x: auto;
            max-width: 100%;
        }
        
        .filter-tab {
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .filter-tab.active {
            background-color: var(--primary);
            color: var(--light);
        }
        
        /* Transactions Container */
        .transactions-container {
            background-color: var(--light);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .transactions-header {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 0.5fr;
            padding: 1rem 1.5rem;
            background-color: var(--secondary);
            font-weight: 600;
            color: var(--gray);
            border-bottom: 1px solid var(--light-gray);
        }
        
        .transaction-item {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 0.5fr;
            padding: 1.5rem;
            border-bottom: 1px solid var(--light-gray);
            transition: background-color 0.3s;
            align-items: center;
        }
        
        .transaction-item:hover {
            background-color: #fafafa;
        }
        
        .transaction-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .transaction-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: linear-gradient(135deg, #e3ecff 0%, #f0f4ff 100%);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        
        .transaction-details h3 {
            margin-bottom: 0.3rem;
            font-size: 1rem;
        }
        
        .transaction-details p {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        .transaction-provider {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .provider-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4a6bff 0%, #6a4bff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.8rem;
        }
        
        .transaction-amount {
            font-weight: 600;
            color: var(--dark);
        }
        
        .transaction-date {
            color: var(--gray);
        }
        
        .transaction-status {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-completed {
            background-color: #e6f7ee;
            color: var(--success);
        }
        
        .status-pending {
            background-color: #fff8e6;
            color: var(--warning);
        }
        
        .status-failed {
            background-color: #ffe6e6;
            color: var(--danger);
        }
        
        .status-refunded {
            background-color: #e6f7ff;
            color: var(--info);
        }
        
        .transaction-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }
        
        /* Payment Methods Section */
        .payment-methods {
            background-color: var(--light);
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .section-title {
            font-size: 1.3rem;
            color: var(--dark);
        }
        
        .methods-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .method-card {
            border: 1px solid var(--light-gray);
            border-radius: 10px;
            padding: 1.5rem;
            transition: all 0.3s;
            position: relative;
        }
        
        .method-card:hover {
            border-color: var(--primary);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .method-card.default {
            border-color: var(--primary);
            background-color: #f0f4ff;
        }
        
        .method-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .method-type {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-weight: 600;
        }
        
        .method-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: linear-gradient(135deg, #e3ecff 0%, #f0f4ff 100%);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        
        .default-badge {
            background-color: var(--primary);
            color: var(--light);
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 500;
        }
        
        .method-details {
            margin-bottom: 1.5rem;
        }
        
        .method-number {
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }
        
        .method-info {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        .method-actions {
            display: flex;
            gap: 1rem;
        }
        
        /* Buttons */
        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
        }
        
        .btn-outline:hover {
            background-color: var(--primary);
            color: var(--light);
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: var(--light);
        }
        
        .btn-primary:hover {
            background-color: #016223;;
        }
        
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }
        
        .btn-icon {
            width: 36px;
            height: 36px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: transparent;
            border: 1px solid var(--light-gray);
            cursor: pointer;
            transition: all 0.3s;
            color: var(--gray);
        }
        
        .btn-icon:hover {
            background-color: var(--secondary);
            color: var(--primary);
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }
        
        .pagination-button {
            padding: 0.6rem 1rem;
            border: 1px solid var(--light-gray);
            border-radius: 5px;
            background-color: var(--light);
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
        }
        
        .pagination-button:hover {
            background-color: var(--primary);
            color: var(--light);
            border-color: var(--primary);
        }
        
        .pagination-button.active {
            background-color: var(--primary);
            color: var(--light);
            border-color: var(--primary);
        }
        
        /* Footer */
        footer {
            background-color: var(--dark);
            color: var(--light);
            padding: 3rem 5%;
            margin-top: 3rem;
        }
        
        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }
        
        .footer-column h3 {
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
        }
        
        .footer-column ul {
            list-style: none;
        }
        
        .footer-column ul li {
            margin-bottom: 0.8rem;
        }
        
        .footer-column ul li a {
            color: #adb5bd;
            text-decoration: none;
            transition: color 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .footer-column ul li a:hover {
            color: var(--light);
        }
        
        .copyright {
            text-align: center;
            padding-top: 2rem;
            margin-top: 2rem;
            border-top: 1px solid #495057;
            color: #adb5bd;
        }
        
        /* Responsive Design */
        @media (max-width: 1024px) {
            .transactions-header, .transaction-item {
                grid-template-columns: 1fr 1fr 1fr;
                gap: 1rem;
            }
            
            .transaction-actions {
                grid-column: span 3;
                justify-content: center;
                margin-top: 1rem;
            }
        }
        
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .user-name {
                display: none;
            }
            
            .transactions-header {
                display: none;
            }
            
            .transaction-item {
                display: flex;
                flex-direction: column;
                gap: 1rem;
                padding: 1.5rem;
                align-items: flex-start;
            }
            
            .transaction-info {
                width: 100%;
            }
            
            .transaction-details {
                flex: 1;
            }
            
            .transaction-meta {
                display: flex;
                flex-wrap: wrap;
                gap: 1rem;
                width: 100%;
            }
            
            .transaction-meta > div {
                flex: 1;
                min-width: 120px;
            }
            
            .transaction-actions {
                width: 100%;
                justify-content: flex-start;
            }
            
            .methods-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="breadcrumb-content">
            <a href="client-dashboard.html">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <span>›</span>
            <span>Payments</span>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-credit-card"></i>
                Payments
            </h1>
            <div class="header-actions">
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Add Payment Method
                </button>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-info">
                    <h3>$2,850</h3>
                    <p>Total Spent</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>24</h3>
                    <p>Completed Payments</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>3</h3>
                    <p>Pending Payments</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="stat-info">
                    <h3>2</h3>
                    <p>Refund Requests</p>
                </div>
            </div>
        </div>
        
        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <div class="filter-tab active" data-filter="all">
                <i class="fas fa-layer-group"></i>
                All Transactions
            </div>
            <div class="filter-tab" data-filter="completed">
                <i class="fas fa-check-circle"></i>
                Completed
            </div>
            <div class="filter-tab" data-filter="pending">
                <i class="fas fa-clock"></i>
                Pending
            </div>
            <div class="filter-tab" data-filter="failed">
                <i class="fas fa-times-circle"></i>
                Failed
            </div>
            <div class="filter-tab" data-filter="refunded">
                <i class="fas fa-exchange-alt"></i>
                Refunded
            </div>
        </div>
        
        <!-- Transactions Container -->
        <div class="transactions-container">
            <!-- Transactions Header -->
            <div class="transactions-header">
                <div>Transaction Details</div>
                <div>Provider</div>
                <div>Amount</div>
                <div>Date</div>
                <div>Status</div>
            </div>
            
            <!-- Transaction Item 1 -->
            <div class="transaction-item" data-status="completed">
                <div class="transaction-info">
                    <div class="transaction-icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <div class="transaction-details">
                        <h3>Website Development</h3>
                        <p>Project: E-commerce Store</p>
                    </div>
                </div>
                <div class="transaction-provider">
                    <div class="provider-avatar">SJ</div>
                    <span>Sarah Johnson</span>
                </div>
                <div class="transaction-amount">$1,200.00</div>
                <div class="transaction-date">Jun 15, 2023</div>
                <div class="transaction-status status-completed">Completed</div>
                <div class="transaction-actions">
                    <button class="btn-icon" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-icon" title="Download Invoice">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
            
            <!-- Transaction Item 2 -->
            <div class="transaction-item" data-status="pending">
                <div class="transaction-info">
                    <div class="transaction-icon">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                    <div class="transaction-details">
                        <h3>Logo Design</h3>
                        <p>Project: Tech Startup</p>
                    </div>
                </div>
                <div class="transaction-provider">
                    <div class="provider-avatar">MC</div>
                    <span>Michael Chen</span>
                </div>
                <div class="transaction-amount">$350.00</div>
                <div class="transaction-date">Jun 10, 2023</div>
                <div class="transaction-status status-pending">Pending</div>
                <div class="transaction-actions">
                    <button class="btn-icon" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-icon" title="Cancel Payment">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <!-- Transaction Item 3 -->
            <div class="transaction-item" data-status="completed">
                <div class="transaction-info">
                    <div class="transaction-icon">
                        <i class="fas fa-wrench"></i>
                    </div>
                    <div class="transaction-details">
                        <h3>Home Repair</h3>
                        <p>Project: Plumbing Fix</p>
                    </div>
                </div>
                <div class="transaction-provider">
                    <div class="provider-avatar">DW</div>
                    <span>David Wilson</span>
                </div>
                <div class="transaction-amount">$850.00</div>
                <div class="transaction-date">May 30, 2023</div>
                <div class="transaction-status status-completed">Completed</div>
                <div class="transaction-actions">
                    <button class="btn-icon" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-icon" title="Download Invoice">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
            
            <!-- Transaction Item 4 -->
            <div class="transaction-item" data-status="refunded">
                <div class="transaction-info">
                    <div class="transaction-icon">
                        <i class="fas fa-pencil-alt"></i>
                    </div>
                    <div class="transaction-details">
                        <h3>Content Writing</h3>
                        <p>Project: Blog Articles</p>
                    </div>
                </div>
                <div class="transaction-provider">
                    <div class="provider-avatar">LW</div>
                    <span>Lisa Wong</span>
                </div>
                <div class="transaction-amount">$280.00</div>
                <div class="transaction-date">May 22, 2023</div>
                <div class="transaction-status status-refunded">Refunded</div>
                <div class="transaction-actions">
                    <button class="btn-icon" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-icon" title="Download Invoice">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
            
            <!-- Transaction Item 5 -->
            <div class="transaction-item" data-status="failed">
                <div class="transaction-info">
                    <div class="transaction-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="transaction-details">
                        <h3>App Design</h3>
                        <p>Project: Fitness App</p>
                    </div>
                </div>
                <div class="transaction-provider">
                    <div class="provider-avatar">ET</div>
                    <span>Emma Thompson</span>
                </div>
                <div class="transaction-amount">$1,500.00</div>
                <div class="transaction-date">May 18, 2023</div>
                <div class="transaction-status status-failed">Failed</div>
                <div class="transaction-actions">
                    <button class="btn-icon" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-icon" title="Retry Payment">
                        <i class="fas fa-redo"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Payment Methods Section -->
        <div class="payment-methods">
            <div class="section-header">
                <h2 class="section-title">Payment Methods</h2>
                <button class="btn btn-outline">
                    <i class="fas fa-plus"></i>
                    Add New Method
                </button>
            </div>
            
            <div class="methods-grid">
                <!-- Credit Card -->
                <div class="method-card default">
                    <div class="method-header">
                        <div class="method-type">
                            <div class="method-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <span>Credit Card</span>
                        </div>
                        <span class="default-badge">Default</span>
                    </div>
                    <div class="method-details">
                        <div class="method-number">**** **** **** 4512</div>
                        <div class="method-info">Expires: 05/2025</div>
                    </div>
                    <div class="method-actions">
                        <button class="btn btn-outline btn-sm">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-outline btn-sm">
                            <i class="fas fa-trash"></i>
                            Remove
                        </button>
                    </div>
                </div>
                
                <!-- PayPal -->
                <div class="method-card">
                    <div class="method-header">
                        <div class="method-type">
                            <div class="method-icon">
                                <i class="fab fa-paypal"></i>
                            </div>
                            <span>PayPal</span>
                        </div>
                    </div>
                    <div class="method-details">
                        <div class="method-number">john.client@example.com</div>
                        <div class="method-info">Connected</div>
                    </div>
                    <div class="method-actions">
                        <button class="btn btn-outline btn-sm">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-outline btn-sm">
                            <i class="fas fa-trash"></i>
                            Remove
                        </button>
                    </div>
                </div>
                
                <!-- Bank Transfer -->
                <div class="method-card">
                    <div class="method-header">
                        <div class="method-type">
                            <div class="method-icon">
                                <i class="fas fa-university"></i>
                            </div>
                            <span>Bank Transfer</span>
                        </div>
                    </div>
                    <div class="method-details">
                        <div class="method-number">**** 6789</div>
                        <div class="method-info">Chase Bank • Checking</div>
                    </div>
                    <div class="method-actions">
                        <button class="btn btn-outline btn-sm">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-outline btn-sm">
                            <i class="fas fa-trash"></i>
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="pagination">
            <button class="pagination-button">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="pagination-button active">1</button>
            <button class="pagination-button">2</button>
            <button class="pagination-button">3</button>
            <button class="pagination-button">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
    
    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-column">
                <h3>ServiceHub</h3>
                <p>Connecting clients with skilled professionals worldwide through our secure platform.</p>
            </div>
            <div class="footer-column">
                <h3>For Clients</h3>
                <ul>
                    <li><a href="#"><i class="fas fa-arrow-right"></i> How to Hire</a></li>
                    <li><a href="#"><i class="fas fa-arrow-right"></i> Payment Protection</a></li>
                    <li><a href="#"><i class="fas fa-arrow-right"></i> Client Resources</a></li>
                    <li><a href="#"><i class="fas fa-arrow-right"></i> Post a Job</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Support</h3>
                <ul>
                    <li><a href="#"><i class="fas fa-arrow-right"></i> Help Center</a></li>
                    <li><a href="#"><i class="fas fa-arrow-right"></i> Safety Tips</a></li>
                    <li><a href="#"><i class="fas fa-arrow-right"></i> Contact Us</a></li>
                    <li><a href="#"><i class="fas fa-arrow-right"></i> FAQs</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Legal</h3>
                <ul>
                    <li><a href="#"><i class="fas fa-arrow-right"></i> Terms of Service</a></li>
                    <li><a href="#"><i class="fas fa-arrow-right"></i> Privacy Policy</a></li>
                    <li><a href="#"><i class="fas fa-arrow-right"></i> Cookie Policy</a></li>
                    <li><a href="#"><i class="fas fa-arrow-right"></i> Dispute Resolution</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2023 ServiceHub. All rights reserved.</p>
        </div>
    </footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter tabs functionality
            const filterTabs = document.querySelectorAll('.filter-tab');
            const transactionItems = document.querySelectorAll('.transaction-item');
            
            filterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    
                    // Update active tab
                    filterTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Filter transaction items
                    transactionItems.forEach(item => {
                        if (filter === 'all') {
                            item.style.display = 'grid';
                        } else {
                            const status = item.getAttribute('data-status');
                            if (status === filter) {
                                item.style.display = 'grid';
                            } else {
                                item.style.display = 'none';
                            }
                        }
                    });
                });
            });
            
            // View details buttons
            const viewButtons = document.querySelectorAll('.btn-icon[title="View Details"]');
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const transactionName = this.closest('.transaction-item').querySelector('h3').textContent;
                    alert(`Viewing details for: ${transactionName}`);
                });
            });
            
            // Download invoice buttons
            const downloadButtons = document.querySelectorAll('.btn-icon[title="Download Invoice"]');
            downloadButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const transactionName = this.closest('.transaction-item').querySelector('h3').textContent;
                    alert(`Downloading invoice for: ${transactionName}`);
                });
            });
            
            // Cancel payment buttons
            const cancelButtons = document.querySelectorAll('.btn-icon[title="Cancel Payment"]');
            cancelButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const transactionName = this.closest('.transaction-item').querySelector('h3').textContent;
                    if (confirm(`Are you sure you want to cancel payment for ${transactionName}?`)) {
                        alert(`Payment for ${transactionName} has been cancelled.`);
                    }
                });
            });
            
            // Retry payment buttons
            const retryButtons = document.querySelectorAll('.btn-icon[title="Retry Payment"]');
            retryButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const transactionName = this.closest('.transaction-item').querySelector('h3').textContent;
                    alert(`Retrying payment for: ${transactionName}`);
                });
            });
            
            // Add payment method buttons
            const addMethodButtons = document.querySelectorAll('.btn-primary, .btn-outline');
            addMethodButtons.forEach(button => {
                if (button.textContent.includes('Add')) {
                    button.addEventListener('click', function() {
                        alert('Opening add payment method form');
                    });
                }
            });
            
            // Edit payment method buttons
            const editButtons = document.querySelectorAll('.method-actions .btn-outline:first-child');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const methodType = this.closest('.method-card').querySelector('.method-type span').textContent;
                    alert(`Editing ${methodType} payment method`);
                });
            });
            
            // Remove payment method buttons
            const removeButtons = document.querySelectorAll('.method-actions .btn-outline:last-child');
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const methodType = this.closest('.method-card').querySelector('.method-type span').textContent;
                    if (confirm(`Are you sure you want to remove your ${methodType} payment method?`)) {
                        alert(`${methodType} payment method has been removed.`);
                    }
                });
            });
            
            // Pagination buttons
            const paginationButtons = document.querySelectorAll('.pagination-button');
            paginationButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (!this.querySelector('.fa-chevron-left') && !this.querySelector('.fa-chevron-right')) {
                        paginationButtons.forEach(btn => btn.classList.remove('active'));
                        this.classList.add('active');
                    }
                    alert(`Loading page ${this.textContent}`);
                });
            });
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Jobs - ServiceHub</title>
    <?php include 'navbar.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        
        
        /* Breadcrumb */
        .breadcrumb {
            background-color: var(--secondary);
            padding: 1rem 5%;
            border-bottom: 1px solid #e9ecef;
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
        }
        
        .breadcrumb span {
            color: #666;
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
        }
        
        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
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
        }
        
        .filter-tab {
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
            white-space: nowrap;
        }
        
        .filter-tab.active {
            background-color: var(--primary);
            color: var(--light);
        }
        
        /* Search and Filter */
        .search-filter {
            background-color: var(--light);
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .search-box {
            flex: 1;
            min-width: 300px;
            position: relative;
        }
        
        .search-input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }
        
        .filter-select {
            padding: 0.8rem 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: var(--light);
            min-width: 150px;
        }
        
        /* Jobs Container */
        .jobs-container {
            background-color: var(--light);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        .jobs-header {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 0.5fr;
            padding: 1rem 1.5rem;
            background-color: #f8f9fa;
            font-weight: 600;
            color: #666;
            border-bottom: 1px solid #eee;
        }
        
        .job-item {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 0.5fr;
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s;
            align-items: center;
        }
        
        .job-item:hover {
            background-color: #fafafa;
        }
        
        .job-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .job-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background-color: #e3ecff;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        
        .job-details h3 {
            margin-bottom: 0.3rem;
            font-size: 1rem;
        }
        
        .job-details p {
            color: #666;
            font-size: 0.9rem;
        }
        
        .job-provider {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .provider-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #eee;
        }
        
        .job-budget {
            font-weight: 600;
            color: var(--dark);
        }
        
        .job-deadline {
            color: #666;
        }
        
        .job-status {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-active {
            background-color: #cce5ff;
            color: #004085;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .job-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .empty-state h3 {
            margin-bottom: 0.5rem;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-top: 2rem;
            padding: 1rem;
        }
        
        .pagination-button {
            padding: 0.6rem 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: var(--light);
            cursor: pointer;
            transition: all 0.3s;
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
        
        /* Buttons */
        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            font-size: 0.9rem;
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
            background-color: #016223;
        }
        
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }
        
        .btn-icon {
            width: 32px;
            height: 32px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: transparent;
            border: 1px solid #ddd;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-icon:hover {
            background-color: #f8f9fa;
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
        
        @media (max-width: 1024px) {
            .jobs-header, .job-item {
                grid-template-columns: 1fr 1fr 1fr;
                gap: 1rem;
            }
            
            .job-actions {
                grid-column: span 3;
                justify-content: center;
            }
        }
        
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .user-name {
                display: none;
            }
            
            .jobs-header {
                display: none;
            }
            
            .job-item {
                display: flex;
                flex-direction: column;
                gap: 1rem;
                padding: 1.5rem;
            }
            
            .job-info {
                width: 100%;
            }
            
            .job-details {
                flex: 1;
            }
            
            .search-filter {
                flex-direction: column;
            }
            
            .search-box {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>
    
    <!-- Breadcrumb -->
    <!--<div class="breadcrumb">
        <div class="breadcrumb-content">
            <a href="client-dashboard.html">Dashboard</a>
            <span>›</span>
            <span>My Jobs</span>
        </div>
    </div>-->
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">My Jobs</h1>
            <div class="header-actions">
                <button class="btn btn-primary">Post New Job</button>
            </div>
        </div>
        
        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <div class="filter-tab active" data-filter="all">All Jobs</div>
            <div class="filter-tab" data-filter="active">Active</div>
            <div class="filter-tab" data-filter="pending">Pending</div>
            <div class="filter-tab" data-filter="completed">Completed</div>
            <div class="filter-tab" data-filter="cancelled">Cancelled</div>
        </div>
        
        <!-- Search and Filter -->
        <div class="search-filter">
            <div class="search-box">
                <i class="search-icon">🔍</i>
                <input type="text" class="search-input" placeholder="Search jobs...">
            </div>
            <select class="filter-select">
                <option>Sort by: Newest</option>
                <option>Sort by: Oldest</option>
                <option>Sort by: Budget: High to Low</option>
                <option>Sort by: Budget: Low to High</option>
            </select>
            <select class="filter-select">
                <option>All Categories</option>
                <option>Web Development</option>
                <option>Design</option>
                <option>Writing</option>
                <option>Home Services</option>
            </select>
        </div>
        
        <!-- Jobs Container -->
        <div class="jobs-container">
            <!-- Jobs Header -->
            <div class="jobs-header">
                <div>Job Details</div>
                <div>Provider</div>
                <div>Budget</div>
                <div>Deadline</div>
                <div>Status</div>
            </div>
            
            <!-- Job Item 1 -->
            <div class="job-item" data-status="active">
                <div class="job-info">
                    <div class="job-icon">💻</div>
                    <div class="job-details">
                        <h3>E-commerce Website Development</h3>
                        <p>Web Development</p>
                    </div>
                </div>
                <div class="job-provider">
                    <div class="provider-avatar"></div>
                    <span>Sarah Johnson</span>
                </div>
                <div class="job-budget">$1,200</div>
                <div class="job-deadline">Jun 30, 2023</div>
                <div class="job-status status-active">Active</div>
                <div class="job-actions">
                    <button class="btn-icon" title="View Details">👁️</button>
                    <button class="btn-icon" title="Message">💬</button>
                </div>
            </div>
            
            <!-- Job Item 2 -->
            <div class="job-item" data-status="pending">
                <div class="job-info">
                    <div class="job-icon">🎨</div>
                    <div class="job-details">
                        <h3>Logo Design for Tech Startup</h3>
                        <p>Graphic Design</p>
                    </div>
                </div>
                <div class="job-provider">
                    <div class="provider-avatar"></div>
                    <span>Michael Chen</span>
                </div>
                <div class="job-budget">$350</div>
                <div class="job-deadline">Jun 15, 2023</div>
                <div class="job-status status-pending">Pending Review</div>
                <div class="job-actions">
                    <button class="btn-icon" title="View Details">👁️</button>
                    <button class="btn-icon" title="Message">💬</button>
                </div>
            </div>
            
            <!-- Job Item 3 -->
            <div class="job-item" data-status="completed">
                <div class="job-info">
                    <div class="job-icon">🔧</div>
                    <div class="job-details">
                        <h3>Home Plumbing Repair</h3>
                        <p>Home Services</p>
                    </div>
                </div>
                <div class="job-provider">
                    <div class="provider-avatar"></div>
                    <span>David Wilson</span>
                </div>
                <div class="job-budget">$850</div>
                <div class="job-deadline">May 30, 2023</div>
                <div class="job-status status-completed">Completed</div>
                <div class="job-actions">
                    <button class="btn-icon" title="View Details">👁️</button>
                    <button class="btn-icon" title="Leave Review">⭐</button>
                </div>
            </div>
            
            <!-- Job Item 4 -->
            <div class="job-item" data-status="active">
                <div class="job-info">
                    <div class="job-icon">📱</div>
                    <div class="job-details">
                        <h3>Mobile App UI/UX Design</h3>
                        <p>UI/UX Design</p>
                    </div>
                </div>
                <div class="job-provider">
                    <div class="provider-avatar"></div>
                    <span>Emma Thompson</span>
                </div>
                <div class="job-budget">$1,500</div>
                <div class="job-deadline">Jul 15, 2023</div>
                <div class="job-status status-active">In Progress</div>
                <div class="job-actions">
                    <button class="btn-icon" title="View Details">👁️</button>
                    <button class="btn-icon" title="Message">💬</button>
                </div>
            </div>
            
            <!-- Job Item 5 -->
            <div class="job-item" data-status="cancelled">
                <div class="job-info">
                    <div class="job-icon">📝</div>
                    <div class="job-details">
                        <h3>Blog Content Writing</h3>
                        <p>Content Writing</p>
                    </div>
                </div>
                <div class="job-provider">
                    <div class="provider-avatar"></div>
                    <span>Lisa Wong</span>
                </div>
                <div class="job-budget">$280</div>
                <div class="job-deadline">Cancelled</div>
                <div class="job-status status-cancelled">Cancelled</div>
                <div class="job-actions">
                    <button class="btn-icon" title="View Details">👁️</button>
                    <button class="btn-icon" title="Re-post">↻</button>
                </div>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="pagination">
            <button class="pagination-button">← Previous</button>
            <button class="pagination-button active">1</button>
            <button class="pagination-button">2</button>
            <button class="pagination-button">3</button>
            <button class="pagination-button">Next →</button>
        </div>
    </div>
    
    <!-- Footer -->
    <?php include 'footer.php'; ?>
    <footer>
        <div class="footer-content">
            <div class="footer-column">
                <h3>ServiceHub</h3>
                <p>Connecting clients with skilled professionals worldwide through our secure platform.</p>
            </div>
            <div class="footer-column">
                <h3>For Clients</h3>
                <ul>
                    <li><a href="#">How to Hire</a></li>
                    <li><a href="#">Payment Protection</a></li>
                    <li><a href="#">Client Resources</a></li>
                    <li><a href="#">Post a Job</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Support</h3>
                <ul>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Safety Tips</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">FAQs</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Legal</h3>
                <ul>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Cookie Policy</a></li>
                    <li><a href="#">Dispute Resolution</a></li>
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
            const jobItems = document.querySelectorAll('.job-item');
            
            filterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    
                    // Update active tab
                    filterTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Filter job items
                    jobItems.forEach(item => {
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
            
            // Search functionality
            const searchInput = document.querySelector('.search-input');
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                jobItems.forEach(item => {
                    const jobTitle = item.querySelector('h3').textContent.toLowerCase();
                    const jobCategory = item.querySelector('.job-details p').textContent.toLowerCase();
                    const providerName = item.querySelector('.job-provider span').textContent.toLowerCase();
                    
                    if (jobTitle.includes(searchTerm) || jobCategory.includes(searchTerm) || providerName.includes(searchTerm)) {
                        item.style.display = 'grid';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
            
            // Action buttons functionality
            const viewButtons = document.querySelectorAll('.btn-icon[title="View Details"]');
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const jobTitle = this.closest('.job-item').querySelector('h3').textContent;
                    alert(`Viewing details for: ${jobTitle}`);
                });
            });
            
            const messageButtons = document.querySelectorAll('.btn-icon[title="Message"]');
            messageButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const providerName = this.closest('.job-item').querySelector('.job-provider span').textContent;
                    alert(`Opening chat with ${providerName}`);
                });
            });
            
            const reviewButtons = document.querySelectorAll('.btn-icon[title="Leave Review"]');
            reviewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const jobTitle = this.closest('.job-item').querySelector('h3').textContent;
                    alert(`Opening review form for: ${jobTitle}`);
                });
            });
            
            const repostButtons = document.querySelectorAll('.btn-icon[title="Re-post"]');
            repostButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const jobTitle = this.closest('.job-item').querySelector('h3').textContent;
                    alert(`Re-posting job: ${jobTitle}`);
                });
            });
            
            // Post new job button
            const postJobBtn = document.querySelector('.header-actions .btn-primary');
            postJobBtn.addEventListener('click', function() {
                alert('Redirecting to post a new job page');
            });
            
            // Pagination buttons
            const paginationButtons = document.querySelectorAll('.pagination-button');
            paginationButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (!this.textContent.includes('Previous') && !this.textContent.includes('Next')) {
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
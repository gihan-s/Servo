
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
            box-shadow: 0 4px 12px rgba(0,133,0,0.25);
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

    <title>My Job Posts - ServiceHub</title>
</head>

<body>
    <section class="service-requests">
        <div class="header-requests">
            <div class="header-top" style="display:flex; justify-content: space-between; align-items: center;">
                <h1>My Job Posts</h1>
                
            </div>
            <div class="search-header">
                <div class="search-button">
                    <input type="text" placeholder="Search my job posts...">
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
                <div id="active-posts" class="buttons active">Active Posts</div>
                <div id="draft-posts" class="buttons">Draft Posts</div>
                <div id="expired-posts" class="buttons">Expired Posts</div>
            </div>
        </div>

        <div class="request-content">
            <!-- ACTIVE POSTS SECTION -->
            <div class="active-posts active requests-section">
                <div class="item-list">
                    <!-- Active Post 1 -->
                    <div class="search-item">
                        <div class="status-badge status-active">Active</div>

                        <div class="post-header">
                            <div class="post-meta">
                                <div class="post-date">
                                    <i class="fas fa-calendar"></i>
                                    <span>Posted 2 days ago</span>
                                </div>
                            </div>
                            <div class="post-actions">
                                <button class="action-btn btn-edit">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </button>
                                <button class="action-btn btn-view">
                                    <i class="fas fa-eye"></i>
                                    View
                                </button>
                                <button class="action-btn btn-delete">
                                    <i class="fas fa-trash"></i>
                                    Delete
                                </button>
                            </div>
                        </div>

                        <h3 class="post-title">🚀 Full-Stack E-commerce Platform Development</h3>

                        <div class="post-description">
                            We need an experienced full-stack developer to build a modern e-commerce platform with React
                            frontend and Node.js backend. The project includes user authentication, payment integration,
                            admin dashboard, and inventory management. Looking for someone who can deliver high-quality
                            code with proper documentation.
                        </div>

                        <div class="post-skills">
                            <span class="skills-label">Required Skills:</span>
                            <div class="skills-tags">
                                <span class="skill-tag">React.js</span>
                                <span class="skill-tag">Node.js</span>
                                <span class="skill-tag">MongoDB</span>
                                <span class="skill-tag">Express.js</span>
                                <span class="skill-tag">Payment APIs</span>
                                <span class="skill-tag">AWS</span>
                            </div>
                        </div>

                        <div class="post-footer">
                            <div class="post-details">
                                <div class="detail-item">
                                    <span class="detail-label">Budget</span>
                                    <span class="detail-value budget-amount">$5,000 - $8,000</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Proposals Received</span>
                                    <span class="detail-value proposals-count">23</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Level</span>
                                    <span class="detail-value project-level">Expert</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Duration</span>
                                    <span class="detail-value project-duration">2-3 months</span>
                                </div>
                            </div>
                        </div>

                        <div class="engagement-stats">
                            <div class="stat-item">
                                <i class="fas fa-eye"></i>
                                <span>156 views</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-clock"></i>
                                <span>5 days left</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-user-check"></i>
                                <span>3 shortlisted</span>
                            </div>
                        </div>
                    </div>

                    <!-- Active Post 2 -->
                    <div class="search-item">
                        <div class="status-badge status-active">Active</div>

                        <div class="post-header">
                            <div class="post-meta">
                                <div class="post-date">
                                    <i class="fas fa-calendar"></i>
                                    <span>Posted 1 week ago</span>
                                </div>
                            </div>
                            <div class="post-actions">
                                <button class="action-btn btn-edit">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </button>
                                <button class="action-btn btn-view">
                                    <i class="fas fa-eye"></i>
                                    View
                                </button>
                                <button class="action-btn btn-delete">
                                    <i class="fas fa-trash"></i>
                                    Delete
                                </button>
                            </div>
                        </div>

                        <h3 class="post-title">📱 Mobile App UI/UX Design for iOS & Android</h3>

                        <div class="post-description">
                            Seeking a talented UI/UX designer to create intuitive and visually appealing designs for our
                            mobile application. The app focuses on fitness tracking and requires modern, clean
                            interfaces with excellent user experience. Portfolio with mobile design experience required.
                        </div>

                        <div class="post-skills">
                            <span class="skills-label">Required Skills:</span>
                            <div class="skills-tags">
                                <span class="skill-tag">UI/UX Design</span>
                                <span class="skill-tag">Figma</span>
                                <span class="skill-tag">Adobe XD</span>
                                <span class="skill-tag">Prototyping</span>
                                <span class="skill-tag">Mobile Design</span>
                                <span class="skill-tag">User Research</span>
                            </div>
                        </div>

                        <div class="post-footer">
                            <div class="post-details">
                                <div class="detail-item">
                                    <span class="detail-label">Budget</span>
                                    <span class="detail-value budget-amount">$40 - $60/hr</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Proposals Received</span>
                                    <span class="detail-value proposals-count">47</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Level</span>
                                    <span class="detail-value project-level">Intermediate</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Duration</span>
                                    <span class="detail-value project-duration">1-2 months</span>
                                </div>
                            </div>
                        </div>

                        <div class="engagement-stats">
                            <div class="stat-item">
                                <i class="fas fa-eye"></i>
                                <span>287 views</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-clock"></i>
                                <span>12 days left</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-user-check"></i>
                                <span>8 shortlisted</span>
                            </div>
                        </div>
                    </div>
                    <!-- Pagination Active -->
                    <div class="pagination" aria-label="Pagination Active Posts">
                        <button class="page-btn prev" disabled><i class="fa-regular fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn next"><i class="fa-regular fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>

            <!-- DRAFT POSTS SECTION -->
            <div class="draft-posts requests-section draft-section" style="display: none;">
                <div class="item-list">
                    <!-- Draft Post 1 -->
                    <div class="search-item">
                        <div class="status-badge status-draft">Draft</div>

                        <div class="post-header">
                            <div class="post-meta">
                                <div class="post-date">
                                    <i class="fas fa-calendar"></i>
                                    <span>Created 3 days ago</span>
                                </div>
                            </div>
                            <div class="post-actions">
                                <button class="action-btn btn-edit">
                                    <i class="fas fa-edit"></i>
                                    Continue
                                </button>
                                <button class="action-btn btn-view">
                                    <i class="fas fa-rocket"></i>
                                    Publish
                                </button>
                                <button class="action-btn btn-delete">
                                    <i class="fas fa-trash"></i>
                                    Delete
                                </button>
                            </div>
                        </div>

                        <h3 class="post-title">🎯 Digital Marketing Campaign & SEO Optimization</h3>

                        <div class="post-description">
                            Looking for a digital marketing expert to boost our online presence through comprehensive
                            SEO strategies, content marketing, and social media campaigns. Need someone experienced with
                            Google Analytics, keyword research, and conversion optimization...
                        </div>

                        <div class="post-skills">
                            <span class="skills-label">Required Skills:</span>
                            <div class="skills-tags">
                                <span class="skill-tag">SEO</span>
                                <span class="skill-tag">Google Analytics</span>
                                <span class="skill-tag">Content Marketing</span>
                                <span class="skill-tag">Social Media</span>
                            </div>
                        </div>

                        <div class="post-footer">
                            <div class="post-details">
                                <div class="detail-item">
                                    <span class="detail-label">Budget</span>
                                    <span class="detail-value budget-amount">$2,500 - $4,000</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Completion</span>
                                    <span class="detail-value" style="color: #d97706;">75%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Draft Post 2 -->
                    <div class="search-item">
                        <div class="status-badge status-draft">Draft</div>

                        <div class="post-header">
                            <div class="post-meta">
                                <div class="post-date">
                                    <i class="fas fa-calendar"></i>
                                    <span>Created 1 week ago</span>
                                </div>
                            </div>
                            <div class="post-actions">
                                <button class="action-btn btn-edit">
                                    <i class="fas fa-edit"></i>
                                    Continue
                                </button>
                                <button class="action-btn btn-view">
                                    <i class="fas fa-rocket"></i>
                                    Publish
                                </button>
                                <button class="action-btn btn-delete">
                                    <i class="fas fa-trash"></i>
                                    Delete
                                </button>
                            </div>
                        </div>

                        <h3 class="post-title">🏗️ WordPress Website Development & Customization</h3>

                        <div class="post-description">
                            Need a skilled WordPress developer to create a custom business website with advanced
                            functionality. The site should include e-commerce capabilities, custom plugins, and
                            responsive design...
                        </div>

                        <div class="post-skills">
                            <span class="skills-label">Required Skills:</span>
                            <div class="skills-tags">
                                <span class="skill-tag">WordPress</span>
                                <span class="skill-tag">PHP</span>
                                <span class="skill-tag">MySQL</span>
                                <span class="skill-tag">WooCommerce</span>
                            </div>
                        </div>

                        <div class="post-footer">
                            <div class="post-details">
                                <div class="detail-item">
                                    <span class="detail-label">Budget</span>
                                    <span class="detail-value budget-amount">$3,000 - $5,000</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Completion</span>
                                    <span class="detail-value" style="color: #d97706;">45%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Pagination Draft -->
                    <div class="pagination" aria-label="Pagination Draft Posts">
                        <button class="page-btn prev" disabled><i class="fa-regular fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn next"><i class="fa-regular fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>

            <!-- EXPIRED POSTS SECTION -->
            <div class="expired-posts requests-section expired-section" style="display: none;">
                <div class="item-list">
                    <!-- Expired Post 1 -->
                    <div class="search-item">
                        <div class="status-badge status-expired">Expired</div>

                        <div class="post-header">
                            <div class="post-meta">
                                <div class="post-date">
                                    <i class="fas fa-calendar"></i>
                                    <span>Expired 5 days ago</span>
                                </div>
                            </div>
                            <div class="post-actions">
                                <button class="action-btn btn-view">
                                    <i class="fas fa-eye"></i>
                                    View
                                </button>
                                <button class="action-btn btn-edit">
                                    <i class="fas fa-redo"></i>
                                    Repost
                                </button>
                                <button class="action-btn btn-delete">
                                    <i class="fas fa-trash"></i>
                                    Delete
                                </button>
                            </div>
                        </div>

                        <h3 class="post-title">📊 Data Analysis & Business Intelligence Dashboard</h3>

                        <div class="post-description">
                            We needed a data analyst to create comprehensive business intelligence dashboards using
                            Python and Tableau. The project involved analyzing sales data, customer behavior, and market
                            trends to provide actionable insights for strategic decision-making.
                        </div>

                        <div class="post-skills">
                            <span class="skills-label">Required Skills:</span>
                            <div class="skills-tags">
                                <span class="skill-tag">Python</span>
                                <span class="skill-tag">Tableau</span>
                                <span class="skill-tag">SQL</span>
                                <span class="skill-tag">Data Analysis</span>
                                <span class="skill-tag">Power BI</span>
                            </div>
                        </div>

                        <div class="post-footer">
                            <div class="post-details">
                                <div class="detail-item">
                                    <span class="detail-label">Budget</span>
                                    <span class="detail-value budget-amount">$4,000 - $6,000</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Final Proposals</span>
                                    <span class="detail-value proposals-count">31</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Duration</span>
                                    <span class="detail-value project-duration">1-2 months</span>
                                </div>
                            </div>
                        </div>

                        <div class="engagement-stats">
                            <div class="stat-item">
                                <i class="fas fa-eye"></i>
                                <span>198 views</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-clock"></i>
                                <span>Expired</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-user-check"></i>
                                <span>5 shortlisted</span>
                            </div>
                        </div>
                    </div>

                    <!-- Expired Post 2 -->
                    <div class="search-item">
                        <div class="status-badge status-expired">Expired</div>

                        <div class="post-header">
                            <div class="post-meta">
                                <div class="post-date">
                                    <i class="fas fa-calendar"></i>
                                    <span>Expired 2 weeks ago</span>
                                </div>
                            </div>
                            <div class="post-actions">
                                <button class="action-btn btn-view">
                                    <i class="fas fa-eye"></i>
                                    View
                                </button>
                                <button class="action-btn btn-edit">
                                    <i class="fas fa-redo"></i>
                                    Repost
                                </button>
                                <button class="action-btn btn-delete">
                                    <i class="fas fa-trash"></i>
                                    Delete
                                </button>
                            </div>
                        </div>

                        <h3 class="post-title">🎨 Logo Design & Brand Identity Package</h3>

                        <div class="post-description">
                            We were looking for a creative graphic designer to develop a complete brand identity package
                            including logo design, color palette, typography, and brand guidelines. The project was for
                            a tech startup in the sustainability sector requiring modern, clean designs.
                        </div>

                        <div class="post-skills">
                            <span class="skills-label">Required Skills:</span>
                            <div class="skills-tags">
                                <span class="skill-tag">Logo Design</span>
                                <span class="skill-tag">Brand Identity</span>
                                <span class="skill-tag">Adobe Illustrator</span>
                                <span class="skill-tag">Typography</span>
                                <span class="skill-tag">Color Theory</span>
                            </div>
                        </div>

                        <div class="post-footer">
                            <div class="post-details">
                                <div class="detail-item">
                                    <span class="detail-label">Budget</span>
                                    <span class="detail-value budget-amount">$1,200 - $2,000</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Final Proposals</span>
                                    <span class="detail-value proposals-count">52</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Duration</span>
                                    <span class="detail-value project-duration">2-3 weeks</span>
                                </div>
                            </div>
                        </div>

                        <div class="engagement-stats">
                            <div class="stat-item">
                                <i class="fas fa-eye"></i>
                                <span>343 views</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-clock"></i>
                                <span>Expired</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-user-check"></i>
                                <span>12 shortlisted</span>
                            </div>
                        </div>
                    </div>
                    <!-- Pagination Expired -->
                    <div class="pagination" aria-label="Pagination Expired Posts">
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
                if (this.id === 'active-posts') {
                    sectionClass = 'active-posts';
                } else if (this.id === 'draft-posts') {
                    sectionClass = 'draft-posts';
                } else if (this.id === 'expired-posts') {
                    sectionClass = 'expired-posts';
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
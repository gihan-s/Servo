<?php include '../config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
    
    <style>
        /* Complete Professional Freelancing Platform Card Design */
        .service-requests {
            background: #f8fafc;
            min-height: 100vh;
            padding: 20px;
        }
        
        .header-requests h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .search-header {
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .search-button {
            display: flex;
            flex: 1;
            min-width: 300px;
        }
        
        .search-button input {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-right: none;
            border-radius: 12px 0 0 12px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s ease;
        }
        
        .search-button input:focus {
            border-color: #10b981;
        }
        
        .search-button button {
            padding: 12px 20px;
            background: #10b981;
            color: white;
            border: 2px solid #10b981;
            border-radius: 0 12px 12px 0;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .search-button button:hover {
            background: #059669;
            border-color: #059669;
        }
        
        .filter {
            padding: 12px 20px;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .filter:hover {
            border-color: #10b981;
            color: #10b981;
        }
        
        .container-changer {
            display: flex;
            gap: 8px;
            margin-bottom: 24px;
            background: white;
            padding: 8px;
            border-radius: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
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
            background: #10b981;
            color: white;
        }
        
        .buttons:not(.active):hover {
            background: #f3f4f6;
            color: #10b981;
        }
        
        .item-list {
            display: grid;
            gap: 24px;
            max-width: 1200px;
            margin: 0 auto;
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
        
        .search-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #3b82f6);
        }
        
        .search-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: #10b981;
        }
        
        /* Post Header Section */
        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .client-section {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .client-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e5e7eb;
            transition: border-color 0.3s ease;
        }
        
        .search-item:hover .client-avatar {
            border-color: #10b981;
        }
        
        .client-details h4 {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 6px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .verified-icon {
            background: #10b981;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        
        .client-stats {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 4px;
        }
        
        .rating-display {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #f59e0b;
        }
        
        .rating-number {
            color: #374151;
            font-weight: 600;
            font-size: 14px;
        }
        
        .review-count {
            color: #6b7280;
            font-size: 14px;
        }
        
        .client-location {
            display: flex;
            align-items: center;
            gap: 4px;
            color: #6b7280;
            font-size: 13px;
        }
        
        .post-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .post-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }
        
        .badge-urgent {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .badge-featured {
            background: #fffbeb;
            color: #d97706;
            border: 1px solid #fed7aa;
        }
        
        .badge-fixed {
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }
        
        .badge-hourly {
            background: #f5f3ff;
            color: #7c3aed;
            border: 1px solid #c4b5fd;
        }
        
        /* Post Content Section */
        .post-title {
            font-size: 22px;
            font-weight: 700;
            color: #10b981;
            margin-bottom: 16px;
            line-height: 1.4;
            cursor: pointer;
            transition: color 0.2s ease;
        }
        
        .post-title:hover {
            color: #059669;
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
            cursor: pointer;
        }
        
        .skill-tag:hover {
            background: #10b981;
            color: white;
            border-color: #10b981;
            transform: translateY(-1px);
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
            color: #10b981;
            font-size: 18px;
        }
        
        .proposals-count {
            color: #3b82f6;
        }
        
        .project-level {
            background: #ecfdf5;
            color: #065f46;
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
        
        .post-actions {
            display: flex;
            gap: 12px;
        }
        
        .action-btn {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: none;
        }
        
        .btn-save {
            background: white;
            color: #10b981;
            border: 2px solid #10b981;
        }
        
        .btn-save:hover {
            background: #10b981;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        
        .btn-proposal {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: 2px solid #10b981;
        }
        
        .btn-proposal:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }
        
        .post-timestamp {
            position: absolute;
            top: 16px;
            right: 16px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            color: #6b7280;
            border: 1px solid #e5e7eb;
        }
        
        /* Additional Post Elements */
        .post-priority {
            position: absolute;
            top: 50px;
            right: 16px;
            background: #fee2e2;
            color: #dc2626;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
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
            
            .post-actions {
                width: 100%;
                justify-content: center;
            }
            
            .search-header {
                flex-direction: column;
            }
            
            .search-button {
                min-width: auto;
            }
        }
    </style>

    <title>Browse Job Posts - ServiceHub</title>
</head>

<body>
    <section class="service-requests">
        <div class="header-requests">
            <h1><i class="fas fa-briefcase"></i> Available Projects</h1>
            <div class="search-header">
                <div class="search-button">
                    <input type="text" placeholder="Search for job posts...">
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
                <div id="pending-requests" class="buttons active">All Posts</div>
                <div id="payment-pending-requests" class="buttons">Fixed Price</div>
                <div id="declined-requests" class="buttons">Hourly</div>
            </div>
        </div>
        
        <div class="request-content">
            <div class="pending-requests active requests-section">
                <div class="item-list">
                    <!-- Professional Post Card 1 -->
                    <div class="search-item">
                        <div class="post-timestamp">Posted 2 hours ago</div>
                        <div class="post-priority">Urgent</div>
                        
                        <div class="post-header">
                            <div class="client-section">
                                <img src="<?= BASE_URL ?>/assets/img/avatar1.jpg" alt="Client" class="client-avatar" onerror="this.src='https://via.placeholder.com/60x60/10b981/ffffff?text=TC'">
                                <div class="client-details">
                                    <h4>
                                        TechCorp Solutions
                                        <span class="verified-icon"><i class="fas fa-check"></i></span>
                                    </h4>
                                    <div class="client-stats">
                                        <div class="rating-display">
                                            <i class="fas fa-star"></i>
                                            <span class="rating-number">4.9</span>
                                            <span class="review-count">(127 reviews)</span>
                                        </div>
                                    </div>
                                    <div class="client-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>United States</span>
                                    </div>
                                </div>
                            </div>
                            <div class="post-badges">
                                <span class="post-badge badge-urgent">Urgent</span>
                                <span class="post-badge badge-fixed">Fixed Price</span>
                            </div>
                        </div>

                        <h3 class="post-title">🚀 Full-Stack E-commerce Platform Development</h3>
                        
                        <div class="post-description">
                            We need an experienced full-stack developer to build a modern e-commerce platform with React frontend and Node.js backend. The project includes user authentication, payment integration, admin dashboard, and inventory management. Looking for someone who can deliver high-quality code with proper documentation.
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
                                    <span class="detail-label">Proposals</span>
                                    <span class="detail-value proposals-count">12</span>
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
                            <div class="post-actions">
                                <button class="action-btn btn-save">
                                    <i class="far fa-heart"></i>
                                    Save Job
                                </button>
                                <button class="action-btn btn-proposal">
                                    <i class="fas fa-paper-plane"></i>
                                    Send Proposal
                                </button>
                            </div>
                        </div>

                        <div class="engagement-stats">
                            <div class="stat-item">
                                <i class="fas fa-eye"></i>
                                <span>89 views</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-clock"></i>
                                <span>5 days left to bid</span>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Post Card 2 -->
                    <div class="search-item">
                        <div class="post-timestamp">Posted 5 hours ago</div>
                        
                        <div class="post-header">
                            <div class="client-section">
                                <img src="<?= BASE_URL ?>/assets/img/avatar2.jpg" alt="Client" class="client-avatar" onerror="this.src='https://via.placeholder.com/60x60/3b82f6/ffffff?text=DM'">
                                <div class="client-details">
                                    <h4>
                                        Digital Marketing Pro
                                        <span class="verified-icon"><i class="fas fa-check"></i></span>
                                    </h4>
                                    <div class="client-stats">
                                        <div class="rating-display">
                                            <i class="fas fa-star"></i>
                                            <span class="rating-number">4.7</span>
                                            <span class="review-count">(89 reviews)</span>
                                        </div>
                                    </div>
                                    <div class="client-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>Canada</span>
                                    </div>
                                </div>
                            </div>
                            <div class="post-badges">
                                <span class="post-badge badge-hourly">Hourly</span>
                                <span class="post-badge badge-featured">Featured</span>
                            </div>
                        </div>

                        <h3 class="post-title">📱 Mobile App UI/UX Design for iOS & Android</h3>
                        
                        <div class="post-description">
                            Seeking a talented UI/UX designer to create intuitive and visually appealing designs for our mobile application. The app focuses on fitness tracking and requires modern, clean interfaces with excellent user experience. Portfolio with mobile design experience required.
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
                                    <span class="detail-label">Proposals</span>
                                    <span class="detail-value proposals-count">27</span>
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
                            <div class="post-actions">
                                <button class="action-btn btn-save">
                                    <i class="far fa-heart"></i>
                                    Save Job
                                </button>
                                <button class="action-btn btn-proposal">
                                    <i class="fas fa-paper-plane"></i>
                                    Send Proposal
                                </button>
                            </div>
                        </div>

                        <div class="engagement-stats">
                            <div class="stat-item">
                                <i class="fas fa-eye"></i>
                                <span>156 views</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-clock"></i>
                                <span>7 days left to bid</span>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Post Card 3 -->
                    <div class="search-item">
                        <div class="post-timestamp">Posted 1 day ago</div>
                        
                        <div class="post-header">
                            <div class="client-section">
                                <img src="<?= BASE_URL ?>/assets/img/avatar3.jpg" alt="Client" class="client-avatar" onerror="this.src='https://via.placeholder.com/60x60/f59e0b/ffffff?text=SM'">
                                <div class="client-details">
                                    <h4>
                                        Startup Maven
                                        <span class="verified-icon"><i class="fas fa-check"></i></span>
                                    </h4>
                                    <div class="client-stats">
                                        <div class="rating-display">
                                            <i class="fas fa-star"></i>
                                            <span class="rating-number">4.8</span>
                                            <span class="review-count">(203 reviews)</span>
                                        </div>
                                    </div>
                                    <div class="client-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>United Kingdom</span>
                                    </div>
                                </div>
                            </div>
                            <div class="post-badges">
                                <span class="post-badge badge-fixed">Fixed Price</span>
                            </div>
                        </div>

                        <h3 class="post-title">🎯 Digital Marketing Campaign & SEO Optimization</h3>
                        
                        <div class="post-description">
                            Looking for a digital marketing expert to boost our online presence through comprehensive SEO strategies, content marketing, and social media campaigns. Need someone experienced with Google Analytics, keyword research, and conversion optimization to drive measurable results.
                        </div>

                        <div class="post-skills">
                            <span class="skills-label">Required Skills:</span>
                            <div class="skills-tags">
                                <span class="skill-tag">SEO</span>
                                <span class="skill-tag">Google Analytics</span>
                                <span class="skill-tag">Content Marketing</span>
                                <span class="skill-tag">Social Media</span>
                                <span class="skill-tag">PPC Advertising</span>
                                <span class="skill-tag">Conversion Optimization</span>
                            </div>
                        </div>

                        <div class="post-footer">
                            <div class="post-details">
                                <div class="detail-item">
                                    <span class="detail-label">Budget</span>
                                    <span class="detail-value budget-amount">$2,500 - $4,000</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Proposals</span>
                                    <span class="detail-value proposals-count">8</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Level</span>
                                    <span class="detail-value project-level">Expert</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Duration</span>
                                    <span class="detail-value project-duration">1 month</span>
                                </div>
                            </div>
                            <div class="post-actions">
                                <button class="action-btn btn-save">
                                    <i class="far fa-heart"></i>
                                    Save Job
                                </button>
                                <button class="action-btn btn-proposal">
                                    <i class="fas fa-paper-plane"></i>
                                    Send Proposal
                                </button>
                            </div>
                        </div>

                        <div class="engagement-stats">
                            <div class="stat-item">
                                <i class="fas fa-eye"></i>
                                <span>67 views</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-clock"></i>
                                <span>10 days left to bid</span>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Post Card 4 -->
                    <div class="search-item">
                        <div class="post-timestamp">Posted 3 days ago</div>
                        
                        <div class="post-header">
                            <div class="client-section">
                                <img src="<?= BASE_URL ?>/assets/img/avatar4.jpg" alt="Client" class="client-avatar" onerror="this.src='https://via.placeholder.com/60x60/dc2626/ffffff?text=DS'">
                                <div class="client-details">
                                    <h4>
                                        Data Science Hub
                                        <span class="verified-icon"><i class="fas fa-check"></i></span>
                                    </h4>
                                    <div class="client-stats">
                                        <div class="rating-display">
                                            <i class="fas fa-star"></i>
                                            <span class="rating-number">4.9</span>
                                            <span class="review-count">(156 reviews)</span>
                                        </div>
                                    </div>
                                    <div class="client-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>Australia</span>
                                    </div>
                                </div>
                            </div>
                            <div class="post-badges">
                                <span class="post-badge badge-hourly">Hourly</span>
                                <span class="post-badge badge-urgent">Urgent</span>
                            </div>
                        </div>

                        <h3 class="post-title">📊 Machine Learning Model for Predictive Analytics</h3>
                        
                        <div class="post-description">
                            We need a data scientist to develop and implement machine learning models for predictive analytics. The project involves analyzing large datasets, feature engineering, model training, and deployment. Experience with Python, TensorFlow, and cloud platforms is essential.
                        </div>

                        <div class="post-skills">
                            <span class="skills-label">Required Skills:</span>
                            <div class="skills-tags">
                                <span class="skill-tag">Machine Learning</span>
                                <span class="skill-tag">Python</span>
                                <span class="skill-tag">TensorFlow</span>
                                <span class="skill-tag">Data Analysis</span>
                                <span class="skill-tag">AWS/GCP</span>
                                <span class="skill-tag">SQL</span>
                            </div>
                        </div>

                        <div class="post-footer">
                            <div class="post-details">
                                <div class="detail-item">
                                    <span class="detail-label">Budget</span>
                                    <span class="detail-value budget-amount">$75 - $100/hr</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Proposals</span>
                                    <span class="detail-value proposals-count">19</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Level</span>
                                    <span class="detail-value project-level">Expert</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Duration</span>
                                    <span class="detail-value project-duration">3-6 months</span>
                                </div>
                            </div>
                            <div class="post-actions">
                                <button class="action-btn btn-save">
                                    <i class="far fa-heart"></i>
                                    Save Job
                                </button>
                                <button class="action-btn btn-proposal">
                                    <i class="fas fa-paper-plane"></i>
                                    Send Proposal
                                </button>
                            </div>
                        </div>

                        <div class="engagement-stats">
                            <div class="stat-item">
                                <i class="fas fa-eye"></i>
                                <span>234 views</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-clock"></i>
                                <span>4 days left to bid</span>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Post Card 5 -->
                    <div class="search-item">
                        <div class="post-timestamp">Posted 6 days ago</div>
                        
                        <div class="post-header">
                            <div class="client-section">
                                <img src="<?= BASE_URL ?>/assets/img/avatar5.jpg" alt="Client" class="client-avatar" onerror="this.src='https://via.placeholder.com/60x60/8b5cf6/ffffff?text=CB'">
                                <div class="client-details">
                                    <h4>
                                        Creative Brand Studio
                                        <span class="verified-icon"><i class="fas fa-check"></i></span>
                                    </h4>
                                    <div class="client-stats">
                                        <div class="rating-display">
                                            <i class="fas fa-star"></i>
                                            <span class="rating-number">4.6</span>
                                            <span class="review-count">(74 reviews)</span>
                                        </div>
                                    </div>
                                    <div class="client-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>Germany</span>
                                    </div>
                                </div>
                            </div>
                            <div class="post-badges">
                                <span class="post-badge badge-fixed">Fixed Price</span>
                                <span class="post-badge badge-featured">Featured</span>
                            </div>
                        </div>

                        <h3 class="post-title">🎨 Brand Identity & Logo Design Package</h3>
                        
                        <div class="post-description">
                            Seeking a creative graphic designer to develop a complete brand identity package including logo design, color palette, typography, and brand guidelines. The project is for a tech startup in the sustainability sector. We need modern, clean designs that reflect innovation and environmental consciousness.
                        </div>

                        <div class="post-skills">
                            <span class="skills-label">Required Skills:</span>
                            <div class="skills-tags">
                                <span class="skill-tag">Logo Design</span>
                                <span class="skill-tag">Brand Identity</span>
                                <span class="skill-tag">Adobe Illustrator</span>
                                <span class="skill-tag">Typography</span>
                                <span class="skill-tag">Color Theory</span>
                                <span class="skill-tag">Brand Guidelines</span>
                            </div>
                        </div>

                        <div class="post-footer">
                            <div class="post-details">
                                <div class="detail-item">
                                    <span class="detail-label">Budget</span>
                                    <span class="detail-value budget-amount">$1,200 - $2,000</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Proposals</span>
                                    <span class="detail-value proposals-count">34</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Level</span>
                                    <span class="detail-value project-level">Intermediate</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Duration</span>
                                    <span class="detail-value project-duration">2-3 weeks</span>
                                </div>
                            </div>
                            <div class="post-actions">
                                <button class="action-btn btn-save">
                                    <i class="far fa-heart"></i>
                                    Save Job
                                </button>
                                <button class="action-btn btn-proposal">
                                    <i class="fas fa-paper-plane"></i>
                                    Send Proposal
                                </button>
                            </div>
                        </div>

                        <div class="engagement-stats">
                            <div class="stat-item">
                                <i class="fas fa-eye"></i>
                                <span>187 views</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-clock"></i>
                                <span>8 days left to bid</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Popup (Hidden by default) -->
    <div class="pop-up-section filter-pop-up deactive">
        <div class="pop-up deactive">
            <div class="pop-up-header">
                <div class="pop-up-title">Filter Jobs</div>
                <i class="fa-light fa-xmark" id="filter-pop-up"></i>
            </div>
            <hr>
            <div class="pop-up-content">
                <div class="filter-section">
                    <h4>Job Type</h4>
                    <label><input type="checkbox"> Fixed Price</label>
                    <label><input type="checkbox"> Hourly Rate</label>
                </div>
                <div class="filter-section">
                    <h4>Experience Level</h4>
                    <label><input type="checkbox"> Entry Level</label>
                    <label><input type="checkbox"> Intermediate</label>
                    <label><input type="checkbox"> Expert</label>
                </div>
                <div class="filter-section">
                    <h4>Budget Range</h4>
                    <label><input type="checkbox"> Under $1,000</label>
                    <label><input type="checkbox"> $1,000 - $5,000</label>
                    <label><input type="checkbox"> $5,000+</label>
                </div>
            </div>
            <hr>
            <div class="button-apply">
                <button>Apply filters</button>
            </div>
        </div>
    </div>

</body>
<script src="<?= BASE_URL ?>/assets/js/cardList.js" defer></script>

</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'navbar.php'; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Providers - ServiceHub</title>
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
            padding: 0.8rem 1rem 0.8rem 3rem;
            border: 1px solid var(--light-gray);
            border-radius: 5px;
            font-size: 0.9rem;
        }
        
        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }
        
        .filter-select {
            padding: 0.8rem 1rem;
            border: 1px solid var(--light-gray);
            border-radius: 5px;
            background-color: var(--light);
            min-width: 150px;
            color: var(--dark);
        }
        
        /* Providers Grid */
        .providers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .provider-card {
            background-color: var(--light);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .provider-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .provider-header {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .provider-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4a6bff 0%, #6a4bff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .provider-info {
            flex: 1;
        }
        
        .provider-info h3 {
            margin-bottom: 0.3rem;
            font-size: 1.2rem;
        }
        
        .provider-info p {
            color: var(--gray);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .provider-rating {
            color: var(--warning);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        
        .provider-meta {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: var(--gray);
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        
        .provider-content {
            padding: 1.5rem;
        }
        
        .provider-skills {
            margin-bottom: 1.5rem;
        }
        
        .skills-title {
            font-weight: 600;
            margin-bottom: 0.8rem;
            color: var(--dark);
        }
        
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .skill-tag {
            background-color: #e9f0ff;
            color: var(--primary);
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .provider-footer {
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--light-gray);
        }
        
        .provider-price {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--dark);
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
            .providers-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
        }
        
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .user-name {
                display: none;
            }
            
            .search-filter {
                flex-direction: column;
            }
            
            .search-box {
                min-width: 100%;
            }
            
            .provider-header {
                flex-direction: column;
                text-align: center;
            }
            
            .provider-meta {
                justify-content: center;
            }
            
            .provider-footer {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Breadcrumb -->
    <!--<div class="breadcrumb">
        <div class="breadcrumb-content">
            <a href="client-dashboard.html">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <span>›</span>
            <span>Providers</span>
        </div>
    </div>-->
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-users"></i>
                Service Providers
            </h1>
            <div class="header-actions">
                <button class="btn btn-outline">
                    <i class="fas fa-sliders-h"></i>
                    Filters
                </button>
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Post a Job
                </button>
            </div>
        </div>
        
        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <div class="filter-tab active" data-filter="all">
                <i class="fas fa-layer-group"></i>
                All Providers
            </div>
            <div class="filter-tab" data-filter="featured">
                <i class="fas fa-star"></i>
                Featured
            </div>
            <div class="filter-tab" data-filter="top-rated">
                <i class="fas fa-trophy"></i>
                Top Rated
            </div>
            <div class="filter-tab" data-filter="online">
                <i class="fas fa-circle"></i>
                Online Now
            </div>
            <div class="filter-tab" data-filter="saved">
                <i class="fas fa-bookmark"></i>
                Saved Providers
            </div>
        </div>
        
        <!-- Search and Filter -->
        <div class="search-filter">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Search providers by name, skills...">
            </div>
            <select class="filter-select">
                <option>All Categories</option>
                <option>Web Development</option>
                <option>Graphic Design</option>
                <option>Content Writing</option>
                <option>Mobile Development</option>
                <option>Home Services</option>
            </select>
            <select class="filter-select">
                <option>Sort by: Recommended</option>
                <option>Sort by: Highest Rated</option>
                <option>Sort by: Most Reviews</option>
                <option>Sort by: Price: Low to High</option>
                <option>Sort by: Price: High to Low</option>
            </select>
        </div>
        
        <!-- Providers Grid -->
        <div class="providers-grid">
            <!-- Provider Card 1 -->
            <div class="provider-card">
                <div class="provider-header">
                    <div class="provider-avatar">SJ</div>
                    <div class="provider-info">
                        <h3>Sarah Johnson</h3>
                        <p>Web Developer & Designer</p>
                        <div class="provider-rating">
                            <i class="fas fa-star"></i>
                            <span>4.9</span>
                            <span>(48 reviews)</span>
                        </div>
                        <div class="provider-meta">
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>San Francisco, CA</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Verified</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="provider-content">
                    <div class="provider-skills">
                        <h4 class="skills-title">Skills & Expertise</h4>
                        <div class="skills-list">
                            <span class="skill-tag">WordPress</span>
                            <span class="skill-tag">React</span>
                            <span class="skill-tag">UI/UX Design</span>
                            <span class="skill-tag">Node.js</span>
                        </div>
                    </div>
                    <p class="provider-description">
                        Full-stack developer with 8+ years of experience creating beautiful, functional websites and applications.
                    </p>
                </div>
                <div class="provider-footer">
                    <div class="provider-price">$50/hr</div>
                    <div class="provider-actions">
                        <button class="btn btn-outline btn-sm">
                            <i class="far fa-bookmark"></i>
                            Save
                        </button>
                        <button class="btn btn-primary btn-sm">
                            <i class="fas fa-envelope"></i>
                            Contact
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Provider Card 2 -->
            <div class="provider-card">
                <div class="provider-header">
                    <div class="provider-avatar">MC</div>
                    <div class="provider-info">
                        <h3>Michael Chen</h3>
                        <p>Graphic Designer</p>
                        <div class="provider-rating">
                            <i class="fas fa-star"></i>
                            <span>4.7</span>
                            <span>(36 reviews)</span>
                        </div>
                        <div class="provider-meta">
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>New York, NY</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Verified</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="provider-content">
                    <div class="provider-skills">
                        <h4 class="skills-title">Skills & Expertise</h4>
                        <div class="skills-list">
                            <span class="skill-tag">Logo Design</span>
                            <span class="skill-tag">Branding</span>
                            <span class="skill-tag">Illustration</span>
                            <span class="skill-tag">Adobe Creative Suite</span>
                        </div>
                    </div>
                    <p class="provider-description">
                        Creative designer specializing in branding and visual identity for startups and established businesses.
                    </p>
                </div>
                <div class="provider-footer">
                    <div class="provider-price">$45/hr</div>
                    <div class="provider-actions">
                        <button class="btn btn-outline btn-sm">
                            <i class="far fa-bookmark"></i>
                            Save
                        </button>
                        <button class="btn btn-primary btn-sm">
                            <i class="fas fa-envelope"></i>
                            Contact
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Provider Card 3 -->
            <div class="provider-card">
                <div class="provider-header">
                    <div class="provider-avatar">DW</div>
                    <div class="provider-info">
                        <h3>David Wilson</h3>
                        <p>Home Repair Specialist</p>
                        <div class="provider-rating">
                            <i class="fas fa-star"></i>
                            <span>5.0</span>
                            <span>(62 reviews)</span>
                        </div>
                        <div class="provider-meta">
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Chicago, IL</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Verified</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="provider-content">
                    <div class="provider-skills">
                        <h4 class="skills-title">Skills & Expertise</h4>
                        <div class="skills-list">
                            <span class="skill-tag">Plumbing</span>
                            <span class="skill-tag">Electrical</span>
                            <span class="skill-tag">Carpentry</span>
                            <span class="skill-tag">Home Renovation</span>
                        </div>
                    </div>
                    <p class="provider-description">
                        Licensed home repair specialist with 12 years of experience in plumbing, electrical, and carpentry work.
                    </p>
                </div>
                <div class="provider-footer">
                    <div class="provider-price">$65/hr</div>
                    <div class="provider-actions">
                        <button class="btn btn-outline btn-sm">
                            <i class="far fa-bookmark"></i>
                            Save
                        </button>
                        <button class="btn btn-primary btn-sm">
                            <i class="fas fa-envelope"></i>
                            Contact
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Provider Card 4 -->
            <div class="provider-card">
                <div class="provider-header">
                    <div class="provider-avatar">LW</div>
                    <div class="provider-info">
                        <h3>Lisa Wong</h3>
                        <p>Content Writer & SEO Specialist</p>
                        <div class="provider-rating">
                            <i class="fas fa-star"></i>
                            <span>4.8</span>
                            <span>(42 reviews)</span>
                        </div>
                        <div class="provider-meta">
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Austin, TX</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Verified</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="provider-content">
                    <div class="provider-skills">
                        <h4 class="skills-title">Skills & Expertise</h4>
                        <div class="skills-list">
                            <span class="skill-tag">SEO Writing</span>
                            <span class="skill-tag">Blog Content</span>
                            <span class="skill-tag">Copywriting</span>
                            <span class="skill-tag">Technical Writing</span>
                        </div>
                    </div>
                    <p class="provider-description">
                        Professional content writer specializing in SEO-optimized articles, blog posts, and website copy.
                    </p>
                </div>
                <div class="provider-footer">
                    <div class="provider-price">$35/hr</div>
                    <div class="provider-actions">
                        <button class="btn btn-outline btn-sm">
                            <i class="far fa-bookmark"></i>
                            Save
                        </button>
                        <button class="btn btn-primary btn-sm">
                            <i class="fas fa-envelope"></i>
                            Contact
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Provider Card 5 -->
            <div class="provider-card">
                <div class="provider-header">
                    <div class="provider-avatar">RG</div>
                    <div class="provider-info">
                        <h3>Robert Garcia</h3>
                        <p>Mobile App Developer</p>
                        <div class="provider-rating">
                            <i class="fas fa-star"></i>
                            <span>4.6</span>
                            <span>(28 reviews)</span>
                        </div>
                        <div class="provider-meta">
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Miami, FL</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Verified</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="provider-content">
                    <div class="provider-skills">
                        <h4 class="skills-title">Skills & Expertise</h4>
                        <div class="skills-list">
                            <span class="skill-tag">iOS Development</span>
                            <span class="skill-tag">Android</span>
                            <span class="skill-tag">Flutter</span>
                            <span class="skill-tag">React Native</span>
                        </div>
                    </div>
                    <p class="provider-description">
                        Experienced mobile developer creating cross-platform applications for startups and enterprises.
                    </p>
                </div>
                <div class="provider-footer">
                    <div class="provider-price">$75/hr</div>
                    <div class="provider-actions">
                        <button class="btn btn-outline btn-sm">
                            <i class="far fa-bookmark"></i>
                            Save
                        </button>
                        <button class="btn btn-primary btn-sm">
                            <i class="fas fa-envelope"></i>
                            Contact
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Provider Card 6 -->
            <div class="provider-card">
                <div class="provider-header">
                    <div class="provider-avatar">ET</div>
                    <div class="provider-info">
                        <h3>Emma Thompson</h3>
                        <p>UI/UX Designer</p>
                        <div class="provider-rating">
                            <i class="fas fa-star"></i>
                            <span>4.9</span>
                            <span>(51 reviews)</span>
                        </div>
                        <div class="provider-meta">
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Seattle, WA</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Verified</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="provider-content">
                    <div class="provider-skills">
                        <h4 class="skills-title">Skills & Expertise</h4>
                        <div class="skills-list">
                            <span class="skill-tag">UI Design</span>
                            <span class="skill-tag">UX Research</span>
                            <span class="skill-tag">Figma</span>
                            <span class="skill-tag">Prototyping</span>
                        </div>
                    </div>
                    <p class="provider-description">
                        UX designer focused on creating intuitive, user-friendly interfaces for web and mobile applications.
                    </p>
                </div>
                <div class="provider-footer">
                    <div class="provider-price">$60/hr</div>
                    <div class="provider-actions">
                        <button class="btn btn-outline btn-sm">
                            <i class="far fa-bookmark"></i>
                            Save
                        </button>
                        <button class="btn btn-primary btn-sm">
                            <i class="fas fa-envelope"></i>
                            Contact
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
            
            filterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    
                    // Update active tab
                    filterTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // In a real app, this would filter providers
                    alert(`Filtering by: ${filter}`);
                });
            });
            
            // Search functionality
            const searchInput = document.querySelector('.search-input');
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                console.log(`Searching for: ${searchTerm}`);
                // In a real app, this would filter providers based on search term
            });
            
            // Save provider buttons
            const saveButtons = document.querySelectorAll('.btn-outline.btn-sm');
            saveButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const providerName = this.closest('.provider-card').querySelector('h3').textContent;
                    const icon = this.querySelector('i');
                    
                    if (icon.classList.contains('far')) {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                        this.style.color = 'var(--primary)';
                        alert(`Saved ${providerName} to your favorites!`);
                    } else {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        this.style.color = '';
                        alert(`Removed ${providerName} from your favorites!`);
                    }
                });
            });
            
            // Contact provider buttons
            const contactButtons = document.querySelectorAll('.btn-primary.btn-sm');
            contactButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const providerName = this.closest('.provider-card').querySelector('h3').textContent;
                    alert(`Opening contact form for ${providerName}`);
                });
            });
            
            // Post a job button
            const postJobBtn = document.querySelector('.header-actions .btn-primary');
            postJobBtn.addEventListener('click', function() {
                alert('Redirecting to post a job page');
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
    
    <style>
        /* Upwork/Freelancer Style Bidding Posts */
        .search-item {
            background: #fff;
            border: 1px solid #e4e5e7;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .search-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-color: #14a800;
        }
        
        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }
        
        .client-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .client-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .client-details h4 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: #001e00;
        }
        
        .client-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 4px;
        }
        
        .rating {
            display: flex;
            align-items: center;
            gap: 4px;
            color: #14a800;
            font-size: 14px;
        }
        
        .posted-time {
            color: #5e6d55;
            font-size: 14px;
        }
        
        .post-title {
            font-size: 20px;
            font-weight: 600;
            color: #14a800;
            margin-bottom: 12px;
            line-height: 1.3;
            cursor: pointer;
        }
        
        .post-title:hover {
            color: #108a00;
        }
        
        .post-description {
            color: #5e6d55;
            line-height: 1.6;
            margin-bottom: 16px;
            font-size: 14px;
        }
        
        .post-skills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
        }
        
        .skill-tag {
            background: #f7f8f9;
            color: #5e6d55;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            border: 1px solid #e4e5e7;
        }
        
        .post-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 16px;
            border-top: 1px solid #e4e5e7;
        }
        
        .budget-info {
            display: flex;
            align-items: center;
            gap: 24px;
        }
        
        .budget, .proposals {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .budget label, .proposals label {
            color: #5e6d55;
            font-size: 13px;
            font-weight: 500;
        }
        
        .budget span, .proposals span {
            color: #001e00;
            font-weight: 600;
            font-size: 16px;
        }
        
        .action-buttons {
            display: flex;
            gap: 12px;
        }
        
        .btn-primary {
            background: #14a800;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .btn-primary:hover {
            background: #108a00;
        }
        
        .btn-secondary {
            background: transparent;
            color: #14a800;
            border: 1px solid #14a800;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: #14a800;
            color: white;
        }
        
        .verification-badge {
            background: #14a800;
            color: white;
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 3px;
            margin-left: 8px;
        }
        
        .urgent-badge {
            background: #ff6b35;
            color: white;
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 600;
        }
        
        .fixed-price-badge {
            background: #2c5aa0;
            color: white;
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 600;
        }
        
        .hourly-badge {
            background: #6b7280;
            color: white;
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 600;
        }
    </style>

    <title>Document</title>
</head>

<body>
    <section class="service-requests">
        <div class="header-requests">
            <h1>Available Job Posts</h1>
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
                    
                    <!-- Job Post 1 -->
                    <div class="search-item">
                        <div class="post-header">
                            <div class="client-info">
                                <img src="sampleImg.jpg" alt="Client" class="client-avatar">
                                <div class="client-details">
                                    <h4>Chethiya Bandara <span class="verification-badge">✓ Verified</span></h4>
                                    <div class="client-meta">
                                        <div class="rating">
                                            <i class="fa-solid fa-star"></i>
                                            <span>4.9 (127 reviews)</span>
                                        </div>
                                        <span class="posted-time">Posted 2 hours ago</span>
                                    </div>
                                </div>
                            </div>
                            <span class="urgent-badge">URGENT</span>
                        </div>
                        
                        <h3 class="post-title">Need a Graphic Designer to Design Class Posts</h3>
                        
                        <p class="post-description">
                            I'm looking for a creative graphic designer to create engaging social media posts for my online classes. The designs should be modern, eye-catching, and align with our educational brand. Experience with educational content design is preferred.
                        </p>
                        
                        <div class="post-skills">
                            <span class="skill-tag">Graphic Design</span>
                            <span class="skill-tag">Social Media Design</span>
                            <span class="skill-tag">Adobe Photoshop</span>
                            <span class="skill-tag">Brand Design</span>
                        </div>
                        
                        <div class="post-meta">
                            <div class="budget-info">
                                <div class="budget">
                                    <label>Budget</label>
                                    <span>$40/hr</span>
                                </div>
                                <div class="proposals">
                                    <label>Proposals</label>
                                    <span>8</span>
                                </div>
                            </div>
                            <div class="action-buttons">
                                <button class="btn-secondary"><i class="fa-light fa-heart"></i> Save</button>
                                <button class="btn-primary">Submit Proposal</button>
                            </div>
                        </div>
                    </div>

                    <!-- Job Post 2 -->
                    <div class="search-item">
                        <div class="post-header">
                            <div class="client-info">
                                <img src="sampleImg.jpg" alt="Client" class="client-avatar">
                                <div class="client-details">
                                    <h4>Nimal Perera</h4>
                                    <div class="client-meta">
                                        <div class="rating">
                                            <i class="fa-solid fa-star"></i>
                                            <span>4.7 (89 reviews)</span>
                                        </div>
                                        <span class="posted-time">Posted 5 hours ago</span>
                                    </div>
                                </div>
                            </div>
                            <span class="hourly-badge">HOURLY</span>
                        </div>
                        
                        <h3 class="post-title">Need a UI/UX Designer for Mobile App Layout</h3>
                        
                        <p class="post-description">
                            Transform your app with sleek, user-friendly designs — delivered with precision and creativity. Looking for a UI/UX expert specializing in mobile and web interfaces to create intuitive designs for our e-commerce app.
                        </p>
                        
                        <div class="post-skills">
                            <span class="skill-tag">UI/UX Design</span>
                            <span class="skill-tag">Mobile Design</span>
                            <span class="skill-tag">Figma</span>
                            <span class="skill-tag">Prototyping</span>
                        </div>
                        
                        <div class="post-meta">
                            <div class="budget-info">
                                <div class="budget">
                                    <label>Hourly Rate</label>
                                    <span>$50/hr</span>
                                </div>
                                <div class="proposals">
                                    <label>Proposals</label>
                                    <span>15</span>
                                </div>
                            </div>
                            <div class="action-buttons">
                                <button class="btn-secondary"><i class="fa-light fa-heart"></i> Save</button>
                                <button class="btn-primary">Submit Proposal</button>
                            </div>
                        </div>
                    </div>

                    <!-- Job Post 3 -->
                    <div class="search-item">
                        <div class="post-header">
                            <div class="client-info">
                                <img src="sampleImg.jpg" alt="Client" class="client-avatar">
                                <div class="client-details">
                                    <h4>Sunil Fernando <span class="verification-badge">✓ Verified</span></h4>
                                    <div class="client-meta">
                                        <div class="rating">
                                            <i class="fa-solid fa-star"></i>
                                            <span>5.0 (45 reviews)</span>
                                        </div>
                                        <span class="posted-time">Posted 1 day ago</span>
                                    </div>
                                </div>
                            </div>
                            <span class="fixed-price-badge">FIXED PRICE</span>
                        </div>
                        
                        <h3 class="post-title">Require a Developer for E-commerce Website</h3>
                        
                        <p class="post-description">
                            Build a robust e-commerce platform with seamless payment integration. I'm looking for a developer skilled in Shopify, WooCommerce, and custom solutions to create a modern online store.
                        </p>
                        
                        <div class="post-skills">
                            <span class="skill-tag">E-commerce</span>
                            <span class="skill-tag">Shopify</span>
                            <span class="skill-tag">WooCommerce</span>
                            <span class="skill-tag">Payment Integration</span>
                        </div>
                        
                        <div class="post-meta">
                            <div class="budget-info">
                                <div class="budget">
                                    <label>Fixed Budget</label>
                                    <span>$2,500</span>
                                </div>
                                <div class="proposals">
                                    <label>Proposals</label>
                                    <span>23</span>
                                </div>
                            </div>
                            <div class="action-buttons">
                                <button class="btn-secondary"><i class="fa-light fa-heart"></i> Save</button>
                                <button class="btn-primary">Submit Proposal</button>
                            </div>
                        </div>
                    </div>

                    <!-- Job Post 4 -->
                    <div class="search-item">
                        <div class="post-header">
                            <div class="client-info">
                                <img src="sampleImg.jpg" alt="Client" class="client-avatar">
                                <div class="client-details">
                                    <h4>Kamal Silva</h4>
                                    <div class="client-meta">
                                        <div class="rating">
                                            <i class="fa-solid fa-star"></i>
                                            <span>4.8 (156 reviews)</span>
                                        </div>
                                        <span class="posted-time">Posted 3 days ago</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h3 class="post-title">Need a Content Writer for Blog Posts</h3>
                        
                        <p class="post-description">
                            Craft engaging and SEO-optimized blog content to boost your online presence. I'm looking for a writer with expertise in tech and lifestyle topics to create compelling articles for our website.
                        </p>
                        
                        <div class="post-skills">
                            <span class="skill-tag">Content Writing</span>
                            <span class="skill-tag">SEO</span>
                            <span class="skill-tag">Blog Writing</span>
                            <span class="skill-tag">Tech Writing</span>
                        </div>
                        
                        <div class="post-meta">
                            <div class="budget-info">
                                <div class="budget">
                                    <label>Hourly Rate</label>
                                    <span>$30/hr</span>
                                </div>
                                <div class="proposals">
                                    <label>Proposals</label>
                                    <span>12</span>
                                </div>
                            </div>
                            <div class="action-buttons">
                                <button class="btn-secondary"><i class="fa-light fa-heart"></i> Save</button>
                                <button class="btn-primary">Submit Proposal</button>
                            </div>
                        </div>
                    </div>

                    <!-- Job Post 5 -->
                    <div class="search-item">
                        <div class="post-header">
                            <div class="client-info">
                                <img src="sampleImg.jpg" alt="Client" class="client-avatar">
                                <div class="client-details">
                                    <h4>Ruwan Wijesinghe</h4>
                                    <div class="client-meta">
                                        <div class="rating">
                                            <i class="fa-solid fa-star"></i>
                                            <span>4.6 (72 reviews)</span>
                                        </div>
                                        <span class="posted-time">Posted 6 hours ago</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h3 class="post-title">Seeking a Video Editor for Promotional Clips</h3>
                        
                        <p class="post-description">
                            Create stunning promotional videos with smooth edits and effects. I'm looking for a video editor experienced in Adobe Premiere and After Effects to produce high-quality marketing content.
                        </p>
                        
                        <div class="post-skills">
                            <span class="skill-tag">Video Editing</span>
                            <span class="skill-tag">Adobe Premiere</span>
                            <span class="skill-tag">After Effects</span>
                            <span class="skill-tag">Motion Graphics</span>
                        </div>
                        
                        <div class="post-meta">
                            <div class="budget-info">
                                <div class="budget">
                                    <label>Hourly Rate</label>
                                    <span>$45/hr</span>
                                </div>
                                <div class="proposals">
                                    <label>Proposals</label>
                                    <span>18</span>
                                </div>
                            </div>
                            <div class="action-buttons">
                                <button class="btn-secondary"><i class="fa-light fa-heart"></i> Save</button>
                                <button class="btn-primary">Submit Proposal</button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="pagination">
                    <button class="prev-page"><i class="fa-solid fa-chevron-left"></i> Previous</button>
                    <span class="page-number">1</span>
                    <button class="next-page">Next <i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>
            
            <!-- Fixed Price Posts Section -->
            <div class="payment-pending-requests requests-section">
                <div class="item-list">
                    <div class="search-item">
                        <div class="post-header">
                            <div class="client-info">
                                <img src="sampleImg.jpg" alt="Client" class="client-avatar">
                                <div class="client-details">
                                    <h4>Himath Adithya <span class="verification-badge">✓ Verified</span></h4>
                                    <div class="client-meta">
                                        <div class="rating">
                                            <i class="fa-solid fa-star"></i>
                                            <span>4.5 (68 reviews)</span>
                                        </div>
                                        <span class="posted-time">Posted 1 day ago</span>
                                    </div>
                                </div>
                            </div>
                            <span class="fixed-price-badge">FIXED PRICE</span>
                        </div>
                        
                        <h3 class="post-title">Need a Good 3D Artist for Product Visualization</h3>
                        
                        <p class="post-description">
                            Looking for a skilled 3D artist to create realistic product visualizations for our e-commerce platform. Must have experience with Blender or 3ds Max and product rendering.
                        </p>
                        
                        <div class="post-skills">
                            <span class="skill-tag">3D Modeling</span>
                            <span class="skill-tag">Blender</span>
                            <span class="skill-tag">Product Visualization</span>
                            <span class="skill-tag">Rendering</span>
                        </div>
                        
                        <div class="post-meta">
                            <div class="budget-info">
                                <div class="budget">
                                    <label>Fixed Budget</label>
                                    <span>$1,800</span>
                                </div>
                                <div class="proposals">
                                    <label>Proposals</label>
                                    <span>6</span>
                                </div>
                            </div>
                            <div class="action-buttons">
                                <button class="btn-secondary"><i class="fa-light fa-heart"></i> Save</button>
                                <button class="btn-primary">Submit Proposal</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Hourly Posts Section -->
            <div class="declined-requests requests-section">
                <div class="item-list">
                    <div class="search-item">
                        <div class="post-header">
                            <div class="client-info">
                                <img src="sampleImg.jpg" alt="Client" class="client-avatar">
                                <div class="client-details">
                                    <h4>Priya Menon</h4>
                                    <div class="client-meta">
                                        <div class="rating">
                                            <i class="fa-solid fa-star"></i>
                                            <span>4.8 (95 reviews)</span>
                                        </div>
                                        <span class="posted-time">Posted 4 hours ago</span>
                                    </div>
                                </div>
                            </div>
                            <span class="hourly-badge">HOURLY</span>
                        </div>
                        
                        <h3 class="post-title">Need a Content Writer for Marketing Materials</h3>
                        
                        <p class="post-description">
                            Looking for an ongoing content writer to help with our marketing materials, blog posts, and social media content. This is a long-term hourly project with consistent work.
                        </p>
                        
                        <div class="post-skills">
                            <span class="skill-tag">Content Writing</span>
                            <span class="skill-tag">Marketing</span>
                            <span class="skill-tag">Social Media</span>
                            <span class="skill-tag">SEO Writing</span>
                        </div>
                        
                        <div class="post-meta">
                            <div class="budget-info">
                                <div class="budget">
                                    <label>Hourly Rate</label>
                                    <span>$25 - $35/hr</span>
                                </div>
                                <div class="proposals">
                                    <label>Proposals</label>
                                    <span>14</span>
                                </div>
                            </div>
                            <div class="action-buttons">
                                <button class="btn-secondary"><i class="fa-light fa-heart"></i> Save</button>
                                <button class="btn-primary">Submit Proposal</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Keep all existing pop-ups -->
    <div class="pop-up-section filter-pop-up deactive">
        <div class="pop-up deactive">
            <div class="pop-up-header">
                <div class="pop-up-title">Add Filters</div>
                <i class="fa-light fa-xmark" id="filter-pop-up"></i>
            </div>
            <hr>
            <div class="pop-up-content">
                <div class="search-filters">
                    <div class="filter-item">
                        <div class="filter-title"><span>Hourly rate</span><i
                                class="fa-light fa-chevron-down rotated"></i>
                        </div>
                        <ul class="filter-options active radios">
                            <li><input type="radio" name="rate" id="rate" checked>Any hourly rate</li>
                            <li><input type="radio" name="rate" id="rate">Less than $10</li>
                            <li><input type="radio" name="rate" id="rate">$10 - $30</li>
                            <li><input type="radio" name="rate" id="rate">$30 - $60</li>
                            <li><input type="radio" name="rate" id="rate">$60 & above</li>
                        </ul>
                    </div>
                    <div class="filter-item">
                        <div class="filter-title"><span>Project success</span><i class="fa-light fa-chevron-down"></i>
                        </div>
                        <ul class="filter-options radios">
                            <li><input type="radio" name="success" id="success" checked>Any success rate</li>
                            <li><input type="radio" name="success" id="success">90% & up</li>
                            <li><input type="radio" name="success" id="success">80% & up</li>
                            <li><input type="radio" name="success" id="success">70% & up</li>
                            <li><input type="radio" name="success" id="success">Less than 70%</li>
                        </ul>
                    </div>
                    <div class="filter-item">
                        <div class="filter-title"><span>Total Earnings</span><i class="fa-light fa-chevron-down"></i>
                        </div>
                        <ul class="filter-options radios">
                            <li><input type="radio" name="earnings" id="earnings" checked>Any amount earned</li>
                            <li><input type="radio" name="earnings" id="earnings">$1+ earned</li>
                            <li><input type="radio" name="earnings" id="earnings">$100+ earned</li>
                            <li><input type="radio" name="earnings" id="earnings">$1K+ earned</li>
                            <li><input type="radio" name="earnings" id="earnings">$10K+ earned</li>
                            <li><input type="radio" name="earnings" id="earnings">No earnings yet</li>
                        </ul>
                    </div>
                    <div class="filter-item">
                        <div class="filter-title"><span>Language</span><i class="fa-light fa-chevron-down"></i></div>
                        <ul class="filter-options checkboxes">
                            <li><input type="checkbox" name="language" id="language" checked>English</li>
                            <li><input type="checkbox" name="language" id="language">Sinhala</li>
                            <li><input type="checkbox" name="language" id="language">Tamil</li>
                            <li><input type="checkbox" name="language" id="language">Other</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="button-apply">
                <button>Apply filters</button>
            </div>
        </div>
    </div>

    <div class="pop-up-section request-det-pop-up deactive">
        <div class="pop-up deactive">
            <div class="pop-up-header">
                <div class="pop-up-title">Job Post Details</div>
                <i class="fa-light fa-xmark" id="request-det-pop-up"></i>
            </div>
            <hr>
            <div class="pop-up-content">
                <div class="request-details">
                    <ul>
                        <li>
                            <span class="det-title">Client :</span>
                            <span class="det"><a href="">Chethiya Bandara</a></span>
                        </li>
                        <li>
                            <span class="det-title">Title :</span>
                            <span class="det">Need a Graphic Designer to Design Class Posts</span>
                        </li>
                        <li>
                            <span class="det-title">Description :</span>
                            <span class="det">I'm looking for a creative graphic designer to create engaging social media posts for my online classes. The designs should be modern, eye-catching, and align with our educational brand.</span>
                        </li>
                        <li>
                            <span class="det-title">Budget :</span>
                            <span class="det">$40/hr</span>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="button" style="width:100%">
                <button class="btn-secondary"><i class="fa-light fa-heart"></i> Save Post</button>
                <button class="btn-primary"><i class="fa-regular fa-paper-plane"></i> Submit Proposal</button>
            </div>
        </div>
    </div>

</body>
<script src="<?= BASE_URL ?>/assets/js/cardList.js" defer></script>
</html>

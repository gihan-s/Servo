<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/clientPosts.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">

    <script src="<?= BASE_URL ?>/assets/js/cardList.js" defer></script>
    <script src="<?= BASE_URL ?>/assets/js/clientPosts.js" defer></script>
    <title>My Job Posts</title>
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
                    <button type="button" class="post-job-btn" id="create-post-pop-up"><i
                            class="fa-regular fa-plus"></i> Create Post</button>
                </div>

            </div>


            <div class="container-changer">
                <div class="tab-buttons">
                    <div id="active-posts" class="buttons active">Active Posts</div>
                    <div id="draft-posts" class="buttons">Draft Posts</div>
                    <div id="expired-posts" class="buttons">Expired Posts</div>
                </div>
            </div>
        </div>

        <div class="request-content">
            <!-- ACTIVE POSTS SECTION -->
            <div class="active-posts active requests-section">
                <div class="item-list">
                    <!-- Active Post 1 -->
                    <div class="search-item">

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
                        </div>
                    </div>

                    <!-- Active Post 2 -->
                    <div class="search-item">

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
                                    <span class="detail-label">Proposals Received</span>
                                    <span class="detail-value proposals-count">0</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Level</span>
                                    <span class="detail-value project-level">—</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Duration</span>
                                    <span class="detail-value project-duration">—</span>
                                </div>
                            </div>
                        </div>


                    </div>

                    <!-- Draft Post 2 -->
                    <div class="search-item">

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
                                    <span class="detail-label">Proposals Received</span>
                                    <span class="detail-value proposals-count">0</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Level</span>
                                    <span class="detail-value project-level">—</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Duration</span>
                                    <span class="detail-value project-duration">—</span>
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
                        </div>
                    </div>

                    <!-- Expired Post 2 -->
                    <div class="search-item">

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
<!-- Create Post Modal (matches project pop-up pattern) -->
<div class="pop-up-section create-post-pop-up deactive">
    <div class="pop-up deactive">
        <div class="pop-up-header">
            <div class="pop-up-title">Create a New Post</div>
            <i class="fa-light fa-xmark" id="create-post-pop-up"></i>
        </div>
        <hr>
        <div class="pop-up-content">
            <form id="create-post-form" class="create-post-form" onsubmit="return false;">
                <div class="form-group">
                    <label for="post-title">Post Title</label>
                    <input type="text" id="post-title" name="title" placeholder="Enter a clear, concise title" required>
                </div>
                <div class="form-group">
                    <label for="post-description">Post Description</label>
                    <textarea id="post-description" name="description" rows="4"
                        placeholder="Describe the work, scope, deliverables, and expectations" required></textarea>
                </div>
                <div class="form-group">
                    <label for="post-skills">Required Skills</label>
                    <input type="text" id="post-skills" name="skills"
                        placeholder="e.g., React, Node.js, SEO (comma-separated)">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="requesting-price">Requesting Price</label>
                        <input type="number" id="requesting-price" name="price" placeholder="e.g., 5000" min="0"
                            step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="price-type">Price Type</label>
                        <select id="price-type" name="price_type">
                            <option value="hourly">Hourly</option>
                            <option value="fixed">Entire Work</option>
                            <option value="daily">Daily</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="work-level">Level of Work</label>
                        <select id="work-level" name="level">
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="expert">Expert</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="duration">Duration</label>
                        <input type="text" id="duration" name="duration" placeholder="e.g., 2-3 months">
                    </div>
                </div>
                <div class="form-group">
                    <label for="expire-date">Post Expire Date (limits bidding time)</label>
                    <input type="date" id="expire-date" name="expire_date">
                </div>

                <div class="modal-actions">
                    <button type="button" class="action-btn btn-view" id="create-post-pop-up"
                        data-role="cancel">Cancel</button>
                    <button type="button" class="action-btn btn-edit" id="create-post-pop-up" data-role="save-draft">
                        <i class="fa-regular fa-floppy-disk"></i>
                        Save Draft
                    </button>
                    <button type="button" class="action-btn btn-view" id="create-post-pop-up" data-role="publish">
                        <i class="fa-regular fa-rocket"></i>
                        Publish Post
                    </button>
                    <button type="button" class="action-btn btn-edit" data-role="save-post" style="display:none;">
                        <i class="fa-regular fa-floppy-disk"></i>
                        Save Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    
    // (Removed previous capture guard). We'll override togglePopUp safely after external scripts load.
</script>
<!-- Post Details Modal -->
<div class="pop-up-section request-modal deactive" id="postDetailsRoot">
    <div class="pop-up deactive" id="postDetailsModal" style="max-width:720px; border-radius:16px;">
        <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
            <div class="pop-up-title">Post Details</div>
            <i class="fa-light fa-xmark" id="postDetailsClose" style="cursor:pointer;"></i>
        </div>
        <hr>
        <div class="pop-up-content post-view" style="display:flex; flex-direction:column; gap:12px;">
            <div class="post-view-title" id="modalPostTitle">Post Title</div>
            <div class="post-view-meta">
                <span class="chip"><i class="fa-regular fa-calendar"></i><span id="modalPostDate">—</span></span>
            </div>
            <div class="post-view-section">
                <div class="section-title">Description</div>
                <div id="modalPostDescription" class="section-body">Description</div>
            </div>
            <div class="post-view-section">
                <div class="section-title">Required Skills</div>
                <div id="modalPostSkills" class="skills-row"></div>
            </div>
            <div class="post-view-section">
                <div class="section-title">Details</div>
                <div id="modalPostKV" class="kv-grid"></div>
            </div>
            <div class="post-view-section" id="modalEngagementSection" style="display:none;">
                <div class="section-title">Engagement</div>
                <div id="modalPostEngagement" class="engagement-row"></div>
            </div>
        </div>
        <div class="modal-actions" style="justify-content:flex-end;">
            <button class="action-btn btn-delete" id="modalDeleteBtn"><i class="fa-regular fa-circle-xmark"></i>
                Delete</button>
        </div>
    </div>
</div>


<!-- Confirm Delete Modal -->
<div class="pop-up-section confirm-modal deactive" id="confirmDeleteRoot">
    <div class="pop-up deactive" id="confirmDelete">
        <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
            <div class="pop-up-title">Confirm Delete</div>
            <i class="fa-light fa-xmark" id="confirmDeleteClose" style="cursor:pointer;"></i>
        </div>
        <hr>
        <div class="pop-up-content">
            Are you sure you want to delete this post? This action cannot be undone.
        </div>
        <div class="modal-actions">
            <button class="action-btn btn-view" id="confirmKeep">Keep</button>
            <button class="action-btn btn-delete" id="confirmDeleteBtn"><i class="fa-regular fa-circle-xmark"></i> Yes,
                Delete</button>
        </div>
    </div>
</div>

<!-- Confirm Publish Modal -->
<div class="pop-up-section confirm-modal deactive" id="confirmPublishRoot">
    <div class="pop-up deactive" id="confirmPublish">
        <div class="pop-up-header" style="display:flex; align-items:center; justify-content:space-between;">
            <div class="pop-up-title">Publish Post</div>
            <i class="fa-light fa-xmark" id="confirmPublishClose" style="cursor:pointer;"></i>
        </div>
        <hr>
        <div class="pop-up-content">
            Are you sure you want to publish this draft? It will become visible for providers to bid.
        </div>
        <div class="modal-actions">
            <button class="action-btn btn-view" id="confirmPublishKeep">Cancel</button>
            <button class="action-btn btn-edit" id="confirmPublishBtn"><i class="fa-regular fa-rocket"></i>
                Publish</button>
        </div>
    </div>
</div>


</html>
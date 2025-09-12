<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard - ServiceHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-text">
                <h2>Welcome back, John!</h2>
                <p>Here's what's happening with your jobs and services today</p>
                <div class="welcome-actions">
                    <button class="btn btn-post-job">Post a Job</button>
                    <button class="btn-find-providers btn">Find Providers</button>
                </div>
            </div>
            <div class="welcome-image">
                <img src="waving_graphic.png" alt="Welcome Illustration" style="width: 100%; height: 200px;">
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <div class="stat-info">
                    <h3>5</h3>
                    <p>Active Jobs</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fa-solid fa-square-check"></i>
                </div>
                <div class="stat-info">
                    <h3>12</h3>
                    <p>Completed Jobs</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fa-solid fa-message"></i>
                </div>
                <div class="stat-info">
                    <h3>3</h3>
                    <p>Unread Messages</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fa-solid fa-bookmark"></i>
                </div>
                <div class="stat-info">
                    <h3>8</h3>
                    <p>Providers Saved</p>
                </div>
            </div>
        </div>

        <!-- Active Jobs Section -->
        <div class="jobs-section">
            <div class="section-header">
                <h2 class="section-title">Active Jobs</h2>
                <a href="client-jobs.php" class="view-all">View All →</a>
            </div>

            <div class="jobs-grid">
                <div class="job-card">
                    <div class="job-header">
                        <div>
                            <h3 class="job-title">Website Development</h3>
                            <p class="job-provider">Sarah Johnson</p>
                        </div>
                        <span class="job-status status-in-progress">In Progress</span>
                    </div>
                    <div class="job-details">
                        <p class="job-description">E-commerce website for clothing store with shopping cart and payment
                            integration.</p>
                        <div class="job-meta">
                            <div>
                                <i>📅</i> Deadline: Jun 30, 2023
                            </div>
                            <div>
                                <i>💰</i> $1,200
                            </div>
                        </div>
                    </div>
                    <div class="job-actions">
                        <button class="btn btn-outline btn-sm">View Details</button>
                        <button class="btn btn-primary btn-sm">Message</button>
                    </div>
                </div>

                <div class="job-card">
                    <div class="job-header">
                        <div>
                            <h3 class="job-title">Logo Design</h3>
                            <p class="job-provider">Michael Chen</p>
                        </div>
                        <span class="job-status status-pending">Pending Review</span>
                    </div>
                    <div class="job-details">
                        <p class="job-description">Modern logo design for tech startup with brand guidelines.</p>
                        <div class="job-meta">
                            <div>
                                <i>📅</i> Deadline: Jun 15, 2023
                            </div>
                            <div>
                                <i>💰</i> $350
                            </div>
                        </div>
                    </div>
                    <div class="job-actions">
                        <button class="btn btn-outline btn-sm">Review Work</button>
                        <button class="btn btn-primary btn-sm">Message</button>
                    </div>
                </div>

                <div class="job-card">
                    <div class="job-header">
                        <div>
                            <h3 class="job-title">Home Repair</h3>
                            <p class="job-provider">David Wilson</p>
                        </div>
                        <span class="job-status status-completed">Completed</span>
                    </div>
                    <div class="job-details">
                        <p class="job-description">Plumbing and electrical work for kitchen renovation.</p>
                        <div class="job-meta">
                            <div>
                                <i>📅</i> Completed: May 30, 2023
                            </div>
                            <div>
                                <i>💰</i> $850
                            </div>
                        </div>
                    </div>
                    <div class="job-actions">
                        <button class="btn btn-outline btn-sm">View Details</button>
                        <button class="btn btn-primary btn-sm">Leave Review</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommended Providers -->
        <div class="providers-section">
            <div class="section-header">
                <h2 class="section-title">Recommended Providers</h2>
                <a href="client-providers.php" class="view-all">Browse All →</a>
            </div>

            <div class="providers-grid">
                <div class="provider-card">
                    <div class="provider-header">
                        <div class="provider-avatar"></div>
                        <div class="provider-info">
                            <h4>Emma Thompson</h4>
                            <p>Graphic Designer</p>
                            <div class="provider-rating">★★★★★ (24)</div>
                        </div>
                    </div>
                    <div class="provider-skills">
                        <p>Skills:</p>
                        <div class="skills-list">
                            <span class="skill-tag">Logo Design</span>
                            <span class="skill-tag">Branding</span>
                            <span class="skill-tag">Illustration</span>
                        </div>
                    </div>
                    <div class="provider-footer">
                        <div class="provider-price">$45/hr</div>
                        <button class="btn btn-primary btn-sm">Hire Now</button>
                    </div>
                </div>

                <div class="provider-card">
                    <div class="provider-header">
                        <div class="provider-avatar"></div>
                        <div class="provider-info">
                            <h4>Robert Garcia</h4>
                            <p>Mobile Developer</p>
                            <div class="provider-rating">★★★★☆ (18)</div>
                        </div>
                    </div>
                    <div class="provider-skills">
                        <p>Skills:</p>
                        <div class="skills-list">
                            <span class="skill-tag">iOS</span>
                            <span class="skill-tag">Android</span>
                            <span class="skill-tag">Flutter</span>
                        </div>
                    </div>
                    <div class="provider-footer">
                        <div class="provider-price">$65/hr</div>
                        <button class="btn btn-primary btn-sm">Hire Now</button>
                    </div>
                </div>

                <div class="provider-card">
                    <div class="provider-header">
                        <div class="provider-avatar"></div>
                        <div class="provider-info">
                            <h4>Lisa Wong</h4>
                            <p>Content Writer</p>
                            <div class="provider-rating">★★★★★ (32)</div>
                        </div>
                    </div>
                    <div class="provider-skills">
                        <p>Skills:</p>
                        <div class="skills-list">
                            <span class="skill-tag">Blog Writing</span>
                            <span class="skill-tag">SEO</span>
                            <span class="skill-tag">Copywriting</span>
                        </div>
                    </div>
                    <div class="provider-footer">
                        <div class="provider-price">$35/hr</div>
                        <button class="btn btn-primary btn-sm">Hire Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="dashboard.js"></script>
</body>

</html>
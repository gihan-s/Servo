<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Requests - ServiceHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../legacy/styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Job Requests</h1>
            <div class="header-actions">
                <button class="btn btn-outline">Browse More Jobs</button>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <div class="filter-tab active">All Requests</div>
            <div class="filter-tab">New</div>
            <div class="filter-tab">Pending Response</div>
            <div class="filter-tab">Accepted</div>
            <div class="filter-tab">Declined</div>
        </div>

        <!-- Job Requests List -->
        <div class="jobs-list">
            <div class="job-card urgent">
                <div class="job-header">
                    <div>
                        <h3 class="job-title">Website Development</h3>
                        <p class="job-client">Client: Sarah Johnson</p>
                        <span class="job-urgent">Urgent</span>
                    </div>
                    <span class="job-status status-new">New Request</span>
                </div>
                <div class="job-details">
                    <p class="job-description">E-commerce website for clothing store with shopping cart functionality, payment gateway integration, and admin panel for inventory management.</p>
                    <div class="job-meta">
                        <div><i class="fa-solid fa-calendar"></i> Deadline: Jun 30, 2023</div>
                        <div><i class="fa-solid fa-dollar-sign"></i> $1,200</div>
                        <div><i class="fa-solid fa-clock"></i> Posted: 2 hours ago</div>
                        <div><i class="fa-solid fa-map-marker-alt"></i> Remote Work</div>
                    </div>
                    <div class="job-skills">
                        <span class="skill-tag">PHP</span>
                        <span class="skill-tag">MySQL</span>
                        <span class="skill-tag">JavaScript</span>
                        <span class="skill-tag">CSS</span>
                    </div>
                </div>
                <div class="job-actions">
                    <button class="btn btn-primary btn-sm">Accept Request</button>
                    <button class="btn btn-outline btn-sm">View Details</button>
                    <button class="btn btn-danger btn-sm">Decline</button>
                </div>
            </div>

            <div class="job-card">
                <div class="job-header">
                    <div>
                        <h3 class="job-title">Mobile App Development</h3>
                        <p class="job-client">Client: Michael Chen</p>
                    </div>
                    <span class="job-status status-new">New Request</span>
                </div>
                <div class="job-details">
                    <p class="job-description">Cross-platform mobile app for fitness tracking with social features, workout plans, and progress analytics.</p>
                    <div class="job-meta">
                        <div><i class="fa-solid fa-calendar"></i> Deadline: Jul 15, 2023</div>
                        <div><i class="fa-solid fa-dollar-sign"></i> $2,500</div>
                        <div><i class="fa-solid fa-clock"></i> Posted: 1 day ago</div>
                        <div><i class="fa-solid fa-map-marker-alt"></i> Remote Work</div>
                    </div>
                    <div class="job-skills">
                        <span class="skill-tag">React Native</span>
                        <span class="skill-tag">Node.js</span>
                        <span class="skill-tag">MongoDB</span>
                        <span class="skill-tag">REST API</span>
                    </div>
                </div>
                <div class="job-actions">
                    <button class="btn btn-primary btn-sm">Accept Request</button>
                    <button class="btn btn-outline btn-sm">View Details</button>
                    <button class="btn btn-danger btn-sm">Decline</button>
                </div>
            </div>

            <div class="job-card">
                <div class="job-header">
                    <div>
                        <h3 class="job-title">Logo Design</h3>
                        <p class="job-client">Client: Emma Wilson</p>
                    </div>
                    <span class="job-status status-pending">Pending Response</span>
                </div>
                <div class="job-details">
                    <p class="job-description">Modern and minimalist logo design for tech startup with complete brand identity package including business cards and letterhead.</p>
                    <div class="job-meta">
                        <div><i class="fa-solid fa-calendar"></i> Deadline: Jun 20, 2023</div>
                        <div><i class="fa-solid fa-dollar-sign"></i> $350</div>
                        <div><i class="fa-solid fa-clock"></i> Posted: 3 days ago</div>
                        <div><i class="fa-solid fa-map-marker-alt"></i> Remote Work</div>
                    </div>
                    <div class="job-skills">
                        <span class="skill-tag">Adobe Illustrator</span>
                        <span class="skill-tag">Brand Design</span>
                        <span class="skill-tag">Logo Design</span>
                    </div>
                </div>
                <div class="job-actions">
                    <button class="btn btn-success btn-sm">Accepted</button>
                    <button class="btn btn-outline btn-sm">View Details</button>
                    <button class="btn btn-primary btn-sm">Message Client</button>
                </div>
            </div>

            <div class="job-card">
                <div class="job-header">
                    <div>
                        <h3 class="job-title">Home Renovation</h3>
                        <p class="job-client">Client: David Brown</p>
                    </div>
                    <span class="job-status status-new">New Request</span>
                </div>
                <div class="job-details">
                    <p class="job-description">Complete kitchen and bathroom renovation including plumbing, electrical work, tiling, and fixture installation.</p>
                    <div class="job-meta">
                        <div><i class="fa-solid fa-calendar"></i> Deadline: Aug 1, 2023</div>
                        <div><i class="fa-solid fa-dollar-sign"></i> $1,800</div>
                        <div><i class="fa-solid fa-clock"></i> Posted: 1 week ago</div>
                        <div><i class="fa-solid fa-map-marker-alt"></i> Downtown Area</div>
                    </div>
                    <div class="job-skills">
                        <span class="skill-tag">Plumbing</span>
                        <span class="skill-tag">Electrical</span>
                        <span class="skill-tag">Tiling</span>
                        <span class="skill-tag">Renovation</span>
                    </div>
                </div>
                <div class="job-actions">
                    <button class="btn btn-primary btn-sm">Accept Request</button>
                    <button class="btn btn-outline btn-sm">View Details</button>
                    <button class="btn btn-danger btn-sm">Decline</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>



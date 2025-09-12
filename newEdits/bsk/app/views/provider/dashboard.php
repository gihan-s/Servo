<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provider Dashboard - ServiceHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../legacy/styles.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-text">
                <h2>Welcome back, Provider!</h2>
                <p>Here's what's happening with your services and jobs today</p>
                <div class="welcome-actions">
                    <button class="btn btn-primary">Browse Jobs</button>
                    <button class="btn btn-outline">Update Services</button>
                </div>
            </div>
            <div class="welcome-image">
                <img src="/servo.png" alt="Welcome Illustration" style="width: 100%; height: 200px;">
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <div class="stat-info">
                    <h3>8</h3>
                    <p>Active Jobs</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fa-solid fa-square-check"></i>
                </div>
                <div class="stat-info">
                    <h3>24</h3>
                    <p>Completed Jobs</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>3</h3>
                    <p>Pending Requests</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>
                <div class="stat-info">
                    <h3>$3,450</h3>
                    <p>Total Earnings</p>
                </div>
            </div>
        </div>

        <!-- Dashboard Content Grid -->
        <div class="dashboard-grid">
            <!-- Recent Job Requests -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Recent Job Requests</h3>
                    <a href="/provider/job_requests" class="view-all">View All</a>
                </div>
                <div class="job-requests-list">
                    <div class="job-request-item">
                        <div class="job-info">
                            <h4>Website Development</h4>
                            <p>E-commerce site for clothing store</p>
                            <span class="job-budget">$1,200</span>
                        </div>
                        <div class="job-actions">
                            <button class="btn btn-primary btn-sm">Accept</button>
                            <button class="btn btn-outline btn-sm">Decline</button>
                        </div>
                    </div>
                    <div class="job-request-item">
                        <div class="job-info">
                            <h4>Logo Design</h4>
                            <p>Modern logo for tech startup</p>
                            <span class="job-budget">$350</span>
                        </div>
                        <div class="job-actions">
                            <button class="btn btn-primary btn-sm">Accept</button>
                            <button class="btn btn-outline btn-sm">Decline</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Jobs -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Active Jobs</h3>
                    <a href="/provider/jobs" class="view-all">View All</a>
                </div>
                <div class="active-jobs-list">
                    <div class="active-job-item">
                        <div class="job-info">
                            <h4>Mobile App Development</h4>
                            <p>Client: Sarah Johnson</p>
                            <div class="job-progress">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 70%"></div>
                                </div>
                                <span>70% Complete</span>
                            </div>
                        </div>
                        <div class="job-deadline">
                            <i class="fa-solid fa-calendar"></i>
                            Due: Jun 30, 2023
                        </div>
                    </div>
                    <div class="active-job-item">
                        <div class="job-info">
                            <h4>Home Renovation</h4>
                            <p>Client: Michael Chen</p>
                            <div class="job-progress">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 40%"></div>
                                </div>
                                <span>40% Complete</span>
                            </div>
                        </div>
                        <div class="job-deadline">
                            <i class="fa-solid fa-calendar"></i>
                            Due: Jul 15, 2023
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings Overview -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Earnings Overview</h3>
                    <a href="/provider/earnings" class="view-all">View Details</a>
                </div>
                <div class="earnings-overview">
                    <div class="earnings-item">
                        <span class="earnings-label">This Month</span>
                        <span class="earnings-amount">$1,250</span>
                    </div>
                    <div class="earnings-item">
                        <span class="earnings-label">Last Month</span>
                        <span class="earnings-amount">$980</span>
                    </div>
                    <div class="earnings-item">
                        <span class="earnings-label">Pending Payment</span>
                        <span class="earnings-amount">$420</span>
                    </div>
                </div>
            </div>

            <!-- Recent Messages -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Recent Messages</h3>
                    <a href="/provider/messages" class="view-all">View All</a>
                </div>
                <div class="messages-list">
                    <div class="message-item">
                        <div class="message-avatar">
                            <img src="/assets/img/default-avatar.png" alt="Client Avatar">
                        </div>
                        <div class="message-content">
                            <div class="message-header">
                                <strong>Sarah Johnson</strong>
                                <span class="message-time">2 hours ago</span>
                            </div>
                            <p>Can we schedule a call to discuss the project requirements?</p>
                        </div>
                    </div>
                    <div class="message-item">
                        <div class="message-avatar">
                            <img src="/assets/img/default-avatar.png" alt="Client Avatar">
                        </div>
                        <div class="message-content">
                            <div class="message-header">
                                <strong>Michael Chen</strong>
                                <span class="message-time">1 day ago</span>
                            </div>
                            <p>Great work on the initial design! Looking forward to the next phase.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>



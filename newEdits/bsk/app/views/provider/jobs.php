<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Jobs - ServiceHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../legacy/styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">My Jobs</h1>
            <div class="header-actions">
                <button class="btn btn-primary">Browse Available Jobs</button>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <div class="filter-tab active">All Jobs</div>
            <div class="filter-tab">In Progress</div>
            <div class="filter-tab">Completed</div>
            <div class="filter-tab">Pending Payment</div>
        </div>

        <!-- Jobs List -->
        <div class="jobs-list">
            <div class="job-card">
                <div class="job-header">
                    <div>
                        <h3 class="job-title">Mobile App Development</h3>
                        <p class="job-client">Client: Sarah Johnson</p>
                    </div>
                    <span class="job-status status-in-progress">In Progress</span>
                </div>
                <div class="job-details">
                    <p class="job-description">iOS and Android app for fitness tracking with social features.</p>
                    <div class="job-meta">
                        <div><i class="fa-solid fa-calendar"></i> Deadline: Jul 15, 2023</div>
                        <div><i class="fa-solid fa-dollar-sign"></i> $2,500</div>
                        <div><i class="fa-solid fa-clock"></i> Started: May 20, 2023</div>
                    </div>
                    <div class="job-progress">
                        <label>Progress: 70%</label>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 70%"></div>
                        </div>
                    </div>
                </div>
                <div class="job-actions">
                    <button class="btn btn-outline btn-sm">Update Progress</button>
                    <button class="btn btn-primary btn-sm">Message Client</button>
                </div>
            </div>

            <div class="job-card">
                <div class="job-header">
                    <div>
                        <h3 class="job-title">Home Renovation</h3>
                        <p class="job-client">Client: Michael Chen</p>
                    </div>
                    <span class="job-status status-in-progress">In Progress</span>
                </div>
                <div class="job-details">
                    <p class="job-description">Kitchen and bathroom renovation with modern fixtures.</p>
                    <div class="job-meta">
                        <div><i class="fa-solid fa-calendar"></i> Deadline: Aug 1, 2023</div>
                        <div><i class="fa-solid fa-dollar-sign"></i> $1,800</div>
                        <div><i class="fa-solid fa-clock"></i> Started: Jun 1, 2023</div>
                    </div>
                    <div class="job-progress">
                        <label>Progress: 40%</label>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 40%"></div>
                        </div>
                    </div>
                </div>
                <div class="job-actions">
                    <button class="btn btn-outline btn-sm">Update Progress</button>
                    <button class="btn btn-primary btn-sm">Message Client</button>
                </div>
            </div>

            <div class="job-card">
                <div class="job-header">
                    <div>
                        <h3 class="job-title">E-commerce Website</h3>
                        <p class="job-client">Client: Emma Wilson</p>
                    </div>
                    <span class="job-status status-completed">Completed</span>
                </div>
                <div class="job-details">
                    <p class="job-description">Full e-commerce website with payment gateway integration.</p>
                    <div class="job-meta">
                        <div><i class="fa-solid fa-calendar"></i> Completed: May 25, 2023</div>
                        <div><i class="fa-solid fa-dollar-sign"></i> $1,500</div>
                        <div><i class="fa-solid fa-clock"></i> Started: Apr 10, 2023</div>
                    </div>
                    <div class="job-progress">
                        <label>Progress: 100%</label>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
                <div class="job-actions">
                    <button class="btn btn-outline btn-sm">View Details</button>
                    <button class="btn btn-success btn-sm">Payment Received</button>
                </div>
            </div>

            <div class="job-card">
                <div class="job-header">
                    <div>
                        <h3 class="job-title">Logo Design</h3>
                        <p class="job-client">Client: David Brown</p>
                    </div>
                    <span class="job-status status-pending-payment">Pending Payment</span>
                </div>
                <div class="job-details">
                    <p class="job-description">Brand logo and identity package for restaurant business.</p>
                    <div class="job-meta">
                        <div><i class="fa-solid fa-calendar"></i> Completed: Jun 5, 2023</div>
                        <div><i class="fa-solid fa-dollar-sign"></i> $450</div>
                        <div><i class="fa-solid fa-clock"></i> Started: May 28, 2023</div>
                    </div>
                    <div class="job-progress">
                        <label>Progress: 100%</label>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
                <div class="job-actions">
                    <button class="btn btn-outline btn-sm">Request Payment</button>
                    <button class="btn btn-primary btn-sm">Message Client</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>



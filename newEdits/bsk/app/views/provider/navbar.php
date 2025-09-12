<!-- Header -->
<header>
    <nav class="navbar">
        <a href="../../../index.php" class="logo">
            <span><img src="../../../assets/img/logo.png" width="150px"></span>
        </a>
        <div class="nav-links">
            <a href="dashboard.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </a>
            <a href="jobs.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'jobs.php') ? 'active' : ''; ?>">
                <i class="fas fa-briefcase"></i>
                <span>My Jobs</span>
            </a>
            <a href="job_requests.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'job_requests.php') ? 'active' : ''; ?>">
                <i class="fas fa-inbox"></i>
                <span>Job Requests</span>
            </a>
            <a href="messages.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'messages.php') ? 'active' : ''; ?>">
                <i class="fas fa-comments"></i>
                <span>Messages</span>
            </a>
            <a href="earnings.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'earnings.php') ? 'active' : ''; ?>">
                <i class="fas fa-chart-line"></i>
                <span>Earnings</span>
            </a>
            <a href="profile.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : ''; ?>">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
        </div>
        <div class="user-menu">
            <div class="notification-icon">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">5</span>
            </div>
            <div class="user-profile">
                <div class="user-avatar">SP</div>
                <div class="user-name">Service Provider</div>
            </div>
        </div>
    </nav>
</header>

<!-- Header -->
<header>
    <nav class="navbar">
        <a href="index.php" class="logo">
            <span><img src="servo.png" width="150px"></span>
        </a>
        <div class="nav-links">
            <a href="client-dashboard.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'client-dashboard.php') ? 'active' : ''; ?>">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </a>
            <a href="client-jobs.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'client-jobs.php') ? 'active' : ''; ?>">
                <i class="fas fa-briefcase"></i>
                <span>My Jobs</span>
            </a>
            <a href="chat.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'chat.php') ? 'active' : ''; ?>">
                <i class="fas fa-comments"></i>
                <span>Messages</span>
            </a>
            <a href="client-providers.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'client-providers.php') ? 'active' : ''; ?>">
                <i class="fas fa-users"></i>
                <span>Providers</span>
            </a>
            <a href="client-payments.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'client-payments.php') ? 'active' : ''; ?>">
                <i class="fas fa-credit-card"></i>
                <span>Payments</span>
            </a>
        </div>
        <div class="user-menu">
            <div class="notification-icon">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </div>
            <div class="user-profile">
                <div class="user-avatar">JC</div>
                <div class="user-name">John Client</div>
            </div>
        </div>
    </nav>
</header>

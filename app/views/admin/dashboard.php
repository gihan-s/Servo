<?php
$TopBarHeader = "Dashboard";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- css -->
    <link rel="stylesheet" href="/assets/css/elementStyles.css">
    <link rel="stylesheet" href="/assets/css/gridTemplates.css">

    <link rel="stylesheet" href="/assets/css/admin-main.css">
    <link rel="stylesheet" href="/assets/css/admin-sidebar.css">

    <!-- Javascript -->
    <script src="/assets/js/elementScript.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="/assets/js/admin-script.js" defer></script>

    <?php
        // Prepare chart data for JavaScript
        $chartLabels = [];
        $chartCounts = [];
        foreach ($postActivity as $row) {
            $chartLabels[] = $row['day'];
            $chartCounts[] = (int)$row['total_count'];
        }
        $projectCounts = $projectCounts ?? ['pending' => 0, 'ongoing' => 0, 'completed' => 0, 'rejected' => 0, 'total' => 0];
    ?>
    <script>
        window.dashboardChartData = {
            postActivity: {
                labels: <?= json_encode($chartLabels) ?>,
                counts: <?= json_encode($chartCounts) ?>
            },
            projectCounts: {
                pending:   <?= (int)($projectCounts['pending']   ?? 0) ?>,
                ongoing:   <?= (int)($projectCounts['ongoing']   ?? 0) ?>,
                completed: <?= (int)($projectCounts['completed'] ?? 0) ?>,
                rejected:  <?= (int)($projectCounts['rejected']  ?? 0) ?>
            }
        };
    </script>

    <script src="/assets/js/admin-dashboardCharts.js" defer></script>

</head>

<body>

    <?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/topbar.php'; ?>





    <div class="card-wrapper">

        <div class="container top-card">

            <div>
                <h3>Active Clients</h3>
                <h1><?= number_format($activeClients) ?></h1>
                <span>Currently Using the System</span>
            </div>

            <img src="/assets/img/admin-icon/softwares.jpg" alt="">

        </div>

        <div class="container top-card">

            <div>
                <h3>Active Providers</h3>
                <h1><?= number_format($activeProviders) ?></h1>
                <span>Currently Delivering Services</span>
            </div>

            <img src="/assets/img/admin-icon/invoices.webp" alt="">

        </div>

        <div class="container top-card">

            <div>
                <h3>Total Active Posts</h3>
                <h1><?= number_format($activePosts) ?></h1>
                <span>Currently Published</span>
            </div>

            <img src="/assets/img/admin-icon/sales.webp" alt="">

        </div>

        <div class="container top-card">

            <div>
                <h3>Payment Received</h3>
                <h1><?= number_format($totalPayment, 2) ?></h1>
                <span>Total Completed Payments</span>
            </div>

            <img src="/assets/img/admin-icon/accounting.png" alt="">

        </div>

    </div>



<div class="card-wrapper-2">

    <div>

        <div class="card-wrapper-3">

            <div class="container">
                <canvas id="NoOfSoftwaresChart"></canvas>
            </div>

            <div class="container">
                <div class="dougnut-chart-wrapper">
                    <canvas id="VisitsSummery"></canvas>

                    <h3>Projects Summary</h3>
                </div>

                <table class="visit-table">
                    <tr>
                        <td>Pending Projects</td>
                        <td><?= number_format((int)($projectCounts['pending'] ?? 0)) ?></td>
                    </tr>

                    <tr>
                        <td>Ongoing Projects</td>
                        <td><?= number_format((int)($projectCounts['ongoing'] ?? 0)) ?></td>
                    </tr>

                    <tr>
                        <td>Completed Projects</td>
                        <td><?= number_format((int)($projectCounts['completed'] ?? 0)) ?></td>
                    </tr>

                    <tr>
                        <td>Rejected Projects</td>
                        <td><?= number_format((int)($projectCounts['rejected'] ?? 0)) ?></td>
                    </tr>

                    <tr>
                        <th>Total</th>
                        <th><?= number_format((int)($projectCounts['total'] ?? 0)) ?></th>
                    </tr>

                </table>
            </div>

        </div>

        <div class="card-wrapper-4">

            <div class="container">

                <h3>Most Engaging Providers</h3>

                <?php
                $maxBids = !empty($topBidProviders) ? (int)$topBidProviders[0]['bid_count'] : 1;
                foreach ($topBidProviders as $provider):
                    $progress = $maxBids > 0 ? round(((int)$provider['bid_count'] / $maxBids) * 100) : 0;
                    $name = htmlspecialchars($provider['First_Name'] . ' ' . $provider['Last_Name']);
                    $imgSrc = !empty($provider['Profile_Picture'])
                        ? BASE_URL . '/file/user-files/' . urlencode($provider['Profile_Picture'])
                        : '/assets/img/default-avatar.png';
                ?>
                <div class="customer-progress-card">
                    <img src="<?= $imgSrc ?>" alt="<?= $name ?>">
                    <div>
                        <h4><?= $name ?></h4>
                        <div class="progress-bar" style="--progress: 0%" data-progress="<?= $progress ?>"></div>
                        <span>Bids : <?= number_format((int)$provider['bid_count']) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($topBidProviders)): ?>
                <p>No data available.</p>
                <?php endif; ?>

            </div>

            <div class="container">

                <h3>Most Earned Providers</h3>

                <?php
                $maxEarning = !empty($topEarnProviders) ? (float)$topEarnProviders[0]['Total_Earning'] : 1;
                foreach ($topEarnProviders as $provider):
                    $progress = $maxEarning > 0 ? round(((float)$provider['Total_Earning'] / $maxEarning) * 100) : 0;
                    $name = htmlspecialchars($provider['First_Name'] . ' ' . $provider['Last_Name']);
                    $imgSrc = !empty($provider['Profile_Picture'])
                        ? BASE_URL . '/file/user-files/' . urlencode($provider['Profile_Picture'])
                        : '/assets/img/default-avatar.png';
                ?>
                <div class="customer-progress-card">
                    <img src="<?= $imgSrc ?>" alt="<?= $name ?>">
                    <div>
                        <h4><?= $name ?></h4>
                        <div class="progress-bar" style="--progress: 0%" data-progress="<?= $progress ?>"></div>
                        <span>Amount : Rs. <?= number_format((float)$provider['Total_Earning'], 2) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($topEarnProviders)): ?>
                <p>No data available.</p>
                <?php endif; ?>

            </div>

            <div class="container">


                <h3>Most Spending Clients</h3>

                <?php
                $maxSpent = !empty($topClients) ? (float)$topClients[0]['total_spent'] : 1;
                foreach ($topClients as $client):
                    $progress = $maxSpent > 0 ? round(((float)$client['total_spent'] / $maxSpent) * 100) : 0;
                    $name = htmlspecialchars($client['First_Name'] . ' ' . $client['Last_Name']);
                    $imgSrc = !empty($client['Profile_Picture'])
                        ? BASE_URL . '/file/user-files/' . urlencode($client['Profile_Picture'])
                        : '/assets/img/default-avatar.png';
                ?>
                <div class="customer-progress-card">
                    <img src="<?= $imgSrc ?>" alt="<?= $name ?>">
                    <div>
                        <h4><?= $name ?></h4>
                        <div class="progress-bar" style="--progress: 0%" data-progress="<?= $progress ?>"></div>
                        <span>Amount : Rs. <?= number_format((float)$client['total_spent'], 2) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($topClients)): ?>
                <p>No data available.</p>
                <?php endif; ?>

            </div>

        </div>

    </div>
    
</div>

</body>

</html>

<script>
    const Sidemenu_Active_ID = 'SM_Dashboard';
</script>
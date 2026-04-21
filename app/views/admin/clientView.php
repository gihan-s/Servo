<?php
$TopBarHeader = htmlspecialchars($user['First_Name'] . ' ' . $user['Last_Name']);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client - <?= htmlspecialchars($user['First_Name'] . ' ' . $user['Last_Name']) ?></title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/elementStyles.css">
    <link rel="stylesheet" href="/assets/css/gridTemplates.css">
    <link rel="stylesheet" href="/assets/css/admin-main.css">
    <link rel="stylesheet" href="/assets/css/admin-sidebar.css">
    <link rel="stylesheet" href="/assets/css/providerView.css">

    <!-- JS -->
    <script src="/assets/js/elementScript.js" defer></script>
    <script src="/assets/js/admin-script.js" defer></script>
</head>

<body>

    <?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/topbar.php'; ?>

    <div style="margin-bottom: 16px;">
        <a href="/admin/clients" style="text-decoration:none; color:#555; font-size:14px;">
            <i class="fa-solid fa-arrow-left" style="margin-right:6px;"></i>Back to Clients
        </a>
    </div>

    <!-- Stats Summary -->
    <div class="card-wrapper" id="TopCardsArea" style="margin-bottom:24px;">

        <div class="container top-card">
            <div>
                <h3>Total Posts</h3>
                <h1><?= number_format((int)$stats['total_posts']) ?></h1>
                <span>Posts Created</span>
            </div>
            <img src="/assets/img/admin-icon/pendingImp.webp" alt="">
        </div>

        <div class="container top-card">
            <div>
                <h3>Total Projects</h3>
                <h1><?= number_format((int)$stats['total_projects']) ?></h1>
                <span>Projects Undertaken</span>
            </div>
            <img src="/assets/img/admin-icon/websites.jpg" alt="">
        </div>

        <div class="container top-card">
            <div>
                <h3>Total Spent</h3>
                <h1>Rs. <?= number_format((float)$stats['total_spent'], 2) ?></h1>
                <span>Paid Payments</span>
            </div>
            <img src="/assets/img/admin-icon/customers.jpg" alt="">
        </div>

    </div>


    <div class="provider-wrapper">

        <!-- Personal Details -->
        <div class="container">
            <h4 class="provider-subtitle">Personal Details</h4>

            <div class="table-layout">

                <span class="key">Client ID</span>
                <span>:</span>
                <span class="value"><?= htmlspecialchars($user['Client_ID']) ?></span>

                <span class="key">First Name</span>
                <span>:</span>
                <span class="value"><?= htmlspecialchars($user['First_Name']) ?></span>

                <span class="key">Last Name</span>
                <span>:</span>
                <span class="value"><?= htmlspecialchars($user['Last_Name']) ?></span>

                <span class="key">Gender</span>
                <span>:</span>
                <span class="value"><?= htmlspecialchars($user['Gender'] ?? '—') ?></span>

                <span class="key">Email</span>
                <span>:</span>
                <span class="value"><?= htmlspecialchars($user['Email']) ?></span>

                <span class="key">Contact No</span>
                <span>:</span>
                <span class="value"><?= htmlspecialchars($user['Contact_No'] ?? '—') ?></span>

                <span class="key">Social Link</span>
                <span>:</span>
                <span class="value">
                    <?php if (!empty($user['Social_Link'])): ?>
                        <a href="<?= htmlspecialchars($user['Social_Link']) ?>" target="_blank" rel="noopener noreferrer">
                            <?= htmlspecialchars($user['Social_Link']) ?>
                        </a>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </span>

                <span class="key">Bio</span>
                <span>:</span>
                <span class="value"><?= htmlspecialchars($user['Bio'] ?? '—') ?></span>

                <span class="key">Status</span>
                <span>:</span>
                <span class="value">
                    <?php
                    $chipType = '';
                    switch (strtolower($user['Status'])) {
                        case 'active': $chipType = 'chip-green'; break;
                        case 'banned': $chipType = 'chip-red'; break;
                        default:       $chipType = 'chip-purple';
                    }
                    ?>
                    <span class="<?= $chipType ?>"><?= htmlspecialchars($user['Status']) ?></span>
                </span>

                <span class="key">Online</span>
                <span>:</span>
                <span class="value">
                    <?= $user['Is_Online'] ? '<span class="chip-green">Online</span>' : '<span>Offline</span>' ?>
                </span>

                <span class="key">Last Seen</span>
                <span>:</span>
                <span class="value">
                    <?= $user['Last_Seen'] ? htmlspecialchars(date('M d, Y  h:i A', strtotime($user['Last_Seen']))) : '—' ?>
                </span>

                <span class="key">Registered At</span>
                <span>:</span>
                <span class="value">
                    <?= htmlspecialchars(date('M d, Y  h:i A', strtotime($user['Created_At']))) ?>
                </span>

            </div>
        </div>


        <!-- Profile Picture -->
        <div class="container">
            <h4 class="provider-subtitle">Profile Picture</h4>
            <?php if (!empty($user['Profile_Picture'])): ?>
                <img class="profile-picture"
                     src="<?= BASE_URL ?>/file/user-files/<?= urlencode($user['Profile_Picture']) ?>"
                     alt="Profile Picture">
            <?php else: ?>
                <p style="color:#999; font-size:14px;">No profile picture uploaded.</p>
            <?php endif; ?>
        </div>

    </div>


    <!-- Recent Posts -->
    <h3 class="provider-title" style="margin-top:28px;">Recent Posts</h3>

    <div class="container" style="margin-bottom:32px;">

        <?php if (empty($recentPosts)): ?>
            <p style="color:#999; font-size:14px; padding:12px 0;">No posts found for this client.</p>
        <?php else: ?>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Post ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentPosts as $post): ?>
                            <?php
                            $postChip = '';
                            switch (strtolower($post['Post_Status'])) {
                                case 'active':   $postChip = 'chip-green';  break;
                                case 'draft':    $postChip = 'chip-purple'; break;
                                case 'closed':   $postChip = 'chip-red';    break;
                                default:         $postChip = 'chip-purple';
                            }
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($post['Post_ID']) ?></td>
                                <td><?= htmlspecialchars($post['Title']) ?></td>
                                <td><?= htmlspecialchars($post['Category_Name']) ?></td>
                                <td>Rs. <?= number_format((float)$post['Requesting_Price'], 2) ?> / <?= htmlspecialchars($post['Price_Type']) ?></td>
                                <td><span class="<?= $postChip ?>"><?= htmlspecialchars($post['Post_Status']) ?></span></td>
                                <td><?= htmlspecialchars(date('M d, Y', strtotime($post['Created_At']))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>

<script>
    const Sidemenu_Active_ID = 'SM_Clients';
</script>

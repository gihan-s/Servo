
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Client Dashboard</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/footer.css" />
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css" />
    
</head>

<body>
    <?php // Use filesystem path for includes (BASE_URL is for URLs, not filesystem)
    require_once __DIR__ . '/../../includes/navbar.php'; ?>
    <main class="dashboard-wrapper">
        <header class="dashboard">
            <h1>Welcome Back</h1>
            <div class="subtitle">Your account overview, activity and quick actions in one place.</div>
        </header>

        <!-- Metrics -->
        <section class="metrics-grid" aria-label="Key metrics">
            <!-- Active Posts -->
            <div class="metric-card">
                <div class="metric-icon" style="background:#ecfdf5; color:#008500;"><i class="fas fa-clipboard-list"></i>
                </div>
                <div class="metric-title">Active Posts</div>
                <div class="metric-value">3</div>
                <div class="metric-delta delta-up"><i class="fa-solid fa-arrow-up"></i> +1 this week</div>
            </div>
            <!-- Pending Payments -->
            <div class="metric-card">
                <div class="metric-icon" style="background:#fefce8; color:#b45309;"><i class="fas fa-file-invoice-dollar"></i></div>
                <div class="metric-title">Pending Payments</div>
                <div class="metric-value">2</div>
                <div class="metric-delta" style="color:#b45309;"><i class="fa-solid fa-hourglass"></i> Due soon</div>
            </div>
            <!-- Total Projects -->
            <div class="metric-card">
                <div class="metric-icon" style="background:#eff6ff; color:#008500;"><i class="fas fa-briefcase"></i></div>
                <div class="metric-title">Total Projects</div>
                <div class="metric-value">5</div>
                <div class="metric-delta delta-up"><i class="fa-solid fa-arrow-up"></i> +2</div>
            </div>
            <!-- Total Spent -->
            <div class="metric-card">
                <div class="metric-icon" style="background:#ecfdf5; color:#008500;"><i class="fas fa-sack-dollar"></i>
                </div>
                <div class="metric-title">Total Spent</div>
                <div class="metric-value">$3,450</div>
                <div class="metric-delta delta-up"><i class="fa-solid fa-arrow-up"></i> +5% vs last month</div>
            </div>
        </section>

        <!-- Activity -->
        <section class="activity-grid" aria-label="Recent activity">
            <div class="activity-card">
                <h3><i class="fas fa-file-invoice"></i> Recent Payments</h3>
                <ul class="list">
                    <li class="list-item">
                        <div class="item-top">
                            <div class="item-title">Invoice #INV-10452 • Sprint 3 Development</div>
                            <span class="status-badge status-pending">Pending</span>
                        </div>
                        <div class="item-meta">
                            <span><i class="fa-regular fa-calendar"></i> Sep 02, 2025</span>
                            <span><i class="fa-regular fa-coins"></i> $1,200.00</span>
                            <span><i class="fa-regular fa-credit-card"></i> Visa</span>
                        </div>
                    </li>
                    <li class="list-item">
                        <div class="item-top">
                            <div class="item-title">Invoice #INV-10398 • Brand Pack Delivery</div>
                            <span class="status-badge status-paid">Paid</span>
                        </div>
                        <div class="item-meta">
                            <span><i class="fa-regular fa-calendar"></i> Aug 28, 2025</span>
                            <span><i class="fa-regular fa-coins"></i> $950.00</span>
                            <span><i class="fa-regular fa-credit-card"></i> Stripe</span>
                        </div>
                    </li>
                    <li class="list-item">
                        <div class="item-top">
                            <div class="item-title">Invoice #INV-10321 • QA Cycle Refund</div>
                            <span class="status-badge status-refunded">Refunded</span>
                        </div>
                        <div class="item-meta">
                            <span><i class="fa-regular fa-calendar"></i> Aug 30, 2025</span>
                            <span><i class="fa-regular fa-coins"></i> $420.00</span>
                            <span><i class="fa-regular fa-credit-card"></i> Stripe</span>
                        </div>
                    </li>
                </ul>
                <div style="margin-top:18px; text-align:right;">
                    <button class="link-btn"><i class="fa-regular fa-arrow-right"></i> View All Payments</button>
                </div>
            </div>
            <div class="activity-card">
                <h3><i class="fas fa-clipboard-list"></i> Recent Posts</h3>
                <ul class="list">
                    <li class="list-item">
                        <div class="item-top">
                            <div class="item-title">Full-Stack E‑commerce Platform</div>
                            <span class="status-badge status-open">Open</span>
                        </div>
                        <div class="item-meta">
                            <span><i class="fa-regular fa-calendar"></i> 2d ago</span>
                            <span><i class="fa-regular fa-users"></i> 23 proposals</span>
                            <span><i class="fa-regular fa-hourglass"></i> 5 days left</span>
                        </div>
                    </li>
                    <li class="list-item">
                        <div class="item-top">
                            <div class="item-title">Mobile App UI/UX Design</div>
                            <span class="status-badge status-open">Open</span>
                        </div>
                        <div class="item-meta">
                            <span><i class="fa-regular fa-calendar"></i> 1w ago</span>
                            <span><i class="fa-regular fa-users"></i> 47 proposals</span>
                            <span><i class="fa-regular fa-hourglass"></i> 12 days left</span>
                        </div>
                    </li>
                    <li class="list-item">
                        <div class="item-top">
                            <div class="item-title">Digital Marketing Campaign Plan</div>
                            <span class="status-badge status-draft">Draft</span>
                        </div>
                        <div class="item-meta">
                            <span><i class="fa-regular fa-calendar"></i> 3d ago</span>
                            
                            <span><i class="fa-regular fa-layer-group"></i> Draft</span>
                        </div>
                    </li>
                </ul>
                <div style="margin-top:18px; text-align:right;">
                    <button class="link-btn"><i class="fa-regular fa-arrow-right"></i> Manage Posts</button>
                </div>
            </div>
        </section>

        <!-- Projects Snapshot -->
        <section aria-label="Projects snapshot" style="margin-bottom:56px;">
            <div class="section-header">
                <h2>Active Projects</h2>
                <div class="section-actions">
                    <button class="link-btn"><i class="fa-regular fa-eye"></i> View All</button>
                    <button class="link-btn"><i class="fa-regular fa-plus"></i> New Project</button>
                </div>
            </div>
            <div class="card" style="padding:0; overflow:hidden;">
                <table style="width:100%; border-collapse:separate; border-spacing:0; font-size:14px;">
                    <thead
                        style="background:#f1f5f9; text-align:left; font-size:12px; letter-spacing:.5px; text-transform:uppercase; color:#475569;">
                        <tr>
                            <th style="padding:14px 20px;">Project</th>
                            <th style="padding:14px 20px;">Stage</th>
                            <th style="padding:14px 20px;">Provider</th>
                            <th style="padding:14px 20px;">Budget</th>
                            <th style="padding:14px 20px;">Progress</th>
                            <th style="padding:14px 20px; text-align:right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-top:1px solid #e5e7eb;">
                            <td style="padding:16px 20px; font-weight:600; color:#111827;">E‑commerce Platform</td>
                            <td style="padding:16px 20px;">Sprint 3</td>
                            <td style="padding:16px 20px;">DevStudio Labs</td>
                            <td style="padding:16px 20px; color:#008500; font-weight:600;">$6,500</td>
                            <td style="padding:16px 20px;">
                                <div
                                    style="background:#e2e8f0; height:8px; border-radius:6px; position:relative; overflow:hidden;">
                                    <div
                                        style="background:#008500; width:60%; position:absolute; inset:0; border-radius:6px;">
                                    </div>
                                </div>
                            </td>
                            <td style="padding:16px 20px; text-align:right;">
                                <button class="ghost-btn"><i class="fa-regular fa-eye"></i> Details</button>
                            </td>
                        </tr>
                        <tr style="border-top:1px solid #e5e7eb;">
                            <td style="padding:16px 20px; font-weight:600; color:#111827;">Analytics Dashboard</td>
                            <td style="padding:16px 20px;">QA</td>
                            <td style="padding:16px 20px;">DataCraft</td>
                            <td style="padding:16px 20px; color:#008500; font-weight:600;">$4,800</td>
                            <td style="padding:16px 20px;">
                                <div
                                    style="background:#e2e8f0; height:8px; border-radius:6px; position:relative; overflow:hidden;">
                                    <div
                                        style="background:#008500; width:82%; position:absolute; inset:0; border-radius:6px;">
                                    </div>
                                </div>
                            </td>
                            <td style="padding:16px 20px; text-align:right;">
                                <button class="ghost-btn"><i class="fa-regular fa-eye"></i> Details</button>
                            </td>
                        </tr>
                        <tr style="border-top:1px solid #e5e7eb;">
                            <td style="padding:16px 20px; font-weight:600; color:#111827;">Mobile Fitness App</td>
                            <td style="padding:16px 20px;">Design</td>
                            <td style="padding:16px 20px;">UXPro Studio</td>
                            <td style="padding:16px 20px; color:#008500; font-weight:600;">$3,200</td>
                            <td style="padding:16px 20px;">
                                <div
                                    style="background:#e2e8f0; height:8px; border-radius:6px; position:relative; overflow:hidden;">
                                    <div
                                        style="background:#008500; width:35%; position:absolute; inset:0; border-radius:6px;">
                                    </div>
                                </div>
                            </td>
                            <td style="padding:16px 20px; text-align:right;">
                                <button class="ghost-btn"><i class="fa-regular fa-eye"></i> Details</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Quick Actions -->
        <section aria-label="Quick actions" style="margin-bottom:40px;">
            <div class="section-header">
                <h2>Quick Actions</h2>
            </div>
            <div class="actions-grid">
                <div class="action-card">
                    <h3>Create a New Post</h3>
                    <p>Describe the work you need and start receiving proposals from verified providers.</p>
                    <button class="primary-btn"><i class="fa-regular fa-plus"></i> New Post</button>
                </div>
                <div class="action-card">
                    <h3>Find Providers</h3>
                    <p>Search and filter professionals by skill, rating, price and availability.</p>
                    <button class="primary-btn"><i class="fa-regular fa-magnifying-glass"></i> Search</button>
                </div>
            </div>
        </section>

        <!-- Support / Help -->
        <section aria-label="Help and support" style="margin-bottom:64px;">
            <div class="section-header">
                <h2>Need Help?</h2>
            </div>
            <div class="card" style="display:flex; flex-wrap:wrap; gap:28px; align-items:center;">
                <div style="flex:1 1 320px; min-width:280px;">
                    <h3 style="margin:0 0 10px; font-size:18px; font-weight:700; color:#111827;">We’re here to help</h3>
                    <p style="margin:0 0 16px; color:#475569; line-height:1.6; font-size:14px;">Browse FAQs, open a
                        support ticket or chat with our team about billing, security or project concerns.</p>
                    <div style="display:flex; gap:12px; flex-wrap:wrap;">
                        <button class="ghost-btn"><i class="fa-regular fa-circle-question"></i> FAQs</button>
                        <button class="ghost-btn"><i class="fa-regular fa-message-dots"></i> Contact Support</button>
                        <button class="ghost-btn"><i class="fa-regular fa-shield-check"></i> Trust & Safety</button>
                    </div>
                </div>
                <div
                    style="flex:1 1 260px; min-width:240px; background:#f8fafc; border:1px dashed #cbd5e1; padding:20px 22px; border-radius:14px; display:flex; flex-direction:column; gap:10px;">
                    <div
                        style="font-size:13px; font-weight:600; letter-spacing:.5px; color:#64748b; text-transform:uppercase;">
                        Platform Tips</div>
                    <ul
                        style="margin:0; padding-left:18px; font-size:13px; color:#475569; line-height:1.55; display:flex; flex-direction:column; gap:6px;">
                        <li>Keep project requirements clear & versioned.</li>
                        <li>Use milestone payments for phased work.</li>
                        <li>Archive inactive posts to stay organized.</li>
                        <li>Download receipts for bookkeeping monthly.</li>
                    </ul>
                </div>
            </div>
        </section>
    </main>
    <?php // Footer include fixed to use absolute filesystem path relative to this directory
    require_once __DIR__ . '/../../includes/footer.php'; ?>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Provider Dashboard</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/elementStyles.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/provider-dashboard.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<body>
    <?php // Use filesystem path for includes (BASE_URL is for URLs, not filesystem)
    require_once __DIR__ . '/../../includes/navbar.php'; ?>
    <?php
    $activeProjectsCount = isset($activeProjectsCount) ? (int) $activeProjectsCount : 0;
    $pendingProjectsCount = isset($pendingProjectsCount) ? (int) $pendingProjectsCount : 0;
    $totalBids = isset($totalBids) ? (int) $totalBids : 0;
    $totalRevenue = isset($totalRevenue) ? (float) $totalRevenue : 0.0;
    $earningsChange = isset($earningsChange) ? (float) $earningsChange : 0.0;
    $completedThisMonth = isset($completedThisMonth) ? (int) $completedThisMonth : 0;
    $recentPayments = isset($recentPayments) && is_array($recentPayments) ? $recentPayments : [];
    $recentBids = isset($recentBids) && is_array($recentBids) ? $recentBids : [];
    $activeProjects = isset($activeProjects) && is_array($activeProjects) ? $activeProjects : [];
    $dashboardChartSeries = isset($dashboardChartSeries) && is_array($dashboardChartSeries) ? $dashboardChartSeries : [
        '6m' => ['labels' => [], 'values' => []],
        '12m' => ['labels' => [], 'values' => []],
        '36m' => ['labels' => [], 'values' => []],
        'all' => ['labels' => [], 'values' => []],
    ];
    ?>
    <main class="dashboard-wrapper">
        <header class="dashboard">
            <h1>Welcome Back</h1>
            <div class="subtitle">Your account overview, activity and quick actions in one place.</div>
        </header>

        <!-- Metrics -->
        <section class="metrics-grid" aria-label="Key metrics">
            <!-- Active Projects -->
            <div class="metric-card">
                <div class="metric-icon" style="background:#ecfdf5; color:#008500;"><i class="fas fa-briefcase"></i>
                </div>
                <div class="metric-title">Active Projects</div>
                <div class="metric-value"><?= number_format($activeProjectsCount) ?></div>
                <div class="metric-delta" style="color:#64748b;"><i class="fa-solid fa-layer-group"></i> Ongoing now</div>
            </div>
            <!-- Pending Projects -->
            <div class="metric-card">
                <div class="metric-icon" style="background:#fefce8; color:#b45309;"><i class="fas fa-hourglass"></i></div>
                <div class="metric-title">Pending Projects</div>
                <div class="metric-value"><?= number_format($pendingProjectsCount) ?></div>
                <div class="metric-delta" style="color:#b45309;"><i class="fa-solid fa-hourglass"></i> Awaiting action</div>
            </div>
            <!-- Total Bids -->
            <div class="metric-card">
                <div class="metric-icon" style="background:#eff6ff; color:#008500;"><i class="fas fa-gavel"></i></div>
                <div class="metric-title">Total Bids</div>
                <div class="metric-value"><?= number_format($totalBids) ?></div>
                <div class="metric-delta" style="color:#64748b;"><i class="fa-solid fa-list"></i> Across all posts</div>
            </div>
            <!-- Total Revenue -->
            <div class="metric-card">
                <div class="metric-icon" style="background:#ecfdf5; color:#008500;"><i class="fas fa-sack-dollar"></i>
                </div>
                <div class="metric-title">Total Revenue</div>
                <div class="metric-value">LKR <?= number_format($totalRevenue, 2) ?></div>
                <div class="metric-delta <?= $earningsChange >= 0 ? 'delta-up' : '' ?>">
                    <i class="fa-solid <?= $earningsChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' ?>"></i>
                    <?= number_format(abs($earningsChange), 1) ?>% vs last month
                </div>
            </div>
        </section>

        

        <!-- Revenue Progress Graph -->
        <section aria-label="Revenue progress" style="margin-bottom:48px;">
            <div class="card" style="padding:18px 18px 8px;">
                <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; margin:4px 6px 10px;">
                    <h2 style="margin:0; font-size:18px; font-weight:700; color:#111827;">Your Progress</h2>
                    <div class="range-toggle" role="tablist" aria-label="Range selector">
                        <button class="range-btn" data-range="6m" aria-selected="false">6M</button>
                        <button class="range-btn active" data-range="12m" aria-selected="true">12M</button>
                        <button class="range-btn" data-range="36m" aria-selected="false">3Y</button>
                        <button class="range-btn" data-range="all" aria-selected="false">All</button>
                    </div>
                </div>
                <div class="chart-scroll"><div class="chart-wrap">
                    <svg id="revenueChart" width="100%" height="300" viewBox="0 0 800 300" preserveAspectRatio="xMidYMid meet" role="img" aria-label="Monthly revenue line chart"></svg>
                </div></div>
            </div>
        </section>

        <!-- Activity -->
        <section class="activity-grid" aria-label="Recent activity">
            <div class="activity-card">
                <h3><i class="fas fa-file-invoice"></i> Recent Payments</h3>
                <ul class="list">
                    <?php if (!empty($recentPayments)): ?>
                        <?php foreach ($recentPayments as $payment): ?>
                            <?php
                            $statusRaw = strtolower(trim((string) ($payment['Status'] ?? '')));
                            $statusClass = 'status-pending';
                            if ($statusRaw === 'paid') {
                                $statusClass = 'status-paid';
                            } elseif (in_array($statusRaw, ['refunded', 'refund requested'], true)) {
                                $statusClass = 'status-refunded';
                            }
                            $timeRaw = $payment['Paid_Time'] ?? $payment['Hold_Time'] ?? null;
                            $timeLabel = $timeRaw ? date('M d, Y', strtotime((string) $timeRaw)) : 'N/A';
                            ?>
                            <li class="list-item">
                                <div class="item-top">
                                    <div class="item-title">Invoice #<?= (int) ($payment['Payment_ID'] ?? 0) ?> • <?= htmlspecialchars((string) ($payment['Project_Title'] ?? 'Project')) ?></div>
                                    <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars((string) ($payment['Status'] ?? 'Unknown')) ?></span>
                                </div>
                                <div class="item-meta">
                                    <span><i class="fa-solid fa-calendar"></i> <?= $timeLabel ?></span>
                                    <span><i class="fa-solid fa-coins"></i> LKR <?= number_format((float) ($payment['Amount'] ?? 0), 2) ?></span>
                                    <span><i class="fa-solid fa-check-double"></i> Net after commission</span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-item">
                            <div class="item-top">
                                <div class="item-title">No recent payments</div>
                                <span class="status-badge status-pending">-</span>
                            </div>
                            <div class="item-meta">
                                <span><i class="fa-solid fa-circle-info"></i> Payments appear here after transactions are recorded.</span>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
                <div style="margin-top:18px; text-align:right;">
                    <a class="link-btn" href="<?= BASE_URL ?>/earnings"><i class="fa-solid fa-arrow-right"></i> View All Payments</a>
                </div>
            </div>
            <div class="activity-card">
                <h3><i class="fas fa-clipboard-list"></i> Recent Bids</h3>
                <ul class="list">
                    <?php if (!empty($recentBids)): ?>
                        <?php foreach ($recentBids as $bid): ?>
                            <?php
                            $statusKey = strtolower((string) ($bid['Status_Key'] ?? 'active'));
                            $statusClass = 'status-open';
                            if ($statusKey === 'accepted') {
                                $statusClass = 'status-paid';
                            } elseif (in_array($statusKey, ['rejected', 'closed', 'deleted'], true)) {
                                $statusClass = 'status-refunded';
                            }
                            ?>
                            <li class="list-item">
                                <div class="item-top">
                                    <div class="item-title"><?= htmlspecialchars((string) ($bid['Title'] ?? 'Untitled Post')) ?></div>
                                    <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars((string) ucfirst($statusKey)) ?></span>
                                </div>
                                <div class="item-meta">
                                    <span><i class="fa-solid fa-calendar"></i> <?= htmlspecialchars((string) ($bid['Bid_Date'] ?? 'N/A')) ?></span>
                                    <span><i class="fa-solid fa-coins"></i> <?= htmlspecialchars((string) ($bid['Bid_Amount'] ?? 'LKR 0.00')) ?></span>
                                    <span><i class="fa-solid fa-hourglass"></i> <?= htmlspecialchars((string) ($bid['Timeline'] ?? 'N/A')) ?></span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-item">
                            <div class="item-top">
                                <div class="item-title">No recent bids</div>
                                <span class="status-badge status-open">-</span>
                            </div>
                            <div class="item-meta">
                                <span><i class="fa-solid fa-circle-info"></i> Submit bids from the feed to see them here.</span>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
                <div style="margin-top:18px; text-align:right;">
                    <a class="link-btn" href="<?= BASE_URL ?>/bids"><i class="fa-solid fa-arrow-right"></i> Manage Bids</a>
                </div>
            </div>
        </section>

        <!-- Projects Snapshot -->
        <section aria-label="Projects snapshot" style="margin-bottom:56px;">
            <div class="section-header">
                <h2>Active Projects</h2>
                <div class="section-actions">
                    <a class="link-btn" href="<?= BASE_URL ?>/projects"><i class="fa-solid fa-eye"></i> View All</a>
                    <a class="link-btn" href="<?= BASE_URL ?>/projects"><i class="fa-solid fa-plus"></i> New Project</a>
                </div>
            </div>
            <div class="card" style="padding:0; overflow:hidden;">
                <table style="width:100%; border-collapse:separate; border-spacing:0; font-size:14px;">
                    <thead
                        style="background:#f1f5f9; text-align:left; font-size:12px; letter-spacing:.5px; text-transform:uppercase; color:#475569;">
                        <tr>
                            <th style="padding:14px 20px;">Project</th>
                            <th style="padding:14px 20px;">Stage</th>
                            <th style="padding:14px 20px;">Client</th>
                            <th style="padding:14px 20px;">Budget</th>
                            <th style="padding:14px 20px;">Progress</th>
                            <th style="padding:14px 20px; text-align:right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($activeProjects)): ?>
                            <?php foreach ($activeProjects as $project): ?>
                                <?php $progress = max(0, min(100, (int) ($project['Progress'] ?? 0))); ?>
                                <tr style="border-top:1px solid #e5e7eb;">
                                    <td style="padding:16px 20px; font-weight:600; color:#111827;"><?= htmlspecialchars((string) ($project['Title'] ?? 'Untitled')) ?></td>
                                    <td style="padding:16px 20px;"><?= htmlspecialchars(ucwords(str_replace(['-', '_'], ' ', (string) ($project['Project_Status'] ?? 'ongoing')))) ?></td>
                                    <td style="padding:16px 20px;"><?= htmlspecialchars((string) ($project['Client_Name'] ?? 'Unknown Client')) ?></td>
                                    <td style="padding:16px 20px; color:#008500; font-weight:600;">LKR <?= number_format((float) ($project['Requesting_Price'] ?? 0), 2) ?></td>
                                    <td style="padding:16px 20px;">
                                        <div
                                            style="background:#e2e8f0; height:8px; border-radius:6px; position:relative; overflow:hidden;">
                                            <div
                                                style="background:#008500; width:<?= $progress ?>%; position:absolute; inset:0; border-radius:6px;">
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding:16px 20px; text-align:right;">
                                        <a class="ghost-btn" href="<?= BASE_URL ?>/projects"><i class="fa-solid fa-eye"></i> Details</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr style="border-top:1px solid #e5e7eb;">
                                <td colspan="6" style="padding:18px 20px; color:#64748b;">No active projects found.</td>
                            </tr>
                        <?php endif; ?>
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
                    <a class="primary-btn" href="<?= BASE_URL ?>/feed"><i class="fa-solid fa-plus"></i> New Bid</a>
                </div>
                <div class="action-card">
                    <h3>Manage Projects</h3>
                    <p>Track ongoing deliveries, pending reviews, and completed work in one place.</p>
                    <a class="primary-btn" href="<?= BASE_URL ?>/projects"><i class="fa-solid fa-folder-plus"></i> Projects</a>
                </div>
                <div class="action-card">
                    <h3>Browse Feed</h3>
                    <p>Find matching requests and submit new bids with clear timelines and pricing.</p>
                    <a class="primary-btn" href="<?= BASE_URL ?>/feed"><i class="fa-solid fa-magnifying-glass"></i> Browse</a>
                </div>
                <div class="action-card">
                    <h3>Manage Payments</h3>
                    <p>Review pending invoices, download receipts or request a refund.</p>
                    <a class="primary-btn" href="<?= BASE_URL ?>/earnings"><i class="fa-solid fa-file-invoice-dollar"></i> Payments</a>
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
                        <button class="ghost-btn"><i class="fa-solid fa-circle-question"></i> FAQs</button>
                        <button class="ghost-btn"><i class="fa-solid fa-message-dots"></i> Contact Support</button>
                        <button class="ghost-btn"><i class="fa-solid fa-shield-check"></i> Trust & Safety</button>
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
    <script>
    (function(){
        const svg = document.getElementById('revenueChart');
        if (!svg) return;
        const seriesByRange = <?= json_encode($dashboardChartSeries, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;

        function formatCurrency(n){
            try { return new Intl.NumberFormat('en-LK',{style:'currency',currency:'LKR',maximumFractionDigits:0}).format(n); }
            catch { return 'LKR ' + n.toLocaleString(); }
        }

        const viewH = 300, pad = {l:52, r:16, t:18, b:40};
        const pxPerMonth = 80; // not used when fitting; kept for easy future tweaks
        function setSvgSize(monthCount){
            const wrap = svg.closest('.chart-wrap');
            const containerW = (wrap && wrap.clientWidth) ? wrap.clientWidth : 800;
            const innerW = Math.max(300, containerW - pad.l - pad.r);
            const viewW = innerW + pad.l + pad.r;
            svg.setAttribute('viewBox', `0 0 ${viewW} ${viewH}`);
            svg.setAttribute('width', '100%');
            return {viewW, innerW};
        }

        function render(rangeKey){
            const selected = seriesByRange[rangeKey] || seriesByRange['12m'] || { labels: [], values: [] };
            const labelsArr = Array.isArray(selected.labels) ? selected.labels : [];
            const valuesArr = Array.isArray(selected.values) ? selected.values.map(v => Number(v) || 0) : [];

            if (!valuesArr.length) {
                labelsArr.push('No data');
                valuesArr.push(0);
            }

            const sizes = setSvgSize(valuesArr.length);
            const viewW = sizes.viewW;
            const innerW = sizes.innerW;
            const minY = 0;
            const rawMax = Math.max(...valuesArr);
            const niceMax = niceNumber(rawMax, false);
            const maxY = Math.max(100, niceMax);
            const w = innerW;
            const h = viewH - pad.t - pad.b;

            while(svg.firstChild) svg.removeChild(svg.firstChild);

            // Grid horizontal lines (5)
            const grid = document.createElementNS('http://www.w3.org/2000/svg','g');
            grid.setAttribute('class','chart-grid');
            for(let i=0;i<=5;i++){
                const y = Math.round(pad.t + (h * i/5)) + 0.5; // crisp pixel
                const line = document.createElementNS('http://www.w3.org/2000/svg','line');
                line.setAttribute('x1', pad.l);
                line.setAttribute('x2', pad.l + w);
                line.setAttribute('y1', y);
                line.setAttribute('y2', y);
                grid.appendChild(line);
                // Y axis labels
                const val = Math.round(maxY * (1 - i/5));
                const txt = document.createElementNS('http://www.w3.org/2000/svg','text');
                txt.setAttribute('class','chart-axis');
                txt.setAttribute('x', pad.l - 8);
                txt.setAttribute('y', y + 4);
                txt.setAttribute('text-anchor','end');
                txt.textContent = formatNumber(val);
                grid.appendChild(txt);
            }
            svg.appendChild(grid);

            // Path
            // Fill container width for all ranges (no horizontal scroll)
            const step = (valuesArr.length>1) ? (w / (valuesArr.length - 1)) : 0;
            const points = valuesArr.map((value, i)=>{
                const x = pad.l + (i * step);
                const y = pad.t + h - (h * (value - minY)/(maxY - minY || 1));
                return {x,y};
            });
            const path = document.createElementNS('http://www.w3.org/2000/svg','path');
            const dAttr = points.map((p,i)=> (i?`L${p.x},${p.y}`:`M${p.x},${p.y}`)).join(' ');
            path.setAttribute('d', dAttr);
            path.setAttribute('class','chart-line');
            path.setAttribute('stroke','#4f46e5');
            path.setAttribute('fill','none');
            svg.appendChild(path);

            // Points
            points.forEach((p,i)=>{
                const c = document.createElementNS('http://www.w3.org/2000/svg','circle');
                c.setAttribute('cx', p.x);
                c.setAttribute('cy', p.y);
                c.setAttribute('r', 3.5);
                c.setAttribute('class','chart-point');
                c.setAttribute('data-i', i);
                svg.appendChild(c);
            });

            // X axis labels (every ~month or spaced)
            const xAxis = document.createElementNS('http://www.w3.org/2000/svg','g');
            xAxis.setAttribute('class','chart-axis');
            const targetTicks = 10; // try to keep around 10 labels
            const stepTick = Math.max(1, Math.ceil(labelsArr.length / targetTicks));
            labelsArr.forEach((label,i)=>{
                if (i%stepTick!==0 && i!==labelsArr.length-1) return;
                const x = pad.l + (i * step);
                const y = pad.t + h + 18;
                const txt = document.createElementNS('http://www.w3.org/2000/svg','text');
                txt.setAttribute('x', x);
                txt.setAttribute('y', y);
                txt.setAttribute('text-anchor','middle');
                txt.textContent = String(label || '');
                xAxis.appendChild(txt);
            });
            svg.appendChild(xAxis);
        }

        function niceNumber(max, round=true){
            // nice rounded axis maximum based on 1-2-5 rule
            const exp = Math.floor(Math.log10(max || 1));
            const f = (max || 1) / Math.pow(10, exp);
            let nf;
            if (round){
                if (f < 1.5) nf = 1;
                else if (f < 3) nf = 2;
                else if (f < 7) nf = 5;
                else nf = 10;
            } else {
                if (f <= 1) nf = 1;
                else if (f <= 2) nf = 2;
                else if (f <= 5) nf = 5;
                else nf = 10;
            }
            return nf * Math.pow(10, exp);
        }

        function formatNumber(n){
            try { return new Intl.NumberFormat('en-US', {maximumFractionDigits:0}).format(n); }
            catch { return String(Math.round(n)); }
        }

        // Wire range buttons
        const btns = Array.from(document.querySelectorAll('.range-btn'));
        function setRange(key){
            btns.forEach(b=>{ const on = b.dataset.range===key; b.classList.toggle('active', on); b.setAttribute('aria-selected', String(on)); });
            render(key);
        }
        btns.forEach(b=> b.addEventListener('click', ()=> setRange(b.dataset.range)));
    setRange('12m');
    window.addEventListener('resize', ()=>{ render(document.querySelector('.range-btn.active')?.dataset.range || '12m'); });
    })();
    </script>
</body>

</html>
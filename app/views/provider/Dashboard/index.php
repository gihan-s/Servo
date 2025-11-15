
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Provider Dashboard</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cardList.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/provider-dashboard.css" />
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
            <!-- Active Projects -->
            <div class="metric-card">
                <div class="metric-icon" style="background:#ecfdf5; color:#008500;"><i class="fas fa-briefcase"></i>
                </div>
                <div class="metric-title">Active Projects</div>
                <div class="metric-value">3</div>
                <div class="metric-delta delta-up"><i class="fa-solid fa-arrow-up"></i> +1 this week</div>
            </div>
            <!-- Pending Projects -->
            <div class="metric-card">
                <div class="metric-icon" style="background:#fefce8; color:#b45309;"><i class="fas fa-hourglass"></i></div>
                <div class="metric-title">Pending Projects</div>
                <div class="metric-value">2</div>
                <div class="metric-delta" style="color:#b45309;"><i class="fa-solid fa-hourglass"></i> Awaiting action</div>
            </div>
            <!-- Total Bids -->
            <div class="metric-card">
                <div class="metric-icon" style="background:#eff6ff; color:#008500;"><i class="fas fa-gavel"></i></div>
                <div class="metric-title">Total Bids</div>
                <div class="metric-value">5</div>
                <div class="metric-delta delta-up"><i class="fa-solid fa-arrow-up"></i> +2</div>
            </div>
            <!-- Total Revenue -->
            <div class="metric-card">
                <div class="metric-icon" style="background:#ecfdf5; color:#008500;"><i class="fas fa-sack-dollar"></i>
                </div>
                <div class="metric-title">Total Revenue</div>
                <div class="metric-value">$4,200</div>
                <div class="metric-delta delta-up"><i class="fa-solid fa-arrow-up"></i> +8% vs last month</div>
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
                            <span><i class="fa-regular fa-pen"></i> 75% complete</span>
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
                    <h3>Start a Project</h3>
                    <p>Scope multi-milestone work with structured timelines and payment stages.</p>
                    <button class="primary-btn"><i class="fa-regular fa-folder-plus"></i> New Project</button>
                </div>
                <div class="action-card">
                    <h3>Find Providers</h3>
                    <p>Search and filter professionals by skill, rating, price and availability.</p>
                    <button class="primary-btn"><i class="fa-regular fa-magnifying-glass"></i> Search</button>
                </div>
                <div class="action-card">
                    <h3>Manage Payments</h3>
                    <p>Review pending invoices, download receipts or request a refund.</p>
                    <button class="primary-btn"><i class="fa-regular fa-file-invoice-dollar"></i> Payments</button>
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
    <script>
    (function(){
        const svg = document.getElementById('revenueChart');
        if (!svg) return;
        const ranges = {
            '6m': 6,
            '12m': 12,
            '36m': 36,
            'all': 60 // fallback for demo; replace with total months of data
        };
        // Demo monthly revenue data (last 60 months). Replace with server-provided data if available.
        const now = new Date();
        const dataAll = Array.from({length:60}, (_,i)=>{
            const d = new Date(now.getFullYear(), now.getMonth()- (59-i), 1);
            // Generate a gentle uptrend with some noise
            const base = 1200 + i*25;
            const val = Math.max(0, Math.round(base + (Math.sin(i/3)*120) + (Math.random()*80-40)));
            return {date: d, value: val};
        });

        function monthLabel(d){
            return d.toLocaleString('en-US', {month:'short'});
        }
        function yearShort(d){ return String(d.getFullYear()).slice(-2); }
        function formatCurrency(n){
            try { return new Intl.NumberFormat('en-US',{style:'currency',currency:'USD',maximumFractionDigits:0}).format(n); }
            catch { return '$' + n.toLocaleString(); }
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
            const count = ranges[rangeKey];
            let arr = dataAll.slice(-count);
            if (rangeKey==='all') arr = dataAll.slice();

            const sizes = setSvgSize(arr.length);
            const viewW = sizes.viewW;
            const innerW = sizes.innerW;
            const minY = 0;
            const rawMax = Math.max(...arr.map(d=>d.value));
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
            const step = (arr.length>1) ? (w / (arr.length - 1)) : 0;
            const points = arr.map((d, i)=>{
                const x = pad.l + (i * step);
                const y = pad.t + h - (h * (d.value - minY)/(maxY - minY || 1));
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
            const stepTick = Math.max(1, Math.ceil(arr.length / targetTicks));
            arr.forEach((d,i)=>{
                if (i%stepTick!==0 && i!==arr.length-1) return;
                const x = pad.l + (i * step);
                const y = pad.t + h + 18;
                const txt = document.createElementNS('http://www.w3.org/2000/svg','text');
                txt.setAttribute('x', x);
                txt.setAttribute('y', y);
                txt.setAttribute('text-anchor','middle');
                txt.textContent = `${monthLabel(arr[i].date)} ${yearShort(arr[i].date)}`;
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
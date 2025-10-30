
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Servo | Notifications</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css" />
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css" />
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #f8fafc;
            margin: 0;
        }

        .notif-page-wrapper {
            max-width: 1280px;
            margin: 0 auto;
            padding: 42px 24px 90px;
            display: flex;
            gap: 42px;
            align-items: flex-start;
        }

        .notif-col {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 26px;
        }

        .notif-panel {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 26px 26px 30px;
            position: relative;
            box-shadow: 0 4px 10px -4px rgba(0, 0, 0, .08);
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        .notif-panel::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #008500;
            border-radius: 18px 18px 0 0;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
        }

        .panel-header h1 {
            font-size: 30px;
            font-weight: 800;
            margin: 0;
            color: #111827;
            letter-spacing: .5px;
        }

        .panel-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #111827;
            font-weight: 600;
            padding: 10px 16px;
            border-radius: 12px;
            font-size: 13px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: .25s;
        }

        .btn:hover {
            border-color: #008500;
            color: #008500;
        }

        .btn-primary {
            background: #008500;
            border-color: #008500;
            color: #fff;
        }

        .btn-primary:hover {
            box-shadow: 0 8px 22px -6px rgba(0, 133, 0, .45);
        }

        .tabs {
            display: flex;
            gap: 10px;
            background: #f1f5f9;
            padding: 6px;
            border-radius: 14px;
            width: max-content;
        }

        .tab {
            border: none;
            background: transparent;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 13px;
            border-radius: 10px;
            cursor: pointer;
            color: #475569;
        }

        .tab.active {
            background: #008500;
            color: #fff;
        }

        .search-row {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .search-row input {
            flex: 1;
            min-width: 240px;
            background: #fff;
            border: 1px solid #e2e8f0;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 14px;
        }

        .search-row input:focus {
            outline: 2px solid #008500;
            outline-offset: 2px;
        }

        .notif-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            max-height: calc(100vh - 280px);
            overflow: auto;
            padding-right: 4px;
        }

        .notif-item {
            display: flex;
            gap: 16px;
            padding: 16px 18px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            position: relative;
            cursor: pointer;
            transition: .25s;
        }

        .notif-item.unread {
            background: #f1fdf5;
            border-color: #bdeeca;
        }

        .notif-item:hover {
            background: #f8fafc;
        }

        .n-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #008500;
        }

        .n-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .n-title {
            font-size: 15px;
            font-weight: 600;
            line-height: 1.35;
            color: #111827;
        }

        .n-meta {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #64748b;
            display: flex;
            gap: 14px;
        }

        .n-tags {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .badge {
            background: #008500;
            color: #fff;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .4px;
        }

        .badge-outline {
            background: #f1f5f9;
            color: #008500;
            border: 1px solid #cfead5;
        }

        .side-summary {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 26px 26px 30px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            position: sticky;
            top: 110px;
            max-height: calc(100vh - 140px);
            overflow: auto;
        }

        .side-summary h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
        }

        .stat {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
            font-weight: 600;
        }

        .stat:last-child {
            border-bottom: none;
        }

        .filters {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .filter-group label {
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: .6px;
            color: #64748b;
        }

        .filter-group select {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 13px;
        }

        .empty {
            text-align: center;
            padding: 50px 20px;
            color: #64748b;
            font-size: 14px;
        }

        .empty h4 {
            margin: 0 0 10px;
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }

        .loading {
            text-align: center;
            padding: 40px 20px;
        }

        .loading .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #e2e8f0;
            border-top-color: #008500;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 14px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .notif-list::-webkit-scrollbar {
            width: 10px;
        }

        .notif-list::-webkit-scrollbar-track {
            background: transparent;
        }

        .notif-list::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 20px;
        }

        .notif-list::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        @media (max-width:1080px) {
            .notif-page-wrapper {
                flex-direction: column;
            }

            .side-summary {
                position: relative;
                top: auto;
                max-height: none;
                max-width: 100%;
            }

            .notif-panel {
                width: 100%;
            }

            .notif-list .notif-item {
                width: 100%;
                box-sizing: border-box;
            }

            .notif-col {
                width: 100%;
            }
        }

        @media (max-width:640px) {
            .notif-page-wrapper {
                padding: 34px 18px 80px;
            }

            .panel-header h1 {
                font-size: 26px;
            }

            .n-title {
                font-size: 14px;
            }
        }

        /* Overrides to enlarge summary sidebar */
        .side-summary {
            flex: 0 0 420px;
            max-width: 420px;
            padding: 32px 30px 36px;
            width: 100%;
        }


        @media (max-width:1080px) {
            .side-summary {
                flex: 1 1 auto;
                max-width: 100%;
                width: 100%;
            }
        }

        .notif-col {
            flex: 1 1 auto;
        }
    </style>
</head>

<body>
    <?php require_once __DIR__ . '/../../includes/navbar.php'; ?>
    <main class="notif-page-wrapper">
        <section class="notif-col">
            <div class="notif-panel" id="notifPanelFull">
                <div class="panel-header">
                    <h1>Notifications</h1>
                    <div class="panel-actions">
                        <div class="tabs" id="notifTabsFull">
                            <button class="tab active" data-tab="all">All</button>
                            <button class="tab" data-tab="unread">Unread</button>
                        </div>
                        <button class="btn" id="markAllFull"><i class="fa-solid fa-check-double"></i> Mark all
                            read</button>
                        <button class="btn-primary btn" id="refreshBtn"><i class="fa-solid fa-rotate"></i>
                            Refresh</button>
                    </div>
                </div>
                <div class="search-row">
                    <input type="text" id="searchInput" placeholder="Search notifications..." />
                </div>
                <div class="notif-list" id="notifListFull"></div>
            </div>
        </section>
        <aside class="side-summary">
            <h3>Summary</h3>
            <div class="stats">
                <div class="stat"><span>Total</span><span id="statTotal">0</span></div>
                <div class="stat"><span>Unread</span><span id="statUnread">0</span></div>
                <div class="stat"><span>Payments</span><span id="statPayment">0</span></div>
                <div class="stat"><span>Bids</span><span id="statBid">0</span></div>
                <div class="stat"><span>Messages</span><span id="statMessage">0</span></div>
            </div>
            <div class="filters">
                <div class="filter-group">
                    <label for="filterType">Type</label>
                    <select id="filterType">
                        <option value="all">All</option>
                        <option value="payment">Payment</option>
                        <option value="bid">Bid</option>
                        <option value="message">Message</option>
                        <option value="milestone">Milestone</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filterStatus">Status</label>
                    <select id="filterStatus">
                        <option value="all">All</option>
                        <option value="unread">Unread</option>
                        <option value="read">Read</option>
                    </select>
                </div>
            </div>
        </aside>
    </main>
    <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
    <script>
        (function () {
            const listEl = document.getElementById('notifListFull');
            const tabs = [...document.querySelectorAll('#notifTabsFull .tab')];
            const markAllBtn = document.getElementById('markAllFull');
            const refreshBtn = document.getElementById('refreshBtn');
            const searchInput = document.getElementById('searchInput');
            const filterType = document.getElementById('filterType');
            const filterStatus = document.getElementById('filterStatus');
            const stats = { total: document.getElementById('statTotal'), unread: document.getElementById('statUnread'), payment: document.getElementById('statPayment'), bid: document.getElementById('statBid'), message: document.getElementById('statMessage') };
            let currentTab = 'all';
            let data = [
                { id: 1, title: 'Payment of $250 released for Project Alpha.', time: '2m', type: 'payment', unread: true, tags: ['Release', 'Milestone'] },
                { id: 2, title: 'New bid received on your post: UI Revamp', time: '15m', type: 'bid', unread: true, tags: ['Bid', 'Project'] },
                { id: 3, title: 'DevStudio Labs sent you a message.', time: '38m', type: 'message', unread: false, tags: ['Chat'] },
                { id: 4, title: 'Contract milestone approved.', time: '1h', type: 'milestone', unread: false, tags: ['Milestone'] },
                { id: 5, title: 'Payment of $900 released for Project Beta.', time: '2h', type: 'payment', unread: true, tags: ['Release'] },
            ];
            function icon(t) {
                const map = { payment: 'fa-credit-card', bid: 'fa-gavel', message: 'fa-comments', milestone: 'fa-flag-checkered' }; return `<i class="fa-solid ${map[t] || 'fa-bell'}"></i>`;
            }
            function escapeHTML(s) { return s.replace(/[&<>"']/g, c => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", "\"": "&quot;" }[c] || c)); }
            function render() {
                if (!listEl) return; listEl.innerHTML = '';
                let filtered = data.filter(n => currentTab === 'all' || (currentTab === 'unread' && n.unread));
                const q = searchInput.value.trim().toLowerCase();
                if (q) filtered = filtered.filter(n => n.title.toLowerCase().includes(q));
                if (filterType.value !== 'all') filtered = filtered.filter(n => n.type === filterType.value);
                if (filterStatus.value === 'unread') filtered = filtered.filter(n => n.unread); else if (filterStatus.value === 'read') filtered = filtered.filter(n => !n.unread);
                if (!filtered.length) { listEl.innerHTML = '<div class="empty"><h4>No notifications</h4><p>Try adjusting filters.</p></div>'; updateStats(); return; }
                filtered.forEach(n => {
                    const div = document.createElement('div');
                    div.className = 'notif-item ' + (n.unread ? 'unread' : '');
                    div.innerHTML = `<div class='n-icon'>${icon(n.type)}</div><div class='n-body'><div class='n-title'>${escapeHTML(n.title)}</div><div class='n-meta'><span>${n.time}</span><span>${n.unread ? 'Unread' : 'Read'}</span></div><div class='n-tags'>${(n.tags || []).map(t => `<span class='badge badge-outline'>${escapeHTML(t)}</span>`).join('')}</div></div>`;
                    div.addEventListener('click', () => { if (n.unread) { n.unread = false; render(); } });
                    listEl.appendChild(div);
                });
                updateStats();
            }
            function updateStats() {
                stats.total.textContent = data.length;
                stats.unread.textContent = data.filter(n => n.unread).length;
                stats.payment.textContent = data.filter(n => n.type === 'payment').length;
                stats.bid.textContent = data.filter(n => n.type === 'bid').length;
                stats.message.textContent = data.filter(n => n.type === 'message').length;
            }
            tabs.forEach(t => t.addEventListener('click', () => { tabs.forEach(x => x.classList.remove('active')); t.classList.add('active'); currentTab = t.dataset.tab; render(); }));
            markAllBtn.addEventListener('click', () => { data.forEach(n => n.unread = false); render(); });
            refreshBtn.addEventListener('click', () => { refreshBtn.disabled = true; refreshBtn.innerHTML = '<i class="fa-solid fa-rotate fa-spin"></i> Refreshing'; setTimeout(() => { data.unshift({ id: Date.now(), title: 'Sample new notification ' + Date.now(), time: 'Just now', type: 'message', unread: true, tags: ['New'] }); refreshBtn.innerHTML = '<i class="fa-solid fa-rotate"></i> Refresh'; refreshBtn.disabled = false; render(); }, 1200); });
            [searchInput, filterType, filterStatus].forEach(el => el.addEventListener('input', render));
            render();
        })();
    </script>
</body>

</html>
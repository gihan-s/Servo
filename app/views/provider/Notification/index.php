<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Servo | Notifications</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/notification.css" />
    
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
                { id: 2, title: 'New project invitation matches your skills: UI Revamp', time: '15m', type: 'bid', unread: true, tags: ['Bid', 'Project'] },
                { id: 3, title: 'Project owner sent you a message.', time: '38m', type: 'message', unread: false, tags: ['Chat'] },
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


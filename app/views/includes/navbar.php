<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/navbar.css">
<!-- Header -->
<header>
    <nav class="navbar" role="navigation" aria-label="Main navigation">
        <a href="<?= BASE_URL ?>" class="logo" aria-label="Home">
            <span><img src="<?= BASE_URL ?>/assets/img/logo.png" width="150" alt="Logo"></span>
        </a>
        <div class="nav-primary" id="navPrimary">
            <button class="nav-hamburger" id="navHamburger" aria-label="Menu" aria-expanded="false"
                aria-controls="drawerMenu">
                <span></span><span></span><span></span>
            </button>
            <div class="nav-links" id="navLinks">
                <?php
                $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
                // Remove query, normalize slashes
                $uri = preg_replace('#/{2,}#','/',$uri);
                // Strip trailing slash (except root)
                $uri = rtrim($uri, '/');
                if ($uri === '') { $uri = '/'; }
                // Compute base path portion from BASE_URL (e.g., http://host/bsk) → '/bsk'
                $basePath = parse_url(BASE_URL, PHP_URL_PATH) ?? '';
                if ($basePath && $basePath !== '/' && str_starts_with($uri, $basePath)) {
                    $uriNoBase = substr($uri, strlen($basePath));
                    if ($uriNoBase === '') { $uriNoBase = '/'; }
                } else {
                    $uriNoBase = $uri;
                }
                /**
                 * Determine active class.
                 * - Exact path match after removing BASE path.
                 * - Also treat nested pages (e.g., /client/profile/settings) as active for /client/profile.
                 */
                function active(string $path, string $currentRelative): string {
                    if ($currentRelative === $path) return 'active';
                    if ($path !== '/' && str_starts_with($currentRelative, $path . '/')) return 'active';
                    return '';
                }
                // Provide helper to build link hrefs consistently
                function hlink(string $path): string { return BASE_URL . $path; }
                ?>
                <a href="<?= hlink('/provider/dashboard') ?>"
                    class="<?= active('/provider/dashboard', $uriNoBase) ?>">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= hlink('/provider/jobs') ?>"
                    class="<?= active('/provider/jobs', $uriNoBase) ?>">
                    <i class="fas fa-briefcase"></i>
                    <span>Projects</span>
                </a>
                <a href="<?= hlink('/provider/providers') ?>" class="<?= active('/provider/providers', $uriNoBase) ?>">
                    <i class="fa-solid fa-users"></i>
                    <span>Providers</span>
                </a>
                <a href="<?= hlink('/provider/posts') ?>"
                    class="<?= active('/provider/posts', $uriNoBase) ?>">
                    <i class="fa-solid fa-layer-plus"></i>
                    <span>Posts</span>
                </a>
                <a href="<?= hlink('/provider/payments') ?>"
                    class="<?= active('/provider/payments', $uriNoBase) ?>">
                    <i class="fa-solid fa-credit-card"></i><span>Payments</span></a>
            </div>
        </div>
        <div class="user-menu" id="userMenu">
            <a href="<?= hlink('/provider/messages') ?>"
                class="<?= active('/provider/messages', $uriNoBase) ?>"
                aria-label="Messages">
                <i class="fas fa-comments"></i>
            </a>
            <button class="notification-icon" id="notifToggle" aria-label="Notifications" aria-haspopup="true"
                aria-expanded="false">
                <i class="fas fa-bell"></i>
                <span class="notification-badge" id="notifBadge">3</span>
            </button>
            <div class="user-profile" onclick="window.location.href='<?= hlink('/provider/profile') ?>'" role="button" tabindex="0"
                aria-label="Profile">
                <div class="user-avatar">JC</div>
                <div class="user-name">John Client</div>
            </div>
            <div class="notif-popover" id="notifPopover" role="dialog" aria-label="Notifications" aria-modal="false">
                <div class="notif-header">
                    <h3>Notifications</h3>
                    <button class="notif-close" id="notifClose" aria-label="Close notifications">&times;</button>
                </div>
                <div class="notif-tabs">
                    <button class="n-tab active" data-tab="all">All</button>
                    <button class="n-tab" data-tab="unread">Unread</button>
                </div>
                <div class="notif-list" id="notifList"></div>
                <div class="notif-footer">
                    <button class="mark-all" id="markAllBtn">Mark all read</button>
                    <a href="<?= BASE_URL ?>/client/notifications" class="view-all">View all</a>
                </div>
            </div>
        </div>
        <div class="drawer" id="drawerMenu" aria-hidden="true">
            <div class="drawer-header">
                <span class="drawer-title">Menu</span>
                <button class="drawer-close" id="drawerClose" aria-label="Close menu">&times;</button>
            </div>
            <div class="drawer-section" id="drawerLinks"><!-- cloned links --></div>
            <div class="drawer-section divider"></div>
            <div class="drawer-section">
                <a href="<?= hlink('/client/messages') ?>"
                    class="drawer-link <?= active('/client/messages', $uriNoBase) ?>"><i
                        class="fas fa-comments"></i> Messages</a>
                <a href="<?= hlink('/client/profile') ?>"
                    class="drawer-link <?= active('/client/profile', $uriNoBase) ?>"><i
                        class="fas fa-user-circle"></i> Profile</a>
        <a href="<?= hlink('/client/notifications') ?>" class="drawer-link <?= active('/client/notifications', $uriNoBase) ?>"><i class="fas fa-bell"></i> Notifications <span
            class="badge">3</span></a>
                <a href="<?= hlink('/logout') ?>" class="drawer-link"><i class="fas fa-arrow-right-from-bracket"></i> Logout</a>
            </div>
        </div>
        <div class="drawer-overlay" id="drawerOverlay" tabindex="-1" aria-hidden="true"></div>
    </nav>
</header>
<style>
    /* --- Responsive Navbar (Upwork-like) --- */
    
</style>
<script>
    // JS for responsive navbar: overflow management & drawer
    (function () {
        const linksContainer = document.getElementById('navLinks');
        const overflowWrap = document.getElementById('navOverflow');
        const overflowMenu = document.getElementById('overflowMenu');
        const trigger = document.getElementById('overflowTrigger');
        const hamburger = document.getElementById('navHamburger');
        const drawer = document.getElementById('drawerMenu');
        const drawerClose = document.getElementById('drawerClose');
        const drawerOverlay = document.getElementById('drawerOverlay');
        const drawerLinks = document.getElementById('drawerLinks');
        // Notifications
        const notifToggle = document.getElementById('notifToggle');
        const notifPopover = document.getElementById('notifPopover');
        const notifClose = document.getElementById('notifClose');
        const notifList = document.getElementById('notifList');
        const markAllBtn = document.getElementById('markAllBtn');
        const notifTabs = () => [...document.querySelectorAll('.notif-tabs .n-tab')];
        const notifData = [
            { id: 1, title: 'Payment of $250 released for Project Alpha.', time: '2m', type: 'payment', unread: true },
            { id: 2, title: 'New bid received on your post: UI Revamp', time: '15m', type: 'bid', unread: true },
            { id: 3, title: 'DevStudio Labs sent you a message.', time: '38m', type: 'message', unread: false },
            { id: 4, title: 'Contract milestone approved.', time: '1h', type: 'milestone', unread: false },
        ];
        function renderNotifications(filter = 'all') {
            if (!notifList) return;
            notifList.innerHTML = '';
            const items = notifData.filter(n => filter === 'all' || (filter === 'unread' && n.unread));
            if (!items.length) {
                notifList.innerHTML = '<div class="notif-empty"><h4>No notifications</h4><p>You\'re all caught up.</p></div>'; return;
            }
            items.forEach(n => {
                const div = document.createElement('div');
                div.className = 'notif-item ' + (n.unread ? 'unread' : '');
                div.dataset.id = n.id;
                div.innerHTML = `<div class=\"notif-icon\">${iconFor(n.type)}</div><div class=\"notif-content\"><div class=\"notif-title\">${escapeHTML(n.title)}</div><div class=\"notif-meta\"><span>${n.time}</span><span>${n.unread ? 'Unread' : 'Read'}</span></div></div>`;
                div.addEventListener('click', () => { if (n.unread) { n.unread = false; div.classList.remove('unread'); updateBadge(); renderNotifications(currentTab); } });
                notifList.appendChild(div);
            });
        }
        function iconFor(type) {
            switch (type) {
                case 'payment': return '<i class="fa-solid fa-credit-card"></i>';
                case 'bid': return '<i class="fa-solid fa-gavel"></i>';
                case 'message': return '<i class="fa-solid fa-comments"></i>';
                case 'milestone': return '<i class="fa-solid fa-flag-checkered"></i>';
                default: return '<i class="fa-solid fa-bell"></i>';
            }
        }
        function updateBadge() {
            const unread = notifData.filter(n => n.unread).length;
            const badge = document.getElementById('notifBadge');
            if (!badge) return;
            badge.textContent = unread; badge.style.display = unread ? 'flex' : 'none';
        }
        let currentTab = 'all';
        function openNotif() { if (!notifPopover) return; notifPopover.classList.add('open'); notifToggle.setAttribute('aria-expanded', 'true'); positionNotif(); }
        function closeNotif() { if (!notifPopover) return; notifPopover.classList.remove('open'); notifToggle.setAttribute('aria-expanded', 'false'); }
        function toggleNotif() { if (!notifPopover) return; (notifPopover.classList.contains('open')) ? closeNotif() : openNotif(); }
        function positionNotif() {
            if (!notifPopover || !notifPopover.classList.contains('open')) return;
            // For desktop we rely on CSS: top: calc(100% + 2px); right:0 inside .user-menu (position:relative)
            // Ensure any inline overrides from previous version are cleared
            if (window.innerWidth > 760) {
                notifPopover.style.left = 'auto';
                notifPopover.style.right = '0';
                notifPopover.style.top = 'calc(100% + 2px)';
            } else {
                // Mobile (fixed) style already defined in media query; leave untouched
                notifPopover.style.top = '';
                notifPopover.style.right = '';
                notifPopover.style.left = '';
            }
        }
        notifToggle && notifToggle.addEventListener('click', e => { e.stopPropagation(); toggleNotif(); });
        notifClose && notifClose.addEventListener('click', closeNotif);
        document.addEventListener('click', e => { if (!notifPopover || !notifPopover.classList.contains('open')) return; if (!notifPopover.contains(e.target) && e.target !== notifToggle && !notifToggle.contains(e.target)) closeNotif(); });
        window.addEventListener('keydown', e => { if (e.key === 'Escape' && notifPopover && notifPopover.classList.contains('open')) closeNotif(); });
        window.addEventListener('resize', () => { if (notifPopover && notifPopover.classList.contains('open')) positionNotif(); });
        notifTabs().forEach(tab => tab.addEventListener('click', () => { notifTabs().forEach(t => t.classList.remove('active')); tab.classList.add('active'); currentTab = tab.dataset.tab; renderNotifications(currentTab); }));
        markAllBtn && markAllBtn.addEventListener('click', () => { notifData.forEach(n => n.unread = false); updateBadge(); renderNotifications(currentTab); });
        function escapeHTML(s) { return s.replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', '\'': '&#39;' }[c])); }
        renderNotifications(); updateBadge();

        function distribute() {
            if (window.innerWidth > 1150) { // reset
                overflowWrap.hidden = true; overflowMenu.innerHTML = '';
                [...linksContainer.querySelectorAll('.overflow-clone')].forEach(el => { el.classList.remove('overflow-clone'); });
                return;
            }
            // show overflow container
            overflowWrap.hidden = false;
            // measure available width for links area
            const maxWidth = window.innerWidth - 520; // heuristic subtract logo + user menu
            let used = 0;
            const linkEls = [...linksContainer.children];
            overflowMenu.innerHTML = '';
            linkEls.forEach(a => { a.style.display = ''; });
            linkEls.forEach(a => {
                const w = a.getBoundingClientRect().width + 20;
                used += w;
                if (used > maxWidth) {
                    a.classList.add('overflow-clone');
                    // hide original
                    a.style.display = 'none';
                    const clone = a.cloneNode(true); clone.className = 'overflow-item' + (a.classList.contains('active') ? ' active' : ''); overflowMenu.appendChild(clone);
                }
            });
            trigger.setAttribute('aria-expanded', 'false'); overflowMenu.style.display = 'none';
        }

        function toggleOverflow() {
            const open = overflowMenu.style.display === 'flex';
            if (open) { overflowMenu.style.display = 'none'; trigger.setAttribute('aria-expanded', 'false'); }
            else { overflowMenu.style.display = 'flex'; }
        }
        trigger && trigger.addEventListener('click', toggleOverflow);
        window.addEventListener('resize', () => { distribute(); if (window.innerWidth > 760 && drawer.classList.contains('open')) closeDrawer(); });
        window.addEventListener('click', e => { if (trigger && !trigger.contains(e.target) && !overflowMenu.contains(e.target)) { overflowMenu.style.display = 'none'; trigger.setAttribute('aria-expanded', 'false'); } });

        // Drawer
        function openDrawer() { drawer.classList.add('open'); drawerOverlay.classList.add('show'); hamburger.setAttribute('aria-expanded', 'true'); document.body.style.overflow = 'hidden'; cloneLinksToDrawer(); }
        function closeDrawer() { drawer.classList.remove('open'); drawerOverlay.classList.remove('show'); hamburger.setAttribute('aria-expanded', 'false'); document.body.style.overflow = ''; }
        function cloneLinksToDrawer() { drawerLinks.innerHTML = '';[...linksContainer.querySelectorAll('a')].forEach(a => { const c = a.cloneNode(true); c.classList.remove('overflow-item'); c.classList.add('drawer-link'); drawerLinks.appendChild(c); }); }
        hamburger && hamburger.addEventListener('click', () => { drawer.classList.contains('open') ? closeDrawer() : openDrawer(); });
        drawerClose && drawerClose.addEventListener('click', closeDrawer);
        drawerOverlay && drawerOverlay.addEventListener('click', closeDrawer);
        window.addEventListener('keydown', e => { if (e.key === 'Escape' && drawer.classList.contains('open')) closeDrawer(); });
        drawer.addEventListener('click', e => { if (e.target.matches('a')) closeDrawer(); });

        // Initialize after load to ensure widths measurable
        window.addEventListener('load', () => { distribute(); positionNotif(); });
        distribute();
    })();
</script>
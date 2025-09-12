<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
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
                <a href="../Dashboard"
                    class="<?php echo ($_SERVER['PHP_SELF'] == '/bsk/app/views/client/Dashboard/index.php') ? 'active' : ''; ?>">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
                <a href="../Projects"
                    class="<?php echo ($_SERVER['PHP_SELF'] == '/bsk/app/views/client/Projects/index.php') ? 'active' : ''; ?>">
                    <i class="fas fa-briefcase"></i>
                    <span>Projects</span>
                </a>
                <a href="../Providers"
                    class="<?php echo ($_SERVER['PHP_SELF'] == '/bsk/app/views/client/Providers/index.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-users"></i>
                    <span>Providers</span>
                </a>
                <a href="../Posts"
                    class="<?php echo ($_SERVER['PHP_SELF'] == '/bsk/app/views/client/Posts/index.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-layer-plus"></i>
                    <span>Posts</span>
                </a>
                <a href="../Payments"
                    class="<?php echo ($_SERVER['PHP_SELF'] == '/bsk/app/views/client/Payments/index.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-credit-card"></i><span>Payments</span></a>
            </div>
        </div>
        <div class="user-menu" id="userMenu">
            <a href="../Messages"
                class="<?php echo (basename($_SERVER['PHP_SELF']) == 'messages.php') ? 'active' : ''; ?>"
                aria-label="Messages">
                <i class="fas fa-comments"></i>
            </a>
            <button class="notification-icon" id="notifToggle" aria-label="Notifications" aria-haspopup="true"
                aria-expanded="false">
                <i class="fas fa-bell"></i>
                <span class="notification-badge" id="notifBadge">3</span>
            </button>
            <div class="user-profile" onclick="window.location.href='../Profile'" role="button" tabindex="0"
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
                    <a href="../Notification" class="view-all">View all</a>
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
                <a href="../Messages"
                    class="drawer-link <?php echo (basename($_SERVER['PHP_SELF']) == 'messages.php') ? 'active' : ''; ?>"><i
                        class="fas fa-comments"></i> Messages</a>
                <a href="../Profile"
                    class="drawer-link <?php echo (strpos($_SERVER['PHP_SELF'], '/Profile/') !== false) ? 'active' : ''; ?>"><i
                        class="fas fa-user-circle"></i> Profile</a>
                <a href="#" class="drawer-link"><i class="fas fa-bell"></i> Notifications <span
                        class="badge">3</span></a>
                <a href="/logout" class="drawer-link"><i class="fas fa-arrow-right-from-bracket"></i> Logout</a>
            </div>
        </div>
        <div class="drawer-overlay" id="drawerOverlay" tabindex="-1" aria-hidden="true"></div>
    </nav>
</header>
<style>
    /* --- Responsive Navbar (Upwork-like) --- */
    :root {
        --nav-height: 70px;
    }

    .navbar {
        gap: 24px;
    }

    .nav-primary {
        display: flex;
        align-items: center;
        flex: 1;
        gap: 22px;
        min-width: 0;
        justify-content: center;
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 30px;
        flex-wrap: nowrap;
    }

    .nav-links a {
        text-decoration: none;
        color: #111827;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
        padding: 6px 0;
    }

    .nav-links a.active {
        color: #008500;
    }

    .nav-links a:hover {
        color: #008500;
    }

    .nav-links a span {
        white-space: nowrap;
    }

    .user-menu {
        display: flex;
        align-items: center;
        gap: 18px;
        position: relative;
        /* anchor for popover */
    }

    .user-menu .user-profile {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }

    .nav-overflow {
        position: relative;
    }

    .overflow-trigger {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        padding: 8px 14px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .overflow-trigger[aria-expanded="true"] {
        background: #e2e8f0;
    }

    .overflow-menu {
        position: absolute;
        top: 110%;
        right: 0;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 12px 32px -8px rgba(0, 0, 0, .15);
        padding: 10px;
        min-width: 200px;
        display: flex;
        flex-direction: column;
        gap: 4px;
        z-index: 2000;
    }

    .overflow-item {
        text-decoration: none;
        color: #111827;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        gap: 10px;
    }

    .overflow-item:hover {
        background: #f1f5f9;
        color: #008500;
    }

    .overflow-item.active {
        background: #f1fdf5;
        color: #008500;
        box-shadow: 0 0 0 1px #008500;
    }

    /* Hamburger / Drawer (below 760px) */
    .nav-hamburger {
        display: none;
        position: relative;
        width: 46px;
        height: 46px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 6px;
    }

    .nav-hamburger span {
        width: 22px;
        height: 2.5px;
        background: #111827;
        border-radius: 3px;
        transition: .4s;
    }

    .nav-hamburger[aria-expanded="true"] span:nth-child(1) {
        transform: translateY(8px) rotate(45deg);
    }

    .nav-hamburger[aria-expanded="true"] span:nth-child(2) {
        opacity: 0;
    }

    .nav-hamburger[aria-expanded="true"] span:nth-child(3) {
        transform: translateY(-8px) rotate(-45deg);
    }

    .drawer {
        position: fixed;
        top: 0;
        left: 0;
        width: 340px;
        max-width: 82%;
        height: 100vh;
        background: #ffffff;
        box-shadow: 4px 0 28px -8px rgba(0, 0, 0, .22);
        transform: translateX(-110%);
        transition: transform .45s cubic-bezier(.65, .05, .36, 1);
        z-index: 3000;
        display: flex;
        flex-direction: column;
        padding: 24px 28px 40px;
        overflow-y: auto;
    }

    .drawer.open {
        transform: translateX(0);
    }

    .drawer-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }

    .drawer-title {
        font-size: 18px;
        font-weight: 700;
    }

    .drawer-close {
        background: none;
        border: 1px solid #e2e8f0;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        font-size: 24px;
        cursor: pointer;
    }

    .drawer-section {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .drawer-section.divider {
        border-top: 1px solid #e2e8f0;
        margin: 18px 0;
    }

    .drawer-link {
        text-decoration: none;
        color: #111827;
        padding: 14px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .drawer-link:hover {
        border-color: #008500;
        color: #008500;
    }

    .drawer-link.active {
        background: #f1fdf5;
        border-color: #008500;
        color: #008500;
        box-shadow: 0 0 0 1px #008500;
    }

    .drawer-link .badge {
        background: #008500;
        color: #fff;
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 20px;
        font-weight: 600;
    }

    .drawer-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .5);
        backdrop-filter: blur(2px);
        z-index: 2500;
        display: none;
        animation: fadeIn .4s ease;
    }

    .drawer-overlay.show {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @media (max-width:1150px) {
        .nav-links {
            gap: 20px;
        }

        .nav-links a span {
            display: none;
        }

        .nav-links a {
            padding: 6px 4px;
        }

        .nav-links a i {
            font-size: 18px;
        }

        .nav-overflow {
            display: block;
        }
    }

    @media (max-width:900px) {
        .nav-links {
            gap: 14px;
        }
    }

    @media (max-width:760px) {
        .nav-primary {
            justify-content: flex-start;
        }

        .nav-links {
            display: none;
        }

        .nav-overflow {
            display: none;
        }

        .nav-hamburger {
            display: flex;
        }

        .user-menu .user-name {
            display: none;
        }
    }

    @media (max-width:500px) {
        .user-menu {
            gap: 10px;
        }

        .user-menu .user-profile {
            padding: 0 4px;
        }
    }

    /* Notification Popover */
    .notification-icon {
        position: relative;
        background: none;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 6px;
        border-radius: 12px;
        transition: background .25s;
    }

    .notification-icon:hover {
        background: #f1f5f9;
    }

    .notification-icon[aria-expanded="true"] {
        background: #e2e8f0;
    }

    .notif-popover {
        position: absolute;
        top: calc(100% + 2px);
        right: 0;
        width: 320px;
        max-height: 520px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 14px 40px -12px rgba(0, 0, 0, .25);
        display: none;
        flex-direction: column;
        padding: 14px 14px 12px;
        z-index: 4000;
    }

    .notif-popover::before {
        content: "";
        position: absolute;
        top: -5px;
        right: 22px;
        width: 12px;
        height: 12px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-bottom: none;
        border-right: none;
        transform: rotate(45deg);
        box-shadow: -2px -2px 4px -2px rgba(0, 0, 0, .08);
    }

    .notif-popover.open {
        display: flex;
        animation: slideDown .35s cubic-bezier(.65, .05, .36, 1);
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .notif-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
    }

    .notif-header h3 {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }

    .notif-close {
        background: none;
        border: 1px solid #e2e8f0;
        width: 38px;
        height: 38px;
        border-radius: 12px;
        font-size: 20px;
        cursor: pointer;
    }

    .notif-close:hover {
        border-color: #008500;
        color: #008500;
    }

    .notif-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 14px;
    }

    .n-tab {
        flex: 1;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        padding: 10px 12px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 10px;
        cursor: pointer;
    }

    .n-tab.active,
    .n-tab:hover {
        background: #008500;
        border-color: #008500;
        color: #fff;
    }

    .notif-list {
        flex: 1;
        overflow: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding-right: 4px;
    }

    .notif-item {
        display: flex;
        gap: 12px;
        padding: 12px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        position: relative;
        cursor: pointer;
        transition: background .25s, border-color .25s;
    }

    .notif-item.unread {
        background: #f1fdf5;
        border-color: #c7f2d3;
    }

    .notif-item:hover {
        background: #f8fafc;
    }

    .notif-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #008500;
    }

    .notif-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .notif-title {
        font-size: 13px;
        font-weight: 600;
        color: #111827;
        line-height: 1.3;
    }

    .notif-meta {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        letter-spacing: .4px;
        text-transform: uppercase;
        display: flex;
        gap: 10px;
    }

    .notif-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding-top: 12px;
        border-top: 1px solid #e2e8f0;
        margin-top: 12px;
    }

    .mark-all {
        background: #fff;
        border: 1px solid #e2e8f0;
        padding: 10px 14px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 10px;
        cursor: pointer;
    }

    .mark-all:hover {
        border-color: #008500;
        color: #008500;
    }

    .view-all {
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        color: #008500;
    }

    .notif-empty {
        text-align: center;
        padding: 40px 20px;
        color: #64748b;
        font-size: 13px;
    }

    .notif-empty h4 {
        margin: 0 0 8px;
        font-size: 15px;
        font-weight: 700;
        color: #111827;
    }

    .notif-popover::-webkit-scrollbar {
        width: 8px;
    }

    .notif-list::-webkit-scrollbar {
        width: 8px;
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

    @media (max-width:760px) {
        .notif-popover {
            position: fixed;
            top: 70px;
            right: 10px;
            left: 10px;
            width: auto;
            max-width: none;
            max-height: 65vh;
        }
    }
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
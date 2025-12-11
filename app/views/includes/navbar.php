<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/navbar.css">
<!-- Header -->

<?php

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? '') == 443 ? 'https' : 'http';
if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) { $scheme = $_SERVER['HTTP_X_FORWARDED_PROTO']; } // if behind proxy
$currentUrl = $scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$uriNoBase  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
// Now $currentUrl is the full URL, $uriNoBase is just the path (useful for active() checks)

// Provider-specific nav links (left side)
if ($_SESSION['role'] === 'Provider') {
    $navLinks = [
        ['label' => 'Dashboard', 'href' => BASE_URL . '/dashboard', 'class' => '"fas fa-gauge"'],
        ['label' => 'Feeds', 'href' => BASE_URL . '/feeds', 'class' => '"fas fa-briefcase"'],
        ['label' => 'Bids', 'href' => BASE_URL . '/bids', 'class' => '"fas fa-coins"'],
        ['label' => 'Projects', 'href' => BASE_URL . '/projects', 'class' => '"fas fa-layer-plus"'],
        ['label' => 'Earnings', 'href' => BASE_URL . '/earnings', 'class' => '"fas fa-money-bill-wave"'],
    ];
}
elseif ($_SESSION['role'] === 'Client') {
    $navLinks = [
        ['label' => 'Dashboard', 'href' => BASE_URL . '/dashboard', 'class' => '"fas fa-chart-simple"'],
        ['label' => 'Projects', 'href' => BASE_URL . '/projects', 'class' => '"fas fa-briefcase"'],
        ['label' => 'Providers', 'href' => BASE_URL . '/providers', 'class' => '"fas fa-users"'],
        ['label' => 'Requests', 'href' => BASE_URL . '/requests', 'class' => '"fas fa-layer-plus"'],
        ['label' => 'Payments', 'href' => BASE_URL . '/payments', 'class' => '"fas fa-credit-card"'],
    ];
}

$navRight = [
    ['type' => 'icon', 'icon' => 'fa-regular fa-envelope', 'href' => BASE_URL . '/messages', 'aria' => 'Messages'],
    ['type' => 'icon', 'icon' => 'fa-regular fa-bell', 'href' => BASE_URL . '/notifications', 'aria' => 'Notifications'],
    ['type' => 'profile', 'href' => BASE_URL . '/profile'], // profile/avatar
];

?>

<header>
    <nav class="navbar" role="navigation" aria-label="Main navigation">
        <!-- servo logo -->
        <a href="<?= BASE_URL ?>" class="logo" aria-label="Home">
            <span><img src="<?= BASE_URL ?>/assets/img/logo.png" width="150" alt="Logo"></span>
        </a>
        <div class="nav-primary" id="navPrimary">
            <!-- hamburger menu button for mobile -->
            <button class="nav-hamburger" id="navHamburger" aria-label="Menu" aria-expanded="false"
                aria-controls="drawerMenu">
                <span></span><span></span><span></span>
            </button>
            <!-- navbar links -->
            <div class="nav-links" id="navLinks">
                <!-- use a loop to generate links -->
                <?php foreach ($navLinks as $link): ?>
                    <a href="<?= $link['href'] ?>" class="<?= ('./' . basename( $_SERVER['REQUEST_URI'])) === $link['href'] ? 'active' : '' ?>">
                        <i class=<?= $link['class']; ?>></i>
                        <span><?= $link['label'] ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="user-menu" id="userMenu">
            <!-- messages section -->
            <a href="<?= $navRight[0]['href'] ?>" class="<?= ('./' . basename( $_SERVER['REQUEST_URI'])) === $navRight[0]['href'] ? 'active' : '' ?>"
                aria-label="Messages">
                <i class="fas fa-comments"></i>
            </a>
            <!-- notifications section -->
            <button class="notification-icon <?= ('./' . basename( $_SERVER['REQUEST_URI'])) === $navRight[1]['href'] ? 'active' : '' ?>" id="notifToggle" aria-label="Notifications" aria-haspopup="true"
                aria-expanded="false">
                <i class="fas fa-bell"></i>
                <span class="notification-badge" id="notifBadge">3</span>
            </button>
            <!-- user profile section -->
            <div class="user-profile" onclick="window.location.href='<?= $navRight[2]['href'] ?>'" role="button"
                tabindex="0" aria-label="Profile">
                <div class="user-avatar" style="overflow: hidden; background-color: transparent; border: 1px solid #33333353;"><img src="<?= BASE_URL . '/../uploads/Users/'. $_SESSION['user_image']?>" alt="" style="height: 100%; width: 100%;"></div>
                <div class="user-name"><?= $_SESSION['user_name']?></div>
            </div>
            <!-- notifications pop-up panel -->
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
                    <a href="<?= $navRight[1]['href'] ?>" class="view-all">View all</a>
                </div>
            </div>
        </div>


        <!-- drawer menu for mobile -->
        <div class="drawer" id="drawerMenu" aria-hidden="true">
            <div class="drawer-header">
                <span class="drawer-title">Menu</span>
                <button class="drawer-close" id="drawerClose" aria-label="Close menu">&times;</button>
            </div>
            <div class="drawer-section" id="drawerLinks"><!-- cloned links --></div>
            <div class="drawer-section divider"></div>
            <div class="drawer-section">
                <a href="<?= $navRight[0]['href'] ?>"
                    class="drawer-link <?= $currentUrl === $navRight[0]['href'] ? 'active' : '' ?>"><i class="fas fa-comments"></i>
                    Messages</a>
                <a href="<?= $navRight[2]['href'] ?>"
                    class="drawer-link <?= $currentUrl === $navRight[2]['href'] ? 'active' : '' ?>"><i class="fas fa-user-circle"></i>
                    Profile</a>
                <a href="<?= $navRight[1]['href'] ?>"
                    class="drawer-link <?= $currentUrl === $navRight[1]['href'] ? 'active' : '' ?>"><i class="fas fa-bell"></i>
                    Notifications <span class="badge">3</span></a>
                <a href="<?= BASE_URL . '/login' ?>" class="drawer-link"><i class="fas fa-arrow-right-from-bracket"></i>
                    Logout</a>
            </div>
        </div>
        <div class="drawer-overlay" id="drawerOverlay" tabindex="-1" aria-hidden="true"></div>
    </nav>
</header>

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

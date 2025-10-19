
// Mobile navigation toggle
const navToggle = document.getElementById('navToggle');
const drawer = document.getElementById('drawer');
const drawerOverlay = document.getElementById('drawerOverlay');
const drawerClose = document.getElementById('drawerClose');

function closeDrawer() {
    drawer.classList.remove('open');
    drawerOverlay.classList.remove('open');
    navToggle.classList.remove('active');
    navToggle.setAttribute('aria-expanded', 'false');
    drawer.setAttribute('aria-hidden', 'true');
}

function openDrawer() {
    drawer.classList.add('open');
    drawerOverlay.classList.add('open');
    navToggle.classList.add('active');
    navToggle.setAttribute('aria-expanded', 'true');
    drawer.setAttribute('aria-hidden', 'false');
}
if (navToggle) {
    navToggle.addEventListener('click', () => {
        drawer.classList.contains('open') ? closeDrawer() : openDrawer();
    });
}
if (drawerOverlay) {
    drawerOverlay.addEventListener('click', closeDrawer);
}
if (drawerClose) {
    drawerClose.addEventListener('click', closeDrawer);
}
// Search mode switch
document.querySelectorAll('.search-switch button').forEach(btn => btn.addEventListener('click', () => {
    if (btn.classList.contains('active')) return;
    document.querySelectorAll('.search-switch button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}));
// Horizontal wheel support for boosted posts
const track = document.querySelector('.boosted-track');
if (track) {
    track.addEventListener('wheel', e => {
        if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
            track.scrollBy({
                left: e.deltaY,
                behavior: 'smooth'
            });
            e.preventDefault();
        }
    }, {
        passive: false
    });
    // Interval-based auto loop scroll (step per card)
    const originalCards = [...track.children];
    // duplicate set for seamless transition
    originalCards.forEach(c => track.appendChild(c.cloneNode(true)));
    let index = 0;
    const gap = 28; // must match CSS gap
    function cardFullWidth(card) {
        return card.offsetWidth + gap;
    }

    function scrollNext() {
        index++;
        const cards = [...track.children];
        const singleWidth = cardFullWidth(cards[0]);
        track.scrollTo({
            left: index * singleWidth,
            behavior: 'smooth'
        });
        // when moved past original set length, snap back without noticeable jump
        if (index >= originalCards.length) {
            // schedule reset after smooth scroll completes
            setTimeout(() => {
                track.scrollLeft = 0;
                index = 0;
            }, 600);
        }
    }
    let intervalMs = 3500; // adjust interval speed here
    let loop = setInterval(scrollNext, intervalMs);
    track.addEventListener('mouseenter', () => {
        clearInterval(loop);
    });
    track.addEventListener('mouseleave', () => {
        loop = setInterval(scrollNext, intervalMs);
    });
}
// Intersection reveal animations
const observer = new IntersectionObserver(entries => {
    entries.forEach(en => {
        if (en.isIntersecting) {
            en.target.classList.add('show');
            observer.unobserve(en.target);
        }
    })
}, {
    threshold: .18
});
document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

// Contact form faux submission
window.submitContactForm = function (form) {
    const status = form.querySelector('.form-status');
    const btn = form.querySelector('.send-btn');
    btn.disabled = true;
    status.textContent = 'Sending...';
    setTimeout(() => {
        status.textContent = 'Message sent successfully';
        btn.disabled = false;
        form.classList.add('success');
        form.reset();
        setTimeout(() => form.classList.remove('success'), 1500);
    }, 1400);
};

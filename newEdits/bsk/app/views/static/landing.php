<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1" />
	<title>BSK Marketplace – Hire Talent & Find Work</title>
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css" />
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/landing.css" />
	<link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
	<meta name="description" content="BSK connects clients & providers with verified skills, secure payments and collaborative project tools." />
</head>
<body class="landing-body">
	<!-- NAVBAR -->
	<nav class="landing-nav">
		<div class="lp-container nav-inner">
			<a href="/" class="brand" aria-label="Home">
				<img src="<?= BASE_URL ?>/assets/img/logo.png" alt="BSK Logo" width="150" />
			</a>
			<button class="nav-toggle" id="navToggle" aria-label="Menu" aria-expanded="false"><span></span><span></span><span></span></button>
			<div class="links" id="mainLinks" role="navigation" aria-label="Primary">
				<a href="#features">Features</a>
				<a href="#boosted">Boosted Posts</a>
				<a href="#categories">Categories</a>
				<a href="#workflow">How it Works</a>
				<a href="#feedback">Success Stories</a>
				<a href="#contact">Contact Us</a>
			</div>
			<div class="auth">
				<a href="<?= BASE_URL ?>/login" class="login">Log In</a>
				<a href="<?= BASE_URL ?>/register" class="cta">Get Started</a>
			</div>
		</div>
	</nav>
	<div class="mobile-drawer" id="drawer" aria-hidden="true" aria-label="Mobile menu">
		<div class="drawer-head">
			<strong>Menu</strong>
			<button class="nav-toggle active" id="drawerClose" aria-label="Close"><span></span><span></span><span></span></button>
		</div>
		<div class="drawer-links">
			<a href="#features">Features</a>
			<a href="#boosted">Boosted Posts</a>
			<a href="#categories">Categories</a>
			<a href="#workflow">How it Works</a>
			<a href="#feedback">Success Stories</a>
			<a href="#contact">Contact Us</a>
			<hr style="border:none;border-top:1px solid #e2e8f0;margin:14px 0;" />
			<a href="<?= BASE_URL ?>/login"><i class="fa-solid fa-arrow-right-to-bracket"></i> Log In</a>
			<a href="<?= BASE_URL ?>/register"><i class="fa-solid fa-user-plus"></i> Create Account</a>
		</div>
	</div>
	<div class="drawer-overlay" id="drawerOverlay" tabindex="-1"></div>

	<!-- HERO -->
	<section class="hero" id="top">
		<div class="hero-inner reveal">
			<div class="hero-copy">
				<h1>Build faster with trusted <span style="color:#a7ffd0;">local & remote</span> professionals.</h1>
				<p>Inspired by leading platforms yet purpose‑built for our region. BSK matches clients with proven providers through transparent posts, structured bids and milestone‑based payments.</p>
			</div>
			<div class="search-panel" aria-label="Search talent or projects">
				<div class="search-switch" role="tablist">
					<button class="active" data-mode="talent" aria-selected="true">Find Talent</button>
					<button data-mode="work">Find Work</button>
				</div>
				<div class="search-row">
					<div class="search-field"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Skill / Title (e.g. Web Design)" aria-label="Search term"></div>
					<div class="search-field"><i class="fa-solid fa-layer-group"></i><input type="text" placeholder="Category" aria-label="Category"></div>
					<div class="search-field"><i class="fa-solid fa-location-dot"></i><input type="text" placeholder="Location / Remote" aria-label="Location"></div>
					<button class="search-btn" aria-label="Search"><i class="fa-solid fa-search"></i> Search</button>
				</div>
				<div class="popular-tags" aria-label="Popular searches">
					<span>Popular:</span>
					<a href="#">Logo Design</a><a href="#">React</a><a href="#">Content Writing</a><a href="#">Mobile Apps</a><a href="#">Data Entry</a>
				</div>
			</div>
		</div>
	</section>

	<!-- FEATURES -->
	<section id="features" class="section">
		<div class="lp-container">
			<div class="section-head reveal">
				<h2>Why Teams Choose BSK</h2>
				<p>From idea to delivery: vetted profiles, category & skill targeting, milestone tracking, transparent pricing and integrated collaboration tools.</p>
			</div>
			<div class="features-grid">
				<div class="feature-card reveal"><div class="feature-icon"><i class="fa-solid fa-shield-check"></i></div><h3>Verified Talent</h3><p>Identity + profile checks, skill tagging and history for confident hiring decisions.</p></div>
				<div class="feature-card reveal"><div class="feature-icon"><i class="fa-solid fa-lock"></i></div><h3>Secure Payments</h3><p>Milestone based releases ensure providers are paid only after approval.</p></div>
				<div class="feature-card reveal"><div class="feature-icon"><i class="fa-solid fa-comments"></i></div><h3>Real-Time Messaging</h3><p>Contextual chat + file handoff to reduce back‑and‑forth and keep momentum.</p></div>
				<div class="feature-card reveal"><div class="feature-icon"><i class="fa-solid fa-bolt"></i></div><h3>Boosted Visibility</h3><p>Optional post & profile boosting surfaces urgent work to active talent.</p></div>
				<div class="feature-card reveal"><div class="feature-icon"><i class="fa-solid fa-diagram-project"></i></div><h3>Project Tracking</h3><p>Central dashboard for bids, budgets, progress and payment checkpoints.</p></div>
				<div class="feature-card reveal"><div class="feature-icon"><i class="fa-solid fa-globe"></i></div><h3>Hybrid Marketplace</h3><p>Blend of district‑level and remote sourcing with localized cost insights.</p></div>
			</div>
		</div>
	</section>

	<!-- BOOSTED POSTS -->
	<section id="boosted" class="boosted-section">
		<div class="lp-container">
			<h2 class="reveal">Trending & Boosted Posts</h2>
		</div>
		<div class="boosted-track" aria-label="Boosted posts carousel">
			<div class="boost-card reveal"><span class="flag">BOOSTED</span><h4>UI/UX Redesign for SaaS Dashboard</h4><p>Modern conversion‑focused revamp emphasizing accessibility.</p><div class="boost-meta"><span><i class="fa-regular fa-coins"></i> $800</span><span><i class="fa-regular fa-clock"></i> 5d left</span></div></div>
			<div class="boost-card reveal"><span class="flag">BOOSTED</span><h4>Local Photography – Product Shoot</h4><p>Lifestyle product imagery; preference to Colombo district talent.</p><div class="boost-meta"><span><i class="fa-regular fa-coins"></i> $320</span><span><i class="fa-regular fa-clock"></i> 2d left</span></div></div>
			<div class="boost-card reveal"><span class="flag">BOOSTED</span><h4>React / API Integration</h4><p>Enhance listing filters & optimize performance bundle.</p><div class="boost-meta"><span><i class="fa-regular fa-coins"></i> $1.2k</span><span><i class="fa-regular fa-clock"></i> 7d left</span></div></div>
			<div class="boost-card reveal"><span class="flag">BOOSTED</span><h4>Logo & Brand Kit for Eco Startup</h4><p>Minimal vector logo + social branding assets.</p><div class="boost-meta"><span><i class="fa-regular fa-coins"></i> $250</span><span><i class="fa-regular fa-clock"></i> 1d left</span></div></div>
			<div class="boost-card reveal"><span class="flag">BOOSTED</span><h4>Laravel Payment Gateway Setup</h4><p>Multi‑provider secure checkout & reporting integration.</p><div class="boost-meta"><span><i class="fa-regular fa-coins"></i> $600</span><span><i class="fa-regular fa-clock"></i> 4d left</span></div></div>
		</div>
	</section>

	<!-- CATEGORIES -->
	<section id="categories" class="section tight">
		<div class="lp-container">
			<div class="section-head reveal"><h2>Explore Categories</h2><p>Browse broad disciplines and specialized niches to tailor your service or sourcing strategy.</p></div>
			<div class="category-grid">
				<div class="category-tile reveal"><i class="fa-solid fa-pen-nib"></i><span>Design & Creative</span></div>
				<div class="category-tile reveal"><i class="fa-solid fa-code"></i><span>Web & Software Dev</span></div>
				<div class="category-tile reveal"><i class="fa-solid fa-mobile-screen"></i><span>Mobile Apps</span></div>
				<div class="category-tile reveal"><i class="fa-solid fa-rectangle-ad"></i><span>Digital Marketing</span></div>
				<div class="category-tile reveal"><i class="fa-solid fa-file-signature"></i><span>Content & Copy</span></div>
				<div class="category-tile reveal"><i class="fa-solid fa-database"></i><span>Data & Analytics</span></div>
				<div class="category-tile reveal"><i class="fa-solid fa-headset"></i><span>Support & Admin</span></div>
				<div class="category-tile reveal"><i class="fa-solid fa-camera"></i><span>Photography</span></div>
				<div class="category-tile reveal"><i class="fa-solid fa-screwdriver-wrench"></i><span>Engineering</span></div>
				<div class="category-tile reveal"><i class="fa-solid fa-globe"></i><span>Translation</span></div>
			</div>
		</div>
	</section>

	<!-- VIDEO INTRO -->
	<section id="intro-video" class="section video-section">
		<div class="lp-container">
			<div class="video-wrapper reveal">
				<video class="showcase-video" controls preload="metadata" poster="<?= BASE_URL ?>/assets/img/landingBG.jpg">
					<source src="<?= BASE_URL ?>/assets/video/intro.mp4" type="video/mp4">
					Your browser does not support the video tag.
				</video>
			</div>
		</div>
	</section>

	<!-- WORKFLOW -->
	<section id="workflow" class="section">
		<div class="lp-container workflow-shell reveal">
			<div>
				<h2 style="margin:0 0 18px;">How BSK Works</h2>
				<p style="margin:0;max-width:640px;line-height:1.55;font-size:.95rem;color:#475569;">A transparent flow that keeps expectations aligned for both clients and providers – from posting & bidding through collaboration, delivery and payment.</p>
			</div>
			<div class="workflow-steps">
				<div class="step reveal"><div class="step-num">1</div><h4>Create a Post</h4><p>Define scope, skills, budget & timeline with clarity.</p></div>
				<div class="step reveal"><div class="step-num">2</div><h4>Receive Bids</h4><p>Providers submit structured proposals & milestone plans.</p></div>
				<div class="step reveal"><div class="step-num">3</div><h4>Collaborate</h4><p>Use integrated messaging & file exchange for momentum.</p></div>
				<div class="step reveal"><div class="step-num">4</div><h4>Secure Payment</h4><p>Release funds only when agreed deliverables are met.</p></div>
				<div class="step reveal"><div class="step-num">5</div><h4>Review & Grow</h4><p>Rate outcomes and build a trusted talent network.</p></div>
			</div>
		</div>
	</section>

	<!-- FEEDBACK -->
	<section id="feedback" class="feedback-section">
		<div class="lp-container">
			<div class="feedback-head reveal">
				<h2>Trusted by growing teams</h2>
				<p>Real stories of faster delivery, high‑quality execution & sustainable talent partnerships.</p>
			</div>
			<div class="feedback-grid">
				<div class="testimonial reveal"><p>“We filled a critical React role in 24 hours. Sprint velocity jumped 3× first iteration.”</p><div class="person"><div class="avatar">AR</div><div><strong>Aruna K.</strong><br><span>Startup Founder</span></div></div></div>
				<div class="testimonial reveal"><p>“Blending local + remote talent let us source a photographer and backend engineer seamlessly.”</p><div class="person"><div class="avatar">DM</div><div><strong>Dilmi M.</strong><br><span>Product Lead</span></div></div></div>
				<div class="testimonial reveal"><p>“Milestone based releases de‑risked delivery and reinforced long‑term provider relationships.”</p><div class="person"><div class="avatar">TS</div><div><strong>Tharindu S.</strong><br><span>Agency Director</span></div></div></div>
			</div>
		</div>
	</section>

	<!-- CONTACT US (Redesigned) -->
	<section id="contact" class="contact-section-alt">
		<div class="contact-shell lp-container">
			<div class="contact-left reveal">
				<h2 class="contact-title">Let's build something impactful</h2>
				<p class="contact-sub">Have a question about projects, provider verification, billing or partnerships? Our small team reads every message.</p>
				<div class="contact-cards">
					<div class="c-card"><i class="fa-solid fa-envelope"></i><div><strong>Email</strong><span>support@bskplatform.com</span></div></div>
					<div class="c-card"><i class="fa-solid fa-headset"></i><div><strong>Live Support</strong><span>Weekdays 09:00 – 17:00</span></div></div>
					<div class="c-card"><i class="fa-solid fa-location-dot"></i><div><strong>Location</strong><span>Colombo, Sri Lanka</span></div></div>
				</div>
				<ul class="contact-meta">
					<li><i class="fa-solid fa-shield-check"></i> Secure form</li>
					<li><i class="fa-solid fa-clock"></i> Avg reply &lt; 24h</li>
					<li><i class="fa-solid fa-globe"></i> Global inquiries welcome</li>
				</ul>
			</div>
			<form class="contact-form-v2 reveal simple" method="post" action="#" onsubmit="event.preventDefault(); submitContactForm(this);" aria-label="Contact form">
				<div class="f">
					<label for="c_name">Name</label>
					<input id="c_name" name="name" type="text" required />
				</div>
				<div class="f">
					<label for="c_email">Email</label>
					<input id="c_email" name="email" type="email" required />
				</div>
				<div class="f">
					<label for="c_message">Message</label>
					<textarea id="c_message" name="message" rows="6" required placeholder="Tell us how we can help..."></textarea>
				</div>
				<div class="actions">
					<button type="submit" class="send-btn-alt"><i class="fa-regular fa-paper-plane"></i> Send Message</button>
					<div class="form-status" aria-live="polite"></div>
				</div>
			</form>
		</div>
	</section>

	<!-- FOOTER (Exact client footer clone) -->
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
	<style>
		/* Enhanced Footer Styling (matches card & pagination theme) */
		footer {
			/* Dark on left (logo side) → lighter to right */
			background: linear-gradient(90deg,#04170c 0%,#082612 50%,#0d3c1e 100%);
			color: #f1f5f9;
			position: relative;
			font-family: 'Inter', Arial, sans-serif;
            margin-top: 0;
		}

		footer::before {
			content: "";
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			height: 4px;
			background: linear-gradient(90deg, #008500, #3b82f6);
		}

		.footer-content {
			max-width: 1280px;
			margin: 0 auto;
			padding: 56px 32px 36px;
			display: grid;
			gap: 40px;
			grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
		}

		.footer-brand h4 {
			font-size: 22px;
			margin: 0 0 10px;
			font-weight: 800;
			letter-spacing: .5px;
			background: linear-gradient(90deg, #008500, #3b82f6);
			-webkit-background-clip: text;
			color: transparent;
		}

		.footer-brand p {
			margin: 0 0 18px;
			line-height: 1.55;
			font-size: 14px;
			color: #cbd5e1;
		}

		.footer-section h5 {
			margin: 0 0 14px;
			font-size: 13px;
			font-weight: 700;
			letter-spacing: 1px;
			text-transform: uppercase;
			color: #94a3b8;
		}

		.footer-links {
			list-style: none;
			margin: 0;
			padding: 0;
			display: flex;
			flex-direction: column;
			gap: 10px;
		}

		.footer-links a {
			text-decoration: none;
			font-size: 14px;
			font-weight: 500;
			color: #e2e8f0;
			display: inline-flex;
			align-items: center;
			gap: 8px;
			position: relative;
			padding: 4px 0;
			transition: color .25s ease;
		}

		.footer-links a::after {
			content: "";
			position: absolute;
			left: 0;
			bottom: 0;
			height: 2px;
			width: 0;
			background: #008500;
			border-radius: 2px;
			transition: width .3s ease;
		}

		.footer-links a:hover {
			color: #fff;
		}

		.footer-links a:hover::after {
			width: 36px;
		}

		.badge-new {
			background: #008500;
			color: #fff;
			font-size: 10px;
			font-weight: 600;
			padding: 2px 6px;
			border-radius: 8px;
			letter-spacing: .5px;
		}

		.newsletter-form { display: flex; flex-direction: column; gap: 12px; }
		.newsletter-form input { background: #1e293b; border: 1px solid #334155; color: #f1f5f9; padding: 10px 14px; border-radius: 10px; font-size: 14px; outline: none; transition: border-color .25s ease, background .25s ease; }
		.newsletter-form input:focus { border-color: #008500; background: #0f1f34; }
		.newsletter-form button { background: #008500; border: 1px solid #008500; color: #fff; font-weight: 600; font-size: 13px; padding: 10px 16px; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all .3s ease; }
		.newsletter-form button:hover { transform: translateY(-2px); box-shadow: 0 8px 22px -6px rgba(0, 133, 0, .45); }

		.social-row { display: flex; gap: 14px; margin-top: 6px; }
		n .social-btn { width: 40px; height: 40px; border-radius: 12px; background: #1e293b; border: 1px solid #334155; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 16px; cursor: pointer; transition: all .3s ease; position: relative; overflow: hidden; }
		.social-btn { width: 40px; height: 40px; border-radius: 12px; background: #1e293b; border: 1px solid #334155; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 16px; cursor: pointer; transition: all .3s ease; position: relative; overflow: hidden; }
		.social-btn:hover { color: #fff; border-color: #008500; box-shadow: 0 6px 18px -4px rgba(0, 133, 0, .45); }
		.social-btn::after { content: ""; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(0, 133, 0, 0), rgba(0, 133, 0, .2)); opacity: 0; transition: opacity .35s ease; }
		.social-btn:hover::after { opacity: 1; }

		.footer-mini-stats { display: flex; gap: 18px; flex-wrap: wrap; margin-top: 18px; }
		.mini-stat { background: #1e293b; border: 1px solid #334155; padding: 10px 14px; border-radius: 12px; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 6px; color: #94a3b8; }
		.mini-stat i { color: #008500; }

		.footer-bottom { border-top: 1px solid #1e293b; margin-top: 30px; padding: 22px 32px 34px; text-align: center; font-size: 13px; color: #64748b; }
		.legal-links { display: flex; justify-content: center; gap: 28px; flex-wrap: wrap; margin-top: 10px; }
		.legal-links a { color: #64748b; font-size: 12px; text-decoration: none; position: relative; padding: 4px 2px; }
		.legal-links a:hover { color: #fff; }
		.legal-links a:focus-visible, .footer-links a:focus-visible, .social-btn:focus-visible, .newsletter-form input:focus-visible, .newsletter-form button:focus-visible { outline: 2px solid #008500; outline-offset: 2px; }
		@media (max-width:640px) { .footer-content { padding: 48px 22px 28px; } }
		.card::before, .search-item::before, .metric-card::before, .activity-card::before, .action-card::before { content: none !important; background: none !important; height: 0 !important; }
	</style>
	<footer aria-label="Site footer">
		<div class="footer-content">
			<div class="footer-brand">
				<img src="<?= BASE_URL ?>/assets/img/logo.png" alt="BSK Logo" class="footer-logo" style="width:160px;height:auto;display:block;margin:0 0 14px;" />
				<p>Connecting clients with trusted providers. Manage projects, payments and communication securely.</p>
				<div class="social-row" aria-label="Social links">
					<button class="social-btn" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></button>
					<button class="social-btn" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></button>
					<button class="social-btn" aria-label="GitHub"><i class="fa-brands fa-github"></i></button>
					<button class="social-btn" aria-label="Dribbble"><i class="fa-brands fa-dribbble"></i></button>
				</div>
				<div class="footer-mini-stats">
					<div class="mini-stat"><i class="fa-regular fa-briefcase"></i> 120+ Projects</div>
					<div class="mini-stat"><i class="fa-regular fa-users"></i> 75 Providers</div>
					<div class="mini-stat"><i class="fa-regular fa-shield-check"></i> Secure</div>
				</div>
			</div>
			<div class="footer-section">
				<h5>Navigation</h5>
				<ul class="footer-links">
					<li><a href="#features"><i class="fa-regular fa-gauge"></i> Features</a></li>
					<li><a href="#boosted"><i class="fa-regular fa-rocket"></i> Boosted Posts</a></li>
					<li><a href="#categories"><i class="fa-regular fa-grid"></i> Categories</a></li>
					<li><a href="#workflow"><i class="fa-regular fa-diagram-project"></i> How it Works</a></li>
					<li><a href="#feedback"><i class="fa-regular fa-comments"></i> Success Stories</a></li>
				</ul>
			</div>
			<div class="footer-section">
				<h5>Support</h5>
				<ul class="footer-links">
					<li><a href="#"><i class="fa-regular fa-circle-question"></i> Help Center</a></li>
					<li><a href="#"><i class="fa-regular fa-envelope"></i> Contact</a></li>
					<li><a href="#"><i class="fa-regular fa-shield"></i> Privacy Policy</a></li>
					<li><a href="#"><i class="fa-regular fa-scale-balanced"></i> Terms of Service</a></li>
					<li><a href="#"><i class="fa-regular fa-shield-halved"></i> Trust & Safety</a></li>
				</ul>
			</div>
			<div class="footer-section">
				<h5>Stay Updated</h5>
				<form class="newsletter-form" action="#" method="post" onsubmit="event.preventDefault(); footerSubscribe();">
					<input type="email" name="email" placeholder="Email address" aria-label="Email address" required />
					<button type="submit"><i class="fa-regular fa-paper-plane"></i> Subscribe</button>
				</form>
				<ul class="footer-links" style="margin-top:14px;">
					<li><a href="#"><i class="fa-regular fa-bell"></i> Notifications</a></li>
					<li><a href="#"><i class="fa-regular fa-book"></i> Documentation</a></li>
				</ul>
			</div>
		</div>
		<div class="footer-bottom">
			<div>&copy; <?= date('Y') ?> Servo. All rights reserved.</div>
			<div class="legal-links" aria-label="Legal links">
				<a href="#">Privacy</a>
				<a href="#">Terms</a>
				<a href="#">Security</a>
				<a href="#">Status</a>
			</div>
		</div>
	</footer>
	<script>
		function footerSubscribe() {
			const form = document.querySelector('.newsletter-form');
			const input = form.querySelector('input[name="email"]');
			if (!input.value) return;
			const btn = form.querySelector('button');
			btn.disabled = true; btn.innerHTML = '<i class="fa-regular fa-check"></i> Subscribed';
			btn.style.background = '#008500';
			setTimeout(() => { btn.disabled = false; btn.innerHTML = '<i class="fa-regular fa-paper-plane"></i> Subscribe'; input.value = ''; }, 4000);
		}
	</script>

	<script>
	// Mobile navigation toggle
	const navToggle=document.getElementById('navToggle');
	const drawer=document.getElementById('drawer');
	const drawerOverlay=document.getElementById('drawerOverlay');
	const drawerClose=document.getElementById('drawerClose');
	function closeDrawer(){drawer.classList.remove('open');drawerOverlay.classList.remove('open');navToggle.classList.remove('active');navToggle.setAttribute('aria-expanded','false');drawer.setAttribute('aria-hidden','true');}
	function openDrawer(){drawer.classList.add('open');drawerOverlay.classList.add('open');navToggle.classList.add('active');navToggle.setAttribute('aria-expanded','true');drawer.setAttribute('aria-hidden','false');}
	if(navToggle){navToggle.addEventListener('click',()=>{drawer.classList.contains('open')?closeDrawer():openDrawer();});}
	if(drawerOverlay){drawerOverlay.addEventListener('click',closeDrawer);}    
	if(drawerClose){drawerClose.addEventListener('click',closeDrawer);}    
	// Search mode switch
	document.querySelectorAll('.search-switch button').forEach(btn=>btn.addEventListener('click',()=>{if(btn.classList.contains('active'))return;document.querySelectorAll('.search-switch button').forEach(b=>b.classList.remove('active'));btn.classList.add('active');}));
	// Horizontal wheel support for boosted posts
	const track=document.querySelector('.boosted-track');
	if(track){
		track.addEventListener('wheel',e=>{if(Math.abs(e.deltaY)>Math.abs(e.deltaX)){track.scrollBy({left:e.deltaY,behavior:'smooth'});e.preventDefault();}}, {passive:false});
		// Interval-based auto loop scroll (step per card)
		const originalCards=[...track.children];
		// duplicate set for seamless transition
		originalCards.forEach(c=>track.appendChild(c.cloneNode(true)));
		let index=0;
		const gap=28; // must match CSS gap
		function cardFullWidth(card){return card.offsetWidth + gap;}
		function scrollNext(){
			index++;
			const cards=[...track.children];
			const singleWidth=cardFullWidth(cards[0]);
			track.scrollTo({left:index*singleWidth,behavior:'smooth'});
			// when moved past original set length, snap back without noticeable jump
			if(index >= originalCards.length){
				// schedule reset after smooth scroll completes
				setTimeout(()=>{track.scrollLeft=0; index=0;}, 600);
			}
		}
		let intervalMs=3500; // adjust interval speed here
		let loop=setInterval(scrollNext, intervalMs);
		track.addEventListener('mouseenter',()=>{clearInterval(loop);});
		track.addEventListener('mouseleave',()=>{loop=setInterval(scrollNext, intervalMs);});
	}
	// Intersection reveal animations
	const observer=new IntersectionObserver(entries=>{entries.forEach(en=>{if(en.isIntersecting){en.target.classList.add('show');observer.unobserve(en.target);}})},{threshold:.18});
	document.querySelectorAll('.reveal').forEach(el=>observer.observe(el));

	// Contact form faux submission
	window.submitContactForm=function(form){
		const status=form.querySelector('.form-status');
		const btn=form.querySelector('.send-btn');
		btn.disabled=true; status.textContent='Sending...';
		setTimeout(()=>{status.textContent='Message sent successfully'; btn.disabled=false; form.classList.add('success'); form.reset(); setTimeout(()=>form.classList.remove('success'),1500);},1400);
	};
	</script>
</body>
</html>

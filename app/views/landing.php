<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1" />
	<title>Servo - Hire Talent & Find Work</title>

	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css" />
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/landing.css" />
	<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/footer.css" />

	<script src="<?= BASE_URL ?>/assets/js/landing.js" defer></script>

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
				<p>Inspired by leading platforms yet purpose‑built for our region. Servo matches clients with proven providers through concise posts, transparent bid system and pay on approve payment system.</p>
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
				<h2>Why People Choose Servo</h2>
				<p>From need of talent to getting work done: Servo streamlines the process for clients and providers alike.</p>
			</div>
			<div class="features-grid">
				<div class="feature-card reveal">
					<div class="feature-icon"><i class="fa-solid fa-shield-check"></i></div>
					<h3>Verified Talent</h3>
					<p>Identity + profile checks, skill tagging and history for confident hiring decisions.</p>
				</div>
				<div class="feature-card reveal">
					<div class="feature-icon"><i class="fa-solid fa-lock"></i></div>
					<h3>Secure Payments</h3>
					<p>Pay On Approve payment ensure providers are paid only after approval.</p>
				</div>
				<div class="feature-card reveal">
					<div class="feature-icon"><i class="fa-solid fa-comments"></i></div>
					<h3>Real-Time Messaging</h3>
					<p>Contextual chat + file handoff to reduce back‑and‑forth and keep momentum.</p>
				</div>
				<div class="feature-card reveal">
					<div class="feature-icon"><i class="fa-solid fa-bolt"></i></div>
					<h3>Boosted Visibility</h3>
					<p>Optional services & profile boosting surfaces urgent work to active talent.</p>
				</div>
				<div class="feature-card reveal">
					<div class="feature-icon"><i class="fa-solid fa-diagram-project"></i></div>
					<h3>Project Tracking</h3>
					<p>Central dashboard for bids, budgets, progress and payment checkpoints.</p>
				</div>
				<div class="feature-card reveal">
					<div class="feature-icon"><i class="fa-solid fa-globe"></i></div>
					<h3>Hybrid Marketplace</h3>
					<p>Blend of district‑level and remote sourcing with localized cost insights.</p>
				</div>
			</div>
		</div>
	</section>

	<!-- BOOSTED POSTS -->
	<section id="boosted" class="boosted-section">
		<div class="lp-container">
			<h2 class="reveal">Trending & Boosted Services</h2>
		</div>
		<div class="boosted-track" aria-label="Boosted posts carousel">
			<div class="boost-card reveal"><span class="flag">BOOSTED</span>
				<h4>UI/UX Redesign for SaaS Dashboard</h4>
				<p>Modern conversion‑focused revamp emphasizing accessibility.</p>
				<div class="boost-meta"><span><i class="fa-regular fa-coins"></i> $800</span><span><i class="fa-regular fa-clock"></i> 5d left</span></div>
			</div>
			<div class="boost-card reveal"><span class="flag">BOOSTED</span>
				<h4>Local Photography – Product Shoot</h4>
				<p>Lifestyle product imagery; preference to Colombo district talent.</p>
				<div class="boost-meta"><span><i class="fa-regular fa-coins"></i> $320</span><span><i class="fa-regular fa-clock"></i> 2d left</span></div>
			</div>
			<div class="boost-card reveal"><span class="flag">BOOSTED</span>
				<h4>React / API Integration</h4>
				<p>Enhance listing filters & optimize performance bundle.</p>
				<div class="boost-meta"><span><i class="fa-regular fa-coins"></i> $1.2k</span><span><i class="fa-regular fa-clock"></i> 7d left</span></div>
			</div>
			<div class="boost-card reveal"><span class="flag">BOOSTED</span>
				<h4>Logo & Brand Kit for Eco Startup</h4>
				<p>Minimal vector logo + social branding assets.</p>
				<div class="boost-meta"><span><i class="fa-regular fa-coins"></i> $250</span><span><i class="fa-regular fa-clock"></i> 1d left</span></div>
			</div>
			<div class="boost-card reveal"><span class="flag">BOOSTED</span>
				<h4>Laravel Payment Gateway Setup</h4>
				<p>Multi‑provider secure checkout & reporting integration.</p>
				<div class="boost-meta"><span><i class="fa-regular fa-coins"></i> $600</span><span><i class="fa-regular fa-clock"></i> 4d left</span></div>
			</div>
		</div>
	</section>

	<!-- CATEGORIES -->
	<section id="categories" class="section tight">
		<div class="lp-container">
			<div class="section-head reveal">
				<h2>Explore Categories</h2>
				<p>Browse broad disciplines and specialized niches to tailor your service or sourcing strategy.</p>
			</div>
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
				<h2 style="margin:0 0 18px;">How Servo's Bidding System Works</h2>
				<p style="margin:0;max-width:640px;line-height:1.55;font-size:.95rem;color:#475569;">A transparent flow that keeps expectations aligned for both clients and providers – from posting & bidding through collaboration, delivery and payment.</p>
			</div>
			<div class="workflow-steps">
				<div class="step reveal">
					<div class="step-num">1</div>
					<h4>Create a Post</h4>
					<p>Define scope, skills, budget & timeline with clarity.</p>
				</div>
				<div class="step reveal">
					<div class="step-num">2</div>
					<h4>Receive Bids</h4>
					<p>Providers submit structured proposals & milestone plans.</p>
				</div>
				<div class="step reveal">
					<div class="step-num">3</div>
					<h4>Collaborate</h4>
					<p>Use integrated messaging & file exchange for momentum.</p>
				</div>
				<div class="step reveal">
					<div class="step-num">4</div>
					<h4>Secure Payment</h4>
					<p>Release funds only when agreed deliverables are met.</p>
				</div>
				<div class="step reveal">
					<div class="step-num">5</div>
					<h4>Review & Grow</h4>
					<p>Rate outcomes and build a trusted talent network.</p>
				</div>
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
				<div class="testimonial reveal">
					<p>“We filled a critical React role in 24 hours. Sprint velocity jumped 3× first iteration.”</p>
					<div class="person">
						<div class="avatar">AR</div>
						<div><strong>Aruna K.</strong><br><span>Startup Founder</span></div>
					</div>
				</div>
				<div class="testimonial reveal">
					<p>“Blending local + remote talent let us source a photographer and backend engineer seamlessly.”</p>
					<div class="person">
						<div class="avatar">DM</div>
						<div><strong>Dilmi M.</strong><br><span>Product Lead</span></div>
					</div>
				</div>
				<div class="testimonial reveal">
					<p>“Milestone based releases de‑risked delivery and reinforced long‑term provider relationships.”</p>
					<div class="person">
						<div class="avatar">TS</div>
						<div><strong>Tharindu S.</strong><br><span>Agency Director</span></div>
					</div>
				</div>
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
					<div class="c-card"><i class="fa-solid fa-envelope"></i>
						<div><strong>Email</strong><span>support@servoplatform.com</span></div>
					</div>
					<div class="c-card"><i class="fa-solid fa-headset"></i>
						<div><strong>Live Support</strong><span>Weekdays 09:00 – 17:00</span></div>
					</div>
					<div class="c-card"><i class="fa-solid fa-location-dot"></i>
						<div><strong>Location</strong><span>Colombo, Sri Lanka</span></div>
					</div>
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

	<?php include __DIR__ . '/includes/footer.php'; ?>

</body>

</html>
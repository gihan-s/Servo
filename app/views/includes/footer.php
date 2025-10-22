<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/footer.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
	
	<footer aria-label="Site footer">
	    <div class="footer-content">
	        <div class="footer-brand">
	            <img src="<?= BASE_URL ?>/assets/img/logo.png" alt="BSK Logo" class="footer-logo" style="width:160px;height:auto;display:block;margin:0 0 14px;" />
	            <p>Connecting clients with trusted providers. Manage projects, payments and communication securely.</p>
	            <!-- <div class="social-row" aria-label="Social links">
	                <button class="social-btn" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></button>
	                <button class="social-btn" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></button>
	                <button class="social-btn" aria-label="GitHub"><i class="fa-brands fa-github"></i></button>
	                <button class="social-btn" aria-label="Dribbble"><i class="fa-brands fa-dribbble"></i></button>
	            </div> -->
	            <div class="footer-mini-stats">
	                <div class="mini-stat"><i class="fa-regular fa-briefcase"></i> 120+ Projects</div>
	                <div class="mini-stat"><i class="fa-regular fa-users"></i> 75 Providers</div>
	                <!-- <div class="mini-stat"><i class="fa-regular fa-shield-check"></i> Secure</div> -->
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
	        btn.disabled = true;
	        btn.innerHTML = '<i class="fa-regular fa-check"></i> Subscribed';
	        btn.style.background = '#008500';
	        setTimeout(() => {
	            btn.disabled = false;
	            btn.innerHTML = '<i class="fa-regular fa-paper-plane"></i> Subscribe';
	            input.value = '';
	        }, 4000);
	    }
	</script>
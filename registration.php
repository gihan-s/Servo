<html>

<head>
	<link rel="stylesheet" href="assets/css/elements.css">
	<link rel="stylesheet" href="assets/css/GridTemplates.css">
	<link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
	
	<style>
		* {
			color: #333;
		}

		:root {
			--theme-color: #008500;
			--border-color: #333;
			--background-color: #ffffffff;
		}

		body {
			font-family: Arial, sans-serif;
			background-color: var(--background-color);
			margin: 0;
			padding: 0;
			padding-top: 70px; /* Add padding to account for fixed header */
		}

		.header {
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			height: 70px;
			background-color: var(--background-color);
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding: 0 40px;
			z-index: 1000;
		}

		.header .logo {
			height: 30px;
			width: auto;
		}

		/* Responsive header adjustments */
		@media (max-width: 768px) {
			.header {
				padding: 0 20px;
			}
			
			.header .logo {
				height: 40px;
			}
		}

		.main-section {
			padding: 40px;
			margin: auto;
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
			max-width: 400px;
			width: 100%;
			background-color: var(--background-color);
			border-radius: 10px;
			z-index: 1;
			margin-top: 35px; /* Add margin to account for header */
		}

		.main-section img {
			width: 100%;
			max-width: 200px;
			margin: 0 auto;
			display: block;
			margin-bottom: 40px;
		}

		input:-webkit-autofill,
		input:-webkit-autofill:hover,
		input:-webkit-autofill:focus,
		input:-webkit-autofill:active {
			-webkit-box-shadow: 0 0 0 30px #fff inset !important;
			/* Force white background */
			box-shadow: 0 0 0 30px #fff inset !important;
			/* Ensure consistency across browsers */
			-webkit-text-fill-color: #333 !important;
			/* Text color */
			background-color: #fff !important;
			/* Explicit background color */
		}

		.section {
			position: relative;
			text-align: center;
			display: none;
			margin-bottom: 30px;
			padding: 0 10px;
			z-index: 1;
		}

		.section::-webkit-scrollbar {
			width: 6px;
		}

		.section::-webkit-scrollbar-track {
			background: #f1f1f1;
			border-radius: 10px;
		}

		.section::-webkit-scrollbar-thumb {
			background: #c1c1c1;
			border-radius: 10px;
		}

		.section::-webkit-scrollbar-thumb:hover {
			background: #a8a8a8;
		}

		.section.active {
			display: block;
		}

		.button-section {
			width: 100%;
			position: relative;
			margin: 0% auto;
			display: flex;
			justify-content: space-between;

		}

		.button-section button {
			width: 100px;
		}

		.text-container,
		.select-container {
			width: 100%;
			margin-bottom: 10px;
		}

		.input-grid-1{
			margin-bottom: 5px;
		}

		.select-container .options {
			text-align: left;
		}

		.toggle-section {
			display: flex;
			justify-content: center;
			margin: 10px auto;
			margin-bottom: 40px;
			border: 1px solid #33300080;
			border-radius: 10px;
			padding: 5px;
		}

		.toggle-button {
			flex: 1;
			padding: 10px;
			cursor: pointer;
			text-align: center;
			border-radius: 10px;
			font-weight: 600;
			font-size: 16px;
			color: var(--border-color);
			transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
		}

		.toggle-button i {
			color: var(--border-color);
		}

		.toggle-button.active {
			background-color: var(--theme-color);
			color: var(--background-color);
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
		}

		.toggle-button.active i {
			color: var(--background-color);
		}

		.hr-title-container {
			position: relative;
			text-align: center;
			width: 100%;
			max-width: 400px;
			margin: 10px auto;
			margin-top: 0px;
		}

		.hr-title {
			position: relative;
			display: inline-block;
			background-color: var(--background-color);
			padding: 0 10px;
			margin: auto;
			font-size: 22px;
			font-weight: bold;
			color: var(--border-color);
			z-index: 1;
		}

		.hr-line {
			position: absolute;
			top: 50%;
			transform: translateY(-50%);
			left: 0;
			right: 0;
			z-index: 0;
		}

		.profile-container {
			margin: 0 auto;
			width: fit-content;
			position: relative;
			margin-bottom: 40px;
		}

		.profile-photo {
			width: 150px;
			height: 150px;
			border-radius: 50%;
			background-color: var(--profilePhoto);
			display: flex;
			align-items: center;
			justify-content: center;
			position: relative;
			left: 50%;
			transform: translateX(-50%);
			overflow: hidden;
			border: 1px solid var(--theme-color);
		}

		.profile-photo i {
			font-size: 80px;
			color: var(--theme-color);
		}

		.profile-photo img {
			width: 100%;
			height: 100%;
			object-fit: cover;
			display: none;
			margin-bottom: auto;
		}

		.profile-container .upload-button {
			font-size: 16px;
			border: none;
			border-radius: 50%;
			position: absolute;
			bottom: 10px;
			right: 10px;
			font-size: 25px;
			color: var(--theme-color);
			background-color: var(--background-color);
			cursor: pointer;
			transition: background-color 0.3s ease;
		}


		/* Hide the file input */
		.profile-container input[type="file"],
		.nic-container input[type="file"] {
			display: none;
		}

		.nic-container {
			text-align: center;
			margin: 20px auto;
			position: relative;
			max-width: 86mm;
		}

		.nic-photo {
			max-width: 86mm;
			height: 54mm;
			background-color: var(--background-color);
			position: relative;
			overflow: hidden;
			left: 50%;
			transform: translateX(-50%);
			display: flex;
			align-items: center;
			justify-content: center;
			margin-bottom: 10px;
			border: 2px solid var(--theme-color);
			border-radius: 10px;

		}

		.nic-photo i {
			font-size: 50px;
			color: var(--theme-color);
			padding: 20px;
		}

		.nic-photo img {
			min-width: 100%;
			min-height: 100%;
			object-fit: cover;
			/* Preserve original aspect ratio */
			display: none;

			margin-bottom: auto;
			/* Hidden by default */
		}

		.nic-container .upload-button {
			font-size: 16px;
			border-radius: 10px;
			position: absolute;
			bottom: 10px;
			right: 5px;
			cursor: pointer;
			scale: 0.8;
			transition: background-color 0.3s ease;
		}

		.accordion-item {
			margin: 0.5rem 0;
		}

		.accordion-item .accordion-title {
			display: flex;
			align-items: center;
			justify-content: space-between;
			cursor: pointer;
			padding: 0rem 1rem;
			height: 40px;
			border: 2px solid #e0e0e0;
			border-radius: 8px;
			background-color: var(--background-color);
			transition: all 0.3s ease;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
			position: relative;
		}

		.accordion-item .accordion-title:hover {
			border-color: var(--theme-color);
			box-shadow: 0 3px 8px rgba(0, 133, 0, 0.15);
		}

		.accordion-item .accordion-title .title-content {
			display: flex;
			align-items: center;
			flex: 1;
		}

		.accordion-item .accordion-title .title-actions {
			display: flex;
			align-items: center;
			gap: 10px;
		}

		.accordion-item .accordion-title .close-btn {
			color: #666;
			font-size: 16px;
			cursor: pointer;
			padding: 4px;
			border-radius: 4px;
			transition: all 0.3s ease;
			background: none;
			border: none;
		}

		.accordion-item .accordion-title .close-btn:hover {
			color: #ff4444;
			background-color: rgba(255, 68, 68, 0.1);
		}

		.accordion-item .accordion-title span {
			font-weight: 600;
			font-size: 1rem;
			color: #333;
		}

		.accordion-item .accordion-title i {
			color: #333;
			font-size: 18px;
			transition: transform 0.3s ease;
		}

		.accordion-item .accordion-title i.rotated {
			transform: rotate(180deg);
		}

		.accordion-item .accordion-options {
			height: 0;
			overflow: hidden;
			padding: 0;
			margin: auto;
			transition: height 0.3s ease;
		}

		.accordion-item .accordion-options.active {
			height: auto;
			overflow: visible;
			padding: 0.5rem;
			padding-top: 1.5rem;
		}

		.password-toggle {
			position: absolute;
			right: 10px;
			top: 50%;
			transform: translateY(-50%);
			background: none;
			border: none;
			cursor: pointer;
			color: #666;
			font-size: 16px;
			padding: 5px;
			z-index: 2;
			transition: color 0.3s ease;
		}

		.password-toggle:hover {
			color: var(--theme-color);
		}

		.text-container {
			position: relative;
		}

		/* Validation tooltip styles */
		.validation-tooltip {
			position: absolute;
			bottom: -30px;
			left: 0;
			background-color: #ff4444;
			color: white;
			padding: 5px 10px;
			border-radius: 4px;
			font-size: 12px;
			white-space: nowrap;
			z-index: 1000;
			opacity: 0;
			transform: translateY(-5px);
			transition: all 0.3s ease;
			pointer-events: none;
		}

		.validation-tooltip::before {
			content: '';
			position: absolute;
			top: -5px;
			left: 15px;
			border-left: 5px solid transparent;
			border-right: 5px solid transparent;
			border-bottom: 5px solid #ff4444;
		}

		.validation-tooltip.show {
			opacity: 1;
			transform: translateY(0);
		}

		.text-field.error {
			border-color: #ff4444 !important;
			box-shadow: 0 0 5px rgba(255, 68, 68, 0.3);
		}

		/* Password strength indicator styles */
		.password-strength {
			margin-top: 8px;
			margin-bottom: 10px;
		}

		.password-strength-bar {
			height: 4px;
			background-color: #e0e0e0;
			border-radius: 2px;
			overflow: hidden;
			margin-bottom: 5px;
		}

		.password-strength-fill {
			height: 100%;
			width: 0%;
			transition: all 0.3s ease;
			border-radius: 2px;
		}

		.password-strength-fill.very-weak {
			width: 20%;
			background-color: #ff4444;
		}

		.password-strength-fill.weak {
			width: 40%;
			background-color: #ff8800;
		}

		.password-strength-fill.medium {
			width: 60%;
			background-color: #ffaa00;
		}

		.password-strength-fill.strong {
			width: 80%;
			background-color: #88cc00;
		}

		.password-strength-fill.very-strong {
			width: 100%;
			background-color: #00aa00;
		}

		.password-strength-text {
			font-size: 12px;
			color: #666;
			text-align: left;
		}

		.password-strength-text.very-weak {
			color: #ff4444;
		}

		.password-strength-text.weak {
			color: #ff8800;
		}

		.password-strength-text.medium {
			color: #ffaa00;
		}

		.password-strength-text.strong {
			color: #88cc00;
		}

		.password-strength-text.very-strong {
			color: #00aa00;
		}
	</style>
</head>

<body>
	<header class="header">
		<img src="assets/img/logo.png" alt="Servo Logo" class="logo">
		<a href="signup.php" class="button" style="text-decoration:none;">
			<i class="fa-solid fa-sign-in-alt" style="padding-right: 5px"></i>Login
		</a>
	</header>
	<form id="registrationForm" class="form" action="" method="post" enctype="multipart/form-data">
		<div class="main-section">
			<div class="section active">
				<div class="input-field">
					<!-- Toggle for user type -->
					<div class="toggle-section user-change">
						<div class="toggle-button active"><i class="fa-solid fa-user"
								style="padding-right: 10px"></i>Client</div>
						<div class="toggle-button"><i class="fa-solid fa-user-helmet-safety"
								style="padding-right: 10px"></i>Provider</div>
					</div>
					<div class="hr-title-container">
						<span class="hr-title">Personal Information</span>
						<hr class="hr-line">
					</div>
					<!-- Input fields -->
					<div class="input-grid-1">
						<div class="text-container">
							<div class="label text-label">First Name *</div>
							<input type="text" class="text-field" name="" id="">
						</div>
					</div>
					<div class="input-grid-1">
						<div class="text-container">
							<div class="label text-label">Last Name *</div>
							<input type="text" class="text-field" name="" id="">
						</div>
					</div>
					<div class="input-grid-1">
						<div class="select-container">
							<div class="text-container">
								<div class="label dropdown-label">Gender *</div>
								<input type="text" class="text-field-dropdown" readonly name="" id="">
							</div>


							<div class="options">
								<div>Male</div>
								<div>Female</div>
								<div>Prefer not to say</div>
							</div>
						</div>
					</div>
					<div class="input-grid-1">
						<div class="text-container">
							<div class="label text-label">Email *</div>
							<input type="email" class="text-field" name="" id="">
						</div>
					</div>
					<div class="input-grid-1">
						<div class="text-container">
							<div class="label text-label">Contact No *</div>
							<input type="text" class="text-field" name="" id="">
						</div>
					</div>
					<div class="input-grid-1" id="nicField" style="display:none;">
						<div class="text-container">
							<div class="label text-label">NIC *</div>
							<input type="text" class="text-field" name="" id="">
						</div>
					</div>
				</div>
			</div>

			<div class="section">
				<div class="hr-title-container">
					<span class="hr-title">Profile Information</span>
					<hr class="hr-line">
				</div>
				<div class="input-field">
					<div class="profile-container">
						<div class="profile-photo" id="profilePhoto">
							<i class="fas fa-user"></i>
							<img id="profileImage" src="" alt="Profile Picture">
						</div>
						<label for="fileInput" class="upload-button"><i class="fa-solid fa-circle-plus"></i></label>
						<input type="file" id="fileInput" accept="image/*">
					</div>

					<div class="input-grid-1">
						<div class="text-container">
							<div class="label text-label">Bio</div>
							<textarea class="text-field" name="bio" spellcheck="false"></textarea>
						</div>
					</div>
					<div class="input-grid-1">
						<div class="text-container">
							<div class="label text-label">Website</div>
							<input type="text" class="text-field" name="" id="">
						</div>
					</div>
				</div>
			</div>

			<div class="section">
				<div class="hr-title-container">
					<span class="hr-title">Upload Documents</span>
					<hr class="hr-line">
				</div>
				<div class="input-field">
					<!-- NIC Front -->
					<div class="nic-container">
						<span class="label">NIC Front</span>
						<div class="nic-photo" id="nicFrontPhoto">
							<i class="fas fa-id-card"></i>
							<img id="nicFrontImage" src="" alt="NIC Front">
						</div>
						<label class="button upload-button" for="nicFrontInput"><i class="fa-solid fa-plus"
								style="padding-right: 10px"></i>Upload</label>
						<input type="file" id="nicFrontInput" accept="image/*">
					</div>

					<!-- NIC Back -->
					<div class="nic-container">
						<span class="label">NIC Back</span>
						<div class="nic-photo" id="nicBackPhoto">
							<i class="fas fa-id-card"></i>
							<img id="nicBackImage" src="" alt="NIC Back">
						</div>
						<label class="button upload-button" for="nicBackInput"><i class="fa-solid fa-plus"
								style="padding-right: 10px"></i>Upload</label>
						<input type="file" id="nicBackInput" accept="image/*">
					</div>
				</div>
				<div class="input-grid-1">
					<div class="text-container">
						<div class="label text-label label-float">Resume</div>
						<input type="file" class="text-field" name="resume" accept=".pdf,.doc,.docx,.xls,.xlsx,.txt">
					</div>
				</div>
			</div>

			<div class="section">
				<div class="hr-title-container">
					<span class="hr-title">Edit categories</span>
					<hr class="hr-line">
				</div>
				<div class="cat-section">
					<div class="accordion-item">
						<div class="accordion-title">
							<div class="title-content">
								<span>Category 1</span>
							</div>
							<div class="title-actions">
								<button class="close-btn" onclick="removeCategory(this)" type="button">
									<i class="fa-solid fa-times"></i>
								</button>
								<i class="fa-light fa-chevron-down rotated"></i>
							</div>
						</div>
						<ul class="accordion-options active">
							<div class="input-grid-1">
								<div class="search-select-container">
									<div class="text-container">
										<div class="label search-dropdown-label">Category Name</div>
										<input type="text" class="text-field-search-dropdown" autocomplete="off"
											onkeydown="return false" name="" id="">
									</div>
									<div class="options">
										<span class="text-container">
											<input type="text" class="text-field-search">
										</span>
										<div class="option-list">
											<div>Option 1</div>
											<div>Option 2</div>
											<div>Option 3</div>
											<div>Option 4</div>
											<div>Option 5</div>
										</div>
									</div>
								</div>
							</div>
							<div class="input-grid-1">
								<div class="text-container">
									<div class="label text-label">Title</div>
									<input type="text" class="text-field" name="" id="">
								</div>
							</div>
							<div class="input-grid-1">
								<div class="text-container">
									<div class="label text-label">Description</div>
									<textarea class="text-field" name="bio" spellcheck="false"></textarea>
								</div>
							</div>
							<div class="input-grid-1">
								<div class="text-container">
									<div class="label text-label">Default Price</div>
									<input type="text" class="text-field" name="" id="">
								</div>
							</div>
							<div class="input-grid-1">
								<div class="search-select-container multiple-selector">
									<div class="text-container">
										<div class="label search-dropdown-label">Locations</div>
										<input type="text" class="text-field-search-dropdown" autocomplete="off"
											onkeydown="return false" name="" id="">
									</div>
									<div class="options">
										<span class="text-container">
											<input type="text" class="text-field-search">
										</span>
										<div class="option-list">
											<div>
												<input type="checkbox" id="category1_checkbox1">
												<label for="category1_checkbox1">Display</label>
											</div>
											<div>
												<input type="checkbox" id="category1_checkbox2">
												<label for="category1_checkbox2">Display AAA Grade</label>
											</div>
											<div>
												<input type="checkbox" id="category1_checkbox3">
												<label for="category1_checkbox3">Display with Frame</label>
											</div>
											<div>
												<input type="checkbox" id="category1_checkbox4">
												<label for="category1_checkbox4">Display with Touch panel</label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="input-grid-1">
								<div class="search-select-container multiple-selector">
									<div class="text-container">
										<div class="label search-dropdown-label">Skills</div>
										<input type="text" class="text-field-search-dropdown" autocomplete="off"
											onkeydown="return false" name="" id="">
									</div>
									<div class="options">
										<span class="text-container">
											<input type="text" class="text-field-search">
										</span>
										<div class="option-list">
											<div>
												<input type="checkbox" id="category1_checkbox5">
												<label for="category1_checkbox5">Display</label>
											</div>
											<div>
												<input type="checkbox" id="category1_checkbox6">
												<label for="category1_checkbox6">Display AAA Grade</label>
											</div>
											<div>
												<input type="checkbox" id="category1_checkbox7">
												<label for="category1_checkbox7">Display with Frame</label>
											</div>
											<div>
												<input type="checkbox" id="category1_checkbox8">
												<label for="category1_checkbox8">Display with Touch panel</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</ul>
					</div>
				</div>
				<div class="input-grid-1">
					<button type="button" class="button outline" id="addCategoryBtn" style="width: 100%;"><i class="fa-solid fa-plus"
						style="padding-right: 10px"></i>Add Category</button>
				</div>
			</div>

			<div class="section">
				<div class="hr-title-container">
					<span class="hr-title">Account Security</span>
					<hr class="hr-line">
				</div>
				<div class="input-field">
					<div class="input-grid-1" style="margin-bottom: 0; gap: 0px;">
						<div class="text-container">
							<div class="label text-label">Password *</div>
							<input type="password" class="text-field" name="password" id="password">
							<button type="button" class="password-toggle" onclick="togglePassword('password')">
								<i class="fa-solid fa-eye" id="password-icon"></i>
							</button>
						</div>
						<div class="password-strength" id="passwordStrength" style="display: none;">
							<div class="password-strength-bar">
								<div class="password-strength-fill" id="passwordStrengthFill"></div>
							</div>
							<div class="password-strength-text" id="passwordStrengthText">Enter a password</div>
						</div>
					</div>
					<div class="input-grid-1">
						<div class="text-container">
							<div class="label text-label">Confirm Password *</div>
							<input type="password" class="text-field" name="repassword" id="repassword">
							<button type="button" class="password-toggle" onclick="togglePassword('repassword')">
								<i class="fa-solid fa-eye" id="repassword-icon"></i>
							</button>
						</div>
					</div>
				</div>
			</div>

			<div class="button-section">
				<button type="button" class="button outline" id="prevBtn" onclick="prevSection()"><i
						class="fa-regular fa-arrow-left" style="padding-right: 5px"></i>Back</button>
				<button type="button" class="button" id="nextBtn" onclick="nextSection()">Next<i
						class="fa-regular fa-arrow-right" style="padding-left: 5px"></i></button>
				<button class="button" id="submit-button" type="submit">Register</button>
			</div>
		</div>
	</form>

	<script>
		let isProvider = false;
		const toggleBtn = document.querySelectorAll('.user-change .toggle-button');
		const nicField = document.getElementById('nicField');

		toggleBtn.forEach((btn) => {
			btn.addEventListener('click', () => {
				toggleBtn.forEach(b => b.classList.remove('active'));
				btn.classList.add('active');
				if (btn.textContent === 'Provider') {
					isProvider = true;
					nicField.style.display = 'block';
				} else {
					isProvider = false;
					nicField.style.display = 'none';
				}
			});
		});

		ind = 0;
		const sections = document.querySelectorAll(".section");
		const prevBtn = document.getElementById('prevBtn');
		const nextBtn = document.getElementById('nextBtn');
		const submitBtn = document.getElementById('submit-button');

		function updateButtonState() {
			if (ind === 0) {
				prevBtn.style.visibility = 'hidden';
			} else {
				prevBtn.style.visibility = 'visible';
			}
			if (ind === sections.length - 1) {
				nextBtn.style.display = 'none';
				submitBtn.style.display = 'block';
			} else {
				nextBtn.style.display = 'block';
				submitBtn.style.display = 'none';
			}
		}

		function showSection(index) {
			sections.forEach((sec, i) => {
				sec.classList.toggle('active', i === index);
			});
			updateButtonState();
		}

		// Email validation function
		function validateEmail(email) {
			const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			return emailRegex.test(email);
		}

		// Password validation function
		function validatePassword(password) {
			// Check if password has at least one lowercase letter, one uppercase letter, and one number
			const hasLowercase = /[a-z]/.test(password);
			const hasUppercase = /[A-Z]/.test(password);
			const hasNumber = /[0-9]/.test(password);
			
			// Password must be at least 8 characters and have lowercase, uppercase, and numbers
			return password.length >= 8 && hasLowercase && hasUppercase && hasNumber;
		}

		// Calculate password strength
		function calculatePasswordStrength(password) {
			if (!password) return { strength: 'none', score: 0 };
			
			let score = 0;
			const checks = {
				length: password.length >= 8,
				longLength: password.length > 12,
				lowercase: /[a-z]/.test(password),
				uppercase: /[A-Z]/.test(password),
				numbers: /[0-9]/.test(password),
				symbols: /[^a-zA-Z0-9]/.test(password)
			};
			
			// Only lowercase letters
			if (checks.lowercase && !checks.uppercase && !checks.numbers && !checks.symbols) {
				return { strength: 'very-weak', score: 1, text: 'Very Weak' };
			}
			
			// Lowercase and uppercase
			if (checks.lowercase && checks.uppercase && !checks.numbers && !checks.symbols) {
				return { strength: 'weak', score: 2, text: 'Weak' };
			}
			
			// Lowercase, uppercase, and numbers
			if (checks.lowercase && checks.uppercase && checks.numbers && !checks.symbols) {
				return { strength: 'medium', score: 3, text: 'Medium' };
			}
			
			// Lowercase, uppercase, numbers, and symbols with length > 12
			if (checks.lowercase && checks.uppercase && checks.numbers && checks.symbols && checks.longLength) {
				return { strength: 'very-strong', score: 5, text: 'Very Strong' };
			}
			
			// Lowercase, uppercase, numbers, and symbols (but length <= 12)
			if (checks.lowercase && checks.uppercase && checks.numbers && checks.symbols && checks.length) {
				return { strength: 'strong', score: 4, text: 'Strong' };
			}
			
			// Default case - very weak
			return { strength: 'very-weak', score: 1, text: 'Very Weak' };
		}

		// Update password strength indicator
		function updatePasswordStrength(password) {
			const strengthContainer = document.getElementById('passwordStrength');
			const strengthFill = document.getElementById('passwordStrengthFill');
			const strengthText = document.getElementById('passwordStrengthText');
			
			if (!password) {
				strengthContainer.style.display = 'none';
				return;
			}
			
			strengthContainer.style.display = 'block';
			const result = calculatePasswordStrength(password);
			
			// Remove all strength classes
			strengthFill.className = 'password-strength-fill';
			strengthText.className = 'password-strength-text';
			
			// Add current strength class
			strengthFill.classList.add(result.strength);
			strengthText.classList.add(result.strength);
			strengthText.textContent = result.text;
		}

		// NIC validation function for Sri Lankan NICs
		function validateNIC(nic) {
			// Remove spaces and convert to uppercase
			nic = nic.replace(/\s/g, '').toUpperCase();
			
			// Old format: 9 digits + V (e.g., 123456789V)
			const oldFormatRegex = /^[0-9]{9}[VX]$/;
			
			// New format: 12 digits (e.g., 123456789012)
			const newFormatRegex = /^[0-9]{12}$/;
			
			return oldFormatRegex.test(nic) || newFormatRegex.test(nic);
		}

		// Show validation tooltip
		function showValidationTooltip(inputElement, message) {
			// Remove any existing tooltip
			const existingTooltip = inputElement.parentNode.querySelector('.validation-tooltip');
			if (existingTooltip) {
				existingTooltip.remove();
			}

			// Add error styling to input
			inputElement.classList.add('error');

			// Create and show tooltip
			const tooltip = document.createElement('div');
			tooltip.className = 'validation-tooltip';
			tooltip.textContent = message;
			inputElement.parentNode.appendChild(tooltip);

			// Show tooltip with animation
			setTimeout(() => {
				tooltip.classList.add('show');
			}, 10);

			// Auto-hide tooltip after 3 seconds
			setTimeout(() => {
				hideValidationTooltip(inputElement);
			}, 3000);
		}

		// Hide validation tooltip
		function hideValidationTooltip(inputElement) {
			const tooltip = inputElement.parentNode.querySelector('.validation-tooltip');
			if (tooltip) {
				tooltip.classList.remove('show');
				setTimeout(() => {
					tooltip.remove();
				}, 300);
			}
			inputElement.classList.remove('error');
		}

		// Validate section 1
		function validateSection1() {
			const section1 = document.querySelector('.section.active');
			const textInputs = section1.querySelectorAll('input[type="text"]');
			const firstNameInput = textInputs[0]; // First text input - First Name
			const lastNameInput = textInputs[1]; // Second text input - Last Name  
			const genderInput = textInputs[2]; // Third text input - Gender (dropdown)
			const contactInput = textInputs[3]; // Fifth input (4th text input) - Contact No
			const emailInput = section1.querySelector('input[type="email"]');
			let isValid = true;

			// Clear any existing tooltips
			[firstNameInput, lastNameInput, genderInput, emailInput, contactInput].forEach(input => {
				if (input) hideValidationTooltip(input);
			});

			// Validate first name
			if (!firstNameInput.value.trim()) {
				showValidationTooltip(firstNameInput, 'First name is required');
				isValid = false;
			}

			// Validate last name
			if (!lastNameInput.value.trim()) {
				showValidationTooltip(lastNameInput, 'Last name is required');
				isValid = false;
			}

			// Validate gender
			if (!genderInput.value.trim()) {
				showValidationTooltip(genderInput, 'Gender is required');
				isValid = false;
			}

			// Validate email
			if (!emailInput.value.trim()) {
				showValidationTooltip(emailInput, 'Email is required');
				isValid = false;
			} else if (!validateEmail(emailInput.value.trim())) {
				showValidationTooltip(emailInput, 'Please enter a valid email address');
				isValid = false;
			}

			// Validate contact number
			const contactValue = contactInput.value.trim();
			if (!contactValue) {
				showValidationTooltip(contactInput, 'Contact number is required');
				isValid = false;
			} else if (!/^0\d{9}$/.test(contactValue)) {
				showValidationTooltip(contactInput, 'Contact number must be 10 digits starting with 0');
				isValid = false;
			}

			// Validate NIC if displayed (for providers)
			const nicField = document.getElementById('nicField');
			if (nicField && nicField.style.display !== 'none') {
				const nicInput = textInputs[4]; // NIC input (when displayed)
				if (nicInput) {
					hideValidationTooltip(nicInput);
					const nicValue = nicInput.value.trim();
					if (!nicValue) {
						showValidationTooltip(nicInput, 'NIC is required');
						isValid = false;
					} else if (!validateNIC(nicValue)) {
						showValidationTooltip(nicInput, 'Please enter a valid Sri Lankan NIC (e.g., 123456789V or 123456789012)');
						isValid = false;
					}
				}
			}

			return isValid;
		}

		// Validate section 2 (Profile Information)
		function validateSection2() {
			const fileInput = document.getElementById('fileInput');
			const profileImage = document.getElementById('profileImage');
			let isValid = true;

			// Clear any existing tooltips on profile photo container
			const profileContainer = document.querySelector('.profile-container');
			const existingTooltip = profileContainer.querySelector('.validation-tooltip');
			if (existingTooltip) {
				existingTooltip.remove();
			}

			// Check if profile image is uploaded
			const hasFileSelected = fileInput.files && fileInput.files.length > 0;
			const hasImageDisplayed = profileImage.style.display === 'block' && profileImage.src && profileImage.src !== '';
			
			if (!hasFileSelected && !hasImageDisplayed) {
				showProfileValidationTooltip(profileContainer, 'Profile image is required');
				isValid = false;
			} else if (hasFileSelected) {
				// Validate file size (8MB = 8 * 1024 * 1024 bytes)
				const file = fileInput.files[0];
				const maxSize = 8 * 1024 * 1024; // 8MB in bytes
				
				if (file.size > maxSize) {
					showProfileValidationTooltip(profileContainer, 'Profile image must be smaller than 8MB');
					isValid = false;
				}
			}

			return isValid;
		}

		// Validate section 3 (Upload Documents) - Only for providers
		function validateSection3() {
			// Only validate if user is a provider (section 3 is only shown for providers)
			if (!isProvider) {
				return true;
			}

			let isValid = true;

			// Validate NIC Front
			const nicFrontInput = document.getElementById('nicFrontInput');
			const nicFrontImage = document.getElementById('nicFrontImage');
			const nicFrontContainer = document.querySelector('#nicFrontPhoto').parentNode;
			
			// Clear existing tooltips
			hideNICValidationTooltip(nicFrontContainer);

			const hasNicFrontFile = nicFrontInput.files && nicFrontInput.files.length > 0;
			const hasNicFrontDisplayed = nicFrontImage.style.display === 'block' && nicFrontImage.src && nicFrontImage.src !== '';
			
			if (!hasNicFrontFile && !hasNicFrontDisplayed) {
				showNICValidationTooltip(nicFrontContainer, 'NIC front image is required');
				isValid = false;
			} else if (hasNicFrontFile) {
				// Validate file size (5MB = 5 * 1024 * 1024 bytes)
				const file = nicFrontInput.files[0];
				const maxSize = 5 * 1024 * 1024; // 5MB in bytes
				
				if (file.size > maxSize) {
					showNICValidationTooltip(nicFrontContainer, 'NIC front image must be smaller than 5MB');
					isValid = false;
				}
			}

			// Validate NIC Back
			const nicBackInput = document.getElementById('nicBackInput');
			const nicBackImage = document.getElementById('nicBackImage');
			const nicBackContainer = document.querySelector('#nicBackPhoto').parentNode;
			
			// Clear existing tooltips
			hideNICValidationTooltip(nicBackContainer);

			const hasNicBackFile = nicBackInput.files && nicBackInput.files.length > 0;
			const hasNicBackDisplayed = nicBackImage.style.display === 'block' && nicBackImage.src && nicBackImage.src !== '';
			
			if (!hasNicBackFile && !hasNicBackDisplayed) {
				showNICValidationTooltip(nicBackContainer, 'NIC back image is required');
				isValid = false;
			} else if (hasNicBackFile) {
				// Validate file size (5MB = 5 * 1024 * 1024 bytes)
				const file = nicBackInput.files[0];
				const maxSize = 5 * 1024 * 1024; // 5MB in bytes
				
				if (file.size > maxSize) {
					showNICValidationTooltip(nicBackContainer, 'NIC back image must be smaller than 5MB');
					isValid = false;
				}
			}

			return isValid;
		}

		// Validate section 4 (Categories) - Only for providers
		function validateSection4() {
			// Only validate if user is a provider (section 4 is only shown for providers)
			if (!isProvider) {
				return true;
			}

			let isValid = true;
			const categories = document.querySelectorAll('.accordion-item');
			let hasEmptyCategory = false;
			let firstEmptyCategory = null;

			categories.forEach((category, index) => {
				// Get all inputs within this category
				const categoryNameInput = category.querySelector('.text-field-search-dropdown');
				const titleInput = category.querySelector('input[type="text"].text-field');
				const descriptionTextarea = category.querySelector('textarea');
				const defaultPriceInput = category.querySelectorAll('input[type="text"].text-field')[1]; // Second text field
				
				// Get location checkboxes
				const locationCheckboxes = category.querySelectorAll('.multiple-selector')[0]?.querySelectorAll('input[type="checkbox"]:checked') || [];
				
				// Get skill checkboxes
				const skillCheckboxes = category.querySelectorAll('.multiple-selector')[1]?.querySelectorAll('input[type="checkbox"]:checked') || [];

				// Check if this category is completely empty
				const isCategoryEmpty = (!categoryNameInput?.value?.trim()) && 
									   (!titleInput?.value?.trim()) && 
									   (!descriptionTextarea?.value?.trim()) && 
									   (!defaultPriceInput?.value?.trim()) && 
									   (locationCheckboxes.length === 0) && 
									   (skillCheckboxes.length === 0);

				if (isCategoryEmpty) {
					hasEmptyCategory = true;
					if (!firstEmptyCategory) {
						firstEmptyCategory = category;
					}
					return; // Skip validation for completely empty categories
				}

				// If category has some content, validate all required fields
				let categoryIsValid = true;

				// Validate category name
				if (!categoryNameInput?.value?.trim()) {
					showCategoryValidationTooltip(categoryNameInput, 'Category name is required');
					categoryIsValid = false;
				}

				// Validate title
				if (!titleInput?.value?.trim()) {
					showCategoryValidationTooltip(titleInput, 'Title is required');
					categoryIsValid = false;
				}

				// Validate description
				if (!descriptionTextarea?.value?.trim()) {
					showCategoryValidationTooltip(descriptionTextarea, 'Description is required');
					categoryIsValid = false;
				}

				// Validate default price
				if (!defaultPriceInput?.value?.trim()) {
					showCategoryValidationTooltip(defaultPriceInput, 'Default price is required');
					categoryIsValid = false;
				}

				// Validate at least one location selected
				if (locationCheckboxes.length === 0) {
					const locationContainer = category.querySelectorAll('.multiple-selector')[0];
					showCategoryValidationTooltip(locationContainer?.querySelector('.text-field-search-dropdown'), 'At least one location must be selected');
					categoryIsValid = false;
				}

				// Validate at least one skill selected
				if (skillCheckboxes.length === 0) {
					const skillContainer = category.querySelectorAll('.multiple-selector')[1];
					showCategoryValidationTooltip(skillContainer?.querySelector('.text-field-search-dropdown'), 'At least one skill must be selected');
					categoryIsValid = false;
				}

				if (!categoryIsValid) {
					isValid = false;
					// Expand this category to show errors
					category.querySelector('.accordion-options').classList.add('active');
					category.querySelector('.fa-chevron-down').classList.add('rotated');
				}
			});

			// If there are completely empty categories, show error
			if (hasEmptyCategory && firstEmptyCategory) {
				const categoryTitle = firstEmptyCategory.querySelector('.title-content span');
				showCategoryValidationTooltip(categoryTitle, 'Empty categories cannot be submitted. Please fill all required fields or remove empty categories.');
				isValid = false;
				
				// Expand the first empty category
				firstEmptyCategory.querySelector('.accordion-options').classList.add('active');
				firstEmptyCategory.querySelector('.fa-chevron-down').classList.add('rotated');
			}

			return isValid;
		}

		// Validate section 5 (Account Security)
		function validateSection5() {
			const passwordInput = document.getElementById('password');
			const repasswordInput = document.getElementById('repassword');
			let isValid = true;

			// Clear any existing tooltips
			hideValidationTooltip(passwordInput);
			hideValidationTooltip(repasswordInput);

			const passwordValue = passwordInput.value.trim();
			const repasswordValue = repasswordInput.value.trim();

			// Validate password
			if (!passwordValue) {
				showValidationTooltip(passwordInput, 'Password is required');
				isValid = false;
			} else if (!validatePassword(passwordValue)) {
				showValidationTooltip(passwordInput, 'Password must contain at least 8 characters with lowercase, uppercase, and numbers');
				isValid = false;
			}

			// Validate confirm password
			if (!repasswordValue) {
				showValidationTooltip(repasswordInput, 'Please confirm your password');
				isValid = false;
			} else if (passwordValue !== repasswordValue) {
				showValidationTooltip(repasswordInput, 'Passwords do not match');
				isValid = false;
			}

			return isValid;
		}

		// Show validation tooltip for category inputs
		function showCategoryValidationTooltip(inputElement, message) {
			if (!inputElement) return;

			// Remove any existing tooltip
			const existingTooltip = inputElement.parentNode.querySelector('.validation-tooltip');
			if (existingTooltip) {
				existingTooltip.remove();
			}

			// Add error styling to input
			inputElement.classList.add('error');

			// Create and show tooltip
			const tooltip = document.createElement('div');
			tooltip.className = 'validation-tooltip';
			tooltip.textContent = message;
			tooltip.style.maxWidth = '250px';
			tooltip.style.whiteSpace = 'normal';
			inputElement.parentNode.appendChild(tooltip);

			// Show tooltip with animation
			setTimeout(() => {
				tooltip.classList.add('show');
			}, 10);

			// Auto-hide tooltip after 4 seconds
			setTimeout(() => {
				hideCategoryValidationTooltip(inputElement);
			}, 4000);
		}

		// Hide validation tooltip for category inputs
		function hideCategoryValidationTooltip(inputElement) {
			if (!inputElement) return;
			
			const tooltip = inputElement.parentNode.querySelector('.validation-tooltip');
			if (tooltip) {
				tooltip.classList.remove('show');
				setTimeout(() => {
					tooltip.remove();
				}, 300);
			}
			inputElement.classList.remove('error');
		}

		// Show validation tooltip for profile container
		function showProfileValidationTooltip(container, message) {
			// Remove any existing tooltip
			const existingTooltip = container.querySelector('.validation-tooltip');
			if (existingTooltip) {
				existingTooltip.remove();
			}

			// Create and show tooltip
			const tooltip = document.createElement('div');
			tooltip.className = 'validation-tooltip';
			tooltip.textContent = message;
			tooltip.style.position = 'absolute';
			tooltip.style.bottom = '-35px';
			tooltip.style.left = '50%';
			tooltip.style.transform = 'translateX(-50%)';
			container.appendChild(tooltip);

			// Show tooltip with animation
			setTimeout(() => {
				tooltip.classList.add('show');
			}, 10);

			// Auto-hide tooltip after 3 seconds
			setTimeout(() => {
				hideProfileValidationTooltip(container);
			}, 3000);
		}

		// Hide validation tooltip for profile container
		function hideProfileValidationTooltip(container) {
			const tooltip = container.querySelector('.validation-tooltip');
			if (tooltip) {
				tooltip.classList.remove('show');
				setTimeout(() => {
					tooltip.remove();
				}, 300);
			}
		}

		// Show validation tooltip for NIC containers
		function showNICValidationTooltip(container, message) {
			// Remove any existing tooltip
			const existingTooltip = container.querySelector('.validation-tooltip');
			if (existingTooltip) {
				existingTooltip.remove();
			}

			// Create and show tooltip
			const tooltip = document.createElement('div');
			tooltip.className = 'validation-tooltip';
			tooltip.textContent = message;
			tooltip.style.position = 'absolute';
			tooltip.style.bottom = '-35px';
			tooltip.style.left = '50%';
			tooltip.style.transform = 'translateX(-50%)';
			tooltip.style.whiteSpace = 'nowrap';
			container.appendChild(tooltip);

			// Show tooltip with animation
			setTimeout(() => {
				tooltip.classList.add('show');
			}, 10);

			// Auto-hide tooltip after 3 seconds
			setTimeout(() => {
				hideNICValidationTooltip(container);
			}, 3000);
		}

		// Hide validation tooltip for NIC containers
		function hideNICValidationTooltip(container) {
			const tooltip = container.querySelector('.validation-tooltip');
			if (tooltip) {
				tooltip.classList.remove('show');
				setTimeout(() => {
					tooltip.remove();
				}, 300);
			}
		}

		function nextSection() {
			// Validate current section before proceeding
			if (ind === 0) { // Section 1 validation
				if (!validateSection1()) {
					return; // Don't proceed if validation fails
				}
			} else if (ind === 1) { // Section 2 validation
				if (!validateSection2()) {
					return; // Don't proceed if validation fails
				}
			} else if (ind === 2) { // Section 3 validation (Upload Documents for providers)
				if (!validateSection3()) {
					return; // Don't proceed if validation fails
				}
			} else if (ind === 3) { // Section 4 validation (Categories for providers)
				if (!validateSection4()) {
					return; // Don't proceed if validation fails
				}
			} else if (ind === 4) { // Section 5 validation (Account Security)
				if (!validateSection5()) {
					return; // Don't proceed if validation fails
				}
			}

			if (ind === 1) { // currently in section 2
				if (isProvider) {
					ind = 2; // go to section 3
				} else {
					ind = 4; // skip to section 5
				}
			} else if (ind < sections.length - 1) {
				ind += 1;
			}
			showSection(ind);
		}

		function prevSection() {
			if (ind === 4 && !isProvider) {
				ind = 1; // from section 5 to section 2 for clients
			} else if (ind > 0) {
				ind -= 1;
			}
			showSection(ind);
		}

		// Initialize button state on page load
		updateButtonState();

		// Add input event listeners to clear validation errors
		document.addEventListener('DOMContentLoaded', function() {
			const section1 = document.querySelector('.section.active');
			const textInputs = section1.querySelectorAll('input[type="text"]');
			const firstNameInput = textInputs[0]; // First text input - First Name
			const lastNameInput = textInputs[1]; // Second text input - Last Name  
			const genderInput = textInputs[2]; // Third text input - Gender (dropdown)
			const contactInput = textInputs[3]; // Fifth input (4th text input) - Contact No
			const emailInput = section1.querySelector('input[type="email"]');

			// Add event listeners to clear validation on input
			[firstNameInput, lastNameInput, genderInput, emailInput, contactInput].forEach(input => {
				if (input) {
					input.addEventListener('input', function() {
						hideValidationTooltip(this);
					});
				}
			});

			// Add event listener for NIC input (when displayed)
			const nicInput = textInputs[4]; // NIC input
			if (nicInput) {
				nicInput.addEventListener('input', function() {
					hideValidationTooltip(this);
				});
			}

			// Add event listeners for category validation clearing
			setupCategoryValidationListeners();

			// Add event listeners for password inputs
			setupPasswordValidationListeners();
		});

		// Setup event listeners for password validation
		function setupPasswordValidationListeners() {
			const passwordInput = document.getElementById('password');
			const repasswordInput = document.getElementById('repassword');

			// Password strength and validation
			if (passwordInput) {
				passwordInput.addEventListener('input', function() {
					const password = this.value;
					updatePasswordStrength(password);
					hideValidationTooltip(this);
					
					// Also check confirm password if it has a value
					if (repasswordInput && repasswordInput.value) {
						if (password !== repasswordInput.value) {
							showValidationTooltip(repasswordInput, 'Passwords do not match');
						} else {
							hideValidationTooltip(repasswordInput);
						}
					}
				});
			}

			// Confirm password validation
			if (repasswordInput) {
				repasswordInput.addEventListener('input', function() {
					hideValidationTooltip(this);
					
					const password = passwordInput ? passwordInput.value : '';
					if (this.value && password !== this.value) {
						showValidationTooltip(this, 'Passwords do not match');
					}
				});
			}
		}

		// Setup event listeners for category validation
		function setupCategoryValidationListeners() {
			// Add event listeners to all category inputs to clear validation on input
			document.querySelectorAll('.accordion-item').forEach(category => {
				// Category name input
				const categoryNameInput = category.querySelector('.text-field-search-dropdown');
				if (categoryNameInput) {
					categoryNameInput.addEventListener('input', function() {
						hideCategoryValidationTooltip(this);
					});
				}

				// Title input
				const titleInput = category.querySelector('input[type="text"].text-field');
				if (titleInput) {
					titleInput.addEventListener('input', function() {
						hideCategoryValidationTooltip(this);
					});
				}

				// Description textarea
				const descriptionTextarea = category.querySelector('textarea');
				if (descriptionTextarea) {
					descriptionTextarea.addEventListener('input', function() {
						hideCategoryValidationTooltip(this);
					});
				}

				// Default price input
				const defaultPriceInput = category.querySelectorAll('input[type="text"].text-field')[1];
				if (defaultPriceInput) {
					defaultPriceInput.addEventListener('input', function() {
						hideCategoryValidationTooltip(this);
					});
				}

				// Location and skill checkboxes
				const checkboxes = category.querySelectorAll('input[type="checkbox"]');
				checkboxes.forEach(checkbox => {
					checkbox.addEventListener('change', function() {
						const container = this.closest('.multiple-selector');
						if (container) {
							const searchInput = container.querySelector('.text-field-search-dropdown');
							if (searchInput) {
								hideCategoryValidationTooltip(searchInput);
							}
						}
					});
				});
			});
		}

		const fileInput = document.getElementById('fileInput');
		const profilePhoto = document.getElementById('profilePhoto');
		const profileImage = document.getElementById('profileImage');

		fileInput.addEventListener('change', (event) => {
			const file = event.target.files[0];
			if (file) {
				// Validate file size (8MB = 8 * 1024 * 1024 bytes)
				const maxSize = 8 * 1024 * 1024; // 8MB in bytes
				const profileContainer = document.querySelector('.profile-container');
				
				if (file.size > maxSize) {
					showProfileValidationTooltip(profileContainer, 'Profile image must be smaller than 8MB');
					// Clear the file input
					fileInput.value = '';
					return;
				}

				// Clear any existing validation tooltips
				hideProfileValidationTooltip(profileContainer);

				const reader = new FileReader();
				reader.onload = (e) => {
					profileImage.src = e.target.result; // Set image source to data URL
					profileImage.style.display = 'block'; // Show the image
					profilePhoto.querySelector('i').style.display = 'none'; // Hide the icon
				};
				reader.readAsDataURL(file); // Read file as data URL (client-side)
			}
		});

		function setupImageUpload(inputId, photoContainerId, imageId) {
			const fileInput = document.getElementById(inputId);
			const photoContainer = document.getElementById(photoContainerId);
			const image = document.getElementById(imageId);

			fileInput.addEventListener('change', (event) => {
				const file = event.target.files[0];
				if (file) {
					// Validate file size for NIC images (5MB = 5 * 1024 * 1024 bytes)
					const maxSize = 5 * 1024 * 1024; // 5MB in bytes
					const nicContainer = photoContainer.parentNode;
					
					if (file.size > maxSize) {
						showNICValidationTooltip(nicContainer, 'Image must be smaller than 5MB');
						// Clear the file input
						fileInput.value = '';
						return;
					}

					// Clear any existing validation tooltips
					hideNICValidationTooltip(nicContainer);

					const reader = new FileReader();
					reader.onload = (e) => {
						image.src = e.target.result; // Set image source to data URL
						image.style.display = 'block'; // Show the image
						photoContainer.querySelector('i').style.display = 'none'; // Hide the icon
					};
					reader.readAsDataURL(file); // Read file as data URL (client-side)
				}
			});
		}

		// Set up handlers for NIC inputs
		setupImageUpload('nicFrontInput', 'nicFrontPhoto', 'nicFrontImage');
		setupImageUpload('nicBackInput', 'nicBackPhoto', 'nicBackImage');


		document.querySelectorAll('.accordion-item .accordion-title').forEach(option => {
			option.addEventListener('click', function (e) {
				// Don't trigger accordion if close button was clicked
				if (e.target.closest('.close-btn')) {
					return;
				}
				
				// Close all other accordions first
				const allAccordionOptions = document.querySelectorAll('.accordion-options');
				const allChevrons = document.querySelectorAll('.accordion-title i.fa-chevron-down');
				
				allAccordionOptions.forEach(opt => opt.classList.remove('active'));
				allChevrons.forEach(chevron => chevron.classList.remove('rotated'));
				
				// Then open the clicked one
				this.querySelector('.fa-chevron-down').classList.add('rotated');
				this.nextElementSibling.classList.add('active');
			});
		});

		// Add category functionality
		let categoryCount = 1;
		
		function updateCategoryNumbers() {
			const categories = document.querySelectorAll('.accordion-item');
			categories.forEach((category, index) => {
				const span = category.querySelector('.title-content span');
				span.textContent = `Category ${index + 1}`;
			});
			categoryCount = categories.length;
		}
		
		function removeCategory(button) {
			const accordionItem = button.closest('.accordion-item');
			const categorySection = document.querySelector('.cat-section');
			
			// Don't allow removing if it's the only category
			if (categorySection.children.length <= 1) {
				alert('You must have at least one category.');
				return;
			}
			
			// Check if the category being removed is currently expanded
			const isExpanded = accordionItem.querySelector('.accordion-options').classList.contains('active');
			
			// Remove the category
			accordionItem.remove();
			
			// Update category numbers
			updateCategoryNumbers();
			
			// If the removed category was expanded, expand the last category
			if (isExpanded) {
				const categories = document.querySelectorAll('.accordion-item');
				const lastCategory = categories[categories.length - 1];
				
				// Close all categories first
				const allAccordionOptions = document.querySelectorAll('.accordion-options');
				const allChevrons = document.querySelectorAll('.accordion-title i.fa-chevron-down');
				
				allAccordionOptions.forEach(opt => opt.classList.remove('active'));
				allChevrons.forEach(chevron => chevron.classList.remove('rotated'));
				
				// Expand the last category
				lastCategory.querySelector('.fa-chevron-down').classList.add('rotated');
				lastCategory.querySelector('.accordion-options').classList.add('active');
			}
		}
		
		function addAccordionEventListener(accordionTitle) {
			accordionTitle.addEventListener('click', function (e) {
				// Don't trigger accordion if close button was clicked
				if (e.target.closest('.close-btn')) {
					return;
				}
				
				// Close all other accordions first
				const allAccordionOptions = document.querySelectorAll('.accordion-options');
				const allChevrons = document.querySelectorAll('.accordion-title i.fa-chevron-down');
				
				allAccordionOptions.forEach(opt => opt.classList.remove('active'));
				allChevrons.forEach(chevron => chevron.classList.remove('rotated'));
				
				// Then open the clicked one
				this.querySelector('.fa-chevron-down').classList.add('rotated');
				this.nextElementSibling.classList.add('active');
			});
		}

		function addCategory() {
			const categorySection = document.querySelector('.cat-section');
			
			// Collapse all existing categories
			const allAccordionOptions = document.querySelectorAll('.accordion-options');
			const allChevrons = document.querySelectorAll('.accordion-title i.fa-chevron-down');
			
			allAccordionOptions.forEach(option => {
				option.classList.remove('active');
			});
			
			allChevrons.forEach(chevron => {
				chevron.classList.remove('rotated');
			});
			
			// Clone the first category element
			const firstCategory = document.querySelector('.accordion-item');
			const newCategory = firstCategory.cloneNode(true);
			
			// Clear all input values in the cloned category
			newCategory.querySelectorAll('input, textarea').forEach(input => {
				if (input.type === 'checkbox') {
					input.checked = false;
				} else {
					input.value = '';
				}
			});
			
			// Clear any floated labels in the new category
			newCategory.querySelectorAll('.text-label, .dropdown-label, .search-dropdown-label').forEach(label => {
				label.classList.remove('label-float');
			});
			
			// Insert new category
			categorySection.appendChild(newCategory);
			
			// Update all category numbers
			updateCategoryNumbers();
			
			// Update checkbox IDs to be unique based on new category count
			const newCategoryIndex = categoryCount;
			newCategory.querySelectorAll('input[type="checkbox"]').forEach((checkbox, index) => {
				const oldId = checkbox.id;
				// Create completely unique IDs by combining category index and checkbox index
				const newId = `category${newCategoryIndex}_checkbox${index + 1}`;
				checkbox.id = newId;
				const label = checkbox.nextElementSibling;
				if (label && label.tagName === 'LABEL') {
					label.setAttribute('for', newId);
				}
			});
			
			// Make sure the new category is expanded
			newCategory.querySelector('.fa-chevron-down').classList.add('rotated');
			newCategory.querySelector('.accordion-options').classList.add('active');
			
			// Add event listener to the new category's accordion title
			const newAccordionTitle = newCategory.querySelector('.accordion-title');
			addAccordionEventListener(newAccordionTitle);
			
			// Setup validation listeners for the new category
			setupCategoryValidationListeners();
			
			// Store current input values and their corresponding labels BEFORE re-executing elements.js
			const inputStates = new Map();
			document.querySelectorAll('.text-field, .text-field-dropdown, .text-field-search-dropdown, textarea').forEach(input => {
				const textContainer = input.closest('.text-container');
				if (textContainer) {
					const label = textContainer.querySelector('.text-label, .dropdown-label, .search-dropdown-label');
					if (label && input.value.trim() !== '') {
						inputStates.set(input, {
							value: input.value,
							label: label,
							shouldFloat: true
						});
					}
				}
			});
			
			// Force re-execution of elements.js with a fresh fetch
			fetch('assets/js/elements.js?t=' + Date.now())
				.then(response => response.text())
				.then(code => {
					// Execute the code in global scope
					(1, eval)(code);
					
					// Restore proper label states after elements.js re-execution
					// Use multiple attempts to ensure it sticks
					const restoreLabels = () => {
						console.log('Restoring labels...', inputStates.size);
						inputStates.forEach((state, input) => {
							if (state.shouldFloat && state.label) {
								console.log('Floating label for:', input.value);
								state.label.classList.add('float');
							}
						});
					};
					
					// Restore immediately
					restoreLabels();
					
					// Restore again after a short delay to ensure it sticks
					setTimeout(restoreLabels, 100);
					setTimeout(restoreLabels, 200);
				})
				.catch(error => {
					console.error('Error reloading elements.js:', error);
				});
		}

		// Add event listener to the initial add category button
		document.getElementById('addCategoryBtn').addEventListener('click', addCategory);

		// Password toggle functionality
		function togglePassword(inputId) {
			const passwordInput = document.getElementById(inputId);
			const toggleIcon = document.getElementById(inputId + '-icon');
			
			if (passwordInput.type === 'password') {
				passwordInput.type = 'text';
				toggleIcon.classList.remove('fa-eye');
				toggleIcon.classList.add('fa-eye-slash');
			} else {
				passwordInput.type = 'password';
				toggleIcon.classList.remove('fa-eye-slash');
				toggleIcon.classList.add('fa-eye');
			}
		}

		// Form submission validation
		document.getElementById('registrationForm').addEventListener('submit', function(e) {
			e.preventDefault(); // Prevent form submission
			
			// Validate all sections before submitting
			let allValid = true;
			
			// Validate section 1 (Personal Information)
			if (!validateSection1()) {
				allValid = false;
				showSection(0); // Go to section 1
				return;
			}
			
			// Validate section 2 (Profile Information)
			if (!validateSection2()) {
				allValid = false;
				showSection(1); // Go to section 2
				return;
			}
			
			// For providers, validate additional sections
			if (isProvider) {
				// Validate section 3 (Upload Documents)
				if (!validateSection3()) {
					allValid = false;
					showSection(2); // Go to section 3
					return;
				}
				
				// Validate section 4 (Categories)
				if (!validateSection4()) {
					allValid = false;
					showSection(3); // Go to section 4
					return;
				}
			}
			
			// Validate section 5 (Account Security)
			if (!validateSection5()) {
				allValid = false;
				showSection(4); // Go to section 5
				return;
			}
			
			if (allValid) {
				// All validation passed, submit the form
				alert('Registration form is valid! Ready to submit.');
				// Uncomment the line below to actually submit the form
				// this.submit();
			}
		});



	</script>
	<script src="assets/js/elements.js" defer></script>
</body>

</html>
<html>

<head>
	<link rel="stylesheet" href="assets/css/elements.css">
	<link rel="stylesheet" href="assets/css/GridTemplates.css">
	<link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
	<style>
		* {
			color: #333;
		}

		body {
			font-family: Arial, sans-serif;
			background-color: #00850012;
			margin: 0;
			padding: 0;
		}

		.main-section {
			padding: 40px;
			margin: auto;
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			border: 1px solid #888;
			max-width: 400px;
			width: 100%;
			background-color: #fff;
			border-radius: 10px;
		}

		input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #fff inset !important; /* Force white background */
            box-shadow: 0 0 0 30px #fff inset !important; /* Ensure consistency across browsers */
            -webkit-text-fill-color: #333 !important; /* Text color */
            background-color: #fff !important; /* Explicit background color */
        }

		.section {
			position: relative;
			text-align: center;
			display: none;
			margin-bottom: 30px;
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

		.button-section button{
			width: 100px;
		}

		.text-container,
		.select-container {
			width: 100%;
		}

		.select-container .options {
			text-align: left;
		}

		.toggle-section {
			display: flex;
			justify-content: center;
			margin: 10px auto;
			margin-bottom: 40px;
			border: 1px solid #00850080;
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
			color: #008500;
			transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
		}

		.toggle-button i{
			color: #008500;
		}

		.toggle-button.active {
			background-color: #008500;
			color: white;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
		}

		.toggle-button.active i{
			color: white;
		}
		
		.hr-title-container {
			position: relative;
			text-align: center;
			width: 100%;
			max-width: 400px;
			margin: 20px auto;
		}

		.hr-title {
			position: relative;
			display: inline-block;
			background-color: #fff;
			padding: 0 10px;
			margin: auto;
			font-size: 22px;
			font-weight: bold;
			color: #333;
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
	</style>
</head>

<body>
	<form id="registrationForm" class="form" action="" method="post" enctype="multipart/form-data">
		<div class="main-section">
			<div class="section active">
				<div class="input-field">
					<!-- Toggle for user type -->
					<div class="toggle-section user-change">
						<div class="toggle-button active"><i class="fa-solid fa-user" style="padding-right: 10px"></i>Client</div>
						<div class="toggle-button"><i class="fa-solid fa-user-helmet-safety" style="padding-right: 10px"></i>Provider</div>
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
					<div class="input-grid-1" id="nicField" style="display:none;">
						<div class="text-container">
							<div class="label text-label">NIC *</div>
							<input type="text" class="text-field" name="" id="">
						</div>
					</div>
				</div>
			</div>

			<div class="section">
				<h1>SECTION 2</h1>
				<h2>For Clients & Providers</h2>
				<p>this section contains a section where users are asked their bio, profile picture and social links
				</p>
				<div class="input-field">
					<label for="profilePic">Profile Picture</label>
					<input type="file" name="profilePic" accept="image/*"><br>
					<label for="bio">Bio:</label><br>
					<textarea name="bio" placeholder="Bio (describe yourself)"></textarea><br>
					<input type="text" name="link" value="" placeholder="Social Link (ex:facebook.com/username)">
				</div>
			</div>

			<div class="section">
				<h1>SECTION 3</h1>
				<h2>For Providers Only</h2>
				<p>Providers are asked to upload their NIC Front & Back info and Resume</p>
				<div class="input-field">
					<label for="nicFront">NIC Front: </label>
					<input type="file" name="nicFront" accept="image/*"><br>
					<label for="nicBack">NIC Back: </label>
					<input type="file" name="nicBack" accept="image/*"><br>
					<label for="resume">Resume: </label>
					<input type="file" name="resume" accept=".pdf,.doc,.docx,.xls,.xlsx,.txt"><br>
				</div>
			</div>

			<div class="section">
				<h1>SECTION 4</h1>
				<h2>For Providers Only</h2>
				<p>Providers are asked to add their categories here</p>
				<ul>
					<li>Select Category from Selection</li>
					<li>Service Name</li>
					<li>Description</li>
					<li>Location(for physical services)</li>
				</ul>
				<p>Providers should be given the ability to select as many as categories possible, and for each category
					they select, all the fields are required, categories are divided into two groups, which are physical
					and
					digital.</p>
			</div>

			<div class="section">
				<h1>SECTION 5</h1>
				<h2>For Clients & Providers</h2>
				<p>Both providers and Clients are asked to enter their passwords here.</p>
				<div class="input-field">
					<input type="password" name="password" value="" placeholder="Password"><br>
					<input type="password" name="repassword" value="" placeholder="Re-type password"><br>
				</div>
			</div>

			<div class="button-section">
				<button type="button" class="button outline" id="prevBtn" onclick="prevSection()"><i class="fa-regular fa-arrow-left" style="padding-right: 5px"></i>Back</button>
				<button type="button" class="button" id="nextBtn" onclick="nextSection()">Next<i class="fa-regular fa-arrow-right" style="padding-left: 5px"></i></button>
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

			function nextSection() {
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
		</script>
		<script src="assets/js/elements.js" defer></script>
</body>

</html>
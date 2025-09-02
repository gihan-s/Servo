<html>
<head>
	<style>
		* {
				color: #333;
		}

		.section {
			padding: 5px 20px;
			margin: 7.5% auto 0;
			border: 1px solid #ccc;
			max-width: 800px;
			width: 100%;
			max-height: 600px;
			height: 60%;
			position: relative;
			text-align: center;
			display: none;
		}
		
		.section.active {
			display: block;
		}

		.button-section {
			width: 100%;
			max-width: 800px;
			position: relative;
			margin: 0% auto;
		}
	</style>
</head>
<body>
<form id="registrationForm">
	<div class="section active">
		<h1>SECTION 1</h1>
		<h2>For Clients & Providers</h2>
		<p>this contains a page where clients and providers are asked their basic information</p>
		<p>add a toggle in the first page, to choose whether the user is registering as a client or provider</p>
		<div class="input-field">
			<!-- Toggle for user type -->
			<label style="font-weight:600;">Registering as: </label>
			<button type="button" id="toggleUserType" style="margin-bottom:10px;">Client</button>
			<br>
			<input type="text" name="fname" value="" placeholder="First Name"><br>
			<input type="text" name="lname" value="" placeholder="Last Name"><br>
			<input type="email" name="email" value="" placeholder="Email"><br>
			<input type="text" name="nic" id="nicField" value="" placeholder="NIC Number" style="display:none;"><br>
			<label for="gender">Gender: </label>
			<input type="radio" name="gender" id="gender-male" value="M">
			<label for="gender-male">Male</label>
			<input type="radio" name="gender" id="gender-female" value="F">
			<label for="gender-female">Female</label>
		</div>
		<button type="button" onclick="clearSection1()">Clear</button>
	</div>

	<div class="section">
		<h1>SECTION 2</h1>
		<h2>For Clients & Providers</h2>
		<p>this section contains a section where users are asked their bio, profile picture and social links </p>
		<div class="input-field">
			<label for="profilePic">Profile Picture</label>
			<input type="file" name="profilePic" accept="image/*"><br>
			<label for="bio">Bio:</label><br>
			<textarea name="bio" placeholder="Bio (describe yourself)"></textarea><br>
			<input type="text" name="link" value="" placeholder="Social Link (ex:facebook.com/username)">			
		</div>
		<button type="button" onclick="clearSection2()">Clear</button>
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
		<button type="button" onclick="clearSection3()">Clear</button>
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
		<p>Providers should be given the ability to select as many as categories possible, and for each category they select, all the fields are required, categories are divided into two groups, which are physical and digital.</p>
		<button type="button" onclick="clearSection4()">Clear</button>
	</div>

	<div class="section">
		<h1>SECTION 5</h1>
		<h2>For Clients & Providers</h2>
		<p>Both providers and Clients are asked to enter their passwords here.</p>
		<div class="input-field">
			<input type="password" name="password" value="" placeholder="Password"><br>
			<input type="password" name="repassword" value="" placeholder="Re-type password"><br>
		</div>
		<button type="button" onclick="clearSection5()">Clear</button>
		<button type="submit" action="register_process.php" method="post">Submit</button>
	</div>

	<div class="button-section">
		<button type="button" id="prevBtn" onclick="prevSection()">Prev</button>
		<button type="button" id="nextBtn" onclick="nextSection()">Next</button>
	</div>

	<p>After going thru all sections the user is redirected to the login, and if the registration is unsuccessful they are redirected back to Home page after being shown an error message.</p>

	<script>
		let isProvider = false;
		const toggleBtn = document.getElementById('toggleUserType');
		const nicField = document.getElementById('nicField');
		if (toggleBtn) {
			toggleBtn.addEventListener('click', function() {
				isProvider = !isProvider;
				toggleBtn.textContent = isProvider ? 'Provider' : 'Client';
				nicField.style.display = isProvider ? '' : 'none';
			});
			// Set initial state
			nicField.style.display = isProvider ? '' : 'none';
		}

		ind = 0;
		const sections = document.querySelectorAll(".section");
		const prevBtn = document.getElementById('prevBtn');
		const nextBtn = document.getElementById('nextBtn');

		function updateButtonState() {
			if (ind === 0) {
				prevBtn.disabled = true;
			} else {
				prevBtn.disabled = false;
			}
			if (ind === sections.length - 1) {
				nextBtn.disabled = true;
			} else {
				nextBtn.disabled = false;
			}
		}

		function showSection(index) {
			sections.forEach((sec,i) => {
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
</form>
</body>
</html>

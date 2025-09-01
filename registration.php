<html>
<head>
	<style>
		* {
				color: #333;
		}

		.section {
			padding: 20px 20px;
			margin: 10px 10px;
			border: 1px solid #ccc;
			max-width: 800px;
			width: 100%;
			max-height: 600px;
			height: 60%;
			position: relative;
			margin: 7.5% auto;
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
			margin: auto;
		}
	</style>
</head>
<body>
<form id="registrationForm">
	<div class="section active">
		<h1>SECTION 1</h1>
		<h2>For Clients & Providers</h2>
		<p>this contains a page where clients and providers are asked there basic information</p>
		<p>add a toggle in the first page, to choose whether the user is registering as a client or provider</p>
		<div class="input-field">
			<input type="text" name="fname" value="" placeholder="First Name"><br>
			<input type="text" name="lname" value="" placeholder="Last Name"><br>
			<input type="email" name="email" value="" placeholder="Email"><br>
			<input type="text" name="nic" value="" placeholder="NIC Number"><br>
			<label for="gender">Gender: </label>
			<input type="radio" name="gender" value="M">
			<label for="gender">Male</label>
			<input type="radio" name="gender" value="F">
			<label for="gender">Female</label>
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
		<button type="button" onclick="prevSection()">Prev</button>
		<button type="button" onclick="nextSection()">Next</button>
	</div>

	<p>After going thru all sections the user is redirected to the login, and if the registration is unsuccessful they are redirected back to Home page after being shown an error message.</p>

	<script>
		ind = 0;
		const sections = document.querySelectorAll(".section");

		function showSection(index) {
			sections.forEach((sec,i) => {
				sec.classList.toggle('active', i === index);
			});
		}

		function nextSection() {
			if (ind < sections.length - 1) {
				ind += 1;
			} else {
				ind = 0;
			}
			showSection(ind);
		}

		function prevSection() {
			if (ind > 0) {
				ind -= 1;
			} else {
				ind = sections.length - 1;
			}
			showSection(ind);
		}
	</script>
</form>
</body>
</html>

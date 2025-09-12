<html>
<head>
	<link rel="stylesheet" href="/assets/css/registerStyles.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link
		href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap"
		rel="stylesheet">
	<link rel="stylesheet" href="/assets/css/elements.css">
	<link rel="stylesheet" href="/assets/css/GridTemplates.css">
	<link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">

</head>

<body>
	<header class="header">
		<img src="/assets/img/logo.png" alt="Servo Logo" class="logo">
		<a href="login" class="button" style="text-decoration:none;">
			<i class="fa-solid fa-sign-in-alt" style="padding-right: 5px"></i>Login
		</a>
	</header>
	<?php
	// Pull flashed errors / old values (set in AuthController) without altering design
	if (session_status() === PHP_SESSION_NONE) { session_start(); }
	$errors = $_SESSION['reg_errors'] ?? [];
	$old = $_SESSION['reg_old'] ?? [];
	unset($_SESSION['reg_errors'], $_SESSION['reg_old']);
	function old($key, $default='') { global $old; return htmlspecialchars($old[$key] ?? $default); }
	?>
	<form id="registrationForm" class="form" action="<?= BASE_URL ?>/register" method="post" enctype="multipart/form-data">
		<?php if ($errors): ?>
			<div class="input-grid-1" style="margin:10px 0;">
				<div class="text-container" style="color:#b10000; background:#ffe8e8; padding:10px; border-radius:6px; font-size:14px; line-height:1.4;">
					<strong>There were some problems:</strong>
					<ul style="margin:6px 0 0 18px; padding:0;">
						<?php foreach ($errors as $e): ?>
							<li><?= htmlspecialchars($e) ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		<?php endif; ?>
		<div class="main-section">
			<div class="section active">
				<div class="input-field">
					<!-- Toggle for user type -->
					<div class="toggle-section user-change">
						<div class="toggle-button<?= (old('user_type','client')==='client'? ' active':'') ?>"><i class="fa-solid fa-user"
								style="padding-right: 10px"></i>Client</div>
						<div class="toggle-button<?= (old('user_type')==='provider'? ' active':'') ?>"><i class="fa-solid fa-user-helmet-safety"
								style="padding-right: 10px"></i>Provider</div>
					</div>
					<input type="hidden" name="user_type" id="user_type" value="<?= old('user_type','client') ?>">
					<div class="hr-title-container">
						<span class="hr-title">Personal Information</span>
						<hr class="hr-line">
					</div>
					<!-- Input fields -->
					<div class="input-grid-1">
						<div class="text-container">
							<div class="label text-label">First Name *</div>
							<input type="text" class="text-field" name="first_name" value="<?= old('first_name') ?>" required>
						</div>
					</div>
					<div class="input-grid-1">
						<div class="text-container">
							<div class="label text-label">Last Name *</div>
							<input type="text" class="text-field" name="last_name" value="<?= old('last_name') ?>" required>
						</div>
					</div>
					<div class="input-grid-1">
						<div class="select-container">
							<div class="text-container">
								<div class="label dropdown-label">Gender *</div>
								<input type="text" class="text-field-dropdown" readonly name="gender" value="<?= old('gender') ?>" id="genderInput">
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
							<input type="email" class="text-field" name="email" value="<?= old('email') ?>" required>
						</div>
					</div>
					<div class="input-grid-1">
						<div class="text-container">
							<div class="label text-label">Contact No *</div>
							<input type="text" class="text-field" name="contact_no" value="<?= old('contact_no') ?>" id="">
						</div>
					</div>
					<div class="input-grid-1" id="nicField" style="display:none;">
						<div class="text-container">
							<div class="label text-label">NIC *</div>
							<input type="text" class="text-field" name="nic_no" value="<?= old('nic_no') ?>" id="">
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
						<input type="file" id="fileInput" name="profile_picture" accept="image/*">
					</div>

					<div class="input-grid-1">
						<div class="text-container">
							<div class="label text-label">Bio</div>
							<textarea class="text-field" name="bio" spellcheck="false"><?= old('bio') ?></textarea>
						</div>
					</div>
					<div class="input-grid-1">
						<div class="text-container">
							<div class="label text-label">Website</div>
							<input type="text" class="text-field" name="website" value="<?= old('website') ?>" id="">
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
						<input type="file" id="nicFrontInput" name="nic_front" accept="image/*">
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
						<input type="file" id="nicBackInput" name="nic_back" accept="image/*">
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
									<textarea class="text-field" spellcheck="false"></textarea>
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
					<button type="button" class="button outline" id="addCategoryBtn" style="width: 100%;"><i
							class="fa-solid fa-plus" style="padding-right: 10px"></i>Add Category</button>
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


	<script src="/assets/js/elements.js" defer></script>
	<script src="<?= BASE_URL ?>/assets/js/registerScript.js" defer></script>
	<script>
	// Fallback: gender options click to populate input if main JS missed
	document.addEventListener('DOMContentLoaded', function(){
	  var genderInput = document.getElementById('genderInput');
	  var optionsWrap = genderInput ? genderInput.parentElement.parentElement.querySelector('.options') : null;
	  if(optionsWrap && genderInput){
	    optionsWrap.querySelectorAll('div').forEach(function(opt){
	      opt.addEventListener('click', function(){genderInput.value = this.textContent.trim();});
	    });
	  }
	  // Safety: ensure hidden user_type syncs with active toggle
	  var userTypeHidden = document.getElementById('user_type');
	  var toggleBtns = document.querySelectorAll('.user-change .toggle-button');
	  toggleBtns.forEach(function(btn){
	    btn.addEventListener('click', function(){
	      var isProv = btn.textContent.trim().toLowerCase()==='provider';
	      if(userTypeHidden) userTypeHidden.value = isProv ? 'provider':'client';
	    });
	  });
	});

	// Serialize provider categories before submit (placeholder collects values by data attributes to hidden field)
	(function(){
	  const form = document.getElementById('registrationForm');
	  if(!form) return;
	  let hidden = document.getElementById('provider_categories_json');
	  if(!hidden){ hidden = document.createElement('input'); hidden.type='hidden'; hidden.name='provider_categories_json'; hidden.id='provider_categories_json'; form.appendChild(hidden); }
	  form.addEventListener('submit', function(){
	    if(document.getElementById('user_type').value !== 'provider') return;
	    const data = [];
	    document.querySelectorAll('.accordion-item').forEach(function(item){
	      const catNameInput = item.querySelector('.text-field-search-dropdown');
	      const textFields = item.querySelectorAll('input.text-field');
	      const titleInput = textFields[0];
	      const priceInput = textFields[1];
	      const descInput = item.querySelector('textarea');
	      const categoryId = catNameInput && catNameInput.dataset.id ? parseInt(catNameInput.dataset.id) : 0;
	      if(!categoryId) return;
	      const entry = {
	        category_id: categoryId,
	        title: titleInput ? titleInput.value.trim(): '',
	        description: descInput ? descInput.value.trim(): '',
	        price: priceInput ? priceInput.value.trim(): ''
	      };
	      const multiSelectors = item.querySelectorAll('.multiple-selector');
	      const locContainer = multiSelectors[0];
	      const skillContainer = multiSelectors[1];
	      // Locations
	      const locs = [];
	      if(locContainer){
	        locContainer.querySelectorAll('input[type="checkbox"]').forEach(cb=>{
	          if(cb.checked){
	            if(cb.dataset.locId){ locs.push({type:'loc', id: parseInt(cb.dataset.locId)}); }
	            else if(cb.dataset.distId){ locs.push({type:'dist', id: parseInt(cb.dataset.distId)}); }
	          }
	        });
	      }
	      entry.locations = locs;
	      // Skills
	      let skills = [];
	      if(skillContainer){
	        skills = Array.from(skillContainer.querySelectorAll('input[type="checkbox"][data-skill-id]:checked')).map(cb=>parseInt(cb.dataset.skillId));
	      }
	      entry.skills = skills;
	      data.push(entry);
	    });
	    hidden.value = JSON.stringify(data);
	  });
	})();
	</script>

</body>

</html>
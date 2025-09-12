let isProvider = (document.getElementById('user_type') && document.getElementById('user_type').value === 'provider');
		const toggleBtn = document.querySelectorAll('.user-change .toggle-button');
		const nicField = document.getElementById('nicField');
		const userTypeHidden = document.getElementById('user_type');

		// Preloaded uniqueness data (no AJAX) injected server-side
		const REG_DATA = window.__REG_DATA__ || {clientEmails: [], providerEmails: [], providerNics: [], categories: [], skills: [], locations: [], districts: []};
		const REG_UNIQUE = {clientEmails: REG_DATA.clientEmails, providerEmails: REG_DATA.providerEmails, providerNics: REG_DATA.providerNics};

		// Helpers to float label (align with CSS class 'label-float')
		function floatLabel(input){
			if(!input) return;
			const lbl = input.closest('.text-container')?.querySelector('.text-label, .dropdown-label, .search-dropdown-label, .label');
			if(lbl) lbl.classList.add('label-float');
		}

		function populateCategories(root){
			const optList = root.querySelector('.search-select-container:not(.multiple-selector) .option-list');
			const input = root.querySelector('.search-select-container:not(.multiple-selector) .text-field-search-dropdown');
			if(!optList || !input) return; optList.innerHTML='';
			if(!REG_DATA.categories.length){ optList.innerHTML='<div style="opacity:.6">No categories</div>'; return; }
			REG_DATA.categories.forEach(cat=>{
				const d=document.createElement('div'); d.textContent=cat.Name; d.dataset.id=cat.Category_ID;
				// Use mousedown so it fires before global body mousedown closes dropdown
				d.addEventListener('mousedown', (ev)=>{
					if(!input) return;
					input.value=cat.Name;
					input.dataset.id=cat.Category_ID;
					floatLabel(input);
					const container = input.closest('.search-select-container');
					if(container) container.classList.remove('dropdown-view');
				});
				d.addEventListener('click',()=>{
					if(!input){ console.warn('[Registration] Category input missing when selecting', cat); return; }
					input.value=cat.Name;
					input.dataset.id=cat.Category_ID;
					floatLabel(input);
					// Close dropdown explicitly (elements.js won't auto-bind for dynamically added items)
					const container = input.closest('.search-select-container');
					if(container) container.classList.remove('dropdown-view');
					// Trigger change/input events for any listeners
					input.dispatchEvent(new Event('input',{bubbles:true}));
					input.dispatchEvent(new Event('change',{bubbles:true}));
				});
				optList.appendChild(d);
			});
		}
		function populateLocations(root){
			const locSel = root.querySelectorAll('.multiple-selector')[0]; if(!locSel) return; const list=locSel.querySelector('.option-list'); if(!list) return; list.innerHTML='';
			if(!REG_DATA.districts.length && !REG_DATA.locations.length){
				console.warn('[Registration] No districts / locations data provided in REG_DATA. Seed Districts & Location tables.');
				list.innerHTML='<div style="opacity:.6">No locations (seed Districts & Location tables)</div>';
				return;
			}
			REG_DATA.districts.forEach(d=>{ const id='dist_'+d.ID+'_'+Math.random().toString(36).slice(2,7); const wrap=document.createElement('div'); wrap.innerHTML=`<input type="checkbox" data-dist-id="${d.ID}" id="${id}"><label for="${id}">${d.District} <span style='font-size:10px;color:#666'>(District)</span></label>`; list.appendChild(wrap); });
			REG_DATA.locations.filter(l=>l.City).forEach(l=>{ const id='loc_'+l.Location_ID+'_'+Math.random().toString(36).slice(2,7); const wrap=document.createElement('div'); wrap.innerHTML=`<input type="checkbox" data-loc-id="${l.Location_ID}" id="${id}"><label for="${id}">${l.City} <span style='font-size:10px;color:#666'>(City)</span></label>`; list.appendChild(wrap); });
		}
		function populateSkills(root){
			const skillSel = root.querySelectorAll('.multiple-selector')[1]; if(!skillSel) return; const list=skillSel.querySelector('.option-list'); if(!list) return; list.innerHTML='';
			if(!REG_DATA.skills.length){ list.innerHTML='<div style="opacity:.6">No skills</div>'; return; }
			REG_DATA.skills.forEach(s=>{ const id='skill_'+s.Skill_ID+'_'+Math.random().toString(36).slice(2,7); const wrap=document.createElement('div'); wrap.innerHTML=`<input type="checkbox" data-skill-id="${s.Skill_ID}" id="${id}"><label for="${id}">${s.Skill}</label>`; list.appendChild(wrap); });
		}
		function initCategoryBlock(root){ populateCategories(root); populateLocations(root); populateSkills(root); }

		// Ensure single (category) dropdown in a category block always works even if elements.js misses dynamic options
		function ensureCategoryDropdownBehavior(root){
			const container = root.querySelector('.search-select-container:not(.multiple-selector)');
			if(!container) return; // no single category dropdown found
			const displayInput = container.querySelector('.text-field-search-dropdown');
			const searchInput = container.querySelector('.options .text-field-search');
			const optionList = container.querySelector('.option-list');
			if(!displayInput || !optionList) return;
			// Open on click/focus
			function open(){ container.classList.add('dropdown-view'); if(searchInput){ searchInput.focus(); } }
			displayInput.addEventListener('click', open);
			displayInput.addEventListener('focus', open);
			// Delegate option click
			optionList.addEventListener('click', (e)=>{
				const opt = e.target.closest('div');
				if(!opt || opt.parentElement !== optionList) return;
				const id = opt.dataset.id || '';
				const text = opt.textContent.trim();
				displayInput.value = text;
				if(id) displayInput.dataset.id = id;
				floatLabel(displayInput);
				container.classList.remove('dropdown-view');
			});
		}

		// Generic enhancer for ALL search-select containers (single + multiple) so newly cloned ones open properly
		function ensureSearchDropdowns(root){
			root.querySelectorAll('.search-select-container').forEach(sc=>{
				if(sc.__dropdownBound) return; // JS property not copied by cloneNode
				sc.__dropdownBound = true;
				sc.removeAttribute('data-enhanced'); // clean any copied attribute from source
				const displayInput = sc.querySelector('.text-field-search-dropdown');
				const internalSearch = sc.querySelector('.options .text-field-search');
				function open(){ sc.classList.add('dropdown-view'); if(internalSearch){ internalSearch.focus(); } }
				if(displayInput){
					displayInput.addEventListener('click', e=>{ e.stopPropagation(); open(); });
					displayInput.addEventListener('focus', open);
				}
				sc.addEventListener('click', e=>{ if(!sc.classList.contains('dropdown-view')) open(); });
			});
		}

		// Global click to close any open search-select when clicking outside
		document.addEventListener('click', (e)=>{
			if(e.target.closest('.search-select-container')) return;
			document.querySelectorAll('.search-select-container.dropdown-view').forEach(el=> el.classList.remove('dropdown-view'));
		});

		// Attach label floating behavior for normal text fields & textareas inside a root (for dynamically added categories)
		function attachLabelBehaviors(root){
			root.querySelectorAll('.text-field, .text-field-dropdown, textarea').forEach(inp=>{
				const label = inp.closest('.text-container')?.querySelector('.text-label, .dropdown-label, .label');
				if(!label) return;
				inp.addEventListener('focus', ()=>{ label.classList.add('label-float'); label.style.color='var(--themeColor)'; });
				inp.addEventListener('blur', ()=>{ if(!inp.value.trim()) label.classList.remove('label-float'); label.style.color='var(--textFieldLabelColor)'; });
			});
		}

		function attachMultiSelectBehavior(root){
			root.querySelectorAll('.multiple-selector').forEach(ms=>{
				const input = ms.querySelector('.text-field-search-dropdown');
				if(!input) return;
				const checkboxes = ms.querySelectorAll('input[type="checkbox"]');
				function refresh(){
					const selected = Array.from(checkboxes).filter(cb=>cb.checked).map(cb=>{
						const lbl = ms.querySelector('label[for="'+cb.id+'"]');
						return lbl ? lbl.childNodes[0].textContent.trim() : '';
					}).filter(Boolean);
					if(selected.length===0){ input.value=''; }
					else if(selected.length<=2){ input.value = selected.join(', '); }
					else { input.value = selected.slice(0,2).join(', ') + ' +' + (selected.length-2); }
					floatLabel(input);
				}
				checkboxes.forEach(cb=> cb.addEventListener('change', refresh));
			});
		}

		function enhanceCategorySelection(){
			document.querySelectorAll('.search-select-container:not(.multiple-selector) .option-list div').forEach(div=>{
				div.addEventListener('click', ()=>{
					const input = div.closest('.search-select-container').querySelector('.text-field-search-dropdown');
					if(input){ floatLabel(input); }
				});
			});
		}

		document.addEventListener('DOMContentLoaded', ()=>{
			const firstAcc=document.querySelector('.accordion-item');
			console.log('[Registration] REG_DATA counts', {
				categories: REG_DATA.categories.length,
				skills: REG_DATA.skills.length,
				districts: REG_DATA.districts.length,
				locations: REG_DATA.locations.length
			});
			if(firstAcc){
				initCategoryBlock(firstAcc);
				attachMultiSelectBehavior(firstAcc);
				enhanceCategorySelection();
				ensureCategoryDropdownBehavior(firstAcc);
				ensureSearchDropdowns(firstAcc);
			}

			// Global delegated fallback for category option selection (single-select not multiple-selector)
			document.body.addEventListener('click', function(e){
				const opt = e.target.closest('.search-select-container:not(.multiple-selector) .option-list div');
				if(!opt) return;
				const list = opt.parentElement; if(!list) return;
				const container = list.closest('.search-select-container'); if(!container) return;
				const input = container.querySelector('.text-field-search-dropdown'); if(!input) return;
				input.value = opt.textContent.trim();
				if(opt.dataset.id) input.dataset.id = opt.dataset.id;
				floatLabel(input);
				container.classList.remove('dropdown-view');
					// Debug log
					console.log('[Registration] Category selected via global delegate:', {text: input.value, id: input.dataset.id});
			});

				// Safety: stop clicks on search text box from propagating out prematurely (should not prevent option clicks)
				document.body.addEventListener('mousedown', function(e){
					if(e.target.classList && e.target.classList.contains('text-field-search')){
						// Allow focusing search without closing dropdown
						const wrap = e.target.closest('.search-select-container');
						if(wrap) wrap.classList.add('dropdown-view');
					}
				});
		});

		function checkUniqueIdentifiers() {
			const emailInput = document.querySelector('input[name="email"]');
			const nicInput = document.querySelector('input[name="nic_no"]');
			let ok = true;
			if (emailInput) {
				const emailVal = emailInput.value.trim().toLowerCase();
				if (userTypeHidden.value === 'client') {
					if (REG_UNIQUE.clientEmails.map(e=>e.toLowerCase()).includes(emailVal)) {
						showValidationTooltip(emailInput, 'Email already in use (client)');
						ok = false;
					}
				} else { // provider
					if (REG_UNIQUE.providerEmails.map(e=>e.toLowerCase()).includes(emailVal)) {
						showValidationTooltip(emailInput, 'Email already in use (provider)');
						ok = false;
					}
				}
			}
			if (userTypeHidden.value === 'provider' && nicInput) {
				const nicVal = nicInput.value.trim().toUpperCase();
				if (nicVal && REG_UNIQUE.providerNics.map(n=>n.toUpperCase()).includes(nicVal)) {
					showValidationTooltip(nicInput, 'NIC already in use');
					ok = false;
				}
			}
			return ok;
		}

		toggleBtn.forEach((btn) => {
			btn.addEventListener('click', () => {
				// Determine role based on button text content
				const isProv = btn.textContent.trim().toLowerCase() === 'provider';
				isProvider = isProv;
				if (userTypeHidden) userTypeHidden.value = isProv ? 'provider' : 'client';
				toggleBtn.forEach(b => b.classList.remove('active'));
				btn.classList.add('active');
				nicField.style.display = isProvider ? 'block' : 'none';
			});
		});

		// Show NIC field if old value indicated provider (post-back repopulation scenario)
		if (isProvider && nicField) {
			nicField.style.display = 'block';
		}

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
			// Check if password has all 5 requirements
			const hasLowercase = /[a-z]/.test(password);
			const hasUppercase = /[A-Z]/.test(password);
			const hasNumber = /[0-9]/.test(password);
			const hasSymbol = /[^a-zA-Z0-9]/.test(password);
			const hasMinLength = password.length > 12;
			
			// Password must meet all 5 requirements for validation
			return hasLowercase && hasUppercase && hasNumber && hasSymbol && hasMinLength;
		}

		// Calculate password strength
		function calculatePasswordStrength(password) {
			if (!password) return { strength: 'none', score: 0 };
			
			// Check the 5 key requirements
			const requirements = {
				lowercase: /[a-z]/.test(password),
				uppercase: /[A-Z]/.test(password),
				numbers: /[0-9]/.test(password),
				symbols: /[^a-zA-Z0-9]/.test(password),
				longLength: password.length > 12
			};
			
			// Count how many requirements are met
			const metRequirements = Object.values(requirements).filter(Boolean).length;
			
			// Determine strength based on number of requirements met
			switch (metRequirements) {
				case 1:
					return { strength: 'very-weak', score: 1, text: 'Very Weak' };
				case 2:
					return { strength: 'weak', score: 2, text: 'Weak' };
				case 3:
					return { strength: 'medium', score: 3, text: 'Medium' };
				case 4:
					return { strength: 'strong', score: 4, text: 'Strong' };
				case 5:
					return { strength: 'very-strong', score: 5, text: 'Very Strong' };
				default:
					return { strength: 'very-weak', score: 1, text: 'Very Weak' };
			}
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

			// For clients, make profile image optional so form can submit easily
			if (!isProvider) {
				return true;
			}

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
				showValidationTooltip(passwordInput, 'Password must contain lowercase, uppercase, numbers, symbols, and be more than 12 characters');
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
			// Uniqueness checks happen when leaving section 1 (personal info)
			// Validate current section before proceeding
			if (ind === 0) { // Section 1 validation
					if (!validateSection1()) { return; }
					if (!checkUniqueIdentifiers()) { return; }
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

			// Re-populate dynamic option lists for this new block
			initCategoryBlock(newCategory);
			attachMultiSelectBehavior(newCategory);
			enhanceCategorySelection();
			ensureCategoryDropdownBehavior(newCategory);
			ensureSearchDropdowns(newCategory);
			
			// Update all category numbers
			updateCategoryNumbers();
			
			// (Removed manual checkbox ID rewriting to prevent overlaps & preserve generated IDs)
			
			// Make sure the new category is expanded
			newCategory.querySelector('.fa-chevron-down').classList.add('rotated');
			newCategory.querySelector('.accordion-options').classList.add('active');
			
			// Add event listener to the new category's accordion title
			const newAccordionTitle = newCategory.querySelector('.accordion-title');
			addAccordionEventListener(newAccordionTitle);
			
			// Setup validation listeners for the new category
			setupCategoryValidationListeners();
			
			// Attach label behaviors to new inputs & textareas
			attachLabelBehaviors(newCategory);
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
		const regFormEl = document.getElementById('registrationForm');
		regFormEl.addEventListener('submit', function(e) {
			console.log('[Registration] submit handler triggered');
			console.log('[Registration] user_type value:', userTypeHidden ? userTypeHidden.value : 'N/A');
			let block = false; // decide whether to block submission
			
			// Validate all sections before submitting
			let allValid = true;
			
			// Validate section 1 (Personal Information)
			if (!validateSection1()) { allValid = false; showSection(0); block = true; }
			if (!checkUniqueIdentifiers()) { allValid = false; showSection(0); block = true; }
			
			// Validate section 2 (Profile Information)
			if (!validateSection2()) { allValid = false; showSection(1); block = true; }
			
			// For providers, validate additional sections
			if (isProvider) {
				// Validate section 3 (Upload Documents)
				if (!validateSection3()) { allValid = false; showSection(2); block = true; }
				
				// Validate section 4 (Categories)
				if (!validateSection4()) { allValid = false; showSection(3); block = true; }
			}
			
			// Validate section 5 (Account Security)
			if (!validateSection5()) { allValid = false; showSection(4); block = true; }
			
			if (block || !allValid) {
				// prevent submission due to validation errors
				e.preventDefault();
				return;
			}
			// allow natural form submission (no preventDefault)
		});


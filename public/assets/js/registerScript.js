const userTypeToggles = document.querySelectorAll(".toggle-section.user-change .toggle-button");
for (let i = 0; i < userTypeToggles.length; i++) {
    const element = userTypeToggles[i];

    element.addEventListener("click", () => {
        userTypeToggles[0].classList.remove("active");
        userTypeToggles[1].classList.remove("active");
        element.classList.add("active");
        document.getElementById("user_type").value = element.innerText.toLowerCase();
        changeUIByUserType();
    })

}


const inputs = document.querySelectorAll("input");
for (let i = 0; i < inputs.length; i++) {
    const element = inputs[i];
    if (element.value != '' && element.parentElement.classList.contains("text-container") && element.parentElement.querySelector(".label")) {
        element.parentElement.querySelector(".label").classList.add("label-float");
    }
}

const textAreas = document.querySelectorAll("textarea");
for (let i = 0; i < textAreas.length; i++) {
    const element = textAreas[i];
    if (element.value != '' && element.parentElement.classList.contains("text-container") && element.parentElement.querySelector(".label")) {
        element.parentElement.querySelector(".label").classList.add("label-float");
    }
}


function changeUIByUserType() {

    var userType = document.getElementById("user_type").value;

    if (userType == "provider") {
        document.getElementsByName("nic_no")[0].parentElement.style.display = "block";
        document.getElementsByName("nic_no")[0].required = true;
    } else {
        document.getElementsByName("nic_no")[0].parentElement.style.display = "none";
        document.getElementsByName("nic_no")[0].required = false;
    }

}

if (document.getElementById("registrationForm1")) {
    document.getElementById("registrationForm1").addEventListener("submit", (event) => {
        event.preventDefault();
        if (validateSection1()) {

            const submitButton = document.querySelector("#registrationForm1 button[type=submit]");
            submitButton.style.opacity = '0.7';
            submitButton.disabled = true;
            

            fetch("./register/check-email", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "email=" + encodeURIComponent(document.getElementsByName("email")[0].value)
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status == 'ok') {
                        event.target.submit();
                    } else {
                         showValidationTooltip(document.getElementsByName("email")[0], data.message);

                         submitButton.style.opacity = '1';
            submitButton.disabled = false;

                    }
                })
                .catch(err => console.error(err));
            
            
        }
    })

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
    const nicField = document.getElementsByName('nic_no')[0].parentElement;

    if (nicField && nicField.style.display !== 'none') {
        const nicInput = textInputs[4]; // NIC input (when displayed)
        console.log(nicInput);

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





const fileInput = document.getElementById('fileInput');
const profilePhoto = document.getElementById('profilePhoto');
const profileImage = document.getElementById('profileImage');

if (fileInput) {
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

if (document.getElementById("registrationForm2")) {
    document.getElementById("registrationForm2").addEventListener("submit", (event) => {
        event.preventDefault();
        if (validateSection2()) {
            event.target.submit();
        }
    })
}

// Validate section 2 (Profile Information)
function validateSection2() {
    const fileInput = document.getElementById('fileInput');
    const profileImage = document.getElementById('profileImage');
    let isValid = true;

    // For clients, make profile image optional so form can submit easily
    if (document.getElementById("user_type").value == 'client') {
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



const passwordInput = document.getElementById('password');
const repasswordInput = document.getElementById('repassword');
if (passwordInput) {
    // Password strength and validation
    if (passwordInput) {
        passwordInput.addEventListener('input', function () {
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
        repasswordInput.addEventListener('input', function () {
            hideValidationTooltip(this);

            const password = passwordInput ? passwordInput.value : '';
            if (this.value && password !== this.value) {
                showValidationTooltip(this, 'Passwords do not match');
            }
        });
    }
}


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


if (document.getElementById("registrationForm3")) {
    document.getElementById("registrationForm3").addEventListener("submit", (event) => {
        event.preventDefault();
        if (validateSection3()) {
            event.target.submit();
        }
    })
}


// Validate section 3 (Account Security)
function validateSection3() {
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
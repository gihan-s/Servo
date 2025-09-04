<?php
// Employer signup page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Sign Up</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body { font-family: 'Montserrat', Arial, sans-serif; }
        .signup-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: url('assets/img/landingBG.jpg') no-repeat center center fixed;
            background-size: cover;
            z-index: 0;
        }
        .signup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: #00000057;
            z-index: 1;
        }
        .signup-form-container {
            background: #fff;
            max-width: 500px;
            margin: 70px auto;
            border-radius: 8px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            padding: 40px 30px;
            position: relative;
            z-index: 2;
        }
        .signup-form-title { font-size: 2rem; font-weight: 700; color: #263646; margin-bottom: 30px; text-align: center; }
        .signup-form label { display: block; margin-bottom: 8px; color: #263646; font-weight: 500; }
        .signup-form input { width: 100%; padding: 10px; margin-bottom: 18px; border-radius: 5px; border: 1px solid #d1d5db; font-size: 1rem; }
        .signup-btn { background: #1ec773; color: #fff; border: none; border-radius: 6px; padding: 12px 0; font-size: 1.1rem; font-weight: 600; cursor: pointer; width: 100%; transition: background 0.2s; }
        .signup-btn:hover { background: #17a65e; }
    </style>
</head>
<body>
    <div class="signup-bg"></div>
    <div class="signup-overlay"></div>
    <div class="signup-form-container">
        <div class="signup-form-title">Employer Sign Up</div>
        <form class="signup-form" id="employerSignupForm" autocomplete="off">
            <label for="profilePic">Profile Picture</label>
            <input type="file" id="profilePic" accept="image/*">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required>
            <label for="nic">NIC</label>
            <input type="text" id="nic" name="nic" required>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <label for="contact">Contact Number</label>
            <input type="text" id="contact" name="contact" required>
            <button type="submit" class="signup-btn">Sign Up</button>
        </form>
        <div id="successSection" style="display:none;">
            <div style="text-align:center; margin-bottom:20px;">
                <h2 style="color:#1ec773;">Account Successfully Created!</h2>
                <p>Your employer profile has been created. Here is a preview:</p>
            </div>
            <div id="profilePreview" style="background:#f7f7f7; border-radius:10px; box-shadow:0 2px 8px #0001; padding:24px; max-width:400px; margin:0 auto;">
                <!-- Profile preview will be rendered here -->
            </div>
            <button id="editBtn" class="signup-btn" style="margin-top:20px; background:#263646;">Edit Profile</button>
        </div>
    </div>
<script>
// Helper to read image as base64
function readImageAsBase64(file, callback) {
    const reader = new FileReader();
    reader.onload = function(e) { callback(e.target.result); };
    reader.readAsDataURL(file);
}

const form = document.getElementById('employerSignupForm');
const successSection = document.getElementById('successSection');
const profilePreview = document.getElementById('profilePreview');
const editBtn = document.getElementById('editBtn');
let profilePicData = '';

document.getElementById('profilePic').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        readImageAsBase64(file, function(data) {
            profilePicData = data;
        });
    }
});

form.addEventListener('submit', function(e) {
    e.preventDefault();
    // Validate required fields
    const name = document.getElementById('name').value.trim();
    const nic = document.getElementById('nic').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const contact = document.getElementById('contact').value.trim();
    if (!name || !nic || !email || !password || !contact) {
        alert('Please fill all required fields.');
        return;
    }
    // Save to sessionStorage
    const employerData = { name, nic, email, password, contact, profilePic: profilePicData };
    sessionStorage.setItem('employerProfile', JSON.stringify(employerData));
    // Show success and preview
    renderProfilePreview(employerData);
    form.style.display = 'none';
    successSection.style.display = 'block';
});

function renderProfilePreview(data) {
    profilePreview.innerHTML = `
        <div style="text-align:center;">
            <img src="${data.profilePic || 'assets/img/logo.png'}" alt="Profile Picture" style="width:90px; height:90px; object-fit:cover; border-radius:50%; margin-bottom:16px; border:2px solid #1ec773;">
            <h3 style="color:#263646; margin-bottom:8px;">${data.name}</h3>
            <div style="color:#555; font-size:1rem; margin-bottom:6px;"><strong>NIC:</strong> ${data.nic}</div>
            <div style="color:#555; font-size:1rem; margin-bottom:6px;"><strong>Email:</strong> ${data.email}</div>
            <div style="color:#555; font-size:1rem; margin-bottom:6px;"><strong>Contact:</strong> ${data.contact}</div>
        </div>
    `;
}

editBtn.addEventListener('click', function() {
    // Load data back into form
    const data = JSON.parse(sessionStorage.getItem('employerProfile'));
    document.getElementById('name').value = data.name;
    document.getElementById('nic').value = data.nic;
    document.getElementById('email').value = data.email;
    document.getElementById('password').value = data.password;
    document.getElementById('contact').value = data.contact;
    profilePicData = data.profilePic;
    form.style.display = 'block';
    successSection.style.display = 'none';
});
</script>
</body>
</html>
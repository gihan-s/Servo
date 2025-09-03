<?php
// Service Information Page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Information</title>
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
        .progress-bar {
            width: 100%;
            background: #e9ecec;
            height: 8px;
            border-radius: 8px;
            margin-bottom: 30px;
            overflow: hidden;
        }
        .progress {
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #1ec773 70%, #e9ecec 100%);
            border-radius: 8px;
            transition: width 0.3s;
        }
        .service-form-container {
            background: #fff;
            max-width: 500px;
            margin: 70px auto;
            border-radius: 8px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            padding: 40px 30px;
            position: relative;
            z-index: 2;
        }
        .service-form-title { font-size: 2rem; font-weight: 700; color: #263646; margin-bottom: 30px; text-align: center; }
        .service-form label { display: block; margin-bottom: 8px; color: #263646; font-weight: 500; }
        .service-form select, .service-form textarea, .service-form input { width: 100%; padding: 10px; margin-bottom: 18px; border-radius: 5px; border: 1px solid #d1d5db; font-size: 1rem; }
        .service-form input[type="file"] { margin-bottom: 18px; }
        .service-btn { background: #1ec773; color: #fff; border: none; border-radius: 6px; padding: 12px 0; font-size: 1.1rem; font-weight: 600; cursor: pointer; width: 100%; transition: background 0.2s; }
        .service-btn:hover { background: #17a65e; }
        .preview-container {
            margin-top: 30px;
            text-align: center;
        }
        .preview-card {
            background: #f7f7f7;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            display: inline-block;
            text-align: left;
            max-width: 400px;
        }
        .preview-card .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid #1ec773;
        }
        .edit-btn {
            background: #fff;
            color: #1ec773;
            border: 2px solid #1ec773;
            border-radius: 6px;
            padding: 8px 20px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            margin-top: 20px;
            transition: background 0.2s, color 0.2s;
        }
        .edit-btn:hover {
            background: #1ec773;
            color: #fff;
        }
        @media (max-width: 600px) {
            .service-form-container, .preview-card {
                padding: 20px 5px;
                max-width: 98vw;
            }
        }
    </style>
    <script>
    function validateForm() {
        var serviceType = document.getElementById('service_type').value;
        var about = document.getElementById('about').value.trim();
        if(serviceType === '' || about === '') {
            alert('Please fill all required fields.');
            return false;
        }
        return true;
    }
    </script>
</head>
<body>
    <div class="signup-bg"></div>
    <div class="signup-overlay"></div>
    <div class="service-form-container">
        <div class="progress-bar"><div class="progress" style="width:100%"></div></div>
        <div class="service-form-title">Service Information</div>
        <form class="service-form" method="post" action="#" enctype="multipart/form-data" onsubmit="return false;">
            <label for="profile_pic">Profile Picture</label>
            <input type="file" id="profile_pic" name="profile_pic" accept="image/*">
            <label for="service_type">Service Type</label>
            <select id="service_type" name="service_type" required>
                <option value="">Select Service Type</option>
                <option value="Electrician">Electrician</option>
                <option value="Plumber">Plumber</option>
                <option value="Teacher">Teacher</option>
                <option value="Photographer">Photographer</option>
                <option value="Videographer">Videographer</option>
                <option value="Butler">Butler</option>
            </select>
            <label for="about">About You</label>
            <textarea id="about" name="about" rows="5" required placeholder="Tell us about yourself..."></textarea>
            <label for="experience">Experience <span style="color:#888;font-size:0.9em;">(Optional)</span></label>
            <input type="text" id="experience" name="experience" placeholder="e.g. 5 years in the field">
            <button type="button" class="service-btn" onclick="showSuccessAndPreview()">Submit</button>
        </form>
        <div id="success-message" style="display:none;">
            <div class="preview-container">
                <h2 style="color:#1ec773;">Account Successfully Created!</h2>
                <div id="account-preview" class="preview-card">
                    <!-- Preview will be injected here -->
                </div>
                <button class="edit-btn" onclick="editAccount()">Edit</button>
            </div>
        </div>
    </div>
</body>
<script>
function showSuccessAndPreview() {
    var serviceType = document.getElementById('service_type').value;
    var about = document.getElementById('about').value.trim();
    var experience = document.getElementById('experience').value.trim();
    var profilePicInput = document.getElementById('profile_pic');
    if(serviceType === '' || about === '') {
        alert('Please fill all required fields.');
        return;
    }
    // Save service info to sessionStorage
    sessionStorage.setItem('service_type', serviceType);
    sessionStorage.setItem('about', about);
    sessionStorage.setItem('experience', experience);
    // Show success message
    document.querySelector('.service-form').style.display = 'none';
    document.getElementById('success-message').style.display = 'block';
    // Get previous info from sessionStorage (set in general_information.php)
    var name = sessionStorage.getItem('name') || '';
    var nic = sessionStorage.getItem('nic') || '';
    var address1 = sessionStorage.getItem('address1') || '';
    var address2 = sessionStorage.getItem('address2') || '';
    var city = sessionStorage.getItem('city') || '';
    var country = sessionStorage.getItem('country') || '';
    var contact = sessionStorage.getItem('contact') || '';
    var email = sessionStorage.getItem('email') || '';
    var service_type = sessionStorage.getItem('service_type') || '';
    var about_you = sessionStorage.getItem('about') || '';
    var experience_val = sessionStorage.getItem('experience') || '';
    // Handle profile picture preview
    var profilePicHtml = '';
    if(profilePicInput.files && profilePicInput.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            profilePicHtml = `<img src='${e.target.result}' class='profile-pic' alt='Profile Picture'>`;
            renderPreview(profilePicHtml);
        };
        reader.readAsDataURL(profilePicInput.files[0]);
    } else {
        profilePicHtml = `<img src='https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=1ec773&color=fff&size=100' class='profile-pic' alt='Profile Picture'>`;
        renderPreview(profilePicHtml);
    }
    function renderPreview(profilePicHtml) {
        var previewHtml = `${profilePicHtml}
            <strong>Full Name:</strong> ${name}<br>
            <strong>NIC:</strong> ${nic}<br>
            <strong>Address:</strong> ${address1}<br>${address2}<br>${city}, ${country}<br>
            <strong>Contact Number:</strong> ${contact}<br>
            <strong>Email:</strong> ${email}<br>
            <strong>Service Type:</strong> ${service_type}<br>
            <strong>About You:</strong> ${about_you}<br>
            <strong>Experience:</strong> ${experience_val ? experience_val : 'N/A'}<br>`;
        document.getElementById('account-preview').innerHTML = previewHtml;
    }
}

function editAccount() {
    // Show form again for editing
    document.querySelector('.service-form').style.display = 'block';
    document.getElementById('success-message').style.display = 'none';
    // Restore values from sessionStorage
    document.getElementById('service_type').value = sessionStorage.getItem('service_type') || '';
    document.getElementById('about').value = sessionStorage.getItem('about') || '';
    document.getElementById('experience').value = sessionStorage.getItem('experience') || '';
}
</script>
</html>

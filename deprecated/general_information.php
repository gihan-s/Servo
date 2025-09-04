<?php
// General Information Page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General Information</title>
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
            width: 50%;
            height: 100%;
            background: linear-gradient(90deg, #1ec773 70%, #e9ecec 100%);
            border-radius: 8px;
            transition: width 0.3s;
        }
        .info-form-container {
            background: #fff;
            max-width: 500px;
            margin: 70px auto;
            border-radius: 8px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            padding: 40px 30px;
            position: relative;
            z-index: 2;
        }
        .info-form-title { font-size: 2rem; font-weight: 700; color: #263646; margin-bottom: 30px; text-align: center; }
        .info-form label { display: block; margin-bottom: 8px; color: #263646; font-weight: 500; }
        .info-form input { width: 100%; padding: 10px; margin-bottom: 18px; border-radius: 5px; border: 1px solid #d1d5db; font-size: 1rem; }
        .info-form .address-group input { margin-bottom: 8px; }
        .info-form input[type="file"] { margin-bottom: 18px; }
        .info-btn { background: #1ec773; color: #fff; border: none; border-radius: 6px; padding: 12px 0; font-size: 1.1rem; font-weight: 600; cursor: pointer; width: 100%; transition: background 0.2s; }
        .info-btn:hover { background: #17a65e; }
        @media (max-width: 600px) {
            .info-form-container {
                padding: 20px 5px;
                max-width: 98vw;
            }
        }
    </style>
</head>
<body>
    <div class="signup-bg"></div>
    <div class="signup-overlay"></div>
    <div class="info-form-container">
        <div class="progress-bar"><div class="progress" style="width:50%"></div></div>
        <div class="info-form-title">General Information</div>
        <form class="info-form" method="post" action="#" enctype="multipart/form-data">
            <label for="profile_pic">NIC Front Side</label>
            <input type="file" id="profile_pic" name="profile_pic" accept="image/*">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required>
            <label for="nic">NIC Number</label>
            <input type="text" id="nic" name="nic" required>
            <label>Address</label>
            <div class="address-group">
                <input type="text" id="address1" name="address1" placeholder="Address Line 1" required>
                <input type="text" id="address2" name="address2" placeholder="Address Line 2" required>
                <input type="text" id="city" name="city" placeholder="City" required>
                <input type="text" id="country" name="country" placeholder="Country" required>
            </div>
            <label for="contact">Contact Number</label>
            <input type="text" id="contact" name="contact" required>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <button type="button" class="info-btn" onclick="validateAndContinue()">Continue</button>
        </form>
    </div>
</body>
<script>
function validateAndContinue() {
    var requiredFields = [
        'name', 'nic', 'address1', 'address2', 'city', 'country', 'contact', 'email'
    ];
    var allFilled = true;
    for (var i = 0; i < requiredFields.length; i++) {
        var field = document.getElementById(requiredFields[i]);
        if (!field.value.trim()) {
            alert('Please fill all fields.');
            field.focus();
            allFilled = false;
            break;
        }
    }
    // Handle profile picture
    var profilePicInput = document.getElementById('profile_pic');
    if (allFilled) {
        requiredFields.forEach(function(key) {
            sessionStorage.setItem(key, document.getElementById(key).value.trim());
        });
        // Save profile picture as base64 in sessionStorage
        if(profilePicInput.files && profilePicInput.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                sessionStorage.setItem('profile_pic', e.target.result);
                window.location.href = 'service_information.php';
            };
            reader.readAsDataURL(profilePicInput.files[0]);
        } else {
            sessionStorage.removeItem('profile_pic');
            window.location.href = 'service_information.php';
        }
    }
}
</script>
</html>

<?php $BaseURL = "." ?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servo | Get Started</title>

    <link rel="stylesheet" href="<?= $BaseURL ?>/assets/css/registerStyles.css">
    <link rel="stylesheet" href="<?= $BaseURL ?>/assets/css/elementStyles.css">
    <link rel="stylesheet" href="<?= $BaseURL ?>/assets/css/gridTemplates.css">
    <link rel="stylesheet" href="<?= $BaseURL ?>/assets/css/register-inline.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="<?= $BaseURL ?>/assets/js/registerScript.js" defer></script>
</head>

<body>

    <?php include 'header.php' ?>

    <form id="registrationForm1" class="form" action="./register/personalsubmit" method="post" enctype="multipart/form-data" novalidate>

        <div class="main-section">

            <img src="<?= BASE_URL ?>/assets/img/logo.png" alt="Servo">

            <div id="register-header" class="register-header">
                <h2>Sign Up as a Client</h2>
            </div>

            <div id="user-description" class="user-description">
                Looking to hire services.
            </div>

            <div class="toggle-section user-change">
                <div class="toggle-button active"><i class="fa-solid fa-user"></i>Client</div>
                <div class="toggle-button"><i class="fa-solid fa-user-helmet-safety"></i>Provider</div>
            </div>

            <div class="section active">

                <div class="input-field">

                    <input type="hidden" name="user_type" id="user_type" value="client" />

                    <div class="hr-title-container">
                        <span class="hr-title">Personal Information</span>
                        <hr class="hr-line">
                    </div>

                    <?php
                        function getFromSession($name)
                        {
                            return isset($_SESSION["register"][$name]) ? $_SESSION["register"][$name] : "";
                        }
                    ?>

                    <!-- Input fields -->
                    <div class="input-grid-1">
                        <div class="text-container">
                            <div class="label text-label">First Name *</div>
                            <input type="text" class="text-field" name="first_name" value="<?= getFromSession("first_name") ?>" required>
                        </div>

                        <div class="text-container">
                            <div class="label text-label">Last Name *</div>
                            <input type="text" class="text-field" name="last_name" value="<?= getFromSession("last_name") ?>" required>
                        </div>

                        <div class="select-container">
                            <div class="text-container">
                                <div class="label dropdown-label">Gender *</div>
                                <input type="text" class="text-field-dropdown" name="gender" value="<?= getFromSession("gender") ?>" id="genderInput" autocomplete="off" onkeydown="return false" required>
                            </div>

                            <div class="options">
                                <div>Male</div>
                                <div>Female</div>
                                <div>Prefer not to say</div>
                            </div>
                        </div>

                        <div class="text-container">
                            <div class="label text-label">Email *</div>
                            <input type="email" class="text-field" name="email" value="<?= getFromSession("email") ?>" required>
                        </div>

                        <div class="text-container">
                            <div class="label text-label">Contact No *</div>
                            <input type="text" class="text-field" name="contact_no" value="<?= getFromSession("contact_no") ?>" id="" required>
                        </div>

                        <div class="text-container nic-field-hidden" style="display: none;">
                            <div class="label text-label">NIC *</div>
                            <input type="text" class="text-field" name="nic_no" value="<?= getFromSession("nic_no") ?>" id="">
                        </div>

                    </div>


                    <div class="button-section">
                        <button type="submit" class="button" id="nextBtn">Next
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </form>


    <form id="EmailVerificationForm">
        <div class="dialog-box-2" id="EmailVerificationDialog">
            <div class="dialog-content" style="width: 400px;">
                <div class="dialog-title">
                    <div class="title">Email Verification</div>
                    <div>
                        <i class="fa-solid fa-xmark dialog-close-button-2"
                            onclick="closeDialogBox('EmailVerificationDialog')"></i>
                    </div>
                </div>

                <p>We have sent a verification code to your email. Please enter it below to verify your account.</p>
                <input type="text" id="Email_OTP" maxlength="6" minlength="6" required>


                <button type="submit" class="button">Verify</button>
            </div>
        </div>
    </form>

    <style>
        #EmailVerificationDialog p {
            font-size: 0.9em;
            opacity: 0.8;
            text-align: center;
        }

        #EmailVerificationDialog input {
            width: 100%;
            font-size: 1.8em;
            padding: 7px;
            border: none;
            outline: 2px solid #00000030;
            border-radius: 10px;
            text-align: center;
            font-weight: bold;
            margin-top: 15px;
            letter-spacing: 15px;
            transition: all 0.3s;
            color: #282828;
        }

        #EmailVerificationDialog input:focus {
            outline: 2px solid var(--primary-color);
        }

        #EmailVerificationDialog button{
            width: 100%;
            margin-top: 15px;
        }
    </style>


</body>
</html>

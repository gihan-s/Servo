<?php
$BaseURL = "..";
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servo | Get Started</title>

    <link rel="stylesheet" href="<?= $BaseURL ?>/assets/css/registerStyles.css">
    <link rel="stylesheet" href="<?= $BaseURL ?>/assets/css/register-footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?= $BaseURL ?>/assets/css/elementStyles.css">
    <link rel="stylesheet" href="<?= $BaseURL ?>/assets/css/gridTemplates.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">

    <script src="<?= $BaseURL ?>/assets/js/elementScript.js" defer></script>
    <script src="<?= $BaseURL ?>/assets/js/registerScript.js" defer></script>


</head>

<body>

    <?php include 'header.php' ?>

    <form id="registrationForm3" class="form" action="./passwordsubmit" method="post" enctype="multipart/form-data" novalidate>

        <div class="main-section">

            <div class="section active">

                <div class="input-field">

                    <div class="hr-title-container" style="margin-bottom: 30px;">
                        <span class="hr-title">Account Security</span>
                        <hr class="hr-line">
                    </div>

                    <?php

                    function getFromSession($name)
                    {
                        return isset($_SESSION["register"][$name]) ? $_SESSION["register"][$name] : "";
                    }

                    ?>


                    <input type="hidden" name="user_type" id="user_type" value="<?= getFromSession("user_type") ?>">


                    <div class="input-field">

                        <div class="input-grid-1" style="gap: 5px !important;">

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


                    <div class="button-section">

                        <button type="button" onclick="previousStep();" class="button outline">Back
                            <i class="fa-regular fa-arrow-left" style="padding-left: 5px"></i>
                        </button>

                        <button type="submit" class="button" id="nextBtn">Register
                            <i class="fa-regular fa-arrow-right" style="padding-left: 5px"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </form>

</body>

</html>


<script>

    function previousStep(){
        if (document.getElementById("user_type").value == 'client') {
            window.location = `../register/profile`
        } else {
            window.location = `../register/services`
        }
    }
</script>

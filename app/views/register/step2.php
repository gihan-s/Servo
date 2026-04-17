<?php
$BaseURL = "..";
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servo | Get Started</title>

    <link rel="stylesheet" href="<?= $BaseURL ?>/assets/css/registerStyles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="<?= $BaseURL ?>/assets/css/elementStyles.css">
    <link rel="stylesheet" href="<?= $BaseURL ?>/assets/css/gridTemplates.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="<?= $BaseURL ?>/assets/js/registerScript.js" defer></script>


</head>

<body>

    <?php include 'header.php' ?>

    <form id="registrationForm2" class="form" action="./profilesubmit" method="post" enctype="multipart/form-data" novalidate>

        <div class="main-section">

            <div class="section active">

                <div class="input-field">

                    <div class="hr-title-container">
                        <span class="hr-title">Profile Information</span>
                        <hr class="hr-line">
                    </div>

                    <?php

                    function getFromSession($name)
                    {
                        return isset($_SESSION["register"][$name]) ? $_SESSION["register"][$name] : "";
                    }

                    ?>


                    <input type="hidden" name="user_type" id="user_type" value="<?= getFromSession("user_type") ?>">


                    <!-- Input fields -->

                    <div class="input-field">
                        <div class="profile-container">
                            <div class="profile-photo" id="profilePhoto">
                                <i class="fas fa-user"></i>

                                <img id="profileImage" src="<?= "/file/temp-images/" . getFromSession('profile_picture') ?>" alt="Profile Picture">
                            </div>
                            <label for="fileInput" class="upload-button"><i class="fa-solid fa-circle-plus"></i></label>
                            <input type="file" id="fileInput" name="profile_picture" accept="image/*">
                        </div>

                        <div class="input-grid-1">
                            <div class="text-container">
                                <div class="label text-label">Bio</div>
                                <textarea class="text-field" name="bio" spellcheck="false"><?= getFromSession('bio') ?></textarea>
                            </div>

                            <div class="text-container">
                                <div class="label text-label">Website</div>
                                <input type="text" class="text-field" name="website" value="<?= getFromSession('website') ?>" id="">
                            </div>
                        </div>
                    </div>

                    <div style="<?= getFromSession("user_type") == 'provider' ? "" : "display: none;" ?>">

                        <div class="hr-title-container" style="margin-top: 25px;">
                            <span class="hr-title">Social Media Links</span>
                            <hr class="hr-line">
                        </div>

                        <div class="social-media-link-wrapper">
                            <div>
                                <span>
                                    <i class="fa-brands fa-linkedin"></i>
                                    Linked In
                                </span>
                                <input type="text" name="social_media_linkedin" value="<?= getFromSession('linkedin') ?>">
                            </div>
                            <div>
                                <span>
                                    <i class="fa-brands fa-facebook"></i>
                                    Facebook
                                </span>
                                <input type="text" name="social_media_facebook" value="<?= getFromSession('facebook') ?>">
                            </div>
                            <div>
                                <span>
                                    <i class="fa-brands fa-instagram"></i>
                                    Instagram
                                </span>
                                <input type="text" name="social_media_instagram" value="<?= getFromSession('instagram') ?>">
                            </div>
                            <div>
                                <span>
                                    <i class="fa-brands fa-tiktok"></i>
                                    Tiktok
                                </span>
                                <input type="text" name="social_media_tiktok" value="<?= getFromSession('tiktok') ?>">
                            </div>
                            <div>
                                <span>
                                    <i class="fa-brands fa-youtube"></i>
                                    Youtube
                                </span>
                                <input type="text" name="social_media_youtube" value="<?= getFromSession('youtube') ?>">
                            </div>
                            <div>
                                <span>
                                    <i class="fa-brands fa-github"></i>
                                    Github
                                </span>
                                <input type="text" name="social_media_github" value="<?= getFromSession('github') ?>">
                            </div>
                        </div>

                    </div>

                    <div class="button-section">

                        <button type="button" onclick="window.location = `../register`" class="button outline">Back
                            <i class="fa-solid fa-arrow-left" style="padding-left: 5px"></i>
                        </button>

                        <button type="submit" class="button" id="nextBtn">Next
                            <i class="fa-solid fa-arrow-right" style="padding-left: 5px"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </form>

</body>

</html>


<?php
if (getFromSession('profile_picture') != '') {
?>
    <script>
        window.addEventListener("load", () => {
            const profilePhoto = document.getElementById('profilePhoto');
            const profileImage = document.getElementById('profileImage');
            profileImage.style.display = 'block'; // Show the image
            profilePhoto.querySelector('i').style.display = 'none';
        })
    </script>
<?php
}

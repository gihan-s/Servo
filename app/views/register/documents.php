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

    <form id="registrationForm4" class="form" action="./documentsubmit" method="post" enctype="multipart/form-data" novalidate>

        <div class="main-section">

            <div class="section active">

                <div class="input-field">

                    <div class="hr-title-container">
                        <span class="hr-title">Upload Documents</span>
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
					<!-- NIC Front -->
					<div class="nic-container">
						<span class="label">NIC Front</span>
						<div class="nic-photo" id="nicFrontPhoto">
							<i class="fas fa-id-card"></i>
							<img id="nicFrontImage" alt="NIC Front" src="<?= "/file/temp-images/" . getFromSession('nic_front') ?>">
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
							<img id="nicBackImage" alt="NIC Back" src="<?= "/file/temp-images/" . getFromSession('nic_back') ?>">
						</div>
						<label class="button upload-button" for="nicBackInput"><i class="fa-solid fa-plus"
								style="padding-right: 10px"></i>Upload</label>
						<input type="file" id="nicBackInput" name="nic_back" accept="image/*">
					</div>
				</div>
				<div class="input-grid-1">
					<div class="text-container">
						<div class="label text-label label-float">Resume</div>
						<input type="file" class="text-field" name="resume" accept=".pdf">
					</div>
				</div>


                    <div class="button-section">

                        <button type="button" onclick="window.location = `../register/profile`" class="button outline">Back
                            <i class="fa-regular fa-arrow-left" style="padding-left: 5px"></i>
                        </button>

                        <button type="submit" class="button" id="nextBtn">Next
                            <i class="fa-regular fa-arrow-right" style="padding-left: 5px"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </form>

</body>

</html>


<?php
if (getFromSession('nic_front') != '') {
?>
    <script>
        window.addEventListener("load", () => {
            const profilePhoto = document.getElementById('nicFrontPhoto');
            const profileImage = document.getElementById('nicFrontImage');
            profileImage.style.display = 'block'; // Show the image
            profilePhoto.querySelector('i').style.display = 'none';
        })
    </script>
<?php
}

if (getFromSession('nic_back') != '') {
?>
    <script>
        window.addEventListener("load", () => {
            const profilePhoto = document.getElementById('nicBackPhoto');
            const profileImage = document.getElementById('nicBackImage');
            profileImage.style.display = 'block'; // Show the image
            profilePhoto.querySelector('i').style.display = 'none';
        })
    </script>
<?php
}

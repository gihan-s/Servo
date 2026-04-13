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
    <link rel="stylesheet" href="<?= $BaseURL ?>/assets/css/registerServices.css">

    <script src="<?= $BaseURL ?>/assets/js/elementScript.js" defer></script>
    <script src="<?= $BaseURL ?>/assets/js/registerScript.js" defer></script>
    <script src="<?= $BaseURL ?>/assets/js/addServiceScript.js" defer></script>


</head>

<body>

    <?php include 'header.php' ?>

    <form id="registrationForm5" class="form" action="./servicesubmit" method="post" enctype="multipart/form-data" novalidate>

        <div class="main-section">

            <div class="section active">

                <div class="input-field">

                    <div class="hr-title-container">
                        <span class="hr-title">Service Areas</span>
                        <hr class="hr-line">
                    </div>

                    <?php

                    function getFromSession($name)
                    {
                        return isset($_SESSION["register"][$name]) ? $_SESSION["register"][$name] : "";
                    }


                    ?>

                    <div id="service-card-wrapper">
                        <?php
                        if (isset($_SESSION["register"]["category_id"]) && count($_SESSION["register"]["category_id"]) > 0) {
                            foreach ($_SESSION["register"]["category_id"] as $key => $value) {
                        ?>

                                <div class="search-item">
                                    <div class="status-badge status-active"><?= $_SESSION["register"]["category_name"][$key] ?></div>

                                    <div class="post-header">
                                        <div class="post-meta">

                                        </div>
                                        <div class="post-actions">
                                            <button type="button" onclick='deleteService(this)' class="action-btn btn-delete">
                                                <i class="fas fa-trash"></i>
                                                Delete
                                            </button>
                                        </div>
                                    </div>

                                    <h3 class="post-title"><?= $_SESSION["register"]["title"][$key] ?></h3>

                                    <div class="post-description">
                                        <?= $_SESSION["register"]["description"][$key] ?>
                                    </div>

                                    <div class="post-skills">
                                        <span class="skills-label">Skills:</span>
                                        <div class="skills-tags">
                                            <span class="skill-tag">
                                                <?= join("</span><span class='skill-tag'>", json_decode($_SESSION["register"]["skills"][$key])) ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="post-footer">
                                        <div class="post-details">
                                            <div class="detail-item">
                                                <span class="detail-label">
                                                    <i class="fa-solid fa-circle-dollar"></i>
                                                    Rs. <?= number_format($_SESSION["register"]["default_price"][$key], 2) ?> / <?= $_SESSION["register"]["price_type"][$key] ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="engagement-stats">
                                        <div class="stat-item">
                                            <i class="fas fa-location-pin"></i>
                                            <?= join(", ", json_decode($_SESSION["register"]["locations"][$key])) ?>
                                        </div>
                                    </div>

                                    <div hidden>
                                        <input type='hidden' name='category_id[]' value='<?= $_SESSION["register"]["category_id"][$key] ?>'>
                                        <input type='hidden' name='category_name[]' value='<?= $_SESSION["register"]["category_name"][$key] ?>'>
                                        <input type='hidden' name='title[]' value='<?= $_SESSION["register"]["title"][$key] ?>'>
                                        <input type='hidden' name='description[]' value='<?= $_SESSION["register"]["description"][$key] ?>'>
                                        <input type='hidden' name='default_price[]' value='<?= $_SESSION["register"]["default_price"][$key] ?>'>
                                        <input type='hidden' name='skills[]' value='<?= $_SESSION["register"]["skills"][$key] ?>'>
                                        <input type='hidden' name='locations[]' value='<?= $_SESSION["register"]["locations"][$key] ?>'>

                                        <input type='hidden' name='portfolio_link[]' value='<?= $_SESSION["register"]["portfolio_link"][$key] ?>'>
                                        <input type='hidden' name='price_type[]' value='<?= $_SESSION["register"]["price_type"][$key] ?>'>
                                        <input type='hidden' name='price_negotiability[]' value='<?= $_SESSION["register"]["price_negotiability"][$key] ?>'>
                                    </div>
                                </div>

                        <?php
                            }
                        } else {
                            echo "<p>No service selected</p>";
                        }
                        ?>
                    </div>


                    <div class="top-button-wrapper">
                        <button type="button" class="button" id="nextBtn" onclick="viewDialogBox('AddServiceDialog')">
                            <i class="fa-solid fa-plus" style="padding-right: 5px"></i>
                            Add Service
                        </button>
                    </div>

                    <div class="button-section">

                        <button type="button" onclick="window.location = `../register/documents`" class="button outline">Back
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


    <?php include 'addServiceDialog.php' ?>


</body>

</html>


<div class="dialog-box-2" id="AddMinimumOneDialog">
    <div class="dialog-content" style="width: 400px;">
        <div class="dialog-title">
            <div class="title">Sorry!</div>

            <div>
                <i class="fa-solid fa-xmark dialog-close-button-2"
                    onclick="closeDialogBox('AddMinimumOneDialog')"></i>
            </div>
        </div>

        <i class="fa-solid fa-circle-exclamation" style="font-size: 1em; margin-right: 10px;"></i>
        Add minimum one service to continue.

    </div>

</div>
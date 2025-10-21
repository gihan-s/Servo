<?php
$BaseURL = "..";
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servo - Get Started</title>

    <link rel="stylesheet" href="<?= $BaseURL ?>/assets/css/registerStyles.css">
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
                                                    Rs. <?= number_format($_SESSION["register"]["default_price"][$key], 2) ?> / hr
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
                            <i class="fa-regular fa-plus" style="padding-right: 5px"></i>
                            Add Service
                        </button>
                    </div>

                    <div class="button-section">

                        <button type="button" onclick="window.location = `../register/documents`" class="button outline">Back
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


    <div class="dialog-box-2" id="AddServiceDialog">
        <div class="dialog-content" style="width: 500px;">
            <div class="dialog-title">
                <div class="title">Add Service</div>
                <div>
                    <i class="fa-solid fa-xmark dialog-close-button-2"
                        onclick="closeDialogBox('AddServiceDialog')"></i>
                </div>
            </div>


            <div class="input-grid-1">

                <div class="search-select-container" data-idinput="Category_ID">
                    <div class="text-container">
                        <div class="label search-dropdown-label">Service Category</div>
                        <input type="text" class="text-field-search-dropdown" id="Category" autocomplete="off" onkeydown="return false">
                    </div>
                    <div class="options">
                        <span class="text-container">
                            <input type="text" class="text-field-search">
                        </span>
                        <div class="option-list" onclick="selectCategory()">
                            <?php foreach ($Categories as $Category): ?>
                                <div data-id="<?= $Category['Category_ID'] ?>"><?= htmlspecialchars($Category['Name']) ?></div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>

                <input type="hidden" id="Category_ID">

                <div class="text-container">
                    <div class="label text-label">Title</div>
                    <input type="text" class="text-field" id="Title">
                </div>


                <div class="text-container">
                    <div class="label text-label">Description</div>
                    <textarea class="text-field" spellcheck="false" id="Description"></textarea>
                </div>


                <div class="text-container">
                    <div class="label text-label">Hourly Rate (Rs.)</div>
                    <input type="number" step="any" class="text-field" id="Default_Price">
                </div>

            </div>

            <div style="display: none;" id="SkillArea">

                <h5>Skills</h5>

                <div style="display: flex; gap: 15px; margin-bottom: 15px;">

                    <div class="search-select-container add-option" style="width: 100%;">

                        <div class="text-container">
                            <div class="label search-dropdown-label">Skill</div>
                            <input type="text" class="text-field-search-dropdown" id="SkillAddInput" autocomplete="off" onkeydown="return false">
                        </div>

                        <div class="options">

                            <span class="text-container">
                                <input type="text" class="text-field-search" placeholder="Enter new skill to add">
                            </span>

                            <div class="option-list" id="SkillsOptionList">

                            </div>
                        </div>
                    </div>

                    <button class="button" style="white-space: nowrap;" onclick="addSkill();">
                        <i class="fa-solid fa-plus" style="margin-right: 10px;"></i>
                        Add
                    </button>

                </div>

                <div class="chip-wrapper" id="SkillsChips">
                    <input type="hidden" id="Skills">

                    <p>No skill selected</p>
                </div>


                <h5>Locations</h5>

                <div style="display: flex; gap: 15px; margin-bottom: 15px;">

                    <div class="search-select-container" style="flex: 1;">
                        <div class="text-container">
                            <div class="label search-dropdown-label">District</div>
                            <input type="text" class="text-field-search-dropdown" autocomplete="off" onkeydown="return false"
                                id="District">
                        </div>

                        <div class="options">

                            <span class="text-container">
                                <input type="text" class="text-field-search">
                            </span>

                            <div class="option-list" onclick="selectDistrict()">

                                <?php foreach ($Districts as $District): ?>
                                    <div><?= htmlspecialchars($District['District']) ?></div>
                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>


                    <div class="search-select-container" style="flex: 1;">
                        <div class="text-container">
                            <div class="label search-dropdown-label">City</div>
                            <input type="text" class="text-field-search-dropdown" autocomplete="off" onkeydown="return false"
                                id="City">
                        </div>

                        <div class="options">

                            <span class="text-container">
                                <input type="text" class="text-field-search">
                            </span>

                            <div class="option-list">
                            </div>
                        </div>
                    </div>


                    <button class="button" style="white-space: nowrap;" onclick="addLocation();">
                        <i class="fa-solid fa-plus" style="margin-right: 10px;"></i>
                        Add
                    </button>

                </div>

                <div class="chip-wrapper" id="LocationChips">
                    <input type="hidden" id="Locations">

                    <p>No location selected</p>
                </div>



                <button class="button" type="button" style="width: 100%; margin-top: 40px;" onclick="validateData();">
                    <i class="fa-solid fa-plus" style="margin-right: 10px;"></i>
                    Add Service
                </button>

            </div>


        </div>

    </div>






</body>

</html>

<script>
    function deleteService(element) {
        element.parentElement.parentElement.parentElement.remove();
    }

    function validateData() {

        var Category = document.getElementById("Category").value;
        var Title = document.getElementById("Title").value;
        var Description = document.getElementById("Description").value;
        var Default_Price = document.getElementById("Default_Price").value;

        if (Category == '') {
            document.getElementById("Category").focus();
            return;
        }
        if (Title == '') {
            document.getElementById("Title").focus();
            return;
        }
        if (Description == '') {
            document.getElementById("Description").focus();
            return;
        }
        if (Default_Price == '') {
            document.getElementById("Default_Price").focus();
            return;
        }

        var Skills = document.querySelector("#Skills").value == '' ? [] : JSON.parse(document.querySelector("#Skills").value);
        if (Skills.length == 0) {
            document.getElementById("SkillAddInput").focus();
            return;
        }

        var Locations = document.querySelector("#Locations").value == '' ? [] : JSON.parse(document.querySelector("#Locations").value);
        if (Locations.length == 0) {
            document.getElementById("District").focus();
            return;
        }


        addService();
    }

    function addService() {

        var Category = document.getElementById("Category").value;
        var Title = document.getElementById("Title").value;
        var Description = document.getElementById("Description").value;
        var Default_Price = document.getElementById("Default_Price").value;
        var Skills = document.querySelector("#Skills").value == '' ? [] : JSON.parse(document.querySelector("#Skills").value);
        var Locations = document.querySelector("#Locations").value == '' ? [] : JSON.parse(document.querySelector("#Locations").value);


        const newService = document.createElement("div");
        newService.classList.add("search-item");

        newService.innerHTML = `
            <div class="status-badge status-active">${Category}</div>

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

            <h3 class="post-title">${Title}</h3>

            <div class="post-description">
                ${Description}
            </div>

            <div class="post-skills">
                <span class="skills-label">Skills:</span>
                <div class="skills-tags">
                    <span class="skill-tag">
                    ${Skills.join("</span><span class='skill-tag'>")}       
                    </span>
                </div>
            </div>

            <div class="post-footer">
                <div class="post-details">
                    <div class="detail-item">
                        <span class="detail-label">
                            <i class="fa-solid fa-circle-dollar"></i>
                            Rs. ${Number(Default_Price).toFixed(2)} / hr
                        </span>
                    </div>
                </div>
            </div>

            <div class="engagement-stats">
                <div class="stat-item">
                    <i class="fas fa-location-pin"></i>
                    <span>${Locations.join(", ")}</span>
                </div>
            </div>

            <div hidden>
                <input type='hidden' name='category_id[]' value='${document.getElementById("Category_ID").value}'>
                <input type='hidden' name='category_name[]' value='${Category}'>
                <input type='hidden' name='title[]' value='${Title}'>
                <input type='hidden' name='description[]' value='${Description}'>
                <input type='hidden' name='default_price[]' value='${Default_Price}'>
                <input type='hidden' name='skills[]' value='${JSON.stringify(Skills)}'>
                <input type='hidden' name='locations[]' value='${JSON.stringify(Locations)}'>
            </div>
        `;

        if (document.getElementById("service-card-wrapper").querySelector("p")) {
            document.getElementById("service-card-wrapper").querySelector("p").remove();
        }

        document.getElementById("service-card-wrapper").appendChild(newService);

        inputReset('AddServiceDialog');

        closeDialogBox('AddServiceDialog');

    }


    function selectCategory() {
        document.getElementById("Title").value = document.getElementById("Category").value;
        document.getElementById("Title").parentElement.querySelector(".label").classList.add("label-float");

        if (document.getElementById("Title").value != '') {
            document.getElementById("SkillArea").style.display = 'block';
        }


        CategoryID = document.getElementById("Category_ID").value;
        document.getElementById("SkillAddInput").value = "";
        document.getElementById("SkillsOptionList").innerHTML = "";
        document.getElementById("SkillAddInput").parentElement.querySelector(".label").classList.remove("label-float");

        fetch("./get-skills", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "category_id=" + encodeURIComponent(CategoryID)
            })
            .then(res => res.json())
            .then(data => {
                if (data.status == 'ok') {
                    data.result.forEach(element => {
                        addItemToDropdown("SkillAddInput", element.Skill, false);
                    });
                } else {
                    console.log(data);
                }
            })
            .catch(err => console.error(err));


    }

    function addSkill() {
        if (addChip('SkillsChips', document.getElementById("SkillAddInput").value)) {
            document.getElementById("SkillAddInput").value = "";
            document.getElementById("SkillAddInput").parentElement.querySelector(".label").classList.remove("label-float");
        } else {
            document.getElementById("SkillAddInput").focus();
        }
    }


    function selectDistrict() {

        document.getElementById("City").parentElement.parentElement.querySelector(".option-list").innerHTML = '';

        var current = document.querySelector("#LocationChips input").value == '' ? [] : JSON.parse(document.querySelector("#LocationChips input").value);
        if (current.indexOf("All Districts") > -1 || current.indexOf(document.getElementById("District").value + " District") > -1) {
            return;
        }


        fetch("./get-cities", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "district=" + encodeURIComponent(document.getElementById("District").value)
            })
            .then(res => res.json())
            .then(data => {
                if (data.status == 'ok') {
                    data.result.forEach(element => {
                        if (element.City == "All") {
                            addItemToDropdown("City", element.City, true);
                        } else {
                            addItemToDropdown("City", element.City, false);
                        }
                    });
                } else {
                    console.log(data);
                }
            })
            .catch(err => console.error(err));


    }


    function addLocation() {
        var district = document.getElementById("District").value;
        var city = document.getElementById("City").value;

        if (district == '' || city == '') {
            return;
        }

        if (city == 'All') {

            if (district == 'All') {

                const currentChips = document.querySelectorAll("#LocationChips .chip");
                for (let i = 0; i < currentChips.length; i++) {
                    removeChip(currentChips[i]);
                }

                addChip('LocationChips', "All Districts");

            } else {

                const currentChips = document.querySelectorAll("#LocationChips .chip");
                for (let i = 0; i < currentChips.length; i++) {
                    if (currentChips[i].dataset.district == district) {
                        removeChip(currentChips[i]);
                    }
                }

                addChip('LocationChips', district + " District");

            }

        } else {

            if (chip = addChip('LocationChips', city)) {
                chip.dataset.district = district;
            }

        }

        document.getElementById("City").value = "";
        document.getElementById("City").parentElement.querySelector(".label").classList.remove("label-float");

        selectDistrict();
    }
</script>

<style>
    .main-section {
        max-width: 800px;
    }

    .button-section button {
        flex: unset;
        width: 150px;
    }

    .top-button-wrapper {
        margin-bottom: 25px;
    }

    #AddServiceDialog h5 {
        text-align: center;
        font-size: 0.9em;
        margin-top: 35px;
    }

    .search-item {
        background: white;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e5e7eb;
        position: relative;
        overflow: hidden;

        text-align: left;

        margin-bottom: 25px;
    }

    .search-item::before {
        content: none;
    }

    .search-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: #008500;
    }

    /* Status Indicators */
    .status-badge {
        position: absolute;
        bottom: 16px;
        /* moved to bottom */
        right: 16px;
        top: auto;
        /* override previous top positioning */
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-active {
        background: #dcfce7;
        color: #008500;
        border: 1px solid #bbf7d0;
    }

    /* Post Header Section */
    .post-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }


    .post-actions {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        display: flex;
        align-items: center;
        gap: 6px;
        text-transform: none;
    }

    .btn-delete {
        background: white;
        color: #dc2626;
        border: 2px solid #dc2626;
    }

    .btn-delete i {
        color: #dc2626;
    }


    .btn-delete:hover {
        background: #dc2626;
        color: white;
        transform: translateY(-1px);
    }

    .btn-delete:hover i {
        color: white;
    }

    /* Post Content Section */
    .post-title {
        font-size: 22px;
        font-weight: 700;
        color: #008500;
        margin-bottom: 16px;
        line-height: 1.4;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .post-description {
        color: #4b5563;
        line-height: 1.7;
        margin-bottom: 20px;
        font-size: 15px;
    }

    .post-skills {
        margin-bottom: 24px;
    }

    .skills-label {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 10px;
        display: block;
    }

    .skills-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .skill-tag {
        background: #f1f5f9;
        color: #475569;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .skill-tag:hover {
        background: #008500;
        color: white;
        border-color: #008500;
        transform: translateY(-1px);
    }


    /* Post Footer Section */
    .post-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 20px;
        border-top: 1px solid #f3f4f6;
    }

    .post-details {
        display: flex;
        gap: 32px;
    }

    .detail-item {
        text-align: center;
    }

    .detail-label {
        display: block;
        font-size: 12px;
        color: #6b7280;
        font-weight: 500;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 16px;
        font-weight: 700;
        color: #111827;
    }


    .engagement-stats {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-top: 8px;
        font-size: 12px;
        color: #6b7280;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 4px;
    }
</style>



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
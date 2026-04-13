function deleteService(element) {
    element.parentElement.parentElement.parentElement.remove();
}

function validateData() {

    var Category = document.getElementById("Category").value;
    var Title = document.getElementById("Title").value;
    var Description = document.getElementById("Description").value;
    var Default_Price = document.getElementById("Default_Price").value;
    var Price_Type = document.getElementById("Price_Type").value;

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

    if (Price_Type == '') {
        document.getElementById("Price_Type").focus();
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

function renderServiceCardTemplate(templateId, Category, Title, Description, Default_Price, Skills, Locations, CategoryIcon, Price_Type) {

    if (templateId == "provider-profile-template") {

        return `
            <div class="req-head">
                <img class="req-avatar" src="/file/category-icons/${CategoryIcon || 'default-category.png'}" alt="" onerror="this.style.visibility='hidden'"/>
                <div class="req-main">
                    <span class="req-name">${Category}</span>
                    <span class="req-title">${Title}</span>
                    <span class="req-time">${Locations}</span>
                </div>
                <div class="req-actions">
                    <button style='display:none;' class="btn-outline-blue" data-action="view"><i class="fa-solid fa-eye"></i> View</button>
                    <button style='display:none;' class="btn-outline-blue" data-action="edit"><i class="fa-solid fa-pen"></i> Edit</button>
                    <button class="btn-outline-rose" data-action="remove"><i class="fa-solid fa-xmark"></i> Delete</button>
                </div>
            </div>
            <div class="req-tags">
                <span class="tag">
                    <i class="fa-solid fa-tag"></i> ${Price_Type}: <strong>${Number(Default_Price).toFixed(2)}</strong></span>
                    ${Skills.map((s) => `<span class=\"tag\">${s}</span>`).join("")}
            </div>
            <div class="req-desc">${Description || "—"}</div>
        `;

    }

    return `
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
                            Rs. ${Number(Default_Price).toFixed(2)} / ${document.getElementById("Price_Type").value}
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
    `;

}

function addService() {

    var Category = document.getElementById("Category").value;
    var Title = document.getElementById("Title").value;
    var Description = document.getElementById("Description").value;
    var Default_Price = document.getElementById("Default_Price").value;
    var Skills = document.querySelector("#Skills").value == '' ? [] : JSON.parse(document.querySelector("#Skills").value);

    const LocationString = document.querySelector("#Locations").value;
    console.log(LocationString);
    console.log(typeof LocationString);
    
    var Locations = document.querySelector("#Locations").value == '' ? [] : JSON.parse(LocationString);
    // console.log(document.querySelector("#Locations").value);
    // var Locations = [];

    var CategoryIcon = document.getElementById("Category_Icon").value;
    var Price_Type = document.getElementById("Price_Type").value;

    Skills = Skills.map(item => item.value);
    Locations = Locations.map(item => item.value);


    const newService = document.createElement("div");
    newService.classList.add("search-item");
    newService.classList.add("req-card");


    var templateId = "register-template";
    if (typeof AddServiceCardTemplate !== "undefined") {
        templateId = AddServiceCardTemplate;
    }

    const generated = renderServiceCardTemplate(templateId, Category, Title, Description, Default_Price, Skills, Locations, CategoryIcon, Price_Type);

    console.log(JSON.stringify(Locations));

    newService.innerHTML = `
            ${generated}
            <div hidden>
                <input type='hidden' name='category_id[]' value='${document.getElementById("Category_ID").value}'>
                <input type='hidden' name='category_name[]' value='${Category}'>
                <input type='hidden' name='title[]' value='${Title}'>
                <input type='hidden' name='description[]' value='${Description}'>
                <input type='hidden' name='default_price[]' value='${Default_Price}'>
                <input type='hidden' name='skills[]' value='${JSON.stringify(Skills)}'>
                <input type='hidden' name='locations[]' value='${JSON.stringify(Locations)}'>
                <input type='hidden' name='portfolio_link[]' value='${document.getElementById("Portfolio_Link").value}'>
                <input type='hidden' name='price_type[]' value='${Price_Type}'>
                <input type='hidden' name='price_negotiability[]' value='${document.getElementById("Price_Negotiability").checked}'>
            </div>
        `;

    console.log(document.getElementById("Price_Negotiability").checked);


    if (document.getElementById("service-card-wrapper").querySelector("p")) {
        document.getElementById("service-card-wrapper").querySelector("p").remove();
    }

    document.getElementById("service-card-wrapper").appendChild(newService);

    inputReset('AddServiceDialog');

    closeDialogBox('AddServiceDialog');

    if (typeof AddServiceCardTemplate !== "undefined" && AddServiceCardTemplate == "provider-profile-template") {
        saveServiceToDatabase(newService);
    }

}


function saveServiceToDatabase(serviceElement) {
    var formData = new FormData();
    serviceElement.querySelectorAll("input[type='hidden']").forEach(input => {
        formData.append(input.name, input.value);
    });
    fetch("/profile/add-service", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success === true) {
                showToast("New service added successfully", "success");
            } else {
                showToast("Error adding service", "error");
            }
        })
        .catch(error => {
            // console.error('Error:', error);
        });

}



function selectCategory(event) {

    document.getElementById("Title").value = document.getElementById("Category").value;
    document.getElementById("Title").parentElement.querySelector(".label").classList.add("label-float");

    if (event.target.dataset.icon) {
        document.getElementById("Category_Icon").value = event.target.dataset.icon;
    }

    if (document.getElementById("Title").value != '') {
        document.getElementById("SkillArea").style.display = 'block';
    }

    CategoryID = document.getElementById("Category_ID").value;
    // console.log(CategoryID);

    document.getElementById("SkillAddInput").value = "";
    document.getElementById("SkillsOptionList").innerHTML = "";
    document.getElementById("SkillAddInput").parentElement.querySelector(".label").classList.remove("label-float");

    fetch("/register/get-skills", {
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


    fetch("/register/get-cities", {
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
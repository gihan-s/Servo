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
                    <div class="option-list" onclick="selectCategory(event)">
                        <?php foreach ($Categories as $Category): ?>
                            <div data-id="<?= $Category['Category_ID'] ?>" data-icon="<?= $Category['Icon'] ?>"><?= htmlspecialchars($Category['Name']) ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <input type="hidden" id="Category_ID">
            <input type="hidden" id="Category_Icon">

            <div class="text-container">
                <div class="label text-label">Title</div>
                <input type="text" class="text-field" id="Title">
            </div>

            <div class="text-container">
                <div class="label text-label">Description</div>
                <textarea class="text-field" spellcheck="false" id="Description"></textarea>
            </div>

            <div class="text-container">
                <div class="label text-label">Portfolio Link</div>
                <input type="text" class="text-field" id="Portfolio_Link">
            </div>

            <div class="input-grid-2" style="margin-bottom: 0px;">

                <div class="select-container">
                    <div class="text-container">
                        <div class="label dropdown-label label-float notreset">Price Type</div>
                        <input type="text" class="text-field-dropdown" value="Hourly" readonly id="Price_Type">
                    </div>
                    <div class="options">
                        <div>Hourly</div>
                        <div>Daily</div>
                        <div>Fixed</div>
                    </div>
                </div>

                <div class="text-container">
                    <div class="label text-label">Rate (Rs.)</div>
                    <input type="number" step="any" class="text-field" id="Default_Price">
                </div>
            </div>

            <label class="checkbox-container">
                <input type="checkbox" id="Price_Negotiability">
                <span class="checkmark"></span>
                <label for=""> Price is negotiable</label>
            </label>
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



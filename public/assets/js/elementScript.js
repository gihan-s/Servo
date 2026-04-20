//Text Fields
let TextFieldArray = document.getElementsByClassName("text-field");
let TextLabelArray = document.getElementsByClassName("text-label");

for (let i = 0; i < TextFieldArray.length; i++) {
    TextFieldArray[i].addEventListener(
        "focus",
        (event) => {
            if (event.target.parentElement.querySelector(".text-label") != null) {
                event.target.parentElement.querySelector(".text-label").classList.add("label-float");
                event.target.parentElement.querySelector(".text-label").style.color = "var(--themeColor)";
            }

        },
        false
    );
    TextFieldArray[i].addEventListener(
        "blur",
        (event) => {

            if (event.target.parentElement.querySelector(".text-label") != null) {

                setTimeout(() => {
                    if (event.target.value == "") {
                        event.target.parentElement.querySelector(".text-label").classList.remove("label-float");
                    }

                    event.target.parentElement.querySelector(".text-label").style.color = "var(--textFieldLabelColor)";
                }, 200);

            }


        },
        false
    );
}



//Drop Downs
const DropDownArray = document.getElementsByClassName("select-container");
const DropDowntextfieldArray = document.querySelectorAll(
    ".select-container input"
);
const DropdownLabelArray = document.getElementsByClassName("dropdown-label");

for (let i = 0; i < DropDownArray.length; i++) {
    DropDownArray[i].addEventListener(
        "click",
        () => {
            DropDownArray[i].classList.add("dropdown-view");
        },
        false
    );

    var optionArray = DropDownArray[i].querySelectorAll(".options div");
    for (let j = 0; j < optionArray.length; j++) {
        const element = optionArray[j];

        element.addEventListener(
            "click",
            () => {
                DropDowntextfieldArray[i].value = element.innerHTML;
                DropDowntextfieldArray[i].dataset.id = element.dataset.id;
                dropdownFocus(i);
                DropdownLabelArray[i].style.color = "var(--textFieldLabelColor)";
            },
            false
        );

        element.addEventListener("mousedown", () => {
            element.click();
        })
    }
}

for (let i = 0; i < DropDowntextfieldArray.length; i++) {
    DropDowntextfieldArray[i].addEventListener(
        "focus",
        () => {
            dropdownFocus(i);
        },
        false
    );

    DropDowntextfieldArray[i].addEventListener(
        "blur",
        () => {
            dropdownBlur(i);

            setTimeout(() => {
                DropDownArray[i].classList.remove("dropdown-view");
            }, 200);
        },
        false
    );
}

function dropdownFocus(i) {
    DropdownLabelArray[i].classList.add("label-float");
    DropdownLabelArray[i].style.color = "var(--themeColor)";
}

function dropdownBlur(i) {
    if (
        DropDowntextfieldArray[i].value == "" ||
        DropDowntextfieldArray[i].value == undefined
    ) {
        DropdownLabelArray[i].classList.remove("label-float");
    }

    DropdownLabelArray[i].style.color = "var(--textFieldLabelColor)";
}


// Dropdowns with Search

const SearchDropDownArray = document.getElementsByClassName(
    "search-select-container"
);
const SearchDropDowntextfieldArray = document.querySelectorAll(
    ".search-select-container .text-field-search-dropdown"
);
const SearchDropdownLabelArray = document.getElementsByClassName(
    "search-dropdown-label"
);

const SearchDropDowntextfieldArray2 = document.querySelectorAll(
    ".search-select-container .options input[type=text]"
);

const SDFocusTrack = [];

document.body.addEventListener("mousedown", (event) => {

    if (document.querySelector(".dropdown-view") != null) {

        if (event.target.parentElement === null) {
            return;
        }

        if (event.target.parentElement.parentElement != null &&
            event.target.parentElement.parentElement.classList.contains("search-select-container") &&
            !event.target.parentElement.parentElement.classList.contains("dropdown-view")

        ) {
            document.querySelector(".dropdown-view").classList.remove("dropdown-view");
        } else {

            if (
                event.target.parentElement.parentElement == null ||
                (!event.target.parentElement.parentElement.classList.contains("option-list") &&
                    !event.target.parentElement.parentElement.classList.contains("search-select-container"))
            ) {

                if (event.target.parentElement.parentElement != null) {
                    if (
                        event.target.parentElement.parentElement.classList.contains("options")
                    ) {

                        if (event.target.parentElement.classList.contains("option-list")) {
                            document.querySelector(".dropdown-view").classList.remove("dropdown-view");
                        }

                    } else {

                        document.querySelector(".dropdown-view").classList.remove("dropdown-view");
                    }
                } else {
                    document.querySelector(".dropdown-view").classList.remove("dropdown-view");
                }
            }
        }
    }
})

let searchDropdownsOptionsArray = [];

for (let i = 0; i < SearchDropDownArray.length; i++) {
    var dropdownOptions = [];

    var optionArray = SearchDropDownArray[i].querySelectorAll(".option-list div");

    for (let j = 0; j < optionArray.length; j++) {
        const element = optionArray[j];
        dropdownOptions.push(element.innerHTML);
    }

    searchDropdownsOptionsArray.push(dropdownOptions);
}

for (let i = 0; i < SearchDropDownArray.length; i++) {
    SDFocusTrack.push(true);

    SearchDropDownArray[i].addEventListener(
        "click",
        () => {
            SearchDropDownArray[i].classList.add("dropdown-view");
            SearchDropDowntextfieldArray2[i].focus();
        },
        false
    );

    var optionArray = SearchDropDownArray[i].querySelectorAll(".option-list div");
    for (let j = 0; j < optionArray.length; j++) {
        const element = optionArray[j];

        element.addEventListener(
            "click",
            (event) => {
                const parentSelect = event.target.parentElement.parentElement.parentElement;
                if (parentSelect.parentElement.classList.contains("multiple-selector")) {
                    multipleDropdownFiller(SearchDropDownArray[i]);
                } else {
                    SearchDropDowntextfieldArray[i].value = element.innerText;
                    // if (element.dataset.id) {
                    //     SearchDropDowntextfieldArray[i].dataset.id = element.dataset.id;
                    // }
                    if (parentSelect.dataset.idinput != undefined) {
                        document.getElementById(parentSelect.dataset.idinput).value = element.dataset.id;
                    }
                }
                searchDropdownFocus(i);
                SearchDropdownLabelArray[i].style.color = "var(--textFieldLabelColor)";
                searchDropdownBlur(i);
                SearchDropDownArray[i].classList.remove("dropdown-view");
                
            },
            false
        );


        if (element.parentElement.parentElement.parentElement.classList.contains("multiple-selector")) {
            element.addEventListener("mouseup", () => {
                element.click();
            })
        } else {
            element.addEventListener("mousedown", () => {
                element.click();
            })
        }
    }
}

function multipleDropdownFiller(element) {
    eles = element.querySelectorAll(".option-list div");
    arr = [];

    for (let i = 0; i < eles.length; i++) {
        const element2 = eles[i];
        if (element2.querySelector("input").checked) {
            arr.push(element2.innerText.trim());
        } else {
            arr = arr.filter((x) => {
                if (x != element2.innerText) {
                    return x;
                }
            })
        }
    }

    element.querySelector(".text-field-search-dropdown").value = arr.join(", ");

}

function searchDropdownFocus(i) {
    SearchDropdownLabelArray[i].classList.add("label-float");
    SearchDropdownLabelArray[i].style.color = "var(--themeColor)";
}

function searchDropdownBlur(i) {
    if (
        SearchDropDowntextfieldArray[i].value == "" ||
        SearchDropDowntextfieldArray[i].value == undefined
    ) {
        SearchDropdownLabelArray[i].classList.remove("label-float");
    }

    SearchDropdownLabelArray[i].style.color = "var(--textFieldLabelColor)";
}

const searchDropdowns = document.querySelectorAll(".search-select-container");

for (let i = 0; i < searchDropdowns.length; i++) {
    const element = searchDropdowns[i];

    let textField = element.querySelector(".options input");

    if (!textField) {
        continue;
    }

    textField.addEventListener("keyup", (event) => {

        newAllOptions = event.target.parentElement.parentElement.querySelectorAll(".option-list div");
        for (let p = 0; p < newAllOptions.length; p++) {
            const newOption = newAllOptions[p];
            newOption.style.display = "none";

        }

        filteredoptionsArray = Array.from(newAllOptions).filter((item) => {
            return item.innerHTML.toLowerCase().indexOf(textField.value.toLowerCase()) > -1;
        });

        var optionCount = filteredoptionsArray.length;
        var check = true;

        for (let p = 0; p < filteredoptionsArray.length; p++) {
            const newOption = filteredoptionsArray[p];
            newOption.style.display = "flex";


            if (check && textField.value.trim() == newOption.innerHTML.trim()) {
                check = false;
            }

            if (newOption.classList.contains("temp-add-option")) {
                optionCount--;
            }
        }

        if (textField.value.trim() == '') {
            check = false;
        }


        if (element.querySelector(".option-list .temp-add-option") !== null) {
            element.querySelector(".option-list .temp-add-option").remove();
        }


        // Add New Option To Dropdown
        if (element.classList.contains("add-option") && check) {

            const isAddOption = true;

            const AddOptionDiv = document.createElement("div");
            AddOptionDiv.innerHTML = `Add&nbsp; <b><i>"${textField.value}"<b></i>`;
            AddOptionDiv.dataset.value = textField.value;
            AddOptionDiv.classList.add("temp-add-option");

            AddOptionDiv.addEventListener("mousedown", () => {

                const newElement = document.createElement("div");
                newElement.innerHTML = AddOptionDiv.dataset.value;
                newElement.dataset.id = AddOptionDiv.dataset.value;

                newElement.addEventListener("click", () => {
                    var searchDD = element;
                    searchDD.querySelector(".text-field-search-dropdown").value = AddOptionDiv.dataset.value;
                    if (newElement.dataset.id) {
                        searchDD.querySelector(".text-field-search-dropdown").dataset.id = newElement.dataset.id;
                    }
                    searchDD.querySelector(".search-dropdown-label").classList.add("label-float");
                });

                newElement.addEventListener("mousedown", () => {
                    newElement.click();
                })

                element.querySelector(".option-list").appendChild(newElement);

                newElement.click();
                element.classList.remove("dropdown-view");
                element.querySelector(".option-list .temp-add-option").remove();

            });


            if (element.querySelector(".option-list .temp-add-option") !== null) {
                element.querySelector(".option-list .temp-add-option").remove();
            }
            event.target.parentElement.parentElement.querySelector(".option-list").appendChild(AddOptionDiv);

        }

    });
}



// Dialog Boxes
function viewDialogBox(id, reset = true) {
    if (reset) {
        inputReset(id);
    }
    document.getElementById(id).classList.add("dialog-box-2-view");
}

function closeDialogBox(id) {
    document.getElementById(id).classList.remove("dialog-box-2-view");
}


// SnackBar
const snackbarArray = document.getElementsByClassName("snackbar");

for (let i = 0; i < snackbarArray.length; i++) {
    const element = snackbarArray[i];
    element.querySelector("i").addEventListener("click", () => {
        element.classList.remove("snackbar-view");
    });
}

function showSnackbar(elementID) {
    var element = document.getElementById(elementID);

    element.classList.add("snackbar-view");

    setTimeout(() => {
        element.classList.remove("snackbar-view");
    }, 3000);
}



//Custom Input Validations
function showValidationTooltip(inputElement, message) {
    // Remove any existing tooltip
    const existingTooltip = inputElement.parentNode.querySelector('.validation-tooltip');
    if (existingTooltip) {
        existingTooltip.remove();
    }

    // Add error styling to input
    inputElement.classList.add('error');

    // Create and show tooltip
    const tooltip = document.createElement('div');
    tooltip.className = 'validation-tooltip';
    tooltip.textContent = message;
    inputElement.parentNode.appendChild(tooltip);

    // Show tooltip with animation
    setTimeout(() => {
        tooltip.classList.add('show');
    }, 10);

    // Auto-hide tooltip after 3 seconds
    setTimeout(() => {
        hideValidationTooltip(inputElement);
    }, 3000);
}

// Hide validation tooltip
function hideValidationTooltip(inputElement) {
    const tooltip = inputElement.parentNode.querySelector('.validation-tooltip');
    if (tooltip) {
        tooltip.classList.remove('show');
        setTimeout(() => {
            tooltip.remove();
        }, 300);
    }
    inputElement.classList.remove('error');
}



function addChip(elementID, chipValue, chipID) {

    if (chipValue == '') {
        return false;
    }

    var currentValues = document.getElementById(elementID).querySelector("input").value;
    currentValues = currentValues == '' ? [] : JSON.parse(currentValues);

    if (currentValues.some(item => item.value === chipValue)) {
        return false;
    }

    currentValues.push({ value: chipValue, id: chipID });
    document.getElementById(elementID).querySelector("input").value = JSON.stringify(currentValues);


    const newChip = document.createElement("div");
    newChip.classList.add("chip");
    newChip.innerHTML = `
    <span>${chipValue}</span>
    <i class="fa-solid fa-xmark"></i>
    `;

    if (document.getElementById(elementID).querySelector("p")) {
        document.getElementById(elementID).querySelector("p").remove();
    }

    document.getElementById(elementID).appendChild(newChip)

    newChip.querySelector("i").addEventListener("click", () => {
        removeChip(newChip);
    })

    return newChip;
}


function removeChip(chip) {
    var currentValues = chip.parentElement.querySelector("input").value;
    currentValues = currentValues == '' ? [] : JSON.parse(currentValues);
    currentValues = currentValues.filter(item => item.value !== chip.querySelector("span").innerHTML);
    chip.parentElement.querySelector("input").value = JSON.stringify(currentValues);
    chip.remove();
}



function addItemToDropdown(DropdownID, value, isDefault = true, valueID) {
    var element = document.createElement("div");
    element.innerHTML = value;
    element.dataset.id = valueID;
    const OptionsList = document.getElementById(DropdownID).parentElement.parentElement.querySelector(".option-list");
    OptionsList.appendChild(element);



    if (isDefault) {
        document.getElementById(DropdownID).value = value;
        document.getElementById(DropdownID).parentElement.querySelector(".search-dropdown-label").classList.add("label-float");
    }

    element.addEventListener("click", () => {
        var searchDD = element.parentElement.parentElement.parentElement;
        searchDD.querySelector(".text-field-search-dropdown").value = element.innerHTML; if (element.dataset.id) {
            searchDD.querySelector(".text-field-search-dropdown").dataset.id = element.dataset.id;
        }
        searchDD.querySelector(".search-dropdown-label").classList.add("label-float");
    });

    element.addEventListener("mousedown", () => {
        element.click();
    })

    return element;
}



function inputReset(formID) {
    const root = document.getElementById(formID);

    const elementArray = root.querySelectorAll("input");
    for (let i = 0; i < elementArray.length; i++) {
        const element = elementArray[i];

        if (
            !element.classList.contains("notreset") &&
            !element.classList.contains("tel-country-code")
        ) {
            element.value = "";
            try {
                if (
                    !element.parentElement
                        .querySelector(".label")
                        .classList.contains("tel-dropdown-label") &&
                    !element.parentElement
                        .querySelector(".label")
                        .classList.contains("date-label")
                ) {
                    element.parentElement
                        .querySelector(".label")
                        .classList.remove("label-float");
                }
            } catch (error) { }
        }
    }

    const checkBoxArray = document
        .getElementById(formID)
        .querySelectorAll("input[type=checkbox]");
    for (let i = 0; i < checkBoxArray.length; i++) {
        const element = checkBoxArray[i];
        element.checked = false;
    }

    const dateFieldArray = document
        .getElementById(formID)
        .querySelectorAll("input[type=date]");
    for (let i = 0; i < dateFieldArray.length; i++) {
        const element = dateFieldArray[i];
        element.valueAsDate = new Date();
    }


    const textareas = document.getElementById(formID).querySelectorAll("textarea");
    for (let i = 0; i < textareas.length; i++) {
        const element = textareas[i];

        if (
            !element.classList.contains("notreset")
        ) {
            element.value = "";
            try {
                if (
                    !element.parentElement
                        .querySelector(".label")
                        .classList.contains("tel-dropdown-label") &&
                    !element.parentElement
                        .querySelector(".label")
                        .classList.contains("date-label")
                ) {
                    element.parentElement
                        .querySelector(".label")
                        .classList.remove("label-float");
                }
            } catch (error) { }
        }
    }


    const chipWrappers = document.getElementById(formID).querySelectorAll(".chip-wrapper");
    for (let i = 0; i < chipWrappers.length; i++) {
        const element = chipWrappers[i];

        const currentChips = element.querySelectorAll(".chip");
        for (let j = 0; j < currentChips.length; j++) {
            removeChip(currentChips[j]);
        }

    }
}




//Option Menu
const OptionMenus = document.querySelectorAll(".option-menu");
for (let i = 0; i < OptionMenus.length; i++) {
    const element = OptionMenus[i];

    element.querySelector(".option-menu-button").addEventListener("click", (event) => {

        setTimeout(() => {
            event.target.nextElementSibling.classList.add("active");

            setTimeout(() => {
                document.body.addEventListener("click", (event2) => {
                    if (event2.target !== element.querySelector(".option-menu-button")) {
                        event.target.nextElementSibling.classList.remove("active");
                    }
                }, { once: true })
            }, 50);

        }, 50)
    })
}


//pagination
function nextPagination(element) {
    if (!element.parentElement.querySelector(".active").nextElementSibling.classList.contains("next")) {
        element.parentElement.querySelector(".active").nextElementSibling.click();
    }
}
function previosPagination(element) {
    if (!element.parentElement.querySelector(".active").previousElementSibling.classList.contains("prev")) {
        element.parentElement.querySelector(".active").previousElementSibling.click();
    }
}

function showLoadingOn(elementID) {
    const element = document.getElementById(elementID);

    const div = document.createElement("div");
    div.style.display = 'flex';
    div.style.justifyContent = 'center';
    const img = document.createElement("img");
    img.src = '/assets/img/loading.gif';
    img.style.width = "200px";
    div.appendChild(img);
    element.innerHTML = '';
    element.appendChild(div);
}




/**
 * Toast Notification System
 * Usage:
 *   showToast('success', 'Success!', 'Your post was published successfully.');
 *   showToast('error', 'Error!', 'Failed to save post.');
 *   showToast('warning', 'Warning!', 'Please fill all required fields.');
 *   showToast('info', 'Info', 'New updates available.');
 */

// Initialize toast container on page load
document.addEventListener('DOMContentLoaded', function() {
    if (!document.querySelector('.toast-container')) {
        const container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }
});

/**
 * Show a toast notification
 * @param {string} type - Toast type: 'success', 'error', 'warning', 'info'
 * @param {string} title - Toast title
 * @param {string} message - Toast message
 * @param {number} duration - Duration in milliseconds (default: 5000)
 */
function showToast(type = 'info', title = '', message = '', duration = 5000) {
    // Get or create container
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    // Get icon based on type
    const icons = {
        success: 'fa-circle-check',
        error: 'fa-circle-xmark',
        warning: 'fa-triangle-exclamation',
        info: 'fa-circle-info'
    };
    
    const icon = icons[type] || icons.info;

    // Build toast HTML
    toast.innerHTML = `
        <div class="toast-icon">
            <i class="fa-solid ${icon}"></i>
        </div>
        <div class="toast-content">
            ${title ? `<div class="toast-title">${title}</div>` : ''}
            ${message ? `<div class="toast-message">${message}</div>` : ''}
        </div>
        <div class="toast-close">
            <i class="fa-solid fa-xmark"></i>
        </div>
        <div class="toast-progress" style="width: 100%;"></div>
    `;

    // Add to container
    container.appendChild(toast);

    // Show toast with animation
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);

    // Progress bar animation
    const progressBar = toast.querySelector('.toast-progress');
    if (progressBar && duration > 0) {
        progressBar.style.transition = `width ${duration}ms linear`;
        setTimeout(() => {
            progressBar.style.width = '0%';
        }, 50);
    }

    // Close button functionality
    const closeBtn = toast.querySelector('.toast-close');
    closeBtn.addEventListener('click', () => {
        removeToast(toast);
    });

    // Auto remove after duration
    if (duration > 0) {
        setTimeout(() => {
            removeToast(toast);
        }, duration);
    }

    return toast;
}

/**
 * Remove a toast with animation
 * @param {HTMLElement} toast - Toast element to remove
 */
function removeToast(toast) {
    toast.classList.remove('show');
    toast.classList.add('hide');
    
    setTimeout(() => {
        if (toast.parentElement) {
            toast.parentElement.removeChild(toast);
        }
    }, 300);
}

/**
 * Shorthand functions for different toast types
 */
function showSuccessToast(title, message, duration) {
    return showToast('success', title, message, duration);
}

function showErrorToast(title, message, duration) {
    return showToast('error', title, message, duration);
}

function showWarningToast(title, message, duration) {
    return showToast('warning', title, message, duration);
}

function showInfoToast(title, message, duration) {
    return showToast('info', title, message, duration);
}

// Make functions globally available
window.showToast = showToast;
window.showSuccessToast = showSuccessToast;
window.showErrorToast = showErrorToast;
window.showWarningToast = showWarningToast;
window.showInfoToast = showInfoToast;
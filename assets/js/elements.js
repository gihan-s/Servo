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

                newElement.addEventListener("click", () => {
                    var searchDD = element;
                    searchDD.querySelector(".text-field-search-dropdown").value = AddOptionDiv.dataset.value;
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
function viewDialogBox(id) {
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
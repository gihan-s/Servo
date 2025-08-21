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

    element.addEventListener("mousedown", ()=>{
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
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
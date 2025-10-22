document.querySelector(".user-wrapper").addEventListener("mouseenter", (event) => {
  event.target.style.width = event.target.scrollWidth + "px";
})

document.querySelector(".user-wrapper").addEventListener("mouseleave", (event) => {
  event.target.style.width = "50px";
})






const ProgressBars = document.querySelectorAll(".progress-bar");
for (let i = 0; i < ProgressBars.length; i++) {
  const element = ProgressBars[i];

  element.style.setProperty("--progress", element.dataset.progress + "%");


}


if (Sidemenu_Active_ID != undefined) {
  document.getElementById(Sidemenu_Active_ID).classList.add("active");
}


const stepProgressBarSteps = document.querySelectorAll(".step-progress-bar .step");
for (let i = 0; i < stepProgressBarSteps.length; i++) {
  const element = stepProgressBarSteps[i];
  element.style.left = element.dataset.step;
  if (element.previousElementSibling != null) {
    var width = element.offsetLeft - element.previousElementSibling.offsetLeft;
    element.style.setProperty("--progress-len", width + "px");
  }
}

window.addEventListener("resize", () => {
  for (let i = 0; i < stepProgressBarSteps.length; i++) {
    const element = stepProgressBarSteps[i];
    element.style.left = element.dataset.step;
    if (element.previousElementSibling != null) {
      var width = element.offsetLeft - element.previousElementSibling.offsetLeft;
      element.style.setProperty("--progress-len", width + "px");
    }
  }
})



function usernameVerify(element) {

  $.post("php/usernameVerify.php", {
    Username: element.value
  }, (data) => {

    console.log(data);
    
    if (data == 'Ok') {

      element.parentElement.parentElement.querySelector("i").className = 'fa-solid fa-circle-check';
      element.parentElement.parentElement.querySelector("i").style.color = 'green';

    } else {
      element.parentElement.parentElement.querySelector("i").className = 'fa-solid fa-circle-xmark tooltip';
      element.parentElement.parentElement.querySelector("i").dataset.tooltip = 'Username Not Available';
      element.parentElement.parentElement.querySelector("i").style.color = 'red';
    }
  })

}



function folderNameVerify(element) {

  $.post("php/folderNameVerify.php", {
    Folder_Name: element.value
  }, (data) => {

    console.log(data);
    
    if (data == 'Ok') {

      element.parentElement.parentElement.querySelector("i").className = 'fa-solid fa-circle-check';
      element.parentElement.parentElement.querySelector("i").style.color = 'green';

    } else {
      element.parentElement.parentElement.querySelector("i").className = 'fa-solid fa-circle-xmark tooltip';
      element.parentElement.parentElement.querySelector("i").dataset.tooltip = 'Folder Name Not Available';
      element.parentElement.parentElement.querySelector("i").style.color = 'red';
    }
  })

}
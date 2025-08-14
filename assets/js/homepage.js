const topSwitchWrapper = document.querySelectorAll(".content .glass-wrapper .switch-wrapper .switch-option");


for (let i = 0; i < topSwitchWrapper.length; i++) {
    const element = topSwitchWrapper[i];
    
    element.addEventListener("click", ()=> {
        const element2 = document.querySelector(".content .glass-wrapper .switch-wrapper");
        element2.style.setProperty("--left", 50 * i + "%");
    })
}
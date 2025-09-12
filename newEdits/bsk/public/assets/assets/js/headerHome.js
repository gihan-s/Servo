const navOptions = document.querySelectorAll("header .options-wrapper .option");
for (let i = 0; i < navOptions.length; i++) {
    const option = navOptions[i];
    
    option.addEventListener("mouseenter", ()=> {
        const slides = document.querySelectorAll(".header-expand-wrapper .header-expand");
        for (let j = 0; j < slides.length; j++) {
            const slide = slides[j];
            slide.style.display = 'none';
        }
        slides[i].style.display = 'block';
    })
}
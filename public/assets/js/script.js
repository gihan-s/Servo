document.addEventListener("DOMContentLoaded", () => {
    // Wait until everything (images, scripts) are loaded
    window.addEventListener("load", () => {
        const loader = document.getElementById("loader");

        loader.style.opacity = "0";
        setTimeout(() => {
            loader.style.display = "none";
        }, 500); // small fade delay
    });
});

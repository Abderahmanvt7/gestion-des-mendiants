let animationLoad = document.querySelector(".loading"),
    statistics = document.querySelectorAll(".statistics");
window.addEventListener("load", function () {
    // cacher l'animation
    animationLoad.style.display = "none";
    // changer la background de body en ajoutant la class 'body'
    document.body.classList.add("body");
    // afficher les statistics
    statistics.forEach((stat) => {
        stat.style.display = "flex";
    });
});

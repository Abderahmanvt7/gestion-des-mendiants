// selection des elements
let inputUsername = document.getElementById("username"),
    inputPassword = document.getElementById("password"),
    eyeImg = document.getElementById("eye-img"),
    eyeSlashImg = document.getElementById("eye-slash-img");
// verifier le nom
inputUsername.oninvalid = function () {
    this.setCustomValidity("Le nom doit etre contiet au moins 5 caracteurs");
    this.oninput = function () {
        if (this.value.length >= 5) {
            this.setCustomValidity("");
        } else {
            this.setCustomValidity(
                "Le nom doit etre contiet au moins 5 caracteurs"
            );
        }
    };
};
// verifier le mot de passe
inputPassword.oninvalid = function () {
    this.setCustomValidity(
        "le mot de passe doit etre contient au moins 4 caracteurs"
    );
    this.oninput = function () {
        if (this.value.length >= 4) {
            this.setCustomValidity("");
        } else {
            this.setCustomValidity(
                "le mot de passe doit etre contient au moins 4 caracteurs"
            );
        }
    };
};

// echange le mot de passe entre afficher et cacher
// on fait cette fonction en changeant le type de champ (input) entre password et text
// et a l'aide de 2 icons (eyeImg et eyeSlashImg) si on clique sur l'une cache et l'autre apparie
// afficher le mot de passe
eyeImg.onclick = function () {
    var inputType = inputPassword.getAttribute("type");
    if (inputType === "password") {
        inputPassword.setAttribute("type", "text");
        this.style.display = "none";
        eyeSlashImg.style.display = "block";
    }
};
// cacher le mot de passe
eyeSlashImg.onclick = function () {
    var inputType = inputPassword.getAttribute("type");
    if (inputType !== "password") {
        inputPassword.setAttribute("type", "password");
        this.style.display = "none";
        eyeImg.style.display = "block";
    }
};
// button close
let btnClose = document.querySelectorAll(".btn-close");
for (btn of btnClose) {
    btn.addEventListener("click", function () {
        // fermer l'element overt
        this.parentElement.style.display = "none";
    });
}

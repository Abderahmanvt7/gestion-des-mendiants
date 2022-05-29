// animation before loading page
let animationLoad = document.querySelector(".loading"),
    container = document.querySelector(".container");
window.addEventListener("load", function () {
    animationLoad.style.display = "none";
    this.document.body.classList.add("body");
    container.style.display = "block";
});

// button close
let btnClose = document.querySelectorAll(".btn-close");
btnClose.forEach((btn) => {
    btn.addEventListener("click", function () {
        console.log("close was clicked");
        // fermer l'element overt
        this.parentElement.style.display = "none";
        // changer l'opacity de la page
        removeOppacity();
    });
});

// Ajouter admin
let formAjouter = document.getElementById("form-ajouter"),
    btnAjouter = document.querySelector(".btn-ajouter");

btnAjouter.addEventListener("click", function () {
    formAjouter.style.display = "block";
    // changer l'opacity de la page
    addOpacity(btnAjouter);
});

// suppression
let formSupprimer = document.getElementById("supprimer"),
    btnsSupprimer = document.querySelectorAll(".btn-supprimer"),
    messageConfirmerSuppression = document.getElementById(
        "confirm-suppression"
    ),
    confirmerSuppression = document.querySelector(".btn-danger.supprimer"),
    annullerSuppression = document.querySelector(".btn-annuler-supression"),
    inputIdSupprimer = document.getElementById("id-supprimer");

// supprimer et confirmer la suppression
for (let i = 0; i < btnsSupprimer.length; i++) {
    btnsSupprimer[i].addEventListener("click", function () {
        // affecter le nom et le prenom dans des variables pour ils utiliser dans le message
        // de confirmer la suppression
        let nom =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-username"
            ).textContent;

        inputIdSupprimer.value =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-id"
            ).textContent;
        // messge de confirmer la suppression
        messageConfirmerSuppression.style.display = "block";
        messageConfirmerSuppression.firstElementChild.innerHTML = `supprimer <span class="name"> ${nom} </span>?`;
        // changer l'opacity de la page
        addOpacity(this);
    });
}
// annuler la suppression
annullerSuppression.addEventListener("click", function (e) {
    //preventer la la fonction par defaut pour n'envoyer pas la formulaire
    e.preventDefault();
    // cacher la message de confirmation
    messageConfirmerSuppression.style.display = "none";
    // changer l'opacity de la page
    removeOppacity();
});

// modifier admin
let formModifier = document.getElementById("form-modifier"),
    btnsModifier = document.querySelectorAll(".btn-modifier"),
    inputUserName = document.getElementById("username-modifier"),
    inputEmail = document.getElementById("email-modifier"),
    inputFullName = document.getElementById("full-name-modifier"),
    inputIdModifier = document.getElementById("id-modifier"),
    inputPermission = document.getElementById("permission-modifier");

for (let i = 0; i < btnsModifier.length; i++) {
    btnsModifier[i].addEventListener("click", function () {
        // afficher la formulaire de modification
        formModifier.style.display = "block";
        // ajouter les information de la ligne choisi dans les champs de la formulaire
        inputIdModifier.value =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-id"
            ).textContent;
        inputUserName.value =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-username"
            ).textContent;
        inputEmail.value =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-email"
            ).textContent;
        inputFullName.value =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-fullname"
            ).textContent;
        inputPermission.value =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-perminssion"
            ).textContent;
        // changer l'opacity de la page
        addOpacity(this);
    });
}

// Handle opacity
let el = null;
function addOpacity(e) {
    el = document.createElement("div");
    el.classList.add("opacity");
    e.parentElement.appendChild(el);
}
function removeOppacity() {
    if (el) {
        el.remove();
    }
}

// animation before loading page
let animationLoad = document.querySelector(".loading"),
    hebergementHeader = document.querySelector(".hebergement-header"),
    statistics = document.querySelectorAll(".statistics");
window.addEventListener("load", function () {
    // cacher l'animation
    animationLoad.style.display = "none";
    // changer la background du body en ajoutant la classe 'body'
    this.document.body.classList.add("body");
    // afficher les statistics
    statistics.forEach((stat) => {
        stat.style.display = "flex";
    });
    // afficher l'hebergement header
    hebergementHeader.style.display = "flex";
});

// select box filter
let filterSelect = document.getElementById("filter-list");
filterSelect.onchange = function () {
    this.parentElement.lastElementChild.click();
};

// Ajouter un mendiant a une chambre
let formAjouterToChambre = document.getElementById("form-ajouter-to-chambre"),
    btnsAjouterToChambre = document.querySelectorAll(".btn-ajouter"),
    idChambre = document.getElementById("id-chambre-ajouter-mendiant");

for (let button of btnsAjouterToChambre) {
    button.addEventListener("click", function () {
        formAjouterToChambre.style.display = "block";

        idChambre.value = this.parentElement
            .querySelector("span")
            .textContent.substr(8);

        // changer l'opacity de la page
        addOpacity(button);
    });
}

// Ajouter nouveau chambre
let nouveauChambre = document.querySelector(".ajouter-chambre");
nouveauChambre.addEventListener("click", function () {
    this.parentElement.lastElementChild.click();
});

// button close
let btnClose = document.querySelectorAll(".btn-close");
for (btn of btnClose) {
    btn.addEventListener("click", function () {
        console.log("close was clicked");
        // fermer l'element overt
        this.parentElement.style.display = "none";
        // changer l'opacity de la page
        removeOppacity();
    });
}

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
        let id = this.parentElement.querySelector(".chambre-id").value;

        inputIdSupprimer.value = id;
        // messge de confirmer la suppression
        messageConfirmerSuppression.style.display = "block";
        messageConfirmerSuppression.firstElementChild.innerHTML = `supprimer <span class="name">chambre ${id} </span>?`;
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

// Handle opacity
let el = null;
function addOpacity(element) {
    el = document.createElement("div");
    el.classList.add("opacity");
    element.parentElement.appendChild(el);
}
function removeOppacity() {
    if (el) {
        el.remove();
    }
}

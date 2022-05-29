// modifier activites
let formModifier = document.getElementById("form-modifier"),
    btnsModifier = document.querySelectorAll(".btn-modifier"),
    inputNom = document.getElementById("nom-modifier"),
    inputDomaine = document.getElementById("domaine-modifier"),
    inputDateActivite = document.getElementById("date-activite-modifier"),
    inputIdModifier = document.getElementById("id-modifier");

for (let i = 0; i < btnsModifier.length; i++) {
    btnsModifier[i].addEventListener("click", function () {
        // afficher la formulaire de modification
        formModifier.style.display = "block";
        // ajouter les information de la ligne choisi dans les champs de la formulaire
        inputIdModifier.value =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-id"
            ).textContent;
        inputNom.value =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-nom"
            ).textContent;
        inputDomaine.value =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-domaine"
            ).textContent;
        inputDateActivite.value =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-date-activite"
            ).textContent;
        // changer l'opacity de la page
        addOpacity(this);
    });
}

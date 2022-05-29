// modifier activites
let formModifier = document.getElementById("form-modifier"),
    btnsModifier = document.querySelectorAll(".btn-modifier"),
    inputNom = document.getElementById("nom-modifier"),
    inputDomaine = document.getElementById("domaine-modifier"),
    inputDebut = document.getElementById("date-debut-modifier"),
    inputFin = document.getElementById("date-fin-modifier"),
    inputFormateur = document.getElementById("formateur-modifier"),
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
        inputDebut.value =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-debut-formation"
            ).textContent;
        inputFin.value =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-fin-formation"
            ).textContent;
        inputFormateur.value =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-formateur"
            ).textContent;
        // changer l'opacity de la page
        addOpacity(this);
    });
}

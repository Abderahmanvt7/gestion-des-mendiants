// modifier mendiant
let formModifier = document.getElementById("form-modifier"),
    btnsModifier = document.querySelectorAll(".btn-modifier"),
    inputNom = document.getElementById("nom-modifier"),
    inputPreNom = document.getElementById("prenom-modifier"),
    inputDateNaissance = document.getElementById("date-naissance-modifier"),
    inputIdModifier = document.getElementById("id-modifier"),
    iputIdChambre = document.getElementById("id-chambre-modifier");

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
        inputPreNom.value =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-prenom"
            ).textContent;
        inputDateNaissance.value =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-date-naissance"
            ).textContent;
        iputIdChambre.value =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-id-chambre"
            ).textContent;
        // changer l'opacity de la page
        addOpacity(this);
    });
}

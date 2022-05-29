// animation before loading page
let animationLoad = document.querySelector(".loading"),
    container = document.querySelector(".container"),
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
    // afficher container
    container.style.display = "block";
});

// select box filter
let filterSelect = document.getElementById("filter-list");

filterSelect.onchange = function () {
    this.parentElement.lastElementChild.click();
};

// Search for an element within list
let inputSearch = document.querySelector(".input-rechercher"),
    datalist = document.querySelector(".datalist-search"),
    btnRechercher = document.querySelector(".btn-search"),
    popupInvalidSearch = document.querySelector(".invalid-search");
let trCherched;
btnRechercher.addEventListener("click", function (e) {
    e.stopPropagation();
    let searcherdValue = inputSearch.value;

    searcherdValue = searcherdValue.replace(" ", "-");

    if (document.getElementById(`${searcherdValue}`) === null) {
        console.log("not found");
        popupInvalidSearch.style.display = "block";
    }
    if (document.getElementById(`${searcherdValue}`)) {
        trCherched = document.getElementById(`${searcherdValue}`);

        if (trCherched.parentElement.querySelectorAll(".item-searched")) {
            trCherched.parentElement
                .querySelectorAll(".item-searched")
                .forEach((tr) => {
                    tr.classList.remove("item-searched");
                });
        }

        trCherched.classList.add("item-searched");
        // window.scrollTo(0, trCherched.offsetHeight);
        window.scrollTo(0, trCherched.offsetTop - 100);
    }

    // remplacer l'espace entre le nom et le prenom par une tire (-), pour qu'il soit meme a l'id du tr cherche
    // rechercher a un ligne du tableau a le nom et le prenom comme un id
    // si on trouve le ligne rechercher on ajoute a lui la classe tr-chercherpour change son background et
    //  son couleur et scroller la page a sa position on efface cette classe a ses freres
});
// cacher popup invalid search si une click n'est pas sur bouton search
document.onclick = function () {
    popupInvalidSearch.style.display = "none";
};
inputSearch.onblur = function () {
    datalist.id = "";
};
inputSearch.addEventListener("keydown", function () {
    if (inputSearch.value) {
        datalist.id = "liste-des-mendinets";
    } else {
        datalist.id = "";
    }
});

// button go to top
let btnToTop = document.querySelector(".btn-to-top");

window.onscroll = function () {
    if (window.pageYOffset > 1500) {
        btnToTop.style.display = "block";
    } else {
        btnToTop.style.display = "none";
    }
};
btnToTop.addEventListener("click", function () {
    window.scrollTo(0, 0);
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

// button to click sumbit button
let clickSumbits = document.querySelectorAll(".click-sumbit");
for (let button of clickSumbits) {
    button.addEventListener("click", function () {
        this.parentElement.lastElementChild.click();
    });
}
// add opacity when a popup displayed
let addopacity = document.getElementById("add-opacity");

window.onload = function () {
    if (addopacity !== null) {
        addOpacity(addopacity);
    } else {
        removeOppacity();
    }
};

// Ajouter (mendiant- formation - activite)
let formAjouter = document.getElementById("form-ajouter"),
    btnAjouter = document.querySelector(".btn-ajouter");

btnAjouter.addEventListener("click", function () {
    formAjouter.style.display = "block";
    // changer l'opacity de la page
    addOpacity(this);
});

// Ajouter beneficaires
let formBeneficaireAjouter = document.getElementById(
        "form-ajouter-beneficaire"
    ),
    btnsBeneficaireAjouter = document.querySelectorAll(".btn-beneficaire"),
    idAcitvite = document.getElementById("id-ajouter-beneficaire"),
    nomActivite = document.getElementById("nom-ajouter-beneficaire");

for (let i = 0; i < btnsBeneficaireAjouter.length; i++) {
    btnsBeneficaireAjouter[i].addEventListener("click", function () {
        formBeneficaireAjouter.style.display = "block";

        idAcitvite.value =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-id"
            ).textContent;
        nomActivite.value =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-nom"
            ).textContent;

        // changer l'opacity de la page
        addOpacity(this);
    });
}

// supprimer (mendiant - formation - activite)
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
                ".td-nom"
            ).textContent;

        let prenom =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-prenom"
            ) || " ";
        prenom = prenom.textContent || "";

        inputIdSupprimer.value =
            this.parentElement.parentElement.parentElement.querySelector(
                ".td-id"
            ).textContent;
        // messge de confirmer la suppression
        messageConfirmerSuppression.style.display = "block";
        messageConfirmerSuppression.firstElementChild.innerHTML = `supprimer <span class="name"> ${nom} ${prenom} </span>?`;
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

<?php
    // start session 
    session_start();
    // renvoyer a l'authentification s'il n'a pas authentifier
    if (!isset($_SESSION['loggin'])) {
        header("location:../index.php");
        exit();
    }

    // Routes
    $css = "../layout/css/";
    $js = "../layout/js/";
    
        
    // define page name 
    $page_name_formation = "formations";

    // include functions
    include "../includes/functions/formation.php";
    // include  layoutfiles
    include "../includes/templates/header.php";
    include "../includes/templates/navbar.php";
    include "../includes/templates/loading.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // get filter type and pass it to the function
        if (isset($_POST['filter-list'])) { 
            $filter_type = $_POST['filter-list'];
            $_SESSION['formation-filter-type'] = $filter_type;
            filtrer_formations_liste();
        }

        // ajouter formation
        if (isset($_POST['id-ajouter'])) {
            $nom_ajouter = filter_var($_POST["nom-ajouter"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $domaine_ajouter = filter_var($_POST["domaine-ajouter"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $date_debut_ajouter = $_POST["date-debut-ajouter"];
            $date_fin_ajouter = $_POST["date-fin-ajouter"];
            $formateur_ajouter = filter_var($_POST["formateur-ajouter"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $ajouter_formation_reponse = ajouter_formation($nom_ajouter, $domaine_ajouter, $date_debut_ajouter, $date_fin_ajouter, $formateur_ajouter);
            if ($ajouter_formation_reponse) {
                filtrer_formations_liste();
                $operation_success = TRUE;
                $message_response = "Ajouter avec succes";
            } else {
                filtrer_formations_liste();
                $operation_success = FALSE;
                $message_response = "Echec de l'operation";
            }
        }

        // supprimer formation
        if (isset($_POST['id-supprimer'])) {
            $id_s = $_POST['id-supprimer'];
            if (supprimer_formation($id_s)) {
                filtrer_formations_liste();
                $operation_success = TRUE;
                $message_response = "Supprimer avec succes";
            } else {
                filtrer_formations_liste();
                $operation_success = FALSE;
                $message_response = "Echec de l'operation";
            }
        }

        // modifier formation
        if (isset($_POST['id-modifier'])) {
            $id_m = $_POST['id-modifier'];
            $nom_modifier = filter_var($_POST["nom-modifier"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $domaine_modifier = filter_var($_POST["domaine-modifier"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $date_debut_modifier = $_POST["date-debut-modifier"];
            $date_fin_modifier = $_POST["date-fin-modifier"];
            $formateur_modifier = filter_var($_POST["formateur-modifier"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if (modifier_formation($id_m, $nom_modifier, $domaine_modifier, $date_debut_modifier, $date_fin_modifier, $formateur_modifier)) {
                filtrer_formations_liste();
                $operation_success = TRUE;
                $message_response = "Modifier avec succes";
            } else {
                filtrer_formations_liste();
                $operation_success = FALSE;
                $message_response = "Echec de l'operation";
            }
        }
        
        // get beneficaire liste
        if (isset($_POST['beneficaire'])) {
            $id_ac = $_POST['beneficaire'];
            filtrer_formations_liste();
            get_beneficaires_list('formations', $id_ac);
        }

        // ajouter beneficaire
        if (isset($_POST['ajouter-beneficaire'])) {
            $id_formation = $_POST['id-ajouter-beneficaire'];
            $id_beneficaire = filter_var($_POST['id-beneficaire-ajouter'], FILTER_SANITIZE_NUMBER_INT);


            $ajouter_beneficaire_response = ajouter_beneficaire('formations', $id_formation, $id_beneficaire);
            if ($ajouter_beneficaire_response === 3) {
                filtrer_formations_liste();
                $operation_success = FALSE;
                $message_response = "Echec de l'operation";
            }
            if ($ajouter_beneficaire_response === 2) {
                filtrer_formations_liste();
                $operation_success = TRUE;
                $message_response = "Ajouter avec succes";
            }
            if ($ajouter_beneficaire_response === 1) {
                filtrer_formations_liste();
                $operation_success = FALSE;
                $message_response = "Recu la formation avant";
            }
            if ($ajouter_beneficaire_response === 0) {
                filtrer_formations_liste();
                $operation_success = FALSE;
                $message_response = "Le mendiant n'exist pas";
            }
        }
    }else {
        filtrer_formations_liste();
    }

    // Statistic des formations
    get_la_plus_recus('formations');
    $nombre_des_formations = get_row_numbers('formations');
    $pourcentage_formations = get_pourcentage_beneficaire('formations');

?>

    <span class="btn btn-to-top"> <i class="fa fa-arrow-up"> </i> </span>

    
<div class="list-header"> <!-- Debut list header -->
            
        <h2 class="primary"> FORMATIONS </h2>

            <div> <!-- Debut input rechercher a un mendient -->
                <input list="liste-des-mendinets" class="input-rechercher" placeholder="nom ou prenom">
                <span class="btn-search"> <i class="fa fa-search"></i></span>
                <datalist id="" class="datalist-search">
                    <?php for ($i = 0; $i < count($foramtions_noms); $i++) {
                    ?>    
                            <option value="<?php echo $foramtions_noms[$i]?>">
                    <?php  } 
                    ?>
                </datalist>
                <span class="invalid-search"> le nom est incorrect ou n'exist pas</span>
            </div> <!-- Fin input rechercher a un mendient -->
            
            <!-- Debut filter mendiants list -->
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                <select name="filter-list" class="filter-list" id="filter-list">
                    <option value="0">filterer par</option>
                    <optgroup label="par id">
                        <option value="1">croissant</option>
                        <option value="2">decroissant</option>
                    </optgroup>
                    <optgroup label="par nom">
                        <option value="3">croissant</option>
                        <option value="4">decroissant</option>
                    </optgroup>
                    <optgroup label="par domaine">
                        <option value="5">croissant</option>
                        <option value="6">decroissant</option>
                    </optgroup>
                    <optgroup label="par debut">
                        <option value="7">croissant</option>
                        <option value="8">decroissant</option>
                    </optgroup>
                    <optgroup label="par fin">
                        <option value="9">croissant</option>
                        <option value="10">decroissant</option>
                    </optgroup>
                    <optgroup label="par formateur">
                        <option value="11">croissant</option>
                        <option value="12">decroissant</option>
                    </optgroup>
                    <optgroup label="par beneficaires">
                        <option value="13">croissant</option>
                        <option value="14">decroissant</option>
                    </optgroup>
                </select>
                <button type="submit" hidden>submit</button>
            </form> <!-- Fin filter mendiants list -->
            
        </div> <!--Fin list header -->

<!-- Debut statistics boxes -->
<div class="statistics with-head-bar">
    <div class="box box-sm">
        <span class="box-icon rose rose-stransparent">
            <i class="fa fa-cubes"> </i>
        </span>
        <h2 class="rose">NOMBRE DES FORMATIONS </h2>
        <span> <?php echo $nombre_des_formations; ?> </span>
    </div>
    <div class="box box-sm">
        <div class="rose">
            <div class="mkCharts" 
                data-percent="<?php echo $pourcentage_formations; ?>"
                data-size="100" 
                data-stroke="3">
            </div>
            Pourcentage des formations reçus
        </div>
    </div>
    <div class="box box-sm plus-recus">
        LA FORMATION LA PLUS RECUS: <br>
        ID: <span class="name"> <?php echo $GLOBALS['la_plus_recus_id']; ?> </span> <br>
        NOM: <span class="name"> <?php echo $GLOBALS['la_plus_recus_nom'];?> </span>
    </div>
</div>
<!-- Fin statistics boxes -->


    <!-- Debut Conatainer -->
<div class="container">
        <div class="container-header">
            <h1> Liste des formations</h1>
            <button class="btn btn-primary btn-ajouter"> <!--button ajouter mendiant -->
                <i class="fa fa-plus"></i>
                Nouvelle formation
            </button>

        </div>
            
        
        <!-- Debut Liste des formations  -->
        <div class="table">
            <div class="thead">
                <div class="tr">
                    <h5> ID </h5>
                    <h5> NOM </h5>
                    <h5> DOMAINE </h5>
                    <h5> DATE DEBUT </h5>
                    <h5> DATE FIN </h5>
                    <h5> FORMATEUR </h5>
                    <div class="td-opr">
                        <h5> OPERATIONS </h5>
                    </div>
                </div>
            </div>
            <div class="tbody">
                <?php  for ($i = 0; $i < count($formations_ids); $i++) { 
                    ?>
                <div class="tr"  id="<?php echo $foramtions_noms[$i]?>">
                    <div class="td td-id"><?php echo $formations_ids[$i]; ?></div>
                    <div class="td td-nom"><?php echo $foramtions_noms[$i]; ?></div>
                    <div class="td td-domaine"><?php echo $formations_domaines[$i]; ?></div>
                    <div class="td td-debut-formation"><?php echo $formations_debut[$i]; ?></div>
                    <div class="td td-fin-formation"><?php echo $formations_fin[$i]; ?></div>
                    <div class="td td-formateur"><?php echo $formations_formateurs[$i]; ?></div>
                    <div class="td-opr">
                        <div>
                            <span class="btn danger btn-supprimer"><i class="fa fa-trash"></i>supprimer</span>
                        </div>
                        <div>
                            <span class="btn blue btn-modifier"><i class="fa fa-edit"></i>modifier</span>
                        </div>
                        <div>
                            <form class="formation-form" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
                                <input type="text" value="<?php echo $formations_ids[$i] ?>" name="beneficaire" hidden>
                                <span class="btn success click-sumbit"> <i class="fa fa-users"></i> beneficaires </span>
                                <input type="submit" hidden />
                            </form>
                        </div>
                        <div>
                            <span class="btn info btn-beneficaire"> <i class="fa fa-plus"></i> ajouter beneficaire</span>
                        </div>
                    </div>
                </div>
                <?php  } 
                    ?>
            </div>
        </div>  <!-- Fin Liste des formations  -->


        <!-- Debut supprimer formation -->
        <form id="supprimer" class="" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
            <input id="id-supprimer"type="text" name="id-supprimer" hidden >
            <div id="confirm-suppression">
                <div class="confirm-suppression"></div>
                <button class="btn btn-annuler-supression">Annuler</button>
                <button type="submit"class="btn btn-danger supprimer">Supprimer</button>
            </div>
        </form> <!-- Fin supprimer  formation -->


        <!--Debut form ajouter-->
        <div class="form-container form-toggle form-long" id="form-ajouter"> 
                <h1 class="text-center">Ajouter</h1>
                <button class="btn btn-close"></button>
                <form id="form-ajouter-activite"class="form" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
                    
                    <!--Debut input nom-->
                    <div>
                        <label class="nom">NOM</label>
                        <input id="nom-ajouter"class="form-control nom" type="text"
                            name="nom-ajouter" placeholder="nom de formation" autocomplete="off"
                            value="<?php if(isset($nom_ajouter))  echo $nom_ajouter; ?>" />
                    </div>            
                    <!--Fin input nom-->
                    
                    <!--Debut input domaine-->
                    <div>
                        <label class="domaine">DOMAINE</label>
                        <input id="domaine-ajouter"class="form-control"autocomplete="off"
                            type="text" name="domaine-ajouter" placeholder="domaine" 
                            value="<?php if(isset($domaine_ajouter))  echo $domine_ajouter; ?>" />
                    </div>            
                    <!--Fin input domaine-->
    
                    <!--Debut input date de debut-->
                    <div>
                        <label class="debut-ajouter">DATE DE DEBUT</label>
                        <input id="date-debut-ajouter" class="form-control debut-ajouter"
                            type="date" name="date-debut-ajouter" placeholder="date de debut" 
                            value="<?php if(isset($date_debut_ajouter))  echo $date_debut_ajouter; ?>" />
                    </div>            
                    <!--Fin input date de debut-->
                    
                    <!--Debut input date de fin-->
                    <div>
                        <label class="fin-ajouter">DATE DE FIN</label>
                        <input id="date-fin-ajouter" class="form-control fin-ajouter"
                            type="date" name="date-fin-ajouter" placeholder="date de fin" 
                            value="<?php if(isset($date_fin_ajouter))  echo $date_fin_ajouter; ?>" />
                    </div>            
                    <!--Fin input date de fin-->

                    <!--Debut input formateur-->
                    <div>
                        <label class="formateur-ajouter">FORMATEUR</label>
                        <input id="formateur-ajouter" class="form-control fin-ajouter"autocomplete="off"
                            type="text" name="formateur-ajouter" placeholder="nom formateur" 
                            value="<?php if(isset($formateur_ajouter))  echo $formateur_ajouter; ?>" />
                    </div>            
                    <!--Fin input formateur-->

                    <!--Debut: input contient l'id pour identifier-->
                    <div class="id-hidden">
                        <input type="text" id="id-ajouter" name="id-ajouter">
                    </div>
                    <!--Fin: input contient l'id pour identifier-->
    
                    <!--Debut boutton submit-->
                    <button type="submit" class="btn btn-primary form-control">
                        Ajouter
                    </button>
                    <!--Debut boutton submit-->
                </form>
            </div> <!-- Fin form ajouter -->


            <!--Debut form modifier-->
            <div class="form-container form-toggle form-long" id="form-modifier"> 
                <h1 class="text-center">Modifier</h1>
                <button class="btn btn-close"></button>
                <form id="form-modifier-formation"class="form" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
                    
                    <!--Debut input nom-->
                    <div>
                        <label class="nom">NOM</label>
                        <input id="nom-modifier"class="form-control nom" type="text"
                            name="nom-modifier" placeholder="nom de formation" 
                            value="<?php if(isset($nom_modifier))  echo $nom_modifier; ?>" />
                    </div>            
                    <!--Fin input nom-->
                    
                    <!--Debut input domaine-->
                    <div>
                        <label class="domaine">DOMAINE</label>
                        <input id="domaine-modifier"class="form-control" autocomplete="off"
                            type="text" name="domaine-modifier" placeholder="domaine" 
                            value="<?php if(isset($domaine_modifier))  echo $domine_modifier; ?>" />
                    </div>            
                    <!--Fin input domaine-->
    
                    <!--Debut input date de debut-->
                    <div>
                        <label class="debut-modifier">DATE DE DEBUT</label>
                        <input id="date-debut-modifier" class="form-control debut-modifier"
                            type="date" name="date-debut-modifier" placeholder="date de debut" 
                            value="<?php if(isset($date_debut_modifier))  echo $date_debut_modifier; ?>" />
                    </div>            
                    <!--Fin input date de debut-->
                    
                    <!--Debut input date de fin-->
                    <div>
                        <label class="fin-modifier">DATE DE FIN</label>
                        <input id="date-fin-modifier" class="form-control fin-modifier"
                            type="date" name="date-fin-modifier" placeholder="date de fin" 
                            value="<?php if(isset($date_fin_modifier))  echo $date_fin_modifier; ?>" />
                    </div>            
                    <!--Fin input date de fin-->

                    <!--Debut input formateur-->
                    <div>
                        <label class="formateur-modifier">FORMATEUR</label>
                        <input id="formateur-modifier" class="form-control"autocomplete="off"
                            type="text" name="formateur-modifier" placeholder="nom formateur" 
                            value="<?php if(isset($formateur_modifier))  echo $formateur_modifier; ?>" />
                    </div>            
                    <!--Fin input formateur-->
                    <!--Debut: input contient l'id pour identifier-->
                    <div class="id-hidden">
                        <input type="text" id="id-modifier" name="id-modifier">
                    </div>
                    <!--Fin: input contient l'id pour identifier-->
    
                    <!--Debut boutton submit-->
                    <button type="submit" class="btn btn-primary form-control">
                        Modifier
                    </button>
                    <!--Debut boutton submit-->
                </form>
            </div> <!-- Fin form modifier -->


            <!-- Debut de beneficiaires -->
            <?php 
                if (isset($beneficaire_dispalayed) && $beneficaire_dispalayed) { ?>
                    <spna id="add-opacity"> </spane>
                    <div id="beneficiaires" class="popup">
                        <button class="btn btn-close"> </button>
                        <?php 
                        if (count($beneficaire_id) > 0) { ?>
                            <h2 class="text-center">
                                <span class="name"> 
                                    <?php
                                        global $nom_table;
                                        echo $nom_table;
                                    ?> 
                                </span>
                                recus par les mendiants suivants:
                            </h2>
                            <div class="table">
                                <div class="thead">
                                    <div class="tr tr-beneficaire">
                                        <h5> ID </h5>
                                        <h5> NOM </h5>
                                        <h5> PRENOM </h5>
                                        <h5> DATE DE NAISSANCE</h5>
                                    </div>
                                    
                                </div>
                                <div class="tbody">
                                    <?php for ($i= 0; $i < count($beneficaire_id); $i++) { 
                                    ?>
                                            <div class="tr">
                                                <div> <?php echo $beneficaire_id[$i] ?></div>
                                                <div> <?php echo $beneficaire_nom[$i] ?></div>
                                                <div> <?php echo $beneficaire_prenom[$i] ?></div>
                                                <div> <?php echo $beneficaire_date_naissance[$i] ?></div>
                                            </div>
                                    <?php } 
                                    ?>
                                </div>
                            </div>
                            <?php } else { ?>
                                <h2 class="text-center"> 
                                <span class="name"> 
                                    <?php
                                        global $nom_table;
                                        echo $nom_table;
                                    ?>  
                                </span>
                                    ne recus par aucun mendiant
                                </h2>
                            <?php } ?>
                    </div>
            <?php } ?> <!-- Fin de beneficiaires -->
            
        
            <!--Debut form ajouter beneficaire-->
            <div class="form-container form-toggle" id="form-ajouter-beneficaire"> 
                <h2 class="h2 text-center">Ajouter Beneficaire</H2>
                <button class="btn btn-close"></button>
                <form id="form-ajouter-beneficaire"class="form" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
                    
                    <!--Debut input id formation-->
                    <div>
                        <label class="id">ID FORMATION</label>
                        <input id="id-ajouter-beneficaire"class="form-control nom" type="text"
                            name="id-ajouter-beneficaire" placeholder="id formation" readonly
                            value="<?php if(isset($id_formation))  echo $id_formation; ?>" />
                    </div>            
                    <!--Fin input id formation-->
    
                    <!--Debut input nom formation-->
                    <div>
                        <label class="nom">NOM FORMATION</label>
                        <input id="nom-ajouter-beneficaire"class="form-control nom" type="text"
                            name="nom-ajouter-beneficaire" placeholder="nom formation" readonly
                            value="<?php if(isset($nom_formation))  echo $nom_formation; ?>" />
                    </div>            
                    <!--Fin input nom formation-->
                    
                    <!--Debut input id beneficaire-->
                    <div>
                        <label class="id-beneficaire">ID BENEFICAIRE</label>
                        <input id="id-beneficaire-ajouter"class="form-control prenom" autocomplete="off"
                            type="text" name="id-beneficaire-ajouter" placeholder="id beneficaire" 
                            value="<?php if(isset($id_beneficaire))  echo $id_beneficaire; ?>" />
                    </div>            
                    <!--Fin input id beneficaire-->
    
                    <!--Debut input nom beneficaire-->
                    <div>
                        <label class="nom-beneficaire">NOM BENEFICAIRE</label>
                        <input id="nom-beneficaire-ajouter"class="form-control beneficaire-ajouter" autocomplete="off"
                            type="text" name="nom-beneficaire-ajouter" placeholder="nom beneficaire" 
                            value="<?php if(isset($nom_beneficaire))  echo $nom_beneficaire; ?>" />
                    </div>            
                    <!--Fin input nom beneficaire-->
                    
                    <!--Debut: input contient l'id pour identifier-->
                    <div class="id-hidden">
                        <input type="text" id="ajouter-beneficaire" name="ajouter-beneficaire">
                    </div>
                    <!--Fin: input contient l'id pour identifier-->
    
                    <!--Debut boutton submit-->
                    <button type="submit" class="btn btn-primary form-control">
                        <i class="fa fa-solid fa-paper-plane i-send"></i>
                        Ajouter
                    </button>
                    <!--Debut boutton submit-->
                </form>
            </div> <!-- Fin form ajouter -->



</div>


    <script src="../layout/js/pages/formations.js"></script>

<?php

    include "../includes/templates/footer.php";

?>
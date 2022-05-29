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
    $page_name_activite = "activites";

    // include functions
    include "../includes/functions/activite.php";
    // include  layoutfiles
    include "../includes/templates/header.php";
    include "../includes/templates/navbar.php";
    include "../includes/templates/loading.php";

    // manupiler les acitvites
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // get filter type and pass it to the function
        if (isset($_POST['filter-list'])) { 
            $filter_type = $_POST['filter-list'];
            $_SESSION['activite-filter-type'] = $filter_type;
            filtrer_activite_list();
        }

        // ajouter activites
        if (isset($_POST['id-ajouter'])) {
            $nom_ajouter = filter_var($_POST["nom-ajouter"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $domine_ajouter = filter_var($_POST["domaine-ajouter"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $date_activite_ajouter = $_POST["date-activite-ajouter"];

            $ajouter_activite_reponse = ajouter_activites($nom_ajouter, $domine_ajouter, $date_activite_ajouter);
            if ($ajouter_activite_reponse) {
                filtrer_activite_list();
                $operation_success = TRUE;
                $message_response = "Ajouter avec succes";
            } else {
                filtrer_activite_list();
                $operation_success = FALSE;
                $message_response = "Echec de l'operation";
            }
            
        }

        // supprimer activites
        if (isset($_POST['id-supprimer'])) {
            $id_s = $_POST['id-supprimer'];
            if (supprimer_activite($id_s)) {
                filtrer_activite_list();
                $operation_success = TRUE;
                $message_response = "Supprimer avec succes";
            } else {
                filtrer_activite_list();
                $operation_success = FALSE;
                $message_response = "Echec de l'operation";
            }
        }

        // modifier actvite
        if (isset($_POST['id-modifier'])) {
            $id_m = $_POST['id-modifier'];
            $nom_modifier = filter_var($_POST["nom-modifier"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $domine_modifier = filter_var($_POST["domaine-modifier"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $date_activite_modifier = $_POST["date-activite-modifier"];

            if (modifier_activite($id_m, $nom_modifier, $domine_modifier, $date_activite_modifier)) {
                filtrer_activite_list();
                $operation_success = TRUE;
                $message_response = "Modifier avec succes";
            } else {
                filtrer_activite_list();
                $operation_success = FALSE;
                $message_response = "Echec de l'operation";
            }
        }

        // get beneficaire liste
        if (isset($_POST['beneficaire'])) {
            $id_ac = $_POST['beneficaire'];
            filtrer_activite_list();
            get_beneficaires_list('activites', $id_ac);
        }

        // ajouter beneficaire
        if (isset($_POST['ajouter-beneficaire'])) {
            $id_activite = $_POST['id-ajouter-beneficaire'];
            $id_beneficaire = filter_var($_POST['id-beneficaire-ajouter'], FILTER_SANITIZE_NUMBER_INT);


            $ajouter_beneficaire_response = ajouter_beneficaire('activites', $id_activite, $id_beneficaire);
            if ($ajouter_beneficaire_response === 3) {
                filtrer_activite_list();
                $operation_success = FALSE;
                $message_response = "Echec de l'operation";
            }
            if ($ajouter_beneficaire_response === 2) {
                filtrer_activite_list();
                $operation_success = TRUE;
                $message_response = "Ajouter avec succes";
            }
            if ($ajouter_beneficaire_response === 1) {
                filtrer_activite_list();
                $operation_success = FALSE;
                $message_response = "Recu l'acitivite avant";
            }
            if ($ajouter_beneficaire_response === 0) {
                filtrer_activite_list();
                $operation_success = FALSE;
                $message_response = "Le mendiant n'exist pas";
            }
        }
    } else {
        filtrer_activite_list();
    }

    // Statistic des activites
    get_la_plus_recus('activites');
    $nombre_des_activites = get_row_numbers('activites');
    $pourcentage_activites = get_pourcentage_beneficaire('activites');
?>

    <span class="btn btn-to-top"> <i class="fa fa-arrow-up"> </i> </span>
    
    <div class="list-header"> <!-- Debut list header -->
            
            <h2 class="primary"> ACTIVITES </h2>

            <div> <!-- Debut input rechercher a un mendient -->
                <input list="liste-des-mendinets" class="input-rechercher" placeholder="nom ou prenom">
                <span class="btn-search"> <i class="fa fa-search"></i></span>
                <datalist id="" class="datalist-search">
                    <?php for ($i = 0; $i < count($activites_noms); $i++) {
                    ?>    
                            <option value="<?php echo $activites_noms[$i]?>">
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
                    <optgroup label="par date d'activite">
                        <option value="7">croissant</option>
                        <option value="8">decroissant</option>
                    </optgroup>
                    <optgroup label="par nombre de beneficaire">
                        <option value="9">croissant</option>
                        <option value="10">decroissant</option>
                    </optgroup>
                </select>
                <button type="submit" hidden>submit</button>
            </form> <!-- Fin filter mendiants list -->
            
    </div> <!--Fin list header -->

<!-- Debut statistics boxes -->
<div class="statistics with-head-bar">
    <div class="box box-sm">
        <span class="box-icon info info-stransparent">
            <i class="fa fa-cubes"> </i>
        </span>
        <h2 class="info">NOMBRE DES ACTIVITES </h2>
        <span> <?php echo $nombre_des_activites; ?> </span>
    </div>
    <div class="box box-sm">
        <div class="info">
            <div class="mkCharts" 
                data-percent="<?php echo $pourcentage_activites; ?>"
                data-size="100" 
                data-stroke="3">
            </div>
            Pourcentage des activites recus
        </div>
    </div>
    <div class="box box-sm plus-recus">
        L'ACTIVITE LA PLUS RECUS: <br>
        ID: <span class="name"> <?php echo $GLOBALS['la_plus_recus_id']; ?> </span> <br>
        NOM: <span class="name"> <?php echo $GLOBALS['la_plus_recus_nom'];?> </span>
    </div>
</div>
<!-- Fin statistics boxes -->

<div class="container">
        
        <div class="container-header">
            <h1> Liste des activites</h1>

            <button class="btn btn-primary btn-ajouter"> <!--button ajouter mendiant -->
                <i class="fa fa-plus"></i>
                Nouvelle activite
            </button>
        </div>
        
        <!-- Debut Liste des activites  -->
        <div class="table">
            <div class="thead">
                <div class="tr">
                    <h5> ID </h5>
                    <h5> NOM </h5>
                    <h5> DOMAINE </h5>
                    <h5> DATE ACTIVITE </h5>
                    <div class="td-opr">
                    <h5> OPERATIONS </h5>
                </div>
                </div>
            </div>
            <div class="tbody">
                <?php  for ($i = 0; $i < count($activites_ids); $i++) { 
                    ?>
                <div class="tr"  id="<?php echo $activites_noms[$i]?>">
                    <div class="td td-id"><?php echo $activites_ids[$i]; ?></div>
                    <div class="td td-nom"><?php echo $activites_noms[$i]; ?></div>
                    <div class="td td-domaine"><?php echo $activites_domaines[$i]; ?></div>
                    <div class="td td-date-activite"><?php echo $acitivites_dates[$i]; ?></div>
                    <div class="td-opr">
                        <div>
                            <span class="btn danger btn-supprimer"><i class="fa fa-trash"></i>supprimer</span>
                        </div>
                        <div>
                            <span class="btn blue btn-modifier"><i class="fa fa-edit"></i>modifier</span>
                        </div>
                        <div>
                            <form class="activite-form" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
                                <input type="text" value="<?php echo $activites_ids[$i] ?>" name="beneficaire" hidden>
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
        </div>  <!-- Fin Liste des activites  -->
        
            
    
        <!-- Debut supprimer activites -->
        <form id="supprimer" class="" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
            <input id="id-supprimer"type="text" name="id-supprimer" hidden >
            <div id="confirm-suppression">
                <div class="confirm-suppression"></div>
                <button class="btn btn-annuler-supression">Annuler</button>
                <button type="submit"class="btn btn-danger supprimer">Supprimer</button>
            </div>
        </form> <!-- Fin supprimer  activites -->
            
    
    
            <!--Debut form ajouter-->
            <div class="form-container form-toggle" id="form-ajouter"> 
                <h1 class="text-center">Ajouter</h1>
                <button class="btn btn-close"></button>
                <form id="form-ajouter-activite"class="form" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
                    
                    <!--Debut input nom-->
                    <div>
                        <label class="nom">NOM</label>
                        <input id="nom-ajouter"class="form-control nom" type="text"
                            name="nom-ajouter" placeholder="nom d'activite" autocomplete="off"
                            value="<?php if(isset($nom_ajouter))  echo $nom_ajouter; ?>" />
                    </div>            
                    <!--Fin input nom-->
                    
                    <!--Debut input domaine-->
                    <div>
                        <label class="domaine">DOMAINE</label>
                        <input id="domaine-ajouter"class="form-control prenom"autocomplete="off"
                            type="text" name="domaine-ajouter" placeholder="domaine" 
                            value="<?php if(isset($domine_ajouter))  echo $domine_ajouter; ?>" />
                    </div>            
                    <!--Fin input domaine-->
    
                    <!--Debut input date d'activite-->
                    <div>
                        <label class="activite-ajouter">Date d'acitivite</label>
                        <input id="date-activite-ajouter"class="form-control activite-ajouter"
                            type="date" name="date-activite-ajouter" placeholder="date d'acitivite" 
                            value="<?php if(isset($date_activite_ajouter))  echo $date_activite_ajouter; ?>" />
                    </div>            
                    <!--Fin input date d'activite-->
                    
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
            <div class="form-container form-toggle" id="form-modifier"> 
                <h1 class="text-center">Modifier</h1>
                <button class="btn btn-close"></button>
                <form id="form-modifier-activite"class="form" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
                    
                    <!--Debut input nom-->
                    <div>
                        <label class="nom">NOM</label>
                        <input id="nom-modifier"class="form-control nom" type="text"
                            name="nom-modifier" placeholder="nom d'activite" autocomplete="off"
                            value="<?php if(isset($nom_modifier))  echo $nom_modifier; ?>" />
                    </div>            
                    <!--Fin input nom-->
                    
                    <!--Debut input domaine-->
                    <div>
                        <label class="domaine">DOMAINE</label>
                        <input id="domaine-modifier"class="form-control prenom"autocomplete="off"
                            type="text" name="domaine-modifier" placeholder="domaine" 
                            value="<?php if(isset($domine_modifier))  echo $domine_modifier; ?>" />
                    </div>            
                    <!--Fin input domaine-->
    
                    <!--Debut input date d'activite-->
                    <div>
                        <label class="activite-ajouter">Date d'acitivite</label>
                        <input id="date-activite-modifier"class="form-control activite-ajouter"
                            type="date" name="date-activite-modifier" placeholder="date d'acitivite" 
                            value="<?php if(isset($date_activite_modifier))  echo $date_activite_modifier; ?>" />
                    </div>            
                    <!--Fin input date d'activite-->
    
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
                    
                    <!--Debut input id activite-->
                    <div>
                        <label class="id">ID ACTIVITE</label>
                        <input id="id-ajouter-beneficaire"class="form-control nom" type="text"
                            name="id-ajouter-beneficaire" placeholder="id activite" readonly
                            value="<?php if(isset($id_activite))  echo $id_activite; ?>" />
                    </div>            
                    <!--Fin input id activite-->
    
                    <!--Debut input nom activite-->
                    <div>
                        <label class="nom">NOM ACTIVITE</label>
                        <input id="nom-ajouter-beneficaire"class="form-control nom" type="text"
                            name="nom-ajouter-beneficaire" placeholder="nom activite" readonly
                            value="<?php if(isset($nom_activite))  echo $nom_activite; ?>" />
                    </div>            
                    <!--Fin input nom activite-->
                    
                    <!--Debut input id beneficaire-->
                    <div>
                        <label class="id-beneficaire">ID BENEFICAIRE</label>
                        <input id="id-beneficaire-ajouter"class="form-control prenom"autocomplete="off"
                            type="text" name="id-beneficaire-ajouter" placeholder="id beneficaire" 
                            value="<?php if(isset($id_beneficaire))  echo $id_beneficaire; ?>" />
                    </div>            
                    <!--Fin input id beneficaire-->
    
                    <!--Debut input nom beneficaire-->
                    <div>
                        <label class="nom-beneficaire">NOM BENEFICAIRE</label>
                        <input id="nom-beneficaire-ajouter"class="form-control beneficaire-ajouter"
                            type="text" name="nom-beneficaire-ajouter" autocomplete="off" placeholder="nom beneficaire" 
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
    


        <script src="../layout/js/pages/activites.js"></script>
<?php

    include "../includes/templates/footer.php";

?>
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
    $page_name_mendiant = "mendiant"; // utiliser pour definit la page ouvert

    // include layout files
    include "../includes/templates/header.php";
    include "../includes/templates/navbar.php";
    include "../includes/templates/loading.php";
    // include funcions
    include "../includes/functions/mendiant.php";

    // global variables
    $operation_success;
    $message_response = "";

    // manupilation des mendiants
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // get filter type and pass it to the function
        if (isset($_POST['filter-list'])) { 
            $filter_type = $_POST['filter-list'];
            $_SESSION['mendiant-filter-type'] = $filter_type;
            filtrer_mendiant_list();
        }

        // supprimer mendiant
        if (isset($_POST['id-supprimer'])) {
            $id_s = $_POST['id-supprimer'];
            if (supprimer_mendiant($id_s)) {
                filtrer_mendiant_list();
                $operation_success = TRUE;
                $message_response = "Supprimer avec succes";
            } else {
                $operation_success = FALSE;
                $message_response = "Echec de l'operation";
            }
        }

        // ajouter mendiant
        if (isset($_POST['id-ajouter'])) {
            
            $nom_ajouter = filter_var($_POST["nom"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $prenom_ajouter = filter_var($_POST["prenom"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $date_naissance_ajouter = $_POST["date-naissance"];
            $id_chambre_ajouter = filter_var($_POST['id-chambre'], FILTER_SANITIZE_NUMBER_INT);

            $ajouter_mendiant_reponse = ajouter_mendiant($nom_ajouter, $prenom_ajouter, $date_naissance_ajouter, $id_chambre_ajouter);
            if ($ajouter_mendiant_reponse === 1) {
                filtrer_mendiant_list();
                $operation_success = TRUE;
                $message_response = "Ajouter avec succes";
            } 
            if($ajouter_mendiant_reponse === 0){
                filtrer_mendiant_list();
                $operation_success = FALSE;
                $message_response = "Chambre plain";
            }
            if ($ajouter_mendiant_reponse === 2) {
                filtrer_mendiant_list();
                $operation_success = FALSE;
                $message_response = "Echec de l'operation";
            }
        }
        // modifier mendiant
        if (isset($_POST['id-modifier'])) {
            $id_m = $_POST['id-modifier'];
            $nom_modifier = filter_var($_POST["nom"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $prenom_modifier = filter_var($_POST["prenom"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $date_naissance_modifier = $_POST["date-naissance"];
            $id_chambre_modifier = filter_var($_POST['id-chambre'], FILTER_SANITIZE_NUMBER_INT);

            $modifer_mendiant_reponse = modifier_mendiant($id_m, $nom_modifier, $prenom_modifier, $date_naissance_modifier, $id_chambre_modifier);
            if ($modifer_mendiant_reponse === 2) {
                filtrer_mendiant_list();
                $operation_success = TRUE;
                $message_response = "Modifier avec succes";
            } 
            if($modifer_mendiant_reponse === 0){
                filtrer_mendiant_list();
                $operation_success = FALSE;
                $message_response = "Chambre plain";
            }
            if ($modifer_mendiant_reponse === 1) {
                filtrer_mendiant_list();
                $operation_success = FALSE;
                $message_response = "Echec de l'operation";
            }
        }

        // mendiant formations
        if (isset($_POST['formations'])) { 
            $id_f = $_POST['formations'];
            filtrer_mendiant_list();
            get_mendiant_formation($id_f);
        }

        // mendiant activites
        if (isset($_POST['activites'])) {
            $id_a =  $_POST['activites'];
            filtrer_mendiant_list();
            get_mendiant_activite($id_a);
        }
        
    } else {
        // renvoyer a l'authentification s'il n'a pas authentifier
        if (!isset($_SESSION['loggin'])) {
            header("location:../index.php");
            exit();
        }
        filtrer_mendiant_list(); 
    }

    // Statistics des mendiants
    $nombre_des_mendiants = get_row_numbers('mendiants');
    $pourcentage_formations = get_pourcentage_beneficaire('formations');
    $pourcentage_activites = get_pourcentage_beneficaire('activites');
    $pourcentage_de_capacite = get_pourcentage_beneficaire('chambres');
?>

    <span class="btn btn-to-top"> <i class="fa fa-arrow-up"> </i> </span>

    
    <div class="list-header"> <!-- Debut list header -->
            
        <h2 class="primary"> MENDIANTS </h2>
    
            <div> <!-- Debut input rechercher a un mendient -->
                <input list="liste-des-mendinets" class="input-rechercher" placeholder="nom ou prenom">
                <span class="btn-search"> <i class="fa fa-search"></i></span>
                <datalist id="" class="datalist-search">
                    <?php for ($i = 0; $i < count($mendiants_ids); $i++) {
                    ?>    
                            <option value="<?php echo $mendiants_noms[$i]." ". $mendiants_prenoms[$i]?>">
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
                    <optgroup label="par prenom">
                        <option value="5">croissant</option>
                        <option value="6">decroissant</option>
                    </optgroup>
                    <optgroup label="par date de naissance">
                        <option value="7">croissant</option>
                        <option value="8">decroissant</option>
                    </optgroup>
                    <optgroup label="par id de chambre">
                        <option value="9">croissant</option>
                        <option value="10">decroissant</option>
                    </optgroup>
                </select>
                <button type="submit" hidden>submit</button>
            </form> <!-- Fin filter mendiants list -->
            
        </div> <!--Fin list header -->


        <!-- Debut statistics boxes -->
    <div class="statistics with-head-bar">
        <div class="box box-sm-sm">
            <span class="box-icon blue blue-stransparent">
                <i class="fa fa-users"> </i>
            </span>
            <h3 class="blue">NOMBRE DES MENDIANTS </h3>
            <span> <?php echo $nombre_des_mendiants; ?> personnes</span>
        </div>
        <div class="box box-sm-sm">
            <div class="info">
                <div class="mkCharts" 
                    data-percent="<?php echo $pourcentage_activites; ?>"
                    data-size="100" 
                    data-stroke="3">
                </div>
                Pourcentage des activites reçus
            </div>
        </div>
        <div class="box box-sm-sm">
            <div class="rose">
                <div class="mkCharts" 
                    data-percent="<?php echo $pourcentage_formations; ?>"
                    data-size="100" 
                    data-stroke="3">
                </div>
                Pourcentage des formations reçus
            </div>
        </div>
        <div class="box box-sm-sm">
            <div class="success">
                <div class="mkCharts" 
                    data-percent="<?php echo $pourcentage_de_capacite; ?>"
                    data-size="100" 
                    data-stroke="3">
                </div>
                Capacité des chambres
            </div>
        </div>
    </div>
    <!-- Fin statistics boxes -->

<div class="container">
    <div class="container-header">
        <h1> Liste des mendiants</h1>
        <!--button ajouter mendiant -->
        <button class="btn btn-primary btn-ajouter">
                <i class="fa fa-plus"></i>
                Ajouter Mendiant
        </button>
    </div>
    
    <!-- Debut Liste des mendiants  -->
    <div class="table">
            <div class="thead">
                <div class="tr">
                    <h5> ID </h5>
                    <h5> NOM </h5>
                    <h5> PRENOM </h5>
                    <h5> DATE DE NAISSANCE </h5>
                    <h5> ID CHAMBRE </h5>
                    <div class="td-opr">
                        <h5> operations </h5>
                    </div>
                </div>
            </div>
            <div class="tbody">
                <?php  for ($i = 0; $i < count($mendiants_ids); $i++) { 
                    ?>
                <div class="tr"  id="<?php echo $mendiants_noms[$i] . "-". $mendiants_prenoms[$i];?>">
                    <div class="td td-id"><?php echo $mendiants_ids[$i]; ?></div>
                    <div class="td td-nom"><?php echo $mendiants_noms[$i]; ?></div>
                    <div class="td td-prenom"><?php echo $mendiants_prenoms[$i]; ?></div>
                    <div class="td td-date-naissance"><?php echo $mendiants_date_naissances[$i]; ?></div>
                    <div class="td td-id-chambre"><?php echo $mendiants_chambre_id[$i]; ?></div>
                    <div class="td-opr">
                        <div>
                            <span class="btn danger btn-supprimer"><i class="fa fa-trash"></i>supprimer</span>
                        </div>
                        <div>
                            <span class="btn blue btn-modifier"><i class="fa fa-edit"></i>modifier</span>
                        </div>
                        <div>
                            <form class="formation-form" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
                                <input type="text" value="<?php echo $mendiants_ids[$i] ?>" name="formations" hidden>
                                <span class="btn success click-sumbit"> <i class="fa fa-graduation-cap"></i> formations </span>
                                <input type="submit" hidden />
                            </form>
                        </div>
                        <div>
                            <form class="activite-form" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
                                <input type="text" value="<?php echo $mendiants_ids[$i] ?>" name="activites" hidden>
                                <span class="btn info click-sumbit"> <i class="fa fa-cubes"></i>  activites</span>
                                <input type="submit" hidden />
                            </form>
                        </div>
                    </div>
                </div>
                <?php  } 
                    ?>
            </div>
        </div>  <!-- Fin Liste des mendiants  -->



        <?php // Debut de mendiant formations popup
            if (isset($formations_dispalyed) && $formations_dispalyed) { ?>
                <span id="add-opacity"> </span>
                <div id="formation" class="popup">
                    <button class="btn btn-close"> </button>
                    <?php if (count($id_formation) > 0) { 
                    ?>
                        <h2 class="text-center">
                            <span class="name"> 
                                <?php
                                    global $nom_mendiant, $prenom_mendinat;
                                    echo $nom_mendiant . " ". $prenom_mendinat;
                                ?> 
                            </span>
                            recu les formations suivantes:
                        </h2>
                        <div class="table table-popup">
                            <div class="formation">
                                <div class="thead">
                                    <div class="tr tr-formation">
                                        <h5> ID</h5>
                                        <h5> NOM </h5>
                                        <h5> DOMAINE</h5>
                                        <h5> DEBUT</h5>
                                        <h5> FIN</h5>
                                        <h5> FORMATEUR </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="tbody">
                                <?php for ($i= 0; $i < count($id_formation); $i++) { 
                                ?>
                                    <div class="tr">
                                        <div> <?php echo $id_formation[$i] ?></div>
                                        <div> <?php echo $nom_formation[$i] ?></div>
                                        <div> <?php echo $domaine_formation[$i] ?></div>
                                        <div> <?php echo $debut_formation[$i] ?></div>
                                        <div> <?php echo $fin_formation[$i] ?></div>
                                        <div> <?php echo $formateur_formation[$i] ?></div>
                                </div>
                                <?php } 
                                ?>
                            </div>
                                </div>
                        <?php } else { 
                        ?>
                            <h2 class="text-center"> 
                            <span class="name"> 
                                <?php
                                    global $nom_mendiant, $prenom_mendinat;
                                    echo $nom_mendiant . " ". $prenom_mendinat;
                                ?> 
                            </span>
                                n'a recus aucune formation
                            </h2>
                        <?php } 
                        ?>
                </div>
        <?php } ?> <!-- Fin de mendiant formations popup-->
        
        
        <?php // Debut des activites popup-
            if (isset($activite_displayed) && $activite_displayed) { ?>
                <span id="add-opacity"> </span>
                <div id="activite" class="popup">
                    <button class="btn btn-close"> </button>
                    <?php if (count($id_activite) > 0) { 
                    ?>
                        <h2 class="text-center">
                            <span class="name"> 
                                <?php
                                    global $nom_mendiant, $prenom_mendinat;
                                    echo $nom_mendiant . " ". $prenom_mendinat;
                                ?> 
                            </span>
                            recu les activites suivantes:
                        </h2>
                        <div class="table">
                            <div class="thead">
                                <div class="tr tr-activite">
                                    <h5> ID</h5>
                                    <h5> NOM</h5>
                                    <h5> DOMAINE </h5>
                                    <h5> DATE </h5>
                                </div>
                            </div>
                            <div class="tbody">
                                <?php for ($i= 0; $i < count($id_activite); $i++) { 
                                ?>
                                        <div class="tr">
                                            <div> <?php echo $id_activite[$i] ?></div>
                                            <div> <?php echo $nom_activite[$i] ?></div>
                                            <div> <?php echo $domaine_activite[$i] ?></div>
                                            <div> <?php echo $date_activite[$i] ?></div>
                                        </div>
                                <?php } 
                                ?>
                            </div>
                        </div>
                    <?php } else { 
                    ?>
                        <h2 class="text-center"> 
                        <span class="name"> 
                            <?php
                                global $nom_mendiant, $prenom_mendinat;
                                echo $nom_mendiant . " ". $prenom_mendinat;
                            ?> 
                        </span>
                            n'a recus aucune activite
                        </h2>
                    <?php } 
                    ?>
                </div>
        <?php } ?> <!-- Fin des activites popup-->
        

        <!-- Debut supprimer un mendiant -->
        <form id="supprimer" class="" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
            <input id="id-supprimer"type="text" name="id-supprimer" hidden >
            <div id="confirm-suppression">
                <div class="confirm-suppression"></div>
                <button class="btn btn-annuler-supression">Annuler</button>
                <button type="submit"class="btn btn-danger supprimer">Supprimer</button>
            </div>
        </form> <!-- Fin supprimer un mendiant -->

        

        <!--Debut form ajouter-->
        <div class="form-container form-toggle" id="form-ajouter"> 
            <h1 class="text-center">Ajouter</h1>
            <button class="btn btn-close"></button>
            <form id="form"class="form" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
                
                <!--Debut input nom-->
                <div>
                    <label class="nom">NOM</label>
                    <input id="nom-ajouter"class="form-control nom" type="text"
                        name="nom" placeholder="nom d'utilisateur" autocomplete="off"
                        value="<?php if(isset($nom_ajouter))  echo $nom_ajouter; ?>" />
                    <i class="fa fa-user fa-fw i"></i>
                </div>            
                <!--Fin input nom-->
                
                <!--Debut input prenom-->
                <div>
                    <label class="prenom">PRENOM</label>
                    <input id="prenom-ajouter"class="form-control prenom"
                        type="text" name="prenom" placeholder="prenom" autocomplete="off"
                        value="<?php if(isset($prenom_ajouter))  echo $prenom_ajouter; ?>" />
                    <i class="fa fa-user fa-fw i"></i>
                </div>            
                <!--Fin input prenom-->

                <!--Debut input date de naissance-->
                <div>
                    <label class="date-naissance">Date de naissance</label>
                    <input id="date-naissance-ajouter"class="form-control date-naissance"
                        type="date" name="date-naissance" placeholder="date de naissace" 
                        value="<?php if(isset($date_naissance_ajouter))  echo $date_naissance_ajouter; ?>" />
                </div>            
                <!--Fin input date de naissance-->
                
                <!--Debut input id de chambre-->
                <div>
                    <label class="id-chambre">Id de chambre</label>
                    <input id="id-chambre-ajouter"class="form-control id-chambre"
                        type="text" name="id-chambre" placeholder="id de chambre" autocomplete="off"
                        value="<?php if(isset($id_chambre_ajouter))  echo $id_chambre_ajouter; ?>" />
                </div>            
                <!--Fin input id de chambre-->

                <!--Debut: input contient l'id pour modifier-->
                <div class="id-hidden">
                    <input type="text" id="id-ajouter" name="id-ajouter">
                </div>
                <!--Fin: input contient l'id pour modifier-->

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
            <form id="form"class="form" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
                
                <!--Debut input nom-->
                <div>
                    <label class="nom">NOM</label>
                    <input id="nom-modifier"class="form-control nom" type="text"
                        name="nom" placeholder="nom d'utilisateur" autocomplete="off"
                        value="<?php if(isset($nom_modifier))  echo $nom_modifier; ?>" />
                    <i class="fa fa-user fa-fw i"></i>
                </div>            
                <!--Fin input nom-->
                
                <!--Debut input prenom-->
                <div>
                    <label class="prenom">PRENOM</label>
                    <input id="prenom-modifier"class="form-control prenom"
                        type="text" name="prenom" placeholder="prenom" autocomplete="off"
                        value="<?php if(isset($prenom_modifier))  echo $prenom_modifier; ?>" />
                    <i class="fa fa-user fa-fw i"></i>
                </div>            
                <!--Fin input prenom-->

                <!--Debut input date de naissance-->
                <div>
                    <label class="date-naissance">Date de naissance</label>
                    <input id="date-naissance-modifier"class="form-control date-naissance"
                        type="date" name="date-naissance" placeholder="date de naissace" 
                        value="<?php if(isset($date_naissance_modifier))  echo $date_naissance_modifier; ?>" />
                </div>            
                <!--Fin input date de naissance-->
                
                <!--Debut input id de chambre-->
                <div>
                    <label class="id-chambre">Id de chambre</label>
                    <input id="id-chambre-modifier"class="form-control id-chambre"
                        type="text" name="id-chambre" placeholder="id de chambre" autocomplete="off"
                        value="<?php if(isset($id_chambre_modifier))  echo $id_chambre_modifier; ?>" />
                </div>            
                <!--Fin input id de chambre-->

                <!--Debut: input contient l'id pour modifier-->
                <div class="id-hidden">
                    <input type="text" id="id-modifier" name="id-modifier">
                </div>
                <!--Fin: input contient l'id pour modifier-->

                <!--Debut boutton submit-->
                <button type="submit" class="btn btn-primary form-control">
                    Modifier
                </button>
                <!--Debut boutton submit-->
            </form>
        </div> <!-- Fin form modifier -->

</div>

    <script src="../layout/js/pages/mendiants.js"></script>
<?php

    include "../includes/templates/footer.php";

?>
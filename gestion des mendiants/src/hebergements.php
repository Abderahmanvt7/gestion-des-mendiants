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
    $page_name_hebergement = "hebergement";
    
    // include functions files
    include "../includes/functions/hebergement.php";
    
    // include layout files
    include "../includes/templates/header.php";
    include "../includes/templates/navbar.php";
    include "../includes/templates/loading.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // get filter type and pass it to the function
        if (isset($_POST['filter-list'])) { 
            $filter_type = $_POST['filter-list'];
            $_SESSION['chambres-filter-type'] = $filter_type;
            filtrer_chambres_liste();
        }


        // ajoute nouveau chambre
        if (isset($_POST['nouveau-chambre'])) {
            if (ajouter_chambre()) {
                filtrer_chambres_liste();
                $operation_success = TRUE;
                $message_response = "Ajouter avec succes";
            } else {
                filtrer_chambres_liste();
                $operation_success = FALSE;
                $message_response = "Echec de l'operation";
            }
        }
        // supprimer chambre
        if (isset($_POST['id-supprimer'])) {
            $id_s = $_POST['id-supprimer'];
            if (supprimer_chambre($id_s)) {
                filtrer_chambres_liste();
                $operation_success = TRUE;
                $message_response = "Supprimer avec succes";
            } else {
                filtrer_chambres_liste();
                $operation_success = FALSE;
                $message_response = "Echec de l'operation";
            }
        }

        // ajouter mendiant au chambre
        if (isset($_POST['ajouter-au-chambre'])) {
            $id_chambre_ajouter = $_POST['id-chambre-ajouter-mendiant'];
            $id_mendiant_ajouter = $_POST['id-mendiant-ajouter'];

            $ajouter_au_chambre_reponse = ajouter_mendiant_au_chambre($id_chambre_ajouter, $id_mendiant_ajouter);
            if ($ajouter_au_chambre_reponse === 0) {
                filtrer_chambres_liste();
                $operation_success = FALSE;
                $message_response = "mendiant n'exist pas";
            }
            if ($ajouter_au_chambre_reponse === 1) {
                filtrer_chambres_liste();
                $operation_success = FALSE;
                $message_response = "Chambre est plain";
            }
            if ($ajouter_au_chambre_reponse === 2) {
                filtrer_chambres_liste();
                $operation_success = TRUE;
                $message_response = "Ajouter avec succes";
            }
        }
    } else {
        filtrer_chambres_liste();
    }

    // Statistic d'hebergement
    $nombre_des_chambres = get_row_numbers('chambres');
    $pourcentage_de_capacite = get_pourcentage_beneficaire('chambres');
?>
    <div class="hebergement-header">
        <div class="hebergement-header-head">
            <div class="hebergement-header-title">
                LISTE DES CHAMBRES
            </div>
            <!-- Debut filter chambre list -->
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                <select name="filter-list" class="filter-list" id="filter-list">
                    <option value="0">filterer par</option>
                    <optgroup label="par id">
                        <option value="1">croissant</option>
                        <option value="2">decroissant</option>
                    </optgroup>
                    <optgroup label="par capacite">
                        <option value="3">croissant</option>
                        <option value="4">decroissant</option>
                    </optgroup>
                </select>
                <button type="submit" hidden>submit</button>
            </form> <!-- Fin filter chambre list -->
        </div>
        <!-- Debut form ajouter chambre -->
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <span class="btn btn-primary ajouter-chambre">Nouveau Chambre</span>
            <input type="submit" name="nouveau-chambre" hidden>
        </form> <!-- Fin form ajouter chambre -->
        
    </div> <!-- fin hebergement header-->


<!-- Debut statistics boxes -->
<div class="statistics with-head-bar">
    <div class="box box-sm">
        <span class="box-icon success success-stransparent">
            <i class="fa fa-house-user"> </i>
        </span>
        <h2 class="success">NOMBRE DES CHAMBRES </h2>
        <span> <?php echo $nombre_des_chambres; ?> </span>
    </div>
    <div class="box box-sm">
        <div class="success">
            <div class="mkCharts" 
                data-percent="<?php echo $pourcentage_de_capacite; ?>"
                data-size="100" 
                data-stroke="3">
            </div>
            Pourcentage de capacite 
        </div>
    </div>
    <div class="box box-sm plus-recus">
        <h2> CAPACITE MAXIMALE POUR UN CHAMBRE</h2>
        <span class="success"> <?php echo CAPACITE_MAX; ?> personnes</span>
    </div>
</div>
<!-- Fin statistics boxes -->


    <!-- Debut hebegement -->
    <div class="hebergement">
        <div class="chambres-list">
        <?php 
            $count = 0; // utiliser pour afficher la liste des mendiants
            for ($i = 0; $i < count($chambres_ids); $i++) {
        ?>
            <div class="chambres-item">
                <div class="chambre-title">
                    <span>CHAMBRE <?php echo $chambres_ids[$i]; ?></span>
                    <button class="btn btn-primary btn-ajouter">+ Ajouter </button>
                </div>
                <div class="table">
                    <div class="thead">
                        <?php if ($chambres_capacites[$i] > 0) { 
                        ?>
                            <div class="tr">
                                <h5> ID </h5>
                                <h5> NOM </h5>
                                <h5> PRENOM</h5>
                            </div>
                        <?php } else { 
                        ?>  
                            <input type="text" class="chambre-id" value="<?php echo $chambres_ids[$i]; ?>" hidden />
                            <button name="supprimer-chambre" class="btn btn-danger btn-supprimer btn-supprimer-chambre">
                                supprimer le chambre
                            </button>
                        <?php }
                        ?>
                    </div>
                    <div class="tbody">
                        <?php for ($j = $count; $j < $chambres_capacites[$i] + $count; $j++) {
                        ?>
                            <div class="tr">
                                <div class="td"> <?php if(isset($mendiants_ids[$j])) echo $mendiants_ids[$j]; ?> </div>
                                <div class="td"> <?php if(isset($mendiants_noms[$j])) echo $mendiants_noms[$j]; ?> </div>
                                <div class="td"> <?php if(isset($mendiants_prenom[$j])) echo $mendiants_prenom[$j]; ?> </div>
                            </div>
                        <?php }
                        $count += $chambres_capacites[$i];
                        ?>
                    </div>
                </div>
                <div class="mendiants-count"><?php echo $chambres_capacites[$i];?> mendiants</div>
            </div>
        <?php }
        ?>
        </div>
    </div> <!-- fin hebergement-->
    
    
            <!--Debut form ajouter mendiant au chambre-->
            <div class="form-container form-toggle" id="form-ajouter-to-chambre"> 
                <h2 class="h2 text-center">Ajouter au chambre</H2>
                <button class="btn btn-close"></button>
                <form class="form" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
                    
                    <!--Debut input id chambre-->
                    <div>
                        <label class="id">ID CHAMBRE</label>
                        <input id="id-chambre-ajouter-mendiant"class="form-control nom" type="text"
                            name="id-chambre-ajouter-mendiant"  placeholder="id activite" readonly
                            value="<?php if(isset($id_chambre))  echo $id_chambre; ?>" />
                    </div>            
                    <!--Fin input id activite-->
                    
                    <!--Debut input id mendiant-->
                    <div>
                        <label class="id-mendiant">ID MENDIANT</label>
                        <input id="id-mendiant-ajouter"class="form-control prenom" autocomplete="off"
                            type="text" name="id-mendiant-ajouter" placeholder="id mendiant" 
                            value="<?php if(isset($id_mendiant))  echo $id_mendiant; ?>" />
                    </div>            
                    <!--Fin input id beneficaire-->
    
                    <!--Debut input nom mendiant-->
                    <div>
                        <label class="nom-mendiant">NOM MENDIANT</label>
                        <input id="nom-mendiant-ajouter"class="form-control beneficaire-ajouter"
                            type="text" name="nom-mendiant-ajouter" autocomplete="off" placeholder="nom mendiant" 
                            value="<?php if(isset($nom_mendiant))  echo $nom_mendiant; ?>" />
                    </div>            
                    <!--Fin input nom beneficaire-->
                    
                    <!--Debut: input contient l'id pour modifier-->
                    <div class="id-hidden">
                        <input type="text" id="ajouter-au-chambre" name="ajouter-au-chambre">
                    </div>
                    <!--Fin: input contient l'id pour modifier-->
    
                    <!--Debut boutton submit-->
                    <button type="submit" class="btn btn-primary form-control">
                        Ajouter
                    </button>
                    <!--Debut boutton submit-->
                </form>
            </div> <!-- Fin form mendiant au chambre -->
            

            <!-- Debut supprimer chambre -->
        <form id="supprimer" class="" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
            <input id="id-supprimer"type="text" name="id-supprimer" hidden >
            <div id="confirm-suppression">
                <div class="confirm-suppression"></div>
                <button class="btn btn-annuler-supression">Annuler</button>
                <button type="submit"class="btn btn-danger supprimer">Supprimer</button>
            </div>
        </form> <!-- Fin supprimer  chambre -->
    
<?php

    include "../includes/templates/footer.php";

?>
<!-- special js file for hebergement -->
<script src="../layout/js/pages/hebergements.js"></script>

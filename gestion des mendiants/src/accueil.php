<?php
    // start session
    session_start();
    // Routes
    $css = "../layout/css/";
    $js = "../layout/js/";
    
    // define page name 
    $page_name = "accueil";

    // renvoyer a l'authentification s'il n'a pas authentifier
    if (!isset($_SESSION['loggin'])) {
        header("location:../index.php");
        exit();
    }
    
    // include  layout files 
    include "../includes/templates/header.php";
    include "../includes/templates/navbar.php";
    include "../includes/templates/loading.php";
    // include functions files
    include  __DIR__ . "\..\configs\connection.php"; // connection
    include "../includes/functions/globals.php";
    
    // tables statistics
    $nombre_des_mendiants = get_row_numbers('mendiants');
    $nombre_des_formations = get_row_numbers('formations');
    $nombre_des_activites = get_row_numbers('activites');
    $nombre_des_chambres = get_row_numbers('chambres');
    // mendiants statistics
    $mendiants_recus_formations = get_nombre_beneficaire('formations');
    $mendiants_non_recus_formations = get_nombre_non_beneficaire('formations');
    $mendiants_recu_activites = get_nombre_beneficaire('activites');
    $mendiants_non_recus_activites = get_nombre_non_beneficaire('activites');
    $mendiant_plus_recus_formations = get_mendiant_le_plus_recus('formations');
    $mendiant_plus_recus_activites = get_mendiant_le_plus_recus('activites');

    // pourcentage des mendiants recus les activites et les formations
    $pourcentage_formations = get_pourcentage_beneficaire('formations');
    $pourcentage_activites = get_pourcentage_beneficaire('activites');

?>



<div class="app-title">
    CENTRE DE GESTION DES MENDINATS
</div>

<div class="statistics">
    <div class="box box-md"> 
        <span class="box-icon blue blue-stransparent">
            <i class="fa fa-users"> </i>
        </span>
        <h2 class="blue"> MENDIANTS </h2>
        <span> <?php echo $nombre_des_mendiants; ?> personnes</span>
    </div>
    <div class="box box-md">
        <span class="box-icon info info-stransparent">
            <i class="fa fa-cubes"> </i>
        </span>
        <h2 class="info"> ACTIVITES </h2>
        <span> <?php echo $nombre_des_activites; ?> activites</span>
    </div>
    <div class="box box-md">
        <span class="box-icon rose rose-stransparent">
            <i class="fa fa-graduation-cap"> </i>
        </span>
        <h2 class="rose"> FORMATIONS </h2>
        <span> <?php echo $nombre_des_formations; ?> formations</span>
    </div>
    <div class="box box-md">
    <span class="box-icon success success-stransparent">
            <i class="fa fa-house-user"> </i>
        </span>
        <h2 class="success"> HEBERGEMENT </h2>
        <span> <?php echo $nombre_des_chambres; ?> chambres </span>
    </div>
</div>

<div class="statistics">
    <div class="box box-ex">
        <div class="stat-text"> Nombre des mendiants recus des formations: <span class="name">
            <?php echo $mendiants_recus_formations;?>
        </span> presonnes</div>
        <div class="stat-text"> Nombre des mendiants recus des activites: <span class="name">
            <?php echo $mendiants_recu_activites;?>
        </span> presonnes</div>
        <div class="stat-text"> Nombre des mendiats n'a recus aucune formation: <span class="name">
            <?php echo $mendiants_non_recus_formations;?>
        </span> presonnes</div>
        <div class="stat-text"> Nombre des mendinats n'a recus aucune activite: <span class="name">
            <?php echo $mendiants_non_recus_activites;?>
        </span> presonnes</div>
        <div class="stat-text"> Le mendiant la plus recus des formations: <span class="name">
            ID - <?php echo $mendiant_plus_recus_formations;?>
        </span> </div>
        <div class="stat-text"> Le mendiant la plus recus des activites: <span class="name">
            ID - <?php echo $mendiant_plus_recus_activites;?>
        </span> </div>
    </div>
    <div class="box box-lg">
        <div class="statistics desgin">
            <div class="rose">
                <div class="mkCharts" 
                    data-percent="<?php echo $pourcentage_formations; ?>"
                    data-color="blue"
                    data-size="160" 
                    data-stroke="3">
                </div>
                Pourcentage des formations recus
            </div>
            <div class="info">
                <div class="mkCharts" 
                    data-percent="<?php echo $pourcentage_activites; ?>"
                    data-size="160" 
                    data-stroke="3">
                </div>
                Pourcentage des activites recus
            </div>
        </div>
    </div>
</div>


<script src="../layout/js/pages/accueil.js"></script>
<?php
    include "../includes/templates/footer.php";
?>
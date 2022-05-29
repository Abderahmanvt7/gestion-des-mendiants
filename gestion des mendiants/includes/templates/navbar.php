
    <nav class="navbar">
        <div class="navbar-content">
            <ul class="navbar-nav">
                <li class="navbar-item  <?php echo $page_name;?>">
                    <a href="accueil.php" class="navbar-link"> Accueille </a>
                </li>
                <li class="navbar-item <?php if(isset($page_name_mendiant)) echo $page_name_mendiant;?>">
                    <a href="mendiants.php" class="navbar-link" title="page des mendiants"> Mendiants </a>
                </li>
                <li class="navbar-item <?php if(isset($page_name_activite)) echo $page_name_activite;?>">
                    <a href="activites.php" class="navbar-link" title="page des activites"> Activites </a>
                </li>
                <li class="navbar-item <?php if(isset($page_name_formation)) echo $page_name_formation;?>">
                    <a href="formations.php" class="navbar-link" title="page des formations"> Formations </a>
                </li>
                <li class="navbar-item <?php if(isset($page_name_hebergement)) echo $page_name_hebergement;?>">
                    <a href="hebergements.php" class="navbar-link" title="page d'hebergement"> Hebergment </a>
                </li>
                <?php if ($_SESSION['root']) { // afficher la page seulement si l'utilisateur est root
                ?>
                <li class="navbar-item <?php if(isset($page_name_administrateur)) echo $page_name_administrateur;?>">
                    <a href="administateurs.php" class="navbar-link" title="page des administrateurs"> Administateurs </a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </nav>

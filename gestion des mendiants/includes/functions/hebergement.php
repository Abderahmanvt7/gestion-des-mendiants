<?php
    // include files
    include  __DIR__ . "\..\..\configs\connection.php"; // connection
    include __DIR__ . '\globals.php'; // some functions

    ######## global varibales ######
    // pour chambres info
    $chambres_ids = array();
    $chambres_capacites = array();
    // pour mendiants info
    $mendiants_ids = array();
    $mendiants_noms = array();
    $mendiants_prenom = array();

    // Afficher et filtrer liste des chambre
    function filtrer_chambres_liste() {
        global $connection,$chambres_ids, $chambres_capacites, $mendiants_ids;
        global $mendiants_noms, $mendiants_prenom;

        $filter_type = $_SESSION['chambres-filter-type'];
        if ($filter_type == 0 || $filter_type == 1) {
            $filter_chambres = "SELECT * FROM `chambres`";
            $filter_mendiants = "SELECT `mendiants`.`id_mendiant`, `nom`, `prenom`, `date_de_naissance` 
                FROM `mendiants`, `chambres` WHERE `mendiants`.`id_chambre` = `chambres`.`id_chambre` 
                ORDER BY `mendiants`.`id_chambre`";
        } else { // liste filtrer
            switch($filter_type) {
                case 2:
                    $filter_chambres = "SELECT * FROM `chambres` ORDER BY `id_chambre`  DESC";
                    $filter_mendiants = "SELECT `mendiants`.`id_mendiant`, `nom`, `prenom`, `date_de_naissance` 
                        FROM `mendiants`, `chambres` WHERE `mendiants`.`id_chambre` = `chambres`.`id_chambre` 
                        ORDER BY `mendiants`.`id_chambre` DESC";
                    break;
                case 3:
                    $filter_chambres = "SELECT * FROM `chambres` ORDER BY `capacite`"; 
                    $filter_mendiants = "SELECT `mendiants`.`id_mendiant`, `nom`, `prenom`, `date_de_naissance` 
                        FROM `mendiants`, `chambres` WHERE `mendiants`.`id_chambre` = `chambres`.`id_chambre` 
                        ORDER BY `capacite`";
                    break;
                case 4:
                    $filter_chambres = "SELECT * FROM `chambres` ORDER BY `capacite` DESC";
                    $filter_mendiants = "SELECT `mendiants`.`id_mendiant`, `nom`, `prenom`, `date_de_naissance` 
                        FROM `mendiants`, `chambres` WHERE `mendiants`.`id_chambre` = `chambres`.`id_chambre` 
                        ORDER BY `capacite` DESC";
                    break;
            }
        } 

        $result_chambres_list = $connection->query($filter_chambres);
        while ($row_chambres = $result_chambres_list->fetch_assoc()) {
            $chambres_ids[] = $row_chambres['id_chambre'];
            $chambres_capacites[] = $row_chambres['capacite'];
        }

        $result_mendiants_list = $connection->query($filter_mendiants);
        while ($row_mendiants = $result_mendiants_list->fetch_assoc()) {
            $mendiants_ids[] = $row_mendiants['id_mendiant'];
            $mendiants_noms[] = $row_mendiants['nom'];
            $mendiants_prenom[] = $row_mendiants['prenom'];
        }
    } // fin liste des chambres

    // Ajouter mendiant au chambre
    function ajouter_mendiant_au_chambre($id_chambre, $id_mendiant) {
        global $connection;

        // mendiant existe ou pas
        if (!is_exist('mendiants', 'id_mendiant', $id_mendiant)) { return 0; }
        
        // chambre plain ou pas
        if (!check_chambre_capacite($id_chambre)) { return 1;}

        // decrementer la capacite de chambre precedent
        $chambre_precedent = get_chambre_id($id_mendiant);
        $result_decrement = decrement_chambre_capacite($chambre_precedent);

        // ajouter au chambre
        $ajouter_au_chambre = "UPDATE `mendiants` SET `id_chambre` = $id_chambre 
            WHERE `id_mendiant` = $id_mendiant";
        $result_ajouter_au_chambre = $connection->query($ajouter_au_chambre);

        // incrementer la capacite de nouveau chambre
        $result_increment = increment_chambre_capacite($id_chambre);

        $result_return = $result_ajouter_au_chambre && $result_increment
            && $result_decrement;

        return 2;
    } // fin ajouter mendiant au chambre


    // Ajouter nouveau chambre
    function ajouter_chambre() {
        global $connection;

        $last_id = get_last_id('chambres', 'id_chambre');
        $last_id++;
        $ajouter_chambre = "INSERT INTO `chambres` (`id_chambre`, `capacite`) VALUES ('$last_id', '0')";

        $result_ajouter_chambre = $connection->query($ajouter_chambre);

        return $result_ajouter_chambre;
    } // Fin ajouter nouveau chambre

    // supprimer chambre
    function supprimer_chambre($id) {
        global $connection;

        if (!is_exist('chambres','id_chambre', $id)) { return FALSE;}

        $supprimer_chambre = "DELETE FROM `chambres` WHERE `id_chambre` = $id";
        $result_supprimer_chambre = $connection->query($supprimer_chambre);

        return $result_supprimer_chambre;
    } // fin supprimer chambre
?>
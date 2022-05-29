<?php

    // include files
    include  __DIR__ . "\..\..\configs\connection.php"; // connection
    include __DIR__ . '\globals.php'; // some functions

    ########## les variables globales #######
    // pour afficher la list des activites
    $activites_ids = array();
    $activites_noms = array();
    $activites_domaines = array();
    $acitivites_dates = array();


    // filter listes des activites
    function filtrer_activite_list() {
        global $connection, $activites_ids, $activites_noms, $activites_domaines, $acitivites_dates;
        
        $filter_type = $_SESSION['activite-filter-type'];
        if ($filter_type == 0) {
            $filter = "SELECT * FROM `activites`"; 
        } else { // liste filtrer
            switch($filter_type) {
                case 1:
                    $filter = "SELECT * FROM `activites` ORDER BY `id_activite` "; break;
                case 2:
                    $filter = "SELECT * FROM `activites` ORDER BY `id_activite`  DESC"; break;
                case 3:
                    $filter = "SELECT * FROM `activites` ORDER BY `nom_activite`"; break;
                case 4:
                    $filter = "SELECT * FROM `activites` ORDER BY `nom_activite` DESC"; break;
                case 5:
                    $filter = "SELECT * FROM `activites` ORDER BY `domaine`"; break;
                case 6:
                    $filter = "SELECT * FROM `activites` ORDER BY `domaine` DESC"; break;
                case 7:
                    $filter = "SELECT * FROM `activites` ORDER BY `date_activite`"; break;
                case 8:
                    $filter = "SELECT * FROM `activites` ORDER BY `date_activite` DESC"; break;
                case 9:
                    $filter = "SELECT DISTINCT `activites`.* FROM `mendiants`, `activites`, `mendiant_activites` 
                    WHERE `mendiants`.`id_mendiant` = `mendiant_activites`.`id_mendiant` 
                    AND `mendiant_activites`.`id_activite` = `activites`.`id_activite` 
                    GROUP BY `activites`.`id_activite` 
                    ORDER BY COUNT(`mendiant_activites`.`id_mendiant`);"; break;
                case 10:
                    $filter = "SELECT DISTINCT `activites`.* FROM `mendiants`, `activites`, `mendiant_activites` 
                    WHERE `mendiants`.`id_mendiant` = `mendiant_activites`.`id_mendiant` 
                    AND `mendiant_activites`.`id_activite` = `activites`.`id_activite` 
                    GROUP BY `activites`.`id_activite` 
                    ORDER BY COUNT(`mendiant_activites`.`id_mendiant`) DESC;"; break;
            }
        } 
        $result = $connection->query($filter);
        while ($row_activites = $result->fetch_assoc()) {

            $activites_ids[] = $row_activites['id_activite'];
            $activites_noms[] = $row_activites['nom_activite'];
            $activites_domaines[] = $row_activites['domaine'];
            $acitivites_dates[] = $row_activites['date_activite'];
        }
    } // end of filtrer list des activites


    // ajouter nouvelle activites
    function ajouter_activites($nom_activite, $domaine_activite, $date_activite) {
        global $connection;

        $ajouter_activite = "INSERT INTO `activites` (`id_activite`, `nom_activite`, `domaine`, `date_activite`) 
            VALUES (NULL, '$nom_activite', '$domaine_activite', '$date_activite')";
        
        $result_ajouter_activite = $connection->query($ajouter_activite);

        return $result_ajouter_activite;
    } // end ajouter nouvell activites



    // supprimer activites
    function supprimer_activite($id_activite) {
        global $connection;

        $supprimer_les_beneficaire = "DELETE FROM `mendiant_activites` WHERE `id_activite` = $id_activite";
        $supprimer_activite = "DELETE FROM `activites` WHERE `id_activite` = $id_activite";

        $result_supprimer_les_beneficaire = $connection->query($supprimer_les_beneficaire);
        $result_supprimer_activite = $connection->query($supprimer_activite);

        if ($result_supprimer_activite && $result_supprimer_les_beneficaire) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // end supprimer activites


    // modifier activites
    function modifier_activite($id_activite, $nom_activite, $domaine_activite, $date_activite) {
        global $connection;

        $modifier_activite = "UPDATE `activites` SET `nom_activite` = '$nom_activite', `domaine` = '$domaine_activite',
                `date_activite` = '$date_activite' WHERE `id_activite` = '$id_activite'";
        
        $result_modifier_activite = $connection->query($modifier_activite);

        return $result_modifier_activite;
    }

?>
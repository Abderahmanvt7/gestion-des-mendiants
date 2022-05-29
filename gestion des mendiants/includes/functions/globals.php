<?php

    ################ globales variables #####
    // pour la capacite
    const CAPACITE_MAX = 10;
    // pour afficher la liste des beneficaire
    $beneficaire_id = array();
    $beneficaire_nom = array();
    $beneficaire_prenom = array();
    $beneficaire_date_naissance = array();
    $nom_table;
    $beneficaire_dispalayed;

    // pour afficher l'id et le nom de formation | activite la plus recus
    $la_plus_recus_id;
    $la_plus_recus_nom;

    // get last id
    function get_last_id($table_name, $id_name) {
        global $connection;

        $get_table_ids = "SELECT `$id_name` FROM `$table_name` ORDER BY `$id_name`";

        $result_table_ids = $connection->query($get_table_ids);

        if (!$result_table_ids) {return -1; }// error case

        $last_table_id = $result_table_ids->num_rows;
        return $last_table_id;
    }

    // check chambre capacite capacite
    function check_chambre_capacite($id_chambre) {
        global $connection;

        if (!is_exist('chambres','id_chambre',$id_chambre)) {
            return FALSE;
        }
        
        $get_chambre_capactie = "SELECT `capacite` FROM `chambres` WHERE `id_chambre` = $id_chambre";

        $result_chambre_capacite = $connection->query($get_chambre_capactie);
        $row_chambre_capacite = $result_chambre_capacite->fetch_assoc();
        $chambre_capacite = $row_chambre_capacite['capacite'];

        if ($chambre_capacite < CAPACITE_MAX) { return TRUE; } 
        else { return FALSE; }
            
    } // end of check chambre capacite capacite

    // get id chambre
    function get_chambre_id($id) {
        global $connection;

        if (!is_exist('mendiants', 'id_mendiant', $id)) { return FALSE;}

        $get_chambre_id = "SELECT `id_chambre` FROM `mendiants` WHERE `id_mendiant` = $id";

        $result_get_chambre_id = $connection->query($get_chambre_id);
        $chambre_id = $result_get_chambre_id->fetch_assoc()['id_chambre'];

        return $chambre_id;

    }
    // increment capacite
    function increment_chambre_capacite($id_chambre) {
        global $connection;

        if ($id_chambre == "" || !is_exist('chambres','id_chambre', $id_chambre)) {
            return FALSE;
        }
        $get_chambre_capactie = "SELECT `capacite` FROM `chambres` WHERE `id_chambre` = $id_chambre";
        $result_chambre_capacite = $connection->query($get_chambre_capactie);
        $row_chambre_capacite = $result_chambre_capacite->fetch_assoc();
        $chambre_capacite = $row_chambre_capacite['capacite'];

        $chambre_capacite++;
        $increment_chambre_capacite = "UPDATE `chambres` SET `capacite` = '$chambre_capacite' WHERE `id_chambre` = '$id_chambre'";
        $result_increment_chambre_capacite = $connection->query($increment_chambre_capacite);

        return $result_increment_chambre_capacite;

    } // end of increment capacite

    // increment capacite
    function decrement_chambre_capacite($id_chambre) {
        global $connection;

        if ($id_chambre == "" || !is_exist('chambres','id_chambre', $id_chambre)) {
            return FALSE;
        }
        $get_chambre_capactie = "SELECT `capacite` FROM `chambres` WHERE `id_chambre` = $id_chambre";
        $result_chambre_capacite = $connection->query($get_chambre_capactie);
        $row_chambre_capacite = $result_chambre_capacite->fetch_assoc();
        $chambre_capacite = $row_chambre_capacite['capacite'];

        $chambre_capacite--;
        $decrement_chambre_capacite = "UPDATE `chambres` SET `capacite` = '$chambre_capacite' WHERE `id_chambre` = '$id_chambre'";
        $result_decrement_chambre_capacite = $connection->query($decrement_chambre_capacite);

        return $result_decrement_chambre_capacite;

    } // end of increment capacite


    // get formation or activites beneficaires
    function get_beneficaires_list($table_name, $id) {
        global $connection, $beneficaire_id, $beneficaire_nom, $beneficaire_prenom;
        global $beneficaire_date_naissance, $nom_table, $beneficaire_dispalayed;
        $beneficaire_dispalayed = TRUE;

        if ($table_name === 'activites') {
            $get_beneficaires = "SELECT DISTINCT
                `mendiants`.`id_mendiant`, `nom`, `prenom`, `date_de_naissance`
                FROM `mendiants`, `activites`, `mendiant_activites`
                WHERE `mendiants`.`id_mendiant` = `mendiant_activites`.`id_mendiant`  
                    AND `mendiant_activites`.`id_activite` = $id;";
            $get_nom = "SELECT `nom_activite` FROM `activites` WHERE `id_activite` = $id";
            $result_get_nom = $connection->query($get_nom);
            $nom_table = $result_get_nom->fetch_assoc()['nom_activite'];
        } else {
            $get_beneficaires = "SELECT DISTINCT
                `mendiants`.`id_mendiant`, `nom`, `prenom`, `date_de_naissance`
                FROM `mendiants`, `formations`, `former_mendiant`
                WHERE `mendiants`.`id_mendiant` = `former_mendiant`.`id_mendiant`  
                    AND `former_mendiant`.`id_formation` = $id;";
            $get_nom = "SELECT `nom_formation` FROM `formations` WHERE `id_formation` = $id";
            $result_get_nom = $connection->query($get_nom);
            $nom_table = $result_get_nom->fetch_assoc()['nom_formation'];
        }

        
        $result_get_beneficaire = $connection->query($get_beneficaires);
        
        while ($row_beneficaire = $result_get_beneficaire->fetch_assoc()) {
            $beneficaire_id[] = $row_beneficaire['id_mendiant'];
            $beneficaire_nom[] = $row_beneficaire['nom'];
            $beneficaire_prenom[] = $row_beneficaire['prenom'];
            $beneficaire_date_naissance[] = $row_beneficaire['date_de_naissance'];
        }
    } // end get formation or activites beneficaires

    // Ajouter nouveau beneficaire
    function ajouter_beneficaire($table_name, $id_row, $id_beneficaire) {
        global $connection;

        // verifier que le beneficaire exist ou pas
        if (!is_exist('mendiants','id_mendiant', $id_beneficaire)) {
            return 0; // mendiant n'exist pas
        }
        
        if (!recus_avant($table_name, $id_beneficaire, $id_row)) {
            return 1; // recus avant
        }

        if ($table_name === 'activites') {
            $ajouter_beneficaire = "INSERT INTO `mendiant_activites` (`id_mendiant`, `id_activite`) 
                VALUES ('$id_beneficaire', '$id_row')";
        } else { // formation
            $ajouter_beneficaire = "INSERT INTO `former_mendiant` (`id_mendiant`, `id_formation`) 
                VALUES ('$id_beneficaire', '$id_row')";
        }
        
        $result_ajouter_beneficaire = $connection->query($ajouter_beneficaire);

        if ($result_ajouter_beneficaire) {
            return 2; // ajouter avec success
        } else {
            return 3; // deja recus l'activite ou la formation
        }
    }

    // Nombre des beneficaires
    function get_nombre_beneficaire($table_name) {
        global $connection;

        $nombre_de_beneficaire = 0;

        if ($table_name === 'activites') {
            $get_nombre_beneficaire = "SELECT COUNT(*) AS 'nombre'
                FROM `mendiants`
                LEFT JOIN `mendiant_activites`
                ON `mendiant_activites`.`id_mendiant` = `mendiants`.`id_mendiant`
                WHERE `mendiant_activites`.`id_activite` IS NOT NULL";
        } else {
            $get_nombre_beneficaire = "SELECT COUNT(*) AS 'nombre'
                FROM `mendiants`
                LEFT JOIN `former_mendiant`
                ON `former_mendiant`.`id_mendiant` = `mendiants`.`id_mendiant`
                WHERE `former_mendiant`.`id_formation` IS NOT NULL";
        }

        $result_get_nombre_beneficaire = $connection->query($get_nombre_beneficaire);

        $nombre_de_beneficaire = $result_get_nombre_beneficaire->fetch_assoc()['nombre'];
        return $nombre_de_beneficaire;
    } // fin nombre des beneficaires

    // Nombre des non-beneficaires
    function get_nombre_non_beneficaire($table_name) {
        global $connection;

        $nombre_des_non_beneficaire = 0;

        if ($table_name === 'activites') {
            $get_nombre_non_beneficaire = "SELECT COUNT(*) AS 'nombre'
                FROM `mendiants`
                LEFT JOIN `mendiant_activites`
                ON `mendiant_activites`.`id_mendiant` = `mendiants`.`id_mendiant`
                WHERE `mendiant_activites`.`id_activite` IS NULL";
        } else {
            $get_nombre_non_beneficaire = "SELECT COUNT(*) AS 'nombre'
                FROM `mendiants`
                LEFT JOIN `former_mendiant`
                ON `former_mendiant`.`id_mendiant` = `mendiants`.`id_mendiant`
                WHERE `former_mendiant`.`id_formation` IS NULL";
        }

        $result_get_nombre_beneficaire = $connection->query($get_nombre_non_beneficaire);

        $nombre_des_non_beneficaire = $result_get_nombre_beneficaire->fetch_assoc()['nombre'];

        return $nombre_des_non_beneficaire;
    } // fin nombre des non-beneficaires

    // get nombres des champs d'une table
    function get_row_numbers($table_name) {
        global $connection;

        $get_all_row = "SELECT * FROM `$table_name`";
        $result_get_all_row = $connection->query($get_all_row);

        return $result_get_all_row->num_rows;
    } // fin get nombre des champs


    // pourcentage des mendiants
    function get_pourcentage_beneficaire($table_name) {
        $nombre_des_mendiants = get_row_numbers('mendiants');
        
        if ($table_name === 'chambres') {
            $capacite_totale = CAPACITE_MAX * get_row_numbers('chambres');
            $pourcentage = (int) ($nombre_des_mendiants * 100 / $capacite_totale);
            return $pourcentage;
        }

        if ($table_name === 'activites') {
            $autre_nombre = get_nombre_beneficaire('activites');
        } else {
            $autre_nombre = get_nombre_beneficaire('formations');
        }

        $pourcentage = (int) ($autre_nombre * 100 / $nombre_des_mendiants);

        return $pourcentage;
    } // Fin pourcentage des mendiats

    // La formation | activites la plus recus
    function get_la_plus_recus($table_name) {
        global $connection, $la_plus_recus_id, $la_plus_recus_nom;

        if ($table_name === 'activites') {
            $get_la_plus_recus = "SELECT DISTINCT `activites`.* FROM `mendiants`, `activites`, `mendiant_activites` 
            WHERE `mendiants`.`id_mendiant` = `mendiant_activites`.`id_mendiant` 
            AND `mendiant_activites`.`id_activite` = `activites`.`id_activite` 
            GROUP BY `activites`.`id_activite` 
            ORDER BY COUNT(`mendiant_activites`.`id_mendiant`) DESC
            LIMIT 1";
            $result_get_la_plus_recus = $connection->query($get_la_plus_recus);
            $les_plus_recus = $result_get_la_plus_recus->fetch_assoc();
            $la_plus_recus_id = $les_plus_recus['id_activite'];
            $la_plus_recus_nom = $les_plus_recus['nom_activite'];
        } else {
            $get_la_plus_recus = "SELECT DISTINCT `formations`.* 
                FROM `mendiants`, `formations`, `former_mendiant` 
                WHERE `mendiants`.`id_mendiant` = `former_mendiant`.`id_mendiant` 
                AND `former_mendiant`.`id_formation` = `formations`.`id_formation` 
                GROUP BY `formations`.`id_formation` 
                ORDER BY COUNT(`former_mendiant`.`id_mendiant`) DESC
                LIMIT 1";
            $result_get_la_plus_recus = $connection->query($get_la_plus_recus);
            $les_plus_recus = $result_get_la_plus_recus->fetch_assoc();
            $la_plus_recus_nom = $les_plus_recus['nom_formation'];
            $la_plus_recus_id = $les_plus_recus['id_formation'];
            
        }
        
        echo "id: " . $la_plus_recus_id . "<br>";
        echo "nom: " . $la_plus_recus_nom;
    } // fin la plus recus

    // le mendiant le plus recus des activites | formations
    function get_mendiant_le_plus_recus($table_name) {
        global $connection;

        if ($table_name === 'activites') {
            $get_le_plus_recus = "SELECT `id`, MAX(`nombre`)
                FROM (
                    SELECT `id_mendiant` AS id, COUNT(*) AS `nombre`
                    FROM `mendiant_activites`
                    GROUP BY `id_mendiant`
                    ORDER BY `nombre` DESC
                ) AS `maximum` ";
        } else {
            $get_le_plus_recus = "SELECT `id`, MAX(`nombre`)
                FROM (
                    SELECT `id_mendiant` AS id, COUNT(*) AS `nombre`
                    FROM `former_mendiant`
                    GROUP BY `id_mendiant`
                    ORDER BY `nombre` DESC
                ) AS `maximum` ";
        }

        
        $result_get_le_plus_recus = $connection->query($get_le_plus_recus);
        $id_le_plus_recus = $result_get_le_plus_recus->fetch_assoc()['id'];
        // le mendiant le plus recus des activites ou des formations
        return $id_le_plus_recus; 
    }
    // Recus (l'acitivite ou la formation) avant?
    function recus_avant($table_name, $id_beneficaire, $id_row) {
        global $connection;
        if ($table_name === "activites") {
            $get_previous = "SELECT `id_mendiant`, `id_activite` FROM `mendiant_activites`
                WHERE `id_mendiant` = $id_beneficaire AND `id_activite` = $id_row";
        } else {
            $get_previous = "SELECT `id_mendiant`, `id_formation` FROM `former_mendiant`
                WHERE `id_mendiant` = $id_beneficaire AND `id_formation` = $id_row";
        }
        
        $result_get_previous = $connection->query($get_previous);

        if ($result_get_previous->num_rows > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    } // fin recus avant

    // is_exist 
    function is_exist($table_name,$row_name, $id) {
        global $connection;

        $search_for_id = "SELECT `$row_name` FROM `$table_name` WHERE `$row_name` = $id";
        $result_search_for_id = $connection->query($search_for_id);

        if (!$result_search_for_id) { return FALSE; } // error

        if ($result_search_for_id->num_rows > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
        
    } // fin is exist

?>
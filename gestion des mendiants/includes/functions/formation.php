<?php
    
    // include files
    include  __DIR__ . "\..\..\configs\connection.php"; // connection
    include __DIR__ . '\globals.php'; // some functions

    ########## les variables globales #######
    // pour afficher la list des activites
    $formations_ids = array();
    $foramtions_noms = array();
    $formations_domaines = array();
    $formations_debut = array();
    $formations_fin = array();
    $formations_formateurs = array();


    // filtrer la liste des formations
    function filtrer_formations_liste() {
        global $connection, $formations_ids, $foramtions_noms, $formations_domaines;
        global $formations_debut, $formations_fin, $formations_formateurs;

        $filter_type = $_SESSION['formation-filter-type'];
        if ($filter_type == 0) {
            $filter = "SELECT * FROM `formations`"; 
        } else { // liste filtrer
            switch($filter_type) {
                case 1:
                    $filter = "SELECT * FROM `formations` ORDER BY `id_formation` "; break;
                case 2:
                    $filter = "SELECT * FROM `formations` ORDER BY `id_formation`  DESC"; break;
                case 3:
                    $filter = "SELECT * FROM `formations` ORDER BY `nom_formation`"; break;
                case 4:
                    $filter = "SELECT * FROM `formations` ORDER BY `nom_formation` DESC"; break;
                case 5:
                    $filter = "SELECT * FROM `formations` ORDER BY `domaine`"; break;
                case 6:
                    $filter = "SELECT * FROM `formations` ORDER BY `domaine` DESC"; break;
                case 7:
                    $filter = "SELECT * FROM `formations` ORDER BY `date_debut`"; break;
                case 8:
                    $filter = "SELECT * FROM `formations` ORDER BY `date_debut` DESC"; break;
                case 9:
                    $filter = "SELECT * FROM `formations` ORDER BY `date_fin`"; break;
                case 10:
                    $filter = "SELECT * FROM `formations` ORDER BY `date_fin` DESC"; break;
                case 11:
                    $filter = "SELECT * FROM `formations` ORDER BY `formateur`"; break;
                case 12:
                    $filter = "SELECT * FROM `formations` ORDER BY `formateur` DESC"; break;
                case 13:
                    $filter = "SELECT DISTINCT `formations`.* FROM `mendiants`, `formations`, `former_mendiant` 
                    WHERE `mendiants`.`id_mendiant` = `former_mendiant`.`id_mendiant` 
                    AND `former_mendiant`.`id_formation` = `formations`.`id_formation` 
                    GROUP BY `formations`.`id_formation` 
                    ORDER BY COUNT(`former_mendiant`.`id_mendiant`);"; break;
                case 14:
                    $filter = "SELECT DISTINCT `formations`.* FROM `mendiants`, `formations`, `former_mendiant` 
                    WHERE `mendiants`.`id_mendiant` = `former_mendiant`.`id_mendiant` 
                    AND `former_mendiant`.`id_formation` = `formations`.`id_formation` 
                    GROUP BY `formations`.`id_formation` 
                    ORDER BY COUNT(`former_mendiant`.`id_mendiant`); DESC"; break;
            }
        } 
        $result_formations_liste = $connection->query($filter);
        while ($row_formations = $result_formations_liste->fetch_assoc()) {
            $formations_ids[] = $row_formations['id_formation'];
            $foramtions_noms[] = $row_formations['nom_formation'];
            $formations_domaines[] = $row_formations['domaine'];
            $formations_debut[] = $row_formations['date_debut'];
            $formations_fin[] = $row_formations['date_fin'];
            $formations_formateurs[] = $row_formations['formateur'];
        }
    } // fin filtrer liste des formations


    // ajouter formation
    function ajouter_formation($nom, $domaine, $date_debut, $date_fin, $formateur) {
        global $connection;

        // get last id
        $id = get_last_id('formations', 'id_formation');
        if ($id == -1) { return 2;} // s'il ya un erreur dans la fonction get_last_id

        $id++;
        $ajouter_formation = "INSERT INTO `formations` (`id_formation`, `nom_formation`, `domaine`, `date_debut`, `date_fin`, `formateur`) 
            VALUES ($id, '$nom', '$domaine', '$date_debut', '$date_fin', '$formateur')";

        $result_ajouter_formation = $connection->query($ajouter_formation);

        return $result_ajouter_formation;
    } // fin ajouter formation


    // Supprimer formation
    function supprimer_formation($id_formation) {
        global $connection;

        $supprimer_les_beneficaire = "DELETE FROM `former_mendiant` WHERE `id_formation` = $id_formation";
        $supprimer_formation = "DELETE FROM `formations` WHERE `id_formation` = $id_formation";

        $result_supprimer_les_beneficaire = $connection->query($supprimer_les_beneficaire);
        $result_supprimer_formation = $connection->query($supprimer_formation);

        if ($result_supprimer_les_beneficaire && $result_supprimer_formation) {
            return TRUE;
        } else {
            return FALSE;
        }
    } // fin supprimer formation

    // Modifier formation
    function modifier_formation($id, $nom, $domaine, $date_debut, $date_fin, $formateur) {
        global $connection;
        
        $modifier_activite = "UPDATE `formations` SET `nom_formation` = '$nom',`domaine` = '$domaine', 
            `date_debut` = '$date_debut', `date_fin` = '$date_fin', `formateur` = '$formateur' 
            WHERE `formations`.`id_formation` = $id";
        $result_modifier_activite = $connection->query($modifier_activite);

        return $result_modifier_activite;
    }
?>
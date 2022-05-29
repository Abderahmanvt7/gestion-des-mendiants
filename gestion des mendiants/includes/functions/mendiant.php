<?php

    // include files
    include  __DIR__ . "\..\..\configs\connection.php"; // connection
    include __DIR__ . '\globals.php'; // some functions

    ########## les variables globales #######
    // pour afficher la list des mendiant
    $mendiants_ids = array();
    $mendiants_noms = array();
    $mendiants_prenoms = array();
    $mendiants_date_naissances = array();
    $mendiants_chambre_id = array();
    //pour afficher le nom et le prenom du mendiant en formation et activite
    $nom_mendiant;
    $prenom_mendinat;
    // pour afficher les formations recus par un mendiant
    $id_formation = array();
    $nom_formation = array();
    $domaine_formation = array();
    $debut_formation = array();
    $fin_formation = array();
    $formateur_formation = array();
    $formations_dispalyed;
    // pour afficher les activites recus par un mendiant
    $id_activite = array();
    $nom_activite = array();
    $domaine_activite = array();
    $date_activite = array();
    $activite_displayed;
    

    // filter la liste des mendiants
    function filtrer_mendiant_list() {
        global $connection, $mendiants_ids, $mendiants_noms;
        global $mendiants_prenoms, $mendiants_date_naissances, $mendiants_chambre_id;

        $filter_type = $_SESSION['mendiant-filter-type'];
        if ($filter_type == 0) {
            $filter = "SELECT * FROM `mendiants`"; 
        } else { // liste filtrer
            switch($filter_type) {
                case 1:
                    $filter = "SELECT * FROM `mendiants` ORDER BY `id_mendiant`"; break;
                case 2:
                    $filter = "SELECT * FROM `mendiants` ORDER BY `id_mendiant`  DESC"; break;
                case 3:
                    $filter = "SELECT * FROM `mendiants` ORDER BY `nom`"; break;
                case 4:
                    $filter = "SELECT * FROM `mendiants` ORDER BY `nom` DESC"; break;
                case 5:
                    $filter = "SELECT * FROM `mendiants` ORDER BY `prenom`"; break;
                case 6:
                    $filter = "SELECT * FROM `mendiants` ORDER BY `prenom` DESC"; break;
                case 7:
                    $filter = "SELECT * FROM `mendiants` ORDER BY `date_de_naissance`"; break;
                case 8:
                    $filter = "SELECT * FROM `mendiants` ORDER BY `date_de_naissance` DESC"; break;
                case 9:
                    $filter = "SELECT * FROM `mendiants` ORDER BY `id_chambre`"; break;
                case 10:
                    $filter = "SELECT * FROM `mendiants` ORDER BY `id_chambre` DESC"; break;
            }
        } 
        $result = $connection->query($filter);
        while ($row_mendiant = $result->fetch_assoc()) {

            $mendiants_ids[] = $row_mendiant['id_mendiant'];
            $mendiants_noms[] = $row_mendiant['nom'];
            $mendiants_prenoms[] = $row_mendiant['prenom'];
            $mendiants_date_naissances[] = $row_mendiant['date_de_naissance'];
            $mendiants_chambre_id[] = $row_mendiant['id_chambre'];
        }
    } // end of filter_mendiant_list();

    // get mendiant formations
    function get_mendiant_formation($id) {
        global $connection, $nom_mendiant, $prenom_mendinat, $id_formation, $nom_formation;
        global $domaine_formation, $debut_formation, $fin_formation, $formateur_formation;
        global $formations_dispalyed;
        $formations_dispalyed = true;

        $get_mendiant_formation = "SELECT DISTINCT
            `former_mendiant`.`id_formation`, `formations`.`nom_formation`, 
            `formations`.`domaine`, `formations`.`date_debut`,
            `formations`.`date_fin`, `formations`.`formateur`
        FROM `mendiants`, `formations`, `former_mendiant`
        WHERE `former_mendiant`.`id_mendiant` = $id AND 
            `formations`.`id_formation` = `former_mendiant`.`id_formation`;";

        $get_mendiat_nom_prenom = "SELECT `nom`, `prenom` FROM `mendiants` WHERE `id_mendiant` = $id";
        
        
        $result_mendiant_nom_prenom = $connection->query($get_mendiat_nom_prenom);
        $mendiant_nom_prenom = $result_mendiant_nom_prenom->fetch_assoc();
        $nom_mendiant = $mendiant_nom_prenom['nom'];
        $prenom_mendinat = $mendiant_nom_prenom['prenom'];

        $result_mendiant_formation = $connection->query($get_mendiant_formation);
        while($row_formation = $result_mendiant_formation->fetch_assoc()){
            $id_formation[] = $row_formation['id_formation'];
            $nom_formation[] = $row_formation['nom_formation'];
            $domaine_formation[] = $row_formation['domaine'];
            $debut_formation[] = $row_formation['date_debut'];
            $fin_formation[] = $row_formation['date_fin'];
            $formateur_formation[] = $row_formation['formateur'];
        };
    } // end of get_mendiant_formation

    // get mendiant activite
    function get_mendiant_activite($id) {
        global $connection, $nom_mendiant, $prenom_mendinat, $id_activite, $nom_activite;
        global $domaine_activite, $date_activite, $activite_displayed;
        $activite_displayed = true;

        $get_mendiat_nom_prenom = "SELECT `nom`, `prenom` FROM `mendiants` WHERE `id_mendiant` = $id";
        $result_mendiant_nom_prenom = $connection->query($get_mendiat_nom_prenom);
        $mendiant_nom_prenom = $result_mendiant_nom_prenom->fetch_assoc();
        $nom_mendiant = $mendiant_nom_prenom['nom'];
        $prenom_mendinat = $mendiant_nom_prenom['prenom'];

        $get_mendiant_activite = "SELECT DISTINCT `activites`.`id_activite`, `nom_activite`, `domaine`, `date_activite`
                FROM `mendiants`, `activites`, `mendiant_activites`
                WHERE `mendiant_activites`.`id_mendiant` = $id AND
                    `mendiant_activites`.`id_activite` = `activites`.`id_activite`";
        $result_mendiant_activite = $connection->query($get_mendiant_activite);
        while($row_actvite = $result_mendiant_activite->fetch_assoc()){
            $id_activite[] = $row_actvite['id_activite'];
            $nom_activite[] = $row_actvite['nom_activite'];
            $domaine_activite[] = $row_actvite['domaine'];
            $date_activite[] = $row_actvite['date_activite'];
        };
    } // end of get_mendiant_activite


    // Ajouter mendiant
    function ajouter_mendiant($nom, $prenom, $date_naissance, $id_chambre) {
        global $connection;

        // verifier la capacti du chambre 
        if (!check_chambre_capacite($id_chambre)) { return 0;} // chambre plain

        // get last id
        $id = get_last_id('mendiants', 'id_mendiant');
        if ($id == -1) { return 2;} // s'il ya un erreur dans la fonction get_last_id

        $id++; // l'id de nouvou mendiant
        $ajouter_mendiant = "INSERT INTO `mendiants`(`id_mendiant`, `nom`, `prenom`, `date_de_naissance`, `id_chambre`) 
            VALUES ('$id', '$nom', '$prenom', '$date_naissance', '$id_chambre')";

        $result_ajouter_mendiant = $connection->query($ajouter_mendiant);
        $result_increment = increment_chambre_capacite($id_chambre);

        if ($result_ajouter_mendiant && $result_increment) {
              return 1;   // ajouter avec succes
        } else {  
            return 2;  // echec
        } 

    } // end of ajouter mendiant

    // Supprimer mendiant
    function supprimer_mendiant($id) {
        global $connection;

        $delete_mendiant_formation = "DELETE FROM former_mendiant WHERE `id_mendiant` = $id";
        $delete_mendiant_activite = "DELETE FROM mendiant_activites WHERE `id_mendiant` = $id";
        $delete_mendiant = "DELETE FROM mendiants WHERE `id_mendiant` = $id";
        
        $result_delete_mendiant_formation = $connection->query($delete_mendiant_formation);
        $result_delete_mendiant_activite = $connection->query($delete_mendiant_activite);
        $result_delete_mendiant = $connection->query($delete_mendiant);
        
        $id_chambre = get_chambre_id($id); // pour decrementer la capacite du chambre
        $result_decrement = decrement_chambre_capacite($id_chambre);

        $result_return = $result_delete_mendiant_formation 
            && $result_delete_mendiant_activite && $result_delete_mendiant;

        return $result_return;
    } // end of delete mendiant

    // modifier mendiant
    function modifier_mendiant($id, $nom, $prenom, $date_naissance, $id_chambre) {
        global $connection;

        // get previous chambre id
        $get_previous_id_chambre = "SELECT id_chambre FROM mendiants WHERE id_mendiant = $id";
        $result_previous_id_chambre = $connection->query($get_previous_id_chambre);
        $previous_id_chambre = $result_previous_id_chambre->fetch_assoc()['id_chambre'];

        
        if ($previous_id_chambre != $id_chambre) {
            if (!check_chambre_capacite($id_chambre)) {
                return 0;// chambre plain
            } else {
                $result_increment = increment_chambre_capacite($id_chambre);
                $result_decrement = decrement_chambre_capacite($previous_id_chambre);
            }
            
        } else {
            $result_increment = TRUE;
            $result_decrement = TRUE;
        }
        
        // // modifier mendiant
        $modifier_mendiant = "UPDATE `mendiants` SET `nom` = '$nom', `prenom` = '$prenom', 
            `date_de_naissance` = '$date_naissance', `id_chambre` = $id_chambre 
            WHERE `id_mendiant` = $id";
        $result_modifier_mendiant = $connection->query($modifier_mendiant);
        
        $return_result = $result_modifier_mendiant 
            && isset($result_increment) && $result_increment 
            && isset($result_decrement) && $result_decrement;
        if ($return_result)
            return 2; // modifier avec success
        else
            return 1; // echec
    }
?>
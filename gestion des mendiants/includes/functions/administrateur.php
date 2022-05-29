<?php
    // include files
    include  __DIR__ . "\..\..\configs\connection.php"; // connection
    include __DIR__ . '\globals.php'; // some functions

    ########## les variables globales #######
    // pour afficher la list des administrateurs
    $admins_ids = array();
    $admins_usernames = array();
    $admins_emails = array();
    $admins_fullnames = array();
    $admins_permissions = array();

    // filtrer admins liste
    function filtrer_admins_liste() {
        global $connection, $admins_ids, $admins_usernames, $admins_emails, $admins_fullnames, $admins_permissions;

        $filter = "SELECT * FROM `administrateuers` ORDER BY `id`"; 
        $result_admins_liste = $connection->query($filter);

        while ($row_admins = $result_admins_liste->fetch_assoc()) {
            $admins_ids[] = $row_admins['id'];
            $admins_usernames[] = $row_admins['username'];
            $admins_emails[] = $row_admins['email'];
            $admins_fullnames[] = $row_admins['fullname'];
            $admins_permissions[] = $row_admins['permission'];
        }
    } // fin filtrer admins liste

    // Ajouter admin
    function ajouter_admin($username,$password, $email, $full_name, $permission) {
        global $connection;

        $ajouter_admin = "INSERT INTO `administrateuers` (`username`, `password`, `email`, `fullname`, `permission`) 
            VALUES ('$username', '$password', '$email', '$full_name', '$permission')";

        $result_ajouter_admin = $connection->query($ajouter_admin);

        return $result_ajouter_admin;
    } // fin ajouter admin

    // Supprimer admin
    function supprimer_admin($id) {
        global $connection;

        // verifier s'il y a au moins un root existe apres la suppression
        if (un_seul_root_reste($id)) {
            return 1; // pas de root apres cette operation
        }

        $supprimer_admin = "DELETE FROM `administrateuers` WHERE `id` = $id";
        $result_supprimer_admin = $connection->query($supprimer_admin);

        if ($result_supprimer_admin) {
            return 2; // supprimer avec success
        } else {
            return 0; // echec de l'operation
        }
    }

    // Modifer admin
    function modifer_admin($id, $username, $password, $email, $full_name, $permission) {
        global $connection;

        // verifier s'il y a au moins un root existe apres la modification
        if (un_seul_root_reste($id)) {
            return 1; // pas de root apres cette operation
        }

        $modifier_admin = "UPDATE `administrateuers` SET  `username` = '$username',
            `password` = '$password', `email` = '$email', `fullname` = '$full_name', 
            `permission` = '$permission' WHERE `administrateuers`.`id` = $id";

        $result_modifier_admin = $connection->query($modifier_admin);

        if ($result_modifier_admin) {
            return 2; // modifier avec success
        } else {
            return 0; // echec de l'operation
        }
    }

    // verifier s'il y a un seul root reste ou plusieurs
    function un_seul_root_reste($id) {
        global $connection;
        $permissions_liste = array();


        $chercher_a_root = "SELECT `permission` FROM `administrateuers` WHERE `id` != $id ";
        $result_chercher_a_root = $connection->query($chercher_a_root);

        while ($row_permission = $result_chercher_a_root->fetch_assoc()) {
            $permissions_liste[] = $row_permission['permission'];
        }

        foreach ($permissions_liste as $permission) {
            if ($permission == 'root') 
                return FALSE;
        }
        return TRUE;
    }
?>
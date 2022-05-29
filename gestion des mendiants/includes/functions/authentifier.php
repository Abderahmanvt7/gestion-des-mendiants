<?php
    // include files 
    include "./configs/connection.php";

    function authentifier($nomSaisie, $passwordSaisie) {
        global $connection;

        // get admins list
        $result = $connection->query('SELECT `username`, `password`, `permission` FROM `administrateuers`');

        $nomsListe = array();
        $passwordListe = array();
        $permissions = array();

        // prendre les donnes de la table administrateuers 
        while($row = $result->fetch_assoc()){
            $nomsListe[] = $row['username']; 
            $passwordListe[] = $row['password']; 
            $permissions[] = $row['permission'];
        };
        
        $nomIndexese = array_keys($nomsListe, $nomSaisie, false);

        if($nomIndexese === false){ 
            return 0; // le nom n'existe pas
        }
        // si le nom existe on passe a la boucle suivant
        foreach($nomIndexese as $index){
            if( $passwordListe[$index]  === $passwordSaisie){
                if ($permissions[$index] === "root") {
                    return 2; // l'admin exist et avoir la permission "root"
                } else {
                    return 1; // l'admin exist mais n'est pas "root"
                }
            }
        }
        /*
        foreach ($nomIndexese as $index) {
            if (password_verify($passwordListe[$index], $passwordSaisie)) {
                if ($permissions[$index] === "root") {
                    return 2; // l'admin exist et avoir la permission "root"
                } else {
                    return 1; // l'admin exist mais n'est pas "root"
                }
            }
        }
        */
        return 0;
    }
    

            // la methode array_keys() retoune:
            // 1- false: si le nom n'existe pas
            // 2- un tableau contient le ou les indexes du nom
    
            // l'element $nomsListe[index] associe a l'element $passwordListe[index]
            // on a les indexes du nom donc on peut connait leurs mots de passe a l'aide de ses indexes
            // la boucle compare le mot de passe de chaque index avec le mot de passe saisie 
            // si ce derniere egale a l'une on retourne true pour accepter le login
            // sinon alors ce mot de passe n'associe pas a le nom saisi, et on passe donc la return suivant
?>

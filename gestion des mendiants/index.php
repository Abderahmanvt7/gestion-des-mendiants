<?php
    // start session 
    session_start();
    $_SESSION['mendiant-filter-type'] = 0;
    $_SESSION['activite-filter-type'] = 0;
    $_SESSION['formation-filter-type'] = 0;
    $_SESSION['chambres-filter-type'] = 0;
    $_SESSION['loggin'] = FALSE;
    $_SESSION['root'] = FALSE;
    // Routes
    $css = "layout/css/";
    $js = "layout/js/";

    $page_name_index = "index"; // utiliser pour definit la page ouvert
    // include files
    include "./includes/templates/header.php";
    include "./configs/connection.php";
    include "./includes/functions/authentifier.php";

    //check if page opened by POST method
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        
        // le nom et le mot de passe saisie
        $username = $_POST["username"];
        $password = $_POST["password"];

        $auth_reponse = authentifier($username, $password);

        $operation_success = FALSE;
        $message_response = "Echec de l'operation";

        if ($auth_reponse === 2) {
            $_SESSION['root'] = TRUE;
        }

        if ($auth_reponse === 0) {
            $operation_success = FALSE;
            $message_response = "le nom ou le mot de passe est incorrect";
        } else {
            $_SESSION['loggin'] = TRUE;
            header("location:src/accueil.php");
            exit();
        }
    
    }
?>

    
    <div class="form-container form-trnsparenet"> <!-- Debut form -->

        <h1 class="text-center">LOGIN</h1>

        <form class="form" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
            
            <div> <!--Debut input nom-->
                <label class="username">NOM</label>
                <input id="username" class="username" required autocomplete="off"
                        type="text" name="username" placeholder="nom d'utilisateur" 
                        value="" />
                <i class="fa fa-user fa-fw i"></i>
            </div>  <!--Fin input nom-->              
            
            <div> <!--Debut input mot de passe-->
                <label class="password">MOT DE PASSE</label>
                <input id="password" class="password" 
                        type="password" name="password" placeholder="mot de passe" 
                        value="" required />
                <i class="fa fa-solid fa-lock fa-fw i"></i>
                <!-- icons pour afficher et cacher le mot de passe -->
                <span id="eye-img"><i class="fa fa-solid fa-eye eye-img"></i></span>
                <span id="eye-slash-img"><i class="fa fa-solid fa-eye-slash eye-slash-img"></i></span>
            </div> <!--Fin input mot de passe-->
            

            <button class="btn btn-primary" type="submit"> <!--Debut boutton submit-->
                Login
            </button> <!--Debut boutton submit-->
            
        </form>

    </div> <!-- Fin form -->

<?php

    include "./includes/templates/footer.php";
?>

    <script src="./layout/js/pages/authentification.js"></script>




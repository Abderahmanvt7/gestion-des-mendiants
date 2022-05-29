<?php
    // start session 
    session_start();
    // renvoyer a l'authentification s'il n'a pas authentifier
    if (!isset($_SESSION['loggin'])) {
        header("location:../index.php");
        exit();
    }
    // Routes
    $css = "../layout/css/";
    $js = "../layout/js/";
    $page_name_administrateur = "administrateur"; // utiliser pour definit la page ouvert

    // include layout files
    include "../includes/templates/header.php";
    include "../includes/templates/navbar.php";
    include "../includes/templates/loading.php";
    // include funcions
    include "../includes/functions/administrateur.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Ajouter admin
        if (isset($_POST['id-ajouter'])) {
            $username_ajouter = filter_var($_POST['username-ajouter'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password_ajouter = filter_var($_POST['password-ajouter'], FILTER_SANITIZE_SPECIAL_CHARS);
            $email_ajouter = filter_var($_POST['email-ajouter'], FILTER_SANITIZE_EMAIL);
            $full_name_ajouter = filter_var($_POST['full-name-ajouter'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $permission_ajouter = $_POST['permission-ajouter'];
            if (ajouter_admin($username_ajouter, $password_ajouter, $email_ajouter, $full_name_ajouter, $permission_ajouter)) {
                filtrer_admins_liste();
                $operation_success = TRUE;
                $message_response = "Ajouter avec succes";
            } else {
                filtrer_admins_liste();
                $operation_success = FALSE;
                $message_response = "Echec de l'operation";
            }
        }

        // supprimer admin
        if (isset($_POST['id-supprimer'])) {
            $id_s = $_POST['id-supprimer'];

            $result_supprimer_admin = supprimer_admin($id_s);

            if ($result_supprimer_admin === 2) {
                filtrer_admins_liste();
                $operation_success = TRUE;
                $message_response = "Supprimer avec succes";
            }

            if ($result_supprimer_admin === 1) {
                filtrer_admins_liste();
                $operation_success = FALSE;
                $message_response = "Il doit y avoir au moins un admin root";
            }

            if ($result_supprimer_admin === 0) {
                filtrer_admins_liste();
                $operation_success = FALSE;
                $message_response = "Echec de l'operation";
            }
        }

        // modifer admin
        if (isset($_POST['id-modifier'])) {
            $id_m = $_POST['id-modifier'];
            $username_modifier = filter_var($_POST['username-modifier'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password_modifier = filter_var($_POST['password-modifier'], FILTER_SANITIZE_SPECIAL_CHARS);
            $email_modifier = filter_var($_POST['email-modifier'], FILTER_SANITIZE_EMAIL);
            $full_name_modifier = filter_var($_POST['full-name-modifier'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $permission_modifier = $_POST['permission-modifier'];

            $result_modifier_admin = modifer_admin($id_m, $username_modifier, $password_modifier, $email_modifier, $full_name_modifier, $permission_modifier);
            
            if ($result_modifier_admin == 2) {
                filtrer_admins_liste();
                $operation_success = TRUE;
                $message_response = "Modifer avec succes";
            }

            if ($result_modifier_admin == 1) {
                filtrer_admins_liste();
                $operation_success = FALSE;
                $message_response = "Il doit y avoir au moins un admin root";
            }

            if ($result_modifier_admin == 0) {
                filtrer_admins_liste();
                $operation_success = FALSE;
                $message_response = "Echec de l'operation";
            }

        }
    } else {
        filtrer_admins_liste();
    }
?>

    <!--Debut liste header -->
    <div class="list-header">
        <div class="liste-header-title">
            LISTE DES ADMINS
        </div>
        <!--button ajouter admin -->
        <button class="btn btn-primary btn-ajouter"> 
            <i class="fa fa-plus"></i>
            AJOUTER ADMIN
        </button>
    </div> <!--Fin liste header -->

<div class="container admin"> 
    
    <!-- Debut Liste des admins  -->
    <div class="table">
        <div class="thead">
            <div class="tr">
                <h5> ID </h5>
                <h5> USERNAME </h5>
                <h5> EMAIL </h5>
                <h5> FULL NAMA </h5>
                <h5> PERMISSION </h5>
                <div class="td-opr">
                    <h5> OPERATIONS </h5>
                </div>
            </div>
        </div>
        <div class="tbody">
            <?php  for ($i = 0; $i < count($admins_ids); $i++) { 
                ?>
            <div class="tr">
                <div class="td td-id"><?php echo $admins_ids[$i]; ?></div>
                <div class="td td-username"><?php echo $admins_usernames[$i]; ?></div>
                <div class="td td-email"><?php echo $admins_emails[$i]; ?></div>
                <div class="td td-fullname"><?php echo $admins_fullnames[$i]; ?></div>
                <div class="td td-perminssion"><?php echo $admins_permissions[$i]; ?></div>
                <div class="td-opr">
                    <div>
                        <span class="btn danger btn-supprimer"><i class="fa fa-trash"></i> <span>supprimer</span> </span>
                    </div>
                    <div>
                        <span class="btn blue btn-modifier"><i class="fa fa-edit"></i> <span>modifier</span> </span>
                    </div>
                </div>
            </div>
            <?php  } 
                ?>
        </div>
    </div>  <!-- Fin Liste des admins  -->

</div> <!-- Fin container -->

    
    <!-- Debut supprimer  admin -->
    <form id="supprimer" class="" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
        <input id="id-supprimer"type="text" name="id-supprimer" hidden >
        <div id="confirm-suppression">
            <div class="confirm-suppression"></div>
            <button class="btn btn-annuler-supression">Annuler</button>
            <button type="submit"class="btn btn-danger supprimer">Supprimer</button>
        </div>
    </form> <!-- Fin supprimer admin -->

        <!--Debut form ajouter-->
        <div class="form-container form-toggle form-long" id="form-ajouter"> 
            <h1 class="text-center">Ajouter</h1>
            <button class="btn btn-close"></button>
            <form class="form" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
                
                <!--Debut input username-->
                <div>
                    <label class="nom">USERNAME</label>
                    <input id="username-ajouter"class="form-control" type="text"autocomplete="off"
                        name="username-ajouter" placeholder="admin username" required
                        value="<?php if(isset($username_ajouter))  echo $username_ajouter; ?>" />
                    <i class="fa fa-user fa-fw i"></i>
                </div>            
                <!--Fin input username-->
                
                <!--Debut input password-->
                <div>
                    <label class="password-ajouter">PASSWORD</label>
                    <input id="password-ajouter"class="form-control" required autocomplete="off"
                        type="password" name="password-ajouter" placeholder="password" 
                        value="<?php if(isset($password_ajouter))  echo $password_ajouter; ?>" />
                    <i class="fa fa-user fa-fw i"></i>
                </div>            
                <!--Fin input password-->

                <!--Debut input email-->
                <div>
                    <label class="email-ajouter">EMAIL</label>
                    <input id="email-ajouter"class="form-control" autocomplete="off"
                        type="email" name="email-ajouter" placeholder="email" 
                        value="<?php if(isset($email_ajouter))  echo $email_ajouter; ?>" />
                </div>            
                <!--Fin input email-->
                
                <!--Debut input full name-->
                <div>
                    <label class="full-name-ajouter">FULL NAME</label>
                    <input id="full-name-ajouter"class="form-control" autocomplete="off"
                        type="text" name="full-name-ajouter" placeholder="admin full name" 
                        value="<?php if(isset($full_name_ajouter))  echo $full_name_ajouter; ?>" />
                </div>            
                <!--Fin input full name-->
                
                <!--Debut input permission -->
                <div>
                    <label for="permission-ajouter"> PERMISSION</label>
                    <select name="permission-ajouter" id="permission-ajouter">
                        <option value="root"> root </option>
                        <option value="user"> user </option>
                    </select>
                </div>  <!--Fin input permission -->
                <!--Debut: input contient l'id pour modifier-->
                <div class="id-hidden">
                    <input type="text" id="id-ajouter" name="id-ajouter">
                </div>
                <!--Fin: input contient l'id pour modifier-->

                <!--Debut boutton submit-->
                <button type="submit" class="btn btn-primary form-control">
                    Ajouter
                </button>
                <!--Debut boutton submit-->
            </form>
        </div> <!-- Fin form ajouter -->


    
<!--Debut form modifier-->
<div class="form-container form-toggle form-long" id="form-modifier"> 
            <h1 class="text-center">Modifier</h1>
            <button class="btn btn-close"></button>
            <form class="form" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="POST">
                
                <!--Debut input username-->
                <div>
                    <label class="nom">USERNAME</label>
                    <input id="username-modifier"class="form-control" type="text" autocomplete="off"
                        name="username-modifier" placeholder="admin username" required
                        value="<?php if(isset($username_modifier))  echo $username_modifier; ?>" />
                    <i class="fa fa-user fa-fw i"></i>
                </div>            
                <!--Fin input username-->
                

                <!--Debut input email-->
                <div>
                    <label class="email-modifier">EMAIL</label>
                    <input id="email-modifier"class="form-control" autocomplete="off"
                        type="email" name="email-modifier" placeholder="email" 
                        value="<?php if(isset($email_modifier))  echo $email_modifier; ?>" />
                </div>            
                <!--Fin input email-->
                
                <!--Debut input full name-->
                <div>
                    <label class="full-name-modifier">FULL NAME</label>
                    <input id="full-name-modifier"class="form-control" autocomplete="off"
                        type="text" name="full-name-modifier" placeholder="admin full name" 
                        value="<?php if(isset($full_name_modifier))  echo $full_name_modifier; ?>" />
                </div>            
                <!--Fin input  full name-->
                
                <!--Debut input permission -->
                <div>
                    <label for="permission-modifier"> PERMISSION</label>
                    <select name="permission-modifier" id="permission-modifier">
                        <option value="user"> user </option>
                        <option value="root"> root </option>
                    </select>
                </div>  <!--Fin input permission -->

                <!--Debut input prenom-->
                <div>
                    <label class="password-modifier">PASSWORD</label>
                    <input id="password-modifier"class="form-control" required
                        type="password" name="password-modifier" placeholder="password" 
                        value="" />
                    <i class="fa fa-user fa-fw i"></i>
                </div>            
                <!--Fin input prenom-->

                <!--Debut: input contient l'id pour identifier-->
                <div class="id-hidden">
                    <input type="text" id="id-modifier" name="id-modifier">
                </div>
                <!--Fin: input contient l'id pour identifier-->


                <!--Debut boutton submit-->
                <button type="submit" class="btn btn-primary form-control">
                    Modifier
                </button>
                <!--Debut boutton submit-->
            </form>
        </div> <!-- Fin form modifier -->


<?php

    include "../includes/templates/footer.php";

?>

<script src="../layout/js/pages/administrateur.js"></script>

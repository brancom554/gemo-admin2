<?php
require_once(_APPS_PATH.'/classes/Database.php');
require_once(_APPS_PATH.'/classes/User.php');


if (isset($_POST['reinitialiser']) ) {

    $password1 = filter_input(INPUT_POST,'password1',FILTER_SANITIZE_STRING);
    $password2 = filter_input(INPUT_POST,'password2',FILTER_SANITIZE_STRING);
    $password3 = filter_input(INPUT_POST,'password3',FILTER_SANITIZE_STRING);

        if (empty($_POST['password1'])) {
            $message1 = "Veuillez remplir le champs";
        }
        if (empty($_POST['password2'])) {
            $message2 = "Veuillez remplir le champs";  
        }
        
        if (empty($_POST['password3'])) {
            $message3 = "Veuillez remplir le champs";   
        }

        if($_POST['password2'] === $_POST['password3']) {

                $user = new User();
                $error = $user->reinitialiser($password1,$password3,$url_array[2]);
        }else{
            $error = "Mot de passe incompatible";

        }
    
}

if(file_exists(_VIEW_PATH."/reinitialisation.phtml")) $view=$lib->lang."/reinitialisation.phtml";
else  $view=$lib->lang."/reinitialisation.phtml";
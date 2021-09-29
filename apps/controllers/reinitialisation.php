<?php
require_once(_APPS_PATH.'/classes/Database.php');
require_once(_APPS_PATH.'/classes/User.php');
require_once(_APPS_PATH.'/classes/Sms.php');



if (isset($_POST['reinitialiser']) ) {

    $code = filter_input(INPUT_POST,'code',FILTER_SANITIZE_STRING);
    $password2 = filter_input(INPUT_POST,'password2',FILTER_SANITIZE_STRING);
    $password3 = filter_input(INPUT_POST,'password3',FILTER_SANITIZE_STRING);

        if (empty($_POST['code'])) {
            $message1 = "Veuillez remplir le champs";
        }
        if (empty($_POST['password2'])) {
            $message2 = "Veuillez remplir le champs";  
        }
        
        if (empty($_POST['password3'])) {
            $message3 = "Veuillez remplir le champs";   
        }

        if($_POST['password2'] === $_POST['password3']) {

                $sms = new Sms();
                $error = $sms->checkCodeReinitialisation($code,$url_array[2]);

                if($error == true) {

                    $user = new User();
                    $error = $user->reinitialiserPassword($code,$password3,$url_array[2]);

                }
        }else{

            $error = "Mot de passe incompatible";

        }

        $error = "Vérifier votre numéro";
    
}

if(file_exists(_VIEW_PATH."/reinitialisation.phtml")) $view=$lib->lang."/reinitialisation.phtml";
else  $view=$lib->lang."/reinitialisation.phtml";
<?php
require_once(_APPS_PATH.'/classes/Database.php');
require_once(_APPS_PATH.'/classes/User.php');

if (isset($_POST['valider']) ) {
    $password1 = filter_input(INPUT_POST,'password1',FILTER_SANITIZE_STRING);
    $password2 = filter_input(INPUT_POST,'password2',FILTER_SANITIZE_STRING);
   
        if (empty($_POST['password1'])) {
            $message1 = "Veuillez remplir le champs";
        }
        if (empty($_POST['password2'])) {
            $message2 = "Veuillez remplir le champs";
            
        }else {
            if ($_POST['password1'] !== $_POST['password2']) {
                $message3 = "Veuillez renseignez le meme mot de passe dans les champs";
            }

            $password = new User();
            $error = $password->changePassword($password2);


            
        }
    }

if(file_exists(_VIEW_PATH.$lib->lang."/password_after_login.phtml"))  $view=$lib->lang."/password_after_login.phtml";
else  $view=$iniObj->defaultLang."/password_after_login.phtml";
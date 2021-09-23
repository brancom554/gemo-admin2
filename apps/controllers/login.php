<?php
require_once(_APPS_PATH.'/classes/Database.php');
require_once(_APPS_PATH.'/classes/User.php');


if (isset($_POST['connexion']) ) {
    $token = filter_input(INPUT_POST,'token',FILTER_SANITIZE_STRING);
    $telephone = filter_input(INPUT_POST,'phone',FILTER_SANITIZE_STRING);
    $passwords = filter_input(INPUT_POST,'password',FILTER_SANITIZE_STRING);
    
    if ($token != $_SESSION['token']) {
        $error = "Token expiré";

        /*var_dump($token);
        var_dump($_SESSION['token']);
        return;*/
        
    }else {
        if (empty($_POST['phone'])) {
            $message1 = "Veuillez remplir le champs";
        }
        if (empty($_POST['password'])) {
            $message2 = "Veuillez remplir le champs";
            
        }else {
            $user = new User();
            $error = $user->seConnecter($telephone,$passwords);
        }
    }
    
}
$_SESSION['token'] = bin2hex(random_bytes(35));

if(file_exists(_VIEW_PATH."/login.phtml")) $view=$lib->lang."/login.phtml";
else  $view=$lib->lang."/login.phtml";
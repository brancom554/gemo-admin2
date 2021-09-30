<?php
require_once(_APPS_PATH.'/classes/Database.php');
require_once(_APPS_PATH.'/classes/User.php');
require_once(_APPS_PATH.'/classes/Sms.php');


if (isset($_POST['reinitialiser']) ) {

    $telephone = filter_input(INPUT_POST,'phone',FILTER_SANITIZE_STRING);
    
    if(empty($telephone)) {

        $error = "Veuillez renseigner le numéro de téléphone";

    }else{

    $phone = '+229'.$telephone;

    $sms = new Sms();
    $key = $sms->generateSmsCode();
    $message = 'Votre code de réinitialisation de mot de passe: '.$key;
    $res = $sms->EnvoisSMS($phone,'GEMO',$message);
	
	//utilisation de l'api de gemo :
	$urlCheck="http://testapigemo.mydko-sarl.com?view=sendSMS&number=".$telephone."&msg=".$message;
	$res = file_get_contents($urlCheck);


    // var_dump($res['message']);
    // die();

    // $res = json_decode($res, true);

    
    if($res) {
        
        $storeCode = new Sms();
        $error = $storeCode->codeReinitialisation($key,$telephone);
        // $error = $storeCode->codeReinitialisation('cedric',$telephone);


    }else{
        $error = "Le code n'a pas été retourner";
        // var_dump($res);
    }

    }

  
    
}


if (isset($_POST['connexion']) ) {
    $token = filter_input(INPUT_POST,'token',FILTER_SANITIZE_STRING);
    $telephone = filter_input(INPUT_POST,'phone',FILTER_SANITIZE_STRING);
    $passwords = filter_input(INPUT_POST,'password',FILTER_SANITIZE_STRING);
    
    if ($token != $_SESSION['token']) {
        $error = "Token expiré";
        
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
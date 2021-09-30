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
	

	$number = "+229".$telephone;
$sender = "GEMO";

	//new sms sender
	$ch = curl_init('https://textbelt.com/text');
$data = array(
  'phone' => $number.'',
  'senderId' => $sender.'',
  'message' => $message.'',
  'key' => 'e16f0f94c6cd23c7cbbf898674f230759b6e5d7bDaXXxLRAPmyoOTs2WyYvpHir7',
);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$res = curl_exec($ch);
curl_close($ch);

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
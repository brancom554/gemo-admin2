<?php
require_once(_APPS_PATH.'/classes/Database.php');

if (isset($_POST['soumettre']) ) {
$sql = 'UPDATE users SET licence_id= 0 WHERE licence_id=:id';
$data=[
        'id' => $url_array[4],
    ];
    $db = new Database();
    $query = $db->InsertDb($sql,$data);
     var_dump($query);
    if ($query == true) {
        $_SESSION['notification'] = "Révoqué avec succès.";
        header('Location:/administration/agent');
        exit;
        
    }else {
        $_SESSION['notification'] = "Opération echouée.";
        header('Location:/administration/agent');
        exit;
    }
}

$sql = 'SELECT licence_key FROM licences WHERE licence_id ='.$url_array[4];
        $db = new Database();
        $response = $db->DisplaysDataDb($sql);

if(file_exists(_VIEW_PATH.$lib->lang."/revocation_licence.phtml"))  $view=$lib->lang."/revocation_licence.phtml";
else  $view=$iniObj->defaultLang."/revocation_licence.phtml";
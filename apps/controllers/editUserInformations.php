<?php
require_once(_APPS_PATH.'/classes/Database.php');

if (isset($_POST['soumettre'])) {
    
    if (empty($_POST['firstname'])) {
        $message1="Veuillez remplir le champs";
    }
    if (empty($_POST['lastname'])) {
        $message2="Veuillez remplir le champs";
    }
    if (empty($_POST['phone_number'])) {
        $message3="Veuillez remplir le champs";
    }
    else {

        $firstname = filter_input(INPUT_POST,'firstname',FILTER_SANITIZE_STRING);
        $lastname = filter_input(INPUT_POST,'lastname',FILTER_SANITIZE_STRING);
        $phone_number = filter_input(INPUT_POST,'phone_number',FILTER_SANITIZE_STRING);

        $sql = 'UPDATE users SET firstname=:firstname, lastname=:lastname, phone_number=:phone_number WHERE user_id=:id';
        // $data = array("firstname" => $_SESSION['user_id'], "lastname" => password_hash($password2, PASSWORD_DEFAULT), "phone_number" => $formatt);
        $db = new Database();
        $data=[
            'id' => $url_array[4],
            'lastname'=> $lastname,
            'phone_number'=> $phone_number,
            'firstname'=> $firstname
        ];
        $response = $db->InsertDb($sql,$data);

        if(!is_array($response)){
            header('Location:/administration/utilisateurs');
        }else{
            var_dump($response);
        }
    }
}

$db = new Database();

$data['user'] = $db->DisplaysDataDb('SELECT * FROM users WHERE user_id='. $url_array[4]);


if(file_exists(_VIEW_PATH.$lib->lang."/children/editUserManager.phtml"))  $view=$lib->lang."/children/editUserManager.phtml";
else  $view=$lib->lang."/children/editUserManager.phtml";


<?php
require_once(_APPS_PATH.'/classes/Database.php');

if (isset($_POST['soumettre'])) {

    
    
    if (empty($_POST['phone'])) {
        $message="Veuillez remplir le champs";
    }
    if (empty($_POST['uuid'])) {
        $messageuuid="Veuillez remplir le champs";
    }
    else {
        $date = new DateTime();
        if ($_POST['password'] === $_POST['confirmer']) {
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT); 

            // var_dump($_POST, $_POST['password'],$_SESSION['address']);

            //  die();
        }else{
            $messagepassword = "Veuillez bien réécrire le mot de passe.";
        }
        
        $sql = 'INSERT INTO users (firstname,lastname,phone_number,creation_date,address_id,company_id,is_active_flag,is_manager,encrypted_password,hash,application_uuid,data_version) VALUES (:firstname,:lastname,:phone,:ddate,:aaddress,:company,:active,:manager,:ppassword,:hhash,:uuid,:vversion)';
        $data=[
            'firstname'=>strtoupper($_POST['nom']),
            'lastname' =>$_POST['prenom'],
            'phone'=> $_POST['phone'],
            'ddate' =>$date->format('Y-m-d H:i'),
            'aaddress' => (int) $_SESSION['address'],
            'company'=> (int) $_SESSION['company'],
            'active' => 1,
            'manager' => 0,
            'ppassword'=> $hash,
            'hhash'=> md5($_POST['password']),
            'uuid'=>$_POST['uuid'],
            'vversion'=> 1
        ];
        $db = new Database();
        $query = $db->InsertDb($sql,$data);
        if (is_array($query)) {

            $message1 = 'Vos informations n\'ont pas pu etre envoyé';
            // var_dump($query);
             exit;
        } else {
            if ($query == true) { 
                header('Location:/administration/utilisateurs');
                exit;  
            }
        }
    }
}

if(file_exists(_VIEW_PATH.$lib->lang."/children/addUserManager.phtml"))  $view=$lib->lang."/children/addUserManager.phtml";
else  $view=$lib->lang."/children/addUserManager.phtml";


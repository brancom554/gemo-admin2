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
        if ($_POST['password'] == $_POST['confirmer']) {
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT); 
        }else{
            $messagepassword = "Veuillez bien réécrire le mot de passe.";
        }
        
        $sql = 'INSERT INTO users (firstname,lastname,phone_number,creation_date,address_id,company_id,is_active_flag,is_manager,encrypted_password,hash,application_uuid,data_version) VALUES (:firstname,:lastname,:phone,:date,:address,:company,:active,:manager,:password,:hash,:uuid,:version)';
        $data=[
            'firstname'=>strtoupper($_POST['nom']),
            'lastname' =>$_POST['prenom'],
            'phone'=> $_POST['phone'],
            'date' =>$date->format('Y-m-d H:i'),
            'address' => (int) $_SESSION['address'],
            'company'=> (int) $_SESSION['company'],
            'active' => 1,
            'manager' => 0,
            'password'=> $hash,
            'hash'=> md5($_POST['password']),
            'uuid'=>$_POST['uuid'],
            'version'=> 1
        ];
        $db = new Database();
        $query = $db->InsertDb($sql,$data);
        if (is_array($query)) {
            var_dump($query);
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


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
        if (!empty($_POST['activer'])) {
            $activ = 1;
            $dates = $date->format('y-m-d H:i');
        }else {
            $activ = 0;
            $dates = 0;
        }
        if ($_POST['password'] == $_POST['confirmer']) {
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT); 
        }else{
            $messagepassword = "Veuillez bien réécrire le mot de passe.";
        }
        
        $sql = 'INSERT INTO users (firstname,lastname,email,phone_number,creation_date,address_id,company_id,is_active_flag,active_date_from,active_date_to,is_manager,encrypted_password,hash,application_uuid,data_version) VALUES (:firstname,:lastname,:email,:phone,:date,:address,:company,:active,:debut,:end,:manager,:password,:hash,:uuid,:version)';
        $data=[
            'firstname'=>strtoupper($_POST['nom']),
            'lastname' =>$_POST['prenom'],
            'email'=> $_POST['email'],
            'phone'=> $_POST['phone'],
            'date' =>$date->format('y-m-d H:i'),
            'address'=> $_POST['addresse'],
            'company'=> $_POST['companie'],
            'active' => $activ,
            'debut'=> $date->format('y-m-d'),
            'end'=>$_POST['expiration'],
            'manager' => $_POST['type'],
            'password'=> $hash,
            'hash'=> md5($_POST['password']),
            'uuid'=>$_POST['uuid'],
            'version'=> 1
        ];
        $db = new Database();
        
            $query = $db->InsertDb($sql,$data);
            if ($query == true) {
                header('Location:/super/manager');
                //var_dump($query);
                exit;  
            }else {
                var_dump($query);
                exit;
            }
        
    }
}
$db = new Database();
$data['addresses'] = $db->DisplayDataDb('SELECT address_id,postal_address FROM addresses');
$data['company'] = $db->DisplayDataDb('SELECT company_id,company_name FROM companies');

if(file_exists(_VIEW_PATH.$lib->lang."/children/addManager.phtml"))  $view=$lib->lang."/children/addManager.phtml";
else  $view=$lib->lang."/children/addManager.phtml";


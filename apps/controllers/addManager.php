<?php
require_once(_APPS_PATH.'/classes/Database.php');

if (isset($_POST['soumettre'])) {
    
    if (empty($_POST['phone'])) {
        $message="Veuillez remplir le champs";
    }
    
    else {
        $date = new DateTime();
        $activ = 1;
        $dates = $date->format('y-m-d H:i');
        if ($_POST['password'] == $_POST['confirmer']) {
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT); 
        }else{
            $messagepassword = "Veuillez bien réécrire le mot de passe.";
        }
        $sql = 'INSERT INTO users (firstname,lastname,email,phone_number,creation_date,address_id,company_id,is_active_flag,active_date_from,active_date_to,is_manager,encrypted_password,hash,data_version) VALUES (:firstname,:lastname,:email,:phone,:date,:address,:company,:active,:debut,:end,:manager,:password,:hash,:version)';
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
            'end'=>$date->add(new DateInterval('P30D'))->format('y-m-d'),
            'manager' => $_POST['type'],
            'password'=> $hash,
            'hash'=> md5($_POST['password']),
            
            'version'=> 1
        ];
        $db = new Database();
        
            $query = $db->InsertDb($sql,$data);
            if (!is_array($query)) {
                header('Location:/managers');
                //var_dump($query);
                exit;  
            }else {
                var_dump($query);
                exit;
            }
    }
}

if (isset($_POST['agent'])) {
    
    if (empty($_POST['phone'])) {
        $message="Veuillez remplir le champs";
    }
    
    else {
        $db = new Database();
        $adress = $db->DisplaysDataDb('SELECT address_id FROM addresses WHERE company_id ='.$_POST['company']);
        $date = new DateTime();
        $activ = 1;
        $dates = $date->format('y-m-d H:i');
        if ($_POST['password'] == $_POST['confirmer']) {
            $hash = password_hash($_POST['password'], PASSWORD_DEFAULT); 
        }else{
            $messagepassword = "Veuillez bien réécrire le mot de passe.";
        }
        $sql = 'INSERT INTO users (firstname,lastname,email,phone_number,creation_date,company_id,address_id,is_active_flag,active_date_from,active_date_to,is_manager,encrypted_password,hash,data_version) VALUES (:firstname,:lastname,:email,:phone,:date,:company,:addresse,:active,:debut,:end,:manager,:password,:hash,:version)';
        $data=[
            'firstname'=>strtoupper($_POST['nom']),
            'lastname' =>$_POST['prenom'],
            'email'=> $_POST['email'],
            'phone'=> $_POST['phone'],
            'date' =>$date->format('y-m-d H:i'),
            'addresse' => $adress['address_id'],
            'company'=> $_POST['company'],
            'active' => $activ,
            'debut'=> $date->format('y-m-d'),
            'end'=>$date->add(new DateInterval('P30D'))->format('y-m-d'),
            'manager' => 0,
            'password'=> $hash,
            'hash'=> md5($_POST['password']),
            
            'version'=> 1
        ];
        
        
            $query = $db->InsertDb($sql,$data);
            if (!is_array($query)) {
                header('Location:/managers/agents/'.$_POST['company']);
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


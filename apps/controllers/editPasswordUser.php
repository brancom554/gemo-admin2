<?php
require_once(_APPS_PATH.'/classes/Database.php');

if (isset($_POST['update'])) {
    if (empty($_POST['password'])) {
        $message="Veuillez sélectionner le mot de passe";
    }
    else {
        if ($_POST['password'] == $_POST['confirmer']) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
        }else{
            $messagepassword = "Veuillez bien réécrire le mot de passe.";
        }
        $sql = 'UPDATE users SET encrypted_password=:password,last_upadte_date =:date WHERE user_id=:id';
        $data=[
            'id' => $url_array[4],
            'password'=> $password,
            'date' => ''
        ];
        $db = new Database();
            $query = $db->InsertDb($sql,$data);
            if ($query == true) {
                $_SESSION['notification'] = "Mot de passe changé avec succès.";
                header('Location:/administration/utilisateurs');
                exit;
                
            }else {
                $_SESSION['notification'] = "Opération echouée.";
                header('Location:/administration/utilisateurs');
                exit;
            }
        
    }
}

/*$db = new Database();
$data['manager'] = $db->DisplaysDataDb('SELECT * FROM users WHERE user_id='.$url_array[4]);*/

if(file_exists(_VIEW_PATH.$lib->lang."/children/editPasswordUser.phtml"))  $view=$lib->lang."/children/editPasswordUser.phtml";
else  $view=$lib->lang."/children/editPasswordUser.phtml";
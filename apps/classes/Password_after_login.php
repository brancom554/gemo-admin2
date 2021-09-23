<?php
require_once(_APPS_PATH.'/classes/Database.php');
//require_once(_APPS_PATH.'/classes/Password_after_login.php');


class Password_after_login{
    function changePassword($password2){
        $dt = new Datetime();
        $formatt = $dt->format("Y-m-d H:s");
        $sql = 'UPDATE users SET last_update_date=:formatt, encrypted_password=:password2 WHERE user_id=:id';
        $data = array("id" => $_SESSION['user_id'], "password2" => password_hash($password2, PASSWORD_DEFAULT), "formatt" => $formatt);
        $db = new Database();
        $response = $db->InsertDb($sql,$data);

        if(!is_array($response)){
            header('Location:/dashboard');
        }else{
            var_dump($response);
        }
    }

}
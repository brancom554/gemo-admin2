<?php
require_once(_APPS_PATH.'/classes/Database.php');

class User{
    function seConnecter(string $telephone, $passwords){    
        $sql = "SELECT user_id,last_update_date,firstname,lastname,encrypted_password,is_manager,address_id,company_id,company_token FROM users WHERE phone_number= $telephone";
        $db = new Database();
        try {
            $data['user'] = $db->DisplaysDataDb($sql);
            if (is_array($data['user'])) {
                if (password_verify($passwords,$data['user']['encrypted_password'])) {
                    $_SESSION['username'] = $data['user']['firstname'].' '.$data['user']['lastname'];
                    $_SESSION['access'] = $data['user']['is_manager'];
                    $_SESSION['company'] = $data['user']['company_id'];
                    $_SESSION['address'] = $data['user']['address_id'];
                    $_SESSION['companie_token'] = $data['user']['company_token'];
                    $_SESSION['user_id'] = $data['user']['user_id'];
                    if ($_SESSION['access'] == '1') {
                        //$sql = "SELECT licence_id,licence_nb_equipement FROM users,licences,licence_types WHERE users.company_id = licences.created_for_company_id AND licences.licence_type_id = licence_types.licence_type_id AND phone_number= $telephone";
                        $sql = "SELECT licence_id,licence_nb_equipment FROM licences lic INNER JOIN users u ON lic.created_for_company_id = u.company_id JOIN licence_types types ON lic.licence_type_id = types.licence_type_id WHERE u.phone_number= $telephone";
                        $data['licence'] = $db->DisplaysDataDb($sql);
                        if (!empty($data['licence']['licence_nb_equipement'])) {
                            $_SESSION['equipement'] = $data['licence']['licence_nb_equipement'];
                        }
                        if (!empty($data['licence']['licence_id'])) {
                            $_SESSION['licence'] = $data['licence']['licence_id'];
                        }

                        if(isset($data['user']['last_update_date']) && $data['user']['last_update_date'] !== null){
                            header('Location:/dashboard');
                        }else{
                            header('Location:/change/reset');
                        }
                        //header('Location:/change/reset');
                        //header('Location:/dashboard');
                        exit;
                    }elseif ($_SESSION['access'] == '2') {
                        header('Location:/dashboard');
                        exit;
                    }else {
                        header('Location:/connecter');
                        exit;
                    }
                }
                else {
                    return $error = "Le numéro ou le mots de passe est incorrect.";
                }
            }
        } catch (\Throwable $th) {
            return $error = "Veuillez contacter le support de GEMO.<br>Le numéro n'existe pas.";
        }
    }

    public function SessionExiste():boolean
    {
        # code...
    }

    public function AddManager(array $manager)
    {
        $sql = 'INSERT INTO users (firstname,lastname,email,phone_number,creation_date,address_id,company_id,is_active_flag,active_date_from,active_date_to,is_manager,encrypted_password,hash,application_uuid,data_version) VALUES (:firstname,:lastname,:email,:phone,:date,:address,:company,:active,:debut,:end,:manager,:password,:hash,:uuid,:version)';
        $data=$manager;
        $db = new Database();
        $query = $db->InsertDb_Id($sql,$data);
        if (!is_array($query)) {
            $id = $query;
            return $id;
        } else {
            return $query;
        } 
    }

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
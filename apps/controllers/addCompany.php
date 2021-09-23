<?php
require_once(_APPS_PATH.'/classes/Database.php');

if (isset($_POST['soumettre'])) {
    
    if (empty($_POST['libelle'])) {
        $message="Veuillez sélectionner le libellé";
    }
    else {
        $date = new DateTime();
        $sql = 'INSERT INTO companies (company_number,company_name,creation_date,company_token,registration_number) VALUES (:rccm,:nom,:date,:token,:ifu)';
        $data=[
            'rccm'=> $_POST['rccm'],
            'nom' => strtoupper($_POST['libelle']) ,
            'date'=> $date->format('y-m-d H:i'),
            'token' => $_POST['token'],
            'ifu' => $_POST['ifu']
        ];
        $db = new Database();
        
            $query = $db->InsertDb($sql,$data);
            if ($query) {
                
                    header('Location:/super/compagnies');
                    exit;
                
            }else {
                var_dump($query);
                return;
            }
        
    }
}

if(file_exists(_VIEW_PATH.$lib->lang."/children/addCompany.phtml"))  $view=$lib->lang."/children/addCompany.phtml";
else  $view=$lib->lang."/children/addCompany.phtml";
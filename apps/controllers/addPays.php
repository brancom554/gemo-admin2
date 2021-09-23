<?php
require_once(_APPS_PATH.'/classes/Database.php');

if (isset($_POST['soumettre'])) {
    
    if (empty($_POST['libelle'])) {
        $message="Veuillez remplir le champs";
    }
    else {
        $date = new DateTime();
        $sql = 'INSERT INTO countries (description,country_short_name,creation_date,data_version) VALUES (:libelle,:description,:date,:version)';
        $data=[
            'libelle'=>strtoupper($_POST['libelle']),
            'description' =>strtoupper($_POST['code']),
            'date'=> $date->format('y-m-d H:i'),
            'version'=> 1
        ];
        $db = new Database();
        
            $query = $db->InsertDb($sql,$data);
            if ($query == true) {
                header('Location:/configurations/pays');
                exit;  
            }else {
                var_dump($query);
                exit;
            }
        
    }
}

if(file_exists(_VIEW_PATH.$lib->lang."/children/addPays.phtml"))  $view=$lib->lang."/children/addPays.phtml";
else  $view=$lib->lang."/children/addPays.phtml";
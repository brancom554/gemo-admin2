<?php
require_once(_APPS_PATH.'/classes/Database.php');

if (isset($_POST['soumettre'])) {
    if (empty($_POST['country'])) {
        $messageCountry="Veuillez sélectionner un pays";
    }
    if (empty($_POST['societe'])) {
        $messageCompany="Veuillez sélectionner une compagnie";
    }
    
    if (empty($_POST['libelle'])) {
        $message="Veuillez sélectionner le libellé";
    }
    else {
        $date = new DateTime();
        $sql = 'INSERT INTO addresses (postal_address,postal_code,creation_date,city,company_id,country_id,data_version) VALUES (:postal_address,:postal_code,:creation_date,:city,:company_id,:country_id,:data_version)';
        $data=[
            'postal_address'=> $_POST['libelle'],
            'postal_code' => $_POST['postal'],
            'city' => $_POST['ville'],
            'creation_date'=> $date->format('y-m-d H:i'),
            'company_id'=> $_POST['societe'],
            'country_id' => $_POST['country'],
            'data_version'=> 1
        ];
        $db = new Database();
            $query = $db->InsertDb($sql,$data);
            if (is_array($query)) {
                var_dump($query);
                exit;
                
            }else {
                header('Location:/configurations/addresses');
                exit;
            }
        
    }
}

$db = new Database();
$data['company'] = $db->DisplayDataDb('SELECT company_id,company_name FROM companies');
$data['country'] = $db->DisplayDataDb('SELECT country_id,description FROM countries');

if(file_exists(_VIEW_PATH.$lib->lang."/children/addAddress.phtml"))  $view=$lib->lang."/children/addAddress.phtml";
else  $view=$lib->lang."/children/addAddress.phtml";
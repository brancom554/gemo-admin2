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
        $sql = 'UPDATE addresses SET postal_address=:postal_address,postal_code=:postal_code,updated_date=:creation_date,city=:city,company_id=:company_id,country_id=:country_id WHERE address_id=:id';
        $data=[
            'id' => $url_array[4],
            'postal_address'=> $_POST['libelle'],
            'postal_code' => $_POST['postal'],
            'city' => $_POST['ville'],
            'creation_date'=> $date->format('y-m-d H:i'),
            'company_id'=> $_POST['societe'],
            'country_id' => $_POST['country'],
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

$data=[
    'id' => $url_array[4]
];
$db = new Database();
$data['company'] = $db->DisplayDataDb('SELECT company_id,company_name FROM companies');
$data['country'] = $db->DisplayDataDb('SELECT country_id,description FROM countries');

$data['addresse'] = $db->DisplaysDataDb('SELECT * FROM addresses WHERE address_id='.$url_array[4]);
$pays = array($data['addresse']['country_id']);
$compagnie = array($data['addresse']['company_id']);
if(file_exists(_VIEW_PATH.$lib->lang."/children/editAddress.phtml"))  $view=$lib->lang."/children/editAddress.phtml";
else  $view=$lib->lang."/children/editAddress.phtml";
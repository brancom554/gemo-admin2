<?php

require_once(_APPS_PATH.'/classes/Database.php');

$db = new Database();
$q = $db->connectDb();
$licence = $q->query('SELECT company_name,licence_key,is_for_equipement_flag,expiration_date,is_active,c.application_uuid FROM companies c,licences L WHERE c.company_id=L.created_for_company_id AND licence_id='.$url_array[4].'')->fetch();

$data['service'] = $q->query('SELECT service_id FROM licence_features c,services S WHERE c.licence_feature_id=S.service_id AND licence_id='.$url_array[4].'')->fetchAll();
$data['services'] = $q->query('SELECT service_id,libelle FROM services ')->fetchAll();
$db = null; 
$service_array = [];
foreach ($data['service'] as $service) {
    $service_array[]= $service['service_id'];
}
$expiration = new DateTime($licence['expiration_date']);

if(file_exists(_VIEW_PATH."/children/editLicence.phtml"))  $view=$lib->lang."/children/editLicence.phtml";
else  $view=$lib->lang."/children/editLicence.phtml";
<?php
require_once(_APPS_PATH.'/classes/Database.php');

$db = new Database();
$q = $db->connectDb();
$data['addresses'] = $q->query('SELECT address_id, company_name,city,description,postal_address,postal_code FROM addresses A,companies C,countries P WHERE A.company_id=C.company_id AND A.country_id=P.country_id')->fetchAll();

if(file_exists(_VIEW_PATH.$lib->lang."/address.phtml"))  $view=$lib->lang."/address.phtml";
else  $view=$iniObj->defaultLang."/address.phtml";
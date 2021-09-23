<?php
require_once(_APPS_PATH.'/classes/Database.php');

$db = new Database();
$q = $db->connectDb();
$data['services'] = $q->query('SELECT * FROM services')->fetchAll();

if(file_exists(_VIEW_PATH.$lib->lang."/service.phtml"))  $view=$lib->lang."/service.phtml";
else  $view=$iniObj->defaultLang."/service.phtml";
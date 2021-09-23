<?php
require_once(_APPS_PATH.'/classes/Database.php');

$db = new Database();
$q = $db->connectDb();
$data['pays'] = $q->query('SELECT * FROM countries')->fetchAll();

if(file_exists(_VIEW_PATH.$lib->lang."/pays.phtml"))  $view=$lib->lang."/pays.phtml";
else  $view=$iniObj->defaultLang."/pays.phtml";
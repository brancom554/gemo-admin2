<?php
require_once(_APPS_PATH.'/classes/Database.php');

$db = new Database();
$q = $db->connectDb();
$data['licence_types'] = $q->query('SELECT * FROM licence_types')->fetchAll();
$db = null;

if(file_exists(_VIEW_PATH."/type.phtml")) $view=$lib->lang."/type.phtml";
else  $view=$lib->lang."/type.phtml";
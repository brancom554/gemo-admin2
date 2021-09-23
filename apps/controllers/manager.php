<?php
require_once(_APPS_PATH.'/classes/Database.php');

$db = new Database();
$q = $db->connectDb();
$data['manager'] = $q->query('SELECT * FROM users')->fetchAll();

if(file_exists(_VIEW_PATH.$lib->lang."/manager.phtml"))  $view=$lib->lang."/manager.phtml";
else  $view=$iniObj->defaultLang."/manager.phtml";
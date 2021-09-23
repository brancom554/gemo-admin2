<?php
require _APPS_PATH.'/classes/Database.php';

$db = new Database();
$data['compagnies'] = $db->DisplayDataDb('SELECT * FROM companies');


if(file_exists(_VIEW_PATH.$lib->lang."/compagny.phtml"))  $view=$lib->lang."/compagny.phtml";
else  $view=$iniObj->defaultLang."/compagny.phtml";
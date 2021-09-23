<?php
require _APPS_PATH.'/classes/Database.php';

$db = new Database();
$q = $db->connectDb();
$data['categories'] = $q->query('SELECT * FROM categories')->fetchAll();

if(file_exists(_VIEW_PATH.$lib->lang."/category.phtml"))  $view=$lib->lang."/category.phtml";
else  $view=$iniObj->defaultLang."/category.phtml";
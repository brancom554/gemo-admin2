<?php
require_once(_APPS_PATH.'/classes/Database.php');

 header("cache-Control: no-cache, must-revalidate"); 

$sql = 'SELECT * FROM inventories WHERE company_token = "'.$_SESSION['companie_token'].'"';
        $db = new Database();
        $response = $db->DisplayDataDb($sql);


if(file_exists(_VIEW_PATH.$lib->lang."/inventaire.phtml"))  $view=$lib->lang."/inventaire.phtml";
else  $view=$lib->lang."/inventaire.phtml";


<?php
require_once(_APPS_PATH.'/classes/Database.php');


$sql = 'SELECT * FROM inventory_detail WHERE inventory_id ='.$url_array[4];
        $db = new Database();
        $response = $db->DisplayDataDb($sql);


if(file_exists(_VIEW_PATH.$lib->lang."/inventaire_detail.phtml"))  $view=$lib->lang."/inventaire_detail.phtml";
else  $view=$lib->lang."/inventaire_detail.phtml";


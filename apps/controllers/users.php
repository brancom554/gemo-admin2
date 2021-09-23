<?php
require_once(_APPS_PATH.'/classes/Database.php');

$db = new Database();
$data['users'] = $db->DisplayDataDb('SELECT * FROM users WHERE company_id ='.$_SESSION['company'].' AND is_manager=0');

if(file_exists(_VIEW_PATH.$lib->lang."/users.phtml"))  $view=$lib->lang."/users.phtml";
else  $view=$lib->lang."/users.phtml";
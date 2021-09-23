<?php
require_once(_APPS_PATH.'/classes/Database.php');

$db = new Database();
$sql = "SELECT l.licence_id,l.licence_key,l.expiration_date,l.is_active,t.licence_type_name,c.company_name FROM licences l INNER JOIN companies c ON l.created_for_company_id = c.company_id INNER JOIN licence_types t ON l.licence_type_id = t.licence_type_id WHERE l.licence_parent_id IS NULL";
$data['licences'] = $db->DisplayDataDb($sql);


if(file_exists(_VIEW_PATH."/licence.phtml")) $view=$lib->lang."/licence.phtml";
else  $view=$lib->lang."/licence.phtml";
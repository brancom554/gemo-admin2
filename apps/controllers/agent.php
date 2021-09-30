<?php
require_once(_APPS_PATH.'/classes/Database.php');

if ($_SESSION['access'] == '1') {

$sql = 'SELECT * FROM licences LEFT JOIN users ON licences.licence_id = users.licence_id WHERE licences.created_for_company_id='.$_SESSION['company'];
// $sql = 'SELECT * FROM licences LEFT JOIN users ON licences.licence_id = users.licence_id WHERE licences.licence_parent_id= 6';

$db = new Database();
$data['licences']= $db->DisplayDataDb($sql);

if (!empty($data['licences'])) {
    $count = count($data['licences']);
    if ($count <= (int) $_SESSION['equipement']) {
        $show=true;
    }else{
        if ($count >(int) $_SESSION['equipement']) {
            $show=false;
        }
    }
}else {
    $show=true;
}
}


if(file_exists(_VIEW_PATH.$lib->lang."/agent.phtml"))  $view=$lib->lang."/agent.phtml";
else  $view=$iniObj->defaultLang."/agent.phtml";
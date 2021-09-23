<?php
require_once(_APPS_PATH.'/classes/Database.php');

// $sql = 'SELECT * FROM licences RIGHT JOIN users ON users.licence_id = licences.licence_id WHERE licences.licence_parent_id='.$_SESSION['licence'];
$sql = 'SELECT * FROM licences LEFT JOIN users ON licences.licence_id = users.licence_id WHERE licences.licence_parent_id= '.$_SESSION['licence'];

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


if(file_exists(_VIEW_PATH.$lib->lang."/agent.phtml"))  $view=$lib->lang."/agent.phtml";
else  $view=$iniObj->defaultLang."/agent.phtml";
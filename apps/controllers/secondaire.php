<?php
require_once(_APPS_PATH.'/classes/Database.php');

try {
    $data=[
        'id' => $url_array[3]
    ];
    $db = new Database();
    $q = $db->connectDb();
    $sql = ('SELECT * FROM licences WHERE licence_parent_id=:id');
    $query = $q->prepare($sql);
    $query->execute($data);
    $data['licences'] = $query->fetchAll();
    $types = null;
    $sql2 = 'SELECT licence_nb_equipment FROM licence_types AS LT INNER JOIN licences AS LIC ON LT.licence_type_id = LIC.licence_type_id WHERE licence_id='.$url_array[4];
    $query = $db->DisplaysDataDb($sql2);
    $d = $query;
    $nb = (int) $d['licence_nb_equipment'];
    $show = false;
    if (count($data['licences']) >= $nb ) {
        $show = true;
    }else {
        $show = false;
    }
} catch (PDOException $e) {
    echo $e->errorInfo();
}


if(file_exists(_VIEW_PATH.$lib->lang."/secondaire.phtml"))  $view=$lib->lang."/secondaire.phtml";
else  $view=$iniObj->defaultLang."/secondaire.phtml";
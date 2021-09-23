<?php
require _APPS_PATH.'/classes/Database.php';

$db = new Database();
$q = $db->connectDb();
try {
    $data['ussd'] = $q->query('SELECT U.category_ussd_id,U.ussd_code,C.libelle,O.libelle as type,U.network_operator_name FROM category_ussd U,categories C,operation_types O WHERE U.category_id = C.category_id AND U.operation_type_id = O.operation_type_id')->fetchAll();
    //var_dump($q->errorInfo());
} catch (PDOExeception $e) {
    var_dump($e->errorInfo());
}

if(file_exists(_VIEW_PATH.$lib->lang."/ussd.phtml"))  $view=$lib->lang."/ussd.phtml";
else  $view=$iniObj->defaultLang."/ussd.phtml";
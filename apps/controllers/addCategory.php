<?php
require_once(_APPS_PATH.'/classes/Database.php');

if (isset($_POST['soumettre'])) {
    $date = new DateTime();

    if (!empty($_POST['type'])) {
        if ($_POST['type'] == 1) {
            $type = "SERVICES TELEPHONIQUES";
        } else {
            if ($_POST['type'] == 2) {
                $type = "SERVICES FINANCIERS";
            }
        }
    }
    
    if (empty($_POST['libelle'])) {
       $message="Veuillez remplir le libellé";
    }
    else {
        $sql = 'INSERT INTO categories (libelle,creation_date,type_category,type_category_libelle) VALUES (:libelle,:date,:type_id,:type_name)';
        $data=[
            'libelle'=>strtoupper($_POST['libelle']),
            'date' => $date->format('y-m-d H:i'),
            'type_id' => $_POST['type'],
            'type_name' => $type
        ];
        $db = new Database();
        //var_dump($data);
        //$conn = $db->connectDb();
        //if ($db->beginTransaction()) {
            $query = $db->InsertDb($sql,$data);
            if ($query) {
                //if ($db->commit()) {
                    header('Location:/configurations/categries');
                    exit;
                //}
            }
        //}
    }
}

if(file_exists(_VIEW_PATH.$lib->lang."/children/addCategory.phtml"))  $view=$lib->lang."/children/addCategory.phtml";
else  $view=$lib->lang."/children/addCategory.phtml";
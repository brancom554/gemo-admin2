<?php
require_once(_APPS_PATH.'/classes/Database.php');

if (isset($_POST['soumettre'])) {
    
    if (empty($_POST['libelle'])) {
        $message="Veuillez sélectionner le libellé";
    }
    else {
        $sql = 'INSERT INTO services (libelle,descriptions) VALUES (:libelle,:descriptions)';
        $data=[
            'libelle'=> $_POST['libelle'],
            'descriptions' => $_POST['description']
        ];
        $db = new Database();
        $conn = $db->connectDb();
        if ($conn->beginTransaction()) {
            $query = $conn->prepare($sql);
            if ($query->execute($data)) {
                if ($conn->commit()) {
                    header('Location:/configurations/service');
                    exit;
                }
            }
        }
    }
}

if(file_exists(_VIEW_PATH.$lib->lang."/children/addServices.phtml"))  $view=$lib->lang."/children/addServices.phtml";
else  $view=$lib->lang."/children/addServices.phtml";
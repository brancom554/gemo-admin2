<?php
require_once(_APPS_PATH.'/classes/Database.php');

if (isset($_POST['soumettre'])) {
    
    if (empty($_POST['libelle'])) {
        $message="Veuillez sélectionner le libellé";
    }
    else {
        $sql = 'UPDATE services SET libelle=:libelle,descriptions=:descriptions WHERE service_id=:id';
        $data=[
            'id' => $url_array[4],
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

$data=[
    'id' => $url_array[4]
];
$db = new Database();
$q = $db->connectDb();
$sql = ('SELECT * FROM services WHERE service_id=:id');
$query = $q->prepare($sql);
$query->execute($data);
$data['services'] = $query->fetch();

if(file_exists(_VIEW_PATH.$lib->lang."/children/editServices.phtml"))  $view=$lib->lang."/children/editServices.phtml";
else  $view=$lib->lang."/children/editServices.phtml";
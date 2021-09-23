<?php
require_once(_APPS_PATH.'/classes/Database.php');

if (isset($_POST['soumettre'])) {
    
    if (empty($_POST['libelle'])) {
        $message="Veuillez sélectionner le libellé";
    }
        $sql = 'UPDATE licence_types SET licence_type_name=:licence_type_name,licence_nb_equipment=:licence_nb_equipment,licence_nb_transactions_day=:licence_nb_transactions_day WHERE licence_type_id=:id';
        $data=[
            'id' => $url_array[4],
            'licence_type_name'=> strtoupper($_POST['libelle']),
            'licence_nb_equipment' => $_POST['equipement'],
            'licence_nb_transactions_day' => $_POST['transaction']
        ];
        $db = new Database();
        $conn = $db->InsertDb($sql,$data);
        if (!is_array($conn)) {             
            header('Location:/configurations/types_licences');
            exit;
        }else {
            var_dump($conn);
        }
    
}

$data=[
    'id' => $url_array[4]
];
$db = new Database();
$q = $db->connectDb();
$sql = ('SELECT * FROM licence_types WHERE licence_type_id=:id');
$query = $q->prepare($sql);
$query->execute($data);
$data['type'] = $query->fetch();

if(file_exists(_VIEW_PATH.$lib->lang."/children/editType.phtml"))  $view=$lib->lang."/children/editType.phtml";
else  $view=$lib->lang."/children/editType.phtml";
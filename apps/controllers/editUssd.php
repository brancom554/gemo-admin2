<?php
require_once(_APPS_PATH.'/classes/Database.php');

if (isset($_POST['soumettre'])) {
    
    if (empty($_POST['ussd'])) {
        $message="Veuillez entrer le code Ussd";
    }
    if (empty($_POST['network'])) {
        $messages="Veuillez entrer l'opérateur Télécom";
    }
    else {
        if (!empty($_POST['network'])) {
            if ($_POST['network'] == 1) {
                $name = "MTN";
            } else {
                if ($_POST['network'] == 2) {
                    $name = "MOOV";
                }
            }
        }
        $sql = 'UPDATE category_ussd SET ussd_code=:ussd,category_id=:category,operation_type_id=:operation,network_operator_number=:network_id,network_operator_name=:network_name WHERE category_ussd_id=:id';
        $data=[
            'id' => $url_array[4],
            'ussd'=> $_POST['ussd'],
            'category' => $_POST['categorie'],
            'operation' => $_POST['operation'],
            'network_id' => $_POST['network'],
            'network_name' => $name
        ];
        $db = new Database();        
            $query = $db->InsertDb($sql,$data);
            if ($query) {
                header('Location:/configurations/ussd');
                var_dump($query);
                exit;  
            }
    }
}

$data=[
    'id' => $url_array[4]
];
$types = $operator = $categorie = [];
$db = new Database();
$sql = ('SELECT U.ussd_code,U.category_id,U.operation_type_id,U.network_operator_number FROM categories C,category_ussd U,operation_types O WHERE C.category_id = U.category_id AND U.operation_type_id = O.operation_type_id AND category_ussd_id='.$url_array[4]);
$data['ussd'] = $db->DisplaysDataDb($sql);
$types = [$data['ussd']['operation_type_id']];
$operator = [$data['ussd']['network_operator_number']];
$categories = [$data['ussd']['category_id']];
$sql= 'SELECT category_id,libelle FROM categories';
$data['categories'] = $db->DisplayDataDb($sql);
$sql= 'SELECT operation_type_id,libelle FROM operation_types';
$data['operation_types'] = $db->DisplayDataDb($sql);

if(file_exists(_VIEW_PATH.$lib->lang."/children/editUssd.phtml"))  $view=$lib->lang."/children/editUssd.phtml";
else  $view=$lib->lang."/children/editUssd.phtml";
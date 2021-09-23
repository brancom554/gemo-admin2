<?php
require_once(_APPS_PATH.'/classes/Database.php');

if (isset($_POST['soumettre'])) {
    
    if (empty($_POST['ussd'])) {
        $message="Veuillez sélectionner le libellé";
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
        $sql = 'INSERT INTO category_ussd (ussd_code,category_id,operation_type_id,network_operator_number,network_operator_name) VALUES (:ussd,:category,:operation,:network_id,:network_name)';
        $data=[
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
                    exit;
                
            }
        
    }
}
$db = new Database(); 
$sql= 'SELECT category_id,libelle FROM categories';
$data['categories'] = $db->DisplayDataDb($sql);
$sql= 'SELECT operation_type_id,libelle FROM operation_types';
$data['operation_types'] = $db->DisplayDataDb($sql);

if(file_exists(_VIEW_PATH.$lib->lang."/children/addUssd.phtml"))  $view=$lib->lang."/children/addUssd.phtml";
else  $view=$lib->lang."/children/addUssd.phtml";
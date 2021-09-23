<?php
require_once(_APPS_PATH.'/classes/Database.php');

if (isset($_POST['soumettre'])) {
    
    if (empty($_POST['libelle'])) {
        $message="Veuillez remplir le champs";
    }
    else {
        $sql = 'UPDATE companies SET company_number=:rccm,company_name=:nom,company_token=:token,registration_number=:ifu WHERE company_id=:id';
        $data=[
            'id' => $url_array[4],
            'rccm'=> $_POST['rccm'],
            'nom' => strtoupper($_POST['libelle']) ,
            'token' => $_POST['token'],
            'ifu' => $_POST['ifu']
        ];
        $db = new Database();
        
            $query = $db->InsertDb($sql,$data);
            if ($query == true) {
                header('Location:/super/compagnies');
                var_dump($query);
                exit;
                
            }else {
                var_dump($query);
                exit;
            }
        
    }
}

$data=[
    'id' => $url_array[4]
];
$db = new Database();
$q = $db->connectDb();
$sql = ('SELECT * FROM companies WHERE company_id=:id');
$query = $q->prepare($sql);
$query->execute($data);
$data['services'] = $query->fetch();

if(file_exists(_VIEW_PATH.$lib->lang."/children/editCompany.phtml"))  $view=$lib->lang."/children/editCompany.phtml";
else  $view=$lib->lang."/children/editCompany.phtml";
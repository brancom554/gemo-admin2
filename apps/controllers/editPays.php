<?php
require_once(_APPS_PATH.'/classes/Database.php');

if (isset($_POST['soumettre'])) {
    
    if (empty($_POST['libelle'])) {
        $message="Veuillez sélectionner le libellé";
    }
    else {
        $sql = 'UPDATE countries SET description=:libelle,country_short_name=:descriptions WHERE country_id=:id';
        $data=[
            'id' => $url_array[4],
            'libelle'=> $_POST['libelle'],
            'descriptions' => $_POST['code']
        ];
        $db = new Database();
            $query = $db->InsertDb($sql,$data);
            if ($query == true) {
                header('Location:/configurations/pays');
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
$sql = ('SELECT * FROM countries WHERE country_id=:id');
$query = $q->prepare($sql);
$query->execute($data);
$data['pays'] = $query->fetch();

if(file_exists(_VIEW_PATH.$lib->lang."/children/editPays.phtml"))  $view=$lib->lang."/children/editPays.phtml";
else  $view=$lib->lang."/children/editPays.phtml";
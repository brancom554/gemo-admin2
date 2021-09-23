<?php
require_once(_APPS_PATH.'/classes/Database.php');

if (isset($_POST['soumettre'])) {

    if (empty($_POST['libelle'])) {
        $messages="Veuillez sélectionner le libellé";
    }

    if (empty($_POST['type'])) {
        $message="Veuillez sélectionner un type";
    }
    else {
        if (!empty($_POST['type'])) {
            if ($_POST['type'] == 1) {
                $type = "SERVICES TELEPHONIQUES";
            } else {
                if ($_POST['type'] == 2) {
                    $type = "SERVICES FINANCIERS";
                }
            }
        }
        $sql = 'UPDATE categories SET libelle=:libelle,type_category=:type_category,type_category_libelle=:type_category_libelle WHERE category_id=:id';
        $data=[
            'id' => $url_array[4],
            'libelle'=> strtoupper($_POST['libelle']),
            'type_category' => $_POST['type'],
            'type_category_libelle' => $type
        ];
        $db = new Database();
        $result =$db->InsertDb($sql,$data);
        if ($result) {
            header('Location:/configurations/categories');
            exit;
        }
        else {
            var_dump($result);
        }
    }
}

$data=[
    'id' => $url_array[4]
];
$db = new Database();
$q = $db->connectDb();
$sql = ('SELECT libelle,type_category,type_category_libelle FROM categories WHERE category_id=:id');
$query = $q->prepare($sql);
$query->execute($data);
$data['categorie'] = $query->fetch();

if(file_exists(_VIEW_PATH.$lib->lang."/children/editCategory.phtml"))  $view=$lib->lang."/children/editCategory.phtml";
else  $view=$lib->lang."/children/editCategory.phtml";
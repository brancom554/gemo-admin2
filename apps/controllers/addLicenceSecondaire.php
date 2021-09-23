<?php
require_once(_APPS_PATH.'/classes/Licence.php');
require_once(_APPS_PATH.'/classes/Database.php');

if (isset($_POST['soumettre'])) {
    
    if (!isset($_POST['licence_fin'])) {
        return $message="Veuillez sélectionner la date d'expiration";
    }
    if (!empty($_POST['licence_fin'])) {
        $date_fin = new DateTime($_POST['licence_fin']);
        $date_expiration = $date_fin->format('y-m-d H:i');
    }
    if (!empty($_POST['actif'])) {
        $actif=1;
        $date_actif = new DateTime();
        $date_activ = $date_actif->format('y-m-d H:i');
    }else{
        $actif=0;
        $date_activ = null;
    }
    $date = new DateTime();
    $db = new Database();
    $conn = $db->connectDb();
    $r = $conn->prepare('INSERT INTO licences (licence_key,creation_date,is_for_equipement_flag,is_active,activation_date,expiration_date,licence_parent_id) VALUES (?,?,?,?,?,?,?)');
    $r->execute([
        $_POST['licence_key'],
        $date->format('y-m-d H:i'),
        1,
        $actif,
        $date_activ,
        $date_expiration,
        $url_array[4]
    ]);
    $licence_id = (int)$conn->lastInsertId();
    $sql = 'INSERT INTO licence_features (licence_id ,service_id) VALUES (:licence,:service)';
    $query = $conn->prepare($sql);
    foreach($_POST['services'] as $service){
        $service_id = (int)$service;
        $data=[
            'licence'=> $licence_id,
            'service' => $service_id
        ];
        $statut = $db->InsertDb($sql,$data);
        if (!$statut) {
            var_dump($statut);
        }
    }
    header('Location:/super/licence/secondaire/'.$url_array[4]);
    exit;
}

$db = new Database();
$q = $db->connectDb();
$data['services'] = $q->query('SELECT service_id,libelle FROM services')->fetchAll();
$db = null;
$key = new Licence();
$licenceKey = $key->generateLicence(25);

if(file_exists(_VIEW_PATH.$lib->lang."/children/addLicenceSecondaire.phtml"))  $view=$lib->lang."/children/addLicenceSecondaire.phtml";
else  $view=$lib->lang."/children/addLicenceSecondaire.phtml";
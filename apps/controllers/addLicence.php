<?php
require_once(_APPS_PATH.'/classes/Licence.php');
require_once(_APPS_PATH.'/classes/User.php');
require_once(_APPS_PATH.'/classes/Addresses.php');
require_once(_APPS_PATH.'/classes/Campany.php');
require_once(_APPS_PATH.'/classes/Database.php');

if (isset($_POST['soumettre'])) {
    $date = new DateTime();
    /*if (!isset($_POST['societe'])) {
        return $message="Veuillez sélectionner la societe";
    }
    if (!isset($_POST['licence_fin'])) {
        return $message="Veuillez sélectionner la date d'expiration";
    }
    if (isset($_POST['equipement'])) {
        $equipement= 1;
    }*/
    if (!empty($_POST['licence_fin'])) {
        $date_fin = new DateTime($_POST['licence_fin']);
        $date_expiration = $date_fin->format('y-m-d H:i');
    }
    /*if (isset($_POST['actif'])) {
        $actif=1;
        $date_actif = new DateTime();
        $date_activ = $date_actif->format('y-m-d H:i');
    }else{
        $actif=0;
        $date_activ = null;
    }*/
    $dataCampanie=[
        'rccm'=> $_POST['rccm'],
        'nom' => strtoupper($_POST['nomCompanie']) ,
        'date'=> $date->format('y-m-d H:i'),
        'token' => $_POST['token'],
        'ifu' => $_POST['ifu']
    ];
    $companie = new Campany();
    $companie_id = $companie->AddCampany($dataCampanie);
    //var_dump($companie_id);
    if (!is_array($companie_id)) {
        $dataAddress=[
            'postal_address'=> $_POST['addresse'],
            'postal_code' => $_POST['postal'],
            'city' => $_POST['ville'],
            'creation_date'=> $date->format('y-m-d H:i'),
            'company_id'=> $companie_id,
            'country_id' => $_POST['country'] ,
            'data_version'=> 1
        ];
        $address = new Addresses();
        $address_id = $address->AddAddresse($dataAddress);
        //var_dump($address_id);
        if (!is_array($address_id)) {
            if ($_POST['password'] == $_POST['confirmer']) {
                $hash = password_hash($_POST['password'], PASSWORD_DEFAULT); 
            }
            $dataManager=[
                'firstname'=>strtoupper($_POST['nom']),
                'lastname' =>$_POST['prenom'],
                'email'=> $_POST['email'],
                'phone'=> $_POST['phone'],
                'date' =>$date->format('y-m-d H:i'),
                'address'=> $address_id,
                'company'=> $companie_id,
                'active' => $activ,
                'debut'=> $date->format('y-m-d'),
                'end'=>$_POST['expiration'],
                'manager' => $_POST['type'],
                'password'=> $hash,
                'hash'=> md5($_POST['password']),
                'uuid'=>$_POST['uuid'],
                'version'=> 1
            ];
            $manager = new User();
            $manager_id = $manager->AddManager($dataManager);
            if (!is_array($manager_id)) {
                $sql3 = "INSERT INTO licences (licence_key,creation_date,is_for_equipement_flag,created_for_company_id,expiration_date,licence_type_id) VALUES (:key,:date,:equipement,:company,:expirate,:type)";
                $data3 = array(
                    'key' =>$_POST['licence_key'],
                    'date' => $date->format('Y-m-d H:i'),
                    'equipement' => 0,
                    'company' => $companie_id,
                    'expirate' => $date_expiration,
                    'type' => $_POST['licence_type']
                );
                $db = new Database();
                $query = $db->InsertDb_Id($sql3,$data3);

                //var_dump($_POST);
                
                $licence_id = $query;

                $data['equipement'] = $db->DisplaysDataDb('SELECT licence_nb_equipment FROM licence_types WHERE licence_type_id ='.$_POST['licence_type']);
                $nb = (int) $data['equipement']['licence_nb_equipment'];

                //var_dump($licence_id);
                //var_dump($nb);
                $secondaire = new Licence();
                $licenceSecondaire = $secondaire->generateLicenceSecondaire($licence_id,$nb,$companie_id,$_POST['services']);
                if ($licenceSecondaire) {
                    header('Location:/super/licence');
                    var_dump($licenceSecondaire);
                    exit;
                }
            } else {
                var_dump($address_id);
            }
            
        } else {
            var_dump($address_id);
        }
        
    } else {
        var_dump($companie_id);
    }
    
    /*$sql ='INSERT INTO licence_features (licence_feature_id,licence_id) VALUES (?,?)';
    $query = $conn->prepare($sql);
    foreach($_POST['services'] as $service){
        $service_id = (int)$service;
        if (!$query->execute([$service_id,$licence_id])) {
            var_dump($query->errorInfo());
        }
    }*/
    //header('Location:/super/licence');
    exit;
}

$db = new Database();
$q = $db->connectDb();
$data['societe'] = $q->query('SELECT company_id,company_name FROM companies')->fetchAll();
$data['services'] = $q->query('SELECT service_id,libelle FROM services')->fetchAll();
$data['type'] = $q->query('SELECT licence_type_id,licence_type_name FROM licence_types')->fetchAll();
$data['country'] = $db->DisplayDataDb('SELECT country_id,description FROM countries');
$db = null;
$key = new Licence();
$licenceKey = $key->generateLicence(25);


if(file_exists(_VIEW_PATH.$lib->lang."/children/addLicence.phtml"))  $view=$lib->lang."/children/addLicence.phtml";
else  $view=$lib->lang."/children/addLicence.phtml";
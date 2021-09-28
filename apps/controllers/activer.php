<?php
require _APPS_PATH.'/classes/Database.php';


if (isset($url_array[3])) {
    $date_actif = new DateTime();
    $date_activ = $date_actif->format('y-m-d H:i');
    $db = new Database();
    $conn = $db->connectDb();
    $data=[
        'is_active'=> 1,
        'id' => $url_array[3],
        'activation' => $date_activ,
        'expiration' => $date_actif->add(new DateInterval('P30D'))->format('y-m-d')
    ];
    
    try {
       
            $sql = 'UPDATE licences SET is_active=:is_active,activation_date=:activation,expiration_date=:expiration WHERE licence_id=:id';
            $query = $db->InsertDb($sql,$data);
            
                if (!is_array($query)) {
                    header('Location:/licences');
                    exit;
                }
            
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    
}

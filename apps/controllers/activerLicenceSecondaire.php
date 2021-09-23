<?php
require _APPS_PATH.'/classes/Database.php';


if (isset($url_array[4])) {
    $date_actif = new DateTime();
    $date_activ = $date_actif->format('y-m-d H:i');
    $db = new Database();
    $conn = $db->connectDb();
    $data=[
        'is_active'=> 1,
        'id' => $url_array[4],
        'activation' => $date_activ
    ];
    
    try {
        if ($conn->beginTransaction()) {
            $sql = 'UPDATE licences SET is_active=:is_active,activation_date=:activation WHERE licence_id=:id';
            $query = $conn->prepare($sql);
            if ($query->execute($data)) {
                if ($conn->commit()) {
                    header('Location:/licences');
                    exit;
                }
            }else {
                echo "pas enrégistrer";
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    
}
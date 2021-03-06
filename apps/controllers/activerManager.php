<?php
require _APPS_PATH.'/classes/Database.php';


if (isset($url_array[3])) {
    $date_actif = new DateTime();
    $date_activ = $date_actif->format('y-m-d H:i');
    $db = new Database();
    $data=[
        'is_active'=> 1,
        'id' => $url_array[3],
        'expire' => $date_actif->add(new DateInterval('P30D'))->format('y-m-d')
    ];
    
    try {
            $sql = 'UPDATE users SET is_active_flag=:is_active,active_date_to=:expire WHERE user_id=:id';
            $query = $db->InsertDb($sql,$data);
            if (!is_array($query)) {
               
                    header('Location:/managers');
                    //var_dump($query);
                    exit;
                
            }else {
                var_dump($query);
                exit;
            }
        
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    
}
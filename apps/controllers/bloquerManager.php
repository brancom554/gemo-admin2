<?php
require _APPS_PATH.'/classes/Database.php';


if (isset($url_array[3])) {
    $db = new Database();
    $data=[
        'is_active'=> 0,
        'id' => $url_array[3],
    ];
    
    try {
        
            $sql = 'UPDATE users SET is_active_flag=:is_active WHERE user_id=:id';
            $query = $db->InsertDb($sql,$data);
            if (!is_array($query)) {
               
                    header('Location:/managers');
                   
                    exit;
                
            }else {
                echo "pas enrégistrer";
            }
        
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    
}
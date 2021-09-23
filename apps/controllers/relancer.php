<?php
require _APPS_PATH.'/classes/Database.php';
require _APPS_PATH.'/classes/Sms.php';

if (isset($url_array[4])) {
    $db = new Database();
    $sms = new Sms();
    $conn = $db->connectDb();
    $data=[
        'is_active'=> 1,
        'id' => $url_array[5],
        'activation' => $date_activ
    ];
    $phone = ''; 
    $message = "";
    
    try {
        $send = $sms->EnvoisSMS($phone, $message);
        
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    
}
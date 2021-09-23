<?php
require_once(_APPS_PATH.'/classes/Database.php');


class Licence_attribution{
    function licenceAttribution($agent, $licence){
        $sql = 'UPDATE users SET licence_id=:licence WHERE user_id=:id';
        $data = array("id" => $agent, "licence" => $licence);
        $db = new Database();
        $response = $db->InsertDb($sql,$data);

        if(!is_array($response)){
            return $response;
        }else{
            var_dump($response);
        }
    }

}
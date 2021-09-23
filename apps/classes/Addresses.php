<?php
require_once(_APPS_PATH.'/classes/Database.php');


class Addresses
{
    
    public function AddAddresse(array $address):int
    {
        $date = new DateTime();
        $sql = 'INSERT INTO addresses (postal_address,postal_code,creation_date,city,company_id,country_id,data_version) VALUES (:postal_address,:postal_code,:creation_date,:city,:company_id,:country_id,:data_version)';
        $data=$address;
        $db = new Database();
        $query = $db->InsertDb_Id($sql,$data);
        if (!is_array($query)) {
            $id = $query;
            return $id;
        } else {
            return $query;
        } 
    }
}

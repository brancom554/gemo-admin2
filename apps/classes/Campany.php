<?php
require_once(_APPS_PATH.'/classes/Database.php');


class Campany
{
    public function AddCampany(array $company):int
    {
        $date = new DateTime();
        $sql = 'INSERT INTO companies (company_number,company_name,creation_date,company_token,registration_number) VALUES (:rccm,:nom,:date,:token,:ifu)';
        $data= $company;
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

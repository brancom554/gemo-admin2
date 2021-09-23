<?php
require_once(_APPS_PATH.'/classes/Database.php');


class Licence
{
    function generateLicence(int $var):string
    {
        $key = implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 25), 5));
        return $key;
    }

    public function generateLicenceSecondaire(int $parent,$nbEquipement,$company,$services):bool
    {
        $date = new DateTime();
        $sql1 ='INSERT INTO licences (licence_key,creation_date,is_for_equipement_flag,created_for_company_id,is_active,licence_parent_id) VALUES (:key,:date,:equipement,:company,:active,:parent)';
        $sq2 ='INSERT INTO licence_features (licence_id,service_id) VALUES (:id,:services)';
        $db = new Database();
        $counter = 0;
        do {
            $licence = $this->generateLicence(25);
            $data2 = array(
                'key' => $licence,
                'date' => $date->format('Y-m-d H:i'),
                'equipement' => 1,
                'company' => $company,
                'active' => 0,
                'parent' => $parent
            );
            $licence_id = $db->InsertDb_Id($sql1,$data2);
            if (!is_array($licence_id)) {
                foreach ($services as $service) {
                    $service_id = (int)$service;
                    $data = [
                        'id' => $licence_id,
                        ':services' => $service_id
                    ];
                    $db->InsertDb($sq2,$data);
                }
            }
            $counter++;
        } while ($counter <= $nbEquipement - 1);
        $db = "";
        return true;
    }

    function licenceAttribution($agent, $licence){
        $sql = 'UPDATE users SET licence_id=0 WHERE licence_id=:licence';

        $data = array("licence" => $licence);
        $db = new Database();
        $response = $db->InsertDb($sql,$data);


        if($response === true) {
            $sql1 = 'UPDATE users SET licence_id=:licence WHERE user_id=:id';
            $data = array("id" => $agent,"licence" => $licence);
            $db = new Database();
            $response = $db->InsertDb($sql1,$data);

            if(!is_array($response)){
                header('Location:/administration/agent');
                exit;
            }else{
                var_dump($response);
            }
            exit;
        }

       
    }
}

<?php

class Sms
{
    public function EnvoisSMS (string $phone, $sender, $message)
    {
        $ch = curl_init('https://textbelt.com/text');
        $data =array(
            'phone' => $phone.'',
            'senderId' => $sender.'',
            'message' => $message.'',
            'key' => 'e16f0f94c6cd23c7cbbf898674f230759b6e5d7bDaXXxLRAPmyoOTs2WyYvpHir7'
        );
        curl_setopt($ch,CURLOPT_PORT,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($data));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    function codeReinitialisation($code,$tel) {
        
        $sql = "SELECT user_id FROM users WHERE phone_number=".$tel;
        
        $db = new Database();

        $response = $db->DisplaysDataDb($sql);

        if(is_array($response)){
            $dt = new Datetime();
            $format_date = $dt->format("Y-m-d H:s");
            $sql1 = 'INSERT INTO validate_password (verify_code,is_used,user_id,created_date) VALUES (:code,:used,:id_user,:creation_date)';
                $data = array( "code" => $code, "used" => 0, "id_user" => $response['user_id'], "creation_date" => $format_date);
                $db = new Database();
                $response1 = $db->InsertDb_Id($sql1,$data);

                if(!is_array($response1)){
                    header("Location:/reinitialiser/$tel");
                }else{
                    var_dump($response1);
                }

        }else{
            var_dump($response);
        }

    }

    function checkCodeReinitialisation($code, $tel) {

        $request = 'SELECT distinct verify_code FROM validate_password INNER JOIN users USING(user_id) WHERE validate_password.is_used=0 AND validate_password.verify_code="'.$code.'" AND users.phone_number='.$tel;
        $db = new Database();
        $response = $db->DisplaysDataDb($request);

        if(is_array($response) && $response["verify_code"] === $code) {

            return true;

        }else{

            return false;
        }
    }
}

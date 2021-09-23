<?php

class Sms
{
    public function EnvoisSMS (string $phone, $message)
    {
        $ch = curl_init('https://textbelt.com/text');
        $data =array(
            'phone' => $number.'',
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
}

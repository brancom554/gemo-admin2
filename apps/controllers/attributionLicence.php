<?php
require_once(_APPS_PATH.'/classes/Database.php');
require_once(_APPS_PATH.'/classes/Licence.php');

if (isset($_POST['soumettre']) ) {

    var_dump($_POST);

        $agent = filter_input(INPUT_POST,'agent',FILTER_VALIDATE_INT);
        // $licence = filter_input(INPUT_POST,'licence',FILTER_SANITIZE_STRING);
       
            if (empty($_POST['agent'])) {
                $message1 = "Veuillez remplir le champs";
            }
            // if (empty($_POST['licence'])) {
            //     $message2 = "Veuillez remplir le champs";   
            // }
            
            if($_POST['agent'] && $url_array[4]){

                $lic = new Licence();
                $error = $lic->licenceAttribution($_POST['agent'], $url_array[4]);
            }
            
                 
                
}

$sql = 'SELECT user_id,firstname,lastname FROM users WHERE is_manager = 0 AND licence_id = 0 AND company_id='.$_SESSION['company'];
$sql1 = 'SELECT licence_key,licence_id FROM licences WHERE licences.licence_id ='.$url_array[4];
        $db = new Database();
        $response = $db->DisplayDataDb($sql);
        $response1 = $db->DisplaysDataDb($sql1);


if(file_exists(_VIEW_PATH.$lib->lang."/children/addAgentLicence.phtml"))  $view=$lib->lang."/children/addAgentLicence.phtml";
else  $view=$lib->lang."/children/addAgentLicence.phtml";


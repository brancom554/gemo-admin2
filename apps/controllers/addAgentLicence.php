<?php
require_once(_APPS_PATH.'/classes/Database.php');
require_once(_APPS_PATH.'/classes/Licence_attribution.php');

if (isset($_POST['soumettre']) ) {

        $agent = filter_input(INPUT_POST,'agent',FILTER_VALIDATE_INT);
        $licence = filter_input(INPUT_POST,'licence',FILTER_VALIDATE_INT);
       
            if (empty($_POST['agent'])) {
                $message1 = "Veuillez remplir le champs";
            }
            if (empty($_POST['licence'])) {
                $message2 = "Veuillez remplir le champs";   
            }
            
            if($_POST['agent'] && $_POST['licence']){

                $lic = new Licence_attribution();
                $error = $lic->licenceAttribution($_POST['agent'], $_POST['licence']);
            }
            
                 
                
}

$sql = 'SELECT user_id,firstname,lastname FROM users WHERE is_manager = 0 AND licence_id = 0 AND company_id='.$_SESSION['company'];
$sql1 = 'SELECT licence_key,licence_id FROM licences WHERE is_active=1 AND created_for_company_id ='.$_SESSION['company'];
        $db = new Database();
        $response = $db->DisplayDataDb($sql);
        $response1 = $db->DisplayDataDb($sql1);


if(file_exists(_VIEW_PATH.$lib->lang."/children/addAgentLicence.phtml"))  $view=$lib->lang."/children/addAgentLicence.phtml";
else  $view=$lib->lang."/children/addAgentLicence.phtml";


<?php
require_once(_APPS_PATH.'/classes/Database.php');

if (isset($_POST['filter'])) {
    $dateDebut = new DateTime($_POST['debut']);
    $debut = $dateDebut->format('Y-m-d');
    $dateFin = new DateTime($_POST['fin']);
    $fin = $dateFin->format('Y-m-d');
	

    if ($_SESSION['access'] == '1') {
		$userSelected = $_POST['marchand'] ;
          $sql ="";    
         // tester si l'utilisateur a choisi une plage de dates 
		 if((!empty($debut) ) && (!empty($fin))  ){
        $sql = "SELECT TR.*,OPT.libelle as description FROM 
        (SELECT ops.operation_type_id,ops.libelle,u.operation_date,ops.network_operator_name operator_name, 
        u.transaction_phone_number, users.firstname, users.lastname, u.statut_operation,u.operation_id 
        FROM user_operations u  INNER JOIN operations ops ON ops.operation_id_source = u.operation_id 
        INNER JOIN users ON users.user_id = u.created_by_user_id 
        WHERE u.created_by_user_id='".$userSelected."' AND u.operation_date BETWEEN '".$debut."' AND '".$fin."')
         AS TR INNER JOIN operation_types AS OPT ON OPT.operation_type_id = TR.operation_type_id";
		 
		 }
		 if( (empty($_POST['debut'])) || ( empty($_POST['fin']) ) ) {
			 
			 $sql = "SELECT TR.*,OPT.libelle as description FROM 
        (SELECT ops.operation_type_id,ops.libelle,u.operation_date,ops.network_operator_name operator_name, 
        u.transaction_phone_number, users.firstname, users.lastname, u.statut_operation,u.operation_id 
        FROM user_operations u  INNER JOIN operations ops ON ops.operation_id_source = u.operation_id 
        INNER JOIN users ON users.user_id = u.created_by_user_id 
        WHERE u.created_by_user_id='".$userSelected."' )
         AS TR INNER JOIN operation_types AS OPT ON OPT.operation_type_id = TR.operation_type_id";
		 }
        
    } else {
        if ($_SESSION['access'] == '2') {
            //$sql = "SELECT Z.operation_date,Z.libelle,Z.amount,Z.network_operator_name,U.firstname,U.lastname FROM (SELECT K.*,O.operation_type_id,O.libelle,O.amount,O.network_operator_name FROM (SELECT us.user_id,usop.operation_date,usop.operation_id FROM users us INNER JOIN user_operations usop ON usop.created_by_user_id = us.user_id WHERE usop.operation_date BETWEEN '".$debut."' AND '".$fin."') as K INNER JOIN operations O ON O.operation_id = K.operation_id ) as Z INNER JOIN users U ON U.  U WHERE U.company_id =".$_POST['manager'];
            $sql = "SELECT Z.operation_date,Z.libelle,Z.amount,Z.statut_operation,Z.transaction_phone_number,Z.network_operator_name,Z.firstname,Z.lastname FROM (SELECT K.*,O.operation_type_id,O.libelle,O.amount,O.statut_operation,O.transaction_phone_number,O.network_operator_name FROM (SELECT us.user_id,usop.operation_date,usop.operation_id,usop.operation_id_source,us.firstname,us.lastname FROM users us INNER JOIN user_operations usop ON usop.created_by_user_id = us.user_id WHERE usop.operation_date BETWEEN '".$debut."' AND '".$fin."' AND us.company_id ='".$_POST['manager']."') as K INNER JOIN operations O ON O.operation_id_source = K.operation_id_source) as Z";
        }
    }
    
    /*$sql = 'SELECT optypes.libelle,userop.operation_date,op.libelle as descriptions FROM user_operations userop INNER JOIN operations op ON op.operation_id = userop.operation_id
    INNER JOIN operation_types optypes ON optypes.operation_type_id = op.operation_type_id INNER JOIN users u ON u.user_id = userop.created_by_user_id
    INNER JOIN licences lic ON lic.application_uuid = userop.application_uuid WHERE userp.operation_date BETWEEN '.$debut.' AND '.$fin.'  AND lic.licence_parent_id ='.$_SESSION['licence'].' AND application_uuid='.$_POST['marchand'].'';*/
    //$sql = "SELECT * FROM user_operations u INNER JOIN operations ops ON ops.operation_id = u.operation_id WHERE u.operation_date BETWEEN '".$debut."' AND '".$fin."' AND ops.application_uuid ='".$_POST['marchand']."'";
    
    try {
        $db = new Database();
        $data['operations'] = $db->DisplayDataDb($sql);
        // var_dump($data['operations']);
        // var_dump($sql);
        // exit;
        
    } catch (\Throwable $th) {
        //throw $th;
        
    }
}

if ($_SESSION['access'] == '1') {
    $db = new Database();
    $sql = 'SELECT firstname,lastname,application_uuid,user_id FROM users WHERE company_id ='.$_SESSION['company'].' AND is_manager=0';
    $data['marchand'] = $db->DisplayDataDb($sql);
} else {
    if ($_SESSION['access'] == '2') {
        $db = new Database();
        $sql = 'SELECT firstname,lastname,company_id FROM users WHERE is_manager=1';
        $data['marchand'] = $db->DisplayDataDb($sql);
    }
}

function getNetworkByOperationId($id){
	
	$operateur = "";
	$db = new Database();
    $sql = "SELECT network_operator_name FROM operations WHERE transaction_phone_number =".$id." ORDER BY operation_date DESC Limit 1 ";
	$ind = substr("".$id,0,2);
	switch ($ind) {
  case 97:
    $operateur="MTN";
    break;
  case 96:
    $operateur="MTN";
    break;
  case 66:
    $operateur="MTN";
    break;
case 67:
    $operateur="MTN";
    break;
case 91:
    $operateur="MTN";
    break;
case 62:
    $operateur="MTN";
    break;	
 case 61:
    $operateur="MTN";
    break;
  case 69:
    $operateur="MTN";
    break;
 case 90:
    $operateur="MTN";
    break;
case 51:
    $operateur="MTN";
    break;
case 52:
    $operateur="MTN";
    break;	
	
	
 case 95:
    $operateur="MOOV";
    break;
  case 94:
    $operateur="MOOV";
    break;
  case 64:
    $operateur="MOOV";
    break;
  case 65:
    $operateur="MOOV";
    break;
 case 63:
    $operateur="MOOV";
    break;
  
 case 99:
    $operateur="MOOV";
    break;
case 60:
    $operateur="MOOV";
    break;	
case 98:
    $operateur="MOOV";
    break;
 case 68:
    $operateur="MOOV";
    break;	
  default:
    $operateur="N/A";
}
	  
    return  $operateur;
	
}

if(file_exists(_VIEW_PATH.$lib->lang."/historique.phtml"))  $view=$lib->lang."/historique.phtml";
else  $view=$iniObj->defaultLang."/historique.phtml";

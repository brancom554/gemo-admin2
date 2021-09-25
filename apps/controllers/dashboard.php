<?php
require_once(_APPS_PATH.'/classes/Database.php');

if ($url_array[2] == "chart") {
    $offres = array();
    $db = new Database();
    $sql4 = "SELECT types.licence_type_name,count(licence_type_name) as total FROM (SELECT licence_key, licence_type_id FROM licences WHERE licence_type_id IS NOT NULL) lic INNER JOIN licence_types types ON lic.licence_type_id = types.licence_type_id GROUP BY types.licence_type_name";
    $data['offres'] = $db->DisplayDataDb($sql4);
    foreach($data['offres'] as $offre){
        array_push($offres,(int)$offre['total']); 
    }
    echo json_encode(['offres' => $offres]);
    exit;
}

if ($url_array[2] == "barChart") {
    $moiss = $totall = array();
    $db = new Database();
    $sql5 = 'SELECT MONTHNAME(operation_date) as mois,count(balance_after_operate) as total FROM operations
    INNER JOIN operation_types USING (operation_type_id) 
    INNER JOIN category_ussd USING (operation_type_id)
    INNER JOIN categories USING (category_id) 
    WHERE operations.company_token="'.$_SESSION['companie_token'].'"  
    AND categories.type_category_libelle="SERVICES TELEPHONIQUES"
    GROUP BY month(operation_date) ORDER BY month(operation_date) ASC';

    // $sqll = 'SELECT MONTHNAME(operation_date) as mois,SUM(balance_after_operate) as total FROM operations
    // INNER JOIN operation_types USING (operation_type_id) 
    // INNER JOIN category_ussd USING (operation_type_id)
    // INNER JOIN categories USING (category_id) 
    // WHERE operations.company_token="'.$_SESSION['companie_token'].'"  
    // AND categories.type_category_libelle="SERVICES FINANCIERS"
    // GROUP BY month(operation_date) ORDER BY month(operation_date) ASC';

    $data['licence_somme'] = $db->DisplayDataDb($sql5);
    // $data['financiers'] = $db->DisplayDataDb($sqll);

    foreach($data['licence_somme'] as $offre){
        array_push($moiss,$offre['mois']);
        array_push($totall,(int)$offre['total']); 
    }

    // foreach($data['financiers'] as $financiers){
    //     array_push($moiss,$financiers['mois']);
    //     array_push($totall,(int)$financiers['total']); 
    // }
    echo json_encode(['moisTelephoniques' => $moiss,'totalTelephoniques' => $totall]);
    exit;
}



if ($url_array[2] == "camembertChart") {
    
    $db = new Database();
    $sql = 'SELECT DISTINCT COUNT(balance_after_operate) FROM operations 
        INNER JOIN operation_types USING (operation_type_id) 
        INNER JOIN category_ussd USING (operation_type_id)
        INNER JOIN categories USING (category_id) 
        WHERE operations.company_token="'.$_SESSION['companie_token'].'"
        AND categories.type_category_libelle="SERVICES TELEPHONIQUES"
        AND category_ussd.company_token="'.$_SESSION['companie_token'].'"';

    $sql1 = 'SELECT DISTINCT COUNT(balance_after_operate) FROM operations 
        INNER JOIN operation_types USING (operation_type_id) 
        INNER JOIN category_ussd USING (operation_type_id)
        INNER JOIN categories USING (category_id) 
        WHERE operations.company_token="'.$_SESSION['companie_token'].'"
        AND categories.type_category_libelle="SERVICES FINANCIERS"
        AND category_ussd.company_token="'.$_SESSION['companie_token'].'"';

    $data1 = $db->DisplayDataDb($sql);
    $data2 = $db->DisplayDataDb($sql1);

    $chart2data = array((int)$data1[0][0],(int)$data2[0][0]);
    echo json_encode($chart2data);
    exit;
}

if ($url_array[2] == "line") {
    $mois = $total = array();
    $db = new Database();
    $sql5 = 'SELECT monthname(operation_date) as mois,count(operation_id) as total FROM operations GROUP BY month(operation_date) ORDER BY month(operation_date) ASC';
    $data['licence_somme'] = $db->DisplayDataDb($sql5);
    foreach($data['licence_somme'] as $offre){
        array_push($mois,$offre['mois']);
        array_push($total,(int)$offre['total']); 
    }
    echo json_encode(['mois' => $mois,'total' => $total]);
    exit;
}


/*$sql = 'SELECT optypes.libelle,userop.operation_date,op.libelle as descriptions FROM user_operations userop INNER JOIN operations op ON op.operation.id = userop.operation_id
INNER JOIN operation_type optypes ON optypes.operation_type = op.operation_type_id INNER JOIN users u ON u.user_id = userop.created_by_user_id
INNER JOIN licences lic ON lic.application_uuid = userop.application_uuid WHERE lic.licence_parent_id ='.$_SESSION['licence'].' LIMIT 3';*/
$sql = "SELECT ops.libelle,opt.operation_date,opt.network_operator_name FROM operations opt INNER JOIN operation_types ops ON ops.operation_type_id = opt.operation_type_id WHERE  opt.company_token ='".$_SESSION['companie_token']."' ORDER BY opt.operation_date DESC LIMIT 6";
$sql2 = "SELECT count(licence_key) AS licence_total FROM licences";
$sql3 = "SELECT count(licence_key) AS licence_total FROM licences WHERE is_active = 1";

$sql4 = 'SELECT DISTINCT SUM(balance_after_operate) FROM operations 
        INNER JOIN operation_types USING (operation_type_id) 
        INNER JOIN category_ussd USING (operation_type_id)
        INNER JOIN categories USING (category_id) 
        WHERE operations.company_token="'.$_SESSION['companie_token'].'" 
        AND operations.network_operator_name="MTN"
        AND categories.type_category_libelle="SERVICES TELEPHONIQUES"
        AND category_ussd.network_operator_name="MTN"
        AND category_ussd.company_token="'.$_SESSION['companie_token'].'"';

$sql5 = 'SELECT DISTINCT SUM(balance_after_operate) FROM operations 
        INNER JOIN operation_types USING (operation_type_id) 
        INNER JOIN category_ussd USING (operation_type_id)
        INNER JOIN categories USING (category_id) 
        WHERE operations.company_token="'.$_SESSION['companie_token'].'" 
        AND operations.network_operator_name="MTN"
        AND categories.type_category_libelle="SERVICES FINANCIERS"
        AND category_ussd.network_operator_name="MTN"
        AND category_ussd.company_token="'.$_SESSION['companie_token'].'"';

$sql6 = 'SELECT DISTINCT SUM(balance_after_operate) FROM operations 
        INNER JOIN operation_types USING (operation_type_id) 
        INNER JOIN category_ussd USING (operation_type_id)
        INNER JOIN categories USING (category_id) 
        WHERE operations.company_token="'.$_SESSION['companie_token'].'" 
        AND operations.network_operator_name="MOOV"
        AND category_ussd.network_operator_name="MOOV"
        AND category_ussd.company_token="'.$_SESSION['companie_token'].'"';

$sql7 = 'SELECT DISTINCT licences.licence_key, licences.is_active, licence_types.licence_type_name FROM licences 
        INNER JOIN licence_types USING (licence_type_id) 
        WHERE licences.created_for_company_id='.$_SESSION['company'];

// $sql7 = 'SELECT DISTINCT SUM(balance_after_operate) FROM operations 
//         INNER JOIN operation_types USING (operation_type_id) 
//         INNER JOIN category_ussd USING (operation_type_id)
//         INNER JOIN categories USING (category_id) 
//         WHERE operations.company_token="'.$_SESSION['companie_token'].'" 
//         AND operations.network_operator_name="MOOV"
//         AND categories.type_category_libelle="SERVICES FINANCIERS"
//         AND category_ussd.network_operator_name="MOOV"
//         AND category_ussd.company_token="'.$_SESSION['companie_token'].'"';

$total = 0;
$offres = array();
$db = new Database();
try {
    $data['operations'] = $db->DisplayDataDb($sql);
    $data['licence_total'] = $db->DisplaysDataDb($sql2);
    $data['licence_active'] = $db->DisplaysDataDb($sql3);
    $data['licence_dashboard'] = $db->DisplayDataDb($sql7);

    $first_box = $db->DisplaysDataDb($sql4);
    $second_box = $db->DisplaysDataDb($sql5);
    $third_box = $db->DisplaysDataDb($sql6);
    // $fourth_box = $db->DisplaysDataDb($sql7);
    $total = (int) $data['licence_total']['licence_total'];
    $total_active = (int) $data['licence_active']['licence_total'];
} catch (\Throwable $th) {
    //throw $th;
}


if(file_exists(_VIEW_PATH.$lib->lang."/dashboard.phtml"))  $view=$lib->lang."/dashboard.phtml";
else  $view=$iniObj->defaultLang."/dashboard.phtml";
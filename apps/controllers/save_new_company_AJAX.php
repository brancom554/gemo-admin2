<?php

unset($error);
foreach ($_REQUEST as $k => $v) {
	$_REQUEST[$k] = urldecode(trim($v));
}
// $lib->debug($_REQUEST);
// exit;

if (isset($_REQUEST)) {
	$company_name = htmlspecialchars($_REQUEST['company_name'], ENT_QUOTES, "UTF-8");
	$company_zipcode = htmlspecialchars($_REQUEST['company_zipcode'], ENT_QUOTES, "UTF-8");
	$activity_area = htmlspecialchars($_REQUEST['activity_area'], ENT_QUOTES, "UTF-8");
	$company_city = htmlspecialchars($_REQUEST['company_city'], ENT_QUOTES, "UTF-8");
	$code_naf = htmlspecialchars($_REQUEST['code_naf'], ENT_QUOTES, "UTF-8");
	$company_type = htmlspecialchars($_REQUEST['company_type'], ENT_QUOTES, "UTF-8");


	//  var_dump($profile_user);
	// die;s
	if (empty(trim($company_name))) {
		echo "false||Veuillez renseigner le nom de la société";
		exit;
	} elseif (empty(trim($company_zipcode))) {
		echo "false||Veuillez renseigner le code postal";
		exit;
	} elseif (empty(trim($activity_area))) {
		echo "false||Veuillez confirmer le domaine d'activité";
		exit;
	} elseif (empty(trim($company_city))) {
		echo "false||Veuillez renseigner la ville";
		exit;
	} elseif (empty(trim($code_naf))) {
		echo "false||Veuillez renseigner le code NAF";
		exit;
	} else {
		//
		// $contact_num = 'D' . substr($zipcode, 0, 2) . substr(strtoupper(normalize($ville)), 0, 3) . substr(strtoupper(normalize($prenom_user)), 0, 2) . rand(1000000, 9000000);
		// $pass = password_hash($password, PASSWORD_ARGON2I);
		$verify_company = $sqlData->getCompanyByName($company_name);
		// var_dump($lib->debug($verify_company));
		// exit;

		if (count($verify_company['data']) != 0) {
			echo 'false||Cette société existe déjà';
			exit;
		} else {
			$register_company = $sqlData->insertNewCompany($company_name, $company_zipcode, $company_city, $activity_area, $company_type, $code_naf);
			if ($register_company == true) {
				echo 'true||Société enregistrée avec succès';
				exit;
			}
		}
	}
}
exit;



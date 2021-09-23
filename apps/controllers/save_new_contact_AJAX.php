<?php

unset($error);
foreach ($_REQUEST as $k => $v) {
	$_REQUEST[$k] = urldecode(trim($v));
}
// $lib->debug($_FILES);

$filename = $_FILES['contacts_csv']['tmp_name'];
if ($_FILES['contacts_csv']['size'] > 0) {
}
$file = fopen($filename, "r");
$array_contacts = [];
$column = fgetcsv($file,10000, ",");

	var_dump($column) ;

// $lib->debug($array_contacts);
exit;

if (isset($_REQUEST)) {
	$first_name = htmlspecialchars($_REQUEST['first_name'], ENT_QUOTES, "UTF-8");
	$last_name = htmlspecialchars($_REQUEST['last_name'], ENT_QUOTES, "UTF-8");
	$phone_number = htmlspecialchars($_REQUEST['phone_number'], ENT_QUOTES, "UTF-8");
	$fonction = htmlspecialchars($_REQUEST['fonction'], ENT_QUOTES, "UTF-8");
	$company = htmlspecialchars($_REQUEST['company'], ENT_QUOTES, "UTF-8");
	$email = htmlspecialchars($_REQUEST['email'], ENT_QUOTES, "UTF-8");
	// $password = htmlspecialchars($_REQUEST['password'], ENT_QUOTES, "UTF-8");
	$is_active = htmlspecialchars($_REQUEST['is_active'], ENT_QUOTES, "UTF-8");
	$is_manager = htmlspecialchars($_REQUEST['is_manager'], ENT_QUOTES, "UTF-8");
	$interest_level = htmlspecialchars($_REQUEST['interest_level'], ENT_QUOTES, "UTF-8");
	// $created_by = htmlspecialchars($_SESSION['customer']['contact_id'], ENT_QUOTES, "UTF-8");
	

	//  var_dump($profile_user);
	// die;s
	if (empty(trim($first_name))) {
		echo "false||Veuillez renseigner le nom de la société";
		exit;
	} elseif (empty(trim($last_name))) {
		echo "false||Veuillez renseigner le code postal";
		exit;
	} elseif (empty(trim($phone_number))) {
		echo "false||Veuillez confirmer le domaine d'activité";
		exit;
	} elseif (empty(trim($fonction))) {
		echo "false||Veuillez renseigner la ville";
		exit;
	} elseif (empty(trim($company))) {
		echo "false||Veuillez renseigner le code NAF";
		exit;
	} elseif (empty(trim($email))) {
		echo "false||Veuillez renseigner le code NAF";
		exit;
	} elseif (empty(trim($is_active))) {
		echo "false||Veuillez renseigner le code NAF";
		exit;
	} elseif (empty(trim($is_manager))) {
		echo "false||Veuillez renseigner le code NAF";
		exit;
	// } elseif (empty(trim($created_by))) {
	// 	echo "false||Veuillez renseigner le code NAF";
	// 	exit;
	
	} else {
		$verify_contact = $sqlData->getContactByEmail($email);
		// var_dump($lib->debug($verify_contact));
		// exit;
		if ($is_manager == 'on') {
			$is_manager = 1;
		}else{
			$is_manager = 0;
		}
		if ($is_active == 'on') {
			$is_active = 1;
		}else{
			$is_active = 0;
		}

		if (count($verify_contact['data']) != 0) {
			echo 'false||Ce contact existe déjà';
			exit;
		} else {
			$register_company = $sqlData->insertNewContact($first_name, $last_name, $email, $phone_number, $fonction, $is_active,$interest_level,$company,$is_manager);
		// $lib->debug($register_company);
			
			if ($register_company) {
				echo 'true||Contact enregistré avec succès';
				exit;
			}
		}
	}
}
exit;



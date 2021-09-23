<?php

unset($error);
foreach ($_REQUEST as $k => $v) {
	$_REQUEST[$k] = urldecode(trim($v));
}
if (isset($_REQUEST)) {
	$contact_id = $_REQUEST['contact_id'];
	$civilite = $_REQUEST['civilite'];
	$nom_user = $_REQUEST['nom_user'];
	$prenom_user = $_REQUEST['prenom_user'];
	$phone_number = $_REQUEST['phone_number'];
	$email = $_REQUEST['email'];
	$zipcode = $_REQUEST['zipcode'];
	$adresse1 = $_REQUEST['adresse1'];
	$ville = $_REQUEST['ville'];
	$magasin_code = $_REQUEST['magasin_code'];
	// var_dump($magasin_code);
	if (isset($magasin_code) && !empty($magasin_code)) {
		$role_contact = 'ROLE_ENTREPRISE';
	}
	

	if (empty(trim($civilite))) {
		echo "false||Veuillez renseigner la civilité";
		exit;
	} elseif (empty(trim($zipcode))) {
		echo "false||Veuillez choisir le pays";
		exit;
	} elseif (empty(trim($phone_number))) {
		echo "false||Veuillez renseigner le numéro de téléphone";
		exit;
	} elseif (empty(trim($prenom_user))) {
		echo "false||Veuillez renseigner votre prénom";
		exit;
	} elseif (empty(trim($nom_user))) {
		echo "false||Veuillez renseigner votre nom";
		exit;
	} elseif (empty(trim($ville))) {
		echo "false||Veuillez renseigner votre ville";
		exit;
	} elseif (empty(trim($adresse1))) {
		echo "false||Veuillez renseigner votre adresse";
		exit;
	} elseif (empty(trim($email))) {
		echo "false||Veuillez renseigner votre adresse email";
		exit;
	} elseif (!preg_match('/^[A-Z][\p{L}-]*$/', $nom_user)) {
		echo "false||Nom invalide";
		exit;
	} elseif (!preg_match('/^[A-Z][\p{L}-]*$/', $prenom_user)) {
		echo "false||Prénom invalide";
		exit;
	} elseif (!preg_match('/^[0-9_]+$/', $phone_number)) {
		echo "false||Numéro de téléphone incorrect";
		exit;
	} else {
		//
		$register_user = $sqlData->updateUser($civilite, $nom_user, $prenom_user, $adresse1, $zipcode, $ville, $phone_number, $email, $magasin_code , $role_contact, $contact_id);

		if ($register_user == true) {
			echo "true||Enregistrement réussi";
			exit;
		} else {
			echo "false||Enregistrement échoué";
			exit;
		}
	}
}
exit;

function normalize($string)
{
	$table = array(
		'Š' => 'S', 'š' => 's', 'Ð' => 'Dj', 'd' => 'dj', 'Ž' => 'Z', 'ž' => 'z', 'C' => 'C', 'c' => 'c', 'C' => 'C', 'c' => 'c',
		'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
		'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
		'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
		'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
		'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
		'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',
		'ÿ' => 'y', 'R' => 'R', 'r' => 'r',
	);

	return strtr($string, $table);
}

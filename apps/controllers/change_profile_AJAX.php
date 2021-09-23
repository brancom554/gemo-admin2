<?php

unset($error);
foreach ($_REQUEST as $k => $v) {
	$_REQUEST[$k] = urldecode(trim($v));
}
// $lib->debug($_REQUEST);


if (
	isset($_REQUEST['profile_id']) && isset($_REQUEST['email']) && isset($_REQUEST['nom_user']) &&
	isset($_REQUEST['prenom_user']) && isset($_REQUEST['civilite']) && isset($_REQUEST['phone_number']) &&
	isset($_REQUEST['zipcode']) && isset($_REQUEST['adresse1']) && isset($_REQUEST['password']) &&
	isset($_REQUEST['password_confirm']) && isset($_REQUEST['business_email']) && isset($_REQUEST['business_name'])
	&& isset($_REQUEST['business_address_1']) && isset($_REQUEST['business_phone_number']) &&
	isset($_REQUEST['business_city'])
) {
	$profile_id = $_REQUEST['profile_id'];
	$email = $_REQUEST['email'];
	$nom_user = $_REQUEST['nom_user'];
	$prenom_user = $_REQUEST['prenom_user'];
	$civilite = $_REQUEST['civilite'];
	$phone_number = $_REQUEST['phone_number'];
	$zipcode = $_REQUEST['zipcode'];
	$adresse1 = $_REQUEST['adresse1'];
	$password = $_REQUEST['password'];
	$password_confirm = $_REQUEST['password_confirm'];
	$business_email = $_REQUEST['business_email'];
	$business_name = $_REQUEST['business_name'];
	$business_address_1 = $_REQUEST['business_address_1'];
	$business_phone_number = $_REQUEST['business_phone_number'];
	$business_city = $_REQUEST['business_city'];

	if (empty(trim($civilite))) {
		echo "false||Veuillez renseigner la civilité";
		exit;
	} elseif (empty(trim($password))) {
		echo "false||Veuillez renseigner le mot de passe";
		exit;
	} elseif (empty(trim($password_confirm))) {
		echo "false||Veuillez confirmer le mot de passe";
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
	} elseif (empty(trim($business_name))) {
		echo "false||Veuillez renseigner le nom de votre magasin";
		exit;
	} elseif (empty(trim($business_city))) {
		echo "false||Veuillez renseigner votre ville";
		exit;
	} elseif (empty(trim($adresse1))) {
		echo "false||Veuillez renseigner votre adresse";
		exit;
	} elseif (empty(trim($business_address_1))) {
		echo "false||Veuillez renseigner l'adresse de votre magasin";
		exit;
	} elseif (empty(trim($email))) {
		echo "false||Veuillez renseigner votre adresse email";
		exit;
	} elseif (empty(trim($business_email))) {
		echo "false||Veuillez renseigner l'adresse email de votre magasin";
		exit;
	} elseif ($password != $password_confirm) {
		echo "false||Les mots de passe ne correspondent pas";
		exit;
	} elseif (!preg_match('/^[A-Z][\p{L}-]*$/', $nom_user) && !preg_match('/^[A-Z][\p{L}-]*$/', $prenom_user)) {
		echo "false||Nom ou prénom invalide";
		exit;
	} elseif (!preg_match('/^[0-9_]+$/', $phone_number)) {
		echo "false||Votre numéro de téléphone incorrect";
		exit;
	} elseif (!preg_match('/^[0-9_]+$/', $business_phone_number)) {
		echo "false||Le numéro de téléphone de votre magasin est incorrect";
		exit;
	} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo "false||Votre adresse email est incorect";
		exit;
	} elseif (!filter_var($business_email, FILTER_VALIDATE_EMAIL)) {
		echo "false||L'adresse email de votre magasin est incorrect";
		exit;
	} else {
		$magasin_code = 'D' . substr($zipcode, 0, 2) . substr(strtoupper(normalize($business_city)), 0, 3);
		$language = 'fr';
		$contact_num = $magasin_code . substr(strtoupper(normalize($prenom_user)), 0, 2) . rand(1000000, 9000000);
		$magasin_url = substr(strtoupper(normalize($business_name)), 0, 5);
		// Business email 
		$business_exist = $sqlData->getBusinessByEmail($business_email);
		$pass = password_hash($password, PASSWORD_ARGON2I);

		// User email 
		$user_exist = $sqlData->getUserByEmail($email);

		// check an buisiness email exist 
		if ($business_exist['rows'] != 0) {
			echo 'false||Votre compte magasin existe déjà, veuillez réinitialiser votre mot de passe <a href="'. $iniObj->siteUrl."customer/reset/".$random = $lib->random().'">ici</a>';
			exit;
		} 
		//check if user email exist before continue ! 
		else if ($user_exist['rows'] != 0) {
			echo 'false||Votre compte utilisateur existe déjà, veuillez réinitialiser votre mot de passe <a href="'. $iniObj->siteUrl."customer/reset/".$random = $lib->random().'">ici</a>';
			exit;
		}
		else {
			$register_business = $sqlData->insertNewBusiness($magasin_code, $business_name, $nom_user, $business_address_1, $zipcode, $business_city, $business_phone_number, $prenom_user, $business_email,$magasin_url);
			if ($register_business) {
				$register_user = $sqlData->insertNewUser($contact_num, $civilite, $nom_user, $prenom_user, $adresse1, $zipcode, $ville, $phone_number, $email, $magasin_code, $profile_id);
				if ($register_user) {
					$user_exist = $sqlData->customerEmailExist($email);
					if ($user_exist) {
						
						$register_user_pref = $sqlData->insertNewUserPref($user_exist['data'][0]->contact_num, $pass);

						
						if ($register_user_pref) {
							/* Locate the email template */
							$mailContent = $sqlData->getTemplate(1);
							$message = '';
							$subject = '';
							/* Replacing content from template */
							$keywordsContent = array(
								"{LOGIN}" => $user_exist['data'][0]->contact_num,
								"{PASSWORD}" => '****',
								"{STORE_NAME}" => $data['data'][0]->store_name,
								"{SERVICE}" => $iniObj->serviceName,
								"{SITE_URL}" => $iniObj->siteUrl,
								"{COMPANY_NAME}" => $iniObj->companyName,
								"{COMPANY_ADDRESS}" => $iniObj->companyAddress,
								"{COMPANY_ZIP_CODE}" => $iniObj->companyZipCode,
								"{COMPANY_CITY}" => $iniObj->companyCity,
								"{COMPANY_COUNTRY}" => $iniObj->companyCountry,
								"{COMPANY_PHONE}" => $iniObj->companyPhoneNum,
								"{COMPANY_FAX}" => $iniObj->companyFaxNum,
								"{SERVICE_EMAIL}" => $iniObj->emailSender
							);

							$keywordsSubject = array(
								"{id}" => $val->shipping_id, "{STORE_NAME}" => $user_exist['data'][0]->store_name
							);

							$message = str_replace(
								array_keys($keywordsContent),
								array_values($keywordsContent),
								$mailContent['data'][0]->body
							);
							$subject = str_replace(array_keys($keywordsSubject), array_values($keywordsSubject), $mailContent['data'][0]->subject);
							// 				 $lib->sendEmailNoCC($iniObj->emailContact,"jacques.jocelyn@jiscomputing.com",$subject,$message
							// 					$lib->sendEmailNoCC($iniObj->emailContact,"pascal.sylvestre@jiscomputing.com",$subject,$message
							$sendEmail = $lib->sendConfirmEmailNoCC($iniObj->emailContact, $email, $subject, $message, $cc = $iniObj->emailContact);
						
							if ($sendEmail) {
								# code...
								echo "true||" . $lang->trl("An e-mail has been sent to your e-mail address with a new password. Please use the new credentials received via email");
								exit;
							} else {
								echo "false||Le serveur de mail ne répond pas";
								exit;
							}
						} else {
							echo "false||Une erreur est survenue lors de l'enregistrement des préférences utilisateurs.";
							exit;
						}
					} else {
						echo "true||Ce utilisateur n'existe pas encore.";
						exit;
					}
				} else {
					echo "true||Enregistrement du contact a échoué";
					exit;
				}
			} else {
				echo "true||Enregistrement du magasin échoué";
				exit;
			}
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

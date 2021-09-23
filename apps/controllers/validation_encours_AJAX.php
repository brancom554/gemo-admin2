<?php

unset($error);
foreach ($_REQUEST as $k => $v) {
	$_REQUEST[$k] = urldecode(trim($v));
}
// $lib->debug($_REQUEST);

if (isset($_REQUEST)) {

	$order_cours = $sqlData->update_order_alert_encours($_REQUEST['order_id']);
	$lib->debug($_SESSION['customer']['store_name']);
	// die(var_dump($register_user_pref));
	if ($order_cours) {
		$data = $sqlData->customerExist($_SESSION['customer']['contact_num']);
		/* Locate the email template */
		if (trim($data['data'][0]->email)) {

			$mailContent = $sqlData->getTemplate(4);
			$message = '';
			$subject = '';
			/* Replacing content from template */
			$keywordsContent = array(
				"{LOGIN}" => $_SESSION['customer']['contact_num'],
				"{PASSWORD}" => '****',
				"{SERVICE}" => $iniObj->serviceName,
				"{SITE_URL}" => $iniObj->siteUrl,
				"{COMPANY_NAME}" => $iniObj->companyName,
				"{COMPANY_ADDRESS}" => $iniObj->companyAddress,
				"{COMPANY_ZIP_CODE}" => $iniObj->companyZipCode,
				"{COMPANY_CITY}" => $iniObj->companyCity,
				"{COMPANY_COUNTRY}" => $iniObj->companyCountry,
				"{COMPANY_PHONE}" => $iniObj->companyPhoneNum,
				"{COMPANY_FAX}" => $iniObj->companyFaxNum,
				"{SERVICE_EMAIL}" => $iniObj->emailSender,
				"{STORE_NAME}" => $_SESSION['customer']['store_name']


			);

			$keywordsSubject = array(
				 "{STORE_NAME}" => $_SESSION['customer']['store_name']
			);

			$message = str_replace(array_keys($keywordsContent),array_values($keywordsContent),$mailContent['data'][0]->body);
			$subject = str_replace(array_keys($keywordsSubject), array_values($keywordsSubject), $mailContent['data'][0]->subject);
			// 				 $lib->sendEmailNoCC($iniObj->emailContact,"jacques.jocelyn@jiscomputing.com",$subject,$message
			// 					$lib->sendEmailNoCC($iniObj->emailContact,"pascal.sylvestre@jiscomputing.com",$subject,$message
			$sendEmail = $lib->sendConfirmEmailNoCC(
				$iniObj->emailContact,
				$data['data'][0]->email,
				$subject,
				$message,
				$cc = $iniObj->emailContact
			);
			if ($sendEmail) {
				# code...
				echo "true||Vous venez de mettre une commande en cours de validation.";
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
		echo "false||Ce utilisateur n'existe pas encore.";
		exit;
	}
	exit;
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

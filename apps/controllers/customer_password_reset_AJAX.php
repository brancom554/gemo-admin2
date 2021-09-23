<?php


unset($message);
unset($error);

foreach ($_REQUEST as $k => $v) {
	// $_POST[$k] = utf8_encode(utf8_decode(urldecode($v)));
	//
	$dataRequest = trim(urldecode($v));
	if ($dataRequest == "undefined") {
		$_REQUEST[$k] = "";
	} else {
		$_REQUEST[$k] = $dataRequest;
	}
	/* handling boolean values for /boolean flag */
	if ($k == "shared") {
		if ($v == "false") $_REQUEST[$k] = 0;
		else $_REQUEST[$k] = 1;
	}
}

if (!trim($_REQUEST['email'])) {
	echo "false||" . $lang->trl("Please enter your email address in order to reset your password");
} else {
	$data = $sqlData->customerExist($_REQUEST['email']);
	//  $lib->debug($data);
	if (trim($data['data'][0]->email)) {
		$salt = time();
		$password = $lib->generatePassword();
		// echo "false||" . $password;

		/* encrypted password*/
		// 		$encrypted=$lib->encryptPassword($password,$salt);

		/* regular password*/
		$encrypted = password_hash($password, PASSWORD_ARGON2I);
		if (
			$sqlData->updatePassword($data['data'][0]->contact_num, $encrypted, $salt)
		) {

			/* Locate the email template */
			$mailContent = $sqlData->getTemplate(3);
			$message = '';
			$subject = '';
			/* Replacing content from template */
			$keywordsContent = array(
				"{LOGIN}" => $data['data'][0]->contact_num, "{PASSWORD}" => $password, "{STORE_NAME}" => $data['data'][0]->store_name, "{LANG}" => $data['data'][0]->language_code, "{SERVICE}" => $iniObj->serviceName, "{SITE_URL}" => $iniObj->siteUrl, "{COMPANY_NAME}" => $iniObj->companyName, "{COMPANY_ADDRESS}" => $iniObj->companyAddress, "{COMPANY_ZIP_CODE}" => $iniObj->companyZipCode, "{COMPANY_CITY}" => $iniObj->companyCity, "{COMPANY_COUNTRY}" => $iniObj->companyCountry, "{COMPANY_PHONE}" => $iniObj->companyPhoneNum, "{COMPANY_FAX}" => $iniObj->companyFaxNum, "{SERVICE_EMAIL}" => $iniObj->emailSender

			);

			$keywordsSubject = array(
				"{id}" => $val->shipping_id, "{STORE_NAME}" => $data['data'][0]->store_name
			);

			$message = str_replace(
				array_keys($keywordsContent),
				array_values($keywordsContent),
				$mailContent['data'][0]->body
			);
			$subject = str_replace(array_keys($keywordsSubject), array_values($keywordsSubject), $mailContent['data'][0]->subject);
			// 				 $lib->sendEmailNoCC($iniObj->emailContact,"jacques.jocelyn@jiscomputing.com",$subject,$message
			// 					$lib->sendEmailNoCC($iniObj->emailContact,"pascal.sylvestre@jiscomputing.com",$subject,$message
			$lib->sendEmailNoCC(
				$iniObj->emailContact,
				$data['data'][0]->email,
				$subject,
				$message,
				$cc = $iniObj->emailContact
			);
			echo "true||" . $lang->trl("An e-mail has been sent to your e-mail address with a new password. Please use the new credentials received via email");
			exit;
		} else {
			echo "false||" . $lang->trl("We were unable to send you an email with your new password. Please try again later");
			exit;
		}
	} else {
		echo "false||" . $lang->trl("Your email could not be found in our system. Please check your entry");
		exit;
	}
}

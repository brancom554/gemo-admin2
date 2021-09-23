<?php

if ($_REQUEST['np'] != $_REQUEST['cp']) {
	echo "false||The new password and the confirmation do not match";
} else {
	$data = $sqlData->customerExist($_REQUEST['contact_num']);
	
	$encryptednewp = password_hash($_REQUEST['np'], PASSWORD_ARGON2I);

	
	if (password_verify($_REQUEST['oldp'], $data['data'][0]->contact_password)) {
		# code...
		if (
			$sqlData->updatePassword($_SESSION['customer']['contact_num'], $encryptednewp, $salt)
		) {

			/* Locate the email template */
			$ENTemplate = $sqlData->getTemplate(29); // loading EN email Template

			$FRTemplate = $sqlData->getTemplate(28); // loading FR email template

			$userTemplate = "{$_SESSION['customer']['prefered_lang']}Template";

			$message = '';
			$subject = '';
			/* Replacing content from template */
			$keywordsContent = array(
				"{EMAIL}" => $_SESSION['customer']['email_address'], "{PASSWORD}" => $_REQUEST['np'], "{LANG}" => $data['data'][0]->prefered_lang, "{SERVICE}" => $iniObj->serviceName, "{SITE_URL}" => $iniObj->siteUrl, "{COMPANY_NAME}" => $iniObj->companyName, "{COMPANY_ADDRESS}" => $iniObj->companyAddress, "{COMPANY_ZIP_CODE}" => $iniObj->companyZipCode, "{COMPANY_CITY}" => $iniObj->companyCity, "{COMPANY_COUNTRY}" => $iniObj->companyCountry, "{COMPANY_PHONE}" => $iniObj->companyPhoneNum, "{COMPANY_FAX}" => $iniObj->companyFaxNum

			);

			$keywordsSubject = array(
				"{id}" => $val->shipping_id, "{SERVICE}" => $iniObj->serviceName
			);


			$message = str_replace(array_keys($keywordsContent), array_values($keywordsContent), ${$userTemplate}['data'][0]->body);
			$subject = str_replace(array_keys($keywordsSubject), array_values($keywordsSubject), ${$userTemplate}['data'][0]->subject);
			$sent = $lib->sendEmailNoCC($iniObj->emailContact, $_SESSION['customer']['email_address'], $subject, $message, $cc = '', $cc = $iniObj->emailContact);

			if ($sent == true) {
				echo "true||" . $lang->trl("Your password has been changed. An e-mail has been sent to your e-mail address with your new password. From now on,Please use the new credentials received from email");
				exit;
			}
			echo "true||" . $lang->trl("Your password has been changed");
			exit;
		} else {
			echo "false||" . $lang->trl("An error occured while updating your password. Please try again later");
		}
	} else {
		echo "false||" . $lang->trl("The current password provided is incorrect");
	}
}

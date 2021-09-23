<?php

/*
   echo "<pre>";
   print_r($_SESSION);
   print_r($_REQUEST);
echo "</pre>";

*/
unset($message);
unset($error);
if(
  isset($_REQUEST['sent']) && $_REQUEST['sent']==1)
{

	if(!trim($_REQUEST['email'])) $error[]=$lang->trl("Please enter your email address in order to reset your password");
	else{
		  $data=$sqlData->customerEmailExist($_REQUEST['email']);
			if(trim($data['data'][0]->email_address) && $data['data'][0]->email_address==$_REQUEST['email']){

				$salt=time();
		    $password = $lib->generatePassword();
		    $encrypted=$lib->encryptPassword($password,$salt);
		  if(
		    $sqlData->updatePassword($data['data'][0]->email_address,$encrypted,$salt)

		  ){

		  	 /* Locate the email template */
		  $ENTemplate = $sqlData->getTemplate(20); // loading EN email Template

		  $FRTemplate = $sqlData->getTemplate(19); // loading FR email template

		$userTemplate="{$data['data'][0]->prefered_lang}Template";
		    $message='';
		    $subject='';
		      /* Replacing content from template */
		      $keywordsContent = array(
		        "{EMAIL}" => $data['data'][0]->email_address
		        ,"{PASSWORD}" => $password
		        ,"{LANG}" => $data['data'][0]->prefered_lang
		        ,"{SERVICE}" => $iniObj->serviceName
            ,"{SITE_URL}"=> $iniObj->siteUrl
            ,"{COMPANY_NAME}"=>$iniObj->companyName
            ,"{COMPANY_ADDRESS}"=>$iniObj->companyAddress
            ,"{COMPANY_ZIP_CODE}"=>$iniObj->companyZipCode
            ,"{COMPANY_CITY}"=>$iniObj->companyCity
            ,"{COMPANY_COUNTRY}"=>$iniObj->companyCountry
            ,"{COMPANY_PHONE}"=>$iniObj->companyPhoneNum
            ,"{COMPANY_FAX}"=>$iniObj->companyFaxNum

		        );

		      $keywordsSubject = array(
            "{id}" => $val->shipping_id
          ,"{SERVICE}" => $iniObj->serviceName
          );


		      $message = str_replace(array_keys($keywordsContent), array_values($keywordsContent), ${$userTemplate}['data'][0]->body);
// 		      $subject =  ${$userTemplate}['data'][0]->subject;
		      $subject = str_replace(array_keys($keywordsSubject), array_values($keywordsSubject), ${$userTemplate}['data'][0]->subject);


		      $lib->sendEmailNoCC($iniObj->emailContact,$data['data'][0]->email_address,$subject,$message
		       ,$cc=$iniObj->emailContact
		      );
		      $_SESSION['SUCCESS'][]=$lang->trl("An e-mail has been sent to your e-mail address with a new password. Please use the credentials provided in the received email");
		      header("Location: /".strtolower($data['data'][0]->prefered_lang)."/".$iniObj->serviceName);
		  }
		  else{

		  	$error[]=$lang->trl("We were unable to send you an email with your new password. Please try again later");
		  }

			}
			else{
				$error[]=$lang->trl("Your email could not be found in our customer base. Please verify your entry");
			}

		}
}



if(!isset($_REQUEST['sent']) || (isset($_REQUEST['sent']) && isset($error))){
  $view= $viewPath."/password_reset.phtml";
}

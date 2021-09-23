<?php
@ini_set("display_errors",0);
DEFINE("_PATH_ROOT", realpath(dirname(__FILE__))); // test local
defined('_APPS_PATH')  || define('_APPS_PATH', realpath(dirname(__FILE__) . '/../..'));
// define('_ROOT_FILES',realpath(dirname(__FILE__) . '/../../'));
define('_ROOT_FILES',realpath(dirname(__FILE__) . '/../../../'));
define("_CONFIG_PATH",_APPS_PATH."/config/");

require(_CONFIG_PATH."config.path.php");
require _LIB_PATH.'CronTools.class.php';


$data = $sqlData->getPasswordNotSent();
// $ENTemplate = $sqlData->getTemplate(23); // loading EN email Template
$FRTemplate = $sqlData->getTemplate(1); // loading FR email template

// $lib->debug($data);

// echo "<br>\n";
// $lib->debug($FRTemplate);

// exit;

if(count($data['data']) >0){
	foreach ($data['data'] as $keys){
    // $salt=time();
    //$password = $lib->generatePassword();
      $encrypted=$lib->encryptPassword($keys->password,$keys->salt);
      $password = $lib->decryptPassword($keys->password,$keys->salt);

//      echo "password found decrypted : ".$password;
     // exit;

      /* Locate the email template */
      $userTemplate="{$keys->lang}Template";
      $message='';
      $subject='';
          /* Replacing content from template */
          $keywordsContent = array(
            "{EMAIL}" => $keys->email
          	,"{LOGIN}" => $keys->login
          	,"{STORE_NAME}" => $keys->store_name
            ,"{PASSWORD}" => $password
            ,"{LANG}" => $keys->lang
            ,"{SERVICE}" => $iniObj->serviceName
            ,"{SITE_URL}"=> $iniObj->siteUrl
            ,"{COMPANY_NAME}"=>$iniObj->companyName
            ,"{COMPANY_ADDRESS}"=>$iniObj->companyAddress
            ,"{COMPANY_ZIP_CODE}"=>$iniObj->companyZipCode
            ,"{COMPANY_CITY}"=>$iniObj->companyCity
            ,"{COMPANY_COUNTRY}"=>$iniObj->companyCountry
            ,"{COMPANY_PHONE}"=>$iniObj->companyPhoneNum
            ,"{COMPANY_FAX}"=>$iniObj->companyFaxNum
          	,"{SERVICE_EMAIL}"=>$iniObj->emailSender
            );

        $keywordsSubject = array(

        "{SERVICE}" => $iniObj->serviceName
        ,"{STORE_NAME}" => $keys->store_name
        );

//       echo "<br>ini\n";
//       $lib->debug($iniObj);
// exit;

          $message = str_replace(array_keys($keywordsContent), array_values($keywordsContent), $FRTemplate['data'][0]->body);
//          echo "<br>message\n";
//           $lib->debug($message);
//           exit;

//           $subject = str_replace(array_keys($keywordsContent), array_values($keywordsContent), ${$userTemplate}['data'][0]->subject);
          $subject = str_replace(array_keys($keywordsSubject), array_values($keywordsSubject), $FRTemplate['data'][0]->subject);

//         echo "<br>subject\n".$subject;
//         echo "<br>subject template\n".$FRTemplate['data'][0]->subject;
//          $lib->debug($subject);
         // exit;

          if(
          		$lib->sendEmailNoCC($iniObj->emailSender ,$keys->email,$subject,$message,$iniObj->emailContact,'','',$iniObj->contactReply,'')
        ){
          if(!$sqlData->setPasswordSent($keys->login)) echo "Table POD # $keys->id not updated";
//           echo "to update table";
          };
      }
  }

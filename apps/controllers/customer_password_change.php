<?php

unset($message);
unset($error);
if(
  isset($_REQUEST['sent']) && $_REQUEST['sent']==1)
{

	$salt=time();
  $encrypted=$lib->encryptPassword($_REQUEST['newPass'],$salt);
if(
    $sqlData->updatePassword($_SESSION['customer']['email_address'],$encrypted,$salt)
  ){
          $_SESSION['SUCCESS'][]=$lang->trl("Your password has been changed");
          header("Location: /".$lib->lang."/".$iniObj->serviceName);
  }
  else{
    $error[]=$lang->trl("An error occured while updating your preferences");
  }
}



if(!isset($_REQUEST['sent']) || (isset($_REQUEST['sent']) && isset($error))){
  $langList = array("FR"=> $lang->trl('French'), "EN"=>$lang->trl('English') );
  $countryList=$sqlData->countryList();
  $view= $viewPath."/password_change.phtml";
}

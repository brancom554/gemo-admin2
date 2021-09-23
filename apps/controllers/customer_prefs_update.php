<?php
/*
 echo "<pre>";
 print_r($_SESSION);
 print_r($_REQUEST);
 echo "</pre>";

 *
 */
unset($message);
unset($error);
if(
isset($_REQUEST['sent']) && $_REQUEST['sent']==1)
{


	if(
	$sqlData->updatePreferences($_SESSION['customer']['email_address'],$_REQUEST['lang'],$_REQUEST['billingAddress'])

	){

		/*
		 * Updating current session vars
		 */
		$data=$sqlData->authenticate($_SESSION['customer']['email_address']);

		if($data['rows']>0){
			foreach ($data['data'][0] as $k=>$v){
				$_SESSION['customer'][$k] = $_SESSION['customer'][$k]=$v;
			}

		}

		$_SESSION['SUCCESS'][]=$lang->trl("Your preferences have been updated");
		$_SESSION['customer']['prefered_lang']=strtolower($_REQUEST['lang']);
		$lib->lang=$_SESSION['LANG']=strtolower($_REQUEST['lang']);

		header("Location: /".$lib->lang."/".$iniObj->serviceName);
	}
	else{

		$error[]=$lang->trl("An error occured while updating your preferences");
	}
}



if(!isset($_REQUEST['sent']) || (isset($_REQUEST['sent']) && isset($error))){
	$langList = array("FR"=> $lang->trl('French'), "EN"=>$lang->trl('English') );

	// $countryList=$sqlData->countryList();
	$billingAddresses = $sqlData->getBillingAddresses($_SESSION['customer']['contact_id']);
	$view= $viewPath."/preferences_update.phtml";
}
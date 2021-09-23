<?php
unset($error);
unset($_SESSION['INFO']);
if(isset($_REQUEST['sent']) && $_REQUEST['sent']==1){
  if( !trim($_REQUEST['companyName'])
   || !trim($_REQUEST['address'])
   || !trim($_REQUEST['zipCode'])
   || !trim($_REQUEST['city'])
   || !trim($_REQUEST['country'])
   || !trim($_REQUEST['lang'])
   || !trim($_REQUEST['phone'])


   ){
   $error[]=$lang->trl("Please ensure that all mandatory fields are populated");
}

if(!isset($error)){
 if (isset($_REQUEST['shared'])) $_REQUEST['shared']=1; else $_REQUEST['shared']=0;

 if($companyId=$sqlData->createCompany($_REQUEST['companyName'])){
  if($id=$sqlData->addNewAddress($_REQUEST,$companyId,$_SESSION['customer']['company_id'],$_SESSION['customer']['contact_id'])){
    $_SESSION['SUCCESS'][]=$lang->trl("This address has been created successfully");
    header("Location: /".$lib->lang."/".$iniObj->serviceName."/addressBook");
  }
  else{
    $error[]=$lang->trl("An error occured while creating your address");
  }

}
else{
  $error[]=$lang->trl("An error occured while creating this company. Please try again later");
}
}
}


if(!isset($_REQUEST['sent']) || (isset($_REQUEST['sent']) && isset($error))){
	$langList = array("FR"=> $lang->trl('French'), "EN"=>$lang->trl('English') );

	$countryList=$sqlData->countryList();
	// $lib->debug($countryList);
  $view= $viewPath."/newAddress.phtml";
}
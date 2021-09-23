<?php

unset($message);
unset($error);
if(!$_SESSION['customer']['authValidated']) {
	echo "false";
}else{
	foreach($_REQUEST as $k=>$v){
		// $_POST[$k] = utf8_encode(utf8_decode(urldecode($v)));
		$_REQUEST[$k] = trim(urldecode($v));
		/* handling boolean values for /boolean flag */
		if($k=="shared"){
			if ($v =="false") $_REQUEST[$k]=0;
			else $_REQUEST[$k]=1;
		}
	}
	if( !$_REQUEST['companyName']
		|| !$_REQUEST['address']
		|| !$_REQUEST['zipCode']
		|| !$_REQUEST['city']
		|| !$_REQUEST['country']
		|| !$_REQUEST['lang']
		|| !$_REQUEST['phone']
	){
		echo "false||".$lang->trl("Please ensure that all mandatory fields are populated");
	}
	else {
		$lib->debug($_REQUEST);
		if ($_REQUEST['shared']=="1") $_REQUEST['shared']=1; else $_REQUEST['shared']=0;

		if($companyId=$sqlData->createCompany($_REQUEST['companyName'])){
			if($id=$sqlData->addNewAddress($_REQUEST,$companyId,$_SESSION['customer']['location_address_id']
				,$_SESSION['customer']['contact_id'])){
				echo "true||".$id."||".$lang->trl("This address has been created successfully");
		}
		else{
			echo "false||".$id."||".$lang->trl("An error occured while creating your address");
		}

	}
	else{
		echo "false||".$id."||".$lang->trl("An error occured while creating this company. Please try again later");
	}
}
}
exit;
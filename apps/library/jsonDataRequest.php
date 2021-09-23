<?php
/*
 * Redirect user on the home page if php page is accessed directly (not from the home page)
 */
// @ini_set("set_time_limit",40);

function tokenInput($input){
// return ("[".$input."]");
  return ($input);
}

require_once _LIB_PATH."JSON.Class.php";
$json = new JsonConverter();

$debug=0;
if($debug ==0)  @header('Content-type: text/html; charset=utf-8');
else{
  @ini_set("display_errors",1);
  echo "<br>Error<pre>";
}

if($url_array[3]=='type'){
	/* removing index.php from the array URL */
	unset($url_array[2]); $url_array=array_values($url_array);
}

// set set the search limit to the sytem default if user settings not set
$searchLimit = (!$_SESSION['customer']['searchLimit']? $iniObj->defaultSearchLimit: $_SESSION['customer']['searchLimit']);
$type=urldecode($url_array[2]);
$id=urldecode($url_array[3]);
// echo "tupe = ".$type;
switch ($type){
  case "track":
  list($id, $search) =explode("||",addslashes(urldecode($url_array[4])));

  $idSlash=addslashes(substr($id, 0,(strlen($id)-$iniObj->trackingKeyLength)));
  $key=addslashes(substr($id, (strlen($id)-$iniObj->trackingKeyLength)));
  $data= $sqlData->getShippingMedicalFlag($idSlash,$key);

  // echo "tracking data =>".$idSlash;
  // $lib->debug($data);
  if($data->health_care_flag==1 && ($_SESSION['customer']['authValidated'] && $_SESSION['customer']['address_id']!= $data->address_billto_id)){

  	echo "false||".$lang->trl("This data cannot be viewed through your current profile");
  	exit;
  }else{

  	$result= $sqlData->getTracking($idSlash,$key);
  	echo $json->array2json($result);

  }

  //
  exit;
  break;

  case "prList": // Product list for current user
  	if(!$_SESSION['customer']['authValidated']) exit;

  	list($id, $type, $search) =explode("||",addslashes(urldecode($url_array[3])));
  	$aSearch =explode(" ",trim($search));
  	/* Do not return results if current user is not the one asking for data */
//   	echo("user id =>".$id."search ".$aSearch." / type :".$type);
  	if($_SESSION['customer']['contact_id'] != $id) exit;
  	if($iniObj->debugSQL==1){
  		echo("user id =>".$id."search ".$aSearch." / type :".$type);


  	}
  	$result= $sqlData->getUserStockProducts($start,$end,$id);
  	echo $json->array2json($result);
  	exit;
  	break;

  	case "paList": // Product list for current user
  		if(!$_SESSION['customer']['authValidated']) exit;

  		list($id, $type, $search) =explode("||",addslashes(urldecode($url_array[3])));
  		$aSearch =explode(" ",trim($search));
  		/* Do not return results if current user is not the one asking for data */
  		//   	echo("user id =>".$id."search ".$aSearch." / type :".$type);
  		if($_SESSION['customer']['contact_id'] != $id) exit;
  		if($iniObj->debugSQL==1){
  			echo("user id =>".$id."search ".$aSearch." / type :".$type);


  		}
  		$result= $sqlData->getUserProductPayments($start,$end,$id);
  		echo $json->array2json($result);
  		exit;
  		break;


  case "cList":
  	if(!$_SESSION['customer']['authValidated']) exit;
  $id=urldecode($url_array[4]);
  $idSlash=addslashes($id);
  /* Do not return results if current user is not the one asking for data */
  if($_SESSION['customer']['address_id'] != $idSlash) exit;

  $result= $sqlData->getUserContactList($idSlash);
  echo $json->array2json($result);
  exit;
  break;



  case "sList": // Shipping list
  	if(!$_SESSION['customer']['authValidated']) exit;
  list($id, $type, $search) =explode("||",addslashes(urldecode($url_array[4])));
  $aSearch =explode(" ",trim($search));
  /* Do not return results if current user is not the one asking for data */
  if($_SESSION['customer']['contact_id'] != $id) exit;
if($iniObj->debugSQL==1){
	echo("user id =>".$id."search ".$aSearch." / type :".$type);


}
  $result= $sqlData->getLastTrackingList($id,$aSearch,$type);
  echo $json->array2json($result);
  exit;
  break;




  /* Contact list */
  case "sCont":
  	if(!$_SESSION['customer']['authValidated']) exit;
  list($id, $search) =explode("||",addslashes(urldecode($url_array[4])));

  $aSearch =explode(" ",trim($search));
  /* Do not return results if current user is not the one asking for data */
  if($_SESSION['customer']['location_address_id'] != $id) exit;
  $includeBilling = $iniObj->addressBookIncludeBillingAddresses;
  if($_SESSION['customer']['sharedAddressBook']== 0) $result= $sqlData->getUserContactListNotShared($id,$aSearch,$_SESSION['customer']['location_address_id'],$_SESSION['customer']['contact_id'],$searchLimit,$includeBilling);
  else $result= $sqlData->getUserContactList($id,$aSearch,$_SESSION['customer']['location_address_id'],$_SESSION['customer']['contact_id'],$searchLimit,$includeBilling);
  echo $json->array2json($result);
  exit;
  break;

  case "sAbook": // Customer address Book list
  	if(!$_SESSION['customer']['authValidated']) exit;
  list($company,$address,$search) =explode("||",addslashes(urldecode($url_array[4])));
  $aSearch =explode(" ",trim($search));
  /* Do not return results if current user is not the one asking for data */
  if($_SESSION['customer']['location_address_id'] != $company) exit;

  if($_SESSION['customer']['sharedAddressBook']== 0) $result= $sqlData->getLastAddressListNotShared($company,$address,$aSearch,$_SESSION['customer']['contact_id'],$searchLimit);
  else $result= $sqlData->getLastAddressList($company,$address,$aSearch,$_SESSION['customer']['contact_id'],$searchLimit);
  echo $json->array2json($result);
  exit;
  break;


  /* Billing address list for the current user*/
  case "billAdd":
  	if(!$_SESSION['customer']['authValidated']) exit;
  list($id, $search) =explode("||",addslashes(urldecode($url_array[4])));
  $aSearch =explode(" ",trim($search));
  /* Do not return results if current user is not the one asking for data */
  if($_SESSION['customer']['contact_id'] != $id) exit;

  $result= $sqlData->getBillingAddresses($_SESSION['customer']['contact_id']);
  echo $json->array2json($result);
  exit;
  break;

  /* Temperature list for the current user*/
  case "pTemp":
  	if(!$_SESSION['customer']['authValidated']) exit;
  	list($id, $search) =explode("||",addslashes(urldecode($url_array[4])));
  	$aSearch =explode(" ",trim($search));

  	$result= $sqlData->listTemperatures();
  	echo $json->array2json($result);
  	exit;
  	break;

  	case "pList":
  		if(!$_SESSION['customer']['authValidated']) exit;
  		list($id, $search) =explode("||",addslashes(urldecode($url_array[4])));

  		$result= $sqlData->listPackaging($id);
  		echo $json->array2json($result);
  		exit;
  	break;

  case "dS" : /* shipping detail */
  	if(!$_SESSION['customer']['authValidated']) exit;
  list($id) =explode("||",addslashes(urldecode($url_array[4])));

  $result=$sqlData->getShippingDetail($_SESSION['customer']['contact_id'],$id);
  echo $json->array2json($result);
  exit;
  break;


case "prefs":
	if(!$_SESSION['customer']['authValidated']) exit;
list($id) =explode("||",addslashes(urldecode($url_array[4])));
  if($_SESSION['customer']['contact_id'] != $id) exit;

$result=$sqlData->getPreferences($_SESSION['customer']['contact_id']);
echo $json->array2json($result);
exit;
break;

  case "country":
  $result=$sqlData->countryList();
  echo $json->array2json($result);
  exit;

  case "language":
   $result=$sqlData->languageList();
  echo $json->array2json($result);
  exit;



}
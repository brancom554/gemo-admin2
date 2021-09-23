<?php
/*
 * Redirect user on the home page if php page is accessed directly (not from the home page)
 */
// @ini_set("set_time_limit",40);

//$url_array = explode("/", $_SERVER["HTTP_REFERER"]) ;
// $url_requested=$url_array[1];if ($url_requested!=_MAIN_PHP_PAGE) Header("Location: "._MAIN_PHP_PAGE);
// $lib->debug($url_array);
// if(!trim($_SERVER["HTTP_REFERER"]) || $url_array[3]!= _MAIN_PHP_PAGE) exit;
// if(trim($artist)) $artist = addslashes(str_replace("%20"," ",$artist));

// $lib->debug($url_array);
// $type=$url_array[3];
require_once _LIB_PATH."XML.Class.php";
$xml = new XmlClass();

$debug=0;
if($debug ==0)  @header('Content-Type: text/xml; charset=utf-8');
else{
@ini_set("display_errors",1);
echo "<br><pre>";
}

if($url_array[2]=='type'){
	/* removing index.php from the array URL */
	unset($url_array[2]); $url_array=array_values($url_array);
}



$type=urldecode($url_array[2]);
$id=urldecode($url_array[3]);
// print_r($url_array);
// echo "<br>tupe = $type";
// echo "<br>id => $id";
 // exit;
switch ($type){
	case "contactList":
		$id=urldecode($url_array[3]);
    $idSlash=addslashes($id);
		$result['contact']= $sqlData->getUserContactList($idSlash);
     // print_r($result);
     // exit;

		$filePath=_PATH_USER_DATA_TMP."xml/contactList_".$idSlash.".xml";
		if (file_exists($filePath))  echo file_get_contents ($filePath);
		else {
			 $result['contact']= $sqlData->getUserContactList($idSlash);
//			$result[]= $sqlData->getUserContactList($idSlash);
			// print_r($result);

		//	$lib->writeFile($filePath, $xml->inputToXml($result));
			echo $xml->inputToXml($result);
//			echo file_get_contents ($filePath);
		}
		exit;
	break;

  case "sCont":
  	$id=urldecode($url_array[3]);
  	$search=urldecode($url_array[4]);
    $idSlash=addslashes($id);
    $result['contact']= $sqlData->getUserContactList($idSlash,$search);
 //     print_r($result);
  //    exit;

    $filePath=_PATH_USER_DATA_TMP."xml/contactList_".$idSlash.".xml";
    if (file_exists($filePath))  echo file_get_contents ($filePath);
    else {
       $result['contact']= $sqlData->getUserContactList($idSlash,$search);
//      $result[]= $sqlData->getUserContactList($idSlash);
      // print_r($result);

    //  $lib->writeFile($filePath, $xml->inputToXml($result));
      echo $xml->inputToXml($result);
//      echo file_get_contents ($filePath);
    }
    exit;
  break;


}
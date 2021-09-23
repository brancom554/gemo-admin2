<?php
@ini_set("display_errors",0);
DEFINE("_PATH_ROOT", realpath(dirname(__FILE__))); // test local
defined('_APPS_PATH')  || define('_APPS_PATH', realpath(dirname(__FILE__) . '/../..'));
// define('_ROOT_FILES',realpath(dirname(__FILE__) . '/../../'));
define('_ROOT_FILES',realpath(dirname(__FILE__) . '/../../../'));
define("_CONFIG_PATH",_APPS_PATH."/config/");

require(_CONFIG_PATH."config.path.php");
require _LIB_PATH.'CronTools.class.php';

$cron = new CronTools();



// $addressId =182;
// $password="occaz9";

// $salt=time();
// $encrypted=$lib->encryptPassword($password,$salt);

/* get all user from this address */

$emailList = $sqlData->getAllUsersWithNoPassword();
// $lib->debug($emailList);
//exit;
/* Loading user preferences along with email address for each shipping
 * $num, $pass,$prospect, $lang,$key){
 * */
$salt=time();
foreach ($emailList['data'] as $keys){

	$password = $lib->generatePassword();
 //	echo "\n password generated : ".$password;
 /* encrypted*/
	// $encrypted=$lib->encryptPassword($password,$salt);
	/*plain text */
	$encrypted=$password;
	$sqlData->insertNewUserPassword($keys->contact_num,$encrypted,$keys->prospection,$keys->language_code,$salt);
}

exit;



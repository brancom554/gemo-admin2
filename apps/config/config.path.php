<?php
if(!isset($_SERVER[ 'APPLICATION_ENV' ])) DEFINE('_INSTANCE', 'DEV'); // Local Test
else DEFINE('_INSTANCE', $_SERVER[ 'APPLICATION_ENV' ]);
//   echo "start config.path -> server instance :  ".$_SERVER[ 'APPLICATION_ENV' ];
// echo "start config.path -> apps instance : ".$_SERVER[ 'APPLICATION_ENV' ]; 
// exit;
// phpinfo();
// exit;
@ini_set("display_errors",0);

/* Compress Headers
 * http://www.gidblog.com/2000/12/compress-your-web-page/
 * */
ob_start( 'ob_gzhandler' );
Header("Expires: " . gmdate("D, d M Y H:i:s", time()) . " GMT");
/*
 * https://secure.kitserve.org.uk/content/php-session-cookie-problems-google-chrome-and-internet-explorer
 */
/*
ini_set('session.use_trans_sid', false);
ini_set('session.use_cookies', true);
ini_set('session.use_only_cookies', true);
$https = false;
if(isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] != 'off') $https = true;
$dirname = rtrim(dirname($_SERVER['PHP_SELF']), '/').'/';
session_name('some_name');
session_set_cookie_params(0, $dirname, $_SERVER['HTTP_HOST'], $https, true);
session_start();
@ini_set("session.name","SID");
*/
@ini_set("track_errors",1);
@ini_set("log_errors",1);

// @ini_set("session.save_handler","files");
@ini_set("allow_url_fopen",1);
// @ini_set("allow_url_include",1);
@ini_set("url_rewriter.tags","");
@ini_set("default_charset",'utf-8');
@session_cache_limiter('private, must-revalidate');
@ini_set("register_globals",0);
@ini_set("error_reporting",E_ALL);
@error_reporting (E_ALL ^ E_NOTICE);
@ini_set("short_open_tag",0);

@ini_set("memory_limit","30M");

@ini_set('max_execution_time', 300); //300 seconds = 5 minutes
set_time_limit(300);

$iniObj = parse_ini_file(_CONFIG_PATH."application.ini", true);

$iniObj = (object) array_merge((array) $iniObj['ALL'], (array) $iniObj[_INSTANCE]);
set_include_path(get_include_path() . PATH_SEPARATOR . _APPS_PATH."/controllers/");

if ($iniObj->errorLog) @ini_set("error_log",_PATH_ROOT."/logs/php_error-".date('d-m-Y').".txt");

/*
 * Reading config.ini for global configuratoin and db
 */
if ($iniObj->displayError==true) @ini_set("display_errors",1);
// ini_set("display_errors",0);

define("_LIB_PATH",_APPS_PATH."/library/");
define("_EXT_LIB_PATH",_APPS_PATH."/externalLibs/");
define("_JS_PATH",_APPS_PATH."/javascript/");
define("_JS_A_PATH",_APPS_PATH."/appsJavaScript/");
define("_CSS_PATH",_APPS_PATH."/css/");
define("_IMG_PATH",_APPS_PATH."/images/");
define("_MODEL_PATH",_APPS_PATH."/model/");
define("_PATH_ROOT_CONFIG",_APPS_PATH."/config/");
define("_LANG_PATH",_APPS_PATH."/language/");
DEFINE("_CONTROLER_PATH",_APPS_PATH."/controllers/");
DEFINE("_VIEW_PATH",_APPS_PATH."/views/");
DEFINE("_PAYLINE_PATH",_LIB_PATH.'payline_1.1.8/');
DEFINE("_PATH_USER_DATA_TMP",_ROOT_FILES."/tempfiles");
define('_WEB_FILES',_ROOT_FILES . '/www/');
define('_WEB_PATH_PDF',_ROOT_FILES . '/vendor');
@ini_set("session.save_path" , _PATH_USER_DATA_TMP.'/sessions');
DEFINE("_HTML_CACHE",_PATH_USER_DATA_TMP."/htmlCache");
DEFINE("_THUMB_PATH",_WEB_FILES."/filesLib/articles_thumbnail/");
DEFINE("_THUMB_PATH_NEW",_WEB_FILES."/filesLib/articles_img/");

//Définition des paramètre du Server SMTP
DEFINE("_USERNAME_SMTP","dkotest2021@gmail.com");
DEFINE("_PASSWORD_SMTP","2021Dko@1");
DEFINE("_HOST_SMTP","smtp.gmail.com");
DEFINE("_PORT_SMTP",465);
DEFINE("_Mail_SMTP","hafizarouna@gmail.com");


/* handle the URL submited by the browser */
$url_array = explode("/", $_SERVER['REQUEST_URI']) ;
//  var_dump($url_array);
//  die;
/*
* Define the lang list
*/
$langList=explode(",", $iniObj->activeLang);


if($url_array[1]=='index.php'){
	/* removing index.php from the array URL */
	unset($url_array[1]); $url_array=array_values($url_array);
}
else if($url_array[1]=='lang'){
	$pages_requested=$url_array[1];
	$lang_requested=$page=$url_array[2];
}
else {
	// var_dump($url_array);
	// die();
	$lang_requested=$page=$url_array[1];
	/*
	 * Ensure that the lang is part of the exiting list already set
	 */
	if(!in_array($lang_requested,array_values($langList))) $lang_requested='';
}

if(!trim($lang_requested)) $lang_requested=$iniObj->defaultLang;


// @ini_set("display_errors",0);
require_once(_LIB_PATH."common_lib.class.php");
require_once(_LIB_PATH."pagination.class.php");
$lib = new CommonLib();
$lib->lang=$_lang=$lang_requested;
require(_LIB_PATH."library.session.php");

require(_LIB_PATH."lib.translation.php");

// Setting time zone
date_default_timezone_set("Europe/Paris");

require_once(_MODEL_PATH."classDbPdo_new.php"); //pdo

require(_MODEL_PATH."SqlDataLib.class.php");

$lang=new Translation();
$_db=new sql($iniObj);
// print_r ($_db);
$sqlData=new sqlDataLib($iniObj);



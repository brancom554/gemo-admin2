<?php
// echo "<br> lib session lang requ ==> ".$lang_requested;


/* ===========================================================
This library defines security access to this application
IF session has not been defined, NO access to the application

==============================================================*/
/* check if USER AGENT is a search engine spider*/
function Visitor_is_spider() {
	$is_spider=false;
     $spider_list =
     array(
           "a_archiver",
           "adnettrack",
           "almaden",
           "antibot",
           "Ask Jeeves",
           "fast-search",
           "gigabot",
           "Googlebot",
           "grub",
           "ibm.com",
           "iliad",
           "inktomi",
           "internetseer",
           "lwp-trivial",
           "Mediapartners",
           "msnbot",
           "naverbot",
           "navi-killou",
           "netresearch",
           "nutchorg",
           "openfind",
           "psbot",
           "QuepasaCreep",
           "relevare",
           "Slurp",
           "Scooter",
           "Slurp/cat",
           "szukacz",
           "turnitin",
           "webcrawler",
           "wget",
           "wisenutbot",
           "Yahoo!"
           );
     $visitor_is_spider = 0;
     foreach($spider_list as $Val) {
         if (eregi($Val, $_SERVER["HTTP_USER_AGENT"])) {
             $is_spider=true;
             break;
         }
     }
     return $is_spider;
}

/*
 * https://secure.kitserve.org.uk/content/php-session-cookie-problems-google-chrome-and-internet-explorer
 */

/*
// if(!Visitor_is_spider()){
$https = false;
if(isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] != 'off') $https = true;
$dirname = rtrim(dirname($_SERVER['PHP_SELF']), '/').'/';
session_name('MyTransporteo');
// echo "session dration =? ".$iniObj->sessionExpires;
session_set_cookie_params(0, $dirname, $_SERVER['HTTP_HOST'], $https, true);
// session_set_cookie_params($iniObj->sessionExpires, $dirname, $_SERVER['HTTP_HOST'], $https, true);
*/
//$https = false;
//if(isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] != 'off') $https = true;
//$dirname = rtrim(dirname($_SERVER['PHP_SELF']), '/').'/';
//session_name($iniObj->sessionName);
//session_set_cookie_params($iniObj->sessionExpires, $dirname, $_SERVER['HTTP_HOST'], $https, true);



@ini_set("session.save_handler","files");

ini_set('session.use_trans_sid', false);
ini_set('session.use_cookies', true);
ini_set('session.use_only_cookies', true);
$https = false;
if(isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] != 'off') $https = true;
$dirname = rtrim(dirname($_SERVER['PHP_SELF']), '/').'/';
session_name($iniObj->sessionName);
session_set_cookie_params(0, $dirname, $_SERVER['HTTP_HOST'], $https, true);
session_cache_limiter('private');
session_cache_expire($iniObj->sessionTimeOut);

session_start();

if (!isset($SID)){
      // session_destroy();
      session_start();
      /*
       * IE issue
       * this line to be added according to http://www.commentcamarche.net/forum/affich-2456784-php-gestion-des-sessions-avec-ie

      header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
      */
      header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
      $SID=session_id();
      $SID_STR = "SID/".$SID;
      $_SESSION['SESSION_TIMEOUT']=$iniObj->sessionTimeOut;
      if(!trim($_SESSION['LANG']))
      	$_SESSION['LANG']= substr(strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"]), 0, 2);
      elseif(!trim($_SESSION['LANG'])) $_SESSION['LANG']='fr';
  }
  else{
  	session_start();
//  	header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
    $SID=session_id();
    $SID_STR = "SID/".$SID;
     #Define here the maximum time a visitor can be in a idle mode (no activity)
     #Defautl time : 1200 seconds (20 Minutes)
     $_SESSION['SESSION_TIMEOUT']=$iniObj->sessionTimeOut;
//       $_SESSION['SESSION_TIMEOUT']=12;
     if (isset($_SESSION['LOGIN']) && $_SESSION['LOGIN'] == 1){
        if (
            isset($_SESSION['LAST_TIME_ACTION'])
            &&
            (
              ($_SESSION['LAST_TIME_ACTION'] + $_SESSION['SESSION_TIMEOUT']) < time()
            )
          )
          $_SESSION['TIMEOUT'] = 1;
        else unset($_SESSION['TIMEOUT']);
     }
     $_SESSION['LAST_TIME_ACTION'] = time();
  }
?>
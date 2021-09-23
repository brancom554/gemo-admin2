<?php
if(!isset($pages_requested) ) $pages_requested=$page=$url_array[1];



//  $lib->debug($pages_requested);
// echo "_page_controller";
// $lib->debug($url_array);

$param1=$pages = explode("_", $pages_requested) ;
$page_requested=$pages[0];
$subPage = $pages[1];
$subPage2 = $pages[2];
$param2=$subPages = explode("_", urldecode($url_array[2])) ;
$param3=$subPages2 = explode("_", urldecode($url_array[3])) ;
$param4=$subPages3 = explode("_", urldecode($url_array[4])) ;


if(isset($email)) $_POST['email']=stripslashes($email);

$crtl=_CONTROLER_PATH."forms/"; // shortening the path to include some files
// var_dump($page_requested);
// die();


include _APPS_PATH."/appsController/defaultController.php";

$random = $lib->random(); // Generate a random number to be added to url for customer logged in (to prevent browser to cache html data

include _VIEW_PATH.$view;
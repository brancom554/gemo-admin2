<?php
/*
 * Redirect user on the home page if php page is accessed directly (not from the home page)
 */

$lang=urldecode($url_array[3]);
switch ($lang){
	case "fr": $lib->lang=$_SESSION['LANG']=$lang;break;
	case "en": $lib->lang=$_SESSION['LANG']=$lang;break;
	case "de": $lib->lang=$_SESSION['LANG']=$lang;break;
	case "pt": $lib->lang=$_SESSION['LANG']=$lang;break;
}

$previous = explode("/", $_SERVER['HTTP_REFERER']) ;


$newUrl=$previous[0].'//'.$previous[2].'/'.$lang.'/'.$previous[4];
if (isset($previous[5])) $newUrl.='/'.$previous[5];
Header("Location: ".$newUrl);

/* redirecting to previous page  in case header function above did not work*/
 echo '<HEAD><META HTTP-EQUIV="Refresh" CONTENT="0;url='.$newUrl.'"></HEAD>';
exit;
?>
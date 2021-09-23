<?php
/*
 * Redirect user on the home page if php page is accessed directly (not from the home page)
*/

/*
 * image size
*  => mini
*  => medium
*  => full
*/

// $lib->debug($url_array);
// exit;
DEFINE("_IMAGE_WIDTH_MINI",$iniObj->imageMiniWidth);
DEFINE("_IMAGE_WIDTH_MAXI",$iniObj->imageMaxWidth);
DEFINE("_IMAGE_WIDTH_MEDIUM",$iniObj->imageMediumWidth);

@ini_set("display_errors",$iniObj->debug);
require (_LIB_PATH."imageEditor.class.php");

$sourcePath=_WEB_FILES.$iniObj->imgPath;
$thumbPath = _WEB_FILES.$iniObj->thumbPath;
$id=urldecode($url_array[4]);
$type=urldecode($url_array[2]); // Article / magasin...

$size=urldecode($url_array[3]); // mini / medium / full
$imgName=$id;
if($size=='full'){
	$finalName =$imgName;

}
else{
	$finalName =$size."_".$id;
	$destinationPath=$thumbPath;
}

if(file_exists($sourcePath.$imgName) && $size=='full'){
	$image=new imageEditor($sourcePath,$thumbPath,$imgName,$id,'full',$source);
	$image->displayImage();
}
else{
	//Retrieve an image for the current artist
//	$info = $sqlData->getMediaDetail($id);
//	$image_name=$info['data'][0]->file_name;
//	$pseudo=$info['data'][0]->pseudo;
//	preg_match("|\.([a-z0-9]{2,4})$|i",$info['data'][0]->file_name, $m);    # Get File extension for a better match
	//$imgExt=strtolower($m[1]);
	$filePath=$path;
	$image=new imageEditor($sourcePath,$thumbPath,$imgName,$id,$size,$source);
	$image->displayImage();
}
?>
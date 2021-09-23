<?php
########################################################
# Script Info
# ===========
# File: ImageEditor.php
# Created: 05/06/03
# Modified: 16/05/04
# Author: Ash Young (ash@evoluted.net)
# Website: http://evoluted.net/php/image-editor.htm
# Requirements: PHP with the GD Library
#
# Description
# ===========
# This class allows you to edit an image easily and
# quickly via php.
#
# If you have any functions that you like to see
# implemented in this script then please just send
# an email to ash@evoluted.net
#
# Limitations
# ===========
# - GIF Editing: this script will only edit gif files
#     your GD library allows this.
#
# Image Editing Functions
# =======================
# resize(int width, int height)
#    resizes the image to proportions specified.
#
# crop(int x, int y, int width, int height)
#    crops the image starting at (x, y) into a rectangle
#    width wide and height high.
#
# addText(String str, int x, int y, Array color)
#    adds the string str to the image at position (x, y)
#    using the colour given in the Array color which
#    represents colour in RGB mode.
#
# addLine(int x1, int y1, int x2, int y2, Array color)
#    adds the line starting at (x1,y1) ending at (x2,y2)
#    using the colour given in the Array color which
#    represents colour in RGB mode.
#
# setSize(int size)
#    sets the size of the font to be used with addText()
#
# setFont(String font)
#    sets the font for use with the addText function. This
#    should be an absolute path to a true type font
#
# shadowText(String str, int x, int y, Array color1, Array color2, int shadowoffset)
#    creates show text, using the font specified by set font.
#    adds the string str to the image at position (x, y)
#    using the colour given in the Array color which
#    represents colour in RGB mode.
#
# Useage
# ======
# First you are required to include this file into your
# php script and then to create a new instance of the
# class, giving it the path and the filename of the
# image that you wish to edit. Like so:
#
# include("ImageEditor.php");
# $imageEditor = new ImageEditor("filename.jpg", "directoryfileisin/");
#
# After you have done this you will be able to edit the
# image easily and quickly. You do this by calling a
# function to act upon the image. See below for function
# definitions and descriptions see above. An example
# would be:
#
# $imageEditor->resize(400, 300);
#
# This would resize our imported image to 400 pixels by
# 300 pixels. To then export the edited image there are
# two choices, out put to file and to display as an image.
# If you are displaying as an image however it is assumed
# that this file will be viewed as an image rather than
# as a webpage. The first line below saves to file, the
# second displays the image.
#
# $imageEditor->outputFile("filenametosaveto.jpg", "directorytosavein/");
#
# $imageEditor->outputImage();
########################################################

class imageEditor {

  var $x;
  var $y;
  var $type;
  var $img;
  var $font;
  var $error;
  var $size;
  var $fileName;
  var $filePath;
  var $fileID;
  var $fullPath;
  var $viewSize;
  var $imageExt;
  var $imageWidth;
  var $imageHeight;
  var $imageRatio;
  var $imgNameMini;
  var $imgNameMedium;
  var $imageHeaderType;

  # CONSTRUCTOR
  function ImageEditor($path,$destPath,$filename,$fileId, $size, $col=NULL){
    $this->font = false;
    $this->error = false;
    $this->size = 25;
    $this->viewSize= $size;
    $this->fileName=$filename;
   //  echo "<br>file name = ".$filename;
    $this->fileID=$fileId;
    $this->sourcePath=$path;
    $this->destinationPath=$destPath;

    $this->fullPath=$this->sourcePath.$this->fileName;
    $this->imageExtension();
    $this->imageInfo();
    $this->imgNameMini="mini_".$this->fileID.".".$this->imageType;
    $this->imgNameMedium="medium_".$this->fileID.".".$this->imageType;

    $this->imgNameMaxi="maxi_".$this->fileID.".".$this->imageType;

    if(is_numeric($filename) && is_numeric($this->sourcePath)){
   	  ## IF NO IMAGE SPECIFIED CREATE BLANK IMAGE
      $this->x = $filename;
      $this->y = $this->sourcePath;
      $this->imageExt = "jpg";
      $this->img = imagecreatetruecolor($this->x, $this->y);
      if(is_array($col)){
      	## SET BACKGROUND COLOUR OF IMAGE
        $colour = ImageColorAllocate($this->img, $col[0], $col[1], $col[2]);
        ImageFill($this->img, 0, 0, $colour);
      }
    }
    else{
    	## IMAGE SPECIFIED SO LOAD THIS IMAGE
      ## FIRST SEE IF WE CAN FIND IMAGE
      if(file_exists($this->sourcePath . $filename)){
        $file = $this->sourcePath . $filename;
      }
      else if (file_exists($this->sourcePath . "/" . $filename)){
        $file = $this->sourcePath . "/" . $filename;
      }
      else{
        $this->errorImage("File Could Not Be Loaded");
      }

      if(!($this->error)){
        ## LOAD OUR IMAGE WITH CORRECT FUNCTION
//        $this->type = strtolower(end(explode('.', $filename)));
        if ($this->imageExt == 'jpg' || $this->imageExt == 'jpeg'){
         $this->img = @imagecreatefromjpeg($file);
         // $this->img = imagecreatefromjpeg($file);
        }
        else if ($this->imageExt == 'png'){
          $this->img = @imagecreatefrompng($file);
        }
        else if ($this->imageExt == 'gif'){
          $this->img = @imagecreatefromgif($file);
        }
        ## SET OUR IMAGE VARIABLES
        $this->x = imagesx($this->img);
        $this->y = imagesy($this->img);
      }
    }
  }

  # Define the IMAGE Extention
  function imageExtension(){
    /* Return the extension and name of the current image -> GIF / JPEG / PNG / BMP .... */
    $name = explode('.', $this->fileName);
    $this->imageExt = strtolower($name[count($name)-1]);
    $this->image_name=$name[0];
    switch($this->imageExt){
      case 'jpeg':  $this->imageType = 'jpg'; break;
      default :     $this->imageType = $this->imageExt;  break;
    }
    // echo "img extneions : ".$this->imageType;
  }

  # retrieve Image information
  function imageInfo(){
    /* Return the extension and name of the current image -> GIF / JPEG / PNG / BMP .... */
    $img=getimagesize($this->fullPath);
    // echo "<br>";
//		print_r($img);
		$this->imageWidth=$img[0];
    $this->imageHeight=$img[1];
    $this->imageRatio = $this->imageWidth / $this->imageHeight;
    $this->imageHeaderType=$img['mime'];
  }
  # RESIZE IMAGE GIVEN X AND Y
  function resize($width, $height){
    if(!$this->error){
      $tmpimage = imagecreatetruecolor($width, $height);
      imagecopyresampled($tmpimage, $this->img, 0, 0, 0, 0,
                           $width, $height, $this->x, $this->y);
      imagedestroy($this->img);
      $this->img = $tmpimage;
      $this->y = $height;
      $this->x = $width;
    }
  }

  # RESIZE IMAGE GIVEN X AND Y
  function resizeRatio($width=0,$height=0,$name){
//    if($width>0 && $width>0) $width=0,$height=0
// echo "<br>file  name resize ratio: ".$name;
//    if ($this->imageWidth > $width)
    if($width==0) $width=$height*$this->imageRatio;
    else if($height==0) $height= $width/$this->imageRatio;
//    else exit;
 //   echo "<br>width=$width / height=$height /   new naem => $name / full path = $this->fullPath";
    //exit;
    switch ($this->imageExt){
      case 'jpg': $tempImg = imagecreatefromjpeg($this->fullPath); break;
      case 'gif': $tempImg = @imagecreatefromgif($this->fullPath); break;
      case 'png': $tempImg = @imagecreatefrompng($this->fullPath); break;
      default: echo '\nError: Unknown image format.';  exit(); break;
    }
 //    echo "\n<br>\ntemp img file = ".$tempImg;
    if ($tempImg){
            $thumb = imagecreatetruecolor($width, $height);  # Allows to display better colors
        if ($thumb){
          /* I prefer the function imagecopyresampled, it produces better quality images,
            Just uncomment imagecopyresized() and comment imagecopyresampled() if you prefer using this imagecopyresized()*/
          # imagecopyresized($out, $temp_img_file, 0, 0, 0, 0, $this->thumbnail_width, $this->thumbnail_height, $this->image_source_width, $this->image_source_height);
          @imagecopyresampled($thumb,
                              $tempImg, 0, 0, 0, 0,
                              $width,
                              $height,
                              $this->imageWidth,
                              $this->imageHeight
                             );
         $this->outputFile($thumb, $this->destinationPath.$name);
 //         $this->outputFile($thumb, $this->destinationPath.$name);

          @imagedestroy($thumb);
        }
        else{ echo '\nCould not create thumbnail';  exit; }
      @imagedestroy($tempImg);
    }
    else{ echo '\nCould not create image resource.';  exit; }
  }

  # CROPS THE IMAGE, GIVE A START CO-ORDINATE AND LENGTH AND HEIGHT ATTRIBUTES
  function crop($x, $y, $width, $height){
    if(!$this->error){
      $tmpimage = imagecreatetruecolor($width, $height);
      imagecopyresampled($tmpimage, $this->img, 0, 0, $x, $y,
                           $width, $height, $width, $height);
      imagedestroy($this->img);
      $this->img = $tmpimage;
      $this->y = $height;
      $this->x = $width;
    }
  }

  # ADDS TEXT TO AN IMAGE, TAKES THE STRING, A STARTING POINT, PLUS A COLOR DEFINITION AS AN ARRAY IN RGB MODE
  function addText($str, $x, $y, $col){
    if(!$this->error){
      if($this->font) {
        $colour = ImageColorAllocate($this->img, $col[0], $col[1], $col[2]);
        if(!imagettftext($this->img, $this->size, 0, $x, $y, $colour, $this->font, $str)) {
          $this->font = false;
          $this->errorImage("Error Drawing Text");
        }
      }
      else {
        $colour = ImageColorAllocate($this->img, $col[0], $col[1], $col[2]);
        Imagestring($this->img, 5, $x, $y, $str, $colour);
      }
    }
  }

  function shadowText($str, $x, $y, $col1, $col2, $offset=2) {
   $this->addText($str, $x, $y, $col1);
   $this->addText($str, $x-$offset, $y-$offset, $col2);

  }

  # ADDS A LINE TO AN IMAGE, TAKES A STARTING AND AN END POINT, PLUS A COLOR DEFINITION AS AN ARRAY IN RGB MODE
  function addLine($x1, $y1, $x2, $y2, $col){
    if(!$this->error){
      $colour = ImageColorAllocate($this->img, $col[0], $col[1], $col[2]);
      ImageLine($this->img, $x1, $y1, $x2, $y2, $colour);
    }
  }

  # RETURN OUR EDITED FILE AS AN IMAGE
  function outputImage($img){
  	$content = $this->readImage($img);

  	if($content){
       header("Content-Type: ".$this->imageHeaderType." ");
       header("Content-Disposition: inline");
       echo $content;
  	}
  }

  # CREATE OUR EDITED FILE ON THE SERVER
  function outputFile($file, $path){
 // 	echo "<br>class ->path => $path";
    switch (strtolower($this->imageExt)){
    	case 'jpg': imagejpeg($file,$path);  break;
      case 'gif': imagegif($file,$path);  break;
      case 'png': imagepng($file,$path);  break;
    }
  }

  # SET OUTPUT TYPE IN ORDER TO SAVE IN DIFFERENT TYPE THAN WE LOADED
  function setImageType($type){
    $this->imageExt = $type;
  }

  # ADDS TEXT TO AN IMAGE, TAKES THE STRING, A STARTING POINT, PLUS A COLOR DEFINITION AS AN ARRAY IN RGB MODE
  function setFont($font) {
    $this->font = $font;
  }

  # SETS THE FONT SIZE
  function setSize($size) {
    $this->size = $size;
  }

  # GET VARIABLE FUNCTIONS
  function getWidth()                {return $this->x;}
  function getHeight()               {return $this->y;}
  function getImageType()            {return $this->imageExt;}

  # CREATES AN ERROR IMAGE SO A PROPER OBJECT IS RETURNED
  function errorImage($str){
    $this->error = false;
    $this->x = 235;
    $this->y = 50;
    $this->imageExt = "jpg";
    $this->img = imagecreatetruecolor($this->x, $this->y);
    $this->addText("AN ERROR OCCURED:", 10, 5, array(250,70,0));
    $this->addText($str, 10, 30, array(255,255,255));
    $this->error = true;
  }

  # Display the immage based on the size requested
  function displayImage(){
  		switch($this->viewSize){
			case "full": $this->outputImage($this->sourcePath.$this->fileName); break;

			case "maxi":
        if(!file_exists($this->destinationPath.$this->imgNameMaxi)){
          $this->resizeRatio(_IMAGE_WIDTH_MAXI,0,$this->imgNameMaxi);
        }
        $this->outputImage($this->destinationPath.$this->imgNameMaxi);

        break;
		case "mini":
				if(!file_exists($this->destinationPath.$this->imgNameMini)){
					$this->resizeRatio(_IMAGE_WIDTH_MINI,0,$this->imgNameMini);
				}
				$this->outputImage($this->destinationPath.$this->imgNameMini);
			break;
			case "medium":
				if(!file_exists($this->destinationPath.$this->imgNameMedium)){
					$this->resizeRatio(_IMAGE_WIDTH_MEDIUM,0,$this->imgNameMedium);
				}
				$this->outputImage($this->destinationPath.$this->imgNameMedium);
				break;
		}
  }

  function readImage($img){
  /* Return only the real image for this given path*/
    if($fp=fopen($img,"r")){
       $content = fread ($fp, filesize($img));
       fclose($fp);
       return($content);
    }else{
       echo '\nCould not open image while accessing to readImage()';exit;
    }
  }
}
?>

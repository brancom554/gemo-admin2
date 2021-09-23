<?php
/*
 * Redirect user on the home page if php page is accessed directly (not from the home page)
 */

// require_once(_PATH_ROOT.'config/xajax/xajax.inc.php');
class XmlClass {
	var $lib;
	var $db;

    function __construct() {
        global $db,$lib;
        $this->db = $db;
        $this->lib = $lib;
    }

    /*
     * format data and retrieve in an array format
     */
    function fetchData($query){
      $result=$this->db->query($query);
      $myArray['rows']=$this->db->nbrecord();
      while ($line = $this->db->fetchNextObject($result)) {
        $myArray['data'][]= $line;
      }
      return($myArray);
    }
    /* source http://www.ibm.com/developerworks/library/os-php-unicode/index.html */
	function utf8_to_unicode_code($utf8_string){
		$expanded = iconv("UTF-8", "UTF-32", $utf8_string);
        return unpack("L*", $expanded);
    }

	function unicode_code_to_utf8($unicode_list){
		$result = "";
        foreach($unicode_list as $key => $value) {
            $one_character = pack("L", $value);
            $result .= iconv("UTF-32", "UTF-8", $one_character);
        }
        return $result;
    }
    /*
     * Retrieve data from DB
     */
    function dataToSelectForm($input,$nodeName='items'){
		$output.= $this->dataToXml($input, "data list", "item",$nodeName);
		return($output);
    }

    function dataToXmlList($input,$nodeName='items'){
		$output.= $this->dataToXml($input, '', "item",$nodeName);
		return($output);
    }


    function dataToXml($inputArray,$title, $itemNames,$nodeName){
//						$doc = new DOMDocument ('1.0', 'ISO-8859-1');
						$doc = new DOMDocument ('1.0', 'UTF-8');
			// create root-element
			$root = $doc->createElement('data');
			$root = $doc->appendChild($root);
			$doc->preserveWhiteSpace = true;
			if(trim($title)){
				$opt = $root->appendChild($doc->createElement("title"));
				$elId = $opt->appendChild($doc->createTextNode($title));
			}
			$optList = $root->appendChild($doc->createElement($nodeName));
			if($inputArray['rows']>0){
			foreach($inputArray[data] as $key=>$value){
				// attach a new option to the root element
			  $opt = $optList->appendChild($doc->createElement($itemNames));
				foreach($value as $title=>$content){
				  // create id element, attach it to the option element
					$elId = $opt->appendChild($doc->createElement(utf8_encode($title)));

				  // add textnode to it, containing the model's id
				  $elId->appendChild($doc->createTextNode(utf8_encode($content)));
				}
			}
			}
			return($doc->saveXML());
    }

    function dataToXmlPlaylist($inputArray,$itemNames){
		$doc = new DOMDocument ('1.0', 'ISO-8859-1');
//			$doc = new DOMDocument ('1.0', 'UTF-8');
		$root = $doc->createElement('playlist');
		$doc->preserveWhiteSpace = true;
		$root = $doc->appendChild($root);
		$root->setAttribute('version', '0');
		$root->setAttribute('xmlns', 'http://xspf.org/ns/0/');

		$optList = $root->appendChild($doc->createElement("trackList"));
		foreach($inputArray['data'] as $key=>$value){
		  $opt = $optList->appendChild($doc->createElement($itemNames));
		  foreach($value as $title=>$content){
			  // create id element, attach it to the option element
			  $elId = $opt->appendChild($doc->createElement(utf8_encode($title)));
			  // add textnode to it, containing the model's id
			  $elId->appendChild($doc->createTextNode(utf8_encode($content)));
			}
		}
		return($doc->saveXML());
    }

    function inputToXml($inputArray){
	//		$doc = new DOMDocument ('1.0', 'ISO-8859-1');
		$doc = new DOMDocument ('1.0', 'UTF-8');

		// create root-element
		$root = $doc->createElement('data');
		$root = $doc->appendChild($root);
		$doc->preserveWhiteSpace = true;
		foreach($inputArray as $keyData=>$valueData){
			if($valueData['rows']>0){
				$optList = $root->appendChild($doc->createElement($keyData)); //Node name
				foreach($valueData[data] as $key=>$value){
				  $opt = $optList->appendChild($doc->createElement('item'));
					foreach($value as $title=>$content){
					  // create id element, attach it to the option element
//						  $elId = $opt->appendChild($doc->createElement(utf8_encode($title)));
					  $elId = $opt->appendChild($doc->createElement(utf8_encode($title)));
					  // add textnode to it, containing the model's id
//						  $elId->appendChild($doc->createTextNode(utf8_encode($content)));
					  $elId->appendChild($doc->createTextNode(utf8_encode($content)));
					}
				}
			}
		}
		return($doc->saveXML());
    }

    function dataToXmlConfig($inputArray){
//			$doc = new DOMDocument ('1.0', 'ISO-8859-1');
			$doc = new DOMDocument ('1.0', 'UTF-8');
			$root = $doc->createElement('config');
			$root = $doc->appendChild($root);
			$doc->preserveWhiteSpace = true;
			foreach($inputArray as $key=>$value){
				$elId = $root->appendChild($doc->createElement($key));
				$elId->appendChild($doc->createTextNode($value));
			}
			return($doc->saveXML());
    }


}


?>
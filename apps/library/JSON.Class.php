<?php
/*
 * Source : http://www.bin-co.com/php/scripts/array2json/
 * see http://old.nabble.com/A-different-approach-to-parsing-XML%2C-and-a-little-help-on--processing-attributes-more-efficiently-to27360720s27240.html#a27360720
 */

class JsonConverter {
	var $lib;
	var $db;

    function __construct() {
        global $db,$lib;
        $this->db = $db;
        $this->lib = $lib;
    }


    function array2json($arr) {
    if(function_exists('json_encode')) return json_encode($arr); //Lastest versions of PHP already has this functionality.
    $parts = array();
    $is_list = false;

    //Find out if the given array is a numerical array
    $keys = array_keys($arr);
    $max_length = count($arr)-1;
    if(($keys[0] == 0) and ($keys[$max_length] == $max_length)) {//See if the first key is 0 and last key is length - 1
        $is_list = true;
        for($i=0; $i<count($keys); $i++) { //See if each key correspondes to its position
            if($i != $keys[$i]) { //A key fails at position check.
                $is_list = false; //It is an associative array.
                break;
            }
        }
    }

    foreach($arr as $key=>$value) {
        if(is_array($value)) { //Custom handling for arrays
            if($is_list) $parts[] = array2json($value); /* :RECURSION: */
            else $parts[] = '"' . $key . '":' . array2json($value); /* :RECURSION: */
        } else {
            $str = '';
            if(!$is_list) $str = '"' . $key . '":';

            //Custom handling for multiple data types
            if(is_numeric($value)) $str .= $value; //Numbers
            elseif($value === false) $str .= 'false'; //The booleans
            elseif($value === true) $str .= 'true';
//            else $str .= '"' . addslashes(utf8_encode($value)) . '"'; //All other things
            else $str .= '"' . addslashes($value) . '"'; //All other things
            // :TODO: Is there any more datatype we should be in the lookout for? (Object?)

            $parts[] = $str;
        }
    }
    $json = implode(',',$parts);

    if($is_list) return '[' . $json . ']';//Return numerical JSON
    return '{' . $json . '}';//Return associative JSON
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

}



?>
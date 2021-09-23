<?php
class Translation{
	var $data;
	var $lang;
	var $langPath;
    function __construct(){
      global $iniObj,$lib;
      $this->db = $db;$this->sqlData=$sqlData;
      //$this->lang = $_SESSION['LANG'];
			$this->lib=$lib;
			$this->lang = $this->lib->lang;
		//	echo "<br> ths lang =>".$this->lang;
			if (($this->lang!='en' && $this->lang!='fr' && $this->lang!='pt' && $this->lang!='de') || !trim($this->lang)) $this->lang='fr';
			$this->langPath= _LANG_PATH.$this->lang.".csv";
			$this->loadCsvData($this->langPath,$this->lang,';');
    }

    protected function loadCsvData($filename, $locale,$separator)
    {
        $this->_file = @fopen($filename, 'rb');
        while(!feof($this->_file)) {
            $content = fgets($this->_file);
            $content = explode($separator, $content);

            for ($x = 0; $x < count($content); ++$x) {
                if (isset($content[$x+1]) and (empty($content[$x+1]))) {
                    $content[$x] .= $separator;
                    $length = 1;
                    if (isset($content[$x+2])) {
                        $content[$x] .= $content[$x+2];
                        $length = 2;
                    }
                    array_splice($content, $x + 1, $length);
                }

            }
            // # marks a comment in the translation source
            if ((!is_array($content) and (substr(trim($content), 0, 1) == "#")) or
                 (is_array($content) and (substr(trim($content[0]), 0, 1) == "#"))) {
                continue;
            }
            if (!empty($content[1])) {

                if (feof($this->_file)) {
                } else {
                    if (substr($content[1], -2, 2) == "\r\n") {
                        $this->data[$locale][$content[0]] = substr($content[1], 0, -2);
                    } else {
                        $this->data[$locale][$content[0]] = substr($content[1], 0, -1);
                    }
                }
            }
        }
    }
    function loadIniFile($path){
    	$this->data = array();
		$values = parse_ini_file($path);
		foreach($values as $key => $val)
		{
    		$this->data[$key] = $val;
		}
    }

    function loadCsvFile($path){

 //   	echo "<br>load file =>".$this->langPath;
    	$this->data = array();

		$values = parse_ini_file($path);

	 	$this->lib->debug($values);
		foreach($values as $key => $val)
		{
    		$this->data[$key] = $val;
    		//  echo "<br>key => ".$key." / val => ".$val;
		}
    }

    function trl($input){
    	if(isset($this->data[$this->lang][$input])) return($this->data[$this->lang][$input]);
    	else return($input);

    }

}





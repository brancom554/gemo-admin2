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

$importFolder = _ROOT_FILES . $iniObj->importFolder;
$errorFolder = _ROOT_FILES . $iniObj->errorFolder;
$backupFolder = _ROOT_FILES . $iniObj->backupFolder ;

$directoryName = "RepTypeSousFamille";

$csvFileName = "Type_sous_familles_articles.csv";
$table_name = 'product_sub_categories';

$filePath = $importFolder.$directoryName."/".$csvFileName;

if($iniObj->debugImport==true){
	echo "import file name =".$filePath;
}

if(file_exists($filePath)){

	$file_processed = false;
	$errorFlag = false;
	$fileContent = file($filePath);
	if($iniObj->debugImport==true){
	 $lib->debug($fileContent);
	}
	if(is_array($fileContent) && count($fileContent)){
		$count 			= count($fileContent);
		$tableFieldNames 	= $fileContent[0];

		$fieldNamesFound	 = explode(';', $fileContent[0]);
		$comma_fields="";
		$headerIncorrect = false;
		if(is_array($fieldNamesFound) && count($fieldNamesFound)) {
			$count_exploded = count($fieldNamesFound);
			$sqlHeaders = " REPLACE DELAYED INTO $table_name ( ";

			$header0 = 'libelle';
			$header1 = 'famille_code';
			$header2 = 'sous_famille_code';
			for($k=0; $k<$count_exploded; $k++) {
				if($k==0 &&  trim($fieldNamesFound[$k])!=$header0) {
					$headerIncorrect=true;
					if($iniObj->debugImport==true){
						echo "\nheader position : $k"." / header found : ".$fieldNamesFound[$k]." / expected : ".$header0;
					}
				}
				if($k==1 &&  trim($fieldNamesFound[$k])!=$header1  ) {
					$headerIncorrect=true;
					if($iniObj->debugImport==true){
						echo "\nheader position : $k"." / header found : ".$fieldNamesFound[$k]." / expected : ".$header1;
					}
				}
				if($k==2 &&  trim($fieldNamesFound[$k])!=$header2  ) {
					$headerIncorrect=true;
					if($iniObj->debugImport==true){
						echo "\nheader position : $k"." / header found : ".$fieldNamesFound[$k]." / expected : ".$header2;
					}
				}
				$sqlHeaders .= " $comma_fields ".trim($fieldNamesFound[$k])." ";
				$comma_fields = ', ';
			}
			$sqlHeaders .= " , last_update_date) VALUES ";

			if($iniObj->debugImport==true){
				echo "\nheaders  : ".$sqlHeaders;
			}
		}

		if($headerIncorrect==true){
			$cron->moveFile ($filePath, $errorFolder, "ERREUR_ENTETE_".$csvFileName);
		}
		else{
			for($i=1; $i< $count; $i++) {
				$content = explode(';', $fileContent[$i]);
				if(is_array($content) && count($content)) {
					$insertSql = '';
					$count_exploded = count($content);
					$insertSql .= " ( ";

					$comma = '';

					for($j=0; $j<$count_exploded; $j++) {
						$last_index = $count_exploded ;
						$comma = ',';
						if(($last_index-1) == $j) {
							$comma = '';
						}
						$str = str_replace("||", "\n<br>", $content[$j]);
						if($iniObj->debugImport==true){
						 echo "\n content : ".$content[$j];
						}
						$insertSql .= " '".addslashes(trim($str))."' $comma ";
					}

					$insertSql .= " ,  NOW() ) ";
					$finalInsert = $sqlHeaders . $insertSql;
					if($iniObj->debugImport==true){
					 echo "\n final insert".$finalInsert;
					}
				}
				if($iniObj->debugImport==true){
					// $sqlData->insertQuery($finalInsert);
					echo "\n final insert".$finalInsert;
				}
				else{
					try {
						$sqlData->insertQuery($finalInsert);
					}
					catch (Exception $e) {
						echo "<br>\n <b>There may be some problem in the data provided in the file " . $final_directory . '/' . $file;
						echo '<br>\n'.$e->getMessage() . '</b><br>\n';
						echo '<br>'.$finalInsert . '<br>';
						$errorFlag = true;
						break;
					}
				}
				if($errorFlag == true){
					$cron->moveFile ($filePath, $errorFolder, "ERREUR_DATA_LIGNE_".$i."_".$csvFileName);
					break;
				}
			}

			$cron->moveFile ($filePath, $backupFolder, "backup_".$csvFileName);
		}
	}
}




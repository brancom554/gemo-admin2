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

$directoryName = "RepVentes";

// $csvFileName = "Type_sous_familles_articles.csv";
$table_name = 'ventes_details';

$header0 = 'vente_article_id';
$header1 = 'contrat_num';
$header2 = 'article_type_code';


$folderPath = $importFolder.$directoryName;
// "/".$csvFileName;

if($iniObj->debugImport==true){
	echo "import folder =".$folderPath;
}

if(is_readable($folderPath) && is_dir($folderPath)){
	$directoryList = opendir($folderPath);
	while($file = readdir($directoryList)) {
		if($file != '.' && $file != '..' ){

			$fileName = $folderPath . "/".$file;
			echo "import file name =".$fileName;
			if($iniObj->debugImport==true){
				echo "import file name =".$fileName;
			}

			if(file_exists($fileName)){

				$file_processed = false;
				$errorFlag = false;
				$fileContent = file($fileName);
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

						for($k=0; $k< ($count_exploded-1); $k++) {
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
//						$sqlHeaders .= " , last_update_date) VALUES ";
						$sqlHeaders .= " ) VALUES ";

						if($iniObj->debugImport==true){
							echo "\nheaders  : ".$sqlHeaders;
						}
					}

					if($headerIncorrect==true){
						$cron->moveFile ($filePath, $errorFolder, "ERREUR_ENTETE_".$fileName);
					}
					else{
						for($i=1; $i< ($count); $i++) {
							$content = explode(';', $fileContent[$i]);
							if(is_array($content) && count($content)) {
								$insertSql = '';
								$count_exploded = count($content);
								$insertSql .= " ( ";

								$comma = '';

								for($j=0; $j< ($count_exploded-1); $j++) {
									$last_index = ($count_exploded-1) ;
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

								// $insertSql .= " ,  NOW() ) ";
								$insertSql .= "  ) ";
								$finalInsert = $sqlHeaders . $insertSql;
								if($iniObj->debugImport==true){
									echo "\n final insert".$finalInsert;
								}
							}
							if($iniObj->debugImport==true){
								 $sqlData->insertQuery($finalInsert);
							//	echo "\n final insert".$finalInsert;
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
								 $cron->moveFile ($folderPath."/".$file, $errorFolder, "ERREUR_DATA_LIGNE_".$i."_".$file);
								break;
							}
						}
						if($iniObj->debugImport==false){
						$cron->moveFile ($folderPath."/".$file, $backupFolder, "backup_".$file);
						}
					}
				}
			}
		}

	}
}


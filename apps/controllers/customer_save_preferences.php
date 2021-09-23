<?php

unset($message);
if (!$_SESSION['customer']['authValidated']) {
	echo "false";
} else {
	foreach ($_REQUEST as $k => $v) {
		// $_POST[$k] = utf8_encode(utf8_decode(urldecode($v)));
		$_REQUEST[$k] = trim(urldecode($v));
		/* handling boolean values for /boolean flag */
		if ($k == "shared") {
			if ($v == "false") $_REQUEST[$k] = 0;
			else $_REQUEST[$k] = 1;
		}
	}
	
	 //$lib->debug($update);
	// var_dump($_REQUEST['prospection']);
	// die;
	if ($sqlData->updatePreferences($_SESSION['customer']['contact_num'],$_REQUEST['lang'],$_REQUEST['prospection'],$_REQUEST['magasin'])) {
		
		$_SESSION['customer']['prefered_lang'] = $_REQUEST['langID'];
		$_SESSION['LANG'] = strtolower($_REQUEST['langID']);
		$_SESSION['customer']['prospection'] = $_REQUEST['prospection'];
		echo "true||" . $lang->trl("Your preferences have been saved");
	} else {
		echo "false||" . $lang->trl("An error occured while saving your record");
	}
}
exit;

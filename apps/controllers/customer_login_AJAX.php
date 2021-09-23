<?php

unset($error);
foreach ($_REQUEST as $k => $v) {
	$_REQUEST[$k] = urldecode(trim($v));
}
// $lib->debug($_REQUEST);

if (empty(trim($_REQUEST['contact_num']))) {
	echo "false||Invallid credentials";
	exit;
}
if (empty($_REQUEST['password'])) {
	echo "false||Invallid credentials";
	exit;
}
// $pass = password_verify(trim($_REQUEST['password']), PASSWORD_ARGON2I);

$data = $sqlData->authenticateUser(trim($_REQUEST['contact_num']));
// var_dump($data);
// die;




//  $lib->debug($data);

if ($iniObj->debugUser == true) {
	// $lib->debug($data);
}
if ($data['rows'] > 0) {
	if ($iniObj->debugUser == true) {
		$_SESSION['customer']['authValidated'] = true;
		foreach ($data['data'][0] as $k => $v) {
			$_COOKIE['customer'][$k] = $_SESSION['customer'][$k] = $v;
		}
		echo "true||" . strtolower($_SESSION['customer']['prefered_lang']);
	} else {

		
		// 		echo " password U =".$data['data'][0]->passwordU;
		// 		echo " password salt =".$data['data'][0]->salt;
		// 		echo "password decrypted : ".$lib->decryptPassword(trim($data['data'][0]->passwordU),trim($data['data'][0]->salt));
		// 		echo  " request password : ".$_REQUEST['password'];

		/* encrypted password*/
		// 		if($lib->decryptPassword($data['data'][0]->passwordU,$data['data'][0]->salt) == $_REQUEST['password']){
		/* plain text password*/
		// if ($data['data'][0]->passwordU == $pass) {


		// 		echo " password U =".$data['data'][0]->passwordU;
		// 		echo " password salt =".$data['data'][0]->salt;
		// 		echo "password decrypted : ".$lib->decryptPassword(trim($data['data'][0]->passwordU),trim($data['data'][0]->salt));
		// 		echo  " request password : ".$_REQUEST['password'];

		/* encrypted password*/
		// 		if($lib->decryptPassword($data['data'][0]->passwordU,$data['data'][0]->salt) == $_REQUEST['password']){
		/* plain text password*/
		// if ($data['data'][0]->passwordU == $_REQUEST['password']) {

		$checkPassword = password_verify($_REQUEST['password'], $data['data'][0]->passwordU);
		// var_dump($checkPassword);
		// die;
		if ($checkPassword) {

			$_SESSION['customer']['authValidated'] = true;
			$_SESSION['SUCCESS'][] = $lang->trl("Welcome to") . " " . $iniObj->serviceName;
			foreach ($data['data'][0] as $k => $v) {
				$_COOKIE['customer'][$k] = $_SESSION['customer'][$k] = $v;
			}
			// var_dump($_SESSION);
			// die;
			$sqlData->updateLoginDate($_SESSION['customer']['contact_num']);
			echo "true||" . strtolower($_SESSION['customer']['prefered_lang']);
			exit;
		} else {
			$_SESSION['customer']['authValidated'] = false;
			echo "false||" . $lang->trl("You could not be authenticated with these credentials");
			exit;
		}
	}
} else {
	echo "false||" . $lang->trl("You could not be authenticated with these credentials. Please contact our customer service");
	exit;
}


exit;

<?php

unset($error);
foreach ($_REQUEST as $k => $v) {
	$_REQUEST[$k] = urldecode(trim($v));
}
// $lib->debug($_REQUEST);


if (
	isset($_REQUEST['civilite']) && isset($_REQUEST['nom_user']) && isset($_REQUEST['password_confirm']) &&
	isset($_REQUEST['prenom_user']) && isset($_REQUEST['phone_number']) &&
	isset($_REQUEST['password']) && isset($_REQUEST['email']) && isset($_REQUEST['profile_user'])
) {

	$civilite = $_REQUEST['civilite'];
	$nom_user = $_REQUEST['nom_user'];
	$prenom_user = $_REQUEST['prenom_user'];
	$phone_number = $_REQUEST['phone_number'];
	$password = $_REQUEST['password'];
	$password_confirm = $_REQUEST['password_confirm'];
	$email = $_REQUEST['email'];
	$profile_user = $_REQUEST['profile_user'];
	// die(var_dump($profile_user));
	//generate contact_num
	$contact_num = 'D' . rand(30, 100);
	$language = 'fr';


	if (
		empty(trim($civilite)) || empty(trim($password)) || empty(trim($password_confirm)) ||
		empty(trim($phone_number)) || empty(trim($prenom_user)) ||
		empty(trim($nom_user))  || empty(trim($email)) || empty(trim($profile_user))
	) {
		echo "false||Invallid credentials";
		exit;
	} elseif ($password != $password_confirm) {
		echo "false||passwords don't match";
	} elseif (!preg_match('/^[A-Z_]+$/', $nom_user) && !preg_match('/^[a-zA-Z_]+$/', $prenom_user)) {
		echo "false||Nom ou prénom invalide";
		exit;
	} else {
		$pass = password_hash($password, PASSWORD_ARGON2I);
		//                die(var_dump($pass));
		$user_exist = $sqlData->getUserByEmail($email);
		if ($user_exist['rows'] != 0) {
			echo "false||this email is already in use";
			exit;
		} else {
			$register_user = $sqlData->insertNewUser($contact_num, $_REQUEST['civilite'], $_REQUEST['nom_user'], $_REQUEST['prenom_user'], $_REQUEST['phone_number'], $_REQUEST['email'], $_REQUEST['profil_user'], $language);
			$user_exist = $sqlData->getUserByEmail($email);
			// die(var_dump($user_exist));
			if ($user_exist['rows'] > 0) {
				foreach ($user_exist['data'] as $user) {
					$register_user_pref = $sqlData->insertNewUserPref($user->contact_num, $pass, $user->language_code);
				}
				// die(var_dump($user_exist));

				if ($register_user_pref) {
					echo "true||" . $lang->trl("Votre compte a été créé avec succès.");
					exit;
				} else {
					echo "false||" . $lang->trl("Une erreur est survenue.");
					exit;
				}
			}
		}
	}
}


exit;

<?php

unset($error);
foreach ($_REQUEST as $k => $v) {
	$_REQUEST[$k] = urldecode(trim($v));
}
// $lib->debug($_REQUEST);

if (empty(trim($_REQUEST['id']))) {
	echo "false||Invallid credentials";
	exit;
}
// $pass = password_verify(trim($_REQUEST['password']), PASSWORD_ARGON2I);

$data = $sqlData->delete_product(trim($_REQUEST['id']));
$lib->debug($data);
// die;

exit;

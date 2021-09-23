<?php

@ini_set("default_charset",'utf-8');
define('DOC_ROOT', DIRNAME(dirname(__FILE__)));

defined('_APPS_PATH')  || define('_APPS_PATH', realpath(dirname(__FILE__) . '/../apps'));
define('_ROOT_FILES',realpath(dirname(__FILE__) . '/../../'));
define("_CONFIG_PATH",_APPS_PATH."/config/");
require(_CONFIG_PATH."config.path.php");



ini_set("memory_limit", "256M");

if(!isset($_SERVER[ 'APPLICATION_ENV' ])) DEFINE('_INSTANCE', 'OVH');
else DEFINE('_INSTANCE', $_SERVER[ 'APPLICATION_ENV' ]);


// echo INSTANCE;
// exit;
set_include_path(
	ZEND_INSTALLED_PATH. PATH_SEPARATOR
	. get_include_path() . PATH_SEPARATOR
);


require_once(APPS_PATH . '/common_functions.php');

DEFINE( 'APP_PATH', _APPS_PATH);


DEFINE(CONFIG_INI_PATH, APPS_PATH . '/configs');

$config = new Zend_Config_Ini(CONFIG_INI_PATH . '/application.ini', INSTANCE);

//create the array of a db parameters
$arr_db = array(
  'host'   => $config->resources->db->params->host,
  'username' => $config->resources->db->params->username,
  'password' => $config->resources->db->params->password,
  'dbname'  => $config->resources->db->params->dbname,
  'charset' => $config->resources->db->params->charset
  );



?>
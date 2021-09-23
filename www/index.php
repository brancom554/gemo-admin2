<?php
@ini_set("display_errors",0);
DEFINE("_PATH_ROOT", realpath(dirname(__FILE__))); // test local
defined('_APPS_PATH')  || define('_APPS_PATH', realpath(dirname(__FILE__) . '/../apps'));
define('_ROOT_FILES',realpath(dirname(__FILE__) . '/../'));
define("_CONFIG_PATH",_APPS_PATH."/config/");

require(_CONFIG_PATH."config.path.php");

if ($iniObj->displayError==true) @ini_set("display_errors",1);
// defined('_APPS_ENV') || define('_APPS_ENV', (getenv('_APPS_ENV') ? getenv('_APPS_ENV') : 'production'));
include (_APPS_PATH."/controllers/_page_controller.php");

if ($iniObj->displayError==true) @ini_set("display_errors",1);
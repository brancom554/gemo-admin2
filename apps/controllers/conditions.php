<?php
 header("cache-Control: no-cache, must-revalidate"); 


if(file_exists(_VIEW_PATH.$lib->lang."/conditions_generales.phtml"))  $view=$lib->lang."/conditions_generales.phtml";
else  $view=$lib->lang."/conditions_generales.phtml";


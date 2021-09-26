<?php
 header("cache-Control: no-cache, must-revalidate"); 


if(file_exists(_VIEW_PATH.$lib->lang."/confidentialite.phtml"))  $view=$lib->lang."/confidentialite.phtml";
else  $view=$lib->lang."/confidentialite.phtml";
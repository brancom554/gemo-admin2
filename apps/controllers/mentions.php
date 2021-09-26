<?php
 header("cache-Control: no-cache, must-revalidate"); 


if(file_exists(_VIEW_PATH.$lib->lang."/mentions_legales.phtml"))  $view=$lib->lang."/mentions_legales.phtml";
else  $view=$lib->lang."/mentions_legales.phtml";


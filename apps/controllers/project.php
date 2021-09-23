<?php

if(file_exists(_VIEW_PATH.$lib->lang."/project.phtml"))  $view=$lib->lang."/project.phtml";
else  $view=$iniObj->defaultLang."/project.phtml";
<?php

if(file_exists(_VIEW_PATH.$lib->lang."/$param2[0].phtml"))  $view=$lib->lang."/$param2[0].phtml";
else  $view=$iniObj->defaultLang."/$param2[0].phtml";

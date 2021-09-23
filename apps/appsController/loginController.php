<?php

    switch ($url_array[2]) {  
        case 'reset':
            include "password_after_login.php";
            break;
        
        default:
            include "login.php";
            break;
    }
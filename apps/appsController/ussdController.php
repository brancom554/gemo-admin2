<?php

    switch ($url_array[3]) {  
        case "ajouter":
            include "addUssd.php";
            break;		
        case "modifier":
            include "editUssd.php";
            break;
        
        default:
            include "ussd.php";
            break;
    }
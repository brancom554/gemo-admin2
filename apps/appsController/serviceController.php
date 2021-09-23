<?php

    switch ($url_array[3]) {  
        case "addService":
            include "addService.php";
            break;		
        case "modifier":
            include "editService.php";
            break;
        
        default:
            include "service.php";
            break;
    }
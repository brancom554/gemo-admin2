<?php

    switch ($url_array[3]) {  
        case "ajouter":
            include "addpays.php";
            break;		
        case "modifier":
            include "editpays.php";
            break;
        
        default:
            include "pays.php";
            break;
    }
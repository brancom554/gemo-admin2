<?php

    switch ($url_array[3]) {  
        case "ajouter":
            include "addPays.php";
            break;		
        case "modifier":
            include "editPays.php";
            break;
        
        default:
            include "pays.php";
            break;
    }

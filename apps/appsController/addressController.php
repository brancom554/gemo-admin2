<?php

    switch ($url_array[3]) {  
        case "ajouter":
            include "addAddress.php";
            break;		
        case "modifier":
            include "editAddress.php";
            break;
        
        default:
            include "addresses.php";
            break;
    }
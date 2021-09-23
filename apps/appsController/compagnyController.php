<?php
    switch ($url_array[3]) {  
        case "ajouter":
            include "addCompany.php";
            break;	
        case "modifier":
            include "editCompany.php";
            break;
        
        default:
            include "compagny.php";
            break;
    }
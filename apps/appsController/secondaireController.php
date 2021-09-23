<?php


    if (intval($url_array[3]) >0) {
        switch ($url_array[4]) {  
            case "addLicenceSecondaire":
                include "addLicenceSecondaire.php";
                break;
            
            default:
                include "secondaire.php";
                break;
        }
    }   
    
else {
    switch ($url_array[3]) {          
        case "activer":
            include "activerLicenceSecondaire.php";
            break;
            
        case "bloquer":
            include "bloquerLicenceSecondaire.php";
            break;	
        
        default:
            //include "secondaire.php";
            break;
    }
}

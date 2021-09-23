<?php

    switch ($url_array[3]) {  
        case "ajouter":
            include "addUserManager.php";
            break;		
        case "password":
            include "editPasswordUser.php";
            break;
        case "informations":
            include "editUserInformations.php";
            break;
        
        default:
            include "userManager.php";
            break;
    }
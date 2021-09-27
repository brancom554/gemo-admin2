<?php

    switch ($url_array[2]) {  
        case "activer":
            include "activerUser.php";
            break;
        case "bloquer":
            include "bloquerUser.php";
            break;	
        case "password":
            include "passwordUser.php";
            break;	
        case "ajouter":
            include "addUser.php";
            break;		
        case "modifier":
            include "editUser.php";
            break;
        
        default:
            include "users.php";
            break;
    }
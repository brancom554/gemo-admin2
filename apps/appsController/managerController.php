<?php

    switch ($url_array[3]) {  
        case "ajouter":
            include "addManager.php";
            break;	
        case "password":
            include "editPassword.php";
            break;
        
        default:
            include "manager.php";
            break;
    }
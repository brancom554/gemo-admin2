<?php

    switch ($url_array[2]) {  
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
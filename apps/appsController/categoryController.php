<?php

    switch ($url_array[3]) {  
        case "ajouter":
            include "addCategory.php";
            break;		
        case "modifier":
            include "editCategory.php";
            break;
        
        default:
            include "category.php";
            break;
    }
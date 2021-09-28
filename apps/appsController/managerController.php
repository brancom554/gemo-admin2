<?php

    switch ($url_array[2]) { 
        case "activer":
            include "activerManager.php";
            break;

        case "agents":
            include "agentManager.php";
            break;

        case "ajouter":
            include "addManager.php";
            break;	
        case "password":
            include "editPassword.php";
            break;

        case "bloquer":
            include "bloquerManager.php";
            break;
        
        default:
            include "manager.php";
            break;
    }
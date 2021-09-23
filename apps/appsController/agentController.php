<?php

switch ($url_array[3]) {  
    case "revocationLicence":
        include "revocationLicence.php";
        break;
    case "ajouter":
        include "addAgent.php";
        break;		
    case "modifier":
        include "editAddress.php";
        break;
    case "licence":
        include "addAgentLicence.php";
        break;
    case "attributionLicence":
        include "attributionLicence.php";
        break;
    
    default:
        include "agent.php";
        break;
}
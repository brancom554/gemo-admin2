<?php

    switch ($url_array[3]) {  
        case 'liste':
            include "inventaire.php";
            break;

        case 'details':
            include "inventaireDetails.php";
            break;
        default:
            include "inventaire.php";
            break;
    }
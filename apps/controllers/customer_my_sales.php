<?php

$sales = $sqlData->getSales($_SESSION['customer']['contact_num']);

$view= $viewPath."/my_sales_all.phtml";

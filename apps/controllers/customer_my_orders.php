<?php

$orders = $sqlData->getOrders($_SESSION['customer']['contact_num']);

$view= $viewPath."/my_orders.phtml";

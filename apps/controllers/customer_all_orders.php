<?php

if(in_array("ROLE_ENTREPRISE", (explode(" ", $_SESSION['customer']['role_contact'])))){
    $orders = $sqlData->getOrdersByMag($_SESSION['customer']['store_code']);
    $paid_orders = $sqlData->getPaidOrdersByMag($_SESSION['customer']['store_code']);
    $pending_orders = $sqlData->getPendingOrdersByMag($_SESSION['customer']['store_code']);
    $cancelled_orders = $sqlData->getCancelledOrdersByMag($_SESSION['customer']['store_code']);
    $in_progress_orders = $sqlData->getInProgressOrdersByMag($_SESSION['customer']['store_code']);
    $closed_orders = $sqlData->getClosedOrdersByMag($_SESSION['customer']['store_code']);
    // $lib->debug($cancelled_orders);
}
else{
    $orders = $sqlData->getOrders($_SESSION['customer']['contact_num']);
    $paid_orders = $sqlData->getPaidOrders($_SESSION['customer']['contact_num']);
    $pending_orders = $sqlData->getPendingOrders($_SESSION['customer']['contact_num']);
    $cancelled_orders = $sqlData->getCancelledOrders($_SESSION['customer']['contact_num']);
    $in_progress_orders = $sqlData->getInProgressOrders($_SESSION['customer']['contact_num']);
    $closed_orders = $sqlData->getClosedOrders($_SESSION['customer']['contact_num']);
}
// $users = $sqlData->getAllUsers();
// $lib->debug($users);
// $lib->debug($orders);
// print_r($paid_orders);
$view= $viewPath."/all_orders.phtml";

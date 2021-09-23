<?php

$articles = $sqlData->getCustomerArticles($_SESSION['customer']['contact_num']);
// $lib->debug($articles);
$view = $viewPath."/articles.phtml";

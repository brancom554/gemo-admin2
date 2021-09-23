<?php

// $page_requested=$pages;
$productCode = urldecode($url_array[3]);
$categoryDescription = urldecode($url_array[3]);
$product=$sqlData->customerProductDetail($productCode);
// var_dump($product);
// die;

$product =$product['data'][0];

$_magasins=$sqlData->getMagasins();

$_categories=$sqlData->getCategories();

$_all_sub_categories = $sqlData->getSubCategoriesCategory();

$_sub_categories = array();
foreach ($_all_sub_categories['data'] as $element) {
    $_sub_categories[$element->libelle][] = $element;
}

if(file_exists(_VIEW_PATH."/customer/product_details.phtml"))  $view="/customer/product_details.phtml";
else  $view="/customer/dashboard/";
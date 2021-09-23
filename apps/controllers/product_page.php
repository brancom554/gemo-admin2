<?php

$page_requested=$pages[0];
$productCode = urldecode($pages[1]);
$categoryDescription = urldecode($pages[2]);
$product=$sqlData->getProductDetail($productCode);
$product =$product['data'][0];

$_magasins=$sqlData->getMagasins();

$_categories=$sqlData->getCategories();

$_all_sub_categories = $sqlData->getSubCategoriesCategory();

$_sub_categories = array();
foreach ($_all_sub_categories['data'] as $element) {
    $_sub_categories[$element->libelle][] = $element;
}


if(file_exists(_VIEW_PATH.$lib->lang."/product_detail.phtml"))  $view=$lib->lang."/product_detail.phtml";
else  $view=$iniObj->defaultLang."/page_home.phtml";
<?php

$pages_requested='checkout';
$currentPage = $param2[1];

if($currentPage<=0) $currentPage=1;

$_magasins=$sqlData->getMagasins();

$_categories=$sqlData->getCategories();

$_all_sub_categories = $sqlData->getSubCategoriesCategory();

$_sub_categories = array();
foreach ($_all_sub_categories['data'] as $element) {
    $_sub_categories[$element->libelle][] = $element;
}

$view=$lib->lang."/checkout_page.phtml";
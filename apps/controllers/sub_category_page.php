<?php

$subCategoryCode = urldecode($pages[1]);
$subCategoryDescription = urldecode($pages[2]);


if(!empty($page)){
	$pages_requested = $page;
}
 if (!empty($url_array[2])){
 	$pages_requested .= "/".$url_array[2];
 }

$categories = $sqlData->getStoreCategories();

$nbResult= $sqlData->getCountProducts($categoryCode,$subCategoryCode);
$nbResult=$nbResult['data'][0];
$currentPage = $param3[1];

if($currentPage<=0) $currentPage=1;

$pagination=new Pagination($iniObj,$nbResult->total);
$start = $pagination->getStartLimit($currentPage);

$products=$sqlData->getProducts($start,$iniObj->nbResultHomePage,$categoryCode,$subCategoryCode);


$subCategoryDetail=$sqlData->getSubCategoryDetail($subCategoryCode);
$subCategoryDetail=$subCategoryDetail['data'][0];

$subCategories =$sqlData->getSubCategoriesProduct($subCategoryDetail->categoryCode);

$_magasins=$sqlData->getMagasins();

$_categories=$sqlData->getCategories();

$_all_sub_categories = $sqlData->getSubCategoriesCategory();

$_sub_categories = array();
foreach ($_all_sub_categories['data'] as $element) {
    $_sub_categories[$element->libelle][] = $element;
}

if(file_exists(_VIEW_PATH.$lib->lang."/sub_category_detail.phtml"))  $view=$lib->lang."/sub_category_detail.phtml";
else  $view=$iniObj->defaultLang."/page_home.phtml";
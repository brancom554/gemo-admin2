<?php
// phpinfo();
// exit;
 $page_requested=$pages[0];
$categoryCode = urldecode($pages[1]);
$categoryDescription = urldecode($pages[2]);


if(!empty($page)){
	$pages_requested = $page;
}

$categories = $sqlData->getStoreCategories();
// $subCategories = $sqlData->getSubCategoriesStore();



$nbResult= $sqlData->getCountProducts($categoryCode);
$nbResult=$nbResult['data'][0];
// $pages_requested='accueil';
$currentPage = $param2[1];

if($currentPage<=0) $currentPage=1;

$pagination=new Pagination($iniObj,$nbResult->total);
$start = $pagination->getStartLimit($currentPage);



$products=$sqlData->getProducts($start,$iniObj->nbResultHomePage,$categoryCode);
$subCategories =$sqlData->getSubCategoriesProduct($categoryCode);
$category=$sqlData->getCategories($categoryCode);
$category=$category['data'][0];

$_magasins=$sqlData->getMagasins();

$_categories=$sqlData->getCategories();

$_all_sub_categories = $sqlData->getSubCategoriesCategory();

$_sub_categories = array();
foreach ($_all_sub_categories['data'] as $element) {
    $_sub_categories[$element->libelle][] = $element;
}

if(file_exists(_VIEW_PATH.$lib->lang."/category_detail.phtml"))  $view=$lib->lang."/category_detail.phtml";
else  $view=$iniObj->defaultLang."/page_home.phtml";
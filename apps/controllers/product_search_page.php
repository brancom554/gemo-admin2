<?php

/*

echo "product search<br>";
$lib->debug($pages);

echo "param1 search<br>";
$lib->debug($param1);


echo "param2 search<br>";
$lib->debug($param2);
echo "param3 search<br>";
$lib->debug($param3);


echo "<br>subpage 1<br>";
$lib->debug($subPage);
echo "<br>subpage 2<br>";
$lib->debug($subPage2);
*/
$searchArray = explode(" ",$param2[0]);
$searchString = $param2[0];
// $searchValue=urldecode($param2);
$nbResult= $sqlData->getCountSearchProducts($searchArray);
$nbResult=$nbResult['data'][0];
$currentPage = $param3[1]	;
if($currentPage<=0) $currentPage=1;

$pages_requested= "search/".$searchString;
// echo "<br>page requested : ".$pages_requested;
$pagination=new Pagination($iniObj,$nbResult->total);
$start = $pagination->getStartLimit($currentPage);

$product=$sqlData->getSearchProducts($start, $iniObj->nbResultPerPage,$searchArray);

$_magasins=$sqlData->getMagasins();

$_categories=$sqlData->getCategories();

$_all_sub_categories = $sqlData->getSubCategoriesCategory();

$_sub_categories = array();
foreach ($_all_sub_categories['data'] as $element) {
    $_sub_categories[$element->libelle][] = $element;
}

// $pages_requested= "magasin_".$storeUrl."/".$url_array[2];
if(file_exists(_VIEW_PATH.$lib->lang."/product_search.phtml"))  $view=$lib->lang."/product_search.phtml";
else  $view=$iniObj->defaultLang."/page_home.phtml";
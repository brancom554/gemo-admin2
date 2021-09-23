<?php

$nbResult= $sqlData->getCountProductPromo();
$nbResult=$nbResult['data'][0];
 $currentPage = $param2[1]	;
if($currentPage<=0) $currentPage=1;


$pagination=new Pagination($iniObj,$nbResult->total);
$start = $pagination->getStartLimit($currentPage);

$product=$sqlData->getProductPromo($start, $iniObj->nbResultPerPage);

$_magasins=$sqlData->getMagasins();

$_categories=$sqlData->getCategories();

$_all_sub_categories = $sqlData->getSubCategoriesCategory();

$_sub_categories = array();
foreach ($_all_sub_categories['data'] as $element) {
    $_sub_categories[$element->libelle][] = $element;
}

if(file_exists(_VIEW_PATH.$lib->lang."/prix_en_baisse.phtml"))  $view=$lib->lang."/prix_en_baisse.phtml";
else  $view=$iniObj->defaultLang."/page_home.phtml";
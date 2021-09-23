<?php
$storeUrl = urldecode($param1[1]);

if(trim($storeUrl)){
	$storeDetail= $sqlData->getStoreDetail($storeUrl);
	$storeImage = $sqlData->getStoreImage($storeUrl);
	$storeDetail= $storeDetail['data'][0];
	$storeImage = $storeImage['data'][0];
	$categories = $sqlData->getStoreCategories($storeUrl);
}
// $lib->debug($url_array);
// $lib->debug($param2);
// $lib->debug($param3);
// $subPage=$param2[0];
// echo "<br>param2 => ".$param2[0];
// echo "<br>param3 => ".$param3[0];
// echo "<br>param2 => ".$param3;
if(!empty($param2[0])){
	//	echo "<br>param2 => ".$param2[0];
	if($param2[0]=='p'){
		$currentPage = $param2[1];
	}
	// Viewing product detail
	else if($param2[0]=='article'){
		$viewProductDetail =1;
		$productCode = urldecode($param2[1]);
		$product=$sqlData->getProductDetail($productCode);
		$product =$product['data'][0];
	}
	else if($param2[0]=='prix'){
		$promoActive = 1;

		//  		echo "<br>subpage =>".$subPage;
		//  		echo "<br>subpage2 =>".$url_array[2];
		$pages_requested= "magasin_".$storeUrl."/".$url_array[2];

		$nbResult= $sqlData->getCountProductPromo();
		$nbResult=$nbResult['data'][0];
		$currentPage = $param3[1]	;
		//  		echo "<br>current page  :".$currentPage;
		// echo "<br>sub page  :".$currentPage;
		if($currentPage<=0) $currentPage=1;


		$pagination=new Pagination($iniObj,$nbResult->total);
		$start = $pagination->getStartLimit($currentPage);

		$product=$sqlData->getProductPromo($start, $iniObj->nbResultPerPage,$storeUrl);
		//  		echo "debug promo";
		//  		$lib->debug($product);

	}
	else if($param2[0]=='search'){
// 		echo "<br>page request magasin".$pages_requested;

		$searchArray = explode(" ",$param3[0]);
		$searchString = $param3[0];
		$nbResult= $sqlData->getCountSearchProducts($searchArray,'','',$storeUrl);
		$nbResult=$nbResult['data'][0];
		$currentPage = $param4[1];
		if($currentPage<=0) $currentPage=1;

// 		echo "<br>search string =>".$searchString;
// 		echo "<br>search value =>";
// 		$lib->debug($searchArray);

		// $pages_requested= "search/".$searchString;
		$pages_requested= "magasin_".$storeUrl."/search/".$searchString;

		// echo "<br>page requested : ".$pages_requested;
		$pagination=new Pagination($iniObj,$nbResult->total);
		$start = $pagination->getStartLimit($currentPage);

		$products=$sqlData->getSearchProducts($start, $iniObj->nbResultPerPage,$searchArray,'','',$storeUrl);
		//	echo "<br>produtcs search value =>";
		// 	$lib->debug($products);

	}
	else
	{
		// 		echo "<br>ELSE param2[0]=='prix' ";
		$categoryId =$param2[0];
		$category=$sqlData->getCategories($categoryId);
		$category=$category['data'][0];
		$pages_requested .= "/".$url_array[2];
		$subCategories =$sqlData->getSubCategoriesStore($storeUrl,$categoryId);
	}
}

if((!empty($param3[0]) && $param2[0]!='search') && !$promoActive){
// 	 	echo "<br> param3[0]=='prix' ";
	if($param3[0]=='p'){
		$currentPage = $param3[1];
		$pages_requested .= "/".$url_array[3];
	}
	else{
		$pages_requested .= "/".$url_array[3];
		$subCategoryId =$param3[0];
		$subCategory=$sqlData->getSubCategoryDetail($subCategoryId);
		$subCategory=$subCategory['data'][0];
		$subCategories =$sqlData->getSubCategoriesStore($storeUrl,$categoryId);
	}
}

if(!empty($param4[0])){
	// 	echo "<br> param4[0]=='prix' ";
	if($param4[0]=='p'){
		$currentPage = $param4[1];
	}
}

if($subCategoryId) {
// 	echo "<br> if subCategoryId";
// 	echo "<br> subCategoryId = ".$subCategoryId;
// 	echo " <br> END";
	$nbResult= $sqlData->getCountProducts($categoryId,$subCategoryId,$storeUrl);
	$nbResult=$nbResult['data'][0];

	if($currentPage<=0) $currentPage=1;

	$pagination=new Pagination($iniObj,$nbResult->total);
	$start = $pagination->getStartLimit($currentPage);
	$products=$sqlData->getProducts($start,$iniObj->nbResultHomePage,$categoryId,$subCategoryId,$storeUrl);
}
else if(!$promoActive && $param2[0]!='search'){
// 	echo "<br> else if promoActive";
	$nbResult= $sqlData->getCountProducts($categoryId,'',$storeUrl);
	$nbResult=$nbResult['data'][0];

	if($currentPage<=0) $currentPage=1;

	$pagination=new Pagination($iniObj,$nbResult->total);
	$start = $pagination->getStartLimit($currentPage);
	$products=$sqlData->getProducts($start,$iniObj->nbResultHomePage,$categoryId,'',$storeUrl);
}

// echo " debug product after all processes : debug promo";
// $lib->debug($products);

$storeActive = 1;

$_magasins=$sqlData->getMagasins();

$_categories=$sqlData->getCategories();

$_all_sub_categories = $sqlData->getSubCategoriesCategory();

$_sub_categories = array();
foreach ($_all_sub_categories['data'] as $element) {
    $_sub_categories[$element->libelle][] = $element;
}


if(file_exists(_VIEW_PATH.$lib->lang."/magasin_detail.phtml"))  $view=$lib->lang."/magasin_detail.phtml";
else  $view=$iniObj->defaultLang."/page_home.phtml";
<?php

/*
 * retrieve the second parameter from the URL
 */

$viewPath = "customer/";

// $lib->debug($url_array);
$sub_page = $url_array[2];


//    var_dump($sub_page);
//   die;

// if (!$_SESSION['customer']['authValidated'] && $sub_page == 'cOrders' && $sub_page == 'dashboard') {
// 	echo "denied||" . $lang->trl("Authentification requise !");
// 	exit;
// }

// if (
// 	!$_SESSION['customer']['authValidated'] && $sub_page != 'pReset'
// 	&& $sub_page != 'cLogin' && $sub_page != 'reset' && $sub_page != 'signup'
// 	&& $sub_page != 'signupmagasin' && $sub_page != 'registerUser' &&
// 	$sub_page != 'registerMagasin' && $sub_page != 'dashboard'
// ) {
// 	$sub_page = "login";
// }

// echo "subpage =>".$sub_page;

// Data for Top menu
// $_magasins = $sqlData->getMagasins();
// $_categories = $sqlData->getCategories();
// $_all_sub_categories = $sqlData->getSubCategoriesCategory();
// $_sub_categories = array();
// foreach ($_all_sub_categories['data'] as $element) {
// 	$_sub_categories[$element->libelle][] = $element;
// }


// var_dump($sub_page);
//  die;
$title = $lang->trl($iniObj->serviceName);
switch ($sub_page) {
	case "dashboard":
		include "home_page.php";
		break;
	case "company_new":
		include "company_new.php";
		break;
	case "company_list":
		include "company_list.php";
		break;
	case "mission_new":
		include "mission_new.php";
		break;
	case "mission_list":
		include "mission_list.php";
		break;
	case "contact_new":
		include "contact_new.php";
		break;
	case "contact_list":
		include "contact_list.php";
		break;
	case "service_new":
		include "service_new.php";
		break;
	case "service_list":
		include "service_list.php";
		break;
	case "saveNewCompany":
		include "save_new_company_AJAX.php";
		break;
	case "saveNewContact":
		include "save_new_contact_AJAX.php";
		break;
	case "companies":
		include "company_list.php";
		break;
	case "contacts":
		include "contact_list.php";
		break;
	case "pChange":
		include "customer_password_change_AJAX.php";
		break;

	case "pMess":
		include "customer_add_tracking.php";
		exit;
		break;

	case "pReset":
		include "customer_password_reset_AJAX.php";
		exit;
		break;

	case "pref":
		$view = $viewPath . "/my_preferences.phtml";
		break;

	case "password":
		$view = $viewPath . "/my_password.phtml";
		break;

	case "sendPrefs": //OK
		$cache = false;
		include "customer_save_preferences.php";
		break;
	case "print": // Print shipping
		include "customer_print.php";
		exit;
		break;

	case "reset":
		$view = $viewPath . "page_reset.phtml";
		break;

	case "login":
		$view = $viewPath . "login.phtml";
		break;

	case "cLogin":
		include 'customer_login_AJAX.php';
		exit;
		break;

	case "checkLogin":
		//include "customer_login.php";
		break;

	case "my_orders":
		$cache = false;
		include "customer_my_orders.php";
		//$view= $viewPath."/my_orders.phtml";
		break;
	case "all_orders":
		$cache = false;
		include "customer_all_orders.php";
		//$view= $viewPath."/my_orders.phtml";
		break;

	case "update_order_status":
		include 'customer_update_order_status_AJAX.php';
		exit;
		break;
	case "product_availability":
		include 'customer_update_product_availability_AJAX.php';
		exit;
		break;

	case "cOrders":
		include 'customer_orders_AJAX.php';
		exit;
		break;
	case "newProduct":
		$view = $viewPath . "/new_product_2.phtml";
		break;
	case "saveNewProduct":
		include "save_new_product_AJAX.php";
		break;
	case "update_product":
		include "update_product_AJAX.php";
		break;


	case "sendNewShipping":
	case "createNewShipping":
		include "customer_save_new_shipping_AJAX.php";
		break;

	case "sendNewAddress":
		include "customer_send_new_address.php";
		exit;
		break;

	case "newAddress":
		// $cache=false;
		include "customer_newAddress.php";
		break;

	case "articles":
		include "customer_articles.php";
		break;

	case "delete_article":
		include "delete_article_AJAX.php";
		break;

	case "detailsProduct":
		include "product_details.php";
		break;

	case "editProduct":
		include "edit_product.php";
		break;

	case "editUser":
		$view = $viewPath . "editUser.phtml";
		break;

	case "edit_user":
		include  "edit_user_AJAX.php";
		break;

	case "change_profile":
		$view = $viewPath . "change_profile.phtml";
		break;

	case "change_store":
		$view = $viewPath . "change_store.phtml";
		break;
	case "users_list":
		$view = $viewPath . "users_list.phtml";
		break;
	case "verify_quantity":
		include "verify_quantity_AJAX.php";
		break;
	case "validation_encours":
		include "validation_encours_AJAX.php";
		break;

	case "uAddress":
		include "customer_updateAddress.php";
		break;

	case "logout":
		//		echo "LOGOUT in progress / session before";
		//  $lib->debug($_SESSION);
		//  exit;
		//	$cache=false;
		@session_start();
		$_SESSION['customer']['authValidated'] = false;
		$_SESSION['customer'] = array();
		unset($_COOKIE);
		unset($_SESSION);

		//http://durak.org/sean/pubs/software/php-5.4.6/function.session-destroy.html
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(
				session_name(),
				'',
				time() - 42000,
				$params["path"],
				$params["domain"],
				$params["secure"],
				$params["httponly"]
			);
		}

		//Finally, destroy the session.
		session_destroy();
		$_COOKIE = array();
		$_SESSION = array();

		session_unset();
		// session_destroy();
		session_write_close();
		setcookie(session_name(), '', 0, '/');
		session_regenerate_id(true);

		// 		echo "LOGIN fin session after";
		// $lib->debug($_SESSION);
		// 		echo "LOGIN fin";exit;
		// $view= $lib->lang."/home_page.phtml";
		// $lib->debug($_SESSION);
		// exit;
		header("Location: /" . $random);
		break;
	case "signup":
		// die;
		$view = $viewPath . "page_signup_user.phtml";
		break;
	case "registerUser":
		//  die;
		include "customer_saveUser_AJAX.php";
		exit;
		break;
	case "signupmagasin":
		//  die;
		$view = $viewPath . "page_signup_magasin.phtml";
		break;
	case "registerMagasin":
		//  die;
		include "customer_saveMagasin_AJAX.php";
		exit;
		break;

	case "my_sales":
		$cache = false;
		include "customer_my_sales.php";
		break;


	default:
		if (!$_SESSION['customer']['authValidated']) {
			header("Location: /" . $iniObj->serviceName);
			exit;
		} else {

			$cache = false;
			// include "customer_home.php";
			$view = $viewPath . "/my_dashboard.phtml";
		}

		break;

		// default : include _VIEW_PATH.$lib->lang."/home_page.phtml"; break;
}

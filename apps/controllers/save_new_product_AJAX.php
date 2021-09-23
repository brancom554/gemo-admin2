<?php

unset($error);

// var_dump(json_encode(($_FILES)));
// var_dump(json_encode(($_REQUEST)));
// exit;
foreach ($_REQUEST as $k => $v) {
	$_REQUEST[$k] = urldecode(trim($v));
}
// $lib->debug($_REQUEST);

// foreach ($_FILES as $k => $v) {
// 	$_FILES[$k] = urldecode(trim($v));
// }


if (isset($_REQUEST)) {
	$magasin_code = $_REQUEST['magasinID'];
	$contact_num = $_REQUEST['contact_num'];
	$libelle_produit = $_REQUEST['libelle_produit'];
	$prix_achat = $_REQUEST['prix_achat'];
	$qte_initiale = $_REQUEST['qte_initiale'];
	$qte_stock = $_REQUEST['qte_stock'];
	$qte_stock_mini = $_REQUEST['qte_minimum_stock'];
	$qte_stock_maxi = $_REQUEST['qte_maximum_stock'];
	$article_type_code = $_REQUEST['article_type_code'];
	$prix_vente_initial = $_REQUEST['prix_vente_initial'];
	$prix_vente_courant = $_REQUEST['prix_vente_courant'];
	$famillecode = $_REQUEST['famillecode'];
	$sous_famille_code = $_REQUEST['sous_famillecode'];
	$qte_maximum_stock = $_REQUEST['qte_maximum_stock'];
	$qte_stock_alerte = $_REQUEST['qte_stock_alerte'];
	$taux_commission = $_REQUEST['taux_commission'];
	$description = $_REQUEST['description'];
	$cout_emballage = $_REQUEST['cout_emballage'];
	$frais_port = $_REQUEST['frais_port'];
	$reference_article_magasin = $magasin_code . '-' . rand(100000, 300000);
	$web_actif = 'OUI';
	$hauteur =  $_REQUEST['hauteur'];
	$largeur =  $_REQUEST['largeur'];
	$poids =  $_REQUEST['poids'];
	$profondeur =  $_REQUEST['profondeur'];
	$prix_net_deposant =  $_REQUEST['prix_net_deposant'];
	$taux_assurance =  $_REQUEST['taux_assurance'];
	// die;s
	if (!isset($libelle_produit)) {
		echo "false||Veuillez renseigner la désignation de l'article";
		exit;
	} elseif (!isset($prix_achat)) {
		echo "false||Veuillez renseigner le prix d'achat de l'article";
		exit;
	} elseif (!isset($qte_initiale)) {
		echo "false||Veuillez renseigner la quantité initiale de l'article";
		exit;
	} elseif (!isset($qte_stock)) {
		echo "false||Veuillez renseigner la quantité de l'article en stock";
		exit;
	} elseif ($qte_stock < 1) {
		echo "false||La quantité en stock du produit doit être supérieure à 0";
		exit;
	} elseif (!isset($prix_vente_initial)) {
		echo "false||Veuillez renseigner le prix de vente initial de l'article";
		exit;
	} elseif (!isset($prix_vente_courant)) {
		echo "false||Veuillez renseigner le prix de vente courant de l'article";
		exit;
	} elseif (!isset($qte_maximum_stock)) {
		echo "false||Veuillez renseigner la quantité maximum de l'article en stock";
		exit;
	} elseif (!isset($qte_stock_alerte)) {
		echo "false||Veuillez renseigner le stock alerte de l'article";
		exit;
	} else {
		//
		$article_exist = $sqlData->getProductByName($libelle_produit);
		if ($article_exist['rows'] != 0) {
			echo "false||Cet article existe déjà";
			exit;
		} else {


			if (isset($_FILES)) {
				if (isset($_REQUEST)) {
					$photo_name = [];
					$nb_files = count($_FILES['files']['tmp_name']);
					if ($_FILES['files']['tmp_name'][0] != '') {
						for ($i = 0; $i < $nb_files; $i++) {
							if ($i == 0) {
								$photo_name[$i] = $_FILES['files']['name'][$i];
							} elseif ($i == 1) {
								$photo_name[$i] = $_FILES['files']['name'][$i];
							} elseif ($i == 2) {
								$photo_name[$i] = $_FILES['files']['name'][$i];
							} elseif ($i == 3) {
								$photo_name[$i] = $_FILES['files']['name'][$i];
							}

							$file_size = $_FILES['files']['size'][$i];
							$file_tmp = $_FILES['files']['tmp_name'][$i];
							$exploser = explode('.', $_FILES['files']['name'][$i]);
							$file_ext = strtolower(end($exploser));
							$extensions = array("jpeg", "jpg", "png");

							if (in_array($file_ext, $extensions) === false) {
								echo "false||L'extension de l'image ". $_FILES['files']['name'][$i] ." doit être du jpg, jpeg ou png.";
								exit;
							}

							if ($file_size > 2097152) {
								echo "false||La taille maximale de l'image " . $_FILES['files']['name'][$i]." ne doit pas dépasser 2Mo";
								exit;
							}

							if (!file_exists(_WEB_FILES . "/filesLib/images/articles_img/")) {
								mkdir(_WEB_FILES . "/filesLib/images/articles_img/" , 0644, true);
							}
							move_uploaded_file($file_tmp, _WEB_FILES . "/filesLib/images/articles_img/" . $_FILES['files']['name'][$i]);
						
						}
					} else {
						$photo_name[0] = "";
						$photo_name[1] = "";
						$photo_name[2] = "";
						$photo_name[3] = "";
					}
				}
			}
			// $lib->debug($photo_name);

			$registerproduct = $sqlData->saveNewProduct(
				$contact_num,
				$article_type_code,
				$famillecode,
				$sous_famille_code,
				$reference_article_magasin,
				$libelle_produit,
				$prix_achat,
				$taux_commission,
				$prix_vente_initial,
				$prix_vente_courant,
				$qte_initiale,
				$qte_stock,
				$prix_net_deposant,
				$qte_stock_mini,
				$qte_stock_maxi,
				$qte_stock_alerte,
				$web_actif,
				$description,
				$photo_name[0],
				$photo_name[1],
				$photo_name[2],
				$photo_name[3],
				$taux_assurance,
				$magasin_code,
				$hauteur,
				$largeur,
				$poids,
				$profondeur,
				$cout_emballage,
				$frais_port
			);
			// $lib->debug($registerproduct);
			// exit;

			if ($registerproduct == true) {

				echo "true||Article enregistré avec succès";
				exit;
			} else {
				echo "false||Enregistrement échoué";
				exit;
			}
		}
	}
}


exit;

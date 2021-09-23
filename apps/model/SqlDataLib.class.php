<?php
/*
 * THis class will handle all data retrieval from DB and return an array with all data necessary
*/

class sqlDataLib
{
	// private $pdo;
	function __construct($ini)
	{
		global $_db, $lib, $lang;
		$this->db = $_db;
		$this->lib = $lib;
		$this->lang = $lang;
		$this->iniObj = $ini;
		// $this->$pdo=null;
		// print_r($this->lang);
	}

	/*
	 * format data and retrieve in an array format
	*/
	function fetchData($q, $arr = array(), $br = 1)
	{
		if ($br == 1)  $keywords = array("''" => "'", "%" => "%25", "\n" => "<br>");
		else $keywords = array("''" => "'", "%" => "%25");

		if ($this->iniObj->debugSQL) echo "<br>Query => <br>$q <br>";
		// $sql = sql::getInstance();

		$sql = $this->db->Connect();

		if (!$sql) {
			die("Error: Cannot connect to database.");
		} else {
			try {
				$stmt = $sql->prepare($q);
				$stmt->execute($arr);
				$o = $stmt->fetchAll();
				$d['data'] = $o;
				$d['rows'] = count($o);
				if ($this->iniObj->debugSQL) $this->debugError($e, $stmt);
				return ($d);
			} catch (Exception $e) {
				if ($this->iniObj->debugSQL) $this->debugError($e);
				return ($e);
			}
		}
		$this->db->disconnect();
	}

	function fetchOneRow($q, $arr = "", $br = 1)
	{
		$sql = sql::getInstance();
		$stmt = $sql->dbh->prepare($q);
		if ($stmt->execute($arr)) {
			// $o = $stmt->fetch(PDO::FETCH_OBJ); // One record
			$o = $stmt->fetch();
		}
		if ($this->iniObj->debugSQL) {
			echo "<br><pre>";
			print_r($o);
		}
		if ($br == 1)  $keywords = array("''" => "'", "%" => "%25", "\n" => "<br>");
		else $keywords = array("''" => "'", "%" => "%25");
		// return($myArray);
	}

	function getMagasins($id = '')
	{
		$q = "SELECT m.magasin_id, m.magasin_code, m.nom_magasin, m.ville, m.nom_contact, m.magasin_url,m.code_postal,m.GpsLat,m.GpsLon, m.telephone1
		 FROM magasins m
		 WHERE 1";
		if (trim($id)) {
			$q .= " AND magasin_id = '" . $id . "'";
		}
		$q .= " ORDER by magasin_id ASC ";
		return ($this->fetchData($q));
	}
	function getMagasinByCOde($magasin_code = '')
	{
		$q = "SELECT m.magasin_id, m.magasin_code, m.nom_magasin, m.ville, m.nom_contact, m.magasin_url,m.code_postal,m.GpsLat,m.GpsLon, m.telephone1, m.email_directeur
		 FROM magasins m
		 WHERE 1";
		if (trim($magasin_code)) {
			$q .= " AND magasin_code = '" . $magasin_code . "'";
		}
		$q .= " ORDER by magasin_id ASC ";
		return ($this->fetchData($q));
	}

	function getCategories($id = '')
	{
		$q = "select famille_code categoryCode, libelle categoryDescription, couleur color from product_categories where 1 ";
		if (trim($id)) {
			$q .= " AND famille_code = '" . $id . "'";
		}
		$q .= " ORDER by libelle ASC ";
		return ($this->fetchData($q));
	}

	function getStoreCategories($storeId = '')
	{
		$q = "SELECT pc.famille_code categoryCode, pc.libelle categoryDescription, pc.couleur color ,COUNT(a.article_ID) totalProduct, m.magasin_url storeUrl, m.ville storeCity
				FROM product_categories pc
				INNER JOIN articles a ON a.famille_code = pc.famille_code
				INNER JOIN magasins m ON m.magasin_code = a.magasin_code

				WHERE 1  AND a.qte_stock>0 AND a.photo1 >'' AND a.web_actif='OUI' ";
		//				INNER JOIN product_sub_categories sc on sc.famille_code = pc.famille_code

		if (trim($storeId)) {
			$q .= " AND m.magasin_url = '" . $storeId . "'";
		}
		$q .= " GROUP BY a.famille_code ORDER BY pc.libelle ASC ";
		return ($this->fetchData($q));
	}

	function getSubCategoriesStore($storeId = '', $categoryId = '')
	{
		$q = " SELECT sc.famille_code categoryCode, sc.sous_famille_code subCategoryCode,pc.libelle categoryDescription,m.magasin_url storeUrl, m.ville storeCity
		, sc.libelle subCategoryDescription,COUNT(a.article_ID) totalProduct
		FROM product_sub_categories sc
		INNER JOIN articles a ON a.sous_famille_code = sc.sous_famille_code
		INNER JOIN product_categories pc ON pc.famille_code = sc.famille_code
		INNER JOIN magasins m ON m.magasin_code = a.magasin_code
		WHERE 1  AND a.qte_stock>0 and a.photo1 >'' AND a.web_actif='OUI' ";
		if (trim($storeId)) {
			$q .= " AND m.magasin_url = '" . $storeId . "'";
		}

		if (trim($categoryId)) {
			$q .= " AND a.famille_code = '" . $categoryId . "'";
		}

		$q .= " GROUP BY sc.sous_famille_code ORDER BY sc.libelle ASC ";
		return ($this->fetchData($q));
	}

	function getSubCategoriesProduct($category = '')
	{
		$q = "
		SELECT sc.famille_code categoryCode, sc.sous_famille_code subCategoryCode
		, sc.libelle subCategoryDescription,COUNT(a.article_ID) totalProduct
		FROM product_sub_categories sc
		INNER JOIN articles a on a.sous_famille_code = sc.sous_famille_code
		WHERE sc.famille_code = '$category'
		AND a.web_actif='OUI'
		and a.photo1 >''
		GROUP BY sc.sous_famille_code ORDER BY sc.libelle ASC
		";
		return ($this->fetchData($q));
	}

	function getSubCategoriesCategory()
	{
		$q = "
		SELECT c.libelle libelle, sc.famille_code categoryCode, sc.famille_code subCategoryCode, sc.libelle subCategoryDescription 
		FROM product_sub_categories sc INNER JOIN product_categories c on c.famille_code = sc.famille_code 
		";
		return ($this->fetchData($q));
	}

	function getProductDetail($id = '')
	{
		$q = "
		SELECT
		pc.famille_code categoryCode
		,pc.libelle categoryDescription
		, sc.sous_famille_code subCategoryCode
		, sc.libelle subCategoryDescription
		, sc.image subCategoryImage
		, a.article_ID
		,a.libelle
		,m.magasin_code
		,m.nom_magasin storeName
		,m.ville storeCity
		,m.code_postal
		,m.magasin_url storeUrl
		,m.adresse1
		,m.adresse2
		,m.email_magasin
		,m.GpsLat
		,m.GpsLon
		,m.motduresponsable
		,m.nom_contact
		,m.nom_magasin storeName
		,m.photomagasin
		,m.telephone1
		,m.telephone2
		,a.photo1
		,a.photo2
		,a.photo3
		,a.photo4
		,a.hauteur
		,a.largeur
		,a.profondeur
		,a.profondeur
		,a.description
		,a.reference_web webReference
		,a.article_type_code typeCode
		,a.qte_stock qteStock
		, FORMAT(a.prix_vente_initial,2) initalPrice
		,FORMAT(a.prix_vente_courant,2) currentPrice
		,FORMAT(a.frais_de_port,2) shippingPrice
		FROM product_sub_categories sc
		INNER JOIN product_categories pc ON pc.famille_code = sc.famille_code
		INNER JOIN articles a ON a.sous_famille_code = sc.sous_famille_code
		INNER JOIN magasins m ON m.magasin_code = a.magasin_code
		WHERE a.article_ID = '$id'
		AND a.web_actif='OUI'
		";
		return ($this->fetchData($q));
	}
	function customerProductDetail($id = '')
	{
		$q = "
		SELECT a.article_ID
		,a.libelle,m.magasin_code,m.nom_magasin storeName,m.ville storeCity,m.code_postal,
		m.magasin_url storeUrl,m.adresse1,m.adresse2,m.email_magasin,m.GpsLat,m.GpsLon,
		m.motduresponsable,m.nom_contact,m.nom_magasin storeName,m.photomagasin,
		m.telephone1,m.telephone2,a.photo1,a.photo2,a.photo3,a.photo4,a.hauteur,a.largeur,
		a.profondeur,a.poids,a.description,a.reference_web webReference
		,a.article_type_code typeCode
		,a.qte_stock qteStock,a.prix_achat,a.qte_stock_mini,a.qte_stock_maxi,a.qte_stock_alerte,
		a.prix_net_deposant,a.famille_code,a.frais_de_port,a.cout_emballage,a.qte_initial,a.taux_commission,a.taux_assurance
		, FORMAT(a.prix_vente_initial,2) initalPrice
		,FORMAT(a.prix_vente_courant,2) currentPrice
		,FORMAT(a.frais_de_port,2) shippingPrice
		FROM articles a
		INNER JOIN magasins m ON m.magasin_code = a.magasin_code
		WHERE a.article_ID = '$id'
		";
		return ($this->fetchData($q));
	}

	function getSubCategoryDetail($id = '')
	{
		$q = "SELECT pc.famille_code categoryCode, pc.libelle categoryDescription, pc.couleur color
				, sc.sous_famille_code subCategoryCode,sc.libelle subCategoryDescription
				FROM product_sub_categories sc
				INNER JOIN product_categories pc ON pc.famille_code = sc.famille_code where 1 ";
		if (trim($id)) {
			$q .= " AND sous_famille_code = '" . $id . "'";
		}

		return ($this->fetchData($q));
	}

	function getCountSearchProducts($search, $category = '', $subcategory = '', $storeUrl = '')
	{

		$cq = '';
		if (isset($search)) {
			/* Handling muliple keywords */
			if (is_array($search)) {
				foreach ($search as $k => $v) {
					if (trim($v)) {
						$cq .= ' AND ( a.libelle like "%' . $v . '%" OR a.description like "%' . $v . '%" ) ';
					}
				}
			} else {
				if (trim($search)) {
					$cq = ' AND ( a.libelle like "%' . $search . '%" OR a.description like "%' . $search . '%" ) ';
				}
			}
		}

		$q = "SELECT
				count(
				a.article_ID) total
				FROM articles a
				INNER JOIN product_sub_categories sc ON sc.sous_famille_code = a.sous_famille_code
				INNER JOIN magasins m ON m.magasin_code = a.magasin_code
				WHERE a.photo1 >''
				AND a.web_actif='OUI'
				AND a.qte_stock>0 ";

		if (trim($category)) $q .= " AND a.famille_code='" . $category . "'";
		if (trim($subcategory)) $q .= " AND a.sous_famille_code='" . $subcategory . "'";
		if (trim($storeUrl)) $q .= " AND m.magasin_url='" . $storeUrl . "'";

		if (trim($cq)) {
			$q .= $cq;
		}

		//echo "<br>query count = ".$q;
		// $q.=" ORDER BY a.date_modif DESC LIMIT 0,$limit ";
		return ($this->fetchData($q));
	}

	function getSearchProducts($startLimit, $endLimit, $search, $category = '', $subcategory = '', $storeUrl = '')
	{

		$cq = '';
		if (isset($search)) {
			/* Handling muliple keywords */
			if (is_array($search)) {
				foreach ($search as $k => $v) {
					if (trim($v)) {
						$cq .= ' AND ( a.libelle like "%' . $v . '%" OR a.description like "%' . $v . '%" OR a.reference_article_magasin like "%' . $v . '%" ) ';
					}
				}
			} else {
				if (trim($search)) {
					$cq = ' AND ( a.libelle like "%' . $search . '%" OR a.description like "%' . $search . '%" OR a.reference_article_magasin like "%' . $search . '%" ) ';
				}
			}
		}

		$q = "SELECT
				a.libelle libelle
				,a.article_ID productId
				, a.sous_famille_code subCategoryCode
				, sc.libelle subCategoryDescription
				, pc.famille_code categoryCode
				, pc.libelle categoryDescription
				, a.photo1 photo1, a.qte_stock
				, m.ville storeCity
				,m.magasin_url storeUrl
				,m.magasin_code storeCode
				,m.nom_magasin storeName
				, a.prix_vente_initial initalPrice
				,a.prix_vente_courant currentPrice
				, FORMAT(a.prix_vente_initial,2) initalPrice
				,FORMAT(a.prix_vente_courant,2) currentPrice
				FROM articles a
				INNER JOIN product_sub_categories sc ON sc.sous_famille_code = a.sous_famille_code
				INNER JOIN product_categories pc ON pc.famille_code = sc.famille_code
				INNER JOIN magasins m ON m.magasin_code = a.magasin_code
				WHERE a.photo1 >''
				AND a.web_actif='OUI'
				AND a.qte_stock>0 ";

		if (trim($category)) $q .= " AND a.famille_code='" . $category . "'";
		if (trim($subcategory)) $q .= " AND a.sous_famille_code='" . $subcategory . "'";
		if (trim($storeUrl)) $q .= " AND m.magasin_url='" . $storeUrl . "'";

		if (trim($cq)) {
			$q .= $cq;
		}

		$q .= " ORDER BY a.date_modif DESC LIMIT $startLimit,$endLimit ";
		return ($this->fetchData($q));
	}


	function getCountProducts($category = '', $subcategory = '', $storeUrl = '')
	{
		$q = "SELECT
				count(
				a.article_ID) total
				FROM articles a
				INNER JOIN product_sub_categories sc ON sc.sous_famille_code = a.sous_famille_code
				INNER JOIN magasins m ON m.magasin_code = a.magasin_code
				WHERE a.photo1 >''
				AND a.web_actif='OUI'
				AND a.qte_stock>0 ";

		if (trim($category)) $q .= " AND a.famille_code='" . $category . "'";
		if (trim($subcategory)) $q .= " AND a.sous_famille_code='" . $subcategory . "'";
		if (trim($storeUrl)) $q .= " AND m.magasin_url='" . $storeUrl . "'";
		//echo "<br>query count = ".$q;
		// $q.=" ORDER BY a.date_modif DESC LIMIT 0,$limit ";
		return ($this->fetchData($q));
	}

	function getProducts($startLimit, $endLimit, $category = '', $subcategory = '', $storeUrl = '')
	{
		$q = "SELECT
				a.libelle libelle
				,a.article_ID productId
				, a.sous_famille_code subCategoryCode
				, sc.libelle subCategoryDescription
				, pc.famille_code categoryCode
				, pc.libelle categoryDescription
				, a.photo1 photo1,a.qte_stock
				, m.ville storeCity
				,m.magasin_url storeUrl
				,m.magasin_code storeCode
				,m.nom_magasin storeName
				,m.email_magasin
				,m.email_directeur
				,a.prix_vente_initial initalPrice
				,a.prix_vente_courant currentPrice
				,FORMAT(a.prix_vente_initial,2) initalPrice
				,FORMAT(a.prix_vente_courant,2) currentPrice
				FROM articles a
				INNER JOIN product_sub_categories sc ON sc.sous_famille_code = a.sous_famille_code
				INNER JOIN product_categories pc ON pc.famille_code = sc.famille_code
				INNER JOIN magasins m ON m.magasin_code = a.magasin_code
				WHERE a.photo1 >''
				AND a.web_actif='OUI'
				AND a.qte_stock>0 ";

		if (trim($category)) $q .= " AND a.famille_code='" . $category . "'";
		if (trim($subcategory)) $q .= " AND a.sous_famille_code='" . $subcategory . "'";
		if (trim($storeUrl)) $q .= " AND m.magasin_url='" . $storeUrl . "'";
		$q .= " ORDER BY a.date_modif DESC LIMIT $startLimit,$endLimit ";
		return ($this->fetchData($q));
	}

	function getUserStockProducts($startLimit, $endLimit, $userID = '')
	{
		$q = "SELECT
				DISTINCT(a.article_ID) productId
				,a.reference_web webId
				,a.libelle libelle
				, a.contact_num
				, a.qte_stock qty
				,a.contrat_num contractNum
				,a.reference_article_magasin articleRef
				, m.ville storeCity
				,m.magasin_url storeUrl
				,m.magasin_code storeCode
				,m.nom_magasin storeName
				,FORMAT(a.prix_vente_initial,2) initialPrice
				FROM articles a
				 INNER JOIN contacts c ON c.contact_num = a.contact_num
				 INNER JOIN ventes_details vd ON vd.contact_num = a.contact_num
				 INNER JOIN magasins m ON m.magasin_code = a.magasin_code

				WHERE
				c.contact_id = :contact_id
				 AND vd.contrat_num = a.contrat_num
				AND a.qte_stock>0
				ORDER by a.reference_web ASC
				";
		// GROUP BY vd.contrat_num, vd.contact_num, a.article_ID
		return ($this->fetchData($q, array(":contact_id" => $userID)));
	}

	function getUserProductPayments($startLimit, $endLimit, $userID = '')
	{
		$q = "SELECT
				DATE_FORMAT(vd.date_cloture_vente,'%d-%m-%Y') salesDate
				,vd.contrat_num contractNum
				,vd.qte_vente nbVente
				,vd.reference_article_magasin articleRef
				,vd.libelle description
				, m.ville storeCity
				,m.magasin_url storeUrl
				,m.magasin_code storeCode
				,m.nom_magasin storeName
				,FORMAT(vd.prix_a_reglee,2) paymentAmount
				,DATE_FORMAT(vd.date_reglement,'%d-%m-%Y') paymentDate
				,vd.reglee, vente_num
				FROM ventes_details vd
				INNER JOIN contacts c ON c.contact_num = vd.contact_num
				INNER JOIN magasins m ON m.magasin_code = vd.magasin_code
				WHERE  vd.reglee=0
				AND c.contact_id = :contact_id
				and vd.date_reglement <> '0000-00-00'
				ORDER BY m.magasin_code ASC, vd.contrat_num, vd.reference_article_magasin, vd.date_cloture_vente DESC ";
		return ($this->fetchData($q, array(":contact_id" => $userID)));
	}

	function getProductPromo($startLimit = 0, $endLimit = 0, $storeId = '', $category = '', $subcategory = '')
	{
		$q = "SELECT
				a.libelle
				,a.article_ID productId
				, a.sous_famille_code subCategoryCode
				, sc.libelle subCategoryDescription
				, pc.famille_code categoryCode
				, pc.libelle categoryDescription
				, a.photo1 photo1,a.qte_stock
				, m.ville storeCity
				,m.magasin_url storeUrl
				,m.magasin_code storeCode
				,m.nom_magasin storeName
				, a.prix_vente_initial initalPrice
				,a.prix_vente_courant currentPrice
				,FORMAT(a.prix_vente_initial,2) initalPrice
				,FORMAT(a.prix_vente_courant,2) currentPrice
				FROM articles a
				INNER JOIN product_sub_categories sc ON sc.sous_famille_code = a.sous_famille_code
				INNER JOIN product_categories pc ON pc.famille_code = sc.famille_code
				INNER JOIN magasins m ON m.magasin_code = a.magasin_code
				WHERE a.photo1 >''
				AND a.qte_stock>0
				AND a.web_actif='OUI'
				AND a.prix_vente_courant < a.prix_vente_initial ";
		//		if(trim($category)) $q.=" AND a.famille_code='".$category."'";
		//	if(trim($subcategory)) $q.=" AND a.sous_famille_code='".$subcategory."'";
		if (trim($storeId)) {
			$q .= " AND m.magasin_url = '" . $storeId . "'";
		}

		$q .= " ORDER BY a.date_modif DESC LIMIT $startLimit,$endLimit ";
		return ($this->fetchData($q));
	}

	function getCountProductPromo($category = '', $subcategory = '')
	{
		$q = "SELECT count(a.article_ID) total
				FROM articles a
				INNER JOIN product_sub_categories sc ON sc.sous_famille_code = a.sous_famille_code
				INNER JOIN magasins m ON m.magasin_code = a.magasin_code
				WHERE a.photo1 >''
				AND a.qte_stock>0
				AND a.web_actif='OUI'
				AND a.prix_vente_courant < a.prix_vente_initial ";
		if (trim($category)) $q .= " AND a.famille_code='" . $category . "'";
		if (trim($subcategory)) $q .= " AND a.sous_famille_code='" . $subcategory . "'";
		return ($this->fetchData($q));
	}

	function getStoreDetail($storeUrl)
	{
		$q = "
		SELECT
		m.nom_magasin storeName
		, m.adresse1
		, m.adresse2
		, m.code_postal storeZipCode
		, m.ville storeCity
		, m.telephone1
		, m.telephone2
		, m.magasin_code storeCode
		, m.magasin_url storeUrl
		, m.code_ape
		, m.comments
		, m.fax
		, m.GpsLat
		, m.GpsLon
		, m.motduresponsable
		FROM magasins m
		WHERE m.magasin_url = '$storeUrl'
		";
		return ($this->fetchData($q));
	}

	function getStoreImage($storeUrl)
	{
		$q = "
		SELECT
		m.nom_magasin storeName
		,mi.image_name storeHeaderImage
		,mi.status storeHeaderImageStatus
		FROM magasins m
		INNER JOIN magasin_images mi ON mi.magasin_code = m.magasin_code
		WHERE m.magasin_url = '$storeUrl'
		";
		return ($this->fetchData($q));
	}

	function insertQuery($q)
	{
		try {
			$sql = $this->db->Connect();
			$sql->beginTransaction();
			$res = $sql->prepare($q);
			$sql->exec($q);
			$lastId = $sql->lastInsertId();
			$sql->commit();
			return (true);
		} catch (Exception $e) //en cas d'erreur
		{
			//	 echo "\n before trx rollback";
			$sql->rollback();
			if ($this->iniObj->debugSQL) {
				$this->debugError($e);
			}
			return (false);
		}
	}

	/* Remove all trailing spaces and set var to UpperCase*/
	function clrSqlVarUpper($var)
	{

		return addslashes(strtoupper(preg_replace('/ +/', ' ', trim($var)))); // Removing all trailing spaces and addslashes as needed
	}


	function clrSqlVar($var)
	{

		return addslashes(preg_replace('/ +/', ' ', trim($var))); // Removing all trailing spaces and addslashes as needed
	}

	function authenticate($login, $password = '')
	{

		/*
		 * retrieve the first created billing address if the default address id from preferences is not available
		*/
		$q = "SELECT
				c.contact_id, c.contact_num,c.magasin_code store_code, c.email email_address, c.nom last_name
				, c.prenom first_name
				, m.nom_magasin store_name, m.ville store_city, m.code_postal store_zip, m.stock_visible stock_active
				, m.adresse1 store_address, m.adresse2 store_address2, m.telephone1 store_phone, m.comments store_comments
				, cp.contact_password passwordU, c.civilite, cp.language_code, cp.confirm_key salt
				FROM contacts c
				INNER JOIN contact_preferences cp ON cp.contact_num = c.contact_num
				INNER JOIN magasins m ON m.magasin_code = c.magasin_code
				WHERE c.contact_num =:login
		";

		return ($this->fetchData($q, array(":login" => $login)));
	}
	// function magasin_user($magasin_code)
	// {

	// 	/*
	// 	 * retrieve the first created billing address if the default address id from preferences is not available
	// 	*/
	// 	$q = "SELECT
	// 			c.contact_id, c.contact_num,c.magasin_code store_code, c.email email_address
	// 			, m.nom_magasin store_name, m.ville store_city, m.code_postal store_zip, m.stock_visible stock_active
	// 			, m.adresse1 store_address, m.adresse2 store_address2, m.telephone1 store_phone, m.comments store_comments
				
	// 			FROM contacts c
	// 			INNER JOIN magasins m ON m.magasin_code = c.magasin_code
	// 			WHERE m.magasin_code =:magasin_code
	// 	";

	// 	return ($this->fetchData($q, array(":magasin_code" => $magasin_code)));
	// }

	/*
     * function authentificate
     */

	function authenticateUser($contact_num)
	{
		$q = "SELECT c.contact_id, c.contact_num,c.magasin_code store_code, c.email email_address,
		 c.nom last_name, c.prenom first_name,cp.contact_num,cp.contact_password passwordU
			,cp.contact_num,c.role_contact,cp.prefered_magasin,m.nom_magasin store_name
			 FROM contacts c, contact_preferences cp,magasins m
			 WHERE cp.contact_num = c.contact_num
			 AND c.magasin_code = m.magasin_code
			 AND cp.contact_num = ? ";
		// die(print_r($q));
		return ($this->fetchData($q, array($contact_num)));
	}


	// function authenticateUser($contact_num, $password)
	// {
	// 	$q = "SELECT c.contact_id, c.contact_num,c.magasin_code store_code, c.email email_address, c.nom last_name
	// 		, c.prenom first_name,cp.contact_num
	// 		 FROM contacts c, contact_preferences cp
	// 		 WHERE cp.contact_num = c.contact_num
	// 		 AND cp.contact_num = ?  AND cp.contact_password= ?";
	// 	// die(print_r($q));
	// 	return ($this->fetchData($q, array($contact_num, $password)));
	// }


	/* update preferences for user */
	function updatePassword($login, $password, $salt)
	{
		$fields = array("contact_password" => $password, "salt" => $salt, "last_update_date" => "now()", "password_sent" => "1");
		$conditions = array("contact_num" => $login);
		if ($this->db->update("contact_preferences", $fields, $conditions)) {

			return (true);
		} else {

			return (false);
		}
	}
	function update_order_alert_encours($order_id)
	{
		$fields = array("alert_sent" => 1);
		$conditions = array("order_id" => $order_id);
		if ($this->db->update("orders", $fields, $conditions)) {
			return (true);
		} else {

			return (false);
		}
	}

	/* update preferences for user */
	function updateProduct(
		$contact_num,$article_type_code,$famille_code,$sous_famille_code,$reference_article_magasin,$libelle_produit,
		$prix_achat,$taux_commission,$prix_vente_initial,$prix_vente_courant,$qte_initiale,$qte_stock,
		$prix_net_deposant,$qte_stock_mini,$qte_stock_maxi,$qte_stock_alerte,$description,$photo_name1,
		$photo_name2,$photo_name3,$photo_name4,$taux_assurance,$magasin_code,$hauteur,$largeur,$poids,
		$profondeur,$code_deposant,$cout_emballage,$frais_port,$article_ID

	) {
		$fields = array(
			"contact_num" => $contact_num, "article_type_code" => $article_type_code,
			"famille_code" => $famille_code, "sous_famille_code" => $sous_famille_code,
			"reference_article_magasin" => $reference_article_magasin, "libelle" => $libelle_produit,
			"prix_achat" => $prix_achat, "taux_commission" => $taux_commission,
			"prix_vente_initial" => $prix_vente_initial, "prix_vente_courant" => $prix_vente_courant,
			"qte_initial" => $qte_initiale, "qte_stock" => $qte_stock, "prix_net_deposant" => $prix_net_deposant,
			"qte_stock_mini" => $qte_stock_mini, "qte_stock_maxi" => $qte_stock_maxi,
			"qte_stock_alerte" => $qte_stock_alerte, "description" => $description, "photo1" => $photo_name1,
			"photo2" => $photo_name2, "photo3" => $photo_name3, "photo4" => $photo_name4,
			"taux_assurance" => $taux_assurance, "magasin_code" => $magasin_code, "hauteur" => $hauteur,
			"largeur" => $largeur, "poids" => $poids, "profondeur" => $profondeur,
			"code_utilisateur" => $code_deposant, "cout_emballage" => $cout_emballage, "frais_de_port" => $frais_port, "date_modif" => "now()"
		);
		$conditions = array("article_ID" => $article_ID);
		// if ($this->db->update("articles", $fields, $conditions)) {

			return ($this->db->update("articles", $fields, $conditions));
		// } else {

			// return (false);
		// }
	}
	
	/*
	 * check if user has an e-mail address
	 */
	function customerEmailExist($login)
	{
		$q = "SELECT c.contact_id, c.contact_num,c.magasin_code store_code, c.email email_address, c.nom last_name
		, c.prenom first_name
		, m.nom_magasin store_name, m.ville store_city, m.code_postal store_zip, m.stock_visible stock_active
		, m.adresse1 store_address, m.adresse2 store_address2, m.telephone1 store_phone, m.comments store_comments
		, cp.contact_password passwordU, c.civilite, cp.language_code, cp.confirm_key salt
		FROM contacts c
		INNER JOIN contact_preferences cp ON cp.contact_num = c.contact_num
		INNER JOIN magasins m ON m.magasin_code = c.magasin_code
		WHERE c.email >'' and  c.email=:login";
		return ($this->fetchData($q, array(":login" => trim($login))));
	}
	/*
	 * check if user has an e-mail address
	 */
	function getAllCustomerExcept($login)
	{
		$q = "SELECT *
			FROM contacts c 
			INNER JOIN magasins m on m.magasin_code=c.magasin_code 
			where c.contact_num>'' and  c.contact_num<>:login ORDER BY contact_id DESC LIMIT 10 ";
		return ($this->fetchData($q, array(":login" => trim($login))));
	}
	/*
	 * check if user has an e-mail address
	 */
	function customerDetail($login)
	{
		$q = "SELECT *
			FROM contacts c 
			where c.contact_num>'' and  c.contact_id=:login";
		return ($this->fetchData($q, array(":login" => trim($login))));
	}

	function customerExist($login)
	{
		$q = "SELECT c.email, c.language_code, c.contact_num,m.nom_magasin store_name , cp.contact_password
		 FROM contacts c 
		 INNER JOIN contact_preferences cp ON cp.contact_num = c.contact_num
		 INNER JOIN magasins m ON m.magasin_code = c.magasin_code
		 where c.contact_num>'' and  c.contact_num=:login";
		return ($this->fetchData($q, array(":login" => trim($login))));
	}


	function getPasswordNotSent()
	{
		/* check users that have a billing address */
		$q = "SELECT DISTINCT c.email email, c.contact_num login, m.nom_magasin store_name,cp.language_code lang, cp.contact_password password, cp.confirm_key salt
				FROM contacts c
				INNER JOIN contact_preferences cp ON cp.contact_num = c.contact_num
				INNER JOIN magasins m on m.magasin_code = c.magasin_code
				WHERE cp.password_sent=0  LIMIT 0,5";
		return ($this->fetchData($q));
	}


	/* Retrieve a Template from Db */
	function getTemplate($id)
	{
		$q = "select subject, body from mail_templates where mail_templates_id='" . trim($id) . "' and deleted_flag='0' and is_active='1'  ";

		return ($this->fetchData($q, array(":mail_templates_id" => $id)));
	}


	function debugError($e, $query = '')
	{
		//Display error
		if ($this->iniObj->debugSQL) {
			if (is_object($query)) {
				echo  "<br>DEBUG <br>Query  OBJECT: " . $query->queryString;
			} else {
				echo  "<br>DEBUG <br>Query : $query <br>";
			}
			if (is_object($e)) {
				echo "<br>Transaction failed - Error reported below :" .
					"<br />Error : " . $e->getMessage() . "<br />";
				"<br />N° : " . $e->getCode();
				"<br>Complete Error <pre>: ";
				// print_r($e);
				echo "</pre>";
			}
		}
	}

	function dataToHtmlForm($input)
	{
		if (is_array($input)) {
			$var = array();
			foreach ($input as $k => $v) {
				$var[$v->id] = $v->name;
			}
			return ($var);
		}
	}

	function getAllUsersWithNoPassword()
	{
		/*
		 * select all rows from contact_preferences where contact_num does not exist yet
		 */
		$q = "SELECT  c.contact_num, IF(c.prospection!='OUI','NON','OUI') prospection, c.language_code, c.email
					FROM contacts c
					LEFT JOIN contact_preferences cp ON cp.contact_num = c.contact_num
					WHERE cp.contact_num IS NULL
					";
		return ($this->fetchData($q));
	}

	/* this method adds a tracking in db along with the user info + shipping info */
	function insertNewUserPassword($num, $pass, $prospect, $lang, $key, $roles)
	{
		try {
			$sql = $this->db->Connect();
			$q = 'INSERT contact_preferences (contact_num, contact_password,prospection,language_code,confirm_key,salt,email_confirme,last_update_date, roles ) ' .
				' VALUES ("' . $num . '","' . $pass . '","' . $prospect . '","' . $lang . '","' . $key . '","' . $key . '",0, now(), "' . $roles . '")';

			$sql->beginTransaction();
			$res = $sql->prepare($q);
			$sql->exec($q);
			//        $lastId=$sql->lastInsertId();
			$sql->commit();
			return (true);
		} catch (Exception $e) //en cas d'erreur
		{
			$sql->rollback();
			if ($this->iniObj->debugSQL) {
				$this->debugError($e);
			}
			return ($e);
		}
	}

	/* this method adds a tracking in db along with the user info + shipping info */
	function insertNewUserInfo($num, $type_contacts_code = "", $civilite = "", $nom, $prenom, $email)
	{
		try {
			$sql = $this->db->Connect();
			$q = 'INSERT contacts (contact_num, type_contacts_code,civilite,nom,prenom,email, date_creation, date_modif) ' .
				' VALUES ("' . $num . '","' . $type_contacts_code . '","' . $civilite . '","' . $nom . '","' . $prenom . '","' . $email . '", now(), now())';

			$sql->beginTransaction();
			$res = $sql->prepare($q);
			$sql->exec($q);
			//        $lastId=$sql->lastInsertId();
			$sql->commit();
			return (true);
		} catch (Exception $e) //en cas d'erreur
		{
			$sql->rollback();
			if ($this->iniObj->debugSQL) {
				$this->debugError($e);
			}
			return ($e);
		}
	}


	/* update contact_preferences status to prevent new email */
	function setPasswordSent($id)
	{
		$q = "update contact_preferences set password_sent=1  WHERE contact_num='$id'";
		if ($this->db->query($q)) return true;
		else return false;
	}

	function sendDebugEmail($err, $function, $request = "")
	{
		$content = "Error from the function " . $function . "<br>\n " . print_r($err, true) . "\n <br>" . print_r($request, true);
		$this->lib->sendEmailAlert($this->iniObj->emailContact, $this->iniObj->DbEmail, "SQL Error", $content);
	}


	function insertNewOrder(
		$customer_number,
		$order_status,
		$shipping_type_id,
		$shipping_options_id,
		$shipping_amount,
		$total_amount,
		$magasin_code,
		$valid,
		$display_order_id
	) {
		try {
			$sql = $this->db->Connect();
			$q = 'INSERT orders (customer_number, order_date, order_status, shipping_type_id, shipping_options_id, shipping_amount, total_amount, magasin_code,display_order_id) ' .
				' VALUES ("' . $customer_number . '", now(), "' . $order_status . '","' . $shipping_type_id . '","' . $shipping_options_id . '","' . $shipping_amount . '","' . $total_amount . '","' . $magasin_code . '", "' . $display_order_id . '")';

			$sql->beginTransaction();
			$res = $sql->prepare($q);
			$sql->exec($q);
			$lastId = $sql->lastInsertId();
			$sql->commit();
			return ($lastId);
		} catch (Exception $e) //en cas d'erreur
		{
			$sql->rollback();
			if ($this->iniObj->debugSQL) {
				$this->debugError($e);
			}
			return ($e);
		}
	}


	function insertNewOrderLine(
		$order_id,
		$reference_web,
		$quantity_requested,
		$quantity_available,
		$status,
		$magasin_code,
		$price
	) {
		try {
			$sql = $this->db->Connect();
			$q = 'INSERT order_lines (order_id, reference_web, quantity_requested, quantity_available, status, magasin_code, price) ' .
				' VALUES ("' . $order_id . '","' . $reference_web . '","' . $quantity_requested . '","' . $quantity_available . '","' . $status . '","' . $magasin_code . '","' . $price . '")';

			$sql->beginTransaction();
			$res = $sql->prepare($q);
			$sql->exec($q);
			$lastId = $sql->lastInsertId();
			$sql->commit();
			return ($lastId);
		} catch (Exception $e) //en cas d'erreur
		{
			$sql->rollback();
			if ($this->iniObj->debugSQL) {
				$this->debugError($e);
			}
			return ($e);
		}
	}

	//get Orders Lines By Order ID
	function getOrdersLinesByOrder($order_id)
	{
		/*
		 * retrieve the first created billing address if the default address id from preferences is not available
		*/
		$q = "SELECT ol.order_lines_id order_lines_id, ol.reference_web reference_web, ol.quantity_requested quantity_requested, ol.quantity_available quantity_available, ol.magasin_code magasin_code, ol.price price, a.libelle libelle, a.photo1 photo1,  a.famille_code famille_code, a.sous_famille_code sous_famille_code, o.order_date order_date,ol.status
			FROM order_lines ol 
			INNER JOIN orders o ON ol.order_id = o.order_id
			INNER JOIN articles a ON ol.reference_web = a.article_ID
			WHERE o.order_id =:order_id
		";
		return ($this->fetchData($q, array(":order_id" => $order_id)));
	}

	//get connected customer orders
	function getOrders($customer_num)
	{
		/*
		 * retrieve the first created billing address if the default address id from preferences is not available
		*/
		$q = "SELECT o.* , 
			COUNT(ol.order_lines_id) as number_of_items,
			GROUP_CONCAT(a.libelle SEPARATOR ' ; ') AS libelles 
			FROM orders o
			INNER JOIN order_lines ol ON ol.order_id = o.order_id
			INNER JOIN articles a ON ol.reference_web = a.article_ID
			WHERE o.customer_number =:customer_num
			GROUP  BY o.order_id
		";
		return ($this->fetchData($q, array(":customer_num" => $customer_num)));
	}

	//get all customers orders
	function getPaidOrders($customer_num)
	{
		$q = "SELECT o.* , 
			COUNT(ol.order_lines_id) as number_of_items,
			GROUP_CONCAT(a.libelle SEPARATOR ' ; ') AS libelles 
			FROM orders o
			INNER JOIN order_lines ol ON ol.order_id = o.order_id
			INNER JOIN articles a ON ol.reference_web = a.article_ID
			WHERE o.customer_number =:customer_num AND o.order_status LIKE 'REGLE'
			GROUP  BY o.order_id
		";
		return ($this->fetchData($q, array(":customer_num" => $customer_num)));
	}

	function getInProgressOrders($customer_num)
	{
		$q = "SELECT o.* , 
			COUNT(ol.order_lines_id) as number_of_items,
			GROUP_CONCAT(a.libelle SEPARATOR ' ; ') AS libelles 
			FROM orders o
			INNER JOIN order_lines ol ON ol.order_id = o.order_id
			INNER JOIN articles a ON ol.reference_web = a.article_ID
			WHERE o.customer_number =:customer_num AND o.order_status LIKE 'EN_COURS' 
			GROUP  BY o.order_id
		";
		return ($this->fetchData($q, array(":customer_num" => $customer_num)));
	}

	function getPendingOrders($customer_num)
	{
		$q = "SELECT o.* , 
			COUNT(ol.order_lines_id) as number_of_items,
			GROUP_CONCAT(a.libelle SEPARATOR ' ; ') AS libelles 
			FROM orders o
			INNER JOIN order_lines ol ON ol.order_id = o.order_id
			INNER JOIN articles a ON ol.reference_web = a.article_ID
			WHERE o.customer_number =:customer_num AND o.order_status LIKE 'EN_ATTENTE'
			GROUP  BY o.order_id
		";
		return ($this->fetchData($q, array(":customer_num" => $customer_num)));
	}
	
	function getCancelledOrders($customer_num)
	{
		$q = "SELECT o.* , 
			COUNT(ol.order_lines_id) as number_of_items,
			GROUP_CONCAT(a.libelle SEPARATOR ' ; ') AS libelles 
			FROM orders o
			INNER JOIN order_lines ol ON ol.order_id = o.order_id
			INNER JOIN articles a ON ol.reference_web = a.article_ID
			WHERE o.customer_number =:customer_num AND o.order_status LIKE 'ANNULEE'
			GROUP  BY o.order_id
		";
		return ($this->fetchData($q, array(":customer_num" => $customer_num)));
	}

	function getClosedOrders($customer_num)
	{


		$q = "SELECT o.* , 
			COUNT(ol.order_lines_id) as number_of_items,
			GROUP_CONCAT(a.libelle SEPARATOR ' ; ') AS libelles 
			FROM orders o
			INNER JOIN order_lines ol ON ol.order_id = o.order_id
			INNER JOIN articles a ON ol.reference_web = a.article_ID
			WHERE o.customer_number =:customer_num AND o.order_status LIKE 'LIVRE'
			GROUP  BY o.order_id 
		";
		return ($this->fetchData($q, array(":customer_num" => $customer_num)));
	}

	//get connected customer orders
	function getOrdersByMag($code_magasin)
	{
		/*
		 * retrieve the first created billing address if the default address id from preferences is not available
		*/
		$q = "SELECT o.* , a.libelle,
			COUNT(ol.order_lines_id) as number_of_items,
			GROUP_CONCAT(a.libelle SEPARATOR ' ; ') AS libelles , 
			GROUP_CONCAT(a.libelle SEPARATOR ' ; ') AS libelles 
			FROM orders o 
			INNER JOIN order_lines ol ON ol.order_id = o.order_id
			INNER JOIN articles a ON ol.reference_web = a.article_ID
			WHERE a.magasin_code =:code_mag
			GROUP  BY o.order_id 
		";
		return ($this->fetchData($q, array(":code_mag" => $code_magasin)));
	}

	//get all customers orders
	function getPaidOrdersByMag($code_magasin)
	{
		$q = "SELECT o.* , 
			COUNT(ol.order_lines_id) as number_of_items,
			GROUP_CONCAT(a.libelle SEPARATOR ' ; ') AS libelles 
			FROM orders o
			INNER JOIN order_lines ol ON ol.order_id = o.order_id
			INNER JOIN articles a ON ol.reference_web = a.article_ID
			WHERE a.magasin_code =:code_mag AND o.order_status LIKE 'REGLE'
			GROUP  BY o.order_id 
		";
		return ($this->fetchData($q, array(":code_mag" => $code_magasin)));
	}

	function getInProgressOrdersByMag($code_magasin)
	{
		$q = "SELECT o.* , 
			COUNT(ol.order_lines_id) as number_of_items,
			GROUP_CONCAT(a.libelle SEPARATOR ' ; ') AS libelles 
			FROM orders o
			INNER JOIN order_lines ol ON ol.order_id = o.order_id
			INNER JOIN articles a ON ol.reference_web = a.article_ID
			WHERE a.magasin_code =:code_mag AND o.order_status LIKE 'EN_COURS'
			GROUP  BY o.order_id 
		";
		return ($this->fetchData($q, array(":code_mag" => $code_magasin)));
	}

	function getPendingOrdersByMag($code_magasin)
	{
		$q = "SELECT o.* , 
			COUNT(ol.order_lines_id) as number_of_items,
			GROUP_CONCAT(a.libelle SEPARATOR ' ; ') AS libelles 
			FROM orders o
			INNER JOIN order_lines ol ON ol.order_id = o.order_id
			INNER JOIN articles a ON ol.reference_web = a.article_ID
			WHERE a.magasin_code =:code_mag AND o.order_status LIKE 'EN_ATTENTE'
			GROUP  BY o.order_id 

		";
		return ($this->fetchData($q, array(":code_mag" => $code_magasin)));
	}
	
	function getCancelledOrdersByMag($code_magasin)
	{
		$q = "SELECT o.* , 
			COUNT(ol.order_lines_id) as number_of_items,
			GROUP_CONCAT(a.libelle SEPARATOR ' ; ') AS libelles 
			FROM orders o
			INNER JOIN order_lines ol ON ol.order_id = o.order_id
			INNER JOIN articles a ON ol.reference_web = a.article_ID
			WHERE a.magasin_code =:code_mag AND o.order_status LIKE 'ANNULEE'
			GROUP  BY o.order_id 

		";
		return ($this->fetchData($q, array(":code_mag" => $code_magasin)));
	}

	function getClosedOrdersByMag($code_magasin)
	{

		$q = "SELECT o.* , 
			COUNT(ol.order_lines_id) as number_of_items,
			GROUP_CONCAT(a.libelle SEPARATOR ' ; ') AS libelles 
			FROM orders o
			INNER JOIN order_lines ol ON ol.order_id = o.order_id
			INNER JOIN articles a ON ol.reference_web = a.article_ID
			WHERE a.magasin_code =:code_mag AND o.order_status LIKE 'LIVRE'
			GROUP  BY o.order_id 
		";
		return ($this->fetchData($q, array(":code_mag" => $code_magasin)));
	}

	function updateOrder($data)
	{
		$q = 'UPDATE orders SET order_status = "' . $data['status'] . '" WHERE order_id="' . $data['order_id'] . '"';

		if ($this->db->query($q)) return true;
		else return false;
	}
	
	

	function updateOrderlinesAvailability($data)
	{
		$fields = array("status" => $data['status']);
		$conditions = array("order_lines_id" => $data['order_lines_id']);
		if ($this->db->update("order_lines", $fields, $conditions)) {
			return (true);
		} else {
			return (false);
		}
	}

	function getOrderCustomer($order_id)
	{
		$q = "SELECT * FROM orders o where o.order_id =:order_id";
		return ($this->fetchData($q, array(":order_id" => $order_id)));
	}

	function getUserBylogin($order_customer)
	{
		$q = "SELECT * FROM contacts u where u.contact_num =:contact_num";
		return ($this->fetchData($q, array(":contact_num" => $order_customer)));
	}

	function registerClientUser($nom_user, $prenom_user, $email, $pass, $phone_number, $profile_id)
	{

		try {
			$sql = $this->db->Connect();

			$q = "INSERT INTO contacts(nom_user, prenom_user, email_address, password, phone_number, profile_id, date_creation) " .
				' VALUES ("' . $nom_user . '", "' . $prenom_user . '", "' . $email . '", "' . $pass . '", "' . $phone_number . '", ' . $profile_id . ', now())';
			//return $q; 
			$sql->beginTransaction();
			$sql->prepare($q);
			$sql->exec($q);
			$sql->commit();

			return true;
		} catch (Exception $e) { //en cas d'erreur

			$sql->rollback();
			if ($this->iniObj->debugSQL) {
				$this->debugError($e);
			}
			return (false);
		}
	}


	function getUserByEmail($email)
	{
		$q = "SELECT * FROM contacts u where u.email =:email";
		return ($this->fetchData($q, array(":email" => $email)));
	}
	function getAllUsers()
	{
		$q = "SELECT * FROM contacts";
		return ($this->fetchData($q));
	}
	function getProductByName($libelle)
	{
		$q = "SELECT * FROM articles a where a.libelle =:libelle";
		return ($this->fetchData($q, array(":libelle" => $libelle)));
	}
	function getContactByEmail($email)
	{
		$q = "SELECT * FROM contacts c where c.contact_email =:email";
		return ($this->fetchData($q, array(":email" => $email)));
	}
	function getCompanies()
	{
		$q = "SELECT * FROM companies ORDER BY company_id DESC";
		return ($this->fetchData($q));
	}
	function getContacts()
	{
		$q = "SELECT * FROM contacts ORDER BY contact_id DESC";
		return ($this->fetchData($q));
	}

	function getCompanyByName($company_name)
	{
		$q = "SELECT * FROM companies c where c.company_name =:c_name";
		return ($this->fetchData($q, array(":c_name" => $company_name)));
	}

	function insertNewCompany($company_name, $company_zipcode, $company_city, $activity_area, $company_type, $code_naf)
	{
		try {
			$sql = $this->db->Connect();
			$q = 'INSERT INTO companies (company_name, company_zipcode, city,activity_area, company_type,code_naf) ' .
				' VALUES ("' . $company_name . '","' . $company_zipcode . '","' . $company_city . '","' . $activity_area . '","' . $company_type . '", "' .$code_naf .  '")';

			$sql->beginTransaction();
			$res = $sql->prepare($q);
			$sql->exec($q);
			//        $lastId=$sql->lastInsertId();
			$sql->commit();
			return (true);
		} catch (Exception $e) //en cas d'erreur
		{
			$sql->rollback();
			if ($this->iniObj->debugSQL) {
				$this->debugError($e);
			}
			return ($e);
		}
	}
	function insertNewContact($first_name, $last_name, $email, $phone_number, $fonction, $is_active,$interest_level,$company,$is_manager)
	{
		try {
			$sql = $this->db->Connect();
			$q = 'INSERT INTO contacts (contact_firstname, contact_lastname, contact_email,phone_number, title,is_active,interest_level,company_id,is_manager) ' .
				' VALUES ("' . $first_name . '","' . $last_name . '","' . $email . '","' . $phone_number . '","' . $fonction . '", "' .$is_active . '", "' .$interest_level . '", "' .$company . '", "' .$is_manager .  '")';

			$sql->beginTransaction();
			$res = $sql->prepare($q);
			$sql->exec($q);
			//        $lastId=$sql->lastInsertId();
			$sql->commit();
			return (true);
		} catch (Exception $e) //en cas d'erreur
		{
			$sql->rollback();
			if ($this->iniObj->debugSQL) {
				$this->debugError($e);
			}
			return ($e);
		}
	}
	function insertNewMission($first_name, $last_name, $email, $phone_number, $fonction, $is_active,$interest_level,$company,$is_manager)
	{
		try {
			$sql = $this->db->Connect();
			$q = 'INSERT INTO missions (mission_label, contact_id , company_id ,mission_date) ' .
				' VALUES ("' . $first_name . '","' . $last_name . '","' . $email . '","' . $phone_number . '","' . $fonction . '", "' .$is_active . '", "' .$interest_level . '", "' .$company . '", "' .$is_manager .  '")';

			$sql->beginTransaction();
			$res = $sql->prepare($q);
			$sql->exec($q);
			//        $lastId=$sql->lastInsertId();
			$sql->commit();
			return (true);
		} catch (Exception $e) //en cas d'erreur
		{
			$sql->rollback();
			if ($this->iniObj->debugSQL) {
				$this->debugError($e);
			}
			return ($e);
		}
	}
	function insertNewService($first_name, $last_name, $email, $phone_number, $fonction, $is_active,$interest_level,$company,$is_manager)
	{
		try {
			$sql = $this->db->Connect();
			$q = 'INSERT INTO desired_services (libelle_service) ' .
				' VALUES ("' . $first_name . '","' . $last_name . '","' . $email . '","' . $phone_number . '","' . $fonction . '", "' .$is_active . '", "' .$interest_level . '", "' .$company . '", "' .$is_manager .  '")';

			$sql->beginTransaction();
			$res = $sql->prepare($q);
			$sql->exec($q);
			//        $lastId=$sql->lastInsertId();
			$sql->commit();
			return (true);
		} catch (Exception $e) //en cas d'erreur
		{
			$sql->rollback();
			if ($this->iniObj->debugSQL) {
				$this->debugError($e);
			}
			return ($e);
		}
	}

	function saveNewProduct(
		$contact_num,
		$article_type_code,
		$famille_code,
		$sous_famille_code,
		$reference_article_magasin,
		$libelle,
		$prix_achat,
		$taux_commission,
		$prix_vente_initial,
		$prix_vente_courant,
		$qte_initial,
		$qte_stock,
		$prix_net_deposant,
		$qte_stock_mini,
		$qte_stock_maxi,
		$qte_stock_alerte,
		$web_actif,
		$description,
		$photo1_name,
		$photo2_name,
		$photo3_name,
		$photo4_name,
		$taux_assurance,
		$magasin_code,
		$hauteur,
		$largeur,
		$poids,
		$profondeur,
		$cout_emballage,
		$frais_port
	) {
		try {
			$sql = $this->db->Connect();
			$q = 'INSERT INTO articles (contact_num,article_type_code, famille_code, sous_famille_code, reference_article_magasin, date_entree, libelle, prix_achat, taux_commission, prix_vente_initial, prix_vente_courant, qte_initial, qte_stock,prix_net_deposant, qte_stock_mini, qte_stock_maxi, qte_stock_alerte,web_actif, description,photo1,photo2,photo3,photo4,taux_assurance, magasin_code,hauteur, largeur, poids, profondeur, cout_emballage, frais_de_port) ' .
				' VALUES ("' . $contact_num . '","' . $article_type_code . '","' . $famille_code . '","' . $sous_famille_code . '","' . $reference_article_magasin . '", now(),"' . $libelle . '","' . $prix_achat . '", "' . $taux_commission . '","' . $prix_vente_initial . '","' . $prix_vente_courant .  '","' . $qte_initial . '","' . $qte_stock . '","' . $prix_net_deposant . '","' . $qte_stock_mini .
				'","' .	$qte_stock_maxi . '","' . $qte_stock_alerte . '","' . $web_actif . '","' . $description . '","' . $photo1_name . '","' . $photo2_name . '","' . $photo3_name . '","' . $photo4_name . '","' . $taux_assurance . '","' . $magasin_code . '","' . $hauteur . '","' . $largeur . '","' . $poids . '","' . $profondeur  . '","' . $cout_emballage . '","' . $frais_port .  '")';

			$sql->beginTransaction();
			$res = $sql->prepare($q);
			$sql->exec($q);
			//        $lastId=$sql->lastInsertId();
			$sql->commit();
			return (true);
		} catch (Exception $e) //en cas d'erreur
		{
			$sql->rollback();
			if ($this->iniObj->debugSQL) {
				$this->debugError($e);
			}
			return ($e);
		}
	}



	function insertNewBusiness($magasin_code, $business_name, $nom_user, $business_address_1,$business_address_2, $zipcode, $business_city, $phone_number, $email_directeur, $prenom_user,$business_email, $magasin_url)
	{
		try {
			$sql = $this->db->Connect();
			$q = 'INSERT INTO magasins (magasin_code, nom_magasin,nom_contact, adresse1,adresse2,code_postal, ville, telephone1,email_directeur,prenom_contact, email_magasin,magasin_url) ' .
				' VALUES ("' . $magasin_code . '","' . $business_name . '","' . $nom_user . '","' . $business_address_1 . '","' . $business_address_2 . '","' . $zipcode . '","' . $business_city . '","' . $phone_number . '", "' . $email_directeur . '", "' . $prenom_user . '", "' . $business_email . '", "' . $magasin_url . '")';

			$sql->beginTransaction();
			$res = $sql->prepare($q);
			$sql->exec($q);
			//        $lastId=$sql->lastInsertId();
			$sql->commit();
			return (true);
		} catch (Exception $e) //en cas d'erreur
		{
			$sql->rollback();
			if ($this->iniObj->debugSQL) {
				$this->debugError($e);
			}
			return ($e);
		}
	}

	function insertNewUserPref($contact_num, $contact_password)
	{
		try {
			$sql = $this->db->Connect();
			$q = 'INSERT INTO contact_preferences (contact_num, contact_password) ' .
				' VALUES ("' . $contact_num . '","' . $contact_password .  '")';

			$sql->beginTransaction();
			$res = $sql->prepare($q);
			$sql->exec($q);
			//        $lastId=$sql->lastInsertId();
			$sql->commit();
			return (true);
		} catch (Exception $e) //en cas d'erreur
		{
			return ($e);
			$sql->rollback();
			if ($this->iniObj->debugSQL) {
				$this->debugError($e);
			}
		}
	}

	/* Return contact preferences */
	function getPreferences($userId)
	{
		$q = "SELECT
				 cp.contact_password, email_confirme, confirm_key, cp.language_code lang
				,cp.prospection, cp.last_update_date, cp.prospection_partenaire, cp.last_login_date, cp.password_sent, cp.salt, cp.prefered_magasin
				FROM contact_preferences cp 
				INNER JOIN contacts c ON cp.contact_num = c.contact_num
				WHERE c.contact_id =:id AND cp.language_code IS NOT NULL AND cp.prospection IS NOT NULL";
		return ($this->fetchData($q, array(":id" => $userId)));
	}
	/* Return products family */
	function getProductFamilyCode()
	{
		$q = "SELECT DISTINCT famille_code,libelle,language_code 
		FROM product_categories";
		return ($this->fetchData($q));
	}
	/* Return products sub_family */
	function getProductSubFamilyCode()
	{
		$q = "SELECT DISTINCT sous_famille_code,famille_code,libelle,language_code FROM product_sub_categories";
		return ($this->fetchData($q));
	}

	/* update preferences for confirmed user */
	function updateConfirmedUser($login, $password, $salt)
	{
		$fields = array("contact_password" => $password, "salt" => $salt, "last_update_date" => "now()", "password_sent" => "1");
		$conditions = array("contact_num" => $login);
		if ($this->db->update("contact_preferences", $fields, $conditions)) {

			return (true);
		} else {

			return (false);
		}
	}
	/* update preferences for confirmed user */
	function updateUser($civilite, $nom, $prenom, $adresse1, $code_postal, $ville, $telephone1, $email, $magasin_code = '', $role_contact, $contact_id)
	{
		$fields = array(
			"civilite" => $civilite, "nom" => $nom, "prenom" => $prenom, "adresse1" => $adresse1, "code_postal" => $code_postal, "ville" => $ville,
			"telephone1" => $telephone1, "email" => $email, "magasin_code" => $magasin_code, "role_contact" => $role_contact, "date_modif" => "now()"
		);
		$conditions = array("contact_id" => $contact_id);
		if ($this->db->update("contacts", $fields, $conditions)) {

			return (true);
		} else {

			return (false);
		}
	}

	function updateLoginDate($user)
	{
		$fields = array("last_login_date" => "now()");
		$conditions = array("contact_num" => $user);
		if ($this->db->update("contact_preferences", $fields, $conditions)) (true);
		else return (false);
	}

	/* update preferences for user */
	function updatePreferences($user, $lang, $prospection, $magasin)
	{
		$fields = array(
			"language_code" => $lang, "prospection" => $prospection, "prefered_magasin" => $magasin, "last_update_date" => "now()"
		);
		$conditions = array("contact_num" => $user);
		if ($this->db->update("contact_preferences", $fields, $conditions)) return (true);
		else return (false);
	}

	//get connected customer sells
	function getSales($customer_num)
	{

		/*
		 * retrieve the first created billing address if the default address id from preferences is not available
		*/
		$q = "SELECT vdtl.qte_vente qte_vente,mag.nom_magasin nom_magasin,art.libelle libelle,art.article_ID article_id,vdtl.prix_net_deposant prix_net_deposant,vdtl.date_cloture_vente date_vente FROM ventes_details vdtl
		INNER JOIN contacts ct ON ct.contact_num=vdtl.contact_num
		INNER JOIN magasins mag ON mag.magasin_code=vdtl.magasin_code
		INNER JOIN articles art ON art.contact_num=ct.contact_num
		WHERE vdtl.contact_num=:customer_num LIMIT 15
		";
		return ($this->fetchData($q, array(":customer_num" => $customer_num)));
	}

	/* retrieve customer articles */
	function getCustomerArticles($customer_num)
	{
		$q = "SELECT c.contact_num, a.contact_num, a.contrat_num, a.article_type_code,a.reference_article_magasin,
		a.famille_code, a.date_entree, a.libelle, a.prix_achat, a.taux_commission, a.prix_vente_initial,
		a.prix_vente_courant, qte_initial, a.qte_stock, a.qte_stock_mini, a.qte_stock_maxi, a.qte_stock_alerte,
		 a.description,a.photo1,a.photo2,a.photo3,a.photo4, a.magasin_code ,a.date_creation,a.date_modif,
		 a.code_utilisateur,a.etat_article,a.article_ID
		 FROM articles a 
			INNER JOIN contacts c ON c.contact_num = a.contact_num
			WHERE c.contact_num =:customer_num LIMIT 15
		";
		return ($this->fetchData($q, array(":customer_num" => $customer_num)));
	}

	/* retrieve customer without connected one */
	function getOtherCustomers($customer_num)
	{
		$q = "SELECT * FROM contacts c 
			WHERE c.contact_num <>:customer_num LIMIT 20
		";
		return ($this->fetchData($q, array(":customer_num" => $customer_num)));
	}
	function delete_product($id)
	{
		$q = "DELETE FROM articles a
			WHERE a.article_ID =:id
		";
		return ($this->fetchData($q, array(":id" => $id)));
	}


	function getCountMagOrders($magasin)
	{
		$q = "SELECT
				count(
				o.order_id) total
				FROM orders o 
				";
		if (trim($magasin)) $q .= " WHERE o.magasin_code ='" . $magasin . "'";

		return ($this->fetchData($q));
	}
	function getProductStockQuantity($product_id)
	{
		$q = "SELECT qte_stock, libelle
				FROM articles a
				";
		if (trim($product_id)) $q .= " WHERE a.article_ID ='" . $product_id . "'";

		return ($this->fetchData($q));
	}

	function getCountMagPendingOrders($magasin)
	{
		$q = "SELECT
				count(
				o.order_id) total
				FROM orders o 
				";
		if (trim($magasin)) $q .= " WHERE o.magasin_code ='" . $magasin . "' AND order_status LIKE 'EN_COURS'";

		return ($this->fetchData($q));
	}
	function getCountMagClosedOrders($magasin)
	{
		$q = "SELECT
				count(
				o.order_id) total
				FROM orders o 
				
				";
		if (trim($magasin)) $q .= " WHERE o.magasin_code ='" . $magasin . "' AND order_status LIKE 'REGLE'";

		return ($this->fetchData($q));
	}
	function getCountMagInProgressOrders($magasin)
	{
		$q = "SELECT
				count(
				o.order_id) total
				FROM orders o 
				
				";
		if (trim($magasin)) $q .= " WHERE o.magasin_code ='" . $magasin . "' AND order_status LIKE 'EN_ATTENTE'";

		return ($this->fetchData($q));
	}
	function getCountMagDeliveredOrders($magasin)
	{
		$q = "SELECT
				count(
				o.order_id) total
				FROM orders o 
				
				";
		if (trim($magasin)) $q .= " WHERE o.magasin_code ='" . $magasin . "' AND order_status LIKE 'LIVRE'";

		return ($this->fetchData($q));
	}



	function getCountOrders($customer_number)
	{
		$q = "SELECT
				count(
				o.order_id) total
				FROM orders o 
				";
		if (trim($customer_number)) $q .= " WHERE o.customer_number ='" . $customer_number . "'";

		return ($this->fetchData($q));
	}
	function getCountPendingOrders($customer_number)
	{
		$q = "SELECT
				count(
				o.order_id) total
				FROM orders o 
				";
		if (trim($customer_number)) $q .= " WHERE o.customer_number ='" . $customer_number . "' AND order_status LIKE 'EN_COURS'";

		return ($this->fetchData($q));
	}
	function getCountClosedOrders($customer_number)
	{
		$q = "SELECT
				count(
				o.order_id) total
				FROM orders o 
				
				";
		if (trim($customer_number)) $q .= " WHERE o.customer_number ='" . $customer_number . "' AND order_status LIKE 'REGLE'";

		return ($this->fetchData($q));
	}
	function getCountInProgressOrders($customer_number)
	{
		$q = "SELECT
				count(
				o.order_id) total
				FROM orders o 
				
				";
		if (trim($customer_number)) $q .= " WHERE o.customer_number ='" . $customer_number . "' AND order_status LIKE 'EN_ATTENTE'";

		return ($this->fetchData($q));
	}
	function getCountDeliveredOrders($customer_number)
	{
		$q = "SELECT
				count(
				o.order_id) total
				FROM orders o 
				
				";
		if (trim($customer_number)) $q .= " WHERE o.customer_number ='" . $customer_number . "' AND order_status LIKE 'LIVRE'";

		return ($this->fetchData($q));
	}
}

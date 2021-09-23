<?php 

class Cron
{
	public function insert($query)
	{
		GLOBAL $db, $connection;
		$this->_db = $db;
		$this->_db->query($query);
	}
	
	public function update($table_name, $query, $where)
	{
		GLOBAL $db, $connection;
		$this->_db = $db;
		$status = $this->_db->update($table_name, $query, $where );
		return $status;
	}
	
	public function delete($table_name, $where)
	{
		GLOBAL $db, $connection;
		$this->_db = $db;
		$status = $this->_db->delete($table_name, $where );
		return $status;
	}
		
		
	public function execute($query)
	{
		GLOBAL $db, $connection;
		$this->_db = $db;
		$result = $this->_db->fetchAll($query);
		
		if(is_array($result) && count($result))
		{
			return $result;
		}
	}
	
	public function fetch_all_columns()
	{
		GLOBAL $db, $connection;
		$this->_db = $db;
		
		$result = $this->_db->fetchCol($query);
		
		if(is_array($result) && count($result))
		{
			return $result;
		}		
		
	}
	
	public function get_unverified_contacts()
 	{
	 	GLOBAL $db, $connection;
	 	
	 	$limit_clause='';
		##Get the store object 
		$store_obj 	= get_config_ini_settings('store');
		
		##if store object exists
		if(is_object($store_obj))
		{
			$MAX_EMAIL_SEND_COUNT = $store_obj->MAX_EMAIL_SEND_COUNT;
		}
	 	
	 	if($MAX_EMAIL_SEND_COUNT >0)
	 	{
	 		$limit_clause = " LIMIT " . $MAX_EMAIL_SEND_COUNT;
	 	}
 	
 		$sql = " 
		SELECT 
			c.email,
			cp.* 
		FROM 
			contacts as c,
			contact_preferences as cp
		WHERE 
			c.contact_num=cp.contact_num AND 
			(cp.confirm_key IS NULL OR cp.confirm_key ='') AND 
			(cp.email_confirme='NON' OR cp.email_confirme IS NULL OR cp.email_confirme='') AND 
			( c.email IS NOT NULL AND c.email!='' )
			$limit_clause
			
	 	";
 		


		$DEBUG = get_config_ini_settings('DEBUG'); 
		if($DEBUG==1)
		{
			echo '<br>' . $sql;
		}
		
		try
		{
			$unverified_contacts_arr = $db->fetchAll($sql);

			if(is_array($unverified_contacts_arr) && count($unverified_contacts_arr))
			{
				return $unverified_contacts_arr;
			}
			else 
			{
				return false;				
			}
		}
		catch(Zend_Exception $e)
		{          
			echo $e->getMessage();exit;
			return false;
		}	   	
 	}	
 	
	public function getEmailMatter($template_name, $language_code)
 	{
	 	GLOBAL $db, $connection;
	 	
	 	if(!$language_code)
	 	{
	 		$language_code='fr';
	 	}
	 	
	 	$sql = " 
 		SELECT 
 			* 
			FROM 
				email_templates 
			WHERE 
				template_id		=	'$template_name' AND 
				language_code	=	'$language_code'
	 	";
 	
		$DEBUG = get_config_ini_settings('DEBUG'); 
		if($DEBUG==1)
		{
			echo '<br>' . $sql;
		}
			 	
		try
		{
			$template = $db->fetchRow($sql);

			if(is_array($template) && count($template))
			{
				return $template;
			}
			else 
			{
				return false;				
			}
		}
		catch(Zend_Exception $e)
		{          
			echo $e->getMessage();exit;
	   		return false;
	  	}	   	
 	}

 
	public function getConfigDetails($name)
 {
 	GLOBAL $db, $connection;
 	$sql = " 
 		SELECT 
 			* 
			FROM 
				config 
			WHERE 
				name='$name'
	 	";
 	
		try
		{
			$arr = $db->fetchRow($sql);

			if(is_array($arr) && count($arr))
			{
				return $arr;
			}
			else 
			{
				return false;				
			}
		}
		catch(Zend_Exception $e)
		{          
			echo $e->getMessage();exit;
   	return false;
  }	   	
 } 
 
 	public function update_contacts($data_arr, $contact_num)
 {
 	GLOBAL $db, $connection;
		
 	try
		{
			$status= $db->update('contact_preferences', $data_arr, " contact_num='$contact_num' " );
			
			//create select clause
			if($status)
			{
				return true;
			}
			else 
			{
				return false;				
			}
		}
		catch(Zend_Exception $e)
		{          
			echo $e->getMessage();exit;
   	return false;
  }	 	
 	 	
 	
 }  
     
 	public function sendEmail($data)
 	{ 
 		global $objCron; 	
 		
    	##Get the store object 
    	$store_obj 	= get_config_ini_settings('store');
    	
    	##if store object exists
    	if(is_object($store_obj))
    	{
	    	$store_return_path = $store_obj->returnPath;
	    	$ADMIN_EMAIL = $store_obj->ADMIN_EMAIL;
	    	if($store_return_path)
	    	{
				$tr = new Zend_Mail_Transport_Sendmail("-f" . $store_return_path);
				Zend_Mail::setDefaultTransport($tr);
	    	}
    	}
    	
    	######### END check if return path is there then prepend it with the email ####################
    	 		 	
		$mail = new Zend_Mail('UTF-8');
		$data[ 'body' ] = stripslashes($data[ 'body' ]);
		
		//converting <br> to \n before sending text email contents
		$text_contents = preg_replace('#<br\s*/?>#i', "\n", $data['body']);
		
		$mail->setBodyText($text_contents,'UTF-8',Zend_Mime::ENCODING_8BIT); 

		$mail->setBodyHtml(nl2br($data['body']),'UTF-8',Zend_Mime::ENCODING_8BIT); 
		
		$ADMIN_EMAIL_REPLY_TO =$objCron->getConfigDetails('ADMIN_EMAIL');		
		
		$mail->setReplyTo($ADMIN_EMAIL_REPLY_TO[ 'value' ]);
		
		if($data[ 'from' ])
		{
			$from = $data[ 'from' ];
		}
		else 
		{
			$from = $ADMIN_EMAIL;
		}
		
		$mail->setFrom($from);

		$mail->addTo($data['to']);

		$mail->setSubject($data['subject']);

		try
		{
			$mail->send();	
			return true;			
		}
		catch(Zend_Exception $e)
		{			
			echo $e->getMessage();
			return false;
		} 				
	} 
 
 	public function get_alert_list()
 {
 	GLOBAL $db, $connection;
 	
 	$limit_clause='';
	##Get the store object 
	$store_obj 	= get_config_ini_settings('store');
	
	##if store object exists
	if(is_object($store_obj))
	{
		$MAX_EMAIL_SEND_COUNT = $store_obj->MAX_EMAIL_SEND_COUNT;
	}
 	
 	if($MAX_EMAIL_SEND_COUNT >0)
 	{
 		$limit_clause = " LIMIT " . $MAX_EMAIL_SEND_COUNT;
 	}
 	
 	
 	$sql = " 
 		SELECT 
 			* 
			FROM 
				alerts
			WHERE 
				frequency>0 
				$limit_clause
	 	";
 	
		try
		{
			$arr = $db->fetchAll($sql);

			if(is_array($arr) && count($arr))
			{
				return $arr;
			}
			else 
			{
				return false;				
			}
		}
		catch(Zend_Exception $e)
		{          
			echo $e->getMessage();exit;
   	return false;
  }	   	
 }	
 
	public function get_alert_keyword_data($keyword, $famille_code='', $magasin_code='')
 {
 	GLOBAL $db, $connection;
 	
 	if($famille_code)
 	{
 		$family__code_sql = " AND famille_code='$famille_code' ";	
 	}
 	
 	if($magasin_code)
 	{
 		$magasin_code_sql = " AND magasin_code='$magasin_code' ";
 	}
 	
 	$sql = " 
 		 SELECT 
 		 	* 
 		 FROM 
 		 	articles 
 		 WHERE 
 		 	( libelle LIKE '%$keyword%' OR description LIKE '%$keyword%' ) 
 		 	$magasin_code_sql
 			$family__code_sql
 		LIMIT 
 			0,15
	 	";
 	
// 	echo "<pre>$sql";
		try
		{
			$arr = $db->fetchAll($sql);

			if(is_array($arr) && count($arr))
			{
				return $arr;
			}
			else 
			{
				return false;				
			}
		}
		catch(Zend_Exception $e)
		{          
			echo $e->getMessage();exit;
   	return false;
  }	   	
 }
  
 
	public function getMagasinCodeByMagasinId($magasin_id)
	{
		GLOBAL $db, $connection;
		try 
		{
			$SELECT = "
				SELECT 
					magasin_code
				FROM 
					magasins
				WHERE 
					abo_ok in (1,2) AND
					magasin_id = '$magasin_id'
			";
			$magasin_code = $db->fetchOne($SELECT);
			
			if($magasin_code)
			{
				return $magasin_code;
			}
			else 
			{
				return false;
			}
		}
		catch(Zend_Exception $e)
		{          
			echo $e->getMessage();exit;
   	return false;
  }			
		
	} 
	
	public function getContactDetailsByContactNum($contact_num)
	{
		GLOBAL $db, $connection;
		try 
		{
			$SELECT = "
			SELECT 
    			c.contact_id,
    			c.type_contacts_code,
    			c.civilite,
    			c.nom,
    			c.prenom,
    			c.societe,
    			c.adresse1,
    			c.adresse2,
    			c.code_postal,
    			c.ville,
    			c.pays,
    			c.telephone1,
    			c.telephone2,
    			c.telephone_mobile,
    			c.fax,
    			c.email,
    			c.taux_commission,
    			c.magasin_code,
    			c.contrat_num,
    			c.total_point_fidelite,
    			c.date_creation,
    			c.date_modif,
    			c.date_naissance,
    			c.envoi_reglt,
    			c.montant_mini_franco,
    			c.Bareme_id,
    			c.pidentite,
    			c.Num_pidentite,
    			c.datepidentite,
    			c.lieupidentite,
    			c.Profession,
    			c.numrc,
    			c.frais_port,
    			c.import_num_contact,
    			c.source,
    			c.export,
    			cp.*
    		FROM 
    			contacts as c,
    			contact_preferences as cp
    		WHERE 
    			c.contact_num=cp.contact_num AND
    			cp.contact_num ='$contact_num' 			
    		";
			
			$info = $db->fetchRow($SELECT);
			
			if($info)
			{
				return $info;
			}
			else 
			{
				return false;
			}
		}
		catch(Zend_Exception $e)
		{          
			echo $e->getMessage();
			exit;
   	return false;
  }			
		
	} 	
	
	public function get_detail_by_code($magasin_code)
	{
		GLOBAL $db, $connection;
		try 
		{
			$SELECT = "
				SELECT 
					* 
				FROM 
					magasins
				WHERE 
					abo_ok in (1,2) AND
					magasin_code = '$magasin_code'
			";
			
			$arr = $db->fetchRow($SELECT);
//			green_pae($arr);
			return $arr;
		}
		catch(Zend_Exception $e)
		{          
			echo $e->getMessage();exit;
   	return false;
  }	
	}
	
	public function getNonBlankPhotoArticles()
	{
		GLOBAL $db, $connection;
		$sql = " 
			SELECT 
				article_ID,
				photo1,
				photo2,
				photo3,
				photo4 
			FROM 
				articles
			where
			(
			 	( photo1 !='' AND photo1 IS NOT NULL )  OR 
			 	( photo2 !='' AND photo2 IS NOT NULL )  OR 
			 	( photo3 !='' AND photo3 IS NOT NULL )  OR 
			 	( photo4 !='' AND photo4 IS NOT NULL ) 
			 )
			 ";
	 	
 	
		try
		{
			$arr = $db->fetchAll($sql);

			if(is_array($arr) && count($arr))
			{
				return $arr;
			}
			else 
			{
				return false;				
			}
		}
		catch(Zend_Exception $e)
		{          
			echo $e->getMessage();exit;
   			return false;
		}
		
	}




	public function get_no_stock_articles()
	{
		GLOBAL $db, $connection;
		$sql = " 
			SELECT 
				a.article_ID,
				a.*	
			FROM 
				articles as a,
				ventes_details  as vd
			where
				a.reference_web	=	vd.reference_web AND 
				qte_stock < 1
		 ";
		
		$DEBUG = get_config_ini_settings('DEBUG'); 
		if($DEBUG==1)
		{
	 		echo '<br>'.$sql;
	 	}
 	
		try
		{
			$arr = $db->fetchAll($sql);


			if(is_array($arr) && count($arr))
			{
				return $arr;
			}
			else 
			{
				return false;				
			}
		}
		catch(Zend_Exception $e)
		{          
			echo $e->getMessage();exit;
   			return false;
		}
		
	}
	
	public function get_expiring_classifieds()
 	{
	 	GLOBAL $db, $connection;
	 	
	 	$limit_clause='';
		##Get the store object 
		$store_obj 	= get_config_ini_settings('store');
		
		##if store object exists
		if(is_object($store_obj))
		{
			$MAX_EMAIL_SEND_COUNT = $store_obj->MAX_EMAIL_SEND_COUNT;
		}
	 	
	 	if($MAX_EMAIL_SEND_COUNT >0)
	 	{
	 		$limit_clause = " LIMIT " . $MAX_EMAIL_SEND_COUNT;
	 	}
	 	
	 	
	 	$sql = " 
	 		SELECT 
 			* 
			FROM 
				classifieds
			WHERE 
				
				enable_flag='Y'
				$limit_clause
	 	";
 	
		try
		{
			
			$arr = $db->fetchAll($sql);

			if(is_array($arr) && count($arr))
			{
				$new_arr=array();
				$i=0;
				foreach($arr as $k=>$v)
				{
					if($v[ 'updated_date' ]!='0000-00-00 00:00:00')
					{
						$update_date_timestamp = strtotime($v[ 'updated_date' ]);
					}
					else if($v[ 'creation_date' ]!='0000-00-00')
					{
						$update_date_timestamp = strtotime($v[ 'creation_date' ]);
					}	
					
					$plus_23_days = date("Y-m-d", strtotime("+23 day", $update_date_timestamp));
					$expire_date  = date("d-m-Y", strtotime("+30 day", $update_date_timestamp));
					
					$v[ 'expire_date' ] = $expire_date;
					
					if($plus_23_days>=date('Y-m-d'))
					{
						$new_arr[$i] = $v;
					}						
					$i++;				
				}
				return $new_arr;
			}
			else 
			{
				return false;				
			}
		}
		catch(Zend_Exception $e)
		{          
			echo $e->getMessage();exit;
   			return false;
		}
	}
	public function get_unmoderated_classifieds()
 	{
	 	GLOBAL $db, $connection;
	 	
	 	$limit_clause='';
		##Get the store object 
		$store_obj 	= get_config_ini_settings('store');
		
		##if store object exists
		if(is_object($store_obj))
		{
			$MAX_EMAIL_SEND_COUNT = $store_obj->MAX_EMAIL_SEND_COUNT;
		}
	 	
	 	if($MAX_EMAIL_SEND_COUNT >0)
	 	{
	 		$limit_clause = " LIMIT " . $MAX_EMAIL_SEND_COUNT;
	 	}
	 	
	 	
	 	$sql = " 
	 		SELECT 
 				* 
			FROM 
				classifieds
			WHERE 
				moderated='pending'
				$limit_clause
	 	";
 	
		try
		{
			
			$arr = $db->fetchAll($sql);

			if(is_array($arr) && count($arr))
			{
				return $arr;
			}
			else 
			{
				return false;				
			}
		}
		catch(Zend_Exception $e)
		{          
			echo $e->getMessage();exit;
   			return false;
		}
	}
	
	public function moderator_exists($county='', $region='', $department='', $city='')
 	{
	 	GLOBAL $db, $connection;
	 	
	 	$limit_clause='';
	 	$search_sql='';
	 	
	 	if($county)
	 	{
	 		$search_sql .= " AND country_id=$county ";
	 	}
	 	
	 	if($region)
	 	{
	 		$search_sql .= " AND region=$region ";
	 	}
	 	
	 	if($department)
	 	{
	 		$search_sql .= " AND departement_id=$department ";
	 	}
	 	
	 	if($city)
	 	{
	 		$search_sql .= " AND city_id=$city  ";
	 	}
	 		 	
	 	$sql = " 
	 		SELECT 
 				* 
			FROM 
				moderators
			WHERE 
				1
				$search_sql
				$limit_clause
	 	";
 	
		try
		{
			
			$record = $db->fetchOne($sql);

			if($record)
			{
				return $record;
			}
			else 
			{
				return false;				
			}
		}
		catch(Zend_Exception $e)
		{          
			echo $e->getMessage();exit;
   			return false;
		}
	}	
	
	public function getCityNameById($city_id)
 	{
	 	GLOBAL $db, $connection;
	 	
	 		 	
		$sql = "
			SELECT 
				description 
			FROM 
				cities
			WHERE 
				id=$city_id
		";
 	
		try
		{
			
			$record = $db->fetchOne($sql);

			if($record)
			{
				return $record;
			}
			else 
			{
				return false;				
			}
		}
		catch(Zend_Exception $e)
		{          
			echo $e->getMessage();exit;
   			return false;
		}
	}	
	
	public function getDeptNameById($dept_id)
 	{
	 	GLOBAL $db, $connection;
	 	
	 		 	
		$sql = "
			SELECT 
				description 
			FROM 
				departement
			WHERE 
				id='$dept_id'
		";
 	
		try
		{
			
			$record = $db->fetchOne($sql);

			if($record)
			{
				return $record;
			}
			else 
			{
				return false;				
			}
		}
		catch(Zend_Exception $e)
		{          
			echo $e->getMessage();exit;
   			return false;
		}
	}
	
	public function getRegionNameById($region_id)
 	{
	 	GLOBAL $db, $connection;
	 	
	 		 	
		$sql = "
			SELECT 
				NCC 
			FROM 
				regions
			WHERE 
				region='$region_id'
		";
 	
		try
		{
			
			$record = $db->fetchOne($sql);

			if($record)
			{
				return $record;
			}
			else 
			{
				return false;				
			}
		}
		catch(Zend_Exception $e)
		{          
			echo $e->getMessage();exit;
   			return false;
		}
	}	
	
	public function get_country_name_by_id($country_id)
 	{
	 	GLOBAL $db, $connection;
	 	
	 		 	
		$sql = " SELECT libelle FROM pays WHERE pays_id='$country_id' ";
 	
		try
		{
			
			$record = $db->fetchOne($sql);

			if($record)
			{
				return $record;
			}
			else 
			{
				return false;				
			}
		}
		catch(Zend_Exception $e)
		{          
			echo $e->getMessage();exit;
   			return false;
		}
	}		
}	

class config
{
	public function fetchConfigParam($name)
	{
		GLOBAL $db, $connection;
		$this->_db = $db;
		try 
		{
			$select = "SELECT * FROM config WHERE name='$name' ";
			 $details = $this->_db->fetchRow($select);
			 
			 return $details;
		}
		catch(Zend_Exception $e)
		{          
			echo $e->getMessage();exit;
   	return false;
  }			
	}
}


?>
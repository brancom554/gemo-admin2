<?php
/*
 * Created on Jan 17, 2006 - 9:00:19 AM
* @Author : Jacques Jocelyn
*          Modify or copy this file requires author's agreement.
* @purpose :
*/
// @ini_set("display_errors",1);
class CommonLib
{
	var $data;
	var $lang;
	function __construct()
	{
		global $db, $sqlData, $iniObj;
		$this->db = $db;
		$this->sqlData = $sqlData;
		$this->lang = $_SESSION['LANG'];
		//$this->debug($this->db);
		$this->ini = $iniObj;
	}

	/* Initialize a the Email Class */
	private function InitEmailClass()
	{
		require_once _EXT_LIB_PATH . "/PHPMailer_v5.1/class.phpmailer.php";
		$this->eMail = new PHPMailer();
		$this->eMail->IsSMTP();                                     // set mailer to use SMTP
		$this->eMail->SetLanguage("en", _EXT_LIB_PATH . "/PHPMailer_v5.1/language/");
		//      $this->eMail->Host = "localhost";  // specify main and backup server
		$this->eMail->Host = $this->ini->smtpHost;  // specify main and backup server
		$this->eMail->Username = $this->ini->smtpUser;
		$this->eMail->Password   = $this->ini->smtpPass;
		$this->eMail->Port = $this->ini->smtpPort;
		$this->eMail->SMTPSecure = $this->ini->smtpSecure;
		$this->eMail->SMTPDebug = $this->ini->smtpDebug;
		$this->eMail->CharSet = "UTF-8";
		$this->eMail->mailer = "smtp";
		// $this->eMail->SMTPSecure = $this->ini->smtpSecure;
		$this->eMail->SMTPAuth =  $this->ini->smtpAuth;     // turn on SMTP authentication
		//       if($this->ini->smtpDebug){
		//       echo "<br>INIT EMAIL LCAAASSS / host :".$this->ini->smtpHost.
		//       " / user : ".$this->ini->smtpUser.
		//       " / pass : ".$this->ini->smtpPass.
		//       " / port : ".$this->ini->smtpPort.
		//       " / smtpSecure : ".$this->ini->smtpSecure.
		//       " / smtpAuth : ".$this->ini->smtpAuth;
		//       }
	}

	function sendMailTrxStatus($email, $trxNum, $amount, $status, $lang)
	{
		switch ($status) {
			case 'Valid':
				$text = "Un paiement a été effectué par carte bancaire \n" .
					"Montant de la facture : {AMOUNT} euros\n" .
					"Numéro de facture : {FACTURE}";
				break;
			default:
				$text = "Une tentative de paiement a été effectué par carte bancaire \n" .
					"Montant de la facture : {AMOUNT} euros\n" .
					"Numéro de facture : {FACTURE}";
				break;
		}

		$keywords = array(
			"{DATE}" => date("d/m/Y - H:i:s"), "{FACTURE}" => $trxNum, "{AMOUNT}" => $amount, "{WEBSITE}" => _SITE, "{SITE}" => _WEBSITE_NAME
		);
		$messageContent = str_replace(array_keys($keywords), array_values($keywords), $text);
		$this->InitEmailClass();
		$this->eMail->From = $email;
		$this->eMail->FromName = $this->ini->companyName;
		$this->eMail->Subject = '[CARTE BANCAIRE] - FACTURE # ' . $trxNum;
		$this->eMail->AddAddress($email);
		$this->eMail->Sender = $email; // Return Path
		$this->eMail->IsHTML(false);                                  // set email format to HTML
		$this->eMail->Body = $messageContent;
		if (!$this->eMail->Send()) {
			$this->error = 1;
			return (false);
		} else return (true);
	}

	function random()
	{
		return (float)rand() / (float)getrandmax();
	}

	function sendEmailTrxToVerify($email, $order, $amount)
	{
		// $text= file_get_contents(_ROOT_FILES."/docs/website/transaction_erreur.txt");
		$text = "Un paiement a été effectué par carte bancaire mais à priori la facture est déjà réglée\n" .
			"Montant de la facture : {AMOUNT}\n" .
			"Numéro de facture : {FACTURE}";
		$keywords = array(
			"{DATE}" => date("d/m/Y h:i:s A"), "{TRX}" => $trx, "{FACTURE}" => $order, "{AMOUNT}" => $amount, "{WEBSITE}" => _SITE, "{SITE}" => _WEBSITE_NAME
		);
		$messageContent = str_replace(array_keys($keywords), array_values($keywords), $text);
		$this->InitEmailClass();
		$this->eMail->From = $email;
		$this->eMail->FromName = $this->ini->companyName;
		$this->eMail->Subject =  '[CARTE BANCAIRE] - FACTURE # ' . $order;
		$this->eMail->AddAddress($email);
		$this->eMail->Sender = $email; // Return Path
		$this->eMail->IsHTML(false);                                  // set email format to HTML
		$this->eMail->Body = $messageContent;
		if (!$this->eMail->Send()) {
			$this->error = 1;
			return (false);
		} else return (true);
	}

	/* Send Alert to User */
	function sendEmailAlert($from, $email, $subject, $content, $Cc = '', $stringAttachment = '', $filename = '', $replyTo = '')
	{
		$this->InitEmailClass();
		$this->eMail->From = $from;
		$this->eMail->FromName = $this->ini->companyName;
		$this->eMail->Subject =  $subject;
		$this->eMail->AddAddress($email);
		if (trim($Cc)) $this->eMail->AddCC($Cc);
		if (trim($replyTo)) $this->eMail->AddReplyTo($replyTo); //Reply to
		$this->eMail->Sender = $from; // Return Path
		$this->eMail->IsHTML(true);                                  // set email format to HTML
		$this->eMail->Body = $content;
		/* Adding attachment if provided */
		if (trim($stringAttachment)) {
			$this->eMail->AddStringAttachment($stringAttachment, $filename);
		}
		if (!$this->eMail->Send()) {
			$this->error = 1;
			echo $this->eMail->ErrorInfo;
			return (false);
		} else return (true);
	}

	/* Send Alert to Ops for new booking */
	function sendEmailNewBooking($from, $email, $subject, $content, $Cc = '', $stringAttachment = '', $filename = '', $replyTo = '')
	{
		$this->InitEmailClass();
		$this->eMail->From = $from;
		$this->eMail->FromName = $this->ini->companyName;
		$this->eMail->Subject =  $subject;
		$this->eMail->AddAddress($email);
		if (trim($Cc)) $this->eMail->AddCC($Cc);
		if (trim($replyTo)) $this->eMail->AddReplyTo($replyTo); //Reply to
		$this->eMail->Sender = $from; // Return Path
		$this->eMail->IsHTML(true);                                  // set email format to HTML
		$this->eMail->Body = $content;
		/* Adding attachment if provided */
		if (trim($stringAttachment)) {
			$this->eMail->AddStringAttachment($stringAttachment, $filename);
		}
		if (!$this->eMail->Send()) {
			$this->error = 1;
			// echo $this->eMail->ErrorInfo;
			return (false);
		} else return (true);
	}

	function sendEmailNoCC($from, $email, $subject, $content, $Cc = '', $stringAttachment = '', $filename = '', $replyTo = '', $binaryAttach = '')
	{
		$this->InitEmailClass();
		$this->eMail->From = $from;
		$this->eMail->FromName = $this->ini->serviceName;
		$this->eMail->Subject =  $subject;
		$this->eMail->AddAddress($email);
		if (trim($Cc)) $this->eMail->AddCC($Cc);
		if (trim($replyTo)) $this->eMail->AddReplyTo($replyTo); //Reply to
		$this->eMail->Sender = $from; // Return Path
		$this->eMail->IsHTML(true);                                  // set email format to HTML
		$this->eMail->Body = $content;
		$this->eMail->AltBody = $content;
		/* Adding attachment if provided */
		if (trim($stringAttachment)) {
			$this->eMail->AddStringAttachment($stringAttachment, $filename);
		}
		if (trim($binaryAttach)) {
			$this->eMail->AddAttachment($binaryAttach);
		}

		if (!$this->eMail->Send()) {
			$this->error = 1;
			echo $this->eMail->ErrorInfo;
			return (false);
		} else return (true);
	}

	function SendContactUsMail($to, $fromMail, $fromName, $subject, $content)
	{
		$this->InitEmailClass();
		$this->eMail->From = $to;
		$this->eMail->FromName = $fromName;
		$this->eMail->Subject = $subject;
		$this->eMail->AddAddress($to);
		$this->eMail->AddReplyTo($fromMail, $fromName);
		$this->eMail->Sender = $fromMail; // Return Path
		$this->eMail->IsHTML(false); // set email format to HTML
		$this->eMail->Body = $content;
		if (!$this->eMail->Send())  return (false);
		else return (true);
	}


	function sendNewsletter($email, $subject, $fileHTML)
	{
		$this->InitEmailClass();
		$this->eMail->From = $this->ini->emailNewsletter;
		$this->eMail->FromName = $this->ini->companyName;
		$this->eMail->AddAddress($email);
		$this->eMail->Sender = $this->ini->emailNewsletter; // Return Path
		$this->eMail->IsHTML(true); // set email format to HTML
		$this->eMail->Subject = $subject;
		$filenameHTML = $fileHTML;
		$filenameTXT = $fileTEXT;
		$keywords = array(
			//     "{PSEUDO}" => $pseudo
			"<!--OneLine-->" =>
			"<br /><center><font size='2'>Si vous ne visualisez pas correctement cette newsletter, <a href='http://" .
				$_SERVER['SERVER_NAME'] . "/newsletter/$fileHTML'>veuillez consulter notre version en ligne</a></font></center>", "<!--Desinscription-->" =>
			"<br /><br /><center><font size='1'>Si vous souhaitez vous désabonner, rendez-vous sur la page ci-dessous <a href='http://" .
				$_SERVER['SERVER_NAME'] .
				"/newsSuppr/$email'>se désabonner</a></font></center>", "{URL}" => $filenameHTML
			//       ,"{MOT_DE_PASSE}" => $password
		);
		$this->eMail->Body =
			str_replace(
				array_keys($keywords),
				array_values($keywords),
				// file_get_contents(_ROOT_FILES."/docs/website/".$filenameTXT)).
				//        "<br><br>".
				file_get_contents(_PATH_ROOT . "/newsletter/" . $fileHTML)

			);
		// $this->eMail->AltBody =file_get_contents(_ROOT_FILES."/docs/website/".$filenameTXT);

		//$this->eMail->Body = str_replace(array_keys($keywords), array_values($keywords), file_get_contents(_ROOT_FILES."/docs/website///demande_mot_de_passe_".$lang.".txt"));
		if (!$this->eMail->Send())  return (false);
		else return (true);
	}



	/* Generate a PDF shipping */
	function generatePdfShipping($id, $lang, $outputMode)
	{

		$reportsPath = _APPS_PATH . "/" . $this->ini->jasperReportPath;
		$jasperReportsLib = _EXT_LIB_PATH . $this->ini->phpJasperReportsLib;

		if (strtoupper($lang) == 'FR') $reportFileName = "/shipping.Fr";
		else $reportFileName = "/shipping.En";
		$reportId = $id;
		$outputFileName = "shipping_" . $reportId . ".pdf";

		require_once($jasperReportsLib . '/class/tcpdf/tcpdf.php');
		require_once($jasperReportsLib . "/class/PHPJasperXML.inc.php");
		require_once($jasperReportsLib . "/setting.php");
		// error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

		$xmlFile =  simplexml_load_file($reportsPath . $reportFileName . ".jrxml");

		$xml2Pdf = new PHPJasperXML($iniObj);
		//$PHPJasperXML->debugsql=true;
		$xml2Pdf->arrayParameter = array("id" => $reportId);
		$xml2Pdf->xml_dismantle($xmlFile);

		$xml2Pdf->transferDBtoArray($iniObj->DbHost, $iniObj->DbUser, $iniObj->DbPass, $iniObj->DbName);
		// use this line if you want to connect with mysql
		// $PHPJasperXML->outpage("D",$outputName);    //page output method I:standard output  D:Download file

		return ($xml2Pdf->outpage($outputMode, $outputFileName));    //page output method I:standard output  D:Download file
	}

	/* Generate a PDF shipping */
	function generatePdfInvoice($id, $lang, $outputMode)
	{

		$reportsPath = _APPS_PATH . "/" . $this->ini->jasperReportPath;

		$jasperReportsLib = _APPS_PATH . "/" . $this->ini->phpJasperReportsLib;

		if (strtoupper($lang) == 'FR') $reportFileName = "/invoice.Fr";
		else $reportFileName = "/invoice.En";
		$reportId = $id;
		$outputFileName = "invoice_" . $reportId . ".pdf";

		require_once($jasperReportsLib . '/class/tcpdf/tcpdf.php');
		require_once($jasperReportsLib . "/class/PHPJasperXML.inc.php");
		require_once($jasperReportsLib . "/setting.php");
		// error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

		$xmlFile =  simplexml_load_file($reportsPath . $reportFileName . ".jrxml");

		$xml2Pdf = new PHPJasperXML($iniObj);
		//$PHPJasperXML->debugsql=true;
		$xml2Pdf->arrayParameter = array("id" => $reportId);
		$xml2Pdf->xml_dismantle($xmlFile);

		$xml2Pdf->transferDBtoArray($iniObj->DbHost, $iniObj->DbUser, $iniObj->DbPass, $iniObj->DbName);
		// use this line if you want to connect with mysql
		// $PHPJasperXML->outpage("D",$outputName);    //page output method I:standard output  D:Download file

		return ($xml2Pdf->outpage($outputMode, $outputFileName));    //page output method I:standard output  D:Download file
	}

	/* Generate a PDF shipping */
	function generatePdfInvoiceList($id, $lang, $outputMode)
	{

		$reportsPath = _APPS_PATH . "/" . $this->ini->jasperReportPath;

		$jasperReportsLib = _APPS_PATH . "/" . $this->ini->phpJasperReportsLib;

		if (strtoupper($lang) == 'FR') $reportFileName = "/invoiceList.Fr";
		else $reportFileName = "/invoice.En";
		$reportId = $id;
		$outputFileName = "invoice_" . $reportId . ".pdf";

		require_once($jasperReportsLib . '/class/tcpdf/tcpdf.php');
		require_once($jasperReportsLib . "/class/PHPJasperXML.inc.php");
		require_once($jasperReportsLib . "/setting.php");
		// error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

		$xmlFile =  simplexml_load_file($reportsPath . $reportFileName . ".jrxml");

		$xml2Pdf = new PHPJasperXML($iniObj);
		//$PHPJasperXML->debugsql=true;
		$xml2Pdf->arrayParameter = array("id" => $reportId);
		$xml2Pdf->xml_dismantle($xmlFile);

		$xml2Pdf->transferDBtoArray($iniObj->DbHost, $iniObj->DbUser, $iniObj->DbPass, $iniObj->DbName);
		// use this line if you want to connect with mysql
		// $PHPJasperXML->outpage("D",$outputName);    //page output method I:standard output  D:Download file

		return ($xml2Pdf->outpage($outputMode, $outputFileName));    //page output method I:standard output  D:Download file
	}

	function generatePassword($size = 6)
	{
		$charset = array(
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P',
			'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '2', '3', '4', '5', '6', '7', '8', '9'
		);

		$new_id = "";
		mt_srand((float)microtime() * 1000000);
		for ($i = 0; $i < $size; $i++) {
			$new_id .= $charset[rand(0, 24)];
		}
		/* while(!$this->is_alphanum($new_id))
	  $this->gen_id($size);
	 */
		return $new_id;
	}



	# Get File extension for a better match
	function fileExt($str)
	{
		//preg_match("|\.([a-z0-9]{2,4})$|i",$in, $m);
		//return(strtolower($m[1]));
		return (pathinfo($str, PATHINFO_EXTENSION));
	}

	function cleanFileName($str)
	{
		return ($this->cleanStr(pathinfo($str, PATHINFO_FILENAME)) . "." . pathinfo($str, PATHINFO_EXTENSION));
	}
	/* check if a file is valid */
	function isFileValid($name)
	{

		echo "";


		/* source : http://www.boutell.com/newfaq/creating/upload.html */
		# Go to all lower case for consistency
		$name = strtolower($name);
		echo "<br>1 - name : $name";
		preg_match('/^(.*)(\..*)?$/', $name, $matches);
		$this->debug($matches);
		# $matches[0] gets the entire string.
		# $matches[1] gets the first sub-expression in (),
		# $matches[2] the second, etc.

		$stem = $matches[1];
		echo "<br>2 - stem : $stem";
		$extension = $matches[2];
		echo "<br>3 - ext : " . $matches[2];
		# Convert whitespace of any kind to single underscores
		$stem = preg_replace('/\s+/', '_', $stem);
		echo "<br>4 - stem : $stem";
		# Remove any remaining characters other than A-Z, a-z, 0-9 and _
		$stem = preg_replace('/[^\w]/', '', $stem);
		echo "<br>5 - stem : $stem";

		# Make sure the file extension has no odd characters
		if (($extension != '') &&
			(!preg_match('/^\.\w+$/', $extension))
		) {
			echo "<br>bad ext";
			die("Bad file extension");
		}
	}

	function sec2hms($sec, $padHours = false)
	{
		// source : http://www.laughing-buddha.net/jon/php/sec2hms/
		// holds formatted string
		$hms = "";

		// there are 3600 seconds in an hour, so if we
		// divide total seconds by 3600 and throw away
		// the remainder, we've got the number of hours
		$hours = intval(intval($sec) / 3600);
		// add to $hms, with a leading 0 if asked for
		$hms .= ($padHours)
			? str_pad($hours, 2, "0", STR_PAD_LEFT) . ':'
			: $hours . ':';

		// dividing the total seconds by 60 will give us
		// the number of minutes, but we're interested in
		// minutes past the hour: to get that, we need to
		// divide by 60 again and keep the remainder
		$minutes = intval(($sec / 60) % 60);
		// then add to $hms (with a leading 0 if needed)
		$hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT) . ':';

		// seconds are simple - just divide the total
		// seconds by 60 and keep the remainder
		$seconds = intval($sec % 60);

		// add to $hms, again with a leading 0 if needed
		$hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

		// done!
		return $hms;
	}

	/*
	 * Verify if the current user is authenticated, otherwise redirect user to the main page
	*/
	function controlLogin($doc = '')
	{
		global $tpl, $SID_STR;
		$this->tpl = $tpl;

		switch ($doc) {
			case 'artist':
				// echo "doc = ".$doc." / type=".$_SESSION['USER_TYPE'];exit;
				if ($_SESSION['USER_TYPE'] <> 'artist' && $_SESSION['USER_TYPE'] <> 'manager' && (isset($_SESSION['LOGIN']) && $_SESSION['LOGIN'] == 1)) {
					Header("Location: /misc/forbidden/SID/" . $SID_STR);
				}
				break;
			case 'editor':
				if ($_SESSION['USER_TYPE'] <> 'editor') {
					Header("Location: /misc/forbidden/" . $SID_STR);
				}
				break;
			case 'manager':
				if ($_SESSION['USER_TYPE'] <> 'manager') {
					Header("Location: /misc/forbidden/" . $SID_STR);
				}
				break;
		}
		if ($_SESSION['EMAIL_CONFIRMED'] != 'Y' && (isset($_SESSION['LOGIN']) && $_SESSION['LOGIN'] == 1)) {
			if ($doc != 'emailUpdate') Header("Location: /misc/email_to_confirm/" . $SID_STR);
		} elseif (!isset($_SESSION['EMAIL']) && !isset($_SESSION['LOGIN']) && !isset($_SESSION['PSEUDO'])) {
			// saving the requested URL
			$_SESSION['LAST_URL'] = $_SERVER['REQUEST_URI'];
			// $_SESSION['RETURN_URL']=$_SERVER["HTTP_REFERER"];
			$_SESSION['RETURN_URL'] = $_SERVER['REQUEST_URI'];
			$_SESSION['formError'][] = $this->mess(106);
			// Header("Location: /"._MAIN_PHP_PAGE."/".$SID_STR );
			$this->tpl->assign("titre_page", $title);
			$tpl->assign('txtM', $this->txtM('ALL'));
			$this->tpl->assign('lang', $_SESSION['LANG']);
			$this->tpl->display("header_meta.tpl");
			$this->tpl->display("forms/formulaire_login_main.tpl");
			$this->tpl->display("pied_general.tpl");
			exit;
		}
	}


	function encryptPasswordOLD($input, $salt)
	{
		// source : http://ideone.com/yQIAX
		$output = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($salt), $input, MCRYPT_MODE_CBC, md5(md5($salt))));
		return ($output);
	}

	function decryptPasswordOLD($input, $salt)
	{
		// source : http://ideone.com/yQIAX
		$output = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($salt), base64_decode($input), MCRYPT_MODE_CBC, md5(md5($salt))), "\0");
		return ($output);
	}

	function encryptPassword($input, $salt)
	{
		// source : http://ideone.com/yQIAX
		$output = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($salt), $input, MCRYPT_MODE_CBC, md5(md5($salt))));
		return ($output);
	}

	function decryptPassword($input, $salt)
	{
		// 		echo "<br>inside decrypt inout : ".$input;
		// source : http://ideone.com/yQIAX
		$output = trim(openssl_encrypt(MCRYPT_RIJNDAEL_256, md5($salt), base64_decode($input), MCRYPT_MODE_CBC, md5(md5($salt))), "\0");
		// 		echo "<br>inside decrypt output1  : ".$output;

		// 		$input = trim($input);
		$output = base64_decode(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($salt), $input, MCRYPT_MODE_CBC, md5(md5($salt))), "\0");

		// 		echo "<br>inside decrypt output 2: ".$output;
		// 		$output = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($salt), base64_decode($input), MCRYPT_MODE_CBC, md5(md5($salt))), "\0");

		return ($output);
	}

	function encryptPasswordNEW($password, $data)
	{
		// source : https://www.warpconduit.net/2013/04/14/highly-secure-data-encryption-decryption-made-easy-with-php-mcrypt-rijndael-256-and-cbc/
		$salt = substr(md5(mt_rand(), true), 8);

		$key = md5($password . $salt, true);
		$iv  = md5($key . $password . $salt, true);

		$ct = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);

		return base64_encode('Salted__' . $salt . $ct);
	}

	function decryptPasswordNEW($password, $data)
	{
		// source : https://www.warpconduit.net/2013/04/14/highly-secure-data-encryption-decryption-made-easy-with-php-mcrypt-rijndael-256-and-cbc/
		$data = base64_decode($data);
		$salt = substr($data, 8, 8);
		$ct   = substr($data, 16);

		$key = md5($password . $salt, true);
		$iv  = md5($key . $password . $salt, true);

		$pt = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ct, MCRYPT_MODE_CBC, $iv);

		return $pt;
	}

	/*
	 * this function will write the content to a given path
	*/
	function writeFile($path, $content, $newfile = 1)
	{
		ob_start();
		if ($newfile == 1) $file = fopen($path, 'w'); //Overwrite any exisiting file
		else $file = fopen($path, 'a+'); //add text at the end of the file, otherwise (if file does not exist) a new file is created
		fputs($file, $content);
		fclose($file);
		ob_flush();
	}

	/*
	 * get an email from input an return a corrected e-mail without extraneous characters
	*/

	function correctEmail($input)
	{
		/* List of values to replace */
		$keywords = array("\n" => "", "\r" => "", " "   => "");
		return (str_replace(array_keys($keywords), array_values($keywords), $input));
	}

	/*
	 * print a variable with a preformated format
	*/
	function debug(&$input)
	{
		echo '<pre><br>File -> ' . __FILE__ . ' and Line => ' . __LINE__;
		echo "<br /> Debug Information for variable <br />";
		print_r($input);
		echo "</pre>";
	}

	function formatNumber($value)
	{
		$result = number_format($value, _NB_DECIMAL_POSITION, ',', ' ');
		return ($result);
	}

	/* convert a date to SQL format*/
	function dateFr2Sql($date, $splitchar = "-")
	{
		/* date is provided French format DD/MM/YYYY, date returned is converted to Mysql format : YY-MM-DD     */
		list($day, $month, $year) = split($splitchar, $date);
		$sqldate = date("Y-m-d", mktime(0, 0, 0, $month, $day, $year));
		return ($sqldate);
	}



	/*
	 * Control if e-mail is in a valid format
	*/
	function emailFormatIsValid($email)
	{
		/* Verification du domain */
		if (_MODE_DEBUG == 1) return (true);
		if (!trim($email)) return false;
		else {
			// echo "<BR>TESTING EMAIL";
			$invalid = array(
				'spamgourmet.com',
				'mailexpire.com',
				'jetable.org',
				'spamday.com',
				'falseaddress.com',
				'netmails.net',
				'mailinator.com',
				'e4ward.com',
				'spambox.us',
				'yopmail.com',
				'haltospam.com',
				'trashmail.net',
				'trashmail.com',
				'ephemail.net',
				'jetable.org'
			);
			list($utilisateur, $domaine) = explode("@", $email, 2);
			if (!checkdnsrr($domaine, "MX") || !trim($utilisateur) || in_array(strtolower($domaine), $invalid))   return false;
			else {
				/* Verification du format de l'adresse email */
				if (!ereg("^([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[@]([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[.]([0-9,a-z,A-Z]){2}([0-9,a-z,A-Z])?$", $email)) return (false);
				else return (true);
			}
		}
	}

	public function br2nl($data)
	{ /* source : http://fr.php.net/nl2br */
		return str_replace(array("<br />", "<br/>", "<br>", "<br>"), "\n", $data);

		// return preg_replace( '!<br.*>!iU', "\n", $data );
	}


	function containsSpecialChar($var)
	{
		//    $chars=array('!','#','[',']','+',"'",'&','/','%','$','^',"{","}",'|','�','`','�',"~","=","!", "(",")","\\");
		//     , ,);
		//      ,
		//    $char_re = '/['.preg_quote(join('', $chars), '/').']/';
		// $var contains something in $chars
		//    if (preg_match($char_re, $var)) return true;

		//  if(preg_match ("@[^a-z0-9 -\_]+@i", $var )) return true;
		//  if (preg_match("'[^A-Za-z0-9 ]�\s{2}'", $var)) return false;
		//  if(preg_match ("/^[0-9A-Za-z -_]+$/", $var )) return false; // OK
		//  if(preg_match ("'[^0-9A-Za-z]'", $var )) return true;
		if (preg_match("/^[A-Za-z0-9 _-]+$/", $var)) return false;
		else return (true);
	}

	/*
	 * clean URL with PHP
	* source : http://cubiq.org/the-perfect-php-clean-url-generator
	*/
	function cleanUrl($input, $delimiter = '_')
	{

		setlocale(LC_ALL, 'fr_FR.UTF8');
		$replace = array(" ", "/", "<br>");

		if (!empty($replace)) {
			$input = str_replace((array)$replace, ' ', $input);
		}
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $input);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = ucfirst(strtolower(trim($clean, '-')));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

		return $clean;
	}

	/* reduce text lenght */
	function shrinkText($var, $lenght)
	{
		if (strlen($var) > $lenght) $text = substr($var, 0, $lenght);
		else $text = $var;
		return ($text);
	}


	/*confirmation email*/
	function sendConfirmEmailNoCC($from, $email, $subject, $content, $Cc = '', $stringAttachment = '', $filename = '', $replyTo = '', $binaryAttach = '')
	{
		$this->InitEmailClass();
		$this->eMail->From = $from;
		$this->eMail->FromName = $this->ini->serviceName;
		$this->eMail->Subject =  $subject;
		$this->eMail->AddAddress($email);
		if (trim($Cc)) $this->eMail->AddCC($Cc);
		if (trim($replyTo)) $this->eMail->AddReplyTo($replyTo); //Reply to
		$this->eMail->Sender = $from; // Return Path
		$this->eMail->IsHTML(true);                                  // set email format to HTML
		$this->eMail->Body = $content;
		/* Adding attachment if provided */
		if (trim($stringAttachment)) {
			$this->eMail->AddStringAttachment($stringAttachment, $filename);
		}
		if (trim($binaryAttach)) {
			$this->eMail->AddAttachment($binaryAttach);
		}

		if (!$this->eMail->Send()) {
			$this->error = 1;
			echo $this->eMail->ErrorInfo;
			return (false);
		} else return (true);
	}
}

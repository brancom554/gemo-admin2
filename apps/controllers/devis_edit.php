<?php
require _EXT_LIB_PATH.'/phpMailer/class.phpmailer.php';
require _WEB_PATH_PDF.'/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

$url =$iniObj->UIPath;
$html='
<style>
td, th {
  
  text-align: left;
  padding: 8px;
}


#fact th,#fact td {
    border: 1px solid #dad7d7;
    padding:10px
}

.total{
    margin-bottom:20px;
    margin-left:470px;
}

#fact th{
    text-align:center
}

.bar{
    margin-top:35%;
    height:2px;
    background:#1D539A;
    width:100%
}

#fact{
    border-collapse: collapse;}

</style>
<page backtop="10mm" backbottom="20mm" backleft="10mm" backright="10mm">
    <page_header style="">
        
	</page_header>

    <div style="margin-left:10px">
        <img style="width:150px" src="http://'.$_SERVER['SERVER_NAME'].''.$url.'/images/logo.png" alt="Logo" />
        <span style="margin-left:450px;font-size:15px;color:#1D539A;font-weight: bold">DEVIS</span>
    </div>
	<div style="margin-top:30px;margin-left:10px;;margin-bottom:40px"> 
    <table style="width:100%; margin-top:30px">
        <tr>
            <th style="width:150px;border:none;color:#1D539A">Facturé à</th>
            <th style="width:180px;border:none;color:#1D539A">Envoyé à</th>
            <th style="width:120px;border:none;color:#1D539A">Date du devis</th>
            <th style="width:50px;border:none">2021/06/16</th>
        </tr>
        <tr>
            <td style="border:none">Client 1</td>
            <td style="border:none">Client 1</td>
            <td style="border:none">Commande n°</td>
            <td style="border:none">2021/0168</td>
        </tr>
        <tr>
            <td style="border:none">Contact</td>
            <td style="border:none">Contact</td>
        </tr>
    </table>
	</div>
    
    <div style="margin-top:15px; text-align:center;margin-left:10px">
        <table id="fact" style="width:100% !important;border: 1px solid #1D539A !important;">
            <tr style="background:#1D539A; color:white">
                <th style="font-size:15px">Qté</th>
                <th style="font-size:15px; width:370px">Désignation</th>
                <th style="font-size:15px">Prix Unit. HT</th>
                <th style="font-size:15px">Montant HT</th>
            </tr>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Grand brun escagot pour manger</td>
                    <td style="text-align:right">100.00</td>
                    <td style="text-align:right">100.00</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Grand brun escagot pour manger</td>
                    <td style="text-align:right">100.00</td>
                    <td style="text-align:right">100.00</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Grand brun escagot pour manger</td>
                    <td style="text-align:right">100.00</td>
                    <td style="text-align:right">100.00</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="total" style="margin-top:20px">
        <span style="text-align:right;margin-left:0px;font-weight:bold">TOTAL HT</span>
        <span style="margin-left:90px">300000</span>
    </div>
    <div class="total">
        <span style="text-align:right;margin-left:0px;font-weight:bold">TVA 18%</span>
        <span style="margin-left:100px">300000</span>
    </div>
    <div class="total">
        <span style="text-align:right;margin-left:0px;font-weight:bold">TOTAL</span>
        <span style="margin-left:75px;font-weight:bold">3000000 Fcfa</span>
    </div>
    <div class="bar"></div>
</page>
';

$html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', 3);
$html2pdf->writeHTML($html);
$html2pdf->output();

$mail = new PHPMailer();
$mail->SMTPDebug = 3;
$mail->isSMTP();
$mail->Host = _HOST_SMTP;
$mail->Username = _USERNAME_SMTP;
$mail->Password = _PASSWORD_SMTP;
$mail->SMTPSecure = "tls";
$mail->Port = _PORT_SMTP;

$mail->SMTPAuth = true;

$mail->From = "";
$mail->FromName = "";

$mail->addAttachment("");

$mail->isHTML(false);
try {
    $mail->send();
    echo "Message has been sent successfully";
} catch (\Throwable $th) {
    echo "Mailer error: ".$mail->ErrorInfo; 
}

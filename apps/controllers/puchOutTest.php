<?php
// https://www.codediesel.com/php/posting-xml-from-php/

$url = "https://devinserm.transporteo.com/fr/InsermDashBoardAutoLogin/";
// $url = "http://inserm/fr/InsermDashBoardAutoLogin/";
$returnUrl = "http://occaz/receivePunchOut/";

//Store your XML Request in a variable
// <?xml version="1.0" standalone="yes"? >
$xml =  <<<XML
<?xml version = '1.0' encoding = 'UTF-8'?>
<!DOCTYPE cXML SYSTEM "http://xml.cxml.org/schemas/cXML/1.1.007/cXML.dtd">
<cXML version="1.1.007" xml:lang="fr-FR" payloadID="20160105104316.1127325916.28805@mytestenvironnement.inserm.fr" timestamp="2016-01-05T10:43:16+01:00">
   <Header>
      <From>
         <Credential domain="DUNS">
            <Identity>381614494</Identity>
         </Credential>
      </From>
      <To>
         <Credential domain="DUNS">
            <Identity>381614494</Identity>
         </Credential>
      </To>
      <Sender>
         <Credential domain="DUNS">
            <Identity>381614494</Identity>
            <SharedSecret>RhethuncakyegbocsOpsagIg4</SharedSecret>
         </Credential>
         <UserAgent>Oracle iProcurement</UserAgent>
      </Sender>
   </Header>
   <Request>
      <PunchOutSetupRequest operation="create">
         <BuyerCookie>441451986996827</BuyerCookie>
         <Extrinsic name="User">MAURYS- THOAS</Extrinsic>
         <BrowserFormPost>
            <URL1>https://mytestenvironnement.inserm.fr:443/OA_HTML/OA.jsp?OAFunc=ICX_CAT_PUNCHOUT_CALLBACK&amp;OAHP=ICX_POR_HOMEPAGE_MENU&amp;OASF=ICX_CAT_PUNCHOUT_CALLBACK&amp;transactionid=1027652868</URL1>
			<URL>$returnUrl></URL>
         </BrowserFormPost>
         <Contact>
            <Name xml:lang="fr-FR">MAURY, M. SEBASTIEN sebastien.maury</Name>
            <Email>jeremy.bonnassies@inserm.fr</Email>
         </Contact>
         <SupplierSetup>
            <URL>https://www.mytestpartner.fr/invoke/EIFR/login</URL>
         </SupplierSetup>
      </PunchOutSetupRequest>
   </Request>
</cXML>
XML;


$headers = array(
		"Content-type: text/xml",
		"Content-length: " . strlen($xml),
		"Connection: close",
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$data = curl_exec($ch);

//  print_r($data);
//  exit;
// $oXML = simplexml_load_string($data);
$oXML = new SimpleXMLElement( $data );
header( 'Content-type: text/xml' );
$url =  (string) $oXML->Response->PunchOutSetupResponse->StartPage->URL;
header('Location: '.$url);

exit;

<?php
// https://www.codediesel.com/php/posting-xml-from-php/

$url = "https://devinserm.transporteo.com/fr/InsermOrderAutoUpdate/";
$xml =  <<<XML
<?xml version = '1.0' encoding = 'ISO-8859-15' standalone = 'no'?>
<!-- Oracle eXtensible Markup Language Gateway Server  -->
<!DOCTYPE cXML SYSTEM "cXML.dtd">
<cXML version="1.2.007" payloadID="2015-01-23-11-56-25.2015000957:0:55.250700559850426704287488650671478353795@" timestamp="2015-01-23T11:56:25+00:00" xml:lang="en">
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
            <SharedSecret>testPunch1234</SharedSecret>
            <SharedSecret1>#WF_DECRYPT#_@#@^!@#^#4!^0^@6$#8#40^#8@$4!##98^!9^99`%9||$B#}0*+@88&0#WF_DECRYPT#</SharedSecret1>
          </Credential>
         <UserAgent>Oracle E-Business Suite Oracle Purchasing 11.5.9</UserAgent>
      </Sender>
   </Header>
   <Request deploymentMode="production">
      <OrderRequest>
         <OrderRequestHeader orderID="2015000957:0:55" orderDate="2015-01-23T11:52:35+00:00" orderType="regular" type="new">
            <Total>
               <Money currency="EUR">9825.00</Money>
            </Total>
            <ShipTo>
               <Address isoCountryCode="FR" addressID="92129">
                  <Name xml:lang="en">Default enterprise name - PA05</Name>
                  <PostalAddress>
                     <DeliverTo/>
                     <Street>INSERM U1153 HOP TENON</Street>
                     <Street>Hôpital Tenon - Bât de Recherche</Street>
                     <Street>4 rue de la Chine</Street>
                     <City>PARIS CEDEX 020</City>
                     <State/>
                     <PostalCode>75970</PostalCode>
                     <Country isoCountryCode="FR">FR</Country>
                  </PostalAddress>
                  <Email/>
                  <Phone>
                     <TelephoneNumber>
                        <CountryCode isoCountryCode="FR"/>
                        <AreaOrCityCode/>
                        <Number>01 56 01 75 80</Number>
                        <Extension/>
                     </TelephoneNumber>
                  </Phone>
                  <URL/>
               </Address>
            </ShipTo>
            <BillTo>
               <Address isoCountryCode="FR" addressID="147">
                  <Name xml:lang="en">PA05</Name>
                  <PostalAddress>
                     <DeliverTo/>
                     <Street>INSERM DELEGATION REGIONALE PARIS 5</Street>
                     <Street>2  RUE D'ALESIA</Street>
                     <Street>CS 51419</Street>
                     <City>PARIS</City>
                     <State/>
                     <PostalCode>75014</PostalCode>
                     <Country isoCountryCode="FR">FR</Country>
                  </PostalAddress>
                  <Email/>
                  <Phone>
                     <TelephoneNumber>
                        <CountryCode isoCountryCode="US"/>
                        <AreaOrCityCode/>
                        <Number>01 40 78 49 00</Number>
                        <Extension/>
                     </TelephoneNumber>
                  </Phone>
                  <URL/>
               </Address>
            </BillTo>
            <Shipping>
               <Money currency="EUR">0</Money>
               <Description xml:lang="en"><ShortName/></Description>
            </Shipping>
            <Contact role="buyer">
               <Name xml:lang="en">GOUVAERT U1153S, SOPHIE sophie.gouvaert</Name>
               <PostalAddress name="PA05">
                  <DeliverTo/>
                  <Street>INSERM DELEGATION REGIONALE PARIS 5</Street>
                  <Street>2  RUE D'ALESIA</Street>
                  <Street>CS 51419</Street>
                  <City>PARIS</City>
                  <State/>
                  <PostalCode>75014</PostalCode>
                  <Country isoCountryCode="FR">FR</Country>
               </PostalAddress>
               <Email>sophie.gouvaert@inserm.fr</Email>
               <Phone>
                  <TelephoneNumber>
                     <CountryCode isoCountryCode="US"/>
                     <AreaOrCityCode/>
                     <Number/>
                     <Extension/>
                  </TelephoneNumber>
               </Phone>
               <URL/>
            </Contact>
            <Comments xml:lang="en">Portable Elsa Lorthe Doctorante</Comments>
            <Extrinsic name="ACKREQD">N</Extrinsic>
            <Extrinsic name="ACKBYDATE"/>
            <Extrinsic name="SUPPNOTE"/>
            <Extrinsic name="TANDC"/>
            <Extrinsic name="MARCHE">13 2 00 05</Extrinsic>
         </OrderRequestHeader>
         <ItemOut quantity="1" agreementItemNumber="46590">
            <ItemID>
               <SupplierPartID>46648</SupplierPartID>
               <SupplierPartAuxiliaryID/>
            </ItemID>
            <ItemDetail>
               <UnitPrice>
                  <Money currency="EUR">3960.00</Money>
               </UnitPrice>
               <Description xml:lang="en">TeoGlace3 (15Kg de glace carbonique)</Description>
               <UnitOfMeasure>UNI</UnitOfMeasure>
               <Classification domain="DUMMY">DUMMY</Classification>
               <ManufacturerPartID/>
               <ManufacturerName xml:lang="en"/>
               <URL/>
               <Extrinsic name="LINENUM">1</Extrinsic>
               <Extrinsic name="SHIPMENTNUM">1</Extrinsic>
               <Extrinsic name="BUYERPARTNUM"/>
               <Extrinsic name="SUPPNOTE"/>
            </ItemDetail>
            <SupplierList>
               <Supplier>
                  <Name xml:lang="en">HEWLETT PACKARD FRANCE</Name>
                  <Comments><Attachment><URL/></Attachment></Comments>
                  <SupplierID domain="DUMMY">DUMMY</SupplierID>
                  <SupplierLocation>
                     <Address isoCountryCode="FR">
                        <Name xml:lang="en">PA05/LES ULIS</Name>
                        <PostalAddress>
                           <DeliverTo/>
                           <Street>CASE 406  1 AVENUE DU CANADA</Street>
                           <Street/>
                           <Street/>
                           <City>LES ULIS CEDEX</City>
                           <State/>
                           <PostalCode>91947</PostalCode>
                           <Country isoCountryCode="FR">FR</Country>
                        </PostalAddress>
                        <Email/>
                        <Phone>
                           <TelephoneNumber>
                              <CountryCode isoCountryCode="US"/>
                              <AreaOrCityCode/>
                              <Number/>
                              <Extension/>
                           </TelephoneNumber>
                        </Phone>
                        <Fax>
                           <TelephoneNumber>
                              <CountryCode isoCountryCode="US"/>
                              <AreaOrCityCode/>
                              <Number/>
                              <Extension/>
                           </TelephoneNumber>
                        </Fax>
                        <URL/>
                     </Address>
                     <OrderMethods>
                        <OrderMethod>
                           <OrderTarget>
                              <OtherOrderTarget>Oracle XML Direct</OtherOrderTarget>
                           </OrderTarget>
                           <OrderProtocol/>
                        </OrderMethod>
                        <Contact>
                           <Name xml:lang="en"/>
                           <Email/>
                           <Phone>
                              <TelephoneNumber>
                                 <CountryCode isoCountryCode="US"/>
                                 <AreaOrCityCode/>
                                 <Number>-</Number>
                                 <Extension/>
                              </TelephoneNumber>
                           </Phone>
                        </Contact>
                     </OrderMethods>
                  </SupplierLocation>
               </Supplier>
            </SupplierList>
            <ShipTo>
               <Address>
                  <Name xml:lang="en"/>
                  <PostalAddress>
                     <Street>INSERM U1153 - Port Royal</Street>
                     <Street>Maternité Port Royal - 6ème Etage</Street>
                     <Street>53 Avenue de l'Observatoire</Street>
                     <City>PARIS</City>
                     <State/>
                     <PostalCode>75014</PostalCode>
                     <Country isoCountryCode="FR">FR</Country>
                  </PostalAddress>
                  <Email/>
                  <Phone>
                     <TelephoneNumber>
                        <CountryCode isoCountryCode="US"/>
                        <AreaOrCityCode/>
                        <Number>01 42 34 55 73</Number>
                        <Extension/>
                     </TelephoneNumber>
                  </Phone>
                  <URL/>
               </Address>
            </ShipTo>
            <Tax>
               <Money currency="EUR"/>
               <Description xml:lang="en">ABSF_20</Description>
               <TaxDetail category="PURCHASING" percentageRate="20">
                  <TaxAmount>
                     <Money currency="EUR"/>
                  </TaxAmount>
                  <TaxLocation xml:lang="en"/>
                  <Description xml:lang="en">TVA SUR ABS France 20%</Description>
               </TaxDetail>
            </Tax>
            <Comments xml:lang="en"/>
         </ItemOut>
      </OrderRequest>
   </Request>
</cXML>
XML;

//setting the curl parameters.
/*
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
// Following line is compulsary to add as it is:
curl_setopt($ch, CURLOPT_POSTFIELDS,"xmlRequest=" . $input_xml);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
$data = curl_exec($ch);

$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$lib->debug($status);

curl_close($ch);
*/

// $ch = curl_init('http://api.local/rest/users');

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

print_r($data);
exit;
// $oXML = simplexml_load_string($data);
$oXML = new SimpleXMLElement( $data );
header( 'Content-type: text/xml' );

exit;
$foo =  (string) $oXML->body->loginURL;
header('Location: '.$foo);

exit;

if(curl_errno($ch))
	print curl_error($ch);
else
	curl_close($ch);

 echo $data;

 //$xml = new SimpleXMLElement($data);
//  $lib->debug($xml);


 exit;

//convert the XML result into array
$array_data = json_decode(json_encode(simplexml_load_string($data)), true);

print_r('<pre>');
print_r($array_data);
print_r('</pre>');

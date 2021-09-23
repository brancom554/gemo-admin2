<?php

unset($error);
foreach ($_REQUEST as $k => $v) {
    $_REQUEST[$k] = urldecode(trim($v));
}

if ($_SESSION['customer']['authValidated']) {
    //customer shoud be authenticated 
    $magasin_code = $_REQUEST['magasin_code'];
    $magasin = $sqlData->getMagasinByCOde($magasin_code);
    // $lib->debug($_REQUEST['orders']['productemailmagasin']);
    // $lib->debug($magasin['data'][0]->email_magasin);
    // exit;

    $orders = json_decode($_REQUEST['orders']);
    //  $lib->debug($orders);
    //  exit;

    $customer_id = $_SESSION['customer']['contact_num'];


    $total = 0;
    foreach ($orders as $key => $order) {
        $total += floatval($order->count) * floatval($order->price);
    }
    // Insert Order in database 
    $order_inserted_id = $sqlData->insertNewOrder($customer_id, "EN_ATTENTE", 0, 0, 0, $total, $orders[0]->storeCode, 0, 0);

    // Insert each order line in database
    $data = "";
    foreach ($orders as $order) {
        $quantite_available = $order->qte_stock - $order->count;
        $order_line_id = $sqlData->insertNewOrderLine($order_inserted_id, $order->id,  $order->count, $quantite_available, "EN_ATTENTE", $orders[0]->storeCode, $order->price);
        // $lib->debug($order_line_id);
        //$data = json_encode($order->id);
    }
    // exit;

    // $email = $magasin['data'][0]->email_magasin;
    // $lib->debug($email);
    // exit;
    $mailContent = $sqlData->getTemplate(5);
    $mailContent2 = $sqlData->getTemplate(8);
    $message = '';
    $subject = '';
    $subject2 = '';
    /* Replacing content from template */
    $keywordsContent = array(
        "{LOGIN}" => $user_exist['data'][0]->contact_num,
        "{PASSWORD}" => '****',
        "{STORE_NAME}" => $magasin['data'][0]->nom_magasin,
        "{SERVICE}" => $iniObj->serviceName,
        "{SITE_URL}" => $iniObj->siteUrl,
        "{COMPANY_NAME}" => $iniObj->companyName,
        "{COMPANY_ADDRESS}" => $iniObj->companyAddress,
        "{COMPANY_ZIP_CODE}" => $iniObj->companyZipCode,
        "{COMPANY_CITY}" => $iniObj->companyCity,
        "{COMPANY_COUNTRY}" => $iniObj->companyCountry,
        "{COMPANY_PHONE}" => $iniObj->companyPhoneNum,
        "{COMPANY_FAX}" => $iniObj->companyFaxNum,
        "{SERVICE_EMAIL}" => $iniObj->emailSender,
        "{LANG}" => $iniObj->defaultLang,
        "{TEMPLATE_IMG}" => $iniObj->customerSite . '/filesLib/template_bg_image/bienvenue.jpg'

    );

    $keywordsSubject = array(
        "{id}" => $val->shipping_id, "{STORE_NAME}" => $magasin['data'][0]->nom_magasin
    );

    $message = str_replace(
        array_keys($keywordsContent),
        array_values($keywordsContent),
        $mailContent['data'][0]->body
    );
    $message2 = str_replace(
        array_keys($keywordsContent),
        array_values($keywordsContent),
        $mailContent2['data'][0]->body
    );

    $subject = str_replace(array_keys($keywordsSubject), array_values($keywordsSubject), $mailContent['data'][0]->subject);
    $subject2 = str_replace(array_keys($keywordsSubject), array_values($keywordsSubject), $mailContent2['data'][0]->subject);
    // 				 $lib->sendEmailNoCC($iniObj->emailContact,"jacques.jocelyn@jiscomputing.com",$subject,$message
    // 					$lib->sendEmailNoCC($iniObj->emailContact,"pascal.sylvestre@jiscomputing.com",$subject,$message
    $sendEmail = $lib->sendConfirmEmailNoCC($iniObj->emailContact, $orders[0]->productemailmagasin, $subject, $message, $cc = $iniObj->emailContact);
    $sendEmail2 = $lib->sendConfirmEmailNoCC($iniObj->emailContact, $_SESSION['customer']['email_address'], $subject2, $message2, $cc = $iniObj->emailContact);

    if ($sendEmail && $sendEmail2) {
        echo "true||Commande en cours de traitement";
        exit;
    } else {
        echo "false||Le serveur de mail ne répond pas";
        exit;
    }
    echo "true||" . $orders[0]->storeCode;
    exit;
} else {
    echo "denied||" . $lang->trl("Autentification requise !");
    exit;
}

exit;

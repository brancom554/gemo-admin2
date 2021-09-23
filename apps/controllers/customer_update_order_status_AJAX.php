<?php
unset($message);
unset($error);

if (in_array("ROLE_ENTREPRISE", (explode(" ", $_SESSION['customer']['role_contact'])))) {
    foreach ($_REQUEST as $k => $v) {
        $_REQUEST[$k] = urldecode(trim($v));
    }

    $customer_id = $_REQUEST['customer_id'];
    $order_id = $_REQUEST['_order_id'];
    $status = $_REQUEST['_status'];

    $order_lines = $sqlData->getOrdersLinesByOrder($order_id);

    if (sizeof($order_lines['data']) == 0) {
        echo "false|| Commande incorrecte ";
    }

    $_data = [
        'order_id' => $order_id,
        'status' => $status
    ];
    $_result = $sqlData->updateOrder($_data);


    if ($_result == true) {
        echo "true|| Le statut de la commande a été enregistré avec succès";

        $customer = $sqlData->authenticate($customer_id)['data'];
        // $lib->debug($customer[0]->email_address);
        // exit;
        $email = $customer[0]->email_address;
        $mailContent = $sqlData->getTemplate(4);
        $message = '';
        $subject = '';
        /* Replacing content from template */
        $keywordsContent = array(
            "{LOGIN}" => $user_exist['data'][0]->contact_num,
            "{PASSWORD}" => '****',
            "{STORE_NAME}" => $_SESSION['customer']['store_name'],
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
            "{id}" => $val->shipping_id, "{STORE_NAME}" => $_SESSION['customer']['store_name']
        );

        $message = str_replace(
            array_keys($keywordsContent),
            array_values($keywordsContent),
            $mailContent['data'][0]->body
        );
        $subject = str_replace(array_keys($keywordsSubject), array_values($keywordsSubject), $mailContent['data'][0]->subject);
        // 				 $lib->sendEmailNoCC($iniObj->emailContact,"jacques.jocelyn@jiscomputing.com",$subject,$message
        // 					$lib->sendEmailNoCC($iniObj->emailContact,"pascal.sylvestre@jiscomputing.com",$subject,$message
        $sendEmail = $lib->sendConfirmEmailNoCC($iniObj->emailContact, $email, $subject, $message, $cc = $iniObj->emailContact);

        if ($sendEmail) {
            echo "true||Commande en cours de traitement, notification envoyée.";
            exit;
        } else {
            echo "false||Le serveur de mail ne répond pas";
            exit;
        }
    } else {
        echo "false|| Une erreur est survenue";
        exit;
    }
} else {
    echo "false|| Une erreur est survenue";
    exit;
}
exit;

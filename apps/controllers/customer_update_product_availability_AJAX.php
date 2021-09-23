<?php
unset($message);
unset($error);

if (in_array("ROLE_ENTREPRISE", (explode(" ", $_SESSION['customer']['role_contact'])))) {
    foreach ($_REQUEST as $k => $v) {
        $_REQUEST[$k] = urldecode(trim($v));
    }

    // $customer_id = $_REQUEST['customer_id'];
    $order_lines_id = $_REQUEST['_order_lines_id'];
    $order_id = $_REQUEST['_order_id'];
    $status = $_REQUEST['_status'];
    $product_id = $_REQUEST['_product_id'];


    $_data = [
        'order_lines_id' => $order_lines_id,
        '_product_id' => $product_id,
        'status' => $status,
    ];

    $order_customer = $sqlData->getOrderCustomer($order_id)['data'][0]->customer_number;
    $customer_email = $sqlData->getUserBylogin($order_customer)['data'][0]->email;
    // $product_libelle = $sqlData->getProductStockQuantity($product_id)['data'][0]->libelle;
    // $lib->debug($customer_email);
    // exit;
    $_result = $sqlData->updateOrderlinesAvailability($_data);

    if ($_result == true) {
        echo "true|| Le statut de la commande a été enregistré avec succès";

        $customer = $sqlData->authenticate($customer_id)['data'];
        // $lib->debug($customer[0]->email_address);
        // exit;
        $email = $customer[0]->email_address;
        $mailContent = $sqlData->getTemplate(10);
        $message = '';
        $subject = '';
        /* Replacing content from template */
        $keywordsContent = array(
            "{LOGIN}" => $order_customer,
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
            "{PRODUCT_NAME}" => $product_libelle,
            // "{TEMPLATE_IMG}" => $iniObj->customerSite . '/filesLib/template_bg_image/bienvenue.jpg'

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
        $sendEmail = $lib->sendConfirmEmailNoCC($iniObj->emailContact, $customer_email, $subject, $message, $cc = $iniObj->emailContact);

        if ($sendEmail) {
            echo "true||La commande de ce produit a été annulée";
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

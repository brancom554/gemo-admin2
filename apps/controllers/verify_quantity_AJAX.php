<?php

unset($error);
foreach ($_REQUEST as $k => $v) {
    $_REQUEST[$k] = urldecode(trim($v));
}

if (isset($_REQUEST['idproduit'])) {
    $product_id = $_REQUEST['idproduit'];
    $quantity = $_REQUEST['quantity'];
    // $lib->debug($product_id);
    // get product stock quantity 
    $stock = $sqlData->getProductStockQuantity($product_id);
    $stock = $stock['data'][0];
    // $lib->debug($stock);
    if ($quantity > $stock->qte_stock) {
        echo "false||La quantité disponible de ce produit est inférieure à celle que vous demandez.";
        exit;
    } else {
        echo "true||Quantité disponible.";
        exit;
    }
    // Insert each order line in database
    exit;
}

exit;

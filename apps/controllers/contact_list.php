<?php
$contacts = $sqlData->getContacts()['data'];
$companies = $sqlData->getCompanies()['data'];
if (file_exists(_VIEW_PATH . "/customer/contact_list.phtml"))  $view = "/customer/contact_list.phtml";
else  $view = "/customer/contact_list.phtml";

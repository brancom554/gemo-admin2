<?php
require_once(_APPS_PATH.'/classes/Database.php');
switch ($url_array[3]) {
    case 'ajouter':
        include "addManager.php";
        break;
    
    default:
        $db = new Database();
        $q = $db->connectDb();
        $data['manager'] = $q->query('SELECT * FROM users WHERE is_manager=0 AND company_id='.$url_array[3])->fetchAll();
        
        if(file_exists(_VIEW_PATH.$lib->lang."/agentManager.phtml"))  $view=$lib->lang."/agentManager.phtml";
        else  $view=$iniObj->defaultLang."/agentManager.phtml";
        break;
}

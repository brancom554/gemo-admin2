<?php

require_once(_APPS_PATH.'/classes/Email.php');

// Action pour l'envois de mail du formulaire de PRISE DE RENDEZ
if (isset($_POST['rdv'])) {
    $body = "Information de la prise de rendez-vous:<br>Nom du gérant : ".$_POST['rdv_gerant']."<br> Nom de la société: ".$_POST['rdv_name']."<br>Contact: ".$_POST['rdv_contact']."<br><br>Poste dans la société : ".$_POST['rdv_poste'];
    $sujet = "Demande de rendez-vous";
    $email = new Email();
    $send = $email->EnvoisDeEmail($sujet,$body);
}

// Action pour l'envois de mail du formulaire Contact
if (isset($_POST['contact'])) {
    $body = "Information du client:<br>Nom du client : ".$_POST['client']."<br> Nom de la société: ".$_POST['clientsociete']."<br>Contact: ".$_POST['clientphone']."<br><br>Message : ".$_POST['clientmessage'];
    $email = new Email();
    $send = $email->EnvoisDeEmail($_POST['clientsubject'],$body);
    if ($send) {
        $status = true;
        $classe = "success";
        $notification = "Votre message a été bien reçu. On vous répondra dans les plus bref délais.";
    }
    $email = null;
    //var_dump($send);
    //exit;
}

// Action pour l'envois de mail du formulaire ESSAI
if (isset($_POST['essai'])) {
    $body = "Information de la prise de rendez-vous:<br>Nom du gérant : ".$_POST['essai_gerant']."<br> Nom de la société: ".$_POST['essai_name']."<br>Contact: ".$_POST['essai_contact']."<br>Le numéro :".$_POST['essai_phone']."<br>Addresse : ".$_POST['essai_address']."<br>Numéro IFU : ".$_POST['essai_ifu']."Les services : ".$_POST['essai_servive'];
    $sujet = "Demande d'essai pour la société ".$_POST['essai_name'];
    $email = new Email();
    $send = $email->EnvoisDeEmail($sujet,$body);
}

if(file_exists(_VIEW_PATH."/index.phtml"))  $view="index.phtml";
else  $view="index.phtml";
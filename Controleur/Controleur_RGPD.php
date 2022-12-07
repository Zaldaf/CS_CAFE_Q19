<?php

use App\Vue\Vue_Connexion_Formulaire_client;
use App\Vue\Vue_RGPD;
use App\Vue\Vue_Structure_BasDePage;
use App\Vue\Vue_Structure_Entete;

$Vue->setEntete(new Vue_Structure_Entete());
\App\Utilitaire\Singleton_Logger::getInstance()->debug("test log $action $case");
switch ($action) {

    case "AccepterRGPD":
        //Il faut le code pour update BDD
        \App\Modele\Modele_Salarie::Salarie_AccepterRGPD($_SESSION["idSalarie"]);
        $Vue->setMenu(new \App\Vue\Vue_Menu_Entreprise_Salarie(0));

        break;
    case "RefuserRGPD":
        //Il faut le code pour log out
        session_destroy();
        unset($_SESSION);
        $Vue->addToCorps(new Vue_Connexion_Formulaire_client());
        break;

    case "AfficherRGPD":
    default:
        $Vue->addToCorps(composant: new Vue_RGPD());
        break;
}
$Vue->setBasDePage(new Vue_Structure_BasDePage());
<?php

use App\Vue\Vue_Connexion_Formulaire_administration;
use App\Vue\Vue_RGPD_Utilisateur;
use App\Vue\Vue_Structure_BasDePage;
use App\Vue\Vue_Structure_Entete;

$Vue->setEntete(new Vue_Structure_Entete());
switch ($action) {

    case "AccepterRGPD":
        //Il faut le code pour update BDD
        \App\Modele\Modele_Utilisateur::Utilisateur_AccepterRGPD($_SESSION["idUtilisateur"]);
        $Vue->setMenu(new \App\Vue\Vue_Menu_Administration($_SESSION['niveauAutorisation']));

        break;
    case "RefuserRGPD":
        //Il faut le code pour log out
        session_destroy();
        unset($_SESSION);
        $Vue->addToCorps(new Vue_Connexion_Formulaire_administration());
        break;

    case "AfficherRGPD":
    default:
        $Vue->addToCorps(composant: new Vue_RGPD_Utilisateur());
        break;
}
$Vue->setBasDePage(new Vue_Structure_BasDePage());
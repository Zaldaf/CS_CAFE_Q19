<?php
session_start();
include_once "vendor/autoload.php";

use App\Utilitaire\Singleton_Logger;
use App\Utilitaire\Vue;
use App\Vue\Vue_AfficherMessage;
use App\Vue\Vue_Structure_Entete;
use function App\Fonctions\CSRF_Renouveler;

$GLOBALS["adminFileName"] = "index_admin1234.php";

$Vue = new Vue();


//Identification du cas demandé (situation)
if (isset($_REQUEST["case"])) {
    $case = $_REQUEST["case"];

} else
    $case = "Cas_Par_Defaut";

//Identification de l'action demandée
if (isset($_REQUEST["action"])) {
    $action = $_REQUEST["action"];

} else
    $action = "Action_Par_Defaut";

if(isset($_SESSION["typeConnexion"]))
    $Vue->addToCorps(new Vue_AfficherMessage("Debug : typeConnexion $_SESSION[typeConnexion]<br>"));
if(isset($_SESSION["idUtilisateur"]))
    $Vue->addToCorps(new Vue_AfficherMessage("Debug : idUtilisateur $_SESSION[idUtilisateur]<br>"));
if(isset($_SESSION["niveauAutorisation"]))
    $Vue->addToCorps(new Vue_AfficherMessage("niveauAutorisation : niveauAutorisation $_SESSION[niveauAutorisation]<br>"));




switch ($case) {
    case "Gerer_CommandeClient":
    case "Gerer_Commande":
        include "Controleur/Controleur_Gerer_Commande.php";
        break;
    case "Gerer_entreprisesPartenaires":
        include "Controleur/Controleur_Gerer_entreprisesPartenaires.php";
        break;
    case "Gerer_utilisateur":
        include "Controleur/Controleur_Gerer_utilisateur.php";
        break;
    case "Gerer_catalogue":
        include "Controleur/Controleur_Gerer_catalogue.php";
        break;
    case "Gerer_monCompte":
        include "Controleur/Controleur_Gerer_monCompte.php";
        break;
    case "RGPD":
        include "Controleur/Controleur_RGPD_Utilisateur.php";
        break ;
    default:
        include "Controleur/Controleur_visiteur_admin.php";
        break;
}


$Vue->afficher();
<?php
session_start();
include_once "vendor/autoload.php";

use App\Utilitaire\Singleton_Logger;
use App\Utilitaire\Vue;
use App\Vue\Vue_AfficherMessage;
use App\Vue\Vue_Connexion_Formulaire_client;
use Monolog\Handler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use App\Vue\Vue_Structure_Entete;
use function App\Fonctions\CSRF_Renouveler;


//Page appelée pour les utilisateurs publics


$Vue = new Vue();
if(isset($_REQUEST["CSRF"]))
    $CSRF = $_REQUEST["CSRF"];
else
    $CSRF  = "";
if(!verifierCSRF($CSRF))
{//La vérification CSRF a échoué ! on bloque tout !
    //si on fait le choix de la connexion !
    session_destroy();
    unset($_SESSION);
    $Vue->setEntete(new Vue_Structure_Entete());
    $Vue->addToCorps(new Vue_Connexion_Formulaire_client());
    $Vue->addToCorps(new Vue_AfficherMessage("Il ne faut pas actualiser !"));
    $Vue->afficher();
    \Singleton_Logger::getInstance()->notice('jeton CSRF réutiliser ');
    die();
}

//Charge le gestionnaire de vue


if (isset($_SESSION["typeConnexionBack"])) {
    $typeConnexion = $_SESSION["typeConnexionBack"];
} else {
    $typeConnexion = "visiteur";
}

//utiliser en débuggage pour avoir le type de connexion
//$Vue->addToCorps(new Vue_AfficherMessage("<br>typeConnexion $typeConnexion<br>"));

//Identification du cas demandé (situation)
if (isset($_REQUEST["case"]))
    $case = $_REQUEST["case"];
else
    $case = "Cas_Par_Defaut";

//Identification de l'action demandée
if (isset($_REQUEST["action"]))
    $action = $_REQUEST["action"];
else
    $action = "Action_Par_Defaut";

switch ($typeConnexion) {
    case "visiteur" :
        include "Controleur/Controleur_visiteur.php";
        break;
    case "entreprise" :

        switch ($case) {
            default:
            case "Cas_Par_Defaut":
            case "Gerer_Entreprise" :
                include "Controleur/Controleur_Gerer_Entreprise.php";
                break;
        }
        break;
    case "entreprise_utilisateur" :
        switch ($case) {
            case "Cas_Par_Defaut":
            case "Gerer_catalogue":
            case "Catalogue_client":
                include "Controleur/Controleur_Catalogue_client.php";
                break;
            case "Gerer_CommandeClient":
                include "Controleur/Controleur_Gerer_CommandeClient.php";
                break;
            case "Gerer_Panier":
                include "Controleur/Controleur_Gerer_Panier.php";
                break;
            case "Gerer_MonCompte_Salarie":
                include "Controleur/Controleur_Gerer_MonCompte_Salarie.php";
                break;
            case "RGPD":
                include "Controleur/Controleur_RGPD.php";
                break ;
        }
}


$Vue->afficher();
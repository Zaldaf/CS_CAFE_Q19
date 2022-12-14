<?php


use App\Modele\Modele_Utilisateur;
use App\Vue\Vue_Compte_Administration_Gerer;
use App\Vue\Vue_Menu_Administration;
use App\Vue\Vue_Utilisateur_Changement_MDP;
use App\Vue\Vue_Connexion_Formulaire_administration;
use App\Vue\Vue_Structure_BasDePage;
use App\Vue\Vue_Structure_Entete;
use function App\Fonctions\CalculComplexiteMdp;


switch ($action) {
    case "changerMDP":
        //Il a cliqué sur changer Mot de passe. Cas pas fini
        $Vue->setEntete(new Vue_Structure_Entete());
        $Vue->setMenu(new Vue_Menu_Administration($_SESSION["niveauAutorisation"]));
        $Vue->addToCorps(new Vue_Utilisateur_Changement_MDP("", "Gerer_monCompte"));
        break;
    case "submitModifMDP":
        //il faut récuperer le mdp en BDD et vérifier qu'ils sont identiques
        $utilisateur = Modele_Utilisateur::Utilisateur_Select_ParId($_SESSION["idUtilisateur"]);
        if (password_verify($_REQUEST["AncienPassword"], $utilisateur["motDePasse"])) {
            if (CalculComplexiteMdp($_REQUEST["NouveauPassword"]>=90)){

                if ($_REQUEST["NouveauPassword"] == $_REQUEST["ConfirmPassword"]) {
                    $Vue->setEntete(new Vue_Structure_Entete());
                    $Vue->setMenu(new Vue_Menu_Administration($_SESSION["niveauAutorisation"]));
                    Modele_Utilisateur::Utilisateur_Modifier_motDePasse($_SESSION["idUtilisateur"], $_REQUEST["NouveauPassword"]);
                    $Vue->addToCorps(new Vue_Compte_Administration_Gerer("<label><b>Votre mot de passe a bien été modifié</b></label>"));
                    // Dans ce cas les mots de passe sont bons, il est donc modifier

                } else {
                    $Vue->setEntete(new Vue_Structure_Entete());
                    $Vue->setMenu(new Vue_Menu_Administration($_SESSION["niveauAutorisation"]));
                    $Vue->addToCorps(new Vue_Utilisateur_Changement_MDP("<label><b>Les nouveaux mots de passe ne sont pas identiques</b></label>", "Gerer_monCompte"));
                }
            }else{
                $Vue->setEntete(new Vue_Structure_Entete());
                $Vue->setMenu(new Vue_Menu_Administration($_SESSION["niveauAutorisation"]));
                $Vue->addToCorps(new Vue_Utilisateur_Changement_MDP("<label><b>Le mot de passe doit être supérieur a 90</b></label>", "Gerer_monCompte"));
            }
            //on vérifie si le mot de passe de la BDD est le même que celui rentré

        } else {
            $Vue->setEntete(new Vue_Structure_Entete());
            $Vue->setMenu(Vue_Administration_Menu());
            $Vue->addToCorps(new Vue_Utilisateur_Changement_MDP("<label><b>Vous n'avez pas saisi le bon mot de passe</b></label>", "Gerer_monCompte"));
        }
        break;
    case  "SeDeconnecter":
        //L'utilisateur a cliqué sur "se déconnecter"
        session_destroy();
        unset($_SESSION);
        $Vue->setEntete(new Vue_Structure_Entete());
        $Vue->addToCorps(new Vue_Connexion_Formulaire_administration());
        break;
    case "réinitialiserMDPUtilisateur":
        //Réinitialiser MDP sur la fiche de l'entreprise
        $Utilisateur = Modele_Utilisateur::Utilisateur_Select_ParId($_REQUEST["idSalarie"]);
        \App\Modele\Modele_Salarie::Salarie_Modifier_motDePasse($_REQUEST["idSalarie"], "secret"); //$Utilisateur["idUtilisateur"]

        $listeUtilisateur = Modele_Utilisateur::Utilisateur_Select();
        $Vue->addToCorps(new Vue_Utilisateur_Liste($listeUtilisateur));

        break;
    default:
        //Cas par défaut: affichage du menu des actions.
        $Vue->setEntete(new Vue_Structure_Entete());
        $Vue->setMenu(new Vue_Menu_Administration($_SESSION["niveauAutorisation"]));
        $Vue->addToCorps(new Vue_Compte_Administration_Gerer());
        break;
}

$Vue->setBasDePage(new Vue_Structure_BasDePage());

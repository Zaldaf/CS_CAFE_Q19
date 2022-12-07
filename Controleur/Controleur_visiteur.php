<?php

use App\Modele\Modele_Entreprise;
use App\Modele\Modele_Salarie;
use App\Vue\Vue_Connexion_Formulaire_client;
use App\Vue\Vue_Mail_Confirme;
use App\Vue\Vue_Mail_ReinitMdp;
use App\Vue\Vue_Structure_BasDePage;
use App\Vue\Vue_Structure_Entete;

use PHPMailer\PHPMailer\PHPMailer;
//Ce contrôleur gère le formulaire de connexion pour les visiteurs

$Vue->setEntete(new Vue_Structure_Entete());

switch ($action) {
    case "reinitmdpconfirm":

        //On regarde si le mail appartient à une entreprise
        $entreprise = Modele_Entreprise::Entreprise_Select_ParMail($_REQUEST["email"]);

        if ($entreprise != null) {
            // le mail appartient à une entreprise
            // on va faire le mail pour cette entreprise !
            $nvMdp = "secret";
            Modele_Entreprise::Entreprise_Modifier_motDePasse($idEntreprise, $nvMdp);

            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = '127.0.0.1';
            $mail->CharSet = "UTF-8";
            $mail->Port = 1025; //Port non crypté
            $mail->SMTPAuth = false; //Pas d’authentification
            $mail->SMTPAutoTLS = false; //Pas de certificat TLS
            $mail->setFrom('contact@labruleriecomtoise.fr', 'contact');
            $mail->addAddress($entreprise["mailContact"], $entreprise["denomination"]);
            if ($mail->addReplyTo('test@labruleriecomtoise.fr', 'admin')) {
                $mail->Subject = 'Objet : MDP !';
                $mail->isHTML(true);
                $mail->Body = "Votre nouveau mot de passe est : $nvMdp";

                if (!$mail->send()) {
                    $msg = 'Désolé, quelque chose a mal tourné. Veuillez réessayer plus tard.';


                } else {
                    $msg = 'Message envoyé ! Merci de nous avoir contactés.';


                }
            } else {
                $msg = 'Il doit manquer qqc !';


            }
        } else {
            //On regarde si le mail appartient à un salarie
            $salarie = Modele_Salarie::Salarie_Select_byMail($_REQUEST["email"]);

            if ($salarie != null) {
                $nvMdp = "secret";
                Modele_Salarie::Salarie_Modifier_motDePasse($salarie["idSalarie"], $nvMdp);

                $mail = new PHPMailer;
                $mail->isSMTP();
                $mail->Host = '127.0.0.1';
                $mail->CharSet = "UTF-8";
                $mail->Port = 1025; //Port non crypté
                $mail->SMTPAuth = false; //Pas d’authentification
                $mail->SMTPAutoTLS = false; //Pas de certificat TLS
                $mail->setFrom('contact@labruleriecomtoise.fr', 'contact');
                $mail->addAddress($salarie["mail"], $salarie["nom"] . " " . $salarie["prenom"]);
                if ($mail->addReplyTo('contact@labruleriecomtoise.fr', 'contact')) {
                    $mail->Subject = 'Objet : MDP !';
                    $mail->isHTML(true);
                    $mail->Body = "Votre nouveau mot de passe est $nvMdp";

                    if (!$mail->send()) {
                        $msg = 'Désolé, quelque chose a mal tourné. Veuillez réessayer plus tard.';
                    } else {
                        $msg = 'Message envoyé ! Merci de nous avoir contactés.';
                    }
                } else {
                    $msg = 'Il doit manquer qqc !';
                }
            }
        }

        $Vue->addToCorps(new Vue_Mail_Confirme());

        break;
    case "reinitmdp":


        $Vue->addToCorps(new Vue_Mail_ReinitMdp());

        break;

    case "reinitmdpconfirmtoken":

        //On regarde si le mail appartient à une entreprise
        $salarier = Modele_Salarie::Salarie_Select_byMail($_REQUEST["email"]);

        if ($salarier != null) {
            // le mail appartient à une entreprise
            // on va faire le mail pour cette entreprise !
            $octetsAleatoires = openssl_random_pseudo_bytes (256) ;
            $valeurJeton = sodium_bin2base64($octetsAleatoires, SODIUM_BASE64_VARIANT_ORIGINAL);
            $jeton = \App\Modele\Modele_Jeton::Jeton_Creation($valeurJeton,$salarier["idSalarie"],1);
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = '127.0.0.1';
            $mail->CharSet = "UTF-8";
            $mail->Port = 1025; //Port non crypté
            $mail->SMTPAuth = false; //Pas d’authentification
            $mail->SMTPAutoTLS = false; //Pas de certificat TLS
            $mail->setFrom('contact@labruleriecomtoise.fr', 'contact');
            $mail->addAddress($salarier["mail"], $salarier["nom"] . " " . $salarier["prenom"]);
            if ($mail->addReplyTo('test@labruleriecomtoise.fr', 'admin')) {
                $mail->Subject = 'Objet : MDP !';
                $mail->isHTML(true);
                $mail->Body =  "<a href=\"http://localhost:8000/index.php?action=token&token=.$valeurJeton.\">Lien à cliquer</a>";

                if (!$mail->send()) {
                    $msg = 'Désolé, quelque chose a mal tourné. Veuillez réessayer plus tard.';


                } else {
                    $msg = 'Message envoyé ! Merci de nous avoir contactés.';


                }
            } else {
                $msg = 'Il doit manquer qqc !';


            }
        }
        $Vue->addToCorps(new Vue_Mail_Confirme());


        break;

    case "Se connecter" :

        if (isset($_REQUEST["compte"]) and isset($_REQUEST["password"])) {
            //Si tous les paramètres du formulaire sont bons


            //Vérification du mot de passe
            $entreprise = Modele_Entreprise::Entreprise_Select_ParCompte($_REQUEST["compte"]);
            // on regarde si l'entreprise existe, et si elle est activée
            if ($entreprise != null) {
                if ($entreprise["desactiver"] == 0) {
                    if (password_verify($_REQUEST["password"], $entreprise["motDePasse"])) {//le mot de passe est associable à ce Hash
                        $_SESSION["idEntreprise"] = $entreprise["idEntreprise"];
                        $_SESSION["typeConnexionBack"] = "entreprise";


                        include "./Controleur/Controleur_Gerer_Entreprise.php";

                    } else {//mot de passe pas bon
                        $msgError = "Mot de passe erroné";

                        $Vue->addToCorps(new Vue_Connexion_Formulaire_client($msgError));

                    }
                } else {
                    $msgError = "Votre entreprise n'a pas l'autorisation nécessaire pour accéder au site";

                    $Vue->addToCorps(new Vue_Connexion_Formulaire_client($msgError));

                }
            } else {
                //On essaie de connecter un salarié
                $salarie = Modele_Salarie::Salarie_Select_byMail($_REQUEST["compte"]);
                // on regarde si l'entreprise existe, et si elle est activée
                if ($salarie != null) {
                    if ($salarie["actif"] == 1) {
                        if (password_verify($_REQUEST["password"], $salarie["password"])) {//le mot de passe est associable à ce Hash
                            $_SESSION["idSalarie"] = $salarie["idSalarie"];
                            $_SESSION["idEntreprise"] = $salarie["idEntreprise"];
                            $_SESSION["typeConnexionBack"] = "entreprise_utilisateur";

                            // if RGPD accepté
                            //A inclure que si RGPD acceptée !
                            if ($salarie["aAccepteRGPD"] == 0 || $salarie["aAccepteRGPD"] == "0")
                                include "./Controleur/Controleur_RGPD.php";
                            else
                                include "./Controleur/Controleur_Catalogue_client.php";
                            //else
                            //


                        } else {//mot de passe pas bon
                            $msgError = "Mot de passe erroné";

                            $Vue->addToCorps(new Vue_Connexion_Formulaire_client($msgError));

                        }
                    } else {
                        $msgError = "Compte désactivé";

                        $Vue->addToCorps(new Vue_Connexion_Formulaire_client($msgError));

                    }
                } else {

                    $msgError = "Identification invalide";

                    $Vue->addToCorps(new Vue_Connexion_Formulaire_client($msgError));


                }
            }
        } else {

            //Il y a un oubli quelque part !
            $msgError = "Vous devez saisir toutes les informations";


            $Vue->addToCorps(new Vue_Connexion_Formulaire_client($msgError));


        }
        break;
    case "token":
        //Là où une commande par token sera traitée
        break;
    default:

        $Vue->addToCorps(new Vue_Connexion_Formulaire_client());

        break;
}


$Vue->setBasDePage(new Vue_Structure_BasDePage());
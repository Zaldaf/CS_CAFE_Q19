<?php
namespace App\Modele;

use App\Utilitaire\Singleton_ConnexionPDO;
use PDO;

class Modele_Entreprise
{
    /**
     * @param $connexionPDO : connexion à la base de données
     * @return mixed : le tableau des étudiants ou null (something went wrong...)
     */
    static function Entreprise_Select()
    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        $requetePreparee = $connexionPDO->prepare('select * from `entreprise` order by denomination');
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        $tableauReponse = $requetePreparee->fetchAll(PDO::FETCH_ASSOC);
        return $tableauReponse;
    }

    /**
     * @param $connexionPDO : connexion à la base de données
     * @param $idEntreprise
     * @return mixed
     */
    static function Entreprise_Select_ParId($idEntreprise)
    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        $requetePreparee = $connexionPDO->prepare('
select * from `entreprise` where idEntreprise = :paramId');
        $requetePreparee->bindParam('paramId', $idEntreprise);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        $etudiant = $requetePreparee->fetch(PDO::FETCH_ASSOC);
        return $etudiant;
    }

    /**
     * @param $connexionPDO
     * @param $denomination
     * @param $rueAdresse
     * @param $complementAdresse
     * @param $codePostal
     * @param $ville
     * @param $pays
     * @param $mailContact
     * @param $siret
     * @return mixed
     */
    static function Entreprise_Creer($denomination, $rueAdresse, $complementAdresse, $codePostal, $ville, $pays, $mailContact, $siret)
    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();

        $requetePreparee = $connexionPDO->prepare(
            'INSERT INTO `entreprise` (`idEntreprise`, `denomination`, `rueAdresse`, `complementAdresse`, `codePostal`, `ville`, `pays`, `mailContact`, `siret`) 
VALUES (NULL, :paramdenomination, :paramrueAdresse, :paramcomplementAdresse, :paramcodePostal, :paramville, :parampays, :parammailContact, :paramsiret);;');

        $requetePreparee->bindParam('paramdenomination', $denomination);
        $requetePreparee->bindParam('paramrueAdresse', $rueAdresse);
        $requetePreparee->bindParam('paramcomplementAdresse', $complementAdresse);
        $requetePreparee->bindParam('paramcodePostal', $codePostal);
        $requetePreparee->bindParam('paramville', $ville);
        $requetePreparee->bindParam('parampays', $pays);
//$requetePreparee->bindParam('paramnumCompte',$numCompte);
        $requetePreparee->bindParam('parammailContact', $mailContact);
        $requetePreparee->bindParam('paramsiret', $siret);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        $idEntreprise = $connexionPDO->lastInsertId();

        $numCompte = substr($denomination, 0, 8) . "_" . $idEntreprise;
        Modele_Entreprise::Entreprise_Modifier_numCompte($idEntreprise, $numCompte);
        Modele_Entreprise::Entreprise_Modifier_motDePasse($idEntreprise, $numCompte);
        $desactiver = 0;
        Modele_Entreprise::Entreprise_Modifier_Desactivation($idEntreprise, $desactiver);
        return $idEntreprise;
    }

    /**
     * @param $connexionPDO
     * @param $idEntreprise
     * @return mixed
     */
    static function Entreprise_Supprimer($idEntreprise)
    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();

        $requetePreparee = $connexionPDO->prepare('delete entreprise.* from `entreprise` where idEntreprise = :paramId');
        $requetePreparee->bindParam('paramId', $idEntreprise);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        return $reponse;
    }

    /**
     * @param $connexionPDO
     * @param $idEntreprise
     * @param $denomination
     * @param $rueAdresse
     * @param $complementAdresse
     * @param $codePostal
     * @param $ville
     * @param $pays
     * @param $mailContact
     * @param $siret
     * @return mixed
     */
    static function Entreprise_Modifier($idEntreprise, $denomination, $rueAdresse, $complementAdresse, $codePostal, $ville, $pays, $mailContact, $siret)

    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();

        $requetePreparee = $connexionPDO->prepare(
            'UPDATE `entreprise` 
SET `denomination`= :paramdenomination,
`rueAdresse`= :paramrueAdresse,
`complementAdresse`= :paramcomplementAdresse,
`codePostal`= :paramcodePostal ,
`ville`= :paramville,
`pays`= :parampays, 
`mailContact`= :parammailContact,
`siret`= :paramsiret
WHERE idEntreprise = :paramidEntreprise');
        $requetePreparee->bindParam('paramdenomination', $denomination);
        $requetePreparee->bindParam('paramrueAdresse', $rueAdresse);
        $requetePreparee->bindParam('paramcomplementAdresse', $complementAdresse);
        $requetePreparee->bindParam('paramcodePostal', $codePostal);
        $requetePreparee->bindParam('paramville', $ville);
        $requetePreparee->bindParam('parampays', $pays);
        $requetePreparee->bindParam('parammailContact', $mailContact);
        $requetePreparee->bindParam('paramsiret', $siret);
        $requetePreparee->bindParam('paramidEntreprise', $idEntreprise);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        return $reponse;
    }


    /**
     * @param $connexionPDO
     * @param $idEntreprise
     * @param $numCompte
     * @return mixed
     */
    static function Entreprise_Modifier_numCompte($idEntreprise, $numCompte)

    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();

        $requetePreparee = $connexionPDO->prepare(
            'UPDATE `entreprise` 
SET `numCompte`= :paramnumCompte 
WHERE idEntreprise = :paramidEntreprise');
        $requetePreparee->bindParam('paramnumCompte', $numCompte);
        $requetePreparee->bindParam('paramidEntreprise', $idEntreprise);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        return $reponse;
    }

    /**
     * @param $connexionPDO
     * @param $idEntreprise
     * @param $siret
     * @return mixed
     */
    static function Entreprise_Modifier_numSiret($idEntreprise, $siret)

    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();

        $requetePreparee = $connexionPDO->prepare(
            'UPDATE `entreprise` 
SET siret = :paramsiret
WHERE idEntreprise = :paramidEntreprise');
        $requetePreparee->bindParam('paramsiret', $siret);
        $requetePreparee->bindParam('paramidEntreprise', $idEntreprise);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        return $reponse;
    }

    /**
     * @param $connexionPDO
     * @param $idEntreprise
     * @param $mail
     * @return mixed
     */
    static function Entreprise_Modifier_mail($idEntreprise, $mail)

    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();

        $requetePreparee = $connexionPDO->prepare(
            'UPDATE `entreprise` 
SET mailContact = :parammailContact
WHERE idEntreprise = :paramidEntreprise');
        $requetePreparee->bindParam('parammailContact', $mail);
        $requetePreparee->bindParam('paramidEntreprise', $idEntreprise);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        return $reponse;
    }

    /**
     * @param $connexionPDO
     * @param $idEntreprise
     * @param $motDePasseClair
     * @return mixed
     */
    static function Entreprise_Modifier_motDePasse($idEntreprise, $motDePasseClair)

    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        $parammotDePasseHache = password_hash($motDePasseClair, PASSWORD_DEFAULT);

        $requetePreparee = $connexionPDO->prepare(
            'UPDATE `entreprise` 
SET motDePasse = :parammotDePasseHache
WHERE idEntreprise = :paramidEntreprise');
        $requetePreparee->bindParam('parammotDePasseHache', $parammotDePasseHache);
        $requetePreparee->bindParam('paramidEntreprise', $idEntreprise);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        return $reponse;
    }

    static function Entreprise_Modifier_motDePasse_ALL($motDePasseClair)

    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        $parammotDePasseHache = password_hash($motDePasseClair, PASSWORD_DEFAULT);

        $requetePreparee = $connexionPDO->prepare(
            'UPDATE `entreprise` 
SET motDePasse = :parammotDePasseHache ');
        $requetePreparee->bindParam('parammotDePasseHache', $parammotDePasseHache);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        return $reponse;
    }

    /**
     * @param $connexionPDO
     * @param $numCompte
     * @return mixed
     */
    static function Entreprise_Select_ParCompte($numCompte)
    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        $requetePreparee = $connexionPDO->prepare('select * from `entreprise` where numCompte = :paramnumCompte');
        $requetePreparee->bindParam('paramnumCompte', $numCompte);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        $entreprise = $requetePreparee->fetch(PDO::FETCH_ASSOC);
        return $entreprise;
    }

    static function Entreprise_Select_ParMail($mailContact)
    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        $requetePreparee = $connexionPDO->prepare('select * from `entreprise` where mailContact = :paramMailContact');
        $requetePreparee->bindParam('paramMailContact', $mailContact);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        $entreprise = $requetePreparee->fetch(PDO::FETCH_ASSOC);
        return $entreprise;
    }

    static function Entreprise_Modifier_Desactivation($idEntreprise, $desactiver)

    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();

        $requetePreparee = $connexionPDO->prepare(
            'UPDATE `entreprise` 
SET `desactiver`= :paramdesactiver
WHERE idEntreprise = :paramidEntreprise');
        $requetePreparee->bindParam('paramdesactiver', $desactiver);
        $requetePreparee->bindParam('paramidEntreprise', $idEntreprise);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        return $reponse;
    }

    static function passgen1($nbChar)
    {
        $chaine = "ABCDEFGHIJKLMONOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $pass = '';
        for ($i = 0; $i < $nbChar; $i++) {
            $pass .= $chaine[random_int(0,strlen($chaine))];
        }
        return $pass;
    }

}
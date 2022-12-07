<?php

namespace App\Modele;

use App\Utilitaire\Singleton_ConnexionPDO;
use PDO;
class Modele_Jeton
{
    /***
     * @param $valeur
     * @param $idUtilisateur
     * @param $codeAction (1 pour renouvelle mdp)
     * @return id jeton créé
     */
    static function  Jeton_Creation($valeur, $idUtilisateur, $codeAction)
    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();

        $requetePreparee = $connexionPDO->prepare(
            'INSERT INTO `token` (`id`, `valeur`, `codeAction`, `idUtilisateur`, `dateFin`) 
VALUES (NULL, :paramvaleur, :paramcodeAction, :paramidUtilisateur, :paramdateFin);');

        $requetePreparee->bindParam('paramvaleur', $valeur);
        $requetePreparee->bindParam('paramcodeAction', $codeAction);
        $requetePreparee->bindParam('paramidUtilisateur', $idUtilisateur);
        $date = new \DateTime();
        $date->add(new \DateInterval("PT900S"));
        $dateExpir = $date->format("y-m-d h:i:s");
        $requetePreparee->bindParam('paramdateFin', $dateExpir);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        $token = $connexionPDO->lastInsertId();
        return $token;
    }

    static function Jeton_Rechercher_ParValeur($valeur)
    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        $requetePreparee = $connexionPDO->prepare('select * from `token` where valeur = :paramId');
        $requetePreparee->bindParam('paramId', $valeur);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        $jeton = $requetePreparee->fetch(PDO::FETCH_ASSOC);
        return $jeton;
    }

    static function Jeton_Delete_parID($idJeton)
    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();

        $requetePreparee = $connexionPDO->prepare('delete token.* from `token` where id = :paramId');
        $requetePreparee->bindParam('paramId', $idJeton);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        return $reponse;
    }
}
<?php

namespace App\Modele;

use App\Utilitaire\Singleton_ConnexionPDO;
use App\Utilitaire\Singleton_ConnexionPDO_Log;
use PDO;
class Modele_Log
{
    static function Realiser_ajouter($idUtilisateur,$idTypeAction,$idObjet){
        $connexionPDO = Singleton_ConnexionPDO_Log::getInstance();
        $requetePreparee = $connexionPDO->prepare(' 
        insert into `realiser` ( idUtilisateur, idTypeAction,date,idObjet) 
        VALUE ( :idUtilisateur, :idTypeAction, :date, :idObjet)');
        $requetePreparee->bindParam('idUtilisateur', $idUtilisateur);
        $requetePreparee->bindParam('idTypeAction', $idTypeAction);
        $date = date("y-m-d H:i:s");
        $requetePreparee->bindParam('date', $date);
        $requetePreparee->bindParam('idObjet', $idObjet);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête

        return $reponse;
    }
}
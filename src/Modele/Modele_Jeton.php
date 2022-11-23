<?php

namespace App\Modele;

use App\Utilitaire\Singleton_ConnexionPDO;
use PDO;

class Modele_Jeton
{
    static function  Jeton_Creation($valeur, $idUtilisateur, $codeAction)
    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        /***/
    }

    static function Jeton_Rechercher($valeur)
    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        /***/
    }

    static function Jeton_Delete($idJeton)
    {
        $connexionPDO = Singleton_ConnexionPDO::getInstance();
        /***/
    }
}
<?php

namespace App\Utilitaire;

use PDO;

class Singleton_ConnexionPDO_Log extends PDO
{
    protected static ?PDO $_PDO = null;

    private function __construct()
    {
        parent::__construct('mysql:host=127.0.0.1;dbname=cs_cafe_log;charset=UTF8',
            "log_insert",
            "secret",
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            )
        );

    }

    public static function getInstance(): PDO
    {

        if (is_null(self::$_PDO)) {
            self::$_PDO = new Singleton_ConnexionPDO_Log();
        }
        return self::$_PDO;
    }
}

<?php
include_once "../vendor/autoload.php";
$utilisateur = \App\Modele\Modele_Salarie::Salarie_Select_byMail("userZoomBox@userZoomBox.com");

$octetsAleatoires = openssl_random_pseudo_bytes (256) ;
$valeurJeton = sodium_bin2base64($octetsAleatoires, SODIUM_BASE64_VARIANT_ORIGINAL);

$idJetonCree = \App\Modele\Modele_Jeton::Jeton_Creation($valeurJeton,$utilisateur["id"],1);

$jetonRecherche = \App\Modele\Modele_Jeton::Jeton_Rechercher_ParValeur($valeurJeton);

if($idJetonCree == $jetonRecherche["id"])
{
    //On a retrouvé le jeton par rapport à sa valeur :)
    //on check si l'utilisateur est le même :)
    if($jetonRecherche["idUtilisateur"] == $utilisateur["id"])
    {
        //L'utilisateur est bon :)
        if($jetonRecherche["codeAction"] == 1)
        {
            //Le code action ets bon :)
            \App\Modele\Modele_Jeton::Jeton_Delete_parID($idJetonCree);
            $jetonRechercheApresDel = \App\Modele\Modele_Jeton::Jeton_Rechercher_ParValeur($valeurJeton);
            if($jetonRechercheApresDel == false)
            {
                // Pas trouvé : c'est le résultat attendu
                echo "Test ok";
            }
            else
            {
                echo "Jeton_Delete_parID : erreur !!";
            }
        }
        else
        {
            echo "codeAction : pbm !";
        }
    }
    else
    {
        echo "idUtilisateur : pbm !";
    }
}
else
{
    echo "Jeton_Creation ou Jeton_Rechercher_ParValeur pbm !";
}

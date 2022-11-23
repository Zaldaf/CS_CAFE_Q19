<?php

function passgen1($nbChar) {
    $chaine ="mnoTUzS5678kVvwxy9WXYZRNCDEFrslq41GtuaHIJKpOPQA23LcdefghiBMbj0";
    srand((double)microtime()*1000000);
    $pass = '';
    for($i=0; $i<$nbChar; $i++){
        $pass .= $chaine[rand()%strlen($chaine)];
    }
    return $pass;
}
$dictionnaire=[];
for ($i=0;$i<25;$i++){
    $dictionnaire[] = [passgen1(10)];
}



//Création de la séquence aléatoire à la base du mot de passe
$octetsAleatoires = openssl_random_pseudo_bytes (8) ;
//Transformation de la séquence binaire en caractères alpha
$motDePasse = sodium_bin2base64($octetsAleatoires, SODIUM_BASE64_VARIANT_ORIGINAL);
echo $motDePasse;

<?php
namespace App\Fonctions;
    function Redirect_Self_URL():void{
        unset($_REQUEST);
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }

function GenereMDP($nbChar) :string{
    $chaine ="mnoTUzS5678kVvwxy9WXYZRNCDEFrslq41GtuaHIJKpOPQA23LcdefghiBMbj0";
    $ln = strlen($chaine);
    $pass = '';
    for($i=0; $i<$nbChar; $i++){
        $pass .= $chaine[random_int(0, $ln-1)];
    }
    return $pass;
}
function CalculComplexiteMdp($mdp) :int{
        $longueur= strlen($mdp);
        $charMin = "abcdefghijklmnopqrstuvwxyz ";
        $charMaj = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $chiffre = "0123456789";

        $isCharMin = false;
        $isCharMaj = false;
        $isChiffre = false;
        $isCharSpe = false;

        for ($i=0;$i <$longueur; $i++){



            if (strpos($charMin,$mdp[$i]>0)){
                $isCharMin=true;
            }
            elseif (strpos($charMaj,$mdp[$i]>0)){
                $isCharMaj=true;
            }
            elseif (strpos($chiffre,$mdp[$i]>0)){
              $isChiffre=true;
            }
            else {
                $isCharSpe=true;

            }

        }
        //Nb caractère
        $nbPossible = 0;
    if ($isCharMin){
        $nbPossible +=26;
    }
    if ($isCharMaj){
        $nbPossible +=26;
    }
    if ($isChiffre){
        $nbPossible +=10;
    }
    if ($isCharSpe){
        $nbPossible +=28;
    }
    //calcul bits
    $complexe=log(pow($longueur,$nbPossible))/log(2);
    return $complexe;
    }


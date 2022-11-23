<?php
function passgen1($nbChar)
{
$chaine = "ABCDEFGHIJKLMONOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789&é'()=*!;,~{[:\^@]}";
$pass = '';
for ($i = 0; $i < $nbChar; $i++) {
$pass .= $chaine[random_int(0,strlen($chaine))];
}
return $pass;
}

echo passgen1(10);
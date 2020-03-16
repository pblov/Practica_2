<?php


$url = array();

$url[]="https://movilsqmiappdev.azurewebsites.net/api/organigrama";
$url[]="https://movilsqmiappdev.azurewebsites.net/api/puesto_trabajo";
$url[]="https://movilsqmiappdev.azurewebsites.net/api/personal";
$url[]="https://movilsqmiappdev.azurewebsites.net/api/asignar_jefe_directo";
$url[]="https://movilsqmiappdev.azurewebsites.net/api/asignar_cargo";
$url[]="https://movilsqmiappdev.azurewebsites.net/api/asignar_area";
$url[]="https://movilsqmiappdev.azurewebsites.net/api/asignar_centro";


$ch = curl_init();
for($i=0;$i<count($url);$i++){
    
 curl_setopt($ch, CURLOPT_URL, $url[$i]);
curl_setopt($ch, CURLOPT_HEADER, 0);

 curl_exec($ch);
    
}

curl_close($ch);


?>
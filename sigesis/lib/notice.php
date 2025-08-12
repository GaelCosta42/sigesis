<?php

$url_verifica = "https://controle.sigesis.com.br/webservices/verificaAtualizacao.php";

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $url_verifica,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
));

$response = curl_exec($curl);

curl_close($curl);

echo json_encode($response);

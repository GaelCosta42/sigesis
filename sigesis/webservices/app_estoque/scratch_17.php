<?php

function sendLogToFirebase($logBody) {
    $lambdaUrl = "https://southamerica-east1-error-logger-5e474.cloudfunctions.net/main";
    $body = array_merge($logBody, ["firestoreCollection" => "logs"]);

    $curl = curl_init($lambdaUrl);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($body));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

echo(sendLogToFirebase(["key" => "Hello, World!"]));


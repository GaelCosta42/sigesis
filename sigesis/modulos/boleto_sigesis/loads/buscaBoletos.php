<?php

ini_set("display_errors", true);
define("_VALID_PHP", true);
require_once("../../../init.php");

$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);

$user = (int) $_SESSION["uid"];
$cpf_cnpj = null;

if ($user > 0) {
    $sql = "SELECT      e.cnpj
            FROM        empresa AS e
            INNER JOIN  usuario AS u ON u.id_empresa = e.id
            WHERE       u.id=?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $user);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $cpf_cnpj = $row['cnpj'];
        }
    }

    if (isset($cpf_cnpj)) {

        $url_verifica = "https://controle.sigesis.com.br/webservices/busca_boletos.php";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url_verifica,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query(['cpf_cnpj' => $cpf_cnpj]),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        echo $response;
    } else {
        echo json_encode(
            array(
                'status' => false,
                'msg' => 'Dados do cliente não foram enviados.',
                'boleto' => null
            )
        );
    }
} else {
    echo json_encode(
        array(
            'status' => false,
            'msg' => 'Sessão expirou, reconecte-se no sistema.',
            'boleto' => null
        )
    );
}


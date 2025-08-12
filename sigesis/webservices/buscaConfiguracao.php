<?php

ini_set("display_errors", true);
define("_VALID_PHP", true);
require_once("../init.php");

$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);

$config = $_POST['config'];
$id_usuario = session('uid');

if ($id_usuario > 0) {

    $qry = "SELECT e.$config
            FROM        empresa AS e
            INNER JOIN  usuario AS u ON e.id = u.id_empresa
            WHERE u.id = $id_usuario
            LIMIT 1";

    $stmt = $mysqli->prepare($qry);

    if ($stmt->execute()) {

        $result = $stmt->get_result();
        $valida = 1;

        while ($row = $result->fetch_assoc()) {
            $valida = (int)$row[$config];
        }

        echo json_encode($valida);
    }
} else {
    echo json_encode(
        array(
            'status' => false,
            'msg' => 'Formulário inválido.',
            'valida' => null
        )
    );
}

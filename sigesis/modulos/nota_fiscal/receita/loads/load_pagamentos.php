<?php

ini_set("display_errors", false);
define("_VALID_PHP", true);
require_once("../../../../init.php");

$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);

$id_nota = $_POST['id_nota'];

if ($id_nota > 0) {

    $qry = "SELECT  nf.valor_nota, 
                    SUM(r.valor) AS receita_valor

            FROM        nota_fiscal AS  nf
            LEFT JOIN   receita     AS  r   ON  r.id_nota = nf.id 
                                            AND r.inativo = 0

            WHERE   nf.id = $id_nota";

    $stmt = $mysqli->prepare($qry);

    if ($stmt->execute()) {

        $result = $stmt->get_result();
        $pagamentos = [];

        while ($row = $result->fetch_assoc()) {
            $pagamentos[] = array(
                'valorTotal' => $row['valor_nota'],
                'valorPago' => $row['receita_valor'],
            );
        }

        echo json_encode(
            array(
                'status' => true,
                'msg' => 'Dados carregados com sucesso!',
                'dados' => $pagamentos
            )
        );
    } else {
        echo json_encode(
            array(
                'status' => false,
                'msg' => 'Falha na execução de validação da nota fiscal.',
                'dados' => null
            )
        );
    }
} else {
    echo json_encode(
        array(
            'status' => false,
            'msg' => 'Formulário inválido.',
            'dados' => null
        )
    );
}

<?php

ini_set("display_errors", true);
define("_VALID_PHP", true);
require_once("../init.php");

$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);

$id = $_POST['id'] ?? null;
$acao = $_POST['acao'];
$cfop_entrada = null;

switch ($acao) {
    case 'novo':

        $cfop = $_POST['cfop'] ?? null;
        $cfop_saida = $_POST['cfop'] ?? null;
        $csosn = $_POST['csosn'] ?? null;

        $qry = "SELECT      cc.cfop_entrada, cc.cfop_saida
                FROM        nota_fiscal_itens   AS nf
                INNER JOIN  conversao_cfop      AS cc ON cc.cfop_fornecedor = nf.cfop
                WHERE       nf.id = ?";

        $stmt = $mysqli->prepare($qry);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {

            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $cfop_entrada = (int) $row['cfop_entrada'] ?? $cfop;
                $cfop_saida = (int) $row['cfop_saida'] ?? $cfop;
            }
        }

        $qry = "SELECT      cc.csosn
                FROM        nota_fiscal_itens   AS nf
                INNER JOIN  conversao_csosn     AS cc ON cc.csosn_cst = nf.icms_cst
                WHERE       nf.id = ?";

        $stmt = $mysqli->prepare($qry);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {

            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $csosn = (int) $row['csosn'];
            }
        }

        echo json_encode(array('cfop_entrada' => $cfop_entrada, 'cfop_saida' => $cfop_saida, 'csosn_cst' => $csosn));
        break;
    case 'combinar':

        $qry = "SELECT      cc.cfop_entrada
                FROM        nota_fiscal_itens   AS nf
                INNER JOIN  conversao_cfop      AS cc ON cc.cfop_fornecedor = nf.cfop
                WHERE       nf.id = ?";

        $stmt = $mysqli->prepare($qry);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {

            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $cfop_entrada = (int) $row['cfop_entrada'];
            }
        }

        echo json_encode(array('cfop_entrada' => $cfop_entrada));
        break;
    default:
        echo json_encode(array('error' => 'Formulário inválido'));
        break;
}
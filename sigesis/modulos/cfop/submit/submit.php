<?php

define("_VALID_PHP", true);
ini_set('display_errors', true);
require_once("../../../init.php");

$user = (int) $_SESSION["uid"];

// VERIFICA SE EXISTE UMA SESSAO DO USUARIO ATIVA =================================================================
if ($user > 0) {

    // CONEXAO COM O BANCO DE DADOS ===============================================================================
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);

    $acao = $_POST['acao'];
    $fluxo = $_POST['fluxo'];

    // VALIDACAO DE PREENCHIMENTO DOS CAMPOS DOS FORMULARIOS ======================================================
    if ($acao === 'cadastrar' || $acao === 'atualizar') {
        switch ($fluxo) {
            case 'cfop': // ------------------------------------------------------------------------------------
                $cfop_fornecedor = (int) $_POST['cfop_fornecedor'] ?? null;
                $cfop_entrada = (int) $_POST['cfop_entrada'] ?? null;
                $cfop_saida = (int) $_POST['cfop_saida'] ?? null;

                if (empty($cfop_fornecedor)) {
                    echo json_encode(array('status' => false, 'msg' => "O campo CFOP Fornecedor é obrigatório."));
                    die();
                }

                if (empty($cfop_entrada)) {
                    echo json_encode(array('status' => false, 'msg' => "O campo CFOP Entrada é obrigatório."));
                    die();
                }

                if (empty($cfop_saida)) {
                    echo json_encode(array('status' => false, 'msg' => "O campo CFOP Saída é obrigatório."));
                    die();
                }

                if (!is_numeric($cfop_fornecedor) || !is_numeric($cfop_entrada) || !is_numeric($cfop_saida)) {
                    echo json_encode(array('status' => false, 'msg' => "Valores não numéricos."));
                    die();
                }

                break;
            case 'csosn': // ---------------------------------------------------------------------------------
                $csosn_cst = (int) $_POST['csosn_cst'] ?? null;
                $csosn = (int) $_POST['csosn'] ?? null;

                if ($csosn_cst === null || $csosn_cst === '') {
                    echo json_encode(array('status' => false, 'msg' => "O campo CSOSN/CST é obrigatório."));
                    die();
                }

                if ($csosn === null || $csosn === '') {
                    echo json_encode(array('status' => false, 'msg' => "O campo CSOSN é obrigatório."));
                    die();
                }

                if (!is_numeric($csosn_cst) || !is_numeric($csosn)) {
                    echo json_encode(array('status' => false, 'msg' => "Valores não numéricos."));
                    die();
                }

                break;
            default:
                die();
        }
    }

    // EXCUCAO DA ACAO (CADASTRAR, ATUALIZAR OU INATIVAR) COM BASE NO FLUXO (CFOP OU CSOSN) =====================
    switch ($acao) {
        case 'cadastrar': // ------------------------------------------------------------------------------------
            if ($fluxo === 'cfop') {

                $observacao = $_POST['observacao'] ?? '';

                // VERIFICA SE O REGISTRO JÁ FOI CADASTRADO =====================================================
                $checkSql = "SELECT COUNT(*) as total FROM conversao_cfop WHERE cfop_fornecedor = ? AND inativo = 0";

                $stmt = $mysqli->prepare($checkSql);
                $stmt->bind_param("i", $cfop_fornecedor);

                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();

                    if ($row['total'] > 0) {
                        echo json_encode(array('status' => false, 'msg' => 'Já existe um registro ativo com este CFOP Fornecedor.'));
                        die();
                    }
                }

                $sql = "INSERT INTO conversao_cfop (cfop_fornecedor, cfop_entrada, cfop_saida, observacao)
                        VALUES (?, ?, ?, ?)";

                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("iiis", $cfop_fornecedor, $cfop_entrada, $cfop_saida, $observacao);

                if ($stmt->execute()) {
                    echo json_encode(array('status' => true, 'msg' => 'Registro inserido com sucesso.'));
                } else {
                    echo json_encode(array('status' => false, 'msg' => 'Registro não foi inserido.'));
                }
            } else if ($fluxo === 'csosn') {

                $observacao = $_POST['observacao'] ?? '';

                // VERIFICA SE O REGISTRO JÁ FOI CADASTRADO ====================================================
                $checkSql = "SELECT COUNT(*) as total FROM conversao_csosn WHERE csosn_cst = ? AND inativo = 0";

                $stmt = $mysqli->prepare($checkSql);
                $stmt->bind_param("i", $csosn_cst);

                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();

                    if ($row['total'] > 0) {
                        echo json_encode(array('status' => false, 'msg' => 'Já existe um registro ativo com este CSOSN/CST.'));
                        die();
                    }
                }

                $sql = "INSERT INTO conversao_csosn (csosn_cst, csosn, observacao)
                        VALUES (?, ?, ?)";

                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("iis", $csosn_cst, $csosn, $observacao);

                if ($stmt->execute()) {
                    echo json_encode(array('status' => true, 'msg' => 'Registro inserido com sucesso.'));
                } else {
                    echo json_encode(array('status' => false, 'msg' => 'Registro não foi inserido.'));
                }
            }

            break;
        case 'atualizar': // ------------------------------------------------------------------------------------
            if ($fluxo === 'cfop') {

                $id = (int) $_POST['id'];
                $observacao = $_POST['observacao'] ?? '';

                $sql = "UPDATE conversao_cfop SET cfop_fornecedor = ?, cfop_entrada = ?, cfop_saida = ?, observacao = ? WHERE id = ?";

                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("iiisi", $cfop_fornecedor, $cfop_entrada, $cfop_saida, $observacao, $id);

                if ($stmt->execute()) {
                    echo json_encode(array('status' => true, 'msg' => 'Registro atualizado com sucesso.'));
                } else {
                    echo json_encode(array('status' => false, 'msg' => 'Registro não foi atualizado.'));
                }
            } else if ($fluxo === 'csosn') {

                $id = (int) $_POST['id'];
                $observacao = $_POST['observacao'] ?? '';

                $sql = "UPDATE conversao_csosn SET csosn_cst = ?, csosn = ?, observacao = ? WHERE id = ?";

                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("iisi", $csosn_cst, $csosn, $observacao, $id);

                if ($stmt->execute()) {
                    echo json_encode(array('status' => true, 'msg' => 'Registro atualizado com sucesso.'));
                } else {
                    echo json_encode(array('status' => false, 'msg' => 'Registro não foi atualizado.'));
                }
            }

            break;
        case 'inativar': // -------------------------------------------------------------------------------------
            $id = $_POST['id'];
            $table = "conversao_$fluxo";

            $sql = "UPDATE $table SET inativo = 1 WHERE id = ?";

            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo json_encode(array('status' => true, 'msg' => 'Registro inativado com sucesso.'));
            } else {
                echo json_encode(array('status' => false, 'msg' => 'Registro não foi inativado.'));
            }
            break;
        default: // ---------------------------------------------------------------------------------------------
            echo json_encode(array('status' => false, 'msg' => 'Formulário inválido.'));
            break;
    }
} else {
    echo json_encode(array('status' => false, 'msg' => "Sessão expirou! Reconecte-se ao sistema."));
}
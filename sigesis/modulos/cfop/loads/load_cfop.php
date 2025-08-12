<?php
ini_set("display_errors", 1);
define("_VALID_PHP", true);
require_once("../../../init.php");

// CONEXAO COM O BANCO DE DADOS
$db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);

$id = $_POST['id'] ?? null;

$pesquisa = $_POST['pesquisa'] ?? '';
$pagina = $_POST['pagina'] ?? 1;
$itensPorPagina = 10;
$offset = ($pagina - 1) * $itensPorPagina;
$totalRegistros = 0;

$condicoes = [];
$palavras = explode(' ', $pesquisa);

$condicoes = '';
if (!empty($pesquisa)) {
    $condicoes .= " AND (observacao LIKE '%$pesquisa%' OR id = '$pesquisa' OR cfop_entrada = '$pesquisa' OR cfop_saida = '$pesquisa' OR cfop_fornecedor = '$pesquisa')";
}

// EM CASO DE FLUXO DE EDICAO, PESQUISA APENAS ATRAVES DO ID DO REGISTRO
if ($id > 0) {
    $condicoes = " AND id = $id";
}

if ($pagina == 1 && empty($id)) {
    $countQuery = " SELECT  COUNT(id) AS 'qtd'
		            FROM conversao_cfop
		            WHERE inativo = 0
                    $condicoes";

    $countStmt = $db->prepare($countQuery);
    $countStmt->execute();

    $countResult = $countStmt->get_result();
    $countData = $countResult->fetch_assoc();
    $totalRegistros = $countData['qtd'];
}

$qry = "SELECT id, cfop_entrada, cfop_saida, cfop_fornecedor, observacao
        FROM conversao_cfop
		WHERE inativo = 0
        $condicoes
        ORDER BY id ASC
        LIMIT $itensPorPagina OFFSET $offset";

//var_dump($qry);
//die();

$stmt = $db->prepare($qry);
if ($stmt->execute()) {

    $resultado = $stmt->get_result();
    $linhas = [];
    $dados = [];

    while ($row = $resultado->fetch_assoc()) {

        $id = $row['id'];
        $cfop_entrada = $row['cfop_entrada'];
        $cfop_saida = $row['cfop_saida'];
        $cfop_fornecedor = $row['cfop_fornecedor'];
        $observacao = $row['observacao'];

        // OBJETO QUE SERÁ USADO PARA O CARREGAMENTO DE DADOS DOS CAMPOS DO FORMULARIO CASO O REGISTRO ESTEJA SENDO EDITADO
        $dados[] = array(
            'cfop_entrada' => $cfop_entrada,
            'cfop_saida' => $cfop_saida,
            'cfop_fornecedor' => $cfop_fornecedor,
            'observacao' => $observacao
        );

        // ATRIBUICAO DOS ELEMENTOS NAS LINHAS DA TABELA            
        $linhas[] = array(
            $cfop_fornecedor,
            $cfop_entrada,
            $cfop_saida,
            $observacao,
            "<button type='button' data-fluxo='cfop' data-codigo='$id' class='btn blue atualizar'><i class='fa fa-pencil' aria-hidden='true'></i></button>
            <button type='button' data-fluxo='cfop' data-acao='inativar' data-codigo='$id' class='btn red inativar'>X</button>"
        );
    }

    $numeroPaginas = ($pagina == 1) ? ceil($totalRegistros / $itensPorPagina) : false;

    echo json_encode([
        'msg' => 'Dados carregados com sucesso.',
        'status' => 'success',
        'dados' => $linhas,
        'numpaginas' => $numeroPaginas,
        'linhasPorPagina' => $itensPorPagina,
        'pagina' => $pagina,
        'query' => $qry,
        'retorno' => $dados
    ]);
} else {
    echo json_encode(['msg' => 'Erro ao consultar os dados.', 'status' => 'error']);
}

$stmt->close();
$db->close();
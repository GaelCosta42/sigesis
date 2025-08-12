<?php
ini_set("display_errors", 1);
define("_VALID_PHP", true);
require_once("../../../init.php");

$db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);

function formatarData($data)
{
    return date("d/m/Y", strtotime($data));
}

function formatarDataMesAno($data)
{
    return date("m/Y", strtotime($data));
}

function formatarDataAno($data)
{
    return date("Y", strtotime($data));
}

$pesquisa = $_POST['pesquisa'] ?? '';
$pagina = $_POST['pagina'] ?? 1;
$itensPorPagina = 10;
$offset = ($pagina - 1) * $itensPorPagina;
$totalRegistros = 0;

$condicoes = [];
$palavras = explode(' ', $pesquisa);

$condicoes = '';
if (!empty($pesquisa)) {
    $condicoes .= " AND (v.numero_nota LIKE '%$pesquisa%' OR v.id = '$pesquisa')";
}

if ($pagina == 1) {
    $countQuery = " SELECT  COUNT(v.id) AS 'qtd'

		            FROM vendas AS v

		            WHERE v.inativo = 0
                    AND v.status_enotas = 'Negada'
                    $condicoes";

    $countStmt = $db->prepare($countQuery);
    $countStmt->execute();

    $countResult = $countStmt->get_result();
    $countData = $countResult->fetch_assoc();
    $totalRegistros = $countData['qtd'];
}

$qry = "SELECT  v.id,
                v.numero_nota,
	            v.data_venda,
	            v.status_enotas,
	            v.motivo_status

        FROM vendas AS v

		WHERE   v.inativo = 0
        AND     v.status_enotas = 'Negada'
        $condicoes
        ORDER BY v.id ASC
        LIMIT $itensPorPagina OFFSET $offset";

//var_dump($qry);
//die();

$stmt = $db->prepare($qry);
if ($stmt->execute()) {
    $resultado = $stmt->get_result();
    $dados = [];
    $data = '';
    while ($row = $resultado->fetch_assoc()) {
        $data = formatarData($row['data_venda']);

        // ATRIBUICAO DOS ELEMENTOS NAS LINHAS DA TABELA
        $dados[] = array(
            $row['id'],
            formatarData($row['data_venda']),
            $row['numero_nota'],
            'NFC-e',
            $row['status_enotas'],
            $row['motivo_status'],
            "<button type='button' class='btn red' onclick=\"window.open('index.php?do=vendas_do_dia', '_blank')\">Corrigir Nota</button>");
    }

    $numeroPaginas = ($pagina == 1) ? ceil($totalRegistros / $itensPorPagina) : false;

    echo json_encode([
        'msg' => 'Dados carregados com sucesso.',
        'status' => 'success',
        'dados' => $dados,
        'numpaginas' => $numeroPaginas,
        'linhasPorPagina' => $itensPorPagina,
        'pagina' => $pagina,
        'query' => $qry,
        'retorno' => $totalRegistros
    ]);
} else {
    echo json_encode(['msg' => 'Erro ao consultar os dados.', 'status' => 'error']);
}

$stmt->close();
$db->close();
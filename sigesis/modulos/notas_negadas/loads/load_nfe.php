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
    $condicoes .= " AND (nf.numero_nota LIKE '%$pesquisa%')";
}

if ($pagina == 1) {

    $countQuery = " SELECT  COUNT(nf.id) AS 'qtd'

		            FROM nota_fiscal AS nf

		            WHERE nf.inativo = 0
                    AND nf.status_enotas = 'Negada'
                    $condicoes";

    $countStmt = $db->prepare($countQuery);
    $countStmt->execute();

    $countResult = $countStmt->get_result();
    $countData = $countResult->fetch_assoc();
    $totalRegistros = $countData['qtd'];
}

$qry = "SELECT  nf.numero_nota,
                nf.id_empresa,
                nf.data_emissao,
                nf.data,
                nf.modelo,
                nf.operacao,
                nf.fiscal,
                nf.status_enotas,
                nf.motivo_status

		FROM nota_fiscal AS nf

		WHERE nf.inativo = 0
        AND nf.status_enotas = 'Negada'
        $condicoes
        ORDER BY nf.id ASC
        LIMIT $itensPorPagina OFFSET $offset";

//var_dump($qry);
//die();

$stmt = $db->prepare($qry);
if ($stmt->execute()) {
    $resultado = $stmt->get_result();
    $dados = [];
    $data = '';
    while ($row = $resultado->fetch_assoc()) {

        $id_empresa = $row['id_empresa'];
        $modelo = $row['modelo'];
        $operacao = $row['operacao'];
        $mes_ano = formatarDataMesAno($row['data_emissao']);
        $numero_nota = $row['numero_nota'];
        $ano = formatarDataAno($row['data_emissao']);

        $filtro = '';

        if (isset($id_empresa)) {
            $filtro .= "&id_empresa=$id_empresa";
        }
        if (isset($modelo)) {
            $filtro .= "&modelo=$modelo";
        }
        if (isset($operacao)) {
            $filtro .= "&operacao=$operacao";
        }
        if (isset($mes_ano)) {
            $filtro .= "&mes_ano=$mes_ano";
        }
        if (isset($numero_nota)) {
            $filtro .= "&numero_nota=$numero_nota";
        }
        if (isset($ano)) {
            $filtro .= "&ano=$ano";
        }

        // ATRIBUICAO DOS ELEMENTOS NAS LINHAS DA TABELA            
        $dados[] = array(
            $row['numero_nota'],
            formatarData($row['data']),
            'NF-e',
            $row['status_enotas'],
            $row['motivo_status'],
            "<button type='button' class='btn red' onclick=\"window.open('index.php?do=notafiscal&acao=notafiscal$filtro', '_blank')\">Corrigir Nota</button>"
        );
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
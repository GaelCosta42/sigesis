<?php ini_set("display_errors", 1);

define("_VALID_PHP", true);

require_once("../../../init.php");

$pesquisa = $_POST['pesquisa'] ?? '';
isset($_POST['pagina']) && $_POST['pagina'] != 'NaN' ? $pagina = $_POST['pagina'] : $pagina = 1;
$itensPorPagina = 10;
$offset = ($pagina - 1) * $itensPorPagina;

$condicoes = [];
$palavras = explode(' ', $pesquisa);

foreach ($palavras as $key => $value) {
    if (!empty($value)) {
        $temp_string = "(";
        if (is_numeric($value)) {
            $temp_string .= " p.id = $value or p.valor_custo like '%$value%' ";
        } else {
            $temp_string .= " p.nome LIKE '%$value%' OR  p.descricao_unidade ";
        }
        $temp_string .= ")";
        $condicoes[$key] = $temp_string;
    }
}

$condicoes = !empty($pesquisa) ? " AND " . implode(" AND ", $condicoes) : "";

$ordenacao = " ORDER BY p.nome ";

if ($pagina == 1) {
    $countQuery = "SELECT 
    COUNT(p.id) as 'qtd'
    FROM
	produto p
    WHERE
	p.inativo = 0 AND p.ecommerce = 1
	$condicoes";

    $result = $db->fetch_all($countQuery);

    if ($result[0]->qtd > 0) {
        $totalRegistros = $result[0]->qtd;
    }
}

$qry = "SELECT DISTINCT p.id, p.nome, p.descricao_unidade, p.valida_estoque, p.estoque, p.valor_custo, p.ecommerce, p.ecommerce_id 
    FROM produto p 
    WHERE p.inativo = 0 AND p.ecommerce = 1
    $condicoes $ordenacao
    LIMIT $itensPorPagina OFFSET $offset";

$resultado = $db->fetch_all($qry);

if (count($resultado) > 0) {

    foreach ($resultado as $key => $value) {
        $dados[] = array(
            $value->id,
            $value->nome,
            $value->descricao_unidade,
            ($value->valida_estoque == 1 ? "Sim" : "Não"),
            $value->estoque,
            moeda($value->valor_custo),
            !($value->ecommerce_id > 0) ? "<button type='button' data-id='{$value->id}' class='btn adicionar'>Adicionar ao e-commerce</button>" : "<button type='button' data-id='{$value->id}' class='btn btn-danger remover'>Remover do e-commerce</button>",
            !($value->ecommerce_id > 0) ? "" : "<button type='button' data-id='{$value->id}' class='btn btn-primary atualizar'>Atualizar produto</button>",
        );
    }

    $numeroPaginas = ($pagina == 1) ? ceil($totalRegistros / $itensPorPagina) : false;

    echo json_encode([
        'msg' => 'Dados carregados com sucesso.',
        'status' => 'success',
        'dados' => $dados,
        'numpaginas' => $numeroPaginas ?? 0,
        'pagina' => $pagina ?? 0,
        'linhasPorPagina' => $itensPorPagina
    ]);
} else {
    echo json_encode(['msg' => 'Nenhum dado foi encontrado.', 'status' => 'success', 'dados' => null]);
}

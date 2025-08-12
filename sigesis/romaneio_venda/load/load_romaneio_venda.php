<?php

ini_set("display_errors", 0);
define("_VALID_PHP", true);
require_once("../../init.php");

$pesquisa       = $_POST['pesquisa'] ?? '';
$pagina         = $_POST['pagina'] ?? 1;
$dtini          = $_POST['dtini'];
$dtfim          = $_POST['dtfim'];
$tpvenda        = $_POST['tipo-venda'];
$itensPorPagina = 10;
$offset         = ($pagina - 1) * $itensPorPagina;

$condicoes  = [];
$palavras   = explode(' ', $pesquisa);

foreach ($palavras as $key => $value) {

    if (!empty($value)) {

        $temp_string = "(";

        if (is_numeric($value)) {
            $temp_string .= "v.id = $value or v.valor_total like '%$value%' ";
        } else {
            $temp_string .= "cad.nome LIKE '%$value%' OR  DATE_FORMAT(v.data_venda,'%d/%m/%Y') LIKE '%$value%'";
        }

        $temp_string .= ")";
        $condicoes[$key] = $temp_string;
    }
}

if (isset($tpvenda) && $tpvenda != 0) {
    $status = " AND v.pago = $tpvenda";
} else {
    $status = "";
}

$condicoes = !empty($pesquisa) ? " AND " . implode(" AND ", $condicoes) : "";

$ordenacao = " ORDER BY v.data_venda DESC ";

if ($pagina == 1) {

    $countQuery = " SELECT COUNT(v.id) AS 'qtd'

                    FROM        vendas      v
	                LEFT JOIN   cadastro    cad ON cad.id = v.id_cadastro

	                WHERE   v.orcamento <>  1 
                    AND     v.inativo   =   0 
                    AND     v.data_venda BETWEEN '$dtini 00:00:00' AND '$dtfim 23:59:59' 
                    $condicoes 
                    $status ";

    $result = $db->fetch_all($countQuery);

    if ($result[0]->qtd > 0) {
        $totalRegistros = $result[0]->qtd;
    }
}

$qry = "SELECT DISTINCT v.id, 
                        v.data_venda, 
                        v.valor_total,
                        v.valor_desconto, 
                        v.valor_despesa_acessoria, 
                        v.valor_pago, 
                        v.usuario, 
                        v.id_cadastro, 
                        v.entrega,
                        cad.cliente,
                        cad.nome,
                        v.pago,    
                        v.orcamento,
                        v.inativo  

        FROM        vendas      v
	    LEFT JOIN   cadastro    cad ON cad.id = v.id_cadastro

	    WHERE   v.orcamento <>  1 
        AND     v.inativo   =   0
        AND     v.data_venda BETWEEN '$dtini 00:00:00' AND '$dtfim 23:59:59' 
        $status
        $condicoes 
        $ordenacao
        
        LIMIT $itensPorPagina OFFSET $offset";

$resultado = $db->fetch_all($qry);

if (count($resultado) > 0) {
    foreach ($resultado as $key => $value) {

        $ids[] = $value->id;
        $data = new DateTime($value->data_venda);
        $link_cliente = "<a href='index.php?do=cadastro&acao=historico&id=$value->id_cadastro'>$value->nome</a>";

        $dados[] = array(
            "<input type='checkbox' class='checkbox-single' id='check-$value->id' value='$value->id' data-total='$totalRegistros'></input>",
            $value->id,
            $data->format('d/m/Y'),
            $value->pago != 1 ? '<span style="background-color: #417169; color: #fff; padding: 5px; display: flex; justify-content: center; border-radius: 3px;">Aberta</span>' : '<span style="background-color: #44b6ae; color: #fff; padding: 5px; display: flex; justify-content: center; border-radius: 3px;">Finalizada</span>',
            $link_cliente,
            moedap($value->valor_total),
            "<button data-id='$value->id' id='gerar-individual' title='Gere o romaneio dessa venda individualmente' class='btn btn-sm yellow-gold gerar-romaneio'><i class='fa fa-truck'></i></button>",
        );
    }

    $numeroPaginas = ($pagina == 1) ? ceil($totalRegistros / $itensPorPagina) : false;

    echo json_encode([
        'msg' => 'Dados carregados com sucesso.',
        'status' => 'success',
        'dados' => $dados,
        'numpaginas' => $numeroPaginas,
        'pagina' => $pagina
    ]);
} else {
    echo json_encode(['msg' => 'Erro ao consultar os dados.', 'status' => 'error']);
}

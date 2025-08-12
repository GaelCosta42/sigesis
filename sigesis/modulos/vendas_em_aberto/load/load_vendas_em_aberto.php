<?php ini_set("display_errors", 0);

define("_VALID_PHP", true);

require_once("../../../init.php");

$pesquisa = $_POST['pesquisa'] ?? '';
$pagina = $_POST['pagina'] ?? 1;
$itensPorPagina = 10;
$offset = ($pagina - 1) * $itensPorPagina;

$condicoes = [];
$palavras = explode(' ', $pesquisa);

foreach ($palavras as $key => $value) {
    if (!empty($value)) {
        $temp_string = "(";
        if (is_numeric($value)) {
            $temp_string .= "v.id = $value or v.valor_total like '%$value%' ";
        } else {
            $temp_string .= "v.usuario LIKE '%$value%' OR  DATE_FORMAT(v.data_venda,'%d/%m/%Y') like '%$value%' OR cad.nome LIKE '%$value%'";
        }
        $temp_string .= ")";
        $condicoes[$key] = $temp_string;
    }
}

$condicoes = !empty($pesquisa) ? " AND " . implode(" AND ", $condicoes) : "";

$ordenacao = " ORDER BY v.data_venda DESC ";

if ($pagina == 1) {

    $countQuery = " SELECT  COUNT(DISTINCT v.id) AS 'qtd'
                    FROM        vendas      AS v
	                LEFT JOIN   cadastro    AS cad  ON  cad.id = v.id_cadastro
	                WHERE v.pago = 2
                    AND v.inativo = 0
                    AND v.orcamento <> 1
                    AND v.venda_agrupamento = 0 
                    $condicoes";

    $result = $db->fetch_all($countQuery);

    if ($result[0]->qtd > 0) {
        $totalRegistros = $result[0]->qtd;
    }
}

$qry = "SELECT DISTINCT v.id,
                        v.data_venda,
                        cad.cliente,
                        SUM(v.valor_total) as valor_total,
                        SUM(v.valor_desconto) as valor_desconto,
                        SUM(v.valor_despesa_acessoria) as valor_despesa_acessoria,
                        SUM(v.valor_pago) as valor_pago, v.usuario, v.id_cadastro, cad.nome,
                        v.entrega,
                        tp.tipo

        FROM        vendas              AS v
	    LEFT JOIN   cadastro            AS cad  ON  cad.id = v.id_cadastro
        LEFT JOIN   (
                        SELECT
                            id_venda,
                            tipo,
                            SUM(valor_pago) AS valor_total_financeiro
                        FROM
                            cadastro_financeiro
                        WHERE
                            inativo = 0
                        GROUP BY
                            id_venda
                    ) AS cf ON cf.id_venda = v.id
	    LEFT JOIN   tipo_pagamento      AS tp   ON  tp.id = cf.tipo

	    WHERE   v.pago = 2
        AND     v.inativo = 0
        AND     v.orcamento <> 1 
        AND v.venda_agrupamento = 0 
        $condicoes

        GROUP BY v.id
        $ordenacao
        LIMIT $itensPorPagina OFFSET $offset";

$resultado = $db->fetch_all($qry);

if (count($resultado) > 0) {
    foreach ($resultado as $key => $value) {

        $ids[] = $value->id;

        $cliente = $value->cliente > 0
            ?
            "<a data-id='$value->id' href='javascript:void(0);' title='Imprimir Venda em aberto A4' class='btn mr-2 btn-sm grey-cascade imprimir_vendaa4'>
                        <i class='fa fa-file-text-o'></i>
                    </a>
                    <a href='javascript:void(0);' data-id='$value->id' title='Ver romaneio' class='btn btn-sm yellow-gold imprimir_romaneio'>
                        <i class='fa fa-truck'></i>
                    </a>"
            :
            "<a href='index.php?do=vendas&amp;acao=adicionarclientevenda&amp;id=$value->id&amp;pg=2' class='btn btn-sm blue popovers' data-container='body' data-trigger='hover' data-placement='top' data-content='Para emissão de NFC-e com valor igual ou superior a R$ 10.000,00 é obrigatório a identificação do cliente.' data-original-title='Vincular cliente a esta venda: $value->id' title='Vincular cliente a esta venda: $value->id'>
                        <i class='fa fa-user'></i>
                    </a>";

        $apagarVenda = ($usuario->is_Administrativo()) ? "<a href='javascript:void(0);' class='btn btn-sm red apagar' data-id='$value->id' acao='processarCancelarVendaAberto' title='Você deseja cancelar esta venda? Código: $value->id'><i class='fa fa-ban'></i></a>" : "";

        $dados[] = array(
            $value->id,
            exibedata($value->data_venda),
            $link_cliente = "<a href='index.php?do=cadastro&acao=historico&id=" . $value->id_cadastro . "'>$value->nome</a>",
            moedap($value->valor_total),
            moedap($value->valor_desconto),
            moedap($value->valor_despesa_acessoria),
            moedap($value->valor_pago),
            $value->tipo ?? '',
            $value->usuario,
            "<div>
                <button data-id='$value->id' title='Imprimir Venda em aberto' class='btn btn-sm grey imprimir_venda'><i class='fa fa-file-o'></i></button>
                <a href='index.php?do=vendas&amp;acao=finalizarvenda&amp;id=$value->id' class='btn btn-sm sigesis-cor-1' title='Ir para: $value->id'><i class='fa fa-share'></i></a>
                $apagarVenda
                $cliente
            </div>"
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
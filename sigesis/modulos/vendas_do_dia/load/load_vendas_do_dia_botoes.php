<?php 
ini_set("display_errors", 0);

define("_VALID_PHP", true);

require_once("../../../init.php");

$pesquisa = $_POST['pesquisa'] ?? '';

$condicoes = [];
$palavras = explode(' ', $pesquisa);
!empty($_POST['data']) ? $data = '"' . $_POST['data'] . '"' : $data = ' CURRENT_DATE() ';

foreach ($palavras as $key => $value) {
    if (!empty($value)) {
        $temp_string = "(";
        if (is_numeric($value)) {
            $temp_string .= "v.id = $value or v.valor_total like '%$value%' or v.numero_nota like '%$value%' ";
        } else {
            $temp_string .= "IFNULL(tp.tipo,'FICHA(crediário)') LIKE '%$value%' OR  DATE_FORMAT(v.data_venda,'%d/%m/%Y') like '%$value%'
            OR v.motivo_status LIKE '%$value%' OR u.nome LIKE '%$value%' OR c.nome LIKE '%$value%' OR v.status_enotas LIKE '%$value%' 
            OR FORMAT(v.valor_total,2,'de_DE') like '%$value%' ";
        }

        $temp_string .= ")";
        $condicoes[$key] = $temp_string;
    }
}

$condicoes = !empty($pesquisa) ? " AND " . implode(" AND ", $condicoes) : "";

$ordenacao = " ORDER BY v.id DESC ";

$qry = "SELECT  v.id, 
                v.fiscal, 
                v.entrega, 
                v.inativo, 
                v.link_danfe, 
                v.id_cadastro, 
                v.numero_nota,
                v.contingencia, 
                v.data_emissao, 
                v.status_enotas, 
                v.motivo_status, 
                v.id_nota_fiscal, 
                c.nome AS cadastro, 
                u.nome AS vendedor, 
                v.troco AS troco, 
                0 AS voucher_crediario, 
                v.valor_pago AS valor_pago, 
                v.valor_troca AS valor_troca, 
                v.valor_total AS valor_total,
                v.valor_desconto AS valor_desconto, 
                IFNULL(tp.tipo,'FICHA(crediário)') AS pagamento
                
        FROM        vendas              AS v
	    LEFT JOIN   cadastro            AS c    ON c.id = v.id_cadastro
	    LEFT JOIN   usuario             AS u    ON u.id = v.id_vendedor
        LEFT JOIN   cadastro_financeiro AS cf   ON cf.id_venda = v.id
	    LEFT JOIN   tipo_pagamento      AS tp   ON tp.id = cf.tipo  

        WHERE   ( v.pago = 1 OR v.fiscal = 2 ) 
	    AND     DATE(v.data_venda) = $data 
        $condicoes 

        GROUP BY v.id $ordenacao";

$resultado = $db->fetch_all($qry);

$dados[] = array('Cód Venda', 'Cliente', 'Desconto', 'Valor total', 'Tipo de pagamento', 'Vendedor', 'Cancelada', 'Status NFC-e', 'Número nota', 'Motivo');

if (count($resultado) > 0) {
    foreach ($resultado as $key => $value) {
        $ids[] = $value->id;
        $cadastro = $value->cadastro ?? '--';
        $classe = ($value->inativo) ? "class='font-red'" : "";
        $estilo_status = ($value->status_enotas == "Autorizada") ? ((!$value->contingencia) ? "badge bg-green" : "badge bg-blue-chambray") : (($value->status_enotas == "Negada") ? "badge bg-red" : (($value->status_enotas == "Inutilizada" || $value->status_enotas == "Cancelada") ? "badge bg-blue-hoki" : "badge bg-yellow"));

        $dados[] = array(
            "<div class='$classe'>$value->id</div>",

            "<a class='$classe' href='index.php?do=cadastro&acao=historico&id=$value->id'>$cadastro</a>",

            ($value->valor_troca > 0) ? "<div class='$classe'>" . moeda($value->valor_desconto - $value->valor_troca + $value->voucher_crediario) . ' +(' . lang('VALOR_TROCA') . MOEDA($value->valor_troca) . ') (' . lang('SALDO_CREDIARIO') . ': ' . moeda($value->voucher_crediario) . ')' . "</div>" : "<div class='$classe'>" . moeda($value->valor_desconto) . "</div>",

            "<span class='bold theme-font valor_total $classe'>" . moedap($value->valor_pago - $value->troco) . "</span></div>",

            "<div class='$classe'>$value->pagamento</div>",

            "<div class='$classe'>$value->vendedor</div>",

            $value->inativo == 1 ? "<div class='$class'>SIM</div>" : "<div class='$class'>NÃO</div>",

            ((!empty($value->status_enotas) && $value->status_enotas != "") || $value->fiscal == 0) ? "<div class='$estilo_status'>" . ($value->status_enotas == "Autorizada" ? (!$value->contingencia ?  $value->status_enotas : lang('NOTA_FISCAL_CONSUMIDOR_CONTIGENCIA')) : ($value->status_enotas == "Negada" ||  $value->status_enotas == "Inutilizada" || $value->status_enotas == "Cancelada" ? $value->status_enotas : lang('NOTA_FISCAL_CONSUMIDOR_PENDENTE'))) . "</div>" : "",

            "<div class='$classe'>" . ($value->numero_nota != '' ? $value->numero_nota : '--') . "</div>",

            "<div class='$classe'>" . ($value->motivo_status != '' ? $value->motivo_status: '--') . "</div>",
            
            !($value->inativo) ?
            "<div style='display: flex; flex-direction: row; flex-wrap: wrap; align-content: space-between; justify-content: space-evenly;'>
            <a data-id='$value->id' title='Ver detalhes' class='btn btn-sm grey-cascade btn-fiscal'><i class='fa fa-search'></i></a>
            <a data-id='$value->id' window.open('recibo.php?id=44183&amp;crediario=0','Imprimir recibo44183','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;' title='Imprimir recibo' class='btn btn-sm yellow-casablanca btn-fiscal'><i class='fa fa-file-o'></i></a>
            <a data-id='$value->id' href='index.php?do=vendas&amp;acao=adicionarclientevenda&amp;id=44183' class='btn btn-sm blue popovers btn-fiscal' data-container='body' data-trigger='hover' data-placement='top' data-content='Para emissão de NFC-e com valor igual ou superior a R$ 10.000,00 é obrigatório a identificação do cliente.' data-original-title='Vincular cliente a esta venda: 44183' title='Vincular cliente a esta venda: 44183'><i class='fa fa-user'></i></a>
            <a data-id='$value->id' onclick='javascript:void window.open('pdf_romaneio.php?id=44183','CODIGO: 44183','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;' title='Ver romaneio' class='btn btn-sm yellow-gold'><i class='fa fa-truck btn-fiscal'></i></a>
            <a data-id='$value->id' href='index.php?do=vendas&amp;acao=cancelarvenda&amp;id=44183&amp;pg=1' class='btn btn-sm red btn-fiscal' title='Você deseja cancelar esta venda? Código: : 44183'><i class='fa fa-ban'></i></a>
            <a data-id='$value->id' href='javascript:void(0);' class='btn btn-sm grey-cascade gerarNFEvendaBloqueio btn-fiscal' title='Só é possível gerar NF-e de vendas com Cliente identificado. Venda: 44183'><i class='fa fa-files-o'></i></a>
            <a data-id='$value->id' href='javascript:void(0);' onclick='location.reload();' title='Recarregar página' class='btn btn-sm btn-reload blue-madison ocultar'><i class='fa fa-refresh'></i></a>
			</div>"
            : ""

        );

        $desconto += ($value->valor_troca > 0) ? ($value->valor_desconto - $value->valor_troca + $value->voucher_crediario) : $value->valor_desconto;
        ($value->inativo) ? null : $valorTotal += $value->valor_total;
    }

    $numeroPaginas = ($pagina == 1) ? ceil($totalRegistros / $itensPorPagina) : false;

    $dados_aux = array('desconto' => $desconto, 'valorTotal' => ($valorTotal - $desconto));

    echo json_encode([
        'msg' => 'Dados carregados com sucesso.',
        'status' => 'success',
        'dados' => $dados,
        'dados_aux' => $dados_aux
    ]);
} else {
    echo json_encode(['msg' => 'Nenhum dado foi encontrado.', 'status' => 'success', 'dados' => null]);
}

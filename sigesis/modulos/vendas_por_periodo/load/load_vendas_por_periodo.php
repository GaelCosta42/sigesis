<?php ini_set("display_errors", 0);

define("_VALID_PHP", true);

require_once("../../../init.php");

$pesquisa = $_POST['pesquisa'] ?? '';
isset($_POST['pagina']) && $_POST['pagina'] != 'NaN' ? $pagina = $_POST['pagina'] : $pagina = 1;

if ($_POST['action'] == 'refresh') {
    $pagina = 1;
}

$itensPorPagina = 10;
$offset = ($pagina - 1) * $itensPorPagina;

$condicoes = [];
$palavras = explode(' ', $pesquisa);
!empty($_POST['dataini']) && !empty($_POST['datafim']) ? $data = ' AND DATE(v.data_venda) BETWEEN "' . $_POST['dataini'] . '"AND "' . $_POST['datafim'] . '"' : $data = ' AND DATE(v.data_venda) = CURDATE() ';

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
$valorTotal = null;

if ($pagina == 1) {
    $countQuery = "SELECT
    COUNT(DISTINCT v.id) as 'qtd', SUM(cf.valor_pago) as valor_total
    FROM
	vendas AS v
	LEFT JOIN cadastro AS c ON c.id = v.id_cadastro
	LEFT JOIN usuario AS u ON u.id = v.id_vendedor
    LEFT JOIN cadastro_financeiro cf ON cf.id_venda = v.id
	LEFT JOIN tipo_pagamento tp ON tp.id = cf.tipo
    LEFT JOIN empresa e ON e.id = cf.id_empresa
    WHERE
	( v.pago = 1 OR v.fiscal = 2 )  AND cf.inativo = 0 AND v.venda_agrupamento=0
	$data $condicoes";

    $result = $db->fetch_all($countQuery);

    if ($result[0]->qtd > 0) {
        $totalRegistros = $result[0]->qtd;
        $valorTotal = $result[0]->valor_total;
    }
}

$qry = "SELECT DISTINCT
	v.id, v.valor_pago-v.troco as valor_total, v.id_cadastro, v.status_enotas, v.numero_nota, v.motivo_status, v.contingencia,
    SUM(DISTINCT v.valor_pago) as valor_pago, SUM(v.valor_troca) as valor_troca, SUM(v.valor_desconto) as valor_desconto,
    SUM(v.voucher_crediario) as voucher_crediario, SUM(v.troco) as troco, v.fiscal, v.id_nota_fiscal, v.link_danfe, v.data_emissao, v.entrega, v.inativo,
    c.nome AS cadastro, u.nome AS vendedor, IFNULL(tp.tipo,'FICHA(crediário)') as pagamento, tp.id_categoria, cf.id_empresa,
    e.modulo_emissao_boleto, e.boleto_banco as 'banco_boleto', nf.motivo_status AS motivo_status_nf, nf.status_enotas AS status_enotas_nf, nf.contingencia AS contingencia_nf,
    nf.inativo AS inativo_nf, nf.fiscal AS fiscal_nf, nf.id AS id_nf, nf.id_venda, nf.numero_nota AS numero_nota_nf
	FROM
    vendas AS v
    LEFT JOIN nota_fiscal AS nf	ON v.id = nf.id_venda
	LEFT JOIN cadastro AS c ON c.id = v.id_cadastro
	LEFT JOIN usuario AS u ON u.id = v.id_vendedor
    LEFT JOIN cadastro_financeiro cf ON cf.id_venda = v.id
	LEFT JOIN tipo_pagamento tp ON tp.id = cf.tipo
    LEFT JOIN empresa e ON e.id = cf.id_empresa
    WHERE
	( v.pago = 1 OR v.fiscal = 2 )  AND cf.inativo < 5 AND v.venda_agrupamento=0
	$data
    $condicoes GROUP BY v.id $ordenacao
    LIMIT $itensPorPagina OFFSET $offset";

$resultado = $db->fetch_all($qry);
$estilo_status = "";
$status = "";
if (count($resultado) > 0) {
    foreach ($resultado as $key => $value) {
        $ids[] = $value->id;
        $cadastro = $value->cadastro ?? '--';
        $classe = ($value->inativo) ? "font-red" : "";
        $estilo_status = ($value->status_enotas == "Autorizada") ? ((!$value->contingencia) ? "badge bg-green" : "badge bg-blue-chambray") : (($value->status_enotas == "Negada") ? "badge bg-red" : (($value->status_enotas == "Inutilizada" || $value->status_enotas == "Cancelada") ? "badge bg-blue-hoki" : "badge bg-yellow"));
        $cor_fiscal = ($value->fiscal && $value->status_enotas == "Autorizada") ? 'green' : 'purple';
        $categoria_pagamento = ($value) ? $value->id_categoria : 0;

        $opcoes = '<div style="display: flex; flex-direction: row; flex-wrap: wrap; align-content: space-between; justify-content: space-evenly;">';

        if (!$value->inativo) {
            $opcoes .= "<a href=\"javascript:void(0);\" onclick=\"javascript:void window.open('imprimir_vendas.php?id={$value->id}','CODIGO: {$value->id}','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;\" title=\"" . lang('VER_DETALHES') . "\" class=\"btn btn-sm grey-cascade btn-fiscal\"><i class=\"fa fa-search\"></i></a>";

            if (!$value->fiscal || $value->status_enotas == "Inutilizada") {
                $opcoes .= "<a href=\"javascript:void(0);\" onclick=\"javascript:void window.open('recibo.php?id={$value->id}&crediario={$pgto_crediario}','" . lang('IMPRIMIR_RECIBO') . "{$value->id}','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;\" title=\"" . lang('IMPRIMIR_RECIBO') . "\" class=\"btn btn-sm yellow-casablanca btn-fiscal\"><i class=\"fa fa-file-o\"></i></a>";
            }

            if (($usuario->is_nfc() && $core->tipo_sistema != 2) && (!$value->id_nota_fiscal) && $value->status_enotas != "Inutilizada") {
                if ($value->status_enotas == "Autorizada" && !$value->contingencia) {
                    $opcoes .= "<a href=\"{$value->link_danfe}\" title=\"" . lang('NOTA_FISCAL_DANFE') . "\" class=\"btn btn-sm green\"><i class=\"fa fa-file-text-o\"></i></a>";
                    $dentroPrazoCancelamento = !(strtotime($value->data_emissao . ' +30 minutes') < strtotime(date('Y-m-d H:i:s')));
                    if ($dentroPrazoCancelamento) {
                        $opcoes .= "<a href=\"index.php?do=vendas&acao=cancelarvendafiscal&id={$value->id}\" class=\"btn btn-sm red btn-fiscal\" title=\"" . lang('CADASTRO_APAGAR_VENDA_FISCAL') . ": {$value->id}\"><i class=\"fa fa-minus-circle\"></i></a>";
                    }
                } else {
                    if (!$value->cadastro) {
                        $opcoes .= "<a href=\"index.php?do=vendas&acao=adicionarclientevenda&id={$value->id}\" class=\"btn btn-sm blue popovers btn-fiscal\" data-container=\"body\" data-trigger=\"hover\" data-placement=\"top\" data-content=\"" . lang('CADASTRO_CLIENTE_VENDA_TEXTO') . "\" data-original-title=\"" . lang('CADASTRO_CLIENTE_VENDA') . ": {$value->id}\" title=\"" . lang('CADASTRO_CLIENTE_VENDA') . ": {$value->id}\"><i class=\"fa fa-user\"></i></a>";
                    }
                    if ($value->cadastro || $value->valor_total < 10000.00) {
                        if (!$value->contingencia && $value->status_enotas != "Negada") {
                            $opcoes .= "<a href=\"javascript:void(0);\" onclick=\"$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?id={$value->id}','" . lang('FISCAL_NFC') . "{$value->id}','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;\" title=\"" . lang('FISCAL_NFC') . "\" class=\"btn btn-sm btn-fiscal {$cor_fiscal}\"><i class=\"fa fa-file-text-o\"></i></a>";
                            if ($usuario->is_Controller()) {
                                $opcoes .= "<a href=\"javascript:void(0);\" onclick=\"$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?debug=1&id={$value->id}','" . lang('FISCAL_NFC') . "{$value->id}','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;\" title=\"" . lang('FISCAL_NFC') . " [DEBUG]\" class=\"btn btn-sm btn-fiscal {$cor_fiscal}\"><i class=\"fa fa-bug\"></i></a>";
                            }
                        } else {
                            if ($value->status_enotas == "Negada") {
                                $opcoes .= "<a href=\"javascript:void(0);\" onclick=\"$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?negada=1&id={$value->id}','" . lang('FISCAL_NFC_REPROCESSAR') . "{$value->id}','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;\" title=\"" . lang('FISCAL_NFC_REPROCESSAR') . "\" class=\"btn btn-sm red btn-fiscal\"><i class=\"fa fa-file-text-o\"></i></a>";
                                if ($usuario->is_Controller()) {
                                    $opcoes .= "<a href=\"javascript:void(0);\" onclick=\"$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?negada=1&debug=1&id={$value->id}','" . lang('FISCAL_NFC_REPROCESSAR') . "{$value->id}','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;\" title=\"" . lang('FISCAL_NFC_REPROCESSAR') . " [DEBUG]\" class=\"btn btn-sm red btn-fiscal\"><i class=\"fa fa-bug\"></i></a>";
                                }
                            } else {
                                $opcoes .= "<a href=\"javascript:void(0);\" onclick=\"$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?contingencia=1&id={$value->id}','" . lang('FISCAL_NFC_REPROCESSAR') . "{$value->id}','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;\" title=\"" . lang('FISCAL_NFC_REPROCESSAR') . "\" class=\"btn btn-sm blue-chambray btn-fiscal\"><i class=\"fa fa-file-text-o\"></i></a>";
                                if ($usuario->is_Controller()) {
                                    $opcoes .= "<a href=\"javascript:void(0);\" onclick=\"$('.btn-fiscal').hide(); $('.btn-reload').show(); javascript:void window.open('nfc.php?contingencia=1&debug=1&id={$value->id}','" . lang('FISCAL_NFC_REPROCESSAR') . "{$value->id}','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;\" title=\"" . lang('FISCAL_NFC_REPROCESSAR') . " [DEBUG]\" class=\"btn btn-sm blue-chambray btn-fiscal\"><i class=\"fa fa-bug\"></i></a>";
                                }
                            }
                        }
                    }
                }
            } elseif (!$value->cadastro) {
                $opcoes .= "<a href=\"index.php?do=vendas&acao=adicionarclientevenda&id={$value->id}\" class=\"btn btn-sm blue popovers btn-fiscal\" data-container=\"body\" data-trigger=\"hover\" data-placement=\"top\" data-content=\"" . lang('CADASTRO_CLIENTE_VENDA_TEXTO') . "\" data-original-title=\"" . lang('CADASTRO_CLIENTE_VENDA') . ": {$value->id}\" title=\"" . lang('CADASTRO_CLIENTE_VENDA') . ": {$value->id}\"><i class=\"fa fa-user\"></i></a>";
            }

            if ($value->entrega) {
                $opcoes .= "<a href=\"javascript:void(0);\" onclick=\"javascript:void window.open('pdf_romaneio.php?id={$value->id}','CODIGO: {$value->id}','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;\" title=\"" . lang('VER_ROMANEIO') . "\" class=\"btn btn-sm yellow-gold\"><i class=\"fa fa-truck btn-fiscal\"></i></a>";
            }

            if ($value->modulo_emissao_boleto == 1 && $categoria_pagamento == 4) {
                $opcoes .= "<a href=\"boleto_{$value->banco_boleto}.php?todos=1&id_pagamento={$value->id}&id_empresa={$value->id_empresa}\" target=\"_blank\" title=\"Gerar todos os boletos\" class=\"btn btn-sm grey-cascade btn-fiscal\"><i class=\"fa fa-bold\"></i></a>";
            }

            if (!$value->fiscal && $usuario->is_Gerencia() && !$value->id_nota_fiscal || $value->status_enotas == "Inutilizada") {
                $opcoes .= "<a href=\"index.php?do=vendas&acao=cancelarvenda&id={$value->id}&pg=1\" class=\"btn btn-sm red btn-fiscal\" title=\"" . lang('CADASTRO_APAGAR_VENDA') . ": {$value->id}\"><i class=\"fa fa-ban\"></i></a>";
            }

            if ($value->id_nota_fiscal) {
                if (isset($value->status_enotas_nf)) {
                    if ($value->status_enotas_nf === "Negada") {
                        $cor_status = 'red';
                    } else {
                        $cor_status = 'green';
                    }
                } else {
                    $cor_status = 'purple';
                }
                $opcoes .= "<a href=\"index.php?do=notafiscal&acao=visualizar&id={$value->id_nota_fiscal}\" class=\"btn btn-sm $cor_status\" title=\"NF-e\">NF-e</a>";
            }

            if (!$value->fiscal && !$value->id_nota_fiscal && $core->tipo_sistema != 2 && $core->tipo_sistema != 3) {
                if ($value->cadastro) {
                    $opcoes .= "<a data-id=\"{$value->id}\" href=\"javascript:void(0);\" class=\"btn btn-sm blue gerarNFEvenda btn-fiscal\" id=\"{$value->id}\" title=\"" . lang('NOTA_FISCAL_CONVERTER') . ": {$value->id}\"><i class=\"fa fa-files-o\"></i></a>";
                } else {
                    $opcoes .= "<a data-id=\"{$value->id}\" href=\"javascript:void(0);\" class=\"btn btn-sm grey-cascade gerarNFEvendaBloqueio btn-fiscal\" title=\"" . lang('NOTA_FISCAL_CONVERTER_NAO') . ": {$value->id}\"><i class=\"fa fa-files-o\"></i></a>";
                }
            }

            if ($pagamentoCrediario && $pagamentoCrediario > 0) {
                $opcoes .= "<a href=\"javascript:void(0);\" onclick=\"javascript:void window.open('recibo_promissorias.php?id_venda={$value->id}&id_receita=0','" . lang('IMPRIMIR_RECIBO_PROMISSORIAS') . ": {$value->id}','width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;\" title=\"" . lang('IMPRIMIR_RECIBO_PROMISSORIAS') . "\" class=\"btn btn-sm yellow btn-fiscal\"><i class=\"fa fa-list-alt\"></i></a>";
            }
        } elseif ($value->link_danfe) {
            $opcoes .= "<a href=\"{$value->link_danfe}\" title=\"" . lang('NOTA_FISCAL_DANFE') . "\" class=\"btn btn-sm green\"><i class=\"fa fa-file-pdf-o\"></i></a>";
        } else {
            $opcoes .="<div class='badge bg-red'>Venda cancelada</div>";
        }

        $opcoes .= "<a href=\"javascript:void(0);\" onClick=\"location.reload();\" title=\"" . lang('RECARREGAR') . "\" class=\"btn btn-sm btn-reload blue-madison ocultar\"><i class=\"fa fa-refresh\"></i></a>";

        $opcoes .= '</div>';

        if ($value->id_venda > 0) {
            if (((!empty($value->status_enotas_nf) && $value->status_enotas_nf != "") || $value->fiscal_nf == 0)) {
                $estilo_status = ($value->status_enotas_nf == "Autorizada") ? ((!$value->contingencia_nf) ? "badge bg-green" : "badge bg-blue-chambray") : (($value->status_enotas_nf == "Negada") ? "badge bg-red" : (($value->status_enotas_nf == "Inutilizada" || $value->status_enotas_nf == "Cancelada") ? "badge bg-blue-hoki" : "badge bg-yellow"));
                $status = "<div class='$estilo_status'>".(($value->status_enotas_nf == "Autorizada") ? ((!$value->contingencia_nf) ? $value->status_enotas_nf : lang('NOTA_FISCAL_CONSUMIDOR_CONTIGENCIA')) : (($value->status_enotas_nf == "Negada" || $value->status_enotas_nf == "Inutilizada" || $value->status_enotas_nf == "Cancelada") ? $value->status_enotas_nf : (lang('NOTA_FISCAL_CONSUMIDOR_PENDENTE'))))."</div>";
            }
        } else if (((!empty($value->status_enotas) && $value->status_enotas != "") || $value->fiscal == 0)){
            $status = "<div class='$estilo_status'>".(($value->status_enotas == "Autorizada") ? ((!$value->contingencia) ? $value->status_enotas : lang('NOTA_FISCAL_CONSUMIDOR_CONTIGENCIA')) : (($value->status_enotas == "Negada" || $value->status_enotas == "Inutilizada" || $value->status_enotas == "Cancelada") ? $value->status_enotas : (lang('NOTA_FISCAL_CONSUMIDOR_PENDENTE'))))."</div>";
        }

        $dados[] = array(
            "<div class='$classe'>$value->id</div>",
            "<a class='$classe' href='index.php?do=cadastro&acao=historico&id=$value->id_cadastro'>$cadastro</a>",
            ($value->valor_troca > 0) ? "<div class='$classe'>" . moeda($value->valor_desconto - $value->valor_troca + $value->voucher_crediario) . ' +(' . lang('VALOR_TROCA') . MOEDA($value->valor_troca) . ') (' . lang('SALDO_CREDIARIO') . ': ' . moeda($value->voucher_crediario) . ')' . "</div>" : "<div class='$classe'>" . moeda($value->valor_desconto) . "</div>",
            "<span class='bold theme-font valor_total $classe'>" . moedap($value->valor_pago - $value->troco) . "</span>",
            "<div class='$classe'>$value->pagamento</div>",
            "<div class='$classe'>$value->vendedor</div>",
            $value->inativo == 1 ? "<div class='$classe'>SIM</div>" : "<div class='$classe'>NÃO</div>",
            $status,
            "<div class='$classe'>" . ($value->id_venda > 0 ? $value->numero_nota_nf : $value->numero_nota) . "</div>",
            "<div class='$classe'>" . ($value->motivo_status != '' ? $value->motivo_status : '--') . "</div>",
            $opcoes
        );
    }

    $numeroPaginas = ($pagina == 1) ? ceil($totalRegistros / $itensPorPagina) : false;

    echo json_encode([
        'msg' => 'Dados carregados com sucesso.',
        'status' => 'success',
        'dados' => $dados,
        'numpaginas' => $numeroPaginas ?? 0,
        'pagina' => $pagina ?? 0,
        'retorno' => $valorTotal > 0 ? moeda($valorTotal) : null
    ]);
} else {
    echo json_encode(['msg' => 'Nenhum dado foi encontrado.', 'status' => 'success', 'dados' => null]);
}
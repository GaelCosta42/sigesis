<?php
    define("_VALID_PHP", true);
    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/json');
    require('../../init.php');


$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$dataVenda = (isset($_POST['data_venda']) && !empty($_POST['data_venda'])) ? $_POST['data_venda'] : date("d/m/Y");

## Search 
$searchQuery = " ";
if($searchValue != ''){
   $searchQuery = " and (c.nome like '%".$searchValue."%' or 
        v.valor_total like '%".$searchValue."%' or 
        v.id like '%".$searchValue."%' ";        
}

## Total number of records without filtering
$sql = "SELECT COUNT(*) AS allcount
        FROM vendas as v
	LEFT JOIN cadastro as cad ON cad.id = v.id_cadastro
	WHERE (v.pago = 1 OR v.fiscal = 2) AND DATE_FORMAT(v.data_venda, '%d/%m/%Y') = '$dataVenda' ";        
$records = $db->first($sql);
$totalRecords = $records->allcount;

## Total number of record with filtering
$sql = "SELECT COUNT(*) AS allcount
        FROM vendas as v
        LEFT JOIN cadastro as c ON c.id = v.id_cadastro        
        WHERE (v.pago = 1 OR v.fiscal = 2) AND DATE_FORMAT(v.data_venda, '%d/%m/%Y') = '$dataVenda' 
        AND 1 $searchQuery ";
$records = $db->first($sql);
$totalRecordwithFilter = $records->allcount;

## Fetch records
$sql = "SELECT v.*, c.nome as cadastro, u.nome as vendedor
        FROM vendas as v
        LEFT JOIN cadastro as c ON c.id = v.id_cadastro
        LEFT JOIN usuario as u ON u.id = v.id_vendedor
        WHERE (v.pago = 1 OR v.fiscal = 2) AND DATE_FORMAT(v.data_venda, '%d/%m/%Y') = '$dataVenda'
        AND 1 $searchQuery
        ORDER BY $columnName $columnSortOrder LIMIT $row , $rowperpage";

$records = $db->fetch_all($sql);
$data = array();
$estilo_status = "";
foreach($records as $exrow) {
        $pgto_crediario = 0;
        $estilo_status = ($exrow->status_enotas=="Autorizada") ? ((!$exrow->contingencia) ? "badge bg-green" : "badge bg-blue-chambray") : (($exrow->status_enotas=="Negada") ? "badge bg-red" : (($exrow->status_enotas=="Inutilizada" || $exrow->status_enotas=="Cancelada") ? "badge bg-blue-hoki" : "badge bg-yellow"));
        $row_tipopagamento = $cadastro->getPagamentosVenda($exrow->id);
        $id_pagamento = ($row_tipopagamento) ? $row_tipopagamento[0]->tipo : 0;
        $categoria_pagamento = ($id_pagamento>0) ? getValue("id_categoria", "tipo_pagamento", "id = ".$id_pagamento) : 0;
        $cor_fiscal = ($exrow->fiscal && $exrow->status_enotas=="Autorizada") ? 'green' : 'purple';
        
        $link_cliente = "<a href='index.php?do=cadastro&acao=historico&id=".$exrow->id_cadastro."'>".$exrow->cadastro."</a>";
        $classeTR = ($exrow->inativo) ? "class='font-red'" : "";
        $valorDesconto = ($exrow->valor_troca > 0) ? moeda($exrow->valor_desconto-$exrow->valor_troca + $exrow->voucher_crediario).' +('.lang('VALOR_TROCA').MOEDA($exrow->valor_troca).') ('.lang('SALDO_CREDIARIO').': '.moeda($exrow->voucher_crediario).')' : moeda($exrow->valor_desconto);

        $statusNFC = "";
        $valorStatus = "";
        if ((!empty($exrow->status_enotas) && $exrow->status_enotas!="") || $exrow->fiscal==0):
                $statusNFC = ($exrow->status_enotas=="Autorizada") ? ((!$exrow->contingencia) ? $exrow->status_enotas : lang('NOTA_FISCAL_CONSUMIDOR_CONTIGENCIA')) : (($exrow->status_enotas=="Negada" || $exrow->status_enotas=="Inutilizada" || $exrow->status_enotas=="Cancelada") ? $exrow->status_enotas : (lang('NOTA_FISCAL_CONSUMIDOR_PENDENTE')));
                $valorStatus = '<div class="'.$estilo_status.'">'.$statusNFC.'</div>';
        endif;

        $tipoPagamento = "";
        $pagamentoCrediario = 0;
        if($row_tipopagamento):
                foreach ($row_tipopagamento as $prow):
                        $tipoPagamento .= pagamento($prow->pagamento).'<br/>';
                        $pgto_crediario = ($prow->pagamento==NULL) ? 1 : $pgto_crediario;
                        $pagamentoCrediario = ($prow->id_categoria==9) ? $pagamentoCrediario+1 : $pagamentoCrediario;
                endforeach;
        endif;       

        $data[] = array( 
                "id" => $exrow->id,
                "cliente" => $link_cliente,
                "desconto" => $valorDesconto,
                "valor_total" => moedap($exrow->valor_pago-$exrow->troco),
                "tipo_pagamento" => $tipoPagamento,
                "vendedor" => $exrow->vendedor,
                "cancelada" => ($exrow->inativo) ? "SIM" : "NAO",
                "status_nfc" => $valorStatus,
                "numero_nota" => $exrow->numero_nota,
                "motivo" => $exrow->motivo_status,
                "classTR" => $classeTR,
                "pgto_crediario" => $pgto_crediario,
                "row_pagamento" => $row_tipopagamento,
                "categoria_pagamento" => $categoria_pagamento,
                "cor_fiscal" => $cor_fiscal,
                "pagamentoCrediario" => $pagamentoCrediario,
                "row_venda" => $exrow
        );
}

## Response
$response = array(
  "draw" => intval($draw),
  "iTotalRecords" => $totalRecords,
  "iTotalDisplayRecords" => $totalRecordwithFilter,
  "aaData" => $data  
);

echo json_encode($response);

?>
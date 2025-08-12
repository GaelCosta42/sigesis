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

## Search 
$searchQuery = " ";
if($searchValue != ''){
   $searchQuery = " and (c.nome like '%".$searchValue."%' or 
        v.valor_total like '%".$searchValue."%' or 
        v.id like '%".$searchValue."%' or 
        DATE_FORMAT(v.data_venda,'%d/%m/%Y') like '%".$searchValue."%') ";        
}

//$id = (!empty($_POST['id'])) ? $_POST['id'] : '';

## Total number of records without filtering
$sql = "SELECT COUNT(*) AS allcount 
        FROM vendas as v
	LEFT JOIN cadastro as cad ON cad.id = v.id_cadastro
	WHERE v.pago = 2 
        AND v.orcamento <> 1 
        AND v.inativo = 0";
        
$records = $db->first($sql);

$totalRecords = $records->allcount;

## Total number of record with filtering
$sql = "SELECT COUNT(*) AS allcount 
        FROM vendas as v
        LEFT JOIN cadastro as c ON c.id = v.id_cadastro
        WHERE v.pago = 2 
        AND v.inativo = 0  
        AND v.orcamento <> 1 
        AND 1 $searchQuery ";
$records = $db->first($sql);
$totalRecordwithFilter = $records->allcount;

## Fetch records
$sql = "SELECT v.*, c.nome as cadastro
        FROM vendas as v
        LEFT JOIN cadastro as c ON c.id = v.id_cadastro
        WHERE v.pago = 2 
        AND v.inativo = 0 
        AND v.orcamento <> 1
        AND 1 $searchQuery
        ORDER BY $columnName $columnSortOrder LIMIT $row , $rowperpage";

$records = $db->fetch_all($sql);
$data = array();

foreach($records as $row) {
    $link_cliente = "<a href='index.php?do=cadastro&acao=historico&id=".$row->id_cadastro."'>".$row->cadastro."</a>";
    $sql_pagamento = "SELECT tp.tipo
                        FROM tipo_pagamento AS tp
                        LEFT JOIN cadastro_financeiro AS cf ON cf.tipo=tp.id
                        WHERE cf.id_venda=$row->id AND cf.inativo=0";
    $row_pagamentos = $db->fetch_all($sql_pagamento);
    $pagamentos_venda = '';
    if ($row_pagamentos) {
        foreach($row_pagamentos as $rpgto) {
                $pagamentos_venda .= $rpgto->tipo."<br>";
        }
    }
    $data[] = array( 
       "id" => $row->id,
       "data_venda" => exibedata($row->data_venda),
       "cliente" => $link_cliente,
       "valor_total" => moedap($row->valor_total),
       "valor_desconto" => moedap($row->valor_desconto),
       "valor_despesa_acessoria" => moedap($row->valor_despesa_acessoria),
       "valor_pago" => moedap($row->valor_pago),
       "usuario" => $row->usuario,
       "id_cadastro" => $row->id_cadastro,
       "entrega" => $row->entrega,
       "pagamentos" => $pagamentos_venda
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
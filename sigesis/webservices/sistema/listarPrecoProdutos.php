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
$searchValue = $_POST['search']['value']; // Search value

## Search 
$searchQuery = " ";
if($searchValue != ''){
   $searchQuery = " and (p.nome like '%".$searchValue."%' or 
        p.codigo like '%".$searchValue."%' or 
        p.codigobarras like '%".$searchValue."%' or 
        p.estoque like '%".$searchValue."%' or 
        p.valor_custo like'%".converteMoeda($searchValue)."%' or
        t.valor_venda like'%".converteMoeda($searchValue)."%' or
        t.percentual like'%".converteMoeda($searchValue)."%' ) ";
}

$id = (!empty($_POST['id'])) ? $_POST['id'] : '';

## Total number of records without filtering
$sql = "SELECT COUNT(*) AS allcount 
        FROM produto_tabela as t
	    LEFT JOIN produto AS p ON p.id = t.id_produto
        WHERE t.id_tabela = $id AND p.grade = 1 AND p.inativo = 0";
$records = $db->first($sql);

$totalRecords = $records->allcount;

## Total number of record with filtering
$sql = "SELECT COUNT(*) AS allcount 
        FROM produto_tabela as t
	    LEFT JOIN produto AS p ON p.id = t.id_produto
        WHERE t.id_tabela = $id AND p.grade = 1 AND p.inativo = 0 $searchQuery ";
$records = $db->first($sql);
$totalRecordwithFilter = $records->allcount;

## Fetch records
$sql = "SELECT t.*, p.nome, p.imagem, p.codigo, p.codigobarras, p.valor_custo, p.valor_avista, p.estoque, p.codigo_interno
        FROM produto_tabela as t
        LEFT JOIN produto AS p ON p.id = t.id_produto
        WHERE t.id_tabela = $id AND p.grade = 1 AND p.inativo = 0 $searchQuery 
        order by $columnName limit $row , $rowperpage";

$records = $db->fetch_all($sql);
$data = array();

foreach($records as $row) {
    $observacao = "";
    $queryObservacao = "SELECT atr.atributo,pra.descricao
                        FROM produto_atributo AS pra 
                        LEFT JOIN atributo AS atr ON atr.id=pra.id_atributo
                        WHERE pra.inativo=0 AND atr.inativo=0 AND pra.id_produto=$row->id_produto";
    $rowObservacao = $db->fetch_all($queryObservacao);
    if ($rowObservacao) {
        foreach($rowObservacao as $obs) {
            $observacao .= $obs->atributo.': '.$obs->descricao.'<br>';
        }
    }

    $data[] = array( 
       "id"=>$row->id,
       "nome"=>$row->nome,
       "codigo"=>$row->codigo,
       "codigobarras"=>$row->codigobarras,
       "codigointerno"=>$row->codigo_interno,
       "estoque"=>decimalp($row->estoque),
       "valor_venda"=>moedap($row->valor_venda),
       "valor_avista"=> ($row->valor_avista>0) ? moedap($row->valor_avista) : '---',
       "observacao"=> ($observacao!="") ? $observacao : '---'
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
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
   $searchQuery = " and (p.nome like '%".$searchValue."%' or 
        p.codigo like '%".$searchValue."%' or 
        p.codigo_interno like '%".$searchValue."%' or 
        p.codigobarras like '%".$searchValue."%' or 
        c.categoria like '%".$searchValue."%' or 
        g.grupo like '%".$searchValue."%' or
        p.estoque like '%".$searchValue."%' or 
        p.produto_balanca like '%".$searchValue."%' or 
        p.id like '%".$searchValue."%' or 
        p.valor_custo like'%".$searchValue."%' ) ";
}

## Total number of records without filtering
$sql = "SELECT COUNT(*) AS allcount FROM produto WHERE inativo = 0";
$records = $db->first($sql);
$totalRecords = $records->allcount;

$wgrupo = (!empty($_POST['id_grupo'])) ? " AND p.id_grupo = ".$_POST['id_grupo'] : "";
$wcategoria = (!empty($_POST['id_categoria'])) ? " AND p.id_categoria = ".$_POST['id_categoria'] : "";
$wfabricante = (!empty($_POST['id_fabricante'])) ? " AND p.id_fabricante = ".$_POST['id_fabricante'] : "";

## Total number of record with filtering
$sql = "SELECT COUNT(*) AS allcount 
        FROM produto AS p 
        LEFT JOIN categoria AS c ON c.id = p.id_categoria
        LEFT JOIN grupo AS g ON g.id = p.id_grupo
        LEFT JOIN fabricante AS fa ON fa.id = p.id_fabricante
        LEFT JOIN produto_fornecedor as pp on pp.id_produto = p.id and pp.principal = 1
        WHERE p.inativo = 0 AND 1".$searchQuery.$wgrupo.$wcategoria.$wfabricante;
$records = $db->first($sql);
$totalRecordwithFilter = $records->allcount;

## Fetch records
$sql = "SELECT p.*, c.categoria, g.grupo, fa.fabricante, pp.prazo " 
    . "\n FROM produto as p"
    . "\n LEFT JOIN categoria AS c ON c.id = p.id_categoria"
    . "\n LEFT JOIN grupo AS g ON g.id = p.id_grupo"
    . "\n LEFT JOIN fabricante AS fa ON fa.id = p.id_fabricante"
    . "\n LEFT JOIN produto_fornecedor as pp on pp.id_produto = p.id and pp.principal = 1"
    . "\n WHERE p.inativo = 0 $wgrupo $wcategoria $wfabricante "
    . "\n AND 1".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empQuery = "SELECT * from employee WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$records = $db->fetch_all($sql);
$data = array();

foreach($records as $row) {

	$sql_attr = "SELECT pa.descricao, at.atributo " 
	  . "\n FROM produto_atributo AS pa" 
	  . "\n LEFT JOIN atributo AS at on at.id = pa.id_atributo" 
	  . "\n WHERE pa.inativo = 0 AND at.inativo = 0 AND pa.id_produto = $row->id ";
	  $row_attr = $db->fetch_all($sql_attr);

      $valor_atributos = "";

	  if ($row_attr){
		  foreach($row_attr as $rAtributo){
			$valor_atributos .= $rAtributo->atributo.": <b>".$rAtributo->descricao."</b><br>";
		  }
	  }

    $data[] = array( 
       "id"=>$row->id,
       "nome"=>$row->nome,
       "codigo"=>$row->codigo,
       "ncm"=>$row->ncm,
       "cest"=>$row->cest,
       "grupo"=>$row->grupo,
       "categoria"=>$row->categoria,
       "atributos"=>$valor_atributos,
       "estoque"=>decimalp($row->estoque),
       "estoque_minimo"=>decimalp($row->estoque_minimo),
       "valor_custo"=>moedap($row->valor_custo),
       "grade"=>$row->grade,
       "id_categoria"=>$row->id_categoria,
       "codigobarras"=>$row->codigobarras,
       "codigo_interno"=>$row->codigo_interno,
       "produto_balanca"=>($row->produto_balanca == 1) ? "<span style='background-color: #20B551; color: #fff; padding: 5px'>SIM</span>" : "<span style='background-color: #f00; color: #fff; padding: 5px'>NÃO</span>"
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
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
        p.codigobarras like '%".$searchValue."%' or 
        c.categoria like '%".$searchValue."%' or 
        g.grupo like '%".$searchValue."%' or 
        p.estoque like '%".$searchValue."%' ) ";
}

$id = (!empty($_POST['id'])) ? $_POST['id'] : '';
$wgrupo = (!empty($_POST['id_grupo'])) ? " AND p.id_grupo = ".$_POST['id_grupo'] : "";
$wcategoria = (!empty($_POST['id_categoria'])) ? " AND p.id_categoria = ".$_POST['id_categoria'] : "";
$wfabricante = (!empty($_POST['id_fabricante'])) ? " AND p.id_fabricante = ".$_POST['id_fabricante'] : "";

## Total number of records without filtering
$sql = "SELECT COUNT(*) AS allcount 
        FROM produto_tabela as t
	    LEFT JOIN produto AS p ON p.id = t.id_produto
		LEFT JOIN categoria AS c ON c.id = p.id_categoria
		LEFT JOIN grupo AS g ON g.id = p.id_grupo
	    LEFT JOIN fabricante AS fa ON fa.id = p.id_fabricante
        WHERE t.id_tabela = $id AND p.grade = 1 AND p.inativo = 0";
$records = $db->first($sql);

$totalRecords = $records->allcount;

## Total number of record with filtering
$sql = "SELECT COUNT(*) AS allcount 
        FROM produto_tabela as t
	    LEFT JOIN produto AS p ON p.id = t.id_produto
		LEFT JOIN categoria AS c ON c.id = p.id_categoria
		LEFT JOIN grupo AS g ON g.id = p.id_grupo
	    LEFT JOIN fabricante AS fa ON fa.id = p.id_fabricante
        WHERE t.id_tabela = $id AND p.grade = 1 AND p.inativo = 0  
        AND 1 $searchQuery $wgrupo $wcategoria $wfabricante";
$records = $db->first($sql);
$totalRecordwithFilter = $records->allcount;

## Fetch records
 $sql = "SELECT t.*, p.nome, p.imagem, p.codigo, p.codigobarras, p.valor_custo, p.estoque, c.categoria, g.grupo, fa.fabricante
        FROM produto_tabela as t
        LEFT JOIN produto AS p ON p.id = t.id_produto
        LEFT JOIN categoria AS c ON c.id = p.id_categoria
        LEFT JOIN grupo AS g ON g.id = p.id_grupo
        LEFT JOIN fabricante AS fa ON fa.id = p.id_fabricante
        WHERE t.id_tabela = $id AND p.grade = 1 AND p.inativo = 0 $searchQuery $wgrupo $wcategoria $wfabricante
        ORDER BY $columnName $columnSortOrder limit $row , $rowperpage";

$records = $db->fetch_all($sql);
$data = array();

foreach($records as $row) {
    $data[] = array( 
       "id"=>$row->id,
       "nome"=>$row->nome,
       "codigo"=>$row->codigo,
       "codigobarras"=>$row->codigobarras,
       "grupo"=>$row->grupo,
       "categoria"=>$row->categoria,
       "estoque"=>decimalp($row->estoque),
	   "valor_custo"=>moedap($row->valor_custo),
       "percentual"=>percent($row->percentual),
       "valor_venda"=>moedap($row->valor_venda)
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
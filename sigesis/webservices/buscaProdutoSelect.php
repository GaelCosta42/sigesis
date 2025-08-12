<?php

ini_set("display_errors", true);
define("_VALID_PHP", true);
require_once("../init.php");

$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);

$search = isset($_GET['search']) ? $mysqli->real_escape_string($_GET['search']) : '';
$id_grupo = isset($_GET['id_grupo']) ? (int) $_GET['id_grupo'] : null;
$id_categoria = isset($_GET['id_categoria']) ? (int) $_GET['id_categoria'] : null;
$id_fabricante = isset($_GET['id_fabricante']) ? (int) $_GET['id_fabricante'] : null;

$wgrupo = $id_grupo ? "AND p.id_grupo = $id_grupo" : "";
$wcategoria = $id_categoria ? "AND p.id_categoria = $id_categoria" : "";
$wfabricante = $id_fabricante ? "AND p.id_fabricante = $id_fabricante" : "";
$wsearch = $search ? "AND (p.nome LIKE '%$search%' OR p.id = '$search' OR p.codigobarras = '$search')" : "";

$qry = "SELECT 	p.id, p.nome

		FROM 		produto 			AS p
		LEFT JOIN 	categoria 			AS c 	ON 	c.id = p.id_categoria
		LEFT JOIN 	grupo 				AS g 	ON 	g.id = p.id_grupo
		LEFT JOIN 	fabricante 			AS fa 	ON 	fa.id = p.id_fabricante
		LEFT JOIN 	produto_fornecedor 	AS pp 	ON 	pp.id_produto = p.id 
												AND pp.principal = 1

		WHERE 	p.inativo = 0 
		$wgrupo
		$wcategoria
		$wfabricante
        $wsearch

		ORDER BY p.nome";

// var_dump($qry);
// die();

$result = $mysqli->query($qry);

if ($result) {
    $produtos = [];
    while ($row = $result->fetch_assoc()) {
        $produtos[] = [
            'id' => $row['id'],
            'nome' => $row['nome'],
        ];
    }
    echo json_encode($produtos);
} else {
    echo json_encode([]);
}
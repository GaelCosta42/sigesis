<?php

/**
 * Buscar produto por Codigo de Barras
 *
 */
define("_VALID_PHP", true);
require('../../init.php');		

$retorno = 0;
$codigobarras = 0;

$json_string = file_get_contents('php://input');
$json_array = json_decode($json_string,true);
if ($json_string) {
	foreach($json_array as $nome_campo => $valor) {
		if($nome_campo == "codigobarras")
			$codigobarras = $valor;	
	}
}

//200 - OK
//400 - codigo inválido ou nulo
//404 - não encontrado

if ($codigobarras>0) {
	$sql = "SELECT p.id, p.nome, p.codigo, p.codigobarras, pt.valor_venda, p.inativo "
	  . "\n FROM produto AS p "
	  . "\n LEFT JOIN produto_tabela AS pt ON pt.id_produto=p.id "
	  . "\n where p.codigobarras = '$codigobarras' and p.inativo=0";

	$retorno_row = $db->first($sql);

	if ($retorno_row) {
		$json_retorno = array(
			"id" => $retorno_row->id,
			"nome" => $retorno_row->nome,
			"codigo" => $retorno_row->codigo,
			"codigobarras" => $retorno_row->codigobarras,
			"valor_venda" => $retorno_row->valor_venda,
			"status" => "Sucesso",
			"code" => 200
		);
		$retorno = json_encode($json_retorno);
	} else {
		$json_erro = array(
			"status" => "Nenhum produto com este código",
			"code" => 404
		);
		$retorno = json_encode($json_erro);
	}
} else {
	$json_erro = array(
		"status" => "Código de barras inválido ou nulo",
		"code" => 400
	);
	$retorno = json_encode($json_erro);
}

echo $retorno;

?>
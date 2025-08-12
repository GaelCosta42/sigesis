<?php

/**
 * Buscar produto por Codigo de Barras
 *
 */
define("_VALID_PHP", true);
require('../../init.php');		

$retorno = 0;
$senha = 0;

$json_string = file_get_contents('php://input');
$json_array = json_decode($json_string,true);
if ($json_string) {
	foreach($json_array as $nome_campo => $valor) {
		if($nome_campo == "senha")
			$senha = $valor;	
	}
}

//200 - OK
//400 - senha inválida ou nula
//404 - não encontrado

if ($senha>0) {
	/*
	$sql = "SELECT p.id, p.nome, p.codigo, p.codigobarras, pt.valor_venda, p.inativo "
	  . "\n FROM produto AS p "
	  . "\n LEFT JOIN produto_tabela AS pt ON pt.id_produto=p.id "
	  . "\n where p.codigobarras = '$codigobarras' and p.inativo=0";
	*/
	$retorno_row = true; /*$db->first($sql);*/

	if ($senha=='123456') {
		$json_retorno = array(
			"status" => "Sucesso",
			"code" => 200
		);
		$retorno = json_encode($json_retorno);
	} else {
		$json_erro = array(
			"status" => "Senha não autorizada",
			"code" => 404
		);
		$retorno = json_encode($json_erro);
	}
} else {
	$json_erro = array(
		"status" => "Senha inválida ou nula",
		"code" => 400
	);
	$retorno = json_encode($json_erro);
}

echo $retorno;

?>
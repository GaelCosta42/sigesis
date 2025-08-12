<?php

/**
 * Webservices: Config
 *
 */

set_time_limit(0);
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 6000);
ini_set('fastcgi_read_timeout', 6000);
ini_set('default_socket_timeout', 9000);
define('_VALID_PHP', true);
require('../init.php');

$bloqueioSistema = $empresa->verificarSituacaoCadastro(); //Obter via webservice informação de liberação de acesso ou não do controle.

if ($bloqueioSistema) {
	$json_erro = array(
		"code" => 400,
		"status" => "Prezado cliente, gentileza entrar em contato com o nosso setor Administrativo no telefone (31) 3829-1960"
	);
	$retorno =  json_encode($json_erro);
	echo $retorno;
} else {
	try {
		$status = "";

		$row_tabela = $produto->getTabelaPrecos();
		if ($row_tabela) {
			$existe_tabela = false;
			foreach ($row_tabela as $exrow) {
				if ($exrow->appvendas == 0) {
					$array_tabela[] = array(
						"id" => $exrow->id,
						"tabela" => $exrow->tabela,
						"quantidade" => $exrow->quantidade,
						"desconto" => $exrow->desconto,
						"nivel" => $exrow->nivel
					);
					$existe_tabela = true;
				}
			}
			if (!$existe_tabela) $array_tabela = [];
		} else {
			$array_tabela = [];
		}

	} catch (Exception $e) {
		$status = "Erro - EXCEÇÃO: " . $e->getMessage();
		$array_grupo = [];
		$array_categoria = [];
		$array_fabricante = [];
		$array_tabela = [];
	}

	$jsonRetorno = array(
		"tabela" => $array_tabela,
		"status" => $status,
	);
	$retorno =  json_encode($jsonRetorno);
	echo $retorno;
}

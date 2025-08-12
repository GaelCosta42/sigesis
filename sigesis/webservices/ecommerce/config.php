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
	require('../../init.php');
	
	if (!$core->modulo_integracao){
		$json_erro = array(
			"status" => 400,
			"retorno" => "OPCAO NAO DISPONIVEL. LIGUE PARA (31) 3829-1950"
		);
		$retorno =  json_encode($json_erro);
		echo $retorno;
	}
	else {
	
		try {
			
			$status = "";
			
			$row_pagamento = $faturamento->getTipoPagamento();
			if($row_pagamento) {		
				foreach ($row_pagamento as $exrow){
					$array_pagamento[] = array(
						"id" => $exrow->id,
						"tipo" => $exrow->tipo,
						"taxa" => $exrow->taxa,
						"dias" => $exrow->dias,
						"parcelas" => $exrow->parcelas
					);
				}
			} else {
				$array_pagamento = [];
			}
			$row_grupo = $grupo->getGrupos();
			if($row_grupo) {		
				foreach ($row_grupo as $exrow){
					$array_grupo[] = array(
						"id" => $exrow->id,
						"grupo" => $exrow->grupo
					);
				}
			} else {
				$array_grupo = [];
			}
			$row_categoria = $categoria->getCategorias();
			if($row_categoria) {		
				foreach ($row_categoria as $exrow){
					$array_categoria[] = array(
						"id" => $exrow->id,
						"categoria" => $exrow->categoria
					);
				}
			} else {
				$array_categoria = [];
			}
			$row_fabricante = $fabricante->getFabricantes();
			if($row_fabricante) {		
				foreach ($row_fabricante as $exrow){
					$array_fabricante[] = array(
						"id" => $exrow->id,
						"fabricante" => $exrow->fabricante
					);
				}
			} else {
				$array_fabricante = [];
			}
			$row_tabela = $produto->getTabelaPrecos();
			if($row_tabela) {		
				foreach ($row_tabela as $exrow){
					$array_tabela[] = array(
						"id" => $exrow->id,
						"tabela" => $exrow->tabela,
						"quantidade" => $exrow->quantidade,
						"desconto" => $exrow->desconto,
						"nivel" => $exrow->nivel
					);
				}
			} else {
				$array_tabela = [];
			}
		} catch (Exception $e) {
			$status = "Erro - EXCEÇÃO: ".$e->getMessage();
			$array_pagamento = [];
			$array_grupo = [];
			$array_categoria = [];
			$array_fabricante = [];
			$array_tabela = [];
		}

		$jsonRetorno = array(
			"pagamentos" => $array_pagamento,
			"grupos" => $array_grupo,
			"categorias" => $array_categoria,
			"fabricantes" => $array_fabricante,
			"tabelas" => $array_tabela,
		);
		$retorno =  json_encode($jsonRetorno);
		echo $retorno;
	}
?>
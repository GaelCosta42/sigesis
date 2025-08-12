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

$bloqueioSistema = $empresa->verificarSituacaoCadastro(); //Obter via webservice informação de liberação de acesso ou não do controle.

if ((!$core->app_vendas && $core->tipo_sistema != 4) || ($bloqueioSistema)) {
	$json_erro = array(
		"code" => 400,
		"status" => "OPCAO NAO DISPONIVEL. LIGUE PARA (31) 3829-1950"
	);
	$retorno =  json_encode($json_erro);
	echo $retorno;
} else {
	try {
		$status = "";

		$sql_empresa = "SELECT * FROM empresa WHERE inativo = 0";
		$row_empresa = $db->first($sql_empresa);
		$whatsapp = "";
		$telefone = "";
		$mostrar_vendas_dia_vendedor = "";

		if ($row_empresa) {
			$whatsapp = '55' . limparNumero($row_empresa->celular);
			$telefone = $row_empresa->telefone;
			$mostrar_vendas_dia_vendedor = intval($row_empresa->mostrar_vendas_dia_vendedor);
		}

		$row_cadastro = $cadastro->getCadastros('CLIENTE');
		if ($row_cadastro) {
			foreach ($row_cadastro as $exrow) {
				$array_cliente[] = array(
					"id" => $exrow->id,
					"nome" => capitalize($exrow->nome),
					"razao_social" => capitalize($exrow->razao_social),
					"cpf_cnpj" => $exrow->cpf_cnpj ? formatar_cpf_cnpj($exrow->cpf_cnpj) : "",
					"cep" => limparNumero($exrow->cep),
					"endereco" => $exrow->endereco,
					"numero" => $exrow->numero,
					"complemento" => $exrow->complemento,
					"bairro" => $exrow->bairro,
					"cidade" => $exrow->cidade,
					"estado" => $exrow->estado,
					"telefone" => $exrow->telefone,
					"celular" => $exrow->celular,
					"email" => $exrow->email,
					"crediario" => ($exrow->crediario > 0) ? 1 : 0
				);
			}
		} else {
			$array_cliente = [];
		}
		$row_pagamento = $faturamento->getTipoPagamento();
		if ($row_pagamento) {
			foreach ($row_pagamento as $exrow) {
				$array_pagamento[] = array(
					"id" => $exrow->id,
					"tipo" => $exrow->tipo,
					"primeiro_vencimento" => $exrow->primeiro_vencimento,
					"taxa" => $exrow->taxa,
					"dias" => $exrow->dias,
					"parcelas" => $exrow->parcelas,
					"desconto" => (float) $exrow->desconto
				);
			}
		} else {
			$array_pagamento = [];
		}

		$row_grupo = $grupo->getGrupos();
		if ($row_grupo) {
			foreach ($row_grupo as $exrow) {
				$array_grupo[] = array(
					"id" => $exrow->id,
					"grupo" => $exrow->grupo
				);
			}
		} else {
			$array_grupo = [];
		}

		$row_categoria = $categoria->getCategorias();
		if ($row_categoria) {
			foreach ($row_categoria as $exrow) {
				$array_categoria[] = array(
					"id" => $exrow->id,
					"categoria" => $exrow->categoria
				);
			}
		} else {
			$array_categoria = [];
		}

		$row_fabricante = $fabricante->getFabricantes();
		if ($row_fabricante) {
			foreach ($row_fabricante as $exrow) {
				$array_fabricante[] = array(
					"id" => $exrow->id,
					"fabricante" => $exrow->fabricante
				);
			}
		} else {
			$array_fabricante = [];
		}

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

		//Array de configuração do APP
		$row_config_app = $empresa->getConfigsAppEmpresa();
		if ($row_config_app) {
			foreach ($row_config_app as $approw) {
				$config_app = array(
					"cor_destaque" 					=> $approw->cor_destaque,
					"tema_escuro" 					=> intval($approw->tema_escuro),
					"imagem_logo"					=> $approw->imagem_logo,
					"imagem_popup" 					=> $approw->imagem_popup,
					"mostrar_vendas_dia_vendedor" 	=> $mostrar_vendas_dia_vendedor
				);
			}
		} else {
			$config_app = "";
		}

		$row_taxas = $produto->getTaxas();
		if ($row_taxas) {
			foreach ($row_taxas as $taxa_result) {
				$array_taxas[] = array(
					"id"        => (int) $taxa_result->id,
					"bairro"    => (string) cleanSanitize($taxa_result->bairro),
					"cidade"    => (string) cleanSanitize($taxa_result->cidade),
					"valor"     => (float) $taxa_result->valor_taxa,
					"tempo"     => (string) $taxa_result->tempo_aproximado
				);
			}
		} else {
			$array_taxas = [];
		}
	} catch (Exception $e) {
		$status = "Erro - EXCEÇÃO: " . $e->getMessage();
		$array_cliente = [];
		$array_pagamento = [];
		$array_grupo = [];
		$array_categoria = [];
		$array_fabricante = [];
		$array_tabela = [];
		$array_taxas = [];
		$config_app = "";
	}

	$jsonRetorno = array(
		"cadastro" => $array_cliente,
		"pagamento" => $array_pagamento,
		"grupo" => $array_grupo,
		"categoria" => $array_categoria,
		"fabricante" => $array_fabricante,
		"tabela" => $array_tabela,
		"taxas" => $array_taxas,
		"cadastro_app" => intval($row_empresa->cadastro_app),
		"crediario" => intval($row_empresa->crediario_app), //se for 1 permite salvar venda no crediário pelo app
		"desconto" => intval($row_empresa->desconto_app), //Se for 1 permite conceder desconto pelo app de vendas
		"ordenar_valor" => intval($row_empresa->ordernar_valor_app), //se for 1 lista os produtos ordenados por valor (asc) no app
		"whatsapp" => $whatsapp,
		"telefone" => $telefone,
		"status" => $status,
		"aplicativo" => (object) $config_app
	);
	$retorno =  json_encode($jsonRetorno);
	echo $retorno;
}

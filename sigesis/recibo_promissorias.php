<?php
  /**
   * Boleto Sicoob Layout
   *
   */
	define("_VALID_PHP", true);
  
	require("init.php");
	if (!$usuario->is_Todos())
	  redirect_to("login.php");
	
$id_venda = get('id_venda');
$id_receita = get('id_receita');
$row_promissorias = 0;

if ($id_venda && $id_venda>0) { //imprime todas as promissorias da venda
	$row_promissorias = $cadastro->getPromissoriasVenda($id_venda);
} elseif ($id_receita && $id_receita>0) { //imprime uma promissoria especifica de uma receita
	$row_promissorias = $cadastro->getPromissoriaReceita($id_receita);
}

$quebra = false;

if ($row_promissorias) {  //gera as promissorias
	$quebraPagina = 0;
	foreach ($row_promissorias as $prow) {

		//DADOS DO CLIENTE
		$dados_promissoria["cliente_nome"] = $prow->cliente;
		$dados_promissoria["cliente_documento"] = formatar_cpf_cnpj($prow->cliente_documento);
		$dados_promissoria["cliente_endereco"] = $prow->cliente_endereco;

		//DADOS DA EMPRESA
		$dados_promissoria["empresa_nome"] = $prow->empresa;
		$dados_promissoria["empresa_documento"] = formatar_cpf_cnpj($prow->empresa_documento);
		$dados_promissoria["empresa_local"] = $prow->empresa_cidade.'/'.$prow->empresa_uf;
		$dados_promissoria["empresa_fantasia"] = $prow->fantasia;
		$dados_promissoria["empresa_endereco1"] = $prow->endereco.', '.$prow->numero;
		$dados_promissoria["empresa_endereco2"] = $prow->bairro.', '.$dados_promissoria["empresa_local"];


		//DADOS DA PROMISSORIA
		$dados_promissoria["numero"] = $prow->id_venda.'/'.$prow->id;
		$dados_promissoria["data_vencimento"] = $prow->data_vencimento;
		$valor = moeda($prow->valor);
		$dados_promissoria["valor"] = $valor;
		$dados_promissoria["data_venda"] = exibedata($prow->data_venda);
		$dados_promissoria["data_extenso_numerico"] = dataextenso2(exibedata($prow->data_vencimento));
		$dados_promissoria["data_vencimento_extenso"] = retorna_data_por_extenso(exibedata($prow->data_vencimento));
		$dados_promissoria["valor_extenso"] = strtoupper(retorna_valor_por_extenso($valor,true,false));
		$dados_promissoria["pago"] = ($prow->pago==1) ? true : false;

		$quebraPagina++;
		$arquivo = 'recibo_promissoria_layout.php';
		include($arquivo);
		$quebra = true;
		
	}
} else { //Erro! Não foi informada uma venda e nem uma receita
	echo lang('MSG_ERRO_RECIBO_PROMISSORIA');
}

?>
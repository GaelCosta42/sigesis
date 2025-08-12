<?php
  /**
   * Boleto BB Layout
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  

define("_VALID_PHP", true);
require_once("init.php");

$id_pagamento = get('id_pagamento');
$nota = get('nota');
$todos = get('todos');

$dias_de_prazo_para_pagamento = 15;
$taxa_boleto = 0;
$banco_habilitado = true;
$codigobanco = lang('BOLETOS_BB_CODIGO'); // Num do codigo do banco
$agencia = lang('BOLETOS_BB_AGENCIA'); // Num da agencia, sem digito
$conta = lang('BOLETOS_BB_CONTA'); // Num da conta, sem digito
$variacao = lang('BOLETOS_BB_VARIACAO'); //N�mero da varia��o indicado no frontend
$carteira = lang('BOLETOS_BB_CARTEIRA'); //N�mero da carteira indicado no frontend
$convenio = lang('BOLETOS_BB_CONVENIO'); //N�mero do conv�nio indicado no frontend
$formatacao = lang('BOLETOS_BB_FORMATACAO_CONVENIO'); //Formata��o do conv�nio indicado no frontend
// SEUS DADOS	
$dadosboleto["identificacao"] = lang('BOLETOS_BB_RAZAO_EMPRESA');
$dadosboleto["cpf_cnpj"] = lang('BOLETOS_BB_CNPJ_EMPRESA');
$dadosboleto["endereco"] = lang('BOLETOS_BB_END_EMPRESA');
$dadosboleto["cidade_uf"] = lang('BOLETOS_BB_CIDADE_EMPRESA');
$dadosboleto["cedente"] = lang('BOLETOS_BB_RAZAO_EMPRESA');
	
// INSTRU��ES PARA O CAIXA
$dadosboleto["instrucoes1"] = lang('BOLETOS_INSTRUCOES3');
$dadosboleto["instrucoes2"] = lang('BOLETOS_INSTRUCOES4');
$dadosboleto["instrucoes3"] = lang('BOLETOS_CONTATO');
$dadosboleto["instrucoes4"] = "";
$retorno_row = false;
$quebra = false;
if($banco_habilitado) {
	$retorno_row = $faturamento->getGerarBoletos($id_pagamento, $todos, $nota);
} else {
	echo lang('MSG_ERRO_BOLETO_BANCO_NAO');
}
if($retorno_row) {
	foreach ($retorno_row as $exrow) {	
	
	// DADOS DO BOLETO PARA O SEU CLIENTE
	$total_nota = $faturamento->getReceitaNFTotal($exrow->id_pagamento,$todos, $nota);
	$nrodoc = str_pad($exrow->parcela,3,'0',STR_PAD_LEFT);
	$data_venc = exibedata($exrow->data_vencimento);  
	$valor_cobrado = $exrow->valor; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
	$valor_boleto = number_format($valor_cobrado+$taxa_boleto, 2, ',', '');
	$valor_cobrado = number_format($valor_cobrado, 2, ',', '');
	$cpf = $exrow->cpf_cnpj;
	$endere�o = "";
	$endere�o = ($exrow->endereco) ? $exrow->endereco : "";
	$endere�o .= ($exrow->numero) ? ", ".$exrow->numero : "";
	$endere�o .= ($exrow->complemento) ? " - ".$exrow->complemento : "";
	$endere�o .= ($exrow->bairro) ? " - ".$exrow->bairro : "";
	$endere�o2 = "";
	$endere�o2 = ($exrow->cidade) ? $exrow->cidade : "";
	$endere�o2 .= ($exrow->estado) ? "/".$exrow->estado : "";
	$endere�o2 .= ($exrow->cep) ? " - ".$exrow->cep : "";
	
	$dadosboleto["nosso_numero"] = $exrow->id;
	$numero_documento = ($nota) ? $nota.$nrodoc : $exrow->id_pagamento.$nrodoc;
	$numero_documento = limpaNossoNumero($numero_documento);
	$dadosboleto["numero_documento"] = $numero_documento;	// Num do pedido ou do documento
	$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
	$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emiss�o do Boleto
	$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
	$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com v�rgula e sempre com duas casas depois da virgula

	// DADOS DO SEU CLIENTE	
	$nome_cadastro = ($exrow->razao_social) ? $exrow->razao_social : $exrow->nome;
	$dadosboleto["sacado"] = $nome_cadastro." ".$cpf;
	$dadosboleto["endereco1"] = $endere�o ;
	$dadosboleto["endereco2"] = $endere�o2;
		
	// INFORMACOES PARA O CLIENTE
	$dadosboleto["demonstrativo1"] = lang('BOLETOS_PAGAMENTO');
	$dadosboleto["demonstrativo1"] .= $nome_cadastro." ".$cpf;
	$dadosboleto["demonstrativo2"] = lang('PARCELA').": ".$exrow->parcela." de ".$total_nota->quant;
	$dadosboleto["demonstrativo3"] = "";

	// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
	$dadosboleto["quantidade"] = "0";
	$dadosboleto["valor_unitario"] = "";
	$dadosboleto["aceite"] = "N";		
	$dadosboleto["especie"] = "R$";
	$dadosboleto["especie_doc"] = "DM";


	// ---------------------- DADOS FIXOS DE CONFIGURA��O DO SEU BOLETO --------------- //


	// DADOS DA SUA CONTA - BANCO DO BRASIL
	$dadosboleto["agencia"] = $agencia; // Num da agencia, sem digito
	$dadosboleto["conta"] = $conta; // Num da conta, sem digito

	// DADOS PERSONALIZADOS - BANCO DO BRASIL
	$dadosboleto["convenio"] = $convenio;  // Num do conv�nio - REGRA: 6 ou 7 ou 8 d�gitos
	$dadosboleto["contrato"] = ""; // Num do seu contrato
	$dadosboleto["carteira"] = $carteira;
	$dadosboleto["variacao_carteira"] = $variacao;  // Varia��o da Carteira, com tra�o (opcional)

	// TIPO DO BOLETO
	$dadosboleto["formatacao_convenio"] = $formatacao; // REGRA: 8 p/ Conv�nio c/ 8 d�gitos, 7 p/ Conv�nio c/ 7 d�gitos, ou 6 se Conv�nio c/ 6 d�gitos
	$dadosboleto["formatacao_nosso_numero"] = "2"; // REGRA: Usado apenas p/ Conv�nio c/ 6 d�gitos: informe 1 se for NossoN�mero de at� 5 d�gitos ou 2 para op��o de at� 17 d�gitos

	/*
	#################################################
	DESENVOLVIDO PARA CARTEIRA 18

	- Carteira 18 com Convenio de 8 digitos
	  Nosso n�mero: pode ser at� 9 d�gitos

	- Carteira 18 com Convenio de 7 digitos
	  Nosso n�mero: pode ser at� 10 d�gitos

	- Carteira 18 com Convenio de 6 digitos
	  Nosso n�mero:
	  de 1 a 99999 para op��o de at� 5 d�gitos
	  de 1 a 99999999999999999 para op��o de at� 17 d�gitos

	#################################################
	*/
	
	$codigo_banco_com_dv = $boleto->geraCodigoBanco_bb($codigobanco);
	$nummoeda = "9";
	$fator_vencimento = $boleto->fator_vencimento($dadosboleto["data_vencimento"]);

	//valor tem 10 digitos, sem virgula
	$valor = $boleto->formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
	//agencia � sempre 4 digitos
	$agencia = $boleto->formata_numero($dadosboleto["agencia"],4,0);
	//conta � sempre 8 digitos
	$conta = $boleto->formata_numero($dadosboleto["conta"],8,0);
	//carteira 18
	$carteira = $dadosboleto["carteira"];
	//agencia e conta
	$agencia_codigo = $agencia."-". $boleto->modulo_11_bb($agencia) ." / ". $conta ."-". $boleto->modulo_11_bb($conta);
	
	//Zeros: usado quando convenio de 7 digitos
	$livre_zeros='000000';

	// Carteira 18 com Conv�nio de 8 d�gitos
	if ($dadosboleto["formatacao_convenio"] == "8") {
		$convenio = $boleto->formata_numero($dadosboleto["convenio"],8,0,"convenio");
		// Nosso n�mero de at� 9 d�gitos
		$nossonumero = $boleto->formata_numero($dadosboleto["nosso_numero"],9,0);
		$dv=$boleto->modulo_11_bb("$codigobanco$nummoeda$fator_vencimento$valor$livre_zeros$convenio$nossonumero$carteira");
		$linha="$codigobanco$nummoeda$dv$fator_vencimento$valor$livre_zeros$convenio$nossonumero$carteira";
		//montando o nosso numero que aparecer� no boleto
		$nossonumero = $convenio . $nossonumero ."-". $boleto->modulo_11_bb($convenio.$nossonumero);
	}

	// Carteira 18 com Conv�nio de 7 d�gitos
	if ($dadosboleto["formatacao_convenio"] == "7") {
		$convenio = $boleto->formata_numero($dadosboleto["convenio"],7,0,"convenio");
		// Nosso n�mero de at� 10 d�gitos
		$nossonumero = $boleto->formata_numero($dadosboleto["nosso_numero"],10,0);
		$dv=$boleto->modulo_11_bb("$codigobanco$nummoeda$fator_vencimento$valor$livre_zeros$convenio$nossonumero$carteira");
		$linha="$codigobanco$nummoeda$dv$fator_vencimento$valor$livre_zeros$convenio$nossonumero$carteira";
	  $nossonumero = $convenio.$nossonumero;
		//N�o existe DV na composi��o do nosso-n�mero para conv�nios de sete posi��es
	}

	// Carteira 18 com Conv�nio de 6 d�gitos
	if ($dadosboleto["formatacao_convenio"] == "6") {
		$convenio = $boleto->formata_numero($dadosboleto["convenio"],6,0,"convenio");
		
		if ($dadosboleto["formatacao_nosso_numero"] == "1") {
			
			// Nosso n�mero de at� 5 d�gitos
			$nossonumero = $boleto->formata_numero($dadosboleto["nosso_numero"],5,0);
			$dv = $boleto->modulo_11_bb("$codigobanco$nummoeda$fator_vencimento$valor$convenio$nossonumero$agencia$conta$carteira");
			$linha = "$codigobanco$nummoeda$dv$fator_vencimento$valor$convenio$nossonumero$agencia$conta$carteira";
			//montando o nosso numero que aparecer� no boleto
			$nossonumero = $convenio . $nossonumero ."-". $boleto->modulo_11_bb($convenio.$nossonumero);
		}
		
		if ($dadosboleto["formatacao_nosso_numero"] == "2") {
			
			// Nosso n�mero de at� 17 d�gitos
			$nservico = "21";
			$nossonumero = $boleto->formata_numero($dadosboleto["nosso_numero"],17,0);
			$dv = $boleto->modulo_11_bb("$codigobanco$nummoeda$fator_vencimento$valor$convenio$nossonumero$nservico");
			$linha = "$codigobanco$nummoeda$dv$fator_vencimento$valor$convenio$nossonumero$nservico";
		}
	}

	$dadosboleto["codigo_barras"] = $linha;
	$dadosboleto["linha_digitavel"] = $boleto->monta_linha_digitavel($linha);
	$dadosboleto["agencia_codigo"] = $agencia_codigo;
	$dadosboleto["nosso_numero"] = $nossonumero;
	$dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;


	include("boleto_bb_layout.php");
	$quebra = true;
	}
} else {
	echo lang('MSG_ERRO_BOLETO');
}
?>

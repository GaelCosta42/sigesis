<?php
  /**
   * Boleto CAIXA ECONOMICA FEDERAL Layout
   *
   */
	define("_VALID_PHP", true);
  
	require("init.php");
	if (!$usuario->is_Todos())
	  redirect_to("login.php");
	
// ------------------------- DADOS DINÂMICOS DO SEU CLIENTE PARA A GERAÇÃO DO BOLETO (FIXO OU VIA GET) -------------------- //
// Os valores abaixo podem ser colocados manualmente ou ajustados p/ formulário c/ POST, GET ou de BD (MySql,Postgre,etc)	//

// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento = 15;
$taxa_boleto = 0;

$id_empresa = get('id_empresa');
$row_empresa = Core::getRowById("empresa", $id_empresa);

$codigobanco = $row_empresa->boleto_codigo_banco; // Código do Banco
$agencia = $row_empresa->boleto_agencia; // Num da agencia, sem digito
$conta = $row_empresa->boleto_conta; // Num da conta, sem digito
$convenio = $row_empresa->boleto_convenio; //Número do convênio indicado no frontend
$instrucoes1 = $row_empresa->boleto_instrucoes1;
$instrucoes2 = $row_empresa->boleto_instrucoes2;
$instrucoes3 = $row_empresa->boleto_instrucoes3;
$instrucoes4 = $row_empresa->boleto_instrucoes4;

$quebra = false;
$id_pagamento = get('id_pagamento');
$todos = get('todos');
$retorno_row = $faturamento->getGerarBoletos($id_pagamento, $todos);
$quebra = false;

if($retorno_row) {
	foreach ($retorno_row as $exrow) {

	// DADOS DO BOLETO PARA O SEU CLIENTE
	$carteira = 1;
	$nrodoc = str_pad($exrow->parcela,3,'0',STR_PAD_LEFT);
	$data_venc = exibedata($exrow->data_vencimento);  
	$valor_cobrado = $exrow->valor; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
	$valor_boleto = number_format($valor_cobrado+$taxa_boleto, 2, ',', '');
	$valor_cobrado = number_format($valor_cobrado, 2, ',', '');
	$cpf = isset($exrow->cpf_cnpj) ? $exrow->cpf_cnpj : (($exrow->tipo==1) ? $exrow->cnpj : $exrow->cpf);
	$IdDoSeuSistemaAutoIncremento = $exrow->id  + 14123000000000000;
	$nome_cadastro = ($exrow->razao_social) ? $exrow->razao_social : $exrow->nome;
	$endereço = ($exrow->endereco) ? $exrow->endereco : "";
	$endereço .= ($exrow->numero) ? ", ".$exrow->numero : "";
	$endereço .= ($exrow->complemento) ? " - ".$exrow->complemento : "";
	$endereço .= ($exrow->bairro) ? " - ".$exrow->bairro : "";
	$endereço2 = ($exrow->cidade) ? $exrow->cidade : "";
	$endereço2 .= ($exrow->estado) ? "/".$exrow->estado : "";
	$endereço2 .= ($exrow->cep) ? " - ".$exrow->cep : "";


	if(!function_exists('formata_numdoc'))
	{
		function formata_numdoc($num,$tamanho)
		{
			while(strlen($num)<$tamanho)
			{
				$num="0".$num; 
			}
		return $num;
		}
	}

	$NossoNumero = formata_numdoc($IdDoSeuSistemaAutoIncremento,17);
	$dvNossoNumero = $boleto->dvNossoNumeroCaixa(strval($NossoNumero));
	$dadosboleto["nosso_numero"] = $NossoNumero . $dvNossoNumero;

	$dadosboleto["numero_documento"] = $exrow->id_pagamento.$nrodoc;	// Num do pedido ou do documento
	$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
	$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
	$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
	$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

	// DADOS DO SEU CLIENTE
	$dadosboleto["sacado"] = $nome_cadastro." ".formatar_cpf_cnpj($cpf);
	$dadosboleto["endereco1"] = $endereço ;
	$dadosboleto["endereco2"] = $endereço2;

	// INFORMACOES PARA O CLIENTE
	$dadosboleto["demonstrativo1"] = lang('BOLETOS_PAGAMENTO');
	$dadosboleto["demonstrativo1"] .= $nome_cadastro." ".formatar_cpf_cnpj($cpf);
	$dadosboleto["demonstrativo2"] = lang('PARCELA').": ".$exrow->parcela;

	// INSTRUÇÕES PARA O CAIXA
	$dadosboleto["instrucoes1"] = $instrucoes1;
	$dadosboleto["instrucoes2"] = $instrucoes2;
	$dadosboleto["instrucoes3"] = $instrucoes3;
	$dadosboleto["instrucoes4"] = $instrucoes4;

	// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
	$dadosboleto["quantidade"] = "0";
	$dadosboleto["valor_unitario"] = "";
	$dadosboleto["aceite"] = "N";		
	$dadosboleto["especie"] = "R$";
	$dadosboleto["especie_doc"] = "DM";


	// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
	// DADOS ESPECIFICOS DO SICOOB
	$dadosboleto["modalidade_cobranca"] = "01";
	$dadosboleto["numero_parcela"] = $nrodoc;


	// DADOS DA SUA CONTA - BANCO SICOOB
	$dadosboleto["agencia"] = $agencia; // Num da agencia, sem digito
	$dadosboleto["conta"] = $conta; // Num da conta, sem digito

	// DADOS PERSONALIZADOS - SICOOB
	$dadosboleto["convenio"] = $convenio; // Num do convênio - REGRA: No máximo 7 dígitos
	$dadosboleto["carteira"] = $carteira;

	// SEUS DADOS
	$dadosboleto["identificacao"] = $core->empresa;
	$dadosboleto["cpf_cnpj"] = formatar_cpf_cnpj($row_empresa->cnpj);
	$dadosboleto["endereco"] = $row_empresa->endereco.', '.$row_empresa->numero;
	$dadosboleto["endereco"] .= ($row_empresa->complemento) ? ', '.$row_empresa->complemento : "";
	$dadosboleto["endereco"] .= ' - '.$row_empresa->bairro;
	$dadosboleto["cidade_uf"] = $row_empresa->cidade.'/'.$row_empresa->estado;
	$dadosboleto["cedente"] = $row_empresa->razao_social.' | '.formatar_cpf_cnpj($row_empresa->cnpj);
	
	$codigo_banco_com_dv = $boleto->geraCodigoBanco_caixa($codigobanco);
	$nummoeda = "9";
	$fator_vencimento = $boleto->fator_vencimento($dadosboleto["data_vencimento"]);

	//valor tem 10 digitos, sem virgula
	$valor = $boleto->formata_numero($dadosboleto["valor_boleto"],10,0,"valor");
	//agencia é sempre 4 digitos
	$agencia = $boleto->formata_numero($dadosboleto["agencia"],4,0);
	//conta é sempre 8 digitos
	$conta = $boleto->formata_numero($dadosboleto["conta"],8,0);

	$carteira = $dadosboleto["carteira"];

	//Zeros: usado quando convenio de 7 digitos
	$livre_zeros='000000';
	$modalidadecobranca = $dadosboleto["modalidade_cobranca"];
	$numeroparcela      = $dadosboleto["numero_parcela"];

	$convenio = $boleto->formata_numero($dadosboleto["convenio"],7,0);

	//agencia e conta
	$agencia_codigo = $agencia ." / ". $convenio;

	// Nosso número de até 8 dígitos - 2 digitos para o ano e outros 6 numeros sequencias por ano 
	// deve ser gerado no programa boleto_bancoob.php
	$nossonumero = $boleto->formata_numero($dadosboleto["nosso_numero"],8,0);
	//$campolivre  = "$modalidadecobranca$convenio$nossonumero$numeroparcela";

	$nossoNumeroSequencia1 = substr($nossonumero,2,3);
	$nossoNumeroSequencia2 = substr($nossonumero,5,3);
	$nossoNumeroSequencia3 = substr($nossonumero,8,9);

	$nossoNumeroConstante1 = substr($nossonumero,0,1);
	$nossoNumeroConstante2 = substr($nossonumero,1,1);

	$campolivre = "$convenio$nossoNumeroSequencia1$nossoNumeroConstante1$nossoNumeroSequencia2$nossoNumeroConstante2$nossoNumeroSequencia3";
	$dvCampoLivre = $boleto->dvCampoLivreModulo11Caixa($campolivre);

	$dvCodigoBarras = $boleto->dvGeralCodigoBarrasCaixa("$codigobanco$nummoeda$fator_vencimento$valor$campolivre$dvCampoLivre");
	$linha="$codigobanco$nummoeda$dvCodigoBarras$fator_vencimento$valor$campolivre$dvCampoLivre";

	$dadosboleto["codigo_barras"] = $linha;
	$dadosboleto["linha_digitavel"] = $boleto->monta_linha_digitavel_caixa($linha);
	$dadosboleto["agencia_codigo"] = $agencia_codigo.'-'.$boleto->dvCodigoCedenteModulo11Caixa($convenio);
	$dadosboleto["nosso_numero"] = $nossonumero;
	$dadosboleto["codigo_banco_com_dv"] = $codigo_banco_com_dv;

	$banco_boleto = $row_empresa->boleto_banco;
	$arquivo = 'boleto_'.$banco_boleto.'_layout.php';
	include($arquivo);
	$quebra = true;
	}
} else {
		echo lang('MSG_ERRO_BOLETO');
	}
?>
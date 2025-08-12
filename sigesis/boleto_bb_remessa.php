<?php
define("_VALID_PHP", true);
require_once("init.php");
$quantidade_lote = 1;
$valor_total = 0;
$datahoje = date("dmY");
$formatacao_nosso_numero = "2";
$nummoeda = "9";

$banco_habilitado = true;		

$banco = lang('BOLETOS_BB_CODIGO');
$codigobanco = lang('BOLETOS_BB_CODIGO'); // Num da agencia, sem digito
$agencia = lang('BOLETOS_BB_AGENCIA'); // Num da agencia, sem digito
$conta = lang('BOLETOS_BB_CONTA'); // Num da conta, sem digito
$variacao = lang('BOLETOS_BB_VARIACAO'); //Número da variação indicado no frontend
$carteira = lang('BOLETOS_BB_CARTEIRA'); //Número da carteira indicado no frontend
$convenio = lang('BOLETOS_BB_CONVENIO'); //Número do convênio indicado no frontend
$formatacao = lang('BOLETOS_BB_FORMATACAO_CONVENIO'); //Formatação do convênio indicado no frontend
	
$agencia_conta = str_pad(lang('BOLETOS_BB_AGENCIA'),5,"0",STR_PAD_LEFT);
$agencia_dv = $boleto->modulo_11_BB($agencia_conta);
$cedente = str_pad(lang('BOLETOS_BB_CONTA'),12,"0",STR_PAD_LEFT);
$cedente .= $boleto->modulo_11_bb($conta);
$cedente_dv = " ";
$nome_empresa = str_pad(cleanSanitize(lang('BOLETOS_BB_RAZAO_EMPRESA')),30," ",STR_PAD_RIGHT);
$numero_inscricao = str_pad(lang('BOLETOS_BB_CNPJ_EMPRESA_REMESSA'),14,"0",STR_PAD_LEFT);
$numero_inscricao2 = str_pad(lang('BOLETOS_BB_CNPJ_EMPRESA_REMESSA'),15,"0",STR_PAD_LEFT);

$retorno_row = $faturamento->getRemessa();

if($retorno_row):
$quantidade_titulos = count($retorno_row);
//HEADER ARQUIVO
$lote_registro_arquivo = "0000";
$tipo_registro_arquivo = "0";
$cnab1 = str_pad(" ",9," ",STR_PAD_LEFT);
$tipo_inscricao = "2";
$codigoconveniobb1 = str_pad($convenio,9,"0",STR_PAD_LEFT);
$codigoconveniobb2 = "0014";
$codigoconveniobb3 = $carteira;
$codigoconveniobb4 = $variacao;
$codigoconveniobb5 = "  ";
$codigoconveniobb = $codigoconveniobb1.$codigoconveniobb2.$codigoconveniobb3.$codigoconveniobb4.$codigoconveniobb5;
$nome_empresa = substr($nome_empresa, 0,30);
$nome_banco = str_pad("BANCO DO BRASIL",30," ",STR_PAD_RIGHT);
$cnab2 = str_pad(" ",10," ",STR_PAD_LEFT);
$codigo_remessa = "1";
$data_geracao = date("dmY");
$hora_geracao = date("His");
$sequencial_arquivo = date("ymd");
$layout_arquivo = "000"; //083
$gravacao_arquivo = str_pad("0",5,"0",STR_PAD_LEFT);
$reservado_banco = str_pad(" ",20," ",STR_PAD_LEFT);
$reservado_empresa = str_pad(" ",20," ",STR_PAD_LEFT);
$cnab3 = str_pad(" ",29," ",STR_PAD_LEFT);

$a = 0;
$header_arquivo = $banco.$lote_registro_arquivo.$tipo_registro_arquivo.$cnab1.$tipo_inscricao.$numero_inscricao.$codigoconveniobb.$agencia_conta.$agencia_dv.$cedente.$cedente_dv.$nome_empresa.$nome_banco.$cnab2.$codigo_remessa.$data_geracao.$hora_geracao.$sequencial_arquivo.$layout_arquivo.$gravacao_arquivo.$reservado_banco.$reservado_empresa.$cnab3;

$header_arquivo = substr($header_arquivo, 0,240)."\r\n";
$a++;
//HEADER LOTE
$lote_header_lote = "0001";
$tipo_header_lote = "1";
$tipo_operacao_lote = "R";
$tipo_servico_lote = "01";
$cnab4 = "  ";
$layout_lote = "000"; //042
$cnab7 = " ";
$uso_caixa4 = str_pad("0",14,"0",STR_PAD_LEFT);
$uso_caixa5 = "0";
$mensagem1 = str_pad(" ",40," ",STR_PAD_LEFT);
$mensagem2 = str_pad(" ",40," ",STR_PAD_LEFT);
$numero_remessa = date("Ymd");
$data_gravacao = date("dmY");
$data_credito = str_pad("0",8,"0",STR_PAD_LEFT);
$cnab9 = str_pad(" ",33," ",STR_PAD_LEFT);

$l = 0;
$header_lote = $banco.$lote_header_lote.$tipo_header_lote.$tipo_operacao_lote.$tipo_servico_lote.$cnab4.$layout_lote.$cnab7.$tipo_inscricao.$numero_inscricao2.$codigoconveniobb.$agencia_conta.$agencia_dv.$cedente.$cedente_dv.$nome_empresa.$mensagem1.$mensagem2.$numero_remessa.$data_gravacao.$data_credito.$cnab9;

$header_lote = substr($header_lote, 0,240)."\r\n";
$a++;
$l++;
$detalhes = "";
foreach ($retorno_row as $exrow):

	$nrodoc = str_pad($exrow->parcela,3,'0',STR_PAD_LEFT);
	$numero_documento = $exrow->id_pagamento.$nrodoc;
	$numero_documento = limpaNossoNumero($numero_documento);
	$valor_nominal = converteMoedaRemessa($exrow->valor);
	$valor_total += $exrow->valor;
	$parcela = $exrow->parcela;
	
	//Registro Detalhe - Segmento P (Obrigatório - Remessa)
	$lote_detalhe = $lote_header_lote;
	$tipo_detalhe = "3";
	$sequencial_lote = str_pad($l,5,"0",STR_PAD_LEFT);
	$registro_detalhe = "P";
	$cnab10 = " ";
	$movimento_remessa = "01";
	
	//Zeros: usado quando convenio de 7 digitos
	$livre_zeros='000000';

	// Carteira 18 com Convênio de 8 dígitos
	if ($formatacao == "8") {
		$convenio = $boleto->formata_numero($convenio,8,0,"convenio");
		// Nosso número de até 9 dígitos
		$nossonumero = $boleto->formata_numero($exrow->id,9,0);
		//montando o nosso numero que aparecerá no boleto
		$nossonumero = $convenio . $nossonumero ."-". $boleto->modulo_11_bb($convenio.$nossonumero);
	}

	// Carteira 18 com Convênio de 7 dígitos
	if ($formatacao == "7") {
		$convenio = $boleto->formata_numero($convenio,7,0,"convenio");
		// Nosso número de até 10 dígitos
		$nossonumero = $boleto->formata_numero($exrow->id,10,0);
		$nossonumero = $convenio.$nossonumero;
		//Não existe DV na composição do nosso-número para convênios de sete posições
	}

	// Carteira 18 com Convênio de 6 dígitos
	if ($formatacao == "6") {
		$convenio = $boleto->formata_numero($convenio,6,0,"convenio");
		
		if ($formatacao_nosso_numero == "1") {
			
			// Nosso número de até 5 dígitos
			$nossonumero = $boleto->formata_numero($exrow->id,5,0);
			//montando o nosso numero que aparecerá no boleto
			$nossonumero = $convenio . $nossonumero ."-". $boleto->modulo_11_bb($convenio.$nossonumero);
		}
		
		if ($formatacao_nosso_numero == "2") {
			
			// Nosso número de até 17 dígitos
			$nservico = "21";
			$nossonumero = $boleto->formata_numero($exrow->id,17,0);
		}
	}
	$nossonumero = str_pad($nossonumero,20," ",STR_PAD_RIGHT);
	
	$codigo_carteira = "7";
	$cadastro_titulo = "0";
	$tipo_documento = " ";
	$identificacao_emissao = "2";
	$identificacao_entrega = "2";
	$numero_documento = str_pad($numero_documento,15," ",STR_PAD_LEFT);
	$numero_documento = substr($numero_documento,0,15);		
	$data_vencimento = exibedataRemessa($exrow->data_vencimento);
	$valor_nominal = str_pad($valor_nominal,15,"0",STR_PAD_LEFT);
	$agencia_cobradora = "00000 ";
	$especie_titulo = "02";
	$aceite = "A";
	$data_emissao = $datahoje;
	$codigo_juros = "1";
	$data_juros = $data_vencimento;
	$valor_juros = str_pad("033",15,"0",STR_PAD_LEFT);
	$codigo_desconto = "0";
	$data_desconto = str_pad("0",8,"0",STR_PAD_LEFT);
	$valor_desconto = str_pad("0",15,"0",STR_PAD_LEFT);
	$valor_iof = str_pad("0",15,"0",STR_PAD_LEFT);
	$valor_abatimento = str_pad("0",15,"0",STR_PAD_LEFT);
	$uso_empresa = str_pad(" ",25," ",STR_PAD_LEFT);
	$codigo_protesto = "3";
	$dias_protesto = "90";
	$codigo_baixa = "0";
	$dias_baixa = str_pad("0",3,"0",STR_PAD_LEFT);
	$codigo_moeda = "09";
	$numero_contrato = str_pad("0",10,"0",STR_PAD_LEFT);
	$uso_caixa9 = " ";

	$linha_p = $banco.$lote_detalhe.$tipo_detalhe.$sequencial_lote.$registro_detalhe.$cnab10.$movimento_remessa.$agencia_conta.$agencia_dv.$cedente.$cedente_dv.$nossonumero.$codigo_carteira.$cadastro_titulo.$tipo_documento.$identificacao_emissao.$identificacao_entrega.$numero_documento.$data_vencimento.$valor_nominal.$agencia_cobradora.$especie_titulo.$aceite.$data_emissao.$codigo_juros.$data_juros.$valor_juros.$codigo_desconto.$data_desconto.$valor_desconto.$valor_iof.$valor_abatimento.$uso_empresa.$codigo_protesto.$dias_protesto.$codigo_baixa.$dias_baixa.$codigo_moeda.$numero_contrato.$uso_caixa9;
	
	$linha_p = substr($linha_p, 0,240)."\r\n";
	$a++;
	$l++;
	$sequencial_lote = str_pad($l,5,"0",STR_PAD_LEFT);

	//Registro Detalhe - Segmento Q (Obrigatório - Remessa)
	$registro_detalhe = "Q";
	$cnab11 = " ";
	$tipo_inscricao = "1";	
	$cpf = str_pad(0,15,"0",STR_PAD_LEFT);
	if($exrow->tipo == 1) {
		$tipo_inscricao = "2";	
		$cpf = $exrow->cpf_cnpj;		
		$cpf = str_replace("-","",$cpf);		
		$cpf = str_replace(".","",$cpf);
		$cpf = substr($cpf,0,15);				
		$cpf = str_pad($cpf,15,"0",STR_PAD_LEFT);
	} else {
		$tipo_inscricao = "1";	
		$cpf = $exrow->cpf_cnpj;		
		$cpf = str_replace("-","",$cpf);		
		$cpf = str_replace(".","",$cpf);	
		$cpf = substr($cpf,0,15);			
		$cpf = str_pad($cpf,15,"0",STR_PAD_LEFT);
	}
	$numero_inscricao = $cpf;
	$nome_cadastro = ($exrow->razao_social) ? $exrow->razao_social : $exrow->nome;
	$nome = substr($nome_cadastro,0,40);
	$nome = str_pad(cleanSanitize($nome),40," ",STR_PAD_RIGHT);
	$endereco = str_pad(cleanSanitize($exrow->endereco." ".$exrow->numero." ".$exrow->complemento),40," ",STR_PAD_RIGHT);
	$endereco = substr($endereco,0,40);
	$endereco = str_pad(cleanSanitize($endereco),40," ",STR_PAD_RIGHT);
	$bairro = substr(cleanSanitize($exrow->bairro),0,15);
	$bairro = str_pad(cleanSanitize($bairro),15," ",STR_PAD_RIGHT);
	$cep = $exrow->cep;		
	$cep = str_replace("-","",$cep);		
	$cep = str_replace(".","",$cep);	
	$cep = substr($cep,0,8);	
	$cep = str_pad($cep,8,"0",STR_PAD_RIGHT);
	$cidade = substr($exrow->cidade,0,15);
	$cidade = str_pad(cleanSanitize($cidade),15," ",STR_PAD_RIGHT);
	$uf = substr($exrow->estado,0,2);
	$uf = str_pad($uf,2," ",STR_PAD_RIGHT);
	$tipo_inscricao_aval = "0";
	$numero_inscricao_avl = str_pad("0",15,"0",STR_PAD_LEFT);
	$nome_aval = str_pad(" ",40," ",STR_PAD_LEFT);
	$banco_correspondente = "000";
	$nosso_numero_correspondente = str_pad(" ",20," ",STR_PAD_LEFT);
	$cnab12 = str_pad(" ",8," ",STR_PAD_LEFT);

	$linha_q = $banco.$lote_detalhe.$tipo_detalhe.$sequencial_lote.$registro_detalhe.$cnab11.$movimento_remessa.$tipo_inscricao.$numero_inscricao.$nome.$endereco.$bairro.$cep.$cidade.$uf.$tipo_inscricao_aval.$numero_inscricao_avl.$nome_aval.$banco_correspondente.$nosso_numero_correspondente.$cnab12;	
	
	$linha_q = substr($linha_q, 0,240)."\r\n";
	$a++;
	$l++;
	$sequencial_lote = str_pad($l,5,"0",STR_PAD_LEFT);

	//Registro Detalhe - Segmento R (Opcional - Remessa)
	$tipo_detalhe = "3";
	$registro_detalhe = "R";
	$cnab13 = " ";
	$campo_descontos = str_pad("0",48,"0",STR_PAD_LEFT);
	$codigo_multa = "2";
	$data_multa = $data_vencimento;
	$valor_multa = str_pad("002",15,"0",STR_PAD_LEFT);
	$campo_informacoes = str_pad(" ",110," ",STR_PAD_LEFT);
	$pagador1 = str_pad("0",16,"0",STR_PAD_LEFT);
	$pagador2 = " ";
	$pagador3 = str_pad("0",12,"0",STR_PAD_LEFT);
	$pagador4 = " ";
	$pagador5 = " ";
	$pagador6 = "0";
	$cnab14 = str_pad(" ",9," ",STR_PAD_LEFT);

	$linha_r = $banco.$lote_detalhe.$tipo_detalhe.$sequencial_lote.$registro_detalhe.$cnab13.$movimento_remessa.$campo_descontos.$codigo_multa.$data_multa.$valor_multa.$campo_informacoes.$pagador1.$pagador2.$pagador3.$pagador4.$pagador5.$pagador6.$cnab14;
	
	$linha_r = substr($linha_r, 0,240)."\r\n";
	$a++;
	$l++;
	$sequencial_lote = str_pad($l,5,"0",STR_PAD_LEFT);

	//Registro Detalhe - Segmento S (Opcional - Remessa)
	$tipo_detalhe = "3";
	$registro_detalhe = "S";
	$cnab15 = " ";
	$tipo_impressao = "0";
	$informacao5 = str_pad(" ",40," ",STR_PAD_LEFT);
	$informacao6 = str_pad(" ",40," ",STR_PAD_LEFT);
	$informacao7 = str_pad(" ",40," ",STR_PAD_LEFT);
	$informacao8 = str_pad(" ",40," ",STR_PAD_LEFT);
	$uso_caixa11 = str_pad(" ",40," ",STR_PAD_LEFT);
	$cnab16 = str_pad(" ",22," ",STR_PAD_LEFT);

	$linha_s = $banco.$lote_detalhe.$tipo_detalhe.$sequencial_lote.$registro_detalhe.$cnab15.$movimento_remessa.$tipo_impressao.$informacao5.$informacao6.$informacao7.$informacao8.$uso_caixa11.$cnab16;	
	
	// $linha_s = substr($linha_s, 0,240)."\r\n";
	$linha_s = "";
	
	$a++;
	$l++;
	
	$detalhes .= $linha_p.$linha_q.$linha_r.$linha_s;

endforeach;
unset($exrow);

//TRAILER LOTE
$l++;
$tipo_trailer_lote = "5";
$valor_titulos = converteMoedaRemessa($valor_total);
$cnab4 = str_pad(" ",9," ",STR_PAD_LEFT);
$quantidade_registro = str_pad($l,6,"0",STR_PAD_LEFT);
$quantidade_titulos = str_pad($quantidade_titulos,6,"0",STR_PAD_LEFT);
$valor_titulos = str_pad($valor_titulos,17,"0",STR_PAD_LEFT);
$quantidade_cobranca = str_pad("0",6,"0",STR_PAD_LEFT);
$valor_cobranca = str_pad("0",17,"0",STR_PAD_LEFT);
$quantidade_caucionada = str_pad("0",6,"0",STR_PAD_LEFT);
$valor_caucionada = str_pad("0",17,"0",STR_PAD_LEFT);
$quantidade_descontada = str_pad("0",6,"0",STR_PAD_LEFT);
$valor_descontada = str_pad("0",17,"0",STR_PAD_LEFT);
$cnab17 = str_pad(" ",8," ",STR_PAD_LEFT);
$cnab18 = str_pad(" ",117," ",STR_PAD_LEFT);

$trailer_lote = $banco.$lote_detalhe.$tipo_trailer_lote.$cnab4.$quantidade_registro.$quantidade_titulos.$valor_titulos.$quantidade_cobranca.$valor_cobranca.$quantidade_caucionada.$valor_caucionada.$quantidade_descontada.$valor_descontada.$cnab17.$cnab18;

$trailer_lote = substr($trailer_lote, 0,240)."\r\n";
$a += 2;

//TRAILER ARQUIVO
$lote_trailer_arquivo = "9999";
$tipo_trailer_arquivo = "9";
$cnab4 = str_pad(" ",9," ",STR_PAD_LEFT);
$quantidade_lote = str_pad($quantidade_lote,6,"0",STR_PAD_LEFT);
$quantidade_registro = str_pad($a,6,"0",STR_PAD_LEFT);
$cnab5 = str_pad("0",6,"0",STR_PAD_LEFT);
$cnab6 = str_pad(" ",205," ",STR_PAD_LEFT);

$trailer_arquivo = $banco.$lote_trailer_arquivo.$tipo_trailer_arquivo.$cnab4.$quantidade_lote.$quantidade_registro.$cnab5.$cnab6."\r\n";;
// $ultima_linha = "       \r\n";

$arquivo_remessa = $header_arquivo.$header_lote.$detalhes.$trailer_lote.$trailer_arquivo;

$arquivo_remessa = mb_convert_encoding($arquivo_remessa, "ISO-8859-1");

header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="BB_'.date("Ymd").'.REM"');
header('Content-Length: ' . strlen( $arquivo_remessa ) ); 
header('Content-Type: text/plain; charset=windows-1251');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Expires: 0');
echo $arquivo_remessa;
else:
echo lang('MSG_ERRO_REMESSA');
endif;
?>
<?php
define("_VALID_PHP", true);
require_once("init.php");
$retorno_row = $faturamento->getRemessa();
$quantidade_lote = 1;
$valor_total = 0;
$s = 0;
$datahoje = date("dmY");
$taxa_boleto = 3;


if($retorno_row):
	$quantidade_titulos = count($retorno_row);
	
//HEADER ARQUIVO

$id_empresa = get('id_empresa');
$row_empresa = Core::getRowById("empresa", $id_empresa);

$valor_sequencial_atualizado = (isset($row_empresa->boleto_sequencial) && $row_empresa->boleto_sequencial>0) ? $row_empresa->boleto_sequencial : 1;
$data_atualizar_empresa = array(
	'boleto_sequencial' => $valor_sequencial_atualizado + 1
);
$db->update("empresa", $data_atualizar_empresa, "id=".$id_empresa);


$banco = trim($row_empresa->boleto_codigo_banco);
$lote_registro_arquivo = "0000";
$tipo_registro_arquivo = "0";
$cnab1 = str_pad(" ",9," ",STR_PAD_LEFT);
$tipo_inscricao = "2";
$cnpj_empresa_remessa = limparCPF_CNPJ($row_empresa->cnpj);
$numero_inscricao = str_pad($cnpj_empresa_remessa,14,"0",STR_PAD_LEFT);
$uso_caixa1 = str_pad("0",20,"0",STR_PAD_LEFT);
$uso_caixa2 = str_pad("0",7,"0",STR_PAD_LEFT);
$agencia_conta = str_pad($row_empresa->boleto_agencia,5,"0",STR_PAD_LEFT);
$agencia_dv = $boleto->modulo_11_caixa($agencia_conta);
$agencia_dv = ($agencia_dv=='X') ? '0' : $agencia_dv;
$cedente = str_pad($row_empresa->boleto_conta,13,"0",STR_PAD_LEFT);
$agencia_conta_dv = " ";
$agencia_conta_dv1 = "0";
$nome_empresa = str_pad(sanitize($row_empresa->razao_social),30," ",STR_PAD_RIGHT);
$nome_empresa = substr($nome_empresa, 0,30);
$nome_banco = str_pad("CAIXA",30," ",STR_PAD_RIGHT);
$cnab2 = str_pad(" ",10," ",STR_PAD_LEFT);
$codigo_remessa = "1";
$data_geracao = date("dmY");
$hora_geracao = date("His");

$sequencial_arquivo = 241300 + $valor_sequencial_atualizado;

$codigo_beneficiario = str_pad($row_empresa->boleto_convenio,7,"0",STR_PAD_RIGHT);
$layout_arquivo = (strlen($row_empresa->boleto_convenio)<=6) ? "101" : "107"; // estava assim no sicoob: "081";
$gravacao_arquivo = str_pad("0",5,"0",STR_PAD_LEFT);
$reservado_banco = str_pad(" ",20," ",STR_PAD_LEFT);
$reservado_empresa = str_pad(" ",20," ",STR_PAD_LEFT);
$cnab3 = str_pad(" ",29," ",STR_PAD_LEFT);

$header_arquivo = $banco.$lote_registro_arquivo.$tipo_registro_arquivo.$cnab1.$tipo_inscricao.$numero_inscricao.$uso_caixa1.
$agencia_conta.$agencia_dv.$codigo_beneficiario.$uso_caixa2.$nome_empresa.$nome_banco.$cnab2.$codigo_remessa.$data_geracao.
$hora_geracao.$sequencial_arquivo.$layout_arquivo.$gravacao_arquivo.$reservado_banco.$reservado_empresa.$cnab3;

$header_arquivo = substr($header_arquivo, 0,240)."\r\n";

//HEADER LOTE
$lote_header_lote = "0001";
$tipo_header_lote = "1";
$tipo_operacao_lote = "R";
$tipo_servico_lote = "01";
$cnab4 = "00";
$layout_lote = (strlen($row_empresa->boleto_convenio)<=6) ? "060" : "067"; // estava assim no sicoob: "040";
$cnab7 = " ";
$numero_inscricao = str_pad($cnpj_empresa_remessa,15,"0",STR_PAD_LEFT);
$uso_caixa4 = str_pad("0",14,"0",STR_PAD_LEFT);
$uso_caixa5 = "0";
$uso_caixa13 = str_pad("0",13,"0",STR_PAD_LEFT);
$uso_caixa07 = str_pad("0",7,"0",STR_PAD_LEFT);
$uso_caixa01 = str_pad("0",1,"0",STR_PAD_LEFT);
$uso_caixa04 = str_pad("0",4,"0",STR_PAD_LEFT);
$uso_caixa10 = str_pad(" ",10," ",STR_PAD_LEFT);
$mensagem1 = str_pad(" ",40," ",STR_PAD_LEFT);
$mensagem2 = str_pad(" ",40," ",STR_PAD_LEFT);
$numero_remessa_aux = date("ymd");
$numero_remessa = "00".$numero_remessa_aux;
$data_gravacao = date("dmY");
$data_credito = str_pad("0",8,"0",STR_PAD_LEFT);
$codigo_beneficiario2 = (strlen($row_empresa->boleto_convenio)<=6) ? str_pad($row_empresa->boleto_convenio,6,"0",STR_PAD_RIGHT) : str_pad("0",6,"0",STR_PAD_LEFT);;
$cnab9 = str_pad(" ",33," ",STR_PAD_LEFT);

$header_lote = $banco.$lote_header_lote.$tipo_header_lote.$tipo_operacao_lote.$tipo_servico_lote.$cnab4.$layout_lote.$cnab7.$tipo_inscricao.$numero_inscricao.$codigo_beneficiario.$uso_caixa13.$agencia_conta.$agencia_dv.$codigo_beneficiario2.$uso_caixa07.$uso_caixa01.$nome_empresa.$mensagem1.$mensagem2.$numero_remessa.$data_gravacao.$data_credito.$cnab9;

$header_lote = substr($header_lote, 0,240)."\r\n";

$detalhes = "";
$i = 1;
foreach ($retorno_row as $exrow):
	// $i++;
	$s++;
	$numero_documento = $exrow->id_pagamento;
	$valor_nominal = converteMoedaRemessa($exrow->valor);
	$valor_total += $exrow->valor;
	$parcela = $exrow->parcela;
	
	//Registro Detalhe - Segmento P (Obrigatório - Remessa)
	$lote_detalhe = str_pad($i,4,"0",STR_PAD_LEFT);
	$tipo_detalhe = "3";
	$sequencial_lote = str_pad($s,5,"0",STR_PAD_LEFT);
	$registro_detalhe = "P";
	$cnab10 = " ";
	$movimento_remessa = "01";
		
	$codigobanco = $row_empresa->boleto_codigo_banco; 			// Código do banco
	$agencia = $row_empresa->boleto_agencia;					// Num da agencia, sem digito
	$conta = $row_empresa->boleto_conta;						// Num da conta, sem digito
	$convenio = $row_empresa->boleto_convenio;					//Número do convênio indicado no frontend
	
	$IdDoSeuSistemaAutoIncremento = $exrow->id  + 14123000000000000;
	$NossoNumero = formata_numdoc($IdDoSeuSistemaAutoIncremento,17);
	$qtde_nosso_numero = strlen($NossoNumero);
	$sequencia = formata_numdoc($agencia,4).formata_numdoc(str_replace("-","",$convenio),10).formata_numdoc($NossoNumero,7);
	$cont=0;
	$calculoDv = 0;
	for($num=0;$num<=strlen($sequencia);$num++)
	{
		$cont++;
		if($cont == 1)
		{
			// constante fixa Sicoob » 3197 
			$constante = 3;
		}
		if($cont == 2)
		{
			$constante = 1;
		}
		if($cont == 3)
		{
			$constante = 9;
		}
		if($cont == 4)
		{
			$constante = 7;
			$cont = 0;
		}
		$calculoDv = $calculoDv + (intval(substr($sequencia,$num,1)) * $constante);
	}

	$Resto = $calculoDv % 11;
	$Dv = 11 - $Resto;
	if ($Dv > 9) $Dv = 0;

	//$nosso_numero = $NossoNumero . $Dv;
	//$nosso_numero = str_pad($nosso_numero,10,"0",STR_PAD_LEFT); //Identificação do Título
	//$nosso_numero .= str_pad($parcela,2,"0",STR_PAD_LEFT); //Parcela
	//$nosso_numero .= "01"; //Tipo de Modalidade
	//$nosso_numero .= "4"; //Tipo de Formulario

	$nosso_numero = $NossoNumero;

	$codigo_carteira = "1";
	$cadastro_titulo = "1";
	$modalidade_carteira_sinco = "0";
	//$modalidade_carteira_sigcb = "14";
	$tipo_documento = "2";
	$identificacao_emissao = "2";
	$identificacao_entrega = "0";
	$numero_documento = str_pad($numero_documento,11," ",STR_PAD_RIGHT);
	$data_vencimento = exibedataRemessa($exrow->data_vencimento);
	$valor_nominal = str_pad($valor_nominal,15,"0",STR_PAD_LEFT);
	$agencia_cobradora = "00000";
	$digito_agencia_cobradora = "0";
	$especie_titulo = "02";
	$aceite = "A";
	$data_emissao = $datahoje;
	$codigo_juros = "1";
	$data_juros = exibedataRemessaJuros($exrow->data_vencimento);
	$valor_juros = str_pad("033",15,"0",STR_PAD_LEFT);
	$codigo_desconto = "0";
	$data_desconto = str_pad("0",8,"0",STR_PAD_LEFT);
	$valor_desconto = str_pad("0",15,"0",STR_PAD_LEFT);
	$valor_iof = str_pad("0",15,"0",STR_PAD_LEFT);
	$valor_abatimento = str_pad("0",15,"0",STR_PAD_LEFT);
	$uso_empresa = str_pad($numero_documento,25," ",STR_PAD_RIGHT);
	$codigo_protesto = "3";
	$dias_protesto = "00";
	$codigo_baixa = "1";
	$dias_baixa = str_pad("15",3,"0",STR_PAD_LEFT);
	$codigo_moeda = "09";
	$numero_contrato = str_pad("0",10,"0",STR_PAD_LEFT);
	$uso_caixa9 = " ";
	$uso_caixa80 = str_pad("0",80,"0",STR_PAD_LEFT);
	$uso_caixa09 = str_pad("0",9,"0",STR_PAD_LEFT);
	$identificacao_titulo = str_pad($nosso_numero,17,"0",STR_PAD_LEFT);
	$pagamento_parcial = " ";
	$uso_caixa04_espaco = str_pad(" ",4," ",STR_PAD_LEFT);
	$uso_caixa10_zero = str_pad("0",10,"0",STR_PAD_LEFT);

	$linha_p = $banco.$lote_detalhe.$tipo_detalhe.$sequencial_lote.$registro_detalhe.$cnab10.$movimento_remessa.$agencia_conta.
	$agencia_dv.$codigo_beneficiario.$uso_caixa09.$modalidade_carteira_sinco.$identificacao_titulo.$codigo_carteira.$cadastro_titulo.$tipo_documento.$identificacao_emissao.$identificacao_entrega.$numero_documento.$uso_caixa04_espaco.$data_vencimento.$valor_nominal.$agencia_cobradora.$digito_agencia_cobradora.$especie_titulo.$aceite.$data_emissao.$codigo_juros.$data_juros.$valor_juros.$codigo_desconto.$data_desconto.$valor_desconto.$valor_iof.$valor_abatimento.$uso_empresa.$codigo_protesto.$dias_protesto.$codigo_baixa.$dias_baixa.$codigo_moeda.$uso_caixa10_zero.$pagamento_parcial;

	//$linha_p = substr($linha_p, 0,240)."\r\n";
	$linha_p = $linha_p."\r\n";
	$s++;
	$sequencial_lote = str_pad($s,5,"0",STR_PAD_LEFT);

	//Registro Detalhe - Segmento Q (Obrigatório - Remessa)
	$registro_detalhe = "Q";
	$cnab11 = " ";
	$tipo_inscricao = ($exrow->tipo==1) ? "2" : "1";	
	$cpf = str_pad(0,15,"0",STR_PAD_LEFT);
	if($exrow->cpf || $exrow->cnpj) {
		$cpf = ($exrow->tipo==1) ? $exrow->cnpj : $exrow->cpf;		
		$cpf = str_replace("-","",$cpf);		
		$cpf = str_replace(".","",$cpf);
		$cpf = substr($cpf,0,15);			
		$cpf = str_pad($cpf,15,"0",STR_PAD_LEFT);
	}
	$numero_inscricao = $cpf;

	$nome = substr($exrow->nome,0,40);
	$nome = str_pad(sanitize($nome),40," ",STR_PAD_RIGHT);
	$endereco = str_pad(sanitize($exrow->endereco." ".$exrow->numero." ".$exrow->complemento),40," ",STR_PAD_RIGHT);
	$endereco = substr($endereco,0,40);
	$endereco = str_pad(sanitize($endereco),40," ",STR_PAD_RIGHT);
	$bairro = substr(sanitize($exrow->bairro),0,15);
	$bairro = str_pad(sanitize($bairro),15," ",STR_PAD_RIGHT);
	$cep = $exrow->cep;		
	$cep = str_replace("-","",$cep);		
	$cep = str_replace(".","",$cep);	
	$cep = substr($cep,0,8);	
	$cep = str_pad($cep,8,"0",STR_PAD_RIGHT);
	$cidade = substr($exrow->cidade,0,15);
	$cidade = str_pad(sanitize($cidade),15," ",STR_PAD_RIGHT);
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
	$s++;
	$sequencial_lote = str_pad($s,5,"0",STR_PAD_LEFT);

	//Registro Detalhe - Segmento R (Opcional - Remessa)
	$tipo_detalhe = "3";
	$registro_detalhe = "R";
	$cnab13 = " ";
	$campo_descontos = str_pad("0",48,"0",STR_PAD_LEFT);
	$codigo_multa = "2";
	$data_multa = exibedataRemessaJuros($exrow->data_vencimento);
	$valor_multa = str_pad("002",15,"0",STR_PAD_LEFT);
	$informacao_pagador = str_pad(" ",10," ",STR_PAD_LEFT);
	$mensagem3 = str_pad(" ",40," ",STR_PAD_LEFT);
	$mensagem4 = str_pad(" ",40," ",STR_PAD_LEFT);
	$usoCaixa50 = str_pad(" ",50," ",STR_PAD_LEFT);
	$cnab14 = str_pad(" ",11," ",STR_PAD_LEFT);

	$linha_r = $banco.$lote_detalhe.$tipo_detalhe.$sequencial_lote.$registro_detalhe.$cnab13.$movimento_remessa.$campo_descontos.$codigo_multa.$data_multa.$valor_multa.$informacao_pagador.$mensagem3.$mensagem4.$usoCaixa50.$cnab14;

	$linha_r = substr($linha_r, 0,240)."\r\n";
	$s++;
	$sequencial_lote = str_pad($s,5,"0",STR_PAD_LEFT);

	//Registro Detalhe - Segmento S (Opcional - Remessa)
	$tipo_detalhe = "3";
	$registro_detalhe = "S";
	$cnab15 = " ";
	$tipo_impressao = "3";
	$informacao5 = str_pad(" ",40," ",STR_PAD_LEFT);
	$informacao6 = str_pad(" ",40," ",STR_PAD_LEFT);
	$informacao7 = str_pad(" ",40," ",STR_PAD_LEFT);
	$informacao8 = str_pad(" ",40," ",STR_PAD_LEFT);
	$uso_caixa11 = str_pad(" ",40," ",STR_PAD_LEFT);
	$cnab16 = str_pad(" ",22," ",STR_PAD_LEFT);

	$linha_s = $banco.$lote_detalhe.$tipo_detalhe.$sequencial_lote.$registro_detalhe.$cnab15.$movimento_remessa.$tipo_impressao.$informacao5.$informacao6.$informacao7.$informacao8.$uso_caixa11.$cnab16;
	
	$linha_s = substr($linha_s, 0,240)."\r\n";
	
	$detalhes .= $linha_p.$linha_q.$linha_r.$linha_s;

endforeach;
unset($exrow);

//TRAILER LOTE
$tipo_trailer_lote = "5";
$valor_titulos = converteMoedaRemessa($valor_total);
$cnab4 = str_pad(" ",9," ",STR_PAD_LEFT);

$quantidade_registro = str_pad($s+2,6,"0",STR_PAD_LEFT);

$quantidade_titulos = str_pad($quantidade_titulos,6,"0",STR_PAD_LEFT);
$valor_titulos = str_pad($valor_titulos,17,"0",STR_PAD_LEFT);
$quantidade_cobranca = str_pad("0",6,"0",STR_PAD_LEFT);
$valor_cobranca = str_pad("0",17,"0",STR_PAD_LEFT);
$quantidade_caucionada = str_pad("0",6,"0",STR_PAD_LEFT);
$valor_caucionada = str_pad("0",17,"0",STR_PAD_LEFT);
$quantidade_descontada = str_pad("0",6,"0",STR_PAD_LEFT);
$valor_descontada = str_pad("0",17,"0",STR_PAD_LEFT);
$cnab17 = str_pad(" ",31," ",STR_PAD_LEFT);
$cnab18 = str_pad(" ",117," ",STR_PAD_LEFT);

$trailer_lote = $banco.$lote_detalhe.$tipo_trailer_lote.$cnab4.$quantidade_registro.$quantidade_titulos.$valor_titulos.$quantidade_cobranca.$valor_cobranca.$quantidade_caucionada.$valor_caucionada.$cnab17.$cnab18;

$trailer_lote = substr($trailer_lote, 0,240)."\r\n";

//TRAILER ARQUIVO
$lote_trailer_arquivo = "9999";
$tipo_trailer_arquivo = "9";
$cnab4 = str_pad(" ",9," ",STR_PAD_LEFT);
$quantidade_lote = str_pad($quantidade_lote,6,"0",STR_PAD_LEFT);
$quantidade_registro = str_pad($s+4,6,"0",STR_PAD_LEFT);
$cnab5 = str_pad(" ",6," ",STR_PAD_LEFT);
$cnab6 = str_pad(" ",205," ",STR_PAD_LEFT);

$trailer_arquivo = $banco.$lote_trailer_arquivo.$tipo_trailer_arquivo.$cnab4.$quantidade_lote.$quantidade_registro.$cnab5.$cnab6;

$trailer_arquivo = substr($trailer_arquivo, 0,240)."\r\n";

$arquivo_remessa = $header_arquivo.$header_lote.$detalhes.$trailer_lote.$trailer_arquivo;

header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="'.date("Ymd").'.REM"');
header('Content-Length: ' . strlen( $arquivo_remessa ) ); 
header('Content-Type: text/plain; charset=windows-1251');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Expires: 0');
$arquivo_remessa = mb_convert_encoding($arquivo_remessa, "windows-1251","utf-8");
echo $arquivo_remessa;
else:
echo lang('MSG_ERRO_REMESSA');
endif;
?>
<?php
define("_VALID_PHP", true);
require_once("init.php");
$retorno_row = $faturamento->getRemessa();

$quantidade_lote = 1;
$valor_total = 0;
$s = 0;
$datahoje = date("dmY");
$dataNomeArquivo = date("Ymd");
$taxa_boleto = 3;

$pathBoletos = './uploads/boletos/';
$zipName = 'Remessa_Boleto_'.$dataNomeArquivo.'.zip';
$pathZip = $pathBoletos.$zipName;

// Deleta todos arquivos dentro da pasta
$files_to_delete = glob($pathBoletos . '*');
foreach($files_to_delete as $file_to_delete){
	if(is_file($file_to_delete)) {
		unlink($file_to_delete);
	}
}

if($retorno_row):
	$arquivos_boletos = array_chunk($retorno_row,48);
	$versao_arquivo = 1;
//HEADER ARQUIVO

	foreach($arquivos_boletos as $arquivo_boletos)
	{
		$quantidade_titulos = count($arquivo_boletos);

		$id_empresa = get('id_empresa');
		$row_empresa = Core::getRowById("empresa", $id_empresa);

		$banco = trim($row_empresa->boleto_codigo_banco);
		$lote_registro_arquivo = "0000";
		$tipo_registro_arquivo = "0";
		$cnab1 = str_pad(" ",9," ",STR_PAD_LEFT);
		$tipo_inscricao = "2";
		$cnpj_empresa_remessa = limparCPF_CNPJ($row_empresa->cnpj);
		$numero_inscricao = str_pad($cnpj_empresa_remessa,14,"0",STR_PAD_LEFT);
		$uso_caixa1 = str_pad(" ",20," ",STR_PAD_LEFT);
		$agencia_conta = str_pad($row_empresa->boleto_agencia,5,"0",STR_PAD_LEFT);
		$agencia_dv = $boleto->modulo_11_sicoob($agencia_conta);
		$agencia_dv = ($agencia_dv=='X') ? '0' : $agencia_dv;
		$cedente = str_pad($row_empresa->boleto_conta,13,"0",STR_PAD_LEFT);
		$agencia_conta_dv = " ";
		$agencia_conta_dv1 = "0";
		$nome_empresa = str_pad(sanitize($row_empresa->razao_social),30," ",STR_PAD_RIGHT);
		$nome_empresa = substr($nome_empresa, 0,30);
		$nome_banco = str_pad("SICOOB",30," ",STR_PAD_RIGHT);
		$cnab2 = str_pad(" ",10," ",STR_PAD_LEFT);
		$codigo_remessa = "1";
		$data_geracao = date("dmY");
		$hora_geracao = date("His");
		$sequencial_arquivo = date("ymd");
		$layout_arquivo = "081";
		$gravacao_arquivo = str_pad("0",5,"0",STR_PAD_LEFT);
		$reservado_banco = str_pad(" ",20," ",STR_PAD_LEFT);
		$reservado_empresa = str_pad(" ",20," ",STR_PAD_LEFT);
		$cnab3 = str_pad(" ",29," ",STR_PAD_LEFT);

		$header_arquivo = $banco.$lote_registro_arquivo.$tipo_registro_arquivo.$cnab1.$tipo_inscricao.$numero_inscricao.$uso_caixa1.$agencia_conta.$agencia_dv.$cedente.$agencia_conta_dv1.$nome_empresa.$nome_banco.$cnab2.$codigo_remessa.$data_geracao.$hora_geracao.$sequencial_arquivo.$layout_arquivo.$gravacao_arquivo.$reservado_banco.$reservado_empresa.$cnab3;

		$header_arquivo = substr($header_arquivo, 0,240)."\r\n";

		//HEADER LOTE
		$lote_header_lote = "0001";
		$tipo_header_lote = "1";
		$tipo_operacao_lote = "R";
		$tipo_servico_lote = "01";
		$cnab4 = "  ";
		$layout_lote = "040";
		$cnab7 = " ";
		$numero_inscricao = str_pad($cnpj_empresa_remessa,15,"0",STR_PAD_LEFT);
		$uso_caixa4 = str_pad("0",14,"0",STR_PAD_LEFT);
		$uso_caixa5 = "0";
		$mensagem1 = str_pad(" ",40," ",STR_PAD_LEFT);
		$mensagem2 = str_pad(" ",40," ",STR_PAD_LEFT);
		$numero_remessa = date("Ymd");
		$data_gravacao = date("dmY");
		$data_credito = str_pad("0",8,"0",STR_PAD_LEFT);
		$cnab9 = str_pad(" ",33," ",STR_PAD_LEFT);

		$header_lote = $banco.$lote_header_lote.$tipo_header_lote.$tipo_operacao_lote.$tipo_servico_lote.$cnab4.$layout_lote.$cnab7.$tipo_inscricao.$numero_inscricao.$uso_caixa1.$agencia_conta.$agencia_dv.$cedente.$agencia_conta_dv.$nome_empresa.$mensagem1.$mensagem2.$numero_remessa.$data_gravacao.$data_credito.$cnab9;

		$header_lote = substr($header_lote, 0,240)."\r\n";

		$detalhes = "";
		$i = 1;
		foreach ($arquivo_boletos as $exrow):
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
			
			$IdDoSeuSistemaAutoIncremento = $exrow->id  + 2200000;
			$NossoNumero = formata_numdoc($IdDoSeuSistemaAutoIncremento,7);
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


			$nosso_numero = $NossoNumero . $Dv;
			$nosso_numero = str_pad($nosso_numero,10,"0",STR_PAD_LEFT); //Identificação do Título
			$nosso_numero .= str_pad($parcela,2,"0",STR_PAD_LEFT); //Parcela
			$nosso_numero .= "01"; //Tipo de Modalidade
			$nosso_numero .= "4"; //Tipo de Formulario
			$nosso_numero .= str_pad(" ",5," ",STR_PAD_LEFT); //Nosso número completo


			$codigo_carteira = "1";
			$cadastro_titulo = "0";
			$tipo_documento = " ";
			$identificacao_emissao = "2";
			$identificacao_entrega = "2";
			$numero_documento = str_pad($numero_documento,15," ",STR_PAD_LEFT);
			$data_vencimento = exibedataRemessa($exrow->data_vencimento);
			$valor_nominal = str_pad($valor_nominal,15,"0",STR_PAD_LEFT);
			$agencia_cobradora = "00000 ";
			$especie_titulo = "02";
			$aceite = "A";
			$data_emissao = $datahoje;
			$codigo_juros = "2";
			$data_juros = exibedataRemessaJuros($exrow->data_vencimento);
			$valor_juros = str_pad("100",15,"0",STR_PAD_LEFT);
			$codigo_desconto = "0";
			$data_desconto = str_pad("0",8,"0",STR_PAD_LEFT);
			$valor_desconto = str_pad("0",15,"0",STR_PAD_LEFT);
			$valor_iof = str_pad("0",15,"0",STR_PAD_LEFT);
			$valor_abatimento = str_pad("0",15,"0",STR_PAD_LEFT);
			$uso_empresa = str_pad(" ",25," ",STR_PAD_LEFT);
			$codigo_protesto = "1";
			$dias_protesto = "07";
			$codigo_baixa = "0";
			$dias_baixa = str_pad(" ",3," ",STR_PAD_LEFT);
			$codigo_moeda = "09";
			$numero_contrato = str_pad("0",10,"0",STR_PAD_LEFT);
			$uso_caixa9 = " ";

			$linha_p = $banco.$lote_detalhe.$tipo_detalhe.$sequencial_lote.$registro_detalhe.$cnab10.$movimento_remessa.$agencia_conta.$agencia_dv.
			$cedente.$agencia_conta_dv.$nosso_numero.$codigo_carteira.$cadastro_titulo.$tipo_documento.$identificacao_emissao.$identificacao_entrega.
			$numero_documento.$data_vencimento.$valor_nominal.$agencia_cobradora.$especie_titulo.$aceite.$data_emissao.$codigo_juros.$data_juros.$valor_juros.$codigo_desconto.$data_desconto.$valor_desconto.$valor_iof.$valor_abatimento.$uso_empresa.$codigo_protesto.$dias_protesto.$codigo_baixa.$dias_baixa.$codigo_moeda.$numero_contrato.$uso_caixa9;
			
			$linha_p = substr($linha_p, 0,240)."\r\n";
			$s++;
			$sequencial_lote = str_pad($s,5,"0",STR_PAD_LEFT);

			//Registro Detalhe - Segmento Q (Obrigatório - Remessa)
			$registro_detalhe = "Q";
			$cnab11 = " ";
			$tipo_inscricao = ($exrow->tipo==1) ? "2" : "1";	
			$cpf = str_pad(0,15,"0",STR_PAD_LEFT);
			if($exrow->cpf_cnpj) {
				$cpf = $exrow->cpf_cnpj;		
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
		$quantidade_registro = str_pad($s,6,"0",STR_PAD_LEFT);
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

		//TRAILER ARQUIVO
		$lote_trailer_arquivo = "9999";
		$tipo_trailer_arquivo = "9";
		$cnab4 = str_pad(" ",9," ",STR_PAD_LEFT);
		$quantidade_lote = str_pad($quantidade_lote,6,"0",STR_PAD_LEFT);
		$quantidade_registro = str_pad($s,6,"0",STR_PAD_LEFT);
		$cnab5 = str_pad("0",6,"0",STR_PAD_LEFT);
		$cnab6 = str_pad(" ",205," ",STR_PAD_LEFT);

		$trailer_arquivo = $banco.$lote_trailer_arquivo.$tipo_trailer_arquivo.$cnab4.$quantidade_lote.$quantidade_registro.$cnab5.$cnab6;

		$trailer_arquivo = substr($trailer_arquivo, 0,240)."\r\n";

		$arquivo_remessa = $header_arquivo.$header_lote.$detalhes.$trailer_lote.$trailer_arquivo;

		$arquivo_remessa = mb_convert_encoding($arquivo_remessa, "windows-1251","utf-8");

		$BoletoFileName = $pathBoletos.date("Ymd").'arq_'.$versao_arquivo.'.REM';
        file_put_contents($BoletoFileName,$arquivo_remessa);
		$versao_arquivo++;
	}

	$rootPath = realpath($pathBoletos);

    // ZIP
    $zip = new ZipArchive();
    $zip->open($pathZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    $filesToDelete = array();

    //@var SplFileInfo[] $files
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);
            $zip->addFile($filePath, $relativePath);
            $filesToDelete[] = $filePath;
        }
    }

    $zip->close();

    foreach ($filesToDelete as $file) {
        unlink($file);
    }

    //cancelar block da tela aqui
    //echo '<script> $("#overlay").css("display", "none"); </script>';
        
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename=' . $zipName);
    header('Pragma: no-cache');
    readfile($pathZip);

else:
	echo lang('MSG_ERRO_REMESSA');
endif;
?>
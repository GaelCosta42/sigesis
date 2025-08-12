<?php
  define('_VALID_PHP', true);
  require_once("impressao_fatura.php");
  if (isset($_GET['id'])) {  
	$id_nota = get('id');
	$detalhes = $faturamento->getDetalhesNota($id_nota);
	$tipo_pagamento = $faturamento->getReceitasNota($id_nota);
	if($detalhes and $detalhes->modelo == 3) {
		$row_cadatro = Core::getRowById("cadastro", $detalhes->id_cadastro);
		$fatura = new Impressao_Fatura();
		  /* Header Settings */
		  $fatura->setLogo("tcpdf/img/logo.png");
		  $fatura->setColor("#027aa7");
		  $fatura->setType(lang('FATURA'));
		  $fatura->setReference($id_nota." / ".$detalhes->ano);
		  $fatura->setDate(exibedata($detalhes->data_emissao));
		  $fatura->setFrom(array("",$detalhes->nome, formatar_cpf_cnpj($detalhes->cnpj),$detalhes->endereco.", ".$detalhes->numero,$detalhes->bairro.", ".$detalhes->cidade));
		  $fatura->setTo(array("",$row_cadatro->nome, formatar_cpf_cnpj($row_cadatro->cpf_cnpj),$row_cadatro->endereco.", ".$row_cadatro->numero,$row_cadatro->bairro.", ".$row_cadatro->cidade));
		  /* Adding Items in table */
		  $fatura->addItem("",trim($detalhes->descriminacao),1,false,$detalhes->valor_nota,false,$detalhes->valor_nota);
		  /* Add totals */
		  $fatura->addTotal("Total fatura",$detalhes->valor_nota, true);
		  /* Set badge */ 
		  // $fatura->addBadge("Payment Paid");
		  /* Add title */
		  if ($detalhes->inf_adicionais) {
			  $fatura->addTitle("Informacoes adicionais");
			  $fatura->addParagraph($detalhes->inf_adicionais);
		  }

		  if ($tipo_pagamento) {
		  	$fatura->addTitle("INFORMAÇÕES SOBRE PAGAMENTO");
			foreach($tipo_pagamento AS $pagamento) {
				$texto = 'Pagamento: '.$pagamento->pagamento.' - Vencimento: '.exibedata($pagamento->data_pagamento).' - Valor: '.moedap($pagamento->valor_pago);
				$fatura->addParagraph($texto);
			}
		  }

		  //$fatura->addTitle("INFORMAÇÕES AO FISCO E AO CONTRIBUINTE");
		  /* Add Paragraph */
		  //$fatura->addParagraph(lang('FATURA_MSG'));

		  /*
		  if ($detalhes->valor_ir>0 || $detalhes->valor_pis>0 || $detalhes->valor_confins>0 || $detalhes->valor_csll>0 || $detalhes->valor_inss>0) {
		  	$info_tributos = "IR(R$)                                  PIS(R$)                                  CONFINS(R$)                                         CSLL(R$)                                  INSS(R$)<br>";
		  	$info_tributos .= moeda($detalhes->valor_ir)."                           ".moeda($detalhes->valor_pis)."                                ".moeda($detalhes->valor_cofins)."                                                   ";
			$info_tributos .= moeda($detalhes->valor_csll)."                                   ".moeda($detalhes->valor_inss);
			$fatura->addParagraph($info_tributos);
		  }
		*/

		  /* Set footer note */
		  $fatura->setFooternote('SIGESIS - Impressao: '.date('d/m/Y H:i:s'),0,0,'L');
		  /* Render */
		  $fatura->render('fatura_'.$id_nota.'.pdf','I'); /* I => Display on browser, D => Force Download, F => local path save, S => return document path */
		} else {
			echo lang('MSG_ERRO_FATURA');
		}
	} else {
		echo lang('MSG_ERRO_NOTAFISCAL');
	}
?>
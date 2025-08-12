<?php
  define('_VALID_PHP', true);
  require_once("impressao_carta.php");
  if (isset($_GET['id_cliente'])) {
	$total = 0;
	$juros = 0;
	$multa = 0;
	$id_cliente = get('id_cliente');  
	$opcao = 0;
	$row_empresa = Core::getRowById("empresa", 1);
	$retorno_row = $cadastro->getClienteCrediario($id_cliente, $opcao);	
	$valor_pagar = 0;
	if($retorno_row) {
		$row_cliente = Core::getRowById("cadastro", $id_cliente);
		$data_crediario = exibedata($row_cliente->data_crediario);
		$dias = contarDias($data_crediario);
		$carta = new Impressao_Carta();
		  /* Header Settings */
		  $carta->setColor("#027aa7");
		  $carta->setType(dataextenso());
		  $carta->setReference(date('d/m/Y'));
		  $carta->setDate($data_crediario);
		  $carta->setTime(" ".$dias);
		  $carta->setFrom(array("",$row_empresa->nome, ($row_empresa->cnpj),$row_empresa->endereco.", ".$row_empresa->numero,$row_empresa->bairro.", ".$row_empresa->cidade));
		  $carta->setTo(array("",$row_cliente->nome, formatar_cpf_cnpj($row_cliente->cpf_cnpj),$row_cliente->endereco.", ".$row_cliente->numero,$row_cliente->bairro.", ".$row_cliente->cidade));		  
		  /* Add title */
		  $carta->addTitle(lang('INFORMACOES_CLIENTE'));
		  /* Add Paragraph */
		  $carta->addParagraph(lang('MSG_CARTA1'));
		  $carta->addParagraph(lang('MSG_CARTA2'));
		  $carta->addParagraph(lang('MSG_CARTA3'));
		  /* Adding Items in table */
		  foreach ($retorno_row as $exrow) {
			if (($exrow->valor-$exrow->valor_pago)>0) {
				$valor = $exrow->valor-$exrow->valor_pago;
				$valor_pagar += $valor;
				$total += $exrow->valor;
				$juros += $exrow->juros;
				$multa += $exrow->multa;
				$carta->addItem("",exibedataHora($exrow->data_operacao),$exrow->id_venda,false,false,moeda($valor),false);
			}
		  }
		  $credito = floatval($row_cliente->crediario)-$valor_pagar;
		  if($credito > 0) {			  
			  $carta->addTotal("Crédito",moeda($credito), true);
		  }
		  if ($multa>0){
			$carta->addTotal("Multa por atraso",moeda($multa), true);
		  }
		  if ($juros>0){
			$carta->addTotal("Juros por atraso",moeda($juros), true);
		  }
		  $carta->addTotal("Valor a pagar",moeda($valor_pagar), true);
		  /* Set badge */ 
		  // $carta->addBadge("Payment Paid");
		  /* Set footer note */
		  $carta->setFooternote(utf8_decode('SIGESIS - Impressão: ').date('d/m/Y H:i:s'),0,0,'L');
		  /* Render */
		  $carta->render('carta_'.$id_cliente.'.pdf','I'); /* I => Display on browser, D => Force Download, F => local path save, S => return document path */
		} else {
			echo lang('MSG_ERRO_VENDA_SELECIONADA');
		}
	} else {
		echo lang('MSG_ERRO_CLIENTE_SELECIONADO');
	}
?>
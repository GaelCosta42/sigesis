<?php
define('_VALID_PHP', true);
require_once("../impressao_fatura.php");
	$cc = get('cc'); 
	$id = getValue("id", "cotacao", "codigo='".$cc."'");
	$row = Core::getRowById("cotacao", $id);	
	
	if($row->id_status < 5 or $row->id_status == 7)
		header("Location: ./_aviso");
	
		$co = get('co'); 
		$id_fornecedor = getValue("id", "fornecedor", "codigo='".$co."'");
		$row_fornecedor = Core::getRowById("fornecedor", $id_fornecedor);
		$prazo_entrega = $cotacao->getPrazoEntrega($id, $row_fornecedor->cod_fornecedor);
		$lo = get('lo'); 
		$id_loja = getValue("id", "loja", "cod_loja='".$lo."'");
		$row_loja = Core::getRowById("loja", $id_loja);
		$frete = $cotacao->getValorFrete($id, $row_fornecedor->cod_fornecedor);
		$frete = ($frete) ? $frete/100 : 0;
	
		$fatura = new Impressao_Fatura();
		$fatura->setLogo("../assets/img/header.jpg");
		$fatura->setColor("#0f75bc");
		$fatura->setCampo1($id);
		$fatura->setCampo2(exibedata($row->data_abertura));
		$fatura->setCampo3(exibedata($prazo_entrega));
		if($frete)
			$fatura->setCampo4(porcentagem($frete));
		$fatura->setOrigem(array("",$row_loja->razao_social,"CNPJ.: ".formatar_cpf_cnpj($row_loja->cnpj)."      I.E.: ".$row_loja->ie,$row_loja->endereco,$row_loja->bairro.", ".$row_loja->cidade));
		$fatura->setDestino(array("",$row_fornecedor->razao_social,"CNPJ.: ".formatar_cpf_cnpj($row_fornecedor->cpf_cnpj)."      I.E.: ".$row_fornecedor->ie,$row_fornecedor->endereco,$row_fornecedor->bairro.", ".$row_fornecedor->cidade));
		$vl_total = 0;
		$retorno_row = $cotacao->getItensFornecedor($id, $row_fornecedor->cod_fornecedor, $lo);
		if($retorno_row) {
			foreach ($retorno_row as $exrow) {
				$valor = $exrow->valor_unitario;
				$valor_frete = $valor*$frete;
				$vl_total += $total = $exrow->quantidade_pedido*($valor+$valor_frete);
				$valor_frete = ($frete) ? $valor_frete :  false;
				$fatura->addItem("",$exrow->cod_produto." | ".$exrow->produto,$exrow->codigo_barras,decimal($exrow->quantidade_pedido)." ".$exrow->unidade_compra,$exrow->valor_unitario,$valor_frete,$total);
			}
			unset($exrow);
			$fatura->addTotal(lang('TOTAL'),$vl_total, true);
		}
		// $fatura->addBadge("Payment Paid");
		$fatura->addAviso("Informacoes para recebimento dos pedidos");
		$fatura->addTexto(lang('PEDIDO_RECEBIMENTO'));
		$fatura->setFooternote('Divulgacao Online - Impressao: '.date('d/m/Y H:i:s'),0,0,'L');
		$fatura->render('fornecedor_'.$id.'.pdf','I'); /* I => Display on browser, D => Force Download, F => local path save, S => return document path */
?>
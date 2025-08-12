<?php
define('_VALID_PHP', true);
require_once("../impressao_fatura.php");
	$id = get('id'); 
	$cod_fornecedor = get('cod_fornecedor'); 
	$row = Core::getRowById("cotacao", $id);	
	
	if($row->id_status < 5 or $row->id_status == 7)
		header("Location: ./_aviso");
	
		$id_fornecedor = getValue("id", "fornecedor", "cod_fornecedor='".$cod_fornecedor."'");
		$row_fornecedor = Core::getRowById("fornecedor", $id_fornecedor);
		$prazo_entrega = $cotacao->getPrazoEntrega($id, $row_fornecedor->cod_fornecedor);
	
		$fatura = new Impressao_Fatura();
		$fatura->setLogo("../assets/img/header.jpg");
		$fatura->setColor("#0f75bc");
		$fatura->setCampo1($id);
		$fatura->setCampo2(exibedata($row->data_abertura));
		$fatura->setCampo3(exibedata($prazo_entrega));
		$fatura->setOrigem(array("","TODAS AS LOJAS", "","",""));
		$fatura->setDestino(array("",$row_fornecedor->razao_social,"CNPJ.: ".formatar_cpf_cnpj($row_fornecedor->cpf_cnpj)."      I.E.: ".$row_fornecedor->ie,$row_fornecedor->endereco,$row_fornecedor->bairro.", ".$row_fornecedor->cidade));
		$vl_total = 0;
		$retorno_row = $cotacao->getItensFornecedorTodos($id, $row_fornecedor->cod_fornecedor);
		if($retorno_row) {
			foreach ($retorno_row as $exrow) {
				$vl_total += $total = $exrow->quantidade_cotacao*$exrow->valor_unitario;
				$fatura->addItem("",$exrow->cod_produto." | ".$exrow->produto,$exrow->codigo_barras,decimal($exrow->quantidade_cotacao)." ".$exrow->unidade_compra,$exrow->valor_unitario,false,$total);
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
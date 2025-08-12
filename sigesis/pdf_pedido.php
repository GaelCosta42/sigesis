<?php
  /**
   * Imprimir Plano de contas
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
   
   //TOTAL DO CABECALHO DEVE SER = 277 PARA PAGINA Landscape
   //TOTAL DO CABECALHO DEVE SER = 190 PARA PAGINA Retrait
	
	require_once("impressao.php");
	require_once("init.php");
	
	if (!$usuario->is_Todos())
	  redirect_to("login.php");
  
	if (isset($_GET['id'])) { 	
		$id_pedido = get('id');
		$row = Core::getRowById("pedido", $id_pedido);
		$titulo = utf8_decode(lang('CODIGO_PREMIALY').": ".$row->cod_pedido);	
		$inicio = 35;
		
		$nome_empresa = utf8_decode($core->empresa);
	  
		$pdf = new PDF_Impressao( 'P', 'mm', 'A4' );		
		$pdf->setCabecalho();
		$pdf->setRodape();
		$pdf->AddPage();
		$pdf->addEmpresa($titulo, utf8_decode(getValue("loja", "loja", "id_custo='".$row->id_custo."'")), exibedata($row->data_pedido));
		
		$retorno_row = $cotacao->getPedidosItens($id_pedido);
		$departamento = "";
		if($retorno_row) {
			foreach ($retorno_row as $exrow) {
				if(!$exrow->inativo) {
					if($exrow->departamento){
						if($exrow->departamento != $departamento) {
							$pdf->Ln(5);
							$departamento = $exrow->departamento;
							$pdf->SetX("20");
							$pdf->SetFont('arial', 'B', 10);
							$pdf->Cell(0, 10, utf8_decode($departamento));
							$pdf->Ln(1);
							$pdf->Cell(0,0,'',1,0,'C');
							$pdf->Ln(5);
						}
						$pdf->SetX("40");
						$pdf->SetFont('arial', '', 9);
						$pdf->Cell(0, 10, utf8_decode($exrow->cod_pedido_item."==> ".$exrow->produto." ==> Quantidade: ".decimal($exrow->quantidade_pedido)));
						$pdf->Ln(4);
					} else {
						$pdf->Ln(5);
						$pdf->SetX("20");
						$pdf->SetFont('arial', 'B', 10);
						$pdf->Cell(0, 10, utf8_decode($exrow->cod_pedido_item."==> ".$exrow->produto." ==> Quantidade: ".decimal($exrow->quantidade_pedido)));
						$pdf->Ln(1);
						$pdf->Cell(0,0,'',1,0,'C');
						$pdf->Ln(5);
					}
				}
			}
			unset($exrow);
		}
		$pdf->Output();
	} else {
		echo lang('MSG_ERRO_COTACAO');
	}
?>
<?php
  /**
   * PDF Romaneio
   *
   */
   
  define("_VALID_PHP", true);
  
	require_once("impressao.php");
	require_once("init.php");
	
	if (!$usuario->is_Todos())
	  redirect_to("login.php");
	  
	$id = get('id');
	$row = Core::getRowById("vendas", $id);
	$id_vendedor = $row->id_vendedor;
	$id_cadastro = $row->id_cadastro;
	$data_venda = $row->data_venda;
	$nome_vendedor = ($id_vendedor == 0) ? lang('CADASTRO_VENDEDOR_SEM') : getValue("nome", "usuario", "id = ".$id_vendedor);
	if($id_cadastro == 0):
		echo lang('MSG_ERRO_CLIENTE_VENDA');
	else:
		$id_empresa = ($row->id_empresa) ? $row->id_empresa : 1;
		$row_empresa = Core::getRowById("empresa", $id_empresa);
		$row_cadastro = Core::getRowById("cadastro", $id_cadastro);
		
		$linha1 = $row_empresa->nome." - ".formatar_cpf_cnpj($row_empresa->cnpj);
		$dados1a_empresa = substr($linha1,0,49);
		$dados1b_empresa = substr($linha1,49,98);
				
		$linha2 = $row_empresa->endereco.", ".$row_empresa->numero.", ".$row_empresa->bairro.", ".$row_empresa->cidade." - ".$row_empresa->estado;
		$dados2a_empresa = substr($linha2,0,49);
		$dados2b_empresa = substr($linha2,49,98);
		
		$linha3 = $row_empresa->telefone;
		$linha3 .= ($row_empresa->celular) ? $row_empresa->celular : "" ;
		$dados3a_empresa = substr($linha3,0,49);
		$dados3b_empresa = substr($linha3,49,98);
		$pdf = new Impressao_PDF();
		$pdf->Titulo(lang('CODIGO').": ".$id);
		$pdf->SubTitulo(lang('DATA').": ".exibedata($row->data_venda));
		$pdf->HeaderEmpresa(false,$dados1a_empresa, $dados2a_empresa, $dados3a_empresa, $dados1b_empresa, $dados2b_empresa, $dados3b_empresa);

		$pdf->SetFont('helvetica', '', 7, '', false);

		$pdf->AddPage();
			
		//ob_start();
			require(BASEPATH . 'pdf_romaneio_producao_html.php');
			$pdf_html = ob_get_contents();
		
		$pdf->writeHTMLSyle($pdf_html);
		ob_end_clean();	
		$pdf->Output();
	endif;

?>
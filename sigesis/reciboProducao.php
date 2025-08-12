<?php
 /**
   * PDF - Recibo
   *
   */
  define('_VALID_PHP', true);
  define('FPDF_FONTPATH', 'fpdf/font/');
  
  require_once("init.php");
  require_once('impressao_recibo.php');
  
  //if (!$usuario->is_Todos())
	  //redirect_to("login.php");
  
  $id = get('id');
  $recibo58 = $core->is_recibo58();
  $row_venda = Core::getRowById("vendas", $id);
  $id_empresa = ($row_venda->id_empresa) ? $row_venda->id_empresa : 1;
  $row_empresa = Core::getRowById("empresa", $id_empresa);
  $data_venda = exibedataHora($row_venda->data_venda);
  $nome_cliente = "";
  $cpfcnpj_cliente = "";
  $origem = "";
  $telefone_cliente = "";

  if($row_venda->id_cadastro) {
	$row = Core::getRowById("cadastro", $row_venda->id_cadastro);
	$nome_cliente = $row->nome;
	$cpfcnpj_cliente = $row->cpf_cnpj;
	$telefone_cliente = $row->telefone ? $row->telefone : $row->celular;
	$origem = ($row->id_origem) ? getValue("origem", "origem", "id=" . $row->id_origem) : "";
  }

  if($row_venda->id_vendedor) {
	$row_usuario = Core::getRowById("usuario", $row_venda->id_vendedor);
	$nome_vendedor = $row_usuario->nome;
  }

  $voucher_crediario = ($row_venda->voucher_crediario > 0) ? $row_venda->voucher_crediario : 0;
  $data_venda = exibedataHora($row_venda->data_venda);
  $atendente = strtoupper(session('nomeusuario'));
  
  $nome_empresa = $row_empresa->nome;
  $razao_empresa = $row_empresa->razao_social;
  $dados1_empresa = $row_empresa->endereco.", ".$row_empresa->numero;
  $dados2_empresa = $row_empresa->bairro." - ".$row_empresa->cidade." - ".$row_empresa->estado;
  $dados3_empresa = $row_empresa->telefone." - ".formatar_cpf_cnpj($row_empresa->cnpj);
  
  $data_impressao = date('d/m/Y H:i:s');

/*
 * construtor da classe, que permite que seja definido o formato da pagina
 * P=Retrato, mm =tipo de medida utilizada no casso milimetros,
 * tipo de folha = 80 x 80 mm
 * tipo de folha = 58 x 80 mm (quando o ID_ORIGEM (configurações) igual a 58)
 */

$formatorecibo = array(80,280);
$pdf = new PDF_Impressao_Recibo('P', 'mm', $formatorecibo);
//Define as margens esquerda, superior e direita.
$pdf->SetMargins(5, 5, 5);
//define a fonte a ser usada, estilo e tamanho
$pdf->SetFont('arial', '', 10);
//define o titulo
$pdf->SetTitle("Recibo");
//assunto
$pdf->SetSubject($nome_empresa);
// posicao vertical no caso -1.. e o limite da margem
$pdf->SetY("-1");

//EXIBINDO DADOS DA EMPRESA
$pdf->SetFont('arial', 'B', 11);
$pdf->SetX("5");
$pdf->Cell(0, 10, ($nome_empresa));
$pdf->Ln(5);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");

$pdf->Cell(0, 10, (substr($razao_empresa,0,38)));
if (strlen($razao_empresa)>38) {
	$pdf->Ln(4);
	$pdf->SetX("5");
	$pdf->Cell(0, 10, (substr($razao_empresa,38,strlen($razao_empresa))));
}

$pdf->Ln(4);
$pdf->SetX("5");
$pdf->Cell(0, 10, (substr($dados1_empresa,0,38)));
if (strlen($dados1_empresa)>38) {
	$pdf->Ln(4);
	$pdf->SetX("5");
	$pdf->Cell(0, 10, (substr($dados1_empresa,38,strlen($dados1_empresa))));
}

$pdf->Ln(4);
$pdf->SetX("5");
$pdf->Cell(0, 10, (substr($dados2_empresa,0,38)));
if (strlen($dados2_empresa)>38) {
	$pdf->Ln(4);
	$pdf->SetX("5");
	$pdf->Cell(0, 10, (substr($dados2_empresa,38,strlen($dados2_empresa))));
}

$pdf->Ln(4);
$pdf->SetX("5");
$pdf->Cell(0, 10, ($dados3_empresa));
$pdf->Ln(2);
$pdf->SetX("5");
$pdf->Cell(0, 10, ("_____________________________________"));

//EXIBINDO DADOS DA VENDA
$pdf->Ln(5);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, mb_convert_encoding(lang('CODIGO').":", 'UTF-8'));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("20");
$pdf->Cell(0, 10, ($id));
$pdf->SetFont('arial', '', 9);
$pdf->SetX("30");
$pdf->Cell(0, 10, (lang('DATA').":"));
$pdf->SetFont('arial', 'B', 9);
$pdf->SetX("42");
$pdf->Cell(0, 10, ($data_venda));
$pdf->Ln(2);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, ("_____________________________________"));

//EXIBINDO DADOS DO CLIENTE
if($row_venda->id_cadastro) {
	$pdf->Ln(5);
	$pdf->SetFont('arial', '', 8);
	$pdf->SetX("5");
	$pdf->Cell(0, 10, (strtoupper(lang('NOME').":")));

	$pdf->SetFont('arial', 'B', 8);
	$pdf->SetX("15");
	$pdf->Cell(0, 10, (substr($nome_cliente,0,33)));
	
	if (strlen($nome_cliente)>33) {
		$pdf->Ln(4);
		$pdf->SetX("15");
		$pdf->Cell(0, 10, (substr($nome_cliente,33,strlen($nome_cliente))));
	}
	
	$pdf->Ln(4);
	if($cpfcnpj_cliente) {
		$pdf->SetFont('arial', '', 8);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, (strtoupper(lang('CPF_CNPJ').":")));
		$pdf->SetX("20");
		$pdf->SetFont('arial', 'B', 8);
		$pdf->Cell(0, 10, ($cpfcnpj_cliente ?? ''));
		$pdf->Ln(4);
	}

	if($telefone_cliente) {
		$pdf->SetFont('arial', '', 8);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, (strtoupper(lang('TELEFONE').":")));
		$pdf->SetX("22");
		$pdf->SetFont('arial', 'B', 8);
		$pdf->Cell(0, 10, ($telefone_cliente ?? ''));
		$pdf->Ln(4);
	}

	if($row_venda->endereco){

		if( $row_venda->endereco){
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (mb_strtoupper(lang('ENDERECO').":")));
			$pdf->SetX("22");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, ($row_venda->endereco ?? ''));
			$pdf->Ln(4);
		}

		if($row_venda->complemento ){
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (strtoupper(lang('COMPLEMENTO').":")));
			$pdf->SetX("29");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, ($row_venda->complemento ?? ''));
			$pdf->Ln(4);
		}

		if($row_venda->bairro ){
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (strtoupper(lang('BAIRRO').":")));
			$pdf->SetX("17");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, ($row_venda->bairro ?? ''));
			$pdf->Ln(4);
		}

		if($row_venda->cidade ) {
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (strtoupper(lang('CIDADE').":")));
			$pdf->SetX("17");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, ($row_venda->cidade ?? ''));
			$pdf->Ln(4);
		}

		if($row_venda->referencia ) {
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (strtoupper(lang('REFERENCIA').":")));
			$pdf->SetX("24");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, ($row_venda->referencia ?? ''));
			$pdf->Ln(4);
		}
	} else {

		if($row->endereco) {
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (mb_strtoupper(lang('ENDERECO').":")));
			$pdf->SetX("22");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, ($row->endereco ?? ''));
			$pdf->Ln(4);
		}
	
		if($row->numero) {
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (mb_strtoupper(lang('NUMERO').":")));
			$pdf->SetX("19");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, ($row->numero ?? ''));
			$pdf->Ln(4);
		}
	
		if($row->complemento) {
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (strtoupper(lang('COMPLEMENTO').":")));
			$pdf->SetX("29");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, ($row->complemento ?? ''));
			$pdf->Ln(4);
		}
	
		if($row->bairro) {
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (strtoupper(lang('BAIRRO').":")));
			$pdf->SetX("17");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, ($row->bairro ?? ''));
			$pdf->Ln(4);
		}
	
		if($row->cidade) {
			$pdf->SetFont('arial', '', 8);
			$pdf->SetX("5");
			$pdf->Cell(0, 10, (strtoupper(lang('CIDADE').":")));
			$pdf->SetX("17");
			$pdf->SetFont('arial', 'B', 8);
			$pdf->Cell(0, 10, ($row->cidade ?? ''));
			$pdf->Ln(4);
		}
	}


	if($origem != "") {
		$pdf->Ln(2);
		$pdf->SetFont('arial', '', 8);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, (strtoupper(lang('ORIGEM').":")));
		$pdf->SetFont('arial', 'B', 8);
		$pdf->SetX("18");
		$pdf->Cell(0, 10, (strtoupper($origem)));
	}
}

if ($row_venda->observacao){
	$pdf->Ln(5);
	$pdf->SetFont('arial', 'B', 8);
	$pdf->SetX("5");
	$pdf->Cell(0, 10, (lang('OBSERVACAO')));
	$pdf->SetFont('arial', '', 7);
	$pdf->Ln(4);
	$pdf->SetX("5");
	$observacao_venda = (strlen($row_venda->observacao)>50) ? substr($row_venda->observacao,0,50) : $row_venda->observacao ;
	$pdf->Cell(0, 10, ($observacao_venda));	
	if (strlen($row_venda->observacao)>50){
		$pdf->SetFont('arial', '', 7);
		$pdf->Ln(4);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, (substr($row_venda->observacao,50,50)));
	}
	if (strlen($row_venda->observacao)>100){
		$pdf->SetFont('arial', '', 7);
		$pdf->Ln(4);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, (substr($row_venda->observacao,100,50)));
	}
	if (strlen($row_venda->observacao)>150){
		$pdf->SetFont('arial', '', 7);
		$pdf->Ln(4);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, (substr($row_venda->observacao,150,50)));
	}
	if (strlen($row_venda->observacao)>200){
		$pdf->SetFont('arial', '', 7);
		$pdf->Ln(4);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, (substr($row_venda->observacao,200,50)));
	}
}

//EXIBINDO DETALHAMENTO DA VENDA
$pdf->Ln(7);
$pdf->SetFont('arial', 'B', 8);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('DETALHAMENTO_ITENS')));
$pdf->SetFont('arial', '', 7);
$pdf->Ln(5);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('ITEM')));
$pdf->SetX("15");
$pdf->Cell(0, 10, (lang('CODIGO')));
$pdf->Cell(0, 10, (lang('QUANTIDADE')), 0, 0, 'R');
$pdf->Ln(3);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('DESCRICAO')));
$pdf->SetX("30");
$pdf->Ln(2);
$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, ("_____________________________________"));
$pdf->SetFont('arial', '', 7);
$pdf->Ln(5);
$contar = 0;
$total = 0;
$retorno_row = $cadastro->getItemVenda($id);
if($retorno_row){
	foreach ($retorno_row as $exrow) {
		$contar++;
		$item = str_pad($contar, 3, '0', STR_PAD_LEFT);	
		$pdf->SetX("5");
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(0, 10, ($item.'       '.$exrow->codigo));
		$pdf->SetX("5");
		$pdf->SetFont('arial', '', 7);
		$pdf->Cell(0, 10, ($exrow->quantidade), 0, 0, 'R');
		$pdf->Ln(4);
		$pdf->SetX("5");
		$pdf->Cell(0, 10, $exrow->produto);
		$pdf->Ln(5);
	}
	$total = round($total,2);
	unset($exrow);
}

$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, ("_____________________________________"));

if($row_venda->id_vendedor) {
	$pdf->Ln(6);
	$pdf->SetFont('arial', '', 7);
	$pdf->SetX("10");
	$pdf->Cell(0, 10, (strtoupper(lang('VENDEDOR').":")));
	$pdf->SetX("50");
	$pdf->SetFont('arial', 'B', 7);
	$pdf->Cell(0, 10, ($nome_vendedor));
	$pdf->Ln(2);
}

$pdf->SetFont('arial', '', 9);
$pdf->SetX("5");
$pdf->Cell(0, 10, ("_____________________________________"));

//EXIBINDO OBSERVAÇÕES
$pdf->Ln(5);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("5");
$pdf->Cell(0, 10, (lang('IMPRESSO_EM').$data_impressao." - ".$atendente));

$pdf->Ln(4);
$pdf->SetFont('arial', 'B', 7);
$pdf->SetX("5");
$pdf->Cell(0, 10, "SIGESIS - Sistemas de Gestão");

$pdf->Ln(5);
$pdf->SetFont('arial', '', 7);
$pdf->SetX("5");
$pdf->Cell(0, 10, "Recibo para produção - Sem valor Fiscal");
 
/*
 * IMPRIMIR A SAIDA DO ARQUIVO
 * nome do arquivo
 * I: envia o arquivo diretamente para o browser,
 * Se o plug-in estiver instalado ele serao usado.
 * mais opcoes no final do artigo ou visite o manual fpdf.
 */
 $pdf->AutoPrint(true); 
 $pdf->Output("recibo_".$id, "I");

?>

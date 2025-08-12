<?php
  /**
   * Imprimir etiquetas
   *
   */
   
	//require_once("impressao_etiquetas.php");
	require('code128.php');
	require_once("init.php");
	
	if (!$usuario->is_Todos())
	  redirect_to("login.php");
  
	if (!$core->modulo_impressao)
		redirect_to("login.php");
	  
	$id_tabela = get('id'); 
	$quant_impressao = get('quant_impressao'); 
	$lista = get('lista'); 
	if($lista) {
		$lista = substr($lista,0, -1);
		$retorno_row = $produto->getTabelaLista($id_tabela, $lista);
	} else {
		$retorno_row = $produto->getTabela($id_tabela);
	}
		
	// (4 colunas) 
	// Variaveis de Tamanho 
	$mesq = "39"; // Margem Esquerda (mm) 
	$mdir = "2"; // Margem Direita (mm) 
	$msup = "0"; // Margem Superior (mm) 
	$leti = "60"; // Largura da Etiqueta (mm) 
	$aeti = "12"; // Altura da Etiqueta (mm)
	$ncol = "1"; // Número de colunas da folha
	$nlin = "1"; // Número de linhas da folha
	$formatoetiqueta = array(100,12);
	$pdf=new PDF_Code128('L','mm',$formatoetiqueta); // Cria um arquivo novo tipo carta, na vertical.
	$pdf->AddPage(); // adiciona a primeira pagina 
	$pdf->SetMargins(2,0,2); // Define as margens do documento 
	$pdf->SetAuthor("SIGESIS - Sistemas"); // Define o autor 
	$pdf->SetFont('arial','',4); // Define a fonte 
	// $pdf->SetDisplayMode('default','continuous'); 
	$coluna = 0; 
	$linha = 0;
		
	//MONTA A ARRAY PARA ETIQUETAS 
	if($retorno_row)
	{
		for($q=0;$q<$quant_impressao;$q++) {
			foreach($retorno_row as $exrow)
			{
				if($exrow->valor_venda > 0) {
					$linha1 = substr($exrow->nome,0,20);
					$linha2 = moedap($exrow->valor_venda);
					
					if($coluna == $ncol) { // Se for a quarta coluna 
						$coluna = 0; // $coluna volta para o valor inicial 
						$linha = $linha + 1; // $linha é igual ela mesma + 1 
					} 

					if($linha == $nlin) { // Se for a última linha da página 
						$pdf->AddPage(); // Adiciona uma nova página 
						$linha = 0; // $linha volta ao seu valor inicial 
					} 

					$posicaoV = $linha*$aeti; 
					$posicaoH = $coluna*$leti; 
					if($coluna == '0') { // Se a coluna for 0 
						$somaH = $mesq; // Soma Horizontal é apenas a margem da esquerda inicial 
					} else { // Senão 
						$somaH = $mesq+$posicaoH; // Soma Horizontal é a margem inicial mais a posiçãoH 
					} 

					if($linha =='0') { // Se a linha for 0 
						$somaV = $msup; // Soma Vertical é apenas a margem superior inicial 
					} else { // Senão 
						$somaV = $msup+$posicaoV; // Soma Vertical é a margem superior inicial mais a posiçãoV 
					} 

					

					if ($exrow->codigobarras) {
						$pdf->Code128($somaH,$somaV+2,$exrow->codigobarras,25,5);
						
						//Print text uder barcode
						$pdf->SetFont('Arial','',6);
						$pdf->Text($somaH,$somaV+10,$exrow->codigobarras);
					}
					
					$pdf->SetFont('arial','',5);
					$pdf->Text($somaH+28,$somaV+4,utf8_decode($linha1));
					
					$pdf->SetFont('arial','B',8);
					$pdf->Text($somaH+28,$somaV+7,utf8_decode($linha2));
					$coluna = $coluna+1;
				}
			}
		}
		unset($exrow);
		ob_clean();
		$pdf->Output("etiquetas_a4.pdf", "I"); 
	} else {
		echo lang('MSG_ERRO_PRODUTO_IMPRESSAO');
	}
?>
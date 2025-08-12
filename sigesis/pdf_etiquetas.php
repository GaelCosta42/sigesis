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
	  
	$id_tabela = get('id'); 
	$posicao = get('posicao'); 
	$quant_impressao = get('quant_impressao'); 
	$lista = get('lista'); 
	if($lista) {
		$lista = substr($lista,0, -1);
		$retorno_row = $produto->getTabelaLista($id_tabela, $lista);
	} else {
		$retorno_row = $produto->getTabela($id_tabela);
	}
		
	// Polifix 27 etiquetas (3 colunoas) 
	// Variaveis de Tamanho 
	$mesq = "5"; // Margem Esquerda (mm) 
	$mdir = "5"; // Margem Direita (mm) 
	$msup = "10"; // Margem Superior (mm) 
	$leti = "70"; // Largura da Etiqueta (mm) 
	$aeti = "33"; // Altura da Etiqueta (mm)
	$ncol = "3"; // Número de colunas da folha
	$nlin = "9"; // Número de linhas da folha
	$pdf=new PDF_Code128('P','mm','A4'); // Cria um arquivo novo tipo carta, na vertical.
	$pdf->AddPage(); // adiciona a primeira pagina 
	$pdf->SetMargins(1,1,7); // Define as margens do documento 
	$pdf->SetAuthor("SIGESIS - Sistemas"); // Define o autor 
	$pdf->SetFont('arial','',8); // Define a fonte 
	//$pdf->SetDisplayMode($zoom,$layout='continuous'); 
	$coluna = 0; 
	$linha = 0;
	
	//POSICIONA A ETIQUETA DE ACORDO COM A POSIÇÃO ESCOLHIDA
	for($p=1;$p<$posicao;$p++) {
		if($coluna == $ncol) { // Se for a terceira coluna 
			$coluna = 0; // $coluna volta para o valor inicial 
			$linha = $linha +1; // $linha é igual ela mesma +1 
		}
		$coluna = $coluna+1; 
	}
	
	//MONTA A ARRAY PARA ETIQUETAS 
	if($retorno_row)
	{
		for($q=0;$q<$quant_impressao;$q++) {
			foreach($retorno_row as $exrow)
			{
				if($exrow->valor_venda > 0) {
					$linha1 = substr($exrow->nome,0,35);
					$linha2 = moedap($exrow->valor_venda);
					$linha3 = substr($exrow->categoria,0,35);
					
					if($coluna == $ncol) { // Se for a terceira coluna 
						$coluna = 0; // $coluna volta para o valor inicial 
						$linha = $linha +1; // $linha é igual ela mesma +1 
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

					$pdf->SetFont('arial','',8);
					$pdf->Text($somaH,$somaV,utf8_decode($linha1));
					$tamanho = strlen($exrow->codigobarras);
					//if($tamanho == 0 or $tamanho > 6) {
					/*	
					if($tamanho == 0 or $tamanho > 13) {
						$pdf->SetFont('arial','B',20);
						$pdf->Text($somaH,$somaV+8,utf8_decode($linha2));
						$pdf->SetFont('arial','',10);
						$pdf->Text($somaH,$somaV+13,utf8_decode($linha3));
						$pdf->SetFont('arial','',10);
						$pdf->Text($somaH,$somaV+18,utf8_decode($exrow->codigobarras));
					} else {
					*/	
						$pdf->SetFont('arial','B',8);
						$pdf->Text($somaH,$somaV+4,utf8_decode($linha2));
						$pdf->SetFont('arial','',8);
						
						//$pdf->EAN13_sem($somaH,$somaV+6,$exrow->codigobarras);
						
						$pdf->Code128($somaH,$somaV+6,$exrow->codigobarras,35,10);
						
						//Print text uder barcode
						$pdf->SetFont('Arial','',8);
						$pdf->Text($somaH,$somaV+19,$exrow->codigobarras);
					//}
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
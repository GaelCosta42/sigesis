<?php
  /**
   * Imprimir etiquetas
   *
   */
   
	require_once("impressao_etiquetas.php");
	require('code128.php');
	require_once("init.php");
	
	if (!$usuario->is_Todos())
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
	$nomeempresa = (!empty($_SESSION['idempresa'])) ? getValue('nome', 'empresa', 'id='.session('idempresa')) : '';
	$nomeempresa = substr($nomeempresa,0,20);
		
	// Polifix 27 etiquetas (2 colunoas) 
	// Variaveis de Tamanho 
	$mesq = "5"; // Margem Esquerda (mm) 
	$mdir = "5"; // Margem Direita (mm) 
	$msup = "5"; // Margem Superior (mm) 
	$leti = "50"; // Largura da Etiqueta (mm) 
	$aeti = "75"; // Altura da Etiqueta (mm)
	$ncol = "2"; // Número de colunas da folha
	$nlin = "1"; // Número de linhas da folha
	$formatoetiqueta = array(100,75);
	
	//$pdf=new Impressao_etiquetas('L','mm',$formatoetiqueta); // Cria um arquivo novo tipo carta, na vertical.
	$pdf=new PDF_Code128('L','mm',$formatoetiqueta); // Cria um arquivo novo tipo carta, na vertical.

	$pdf->AddPage(); // adiciona a primeira pagina 
	$pdf->SetMargins(1,1,7); // Define as margens do documento 
	$pdf->SetAuthor("SIGESIS - Sistemas"); // Define o autor 
	$pdf->SetFont('arial','',6); // Define a fonte 
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
					$linha1a = substr($exrow->nome,0,18);
					$linha1b = substr($exrow->nome,18,18);
					$linha1c = substr($exrow->nome,36,18);
					$linha2 = moedap($exrow->valor_venda);
					$linha3 = $exrow->codigobarras;
					
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

					$pdf->SetFont('arial','B',8);
					$pdf->Text($somaH,$somaV+9,utf8_decode($nomeempresa));
					$pdf->SetFont('arial','',7);
					$pdf->Text($somaH,$somaV+15,utf8_decode($linha1a));
					$pdf->Text($somaH,$somaV+18,utf8_decode($linha1b));
					$pdf->Text($somaH,$somaV+21,utf8_decode($linha1c));
					
					if ($exrow->codigobarras) {
						//$pdf->EAN13_sem($somaH,$somaV+16,$exrow->codigobarras, 10, 0.35);
						$pdf->Code128($somaH,$somaV+22,$exrow->codigobarras,33,10);
						
						//Print text uder barcode
						$pdf->SetFont('Arial','',7);
						$pdf->Text($somaH,$somaV+36,$exrow->codigobarras);
						
						//$pdf->EAN13_sem($somaH,$somaV+48,$exrow->codigobarras, 8, 0.35);
						$pdf->Code128($somaH,$somaV+48,$exrow->codigobarras,33,8);
						
						//Print text uder barcode
						$pdf->SetFont('Arial','',7);
						$pdf->Text($somaH,$somaV+60,$exrow->codigobarras);
					}
					
					$pdf->SetFont('arial','B',8);
					//$pdf->Text($somaH,$somaV+47,utf8_decode($linha2));
					$pdf->Text($somaH,$somaV+64,utf8_decode($linha2));
					$pdf->SetFont('arial','B',7);
					$pdf->Text($somaH,$somaV+67,utf8_decode($exrow->detalhamento));
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
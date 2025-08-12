<?php
  /**
   * Imprimir etiquetas
   *
   */

	define('_VALID_PHP', true);
	define('FPDF_FONTPATH', 'fpdf/font/');

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
		
	// Polifix 27 etiquetas (3 colunoas) 
	// Variaveis de Tamanho 
	$mesq = "1"; // Margem Esquerda (mm) 
	$mdir = "3"; // Margem Direita (mm) 
	$msup = "5"; // Margem Superior (mm) 
	$leti = "50"; // Largura da Etiqueta (mm) 
	$aeti = "30"; // Altura da Etiqueta (mm)
	$ncol = "1"; // Número de colunas da folha
	$nlin = "1"; // Número de linhas da folha
	$formatoetiqueta = array(50,30);
	$pdf=new PDF_Code128('L','mm',$formatoetiqueta); // Cria um arquivo novo tipo carta, na vertical.
	//$pdf=new Impressao_etiquetas('L','mm',$formatoetiqueta); // Cria um arquivo novo tipo carta, na vertical.
	$pdf->AddPage(); // adiciona a primeira pagina
	$pdf->SetMargins(1,1,7); // Define as margens do documento
	$pdf->SetAuthor("Sigesis - Sistemas"); // Define o autor
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
					$linha1 = substr($exrow->nome,0,20);
					$linha4 = substr($exrow->nome,20,20);
					$linha5 = substr($exrow->nome,40,20);

					$linha6 = substr($exrow->detalhamento,0,14);
					$linha7 = substr($exrow->detalhamento,14,14);
					$linha8 = substr($exrow->detalhamento,28,14);

					$linha2 = moedap($exrow->valor_venda);
					$linha3 = $exrow->codigobarras;
					
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

					$pdf->SetFont('arial','',10);
					$pdf->Text($somaH,$somaV+1,utf8_decode($linha1));
					$pdf->Text($somaH,$somaV+4,utf8_decode($linha4));
					$pdf->Text($somaH,$somaV+7,utf8_decode($linha5));

					if ($exrow->codigobarras) {
						//$pdf->EAN13_sem($somaH,$somaV+9,$exrow->codigobarras,10,0.23);
						$pdf->Code128($somaH,$somaV+9,$exrow->codigobarras,22,11);
						
						//Print text uder barcode
						$pdf->SetFont('Arial','',8);
						$pdf->Text($somaH,$somaV+23,$exrow->codigobarras);

					} 

					$pdf->SetFont('arial','B',15);
					$pdf->Text($somaH+23,$somaV+13,utf8_decode($linha2));

					$pdf->SetFont('arial','',8);
					$pdf->Text($somaH+23,$somaV+17,utf8_decode($linha6));
					$pdf->Text($somaH+23,$somaV+20,utf8_decode($linha7));
					$pdf->Text($somaH+23,$somaV+23,utf8_decode($linha8));

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
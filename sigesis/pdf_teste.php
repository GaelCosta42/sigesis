<?php
  /**
   * PDF Teste
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
   
  define("_VALID_PHP", true);
  
	require_once("impressao.php");
	require_once("init.php");
	$pdf = new Impressao_PDF();
	$pdf->SetFont('helvetica', '', 10, '', false);

	$pdf->AddPage();
	$pdf->Text(0,10,utf8_decode('teste'));

	$pdf->Output();

?>
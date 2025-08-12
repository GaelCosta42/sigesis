<?php
 /**
   * PDF - Impressao
   *
   */
   
require_once('tcpdf/tcpdf.php');

class Impressao_PDF extends TCPDF {
	
	public $orientacao = 'P'; //(P=portrait, L=landscape)
	public $unidade = 'mm'; //[pt=point, mm=millimeter, cm=centimeter, in=inch].
	public $margem_header = 5;
	public $margem_header2 = 30;
	public $margem_footer = 17;
	public $margem_top = 27;
	public $margem_bottom = 25;
	public $margem_left = 15;
	public $margem_right = 15;
	public $fonte = 'helvetica';
	public $fonte_tamanho = 10;
	public $fonte_data = 'helvetica';
	public $fonte_data_tamanho = 8;
	public $fonte_mono = 'courier';
	public $image_scale = 1.25;
	public $head_magnification = 1.25;
	public $titulo = '';
	public $subtitulo = '';
	public $subtitulo2 = '';
	public $imagem_logo = 'logo.png';
	public $imagem_header = 'header.png';
	
	function __construct($orientacao = 'P', $largura = 210, $altura = 297)
	{
		parent::__construct($orientacao, $this->unidade, array($largura, $altura), true, 'UTF-8', false);
		
		// set header and footer fonts
		$this->setHeaderFont(Array($this->fonte, '', $this->fonte_tamanho));
		$this->setFooterFont(Array($this->fonte, '', $this->fonte_tamanho));

		// set default monospaced font
		$this->SetDefaultMonospacedFont($this->fonte_mono);

		// set margins
		$this->SetMargins($this->margem_left, $this->margem_top, $this->margem_right);
		$this->SetFooterMargin($this->margem_footer);

		// set auto page breaks
		$this->SetAutoPageBreak(true, $this->margem_footer);

		// set image scale factor
		$this->setImageScale($this->image_scale);
	}	
	
	function writeHTMLSyle($html)
	{
		require('tcpdf/css/style.php');			
		$this->WriteHTML($style.$html);
	}
	
	public function Titulo($titulo) {
		$this->titulo = $titulo;
		$this->SetCreator('SIGESIS - Sistemas');
		$this->SetAuthor('Vale Telecom');
		$this->SetTitle($titulo);
		$this->SetKeywords('sige, sigesis, sistemas, sistemas gestao, telecom');
	}
	
	public function SubTitulo($subtitulo) {
		$this->subtitulo = $subtitulo;
	}
		
	public function HeaderSIGE() {
		$this->SetHeaderMargin($this->margem_header);
		$this->SetHeaderData($this->imagem_logo, 40, $this->titulo, $this->subtitulo);
	}
	
	public function HeaderCabecalho() {
		$this->SetHeaderMargin($this->margem_header2);
		$this->SetHeaderData($this->imagem_header, 180, '', '');
	}
	
	public function HeaderEmpresa($logo = false, $line1a='', $line1b='', $line1c='', $line2a='', $line2b='', $line2c='') {
		$logo = ($logo) ? $logo : $this->imagem_logo;
		$this->SetHeaderMargin($this->margem_header);
		$this->setHeaderEmpresa($logo, 40, $line1a, $line1b, $line1c, $line2a, $line2b, $line2c);
	}
	
	public function HeaderPDF($logo = false) {
		$logo = ($logo) ? $logo : $this->imagem_logo;
		$this->SetHeaderMargin($this->margem_header);
		$this->SetHeaderData($logo, 40, $this->titulo, $this->subtitulo);
	}

	// Colored table
	// Total das colunas: 180
	public function ColoredTable($header,$data) {
		// Colors, line width and bold font
		$this->SetFillColor(210, 210, 210);
		$this->SetTextColor(0, 0, 0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B');
		// Header
		$w = array(40, 35, 60, 45);
		$num_headers = count($header);
		for($i = 0; $i < $num_headers; ++$i) {
			$this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
		}
		$this->Ln();
		// Color and font restoration
		$this->SetFillColor(240, 240, 240);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Data
		$fill = 0;
		foreach($data as $row) {
			$this->Cell($w[0], 6, 'Telecom', 'LR', 0, 'L', $fill, '', 1);
			$this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
			$this->Cell($w[2], 6, number_format($row[2]), 'LR', 0, 'R', $fill);
			$this->Cell($w[3], 6, number_format($row[3]), 'LR', 0, 'R', $fill);
			$this->Ln();
			$fill=!$fill;
		}
		$this->Cell(array_sum($w), 0, '', 'T');
	}
	
	function linha()
	{
		$this->WriteHTML('<br><br>');
	}
}
?>

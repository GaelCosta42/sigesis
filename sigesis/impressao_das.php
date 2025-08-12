<?php
 /**
   * PDF - Impressao
   *
   */
   
  define('FPDF_FONTPATH', 'fpdf/font/');  
  require_once("fpdf/fpdf.php");
  
class PDF_Impressao extends FPDF
{
// private variables
public $cabecalho;
public $rodape;
public $tamanho = 0;
public $tamanho_cabecalho = 0;
var $colunas;
var $pos_coluna;
var $sep_coluna;
var $ret_coluna;
var $format;
var $angle=0;


// Page header
function Header()
{
	if($this->cabecalho) {
		$this->Image('./assets/img/header.jpg',10,10,190);
	}
}

// Page footer
function Footer()
{
	if($this->rodape) {
		// Position at 1.5 cm from bottom
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Page number
		$this->Cell(0,10,'SIGESIS - Sistemas de Gestão - Impressão: '.date('d/m/Y H:i:s'),0,0,'L');
		$this->Cell(0,10,'Página '.$this->PageNo(),0,0,'R');
	}
}

function setCabecalho($c = true)
{
	$this->cabecalho = $c;
}

function setRodape($r = true)
{
	$this->rodape = $r;
}

function tamanho( $tamanho, $tamanho_cabecalho = false )
{
	$this->tamanho = $tamanho;
	$this->tamanho_cabecalho = ($tamanho_cabecalho) ? $tamanho_cabecalho : $tamanho;
}

function novapagina( $y, $tabela_inicio )
{
	if($y > 240) {
		$this->AddPage();
		$y = $tabela_inicio;
	}
	return $y;
}

// private functions
function RoundedRect($x, $y, $w, $h, $r, $style = '')
{
	$k = $this->k;
	$hp = $this->h;
	if($style=='F')
		$op='f';
	elseif($style=='FD' || $style=='DF')
		$op='B';
	else
		$op='S';
	$MyArc = 4/3 * (sqrt(2) - 1);
	$this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
	$xc = $x+$w-$r ;
	$yc = $y+$r;
	$this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

	$this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
	$xc = $x+$w-$r ;
	$yc = $y+$h-$r;
	$this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
	$this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
	$xc = $x+$r ;
	$yc = $y+$h-$r;
	$this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
	$this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
	$xc = $x+$r ;
	$yc = $y+$r;
	$this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
	$this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
	$this->_out($op);
}

function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
{
	$h = $this->h;
	$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
						$x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
}

function Rotate($angle, $x=-1, $y=-1)
{
	if($x==-1)
		$x=$this->x;
	if($y==-1)
		$y=$this->y;
	if($this->angle!=0)
		$this->_out('Q');
	$this->angle=$angle;
	if($angle!=0)
	{
		$angle*=M_PI/180;
		$c=cos($angle);
		$s=sin($angle);
		$cx=$x*$this->k;
		$cy=($this->h-$y)*$this->k;
		$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
	}
}

function _endpage()
{
	if($this->angle!=0)
	{
		$this->angle=0;
		$this->_out('Q');
	}
	parent::_endpage();
}

// public functions
function sizeOfText( $texte, $largeur)
{
	$largeur = ($largeur) ? $largeur : 1;
	$index    = 0;
	$nb_lines = 0;
	$loop     = TRUE;
	while ( $loop )
	{
		$pos = strpos($texte, "\n");
		if (!$pos)
		{
			$loop  = FALSE;
			$linha = $texte;
		}
		else
		{
			$linha  = substr( $texte, $index, $pos);
			$texte = substr( $texte, $pos+1 );
		}
		$length = floor( $this->GetStringWidth( $linha ) );
		$res = 1 + floor( $length / $largeur) ;
		$nb_lines += $res;
	}
	return $nb_lines;
}

// Empresa
function addEmpresa( $nom = " ", $linha1 = " ", $linha2 = " ", $linha3 = " ", $linha4 = " ")
{
	$x1 = 10;
	$y1 = 10;
	//Positionnement en bas
	$this->SetXY( $x1, $y1 );
	$this->SetFont('Arial','B',11);
	$length = $this->GetStringWidth( $nom );
	$this->Cell( $length, 2, $nom);
	$this->SetFont( "Arial", "", 10);	
	$this->SetXY( $x1, $y1 + 5 );
	$length = $this->GetStringWidth( $linha1 );
	$linhas = $this->sizeOfText( $linha1, $length) ;
	$this->Cell( $length, 2, $linha1);
	$this->SetXY( $x1, $y1 + 10 );
	$length = $this->GetStringWidth( $linha2 );
	$linhas = $this->sizeOfText( $linha2, $length) ;
	$this->Cell( $length, 2, $linha2);
	$this->SetXY( $x1, $y1 + 15 );
	$length = $this->GetStringWidth( $linha3 );
	$linhas = $this->sizeOfText( $linha3, $length) ;
	$this->Cell( $length, 2, $linha3);
	$this->SetXY( $x1, $y1 + 20 );
	$length = $this->GetStringWidth( $linha4 );
	$linhas = $this->sizeOfText( $linha4, $length) ;
	$this->Cell( $length, 2, $linha4);
}

// Titlo
function titulo_centro( $texto = ' ' )
{
	$this->SetFont( "Arial", "B", 11);
	$this->Cell(0,0,$texto, 0,0, "C");
}

// Titlo
function titulo( $texto = ' ', $inicio = 6 )
{
    $r1  = $this->w - 80;
    $r2  = $r1 + 68;
    $y1  = $inicio;
    $y2  = $y1 + 2;
    $mid = ($r1 + $r2 ) / 2;
     
    $szfont = 12;
    $loop   = 0;
    
    while ( $loop == 0 )
    {
       $this->SetFont( "Arial", "B", $szfont );
       $sz = $this->GetStringWidth( $texto );
       if ( ($r1+$sz) > $r2 )
          $szfont --;
       else
          $loop ++;
    }

    $this->SetLineWidth(0.1);
    $this->SetFillColor(192);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 2.5, 'DF');
    $this->SetXY( $r1+1, $y1+2);
    $this->Cell($r2-$r1 -1,5, $texto, 0, 0, "C" );
}

// Sub Titulo
function subtitulo($texto1 = ' ', $texto2 = ' ', $inicio = 6)
{
	$this->SetFont( "Arial", "B", 10);
	$r1  = $this->w - 80;
	$r2  = $r1 + 68;
	$y1  = $inicio;
	$y2  = $y1+10;
	$mid = $y1 + (($y2-$y1) / 2);
	$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $r1 + 16 , $y1+1 );
	$this->Cell(40, 4, $texto1, '', '', "C");
	$this->SetFont( "Arial", "", 10);
	$this->SetXY( $r1 + 16 , $y1+5 );
	$this->Cell(40, 5, $texto2, '', '', "C");
}

function addPageNumber( $page )
{
	$r1  = $this->w - 80;
	$r2  = $r1 + 19;
	$y1  = 17;
	$y2  = $y1;
	$mid = $y1 + ($y2 / 2);
	$this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 3.5, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1+3 );
	$this->SetFont( "Arial", "B", 10+$this->tamanho);
	$this->Cell(10,5, "PAGE", 0, 0, "C");
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1 + 9 );
	$this->SetFont( "Arial", "", 10+$this->tamanho);
	$this->Cell(10,5,$page, 0,0, "C");
}
// Defini��o dos retangulos entre as tabelas
// $rt = 1 = TABELA COMPLETA
// $rt = 2 = SOMENTE RETA EM CIMA
	
function definirCols( $tab, $pc = "C", $sp = true, $rt = 1 )
{
	global $colunas;
	global $pos_coluna;
	global $sep_coluna;
	global $ret_coluna;
	$colunas = $tab;
	$pos_coluna = $pc;
	$sep_coluna = $sp;
	$ret_coluna = $rt;
}

function addCols( $inicio, $final, $tab )
{
	global $pos_coluna;
	global $sep_coluna;
	global $ret_coluna;
	$this->SetFont( "Arial", "B", 10+$this->tamanho_cabecalho);	
	$r1  = 10;
	$r2  = $this->w - ($r1 * 2) ;
	$y1  = $inicio;
	$y2  = $final;
	$this->SetXY( $r1, $y1 );
	if($ret_coluna == 2) $this->Cell(0,0,'',1,0,'C');	
	if($ret_coluna == 1) $this->Rect( $r1, $y1, $r2, $y2, "D");
	if($sep_coluna) $this->Line( $r1, $y1+6, $r1+$r2, $y1+6);
	$colX = $r1;
	while ( list( $lib, $pos ) = each ($tab) )
	{
		$this->SetXY( $colX, $y1+2 );
		$this->Cell( $pos, 1, $lib, 0, 0, $pos_coluna);
		$colX += $pos;
		if($ret_coluna == 1) $this->Line( $colX, $y1, $colX, $y1+$y2);
	}
	$this->SetFont( "Arial", "", 10+$this->tamanho_cabecalho);	
}

function addLineFormat( $tab )
{
	global $format, $colunas;
	
	while ( list( $lib, $pos ) = each ($colunas) )
	{
		if ( isset( $tab["$lib"] ) )
			$format[ $lib ] = $tab["$lib"];
	}
}

function addLine( $linha, $tab, $bold = false, $traco = 0 )
{	
	if($bold) {
		$this->SetFont( "Arial", "B", 11+$this->tamanho);
	} else {
		$this->SetFont( "Arial", "", 10+$this->tamanho);
	}
	
	global $colunas, $format;

	$ordonnee     = 10;
	$maxSize      = $linha;

	reset( $colunas );
	while ( list( $lib, $pos ) = each ($colunas) )
	{
		$longCell  = $pos -2;
		$texte     = $tab[ $lib ];
		$length    = $this->GetStringWidth( $texte );
		$tailleTexte = $this->sizeOfText( $texte, $length );
		$formText  = $format[ $lib ];
		$this->SetXY( $ordonnee, $linha-1);
		$this->MultiCell( $longCell, 4 , $texte, 0, $formText);
		if ( $maxSize < ($this->GetY()  ) )
			$maxSize = $this->GetY() ;
		$ordonnee += $pos;
	}
	
	if($traco == 1) $this->Rect( 10, $maxSize, $ordonnee-10, 0, "D");
	return ( $maxSize - $linha );
}

function minititulo($inicio, $titulo )
{
	$length = $this->GetStringWidth( $titulo  );
	$r1  = 10;
	$r2  = $length + 5;
	$y1  = $inicio;
	$y2  = $y1+5;
	$this->SetXY( $r1 , $y1 );
	$this->SetFont( "Arial", "B", 12);
	$this->Cell($r2,4, $titulo);
	$this->SetFont( "Arial", "", 10);	
	return $y2;
}

function destaques($inicio, $titulo, $texto)
{
	$length_t = $this->GetStringWidth( $titulo  );
	$length_x = $this->GetStringWidth(  $texto );
	$r1  = 10;
	$r2  = $length_t + 5;
	$r3  = $length_x + 5;
	$y1  = $inicio;
	$y2  = $y1+5;
	$this->SetXY( $r1 , $y1 );
	$this->SetFont( "Arial", "B", 10);
	$this->Cell($r2,4, $titulo.": ");
	$this->SetFont( "Arial", "", 10);	
	$this->Cell($r3, 4, $texto);
	return $y2;
}

// add a watermark (temporary estimate, DUPLICATA...)
// call this method first
function rascunho( $texto )
{
	$this->SetFont('Arial','B',50);
	$this->SetTextColor(203,203,203);
	$this->Rotate(45,55,190);
	$this->Text(55,190,$texto);
	$this->Rotate(0);
	$this->SetTextColor(0,0,0);
}
function linha($inicio, $texto)
{
	$length_x = $this->GetStringWidth(  $texto );
	$r1  = 10;
	$r2  = $length_x + 5;
	$y1  = $inicio;
	$y2  = $y1+5;
	$this->SetXY( $r1 , $y1 );
	$this->SetFont( "Arial", "", 10);	
	$this->Cell($r2, 4, $texto);
	return $y2;
}

}
?>

<?php

/* PDF Romaneio de vendas */

define("_VALID_PHP", true);

require_once("impressao.php");
require_once("init.php");

if (!$usuario->is_Todos()) {
    redirect_to("login.php");
}

class CustomPDF extends Impressao_PDF
{
    function Header()
    {

        $db = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);

        $idempresa = $_SESSION['idempresa'];
        $dados = [];

        // DADOS DA EMPRESA
        $qry = "SELECT  razao_social,
                        endereco,
                        telefone,
                        celular,
                        numero,
                        bairro,
                        cidade,
                        estado,
                        cep

                FROM    empresa 
                WHERE   id = $idempresa";

        $resultado = $db->query($qry);

        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $dados = array(
                    'razao_social'  => $row['razao_social'] ?? '',
                    'endereco'      => $row['endereco']     ?? '',
                    'telefone'      => $row['telefone']     ?? '',
                    'numero'        => $row['numero']       ?? '',
                    'bairro'        => $row['bairro']       ?? '',
                    'cidade'        => $row['cidade']       ?? '',
                    'estado'        => $row['estado']       ?? '',
                    'celular'       => $row['celular']      ?? '',
                    'cep'           => $row['cep']          ?? ''
                );
            }
        }

        $this->SetY(5);
        $this->SetFont('Times', '', 16);
        $this->Cell(0, 10, $dados['razao_social'], 0, 0, 'L');
        $this->SetFont('Times', '', 12);
        $this->SetY(12);
        $this->Cell(0, 10, $dados['endereco'] . ', ' . $dados['numero'] . ', ' . $dados['bairro'], 0, 0, 'L');
        $this->SetY(18);
        $this->Cell(0, 10, $dados['cep'] . ', ' . $dados['cidade'] . ', ' . $dados['estado'] . '. ' . $dados['telefone'] . ' / ' . $dados['celular'], 0, 0, 'L');
    }

    function Footer()
    {
        $this->SetY(-40);
        $this->SetFont('Times', '', 14);
        $this->Cell(0, 10, 'TERMO DE RESPONSABILIDADE', 0, 0, 'C');
        $this->SetFont('Times', '', 12);
        $this->SetY(-32);
        $this->MultiCell(0, 10, 'Fica o Sr.: .................................................................... responsável pelas informações acima descritas sob pena das sanções legais sobre seus atos.', 0, 'L');
        $this->SetY(-20);
        $this->Cell(0, 10, '____________________________________________________', 0, 0, 'C');
        $this->SetY(-14);
        $this->Cell(0, 10, 'Assinatura', 0, 0, 'C');
    }

    function writeHTMLSyle($html)
    {
        require('tcpdf/css/style.php');
        $this->WriteHTML($style . $html);
    }
}

$pdf = new CustomPDF();

$pdf->SetAutoPageBreak(true, 40);
$pdf->AddPage();
ob_start();

$pdf->SetFont('Times', '', 11);
$pdf->SetY(27);

require(BASEPATH . 'pdf_romaneio_vendas_html.php');
$pdf_html = ob_get_contents();
$pdf->writeHTMLSyle($pdf_html);
$pdf->Ln();
$pdf->Cell(0, 10, '', 0, 0, 'L');
$pdf->Ln();
$pdf->MultiCell(0, 8, ('Pronto: ____________________________________' . '         ' . 'Expedição: ___________________________________'), 0, 'L');
$pdf->Cell(0, 8, 'Nome Motorista: ____________________________', 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(0, 8, 'Assinatura: _________________________________', 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(0, 8, 'Data: ______ / ______ / ____________', 0, 0, 'L');

ob_end_clean();

$pdf->Output();

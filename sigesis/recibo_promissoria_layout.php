<?php
  /**
   * Recibo de Nota Promissoria
   *
   */
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="utf-8"/>
		<title><?php echo lang('PROMISSORIA_TITULO_M'); ?></title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<meta content="Vale Telecom, SIGE, Sistemas de Gestao" name="description"/>
		<meta content="Vale Telecom" name="author"/>

		<link rel="shortcut icon" href="./assets/img/favicon.png">
		<link rel="apple-touch-icon" href="./assets/img//favicon_60x60.png">
		<link rel="apple-touch-icon" sizes="76x76" href="./assets/img//favicon_76x76.png">
		<link rel="apple-touch-icon" sizes="120x120" href="./assets/img//favicon_120x120.png">
		<link rel="apple-touch-icon" sizes="152x152" href="./assets/img//favicon_152x152.png">

		<style>

			@media screen,print {
			/* *** TIPOGRAFIA BASICA *** */
			* {
				font-family: Arial;
				font-size: 12px;
				margin: 0;
				padding: 0;
			}
		
			/*////////////////////////////////////////////////////////////////////////////*/
			/* FORMATAÇÕES PARA IMPRESSÃO DA NOTA PROMISSÓRIA */

			/* PAGINA */

			.fonte-geral {
				font-size: 22px;
				text-align: justify;
				word-spacing: 3px;
				padding: 5px;
			}

			.fonte-geral1 {
				font-size: 20px;
				text-align: justify;
				word-spacing: 1px;
				padding: 5px;
			}

			.assinatura{
				padding-top: 10px;
			}

			.espaco-esquerdo {
				padding-left: 5px;			
			}

			.espaco-direito {
				padding-right: 5px;			
			}

			#container {
				width: 28cm;
				padding-bottom: 12px;
			}

			.corpo-promissoria {
				width: 28cm;
				padding: 15px;
				border-width: medium;
				border-style: solid;
				border-color: #808080;
				border-radius: 12px;
			}

			.tabela-interna {
				padding-top: 5px;
				width: 100%;
			}

			.destaque-valor-sem-cor {
				border-width: thin;
				border-style: solid;
				border-color: #000000;
				border-radius: 8px;
				padding: 1px 5px 1px 5px;
			}

			.destaque-valor-com-cor {
				border-width: thin;
				border-style: solid;
				border-color: #000000;
				border-radius: 8px;
				padding: 1px 5px 1px 5px;
				background: #EEE8AA;
			}

			.sublinhado-pontilhado {
				border-bottom: 2px dotted #000000;
			}

			/* CABECALHO / TITULO */
			.titulo-promissoria {
				font-size: 30px;
				width:7cm;
				color: black;
				font-weight: bold;
				height:40px;
				text-align: right;
			}
			.titulo-imagem {
				width:7cm;
				height: 1.5cm;
			}
			.titulo-imagem img{
				max-width: 7cm;
				max-height: 1.5cm;			
			}
			.titulo-empresa {
				width:7cm;				
				text-align: center;
			}
			.titulo-empresa span{
				font-size: 14px;
				color: black;
				font-weight: bold;
				display: block;
			}

			/* LINHA DA CORTE */
			.cut {
				width: 28cm;
				margin: 0px auto;
				border-bottom: 1px black dashed;
			}
			.cut p {
				margin: 0 0 5px 0;
				padding: 6px;
				font-family: 'Arial Narrow';
				font-size: 9px;
				color: black;
			}

			/* QUEBRA DE PAGINA DE IMPRESSAO */
			.quebra-pagina { 
				page-break-after: right;
			}

			/* FORMATACAO DA MARCA DE PAGO */
			.pago {
				font-size: 150px;
				color: green;
				font-weight: bold;
				transform: rotate(-25deg);
				position: absolute;
				opacity : 0.4;
				margin-left: 230px;
				margin-top: 125px;
			}

		</style>

	</head>

	<body>
		

		<div id="container">

			<div class="corpo-promissoria">

				<?php if ($dados_promissoria["pago"]): ?>
					<span class="pago">P A G O</span>
				<?php endif; ?>
				
				<table class="tabela-interna" border="0" cellspacing="0" cellpadding="0">
					<tbody>
						<tr class="fonte-geral">
							<td class="titulo-imagem"><img src="tcpdf/img/logo.png"></td>
							<td class="titulo-empresa">
								<span><?php echo $dados_promissoria["empresa_fantasia"]; ?></span>							
								<span><?php echo $dados_promissoria["empresa_endereco1"]; ?></span>							
								<span><?php echo $dados_promissoria["empresa_endereco2"]; ?></span>							
							</td>
							<td class="titulo-promissoria"><?php echo lang('PROMISSORIA_TITULO_M'); ?></td>
						</tr>
					</tbody>
				</table>
				<hr>
				<table class="tabela-interna" cellspacing="0" cellpadding="0">
					<tbody>
						<tr class="fonte-geral">
							<td style="width:5%" class="fonte-geral espaco-direito"><?php echo 'Nº '; ?></td>
							<td style="width:15%;text-align:center" class="destaque-valor-com-cor fonte-geral"><?php echo $dados_promissoria["numero"];?></td>
							<td style="width:60%;text-align:center" class="fonte-geral espaco-esquerdo"><?php echo lang('VENCIMENTO').' '.$dados_promissoria["data_extenso_numerico"];?></td>
							<td style="width:20%;text-align:center" class="fonte-geral destaque-valor-com-cor"><?php echo $dados_promissoria["valor"];?></td>
						</tr>
					</tbody>
				</table>

				<table class="tabela-interna" cellspacing="0" cellPadding="0">
					<tbody>
						<tr class="fonte-geral">
							<td style="width:8%"class="fonte-geral"><?php echo 'Ao(s) '; ?></td>
							<td style="width:92%;text-align:center"class="fonte-geral sublinhado-pontilhado"><?php echo $dados_promissoria["data_vencimento_extenso"]; ?></td>
						</tr>	
						<tr>
							<td colspan="2" class="fonte-geral" style="text-align:center"><?php echo  'pagarei por esta única via de '.lang('PROMISSORIA_TITULO_M'); ?></td>
						</tr>
					</tbody>
				</table>	

				<table class="tabela-interna" cellspacing="0" cellPadding="0">
					<tbody>
						<tr class="fonte-geral">
							<td style="width:5%" class="fonte-geral"><?php echo 'a '; ?></td>
							<td style="width:60%;text-align:center" class="fonte-geral sublinhado-pontilhado"><?php echo $dados_promissoria["empresa_nome"]; ?></td>
							<td style="width:35%;text-align:right" class="fonte-geral"><?php echo lang('CPF_CNPJ').': <span class="fonte-geral sublinhado-pontilhado">'.$dados_promissoria["empresa_documento"].'</span>'; ?></td>
						</tr>
					</tbody>
				</table>

				<table class="tabela-interna" cellspacing="0" cellPadding="0">
					<tbody>
						<tr class="fonte-geral">
							<td style="width:33%" class="fonte-geral"><?php echo 'Ou à sua ordem, a quantia de ';?></td>
							<td style="width:67%;text-align:center" class="fonte-geral destaque-valor-com-cor"><?php echo $dados_promissoria["valor_extenso"];?></td>
						</tr>
					</tbody>
				</table>
				
				<table class="tabela-interna" cellspacing="0" cellPadding="0">
					<tbody>
						<tr class="fonte-geral">
							<td style="width:45%" class="fonte-geral"><?php echo 'em moeda corrente deste pais, pagável em '; ?></td>
							<td style="width:55%;text-align:center" class="fonte-geral sublinhado-pontilhado"><?php echo $dados_promissoria["empresa_local"]; ?></td>
						</tr>
					</tbody>
				</table>

				<table class="tabela-interna" cellspacing="0" cellPadding="0">
					<tbody>
						<tr class="fonte-geral">
							<td style="width:12%" class="fonte-geral"><?php echo lang('PROMISSORIA_EMITENTE'); ?></td>
							<td style="width:50%;text-align:center" class="fonte-geral sublinhado-pontilhado"><?php echo $dados_promissoria["cliente_nome"]; ?></td>
							<td style="width:23%;text-align:center" class="fonte-geral"><?php echo lang('PROMISSORIA_DATA_EMISSAO'); ?></td>
							<td style="width:15%;text-align:center" class="fonte-geral sublinhado-pontilhado"><?php echo $dados_promissoria["data_venda"]; ?></td>
						</tr>
					</tbody>
				</table>

				<table class="tabela-interna" cellspacing="0" cellPadding="0">
					<tbody>
						<tr class="fonte-geral1">
							<td class="fonte-geral1"><?php echo lang('CPF_CNPJ').': '; ?></td>
							<td class="fonte-geral1 sublinhado-pontilhado"><?php echo $dados_promissoria["cliente_documento"]; ?></td>
							<td class="fonte-geral1"><?php echo lang('PROMISSORIA_ENDERECO').': '; ?></td>
							<td class="fonte-geral1 sublinhado-pontilhado"><?php echo $dados_promissoria["cliente_endereco"]; ?></td>
						</tr>
					</tbody>
				</table>

				<table class="tabela-interna" cellspacing="0" cellPadding="0">
					<tbody>
						<tr class="fonte-geral1 assinatura">
							<td style="width:20%" class="fonte-geral1 assinatura"><?php echo lang('PROMISSORIA_ASS_EMITENTE'); ?></td>
							<td style="width:80%" class="fonte-geral1 assinatura sublinhado-pontilhado"></td>
						</tr>
					</tbody>
				</table>

			</div>

			<div class="cut">
				<p></p>
			</div>

		</div>

		<?php if (($quebraPagina%3)==0): ?>
			<p class="quebra-pagina"></p>
		<?php endif; ?>

	</body>

</html>
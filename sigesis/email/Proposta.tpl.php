<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8"/>
<title><?php echo $core->empresa." ".lang('PROPOSTA_VER');?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="Vale Telecom Sigesis Sistemas" name="description"/>
<meta content="Vale Telecom" name="author"/>
<!-- Favicons -->
<link rel="shortcut icon" href="./assets/img/favicon.png">
<link rel="apple-touch-icon" href="./assets/img//favicon_60x60.png">
<link rel="apple-touch-icon" sizes="76x76" href="./assets/img//favicon_76x76.png">
<link rel="apple-touch-icon" sizes="120x120" href="./assets/img//favicon_120x120.png">
<link rel="apple-touch-icon" sizes="152x152" href="./assets/img//favicon_152x152.png">

<style type="text/css">
.cabecalho {
color: #ffffff; font-size: 30px; font-weight: 700; font-family: lato, Helvetica, sans-serif;
}
.sub-cabecalho {
	color: #ffffff; font-size: 16px; font-weight: 500; font-family: lato, Helvetica, sans-serif;
}
.titulo {
	color: #1280a9; font-size: 16px; font-weight: 700; font-family: lato, Helvetica, sans-serif;
}
.texto {
	color: #282828; font-size: 12px;line-height: 2; font-weight: 500; font-family: lato, Helvetica, sans-serif; text-align: justify;
}
.titulo2 {
	color: #b0b0b0; font-size: 16px;line-height: 2; font-weight: 700; font-family: lato, Helvetica, sans-serif;
}
.texto2 {
	color: #b0b0b0; font-size: 12px;line-height: 2; font-weight: 400; font-family: lato, Helvetica, sans-serif;
}
.table {
	border: 1px solid #e6e6e6; padding-bottom: 40px; border-radius: 5px; 
}
.table td {
	padding-left: 15px;
}
.table thead {
	background: #fafafa; color: #1280a9; font-size: 14px; font-weight: 700; font-family: 'Open Sans', Helvetica, sans-serif; border: 1px solid #e6e6e6;padding-left: 25px;
}
.table thead td {
	padding-top: 15px; padding-bottom: 15px; padding-right: 15px; padding-left: 15px;
}
.table tbody {
	color: #282828; font-size: 12px; font-weight: 400; font-family: 'Open Sans', Helvetica, sans-serif;
}
.table tbody td {
	padding-top: 15px; padding-right: 15px;padding-left: 15px;
}
.italico {
	font-style: italic; font-size: 13px; font-weight: 700;
}
.valor {
	color: #303f9f;
}
.table-telefonia {
	background: #fafafa; border: 1px solid #ccc; border-radius: 10px; padding: 20px; text-decoration: none;
}
.table-telefonia td {
	color: #b0b0b0; font-size: 14px; line-height: 1.5; font-weight: 300; font-family: lato, Helvetica, sans-serif;
}
.telefonia {
	color: #1280a9; font-weight: 700;
}
.rodape {
	color: #929292; font-size: 14px;font-weight: 400; font-family: lato, Helvetica, sans-serif; 
}
.rodape a{
	text-decoration: none; color: #1280a9;
}

</style>
</head>
<body>
<!-- Section-7 -->
<table class="table_full editable-bg-color bg_color_e6e6e6 editable-bg-image" bgcolor="#e6e6e6" width="100%" align="center"  mc:repeatable="castellab" mc:variant="Header" cellspacing="0" cellpadding="0" border="0">
	<!-- header -->
	<tr>
		<td align="center">
			<a href="#" class="editable-img">
				<img src="<?php echo "http://".$site_sistema."/assets/img/header.jpg";?>" width="600px" style="display:block; line-height:0; font-size:0; border:0;" border="0" alt="logo" />
			</a>
		</td>
	</tr>
	<!-- INICIO DA CABECALHO -->
	<tr>
		<td>
			<!-- container -->
			<table class="table1 editable-bg-color bg_color_1280a9" bgcolor="#1280a9" width="600" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
				<!-- padding-top -->
				<tr><td height="25"></td></tr>
				<tr>
					<td>
						<!-- Inner container -->
						<table class="table1" width="520" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
							<tr>
								<td align="center" class="cabecalho">
									<?php echo lang('PROPOSTA_COMERCIAL');?>
								</td>
							</tr>

							<!-- horizontal gap -->
							<tr><td height="30"></td></tr>
							
							<tr>
								<td>
									<!-- logo -->
									<table width="50%" align="left" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="left" class="sub-cabecalho">
												<?php echo lang('NUMERO').": ".$row_proposta->id;?>
											</td>
										</tr>
										<tr><td height="22"></td></tr>
									</table><!-- END logo -->

									<!-- options -->
									<table width="50%" align="right" border="0" cellspacing="0" cellpadding="0">
										<!-- margin-top -->
										<tr><td height="3"></td></tr>
										<tr>
											<td align="right" class="sub-cabecalho">
												<?php echo $dataenvio;?>
											</td>
										</tr>
									</table><!-- END options -->

								</td>
							</tr>
						</table><!-- END inner container -->
					</td>
				</tr>				
				
				<tr><td height="10"></td></tr>
			</table><!-- END container -->
		</td>
	</tr>
	<!-- INICIO DA CABECALHO -->
	<!-- body -->
	<tr>
		<td>
			<!-- container -->
			<table class="table1 editable-bg-color bg_color_ffffff" bgcolor="#ffffff" width="600" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
				<!-- padding-top -->
				<tr><td height="30"></td></tr>			
				
				<tr>
					<td>
						<!-- inner container -->
						<table class="table1" width="520" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
							
							<!-- INICIO DA TEXO -->							
							<tr>
								<td>
									<!-- logo -->
									<table width="50%" align="left" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="left" class="titulo">
												<?php echo lang('RAZAO_SOCIAL');?>
											</td>
										</tr>							
										<!-- horizontal gap -->
										<tr><td height="5"></td></tr>
										<tr>
											<td align="left" class="texto">
												<?php echo $row_cadastro->razao_social;?>
											</td>
										</tr>
									</table><!-- END logo -->

									<!-- options -->
									<table width="50%" align="left" border="0" cellspacing="0" cellpadding="0">
										<!-- margin-top -->
										<tr>
											<td align="left" class="titulo">
												<?php echo lang('CONTATO');?>
											</td>
										</tr>							
										<!-- horizontal gap -->
										<tr><td height="5"></td></tr>
										<tr>
											<td align="left" class="texto">
												<?php echo $row_cadastro->contato;?>
											</td>
										</tr>
									</table><!-- END options -->

								</td>
							</tr>
							<!-- horizontal gap -->
							<tr><td height="30"></td></tr>
							<!-- FINAL DA TEXO -->			
							
							<!-- INICIO DA TEXO -->							
							<tr>
								<td>
									<!-- logo -->
									<table width="50%" align="left" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="left" class="titulo">
												<?php echo lang('CPF_CNPJ');?>
											</td>
										</tr>							
										<!-- horizontal gap -->
										<tr><td height="5"></td></tr>
										<tr>
											<td align="left" class="texto">
												<?php echo formatar_cpf_cnpj($row_cadastro->cpf_cnpj);?>
											</td>
										</tr>
									</table><!-- END logo -->

									<!-- options -->
									<table width="50%" align="left" border="0" cellspacing="0" cellpadding="0">
										<!-- margin-top -->
										<tr>
											<td align="left" class="titulo">
												<?php echo lang('INSCRICAO');?>
											</td>
										</tr>							
										<!-- horizontal gap -->
										<tr><td height="5"></td></tr>
										<tr>
											<td align="left" class="texto">
												<?php echo $row_cadastro->ie;?>
											</td>
										</tr>
									</table><!-- END options -->

								</td>
							</tr>
							<!-- horizontal gap -->
							<tr><td height="30"></td></tr>
							<!-- FINAL DA TEXO -->		
							
							<!-- INICIO DA TEXO -->	
							<tr>
								<td align="left" class="titulo">
									<?php echo lang('ENDERECO');?>
								</td>
							</tr>							
							<!-- horizontal gap -->
							<tr><td height="5"></td></tr>
							<tr>
								<td align="left" class="texto">
									<?php echo $row_cadastro->endereco.", ".$row_cadastro->numero." - ".$row_cadastro->bairro." - ".$row_cadastro->cidade."/".$row_cadastro->estado." - ".$row_cadastro->cep;?>
								</td>
							</tr>	
							<!-- horizontal gap -->
							<tr><td height="30"></td></tr>
							<!-- FINAL DA TEXO -->	
							
							
							<!-- INICIO DA TEXO -->							
							<tr>
								<td>
									<!-- logo -->
									<table width="50%" align="left" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="left" class="titulo">
												<?php echo lang('TELEFONE');?>
											</td>
										</tr>							
										<!-- horizontal gap -->
										<tr><td height="5"></td></tr>
										<tr>
											<td align="left" class="texto">
												<?php echo $row_cadastro->telefone." | ".$row_cadastro->celular;?>
											</td>
										</tr>
									</table><!-- END logo -->

									<!-- options -->
									<table width="50%" align="left" border="0" cellspacing="0" cellpadding="0">
										<!-- margin-top -->
										<tr>
											<td align="left" class="titulo">
												<?php echo lang('EMAIL');?>
											</td>
										</tr>							
										<!-- horizontal gap -->
										<tr><td height="5"></td></tr>
										<tr>
											<td align="left" class="texto">
												<?php echo $row_cadastro->email;?>
											</td>
										</tr>
									</table><!-- END options -->

								</td>
							</tr>
							<!-- horizontal gap -->
							<tr><td height="30"></td></tr>
							<!-- FINAL DA TEXO -->		
							
							
							
							<!-- INICIO DA TEXO -->							
							<tr>
								<td>
									<!-- logo -->
									<table width="50%" align="left" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="left" class="titulo">
												<?php echo lang('VALIDADE_PROPOSTA');?>
											</td>
										</tr>							
										<!-- horizontal gap -->
										<tr><td height="5"></td></tr>
										<tr>
											<td align="left" class="texto">
												<?php echo $row_proposta->validade;?>
											</td>
										</tr>
									</table><!-- END logo -->

									<!-- options -->
									<table width="50%" align="left" border="0" cellspacing="0" cellpadding="0">
										<!-- margin-top -->
										<tr>
											<td align="left" class="titulo">
												<?php echo lang('PRAZO_ENTREGA');?>
											</td>
										</tr>							
										<!-- horizontal gap -->
										<tr><td height="5"></td></tr>
										<tr>
											<td align="left" class="texto">
												<?php echo $row_proposta->entrega;?>
											</td>
										</tr>
									</table><!-- END options -->

								</td>
							</tr>
							<!-- horizontal gap -->
							<tr><td height="30"></td></tr>
							<!-- FINAL DA TEXO -->		

							<tr>
								<td style="border-left: 3px solid #e6e6e6; padding-left: 20px;">
									<table width="100%">
										<tr>
											<td align="left" class="titulo2">
												<?php echo lang('APRESENTACAO');?>
											</td>
										</tr>
										<tr>
											<td align="left" class="texto2">
												<?php echo $apresentacao;?>
											</td>
										</tr>
									</table>
								</td>
							</tr>

							<!-- horizontal gap -->
							<tr><td height="30"></td></tr>
							<tr>
								<td style="border-left: 3px solid #e6e6e6; padding-left: 20px;">
									<table width="100%">
										<tr>
											<td align="left" class="titulo2">
												<?php echo lang('INVESTIMENTO');?>
											</td>
										</tr>
										<tr>
											<td align="left" class="texto2">
												<?php echo $investimento;?>
											</td>
										</tr>
									</table>
								</td>
							</tr>

							<!-- horizontal gap -->
							<tr><td height="30"></td></tr>
							<tr>
								<td style="border-left: 3px solid #e6e6e6; padding-left: 20px;">
									<table width="100%">
										<tr>
											<td align="left" class="titulo2">
												<?php echo lang('REFERENCIAS');?>
											</td>
										</tr>
										<tr>
											<td align="left" class="texto2">
												<?php echo $referencias;?>
											</td>
										</tr>
									</table>
								</td>
							</tr>

							<!-- horizontal gap -->
							<tr><td height="30"></td></tr>
							
							<!-- INICIO DA TABELA -->
							<tr>
								<td>
									<!-- table  -->
									<table  width="100%" class="table" border="0" cellspacing="0" cellpadding="0" >
										<thead>
											<tr>
												<td><?php echo lang('ITEM');?></td>
												<td><?php echo lang('DESCRICAO_PRODUTOS');?></td>
												<td><?php echo lang('QUANT');?></td>
												<td><?php echo lang('VL_VENDA');?></td>
												<td><?php echo lang('VL_TOTAL');?></td>
											</tr>
										</thead>
										<tbody>
										<?php 
											if($row_produtos):
												$valor_total = 0;
												$item = 0;
												foreach ($row_produtos as $exrow):
													$item++;
													$valor_total += $exrow->valor_total;
													$valor_proposta += $exrow->valor_total;
										?>
											<tr>
												<td><?php echo $item;?></td>
												<td><?php echo $exrow->produto;?></td>
												<td><?php echo $exrow->quantidade;?></td>
												<td><?php echo decimal($exrow->valor_venda);?></td>
												<td><?php echo decimal($exrow->valor_total);?></td>
											</tr>
										<?php 
												endforeach;
												unset($exrow);
										?>
											<tr>
												<td colspan="4"></td>
												<td class="valor italico"><?php echo decimal($valor_total);?></td>
											</tr>
										<?php 
												endif;
										?>
										</tbody>
									</table><!-- END table -->
								</td>
							</tr>
							<!-- FINAL DA TABELA -->
							
							<!-- horizontal gap -->
							<tr><td height="30"></td></tr>
							
							<!-- INICIO DA TABELA -->
							<tr>
								<td>
									<!-- table  -->
									<table  width="100%" class="table" border="0" cellspacing="0" cellpadding="0" >
										<thead>
											<tr>
												<td><?php echo lang('ITEM');?></td>
												<td><?php echo lang('DESCRICAO_SERVICOS');?></td>
												<td><?php echo lang('VALOR');?></td>
											</tr>
										</thead>
										<tbody>
										<?php 
											if($row_servicos):
												$valor_total = 0;
												$item = 0;
												foreach ($row_servicos as $exrow):
													$item++;
													$valor_total += $exrow->valor;
													$valor_proposta += $exrow->valor;
										?>
											<tr>
												<td><?php echo $item;?></td>
												<td><?php echo $exrow->descricao;?></td>
												<td><?php echo decimal($exrow->valor);?></td>
											</tr>
										<?php 
												endforeach;
												unset($exrow);
										?>
											<tr>
												<td colspan="2"></td>
												<td class="valor italico"><?php echo decimal($valor_total);?></td>
											</tr>
										<?php 
												endif;
										?>
										</tbody>
									</table><!-- END table -->
								</td>
							</tr>
							<!-- FINAL DA TABELA -->
							<!-- horizontal gap -->
							<tr><td height="30"></td></tr>
							<?php 
								if($row_telefonia):
									$valor_total = 0;
									foreach ($row_telefonia as $exrow):
										$valor_total += $exrow->valor_integral;
										$valor_proposta += $exrow->valor_integral;
							?>
							
							<!-- INICIO TELEFONIA -->
							<?php if($exrow->plano_telefone):?>
							<tr>
								<td>
									<!-- column-1  -->
									<table class="table1-2" width="126" align="left" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="center">
												<div style="border-style: none !important; display: block; border: 0 !important;" class="editable-img">
													<img src="<?php echo "http://".$site_sistema."/assets/img/email/telefonia.png";?>" style="display:block; line-height:0; font-size:0; border:0;" border="0" alt="" />
												</div>
											</td>
										</tr>
									<!-- margin-bottom -->
									<tr><td height="30"></td></tr>
									</table><!-- END column-1 -->

									<!-- vertical gap -->
									<table class="tablet_hide" width="20" align="left" border="0" cellspacing="0" cellpadding="0">
										<tr><td height="1"></td></tr>
									</table>

									<!-- column-2  -->
									<table class="table-telefonia" width="374" align="right" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="left">
												<span class="telefonia"><?php echo lang('PLANO_TELEFONIA');?></span>
											</td>
										</tr>
										<!-- horizontal gap -->
										<tr><td height="10"></td></tr>

										<tr>
											<td>
												<?php echo $exrow->plano_telefone;?>
											</td>
										</tr>
									</table><!-- END column-2 -->
								</td>
							</tr>
							<!-- margin-bottom -->
							<tr><td height="20"></td></tr>
							<?php endif;?>
							<?php if($exrow->linhas):?>
							<tr>
								<td>
									<!-- column-1  -->
									<table class="table-telefonia" width="374" align="left" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="left">
												<span class="telefonia"><?php echo lang('LINHAS');?></span>
											</td>
										</tr>
										<!-- horizontal gap -->
										<tr><td height="10"></td></tr>

										<tr>
											<td>
												<?php echo $exrow->linhas;?>
											</td>
										</tr>
									</table><!-- END column-1 -->

									<!-- vertical gap -->
									<table class="tablet_hide" width="20" align="left" border="0" cellspacing="0" cellpadding="0">
										<tr><td height="1"></td></tr>
									</table>
									
									<!-- column-2  -->
									<table class="table1-2" width="126" align="right" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="center">
												<div style="border-style: none !important; display: block; border: 0 !important;" class="editable-img">
													<img src="<?php echo "http://".$site_sistema."/assets/img/email/linhas.png";?>" style="display:block; line-height:0; font-size:0; border:0;" border="0" alt="" />
												</div>
											</td>
										</tr>
									</table><!-- END column-2 -->

								</td>
							</tr>
							<!-- FINAL TELEFONIA -->
							
							<!-- horizontal gap -->
							<tr><td height="20"></td></tr>
							<?php endif;?>
							<?php if($exrow->plano_internet):?>
							
							<!-- INICIO TELEFONIA -->
							<tr>
								<td>
									<!-- column-1  -->
									<table class="table1-2" width="126" align="left" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="center">
												<div style="border-style: none !important; display: block; border: 0 !important;" class="editable-img">
													<img src="<?php echo "http://".$site_sistema."/assets/img/email/internet.png";?>" style="display:block; line-height:0; font-size:0; border:0;" border="0" alt="" />
												</div>
											</td>
										</tr>
									<!-- margin-bottom -->
									<tr><td height="30"></td></tr>
									</table><!-- END column-1 -->

									<!-- vertical gap -->
									<table class="tablet_hide" width="20" align="left" border="0" cellspacing="0" cellpadding="0">
										<tr><td height="1"></td></tr>
									</table>

									<!-- column-2  -->
									<table class="table-telefonia" width="374" align="right" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="left">
												<span class="telefonia"><?php echo lang('PLANO_INTERNET');?></span>
											</td>
										</tr>
										<!-- horizontal gap -->
										<tr><td height="10"></td></tr>

										<tr>
											<td>
												<?php echo $exrow->plano_internet;?>
											</td>
										</tr>
									</table><!-- END column-2 -->
								</td>
							</tr>
							<!-- margin-bottom -->
							<tr><td height="20"></td></tr>
							<?php endif;?>
							<?php if($exrow->adicionais):?>
							<tr>
								<td>
									<!-- column-1  -->
									<table class="table-telefonia" width="374" align="left" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="left">
												<span class="telefonia"><?php echo lang('ADICIONAIS');?></span>
											</td>
										</tr>
										<!-- horizontal gap -->
										<tr><td height="10"></td></tr>

										<tr>
											<td>
												<?php echo $exrow->adicionais;?>
											</td>
										</tr>
									</table><!-- END column-1 -->

									<!-- vertical gap -->
									<table class="tablet_hide" width="20" align="left" border="0" cellspacing="0" cellpadding="0">
										<tr><td height="1"></td></tr>
									</table>
									
									<!-- column-2  -->
									<table class="table1-2" width="126" align="right" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="center">
												<div style="border-style: none !important; display: block; border: 0 !important;" class="editable-img">
													<img src="<?php echo "http://".$site_sistema."/assets/img/email/adicionais.png";?>" style="display:block; line-height:0; font-size:0; border:0;" border="0" alt="" />
												</div>
											</td>
										</tr>
									</table><!-- END column-2 -->
								</td>
							</tr>
							<!-- FINAL TELEFONIA -->
							
							<!-- horizontal gap -->
							<tr><td height="20"></td></tr>
							<?php endif;?>
							<?php if($exrow->beneficios):?>
							
							<!-- INICIO TELEFONIA -->
							<tr>
								<td>
									<!-- column-1  -->
									<table class="table1-2" width="126" align="left" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="center">
												<div style="border-style: none !important; display: block; border: 0 !important;" class="editable-img">
													<img src="<?php echo "http://".$site_sistema."/assets/img/email/beneficios.png";?>" style="display:block; line-height:0; font-size:0; border:0;" border="0" alt="" />
												</div>
											</td>
										</tr>
									<!-- margin-bottom -->
									<tr><td height="30"></td></tr>
									</table><!-- END column-1 -->

									<!-- vertical gap -->
									<table class="tablet_hide" width="20" align="left" border="0" cellspacing="0" cellpadding="0">
										<tr><td height="1"></td></tr>
									</table>

									<!-- column-2  -->
									<table class="table-telefonia" width="374" align="right" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="left">
												<span class="telefonia"><?php echo lang('BENEFICIOS');?></span>
											</td>
										</tr>
										<!-- horizontal gap -->
										<tr><td height="10"></td></tr>

										<tr>
											<td>
												<?php echo $exrow->beneficios;?>
											</td>
										</tr>
									</table><!-- END column-2 -->
								</td>
							</tr>
							<!-- margin-bottom -->
							<tr><td height="20"></td></tr>
							<tr>
								<td>
									<!-- column-1  -->
									<table class="table-telefonia" width="374" align="left" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="left">
												<span class="telefonia"><?php echo lang('VALOR_INTEGRAL');?></span>
											</td>
										</tr>
										<tr>
											<td>
												<?php echo moeda($exrow->valor_integral);?>
											</td>
										</tr>
										<!-- horizontal gap -->
										<tr><td height="10"></td></tr>
										<tr>
											<td align="left">
												<span class="telefonia"><?php echo lang('VALOR_DESCONTO');?></span>
											</td>
										</tr>
										<tr>
											<td>
												<?php echo moeda($exrow->valor_desconto);?>
											</td>
										</tr>
										<!-- horizontal gap -->
										<tr><td height="10"></td></tr>
										<tr>
											<td align="left">
												<span class="telefonia"><?php echo lang('TAXA_INSTALACAO');?></span>
											</td>
										</tr>
										<tr>
											<td>
												<?php echo moeda($exrow->taxa_instalacao);?>
											</td>
										</tr>
									</table><!-- END column-1 -->

									<!-- vertical gap -->
									<table class="tablet_hide" width="20" align="left" border="0" cellspacing="0" cellpadding="0">
										<tr><td height="1"></td></tr>
									</table>
									
									<!-- column-2  -->
									<table class="table1-2" width="126" align="right" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="center">
												<div style="border-style: none !important; display: block; border: 0 !important;" class="editable-img">
													<img src="<?php echo "http://".$site_sistema."/assets/img/email/dinheiro.png";?>" style="display:block; line-height:0; font-size:0; border:0;" border="0" alt="" />
												</div>
											</td>
										</tr>
									</table><!-- END column-2 -->

								</td>
							</tr>
							<tr><td height="20"></td></tr>
							<!-- FINAL TELEFONIA -->
							<?php endif;?>
							<?php 
										endforeach;
										unset($exrow);
									endif;
							?>
							
							<!-- INICIO DA TABELA -->
							<tr>
								<td>
									<!-- table  -->
									<table  width="100%" class="table" border="0" cellspacing="0" cellpadding="0" >
										<thead>
											<tr>
												<td><?php echo lang('ITEM');?></td>
												<td><?php echo lang('CONDICAO_PAGAMENTO');?></td>
												<td><?php echo lang('PARCELAS');?></td>
												<td><?php echo lang('VL_PARCELA');?></td>
												<td><?php echo lang('VL_DESCONTO');?></td>
												<td><?php echo lang('VL_TOTAL');?></td>
											</tr>
										</thead>
										<tbody>
										<?php 
											if($row_pagamentos):
												$item = 0;
												foreach ($row_pagamentos as $exrow):
													$item++;
													$valor_total += $exrow->valor_total;
													$valor_proposta += $exrow->valor_total;
										?>
											<tr>
												<td><?php echo $item;?></td>
												<td><?php echo $exrow->condicao;?></td>
												<td><?php echo $exrow->parcelas;?></td>
												<td><?php echo decimal($exrow->valor_parcelas);?></td>
												<td><?php echo decimal($row_proposta->valor_desconto);?></td>
												<td><?php echo decimal($exrow->valor_total);?></td>
											</tr>
										<?php 
												endforeach;
												unset($exrow);
											endif;
										?>
										</tbody>
									</table><!-- END table -->
								</td>
							</tr>
							<!-- horizontal gap -->
							<tr><td height="30"></td></tr>
							<!-- FINAL DA TABELA -->
							
							<?php if($row_proposta->observacao):?>
							<tr>
								<td style="border-left: 3px solid #e6e6e6; padding-left: 20px;">
									<table width="100%">
										<tr>
											<td align="left" class="titulo2">
												<?php echo lang('OBSERVACAO');?>
											</td>
										</tr>
										<tr>
											<td align="left" class="texto2">
												<?php echo $row_proposta->observacao;?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<!-- horizontal gap -->
							<tr><td height="30"></td></tr>
							<?php endif;?>
							
							<!-- INICIO DA TEXO -->							
							<tr>
								<td>
									<!-- logo -->
									<table width="100%" align="left" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="left" class="titulo italico" colspan="2">
												<?php echo $row_responsavel->nome;?>
											</td>
										</tr>						
										<!-- horizontal gap -->
										<tr><td height="5"></td></tr>
										<tr>
											<td align="left" class="texto" width="50%">
												<?php echo $row_responsavel->telefone;?>
											</td>
											<td align="left" class="texto" width="50%">
												<?php echo $row_responsavel->email;?>
											</td>
										</tr>
									</table>

								</td>
							</tr>
							<!-- horizontal gap -->
							<tr><td height="30"></td></tr>
							<!-- FINAL DA TEXO -->	
							
						</table><!-- END inner container -->
					</td>
				</tr>

				<!-- padding-bottom -->
				<tr><td height="30"></td></tr>
			</table><!-- END container -->
		</td>
	</tr>

	<!-- footer -->
	<tr>
		<td>
			<!-- container -->
			<table class="table1" width="600" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
				<!-- padding-top -->
				<tr><td height="40"></td></tr>

				<tr>
					<td>
						<!--  column-1 -->
						<table width="600" align="center" border="0" cellspacing="0" cellpadding="0">

							<tr>
								<td align="center" width="50%">
									<table class="rodape" align="center" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="center">
												<?php echo lang('VALE1');?>
											</td>
										</tr>
										<tr>
											<td align="center">
												<?php echo lang('VALE2');?>
											</td>
										</tr>
										<tr>
											<td align="center">
												<?php echo lang('VALE3');?>
											</td>
										</tr>
										<tr>
											<td align="center">
												<?php echo lang('VALE4');?>
											</td>
										</tr>
									</table>
								</td>
								<td align="center" width="50%">
									<table class="rodape" align="center" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td align="center">
												<?php echo lang('SOLUTIONS1');?>
											</td>
										</tr>
										<tr>
											<td align="center">
												<?php echo lang('SOLUTIONS2');?>
											</td>
										</tr>
										<tr>
											<td align="center">
												<?php echo lang('SOLUTIONS3');?>
											</td>
										</tr>
										<tr>
											<td align="center">
												<?php echo lang('SOLUTIONS4');?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table><!-- END column-1 -->
					</td>
				</tr>
				
				<tr><td height="40"></td></tr>

				<tr>
					<td>
						<!--  column-1 -->
						<table class="table1-2" width="600" align="left" border="0" cellspacing="0" cellpadding="0">

							<tr>
								<td class="rodape" align="left">
									<img src="<?php echo "http://".$site_sistema."/assets/img/divulgacao.png";?>" border="0" alt="" width="30px"/>&nbsp;&nbsp;2017 &copy; Desenvolvido por <a href="http://www.divulgacaoonline.com.br/" target="_blank">Divulgação Online</a>.
								</td>
							</tr>
						</table><!-- END column-1 -->
					</td>
				</tr>

				<!-- padding-bottom -->
				<tr><td height="30"></td></tr>
			</table><!-- END container -->
		</td>
	</tr>
</table><!-- END wrapper -->
</body>
</html>
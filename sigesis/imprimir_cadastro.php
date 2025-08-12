<?php
  /**
   * Imprimir Cadastro
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  define("_VALID_PHP", true);
  
	require_once("init.php");
	if (!$usuario->is_Todos())
	  redirect_to("login.php");
	  
	$id = get('id');
	$row = Core::getRowById("cadastro", $id);
	
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="pt-BR" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="pt-BR" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="pt-BR">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>

<!-- Meta -->
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="description" content="SIGESIS - Sistemas de Gestão - VOCÊ NO CONTROLE DA SUA EMPRESA, em qualquer lugar... a qualquer momento!"/>
<meta name="keywords" content="vale telecom, sistemas web, aplicativos, mobile, aplicativos vale do aço, aplicativos ipatinga, sistemas de gestão, sistemas web ipatinga, sistemas ipatinga, sistemas vale do aço, sites ipatinga, sites vale do aço, SIGE, SIGESIS, TELECOM, sige, sigesis, telecom" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Vale Telecom"/>

<!-- Title -->
<title><?php echo lang('CADASTRO_INFORMACAO')." - ".$row->nome;?></title>

<!-- Favicons -->
<link rel="shortcut icon" href="./assets/img/favicon.png">
<link rel="apple-touch-icon" href="./assets/img//favicon_60x60.png">
<link rel="apple-touch-icon" sizes="76x76" href="./assets/img//favicon_76x76.png">
<link rel="apple-touch-icon" sizes="120x120" href="./assets/img//favicon_120x120.png">
<link rel="apple-touch-icon" sizes="152x152" href="./assets/img//favicon_152x152.png">

<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="./assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="./assets/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
<link href="./assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="./assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="./assets/plugins/select2/select2.css"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="./assets/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css">
<link href="./assets/css/plugins.css" rel="stylesheet" type="text/css">
<link href="./assets/css/layout.css" rel="stylesheet" type="text/css">
<link href="./assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color">
<link href="./assets/css/custom.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" type="text/css" href="./assets/plugins/bootstrap-datepicker/css/datepicker3.css"/>
<link rel="stylesheet" type="text/css" href="./assets/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css"/>
<link rel="stylesheet" type="text/css" href="./assets/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css"/>
<link rel="stylesheet" type="text/css" href="./assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="./assets/plugins/typeahead/typeahead.css">
<!-- END THEME STYLES -->
</head>
<script Language="JavaScript">
	function Imprimir(){
	window.print();
	window.close();
	}
</Script>
<style type="text/css">
    @media print {
      .noprint { display: none; margin: 30px;}
	  .quebra_pagina {page-break-after:always;}
    }
</style>
<body>
<div class="page-container">
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<div class="row">
					<div class="col-md-12">			
						<div class="portlet light">
							<div class='portlet-title'>
								<div class="caption">
									<i class="fa fa-users font-<?php echo $core->primeira_cor;?>"></i>
									<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('CADASTRO_INFORMACAO');?></span>
								</div>
								<div class='actions noprint'>								
									<a class="btn btn-lg <?php echo $core->primeira_cor;?> hidden-print margin-bottom-5" onclick="javascript:Imprimir();">
									<?php echo lang('IMPRIMIR');?>&nbsp;&nbsp;<i class="fa fa-print"></i>
									</a>
								</div>
							</div>
							<div class="portlet-body">
								<table width="100%">
									<tr>
										<td>
											<table class="table table-bordered table-striped table-advance table-condensed">
												<tbody>	
													<tr>
														<th><?php echo lang('EMPRESA');?></th>
														<td><?php echo getValue("nome", "empresa", "id = ". $row->id_empresa);?></td>
													</tr>
													<tr>
														<th><?php echo lang('NOME');?></th>
														<td><?php echo $row->nome;?></td>
													</tr>
													<tr>
														<th><?php echo lang('RAZAO_SOCIAL');?></th>
														<td><?php echo $row->razao_social;?></td>
													</tr>	
													<tr>
														<th><?php echo lang('CONTATO');?></th>
														<td><?php echo $row->contato;?></td>
													</tr>	
													<tr>
														<th><?php echo lang('CPF_CNPJ');?></th>
														<td><?php echo formatar_cpf_cnpj($row->cpf_cnpj);?></td>
													</tr>
													<tr>
														<th><?php echo lang('EMAIL');?></th>
														<td><?php echo $row->email;?></td>
													</tr>
													<tr>
														<th><?php echo lang('EMAIL');?></th>
														<td><?php echo $row->email2;?></td>
													</tr>
													<tr>
														<th><?php echo lang('CEP');?></th>
														<td><?php echo $row->cep;?></td>
													</tr>
													<tr>
														<th><?php echo lang('ENDERECO');?></th>
														<td><?php echo $row->endereco;?></td>
													</tr>
													<tr>
														<th><?php echo lang('NUMERO');?></th>
														<td><?php echo $row->numero;?></td>
													</tr>
													<tr>
														<th><?php echo lang('COMPLEMENTO');?></th>
														<td><?php echo $row->complemento;?></td>
													</tr
													<tr>
														<th><?php echo lang('BAIRRO');?></th>
														<td><?php echo $row->bairro;?></td>
													</tr>
													<tr>
														<th><?php echo lang('CIDADE');?></th>
														<td><?php echo $row->cidade;?></td>
													</tr>
													<tr>
														<th><?php echo lang('ESTADO');?></th>
														<td><?php echo $row->estado;?></td>
													</tr>
													<tr>
														<th><?php echo lang('TELEFONE');?></th>
														<td><?php echo $row->telefone;?></td>
													</tr>
													<tr>
														<th><?php echo lang('TELEFONE');?></th>
														<td><?php echo $row->telefone2;?></td>
													</tr>
													<tr>
														<th><?php echo lang('CELULAR');?></th>
														<td><?php echo $row->celular;?></td>
													</tr>
													<tr>
														<th><?php echo lang('CELULAR');?></th>
														<td><?php echo $row->celular2;?></td>
													</tr>	
													<tr>
														<th><?php echo lang('OBSERVACAO');?></th>
														<td><?php echo $row->observacao;?></td>
													</tr>		
												</tbody>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
			</div>			
			<?php 	
				$retorno_row = $cadastro->getCadastroRetorno($id);
				if($retorno_row):
			?>
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption font-<?php echo $core->primeira_cor;?>">
								<i class="fa fa-phone font-<?php echo $core->primeira_cor;?>">&nbsp;&nbsp;</i><?php echo lang('CONTATO_RETORNO');?>
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-responsive">
								<table class="table table-bordered table-striped table-condensed">
									<thead>
										<tr>
											<th><?php echo lang('STATUS');?></th>
											<th><?php echo lang('OBSERVACAO');?></th>
											<th><?php echo lang('DATA_RETORNO');?></th>
											<th><?php echo lang('USUARIO');?></th>
											<th><?php echo lang('DATA_CONTATO');?></th>
										</tr>
									</thead>
									<tbody>
									<?php 	
											foreach ($retorno_row as $exrow):
												$estilo = '';
												if($exrow->data_retorno == '0000-00-00')
													$estilo = '';
												elseif($exrow->atrasado)
													$estilo = 'class="danger"';
												elseif($exrow->hoje)
													$estilo = 'class="warning"';
												elseif($exrow->agendado)
													$estilo = 'class="info"';
									?>
										<tr <?php echo $estilo;?>>
											<td><?php echo $exrow->status;?></td>
											<td><?php echo $exrow->observacao;?></td>
											<td><?php echo exibedata($exrow->data_retorno);?></td>
											<td><?php echo $exrow->usuario;?></td>
											<td><?php echo exibedataHora($exrow->data);?></td>
										</tr>
									<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php 
					unset($exrow);
				endif;
			?>
		</div>
	</div>
</div>
<div class="quebra_pagina"></div>
<div class="noprint"></div>
</body>
</html>
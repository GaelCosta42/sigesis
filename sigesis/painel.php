<?php
  /**
   * Painel
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe n�o � permitido.');
  if (!$usuario->is_Todos())
	  redirect_to("login.php");
    
$dataini = (get('dataini')) ? get('dataini') : date('d/m/Y', strtotime('-7 days')); 
$datafim = (get('datafim')) ? get('datafim') : date('d/m/Y'); 
?>
<script src="./assets/scripts/highcharts.js" type="text/javascript"></script>
<?php switch(Filter::$acao): case "resultados": ?>
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('#buscar').click(function() {
			var dataini = $("#dataini").val();
			var datafim = $("#datafim").val();
			window.location.href = 'index.php?do=painel&acao=resultados&dataini='+ dataini +'&datafim='+ datafim;
		});
	});
	// ]]>
</script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('GESTAO');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('MENU_PAINEL');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<div class="row">
				<div class="col-md-12">		
					<div class="portlet light ">
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-inline">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">											
											<div class="row">
												<div class="form-group">
													<div class="col-md-12">
														<?php echo lang('SELECIONE_PERIODO');?>:
														&nbsp;&nbsp;
														<input type="text" class="form-control input-medium calendario data" name="dataini" id="dataini" value="<?php echo $dataini;?>" >
														&nbsp;
														<input type="text" class="form-control input-medium calendario data" name="datafim" id="datafim" value="<?php echo $datafim;?>" >
														&nbsp;
														<button type="button" id="buscar" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-search"/></i> <?php echo lang('BUSCAR');?></button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<?php 
						$total_receitas = $gestao->getInfoReceberTotal($dataini, $datafim);
						$total_despesas = $gestao->getInfoDespesasPagarTotal($dataini, $datafim);
						$diferenca = $total_receitas - $total_despesas;
						$cor_diferenca = ($diferenca < 0) ? 'font-red' : 'font-blue-steel';
				?>
				<div class="col-md-6 col-sm-12">
					<div class="portlet blue box">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-usd">&nbsp;&nbsp;</i><?php echo lang('PAINEL_FINANCEIRO_TOTAL');?>
							</div>
						</div>
						<div class="portlet-body">
							<div class="row static-info">
								<div class="col-md-5 name">
									 <?php echo lang('CONTAS_A_RECEBER');?>:
								</div>
								<div class="col-md-7 value">
									 <?php echo moeda($total_receitas);?>
								</div>
							</div>
							<div class="row static-info">
								<div class="col-md-5 name">
									 <?php echo lang('CONTAS_A_PAGAR');?>:
								</div>
								<div class="col-md-7 value">
									 <?php echo moeda($total_despesas);?>
								</div>
							</div>
							<div class="row static-info">
								<div class="col-md-5 name <?php echo $cor_diferenca;?>">
									 <?php echo lang('DIFERENCA');?>:
								</div>
								<div class="col-md-7 value <?php echo $cor_diferenca;?>">
									 <?php echo moeda($diferenca);?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php 
						$periodo_receitas = $gestao->getInfoReceber($dataini, $datafim);
						$periodo_despesas = $gestao->getInfoDespesasPagar($dataini, $datafim);
						$diferenca1 = $periodo_receitas - $periodo_despesas;
						$cor_diferenca1 = ($diferenca1 < 0) ? 'font-red' : 'font-blue-steel';
						$periodo_recebidos = $gestao->getInfoRecebido($dataini, $datafim);
						$periodo_pagas = $gestao->getInfoDespesas($dataini, $datafim);
						$diferenca2 = $periodo_recebidos - $periodo_pagas;
						$cor_diferenca2 = ($diferenca2 < 0) ? 'font-red' : 'font-blue-steel';
				?>
				<div class="col-md-6 col-sm-12">
					<div class="portlet blue-madison box">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-shopping-cart">&nbsp;&nbsp;</i><?php echo lang('PAINEL_FINANCEIRO_PERIODO');?>
							</div>
						</div>
						<div class="portlet-body">
							<div class="row static-info">
								<div class="col-md-5 name">
									 <?php echo lang('CONTAS_A_RECEBER');?>:
								</div>
								<div class="col-md-7 value">
									 <?php echo moeda($periodo_receitas);?>
								</div>
							</div>
							<div class="row static-info">
								<div class="col-md-5 name">
									 <?php echo lang('CONTAS_A_PAGAR');?>:
								</div>
								<div class="col-md-7 value">
									 <?php echo moeda($periodo_despesas);?>
								</div>
							</div>
							<div class="row static-info">
								<div class="col-md-5 name <?php echo $cor_diferenca1;?>">
									 <?php echo lang('DIFERENCA');?>:
								</div>
								<div class="col-md-7 value <?php echo $cor_diferenca1;?>">
									 <?php echo moeda($diferenca1);?>
								</div>
							</div>
							<br/>
							<div class="row static-info">
								<div class="col-md-5 name">
									 <?php echo lang('CONTAS_RECEBIDAS');?>:
								</div>
								<div class="col-md-7 value">
									 <?php echo moeda($periodo_recebidos);?>
								</div>
							</div>
							<div class="row static-info">
								<div class="col-md-5 name">
									 <?php echo lang('CONTAS_PAGAS');?>:
								</div>
								<div class="col-md-7 value">
									 <?php echo moeda($periodo_pagas);?>
								</div>
							</div>
							<div class="row static-info">
								<div class="col-md-5 name <?php echo $cor_diferenca2;?>">
									 <?php echo lang('DIFERENCA');?>:
								</div>
								<div class="col-md-7 value <?php echo $cor_diferenca2;?>">
									 <?php echo moeda($diferenca2);?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</diV>
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php case "crm": ?>
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('#buscar').click(function() {
			var dataini = $("#dataini").val();
			var datafim = $("#datafim").val();
			window.location.href = 'index.php?do=painel&acao=crm&dataini='+ dataini +'&datafim='+ datafim;
		});
	});
	// ]]>
</script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CRM');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PAINEL_CRM');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<div class="row">
				<div class="col-md-12">		
					<div class="portlet light ">
						<div class="portlet-body form">
							<!-- INICIO FORM-->
							<form action="" autocomplete="off" method="post" class="form-inline">
								<div class="form-body">
									<div class="row">
										<div class="col-md-12">											
											<div class="row">
												<div class="form-group">
													<div class="col-md-12">
														<?php echo lang('SELECIONE_PERIODO');?>:
														&nbsp;&nbsp;
														<input type="text" class="form-control input-medium calendario data" name="dataini" id="dataini" value="<?php echo $dataini;?>" >
														&nbsp;
														<input type="text" class="form-control input-medium calendario data" name="datafim" id="datafim" value="<?php echo $datafim;?>" >
														&nbsp;
														<button type="button" id="buscar" class="btn <?php echo $core->primeira_cor;?>"><i class="fa fa-search"/></i> <?php echo lang('BUSCAR');?></button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<div class="portlet green-haze box">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-phone">&nbsp;&nbsp;</i><?php echo lang('CONTATO_RETORNO');?>
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-scrollable table-scrollable-borderless">
								<table class="table table-light">
									<thead>
										<tr>
											<th><?php echo lang('STATUS');?></th>
											<th><?php echo lang('QUANTIDADE');?></th>
										</tr>
									</thead>
									<tbody>
									<?php 											
										$retorno_row = $cadastro->getTotalCadastroRetorno($dataini, $datafim);
										if($retorno_row):
											foreach ($retorno_row as $exrow):
									?>
											<tr>
											<td><?php echo $exrow->status;?></td>
											<td><?php echo $exrow->quantidade;?></td>
										</tr>
									<?php 	endforeach;?>
									<?php 
											unset($exrow);
										endif;
									?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<div class="portlet yellow-casablanca box">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-users">&nbsp;&nbsp;</i><?php echo lang('CONTATO_RETORNO_CONSULTOR');?>
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-scrollable table-scrollable-borderless">
								<table class="table table-light">
									<thead>
										<tr>
											<th><?php echo lang('CONSULTOR');?></th>
											<th><?php echo lang('STATUS');?></th>
											<th><?php echo lang('QUANTIDADE');?></th>
										</tr>
									</thead>
									<tbody>
									<?php 											
										$retorno_row = $cadastro->getConsultorRetorno($dataini, $datafim);
										if($retorno_row):
											foreach ($retorno_row as $exrow):
									?>
											<tr>
											<td><?php echo $exrow->consultor;?></td>
											<td><?php echo $exrow->status;?></td>
											<td><?php echo $exrow->quantidade;?></td>
										</tr>
									<?php 	endforeach;?>
									<?php 
											unset($exrow);
										endif;
									?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<?php 
					$ligacoes = $gestao->getInfoLigacaoes($dataini, $datafim);
					$visitas = $gestao->getInfoVisitas($dataini, $datafim);
					$agendas = $gestao->getInfoAgendas($dataini, $datafim);
				?>
				<div class="col-md-12 col-sm-12">
					<div class="portlet grey-cascade box">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-comments-o">&nbsp;&nbsp;</i><?php echo lang('PAINEL_RELACIONAMENTO');?>
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-scrollable table-scrollable-borderless">
								<table class="table table-light">
									<thead>
										<tr>
											<th><?php echo lang('ACOES');?></th>
											<th><?php echo lang('QUANTIDADE');?></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td> <?php echo lang('LIGACOES_REALIZADAS');?></td>
											<td><?php echo $ligacoes;?></td>
										</tr>
										<tr>
											<td> <?php echo lang('VISITAS_ATENDIDAS');?></td>
											<td><?php echo $visitas;?></td>
										</tr>
										<tr>
											<td> <?php echo lang('AGENDAS_VISITAS');?></td>
											<td><?php echo $agendas;?></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<div class="portlet blue box">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-users">&nbsp;&nbsp;</i><?php echo lang('CONTATO_CADASTRO_CONSULTOR');?>
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-scrollable table-scrollable-borderless">
								<table class="table table-light">
									<thead>
										<tr>
											<th><?php echo lang('CONSULTOR');?></th>
											<th><?php echo lang('QUANTIDADE');?></th>
										</tr>
									</thead>
									<tbody>
									<?php 											
										$retorno_row = $cadastro->getConsultorCadastro($dataini, $datafim);
										if($retorno_row):
											foreach ($retorno_row as $exrow):
									?>
											<tr>
											<td><?php echo $exrow->consultor;?></td>
											<td><?php echo $exrow->quantidade;?></td>
										</tr>
									<?php 	endforeach;?>
									<?php 
											unset($exrow);
										endif;
									?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php case 'mensalconsultor': ?>
<?php 
	$data = (get('data')) ? get('data') : date("m/Y"); 
	$consultor = (get('consultor')) ? get('consultor') : ""; 
?>
<script type="text/javascript"> 
	  
	$(document).ready(function () {
		$('#mes_ano').click(function() {
			var datafiltro = $("#mes_ano").val();
			var consultor = $("#consultor").val();
			window.location.href = 'index.php?do=painel&acao=mensalconsultor&data='+datafiltro + '&consultor='+consultor;
		});
		
		$('#consultor').click(function() {
			var datafiltro = $("#mes_ano").val();
			var consultor = $("#consultor").val();
			window.location.href = 'index.php?do=painel&acao=mensalconsultor&data='+datafiltro + '&consultor='+consultor;
		});
	});
	
</script>
<!-- INICIO BOX MODAL -->
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('CRM');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PAINEL_CONSULTOR');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class='page-content'>
		<div class='container'>
			<!-- INICIO DO ROW TABELA -->
			<div class='row'>
				<div class='col-md-12'>
					<!-- INICIO TABELA -->						
					<div class='portlet light'>
						<div class='portlet-title'>
							<div class='caption'>
								<i class='fa fa-users font-<?php echo $core->primeira_cor;?>'></i>								
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('PAINEL_CONSULTOR');?></span>
							</div>
						</div>
						<?php 
							$retorno_row = $cadastro->getListaMesConsultor();
							$consultor_row = $cadastro->getTodosConsultoresRetorno($data);
						?>	
						<div class="portlet-body form">
							<form class="form-inline">
								<div class="form-group">
									<select class="select2me form-control input-large" name="mes_ano" id="mes_ano" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
										<option value=""></option>
										<?php 
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>				
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $data) echo 'selected="selected"';?>><?php echo exibeMesAno($srow->mes_ano, true, true);?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<select class="select2me form-control input-large" name="consultor" id="consultor" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
										<option value=""></option>
										<?php 
												if ($consultor_row):
													foreach ($consultor_row as $crow):
										?>				
														<option value="<?php echo $crow->consultor;?>" <?php if($crow->consultor == $consultor) echo 'selected="selected"';?>><?php echo $crow->consultor;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
								</div>
							</form>
						</div>
						<div class='portlet-body'>
							<table class='table table-bordered table-striped table-condensed table-advance'>
								<thead>
									<tr>
										<th>#</th>
										<th>#</th>
										<th><?php echo lang('DIA_SEMANA');?></th>
										<th><?php echo lang('LIGACOES_REALIZADAS');?></th>
										<th><?php echo lang('VISITAS_ATENDIDAS');?></th>
										<th><?php echo lang('AGENDAS_PROGRAMADA');?></th>
										<th><?php echo lang('AGENDAS_MARCADA');?></th>
									</tr>
								</thead>
								<tbody>
								<?php 	
										$data_mes = explode("/", $data);
										$totalligacoes = 0;
										$totalvisitas = 0;
										$totalagendas = 0;
										$totalprogramada = 0;
										$totalmatriculas = 0;
										$gdia = "";
										$gligacoes = "";
										$gvisitas = "";
										$gagendas = "";
										$gprogramada = "";
										$gmatriculas = "";
										$ultimo = cal_days_in_month(CAL_GREGORIAN, $data_mes[0], $data_mes[1]);
										for($i=1;$i<=$ultimo;$i++):
											$total = 0;
											if(strlen($gdia) > 0) {
												$gdia .= ",";
												$gligacoes .= ",";
												$gvisitas .= ",";
												$gagendas .= ",";
												$gprogramada .= ",";
												$gmatriculas .= ",";
											}
											$dia = ($i<10) ? "0".$i : $i;
											$gdia .= $dia;
											$diasemana = diasemana($dia."/".$data, true);
											$ligacoes = $gestao->getInfoLigacaoesConsultor($consultor, $dia."/".$data);
											$totalligacoes += $ligacoes;
											$total += $ligacoes;
											$gligacoes .= ($ligacoes) ? $ligacoes : "0";
											$visitas = $gestao->getInfoVisitasConsultor($consultor, $dia."/".$data);
											$totalvisitas += $visitas;
											$total += $visitas;
											$gvisitas .= ($visitas) ? $visitas : "0";
											$agendas = $gestao->getInfoAgendasConsultor($consultor, $dia."/".$data);
											$totalagendas += $agendas;
											$total += $agendas;
											$gagendas .= ($agendas) ? $agendas : "0";
											$programada = $gestao->getInfoAgendasProgramadaConsultor($consultor, $dia."/".$data);
											$totalprogramada += $programada;
											$total += $programada;
											$gprogramada .= ($programada) ? $programada : "0";
											$colaborador = getValue("nome", "usuario", "usuario='".$consultor."'");
								?>
											<tr>
												<td><?php echo $dia;?></td>
												<td>
													<?php if($total):?>
														<a href="javascript:void(0);" class="btn btn-sm grey-cascade" onclick="javascript:void window.open('ver_retorno.php?consultor=<?php echo $consultor;?>&data=<?php echo $dia."/".$data;?>','RETORNO <?php echo $dia."/".$data;?>','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;" title="<?php echo lang('VISUALIZAR');?>"><i class="fa fa-search"></i></a>
													<?php else: echo "-";?>
													<?php endif;?>
												</td>
												<td><?php echo $diasemana;?></td>
												<td><?php echo $ligacoes;?></td>
												<td><?php echo $ligacoes;?></td>
												<td><?php echo $visitas;?></td>
												<td><?php echo $programada;?></td>
												<td><?php echo $agendas;?></td>
											</tr>
								<?php 
										endfor;
								?>
											<tr>
												<td colspan="2"><strong><?php echo lang('TOTAL');?></strong></td>
												<td><strong><?php echo $totalligacoes;?></strong></td>
												<td><strong><?php echo $totalvisitas;?></strong></td>
												<td><strong><?php echo $totalprogramada;?></strong></td>
												<td><strong><?php echo $totalagendas;?></strong></td>
											</tr>
								</tbody>
							</table>

<script type="text/javascript">
 $(document).ready(function(){
  var s1 = [<?php print $gligacoes;?>];
  var s2 = [<?php print $gvisitas;?>];
  var s3 = [<?php print $gagendas;?>];
  var s4 = [<?php print $gprogramada;?>];
  var cat = [<?php print $gdia;?>];
  
  
  chart = new Highcharts.Chart({
  
            chart: {
                renderTo: 'chart',
                backgroundColor: '#F8F8F8',
				marginTop: 20,
				marginLeft: 80,
                marginBottom: 45,
				zoomType: 'xy'
            },
			credits: {
				enabled: false
			},
            title: {
                text: '',
                x: 0, //center
				y: 10
            },
            xAxis: {
                categories: cat
            },
            yAxis: {
                
				title: {
                    text: null
                },
				labels: {
                    align: 'left',
                    x: -60,
                    y: -3,
                    formatter: function() {
                        return Highcharts.numberFormat(this.value, 2, ',', '.');
                    }
                }
            },
			lang: {
				downloadPNG: 'Download da imagem em PNG',
				downloadJPEG: 'Download da imagem em JPEG',
				downloadPDF: 'Download da imagem em PDF',
				downloadSVG: 'Download da imagem em SVG',
				exportButtonTitle: 'Exportar o tra�ado ou vetor da imagem',
				loading: 'Carregando...',
				months: ['Janeiro', 'Fevereiro', 'Mar�o', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
				shortMonths: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
				printButtonTitle: 'Imprimir o gr�fico',
				resetZoom: 'Retirar zoom',
				resetZoomTitle: 'Retirar zoom 1:1',
				weekdays: ['Domingo', 'Segunda', 'Ter�a', 'Quarta', 'Quinta', 'Sexta', 'S�bado']
			},
            tooltip: {
				shared: true,
				positioner: function () {
                return { x: 110, y: 50 };
				}
            },
            legend: {                
                layout: 'vertical',
                backgroundColor: '#FFFFFF',
                x: 0,
                y: 0,
                shadow: true,				
				align: 'right',
				verticalAlign: 'top',
				floating: false
            },
			plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Ligacoes',
				type: 'spline',
                data: s1
            },{
                name: 'Visitas',
				type: 'spline',
                data: s2
            },{
                name: 'Marcada',
				type: 'spline',
                data: s3
            },{
                name: 'Programada',
				type: 'spline',
                data: s4
            }]
        }); 
});
</script>								
						<div id="chart" class="chart"></div>
						</div>
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php case "situacaoatual": ?>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('CRM');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('SITUACAO_ATUAL');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<div class="portlet yellow-casablanca box">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-map-marker">&nbsp;&nbsp;</i><?php echo lang('CONTATO_CADASTRO_ORIGEM');?>
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-scrollable table-scrollable-borderless">
								<table class="table table-light">
									<thead>
										<tr>
											<th><?php echo lang('ORIGEM');?></th>
											<th><?php echo lang('CONTATO_ABERTO');?></th>
											<th><?php echo lang('CONTATO_ATENDIMENTO');?></th>
											<th><?php echo lang('CONTATO_QUALIFICADOS');?></th>
											<th><?php echo lang('TOTAL');?></th>
										</tr>
									</thead>
									<tbody>
									<?php 											
										$retorno_row = $cadastro->getOrigem();
										$totalaberto = 0;
										$totalatendimento = 0;
										$totalqualificado = 0;
										if($retorno_row):
											foreach ($retorno_row as $exrow):
											$total = 0;
											$aberto = $cadastro->getCadastrosAberto($exrow->id);
											$total += $aberto;
											$totalaberto += $aberto;
											$atendimento = $cadastro->getCadastrosAtendimento($exrow->id);
											$total += $atendimento;
											$totalatendimento += $atendimento;
											$qualificado = $cadastro->getCadastrosQualificado($exrow->id);
											$total += $qualificado;
											$totalqualificado += $qualificado;
									?>
										<tr>
											<td><?php echo $exrow->origem;?></td>
											<td><?php echo $aberto;?></td>
											<td><?php echo $atendimento;?></td>
											<td><?php echo $qualificado;?></td>
											<td><strong><?php echo $total;?></strong></td>
										</tr>
									<?php 	
											endforeach;
									?>
										<tr>
											<td>SEM ORIGEM</td>
											<td><?php echo $semorigem = $cadastro->getCadastroSemOrigem();?></td>
											<td>0</td>
											<td>0</td>
											<td><strong><?php echo $semorigem;?></strong></td>
										</tr>
									
									<?php 	
											$totalaberto += $semorigem;
									?>
										<tr>
											<td><strong><?php echo lang('TOTAL');?></strong></td>
											<td><strong><?php echo $totalaberto;?></strong></td>
											<td><strong><?php echo $totalatendimento;?></strong></td>
											<td><strong><?php echo $totalqualificado;?></strong></td>
											<td><strong><?php echo $totalaberto+$totalatendimento+$totalqualificado;?></strong></td>
										</tr>
									<?php 
											unset($exrow);
										endif;
									?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
				$data = date("m/Y"); 
				$consultor_row = $cadastro->getTodosConsultoresRetorno($data);
				if ($consultor_row):
					foreach ($consultor_row as $crow):
					$consultor = $crow->consultor;
					$programado = $gestao->getInfoRetornoProgramado($consultor);
					$vazio = $gestao->getInfoRetornoVazio($consultor);
					$vencido = $gestao->getInfoRetornoVencido($consultor);
					$novo = $gestao->getInfoNovoContato($consultor);
					$colaborador = getValue("nome", "usuario", "usuario='".$consultor."'");
			?>
			<div class="row">
				<div class="col-md-12 col-sm-12">
					<div class="portlet blue box">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-user">&nbsp;&nbsp;</i><?php echo $colaborador;?>
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-scrollable table-scrollable-borderless">
								<table class="table table-light">
									<thead>
										<tr>
											<th><?php echo lang('ACOES');?></th>
											<th><?php echo lang('QUANTIDADE');?></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td> <?php echo lang('RETORNO_PROGRAMADO');?></td>
											<td><?php echo $programado;?></td>
										</tr>
										<tr>
											<td> <?php echo lang('RETORNO_VENCIDO');?></td>
											<td><?php echo $vencido;?></td>
										</tr>
										<tr>
											<td> <?php echo lang('SEM_DATA_RETORNO');?></td>
											<td><?php echo $vazio;?></td>
										</tr>
										<tr>
											<td> <?php echo lang('CONTATOS_SEM_LIGACOES');?></td>
											<td><?php echo $novo;?></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>			
			<?php
					endforeach;
					unset($srow);
				endif;
			?>
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php case 'contatosconsultor': ?>
<?php if(!$usuario->is_Comercial()):?>
<div class="imagem-fundo">
	<img src="assets/img/logo.png" border="0">
</div>
<?php return; endif;?>
<?php 
	$consultor = (get('consultor')) ? get('consultor') : ""; 
?>
<script type="text/javascript"> 
	  
	$(document).ready(function () {
		$('#consultor').click(function() {
			var consultor = $("#consultor").val();
			window.location.href = 'index.php?do=painel&acao=contatosconsultor&consultor='+consultor;
		});
	});
	
</script>
<!-- INICIO BOX MODAL -->
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('CRM');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PAINEL_CONTATOSCONSULTOR');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class='page-content'>
		<div class='container'>
			<!-- INICIO DO ROW TABELA -->
			<div class='row'>
				<div class='col-md-12'>
					<!-- INICIO TABELA -->						
					<div class='portlet light'>
						<div class='portlet-title'>
							<div class='caption'>
								<i class='fa fa-book font-<?php echo $core->primeira_cor;?>'></i>								
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('PAINEL_CONTATOSCONSULTOR');?></span>
							</div>
						</div>
						<?php 
							$consultor_row = $cadastro->getTodosConsultores();
						?>	
						<form class="form-inline" action="" method="post" name="admin_form" id="admin_form">
							<div class="portlet-body form">
								<div class="form-group">
									<select class="select2me form-control input-large" name="consultor" id="consultor" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
										<option value=""></option>
										<?php 
												if ($consultor_row):
													foreach ($consultor_row as $crow):
										?>				
														<option value="<?php echo $crow->consultor;?>" <?php if($crow->consultor == $consultor) echo 'selected="selected"';?>><?php echo strtoupper($crow->consultor);?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
									&nbsp;&nbsp;
									<input name="consultor" type="hidden" value="<?php echo $consultor;?>" />
									<button type="button" class="btn btn-submit red"><?php echo lang('REMOVER_SELECIONADOS');?></button>
								</div>
							</div>
							<div class='portlet-body'>
								<table class="table table-bordered table-condensed table-advance checkTable">
									<thead >
										<tr>
											<th class="table-checkbox">
												<input type="checkbox" class="group-checkable" data-set=".checkboxes"/>
											</th>
											<th><?php echo lang('CLIENTE');?></th>
											<th><?php echo lang('TELEFONE');?></th>
											<th><?php echo lang('OBSERVACAO');?></th>
											<th><?php echo lang('STATUS');?></th>
											<th><?php echo lang('RETORNO');?></th>
										</tr>
									</thead>
									<tbody>
									<?php 	
											$retorno_row = $cadastro->getContatoConsultor($consultor);
											if($retorno_row):
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
											<td>
												<input name="contatos[]" type="checkbox" class="checkboxes" value="<?php echo $exrow->id;?>"/>
											</td>
											<td><a href="index.php?do=cadastro&acao=contato&id=<?php echo $exrow->id;?>"><?php echo $exrow->nome;?></a></td>
											<td><?php echo $exrow->telefone." ".$exrow->celular;?></td>
											<td><?php echo $exrow->observacao;?></td>
											<td><?php echo $exrow->status;?></td>
											<td><?php echo exibedata($exrow->data_retorno);?></td>
										</tr>
									<?php endforeach;?>
									<?php unset($exrow);
										  endif;?>
									</tbody>
								</table>
							</div>
						</form>
						<?php echo $core->doForm("removerContatos");?>
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php case 'pendentes': ?>
<?php if(!$usuario->is_Comercial()):?>
<div class="imagem-fundo">
	<img src="assets/img/logo.png" border="0">
</div>
<?php return; endif;?>
<?php 
	$consultor = (get('consultor')) ? get('consultor') : ""; 
?>
<script type="text/javascript"> 
	  
	$(document).ready(function () {
		$('#consultor').click(function() {
			var consultor = $("#consultor").val();
			window.location.href = 'index.php?do=painel&acao=pendentes&consultor='+consultor;
		});
	});
	
</script>
<!-- INICIO BOX MODAL -->
<!-- INICIO CONTEUDO DA PAGINA -->
<div class='page-container'>
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class='page-head'>
		<div class='container'>
			<!-- INICIO TITULO DA PAGINA -->
			<div class='page-title'>
				<h1><?php echo lang('CRM');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('PAINEL_CONTATOSPENDENTES');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class='page-content'>
		<div class='container'>
			<!-- INICIO DO ROW TABELA -->
			<div class='row'>
				<div class='col-md-12'>
					<!-- INICIO TABELA -->						
					<div class='portlet light'>
						<div class='portlet-title'>
							<div class='caption'>
								<i class='fa fa-book font-<?php echo $core->primeira_cor;?>'></i>								
								<span class='font-<?php echo $core->primeira_cor;?>'><?php echo lang('PAINEL_CONTATOSPENDENTES');?></span>
							</div>
						</div>
						<?php 
							$consultor_row = $cadastro->getTodosConsultores();
						?>	
						<form class="form-inline" action="" method="post" name="admin_form" id="admin_form">
							<div class="portlet-body form">
								<div class="form-group">
									<select class="select2me form-control input-large" name="consultor" id="consultor" data-placeholder="<?php echo lang('SELECIONE_OPCAO');?>" >
										<option value=""></option>
										<?php 
												if ($consultor_row):
													foreach ($consultor_row as $crow):
										?>				
														<option value="<?php echo $crow->consultor;?>" <?php if($crow->consultor == $consultor) echo 'selected="selected"';?>><?php echo strtoupper($crow->consultor);?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
								</div>
							</div>
							<div class='portlet-body'>
								<table class="table table-bordered table-condensed table-advance dataTable">
									<thead >
										<tr>
											<th><?php echo lang('CLIENTE');?></th>
											<th><?php echo lang('TELEFONE');?></th>
											<th><?php echo lang('OBSERVACAO');?></th>
											<th><?php echo lang('STATUS');?></th>
											<th><?php echo lang('CATEGORIA');?></th>
											<th><?php echo lang('RETORNO');?></th>
											<th><?php echo lang('CONSULTOR');?></th>
										</tr>
									</thead>
									<tbody>
									<?php 	
											$retorno_row = $cadastro->getContatoPendentes($consultor);
											if($retorno_row):
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
											<td><a href="index.php?do=cadastro&acao=contato&id=<?php echo $exrow->id;?>"><?php echo $exrow->nome;?></a></td>
											<td><?php echo $exrow->telefone." ".$exrow->celular;?></td>
											<td><?php echo $exrow->observacao;?></td>
											<td><?php echo $exrow->status;?></td>
											<td><?php echo $exrow->categoria;?></td>
											<td><?php echo exibedata($exrow->data_retorno);?></td>
											<td><?php echo strtoupper($exrow->usuario);?></td>
										</tr>
									<?php endforeach;?>
									<?php unset($exrow);
										  endif;?>
									</tbody>
								</table>
							</div>
						</form>
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- FINAL CONTEUDO DA PAGINA -->
<?php break;?>
<?php default: ?>
<div class="imagem-fundo">
	<img src="assets/img/logo.png" border="0">
</div>
<?php break;?>
<?php endswitch;?>
<?php
  /**
   * Gestão
   *
   */
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');
  if (!$usuario->is_Gerencia())
	  redirect_to("login.php");
	  
  $datafiltro = (get('datafiltro')) ? get('datafiltro') : date("m/Y");
  
?>
<script src="./assets/scripts/highcharts.js" type="text/javascript"></script>
<script src="./assets/css/highcharts.css" type="text/css"></script>
<?php switch(Filter::$acao): case "mapa": 
	$endereco = "";
	$retorno_row = $gestao->getMapa();
	if($retorno_row) {
		foreach ($retorno_row as $exrow){
			$endereco .= "[".$exrow->lat.",".$exrow->lng."],";
		}
		unset($arow);
	}
?>

 <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBux0Uw6x65y7kJVllyFdCSZMppa20e1A&signed_in=true&callback=initMap"></script>
   <script src="./assets/scripts/markerclusterer.js" type="text/javascript"></script>
<script>
		
		function initMap() {

			var map = new google.maps.Map(document.getElementById('mapa'), {
				zoom: <?php print $core->zoom;?>,
				center: {lat: <?php print $core->lat;?>, lng: <?php print $core->lng;?>},
				mapTypeId: google.maps.MapTypeId.ROADMAP
			});
			var image = {
				url: './assets/img/pin.png',
				anchor: new google.maps.Point(10, 10),
				scaledSize: new google.maps.Size(20, 34)
			};
			var markers = [];
			var enderecos = [
			  <?php print $endereco;?>
			];
			
			for (var i = 0; i < enderecos.length; i++) {
				var local = enderecos[i];
				var marker = new google.maps.Marker({
				icon: image,
				position: {lat: local[0], lng: local[1]}
				});
				markers.push(marker);
			}
			var markerCluster = new MarkerClusterer(map, markers, {imagePath: './assets/img/m'});
		}
    </script>
<!-- INICIO CONTEUDO DA PAGINA -->
<div class="page-container">
	<!-- INICIO CABECALHO DA PAGINA -->
	<div class="page-head">
		<div class="container">
			<!-- INICIO TITULO DA PAGINA -->
			<div class="page-title">
				<h1><?php echo lang('MARKETING');?>&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small><?php echo lang('MAPA');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW TABELA -->
			<div class="row">
				<div class="col-md-12">
					<!-- INICIO TABELA -->						
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-map-marker font-<?php echo $core->primeira_cor;?>"></i>								
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('MAPA');?></span>
							</div>
						</div>
						<div class="portlet-body">
							<div id="mapa" class="gmap3"></div>
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
<?php case "dremensal": 

	if ($core->tipo_sistema==1 || $core->tipo_sistema==3)
		redirect_to("login.php");

$ano = (get('ano')) ? get('ano') : '';
$mes = (get('mes')) ? get('mes') : date('m/Y');
$id_empresa = (get('id_empresa')) ? get('id_empresa') : 0;

?>
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('#ano').click(function() {
			var ano = $("#ano").val();
			window.location.href = 'index.php?do=gestao&acao=dreanual&ano='+ ano;
		});
	});
	// ]]>
</script>
<script type="text/javascript"> 
	// <![CDATA[  
	$(document).ready(function () {
		$('#mes_ano').change(function() {
			var mes_ano = $("#mes_ano").val();
			window.location.href = 'index.php?do=gestao&acao=dremensal&mes='+ mes_ano;
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
				<h1><?php echo lang('GESTAO');?>&nbsp;&nbsp;<small><?php echo lang('GESTAO_DRE_MENSAL');?></small></h1>
			</div>
			<!-- FINAL TITULO DA PAGINA -->
		</div>
	</div>
	<!-- FINAL CABECALHO DA PAGINA -->
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW TABELA -->
			<div class="row">
				<div class="col-md-12">
					<!-- INICIO TABELA -->				
					<div class="portlet light">
						<div class="portlet-body">
							<form class="form-inline">
								<div class="form-group">
									<?php 
												$retorno_row = $gestao->getListaMes("despesa", "data_pagamento", false, "DESC");
										?>
									<select class="select2me form-control input-large" name="mes_ano" id="mes_ano" data-placeholder="<?php echo lang('SELECIONE_MES');?>" >
										<option value=""></option>
										<?php 
												if ($retorno_row):
													foreach ($retorno_row as $srow):
										?>				
														<option value="<?php echo $srow->mes_ano;?>" <?php if($srow->mes_ano == $mes) echo 'selected="selected"';?>><?php echo $srow->mes_ano;?></option>
										<?php
													endforeach;
													unset($srow);
												endif;
										?>
									</select>
								</div>
							</form>
						</div>
					</div>
<?php		
	$conta = '';
	$valor = '';
	$valor_receita = '';
	$valor_despesa = '';
	$total_receita = $gestao->getDREReceitaMensalTotal($mes, $id_empresa);
	$total_receita = ($total_receita) ? $total_receita : 0;
	$total_despesa = 0;
	$conta .= "'RECEITAS'";
	$valor_receita .= $total_receita;
	$valor_despesa .= 0;
	$despesa_row = $gestao->getDREDespesaMensal($mes, $id_empresa);
	foreach ($despesa_row as $drow){
		if(strlen($conta) > 0) {
				$conta .= ",";
				$valor_receita .= ",";
				$valor_despesa .= ",";
		}
		$id_pai = $drow->id_pai;
		$pai = $drow->conta_pai;
		$total_despesa += $total = ($drow->total) ? $drow->total : 0;
		$conta .= "'".$pai."'";
		$valor_receita .= 0;
		$valor_despesa .= $total;
	}
	unset($drow);
	$outrasdespesas = $gestao->getDREDespesaMensalTotal($mes, $id_empresa);
	$outrasdespesas = ($outrasdespesas) ? $outrasdespesas : 0;
	$outrasdespesas = $outrasdespesas - $total_despesa;
	$conta .= ",'OUTRAS DESPESAS'";
	$valor_receita .= ',0';
	$valor_despesa .= ','.$outrasdespesas;
?>
<script type="text/javascript">
 $(document).ready(function(){
  var s1 = [<?php print $valor_receita;?>];
  var s2 = [<?php print $valor_despesa;?>];
  var cat = [<?php print $conta;?>];
		
  chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart',
                backgroundColor: '#F8F8F8',
				marginTop: 20,
				marginLeft: 80,
                marginBottom: 200,
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
				categories: cat,
				labels: {
					rotation: -45,
					style: {
						fontSize: '10px',
						fontFamily: 'Verdana, sans-serif'
					}
				}
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
                        return 'R$ ' + Highcharts.numberFormat(this.value, 3, ',', '.');
                    }
                }
            },
			lang: {
				downloadPNG: 'Download da imagem em PNG',
				downloadJPEG: 'Download da imagem em JPEG',
				downloadPDF: 'Download da imagem em PDF',
				downloadSVG: 'Download da imagem em SVG',
				exportButtonTitle: 'Exportar o traçado ou vetor da imagem',
				loading: 'Carregando...',
				months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
				shortMonths: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
				printButtonTitle: 'Imprimir o gráfico',
				resetZoom: 'Retirar zoom',
				resetZoomTitle: 'Retirar zoom 1:1',
				weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado']
			},
            tooltip: {
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': R$ '+ Highcharts.numberFormat(this.y, 3, ',', '.');
                }
            },
            legend: {
                layout: 'horizontal',
                backgroundColor: '#FFFFFF',
                align: 'left',
                verticalAlign: 'top',
                x: 100,
                y: 10,
                floating: true,
                shadow: true
            },
			plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Receitas',
				color: '#2f8417',
				type: 'column',
                data: s1
            },{
                name: 'Despesas',
				color: '#e3000f',
				type: 'column',
                data: s2
            }]
        }); 
});
</script>
			

<?php
	$pieAnalise = "{name: 'Receitas', y: ".$total_receita.", color: '#2f8417'},";
	$pieAnalise .= "{name: 'Despesas', y: ".$total_despesa.", color: '#e3000f'}";
?>

<script type="text/javascript">
 $(function () {
    var chart;
    $(document).ready(function() {
		
		// Build the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart2',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
			credits: {
				enabled: false
			},
			title: {
                text: null
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>',
            	percentageDecimals: 3
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000'
                    }
                }
            },
			lang: {
				downloadPNG: 'Download da imagem em PNG',
				downloadJPEG: 'Download da imagem em JPEG',
				downloadPDF: 'Download da imagem em PDF',
				downloadSVG: 'Download da imagem em SVG',
				exportButtonTitle: 'Exportar o traçado ou vetor da imagem',
				loading: 'Carregando...',
				months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
				shortMonths: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
				printButtonTitle: 'Imprimir o gráfico',
				resetZoom: 'Retirar zoom',
				resetZoomTitle: 'Retirar zoom 1:1',
				weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado']
			},
            series: [{
                type: 'pie',
                name: '<?php echo lang('GESTAO_RECEITA_DESPESA');?>',
                data: [
                    <?php print $pieAnalise;?>
                ]
            }]
        });
    });
    
});
</script>

<?php
	$pieDespesas = '';
	$retorno_row = $gestao->getDREDespesaAPagar($id_empresa);
	if($retorno_row) {
		foreach ($retorno_row as $exrow) {
			$pieDespesas .= "{name: '".exibeMesAno($exrow->mes_ano, true, true)."', y: ".$exrow->total.", z: '".moedap($exrow->total)."'},";
		}
	}
	unset($exrow);
	$pieDespesas = substr($pieDespesas, 0, -1);
?>
<script type="text/javascript">
 $(function () {
    var chart;
    $(document).ready(function() {
		
		// Radialize the colors
		Highcharts.setOptions({
			colors: Highcharts.map(Highcharts.getOptions().colors, function (color) {
				return {
					radialGradient: {
						cx: 0.5,
						cy: 0.3,
						r: 0.7
					},
					stops: [
						[0, color],
						[1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
					]
				};
			})
		});
		
		// Build the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart3',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
			credits: {
				enabled: false
			},
			title: {
                text: null
            },
            tooltip: {
			headerFormat: '',
			pointFormat: '<span style="color:{point.color}">\u25CF</span> <b> {point.name}</b><br/>' +
				'Valor: <b>{point.z}</b><br/>' +
				'Percentual: <b>{point.percentage:.2f}%</b><br/>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000'
                    }
                }
            },
			lang: {
				downloadPNG: 'Download da imagem em PNG',
				downloadJPEG: 'Download da imagem em JPEG',
				downloadPDF: 'Download da imagem em PDF',
				downloadSVG: 'Download da imagem em SVG',
				exportButtonTitle: 'Exportar o traçado ou vetor da imagem',
				loading: 'Carregando...',
				months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
				shortMonths: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
				printButtonTitle: 'Imprimir o gráfico',
				resetZoom: 'Retirar zoom',
				resetZoomTitle: 'Retirar zoom 1:1',
				weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado']
			},
            series: [{
                type: 'pie',
                name: '<?php echo lang('GESTAO_DESPESAS_APAGAR');?>',
                data: [
                    <?php print $pieDespesas;?>
                ]
            }]
        });
    });
    
});
</script>
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-bar-chart-o font-<?php echo $core->primeira_cor;?>"></i>								
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('GESTAO_DRE_MENSAL')." em ".$mes;?></span>
							</div>
						</div>	
						<div class="portlet-body">
							<div id="chart" class="chart"></div>
						</div>
						<!-- FINAL CONTEUDO DA PAGINA -->						
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
			<div class="row">
				<div class="col-md-6">
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-pie-chart font-<?php echo $core->primeira_cor;?>"></i>								
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('GESTAO_RECEITA_DESPESA')." em ".$mes;?></span>
							</div>
						</div>	
						<div class="portlet-body">
							<div id="chart2" class="chart"></div>
						</div>
						<!-- FINAL CONTEUDO DA PAGINA -->						
					</div>
					<!-- FINAL TABELA -->
				</div>
				<div class="col-md-6">
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-pie-chart font-<?php echo $core->primeira_cor;?>"></i>								
								<span class="font-<?php echo $core->primeira_cor;?>"><?php echo lang('GESTAO_DESPESAS_APAGAR');?></span>
							</div>
						</div>	
						<div class="portlet-body">
							<div id="chart3" class="chart"></div>
						</div>
						<!-- FINAL CONTEUDO DA PAGINA -->						
					</div>
					<!-- FINAL TABELA -->
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<?php break;?>
<?php default: ?>
<div class="page-container">
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container">
			<!-- INICIO DO ROW TABELA -->
			<div class="row">
				<div class="col-md-12">
					<div class="imagem-fundo">
						<img src="assets/img/bg-white.png" border="0">
					</div>
				</div>
			</div>
			<!-- FINAL DO ROW TABELA -->
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<?php break;?>
<?php endswitch;?>
<?php
  /**
   * Visualizar E-mails
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
	$emails = getValue("emails", "cadastro_aviso", "id = ".$id);
	$emails = str_replace(";", "; ", $emails);
	
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
<meta name="description" content="SIGESIS - Sistemas - VOCÊ NO CONTROLE DA SUA EMPRESA, em qualquer lugar... a qualquer momento!"/>
<meta name="keywords" content="vale telecom, sistemas web, aplicativos, mobile, aplicativos vale do aço, aplicativos ipatinga, sistemas de gestão, sistemas web ipatinga, sistemas ipatinga, sistemas vale do aço, sites ipatinga, sites vale do aço, SIGE, SIGESIS, TELECOM, sige, sigesis, telecom" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Vale Telecom"/>

<!-- Title -->
<title><?php echo $core->empresa;?></title>

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
							<?php echo $emails;?>
						</div>
					</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
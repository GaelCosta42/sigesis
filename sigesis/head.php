<?php
	/**
	* Header
	*
	*/
  
	if (!defined("_VALID_PHP"))
		die('O acesso direto a está página não é permitido');
?>
	
<!DOCTYPE html>
<!--[if IE 8]> <html lang="pt-BR" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="pt-BR" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="pt-BR">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="description" content="SIGESIS - Sistemas - VOCÊ NO CONTROLE DA SUA EMPRESA, em qualquer lugar... a qualquer momento!"/>
<meta name="keywords" content="Vale Telecom, sistemas web, aplicativos, mobile, aplicativos vale do aço, aplicativos ipatinga, sistemas de gestão, sistemas web ipatinga, sistemas ipatinga, sistemas vale do aço, sites ipatinga, sites vale do aço, SIGE, SIGESIS, TELECOM, sige, sigesis telecom" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Vale Telecom"/>

<!-- Title -->
<title><?php echo $core->empresa;?></title>

<!-- Favicons -->
<link rel="shortcut icon" href="./assets/img/favicon.png">
<link rel="apple-touch-icon" href="./assets/img/favicon_60x60.png">
<link rel="apple-touch-icon" sizes="76x76" href="./assets/img/favicon_76x76.png">
<link rel="apple-touch-icon" sizes="120x120" href="./assets/img/favicon_120x120.png">
<link rel="apple-touch-icon" sizes="152x152" href="./assets/img/favicon_152x152.png">

<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="./assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="./assets/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
<link href="./assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="./assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
<link href="./assets/plugins/select2/select2.css" rel="stylesheet" type="text/css" />
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="assets/css/profile.css" rel="stylesheet" type="text/css"/>
<link href="assets/css/tasks.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="./assets/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css">
<link href="./assets/css/plugins.css" rel="stylesheet" type="text/css">
<link href="./assets/css/layout.css" rel="stylesheet" type="text/css">
<link href="./assets/css/themes/default.css" rel="stylesheet" type="text/css">
<link href="./assets/css/custom.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" type="text/css" href="./assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<link rel="stylesheet" type="text/css" href="./assets/plugins/bootstrap-datepicker/css/datepicker3.css"/>
<link rel="stylesheet" type="text/css" href="./assets/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css"/>
<link rel="stylesheet" type="text/css" href="./assets/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css"/>
<link rel="stylesheet" type="text/css" href="./assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="./assets/plugins/typeahead/typeahead.css">
<link rel="stylesheet" type="text/css" href="./assets/plugins/bootstrap-switch/css/bootstrap-switch.min.css"/>
<link rel="stylesheet" type="text/css" href="./assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>


<!-- BEGIN NOTIFICATION STYLES -->
<link rel="stylesheet" type="text/css" href="./assets/css/notification.css">
<!-- END NOTIFICATION -->

<!-- END THEME STYLES -->

<script src="./assets/plugins/jquery.min.js" type="text/javascript"></script>
<script src="./assets/scripts/jquery.mask.js" type="text/javascript"></script>
<script src="./assets/scripts/jquery.maskMoney.js" type="text/javascript"></script>
<script src="./assets/scripts/shortcut.js" type="text/javascript"></script>

<!-- dataTables -->
<script src="./assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="./assets/plugins/datatables/dataTables.select.min.js"></script>
<script src="./assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="./assets/plugins/datatables/buttons.flash.min.js"></script>
<script src="./assets/plugins/datatables/jszip.min.js"></script>
<script src="./assets/plugins/datatables/pdfmake.min.js"></script>
<script src="./assets/plugins/datatables/vfs_fonts.js"></script>
<script src="./assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="./assets/plugins/datatables/buttons.print.min.js"></script>

<!--[if lt IE 9]>
<script src="./assets/plugins/respond.min.js"></script>
<script src="./assets/plugins/excanvas.min.js"></script> 
<![endif]-->
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="./assets/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="./assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="./assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="./assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="./assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="./assets/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="./assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="./assets/plugins/typeahead/handlebars.min.js" type="text/javascript"></script>
<script src="./assets/plugins/typeahead/typeahead.bundle.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="./assets/plugins/select2/select2.min.js" type="text/javascript"></script>
<script src="./assets/plugins/bootstrap-growl/jquery.bootstrap-growl.min.js" type="text/javascript"></script>
<script src="./assets/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript" ></script>
<script src="./assets/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.pt-BR.js" type="text/javascript" ></script>
<script src="./assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<script src="./assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="./assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>

<!-- END PAGE LEVEL PLUGINS -->

</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-menu-fixed" class to set the mega menu fixed  -->
<!-- DOC: Apply "page-header-top-fixed" class to set the top menu fixed  -->
<body>
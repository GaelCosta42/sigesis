<?php
/**
 * Login
 *
 */

define("_VALID_PHP", true);
require_once("init.php");
?>
<?php
if ($usuario->is_Todos())
	redirect_to("index.php");

$valor_link = file_get_contents("https://controle.sigesis.com.br/uploads/imagem_login/arquivo_link.php");

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="pt-BR" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="pt-BR" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="pt-BR">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description"
		content="SIGESIS - Sistemas - VOCÊ NO CONTROLE DA SUA EMPRESA, em qualquer lugar... a qualquer momento!" />
	<meta name="keywords"
		content="Vale Telecom, sistemas web, aplicativos, mobile, aplicativos vale do aço, aplicativos ipatinga, sistemas de gestão, sistemas web ipatinga, sistemas ipatinga, sistemas vale do aço, sites ipatinga, sites vale do aço, SIGE, SIGESIS, TELECOM, sige, sigesis, telecom" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Vale Telecom" />

	<!-- Title -->
	<title><?php echo $core->empresa; ?></title>

	<!-- Favicons -->
	<link rel="shortcut icon" href="./assets/img/favicon.png">
	<link rel="apple-touch-icon" href="./assets/img//favicon_60x60.png">
	<link rel="apple-touch-icon" sizes="76x76" href="./assets/img//favicon_76x76.png">
	<link rel="apple-touch-icon" sizes="120x120" href="./assets/img//favicon_120x120.png">
	<link rel="apple-touch-icon" sizes="152x152" href="./assets/img//favicon_152x152.png">

	<link href="./assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<!-- BEGIN GLOBAL MANDATORY STYLES -->
	<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<!-- END GLOBAL MANDATORY STYLES -->
	<!-- BEGIN PAGE LEVEL STYLES -->
	<link href="assets/css/login.css" rel="stylesheet" type="text/css" />
	<!-- END PAGE LEVEL SCRIPTS -->
	<!-- BEGIN THEME STYLES -->
	<link href="assets/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css" />
	<link href="assets/css/layout.css" rel="stylesheet" type="text/css" />
	<!-- END THEME STYLES -->
	<script src="./assets/plugins/jquery.min.js" type="text/javascript"></script>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->

<body class="login">
	<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
	<div class="menu-toggler sidebar-toggler">

	</div>
	<!-- END SIDEBAR TOGGLER BUTTON -->
	<!-- BEGIN LOGO -->

	<div class="fullPage">

		<div class="sideLeft">
			<!-- Toda imagem tem que ser PNG e com o nome padrão "img-login.png" -->
			<a href="<?php echo $valor_link; ?>" target="_blank">
				<img src="https://controle.sigesis.com.br/uploads/imagem_login/img-login.png" alt="Propaganda SIGESIS"
					height="100%" width="100%">
			</a>
		</div>

		<div class="sideRight">

			<div class="logo">
				<a href="index.php">
					<img src="assets/img/logo-login.png" style="height: 78px;" alt="" />
				</a>
			</div>
			<!-- END LOGO -->
			<!-- BEGIN LOGIN -->
			<div class="content">
				<!-- BEGIN LOGIN FORM -->
				<form class="login-form" action="" method="post" autocomplete="off">

					<?php
					if (isset($_POST["submit"])):
						?>
						<div class="alert alert-danger">
							<span>
								<?php
								$result = $usuario->login($_POST["usuario"], $_POST["senha"]);
								?>
							</span>
						</div>
						<?php
						//Login successful 
						if ($result)
						:
							redirect_to("index.php");
						endif;
					endif;
					?>
					<div class="form-group">

						<h4 style="color:#fff"><?php echo $core->nome_empresa; ?></h4>
					</div>
					<div class="form-group">
						<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
						<label class="control-label visible-ie8 visible-ie9"><?php echo lang("USUARIO"); ?></label>
						<input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off"
							placeholder="<?php echo lang("USUARIO"); ?>" name="usuario" />
					</div>
					<div class="form-group">
						<label class="control-label visible-ie8 visible-ie9"><?php echo lang("SENHA"); ?></label>
						<input class="form-control form-control-solid placeholder-no-fix" type="password"
							autocomplete="off" placeholder="<?php echo lang("SENHA"); ?>" name="senha" />
					</div>
					<div class="form-group">
						<label class="control-label visible-ie8 visible-ie9"><?php echo lang("PIN"); ?></label>
						<input class="form-control form-control-solid placeholder-no-fix inteiro" type="password"
							autocomplete="off" maxlength="6" onkeyup="somenteNumeros(this);"
							placeholder="<?php echo lang("PIN"); ?>" id="pin" name="pin" />
					</div>
					<div class="form-actions">
						<button type="submit" name="submit" class="btn">
							<span><?php echo lang("ENTRAR"); ?></span>
						</button>
					</div>
				</form>
				<!-- END LOGIN FORM -->

				<div class="text-center">
					<a style="color: #fff"
						href="https://valetelecom.conecta.com.vc/webchat/v2/?cid=63690eda383e1f001b604fc4&host=https://valetelecom.conecta.com.vc"
						target="_blank" class="info-suporte">
						<!-- <i class="fa fa-phone-square"></i>	 -->
						<?php echo lang("INFO_SUPORTE_TELA_LOGIN"); ?>
					</a>
					<div style="margin-top: 5%; display: flex; gap: 15px; justify-content: center;">
						<a href="https://www.facebook.com/sigesisltda/" target="_blank">
							<i class="fa fa-facebook-square fa-2x" style="color: white !important"
								aria-hidden="true"></i>
						</a>
						<a href="https://www.instagram.com/sistemasgestao/" target="_blank">
							<i class="fa fa-instagram fa-2x" style="color: white !important" aria-hidden="true"></i>
						</a>
						<a href="https://www.linkedin.com/company/sigesis/?originalSubdomain=br" target="_blank">
							<i class="fa fa-linkedin-square fa-2x" style="color: white !important"
								aria-hidden="true"></i>
						</a>
					</div>
				</div>

			</div>
			<div class="copyright hide">
				<img src="https://sigen1.sigesis.com.br/assets/img/sige.png" alt="">&bull; <?php echo date('Y'); ?>
				&bull; Desenvolvido por <a href="http://www.telecom.inf.br" target="_blank">Vale Telecom.</a>
			</div>

		</div>

	</div>
	<!-- END LOGIN -->
	<script type="text/javascript">
		// <![CDATA[  
		$(document).ready(function () {
			$(".inteiro").on("keypress keyup blur", function (event) {
				$(this).val($(this).val().replace(/[^\d].+/, ""));
				if ((event.which < 48 || event.which > 57)) {
					event.preventDefault();
				}
			});

			$('#pin').on('keyup', function (e) {
				var pin = $("#pin").val();
				if (pin.length == 6) {
					jQuery.ajax({
						type: 'post',
						url: 'webservices/login.php',
						data: 'pin=' + pin,
						success: function (data) {
							if (data == 1) {
								window.location.href = "index.php";
							} else {
								$('#pin').val('');
								getPermission()
									.then(function () {
										var n = new Notification("Problema no login", {
											body: "Não foi possível fazer o login com o PIN. " + data
										});
									}).catch(function (status) {
										alert('Não foi possível fazer o login com o PIN. ' + data);
										console.log('Had no permission!');
									});
								return false;
							}
						}
					});
				}
			});

			function getStatus() {
				if (!window.Notification) {
					return "unsupported";
				}
				return window.Notification.permission;
			}

			// get permission Promise
			function getPermission() {
				return new Promise((resolve, reject) => {
					Notification.requestPermission(status => {
						var status = getStatus();
						if (status == 'granted') {
							resolve();
						} else {
							reject(status);
						}
					});
				});
			};
		});
		// ]]>
	</script>

</body>
<!-- END BODY -->

</html>
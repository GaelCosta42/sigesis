<?php

/**
 * Home
 *
 * @package Sigesis N1
 * @author Vale Telecom
 * @copyright 2022
 * @version 3
 */

if (!defined("_VALID_PHP"))
	die('Acesso direto a esta classe não é permitido.');
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.min.js"
	integrity="sha512-4F1cxYdMiAW98oomSLaygEwmCnIP38pb4Kx70yQYqRwLVCs3DbRumfBq82T08g/4LJ/smbFGFpmeFlQgoDccgg=="
	crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="./assets/scripts/home.js"></script>
<link rel="stylesheet" href="./assets/css/home.css">

<div class="page-container">
	<!-- INICIO DOS MODULOS DA PAGINA -->
	<div class="page-content">
		<div class="container" style="margin-left: 10%; margin-top: 5%">
			<div class="row">
				<div class="col-md-6">
					<div class="carousel">
						<div class="carousel-inner">
							<div class="carousel-item active">
								<img id="img-notice" class="imagem_print">
								<img class="img-overflow"
									style="display: none; scale: 1.5; margin-left: 20%; margin-top: -30px"
									src="./assets/img/cloudinho-state-1.png">
							</div>
						</div>
						<button class="carousel-control prev">❮</button>
						<button class="carousel-control next">❯</button>
					</div>
				</div>
				<div class="col-md-5 d-flex flex-column">
					<h2 id="title-notice"></h2>
					<p id="date-notice"></p>
					<p id="description-notice"></p>
					<a id="atualizacoes" href="?do=atualizacao&acao=listar"
						style="color: white; background-color: #417169; padding: 5px; border: none">Mais
						atualizações</a>
					<a id="enotas" href="?do=notas_negadas&acao=listar"
						style="display:none;color: white; background-color: #E54141; padding: 5px; border: none">Corrigir
						notas</a>
				</div>
			</div>
		</div>
		<div id="overlay">
			<span class="loader"></span>
		</div>
	</div>
	<!-- FINAL DOS MODULOS DA PAGINA -->
</div>
<!-- MODAL DE IMAGEM -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" id="fechar-modal">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body element">
				<figure id="figure-modal" class="zoom">
					<img id="img-modal" />
				</figure>
			</div>
		</div>
	</div>
</div>
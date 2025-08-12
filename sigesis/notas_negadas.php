<?php

/**
 * Notas negadas
 *
 * @package Sigesis N1
 * @author Vale Telecom
 * @copyright 2022
 * @version 3
 */

if (!defined("_VALID_PHP"))
	die('Acesso direto a esta classe não é permitido.');
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.12.4/sweetalert2.all.js"
	integrity="sha512-7CwElIdU6YF7ExXbTE9Z4xGnaKwLdQTdaMaonRG3XRhcIqTTg9K/eEiNInwBs7UgmY6o5MA2PLEzcwf1rRWKRQ=="
	crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script type="module" src="./modulos/notas_negadas/js/notas_negadas.js"></script>
<link rel="stylesheet" href="./modulos/notas_negadas/css/notas_negadas.css">

<div class="page-content">
	<div class="container">
		<!-- INICIO DO ROW TABELA -->
		<div class="row">
			<div class="col-md-12">
				<!-- INICIO TABELA -->
				<div class="portlet light">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-list font-<?php echo $core->primeira_cor; ?>"></i>
							<span class="font-<?php echo $core->primeira_cor; ?>">Notas Fiscais Negadas</span>
						</div>
					</div>
					<div class="row mb-3"
						style="display: flex;justify-content: flex-end;margin-right: 1.5em;margin-bottom: 1em;">
						<div class="col-md-4">
						</div>
						<div class="col-md-4">
							<input type="text" name="pesquisa-dinamica" id="pesquisa-dinamica-nfe" class="searchbar"
								placeholder="Pesquise pelo número da nota...">
						</div>
					</div>
					<table id="dynamic-table-nfe">
						<thead>
							<tr>
								<th style="width: 12%">Numero da Nota</th>
								<th style="width: 9%">Data Venda</th>
								<th style="width: 7%">Tipo</th>
								<th style="width: 11%">Status E-Notas</th>
								<th style="width: 50%">Motivo</th>
								<th style="width: 10%">Ação</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
					<div class="row"
						style="display: flex;justify-content: flex-end;margin-right: 1.5em;margin-bottom: 1em;margin-top: 1.5em">
						<div class="col-md-4">
						</div>
						<div class="col-md-4">
							<input type="text" name="pesquisa-dinamica" id="pesquisa-dinamica-nfce" class="searchbar"
								placeholder="Pesquise pelo número da nota ou venda...">
						</div>
					</div>
					<table id="dynamic-table-nfce">
						<thead>
							<tr>
								<th style="width: 10%">Cód. Venda</th>
								<th style="width: 9%">Data Venda</th>
								<th style="width: 12%">Numero da Nota</th>
								<th style="width: 7%">Tipo</th>
								<th style="width: 11%">Status E-Notas</th>
								<th style="width: 41%">Motivo</th>
								<th style="width: 10%">Ação</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
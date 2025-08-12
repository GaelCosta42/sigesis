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
<script type="module" src="./modulos/cfop/js/cfop.js"></script>
<link rel="stylesheet" href="./modulos/cfop/css/cfop.css">

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
                            <span class="font-<?php echo $core->primeira_cor; ?>">Conversão de CFOP/CSOSN</span>
                        </div>
                    </div>
                    <div class="alert alert-info" role="alert" style="text-align:center">
                        Cadastre todos os parâmetros fiscais que serão convertidos ao se importar um produto utilizando
                        o XML de um fornecedor.
                    </div>
                    <div class="row"
                        style="display: flex;justify-content: space-between;margin-right: 1.5em;margin-bottom: 1em;margin-top: 1.5em">
                        <div class="col-md-6">
                            <button type="button" class="btn sigesis-cor-1 cadastrar" data-fluxo="cfop">
                                Cadastrar CFOP
                            </button>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="pesquisa-dinamica" id="pesquisa-dinamica-cfop" class="searchbar"
                                placeholder="Pesquise...">
                        </div>
                    </div>
                    <table id="dynamic-table-cfop">
                        <thead>
                            <tr>
                                <th style="width: 13%">CFOP Fornecedor</th>
                                <th style="width: 10%">CFOP Entrada</th>
                                <th style="width: 9%">CFOP Saída</th>
                                <th style="width: 58%">Observação</th>
                                <th style="width: 10%">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="row"
                        style="display: flex;justify-content: space-between;margin-right: 1.5em;margin-bottom: 1em;margin-top: 1.5em">
                        <div class="col-md-6">
                            <button type="button" class="btn sigesis-cor-1 cadastrar" data-fluxo="csosn">
                                Cadastrar CSOSN/CST
                            </button>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="pesquisa-dinamica" id="pesquisa-dinamica-csosn" class="searchbar"
                                placeholder="Pesquise...">
                        </div>
                    </div>
                    <table id="dynamic-table-csosn">
                        <thead>
                            <tr>
                                <th style="width: 16%">CSOSN/CST Fornecedor</th>
                                <th style="width: 16%">CSOSN</th>
                                <th style="width: 58%">Observação</th>
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
    <div class="modal fade" id="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="fechar-modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body element">
                </div>
            </div>
        </div>
    </div>
    <div id="overlay">
        <span class="loader"></span>
    </div>
</div>
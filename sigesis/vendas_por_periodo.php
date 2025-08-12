<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
<script type="module" src="modulos/vendas_por_periodo/js/vendas_por_periodo.js"></script>

<div class="page-container">
    <div class="page-head">
        <div class="container">
            <div class="page-title">
                <h1>Vendas <i class="fa fa-angle-right"></i> <small>Vendas por período</small></h1>
            </div>
        </div>
    </div>

    <div class="page-content" style="min-height: 61.4vh;">
        <div class="container">
            <div class="portlet light">
                <div class="portlet-body">
                    <form autocomplete="off" class="form-inline">
                        <div class="form-group">
                            <input type="date" class="form-control input-medium calendario data payload" name="dataini" id="dataini">
                            <input type="date" class="form-control input-medium calendario data payload" name="datafim" id="datafim">
                            &nbsp;
                            <button type="button" id="selecionardata" name="action" value="refresh" class="btn sigesis-cor-1"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="portlet light">

                <div class="portlet">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="font-sigesis-cor-1">Vendas por período</span>
                        </div>
                        <div class="actions btn-set">
                            <a href="javascript:void(0);" class="btn btn-sm sigesis-cor-1" onclick="javascript:void window.open('imprimir_vendasdia.php?data=12/06/2024','Vendas do dia','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;"><i class="fa fa-print">&nbsp;&nbsp;</i>Imprimir</a>
                        </div>
                    </div>
                </div>

                <div class="portlet-body">
                    <h4>
                        <i class="fa fa-shopping-cart font-sigesis-cor-1"></i>
                        <span class="font-sigesis-cor-1">Vendas</span>
                    </h4>
                </div>

                <div class="portlet-body">
                    <div class="dt-buttons" style="margin-bottom: 0!important;"><a id="copiar" class="dt-button buttons-copy buttons-html5" tabindex="0" href="#"><span>Copy</span></a><a id="csv" class="dt-button buttons-csv buttons-html5" tabindex="0" aria-controls="DataTables_Table_0" href="#"><span>CSV</span></a><a id="xlsx" class="dt-button buttons-excel buttons-html5" tabindex="0" aria-controls="DataTables_Table_0" href="#"><span>Excel</span></a><a id="print" class="dt-button buttons-print" tabindex="0" aria-controls="DataTables_Table_0" href="#"><span>Print</span></a><a id="pdf" class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="DataTables_Table_0" href="#"><span>PDF</span></a></div>
                </div>
                <div class="portlet-body">
                    <div class="row mb-3" style="display: flex;justify-content: flex-end;margin-right: 1.5em;margin-bottom: 1em;">
                        <div class="col-md-9">
                            <h5>Valor total:</h5>
                            <div id="valorTotal"></div>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="pesquisa-dinamica" id="pesquisa-dinamica" class="searchbar" placeholder="Pesquise...">
                        </div>
                    </div>
                    <table id="dynamic-table">
                        <thead>
                            <tr>
                                <th>Cód Venda</th>
                                <th>Cliente</th>
                                <th>Desconto</th>
                                <th>Valor total</th>
                                <th>Tipo de pagamento</th>
                                <th>Vendedor</th>
                                <th>Cancelada</th>
                                <th>Status NFC-e</th>
                                <th>Número nota</th>
                                <th>Motivo</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="bootbox modal fade in" id="nota-fiscal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none; padding-right: 17px;">
        <div class="modal-backdrop fade in"></div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"></div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="id" id="cadastro_id">
                            <p>Retorno</p>
                            <p>
                                <select class="form-control" id="id_status" name="id_status"></select>
                            </p>
                            <p>Data de retorno</p>
                            <p>
                                <input type="date" class="form-control data calendario" style="color: black;" id="data_retorno" name="data_retorno">
                            </p>
                            <p>Observação</p>
                            <p>
                                <input type="text" class="form-control caps" id="observacao" name="observacao">
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button data-bb-handler="salvar" id="retorno" type="button" class="btn sigesis-cor-1">Salvar</button></div>
            </div>
        </div>
    </div>

</div>

</html>
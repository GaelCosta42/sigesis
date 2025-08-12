<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
<script type="module" src="./modulos/boleto_sigesis/js/boleto_sigesis.js"></script>
<link rel="stylesheet" href="./modulos/boleto_sigesis/css/boleto_sigesis.css">

<div class="page-container">

    <div class="page-head">
        <div class="container">
            <!-- INICIO TITULO DA PAGINA -->
            <div class="page-title">
                <h1>Financeiro&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<small>Despesas</small>&nbsp;<i
                        class="fa fa-angle-right"></i>&nbsp;<small>Boleto Sigesis</small>
                </h1>
            </div>
        </div>
    </div>
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light" style="padding: 20px 40px 20px 40px !important">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-file-text-o font-sigesis-cor-1"></i>
                                <span class="font-sigesis-cor-1">2ª via do Boleto Sigesis</span>
                            </div>
                        </div>

                        <div class="portlet-body">
                            <div class="card" style="width: 100%">
                                <div class="card-body">
                                    <h5 class="card-title">Gere, neste menu, todas as 2ª vias de boletos que estão com
                                        pagamento pendente ou atrasado.</h5>
                                </div>
                            </div>
                            <div class="row" style="text-align: center; display: none" id="alert-div">
                            </div>
                            <div class="row">
                                <table id="table-boletos"
                                    class="table table-bordered table-condensed table-advance dataTable-asc"
                                    style="width: 100% !important">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Vencimento</th>
                                            <th>Valor</th>
                                            <th>Adicional</th>
                                            <th>Total</th>
                                            <th>Opções</th>
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
        </div>
    </div>
    <div id="overlay">
        <span class="loader"></span>
    </div>
</div>
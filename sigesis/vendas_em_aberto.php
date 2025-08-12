<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="module" src="modulos/vendas_em_aberto/js/vendas_em_aberto.js"></script>

<div class="page-container">
    <div class="page-head">
        <div class="container">
            <div class="page-title">
                <h1>Vendas <i class="fa fa-angle-right"></i> <small>Vendas em aberto</small></h1>
            </div>
        </div>
    </div>
    <div class="page-wrapper" style="display: flex; flex-direction: column; min-height: 100vh;">
        <div class="page-content" style="flex: 1;">
            <div class="container">
                <div class="portlet light">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-exclamation-triangle font-sigesis-cor-1"></i><span
                                            class="font-sigesis-cor-1">Vendas em aberto</span>
                                    </div>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row mb-3"
                                    style="display: flex;justify-content: flex-end;margin-right: 1.5em;margin-bottom: 1em;">
                                    <div class="col"></div>
                                    <div class="col">
                                        <input type="text" name="pesquisa-dinamica" id="pesquisa-dinamica"
                                            class="searchbar" placeholder="Pesquise...">
                                    </div>
                                </div>
                                <table id="dynamic-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">Código</th>
                                            <th width="10%">Data da venda</th>
                                            <th>Cliente</th>
                                            <th>Total</th>
                                            <th>Desconto</th>
                                            <th>Acréscimo</th>
                                            <th>Valor a pagar</th>
                                            <th>Pagamento</th>
                                            <th>Usuário</th>
                                            <th width="20%">Opções</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="bootbox modal fade in" id="modal" tabindex="-1" role="dialog" aria-hidden="true"
                                style="display: none; padding-right: 17px;">
                                <div class="modal-backdrop fade in"></div>
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header"><button type="button"
                                                class="bootbox-close-button close" data-dismiss="modal"
                                                aria-hidden="true">×</button>
                                            <h4 class="modal-title">Apagar registro</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="bootbox-body">Você deseja cancelar esta venda? Código: </div>
                                        </div>
                                        <div class="modal-footer"><button data-bb-handler="salvar" id="cancelar_venda"
                                                type="button" class="btn red">Apagar Registro</button></div>
                                    </div>
                                </div>
                            </div>
                            <div><input type="hidden" name="cancelar" id="cancelar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
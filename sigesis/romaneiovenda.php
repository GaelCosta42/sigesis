<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.min.js" integrity="sha512-4F1cxYdMiAW98oomSLaygEwmCnIP38pb4Kx70yQYqRwLVCs3DbRumfBq82T08g/4LJ/smbFGFpmeFlQgoDccgg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="module" src="./romaneio_venda/js/romaneio_venda.js"></script>

<style>
    .searchbar {
        background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 32 32' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cg opacity='0.3'%3E%3Cpath d='M29.3333 29.3334L26.6666 26.6667M15.3333 28C16.9967 28 18.6438 27.6724 20.1806 27.0358C21.7174 26.3993 23.1138 25.4662 24.29 24.29C25.4662 23.1138 26.3992 21.7175 27.0358 20.1807C27.6723 18.6439 28 16.9968 28 15.3334C28 13.6699 27.6723 12.0228 27.0358 10.486C26.3992 8.94924 25.4662 7.55288 24.29 6.37667C23.1138 5.20046 21.7174 4.26744 20.1806 3.63088C18.6438 2.99432 16.9967 2.66669 15.3333 2.66669C11.9739 2.66669 8.75207 4.00121 6.37661 6.37667C4.00115 8.75213 2.66663 11.9739 2.66663 15.3334C2.66663 18.6928 4.00115 21.9146 6.37661 24.29C8.75207 26.6655 11.9739 28 15.3333 28Z' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/g%3E%3C/svg%3E%0A");
        background-repeat: no-repeat;
        background-position-x: 2%;
        background-position-y: center;
        padding-left: 2.5em;
    }

    input[type="checkbox"] {
        transform: scale(1.5);
        position: relative;
        top: 50%;
        left: 40%;
        transform: translateY(-50%);
    }
</style>

<body class="page-container">
    <div class="page-head">
        <div class="container">
            <div class="page-title">
                <h1>Vendas <i class="fa fa-angle-right"></i> <small><?php echo lang('ROMANEIO_CARGA'); ?></small> <i class="fa fa-angle-right"></i> <small><?php echo lang('ROMANEIO_CARGA'); ?></small> </h1>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="container">
            <!-- INICIO DO ROW TABELA -->
            <div class="row">
                <div class="col-md-12">
                    <!-- INICIO TABELA -->
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-barcode font-<?php echo $core->primeira_cor; ?>"></i>
                                <span class="font-<?php echo $core->primeira_cor; ?>"><?php echo lang('ROMANEIO_CARGA'); ?></span>
                            </div>
                            <div class="actions btn-set">
                                <button class="btn btn-sm blue inline" id="gerar-selecionados" title="Selecione todas as vendas listadas">
                                    <i class="fa fa-check">&nbsp;&nbsp;</i>
                                    Gerar Selecionados (<p id="selecionados-num" class="inline">0</p>)
                                </button>
                                <button class="btn btn-sm yellow-gold inline" id="gerar-todos" title="Gere o romaneio com os itens selecionados">
                                    <i class="fa fa-truck">&nbsp;&nbsp;</i>
                                    Gerar Todos (<p id="todos-num" class="inline">0</p>)
                                </button>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <form autocomplete="off" class="form-inline">
                                <div style="width: 100%" class="form-group">
                                    Selecione o período:
                                    &nbsp;
                                    <input type="date" style="width: 12%; color: black" name="dtini" type="text" class="form-control payload" name="dataini" id="dtini" value="13/06/2024">
                                    &nbsp;
                                    à
                                    &nbsp;
                                    <input type="date" style="width: 12%; color: black" name="dtfim" type="text" class="form-control payload" name="datafim" id="dtfim" value="13/06/2024">
                                    &nbsp;&nbsp;&nbsp;
                                    Situação da venda:
                                    &nbsp;
                                    <select style="width: 10%; display: inline-block" name="tipo-venda" class="form-control payload" id="tipo-venda">
                                        <option value="0">Todas</option>
                                        <option value="1">Abertas</option>
                                        <option value="2">Finalizadas</option>
                                    </select>
                                    &nbsp;&nbsp;&nbsp;
                                    <a href='javascript:void(0);' class='btn <?php echo $core->primeira_cor; ?>' id="buscar" title='<?php echo lang('BUSCAR'); ?>'><i class='fa fa-search'>&nbsp;&nbsp;</i><?php echo lang('BUSCAR'); ?></a>
                                </div>
                            </form>
                            <br />
                            <div class="row mb-3" style="display: flex;justify-content: flex-end;margin-right: 0em; margin-bottom: 1em;">
                                <div class="col"></div>
                                <div class="col">
                                    <input style="width: 355px" type="text" name="pesquisa-dinamica" id="pesquisa-dinamica" class="searchbar" placeholder="Pesquise as vendas...">
                                </div>
                            </div>
                            <table id="dynamic-table">
                                <thead>
                                    <tr>
                                        <th style="width: 5%"><input type="checkbox" class="checkbox-all" /></th>
                                        <th style="width: 10%">Cód. Venda</th>
                                        <th style="width: 10%">Data</th>
                                        <th style="width: 10%">Situação</th>
                                        <th style="width: 40%">Cliente</th>
                                        <th style="width: 15%">Vl. total</th>
                                        <th style="width: 10%">Opções</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div><input type="hidden" name="cancelar" id="cancelar"></div>

</body>

</html>
import { SimpleTable } from '../../../assets/plugins/SimpleTable/js/SimpleTable_class.js';
$(document).ready(function () {
    // GERA TABELA DO CFOP COM O SIMPLE TABLE
    let tabela_dinamica_cfop = new SimpleTable(
        null,
        `#dynamic-table-cfop`,
        `#pesquisa-dinamica-cfop`,
        true,
        './modulos/cfop/loads/load_cfop.php',
        '.payload'
    );

    // GERA TABELA DO CSOSN COM O SIMPLE TABLE
    let tabela_dinamica_csosn = new SimpleTable(
        null,
        `#dynamic-table-csosn`,
        `#pesquisa-dinamica-csosn`,
        true,
        './modulos/cfop/loads/load_csosn.php',
        '.payload'
    );

    // FUNÇÃO QQUE REALIZA O CARREGAMENTO DO FORMULARIO DENTRO DO MODAL COM BASE NO FLUXO SELECIONADO (CADASTRAR, ATUALIZAR)
    $(document).on('click', '.cadastrar, .atualizar', async function () {
        const fluxo = $(this).data('fluxo');

        $("#modal").modal("show");

        let acao = 'cadastrar';
        let acao_text = 'Cadastrar';
        let observacao = '';

        switch (fluxo) {
            case 'cfop':
                const codigo_cfop = $(this).data('codigo') ?? 0;

                let cfop_fornecedor = null;
                let cfop_entrada = null;
                let cfop_saida = null;

                // CASO SEJA UMA EDICAO, REALIZA A BUSCA DOS DADOS PARA CARREGAR O FORMULARIO COM OS DADOS DO REGISTRO
                if (+codigo_cfop > 0) {
                    acao = 'atualizar';
                    acao_text = 'Atualizar';

                    await $.ajax({
                        type: 'post',
                        url: './modulos/cfop/loads/load_cfop.php',
                        data: {
                            id: +codigo_cfop
                        },
                        dataType: 'json',
                        error: function (e) {
                            Swal.fire({
                                title: 'Falha no Processo!',
                                text: 'Erro ao carregar dados do registro de CFOP.',
                                icon: 'error',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        },
                        success: function (response) {

                            cfop_fornecedor = response.retorno[0].cfop_fornecedor;
                            cfop_entrada = response.retorno[0].cfop_entrada;
                            cfop_saida = response.retorno[0].cfop_saida;
                            observacao = response.retorno[0].observacao;
                        }
                    });
                }

                $('#modal .modal-title').html('Cadastrar CFOP');

                $('#modal .modal-body').html(` 
                    <form class="form-cadastro">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="cfop_fornecedor">CFOP Fornecedor</label>
                                <input placeholder="Digite o CFOP do fornecedor..." type="number" class="form-control" id="cfop_fornecedor" name="cfop_fornecedor" value="${cfop_fornecedor}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="cfop_entrada">CFOP Entrada</label>
                                <input placeholder="Digite o CFOP de entrada..." type="number" class="form-control" id="cfop_entrada" name="cfop_entrada" value="${cfop_entrada}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="cfop_saida">CFOP Saída</label>
                                <input placeholder="Digite o CFOP de saída..." type="number" class="form-control" id="cfop_saida" name="cfop_saida" value="${cfop_saida}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="observacao">Observação</label>
                            <input placeholder="Digite uma observação caso necessário..." type="text" class="form-control" id="observacao" name="observacao" value="${observacao}" required>
                        </div>
                        <div class="form-row">
                            <button type="button" class="btn red" data-dismiss="modal" aria-label="Close" id="fechar-modal">Cancelar</button>
                            <button type="button" data-fluxo="${fluxo}" data-codigo="${codigo_cfop}" data-acao="${acao}" class="btn sigesis-cor-1 submit">${acao_text}</button>
                        </div>
                    </form>
                `);
                break;
            case 'csosn':
                const codigo_csosn = $(this).data('codigo') ?? 0;

                let csosn_cst = null;
                let csosn = null;

                // CASO SEJA UMA EDICAO, REALIZA A BUSCA DOS DADOS PARA CARREGAR O FORMULARIO COM OS DADOS DO REGISTRO
                if (+codigo_csosn > 0) {
                    acao = 'atualizar';
                    acao_text = 'Atualizar';

                    await $.ajax({
                        type: 'post',
                        url: './modulos/cfop/loads/load_csosn.php',
                        data: {
                            id: +codigo_csosn
                        },
                        dataType: 'json',
                        error: function (e) {
                            Swal.fire({
                                title: 'Falha no Processo!',
                                text: 'Erro ao carregar dados do registro de CSOSN.',
                                icon: 'error',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        },
                        success: function (response) {

                            csosn_cst = response.retorno[0].csosn_cst;
                            csosn = response.retorno[0].csosn;
                            observacao = response.retorno[0].observacao;
                        }
                    });
                }
                $('#modal .modal-title').html('Cadastrar CSOSN');

                $('#modal .modal-body').html(`
                    <form class="form-cadastro">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="csosn_cst">CSOSN/CST Fornecedor</label>
                                <input placeholder="Digite um CSOSN/CST..." type="number" class="form-control" id="csosn_cst" name="csosn_cst" value="${csosn_cst}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="csosn">CSOSN</label>
                                <input placeholder="Digite um CSOSN..." type="number" class="form-control" id="csosn" name="csosn" value="${csosn}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="observacao">Observação</label>
                            <input placeholder="Digite uma observação caso necessário..."type="text" class="form-control" id="observacao" name="observacao" value="${observacao}" required>
                        </div>
                        <div class="form-row">
                            <button type="button" class="btn red" data-dismiss="modal" aria-label="Close" id="fechar-modal">Cancelar</button>
                            <button type="button" data-fluxo="${fluxo}" data-codigo="${codigo_csosn}" data-acao="${acao}" class="btn sigesis-cor-1 submit">${acao_text}</button>
                        </div>
                    </form>
                `);
                break;
            default:
                Swal.fire({
                    title: 'Falha no Processo!',
                    text: 'Erro ao executar ação.',
                    icon: 'error',
                    timer: 3000,
                    showConfirmButton: false
                });
                break;
        }
    });

    // REALIZA O SUBMIT COM BASE NO FLUXO SELECIONADO (CADASTRAR, ATUALIZAR, INATIVAR)
    function enviar(fluxo, acao, codigo = null) {
        let dados = null;

        $("#overlay").css("display", "flex");

        switch (acao) {
            case 'cadastrar':
                dados = {
                    ...{
                        acao: acao,
                        fluxo: fluxo
                    },
                    ...Object.fromEntries(new URLSearchParams($('.form-cadastro').serialize()))
                };
                break;
            case 'atualizar':
                dados = {
                    ...{
                        acao: acao,
                        fluxo: fluxo,
                        id: codigo
                    },
                    ...Object.fromEntries(new URLSearchParams($('.form-cadastro').serialize()))
                };
                break;
            case 'inativar':
                dados = {
                    acao: acao,
                    fluxo: fluxo,
                    id: codigo
                }
                break;
            default:
                Swal.fire({
                    title: 'Falha no Processo!',
                    text: 'Erro ao executar ação.',
                    icon: 'error',
                    timer: 3000,
                    showConfirmButton: false
                });
                break;
        }

        $.ajax({
            type: 'post',
            url: './modulos/cfop/submit/submit.php',
            data: dados,
            dataType: 'json',
            error: function (e) {
                $("#overlay").css("display", "none");
            },
            success: function (response) {
                $("#overlay").css("display", "none");

                if (response.status) {
                    Swal.fire({
                        title: 'Sucesso!',
                        text: response.msg,
                        icon: 'success',
                        timer: 3000,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        switch (fluxo) {
                            case 'cfop':
                                // RECARREGA A TABELA DE CFOP CASO SEJA ESTE O FLUXO
                                tabela_dinamica_cfop.refresh();
                                break;
                            case 'csosn':
                                // RECARREGA A TABELA DE CSOSN CASO SEJA ESTE O FLUXO
                                tabela_dinamica_csosn.refresh();
                                break;
                            default:
                                Swal.fire({
                                    title: 'Falha no Processo!',
                                    text: 'Erro ao executar ação.',
                                    icon: 'error',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                                break;
                        }
                    });
                    $("#modal").modal("hide");
                } else {
                    Swal.fire({
                        title: 'Falha no Processo!',
                        text: response.msg,
                        icon: 'error',
                        timer: 3000,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    });
                }
            }
        });
    }

    // FUNCOES DE SUBMIT, ONDE O INATIVAR REALIZA UMA VERIFICACAO DE CONFIRMACAO ANTES DE REALIZAR A ACAO
    $(document).on('click', '.inativar', function () {
        const fluxo = $(this).data('fluxo');
        const acao = $(this).data('acao');
        const codigo = $(this).data('codigo');

        Swal.fire({
            title: 'Inativar Registro',
            text: 'Deseja inativar este registro?',
            icon: 'info',
            showConfirmButton: true,
            showCancelButton: true,
            reverseButtons: true,
            confirmButtonText: 'Inativar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: "#417169",
            cancelButtonColor: "#cb5a5e",
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                enviar(fluxo, acao, codigo);
            }
        });
    })

    $(document).on('click', '.submit', function () {
        const fluxo = $(this).data('fluxo');
        const acao = $(this).data('acao');
        const codigo = $(this).data('codigo') ?? null;

        enviar(fluxo, acao, codigo);
    })
})
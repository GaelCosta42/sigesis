import { SimpleTable } from "../../../assets/plugins/SimpleTable/js/SimpleTable_class.js";
$(document).ready(function () {

    $.ajax({
        type: "POST",
        url: "modulos/ecomd/load/verificaModulo.php",
        dataType: "json",
        success: function (response) {
            if (response.status == 'success') {
                const tabelaProdutos = new SimpleTable(null, '#dynamic-table-produto', '#pesquisa-dinamica-call', true, 'modulos/ecomd/load/load_produtos.php', null);
            } else {
                $('.aba').hide();
                $('.alerta').show();
            }
        },
        error: function () {
            $('.aba').hide();
            $('.alerta').show();
        }
    });

    $(document).on('click', '#div-produtos-btn', function () {
        $('.aba').fadeOut(350).promise().then(function () {
            $(`#div-produtos`).fadeIn(550);
        });
    });

    $(document).on('click', '#div-pedidos-btn', function () {
        $('.aba').fadeOut(350).promise().then(function () {
            $(`#div-pedidos`).fadeIn(550);
        });
    });

    $(document).on('click', '.adicionar', function () {
        const id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "./modulos/ecomd/funcoes/processar_produtos.php",
            data: JSON.stringify({ "acao": "adicionar-produto", 'id': id }),
            dataType: "json",
            beforeSend: function () {
                Swal.fire({
                    title: 'Aguarde...',
                    text: 'Carregando',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function (response) {
                if (response.status == 'success') {
                    Swal.close();
                    tabelaProdutos.refresh();
                } else {
                    Swal.fire({
                        icon: "warning",
                        title: "Atenção!",
                        text: "Ocorreu um erro ao tentar realizar essa ação.",
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: "warning",
                    title: "Atenção!",
                    text: "Ocorreu um erro ao tentar realizar essa ação.",
                });
            }
        });
    });

    $(document).on('click', '.atualizar', function () {
        const id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "./modulos/ecomd/funcoes/processar_produtos.php",
            data: JSON.stringify({ "acao": "atualizar-produto", 'id': id }),
            dataType: "json",
            beforeSend: function () {
                Swal.fire({
                    title: 'Aguarde...',
                    text: 'Carregando',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function (response) {
                if (response.status == 'success') {
                    Swal.close();
                    tabelaProdutos.refresh();
                } else {
                    Swal.fire({
                        icon: "warning",
                        title: "Atenção!",
                        text: "Ocorreu um erro ao tentar realizar essa ação.",
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: "warning",
                    title: "Atenção!",
                    text: "Ocorreu um erro ao tentar realizar essa ação.",
                });
            }
        });
    });

    $(document).on('click', '.remover', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: "Tem certeza que deseja remover o produto do e-commerce?",
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Remover",
            denyButtonText: `Não remover`,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "./modulos/ecomd/funcoes/processar_produtos.php",
                    data: JSON.stringify({ "acao": "excluir-produto", 'id': id }),
                    dataType: "json",
                    beforeSend: function () {
                        Swal.fire({
                            title: 'Aguarde...',
                            text: 'Carregando',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function (response) {
                        if (response.status == 'success') {
                            Swal.close();
                            tabelaProdutos.refresh();
                        } else {
                            Swal.fire({
                                icon: "warning",
                                title: "Atenção!",
                                text: "Ocorreu um erro ao tentar realizar essa ação.",
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: "warning",
                            title: "Atenção!",
                            text: "Ocorreu um erro ao tentar realizar essa ação.",
                        });
                    }
                });
            } else if (result.isDenied) {
                Swal.fire("Nenhuma alteração foi realizada.", "", "info");
            }
        });
    });

    $(document).on('click', '#adicionar-todos', function () {
        Swal.fire({
            title: "Tem certeza que deseja adicionar todos os produtos ao e-commerce?",
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Adicionar",
            denyButtonText: `Não adicionar`,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "./modulos/ecomd/funcoes/processar_produtos.php",
                    data: JSON.stringify({ "acao": "adicionar-produtos" }),
                    dataType: "json",
                    beforeSend: function () {
                        Swal.fire({
                            title: 'Aguarde...',
                            text: 'Carregando',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function (response) {
                        if (response.status == 'completed') {
                            Swal.close();
                            tabelaProdutos.refresh();
                        } else {
                            Swal.fire({
                                icon: "warning",
                                title: "Atenção!",
                                text: "Ocorreu um erro ao tentar realizar essa ação.",
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: "warning",
                            title: "Atenção!",
                            text: "Ocorreu um erro ao tentar realizar essa ação.",
                        });
                    }
                });
            } else if (result.isDenied) {
                Swal.fire("Nenhuma alteração foi realizada.", "", "info");
            }
        });

    });

    $(document).on('click', '#atualizar-todos', function () {
        $.ajax({
            type: "POST",
            url: "./modulos/ecomd/funcoes/processar_produtos.php",
            data: JSON.stringify({ "acao": "atualizar-produtos" }),
            dataType: "json",
            beforeSend: function () {
                Swal.fire({
                    title: 'Aguarde...',
                    text: 'Carregando',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function (response) {
                if (response.status == 'completed') {
                    Swal.close();
                    tabelaProdutos.refresh();
                } else {
                    Swal.fire({
                        icon: "warning",
                        title: "Atenção!",
                        text: "Ocorreu um erro ao tentar realizar essa ação.",
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: "warning",
                    title: "Atenção!",
                    text: "Ocorreu um erro ao tentar realizar essa ação.",
                });
            }
        });
    });
});
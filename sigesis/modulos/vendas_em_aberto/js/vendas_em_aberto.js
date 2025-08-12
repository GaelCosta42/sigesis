import { SimpleTable } from "../../../assets/plugins/SimpleTable/js/SimpleTable_class.js";
$(document).ready(function () {

    let tabela_dinamica = new SimpleTable(null, `#dynamic-table`, `#pesquisa-dinamica`, true, 'modulos/vendas_em_aberto/load/load_vendas_em_aberto.php', null);

    $(document).on('click','.imprimir_venda', function () {
        let id = $(this).data('id');
        window.open(`recibo_orcamento.php?id=${id}`,`Imprimir Orçamento ${id}`,'width=360,height=700,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');
    });

    $(document).on('click','.apagar', function () {

        let id = $(this).data('id');

        $('#cancelar').val(id);

        $('#modal .bootbox-body').html(`Você deseja cancelar esta venda? Código: ${id}`);
        $('#modal').modal('show');
    });

    $(document).on('click','#cancelar_venda', function () {
        $.ajax({
            type: "POST",
            url: "controller.php",
            data: {processarCancelarVendaAberto: $('#cancelar').val()},
            dataType: "text",
            beforeSend: function() {
                $('#modal').modal('hide');
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
                if (response.includes("Sucesso!")) {
                    tabela_dinamica.refresh();
                    Swal.fire({
                        title: "Sucesso!",
                        text: "A venda foi cancelada com sucesso.",
                        icon: "success",
                        allowOutsideClick: false,
                        showConfirmButton: true,
                    });
                } else {
                    tabela_dinamica.refresh();
                    Swal.fire({
                        title: "Atenção!",
                        text: "Houve um erro ao realizar a requisição.",
                        icon: "warning",
                        allowOutsideClick: false,
                        showConfirmButton: true,
                    });
                }
            },
            error: function(){
                $('#modal').modal('hide');
                tabela_dinamica.refresh();
                Swal.fire({
                    title: "Atenção!",
                    text: "Houve um erro ao realizar a requisição.",
                    icon: "warning",
                    allowOutsideClick: false,
                    showConfirmButton: true,
                });
            }
        });
    });

    $(document).on('click', '.imprimir_vendaa4', function () {
        let value = $(this).data('id');
        window.open(`pdf_pedido_orcamento.php?id=${value}`,`CÓDIGO: ${value}`,'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
    });

    $(document).on('click', '.imprimir_romaneio', function () {
        let value = $(this).data('id');
        window.open(`pdf_romaneio.php?id=${value}`,`CÓDIGO: ${value}`,'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
    });

    (function ($) {
        $.fn.modal = function (action) {
            if (action === 'show') {
                this.addClass('in');
                this.show();
                $('<div class="modal-backdrop fade in"></div>').appendTo(document.body);
            } else if (action === 'hide') {
                this.removeClass('in');
                this.hide();
                $('.modal-backdrop').remove();
            }
            return this;
        };

        $(document).on('click', '[data-dismiss="modal"]', function () {
            $(this).closest('.modal').modal('hide');
        });
    })(jQuery);
});
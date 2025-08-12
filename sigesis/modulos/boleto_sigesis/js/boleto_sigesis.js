$(document).ready(function () {

    const table = $("#table-boletos tbody");

    $('.dt-buttons').remove();

    const buscaBoleto = async () => {
        $("#overlay").css("display", "flex");

        $('#alert-div').html(`<div class="alert alert-success" role="alert">Não há boletos pendentes de pagamento.</div>`);

        const url = "./modulos/boleto_sigesis/loads/buscaBoletos.php";
        const result = await $.ajax({ type: "POST", url, dataType: "json" });

        console.log(result);

        table.append(`<tr><td colspan="5" class="text-center">Carregando...</td></tr>`);

        if (result) {

            let situacao = true;

            table.empty();

            result.boleto.forEach((boleto) => {
                const dataVencimento = moment(boleto.data_vencimento);
                const isVencido = dataVencimento.isBefore(moment());

                if (isVencido) { situacao = false }

                const row = `
                    <tr class="${isVencido ? 'alert-danger' : 'alert-warning'}">
                        <td>${dataVencimento.format('DD/MM/YYYY')}</td>
                        <td>${boleto.valor_contrato}</td>
                        <td>${boleto.valor_adicional}</td>
                        <td>${boleto.valor_total}</td>
                        <td>
                            <button class="btn btn-sm purple gerar-boleto" title="Gerar Boleto" data-id_receita="${boleto.id_receita}" data-id_cadastro="${boleto.id_cadastro}">
                                <i class="fa fa-bold"></i>
                            </button>
                        </td>
                    </tr>`;
                table.append(row);
            });

            if (result.boleto.length > 0) {
                situacao ?
                    $('#alert-div').html(`<div class="alert alert-warning" role="alert">Alerta! Existem boletos com pagamentos pendentes. Gere a segunda via na listagem abaixo.</div>`)
                    :
                    $('#alert-div').html(`<div class="alert alert-danger" role="alert">Alerta! Existem boletos com pagamentos atrasados. Gere a segunda via na listagem abaixo.</div>`);
            } else {
                table.append(`<tr><td colspan="5" class="text-center">Não foram encontrados resultados</td></tr>`);
            }
        }

        $("#alert-div").css("display", "block");
        $("#overlay").css("display", "none");
    };

    buscaBoleto();

    $(document).on('click', '.gerar-boleto', function () {
        const idReceita = $(this).data('id_receita');
        const idCadastro = $(this).data('id_cadastro');

        const popupUrl = `https://controle.sigesis.com.br/boleto_bb.php?todos=0&id_receita=${idReceita}&id_cadastro=${idCadastro}`;
        const popupFeatures = "width=800,height=600,scrollbars=yes,resizable=yes";
        window.open(popupUrl, "_blank", popupFeatures);
    })

});

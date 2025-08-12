$(document).ready(function () {

    function toDecimal(value) {
        return parseFloat(value.replace('R$', '').replace('.','').replace(',', '.'));
    }

    function toCurrency(value) {
        const numericValue = parseFloat(value);
        if (isNaN(numericValue)) {
            return `R$ 0,00`;
        }
        return `R$ ${numericValue.toFixed(2).replace('.', ',')}`;
    }

    let lista = [];
    var valida = true;
    const url = new URL(window.location.href);
    const id_nota = url.searchParams.get('id');

    let valorNota = 0;
    let valorNotaPago = 0;
    let valorNotaPendente = 0;

    $.ajax({
        type: 'post',
        url: 'modulos/nota_fiscal/receita/loads/load_pagamentos.php',
        data: {
            id_nota: id_nota
        },
        dataType: 'json',
        success: function (response) {
            if (response.status) {
                valorNota = +response.dados[0].valorTotal;
                valorNotaPago = +response.dados[0].valorPago;
                valorNotaPendente = valorNota - valorNotaPago;

                $('#valor_total').text(`R$ ${valorNota.toFixed(2).replace('.', ',')}`);
                $('#valor_pago').text(`R$ ${valorNotaPago.toFixed(2).replace('.', ',')}`);
                $('#valor_pendente').text(`R$ ${valorNotaPendente.toFixed(2).replace('.', ',')}`);
            }
        }
    });

    $('#receita_data').val(moment().format('DD/MM/YYYY'));

    $("#receita_repeticoes").on("change", function () {
        const value = +$(this).val();
        const parcelas = +$('#receita_pagamento').find(':selected').data('parcelas');

        if (value > parcelas) {
            Swal.fire({
                title: 'Atenção!',
                text: `Número de parcelas para este método de pagamento não pode ser maior que ${parcelas}.`,
                icon: 'info',
                timer: 3000,
                showConfirmButton: false
            });

            $(this).val(parcelas);
        }
    });

    $("#receita_repeticoes").on("keyup", function () {
        const value = +$(this).val();
        const parcelas = +$('#receita_pagamento').find(':selected').data('parcelas');

        if (value > parcelas) {
            Swal.fire({
                title: 'Atenção!',
                text: `Número de parcelas para este método de pagamento não pode ser maior que ${parcelas}.`,
                icon: 'info',
                timer: 3000,
                showConfirmButton: false
            });

            $(this).val(parcelas);
        }
    });

    $("#receita_pagamento").on("change", function () {
        const value = $(this).val();
        $('#receita_repeticoes').attr('disabled', value === null || value === undefined || value === "");
        $('#receita_repeticoes').val(value === null || value === undefined || value === "" ? null : 1);
    });

    $("#addRow").click(function () {
        const documento = $('#receita_documento').val();
        const valor = toDecimal($('#receita_valor').val());
        const pagamento = $('#receita_pagamento option:selected').text();
        const data = moment($('#receita_data').val(), 'DD/MM/YYYY');
        const bancoData = $('#receita_pagamento').find(':selected').data('banco');
        const bancoId = $('#receita_pagamento').find(':selected').data('idbanco');
        const banco = bancoId > 0
            ? bancoData
            : `<a href="index.php?do=tipopagamento&acao=editar&id=${$('#receita_pagamento').val()}" target="_blank">Nenhum Banco Vinculado</a>`;

        if (valor && pagamento && data.isValid()) {

            let contador = 0;
            let valorTotal = 0;

            const repeticoes = +$('#receita_repeticoes').val() > 1 ? +$('#receita_repeticoes').val() : 1;
            const dias = $('#receita_pagamento').find(':selected').data('dias');

            if ((valorNota - (valorNotaPago + valor)) >= 0) {
                while (contador < repeticoes) {
                    const uniqueId = `receita_tr_${moment().format('YYYYMMDDHHmmss')}_${contador}`;
                    const newData = moment(data).add(dias * contador, 'days').format('DD/MM/YYYY');

                    let newValor = (valor / repeticoes).toFixed(2);
                    valorTotal += parseFloat((valor / repeticoes).toFixed(2));

                    if ((contador == repeticoes - 1) && repeticoes > 1) {
                        if (valorTotal < valor) {
                            newValor = ((valor / repeticoes) + (valor - valorTotal)).toFixed(2);
                        }
                        else if (valorTotal > valor) {
                            newValor = ((valor / repeticoes) - (valorTotal - valor)).toFixed(2);
                        }
                    }

                    const newRow = `<tr id="${uniqueId}">
                                    <td>${documento}</td>
                                    <td>${banco}</td>
                                    <td>${toCurrency(newValor)}</td>
                                    <td>${pagamento}</td>
                                    <td>${newData}</td>
                                    <td><button type="button" class="receita_remover btn btn-red" data-id="${uniqueId}">X</button></td>
                                </tr>`;

                    $("#myTable tbody").append(newRow);

                    lista.push({
                        processarNotaFiscalReceita: 1,
                        id_unique: uniqueId,
                        tipo: +$('#receita_pagamento').val(),
                        duplicata: $('#receita_documento').val(),
                        data_pagamento: newData,
                        valor: toCurrency(newValor),
                        id_banco: bancoId,
                        id_nota: $('#id_nota').val(),
                        id_empresa: $('#id_empresa').val(),
                        id_cadastro: $('#id_cadastro').val(),
                        modelo: $('#modelo').val(),
                        numero_nota: $('#numero_nota').val(),
                    });

                    valorNotaPago += +newValor;
                    valorNotaPendente = valorNota - valorNotaPago;

                    $('#valor_pago').text(`R$ ${valorNotaPago.toFixed(3).replace('.', ',')}`);
                    $('#valor_pendente').text(`R$ ${valorNotaPendente.toFixed(3).replace('.', ',')}`);

                    contador++;
                }

                $('#receita_documento').val(null);
                $('#receita_valor').val(null);
                $('#receita_banco').val('').trigger('change');
                $('#receita_pagamento').val('').trigger('change');
                $('#receita_data').val(moment().format('DD/MM/YYYY'));
                $('#receita_repeticoes').val(null);

                if ($('#myTable tbody tr').length > 0) {
                    $('#no-records').remove();
                }
            } else {
                Swal.fire({
                    title: 'Atenção!',
                    text: 'Valor das receitas não pode ser maior que o valor da nota.',
                    icon: 'info',
                    timer: 5000,
                    showConfirmButton: false
                });
            }
        } else {
            Swal.fire({
                title: 'Atenção!',
                text: 'Por favor, preencha todos os campos corretamente.',
                icon: 'info',
                timer: 3000,
                showConfirmButton: false
            });
        }
    });

    $("#myTable").on("click", ".receita_remover", function () {
        const rowId = $(this).data("id");
        const row = $(this).closest("tr");

        const valorNaLinha = parseFloat(row.find("td:nth-child(3)").text().replace("R$", "").replace(",", "."));
        row.remove();

        lista = lista.filter(item => item.id_unique !== rowId);

        valorNotaPago -= valorNaLinha;
        valorNotaPendente = valorNota - valorNotaPago;

        $('#valor_pago').text(`R$ ${valorNotaPago.toFixed(2).replace('.', ',')}`);
        $('#valor_pendente').text(`R$ ${valorNotaPendente.toFixed(2).replace('.', ',')}`);

        if ($('#myTable tbody tr').length === 0) {
            $('#myTable tbody').append(`
                <tr id="no-records">
                    <td colspan="6" style="text-align: center;">Não há registros</td>
                </tr>
            `);
        }
    });

    $("#receita_submit").on('click', async function () {
        if (lista.length > 0) {
            if (valorNotaPendente >= 0) {

                $('#receita_submit').attr('disabled', 'disabled');
                $("#overlay").css("display", "flex");

                async function sendRequest(data) {

                    const dados = new URLSearchParams(data).toString();

                    await $.ajax({
                        type: 'post',
                        url: 'controller.php',
                        data: dados,
                        dataType: '*',
                        error: function (e) {
                            valida = false;
                            $("#overlay").css("display", "none");
                        },
                        success: function (response) {

                            if (!response.includes("Sucesso")) {
                                valida = false;
                            }

                            $("#overlay").css("display", "none");
                        }
                    });
                }

                try {
                    for (let i = 0; i < lista.length; i++) {
                        await sendRequest(lista[i]);
                    }

                    if (valida) {
                        Swal.fire({
                            title: 'Sucesso!',
                            text: 'Os registros de receita foram inseridos com sucesso.',
                            icon: 'success',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            $("#overlay").css("display", "flex");
                            window.location.href = `index.php?do=notafiscal&acao=visualizar&id=${lista[0].id_nota}`;
                        });
                    } else {
                        Swal.fire({
                            title: 'Falha no Processo!',
                            text: 'Contate o suporte Sigesis.',
                            icon: 'error',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            $("#overlay").css("display", "flex");
                            window.location.href = `index.php?do=notafiscal&acao=visualizar&id=${lista[0].id_nota}`;
                        });
                    }
                } catch (error) {
                    console.error("Erro durante o envio:", error);
                } finally {
                    $("#overlay").css("display", "none");
                }
            } else {
                Swal.fire({
                    title: 'Atenção!',
                    text: 'Valor das receitas não pode ser maior que o valor da nota.',
                    icon: 'info',
                    timer: 5000,
                    showConfirmButton: false
                });
            }
        } else {
            Swal.fire({
                title: 'Atenção!',
                text: 'Adicione ao menos um receita.',
                icon: 'info',
                timer: 3000,
                showConfirmButton: false
            });
        }
    });
});
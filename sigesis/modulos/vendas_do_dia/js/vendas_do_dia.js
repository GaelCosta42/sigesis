import { SimpleTable } from "../../../assets/plugins/SimpleTable/js/SimpleTable_class.js";
$(document).ready(function () {
    const urlParams = new URLSearchParams(window.location.search);
    const dateParam = urlParams.get('data');    
    if (dateParam) {
        const formattedDate = moment(dateParam, 'DD/MM/YYYY').format("YYYY-MM-DD");
        $('#data').val(formattedDate);
    }

    let tabela_dinamica = new SimpleTable(null, `#dynamic-table`, `#pesquisa-dinamica`, true, 'modulos/vendas_do_dia/load/load_vendas_do_dia.php', '.payload');

    $(document).on('click', '#selecionardata', function () {
        $(this).addClass('payload');
        tabela_dinamica.refresh();
        $(this).removeClass('payload');
    });

    $(document).on('retornoChanged', function (event, newValue) {
        const table = $('#dynamic-table-total');
        const tbody = $('<tbody></tbody>');
        const tfoot = $('<tfoot></tfoot>');
        let total = 0;
    
        table.find('tbody').remove();
        table.find('tfoot').remove();

        if (newValue !== null && typeof newValue === 'object' && Object.keys(newValue).length > 0) {
            for (const [key, value] of Object.entries(newValue)) {
                const numericValue = parseFloat(value);
                total += isNaN(numericValue) ? 0 : numericValue;
    
                const formattedValue = numericValue.toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                });
    
                const row = $('<tr></tr>');
                row.append(`<td>${key}</td>`);
                row.append(`<td>${formattedValue}</td>`);
                tbody.append(row);
            }
        } else {
            const row = $('<tr></tr>');
            row.append('<td colspan="2" style="text-align: center;">Nenhum registro encontrado</td>');
            tbody.append(row);
        }
    
        const totalFormatted = total.toLocaleString('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        });
        const totalRow = $('<tr></tr>');
        totalRow.append('<td><strong>Total</strong></td>');
        totalRow.append(`<td><strong>${totalFormatted}</strong></td>`);
        tfoot.append(totalRow);
    
        table.append(tbody);
        table.append(tfoot);
    });

    $(document).on('click', '.gerarNFEvendaBloqueio', function () {
        const title = $(this).attr('title');
        $('#nota-fiscal .modal-header').html(`
            <button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">Não é possível gerar uma NF-e de venda sem cliente identificado. Identifique o cliente e tente novamente.</h4>
        `);
        $('#nota-fiscal .modal-body').html(`
            <div class="bootbox-body"><p>${title}</p></div>
        `);
        $('#nota-fiscal .modal-footer').html(`<button data-bb-handler="voltar" id="fechar-modal" type="button" class="btn default">Voltar</button>`);
        $('#nota-fiscal').modal('show');
    });

    $(document).on('click', '.gerarNFEvenda', function () {
        const title = $(this).attr('title');
        const id = $(this).data('id');

        $('#nota-fiscal .modal-header').html(`
            <button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">Converter venda em NF-e</h4>
        `);

        $('#nota-fiscal .modal-body').html(`
            <div class="bootbox-body"><p>${title}</p><input hidden value="${id}" id="id-venda"></div>
        `);

        $('#nota-fiscal .modal-footer').html(`<button data-bb-handler="salvar" id="converter-venda" type="button" class="btn blue">Converter venda em NF-e</button><button data-bb-handler="voltar" id="fechar-modal" type="button" class="btn default">Voltar</button>`);
        $('#nota-fiscal').modal('show');
    });

    $(document).on("click", "#converter-venda", function () {
        $.ajax({
            type: "POST",
            url: "controller.php",
            data: { "gerarNFEvenda": $('#id-venda').val() },
            // dataType: "json",
            success: function (response) {
                alert("Sucesso! NF-e gerada.");
                window.location.href = extractURL(response);
            },
            error: function () {
                console.log('Ocorreu um erro ao enviar a requisição.');
            }
        });
    });

    $(document).on('click', '#fechar-modal', function () {
        $('#nota-fiscal').modal('hide');
    });

    $(document).on('click', '#copiar', function () {
        let elemento = $(this);
        if (!elemento.is(':disabled')) {

            elemento.attr('disabled', true);

            $.ajax({
                type: "POST",
                url: "modulos/vendas_do_dia/load/load_vendas_do_dia_botoes.php",
                data: { 'data': $('.calendario').val() },
                dataType: "json",
                success: function (response) {
                    navigator.clipboard.writeText(convertToCOPY(response.dados));
                    setTimeout(() => {
                        elemento.attr('disabled', false);
                    }, 2000);
                },
                error: function () {
                    console.log('Houve um erro ao copiar as informações da tabela.');
                }
            });
        }
    });

    $(document).on('click', '#csv', function () {
        let elemento = $(this);
        if (!elemento.is(':disabled')) {

            elemento.attr('disabled', true);

            $.ajax({
                type: "POST",
                url: "modulos/vendas_do_dia/load/load_vendas_do_dia_botoes.php",
                data: { 'data': $('.calendario').val() },
                dataType: "json",
                success: function (response) {
                    const dadosCsv = convertToCSV(response.dados);
                    const arquivo = new Blob([dadosCsv], { type: 'text/csv;charset=utf-8;' });
                    const url = URL.createObjectURL(arquivo);
                    const linkTemp = document.createElement('a');
                    linkTemp.setAttribute('href', url);
                    linkTemp.setAttribute('download', 'SIGESIS.csv');
                    document.body.appendChild(linkTemp);
                    linkTemp.click();
                    document.body.removeChild(linkTemp);
                    setTimeout(() => {
                        elemento.attr('disabled', false);
                    }, 2000);
                },
                error: function () {
                    console.log('Houve um erro ao baixar o csv.');
                }
            });
        }
    });

    $(document).on('click', '#xlsx', function () {
        let elemento = $(this);
        if (!elemento.is(':disabled')) {
            elemento.attr('disabled', true);

            $.ajax({
                type: "POST",
                url: "modulos/vendas_do_dia/load/load_vendas_do_dia_botoes.php",
                data: { 'data': $('.calendario').val() },
                dataType: "json",
                success: function (response) {

                    var dado_limpo = cleanData(response.dados).map(row => {
                        const entries = Object.entries(row);
                        return Object.fromEntries(entries);
                    });

                    const headers = Object.values(response.dados[0]);
                    const worksheet = XLSX.utils.json_to_sheet(dado_limpo, { skipHeader: true });
                    XLSX.utils.sheet_add_aoa(worksheet, [headers], { origin: 'A1' });

                    const workbook = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(workbook, worksheet, "Dados");

                    const arquivo = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
                    const blob = new Blob([arquivo], { type: 'application/octet-stream' });
                    const url = URL.createObjectURL(blob);
                    const linkTemp = document.createElement('a');
                    linkTemp.setAttribute('href', url);
                    linkTemp.setAttribute('download', 'SIGESIS.xlsx');
                    document.body.appendChild(linkTemp);
                    linkTemp.click();
                    document.body.removeChild(linkTemp);
                    setTimeout(() => {
                        elemento.attr('disabled', false);
                    }, 2000);
                },
                error: function () {
                    console.log('Houve um erro ao baixar o arquivo XLSX.');
                }
            });
        }
    });

    $(document).on('click', '#print', function () {
        let elemento = $(this);
        if (!elemento.is(':disabled')) {

            elemento.attr('disabled', true);

            $.ajax({
                type: "POST",
                url: "modulos/vendas_do_dia/load/load_vendas_do_dia_botoes.php",
                data: { 'data': $('.calendario').val() },
                dataType: "json",
                success: function (response) {
                    let head = `<head><title>SIGESIS</title><link rel="shortcut icon" href="assets/img/favicon.png"><link rel="apple-touch-icon" href="assets/img/favicon_60x60.png"><link rel="apple-touch-icon" sizes="76x76" href="https://localhost/sigesis/assets/img/favicon_76x76.png"><link rel="apple-touch-icon" sizes="120x120" href="https://localhost/sigesis/assets/img/favicon_120x120.png"><link rel="apple-touch-icon" sizes="152x152" href="https://localhost/sigesis/assets/img/favicon_152x152.png"><link href="https://localhost/sigesis/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"><link href="https://localhost/sigesis/assets/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"><link href="https://localhost/sigesis/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"><link href="https://localhost/sigesis/assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"><link href="https://localhost/sigesis/assets/plugins/select2/select2.css" rel="stylesheet" type="text/css"><link href="https://localhost/sigesis/assets/css/profile.css" rel="stylesheet" type="text/css"><link href="https://localhost/sigesis/assets/css/tasks.css" rel="stylesheet" type="text/css"><link href="https://localhost/sigesis/assets/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css"><link href="https://localhost/sigesis/assets/css/plugins.css" rel="stylesheet" type="text/css"><link href="https://localhost/sigesis/assets/css/layout.css" rel="stylesheet" type="text/css"><link href="https://localhost/sigesis/assets/css/themes/default.css" rel="stylesheet" type="text/css"><link href="https://localhost/sigesis/assets/css/custom.css" rel="stylesheet" type="text/css"><link rel="stylesheet" type="text/css" href="https://localhost/sigesis/assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"><link rel="stylesheet" type="text/css" href="https://localhost/sigesis/assets/plugins/bootstrap-datepicker/css/datepicker3.css"><link rel="stylesheet" type="text/css" href="https://localhost/sigesis/assets/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css"><link rel="stylesheet" type="text/css" href="https://localhost/sigesis/assets/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css"><link rel="stylesheet" type="text/css" href="https://localhost/sigesis/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"><link rel="stylesheet" type="text/css" href="https://localhost/sigesis/assets/plugins/typeahead/typeahead.css"><link rel="stylesheet" type="text/css" href="https://localhost/sigesis/assets/plugins/bootstrap-switch/css/bootstrap-switch.min.css"><link rel="stylesheet" type="text/css" href="https://localhost/sigesis/assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"><link rel="stylesheet" type="text/css" href="assets/css/notification.css"></head>`;
                    let bodyHTML = convertToTABLE(response.dados);
                    let paginaImpressao =
                        `<html>
                    ${head}
                    <body>
                    <h1>SIGESIS</h1>
                    <div></div>
                    <table class="table table-bordered table-striped table-condensed dataTable table_advance">
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
                    </tr>
                    </thead>
                    <tbody>
                    ${bodyHTML}
                    </tbody>
                    <tfoot>
                    <tr>
                    <td></td>
                    <td></td>
                    <td>Total desconto: ${formatToReais(response.dados_aux.desconto)}</td>
                    <td>Valor total: ${formatToReais(response.dados_aux.valorTotal)}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    </tr>
                    </tfoot>
                    </table>
                    </body>
                    </html>`;

                    let printWindow = window.open('', '_blank');
                    printWindow.document.open();
                    printWindow.document.write(paginaImpressao);
                    printWindow.document.close();
                    printWindow.print();

                    setTimeout(() => {
                        elemento.attr('disabled', false);
                    }, 2000);
                },
                error: function () {
                    console.log('Houve um erro ao copiar as informações da tabela.');
                }
            });
        }
    });

    $(document).on('click', '#pdf', function () {
        let elemento = $(this);
        if (!elemento.is(':disabled')) {
            elemento.attr('disabled', true);

            $.ajax({
                type: "POST",
                url: "modulos/vendas_do_dia/load/load_vendas_do_dia_botoes.php",
                data: { 'data': $('.calendario').val() },
                dataType: "json",
                success: function (response) {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF('landscape');

                    const head = [['Cód Venda', 'Cliente', 'Desconto', 'Valor total', 'Tipo de pagamento', 'Vendedor', 'Cancelada', 'Status NFC-e', 'Número nota', 'Motivo']];
                    const body = cleanData(response.dados.slice(1)).map(row => Object.values((row)));

                    const foot = [
                        [
                            '',
                            '',
                            `${formatToReais(response.dados_aux.desconto)}`,
                            `${formatToReais(response.dados_aux.valorTotal)}`,
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                        ]
                    ];

                    doc.autoTable({
                        head: head,
                        body: body,
                        foot: foot,
                        showFoot: 'everyPage',
                        columnStyles: {
                            0: { cellWidth: 'auto' },
                            1: { cellWidth: 'auto' },
                            2: { cellWidth: 'auto' },
                            3: { cellWidth: 30 },
                            4: { cellWidth: 'auto' },
                            5: { cellWidth: 'auto' },
                            6: { cellWidth: 'auto' },
                            7: { cellWidth: 'auto' },
                            8: { cellWidth: 'auto' },
                            9: { cellWidth: 'auto' },
                        },
                    });

                    doc.save('SIGESIS.pdf');
                    setTimeout(() => {
                        elemento.attr('disabled', false);
                    }, 2000);
                },
                error: function () {
                    console.log('Houve um erro ao baixar o PDF.');
                }
            });
        }
    });

    function convertToCOPY(data) {
        return data.map(row => row.map(cell => cleanHTML(cell)).slice(0, -1).join('\t')).join('\n');
    }

    function convertToCSV(data) {
        return data.map(row => row.map(cell => cleanHTML(cell)).slice(0, -1).join(',')).join('\n');
    }

    function convertToTABLE(data) {
        return data.slice(1).map(row => {
            let cells = row.slice(0, -1).map(cell => `<td>${cleanHTML(cell)}</td>`).join('');
            return `<tr>${cells}</tr>`;
        }).join('');
    }

    function formatDateToBR(dateString) {
        if (!dateString) return '';
        const [year, month, day] = dateString.split('-');
        return `${day}/${month}/${year}`;
    }

    function extractURL(inputString) {
        const regex = /index\.php\?[^#]+/g;
        const matches = inputString.match(regex);
        return matches ? matches[0] : null;
    }

    function formatToReais(value) {
        return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }

    function cleanHTML(cell) {
        return cell.replace(/<\/?[^>]+(>|$)/g, "");
    }

    function cleanData(data) {
        return data.map(row => {
            let cleanedRow = {};
            for (let key in row) {
                cleanedRow[key] = cleanHTML(row[key]);
            }
            return cleanedRow;
        });
    }

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
import { SimpleTable } from '../../../assets/plugins/SimpleTable/js/SimpleTable_class.js';
$(document).ready(function () {
    let tabela_dinamica_nfce = new SimpleTable(
        null,
        `#dynamic-table-nfce`,
        `#pesquisa-dinamica-nfce`,
        true,
        './modulos/notas_negadas/loads/load_nfce.php',
        '.payload'
    );

    let tabela_dinamica_nfe = new SimpleTable(
        null,
        `#dynamic-table-nfe`,
        `#pesquisa-dinamica-nfe`,
        true,
        './modulos/notas_negadas/loads/load_nfe.php',
        '.payload'
    );
})
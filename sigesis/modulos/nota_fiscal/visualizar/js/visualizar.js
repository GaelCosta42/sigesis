$(document).ready(function () {

    $('.gerar_nota_fiscal').on('click', function () {
        const idNota = +$(this).data('idnota');
        const controller = +$(this).data('controller');

        $.ajax({
            type: "POST",
            url: "modulos/nota_fiscal/visualizar/loads/load_valida.php",
            data: {
                id_nota: idNota
            },
            dataType: "json",
            beforeSend: function () {
                $("#overlay").css("display", "flex");
            },
            success: function (response) {
                $("#overlay").css("display", "none");
                if (response.valida[0].validado) {
                    Swal.fire({
                        title: 'Processando...',
                        text: 'Aguarde o processamento da nota fiscal.',
                        icon: 'info',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        $("#overlay").css("display", "flex");
                        if (controller) {
                            window.open(`nfe.php?debug=1&id=${idNota}`, '_blank');
                        } else {
                            window.open(`nfe.php?id=${idNota}`, '_blank');
                        }
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Atenção!',
                        text: 'Valor de receita é diferente do valor total da nota, realize os ajustes antes de gerar uma nota fiscal.',
                        icon: 'error',
                        timer: 8000,
                        showConfirmButton: false
                    })
                }
            }
        })
    });
});
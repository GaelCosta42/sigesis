var TableEditable = function () {

    var handleTable = function () {

        function restoreRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);

            for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                oTable.fnUpdate(aData[i], nRow, i, false);
            }

            oTable.fnDraw();
        }

        function editRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);
            jqTds[4].innerHTML = '<input type="text" class="form-control input-xsall caps" value="' + aData[4] + '">';
            jqTds[6].innerHTML = '<input type="text" id="alterar" class="form-control input-xsall decimal" value="' + aData[6] + '">';
            jqTds[7].innerHTML = '<input type="text" class="form-control input-xsall decimal" value="' + aData[7] + '">';
            jqTds[10].innerHTML = '<a href="javascript:void(0);" class="btn btn-xs blue-steel salvar" title="Salvar"><i class="fa fa-save"></i></a><a href="javascript:void(0);" class="btn btn-xs yellow cancelar" title="Cancelar"><i class="fa fa-ban"></i></a>';
			$('.decimal').maskMoney({thousands:'.', decimal:',', symbolStay: false, allowNegative: true});
			$('#alterar').focus();
			$('#alterar').select();
        }

        function saveRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
			var unidade = jqInputs[0].value;
			var quantidade = jqInputs[1].value;
			var valor = jqInputs[2].value;
            var aData = oTable.fnGetData(nRow);
			var id = aData[0];
			jQuery.ajax({
				type: 'POST',
				url: 'controller.php',
				data: 'processarAlterarCotacao=1&id='+id+'&quantidade='+quantidade+'&unidade='+unidade+'&valor='+valor,
				success: function( data )
				{
					var response = data.split("#");
					$.bootstrapGrowl(response[0], {
						ele: "body",
						type: response[1], // (null, 'info', 'danger', 'success', 'warning')
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						delay: 2500,
						stackup_spacing: 10
					});
				}
			});
            oTable.fnUpdate(unidade, nRow, 4, false);
            oTable.fnUpdate(quantidade, nRow, 6, false);
            oTable.fnUpdate(valor, nRow, 7, false);
            oTable.fnUpdate('<a href="javascript:void(0);" class="btn btn-xs blue alterar" title="Alterar"><i class="fa fa-pencil"></i></a>', nRow, 10, false);
            oTable.fnDraw();
        }

        function cancelEditRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
            oTable.fnUpdate(jqInputs[0].value, nRow, 4, false);
            oTable.fnUpdate(jqInputs[1].value, nRow, 6, false);
            oTable.fnUpdate(jqInputs[2].value, nRow, 7, false);
            oTable.fnUpdate('<a href="javascript:void(0);" class="btn btn-xs blue alterar" title="Alterar"><i class="fa fa-pencil"></i></a>', nRow, 10, false);
            oTable.fnDraw();
        }

        var table = $('#table_tabela');

        var oTable = table.dataTable({
			dom: 'Bfrtip',
			buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print'
			],
			"iDisplayLength": 50,
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"language":{
				"sSearch": "<span>Buscar:</span> ",
				"sInfo": "Mostrando de <span>_START_</span> até <span>_END_</span> de <span>_TOTAL_</span> registros",
				"sLengthMenu": "_MENU_ <span>registros por página</span>",
				"sProcessing":   "Processando...",
				"sZeroRecords":  "Não foram encontrados resultados",
				"sInfoEmpty":    "Mostrando de 0 até 0 de 0 registros",
				"sInfoFiltered": "(filtrado de _MAX_ registros no total)",
				"sInfoPostFix":  "",
				"oPaginate": {
					"sFirst":    "Primeiro",
					"sPrevious": "Anterior",
					"sNext":     "Seguinte",
					"sLast":     "Último"
				}
			},
			"stateSave": true
		});

        var nEditing = null;
        var nNew = false;

        table.on('click', '.cancelar', function (e) {
            e.preventDefault();
            if (nNew) {
                oTable.fnDeleteRow(nEditing);
                nEditing = null;
                nNew = false;
            } else {
                restoreRow(oTable, nEditing);
                nEditing = null;
            }
        });

        table.on('click', '.alterar', function (e) {
            e.preventDefault();

            /* Get the row as a parent of the link that was clicked on */
            var nRow = $(this).parents('tr')[0];

            if (nEditing !== null && nEditing != nRow) {
                /* Currently editing - but not this row - restore the old before continuing to edit mode */
                restoreRow(oTable, nEditing);
                editRow(oTable, nRow);
                nEditing = nRow;
            } else {
                /* No edit in progress - let's start one */
                editRow(oTable, nRow);
                nEditing = nRow;
            }
        });

        table.on('click', '.salvar', function (e) {
            e.preventDefault();

            /* Get the row as a parent of the link that was clicked on */
            var nRow = $(this).parents('tr')[0];

            if (nEditing == nRow) {
                /* Editing this row and want to save it */
                saveRow(oTable, nEditing);
                nEditing = null;
            } else {
                /* No edit in progress - let's start one */
                editRow(oTable, nRow);
                nEditing = nRow;
            }
        });

        table.on('click', '.validar', function (e) {
			var id = $(this).attr('id');
			var id_cotacao = $(this).attr('id_cotacao');
			var id_produto = $(this).attr('id_produto');
			jQuery.ajax({
				type: 'POST',
				url: 'controller.php',
				data: 'processarValidarItem=1&id='+id+'&id_cotacao='+id_cotacao+'&id_produto='+id_produto,
				success: function( data )
				{
					var response = data.split("#");
					$.bootstrapGrowl(response[0], {
						ele: "body",
						type: response[1], // (null, 'info', 'danger', 'success', 'warning')
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						delay: 2500,
						stackup_spacing: 10
					});
					location.reload();
				}
			});
        });
    }

    return {

        //main function to initiate the module
        init: function () {
            handleTable();
        }

    };

}();
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
            jqTds[2].innerHTML = '<input type="text" id="alterar" class="form-control input-xsall decimal" value="' + aData[2] + '">';
            jqTds[3].innerHTML = '<a href="javascript:void(0);" class="btn btn-sm blue-steel salvar" title="Salvar"><i class="fa fa-save"></i></a><a href="javascript:void(0);" class="btn btn-sm yellow cancelar" title="Cancelar"><i class="fa fa-ban"></i></a>';
			$('.decimal').maskMoney({thousands:'.', decimal:',', symbolStay: false, allowNegative: true});
			$('#alterar').focus();
        }

        function saveRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
			var quantidade = jqInputs[0].value;
            var aData = oTable.fnGetData(nRow);
			var id = aData[0];
			jQuery.ajax({
				type: 'POST',
				url: 'controller.php',
				data: 'processarAlterarPedido=1&id='+id+'&quantidade='+quantidade,
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
						delay: 500,
						stackup_spacing: 10
					});
				}
			});
            oTable.fnUpdate(quantidade, nRow, 2, false);
            oTable.fnUpdate('<a href="javascript:void(0);" class="btn btn-sm blue alterar" title="Alterar"><i class="fa fa-pencil"></i></a><a href="javascript:void(0);" class="btn btn-sm red delete" title="Apagar"><i class="fa fa-times"></i></a>', nRow, 3, false);
            oTable.fnDraw();
        }

        function cancelEditRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
            oTable.fnUpdate(jqInputs[0].value, nRow, 2, false);
            oTable.fnUpdate('<a href="javascript:void(0);" class="btn btn-sm blue alterar" title="Alterar"><i class="fa fa-pencil"></i></a><a href="javascript:void(0);" class="btn btn-sm red delete" title="Apagar"><i class="fa fa-times"></i></a>', nRow, 3, false);
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

        table.on('click', '.delete', function (e) {
            e.preventDefault();
			var nRow = $(this).parents('tr')[0];
            var aData = oTable.fnGetData(nRow);
			var id = aData[0];
			jQuery.ajax({
				type: 'POST',
				url: 'controller.php',
				data: 'apagarProdutoPedido='+id,
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
						delay: 500,
						stackup_spacing: 10
					});
					location.reload();
				}
			});
			return false;
        });

        table.on('click', '.ativar_produto', function (e) {
            e.preventDefault();
			var nRow = $(this).parents('tr')[0];
            var aData = oTable.fnGetData(nRow);
			var id = aData[0];
			jQuery.ajax({
				type: 'POST',
				url: 'controller.php',
				data: 'ativarProdutoPedido='+id,
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
						delay: 500,
						stackup_spacing: 10
					});
					location.reload();
				}
			});
			return false;
        });

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
    }

    return {

        //main function to initiate the module
        init: function () {
            handleTable();
        }

    };

}();
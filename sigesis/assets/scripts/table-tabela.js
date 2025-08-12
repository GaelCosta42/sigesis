var TableEditable = function () {

    var handleTable = function () {

        function restoreRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);
            aData[10] = '';
            oTable.fnUpdate(aData.id, nRow, 0, false);
            oTable.fnUpdate(aData.codigo, nRow, 1, false);
            oTable.fnUpdate(aData.codigobarras, nRow, 2, false);
            oTable.fnUpdate(aData.nome, nRow, 3, false);
            oTable.fnUpdate(aData.grupo, nRow, 4, false);
            oTable.fnUpdate(aData.categoria, nRow, 5, false);
            oTable.fnUpdate(aData.estoque, nRow, 6, false);
            oTable.fnUpdate(aData.valor_custo, nRow, 7, false);
            oTable.fnUpdate(aData.percentual, nRow, 8, false);
            oTable.fnUpdate(aData.valor_venda, nRow, 9, false);
            oTable.fnUpdate(aData.valor_venda, nRow, 10, false);
        }

		function editRow(oTable, nRow) {
		  var aData = oTable.fnGetData(nRow);
		  var jqTds = $('>td', nRow);
		  jqTds[9].innerHTML = '<input type="text" class="form-control input-small moedap" value="' + aData.valor_venda + '">';
		  jqTds[10].innerHTML = '<a href="javascript:void(0);" class="btn btn-sm blue salvar" title="Salvar"><i class="fa fa-save"></i></a><a href="javascript:void(0);" class="btn btn-sm yellow cancelar" title="Cancelar"><i class="fa fa-ban"></i></a>';
		  $('.moedap').maskMoney({symbol:'R$', thousands:'.', decimal:',', precision: 2, symbolStay: true, allowNegative: true});
		  $('.moedap').focus();
		  $('.moedap').select();
		}

        function saveRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
			var valor = jqInputs[0].value;
            var aData = oTable.fnGetData(nRow);
			var id = aData.id;
			jQuery.ajax({
				type: 'POST',
				url: 'controller.php',
				data: 'processarAlterarValor=1&id='+id+'&valor_venda='+valor,
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
            oTable.fnUpdate(valor, nRow, 9, false);
            oTable.fnUpdate('<a href="javascript:void(0);" class="btn btn-sm green editar" title="Alterar"><i class="fa fa-usd"></i></a><a href="javascript:void(0);" class="btn btn-sm red delete" title="Apagar"><i class="fa fa-times"></i></a>', nRow, 10, false);

			$('#table_listar_tabela_preco').DataTable();
        }

        function cancelEditRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
            oTable.fnUpdate(jqInputs[0].value, nRow, 8, false);
            oTable.fnUpdate('<a href="javascript:void(0);" class="btn btn-sm green editar" title="Alterar"><i class="fa fa-usd"></i></a><a href="javascript:void(0);" class="btn btn-sm red delete" title="Apagar"><i class="fa fa-times"></i></a>', nRow, 9, false);
        }

        var table = $('#table_listar_tabela_preco');

        var oTable = table.dataTable();

        var nEditing = null;
        var nNew = false;

        table.on('click', '.delete', function (e) {
            e.preventDefault();
			var nRow = $(this).parents('tr')[0];
            var aData = oTable.fnGetData(nRow);
			var id = aData.id;
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você deseja apagar este produto?";
			bootbox.dialog({
                    message: aviso,
                    title: "Apagar produto",
                    buttons: {
                      salvar: {
                        label: "Apagar produto",
                        className: "red",
                        callback: function() {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'apagarProdutoTabelaPreco='+id,
								success: function( data )
								{
									oTable.fnDeleteRow(nRow);

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
                        }
                      },
					  voltar: {
                        label: "Voltar",
                        className: "default"
                      }
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

        table.on('click', '.editar', function (e) {
            e.preventDefault();

            /* Get the row as a parent of the link that was clicked on */
            var nRow = $(this).parents('tr')[0];

            if (nEditing !== null && nEditing != nRow)
            {
                /* Currently editing - but not this row - restore the old before continuing to edit mode */

                restoreRow(oTable, nEditing);
                editRow(oTable, nRow);
                nEditing = nRow;

            } else
            {
                /* No edit in progress - let's start one */
                editRow(oTable, nRow);
                nEditing = nRow;
            }
        });

        table.on('click', '.salvar', function (e) {
            e.preventDefault();

            /* Get the row as a parent of the link that was clicked on */
            var nRow = $(this).parents('tr')[0];

            if (nEditing == nRow)
            {
                /* Editing this row and want to save it */
                saveRow(oTable, nEditing);
                nEditing = null;
            } else
            {
                /* No edit in progress - let's start one */
                editRow(oTable, nRow);
                nEditing = nRow;
            }
        });
    }

    return{

        //main function to initiate the module
        init: function () {
            handleTable();
        }
    };

}();
/**
Core script to handle the entire theme and core functions
Arquivo de layout e funcoes javascript
**/

$(document.documentElement).append(`
    <div id="overlay">
        <span class="loader"></span>
    </div>
`);

var Layout = function () {

	var ProdutoID;

	var layoutImgPath = './assets/img/';

	var layoutCssPath = './assets/css/';

	var resBreakpointMd = Metronic.getResponsiveBreakpoint('md');

	//
	///////////////////////////
	// INICIO NOTIFICAÇÃO N2 //
	///////////////////////////
	//

	let alarme = new Audio('./assets/audio/pedido_novo.mp3');
	alarme.volume = 0.5;

	function verificaURL(url_atual, url_true) {
		if (url_atual == url_true) {
			alarme.muted = true;
		}
	}

	let urlAtual = document.URL;
	let urlLink = localStorage.getItem('link_notificacao');
	verificaURL(urlAtual, urlLink);

	function afterIndex(url_all) {
		url_all = url_all.split('//')[1];
		url_all = url_all.split('/')[1];

		return url_all;
	}

	function beforeIndex(url, separador) {
		return url.split(separador, 1)[0];
	}

	function joinFunction(url, link) {
		let x = beforeIndex(url, "index");
		let resultado = x + link;
		localStorage.setItem('link_notificacao', resultado);
	}

	function redirecionar(link) {
		window.location.href = link;
	}

	function timeNotification() {
		var tipo_sistema = $("#tipo_sistema").val();
		if (tipo_sistema == 4) {
			$('.notification-aberto').click(function () {
				let link = $('.notification-aberto').attr('link');
				redirecionar(link);

				let url = window.location.href;
				joinFunction(url, link);

			});

			buttonNotification();
			setInterval(buttonNotification, 12000);
		}
	}

	function buttonNotification() {
		$.getJSON('controller.php?pedidoNotificacao', function (data) {
			if (data.code === 200) {
				document.title = `(${data.quantidade_vendas_abertas}) Novo Pedido`;
				alarme.play();

				if ($('.notification-aberto').hasClass('hidden')) $('.notification-aberto').removeClass('hidden');

				$('.notification-aberto .button-time-notification-quantidade').html(data.quantidade_vendas_abertas);

				let url = `index.php?do=vendas&acao=vendaspedidosentrega`;
				$('.notification-aberto').attr('link', url);
			} else {
				if (!$('.notification-aberto').hasClass('hidden')) {
					$('.notification-aberto').addClass('hidden');
				}
			}
		});
	}

	timeNotification();
	//
	///////////////////////
	// FINAL NOTIFICAÇÃO //
	///////////////////////
	//

	/**
	 * FUNÇÕES DE AJUDA
	 */

	// Converte o valor em float para real
	function floatParaReal(float) {
		let moeda = 0;
		if (float !== '') {
			float = parseFloat(float);
			moeda = float.toFixed(2);
			moeda = moeda.toString()
			moeda = 'R$ ' + moeda.replace('.', ',');
		}
		return moeda;
	}

	// Converte o valor em real para float
	function realParaFloat(moeda) {
		let float = 0;
		if (moeda !== '') {
			float = moeda.replace('R$ ', '');
			float = float.replace('.', '');
			float = float.replace(',', '.');
			float = parseFloat(float);
		}
		return float;
	}

	$(document).ready(function () {
		$('#select_cfop_nfe').on('change.select2', function () {
			let option = $(this).find('option:selected')
			let texto = option[0].attributes.descricao.value
			natureza_operacao_nfe.value = texto.slice(0, 60)
		})
	})

	//Selecionar equipamentos de cliente para ordem de serviço
	$('#id_cadastro_os').change(function () {
		var tipo_sistema = $("#tipo_sistema").val();
		if (tipo_sistema == 5) {
			var id_cliente = $("#id_cadastro_os").val();
			$("#id_equipamento_os").empty();
			jQuery.ajax({
				type: 'post',
				url: 'webservices/buscaequipamentocliente.php',
				data: 'id_cliente=' + id_cliente,
				success: function (data) {
					$("#id_equipamento_os").html(data);
					$("#id_equipamento_os option:first").attr('selected', true);
					$("#id_equipamento_os").select2({
						allowClear: true
					});
				}
			});
		}
	});

	//BOX DIALOG Definir Entregador
	if ($('a.ordem_produto_preco').length > 0) {
		$('a.ordem_produto_preco').click(function () {
			var id_ordem_itens = $(this).attr('id');
			var nome_produto = $(this).attr('nome');
			var valor_atual_produto = $(this).attr('valor');
			var qtde_atual_produto = $(this).attr('qtde');
			var total_atual_produto = $(this).attr('total');

			$("#produto_atual_os").val(nome_produto);
			$("#novo_valor_produto_os").val(valor_atual_produto);
			$("#nova_qtde_produto_os").val(qtde_atual_produto);
			$("#novo_total_produto_os").val(total_atual_produto);

			$("#id_ordem_itens").remove();
			$("#ordem_produto_preco_form").append('<input name="id_ordem_itens" id="id_ordem_itens" type="hidden" value="' + id_ordem_itens + '" />');
			$("#ordem_produto_preco").modal('show');
			$("#novo_valor_produto_os").focus().select();
			return false;
		});
	};

	// CALCULAR Valor Total do Produto com Valor e Quantidade
	$("#novo_valor_produto_os").keyup(function () {
		var valor_produto = $("#novo_valor_produto_os").val();
		var v = valor_produto.replace('.', '');
		v = v.replace(',', '.');
		v = v.replace('R$', '');
		v = parseFloat(v);
		if (isNaN(v)) {
			v = 0;
		}

		var quantidade_produtos = $("#nova_qtde_produto_os").val();
		var q = quantidade_produtos.replace('.', '');
		q = q.replace(',', '.');
		q = q.replace('R$', '');
		q = parseFloat(q);
		if (isNaN(q)) {
			q = 0;
		}

		var novo_valor_total = v * q;
		valor_produto = novo_valor_total.toFixed(2);
		valor_produto = valor_produto.toString();
		valor_produto = valor_produto.replace('.', ',');
		$('#novo_total_produto_os').val('R$ ' + valor_produto);
	});

	// CALCULAR Valor Total do Produto com Valor e Quantidade
	$("#nova_qtde_produto_os").keyup(function () {
		var valor_produto = $("#novo_valor_produto_os").val();
		var v = valor_produto.replace('.', '');
		v = v.replace(',', '.');
		v = v.replace('R$', '');
		v = parseFloat(v);
		if (isNaN(v)) {
			v = 0;
		}

		var quantidade_produtos = $("#nova_qtde_produto_os").val();
		var q = quantidade_produtos.replace('.', '');
		q = q.replace(',', '.');
		q = q.replace('R$', '');
		q = parseFloat(q);
		if (isNaN(q)) {
			q = 0;
		}

		var novo_valor_total = v * q;
		valor_produto = novo_valor_total.toFixed(2);
		valor_produto = valor_produto.toString();
		valor_produto = valor_produto.replace('.', ',');
		$('#novo_total_produto_os').val('R$ ' + valor_produto);
	});

	//Adicionar Ordem de Serviço através da seleção de cliente
	$('#id_cadastro_os').ready(function () {
		var tipo_sistema = $("#tipo_sistema").val();
		if (tipo_sistema == 5) {
			var id_cliente = $("#id_cadastro_os").val();
			$("#id_equipamento_os").empty();
			jQuery.ajax({
				type: 'post',
				url: 'webservices/buscaequipamentocliente.php',
				data: 'id_cliente=' + id_cliente,
				success: function (data) {
					$("#id_equipamento_os").html(data);
					$("#id_equipamento_os option:first").attr('selected', true);
					$("#id_equipamento_os").select2({
						allowClear: true
					});
				}
			});
		}
	});

	//BOX DIALOG Apagar um registro
	if ($('.concluirOrcamento').length > 0) {
		$('.concluirOrcamento').click(function () {
			var id = $(this).attr('id');
			var acao = $(this).attr('acao');
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Concluir este orçamento e disponibilizar para o cliente?";
			bootbox.dialog({
				message: aviso,
				title: "Concluir orçamento",
				buttons: {
					salvar: {
						label: "Concluir orçamento",
						className: "yellow-casablanca",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: acao + '=' + id,
								success: function (data) {
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	//BOX DIALOG para aprovar um orçamento e autorizar a Ordem de Serviço
	if ($('.aprovarOrcamento').length > 0) {
		$('.aprovarOrcamento').click(function () {
			var id = $(this).attr('id');
			var acao = $(this).attr('acao');
			var parent = $(this).parents("tr");
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Cliente aprovou o orçamento. Autorizar este serviço.";
			bootbox.dialog({
				message: aviso,
				title: "Aprovar ordem de serviço",
				buttons: {
					salvar: {
						label: "Aprovar ordem de serviço",
						className: "yellow-lemon",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: acao + '=' + id,
								success: function (data) {
									parent.fadeOut(400, function () {
										parent.remove();
									});
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	//BOX DIALOG para aprovar um orçamento e autorizar a Ordem de Serviço
	if ($('.finalizarServico').length > 0) {
		$('.finalizarServico').click(function () {
			var id = $(this).attr('id');
			var acao = $(this).attr('acao');
			var parent = $(this).parents("tr");
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Confirma a finalização deste serviço";
			bootbox.dialog({
				message: aviso,
				title: "Finalizar serviço",
				buttons: {
					salvar: {
						label: "Finalizar serviço",
						className: "green",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: acao + '=' + id,
								success: function (data) {
									parent.fadeOut(400, function () {
										parent.remove();
									});
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	// CALCULAR Valor Total do ORÇAMENTO
	$("#valor_servico").keyup(function () {
		var valor_servico = $("#valor_servico").val();
		var v = valor_servico.replace('.', '');
		v = v.replace(',', '.');
		v = v.replace('R$', '');
		v = parseFloat(v);
		if (isNaN(v)) {
			v = 0;
		}

		var valor_produtos = $("#valor_produtos").val();
		var p = valor_produtos.replace('.', '');
		p = p.replace(',', '.');
		p = p.replace('R$', '');
		p = parseFloat(p);
		if (isNaN(p)) {
			p = 0;
		}

		var valor_adicional = $("#valor_adicional").val();
		var a = valor_adicional.replace('.', '');
		a = a.replace(',', '.');
		a = a.replace('R$', '');
		a = parseFloat(a);
		if (isNaN(a)) {
			a = 0;
		}

		var valor_desconto = $("#valor_desconto").val();
		var d = valor_desconto.replace('.', '');
		d = d.replace(',', '.');
		d = d.replace('R$', '');
		d = parseFloat(d);
		if (isNaN(d)) {
			d = 0;
		}

		if (d > (v + p + a)) {
			alert('Desconto maior que o valor total da venda.');
			$('#valor_servico').focus().select();
			return false;
		}

		var valor_pagar = (v + p + a) - d;
		valor_pagar = valor_pagar.toFixed(2);
		valor_pagar = valor_pagar.toString();
		valor_pagar = valor_pagar.replace('.', ',');
		$('#valor_total_servico').val('R$ ' + valor_pagar);
	});

	//BOX DIALOG Para confirmar a Duplicação de uma Nota Fiscal
	if ($('.duplicar_nfe').length > 0) {
		$('.duplicar_nfe').click(function () {
			var id = $(this).attr('id');
			var aviso = "Você deseja duplicar esta Nota Fiscal?";
			bootbox.dialog({
				message: aviso,
				title: "Duplicar Nota Fiscal",
				buttons: {
					salvar: {
						label: "Duplicar Nota Fiscal",
						className: "blue-chambray",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'duplicarNotaFiscal=1&id_nota=' + id,
								success: function (data) {
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
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	// SUBMIT - Desabilitar o botão SUBMIT
	if ($('.btn-submit').length > 0) {
		$('.btn-submit').on('click', function () {
			var el = this;
			el.disabled = 'disabled';
			var is_modal = $(this).parents('div').hasClass('modal-footer');
			if (is_modal) {
				var id = $(this).parents('div').parents('div').parents('div').parents('div').attr('id');
				$("#" + id).modal('hide');
			}
			$.bootstrapGrowl("EM PROCESSAMENTO!", {
				ele: "body",
				type: "warning",
				offset: {
					from: "top",
					amount: 50
				},
				align: "center",
				width: "auto",
				delay: 1000,
				stackup_spacing: 10
			});
			$(this).parents('form:first').submit();
			setTimeout(function () {
				el.removeAttribute('disabled');
			}, 3000);
		});
	};

	// Proteção contra duplo cliques
	if ($('.btn-protect').length > 0) {
		$('.btn-protect').on('click', function () {
			var el = this;
			el.disabled = 'disabled';
			setTimeout(function () {
				el.removeAttribute('disabled');
			}, 3000);
		});
	};

	// SUBMIT - Salvar e adicionar novo
	$('#novo').click(function () {
		$('#admin_form').append("<input name='novo' type='hidden' value='1' />");
		$('#admin_form').submit();
	});

	// SUBMIT - Salvar venda
	$('.btnsalvar').click(function () {
		$('#admin_form').append("<input name='salvar' type='hidden' value='1' />");
		$('#admin_form').submit();
	});

	// SUBMIT - Salvar venda crediário
	$('.btncrediario').click(function () {
		let inputValorNormal = document.querySelectorAll(".input-valor-normal")
		inputValorNormal.forEach((campo) => {
			campo.disabled = false
		})
		$('#admin_form').append("<input name='salvar_crediario' type='hidden' value='1' />");
		$('#admin_form').submit();
	});

	// CREDIARIO - Calcular valor a pagar
	// CREDIARIO - Pagar crediario
	if ($('.somar_pagar').length > 0) {
		$('.somar_pagar').click(function () { // FF
			setTimeout(function () {  // para "forçar" o "check" dos itens ocorrer primeiro que a soma
				atualiza_soma();
			}, 1);

		});
	};

	// SUBMIT - Salvar orçaamento na venda
	$('.btn-salvar-orcamento').click(function () {
		$('#admin_form').append("<input name='salvar_orcamento' type='hidden' value='1' />");
		$('#admin_form').submit();
	});

	function atualiza_soma() {
		var soma = 0;
		$('.somar_pagar').each(function (indice, item) {
			var checked = $(item).is(":checked");
			if (checked) {
				var i = $(item).val();
				var temp = i.split("#");
				var v = parseFloat(temp[1]);
				if (!isNaN(v)) {
					soma += v;
				}
			}
		});
		soma = soma * (-1);
		soma = soma.toFixed(2);

		var total_pagar = $("#total_pagar").val();
		var pagar = total_pagar.replace('.', '');
		pagar = pagar.replace(',', '.');
		pagar = pagar.replace('R$ ', '');
		pagar = parseFloat(pagar);
		if (isNaN(pagar)) {
			pagar = 0;
		}

		if (soma == 0 || soma > pagar) {
			$("#soma_pagar_total").text(total_pagar);
			$("#soma_pagar_total2").text(total_pagar);
			$("#valor_pagamento").val(total_pagar);
			$("#valor_pagar").val(total_pagar);
		} else {
			var resultado = soma.toString();
			resultado = 'R$ ' + resultado.replace('.', ',');
			$("#soma_pagar_total").text(resultado);
			$("#soma_pagar_total2").text(resultado);
			$("#valor_pagamento").val(resultado);
			$("#valor_pagar").val(resultado);
		}
	}

	if ($('.checkboxes-crediario-pagamento').length > 0) {
		$('.checkboxes-crediario-pagamento').change(function () {
			var quantidade = 0;
			var valor = 0;
			$('.checkboxes-crediario-pagamento').each(function () {
				var checked = $(this).is(":checked");
				if (checked) {
					quantidade++;
					valor += parseFloat($(this).attr('valor_pago'));
				}
			});
			var valor_final = valor.toFixed(2).toString();
			$('#valor_a_cancelar').val(valor_final);
			valor_final = 'R$ ' + valor_final.replace('.', ',');
			$('.pagamentos-selecionados').html(quantidade);
			$('.valor-a-pagar-crediario').html(valor_final);
			if (quantidade > 0) {
				$('#apagarPagamentoCrediario').attr('disabled', false);
			} else {
				$('#apagarPagamentoCrediario').attr('disabled', true);
			}
		});
	}

	// CREDIARIO - Pagar crediario
	if ($('#pagarcrediario').length > 0) {
		$('#pagarcrediario').click(function () {
			$("#pagar-crediario").modal('hide');
			var admin_form = $("#admin_form").serialize();
			var pagar_form = $("#pagar_form").serialize();
			var valor_pagar = $("#valor_pagar").val();
			var total_pagar = $("#total_pagar").val();

			jQuery.ajax({
				type: 'POST',
				url: 'controller.php',
				data: 'processarPagamentoCrediario=1&' + pagar_form + '&' + admin_form + '&valor_pagar=' + valor_pagar + '&total_pagar=' + total_pagar,
				success: function (data) {
					var response = data.split("#");
					$.bootstrapGrowl(response[0], {
						ele: "body",
						type: response[1],
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						stackup_spacing: 10
					});
					if (response[2] == "1")
						setTimeout(function () {
							window.location.href = response[3];
						}, 1000);
				}
			});
			return false;
		});
	};

	if ($('#valor_pagamento').length > 0) {
		$('#valor_pagamento').keyup(function () {
			let valor = $(this).val();
			let v = valor.replace('.', '');
			v = v.replace(',', '.');
			v = parseFloat(v.replace('R$', ''));

			if (v < 0) {
				alert('Valor NÃO PODE ser negativo.');
				v = v * (-1);
				$(this).val(floatParaReal(v));
			}

			let valor_desconto = $('#valor_desconto_crediario').val();
			valor_desconto = valor_desconto.replace('.', '');
			valor_desconto = valor_desconto.replace(',', '.');
			valor_desconto = parseFloat(valor_desconto.replace('R$', ''));

			let valor_pagar = $('#soma_pagar_total').text();
			valor_pagar = valor_pagar.replace('R$', '');
			valor_pagar = valor_pagar.replace('.', '');
			valor_pagar = parseFloat(valor_pagar.replace(',', '.'));

			if (v !== valor_pagar) {
				$('#valor_desconto_crediario').attr('disabled', true);
				$('#valor_desconto_crediario').val(0);
			}
			else {
				$('#valor_desconto_crediario').attr('disabled', false);
			}
		});
	}

	// BOX DIALOG - Atualizar valor do crediário dos clientes
	if ($('a.atualizar_crediario').length > 0) {
		$('a.atualizar_crediario').click(function () {
			var id = $(this).attr('id');
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Deseja atualizar o valor do crediário de todos os clientes?";
			bootbox.dialog({
				message: aviso,
				title: "Atualizar valor do crediário dos clientes",
				buttons: {
					salvar: {
						label: "Atualizar crediário",
						className: "yellow-casablanca",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'atualizar_crediario=1',
								success: function (data) {
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
										stackup_spacing: 10
									});
									if (response[2] == "1") {
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
									}
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
	};

	//BOX DIALOG Definir Entregador
	if ($('a.definirEntregador').length > 0) {
		$('a.definirEntregador').click(function () {
			var id = $(this).attr('id');
			$("#definir_entregador_form").append('<input name="id_venda" type="hidden" value="' + id + '" />');
			$("#definir-entregador").modal('show');
			return false;
		});
	};

	//BOX DIALOG Novo Produto
	if ($('a.novoproduto').length > 0) {
		$('a.novoproduto').click(async function () {
			var id = $(this).attr('id');
			var cfop = $(this).attr('cfop');
			var ncm_nf = $(this).attr('ncm_nf');
			var cest_nf = $(this).attr('cest_nf');
			var csosn_cst = $(this).attr('csosn_cst');
			var cod_anp = $(this).attr('cod_anp');
			var valor_partida = $(this).attr('valor_partida');
			var codigobarras = $(this).attr('codigobarras');
			var id_nf_itens = $(this).attr('id_nf_itens');

			var cfop_entrada = null;
			var cfop_saida = cfop;

			valor_partida = parseFloat(valor_partida);
			valor_partida = valor_partida.toFixed(3);
			valor_partida = valor_partida.toString();
			valor_partida = 'R$ ' + valor_partida.replace('.', ',');

			await $.ajax({
				type: 'post',
				url: 'webservices/consersaoFiscal.php',
				data: {
					id: +id_nf_itens,
					acao: 'novo'
				},
				dataType: 'json',
				error: function (e) {
					console.error('Erro ao buscar dados de CFOP e CSOSN para realizar conversão')
				},
				success: function (response) {
					cfop_entrada = response.cfop_entrada;
					cfop_saida = response.cfop_saida;

					$('#cfop_entrada').val(cfop_entrada);
					csosn_cst = response.csosn_cst;
				}
			});

			$("#cfop").val(cfop_saida);
			$("#ncm_nf").val(ncm_nf);
			$("#cest_nf").val(cest_nf);
			$("#csosn_cst").val(csosn_cst);
			$("#cod_anp").val(cod_anp);
			$('#valor_partida').val(valor_partida);
			$("#codigobarras").val(codigobarras);
			$("#novo_form").append('<input name="id" type="hidden" value="' + id + '" />');
			$("#novo-produto").modal('show');
			return false;
		});
	};

	//BOX DIALOG Editar Produto de Nota Fiscal de Entrada
	if ($('a.editarInfoProduto').length > 0) {
		$('a.editarInfoProduto').click(function () {
			var id = $(this).attr('id');
			var codigobarras = $(this).attr('codigobarras');
			var cfopsaida = $(this).attr('cfopsaida');
			var cfopentrada = $(this).attr('cfopentrada');
			$("#codigobarras_editar").val(codigobarras);
			$("#cfopsaida_editar").val(cfopsaida);
			$("#cfopentrada_editar").val(cfopentrada);
			$("#editar_produto_form").append('<input name="id" type="hidden" value="' + id + '" />');
			$("#editar-Info-Produto").modal('show');
			return false;
		});
	};

	//BOX DIALOG Combinar Produto
	if ($('a.combinarproduto').length > 0) {
		$('a.combinarproduto').click(async function () {
			var id = $(this).attr('id');
			var id_nf_itens = $(this).attr('id_nf_itens');
			await $.ajax({
				type: 'post',
				url: 'webservices/consersaoFiscal.php',
				data: {
					id: +id_nf_itens,
					acao: 'combinar'
				},
				dataType: 'json',
				error: function (e) {
					console.error('Erro ao buscar dados de CFOP e CSOSN para realizar conversão')
				},
				success: function (response) {
					$('#combinar_cfop_entrada').val(response.cfop_entrada);
				}
			});

			$("#combinar_form").append('<input name="id" type="hidden" value="' + id + '" />');
			$("#combinar-produto").modal('show');
			return false;
		});
	};

	//BOX DIALOG Editar CFOP da nota fiscal de Transporte
	if ($('a.editarCfopTransporte').length > 0) {
		$('a.editarCfopTransporte').click(function () {
			var id_nota = $(this).attr('id_nota');
			$("#editarCfopTransporte_form").append('<input name="id_nota" type="hidden" value="' + id_nota + '" />');
			$("#editar-Cfop-Transporte").modal('show');
			return false;
		});
	};

	// BUSCAR - Buscar cadastros
	if ($('.listar_cadastro').length > 0) {
		$('#btncadastro').click(function () {
			var id_cadastro = $('#id_cadastro').val();
			if (id_cadastro)
				window.location.href = "index.php?do=cadastro&acao=historico&id=" + id_cadastro;
			else
				alert('Cliente não selecionado.');
		});

		$('.listar_cadastro').on('focus', function (e) {
			$('#id_cadastro').val('');
			$('.selecionado').removeClass("mostrar");
			$('.selecionado').addClass("ocultar");
		});

		$('.listar_cadastro').on('blur', function (e) {
			var id = $('#id_cadastro').val();
			if (id) {
				$('.selecionado').removeClass("ocultar");
				$('.selecionado').addClass("mostrar");
			} else {
				$('.selecionado').removeClass("mostrar");
				$('.selecionado').addClass("ocultar");
			}
		});
		var custom = new Bloodhound({
			datumTokenizer: function (d) { return d.tokens; },
			limit: 5,
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: '	webservices/listar_cadastro.php?query=%QUERY'
		});

		custom.initialize();

		$('.listar_cadastro').typeahead(null, {
			name: 'listar_cadastro',
			displayKey: 'nome',
			source: custom.ttAdapter(),
			templates: {
				suggestion: Handlebars.compile([
					'<div class="media">',
					'<div class="media-body">',
					'<h5 class="media-heading">{{nome}}</h5>',
					'<p class="bold">{{cpf_cnpj}}</p>',
					'<p>{{celular}}</p>',
					'<p>{{celular2}}</p>',
					'<p>{{telefone}}</p>',
					'<p>{{telefone2}}</p>',
					'<p>{{endereco_completo}}</p>',
					'<p>_________________________________</p>',
					'</div>',
					'</div>',
				].join(''))
			}
		}).bind("typeahead:selected", function (obj, data) {
			$('#id_cadastro').val(data.id);
			$('.selecionado').removeClass("ocultar");
			$('.selecionado').addClass("mostrar");
		});
	};

	function verificaDebitosCliente(id_cliente) {
		var xhr = new XMLHttpRequest();
		xhr.open("GET", "webservices/verificaDebitosClientes.php?id_cliente=" + id_cliente);
		xhr.addEventListener("load", function () {
			if (xhr.response == 1) {
				$('.devendo').removeClass("ocultar");
				$('.devendo').addClass("mostrar");
				$('#cliente_devendo').removeClass("ocultar");
				$('#cliente_devendo').addClass("mostrarDevedor");
			} else {
				$('.devendo').removeClass("mostrar");
				$('.devendo').addClass("ocultar");
				$('#cliente_devendo').removeClass("mostrarDevedor");
				$('#cliente_devendo').addClass("ocultar");
			}
		});
		xhr.send();
	}

	// BUSCAR - Buscar cadastros
	if ($('.listar_cliente').length > 0) {
		$('#btncadastro').click(function () {
			var id_cadastro = $('#id_cadastro').val();
			if (id_cadastro)
				window.location.href = "index.php?do=cadastro&acao=historico&id=" + id_cadastro;
			else
				alert('Cliente não selecionado.');
		});

		$('.listar_cliente').on('focus', function (e) {
			$('#id_cadastro').val('');
			$('.selecionado').removeClass("mostrar");
			$('.selecionado').addClass("ocultar");
		});

		$('.listar_cliente').on('blur', function (e) {
			var id = $('#id_cadastro').val();
			if (id) {
				$('.selecionado').removeClass("ocultar");
				$('.selecionado').addClass("mostrar");
			} else {
				$('.selecionado').removeClass("mostrar");
				$('.selecionado').addClass("ocultar");
			}
		});
		var custom = new Bloodhound({
			datumTokenizer: function (d) { return d.tokens; },
			limit: 10,
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: 'webservices/listar_cadastro.php?query=%QUERY'
		});

		custom.initialize();

		$('.listar_cliente').typeahead(null, {
			name: 'listar_cliente',
			displayKey: 'nome',
			source: custom.ttAdapter(),
			templates: {
				suggestion: Handlebars.compile([
					'<div class="media">',
					'<div class="media-body">',
					'<h5 class="media-heading">{{nome}}</h5>',
					'<p class="bold">{{cpf_cnpj}}</p>',
					'<p>{{razao_social}}</p>',
					'<p>{{celular}}</p>',
					'<p>{{celular2}}</p>',
					'<p>{{telefone}}</p>',
					'<p>{{telefone2}}</p>',
					'<p>{{endereco_completo}}</p>',
					'<p>_________________________________</p>',
					'</div>',
					'</div>',
				].join(''))
			}
		}).bind("typeahead:selected", function (obj, data) {
			$('#id_cadastro').val(data.id);
			$('#cpf_cnpj').val(data.cpf_cnpj);
			$('#cpf_cnpj_modal').val(data.cpf_cnpj);
			$('#celular').val(data.celular);
			$('.selecionado').removeClass("ocultar");
			$('.selecionado').addClass("mostrar");

			verificaDebitosCliente(data.id);

			if (data.crediarioSistema && data.crediario > 0) {
				$('.btncrediario').removeClass("ocultar");
				$('.btncrediario').addClass("mostrar");
			} else {
				$('.btncrediario').removeClass("mostrar");
				$('.btncrediario').addClass("ocultar");
			}
		});
	};

	//Modal Editar Feriado
	if ($('a.editarferiado').length > 0) {
		$('a.editarferiado').click(function () {
			var id = $(this).attr('id');
			var feriado = $(this).attr('feriado');
			var data = $(this).attr('data');
			$("#feriado_editar").val(feriado);
			$("#data_editar").val(data);
			$("#feriado_form").append('<input name="id" type="hidden" value="' + id + '" />');
			$("#novo-feriado").modal('show');
			return false;
		});
	};

	//Ao digitar o cpf ou cnpj, será retornado os dados do cadastro em Adicionar/Gerar Nota Fiscal, bloco Dados Destinatario
	if ($('#cpfcnpjdestinatario').length > 0)
	{
		$('#cpfcnpjdestinatario').keypress(function (e)
		{
			let cpf_cnpj = $.trim($('#cpfcnpjdestinatario').val());

			cpf_cnpj = cpf_cnpj.replace('.', '');
			cpf_cnpj = cpf_cnpj.replace('.', '');
			cpf_cnpj = cpf_cnpj.replace('.', '');
			cpf_cnpj = cpf_cnpj.replace('-', '');
			cpf_cnpj = cpf_cnpj.replace('/', '');

			if (e.which == 13)
			{
				$.ajax({
					type: 'post',
					url: 'webservices/listar_cadastro_cnpj.php',
					data: 'cpfcnpjdestinatario=' + cpf_cnpj,

					success: function (data)
					{
						let json = $.parseJSON(data);
						if (json.retorno == 1)
						{
							$('#cep').val(decodeURI(json.cep));
							$('#endereco').val(decodeURI(json.endereco));
							$('#numero').val(decodeURI(json.numero));
							$('#complemento').val(decodeURI(json.complemento));
							$('#bairro').val(decodeURI(json.bairro));
							$('#cidade').val(decodeURI(json.cidade));
							$('#estado').val(decodeURI(json.estado));
							json.tipo === "1" ? $('#tipo_j')[0].checked = true : $('#tipo_f')[0].checked = true
						}
					}
				});
			}
		});
	}

	//Ao digitar o cpf ou cnpj, será retornado os dados do cadastro em Adicionar/Gerar Nota Fiscal, bloco Dados Destinatario
	if ($('#trans_cpfcnpj').length > 0)
	{
		$('#trans_cpfcnpj').keypress(function (e)
		{
			let cpf_cnpj = $.trim($('#trans_cpfcnpj').val());

			cpf_cnpj = cpf_cnpj.replace('.', '');
			cpf_cnpj = cpf_cnpj.replace('.', '');
			cpf_cnpj = cpf_cnpj.replace('.', '');
			cpf_cnpj = cpf_cnpj.replace('-', '');
			cpf_cnpj = cpf_cnpj.replace('/', '');

			if (e.which == 13)
			{
				$.ajax({
					type: 'post',
					url: 'webservices/listar_transportadora_cnpj.php',
					data: 'trans_cpfcnpj=' + cpf_cnpj,

					success: function (data)
					{
						let json = $.parseJSON(data);
						if (json.retorno == 1)
						{
							$('#trans_nome').val(decodeURI(json.razao_social));
							$('#trans_inscricaoestadual').val(decodeURI(json.ie));
							$('#trans_cep').val(decodeURI(json.cep));
							$('#trans_endereco').val(decodeURI(json.endereco));
							$('#trans_cidade').val(decodeURI(json.cidade));
							$('#trans_uf').val(decodeURI(json.estado));
							json.tipo === "1" ? $('#tipo_j_trans')[0].checked = true : $('#tipo_f_trans')[0].checked = true
						}
					}
				});
			}
		});
	}

	//BOX DIALOG Finalizar Venda
	if ($('.finalizarvenda').length > 0) {
		$('.finalizarvenda').click(function () {
			var id = $(this).attr('id');
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você deseja finalizar esta venda?";
			bootbox.dialog({
				message: aviso,
				title: "Finalizar venda",
				buttons: {
					salvar: {
						label: "Finalizar venda",
						className: "blue-madison",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'processarFinalizarVenda=1&id_venda=' + id,
								success: function (data) {
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
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	if ($('#id_produto_select2me').length > 0) {
		$('#id_produto_select2me').select2({
			ajax: {
				url: 'webservices/buscaProdutoSelect.php',
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						search: params.term,
						id_grupo: $('#id_grupo').val(),
						id_categoria: $('#id_categoria').val(),
						id_fabricante: $('#id_fabricante').val(),
					};
				},
				processResults: function (data) {
					return {
						results: data.map(function (item) {
							return {
								id: item.id,
								text: item.nome,
							};
						}),
					};
				},
				cache: true,
			},
			placeholder: 'Selecione um produto',
		});
	}

	//BOX DIALOG Finalizar Venda
	if ($('.gerarNFEvenda').length > 0) {
		$('.gerarNFEvenda').click(function () {
			var id = $(this).attr('id');
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você deseja gerar NF-e desta venda?";
			bootbox.dialog({
				message: aviso,
				title: "Converter venda em NF-e",
				buttons: {
					salvar: {
						label: "Converter venda em NF-e",
						className: "blue",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'gerarNFEvenda=' + id,
								success: function (data) {
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	//BOX DIALOG Finalizar Venda
	if ($('.gerarNFEvendaBloqueio').length > 0) {
		$('.gerarNFEvendaBloqueio').click(function () {
			var id = $(this).attr('id');
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Identifique um cliente antes de gerar a NF-e";
			bootbox.dialog({
				message: aviso,
				title: "Não é possível gerar uma NF-e de venda sem cliente identificado. Identifique o cliente e tente novamente.",
				buttons: {
					voltar: {
						label: "Voltar",
						className: "default"
					}
				}
			});
			return false;
		});
	};

	//BOX DIALOG Finalizar Venda
	if ($('.gerarNFeOS').length > 0) {
		$('.gerarNFeOS').click(function () {
			var id = $(this).attr('id');
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você deseja gerar NF-e dos produtos dessa OS?";
			bootbox.dialog({
				message: aviso,
				title: "NF-e de produtos da OS",
				buttons: {
					salvar: {
						label: "NF-e de produtos da OS",
						className: "blue",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'gerarNFeOS=' + id,
								success: function (data) {
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
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	//BOX DIALOG Gerar Nota de Fatura de Ordem de Servico
	if ($('.gerarFaturaOS').length > 0) {
		$('.gerarFaturaOS').click(function () {
			var id = $(this).attr('id');
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você deseja gerar Fatura dessa OS?";
			bootbox.dialog({
				message: aviso,
				title: "Fatura da OS",
				buttons: {
					salvar: {
						label: "Fatura da OS",
						className: "blue-madison",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'gerarFaturaOS=' + id,
								success: function (data) {
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
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	//BOX DIALOG Observacao nao eh possivel gerar nfe de OS
	if ($('.gerarNFeOSBloqueio').length > 0) {
		$('.gerarNFeOSBloqueio').click(function () {
			var id = $(this).attr('id');
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Identifique um cliente antes de gerar a NF-e";
			bootbox.dialog({
				message: aviso,
				title: "Não é possível gerar uma NF-e de Ordem de Serviço sem cliente identificado. Identifique o cliente e tente novamente.",
				buttons: {
					voltar: {
						label: "Voltar",
						className: "default"
					}
				}
			});
			return false;
		});
	};

	//BOX DIALOG Observacao nao eh possivel gerar FATURAMENTO de OS
	if ($('.gerarFaturaOSBloqueio').length > 0) {
		$('.gerarFaturaOSBloqueio').click(function () {
			var id = $(this).attr('id');
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Identifique um cliente antes de gerar o Faturamento";
			bootbox.dialog({
				message: aviso,
				title: "Não é possível gerar FATURAMENTO de Ordem de Serviço sem cliente identificado. Identifique o cliente e tente novamente.",
				buttons: {
					voltar: {
						label: "Voltar",
						className: "default"
					}
				}
			});
			return false;
		});
	};

	//BOX DIALOG Abrir Venda Consignada
	if ($('.abrirvenda').length > 0) {
		$('.abrirvenda').click(function () {
			var id = $(this).attr('id');
			var id_cadastro = $(this).attr('id_cadastro');
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você deseja abrir esta venda consignada?";
			bootbox.dialog({
				message: aviso,
				title: "Abrir venda consignada",
				buttons: {
					salvar: {
						label: "Abrir venda",
						className: "blue-madison",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'abrirVenda=' + id + '&id_cadastro=' + id_cadastro,
								success: function (data) {
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
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	// MODAL SHOW - Alterar Valor Venda
	if ($('a.alterarvalor').length > 0) {
		$('a.alterarvalor').click(function () {
			var valor = $(this).attr('valor');
			var id = $(this).attr('id');
			$("#valor_venda").val(valor);
			$("#alterar_form").append('<input name="id" type="hidden" value="' + id + '" />');
			$("#alterar-valor").modal('show');
			return false;
		});
	};

	// BUSCAR - Buscar produtos
	if ($('.lista_produtos').length > 0) {
		var id_tabela = $("#id_prod_tabela").val();
		var custom = new Bloodhound({
			datumTokenizer: function (d) { return d.tokens; },
			limit: 5,
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: '	webservices/listar_produtos.php?id_tabela=' + id_tabela + '&query=%QUERY'
		});
		custom.initialize();
		$('.lista_produtos').typeahead(null, {
			name: 'lista_produtos',
			displayKey: 'nome',
			source: custom.ttAdapter(),
			hint: (Metronic.isRTL() ? false : true),
			templates: {
				suggestion: Handlebars.compile([
					'<div class="media">',
					'<div class="media-body">',
					'<h5 class="media-heading">{{nome}}</h5>',
					'<p>{{codigonota}} / {{valor}} / Estoque: {{estoque}}</p>',
					'</div>',
					'</div>',
				].join(''))
			}
		}).bind("typeahead:selected", function (obj, data) {
			$('#id_produto').val(data.id);
			$('#valor_produto').val(data.valor);
			$('#valor_venda_produto').val(data.valor_venda);

			if ($('#quantidade_produto_os').length > 0) {
				var quantidade = $("#quantidade_produto_os").val();
				var q = quantidade.replace('.', '');
				q = q.replace(',', '.');
				q = parseFloat(q);
				if (isNaN(q)) {
					q = 1;
				}
				var desconto = $("#desconto_produto").val();
				var d = desconto.replace('.', '');
				d = d.replace(',', '.');
				d = parseFloat(d);
				if (isNaN(d)) {
					d = 0;
				}
				var v = data.valor_venda;
				var valor_total = (v * q) - d;
				valor_total = valor_total.toFixed(2);
				valor_total = valor_total.toString();
				valor_total = valor_total.replace('.', ',');

				$('#valor_total_produto').val('R$ ' + valor_total);
				$('#quantidade_produto_os').focus();
			}
			else if ($('#quantidade_produto').length > 0) {
				var quantidade = $("#quantidade_produto").val();
				var q = quantidade.replace('.', '');
				q = q.replace(',', '.');
				q = parseFloat(q);
				if (isNaN(q)) {
					q = 1;
				}
				var v = data.valor_venda;
				var valor_total = (v) * (q);
				valor_total = valor_total.toFixed(2);
				valor_total = valor_total.toString();
				valor_total = valor_total.replace('.', ',');

				$('#valor_total_produto').val('R$ ' + valor_total);
				$('#quantidade_produto').focus();
			}
		});
	};

	if ($('#valor_venda_produto').length > 0) {
		$('#valor_venda_produto').keyup(function () {
			let valor = $(this).val()
			let v = realParaFloat(valor);
			if (v < 0) {
				alert('Valor NÃO PODE ser negativo.');
				v = v * (-1);
				$(this).val(floatParaReal(v));
			}
		});
	}

	if ($('#id_produto_venda').length > 0) {
		$("#id_produto_venda").select2({
			ajax: {
				url: 'webservices/sistema/buscarProdutos.php',
				dataType: 'json',
				delay: 250,
				data: function (params) {
					let query = {
						nome: params.term
					}
					return query;
				},
				processResults: function (data) {
					var tmpResults = [];
					var id_tabela = $("#id_tabela_venda").val();

					$.each(data, function (index, item) {
						if (id_tabela == item.id_tabela) {
							tmpResults.push({
								'id': item.id,
								'text': item.nome,
							});
						}
					});
					return {
						results: tmpResults
					};
				}
			}
		});
	};

	// CALCULAR Valor do desconto de produto
	$('#desconto_produto').on('keyup', function (e) {
		var desconto = $("#desconto_produto").val();
		if (d > 0) {
			$("#valor_desconto_rapida").trigger({ type: 'keyup', which: 13, keyCode: 13 });
		}
		var d = desconto.replace('.', '');
		d = d.replace(',', '.');
		d = d.replace('R$ ', '');
		d = parseFloat(d);
		if (isNaN(d)) {
			d = 0;
		}
		var v = $("#valor_venda_produto").val();
		var quantidade = $("#quantidade_produto_os").val();
		var q = quantidade.replace('.', '');
		q = q.replace(',', '.');
		q = parseFloat(q);
		if (isNaN(q)) {
			q = 1;
		}
		if (d > (v * q)) {
			alert('Desconto não pode ser superior ao valor total.');
		}
		else {
			var valor_total = (v * q) - d;
			valor_total = valor_total.toFixed(2);
			valor_total = valor_total.toString();
			valor_total = valor_total.replace('.', ',');
			$('#valor_total_produto').val('R$ ' + valor_total);
		}
	});

	if ($('#id_produto_fornecedor').length > 0) {
		$("#id_produto_fornecedor").select2({
			ajax: {
				url: 'webservices/sistema/buscarProdutos.php',
				dataType: 'json',
				delay: 250,
				data: function (params) {
					let query = {
						nome: params.term
					}
					return query;
				},
				processResults: function (data) {
					var tmpResults = [];

					$.each(data, function (index, item) {
						tmpResults.push({
							'id': item.id,
							'text': item.nome
						});
					});
					return {
						results: tmpResults
					};
				}
			}
		});
	};

	//VENDA RAPIDA - Opção para definir data de vencimento para o primeiro boleto.
	$('.selectPagamentoRapida').change(function () {
		var id_categoria = $(".selectPagamentoRapida#tipopagamento").find(':selected').attr('id_categoria');
		if (id_categoria == 4)
			$("#divDataBoleto").css("display", "block");
		else
			$("#divDataBoleto").css("display", "none");
	});

	//TROCA DE PRODUTOS - Opção para definir o tipo de pagamento da compra do produto original.
	$('.selectPagamentoTroca').change(function () {
		var id_categoria = $(".selectPagamentoTroca#tipopagamento").find(':selected').attr('id_categoria');
		var avista = 0;
		avista = parseInt(avista);
		var id_tabela = $("#id_tabela_venda").val();

		$('.pagamento_avista').each(function (indice, item) {
			var i = $(item).val();
			var p = parseInt(i);
			avista = (avista && p);
		});

		if (id_categoria == 4 || id_categoria == 9)
			$("#divDataBoleto").css("display", "block");
		else
			$("#divDataBoleto").css("display", "none");

		var aux = 0;
		var quantidade = [];
		$(".quant_venda_troca").each(function () {
			quantidade.push($(".quant_venda_troca")[aux].value);
			aux++;
		});

		aux = 0;
		var id_produto = [];
		$(".id_produto").each(function () {
			id_produto.push($(".id_produto")[aux].value);
			aux++;
		});

		$(".id_produto").remove();
		$(".total").remove();
		$(".valor").remove();
		$(".estoque").remove();
		$("#tabela_produtos").empty();

		valor_troca = $("#valor_produto_troca").val();
		valor_troca = parseFloat(valor_troca);

		var novo_valor_total = 0
		for (var indice_produto = 0; indice_produto < id_produto.length; indice_produto++) {
			var id_produto_atualizar = id_produto[indice_produto];
			var quantidade_atualizar = quantidade[indice_produto];
			(function (id_produto_atualizar, quantidade_atualizar) {
				jQuery.ajax({
					type: 'post',
					url: 'webservices/buscaproduto.php',
					data: 'id=' + id_produto_atualizar + '&id_tabela=' + id_tabela,
					success: function (data) {
						var temp = data.split("#");
						if (temp.length >= 2) {
							var valor_pagar_produto = 0;
							if ((avista == 1) && (temp[2] > 0)) {
								valor_pagar_produto = temp[2];
							} else {
								valor_pagar_produto = temp[0];
							}
							valor_venda = 'R$ ' + valor_pagar_produto.replace('.', ',');
							estoque = temp[1];
							nome_produto = temp[3];
							valor = valor_pagar_produto;
							total = (valor * quantidade_atualizar);
							novo_valor_total = novo_valor_total + total;
							valor_total = total.toFixed(2);
							valor_total = valor_total.toString();
							valor_total = 'R$ ' + valor_total.replace('.', ',');

							var $el = $("#tabela_produtos");
							$el.prepend(`
								<tr>
									<td><span class="font-md">${nome_produto}</span></td>
									<td><span class="font-md">${estoque}</span></td>
									<td>
										<span class="bold theme-font">
											<input
												type="text"
												class="form-control form-filter input-sm quant_venda_troca decimal"
												name="quantidade[]"
												value="${quantidade_atualizar}"
												id="quantidade_produto"
											/>
										</span>
									</td>
									<td>
										<input
											type="text"
											name="valor_venda[]"
											id="valor_venda_troca"
											class="form-control form-filter input-sm valor_venda_troca_produto_avulso2"
											value="${floatParaReal(valor)}"
										/>
									</td>
									<td><span class="bold theme-font font-md valor_total">${valor_total}<span></td>
									<td>
										<a href="javascript:void(0);" class="btn red remover_produto_troca" title="Deseja remover este produto?"><i class="fa fa-times"></i></a>
									</td>
									<input name="id_produto[]" type="hidden" class="id_produto" value="${id_produto_atualizar}" />
									<input type="hidden" class="total" value="${total}" />
									<input type="hidden" class="estoque" value="${estoque}" />
								</tr>
							`).find("tr").first().hide();

							// <input type="hidden" name="valor_venda[]" class="valor" value="${valor}" />
							// <span class="font-md">${valor_venda}</span>

							$el.find("tr").first().fadeIn();

							$('.quant_venda_troca').maskMoney({ decimal: '.', precision: 3, symbolStay: false, allowNegative: false });
							$('.valor_venda_troca_produto_avulso2').maskMoney({ symbol: 'R$ ', thousands: '.', decimal: ',', precision: 2, symbolStay: true, allowNegative: true });

							$("#valor").val(novo_valor_total);
							var resultado = novo_valor_total;
							resultado = resultado.toFixed(2);
							resultado = resultado.toString();
							resultado = 'R$ ' + resultado.replace('.', ',');
							$("#valor2").text(resultado);

							var desconto = $("#valor_desconto_troca").val();
							var d = desconto.replace('.', '');
							d = d.replace(',', '.');
							d = d.replace('R$ ', '');
							d = parseFloat(d);
							if (isNaN(d)) {
								d = 0;
							}

							/*
							if(d > 0) {
								$("#valor_desconto_troca").trigger({type:'keyup', which:13, keyCode:13});
							}
							*/

							var valor = $("#valor").val();
							var v = parseFloat(valor);
							if (isNaN(v)) {
								v = 0;
							}

							var soma = 0;
							$('.valor_pago').each(function (indice, item) {
								var i = $(item).val();
								var p = parseFloat(i);
								if (!isNaN(p)) {
									soma += p;
								}
							});
							var soma_dinheiro = 0;
							$('.dinheiro').each(function (indice, item) {
								var i = $(item).val();
								var p = parseFloat(i);
								if (!isNaN(p)) {
									soma_dinheiro += p;
								}
							});

							var valor_pagar = v - d - soma - valor_troca;
							if (valor_pagar < 0) {
								$(".valor_pagar_titulo").text('Valor a utilizar');
								$(".valor_pagar_texto").removeClass("font-green-seagreen");
								$(".valor_pagar_texto").addClass("font-red");
								valor_pagar *= -1;
							} else {
								$(".valor_pagar_titulo").text('Valor a pagar');
								$(".valor_pagar_texto").removeClass("font-red");
								$(".valor_pagar_texto").addClass("font-green-seagreen");
							}

							var resultado = valor_pagar.toFixed(2);
							resultado = resultado.toString();
							resultado = 'R$ ' + resultado.replace('.', ',');
							$("#valor_pagar").text(resultado);

							troco = (soma + valor_troca) - (v - d);
							total_troco = 0;
							if (troco < 0)
								total_troco = 0;
							else
								if (troco >= soma_dinheiro)
									total_troco = soma_dinheiro;
								else
									total_troco = troco;

							total_troco = total_troco.toFixed(2);
							if (total_troco > 0) {
								valor_a_pagar = valor_pagar.toFixed(2);

								valor_a_pagar = valor_a_pagar - total_troco;
								valor_a_pagar = valor_a_pagar.toFixed(2);
								resultado = valor_a_pagar.toString();
								resultado = 'R$ ' + resultado.replace('.', ',');
								$("#valor_pagar").text(resultado);
							}

							resultado = total_troco.toString();
							resultado = 'R$ ' + resultado.replace('.', ',');
							$("#troco").text(resultado);
						} else {
							alert('Não foi possível encontrar o produto nesta tabela!');
							return false;
						}
					}
				});
			})(id_produto_atualizar, quantidade_atualizar);
		}
	});

	// CALCULAR Valor Total de desconto para Produto na TROCA
	$('#valor_desconto_troca').on('keyup', function (e) {

		var valor_troca = $("#valor_produto_troca").val();
		valor_troca = parseFloat(valor_troca);
		valor_troca.toFixed(2);
		valor_troca = parseFloat(valor_troca);

		var valor = $("#valor").val();
		v = parseFloat(valor);
		if (isNaN(v)) {
			v = 0;
		}
		v.toFixed(2);
		v = parseFloat(v);

		var desconto = $("#valor_desconto_troca").val();
		var d = desconto.replace('.', '');
		d = d.replace(',', '.');
		d = d.replace('R$ ', '');
		d = parseFloat(d);
		if (isNaN(d)) {
			d = 0;
		}
		d.toFixed(2);
		d = parseFloat(d);

		if (d > (v - valor_troca).toFixed(2)) {
			alert('Desconto maior que o valor total da venda.');
			$('#valor_desconto_troca').focus().select();
			return false;
		}
		var dinheiro = 0;

		var soma = 0;
		$('.valor_pago').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma += p;
			}
		});

		var soma_dinheiro = 0;
		$('.dinheiro').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma_dinheiro += p;
			}
		});

		var valor_pagar = v - d - soma - valor_troca;
		if (valor_pagar < 0) {
			$(".valor_pagar_titulo").text('Valor a utilizar');
			$(".valor_pagar_texto").removeClass("font-green-seagreen");
			$(".valor_pagar_texto").addClass("font-red");
			valor_pagar *= -1;
		} else {
			$(".valor_pagar_titulo").text('Valor a pagar');
			$(".valor_pagar_texto").removeClass("font-red");
			$(".valor_pagar_texto").addClass("font-green-seagreen");
		}

		var resultado = valor_pagar.toFixed(2);
		resultado = resultado.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#valor_pagar").text(resultado);

		var soma_restante = soma - soma_dinheiro;
		var total_pagar_dinheiro = v - soma_restante;

		troco = (soma + valor_troca) - (v - d);

		total_troco = 0;
		if (troco < 0)
			total_troco = 0;
		else
			if (troco >= soma_dinheiro)
				total_troco = soma_dinheiro;
			else
				total_troco = troco;

		total_troco = total_troco.toFixed(2);

		if (total_troco > 0) {
			valor_a_pagar = valor_pagar.toFixed(2);

			valor_a_pagar = valor_a_pagar - total_troco;
			valor_a_pagar = valor_a_pagar.toFixed(2);
			resultado = valor_a_pagar.toString();
			resultado = 'R$ ' + resultado.replace('.', ',');
			$("#valor_pagar").text(resultado);
		}

		resultado = total_troco.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#troco").text(resultado);

		//Desconto em porcentagem - Valor desconto -> Desconto total em porcentagem

		let valor_desconto = $("#valor_desconto_troca").val();

		valor_desconto = valor_desconto.replace('.', '');
		valor_desconto = valor_desconto.replace(',', '.');
		valor_desconto = valor_desconto.replace('R$ ', '');

		let total_sem_troca = $("#valor2").html();
		total_sem_troca = total_sem_troca.replace('.', '');
		total_sem_troca = total_sem_troca.replace(',', '.');
		total_sem_troca = total_sem_troca.replace('R$ ', '');

		let valor_total = total_sem_troca - valor_troca;

		let calc_porcent = ((valor_desconto * 100) / valor_total);
		calc_porcent = calc_porcent.toFixed(2);
		calc_porcent = '% ' + calc_porcent.toString();
		$("#valor_desconto_porcentagem_troca").val(calc_porcent);

		let id_tabela = $('#id_tabela_venda').val();

		$.ajax({
			method: 'POST',
			url: 'webservices/verificaDesconto.php',
			data: 'id_tabela=' + id_tabela,
			success: function (data) {
				let dados = $.parseJSON(data);
				let descTable = dados.desconto;
				let checkDesc = (descTable / 100) * valor_total;
				checkDesc = Math.round(checkDesc * 100) / 100;

				if (d > checkDesc) {
					alert('Erro! O desconto máximo permitido pela tabela selecionada é ' + floatParaReal(checkDesc));
					$('#valor_desconto_troca').val(floatParaReal(0));
					$('#valor_desconto_porcentagem_troca').val(floatParaReal(0));
					return false;
				}
			}
		});
	});

	// CALCULAR Valor Total de desconto por porcentagem para Produto na TROCA
	$("#valor_desconto_porcentagem_troca").on('keyup', function () {
		//Pega o valor do desconto em porcentagem e o valor total, depois faz o cálculo da porcentagem
		//e atribui ao valor do desconto rapida o calulo_porcent

		let desc_porcent = $(this).val();
		desc_porcent = desc_porcent.replace('%', '');
		desc_porcent = desc_porcent.replace('.', '');
		desc_porcent = desc_porcent.replace(',', '.');
		desc_porcent = parseFloat(desc_porcent);

		valor_troca = $("#valor_produto_troca").val();
		valor_troca = parseFloat(valor_troca);

		let total_sem_troca = $("#valor2").html();
		total_sem_troca = total_sem_troca.replace('.', '');
		total_sem_troca = total_sem_troca.replace(',', '.');
		total_sem_troca = total_sem_troca.replace('R$ ', '');
		total_sem_troca = parseFloat(total_sem_troca);

		let val_total = total_sem_troca - valor_troca;

		let calculo_porcent = (desc_porcent / 100) * (val_total);
		calculo_porcent = calculo_porcent.toFixed(2);
		calculo_porcent = 'R$ ' + calculo_porcent.toString();
		calculo_porcent = calculo_porcent.replace('.', ',');

		if ((calculo_porcent > (val_total - valor_troca))) {
			alert('Desconto maior que o valor total da venda.');
			$('#valor_desconto_porcentagem_troca').focus().select();
			return false;
		}

		$("#valor_desconto_troca").val(calculo_porcent);

		let id_tabela = $('#id_tabela_venda').val();

		$.ajax({
			method: 'POST',
			url: 'webservices/verificaDesconto.php',
			data: 'id_tabela=' + id_tabela,
			success: function (data) {
				let dados = $.parseJSON(data);
				let descTable = dados.desconto;
				let checkDesc = (descTable / 100) * val_total;
				checkDesc = Math.round(checkDesc * 100) / 100;

				calculo_porcent = calculo_porcent.replace('.', '');
				calculo_porcent = calculo_porcent.replace(',', '.');
				calculo_porcent = calculo_porcent.replace('R$ ', '');

				if (calculo_porcent > checkDesc) {
					alert('Erro! O desconto máximo permitido pela tabela selecionada é ' + floatParaReal(checkDesc));
					$('#valor_desconto_troca').val(floatParaReal(0));
					$('#valor_desconto_porcentagem_troca').val(floatParaReal(0));
					return false;
				}
			}
		});

		//Atualiza o valor a pagar
		let valor_desconto = $("#valor_desconto_troca").val();
		valor_desconto = valor_desconto.replace('R$ ', '');

		valor_desconto = valor_desconto.replace(',', '.');
		valor_desconto = parseFloat(valor_desconto);
		var soma = 0;

		$('.valor_pago').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma += p;
			}
		});

		let val_pagar = (total_sem_troca - valor_desconto - soma - valor_troca);
		if (val_pagar < 0) {
			$(".valor_pagar_titulo").text('Valor a utilizar');
			$(".valor_pagar_texto").removeClass("font-green-seagreen");
			$(".valor_pagar_texto").addClass("font-red");
			val_pagar *= -1;
		} else {
			$(".valor_pagar_titulo").text('Valor a pagar');
			$(".valor_pagar_texto").removeClass("font-red");
			$(".valor_pagar_texto").addClass("font-green-seagreen");
		}

		val_pagar = val_pagar.toFixed(2);
		val_pagar = val_pagar.replace('.', ',');
		val_pagar = 'R$ ' + val_pagar.toString();
		$("#valor_pagar").text(val_pagar);
	});

	// TROCA DE PRODUTO - Adicionar pagamento na troca de produto
	if ($('#adicionar_pagamento_troca').length > 0) { //case "produtotrocaavulso"
		$('#adicionar_pagamento_troca').click(function () {
			let dataPrimeiroPagamento = document.getElementById("data_boleto")
			let divDataBoleto = document.getElementById("divDataBoleto")

			if (dataPrimeiroPagamento.value == "" && divDataBoleto.style.display == "block") {
				alert("Obrigatório informar a data do primeiro pagamento!")
			} else {
				var id_pagamento = $("#tipopagamento option:selected").val();
				var avista = 0;

				var valor_troca = $("#valor_produto_troca").val();
				valor_troca = parseFloat(valor_troca);

				if (id_pagamento == "") {
					alert('Favor selecionar uma forma de pagamento.');
					$('#tipopagamento').focus().select();
					return false;
				}
				var pagamento = $("#tipopagamento option:selected").text();
				var parcela = $("#parcelas").val();
				var parc = parseFloat(parcela);
				if (isNaN(parc)) {
					parc = 1;
				}
				var valor = $("#valor").val();
				var v = parseFloat(valor);
				if (isNaN(v)) {
					v = 0;
				}

				let total_menos_troca = v - valor_troca;

				var valor_pago = $("#valor_pago").val();
				if (valor_pago == "") {
					valor_pago = $("#valor_pagar").text();
				}
				var vp = valor_pago.replace('.', '');
				vp = vp.replace(',', '.');
				vp = vp.replace('R$ ', '');
				vp = parseFloat(vp);
				if (isNaN(vp)) {
					vp = 0;
				}

				if (vp <= 0) {
					alert('Pagamento deve ser maior que zero.');
					$('#valor_pago').focus().select();
					return false;
				}
				var desconto = $("#valor_desconto_troca").val();
				var d = desconto.replace('.', '');
				d = d.replace(',', '.');
				d = d.replace('R$ ', '');
				d = parseFloat(d);
				if (isNaN(d)) {
					d = 0;
				}

				var valor_desconto = d;
				valor_desconto = valor_desconto.toFixed(2);
				valor_desconto = valor_desconto.toString();
				valor_desconto = valor_desconto.replace('.', ',');

				var dinheiro = 0;
				if (id_pagamento == '1') {
					dinheiro = vp;
				}

				var $el = $("#tabela_pagamentos");
				$el.prepend(`
				<tr>
					<td><span class="font-md">${pagamento}</span></td>
					<td><span class="font-md">${parc}</span></td>
					<td><span class="font-md">R$ ${valor_desconto}</span></td>
					<td>
						<span class="bold theme-font font-md">
						${valor_pago}
						<span>
					</td>
					<td>
						<a href="javascript:void(0);" class="btn red remover_pagamento_troca" title="Deseja remover este pagamento?"><i class="fa fa-times"></i></a>
					</td>
					<input name="id_pagamento[]" type="hidden" class="id_pagamento" value="${id_pagamento}" />
					<input type="hidden" name="dinheiro[]" class="dinheiro" value="${dinheiro}" />
					<input type="hidden" name="parcela[]" class="parcela" value="${parc}" />
					<input type="hidden" name="valor_pago[]" class="valor_pago" value="${vp}" />
					<input type="hidden" name="pagamento_avista[]" class="pagamento_avista" value="${avista}" />
					<input type="hidden" name="valor_desconto_troca" class="" value="${d}" />
				</tr>`
				).find("tr").first().hide();
				$el.find("tr").first().fadeIn();

				var soma = 0;
				$('.valor_pago').each(function (indice, item) {
					var i = $(item).val();
					var p = parseFloat(i);
					if (!isNaN(p)) {
						soma += p;
					}
				});

				var soma_dinheiro = 0;
				$('.dinheiro').each(function (indice, item) {
					var i = $(item).val();
					var p = parseFloat(i);
					if (!isNaN(p)) {
						soma_dinheiro += p;
					}
				});

				var valor_pagar = v - d - soma - valor_troca;

				if (valor_pagar < 0) {
					$(".valor_pagar_titulo").text('Valor a utilizar');
					$(".valor_pagar_texto").removeClass("font-green-seagreen");
					$(".valor_pagar_texto").addClass("font-red");
					valor_pagar *= -1;
				} else {
					$(".valor_pagar_titulo").text('Valor a pagar');
					$(".valor_pagar_texto").removeClass("font-red");
					$(".valor_pagar_texto").addClass("font-green-seagreen");
				}

				var resultado = valor_pagar.toFixed(2);
				resultado = resultado.toString();
				resultado = 'R$ ' + resultado.replace('.', ',');
				$("#valor_pagar").text(resultado);

				troco = (soma + valor_troca) - (v - d);
				total_troco = 0;
				if (troco < 0)
					total_troco = 0;
				else
					if (troco >= soma_dinheiro)
						total_troco = soma_dinheiro;
					else
						total_troco = troco;

				total_troco = total_troco.toFixed(2);
				if (total_troco > 0) {
					valor_a_pagar = valor_pagar.toFixed(2);

					valor_a_pagar = valor_a_pagar - total_troco;
					valor_a_pagar = valor_a_pagar.toFixed(2);
					resultado = valor_a_pagar.toString();
					resultado = 'R$ ' + resultado.replace('.', ',');
					$("#valor_pagar").text(resultado);
				}

				if ($('.valor_pagar_texto').hasClass('font-red')) {
					$('#voucher_crediario').val(total_troco);
				}

				resultado = total_troco.toString();
				resultado = 'R$ ' + resultado.replace('.', ',');
				$("#troco").text(resultado);

				/* Adicionar pagamento */
				// let valor_a_pagar = $('.valor_pagar_texto').text();
				// valor_a_pagar = valor_a_pagar.replace('R$', '');
				// valor_a_pagar = valor_a_pagar.replace('.', '');
				// valor_a_pagar = valor_a_pagar.replace(',', '.');
				// valor_a_pagar = parseFloat(valor_a_pagar);

				// if(valor_a_pagar > 0 && id_pagamento > 0) {
				// 	$('.salvar-restante-crediario').removeClass('ocultar');
				// } else {
				// 	$('.salvar-restante-crediario').addClass('ocultar');
				// }
			}
		});
	};

	// CALCULAR Valor Total Produto
	$('#quantidade_produto').on('keyup', function (e) {
		var quantidade = $("#quantidade_produto").val();
		var q = quantidade.replace('.', '');
		q = q.replace(',', '.');
		q = parseFloat(q);
		if (isNaN(q)) {
			q = 1;
		}

		var v = $("#valor_venda_produto").val();
		var valor_total = v * q;
		valor_total = valor_total.toFixed(2);
		valor_total = valor_total.toString();
		valor_total = valor_total.replace('.', ',');

		$('#valor_total_produto').val('R$ ' + valor_total);
	});

	// CALCULAR Valor Total Produto quanto alterada a quantidade na tela de adicionar produto em Finalizar Venda
	$('#quantidade_finalizar_venda').on('keyup', function (e) {
		var quantidade = $("#quantidade_finalizar_venda").val();
		var q = quantidade.replace('.', '');
		q = q.replace(',', '.');
		q = parseFloat(q);
		if (isNaN(q)) {
			q = 1;
		}

		if (q < 0) {
			alert('Quantidade NÃO PODE ser negativo.');
			q = q * (-1);
			$("#quantidade_finalizar_venda").val(q.toFixed(3));
		}

		var v = $("#valor_venda_produto").val();
		v = v.replace('.', '');
		v = v.replace(',', '.');
		v = v.replace('R$ ', '');
		v = parseFloat(v);

		var d = $("#valor_desconto_finalizar_venda").val();
		d = d.replace('.', '');
		d = d.replace(',', '.');
		d = d.replace('R$ ', '');
		d = parseFloat(d);
		if (isNaN(d)) {
			d = 0;
		}
		var valor_total = (v * q) - d;
		valor_total = valor_total.toFixed(2);
		valor_total = valor_total.toString();
		valor_total = valor_total.replace('.', ',');

		$('#valor_total_produto').val('R$ ' + valor_total);
	});

	//Adicionar produto em finalizar venda
	//Keyup do valor do desconto atualizando o desconto a pagar em porcentagem
	$("#valor_desconto_finalizar_venda").on('keyup', function () {
		let val_desc = $("#valor_desconto_finalizar_venda").val();
		val_desc = val_desc.replace('.', '');
		val_desc = val_desc.replace(',', '.');
		val_desc = parseFloat(val_desc.replace('R$ ', ''));

		let valor_produto = $("#valor_venda_produto").val();
		valor_produto = valor_produto.replace('R$ ', '');
		valor_produto = valor_produto.replace('.', '');
		valor_produto = parseFloat(valor_produto.replace(',', '.'));

		let quantidade = $("#quantidade_finalizar_venda").val();

		let valor_total = valor_produto * quantidade;

		if (val_desc > valor_total) {
			alert("O valor do desconto não pode ser maior do que o valor total");
			$("#valor_desconto_porcent_finalizar_venda").val('');
			$("#valor_desconto_finalizar_venda").val('');

			valor_total = valor_total.toFixed(2);
			valor_total = valor_total.toString();
			valor_total = valor_total.replace('.', ',');
			$('#valor_total_produto').val('R$ ' + valor_total);
		} else {
			let calc_porcent = ((val_desc * 100) / valor_total);
			calc_porcent = calc_porcent.toFixed(2);
			//calc_porcent = '% ' + calc_porcent.toString();
			$("#valor_desconto_porcent_finalizar_venda").val(calc_porcent);

			valor_total = valor_total - val_desc;
			valor_total = valor_total.toFixed(2);
			valor_total = valor_total.toString();
			valor_total = valor_total.replace('.', ',');
			$('#valor_total_produto').val('R$ ' + valor_total);
		}
	});

	//Adicionar produto em finalizar venda
	//Keyup do desconto em porcentagem atualizando o desconto a pagar em valor
	$("#valor_desconto_porcent_finalizar_venda").on('keyup', function () {
		let porcent_desc = $("#valor_desconto_porcent_finalizar_venda").val();
		porcent_desc = porcent_desc.replace('.', '');
		porcent_desc = porcent_desc.replace(',', '.');
		//porcent_desc = parseFloat(porcent_desc.replace('%',''));
		porcent_desc = parseFloat(porcent_desc);

		let valor_produto = $("#valor_venda_produto").val();
		valor_produto = valor_produto.replace('R$ ', '');
		valor_produto = valor_produto.replace('.', '');
		valor_produto = parseFloat(valor_produto.replace(',', '.'));

		let quantidade = $("#quantidade_finalizar_venda").val();

		let valor_total = valor_produto * quantidade;

		let val_desc = (valor_total * porcent_desc) / 100;

		if (val_desc > valor_total) {
			alert("O valor do desconto não pode ser maior do que o valor total");
			$("#valor_desconto_finalizar_venda").val('');
			$("#valor_desconto_porcent_finalizar_venda").val('');

			valor_total = valor_total.toFixed(2);
			valor_total = valor_total.toString();
			valor_total = valor_total.replace('.', ',');
			$('#valor_total_produto').val('R$ ' + valor_total);
		} else {
			valor_total = valor_total - val_desc;
			valor_total = valor_total.toFixed(2);
			valor_total = valor_total.toString();
			valor_total = valor_total.replace('.', ',');
			$('#valor_total_produto').val('R$ ' + valor_total);

			desconto = val_desc.toFixed(2);
			desconto = desconto.toString();
			desconto = desconto.replace('.', ',');
			$('#valor_desconto_finalizar_venda').val('R$ ' + desconto);
		}
	});

	// BUSCAR - Buscar serviços
	if ($('.lista_servicos').length > 0) {
		var custom = new Bloodhound({
			datumTokenizer: function (d) { return d.tokens; },
			limit: 5,
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: '	webservices/listar_servicos.php?query=%QUERY'
		});

		custom.initialize();

		$('.lista_servicos').typeahead(null, {
			name: 'lista_servicos',
			displayKey: 'descricao',
			source: custom.ttAdapter(),
			hint: (Metronic.isRTL() ? false : true),
			templates: {
				suggestion: Handlebars.compile([
					'<div class="media">',
					'<div class="media-body">',
					'<h5 class="media-heading">{{descricao}}</h5>',
					'<p>{{valor_servico}}</p>',
					'</div>',
					'</div>',
				].join(''))
			}
		}).bind("typeahead:selected", function (obj, data) {
			$('#id_servico').val(data.id);
			$('#valor_servico').val(data.valor_servico);

			var quantidade = $("#quantidade_servico").val();
			var q = quantidade.replace('.', '');
			q = q.replace(',', '.');
			q = parseFloat(q);
			if (isNaN(q)) {
				q = 1;
			}
			var v = data.valor;
			var valor_total = (v) * (q);
			valor_total = valor_total.toFixed(2);
			valor_total = valor_total.toString();
			valor_total = valor_total.replace('.', ',');

			$('#valor_total_servico').val('R$ ' + valor_total);
			$('#quantidade_servico').focus();
		});
	};

	// CALCULAR Valor Total de Serviços
	$('#quantidade_servico').on('keyup', function (e) {
		var quantidade = $("#quantidade_servico").val();
		var q = quantidade.replace('.', '');
		q = q.replace(',', '.');
		q = parseFloat(q);
		if (isNaN(q)) {
			q = 1;
		}
		var valor_servico = $("#valor_servico").val();
		var v = valor_servico.replace('.', '');
		v = v.replace(',', '.');
		v = v.replace('R$ ', '');
		v = parseFloat(v);
		if (isNaN(v)) {
			v = 0;
		}
		var valor_total = v * q;
		valor_total = valor_total.toFixed(2);
		valor_total = valor_total.toString();
		valor_total = valor_total.replace('.', ',');

		$('#valor_total_servico').val('R$ ' + valor_total);
	});

	// CALCULAR Valor Total de Serviços
	$('#valor_servico').on('keyup', function (e) {
		var quantidade = $("#quantidade_servico").val();
		var q = quantidade.replace('.', '');
		q = q.replace(',', '.');
		q = parseFloat(q);
		if (isNaN(q)) {
			q = 1;
		}
		var valor_servico = $("#valor_servico").val();
		var v = valor_servico.replace('.', '');
		v = v.replace(',', '.');
		v = v.replace('R$ ', '');
		v = parseFloat(v);
		if (isNaN(v)) {
			v = 0;
		}
		var valor_total = v * q;
		valor_total = valor_total.toFixed(2);
		valor_total = valor_total.toString();
		valor_total = valor_total.replace('.', ',');

		$('#valor_total_servico').val('R$ ' + valor_total);
	});

	// PRODUTOS - Adicionar grade
	if ($('a.adicionargrade').length > 0) {
		$('a.adicionargrade').click(function () {
			var str = $("#admin_form").serialize();
			jQuery.ajax({
				type: 'POST',
				url: 'controller.php',
				data: 'adicionarGrade=1&' + str,
				success: function (data) {
					var response = data.split("#");
					$.bootstrapGrowl(response[0], {
						ele: "body",
						type: response[1],
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						delay: 10000,
						stackup_spacing: 10
					});

					setTimeout(function () {
						window.location.href = response[3];
					}, 1000);
				}
			});
			return false;
		});
	};

	// BOX DIALOG - Limpar os produtos da grade
	if ($('a.limpargrade').length > 0) {
		$('a.limpargrade').click(function () {
			var aviso = "Você deseja LIMPAR todos os produtos da grade de vendas?";
			bootbox.dialog({
				message: aviso,
				title: "Limpar produtos da grade de vendas",
				buttons: {
					salvar: {
						label: "Limpar",
						className: "blue",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'limparGradeVendas=1',
								success: function (data) {
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
										delay: 10000,
										stackup_spacing: 10
									});
									setTimeout(function () {
										location.reload();
									}, 1000);
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
	};

	// PRODUTOS - alterar grade
	if ($('a.gradevendas').length > 0) {
		$('a.gradevendas').click(function () {
			var id = $(this).attr('id');
			var grade = $(this).attr('grade');
			jQuery.ajax({
				type: 'POST',
				url: 'controller.php',
				data: 'processarGradeVendas=1&id=' + id + '&grade=' + grade,
				success: function (data) {
					var response = data.split("#");
					$.bootstrapGrowl(response[0], {
						ele: "body",
						type: response[1],
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						delay: 10000,
						stackup_spacing: 10
					});
					setTimeout(function () {
						location.reload();
					}, 1000);
				}
			});
			return false;
		});
	};

	// BOLETO - Enviar por email
	if ($('a.emailboleto').length > 0) {
		$('a.emailboleto').click(function () {
			var id = $(this).attr('id');
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você confirmar o envio do email com o boleto?";
			bootbox.dialog({
				message: aviso,
				title: "Confirmar envio",
				buttons: {
					salvar: {
						label: "CONFIRMAR",
						className: "blue",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'emailBoleto=1&id=' + id,
								success: function (data) {
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	// MODAL SHOW - Retorno de contato
	if ($('.retornocontato').length > 0) {
		$('.retornocontato').click(function () {
			var id = $(this).attr('id');
			var telefone = $(this).attr('telefone');
			var nome = $(this).attr('nome');
			$("#telefone").text(telefone);
			$("#nome").text(nome);
			$("#retorno_form").append('<input name="id_cadastro" type="hidden" value="' + id + '" />');
			$("#retorno-contato").modal('show');
			return false;
		});
	};

	// MODAL SHOW - Pagar despesa
	if ($('a.pagar').length > 0) {
		$('a.pagar').click(function () {
			var id = $(this).attr('id');
			var id_banco = $(this).attr('id_banco');
			$("#id_banco").val(id_banco).change();
			var documento = $(this).attr('documento');
			$("#documento").val(documento);
			var cheque = $(this).attr('cheque');
			if (cheque == 1) {
				$("#cheque").prop('checked', true);
			}
			$("#despesa_form").append('<input name="id_despesa" type="hidden" value="' + id + '" />');
			$("#pagar-despesa").modal('show');
			return false;
		});
	};

	// MODAL SHOW - Pagar receita
	if ($('a.pagarfinanceiro').length > 0) {
		$('a.pagarfinanceiro').click(function () {
			var id = $(this).attr('id');
			var id_categoria = $(this).attr('id_categoria');
			if (id_categoria == 9) {
				$('#tipo_pagamento_crediario_dv').removeClass("ocultar");
				$('#tipo_pagamento_crediario_dv').addClass("mostrar");
			} else {
				$('#tipo_pagamento_crediario_dv').removeClass("mostrar");
				$('#tipo_pagamento_crediario_dv').addClass("ocultar");
			}
			$("#pagar_form").append('<input name="id_controle" type="hidden" value="' + id + '" />');
			$("#pagar-receita").modal('show');
			return false;
		});
	};

	// MODAL SHOW - Editar receita
	if ($('a.alterarreceita').length > 0) {
		$('a.alterarreceita').click(function () {
			var id = $(this).attr('id');
			var id_pai = $(this).attr('id_pai');
			var id_conta = $(this).attr('id_conta');
			var id_banco = $(this).attr('id_banco');
			var id_empresa = $(this).attr('id_empresa');
			var tipo = $(this).attr('tipo');
			var data_recebido = $(this).attr('data_recebido');
			$("#id_pai_despesa").val(id_pai).change();
			var data_pagamento = $(this).attr('data_pagamento');
			$("#id_empresa2").val(id_empresa).change();
			$("#id_banco2").val(id_banco).change();
			$("#tipo").val(tipo).change();
			$("#data_recebido").val(data_recebido);
			$("#data_pagamento").val(data_pagamento);
			$("#receita_form").append('<input name="id_receita" type="hidden" value="' + id + '" />');
			$("#alterar-receita").modal('show');
			return false;
		});
	};

	// MODAL SHOW - Cadastrar Metas
	if ($('a.metas').length > 0) {
		$('a.metas').click(function () {
			var matriculas = $(this).attr('matriculas');
			var mes_ano = $(this).attr('mes_ano');
			$("#matriculas").val(matriculas);
			$("#meta_form").append('<input name="mes_ano" type="hidden" value="' + mes_ano + '" />');
			$("#novo-meta").modal('show');
			return false;
		});
	};

	// MODAL SHOW - Cadastrar Metas DRE
	if ($('a.metasdre').length > 0) {
		$('a.metasdre').click(function () {
			var valor = $(this).attr('valor');
			var id_conta = $(this).attr('id_conta');
			var mes_ano = $(this).attr('mes_ano');
			$("#valor").val(valor);
			$("#meta_form").append('<input name="id_conta" type="hidden" value="' + id_conta + '" />');
			$("#meta_form").append('<input name="mes_ano" type="hidden" value="' + mes_ano + '" />');
			$("#novo-meta").modal('show');
			return false;
		});
	};

	// MODAL SHOW - Alterar Todos Valor Venda
	if ($('a.alterartodos').length > 0) {
		$('a.alterartodos').click(function () {
			$("#alterar-todos").modal('show');
			return false;
		});
	};

	// BOX DIALOG - Confirmar remessa
	if ($('a.remessa').length > 0) {
		$('a.remessa').click(function () {
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você confirmar o envio da remessa com sucesso?";
			bootbox.dialog({
				message: aviso,
				title: "Confirmar Remessa",
				buttons: {
					salvar: {
						label: "CONFIRMAR",
						className: "green",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'confirmarRemessa=1',
								success: function (data) {
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	// BOX DIALOG - Salvar Salario Colaboradores
	if ($('a.salariocolaboradores').length > 0) {
		$('a.salariocolaboradores').click(function () {
			var mes_ano = $(this).attr('mes_ano');
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você deseja salvar o PAGAMENTO dos salários dos COLABORADORES?";
			bootbox.dialog({
				message: aviso,
				title: "Pagamento dos salários dos colaboradores",
				buttons: {
					salvar: {
						label: "Confirmar PAGAMENTO",
						className: "blue",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'PagarSalarioColaboradores=' + mes_ano,
								success: function (data) {
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	//BOX DIALOG Cancelar Nota Fiscal na Receita
	if ($('.cancelar_nota').length > 0) {
		$('.cancelar_nota').click(function () {
			var id = $(this).attr('id');
			var acao = $(this).attr('acao');
			var parent = $(this).parents("tr");
			var aviso = "Você deseja cancelar esta NOTA FISCAL, esta ação não poderá ser desfeita?";
			bootbox.dialog({
				message: aviso,
				title: "Cancelar NF-e",
				buttons: {
					salvar: {
						label: "Tenho certeza!!!",
						className: "red",
						callback: function () {
							window.open('nfe_cancelar.php?id=' + id, 'Cancelar NF-e', 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
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
	};

	//BOX DIALOG Cancelar Nota Fiscal na Receita
	if ($('.permitir_cancelar_extemporaneo').length > 0) {
		$('.permitir_cancelar_extemporaneo').click(function () {
			var id = $(this).attr('id');
			var acao = $(this).attr('acao');
			var parent = $(this).parents("tr");
			var aviso = "========================================> ATENÇÃO <========================================<br><br> Antes de permitir o cancelamento extemporaneo aqui no sistema a sua contabilidade deve fazer o pedido na Sefaz <br><br> A sua contabilidade já fez o pedido de cancelamento na Sefaz e você tem certeza que deseja autorizar o cancelamento extemporaneo desta NOTA FISCAL? <br><br> ATENÇÃO: Esta ação não irá cancelar a Nota Fiscal, somente habilita o botão de cancelamento.";
			bootbox.dialog({
				message: aviso,
				title: "Habilitar (no sistema) Cancelamento Extemporaneo de NF-e",
				buttons: {
					salvar: {
						label: "Estou ciente e tenho certeza sobre o cancelamento!!!",
						className: "red-pink",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'permitir_cancelar_extemporaneo=' + id,
								success: function (data) {
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	//BOX DIALOG - Exibir conta
	if ($('a.conta').length > 0) {
		$('a.conta').click(function () {
			var id = $(this).attr('id');
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você deseja exibir esta conta?";
			bootbox.dialog({
				message: aviso,
				title: "Plano de contas",
				buttons: {
					salvar: {
						label: "Exibir conta",
						className: "blue",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'exibirConta=' + id,
								success: function (data) {
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	//BOX DIALOG - Ocultar conta
	if ($('a.ocultarconta').length > 0) {
		$('a.ocultarconta').click(function () {
			var id = $(this).attr('id');
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você deseja ocultar esta conta?";
			bootbox.dialog({
				message: aviso,
				title: "Plano de contas",
				buttons: {
					salvar: {
						label: "Ocultar conta",
						className: "blue",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'apagarConta=' + id,
								success: function (data) {
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	//BOX DIALOG - Ativar Origem
	if ($('a.ativarorigem').length > 0) {
		$('a.ativarorigem').click(function () {
			var id = $(this).attr('id');
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você deseja ativar esta origem?";
			bootbox.dialog({
				message: aviso,
				title: "Ativar origem",
				buttons: {
					salvar: {
						label: "Ativar origem",
						className: "green",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'ativarOrigem=' + id,
								success: function (data) {
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	//BOX DIALOG Bloquear usuario
	if ($('.bloquear').length > 0) {
		$('.bloquear').click(function () {
			var id = $(this).attr('id');
			var acao = $(this).attr('acao');
			var parent = $(this).parents("tr");
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você deseja bloquear este registro?";
			bootbox.dialog({
				message: aviso,
				title: "Bloquear registro",
				buttons: {
					salvar: {
						label: "Bloquear Registro",
						className: "yellow",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: acao + '=' + id,
								success: function (data) {
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
										delay: 10000,
										stackup_spacing: 10
									});
									setTimeout(function () {
										location.reload();
									}, 1000);
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
	};

	//BOX DIALOG - Desbloquear Usuario
	if ($('a.ativar').length > 0) {
		$('a.ativar').click(function () {
			var id = $(this).attr('id');
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você deseja ativar este usuário?";
			bootbox.dialog({
				message: aviso,
				title: "Ativar usuário",
				buttons: {
					salvar: {
						label: "Ativar usuário",
						className: "green",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'ativarUsuario=' + id,
								success: function (data) {
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	//BOX DIALOG - Fechar Caixa
	if ($('a.fecharcaixa').length > 0) {
		$('a.fecharcaixa').click(function () {
			var id = $(this).attr('id');
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você deseja fechar este caixa?";
			bootbox.dialog({
				message: aviso,
				title: "Fechar caixa",
				buttons: {
					salvar: {
						label: "Fechar caixa",
						className: "yellow",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'fecharCaixa=' + id,
								success: function (data) {
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	//BOX DIALOG Pagar Cheque
	if ($('a.pagarcheque').length > 0) {
		$('a.pagarcheque').click(function () {
			var id = $(this).attr('id');
			var id_banco = $(this).attr('id_banco');
			$("#id_banco2").val(id_banco).change();
			$("#cheque_form").append('<input name="id_receita" type="hidden" value="' + id + '" />');
			$("#pagar-cheque").modal('show');
			return false;
		});
	};

	//BOX DIALOG Estornar Cheque
	if ($('.estornarcheque').length > 0) {
		$('.estornarcheque').click(function () {
			var id = $(this).attr('id');
			var parent = $(this).parents("tr");
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você deseja estornar este cheque?";
			bootbox.dialog({
				message: aviso,
				title: "Estornar cheque",
				buttons: {
					salvar: {
						label: "Estornar cheque",
						className: "yellow",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'estornarCheque=' + id,
								success: function (data) {
									parent.fadeOut(400, function () {
										parent.remove();
									});
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	// MODAL SHOW - Validar Caixa
	if ($('a.validarcaixa').length > 0) {
		$('a.validarcaixa').click(function () {
			var id = $(this).attr('id');
			var valor_dinheiro = $(this).attr('valor_dinheiro');
			var valor_cheque = $(this).attr('valor_cheque');
			$("#valor_dinheiro").val(valor_dinheiro);
			$("#validar_form").append('<input name="id_caixa" type="hidden" value="' + id + '" />');
			$("#validar-caixa").modal('show');
			return false;
		});
	};

	//CONSULTA EXTERNA - Buscar conta para pagamento
	$('#id_pai_pagamento').change(function () {
		$("#id_conta_pagamento option:first").attr('selected', true);
		$("#id_conta_pagamento").select2({
			allowClear: true
		});
		var pai = $("#id_pai_pagamento").val();
		jQuery.ajax({
			type: 'post',
			url: 'webservices/buscaconta.php',
			data: 'id_pai=' + pai,
			success: function (data) {
				$("#id_conta_pagamento").html(data);
			}
		});
	});

	//CONSULTA EXTERNA -  Buscar conta para despesa
	$('#id_pai_despesa').change(function () {
		$("#id_conta_despesa option:first").attr('selected', true);
		$("#id_conta_despesa").select2({
			allowClear: true
		});
		var pai = $("#id_pai_despesa").val();
		jQuery.ajax({
			type: 'post',
			url: 'webservices/buscaconta.php',
			data: 'id_pai=' + pai,
			success: function (data) {
				$("#id_conta_despesa").html(data);
			}
		});
	});

	// CALCULAR Valor Total Produto
	$('#quantidade').on('keyup', function (e) {
		var valor = $("#valor").val();
		v = parseFloat(valor);
		if (isNaN(v)) {
			v = 0;
		} var quantidade = $("#quantidade").val();
		q = parseFloat(quantidade);
		if (isNaN(q)) {
			q = 1;
		}
		var desconto = $("#valor_desconto").val();
		var d = desconto.replace('.', '');
		d = d.replace(',', '.');
		d = d.replace('R$ ', '');
		d = parseFloat(d);
		if (isNaN(d)) {
			d = 0;
		}
		var valor_total = (v * q) - d;
		valor_total = valor_total.toFixed(2);
		valor_total = valor_total.toString();
		valor_total = valor_total.replace('.', ',');
		$('#valor_total').val('R$ ' + valor_total);
	});

	//Verificar a quantidade do produto quando o campo quantidade perder a seleção
	$(document).on('onBlur', '.quant_venda', function () {
		var produto = $(this).parents("tr");
		var quant_venda = produto.find(".quant_venda").val();
		q = parseFloat(quant_venda);
		if (isNaN(q) || q == 0) {
			produto.find(".quant_venda").focus().select();
			q = 0;
		}
	});

	// CALCULAR Valor Total Produto pela quantidade digitada
	$(document).on('keyup', '.quant_venda', function (e) {

		if (e.which == 13 || e.keyCode == 13) {
			$('.barcode').val('');
			$('.barcode').focus();
			return;
		}

		var produto = $(this).parents("tr");
		var valor = produto.find(".valor").val();
		var quant_venda = produto.find(".quant_venda").val();
		var val_pagar = $("#val_pagar").html();

		v = realParaFloat(valor);
		if (isNaN(v)) {
			v = 0;
		}

		quant_venda = quant_venda.replace(',', '');
		q = parseFloat(quant_venda);
		if (q < 0) {
			alert('Quantidade do produto NÃO PODE ser negativo.');
			produto.find(".quant_venda").val((1.000));
		}
		if (isNaN(q) || q == 0) {
			alert('Cuidado, a quantidade do produto não pode ser zerada.');
			produto.find(".quant_venda").focus().select();
			q = 0;
		}

		if (val_pagar < 0) {
			val_pagar = 0;
			alert("O valor a pagar não pode der negativo");
		}

		var total = (v * q);
		if (((total % 1) != 0) && (!isNaN(total % 1))) {
			total.toString;
			total += '0001';
		}
		total = parseFloat(total);
		valor_total = total.toFixed(2);
		valor_total = valor_total.toString();
		valor_total = valor_total.replace('.', ',');
		produto.find(".valor_total").text('R$ ' + valor_total);
		produto.find(".total").val(total);

		// var acrescimo = $("#valor_acrescimo_rapida").val();
		var acrescimo = $('#valor_acrescimo_modal').val();
		var a = acrescimo.replace('.', '');
		a = a.replace(',', '.');
		a = a.replace('R$ ', '');
		a = parseFloat(a);
		if (isNaN(a)) {
			a = 0;
		}

		var soma = 0;
		$('.total').each(function (indice, item) {
			var i = $(item).val();
			var v = parseFloat(i);
			vlr = v.toFixed(2);
			if (!isNaN(v)) {
				soma += parseFloat(vlr);
			}
		});
		soma = soma.toFixed(2);
		$("#valor").val(soma);
		var resultado = soma.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#valor2").text(resultado);
		$('#valor_total_modal').val(resultado);

		// var desconto = $( "#valor_desconto_rapida" ).val();
		var desconto = $("#valor_desconto_modal").val();
		var d = desconto.replace('.', '');
		d = d.replace(',', '.');
		d = d.replace('R$ ', '');
		d = parseFloat(d);
		if (isNaN(d)) {
			d = 0;
		}

		var valor_pagar = soma + a - d;
		valor_pagar = valor_pagar.toFixed(2);
		valor_pagar = valor_pagar.toString();
		valor_pagar = valor_pagar.replace('.', ',');
		$('#valor_pagar').text('R$ ' + valor_pagar);

		var valor = $("#valor").val();
		var v = parseFloat(valor);
		if (isNaN(v)) {
			v = 0;
		}
		// var desconto = $( "#valor_desconto_rapida" ).val();
		var desconto = $("#valor_desconto_modal").val();
		var d = desconto.replace('.', '');
		d = d.replace(',', '.');
		d = d.replace('R$ ', '');
		d = parseFloat(d);
		if (isNaN(d)) {
			d = 0;
		}

		if (d > 0) {
			$("#valor_desconto_modal").trigger({ type: 'keyup', which: 13, keyCode: 13 });
		}

		var soma = 0;
		$('.valor_pago').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma += p;
			}
		});
		var soma_dinheiro = 0;
		$('.dinheiro').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma_dinheiro += p;
			}
		});

		var valor_pagar = v + a - d - soma;
		if (valor_pagar < 0) {
			valor_pagar = 0;
		}
		var resultado = valor_pagar.toFixed(2);
		resultado = resultado.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#valor_pagar").text(resultado);
		$('#valor_pago_modal').val(resultado);
		$('#valor_pagar_modal_pgto').val(resultado);

		var soma_restante = soma - soma_dinheiro;
		var total_pagar_dinheiro = v + a - d - soma_restante;
		var troco = soma_dinheiro - total_pagar_dinheiro;
		if (troco < 0) {
			troco = 0;
		}
		resultado = troco.toFixed(2);
		resultado = resultado.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#troco").text(resultado);
	});

	// CALCULAR Valor Total Produto
	$('#valor_desconto').on('keyup', function (e) {
		var valor = $("#valor").val();
		v = parseFloat(valor);
		if (isNaN(v)) {
			v = 0;
		}
		var quantidade = $("#quantidade").val();
		q = parseFloat(quantidade);
		if (isNaN(q)) {
			q = 1;
		}
		var desconto = $("#valor_desconto").val();
		var d = desconto.replace('.', '');
		d = d.replace(',', '.');
		d = d.replace('R$ ', '');
		d = parseFloat(d);
		if (isNaN(d)) {
			d = 0;
		}

		var valor_total = (v * q) + a - d;
		var total = valor_total;

		if (((total % 1) != 0) && (!isNaN(total % 1))) {
			total.toString;
			total += '0001';
		}

		total = parseFloat(total);
		valor_total = total.toFixed(2);

		valor_total = valor_total.toString();
		valor_total = valor_total.replace('.', ',');
		$('#valor_total').val('R$ ' + valor_total);
	});

	// Limpa o CODIGO DE BARRAS na tela de VENDAS DO CLIENTE
	$('.barcodevendas').on('blur', function (e) {
		$(".barcodevendas").val('');
	});

	// Ler o CODIGO DE BARRAS na tela de VENDAS DO CLIENTE
	$('.barcodevendas').on('keypress', function (e) {
		var codigo = $(this).val();
		if (e.which == 13) {

			var tipo = codigo.substr(0, 1);
			var prod = codigo.substr(1, 6);
			var vl = codigo.substr(7, 3);
			var dc = codigo.substr(10, 2);

			vl_peso = parseFloat(vl + '.' + dc);
			if (isNaN(vl_peso)) {
				vl_peso = 0;
			}
			$('.barcodevendas').val('');
			$('.barcodevendas').focus();
			var id_tabela = $("#id_tabela_venda").val();
			var id_cadastro = $("#id_cadastro").val();
			var id_venda = $("#id_venda").val();
			var valor = 0;
			var total = 0;
			var valor_venda = 0;
			var valor_total = 0;
			var estoque = 0;
			var quantidade = 1;
			if (tipo == '2') {
				codigo = prod;
			}
			if (prod == '00000') {
				codigo = parseInt(codigo);
			}
			jQuery.ajax({
				type: 'post',
				url: 'webservices/buscacodigo.php',
				data: 'codigo=' + codigo + '&id_tabela=' + id_tabela,
				success: function (data) {
					var temp = data.split("#");
					id_produto = temp[2];
					if (temp.length >= 4 && id_produto > 0) {
						valor_venda = 'R$ ' + temp[0].replace('.', ',');
						valor = temp[0];
						jQuery.ajax({
							type: 'POST',
							url: 'controller.php',
							data: 'processarVendaProduto=1&id_venda=' + id_venda + '&id_produto=' + id_produto + '&id_cadastro=' + id_cadastro + '&id_tabela=' + id_tabela + '&quantidade=' + quantidade + '&valor=' + valor,
							success: function (data) {
								var response = data.split("#");
								$.bootstrapGrowl(response[0], {
									ele: "body",
									type: response[1],
									offset: {
										from: "top",
										amount: 50
									},
									align: "center",
									width: "auto",
									delay: 10000,
									stackup_spacing: 10
								});
								if (response[2] == "1")
									window.location.href = response[3];
								setTimeout(function () {
								}, 0);
							}
						});
					} else {
						alert('Não foi possível encontrar o produto nesta tabela!');
						return false;
					}
				}
			});
		}
	});

	// Limpa o CODIGO DE BARRAS na tela de VENDAS DO CLIENTE
	$('.barcode').on('blur', function (e) {
		$(".barcode").val('');
	});

	// Ler o CODIGO DE BARRAS na tela de VENDAS DO CLIENTE
	$('.barcode').on('keyup', function (e) {
		var codigo = $(this).val();

		if (!codigo) {
			$('.barcode').val('');
			$('.barcode').focus();
			return;
		}

		var codigo_original = $(this).val();

		if (e.which == 13 || e.keyCode == 13) {
			$.ajax({
				type: 'post',
				url: 'webservices/buscaConfiguracao.php',
				data: {
					config: 'calibragem_balanca'
				},
				dataType: 'json',
				success: function (response) {
					const calibragem = response;

					var tipo = codigo.substr(0, 1);
					var prod = codigo.substr(1, 6);
					var vl = codigo.substr(7, 3);
					var dc = codigo.substr(10, 2);

					// APLICAR A CONFIGURACAO =========================================
					if (!calibragem) {
						vl = codigo.substr(7, 2);
						dc = codigo.substr(9, 3);
					}

					vl_peso = parseFloat(vl + '.' + dc);

					if (isNaN(vl_peso)) {
						vl_peso = 0;
					}

					$('.barcode').val('');
					$('.barcode').focus();

					var id_tabela = $("#id_tabela_venda").val();
					var valor = 0;
					var total = 0;
					var valor_venda = 0;
					var valor_total = 0;
					var estoque = 0;
					var quantidade = !calibragem ? parseFloat(vl_peso).toFixed(3) : parseFloat(1.000).toFixed(3);

					if (tipo == '2' && codigo.length == 13) {
						codigo = parseInt(prod);
					}
					if (prod == '00000') {
						codigo = parseInt(codigo);
					}

					jQuery.ajax({
						type: 'post',
						url: 'webservices/buscacodigo.php',
						data: 'codigo=' + codigo + '&id_tabela=' + id_tabela,
						success: function (data) {
							var temp = data.split("#");
							id_produto = temp[2];
							if (temp.length >= 4 && id_produto > 0) {

								let unidade = temp[5];
								let codigo_produto = temp[6];

								pagamentoAVista = false;
								controle_avista = 1;
								controle_pagamentos = 0;
								$('.pagamento_avista').each(function (indice, item) {
									var i = $(item).val();
									var p = parseInt(i);
									controle_avista *= p;
									controle_pagamentos = 1;
								});
								if (controle_avista == 1 && controle_pagamentos == 1)
									pagamentoAVista = true;

								if (pagamentoAVista && temp[4] > 0) {
									valor_produto = temp[4];
								} else {
									valor_produto = temp[0];
								}

								valor_venda_avista = parseFloat(temp[4]).toFixed(2);
								valor_venda_normal = parseFloat(temp[0]).toFixed(2);

								valor_venda = parseFloat(valor_produto);
								nome_produto = temp[3];
								estoque = parseFloat(temp[1]).toFixed(3);
								valor = parseFloat(valor_produto);

								if (isNaN(quantidade) || quantidade == 0) {
									quantidade = parseFloat(1);
									quantidade = quantidade.toFixed(3);
								}

								// APLICAR A CONFIGURACAO =========================================
								if (tipo == '2' && codigo_original.length == 13 && calibragem) {
									quantidade = vl_peso / valor;
									quantidade = quantidade.toFixed(3);
									total = vl_peso;
								} else {
									total = (valor * quantidade);
								}

								valor_total = total.toFixed(2);
								valor_total = valor_total.toString();
								valor_total = 'R$ ' + valor_total.replace('.', ',');

								let modalActive = $('#modal_alterar_valor_produto_venda').val() == 1 ? 1 : 0;

								var $el = $("#tabela_produtos");
								$el.prepend(`
							<tr>
								<td><span class="font-md">#${codigo_produto} - ${nome_produto}</span></td>
								<td><span class="font-md">${unidade}</span></td>
								<td><span class="font-md">${estoque}</span></td>
								<td><span class="bold theme-font">
									<input type="text" class="form-control form-filter input-sm quant_venda decimal" name="quantidade[]" value="${quantidade}" id="quantidade_produto">
								</span></td>
								<td>
									<span class="bold theme-font">
										<input type="text" class="form-control form-filter input-sm moeda valor input-valor-normal" name="valor_venda_tabela[]" valor_avista="${valor_venda_avista}" valor_normal="${valor_venda_normal}" value="${floatParaReal(valor_venda)}" id="vlr_venda_produto" style="width: 90px">
									</span>
								</td>
								<td><span class="bold theme-font font-md valor_total">${valor_total}<span></td>
								<td>
									<a href="javascript:void(0);" class="btn btn_alterar_valor_produto_venda" title="Alterar valor deste produto?" style="background-color: #EECB00; color: #675800">
										<i class="fa fa-dollar"></i>
									</a>
									<a href="javascript:void(0);" class="btn red remover_produto_venda" title="Deseja remover este produto?">
										<i class="fa fa-times"></i>
									</a>
								</td>
								<input name="nome_produto_pdv[]" type="hidden" class="nome_produto_pdv" value="`+ nome_produto + `" />
								<input name="estoque_produto_pdv[]" type="hidden" class="estoque_produto_pdv" value="`+ estoque + `" />
								<input name="id_produto[]" type="hidden" class="id_produto" value="${id_produto}" />
								<input type="hidden" class="total" value="${total}" />
								<input type="hidden" class="estoque" value="${estoque}" />
								<input name="tabelas[]" type="hidden" class="tabelas" value="`+ id_tabela + `" />
							</tr>`
								).find("tr").first().hide();

								if (modalActive == 1) {
									let inputValorNormal = document.querySelector(".input-valor-normal")
									inputValorNormal.disabled = true
								}

								const styles = {
									"pointer-events": "none",
									"background-color": "#eaeaea"
								}

								if (modalActive === 1) {
									$('#vlr_venda_produto').css(styles);
								}

								if (modalActive === 0) $('.btn_alterar_valor_produto_venda').addClass('hidden');
								$el.find("tr").first().fadeIn();

								$('.quant_venda').maskMoney({ decimal: '.', precision: 3, symbolStay: false, allowNegative: true });
								$('#vlr_venda_produto').maskMoney({ symbol: 'R$ ', thousands: '.', decimal: ',', precision: 2, symbolStay: true, allowNegative: true });

								var soma = 0;
								$('.total').each(function (indice, item) {
									var i = $(item).val();
									var v = parseFloat(i);
									vlr = v.toFixed(2);
									if (!isNaN(v)) {
										soma += parseFloat(vlr);
									}
								});
								soma = soma.toFixed(2);
								$("#valor").val(soma);
								var resultado = soma.toString();
								resultado = 'R$ ' + resultado.replace('.', ',');
								$("#valor2").text(resultado);
								$('#valor_total_modal').val(resultado);

								// var desconto = $( "#valor_desconto_rapida" ).val();
								var desconto = $('#valor_desconto_modal').val();
								var d = desconto.replace('.', '');
								d = d.replace(',', '.');
								d = d.replace('R$ ', '');
								d = parseFloat(d);
								if (isNaN(d)) {
									d = 0;
								}
								var valor_pagar = soma - d;
								valor_pagar = valor_pagar.toFixed(2);
								valor_pagar = valor_pagar.toString();
								valor_pagar = valor_pagar.replace('.', ',');
								$('#valor_pagar').text('R$ ' + valor_pagar);

								var valor = $("#valor").val();
								var v = parseFloat(valor);
								if (isNaN(v)) {
									v = 0;
								}
								// var desconto = $( "#valor_desconto_rapida" ).val();
								var desconto = $('#valor_desconto_modal').val();
								var d = desconto.replace('.', '');
								d = d.replace(',', '.');
								d = d.replace('R$ ', '');
								d = parseFloat(d);
								if (isNaN(d)) {
									d = 0;
								}

								if (d > 0) {
									$("#valor_desconto_modal").trigger({ type: 'keyup', which: 13, keyCode: 13 });
								}

								var soma = 0;
								$('.valor_pago').each(function (indice, item) {
									var i = $(item).val();
									var p = parseFloat(i);
									if (!isNaN(p)) {
										soma += p;
									}
								});
								var soma_dinheiro = 0;
								$('.dinheiro').each(function (indice, item) {
									var i = $(item).val();
									var p = parseFloat(i);
									if (!isNaN(p)) {
										soma_dinheiro += p;
									}
								});

								var valor_pagar = v - d - soma;
								if (valor_pagar < 0) {
									valor_pagar = 0;
								}
								var resultado = valor_pagar.toFixed(2);
								resultado = resultado.toString();
								resultado = 'R$ ' + resultado.replace('.', ',');
								$("#valor_pagar").text(resultado);
								$('#valor_pago_modal').val(resultado);
								$('#valor_pagar_modal_pgto').val(resultado);
								$('#show_valor_pagar_modal').val(resultado);

								var soma_restante = soma - soma_dinheiro;
								var total_pagar_dinheiro = v - d - soma_restante;
								var troco = soma_dinheiro - total_pagar_dinheiro;
								if (troco < 0) {
									troco = 0;
								}
								resultado = troco.toFixed(2);
								resultado = resultado.toString();
								resultado = 'R$ ' + resultado.replace('.', ',');
								$("#troco").text(resultado);

								//coloca o foco no codigo de barras
								$('.barcode').focus().select();
							} else {
								alert('Não foi possível encontrar o produto nesta tabela!');
								return false;
							}

							//coloca o foco no codigo de barras
							$('.barcode').focus().select();
						}
					});
				}
			});
		}

		//Se a primeira coisa digitada no codigo de barras for diferente de um número, chama o modal de produtos.
		if (isNaN(codigo_original.substr(0, 1))) {
			modalProdutosComF1(codigo_original.substr(0, 1));
		}
	});

	//RECEITAS - Adicionar receita rapida
	if ($('#adicionar_nova_receita').length > 0) {
		$('#adicionar_nova_receita').click(function () {
			var id_empresa_receita = $("#id_empresa_receita option:selected").val();
			if (id_empresa_receita == "") {
				alert('Favor selecionar uma empresa.');
				$('#id_empresa_receita').focus().select();
				return false;
			}
			var nome_empresa = $("#id_empresa_receita option:selected").text();
			var id_banco_receita = $("#id_banco_receita option:selected").val();
			if (id_banco_receita == "") {
				alert('Favor selecionar um banco.');
				$('#id_banco_receita').focus().select();
				return false;
			}
			var nome_banco = $("#id_banco_receita option:selected").text();
			var tipo_receita = $("#tipo_receita option:selected").val();
			if (tipo_receita == "") {
				alert('Favor selecionar um pagamento.');
				$('#tipo_receita').focus().select();
				return false;
			}
			var nome_tipo = $("#tipo_receita option:selected").text();
			var descricao_receita = $("#descricao_receita").val();
			if (descricao_receita == "") {
				alert('Favor selecionar uma descricao.');
				$('#descricao_receita').focus();
				return false;
			}
			var valor_receita = $("#valor_receita").val();
			if (valor_receita == "") {
				alert('Favor selecionar um valor.');
				$('#valor_receita').focus();
				return false;
			}
			var data_receita = $("#data_receita").val();
			if (data_receita == "") {
				alert('Favor selecionar um data.');
				$('#data_receita').focus();
				return false;
			}

			var $el = $("#tabela_receita");
			$el.prepend(`
				<tr>
					<td><span class="font-md"> ${nome_empresa} </span></td>
					<td><span class="font-md"> ${nome_banco} </span></td>
					<td><span class="font-md"> ${nome_tipo} </span></td>
					<td><span class="font-md"> ${descricao_receita} </span></td>
					<td><span class="font-md"> ${valor_receita} </span></td>
					<td><span class="font-md"> ${data_receita} </span></td>
					<td><a href="javascript:void(0);" class="btn btn-sm red remover_receita" title="Deseja remover esta receita?"><i class="fa fa-times"></i></a></td>
					<input name="id_empresa[]" type="hidden" class="id_empresa" value=" ${id_empresa_receita} " />
					<input type="hidden" name="id_banco[]" class="id_banco" value=" ${id_banco_receita} " />
					<input type="hidden" name="tipo[]" class="tipo" value=" ${tipo_receita} " />
					<input type="hidden" name="descricao[]" class="descricao" value=" ${descricao_receita} " />
					<input type="hidden" name="valor[]" class="valor" value=" ${valor_receita} " />
					<input type="hidden" name="data_receita[]" class="data" value=" ${data_receita} " />
				</tr>
			`).find("tr").first().hide();
			$el.find("tr").first().fadeIn();
		});
	};

	// RECEITAS - Remover receita rapida
	$(document).on('click', 'a.remover_receita', function () {
		var item = $(this).parents("tr");
		item.remove();
	});

	// TROCA DE PRODUTOS - Adicionar produto para troca - case "trocarprodutoavulso"
	if ($('#adicionar_produto_a_trocar').length > 0) {
		$('#adicionar_produto_a_trocar').click(function () {
			var id_produto = $("#id_produto_venda option:selected").val();
			var avista = 0;
			if (id_produto == "" || id_produto == undefined) {
				alert('Favor selecionar um produto.');
				return false;
			}
			var nome_produto = $("#id_produto_venda option:selected").text();
			var id_tabela = $("#id_tabela_venda").val();
			var valor = 0;
			var valor_venda = 0;
			var valor_total = 0;
			var estoque = 0;
			var quantidade = 1;

			jQuery.ajax({
				type: 'post',
				url: 'webservices/buscaproduto.php',
				data: 'id=' + id_produto + '&id_tabela=' + id_tabela,
				success: function (data) {
					var temp = data.split("#");
					if (temp.length >= 2) {
						if ((avista == 1) && (temp[2] > 0)) {
							valor = parseFloat(temp[2]);
						} else {
							valor = parseFloat(temp[0]);
						}

						total = (valor * quantidade);
						valor = valor.toFixed(2);
						valor_venda = 'R$ ' + valor.replace('.', ',');
						estoque = temp[1];

						valor_total = total.toFixed(2);
						total = valor_total;
						valor_total = valor_total.toString();
						valor_total = 'R$ ' + valor_total.replace('.', ',');

						var $el = $("#tabela_produtos");// case "trocarprodutoavulso"
						$el.prepend(`
							<tr>
								<td><span class="font-md">${nome_produto}</span></td>
								<td><span class="font-md">${estoque}</span></td>
								<td>
									<span class="bold theme-font">
										<input type="text" class="form-control form-filter input-sm quant_venda_troca decimal" name="quantidade[]" value="1.000" id="quantidade_produto">
									</span>
								</td>
								<td>
									<input
										type="text"
										name="valor_venda[]"
										id="valor_venda_troca"
										class="form-control form-filter input-sm valor_venda_troca_produto_avulso"
										value="${floatParaReal(valor)}"
									/>
								</td>
								<td><span class="bold theme-font font-md valor_total">${valor_total}<span></td>
								<td>
									<a href="javascript:void(0);" class="btn red remover_produto_venda" title="Deseja remover este produto?">
										<i class="fa fa-times"></i>
									</a>
								</td>
								<input name="id_produto[]" type="hidden" class="id_produto" value="${id_produto}" />
								<input type="hidden" class="total" value="${total}" />
								<input type="hidden" class="estoque" value="${estoque}" />
							</tr>
						`).find("tr").first().hide();

						// <input type="hidden" name="valor_venda[]" class="valor" value="${valor}" />
						$el.find("tr").first().fadeIn();

						$('.quant_venda_troca').maskMoney({ decimal: '.', precision: 3, symbolStay: false, allowNegative: false });
						$('#quantidade_produto').focus().select();

						$('.valor_venda_troca_produto_avulso').maskMoney({ symbol: 'R$ ', thousands: '.', decimal: ',', precision: 2, symbolStay: true, allowNegative: true });

						var soma = 0;
						$('.total').each(function (indice, item) {
							var i = $(item).val();
							var v = parseFloat(i);
							vlr = v.toFixed(2);
							if (!isNaN(v)) {
								soma += parseFloat(vlr);
							}
						});
						soma = soma.toFixed(2);

						$("#valor").val(soma);
						var resultado = soma.toString();
						resultado = 'R$ ' + resultado.replace('.', ',');

						$("#valor2").text(resultado);

						var desconto = $("#valor_desconto_rapida").val();
						var d = desconto.replace('.', '');
						d = d.replace(',', '.');
						d = d.replace('R$ ', '');
						d = parseFloat(d);
						if (isNaN(d)) {
							d = 0;
						}

						if (d > 0) {
							$("#valor_desconto_rapida").trigger({ type: 'keyup', which: 13, keyCode: 13 });
						}

						var acrescimo = $("#valor_acrescimo_rapida").val();
						var a = acrescimo.replace('.', '');
						a = a.replace(',', '.');
						a = a.replace('R$ ', '');
						a = parseFloat(a);
						if (isNaN(a)) {
							a = 0;
						}

						var valor_pagar = soma + a - d;
						valor_pagar = valor_pagar.toFixed(2);
						valor_pagar = valor_pagar.toString();
						valor_pagar = valor_pagar.replace('.', ',');
						$('#valor_pagar').text('R$ ' + valor_pagar);

						var valor = $("#valor").val();
						var v = parseFloat(valor);
						if (isNaN(v)) {
							v = 0;
						}
						var desconto = $("#valor_desconto_rapida").val();
						var d = desconto.replace('.', '');
						d = d.replace(',', '.');
						d = d.replace('R$ ', '');
						d = parseFloat(d);
						if (isNaN(d)) {
							d = 0;
						}
						var soma = 0;
						$('.valor_pago').each(function (indice, item) {
							var i = $(item).val();
							var p = parseFloat(i);
							if (!isNaN(p)) {
								soma += p;
							}
						});
						var soma_dinheiro = 0;
						$('.dinheiro').each(function (indice, item) {
							var i = $(item).val();
							var p = parseFloat(i);
							if (!isNaN(p)) {
								soma_dinheiro += p;
							}
						});

						var valor_pagar = v + a - d - soma;
						if (valor_pagar < 0) {
							valor_pagar = 0;
						}
						var resultado = valor_pagar.toFixed(2);
						resultado = resultado.toString();
						resultado = 'R$ ' + resultado.replace('.', ',');
						$("#valor_pagar").text(resultado);

						var soma_restante = soma - soma_dinheiro;
						var total_pagar_dinheiro = v + a - d - soma_restante;
						var troco = soma_dinheiro - total_pagar_dinheiro;

						if (troco < 0) {
							troco = 0;
						}

						resultado = troco.toFixed(2);
						resultado = resultado.toString();
						resultado = 'R$ ' + resultado.replace('.', ',');
						$("#troco").text(resultado);
					} else {
						alert('Não foi possível encontrar o produto nesta tabela!');
						return false;
					}
				}
			});
		});
	};

	$(document).on('keyup', '.valor_venda_troca_produto_avulso', function () {

		var produto = $(this).parents("tr");
		var valor = produto.find(".valor_venda_troca_produto_avulso").val();
		var quant_venda = produto.find(".quant_venda_troca").val();

		v = realParaFloat(valor);
		if (isNaN(v)) {
			v = 0;
		}

		quant_venda = quant_venda.replace(',', '');
		q = parseFloat(quant_venda);
		if (isNaN(q) || q == 0) {
			alert('Cuidado, a quantidade do produto não pode ser zerada.');
			produto.find(".quant_venda").focus().select();
			q = 0;
		}

		var total = (v * q);
		if (((total % 1) != 0) && (!isNaN(total % 1))) {
			total.toString;
			total += '0001';
		}
		total = parseFloat(total);
		let valor_total = total.toFixed(2);

		valor_total = valor_total.toString();
		valor_total = valor_total.replace('.', ',');
		produto.find(".valor_total").text('R$ ' + valor_total);
		produto.find(".total").val(total);

		var soma = 0;
		$('.total').each(function (indice, item) {
			var i = $(item).val();
			var v = parseFloat(i);
			vlr = v.toFixed(2);
			if (!isNaN(v)) {
				soma += parseFloat(vlr);
			}
		});

		soma = soma.toFixed(2);
		$("#valor").val(soma);
		var resultado = soma.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#valor2").text(resultado);
		$('#valor_total_modal').val(resultado);

		var valor_pagar = soma;
		valor_pagar = valor_pagar.toFixed(2);
		valor_pagar = valor_pagar.toString();
		valor_pagar = valor_pagar.replace('.', ',');
		$('#valor_pagar').text('R$ ' + valor_pagar);

		var valor = $("#valor").val();
		var v = parseFloat(valor);
		if (isNaN(v)) {
			v = 0;
		}


		var soma = 0;
		$('.valor_pago').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma += p;
			}
		});

		var soma_dinheiro = 0;
		$('.dinheiro').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma_dinheiro += p;
			}
		});

		var valor_pagar = v - soma;
		if (valor_pagar < 0) {
			valor_pagar = 0;
		}
		var resultado = valor_pagar.toFixed(2);
		resultado = resultado.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');

	});

	// Limpa o CODIGO DE BARRAS na tela de VENDAS DO CLIENTE
	$('.barcode_troca').on('blur', function (e) {
		$(".barcode_troca").val('');
	});

	// Ler o CODIGO DE BARRAS na tela de VENDAS DO CLIENTE
	$('.barcode_troca').on('keyup', function (e) {
		var codigo = $(this).val();

		if (!codigo) {
			$('.barcode_troca').val('');
			$('.barcode_troca').focus();
			return;
		}

		var codigo_original = $(this).val();
		if (e.which == 13 || e.keyCode == 13) {

			$.ajax({
				type: 'post',
				url: 'webservices/buscaConfiguracao.php',
				data: {
					config: 'calibragem_balanca'
				},
				dataType: 'json',
				success: function (response) {
					const calibragem = response;
					var tipo = codigo.substr(0, 1);
					var prod = codigo.substr(1, 6);

					var vl = codigo.substr(7, 3);
					var dc = codigo.substr(10, 2);

					// APLICAR A CONFIGURACAO =========================================
					if (!calibragem) {
						vl = codigo.substr(7, 2);
						dc = codigo.substr(9, 3);
					}

					vl_peso = parseFloat(vl + '.' + dc);

					if (isNaN(vl_peso)) {
						vl_peso = 0;
					}
					$('.barcode_troca').val('');
					$('.barcode_troca').focus();
					var id_tabela = $("#id_tabela_venda").val();

					var valor_troca = 0;
					valor_troca = $("#valor_produto_troca").val();
					valor_troca = parseFloat(valor_troca);

					var total = 0;
					var valor_venda = 0;
					var valor_total = 0;
					var estoque = 0;
					var quantidade = !calibragem ? parseFloat(vl_peso).toFixed(3) : parseFloat(1.000).toFixed(3);

					if (tipo == '2' && codigo.length == 13) {
						codigo = prod;
					}
					if (prod == '00000') {
						codigo = parseInt(codigo);
					}

					jQuery.ajax({
						type: 'post',
						url: 'webservices/buscacodigo.php',
						data: 'codigo=' + codigo + '&id_tabela=' + id_tabela,
						success: function (data) {
							var temp = data.split("#");
							id_produto = temp[2];
							if (temp.length >= 4 && id_produto > 0) {

								pagamentoAVista = false;
								controle_avista = 1;
								controle_pagamentos = 0;
								$('.pagamento_avista').each(function (indice, item) {
									var i = $(item).val();
									var p = parseInt(i);
									controle_avista *= p;
									controle_pagamentos = 1;
								});
								if (controle_avista == 1 && controle_pagamentos == 1)
									pagamentoAVista = false;

								if (pagamentoAVista && temp[4] > 0) {
									valor_produto = temp[4];
								} else {
									valor_produto = temp[0];
								}

								valor_venda_avista = parseFloat(temp[4]).toFixed(2);
								valor_venda_normal = parseFloat(temp[0]).toFixed(2);

								valor_venda = parseFloat(valor_produto);
								nome_produto = temp[3];
								estoque = parseFloat(temp[1]).toFixed(3);
								valor = parseFloat(valor_produto);

								if (isNaN(quantidade) || quantidade == 0) {
									quantidade = parseFloat(1);
									quantidade = quantidade.toFixed(3);
								}

								if (tipo == '2' && codigo_original.length == 13 && calibragem) {
									quantidade = vl_peso / valor;
									quantidade = quantidade.toFixed(3);
									total = vl_peso;
								} else {
									total = (valor * quantidade);
								}

								valor_total = total.toFixed(2);
								valor_total = valor_total.toString();
								valor_total = 'R$ ' + valor_total.replace('.', ',');

								var $el = $("#tabela_produtos"); //barcode_troca
								$el.prepend(`
							<tr>
								<td>
									<span class="font-md">${nome_produto}</span>
								</td>
								<td>
									<span class="font-md">${estoque}</span>
								</td>
								<td>
									<span class="bold theme-font">
										<input
											type="text"
											class="form-control form-filter input-sm quant_venda_troca decimal"
											name="quantidade[]"
											value="1.000"
											id="quantidade_produto"
										/>
									</span>
								</td>
								<td>
									<input
										type="text"
										name="valor_venda[]"
										id="valor_venda_troca"
										class="form-control form-filter input-sm valor_venda_troca_produto_avulso2"
										value="${floatParaReal(valor)}"
									/>
								</td>
								<td><span class="bold theme-font font-md valor_total">${valor_total}</span></td>
								<td>
									<a
										href="javascript:void(0);"
										class="btn red remover_produto_troca"
										title="Deseja remover este produto?">
										<i class="fa fa-times"></i>
									</a>
								</td>
								<input name="id_produto[]" type="hidden" class="id_produto" value="${id_produto}" />
								<input type="hidden" class="total" value="${total}" />
								<input type="hidden" class="estoque" value="${estoque}" />
							</tr>
						`).find("tr").first().hide();

								// <input type="hidden" name="valor_venda[]" class="valor" value="'+valor+'" />
								// <td><span class="font-md">'+valor_venda+'</span></td>

								$el.find("tr").first().fadeIn();
								$('.quant_venda_troca').maskMoney({ decimal: '.', precision: 3, symbolStay: false, allowNegative: true });

								$('.valor_venda_troca_produto_avulso2').maskMoney({ symbol: 'R$ ', thousands: '.', decimal: ',', precision: 2, symbolStay: true, allowNegative: true });

								var soma = 0;
								$('.total').each(function (indice, item) {
									var i = $(item).val();
									var v = parseFloat(i);
									vlr = v.toFixed(2);
									if (!isNaN(v)) {
										soma += parseFloat(vlr);
									}
								});
								soma = soma.toFixed(2);
								$("#valor").val(soma);
								var resultado = soma.toString();
								resultado = 'R$ ' + resultado.replace('.', ',');
								$("#valor2").text(resultado);

								var desconto = $("#valor_desconto_troca").val() ? $("#valor_desconto_troca").val() : '0';
								var d = desconto.replace('.', '');
								d = d.replace(',', '.');
								d = d.replace('R$ ', '');
								d = parseFloat(d);
								if (isNaN(d)) {
									d = 0;
								}

								if (d > 0) {
									$("#valor_desconto_troca").trigger({ type: 'keyup', which: 13, keyCode: 13 });
								}

								var valor_pagar = soma - d - valor_troca;
								valor_pagar = valor_pagar.toFixed(2);
								valor_pagar = valor_pagar.toString();
								valor_pagar = valor_pagar.replace('.', ',');
								$('#valor_pagar').text('R$ ' + valor_pagar);

								var valor = $("#valor").val();
								var v = parseFloat(valor);
								if (isNaN(v)) {
									v = 0;
								}

								var soma = 0;
								$('.valor_pago').each(function (indice, item) {
									var i = $(item).val();
									var p = parseFloat(i);
									if (!isNaN(p)) {
										soma += p;
									}
								});
								var soma_dinheiro = 0;
								$('.dinheiro').each(function (indice, item) {
									var i = $(item).val();
									var p = parseFloat(i);
									if (!isNaN(p)) {
										soma_dinheiro += p;
									}
								});

								var valor_pagar = v - d - soma - valor_troca;

								if (valor_pagar < 0) {
									$(".valor_pagar_titulo").text('Valor a utilizar');
									$(".valor_pagar_texto").removeClass("font-green-seagreen");
									$(".valor_pagar_texto").addClass("font-red");
									valor_pagar *= -1;
								} else {
									$(".valor_pagar_titulo").text('Valor a pagar');
									$(".valor_pagar_texto").removeClass("font-red");
									$(".valor_pagar_texto").addClass("font-green-seagreen");
								}

								var resultado = valor_pagar.toFixed(2);
								resultado = resultado.toString();
								resultado = 'R$ ' + resultado.replace('.', ',');
								$("#valor_pagar").text(resultado);

								var a = 0; //acrescimo;
								var soma_restante = soma - soma_dinheiro;
								var total_pagar_dinheiro = v + a - d - soma_restante;
								var troco = soma_dinheiro - total_pagar_dinheiro;

								troco = (soma + valor_troca) - (v - d);
								total_troco = 0;
								if (troco < 0)
									total_troco = 0;
								else
									if (troco >= soma_dinheiro)
										total_troco = soma_dinheiro;
									else
										total_troco = troco;

								total_troco = total_troco.toFixed(2);
								if (total_troco > 0) {
									valor_a_pagar = valor_pagar.toFixed(2);

									valor_a_pagar = valor_a_pagar - total_troco;
									valor_a_pagar = valor_a_pagar.toFixed(2);
									resultado = valor_a_pagar.toString();
									resultado = 'R$ ' + resultado.replace('.', ',');
									$("#valor_pagar").text(resultado);
								}

								resultado = total_troco.toString();
								resultado = 'R$ ' + resultado.replace('.', ',');
								$("#troco").text(resultado);

								//coloca o foco no codigo de barras
								$('.barcode_troca').focus().select();


								if ($('.valor_pagar_texto').hasClass('font-green-seagreen')) {
									$('.salvar-restante-crediario').addClass('ocultar');
								} else {
									$('.salvar-restante-crediario').removeClass('ocultar');
								}

							} else {
								alert('Não foi possível encontrar o produto nesta tabela!');
								return false;
							}

							//coloca o foco no codigo de barras
							$('.barcode_troca').focus().select();

						}
					});
				}
			})
		}

		//Se a primeira coisa digitada no codigo de barras for diferente de um número, chama o modal de produtos.
		if (isNaN(codigo_original.substr(0, 1))) {
			modalProdutosComF1(codigo_original.substr(0, 1));
		}

	});

	// TROCA DE PRODUTOS - Adicionar produto na TROCA DE PRODUTOS - case "produtotrocaavulso"
	if ($('#adicionar_produto_troca').length > 0) {
		$('#adicionar_produto_troca').click(function () {
			var id_produto = $("#id_produto_venda option:selected").val();
			if (id_produto == "" || id_produto == undefined) {
				alert('Favor selecionar um produto.');
				$('.salvar-restante-crediario').addClass('ocultar');
				return false;
			}

			var nome_produto = $("#id_produto_venda option:selected").text();
			var id_tabela = $("#id_tabela_venda").val();

			var valor_troca = 0;
			valor_troca = $("#valor_produto_troca").val();
			valor_troca = parseFloat(valor_troca);

			var valor_venda = 0;
			var valor_total = 0;
			var estoque = 0;
			var quantidade = 1;
			jQuery.ajax({
				type: 'post',
				url: 'webservices/buscaproduto.php',
				data: 'id=' + id_produto + '&id_tabela=' + id_tabela,
				success: function (data) {
					var temp = data.split("#");
					if (temp.length >= 2) {
						var pagamento = 0;
						var valor_pagar_produto = 0;
						if ((pagamento == 1) && (temp[2] > 0)) {
							valor_pagar_produto = temp[2];
						} else {
							valor_pagar_produto = temp[0];
						}

						valor_venda = 'R$ ' + valor_pagar_produto.replace('.', ',');
						estoque = temp[1];
						valor = valor_pagar_produto;
						total = (valor * quantidade);
						valor_total = total.toFixed(2);
						valor_total = valor_total.toString();
						valor_total = 'R$ ' + valor_total.replace('.', ',');

						var $el = $("#tabela_produtos"); //case "produtotrocaavulso"
						$el.prepend(`
							<tr>
								<td><span class="font-md">${nome_produto}</span></td>
								<td><span class="font-md">${estoque}</span></td>
								<td>
									<span class="bold theme-font">
										<input
											type="text"
											class="form-control
											form-filter input-sm quant_venda_troca decimal"
											name="quantidade[]"
											value="1.000"
											id="quantidade_produto"
										/>
									</span>
								</td>
								<td>
									<input
										type="text"
										name="valor_venda[]"
										id="valor_venda_troca"
										class="form-control form-filter input-sm valor_venda_troca_produto_avulso2"
										value="${floatParaReal(valor)}"
									/>
								</td>
								<td><span class="bold theme-font font-md valor_total">${valor_total}</span></td>
								<td>
									<a href="javascript:void(0);" class="btn red remover_produto_troca" title="Deseja remover este produto?">
										<i class="fa fa-times"></i>
									</a>
								</td>
								<input name="id_produto[]" type="hidden" class="id_produto" value="${id_produto}" />
								<input type="hidden" class="total" value="${total}" />
								<input type="hidden" class="estoque" value="${estoque}" />
							</tr>
						`).find("tr").first().hide();

						// <input type="hidden" name="valor_venda[]" class="valor" value="${valor}" />
						// <span class="font-md">${valor_venda}</span>

						$el.find("tr").first().fadeIn();

						$('.quant_venda_troca').maskMoney({ decimal: '.', precision: 3, symbolStay: false, allowNegative: true });
						$('#quantidade_produto').focus().select();

						$('.valor_venda_troca_produto_avulso2').maskMoney({ symbol: 'R$ ', thousands: '.', decimal: ',', precision: 2, symbolStay: true, allowNegative: true });

						var soma = 0;
						$('.total').each(function (indice, item) {
							var i = $(item).val();
							var v = parseFloat(i);
							if (!isNaN(v)) {
								soma += v;
							}
						});
						soma = soma.toFixed(2);

						$("#valor").val(soma);
						var resultado = soma.toString();
						resultado = 'R$ ' + resultado.replace('.', ',');
						$("#valor2").text(resultado);
						// $('#valor_pago').val(resultado); //Valor a pagar em troca de produto

						var desconto = $("#valor_desconto_troca").val();
						var d = desconto.replace('.', '');
						d = d.replace(',', '.');
						d = d.replace('R$ ', '');
						d = parseFloat(d);
						if (isNaN(d)) {
							d = 0;
						}

						if (d > 0) {
							$("#valor_desconto_troca").trigger({ type: 'keyup', which: 13, keyCode: 13 });
						}

						var valor_pagar = soma - d - valor_troca;
						valor_pagar = valor_pagar.toFixed(2);
						valor_pagar = valor_pagar.toString();
						valor_pagar = valor_pagar.replace('.', ',');
						$('#valor_pagar').text('R$ ' + valor_pagar);

						var valor = $("#valor").val();
						var v = parseFloat(valor);
						if (isNaN(v)) {
							v = 0;
						}

						var soma = 0;
						$('.valor_pago').each(function (indice, item) {
							var i = $(item).val();
							var p = parseFloat(i);
							if (!isNaN(p)) {
								soma += p;
							}
						});
						var soma_dinheiro = 0;
						$('.dinheiro').each(function (indice, item) {
							var i = $(item).val();
							var p = parseFloat(i);
							if (!isNaN(p)) {
								soma_dinheiro += p;
							}
						});

						var valor_pagar = v - d - soma - valor_troca;

						if (valor_pagar < 0) {
							$(".valor_pagar_titulo").text('Valor a utilizar');
							$(".valor_pagar_texto").removeClass("font-green-seagreen");
							$(".valor_pagar_texto").addClass("font-red");
							valor_pagar *= -1;
						} else {
							$(".valor_pagar_titulo").text('Valor a pagar');
							$(".valor_pagar_texto").removeClass("font-red");
							$(".valor_pagar_texto").addClass("font-green-seagreen");
						}

						var resultado = valor_pagar.toFixed(2);
						resultado = resultado.toString();
						resultado = 'R$ ' + resultado.replace('.', ',');
						$("#valor_pagar").text(resultado);

						troco = (soma + valor_troca) - (v - d);
						total_troco = 0;
						if (troco < 0)
							total_troco = 0;
						else
							if (troco >= soma_dinheiro)
								total_troco = soma_dinheiro;
							else
								total_troco = troco;

						total_troco = total_troco.toFixed(2);
						if (total_troco > 0) {
							valor_a_pagar = valor_pagar.toFixed(2);

							valor_a_pagar = valor_a_pagar - total_troco;
							valor_a_pagar = valor_a_pagar.toFixed(2);
							resultado = valor_a_pagar.toString();
							resultado = 'R$ ' + resultado.replace('.', ',');
							$("#valor_pagar").text(resultado);
						}

						//Verifica no menu Vendas > Caixa > Troca de produto Fiscal se existe valor a utilizar para salvar no crediario;
						if ($('.valor_pagar_texto').hasClass('font-green-seagreen')) {
							$('.salvar-restante-crediario').addClass('ocultar');
						} else {
							$('.salvar-restante-crediario').removeClass('ocultar');
						}


						resultado = total_troco.toString();
						resultado = 'R$ ' + resultado.replace('.', ',');
						$("#troco").text(resultado);
					} else {
						alert('Não foi possível encontrar o produto nesta tabela!');
						return false;
					}
				}
			});
		});
	};

	$(document).on('keyup', '.valor_venda_troca_produto_avulso2', function () {

		var produto = $(this).parents("tr");
		var valor = produto.find(".valor_venda_troca_produto_avulso2").val();
		var quant_venda = produto.find(".quant_venda_troca").val();

		var valor_troca = 0;
		valor_troca = $("#valor_produto_troca").val();
		valor_troca = parseFloat(valor_troca);

		v = realParaFloat(valor);
		if (isNaN(v)) {
			v = 0;
		}

		quant_venda = quant_venda.replace(',', '');
		q = parseFloat(quant_venda);
		if (isNaN(q) || q == 0) {
			alert('Cuidado, a quantidade do produto não pode ser zerada.');
			produto.find(".quant_venda").focus().select();
			q = 0;
		}

		var total = (v * q);
		if (((total % 1) != 0) && (!isNaN(total % 1))) {
			total.toString;
			total += '0001';
		}
		total = parseFloat(total);

		let valor_total = total.toFixed(2);
		valor_total = valor_total.toString();
		valor_total = valor_total.replace('.', ',');
		produto.find(".valor_total").text('R$ ' + valor_total);
		produto.find(".total").val(total);

		var soma = 0;
		$('.total').each(function (indice, item) {
			var i = $(item).val();
			var v = parseFloat(i);
			if (!isNaN(v)) {
				soma += v;
			}
		});
		soma = soma.toFixed(2);

		$("#valor").val(soma);
		var resultado = soma.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#valor2").text(resultado);

		var desconto = $("#valor_desconto_troca").val() ? $("#valor_desconto_troca").val() : '0';
		var d = desconto.replace('.', '');
		d = d.replace(',', '.');
		d = d.replace('R$ ', '');
		d = parseFloat(d);
		if (isNaN(d)) {
			d = 0;
		}

		if (d > 0) {
			$("#valor_desconto_troca").trigger({ type: 'keyup', which: 13, keyCode: 13 });
		}

		var valor_pagar = soma - d - valor_troca;
		valor_pagar = valor_pagar.toFixed(2);
		valor_pagar = valor_pagar.toString();
		valor_pagar = valor_pagar.replace('.', ',');
		$('#valor_pagar').text('R$ ' + valor_pagar);

		var valor = $("#valor").val();
		var v = parseFloat(valor);
		if (isNaN(v)) {
			v = 0;
		}

		var soma = 0;
		$('.valor_pago').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma += p;
			}
		});
		var soma_dinheiro = 0;
		$('.dinheiro').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma_dinheiro += p;
			}
		});

		var valor_pagar = v - d - soma - valor_troca;

		if (valor_pagar < 0) {
			$(".valor_pagar_titulo").text('Valor a utilizar');
			$(".valor_pagar_texto").removeClass("font-green-seagreen");
			$(".valor_pagar_texto").addClass("font-red");
			valor_pagar *= -1;
		} else {
			$(".valor_pagar_titulo").text('Valor a pagar');
			$(".valor_pagar_texto").removeClass("font-red");
			$(".valor_pagar_texto").addClass("font-green-seagreen");
		}

		var resultado = valor_pagar.toFixed(2);
		resultado = resultado.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#valor_pagar").text(resultado);

		//Verifica no menu Vendas > Caixa > Troca de produto Fiscal se existe valor a utilizar para salvar no crediario;
		if ($('.valor_pagar_texto').hasClass('font-green-seagreen')) {
			$('.salvar-restante-crediario').addClass('ocultar');
		} else {
			$('.salvar-restante-crediario').removeClass('ocultar');
		}

		troco = (soma + valor_troca) - (v - d);

		total_troco = 0;

		if (troco < 0)
			total_troco = 0;
		else
			if (troco >= soma_dinheiro)
				total_troco = soma_dinheiro;
			else
				total_troco = troco;

		total_troco = total_troco.toFixed(2);

		if (total_troco > 0) {
			valor_a_pagar = valor_pagar.toFixed(2);

			valor_a_pagar = valor_a_pagar - total_troco;
			valor_a_pagar = valor_a_pagar.toFixed(2);
			resultado = valor_a_pagar.toString();
			resultado = 'R$ ' + resultado.replace('.', ',');
			$("#valor_pagar").text(resultado);
		}

		resultado = total_troco.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#troco").text(resultado);
	});

	// CALCULAR Valor Total Produto na tela de TROCA DE PRODUTO
	$(document).on('keyup', '.quant_venda_troca', function () {
		var produto = $(this).parents("tr");
		// var valor = produto.find(".valor").val() ;
		var valor = produto.find('#valor_venda_troca').val();
		var quant_venda_troca = produto.find(".quant_venda_troca").val();

		var v = valor.replace('.', ',');
		v = v.replace(',', '.');
		v = v.replace('R$', '');
		v = parseFloat(v);
		if (isNaN(v)) {
			v = 0;
		}

		valor_troca = $("#valor_produto_troca").val();
		valor_troca = parseFloat(valor_troca);

		quant_venda_troca = quant_venda_troca.replace(',', '');
		q = parseFloat(quant_venda_troca);
		if (isNaN(q)) {
			q = 1;
		}

		var total = (v * q);

		valor_total = total.toFixed(2);
		valor_total = valor_total.toString();
		valor_total = valor_total.replace('.', ',');

		produto.find(".valor_total").text('R$ ' + valor_total);
		produto.find(".total").val(total);

		var soma = 0;
		$('.total').each(function (indice, item) {
			var i = $(item).val();
			var v = parseFloat(i);
			if (!isNaN(v)) {
				soma += v;
			}
		});
		soma = soma.toFixed(2);

		$("#valor").val(soma);
		var resultado = soma.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#valor2").text(resultado);
		// $("#valor_pago").val(resultado); //Valor a pagar em troca de produto

		var desconto = $("#valor_desconto_troca").val();
		var d = desconto.replace('.', '');
		d = d.replace(',', '.');
		d = d.replace('R$ ', '');
		d = parseFloat(d);
		if (isNaN(d)) {
			d = 0;
		}

		var valor = $("#valor").val();
		var v = parseFloat(valor);
		if (isNaN(v)) {
			v = 0;
		}

		if (d > 0) {
			$("#valor_desconto_troca").trigger({ type: 'keyup', which: 13, keyCode: 13 });
		}

		var soma = 0;
		$('.valor_pago').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma += p;
			}
		});
		var soma_dinheiro = 0;
		$('.dinheiro').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma_dinheiro += p;
			}
		});

		var valor_pagar = v - d - soma - valor_troca;
		if (valor_pagar < 0) {
			$(".valor_pagar_titulo").text('Valor a utilizar');
			$(".valor_pagar_texto").removeClass("font-green-seagreen");
			$(".valor_pagar_texto").addClass("font-red");
			valor_pagar *= -1;
		} else {
			$(".valor_pagar_titulo").text('Valor a pagar');
			$(".valor_pagar_texto").removeClass("font-red");
			$(".valor_pagar_texto").addClass("font-green-seagreen");
		}

		var resultado = valor_pagar.toFixed(2);
		resultado = resultado.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#valor_pagar").text(resultado);

		if ($('.valor_pagar_texto').hasClass('font-green-seagreen')) {
			$('.salvar-restante-crediario').addClass('ocultar');
		} else {
			$('.salvar-restante-crediario').removeClass('ocultar');
		}

		troco = (soma + valor_troca) - (v - d);
		total_troco = 0;
		if (troco < 0)
			total_troco = 0;
		else
			if (troco >= soma_dinheiro)
				total_troco = soma_dinheiro;
			else
				total_troco = troco;

		total_troco = total_troco.toFixed(2);
		if (total_troco > 0) {
			valor_a_pagar = valor_pagar.toFixed(2);

			valor_a_pagar = valor_a_pagar - total_troco;
			valor_a_pagar = valor_a_pagar.toFixed(2);
			resultado = valor_a_pagar.toString();
			resultado = 'R$ ' + resultado.replace('.', ',');
			$("#valor_pagar").text(resultado);
		}

		resultado = total_troco.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#troco").text(resultado);
	});

	if ($('#id_produto_troca').length > 0) {
		$("#id_produto_troca").select2({
			ajax: {
				url: 'webservices/sistema/buscarProdutos.php',
				dataType: 'json',
				delay: 250,
				data: function (params) {
					let query = {
						nome: params.term
					}
					return query;
				},
				processResults: function (data) {
					var tmpResults = [];
					var id_tabela = $("#id_tabela_venda").val();

					$.each(data, function (index, item) {
						if (id_tabela == item.id_tabela) {
							tmpResults.push({
								'id': item.id,
								'text': item.nome,
								'valor': item.valor_venda,
								'codigo': item.codigo,
								'valor_avista': item.valor_avista
							});
						}

					});
					return {
						results: tmpResults
					};

				}
			}
		});
	};

	// TROCA DE PRODUTO - Remover produto na TROCA DE PRODUTO
	$(document).on('click', 'a.remover_produto_troca', function () {
		var item = $(this).parents("tr");
		item.remove();

		valor_troca = $("#valor_produto_troca").val();
		valor_troca = parseFloat(valor_troca);

		var soma = 0;
		$('.total').each(function (indice, item) {
			var i = $(item).val();
			var v = parseFloat(i);
			if (!isNaN(v)) {
				soma += v;
			}
		});

		if (soma <= 0) {
			$('.salvar-restante-crediario').addClass('ocultar');
		}

		soma = soma.toFixed(2);
		$("#valor").val(soma);
		var resultado = soma.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#valor2").text(resultado);
		$("#valor_pago").val(resultado); //Valor a pagar em troca de produto

		var desconto = $("#valor_desconto_troca").val();
		var d = desconto.replace('.', '');
		d = d.replace(',', '.');
		d = d.replace('R$ ', '');
		d = parseFloat(d);
		if (isNaN(d)) {
			d = 0;
		}

		if (d > 0) {
			$("#valor_desconto_troca").trigger({ type: 'keyup', which: 13, keyCode: 13 });
		}

		var valor = $("#valor").val();
		var v = parseFloat(valor);
		if (isNaN(v)) {
			v = 0;
		}
		var desconto = $("#valor_desconto_troca").val();
		var d = desconto.replace('.', '');
		d = d.replace(',', '.');
		d = d.replace('R$ ', '');
		d = parseFloat(d);
		if (isNaN(d)) {
			d = 0;
		}
		var soma = 0;
		$('.valor_pago').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma += p;
			}
		});

		var soma_dinheiro = 0;
		$('.dinheiro').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma_dinheiro += p;
			}
		});

		var valor_pagar = v - d - soma - valor_troca;
		if (valor_pagar < 0) {
			$(".valor_pagar_titulo").text('Valor a utilizar');
			$(".valor_pagar_texto").removeClass("font-green-seagreen");
			$(".valor_pagar_texto").addClass("font-red");
			valor_pagar *= -1;
		} else {
			$(".valor_pagar_titulo").text('Valor a pagar');
			$(".valor_pagar_texto").removeClass("font-red");
			$(".valor_pagar_texto").addClass("font-green-seagreen");
		}

		var resultado = valor_pagar.toFixed(2);
		resultado = resultado.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#valor_pagar").text(resultado);

		if ($('.valor_pagar_texto').hasClass('font-green-seagreen')) {
			$('.salvar-restante-crediario').addClass('ocultar');
		} else {
			$('.salvar-restante-crediario').removeClass('ocultar');
		}

		troco = (soma + valor_troca) - (v - d);
		total_troco = 0;
		if (troco < 0)
			total_troco = 0;
		else
			if (troco >= soma_dinheiro)
				total_troco = soma_dinheiro;
			else
				total_troco = troco;

		total_troco = total_troco.toFixed(2);

		if (total_troco > 0) {
			valor_a_pagar = valor_pagar.toFixed(2);

			valor_a_pagar = valor_a_pagar - total_troco;
			valor_a_pagar = valor_a_pagar.toFixed(2);
			resultado = valor_a_pagar.toString();
			resultado = 'R$ ' + resultado.replace('.', ',');
			$("#valor_pagar").text(resultado);
		}

		resultado = total_troco.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#troco").text(resultado);

	});

	// TROCA DE PRODUTO - Remover pagamento na TROCA DE PRODUTO
	$(document).on('click', 'a.remover_pagamento_troca', function () {
		var item = $(this).parents("tr");
		item.remove();

		valor_troca = $("#valor_produto_troca").val();
		valor_troca = parseFloat(valor_troca);

		var valor = $("#valor").val();
		var v = parseFloat(valor);
		if (isNaN(v)) {
			v = 0;
		}
		var desconto = $("#valor_desconto_troca").val();
		var d = desconto.replace('.', '');
		d = d.replace(',', '.');
		d = d.replace('R$ ', '');
		d = parseFloat(d);
		if (isNaN(d)) {
			d = 0;
		}
		var soma = 0;
		$('.valor_pago').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma += p;
			}
		});
		var soma_dinheiro = 0;
		$('.dinheiro').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma_dinheiro += p;
			}
		});

		var valor_pagar = v - d - soma - valor_troca;
		if (valor_pagar < 0) {
			$(".valor_pagar_titulo").text('Valor a utilizar');
			$(".valor_pagar_texto").removeClass("font-green-seagreen");
			$(".valor_pagar_texto").addClass("font-red");
			valor_pagar *= -1;
		} else {
			$(".valor_pagar_titulo").text('Valor a pagar');
			$(".valor_pagar_texto").removeClass("font-red");
			$(".valor_pagar_texto").addClass("font-green-seagreen");
		}

		var resultado = valor_pagar.toFixed(2);
		resultado = resultado.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#valor_pagar").text(resultado);
		$("#valor_pago").val(resultado); //Valor a pagar em troca de produto

		troco = (soma + valor_troca) - (v - d);
		total_troco = 0;
		if (troco < 0)
			total_troco = 0;
		else
			if (troco >= soma_dinheiro)
				total_troco = soma_dinheiro;
			else
				total_troco = troco;

		total_troco = total_troco.toFixed(2);
		if (total_troco > 0) {
			valor_a_pagar = valor_pagar.toFixed(2);

			valor_a_pagar = valor_a_pagar - total_troco;
			valor_a_pagar = valor_a_pagar.toFixed(2);
			resultado = valor_a_pagar.toString();
			resultado = 'R$ ' + resultado.replace('.', ',');
			$("#valor_pagar").text(resultado);
		}

		resultado = total_troco.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#troco").text(resultado);

		// let valor_a_pagar = $('.valor_pagar_texto').text();
		// valor_a_pagar = valor_a_pagar.replace('R$', '');
		// valor_a_pagar = valor_a_pagar.replace('.', '');
		// valor_a_pagar = valor_a_pagar.replace(',', '.');
		// valor_a_pagar = parseFloat(valor_a_pagar);

		// if(valor_a_pagar > 0) {
		// 	$('.salvar-restante-crediario').removeClass('ocultar');
		// }


	});

	$("#valor_desconto_porcentagem").keyup(function (e) {

		if (e.keyCode == 13 || e.which == 13) {

			//Pega o valor do desconto em porcentagem e o valor total, depois faz o cálculo da porcentagem
			//e atribui ao valor do desconto rapida o calulo_porcent

			let desc_porcent = $("#valor_desconto_porcentagem").val();
			desc_porcent = desc_porcent.replace('%', '');
			desc_porcent = desc_porcent.replace('.', '');
			desc_porcent = parseFloat(desc_porcent.replace(',', '.'));

			let val_total = $("#valor2").html();

			val_total = val_total.replace('.', '');
			val_total = val_total.replace(',', '.');
			val_total = parseFloat(val_total.replace('R$ ', ''));
			if (isNaN(val_total)) {
				val_total = 0;
			}

			let calculo_porcent = ((desc_porcent / 100) * val_total);
			calculo_porcent = calculo_porcent.toFixed(2);
			calculo_porcent = 'R$ ' + calculo_porcent.toString();
			calculo_porcent = calculo_porcent.replace('.', ',');
			if (isNaN(calculo_porcent)) {
				calculo_porcent = 0;
			}

			//desconto em percentual
			$("#valor_desconto_rapida").val(calculo_porcent);
			$('#valor_desconto_modal').val(calculo_porcent);

			//Atualiza o valor a pagar

			let valor_desconto = $("#valor_desconto_rapida").val();
			valor_desconto = valor_desconto.replace('R$ ', '');
			valor_desconto = parseFloat(valor_desconto.replace(',', '.'));

			var acrescimo = $("#valor_acrescimo_rapida").val();
			var acrescimo = acrescimo.replace('.', '');
			acrescimo = acrescimo.replace(',', '.');
			acrescimo = acrescimo.replace('R$ ', '');
			acrescimo = parseFloat(acrescimo);
			if (isNaN(acrescimo)) {
				acrescimo = 0;
			}

			var soma = 0;

			$('.valor_pago').each(function (indice, item) {
				var i = $(item).val();
				var p = parseFloat(i);
				if (!isNaN(p)) {
					soma += p;
				}

			});

			let val_pagar = (val_total + acrescimo - valor_desconto - soma);

			if (val_pagar < 0) {
				val_pagar = 0;
				$("#valor_desconto_rapida").val(0.0);
				$("#valor_desconto_porcentagem").val(0.0);
				alert("O valor a pagar não pode ser negativo");
			}

			val_pagar = val_pagar.toFixed(2);
			val_pagar = val_pagar.replace('.', ',');
			val_pagar = 'R$ ' + val_pagar.toString();

			$("#valor_pagar").html(val_pagar);
		}
	});

	$("#novo_desconto").on('keyup', function () {
		//Keyup do valor do desconto atualizando o desconto a pagar em porcentagem

		let novo_desconto = $("#novo_desconto").val();
		novo_desconto = novo_desconto.replace('.', '');
		novo_desconto = novo_desconto.replace(',', '.');
		novo_desconto = parseFloat(novo_desconto.replace('R$ ', ''));

		let valor_total = $("#valtotal_finalizavenda").html();
		valor_total = valor_total.replace('R$ ', '');
		valor_total = valor_total.replace('.', '');
		valor_total = parseFloat(valor_total.replace(',', '.'));

		let calc_porcent = ((novo_desconto * 100) / valor_total);
		calc_porcent = calc_porcent.toFixed(2);
		//calc_porcent = '% ' + calc_porcent.toString();

		if (novo_desconto > valor_total) {
			alert("O valor do novo desconto não pode ser maior que o valor total");
			$("#desc_porcentagem").val('');
		}

		$("#desc_porcentagem").val(calc_porcent);

	});

	$("#desc_porcentagem").on('keyup', function () {
		//Keyup atualizando o novo desconto em reais

		let descporcent = $("#desc_porcentagem").val();
		descporcent = descporcent.replace('%', '');
		descporcent = descporcent.replace('.', '');
		descporcent = parseFloat(descporcent.replace(',', '.'));

		let valor_total = $("#valtotal_finalizavenda").html();
		valor_total = valor_total.replace('R$ ', '');
		valor_total = valor_total.replace('.', '');
		valor_total = parseFloat(valor_total.replace(',', '.'));

		let calculo_porcent = ((descporcent / 100) * valor_total);
		calculo_porcent = Math.round(calculo_porcent * 100) / 100;
		calculo_porcent = calculo_porcent.toFixed(2);
		calculo_porcent = 'R$ ' + calculo_porcent.toString();
		calculo_porcent = calculo_porcent.replace('.', ',');

		if (((descporcent / 100) * valor_total) > valor_total) {
			alert("O valor de desconto em porcentagem não pode ser maior que o valor total");
		}

		$("#novo_desconto").val(calculo_porcent);
	})

	$("#val_desc").on('keyup', function () {
		//Adicionar produto
		//Keyup do valor do desconto atualizando o desconto a pagar em porcentagem

		let val_desc = $("#val_desc").val();
		val_desc = val_desc.replace('.', '');
		val_desc = val_desc.replace(',', '.');
		val_desc = parseFloat(val_desc.replace('R$ ', ''));

		let valor_total = $("#valtotal_finalizavenda").html();
		valor_total = valor_total.replace('R$ ', '');
		valor_total = valor_total.replace('.', '');
		valor_total = parseFloat(valor_total.replace(',', '.'));

		let calc_porcent = ((val_desc * 100) / valor_total);
		calc_porcent = calc_porcent.toFixed(2);
		//calc_porcent = '% ' + calc_porcent.toString();

		if (val_desc > valor_total) {
			alert("O valor do desconto não pode ser maior do que o valor total");
			$("#desc_porcentagem").val('');
		}

		$("#valor_desconto_porcent").val(calc_porcent);


	})

	$("#valordesconto_porcent").on('keyup', function () {
		//Adicionar produto
		//Keyup atualizando o novo desconto em reais

		let valordesconto_porcent = $("#valordesconto_porcent").val();
		valordesconto_porcent = valordesconto_porcent.replace('%', '');
		valordesconto_porcent = valordesconto_porcent.replace('.', '');
		valordesconto_porcent = parseFloat(valordesconto_porcent.replace(',', '.'));

		let valor_total = $("#valtotal_finalizavenda").html();
		valor_total = valor_total.replace('R$ ', '');
		valor_total = valor_total.replace('.', '');
		valor_total = parseFloat(valor_total.replace(',', '.'));

		let calculo_porcent = ((valordesconto_porcent / 100) * valor_total);
		calculo_porcent = calculo_porcent.toFixed(2);
		calculo_porcent = 'R$ ' + calculo_porcent.toString();
		calculo_porcent = calculo_porcent.replace('.', ',');

		if (((valordesconto_porcent / 100) * valor_total) > valor_total) {
			alert("O valor de desconto em porcentagem não pode ser maior que o valor total");
		}

		$("#val_desc").val(calculo_porcent);
	});


	function atualizaValoresPDVQuandoRemoveProduto() {
		var soma = 0;
		$('.total').each(function (indice, item) {
			var i = $(item).val();
			var v = parseFloat(i);
			vlr = v.toFixed(2);
			if (!isNaN(v)) {
				soma += parseFloat(vlr);
			}
		});
		soma = soma.toFixed(2);
		$("#valor").val(soma);
		var resultado = soma.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#valor2").text(resultado);

		var desconto = $("#valor_desconto_modal").val();
		var d = desconto.replace('.', '');
		d = d.replace(',', '.');
		d = d.replace('R$ ', '');
		d = parseFloat(d);
		if (isNaN(d)) {
			d = 0;
		}

		var acrescimo = $("#valor_acrescimo_modal").val();
		var a = acrescimo.replace('.', '');
		a = a.replace(',', '.');
		a = a.replace('R$ ', '');
		a = parseFloat(a);
		if (isNaN(a)) {
			a = 0;
		}

		var valor_pagar = soma + a - d;

		if (d > 0) {
			$("#valor_desconto_modal").trigger({ type: 'keyup', which: 13, keyCode: 13 });
		}

		valor_pagar = valor_pagar.toFixed(2);
		valor_pagar = valor_pagar.toString();
		valor_pagar = valor_pagar.replace('.', ',');
		$('#valor_pagar').text('R$ ' + valor_pagar);

		var valor = $("#valor").val();
		var v = parseFloat(valor);
		if (isNaN(v)) {
			v = 0;
		}

		var desconto = $("#valor_desconto_modal").val();
		var d = desconto.replace('.', '');
		d = d.replace(',', '.');
		d = d.replace('R$ ', '');
		d = parseFloat(d);
		if (isNaN(d)) {
			d = 0;
		}
		var soma = 0;
		$('.valor_pago').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma += p;
			}
		});
		var soma_dinheiro = 0;
		$('.dinheiro').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma_dinheiro += p;
			}
		});

		var valor_pagar = v + a - d - soma;
		if (valor_pagar < 0) {
			valor_pagar = 0;
		}
		var resultado = valor_pagar.toFixed(2);
		resultado = resultado.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#valor_pagar").text(resultado);

		$('.valor_pago_venda').val(resultado); //Quando remove o produto da venda, o valor altera.
		$('#valor_pago_modal').val(resultado);
		$('#valor_pagar_modal_pgto').val(resultado);

		var soma_restante = soma - soma_dinheiro;
		var total_pagar_dinheiro = v + a - d - soma_restante;
		var troco = soma_dinheiro - total_pagar_dinheiro;

		if (troco < 0) {
			troco = 0;
		}

		resultado = troco.toFixed(2);
		resultado = resultado.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#troco").text(resultado);
	}


	// VENDAS/PEDIDO - Remover produto na venda rapida
	$(document).on('click', 'a.remover_produto_venda', function () {

		var item = $(this).parents("tr");

		//Remove produto se o PIN estiver correto
		if ($('#modal_cancelar_produto_venda').val() == 1) {
			$('#modal_cancelamento_produto').modal('show');
			$('#pinUserCancel').val('');

			$('.btn-form-cancelamento').click(function () {
				const pinUser = $('#pinUserCancel').val();
				$.ajax({
					method: 'POST',
					url: 'controller.php',
					data: 'verificarPinProdutoVenda=1&pin=' + pinUser,
					success: function (data) {
						let res = parseFloat(data.trim());
						if (res === 1) {
							$('.info-pin-incorreto').addClass('hidden');
							$('#modal_cancelamento_produto').modal('hide');
							item.remove();
							atualizaValoresPDVQuandoRemoveProduto();
						} else {
							$('.info-pin-incorreto').removeClass('hidden');
							$('#pinUserCancel').val('');
							return false;
						}
					}
				});
			});

			$(document).on('keyup', '#pinUserCancel', function (event) {
				if (event.which == 13 || event.keyCode == 13) {
					const pinUser = $('#pinUserCancel').val();
					$.ajax({
						method: 'POST',
						url: 'controller.php',
						data: 'verificarPinProdutoVenda=1&pin=' + pinUser,
						success: function (data) {
							let res = parseFloat(data.trim());
							if (res === 1) {
								$('.info-pin-incorreto').addClass('hidden');
								$('#modal_cancelamento_produto').modal('hide');
								item.remove();
								atualizaValoresPDVQuandoRemoveProduto();
							} else {
								$('.info-pin-incorreto').removeClass('hidden');
								$('#pinUserCancel').val('');
								return false;
							}
						}
					});
				}
			});

		} else {
			item.remove();
			atualizaValoresPDVQuandoRemoveProduto();
		}


	});

	// VENDAS/PEDIDO - Remover pagamento na venda rapida
	$(document).on('click', 'a.remover_pagamento', function () {
		var item = $(this).parents("tr");
		item.remove();

		var pagamentosAVista = 1;
		var existePagamento = 0;
		$('.pagamento_avista').each(function (indice, item) {
			existePagamento = 1
			var i = $(item).val();
			var p = parseInt(i);
			if (!isNaN(p)) {
				pagamentosAVista *= p;
			} else {
				pagamentosAVista = 0;
			}
		});

		if (pagamentosAVista == 1 && existePagamento == 1) {
			/* Atualizar os valores do PDV sobre os valores à vista */
			atualizarValoresPDV(1);
		} else {
			/* Atualizar os valores do PDV sobre os valores normais */
			atualizarValoresPDV(0);
		}

		var soma = 0;
		$('.valor_pago').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma += p;
			}
		});
		var valor_pago_pdv = soma.toFixed(2);
		valor_pago_pdv = 'R$ ' + valor_pago_pdv.toString().replace('.', ',');
		$('#valor_pago_pdv').text(valor_pago_pdv);

		var valor = $("#valor").val();
		var v = parseFloat(valor);
		if (isNaN(v)) {
			v = 0;
		}
		// var desconto = $( "#valor_desconto_rapida" ).val();
		var desconto = $("#valor_desconto_modal").val();
		var d = desconto.replace('.', '');
		d = d.replace(',', '.');
		d = d.replace('R$ ', '');
		d = parseFloat(d);
		if (isNaN(d) || existePagamento == 0) {
			d = 0;
			$("#valor_desconto_modal").val(0);
			$("#valor_desconto_rapida").val(0);
		}
		var valor_desconto = d.toFixed(2);
		valor_desconto = 'R$ ' + valor_desconto.toString().replace('.', ',');
		$('#valor_desconto_pdv').text(valor_desconto);

		// var acrescimo = $("#valor_acrescimo_rapida").val();
		var acrescimo = $("#valor_acrescimo_modal").val();
		var a = acrescimo.replace('.', '');
		a = a.replace(',', '.');
		a = a.replace('R$ ', '');
		a = parseFloat(a);
		if (isNaN(a) || existePagamento == 0) {
			a = 0;
		}
		var valor_acrescimo = a.toFixed(2);
		valor_acrescimo = 'R$ ' + valor_acrescimo.toString().replace('.', ',');
		$('#valor_acrescimo_pdv').text(valor_acrescimo);

		var soma = 0;
		$('.valor_pago').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma += p;
			}
		});
		var soma_dinheiro = 0;
		$('.dinheiro').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma_dinheiro += p;
			}
		});

		var valor_pagar = v + a - d - soma;
		if (valor_pagar < 0) {
			valor_pagar = 0;
		}
		var resultado = valor_pagar.toFixed(2);
		resultado = resultado.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#valor_pagar").text(resultado);

		$('.valor_pago_venda').val(resultado); //Quando remove o produto da venda, o valor altera.
		$('#valor_pago_modal').val(resultado);
		$('#valor_pagar_modal_pgto').val(resultado);

		var soma_restante = soma - soma_dinheiro;
		var total_pagar_dinheiro = v + a - d - soma_restante;
		if (total_pagar_dinheiro < 0) {
			total_pagar_dinheiro = 0;
		}
		var troco = soma_dinheiro - total_pagar_dinheiro;
		if (troco < 0) {
			troco = 0;
		}
		resultado = troco.toFixed(2);
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#troco").text(resultado);

		if (troco > soma_dinheiro) {
			resultado = soma_dinheiro.toFixed(2);
			resultado = 'R$ ' + resultado.replace('.', ',');
			$("#troco").text(resultado);
			alert('O valor do TROCO deve ser MENOR que o total pago em DINHEIRO.');
			return false;
		}

	});

	//CONSULTA EXTERNA -  Buscar valor do produto
	$('#id_ref_produto').change(function () {
		var id = $("#id_ref_produto").val();
		if (id == '')
			return false;
		var id_tabela = $("#id_ref_tabela").val();

		jQuery.ajax({
			type: 'post',
			url: 'webservices/buscaproduto.php',
			data: 'id=' + id + '&id_tabela=' + id_tabela,
			success: function (data) {
				var temp = data.split("#");
				if (temp.length >= 2) {
					valor = parseFloat(temp[0]).toFixed(2);
					estoque = temp[1].replace('.', ',');
					$("#valor").val(temp[0]);
					$("#valor_venda_produto").val('R$ ' + valor.replace('.', ','));
					$("#estoque").val(estoque);
					var quantidade = $("#quantidade_finalizar_venda").val();
					q = parseFloat(quantidade);
					if (isNaN(q)) {
						q = '1,000';
						$("#quantidade_finalizar_venda").val(q);
					}

					var valor_total = valor * q;
					valor_total = valor_total.toFixed(2);
					valor_total = valor_total.toString();
					valor_total = valor_total.replace('.', ',');
					$('#valor_total_produto').val('R$ ' + valor_total);
				} else {
					alert('Não foi possível encontrar o produto nesta tabela!');
				}
			}
		});
	});

	//CONSULTA EXTERNA -  Buscar valor do produto
	$('#id_ref_tabela').change(function () {
		var id = $("#id_ref_produto").val();
		var id_tabela = $("#id_ref_tabela").val();

		if (id == '')
			return false;
		jQuery.ajax({
			type: 'post',
			url: 'webservices/buscaproduto.php',
			data: 'id=' + id + '&id_tabela=' + id_tabela,
			success: function (data) {
				var temp = data.split("#");
				if (temp.length >= 2) {
					valor = 'R$ ' + temp[0].replace('.', ',');
					estoque = temp[1].replace('.', ',');
					$("#valor").val(temp[0]);
					$("#valor_venda").val(valor);
					$("#estoque").val(estoque);
					var quantidade = $("#quantidade").val();
					q = parseFloat(quantidade);
					if (isNaN(q)) {
						q = 1;
					}
					var desconto = $("#valor_desconto").val();
					var d = desconto.replace('.', '');
					d = d.replace(',', '.');
					d = d.replace('R$ ', '');
					d = parseFloat(d);
					if (isNaN(d)) {
						d = 0;
					}
					var valor_total = (temp[0] * q) - d;
					valor_total = valor_total.toFixed(2);
					valor_total = valor_total.toString();
					valor_total = valor_total.replace('.', ',');
					$('#valor_total').val('R$ ' + valor_total);
				} else {
					alert('Não foi possível encontrar o produto!');
				}
			}
		});
	});

	//CONSULTA EXTERNA -  Buscar valor do produto
	$('#id_produto_vendas').change(function () {
		var id = $("#id_produto_vendas").val();
		if (id == '')
			return false;
		var id_tabela = $("#id_tabela_vendas").val();

		jQuery.ajax({
			type: 'post',
			url: 'webservices/buscaproduto.php',
			data: 'id=' + id + '&id_tabela=' + id_tabela,
			success: function (data) {
				var temp = data.split("#");
				if (temp.length >= 2) {
					valor = parseFloat(temp[0]).toFixed(2);
					estoque = temp[1].replace('.', ',');
					$("#valor").val(temp[0]);
					$("#valor_venda_produto").val('R$ ' + valor.replace('.', ','));
					$("#estoque").val(estoque);
					var quantidade = $("#quantidade_finalizar_venda").val();
					q = parseFloat(quantidade);
					if (isNaN(q)) {
						q = '1,000';
						$("#quantidade_finalizar_venda").val(q);
					}

					var valor_total = valor * q;
					valor_total = valor_total.toFixed(2);
					valor_total = valor_total.toString();
					valor_total = valor_total.replace('.', ',');
					$('#valor_total_produto').val('R$ ' + valor_total);
				} else {
					alert('Não foi possível encontrar o produto nesta tabela!');
				}
			}
		});
	});

	//CONSULTA EXTERNA - Finalizar Venda -  Buscar valor do produto, verifica de é avista ou normal e seta valores
	$('#id_produto_vendas_fv').change(function () {
		var id = $("#id_produto_vendas_fv").val();
		var aVista = $("#pagamentoAVista").val();

		if (id == '')
			return false;
		var id_tabela = $("#id_tabela_vendas").val();

		jQuery.ajax({
			type: 'post',
			url: 'webservices/buscaproduto.php',
			data: 'id=' + id + '&id_tabela=' + id_tabela,
			success: function (data) {
				var temp = data.split("#");
				if (temp.length >= 2) {
					valorAVista = parseFloat(temp[2]).toFixed(2)
					if (aVista && valorAVista > 0) {
						valor = valorAVista;
					} else {
						valor = parseFloat(temp[0]).toFixed(2);
					}
					estoque = temp[1].replace('.', ',');
					$("#valor").val(valor);
					$("#valor_venda_produto").val('R$ ' + valor.replace('.', ','));
					$("#estoque").val(estoque);
					var quantidade = $("#quantidade_finalizar_venda").val();
					q = parseFloat(quantidade);
					if (isNaN(q)) {
						q = '1,000';
						$("#quantidade_finalizar_venda").val(q);
					}

					var valor_total = valor * q;
					valor_total = valor_total.toFixed(2);
					valor_total = valor_total.toString();
					valor_total = valor_total.replace('.', ',');
					$('#valor_total_produto').val('R$ ' + valor_total);
				} else {
					alert('Não foi possível encontrar o produto nesta tabela!');
				}
			}
		});
	});

	//CONSULTA EXTERNA -  Buscar valor do produto
	$('#id_tabela_vendas').change(function () {
		var id_tabela = $("#id_tabela_vendas").val();
		$("#id_produto_vendas").empty();

		if (id_tabela == '')
			return false;
		jQuery.ajax({
			type: 'post',
			url: 'webservices/listarProdutosTabela.php',
			data: 'id_tabela=' + id_tabela,
			dataType: 'json',
			success: function (data) {
				$("#id_produto_vendas").append('<option value=""></option>');
				for (let i = 0; i < data.length; i++) {
					$("#id_produto_vendas").append('<option value="' + data[i].id + '">' + data[i].nome + '#' + data[i].codigo + '</option>');
				}

				$("#valor_venda_produto").val('');
				$('#estoque').val('');
				$("#quantidade").val('');
				$("#quantidade_finalizar_venda").val('');
				$("#id_ref_tabela").val(id_tabela);
				$('#valor').val('');
			}
		});
	});

	//CONSULTA EXTERNA -  Buscar quantidade no estoque
	$('#id_produto_estoque').change(function () {
		var id_produto = $("#id_produto_estoque").val();
		jQuery.ajax({
			type: 'post',
			url: 'webservices/buscaestoque.php',
			data: 'id_produto=' + id_produto,
			success: function (data) {
				retorno = data.replace('.', ',');
				$("#estoqueatual").val(retorno);
			}
		});
	});

	//CONSULTA EXTERNA -  Buscar quantidade no estoque
	$('#id_produto_entrada_estoque').change(function () {
		var id_produto = $("#id_produto_entrada_estoque").val();
		jQuery.ajax({
			type: 'post',
			url: 'webservices/buscaprodutoestoque.php',
			data: 'id_produto=' + id_produto,
			success: function (data) {
				var retorno = data.split("#");
				var estoque = retorno[0].replace('.', ',');
				var custo = 'R$ ';
				custo += retorno[1].replace('.', ',');
				$("#estoqueatual").val(estoque);
				$("#custoatual").val(custo);
			}
		});
	});

	//CONSULTA EXTERNA -  Buscar valor do serivço
	$('#id_ref_servico').change(function () {
		var id = $("#id_ref_servico").val();
		if (id == '')
			return false;
		jQuery.ajax({
			type: 'post',
			url: 'webservices/buscaservico.php',
			data: 'id=' + id,
			success: function (data) {
				retorno = 'R$ ' + data.replace('.', ',');
				$("#valor_servico").val(retorno);
			}
		});
	});

	//SET FOCUS NO BUSCAR CEP
	function setFocusCep(id) {
		if ($(id).val() == '')
			$(id).focus()
	}

	//CONSULTA EXTERNA -  Buscar CEP por https
	$('#cepbusca').click(function () {
		if ($.trim($('#cep').val()) != "") {
			cep = $('#cep').val();
			jQuery.ajax({
				url: `https://viacep.com.br/ws/${cep}/json/`,
				method: 'get',
				crossDomain: true,
				success: function (response) {
					if (response != 0) {
						let rua = response['logradouro'].toUpperCase()
						let bairro = response['bairro'].toUpperCase()
						let cidade = response['localidade'].toUpperCase()
						let uf = response['uf'].toUpperCase()

						$('#endereco').val(rua)
						$('#bairro').val(bairro)
						$('#cidade').val(cidade)
						$('#estado').val(uf)

						setFocusCep('#numero')
						setFocusCep('#bairro')
					} else {
						alert('Endereço não encontrado');
						return false;
					}
				},
				error: function (error) {
					alert('CEP invalido')
				}
			})
		}
		else {
			alert('Antes, preencha o campo CEP!')
		}
	});

	//CONSULTA EXTERNA -  Buscar CEP por https no cadastor de contabilidade na empresa.
	$('#cepbuscacontador').click(function () {
		if ($.trim($('#contabilidade_cep').val()) != "") {
			cep = $('#contabilidade_cep').val();
			jQuery.ajax({
				url: `https://viacep.com.br/ws/${cep}/json/`,
				method: 'get',
				crossDomain: true,
				success: function (response) {
					if (response != 0) {
						let rua = response['logradouro'].toUpperCase()
						let bairro = response['bairro'].toUpperCase()
						let cidade = response['localidade'].toUpperCase()
						let uf = response['uf'].toUpperCase()

						$('#contabilidade_endereco').val(rua)
						$('#contabilidade_bairro').val(bairro)
						$('#contabilidade_cidade').val(cidade)
						$('#contabilidade_estado').val(uf)

						setFocusCep('#contabilidade_numero')
						setFocusCep('#contabilidade_endereco')
					} else {
						alert('Endereço não encontrado');
						return false;
					}
				},
				error: function (error) {
					alert('CEP invalido')
				}
			})
		}
		else {
			alert('Antes, preencha o campo CEP da Contabilidade!')
		}
	});

	$('#ceptransportadora').click(function () {
		if ($.trim($('#trans_cep').val()) != "") {
			cep = $('#trans_cep').val();
			jQuery.ajax({
				url: `https://viacep.com.br/ws/${cep}/json/`,
				method: 'get',
				crossDomain: true,
				success: function (response) {
					if (response != 0) {
						let rua = response['logradouro'].toUpperCase()
						let bairro = response['bairro'].toUpperCase()
						let cidade = response['localidade'].toUpperCase()
						let uf = response['uf'].toUpperCase()
						if (rua != '') $('#trans_endereco').val(`${rua}, ${bairro}`)
						else {
							$('#trans_endereco').val(`${rua}`)
							setFocusCep('#trans_endereco')
						}
						$('#trans_cidade').val(cidade)
						$('#trans_uf').val(uf)
					} else {
						alert('Endereço não encontrado')
						return false;
					}
				},
				error: function (error) {
					alert('CEP invalido')
				}
			})
		}
		else {
			alert('Antes, preencha o campo CEP da Transportadora!')
		}
	});

	// COMPRAS - Adicionar produto na nota fiscal
	if ($('a.adicionar_produto').length > 0) {
		$('a.adicionar_produto').click(function () {
			var nome_produto = $("#sel_produto option:selected").text();
			var nome = nome_produto.split(" - ");

			var estoque_produto = $("#sel_produto option:selected").attr('estoque');
			var valida_estoque = $("#sel_produto option:selected").attr('validaestoque');

			const trueDevolucao = document.getElementsByName("sel_produto_devolucao")
			if (trueDevolucao.length != 0) {
				var sel_produto = $("#sel_produto option:selected").attr('id_produto');
				if (sel_produto == "") {
					alert('Favor selecionar um produto.');
					return false;
				}
			} else {
				var sel_produto = $("#sel_produto option:selected").val();
				if (sel_produto == "") {
					alert('Favor selecionar um produto.');
					return false;
				}
			}

			var sel_tipo_item = $("#sel_tipo_item option:selected").val();
			if (sel_tipo_item == "") {
				alert('Favor selecionar um tipo.');
				return false;
			}

			var sel_quant_input = $("input[name='sel_quant']");
			var sel_quant_select = $("select[name='sel_quant']");
			if (sel_quant_input.is(':visible')) {
				var sel_quant = sel_quant_input.val().replace('.', '').replace(',', '.');
				sel_quant = parseFloat(sel_quant);
			} else if (sel_quant_select.is(':visible')) {
				var sel_quant = parseFloat(sel_quant_select.val());
			}
			if (isNaN(sel_quant)) {
				alert('Favor informar a quantidade.');
				return false;
			} else {
				if (valida_estoque == 1 && estoque_produto < sel_quant) {
					alert("Produto com estoque insuficiente para a quantidade informada.");
					return false;
				}
			}

			var sel_valor = $("input[name='sel_valor']").val();
			var v = sel_valor.replace('.', '');
			v = v.replace(',', '.');
			v = parseFloat(v);
			if (isNaN(v)) {
				alert('Favor informar o valor unitario.');
				return false;
			}

			var sel_desconto = $("input[name='sel_desconto']").val() == '' ? "0,000" : $("input[name='sel_desconto']").val();
			var d = sel_desconto.replace('.', '');
			d = d.replace(',', '.');
			d = parseFloat(d);
			if (isNaN(d)) {
				d = 0;
			}

			var sel_despesas = $("input[name='sel_despesas']").val() == '' ? "0,000" : $("input[name='sel_despesas']").val();
			var dp = sel_despesas.replace('.', '');
			dp = dp.replace(',', '.');
			dp = parseFloat(dp);
			if (isNaN(dp)) {
				dp = 0;
			}

			var sel_cfop = $("input[name='sel_cfop']").val();
			var sel_ncm = $("input[name='sel_ncm']").val();
			var sel_cest = $("input[name='sel_cest']").val();
			var sel_item_pedido_compra = $("#sel_item_pedido_compra").val();
			var sel_cst = $("input[name='sel_cst']").val();

			var valor_base_icms = $("#sel_icms_base").val();
			var base_icms = valor_base_icms.replace('.', '');
			base_icms = base_icms.replace(',', '.');
			base_icms = parseFloat(base_icms);
			if (isNaN(base_icms)) {
				base_icms = 0;
			}

			var sel_icms = $("input[name='sel_icms']").val();
			var i = sel_icms.replace('.', '');
			i = i.replace(',', '.');
			i = parseFloat(i);
			if (isNaN(i)) {
				i = 0;
			}

			var icms_st_base = $("#sel_st_base").val();
			var bs = icms_st_base.replace('.', '');
			bs = bs.replace(',', '.');
			bs = parseFloat(bs);
			if (isNaN(bs)) {
				bs = 0;
			}

			var icms_st_percentual = $("input[name='sel_st_percentual']").val();
			var st = icms_st_percentual.replace('.', '');
			st = st.replace(',', '.');
			st = parseFloat(st);
			if (isNaN(st)) {
				st = 0;
			}

			var pis_cst = $("input[name='sel_pis_cst']").val();

			var valor_base_pis = $("#sel_pis_base").val();
			var base_pis = valor_base_pis.replace('.', '');
			base_pis = base_pis.replace(',', '.');
			base_pis = parseFloat(base_pis);
			if (isNaN(base_pis)) {
				base_pis = 0;
			}

			var pis_percentual = $("input[name='sel_pis']").val();
			var pis_aliquota = pis_percentual.replace('.', '');
			pis_aliquota = pis_aliquota.replace(',', '.');
			pis_aliquota = parseFloat(pis_aliquota);
			if (isNaN(pis_aliquota)) {
				pis_aliquota = 0;
			}

			var cofins_cst = $("input[name='sel_cofins_cst']").val();

			var valor_base_cofins = $("#sel_cofins_base").val();
			var base_cofins = valor_base_cofins.replace('.', '');
			base_cofins = base_cofins.replace(',', '.');
			base_cofins = parseFloat(base_cofins);
			if (isNaN(base_cofins)) {
				base_cofins = 0;
			}

			var cofins_percentual = $("input[name='sel_cofins']").val();
			var cofins_aliquota = cofins_percentual.replace('.', '');
			cofins_aliquota = cofins_aliquota.replace(',', '.');
			cofins_aliquota = parseFloat(cofins_aliquota);
			if (isNaN(cofins_aliquota)) {
				cofins_aliquota = 0;
			}

			var ipi_cst = $("input[name='sel_ipi_cst']").val();

			var valor_base_ipi = $("#sel_ipi_base").val();
			var base_ipi = valor_base_ipi.replace('.', '');
			base_ipi = base_ipi.replace(',', '.');
			base_ipi = parseFloat(base_ipi);
			if (isNaN(base_ipi)) {
				base_ipi = 0;
			}

			var ipi_percentual = $("input[name='sel_ipi']").val();
			var ipi_aliquota = ipi_percentual.replace('.', '');
			ipi_aliquota = ipi_aliquota.replace(',', '.');
			ipi_aliquota = parseFloat(ipi_aliquota);
			if (isNaN(ipi_aliquota)) {
				ipi_aliquota = 0;
			}

			var mva_percentual = $("input[name='sel_mva']").val();
			var mva_aliquota = mva_percentual.replace('.', '');
			mva_aliquota = mva_aliquota.replace(',', '.');
			mva_aliquota = parseFloat(mva_aliquota);
			if (isNaN(mva_aliquota)) {
				mva_aliquota = 0;
			}

			var valor_total = (v * sel_quant) + dp - d;
			var valor_icms = (base_icms / 100) * i;
			var valor_mva = (bs / 100) * st;
			var valor_st = (mva_aliquota == 0) ? 0 : valor_mva - valor_icms;

			valor_total = valor_total.toFixed(3);
			valor_total = valor_total.toString();
			valor_total = valor_total.replace('.', ',');

			valor_icms = valor_icms.toFixed(3);
			valor_icms = valor_icms.toString();
			valor_icms = valor_icms.replace('.', ',');

			valor_st = valor_st.toFixed(3);
			valor_st = valor_st.toString();
			valor_st = valor_st.replace('.', ',');

			sel_quant = sel_quant.toFixed(3);
			sel_quant = sel_quant.toString();
			sel_quant = sel_quant.replace('.', ',');

			var $el = $("#tabela_produtos"); //tabela de produtos no adicionar nota fiscal
			$el.prepend(`
				<tr>
					<td>${nome[0]}</td>
					<td>${sel_quant}</td>
					<td><span class="bold theme-font valor_total decimalp">${valor_total}</span></td>
					<td><input type="text" class="form-control" name="cfop_produto[]" id="cfop_produto" value="${sel_cfop}" /></td>
					<td><input type="text" class="form-control" name="icms_cst[]" id="icms_cst" type="hidden" value="${sel_cst}" /></td>
					<td><input type="text" class="form-control" name="ncm[]" id="ncm" value="${sel_ncm}" /></td>
					<td><input type="text" class="form-control" name="cest[]" id="cest" value="${sel_cest}" /></td>
					<td>${sel_desconto}</td>
					<td>${sel_despesas}</td>
					<td>${valor_icms}</td>
					<td>${valor_st}</td>
					<td><input type="text" class="form-control" name="item_pedido_compra[]" id="item_pedido_compra" value="${sel_item_pedido_compra}" /></td>
					<td><a href="javascript:void(0);" class="btn btn-xs red remover_produto" title="Deseja remover este produto?"><i class="fa fa-times"></i></a></td>
					<input name="id_produto[]" type="hidden" value="${sel_produto}" />
					<input name="produto_tipo_item[]" type="hidden" value="${sel_tipo_item}" />
					<input name="quantidade_produto[]" type="hidden" value="${sel_quant}" />
					<input name="valor_unitario_produto[]" type="hidden" value="${v}" />
					<input name="valor_desconto_produto[]" type="hidden" value="${d}" />
					<input name="valor_despesas_produto[]" type="hidden" value="${dp}" />
					<input name="valor_base_icms[]" type="hidden" value="${base_icms}" />
					<input name="icms_percentual[]" type="hidden" value="${i}" />
					<input name="icms_st_base[]" type="hidden" value="${bs}" />
					<input name="icms_st_percentual[]" type="hidden" value="${st}" />
					<input name="pis_cst[]" type="hidden" value="${pis_cst}" />
					<input name="valor_base_pis[]" type="hidden" value="${base_pis}" />
					<input name="pis_percentual[]" type="hidden" value="${pis_aliquota}" />
					<input name="cofins_cst[]" type="hidden" value="${cofins_cst}" />
					<input name="valor_base_cofins[]" type="hidden" value="${base_cofins}" />
					<input name="cofins_percentual[]" type="hidden" value="${cofins_aliquota}" />
					<input name="ipi_cst[]" type="hidden" value="${ipi_cst}" />
					<input name="valor_base_ipi[]" type="hidden" value="${base_ipi}" />
					<input name="ipi_percentual[]" type="hidden" value="${ipi_aliquota}" />
					<input name="mva_produto[]" type="hidden" value="${mva_aliquota}"/>
				</tr>
			`).find("tr").first().hide();
			$el.find("tr").first().fadeIn();
			var soma = 0;

			$('.valor_total').each(function (indice, item) {
				var valor = $(item).text();
				valor = valor.replace('.', '');
				valor = valor.replace(',', '.');
				valor = parseFloat(valor);
				if (!isNaN(valor)) {
					soma += valor;
				}
			});

			soma = soma.toFixed(2);
			var resultado = soma.toString();
			resultado = 'R$ ' + resultado.replace('.', ',');
			var $sel_total = $("#sel_total");
			$sel_total.text(resultado);

			$("#sel_valor").val("");
			$("#sel_quant").val("");
			$("#sel_desconto").val("");
			$("#sel_despesas").val("");
			$("#sel_cfop").val("");
			$("#sel_cst").val("");
			$("#sel_ncm").val("");
			$("#sel_cest").val("");
			$("#sel_icms_base").val("");
			$("#sel_icms").val("");
			$("#sel_st_base").val("");
			$("#sel_st_percentual").val("");
			$("#sel_pis_cst").val("");
			$("#sel_pis_base").val("");
			$("#sel_pis").val("");
			$("#sel_cofins_cst").val("");
			$("#sel_cofins_base").val("");
			$("#sel_cofins").val("");
			$("#sel_ipi_cst").val("");
			$("#sel_ipi_base").val("");
			$("#sel_ipi").val("");
			$("#sel_mva").val("");
			$("#sel_item_pedido_compra").val("");
			$("#sel_produto option:first").attr('selected', true);
			$("#sel_produto").select2({
				allowClear: true
			});
			$("#sel_tipo_item option:first").attr('selected', true);
			$("#sel_tipo_item").select2({
				allowClear: true
			});
		});
	};

	//Completar informações fiscais do produto na nota fiscal
	$('.produto_notafiscal').change(function () {
		var id_produto = $(".produto_notafiscal").val();
		jQuery.ajax({
			type: 'post',
			url: 'webservices/buscarinfofiscalproduto.php',
			data: 'id_produto=' + id_produto,
			success: function (data) {
				var retorno = data.split("@");
				$('#sel_cfop').val(retorno[0].trim());
				$('#sel_cst').val(retorno[1].trim());
				$('#sel_ncm').val(retorno[2].trim());
				$('#sel_cest').val(retorno[3].trim());
				$('#sel_icms').val(retorno[4] == 0 ? "" : (parseFloat(retorno[4]).toFixed(2).replace('.', ',').trim()));
				$('#sel_st_percentual').val(retorno[5] == 0 ? "" : (parseFloat(retorno[5]).toFixed(2).replace('.', ',').trim()));
				$('#sel_mva').val(retorno[6] == 0 ? "" : (parseFloat(retorno[6]).toFixed(2).replace('.', ',').trim()));
				$('#sel_pis_cst').val(retorno[7] == 0 ? "" : retorno[7].replace('.', ',').trim());
				$('#sel_pis').val(retorno[8] == 0 ? "" : (parseFloat(retorno[8]).toFixed(2).replace('.', ',').trim()));
				$('#sel_cofins_cst').val(retorno[9] == 0 ? "" : retorno[9].replace('.', ',').trim());
				$('#sel_cofins').val(retorno[10] == 0 ? "" : (parseFloat(retorno[10]).toFixed(2).replace('.', ',').trim()));
				$('#sel_ipi_cst').val(retorno[11] == 0 ? "" : retorno[11].replace('.', ',').trim());
				$('#sel_ipi').val(retorno[12] == 0 ? "" : (parseFloat(retorno[12]).toFixed(2).replace('.', ',').trim()));
			}
		});
	});

	//Completar informações fiscais do produto na nota fiscal de devolução (buscando na nota de compra)
	$('.produto_notafiscal_devolucao').change(function () {
		var id = $(".produto_notafiscal_devolucao").val();
		jQuery.ajax({
			type: 'post',
			url: 'webservices/buscarinfofiscalprodutodevolucao.php',
			data: 'id=' + id,
			success: function (data) {
				var retorno = data.split("@");
				$('#sel_quantidade_devolucao').val(retorno[0].replace('.', ',').trim());
				$('#sel_valor_unitario_devolucao').val(retorno[1].replace('.', ',').trim());
				$('#sel_desconto_devolucao').val(retorno[2].replace('.', ',').trim());
				$('#sel_despesas_devolucao').val(retorno[3].replace('.', ',').trim());
				$('#sel_cfop_devolucao').val(retorno[4].trim());
				$('#sel_cst_devolucao').val(retorno[5].trim());
				$('#sel_ncm_devolucao').val(retorno[6].trim());
				$('#sel_cest_devolucao').val(retorno[7].trim());
				$('#sel_icms_devolucao').val(retorno[8] == 0 ? "" : (parseFloat(retorno[8]).toFixed(2).replace('.', ',').trim()));
				$('#sel_st_percentual_devolucao').val(retorno[9] == 0 ? "" : (parseFloat(retorno[9]).toFixed(2).replace('.', ',').trim()));
				$('#sel_pis_cst_devolucao').val(retorno[10] == 0 ? "" : retorno[10].replace('.', ',').trim());
				$('#sel_pis_devolucao').val(retorno[11] == 0 ? "" : (parseFloat(retorno[11]).toFixed(2).replace('.', ',').trim()));
				$('#sel_cofins_cst_devolucao').val(retorno[12] == 0 ? "" : retorno[12].replace('.', ',').trim());
				$('#sel_cofins_devolucao').val(retorno[13] == 0 ? "" : (parseFloat(retorno[13]).toFixed(2).replace('.', ',').trim()));
				$('#sel_ipi_cst_devolucao').val(retorno[14] == 0 ? "" : retorno[14].replace('.', ',').trim());
				$('#sel_ipi_devolucao').val(retorno[15] == 0 ? "" : (parseFloat(retorno[15]).toFixed(2).replace('.', ',').trim()));
				$('#sel_mva_devolucao').val(retorno[16] == 0 ? "" : (parseFloat(retorno[16]).toFixed(2).replace('.', ',').trim()));
				//$('#sel_cod_anp_devolucao').val(retorno[17] == 0 ? "" : retorno[18].trim());
				//$('#sel_valor_partida_devolucao').val(retorno[19] == 0 ? "" : retorno[19].replace('.', ',').trim());
			}
		});
	});

	$("select[name='sel_produto_devolucao']").change(function () {
		$('#sel_quantidade_devolucao').html('');
		let quantidadeMaxima = $(this).find('option:selected').data('quantidade');

		quantidadeMaxima = quantidadeMaxima.replace('.', '');
		quantidadeMaxima = quantidadeMaxima.replace(',', '.');
		quantidadeMaxima = parseFloat(quantidadeMaxima);

		if (quantidadeMaxima % 1 > 0) {
			let divPai = document.querySelector(".pai_quantidade_devolucao")
			let filhosDiv = document.querySelectorAll(".class_quantidade_devolucao")
			let select = filhosDiv[0]
			let input = filhosDiv[1]

			select.id = ""
			select.style.display = "none"

			input.style.display = "block"
			input.id = "sel_quantidade_devolucao"
			input.textContent = quantidadeMaxima

			document.getElementById("sel_quantidade_devolucao").addEventListener("keyup", () => {
				if (quantidadeMaxima < parseFloat((input.value).replace(',', '.')) || parseFloat((input.value).replace(',', '.')) < 0) {
					input.value = quantidadeMaxima.toString().replace('.', ',');
				}
			})

			divPai.appendChild(input)
		} else {
			let filhosDiv = document.querySelectorAll(".class_quantidade_devolucao")
			let select = filhosDiv[0]
			let input = filhosDiv[1]

			input.style.display = "none"
			input.id = ""

			select.style.display = "block"
			select.id = "sel_quantidade_devolucao"

			for (let i = 1; i <= quantidadeMaxima; i++) {
				i = i.toFixed(3);
				let quantidade = i.replace('.', ',');
				$("#sel_quantidade_devolucao").append('<option value="' + quantidade + '">' + quantidade + '</option>');
			}
		}
	});

	// // SELECT2 EM MODAL
	// if($('#combinar-produto').length > 0) {
	// 	$('#mySelect2').select2({
	// 		dropdownParent: $('#combinar-produto')
	// 	});
	// }

	// COMPRAS - Remover produto na nota fiscal
	$(document).on('click', 'a.remover_produto', function () {
		var produto = $(this).parents("tr");
		var valor_produto = produto.find(".valor_total").text();
		produto.fadeOut(100, function () {
			produto.remove();
		});
		var soma = 0;
		$('.valor_total').each(function (indice, item) {
			var valor = $(item).text();
			valor = valor.replace('.', '');
			valor = valor.replace(',', '.');
			valor = parseFloat(valor);
			if (!isNaN(valor)) {
				soma += valor;
			}
		});
		soma = soma - valor_produto;
		soma = soma.toFixed(2);
		var resultado = soma.toString()
		resultado = 'R$ ' + resultado.replace('.', ',');
		var $sel_total = $("#sel_total");
		$sel_total.text(resultado);
	});

	// VENDAS - Buscar cliente
	if ($('a.buscar_cliente').length > 0) {
		$('a.buscar_cliente').click(function () {
			var nome = $('#nome').val();
			var telefone = $('#telefone').val();
			var endereco = $('#endereco').val();
			window.open('buscar_cliente.php?cliente=' + nome + '&telefone=' + telefone + '&endereco=' + endereco, 'Buscar cliente', 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
		});
	};

	// EXTRATO - Cadastrar despesas
	if ($('a.novadespesa').length > 0) {
		$('a.novadespesa').click(function () {
			window.open('nova_despesa.php?cliente=' + nome + '&telefone=' + telefone + '&endereco=' + endereco, 'Buscar cliente', 'width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');
		});
	};

	// BOX DIALOG - Colocar Duplicata em Despesa
	if ($('a.emdespesa').length > 0) {
		$('a.emdespesa').click(function () {
			var id = $(this).attr('id');
			var aviso = "Você deseja salvar esta DUPLICATA em despesas a pagar?";
			bootbox.dialog({
				message: aviso,
				title: "Salvar duplicata em despesa",
				buttons: {
					salvar: {
						label: "Confirmar DESPESA",
						className: "yellow",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'salvarDuplicata=' + id,
								success: function (data) {
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									setTimeout(function () {
										location.reload();
									}, 1000);
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
	};

	// EXTRATO - Conciliar despesas -> banco
	if ($('a.conciliarbanco').length > 0) {
		$('a.conciliarbanco').click(function () {
			$.bootstrapGrowl("EM PROCESSAMENTO!", {
				ele: "body",
				type: "warning",
				offset: {
					from: "top",
					amount: 50
				},
				align: "center",
				width: "auto",
				delay: 10000,
				stackup_spacing: 10
			});
			var str = $("#admin_form").serialize();
			jQuery.ajax({
				type: 'POST',
				url: 'controller.php',
				data: str,
				success: function (data) {
					var response = data.split("#");
					$.bootstrapGrowl(response[0], {
						ele: "body",
						type: response[1],
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						delay: 10000,
						stackup_spacing: 10
					});
					setTimeout(function () {
						location.reload();
					}, 1000);
				}
			});
			return false;

		});
	};

	// EXTRATO - Cancelar conciliação despesas -> banco
	if ($('a.cancelarconciliacao').length > 0) {
		$('a.cancelarconciliacao').click(function () {
			$.bootstrapGrowl("EM PROCESSAMENTO!", {
				ele: "body",
				type: "warning",
				offset: {
					from: "top",
					amount: 50
				},
				align: "center",
				width: "auto",
				delay: 10000,
				stackup_spacing: 10
			});
			var id = $(this).attr('id');
			jQuery.ajax({
				type: 'POST',
				url: 'controller.php',
				data: 'cancelarConciliacao=1&id=' + id,
				success: function (data) {
					var response = data.split("#");
					$.bootstrapGrowl(response[0], {
						ele: "body",
						type: response[1],
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						delay: 10000,
						stackup_spacing: 10
					});
					setTimeout(function () {
						location.reload();
					}, 1000);
				}
			});
			return false;

		});
	};

	// DESPESAS - salvar despesa
	if ($('#salvardespesa').length > 0) {
		$('#salvardespesa').click(function () {
			var str = $("#admin_form").serialize();
			jQuery.ajax({
				type: 'POST',
				url: 'controller.php',
				data: str,
				success: function (data) {
					var response = data.split("#");
					$.bootstrapGrowl(response[0], {
						ele: "body",
						type: response[1],
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						delay: 10000,
						stackup_spacing: 10
					});
					if (response[2] == "1")
						setTimeout(function () {
							window.location.href = response[3];
						}, 1000);
				}
			});
			return false;

		});
	};

	// DESPESAS - Agrupar despesas
	if ($('a.agrupardespesas').length > 0) {
		$('a.agrupardespesas').click(function () {
			var str = $("#admin_form").serialize();
			jQuery.ajax({
				type: 'POST',
				url: 'controller.php',
				data: str,
				success: function (data) {
					var response = data.split("#");
					$.bootstrapGrowl(response[0], {
						ele: "body",
						type: response[1],
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						delay: 10000,
						stackup_spacing: 10
					});
					if (response[2] == "1")
						setTimeout(function () {
							window.location.href = response[3];
						}, 1000);
				}
			});
			return false;

		});
	};

	// PAGAMENTO - Mostrar campos
	if ($('#tipo_pagamento').length > 0) {
		$('#tipo_pagamento').change(function () {
			var pagamento = $('#tipo_pagamento').val();
			if (pagamento == 1) {
				$('#id_banco').hide();
				$('#total_parcelas').hide();
				$('#numero_cartao').hide();
				$('#data_vencimento').hide();
				$('#nome_cheque').hide();
				$('#banco_cheque').hide();
				$('#numero_cheque').hide();
			} else {
				if (pagamento == 2) {
					$('#id_banco').hide();
					$('#total_parcelas').show();
					$('#numero_cartao').hide();
					$('#data_vencimento').show();
					$('#nome_cheque').show();
					$('#banco_cheque').show();
					$('#numero_cheque').show();
				} else {
					if (pagamento == 3) {
						$('#id_banco').show();
						$('#total_parcelas').hide();
						$('#numero_cartao').hide();
						$('#data_vencimento').hide();
						$('#nome_cheque').hide();
						$('#banco_cheque').hide();
						$('#numero_cheque').hide();
					} else {
						if (pagamento == 4) {
							$('#id_banco').show();
							$('#total_parcelas').show();
							$('#numero_cartao').hide();
							$('#data_vencimento').hide();
							$('#nome_cheque').hide();
							$('#banco_cheque').hide();
							$('#numero_cheque').hide();
						} else {
							$('#id_banco').hide();
							$('#total_parcelas').show();
							$('#numero_cartao').show();
							$('#data_vencimento').hide();
							$('#nome_cheque').hide();
							$('#banco_cheque').hide();
							$('#numero_cheque').hide();
						}
					}
				}
			}
		});
	};

	// PAGAMENTO - Controle de campos para tipo de pagamento no FinalizarVendas em VendasAberto
	if ($('#tipo_pagamento_finalizarvenda').length > 0) {
		$('#tipo_pagamento_finalizarvenda').change(function () {
			var id_categoria = $('#tipo_pagamento_finalizarvenda option:selected').attr('id_categoria');
			var avistaPDV = $('#tipo_pagamento_finalizarvenda option:selected').attr('avista');

			var pagamentosAVistaPDV = 1;
			var usarValorAVista = 0;
			$('.pagamento_avista').each(function (indice, item) {
				var i = $(item).val();
				var p = parseInt(i);
				if (!isNaN(p)) {
					pagamentosAVistaPDV *= p;
				} else {
					pagamentosAVistaPDV = 0;
				}
			});
			usarValorAVista = (pagamentosAVistaPDV == 1 && avistaPDV == 1) ? true : false;

			var valor_pago = 0;
			$('.valor_pagamento').each(function (indice, item) {
				var i = $(item).val();
				var p = parseFloat(i);
				valor_pago += p;
			});

			$('#valor_pago_aberto_dv').show();
			if (id_categoria == 1 || id_categoria == 3 || id_categoria == 5 || id_categoria == 6 || avistaPDV == 1) {
				$('#total_parcelas_aberto').val('1');
				$('#total_parcelas_aberto_dv').hide();
			} else {
				$('#total_parcelas_aberto_dv').show();
			}

			if (id_categoria == 4 || id_categoria == 9) {
				$('#total_parcelas_aberto_dv').show();
				$('#data_parcela_boleto_dv').show();
			} else {
				$('#data_parcela_boleto_dv').hide();
			}

			var valor_acrescimo = $('#acrescimo').text();
			valor_acrescimo = parseFloat(valor_acrescimo.replace('R$ ', '').replace('.', '').replace(',', '.'));
			if (isNaN(valor_acrescimo)) valor_acrescimo = 0;

			var valor_desconto = $('#desconto').text();
			valor_desconto = parseFloat(valor_desconto.replace('R$ ', '').replace('.', '').replace(',', '.'));
			if (isNaN(valor_desconto)) valor_desconto = 0;

			var valorAVista = $('#valor_avista_venda').val();
			var valorAVistaFloat = parseFloat(valorAVista.replace('R$ ', '').replace('.', '').replace(',', '.'));
			valorAVistaFloat -= valor_pago;
			valorAVistaFloat += valor_acrescimo;
			valorAVistaFloat -= valor_desconto;
			valorAVistaFloat = 'R$ ' + valorAVistaFloat.toFixed(2).replace('.', ',');

			var valorNormal = $('#valor_normal_venda').val();
			var valorNormalFloat = parseFloat(valorNormal.replace('R$ ', '').replace('.', '').replace(',', '.'));
			valorNormalFloat -= valor_pago;
			valorNormalFloat += valor_acrescimo;
			valorNormalFloat -= valor_desconto;
			valorNormalFloat = 'R$ ' + valorNormalFloat.toFixed(2).replace('.', ',');

			if (usarValorAVista) {
				$('#valor_pago_aberto').val(valorAVistaFloat);
			} else {
				$('#valor_pago_aberto').val(valorNormalFloat);
			}

		});
	};

	if ($('#valor_pago_aberto').length > 0) {
		$('#valor_pago_aberto').keyup(function () {

			let valor = $(this).val();
			let v = realParaFloat(valor);

			if (v < 0) {
				alert('Valor NÃO PODE ser negativo.');
				v = v * (-1);
				$(this).val(floatParaReal(v));
			}

		});
	}

	if ($('#valor_pago2').length > 0) {
		$('#valor_pago2').keyup(function () {

			let valor = $(this).val();
			let v = realParaFloat(valor);

			if (v < 0) {
				alert('Valor NÃO PODE ser negativo.');
				v = v * (-1);
				$(this).val(floatParaReal(v));
			}

		});
	}

	// SHORTCUT - Atalho para vendas do dia
	shortcut.add("F9", function () {
		window.open("index.php?do=vendas_do_dia");
	});

	// SHORTCUT - Atalho para pesquisar produto
	shortcut.add("F12", function () {
		window.location.href = "index.php?do=vendas&acao=novavenda";
	});

	//RETORNAR PÁGINA
	$('#voltar').click(function () {
		$(this).fadeOut();
		$('#receita_submit').attr('disabled', 'disabled');
		window.history.go(-1);
	});

	//RETORNAR PÁGINA
	$('#fechar').click(function () {
		window.close();
	});

	//MASCARAS TEXT
	var MaskTelefones = function (val) {
		return val.replace(/\D/g, '').length === 11 ? '(00) 0 0000-0000' : '(00) 0000-00009';
	},
		telOptions = {
			onKeyPress: function (val, e, field, options) {
				field.mask(MaskTelefones.apply({}, arguments), options);
			}
		};

	var MaskCPF_CNPJ = function (val) {
		return val.replace(/\D/g, '').length > 11 ? '00.000.000/0000-00' : '000.000.000-009';
	},
		CCOptions = {
			onKeyPress: function (val, e, field, options) {
				field.mask(MaskCPF_CNPJ.apply({}, arguments), options);
			}
		};

	if ($('.placa').length > 0) {
		$('.placa').mask('AAA-0A00');
	}
	if ($('.renavam').length > 0) {
		$('.renavam').mask('000000000000000');
	}
	if ($('.inteiro').length > 0) {
		$('.inteiro').mask('000000');
	}
	if ($('.codigo').length > 0) {
		$('.codigo').mask('000000000000000');
	}
	if ($('.data').length > 0) {
		$('.data').mask('00/00/0000');
	}
	if ($('.hora').length > 0) {
		$('.hora').mask('00:00');
	}
	if ($('.telefone').length > 0) {
		$('.telefone').mask(MaskTelefones, telOptions);
	}
	if ($('.celular').length > 0) {
		$('.celular').mask(MaskTelefones, telOptions);
	}
	if ($('.cep').length > 0) {
		$('.cep').mask('00000-000');
	}
	if ($('.cpf_cnpj').length > 0) {
		$('.cpf_cnpj').mask(MaskCPF_CNPJ, CCOptions);
	}
	if ($('.cpf').length > 0) {
		$('.cpf').mask('000.000.000-00');
	}
	if ($('.cnpj').length > 0) {
		$('.cnpj').mask('00.000.000/0000-00');
	}
	if ($('.numero_cartao').length > 0) {
		$('.numero_cartao').mask('0000****0000');
	}
	if ($('.moeda').length > 0) {
		$('.moeda').maskMoney({ symbol: 'R$ ', thousands: '.', decimal: ',', precision: 2, symbolStay: true, allowNegative: true });
	}
	if ($('.decimal').length > 0) {
		$('.decimal').maskMoney({ thousands: '.', decimal: ',', symbolStay: false, allowNegative: true });
	}
	if ($('.moedap').length > 0) {
		$('.moedap').maskMoney({ symbol: 'R$ ', thousands: '.', decimal: ',', precision: 2, symbolStay: true, allowNegative: true });
	}
	if ($('.casasdecimais').length > 0) {
		$('.casasdecimais').maskMoney({ symbol: 'R$ ', thousands: '.', decimal: ',', precision: 4, symbolStay: true, allowNegative: true });
	}
	if ($('.decimalp').length > 0) {
		$('.decimalp').maskMoney({ thousands: '.', decimal: ',', precision: 3, symbolStay: false, allowNegative: true });
	}
	if ($('.decimalpositivo').length > 0) {
		$('.decimalpositivo').maskMoney({ thousands: '.', decimal: ',', precision: 3, symbolStay: false, allowNegative: false });
	}
	if ($('.desconto').length > 0) {
		$('.desconto').maskMoney({ symbol: '% ', thousands: '.', decimal: ',', precision: 2, symbolStay: true, allowNegative: true });
	}
	if ($('.desconto4').length > 0) {
		$('.desconto4').maskMoney({ symbol: '%', thousands: '.', decimal: ',', precision: 4, symbolStay: true, allowNegative: true });
	}

	if ($('.calendario').length > 0) {
		$('.calendario').datepicker({
			rtl: Metronic.isRTL(),
			orientation: "left",
			language: "pt-BR",
			format: "dd/mm/yyyy",
			todayBtn: "linked",
			autoclose: true
		});
	}

	if ($('.datah').length > 0) {
		$('.datah').mask('99/99/9999 - 99:99');
	}

	if ($('.datahora').length > 0) {
		var dataAtual = new Date();
		$(".datahora").datetimepicker({
			format: "dd/mm/yyyy - hh:ii",
			autoclose: true,
			todayBtn: "linked",
			// startDate: dataAtual,
			pickerPosition: "bottom-left",
			minuteStep: 10
		});
	};

	$('input').bind('change', function () {
		if ($(this).hasClass("caps")) {
			this.value = this.value.toLocaleUpperCase();
		}
	});

	$('textarea').bind('change', function () {
		if ($(this).hasClass("caps")) {
			this.value = this.value.toLocaleUpperCase();
		}
	});

	// Retirar o enter e usar como tab e verificar se tem a classe buscar e submeter o formulario
	$('input, textarea, select, button').on('keypress', function (e) {
		if (e.which == 13) {
			var cl = $(this).hasClass('buscar');
			if (cl) {
				$(this).parents('form:first').submit();
			} else {
				$(this).parent().parent().next().find('input, textarea, select, button').focus();
			}
			return false;
		}
	});

	// BOX DIALOG - PEDIDO - Entregar pedido
	if ($('a.entregapedido').length > 0) {
		$('a.entregapedido').click(function () {
			var id = $(this).attr('id');
			var aviso = "Você deseja confirmar a entrega de todos os itens do pedido?";
			bootbox.dialog({
				message: aviso,
				title: "Confirmar entrega de itens",
				buttons: {
					salvar: {
						label: "Confirmar entrega",
						className: "green",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'processarEntregaPedido=1&id=' + id,
								success: function (data) {
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									setTimeout(function () {
										location.reload();
									}, 1000);
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
	};

	// BOX DIALOG - VENDAS - Agrupar vendas para emissão
	if ($('a.converter_vendas').length > 0) {
		$('a.converter_vendas').click(function () {
			var quantidade = $(this).attr('quantidade');
			var valor = $(this).attr('valor');
			var datai = $(this).attr('datai');
			var dataf = $(this).attr('dataf');
			var cat = $(this).attr('cat'); //1,7,8
			var cli = $(this).attr('cli');
			var id_unico = $(this).attr('id_unico');
			
			var aviso = "Confirmar a conversão/agrupamento das "+quantidade+" vendas ("+valor+") para emissão fiscal?";
			bootbox.dialog({
				message: aviso,
				title: "Confirmar conversão/agrupamento de vendas",
				buttons: {
					salvar: {
						label: "Confirmar conversão/agrupamento",
						className: "purple",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'converter_vendas=1&quantidade='+quantidade+'&valor='+valor+'&datai='+datai+'&dataf='+dataf+'&cat='+cat+'&id_unico='+id_unico+'&cli='+cli,
								success: function (data) {
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									setTimeout(function () {
										location.reload();
									}, 1000);
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
	};

	// BOX DIALOG - PEDIDO ITEM - Entregar item do pedido
	if ($('a.entregapedidoitem').length > 0) {
		$('a.entregapedidoitem').click(function () {
			var id = $(this).attr('id');
			var id_pedido = $(this).attr('id_pedido');
			var aviso = "Você deseja confirmar a entrega do item do pedido?";
			bootbox.dialog({
				message: aviso,
				title: "Confirmar entrega do item",
				buttons: {
					salvar: {
						label: "Confirmar entrega",
						className: "green",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'processarEntregaPedidoItem=1&id=' + id + '&id_pedido=' + id_pedido,
								success: function (data) {
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									setTimeout(function () {
										location.reload();
									}, 1000);
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
	};

	//COTACAO - Bloquear Fornecedor
	if ($('a.bloquearfornecedor').length > 0) {
		$('a.bloquearfornecedor').click(function () {
			$.bootstrapGrowl("EM PROCESSAMENTO!", {
				ele: "body",
				type: "warning",
				offset: {
					from: "top",
					amount: 50
				},
				align: "center",
				width: "auto",
				delay: 10000,
				stackup_spacing: 10
			});
			var cod_fornecedor = $(this).attr('cod_fornecedor');
			var id_cotacao = $(this).attr('id_cotacao');
			jQuery.ajax({
				type: 'POST',
				url: 'controller.php',
				data: 'bloquearFornecedor=1&id_cotacao=' + id_cotacao + '&cod_fornecedor=' + cod_fornecedor,
				success: function (data) {
					var response = data.split("#");
					$.bootstrapGrowl(response[0], {
						ele: "body",
						type: response[1],
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						delay: 10000,
						stackup_spacing: 10
					});
					location.reload();
				}
			});
			return false;

		});
	};

	//COTACAO - Liberar Fornecedor
	if ($('a.liberarfornecedor').length > 0) {
		$('a.liberarfornecedor').click(function () {
			$.bootstrapGrowl("EM PROCESSAMENTO!", {
				ele: "body",
				type: "warning",
				offset: {
					from: "top",
					amount: 50
				},
				align: "center",
				width: "auto",
				delay: 10000,
				stackup_spacing: 10
			});
			var cod_fornecedor = $(this).attr('cod_fornecedor');
			var id_cotacao = $(this).attr('id_cotacao');
			jQuery.ajax({
				type: 'POST',
				url: 'controller.php',
				data: 'liberarFornecedor=1&id_cotacao=' + id_cotacao + '&cod_fornecedor=' + cod_fornecedor,
				success: function (data) {
					var response = data.split("#");
					$.bootstrapGrowl(response[0], {
						ele: "body",
						type: response[1],
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						delay: 10000,
						stackup_spacing: 10
					});
					location.reload();
				}
			});
			return false;

		});
	};

	//COTACAO - Enviar email cotação
	if ($('a.enviaremail').length > 0) {
		$('a.enviaremail').click(function () {
			$.bootstrapGrowl("EM PROCESSAMENTO!", {
				ele: "body",
				type: "warning",
				offset: {
					from: "top",
					amount: 50
				},
				align: "center",
				width: "auto",
				delay: 10000,
				stackup_spacing: 10
			});
			var id = $(this).attr('id');
			var id_cotacao = $(this).attr('id_cotacao');
			var acao = $(this).attr('acao');
			jQuery.ajax({
				type: 'POST',
				url: 'controller.php',
				data: acao + '=' + id_cotacao + '&id=' + id,
				success: function (data) {
					var response = data.split("#");
					$.bootstrapGrowl(response[0], {
						ele: "body",
						type: response[1],
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						delay: 10000,
						stackup_spacing: 10
					});
					if (response[2] == "1")
						setTimeout(function () {
							window.location.href = response[3];
						}, 1000);
				}
			});
			return false;

		});
	};

	//COTACAO - Salvar cotação
	if ($('a.salvarcotacao').length > 0) {
		$('a.salvarcotacao').click(function () {
			var str = $("#admin_form").serialize();
			jQuery.ajax({
				type: 'POST',
				url: 'controller.php',
				data: 'salvarCotacao=1&' + str,
				success: function (data) {
					var response = data.split("#");
					$.bootstrapGrowl(response[0], {
						ele: "body",
						type: response[1],
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						delay: 10000,
						stackup_spacing: 10
					});
					if (response[2] == "1")
						setTimeout(function () {
							window.location.href = response[3];
						}, 1000);
				}
			});
			return false;

		});
	};

	// TABELA DINAMICA
	if ($('.dataTable').length > 0) {
		$('.dataTable').DataTable({
			dom: 'Bfrtip',
			buttons: [
				{ extend: 'copy', footer: true },
				{ extend: 'csv' },
				{ extend: 'excel', footer: true },
				// { extend : 'pdf' ,  footer : true } ,
				{ extend: 'print', footer: true },
				{
					extend: 'pdfHtml5',
					orientation: 'landscape', //orientação - paisagem (landscape) or retrato (portrait)
					pageSize: 'LEGAL',
					footer: true
				}
			],
			"iDisplayLength": 50,
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"language": {
				"sSearch": "<span>Buscar:</span> ",
				"sInfo": "Mostrando de <span>_START_</span> até <span>_END_</span> de <span>_TOTAL_</span> registros",
				"sLengthMenu": "_MENU_ <span>registros por página</span>",
				"sProcessing": "Processando...",
				"sZeroRecords": "Não foram encontrados resultados",
				"sInfoEmpty": "Mostrando de 0 até 0 de 0 registros",
				"sInfoFiltered": "(filtrado de _MAX_ registros no total)",
				"sInfoPostFix": "",
				"sDecimal": ",",
				"oPaginate": {
					"sFirst": "Primeiro",
					"sPrevious": "Anterior",
					"sNext": "Seguinte",
					"sLast": "Último"
				}
			},
			"stateSave": true
		});
	};
	if ($('.dataTable-desc').length > 0) {
		$('.dataTable-desc').DataTable({
			// dom: 'Bfrtip',
			dom:
				"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
				"<'row'<'col-sm-12 col-md-6'l>>",
			buttons: [
				{ extend: 'copy', footer: true },
				{ extend: 'csv' },
				{ extend: 'excel', footer: true },
				// { extend : 'pdf' ,  footer : true } ,
				{ extend: 'print', footer: true },
				{
					extend: 'pdfHtml5',
					orientation: 'landscape', //orientação - paisagem (landscape) or retrato (portrait)
					pageSize: 'LEGAL',
					footer: true
				}
			],
			"order": [[0, "desc"]],
			// 'lengthChange': true,
			// 'pageLength': 10,
			// 'bAutoWidth': false,
			"columnDefs": [
				{
					"targets": [0],
					"visible": false,
					"searchable": false,
					'orderable': false,
				}
			],
			// "iDisplayLength": 50,
			// "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],

			'lengthChange': true,
			'pageLength': 10,
			'bAutoWidth': false,
			"lengthMenu": [[10, 25, 50, 100, 1000000], [10, 25, 50, 100, "Todos"]],

			"language": {
				"sSearch": "<span>Buscar:</span> ",
				"sInfo": "Mostrando de <span>_START_</span> até <span>_END_</span> de <span>_TOTAL_</span> registros",
				"sLengthMenu": "_MENU_ <span>registros por página</span>",
				"sProcessing": "Processando...",
				"sZeroRecords": "Não foram encontrados resultados",
				"sInfoEmpty": "Mostrando de 0 até 0 de 0 registros",
				"sInfoFiltered": "(filtrado de _MAX_ registros no total)",
				"sInfoPostFix": "",
				"sDecimal": ",",
				"oPaginate": {
					"sFirst": "Primeiro",
					"sPrevious": "Anterior",
					"sNext": "Seguinte",
					"sLast": "Último"
				}
			}
		});
	};

	if ($('.dataTable-asc').length > 0) {
		$('.dataTable-asc').DataTable({
			dom: 'Bfrtip',
			buttons: [
				{ extend: 'copy', footer: true },
				{ extend: 'csv' },
				{ extend: 'excel', footer: true },
				// { extend : 'pdf' ,  footer : true } ,
				{ extend: 'print', footer: true },
				{
					extend: 'pdfHtml5',
					orientation: 'landscape', //orientação - paisagem (landscape) or retrato (portrait)
					pageSize: 'LEGAL', // TAMANHO DO PAPEL - A3, A4, A5, LEGAL, LETTER, TABLOID
					footer: true
				}
			],
			"order": [[0, "asc"]],
			"columnDefs": [
				{
					"targets": [0],
					"visible": false,
					"searchable": false
				}
			],
			"iDisplayLength": 50,
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"language": {
				"sSearch": "<span>Buscar:</span> ",
				"sInfo": "Mostrando de <span>_START_</span> até <span>_END_</span> de <span>_TOTAL_</span> registros",
				"sLengthMenu": "_MENU_ <span>registros por página</span>",
				"sProcessing": "Processando...",
				"sZeroRecords": "Não foram encontrados resultados",
				"sInfoEmpty": "Mostrando de 0 até 0 de 0 registros",
				"sInfoFiltered": "(filtrado de _MAX_ registros no total)",
				"sInfoPostFix": "",
				"sDecimal": ",",
				"oPaginate": {
					"sFirst": "Primeiro",
					"sPrevious": "Anterior",
					"sNext": "Seguinte",
					"sLast": "Último"
				}
			}
		});
	};

	if ($('.dataTable-nopage').length > 0) {
		$('.dataTable-nopage').DataTable({
			dom: 'Bfrtip',
			buttons: [
				{ extend: 'copy', footer: true },
				{ extend: 'csv' },
				{ extend: 'excel', footer: true },
				{ extend: 'pdf', footer: true },
				{ extend: 'print', footer: true }
			],
			"paging": false,
			"language": {
				"sSearch": "<span>Buscar:</span> ",
				"sInfo": "Mostrando de <span>_START_</span> até <span>_END_</span> de <span>_TOTAL_</span> registros",
				"sLengthMenu": "_MENU_ <span>registros por página</span>",
				"sProcessing": "Processando...",
				"sZeroRecords": "Não foram encontrados resultados",
				"sInfoEmpty": "Mostrando de 0 até 0 de 0 registros",
				"sInfoFiltered": "(filtrado de _MAX_ registros no total)",
				"sInfoPostFix": "",
				"sDecimal": ",",
				"oPaginate": {
					"sFirst": "Primeiro",
					"sPrevious": "Anterior",
					"sNext": "Seguinte",
					"sLast": "Último"
				}
			}
		});
	};

	$('.table tbody').on('click', '.apagar', function () {
		var id = $(this).attr('id');
		var acao = $(this).attr('acao');
		var parent = $(this).parents("tr");
		var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você deseja apagar este registro?";
		bootbox.dialog({
			message: aviso,
			title: "Apagar registro",
			buttons: {
				salvar: {
					label: "Apagar Registro",
					className: "red",
					callback: function () {
						jQuery.ajax({
							type: 'POST',
							url: 'controller.php',
							data: acao + '=' + id,
							success: function (data) {
								parent.fadeOut(400, function () {
									parent.remove();
								});
								var response = data.split("#");
								$.bootstrapGrowl(response[0], {
									ele: "body",
									type: response[1],
									offset: {
										from: "top",
										amount: 50
									},
									align: "center",
									width: "auto",
									delay: 10000,
									stackup_spacing: 10
								});
								if (response[2] == "1")
									setTimeout(function () {
										window.location.href = response[3];
									}, 1000);
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

	//BOX DIALOG Apagar um registro Cadastro Cliente
	if ($('.apagar').length > 0) {
		$('.apagar').click(function () {

			var id = $(this).attr('id');
			var acao = $(this).attr('acao');
			var parent = $(this).parents("tr");
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você deseja apagar este registro?";

			bootbox.dialog({
				message: aviso,
				title: "Apagar registro",
				buttons: {
					salvar: {
						label: "Apagar Registro",
						className: "red",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: acao + '=' + id,
								success: function (data) {
									var response = data.split("#");
									if (response[1] === "success") {
										parent.fadeOut(400, function () {
											parent.remove();
										});
									}
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	//BOX DIALOG reativar um produto apagado
	if ($('.reativar_produto').length > 0) {
		$('.reativar_produto').click(function () {

			var id = $(this).attr('id');
			var acao = $(this).attr('acao');
			var parent = $(this).parents("tr");
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Deseja reativar este produto?";

			bootbox.dialog({
				message: aviso,
				title: "Reativar produto",
				buttons: {
					salvar: {
						label: "Reativar produto",
						className: "green",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: acao + '=' + id,
								success: function (data) {
									var response = data.split("#");
									if (response[1] === "success") {
										parent.fadeOut(400, function () {
											parent.remove();
										});
									}
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	//BOX DIALOG Apagar um registro
	if ($('.apagarPagamentoCrediario').length > 0) {
		$('.apagarPagamentoCrediario').click(function () {
			var id = $(this).attr('id');
			var acao = $(this).attr('acao');
			var parent = $(this).parents("tr");
			var formApagar = $("#admin_form_apagar_crediario").serialize();
			var aviso = "Você tem certeza que deseja apagar os pagamentos selecionados?";
			bootbox.dialog({
				message: aviso,
				title: "Confirmar apagar pagamentos",
				buttons: {
					salvar: {
						label: "Sim, desejo apagar os pagamentos selecionados",
						className: "red",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: acao + '=' + id + '&' + formApagar,
								success: function (data) {
									parent.fadeOut(400, function () {
										parent.remove();
									});
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
								}
							});
						}
					},
					voltar: {
						label: "Não, quero voltar",
						className: "default"
					}
				}
			});
			return false;
		});
	};

	//BOX DIALOG Apagar um registro
	if ($('.apagarTransferenciaBancos').length > 0) {
		$(document).on('click', '.apagarTransferenciaBancos', function () {
			var id_receita = 0;
			var id_despesa = 0;
			var acao = $(this).attr('acao');
			var texto = '';
			var acaoController = 'apagarDespesaReceitaTransferenciaBancos';
			var idOutro = $(this).attr('id_outro');

			if (acao == 'apagarReceita') {
				id_receita = $(this).attr('id');
				id_despesa = idOutro;
				texto = 'Esta Receita é uma transferencia entre banco, apagando ela também será apagada a despesa correspondente. Despesa: ' + idOutro;
			} else {
				id_despesa = $(this).attr('id');
				id_receita = idOutro;
				texto = 'Esta Despesa é uma transferencia entre banco, apagando ela também será apagada a receita correspondente. Receita: ' + idOutro;
			}

			var parent = $(this).parents("tr");
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você deseja apagar este registro?";
			bootbox.dialog({
				message: texto,
				title: aviso,
				buttons: {
					salvar: {
						label: "Cancelar transferencia entre bancos",
						className: "red",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: acaoController + '=1&id_receita=' + id_receita + '&id_despesa=' + id_despesa,
								success: function (data) {
									parent.fadeOut(400, function () {
										parent.remove();
									});
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	// TABELA CHECKTABLE
	if ($('.checkTable').length > 0) {
		$('.checkTable').DataTable({
			dom:
				"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
				"<'row'<'col-sm-12'rt>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
				"<'row'<'col-sm-12 col-md-6'l>>",
			buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print'
			],
			"language": {
				"sSearch": "<span>Buscar:</span> ",
				"sInfo": "Mostrando de <span>_START_</span> até <span>_END_</span> de <span>_TOTAL_</span> registros",
				"sLengthMenu": "_MENU_ <span>registros por página</span>",
				"sProcessing": "Processando...",
				"sZeroRecords": "Não foram encontrados resultados",
				"sInfoEmpty": "Mostrando de 0 até 0 de 0 registros",
				"sInfoFiltered": "(filtrado de _MAX_ registros no total)",
				"sInfoPostFix": "",
				"sDecimal": ",",
				"oPaginate": {
					"sFirst": "Primeiro",
					"sPrevious": "Anterior",
					"sNext": "Seguinte",
					"sLast": "Último"
				}
			},
			"stateSave": true,
			"lengthChange": true,
			"pageLength": 10,
			"bAutoWidth": false,
			"lengthMenu": [[10, 25, 50, 100, 1000000], [10, 25, 50, 100, "Todos"]],
			columnDefs: [{
				orderable: false,
				targets: 0
			}],
			order: [[1, 'asc']]
		});
		$(".group-checkable").change(function () {
			var set = jQuery(this).attr("data-set");
			var checked = jQuery(this).is(":checked");
			jQuery(set).each(function () {
				if (checked) {
					this.checked = true;
					$(this).parents('tr').addClass("info");
				} else {
					this.checked = false;
					$(this).parents('tr').removeClass("info");
				}
			});
			jQuery.uniform.update(set);
		});
		$(".checkboxes").change(function () {
			var set = jQuery(this).attr("data-set");
			var checked = jQuery(this).is(":checked");
			if (checked) {
				this.checked = true;
				$(this).parents('tr').addClass("info");
			} else {
				this.checked = false;
				$(this).parents('tr').removeClass("info");
			}
			jQuery.uniform.update(set);
		});
	};

	// TABELA CHECKTABLE
	if ($('.Tbcheck').length > 0) {
		$(".group-checkable").change(function () {
			var set = jQuery(this).attr("data-set");
			var checked = jQuery(this).is(":checked");
			jQuery(set).each(function () {
				if (checked) {
					this.checked = true;
					$(this).parents('tr').addClass("info");
				} else {
					this.checked = false;
					$(this).parents('tr').removeClass("info");
				}
			});
			jQuery.uniform.update(set);
		});
		$(".checkboxes").change(function () {
			var set = jQuery(this).attr("data-set");
			var checked = jQuery(this).is(":checked");
			if (checked) {
				this.checked = true;
				$(this).parents('tr').addClass("info");
			} else {
				this.checked = false;
				$(this).parents('tr').removeClass("info");
			}
			jQuery.uniform.update(set);
		});
	};

	//ITENS PARA GERAR DESPESA COM MOVIMENTACAO DE ESTOQUE
	if ($("#estoque_gerar_despesa").length > 0) {
		$("#estoque_gerar_despesa").change(function () {
			var checked = jQuery(this).is(":checked");
			if (checked) {
				$('.itens_despesa_estoque').removeClass("ocultar");
				$('.itens_despesa_estoque').addClass("mostrar");
			} else {
				$('.itens_despesa_estoque').removeClass("mostrar");
				$('.itens_despesa_estoque').addClass("ocultar");
			}
		});
	}

	//LISTAR PRODUTOS DATATABLE
	if ($('#table_listar_produtos').length > 0) {
		let table = $('#table_listar_produtos').DataTable({
			dom:
				"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
				"<'row'<'col-sm-12 col-md-6'l>>",
			/* buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print'
			], */
			buttons: [
				'copy', 'csv', 'excel', 'print',
				{
					extend: 'pdfHtml5',
					orientation: 'landscape',
					pageSize: 'LEGAL'
				}
			],
			'processing': true,
			'serverSide': true,
			'ajax': {
				'url': 'webservices/sistema/listarProdutos.php',
				"type": 'POST',
				"data": function (d) {
					d.id_grupo = $("#id_grupo").val();
					d.id_categoria = $("#id_categoria").val();
					d.id_fabricante = $("#id_fabricante").val();
				}
			},

			'lengthChange': true,
			'pageLength': 10,
			'bAutoWidth': false,
			'lengthMenu': [[10, 25, 50, 100, 1000000], [10, 25, 50, 100, "Todos"]],
			'columnDefs': [{
				'targets': 0,
				'searchable': false,
				'orderable': false,
				'render': function (data, type, full, meta) {
					return `<input name="id_produto[]" type="checkbox" class="checkboxes" value="${data}" >`;
				}
			}, {
				'targets': 2,
				'render': function (data, type, full, meta) {
					return `<a href="index.php?do=produto&acao=editar&id=${full.id}">${data}</a>`;
				}
			}],

			'columns': [
				{ data: 'id' },
				{ data: 'id' },
				{ data: 'nome' },
				{ data: 'ncm' },
				{ data: 'cest' },
				{ data: 'codigo' },
				{ data: 'codigo_interno' },
				{ data: 'codigobarras' },
				{ data: 'grupo' },
				{ data: 'categoria' },
				{ data: 'atributos' },
				{ data: 'estoque' },
				{ data: 'valor_custo' },
				{ data: 'produto_balanca' },
				{
					'searchable': true,
					'orderable': false,
					'render': function (data, type, full, meta) {
						return `
								<a href="javascript:void(0);" class="btn btn-sm ${full.grade == 1 ? 'green-jungle' : 'grey-gallery'} gradevendas" grade="${full.grade == 1 ? 0 : 1}" id="${full.id}" title="Grade de vendas : ${full.nome}"><i class="fa fa-th"></i></a>
								<a href="index.php?do=produto&acao=editar&id=${full.id}&id_grupo=${full.id_grupo}&id_categoria="${full.id_categoria}" class="btn btn-sm blue" title="Editar: ${full.nome}"><i class="fa fa-pencil"></i></a>
								<a href="javascript:void(0);" class="btn btn-sm red apagar" id="${full.id}" acao="apagarProduto" title="Você deseja apagar este produto? ${full.nome}"><i class="fa fa-times"></i></a>
							`;
					}
				}
			],

			"language": {
				"sSearch": "<span>Buscar:</span> ",
				"sInfo": "Mostrando de <span>_START_</span> até <span>_END_</span> de <span>_TOTAL_</span> registros",
				"sLengthMenu": "_MENU_ <span>registros por página</span>",
				"sProcessing": "Processando...",
				"sZeroRecords": "Não foram encontrados resultados",
				"sInfoEmpty": "Mostrando de 0 até 0 de 0 registros",
				"sInfoFiltered": "(filtrado de _MAX_ registros no total)",
				"sInfoPostFix": "",
				"sDecimal": ",",
				"oPaginate": {
					"sFirst": "Primeiro",
					"sPrevious": "Anterior",
					"sNext": "Seguinte",
					"sLast": "Último"
				}
			},

			"drawCallback": function () {
				// PRODUTOS - alterar grade
				$('a.gradevendas').click(function () {
					var id = $(this).attr('id');
					var grade = $(this).attr('grade');
					jQuery.ajax({
						type: 'POST',
						url: 'controller.php',
						data: 'processarGradeVendas=1&id=' + id + '&grade=' + grade,
						success: function (data) {
							var response = data.split("#");
							$.bootstrapGrowl(response[0], {
								ele: "body",
								type: response[1],
								offset: {
									from: "top",
									amount: 50
								},
								align: "center",
								width: "auto",
								delay: 10000,
								stackup_spacing: 10
							});
							table.draw();
						}
					});
					return false;
				});

				if ($("#checkTodos").length > 0) {
					$("#checkTodos").click(function () {
						if ($(this).is(':checked')) {
							$('input:checkbox').prop("checked", true);
						} else {
							$('input:checkbox').prop("checked", false);
						}
					});
				}

				//BOX DIALOG Apagar um registro
				if ($('.apagar').length > 0) {
					$('.apagar').click(function () {

						var id = $(this).attr('id');
						var acao = $(this).attr('acao');
						var parent = $(this).parents("tr");
						var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você deseja apagar este registro?";

						bootbox.dialog({
							message: aviso,
							title: "Apagar registro",
							buttons: {
								salvar: {
									label: "Apagar Registro",
									className: "red",
									callback: function () {
										jQuery.ajax({
											type: 'POST',
											url: 'controller.php',
											data: acao + '=' + id,
											success: function (data) {
												var response = data.split("#");
												if (response[1] === "success") {
													parent.fadeOut(400, function () {
														parent.remove();
													});
												}
												$.bootstrapGrowl(response[0], {
													ele: "body",
													type: response[1],
													offset: {
														from: "top",
														amount: 50
													},
													align: "center",
													width: "auto",
													delay: 10000,
													stackup_spacing: 10
												});
												if (response[2] == "1")
													setTimeout(function () {
														window.location.href = response[3];
													}, 1000);
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
				};

				$('#table_listar_produtos tbody tr').each(function () {
					var data = table.row(this).data();
					if (data && data.estoque !== undefined && data.estoque_minimo !== undefined) {
						var estoque = parseFloat(data.estoque.replace(',', '.'));
						var estoque_minimo = parseFloat(data.estoque_minimo.replace(',', '.'));
						if (estoque <= estoque_minimo && estoque_minimo > 0) {
							$(this).addClass('danger');
						} else {
							$(this).removeClass('danger');
						}
					} else {
						$(this).removeClass('danger');
					}
				});
			}
		});
		$('#id_grupo').change(() => table.draw());
		$('#id_categoria').change(() => table.draw());
		$('#id_fabricante').change(() => table.draw());
	};

	//LISTAR TABELA DE PREÇOS DATATABLE
	if ($('#table_listar_tabela_preco').length > 0) {
		let table = $('#table_listar_tabela_preco').DataTable({
			dom:
				"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
				"<'row'<'col-sm-12 col-md-6'l>>",
			// buttons: [
			// 	'copy', 'csv', 'excel', 'pdf', 'print'
			// ],
			buttons: [
				'copy', 'csv', 'excel', 'print',
				{
					extend: 'pdfHtml5',
					orientation: 'landscape', //orientação - paisagem
					pageSize: 'LEGAL'
				}
			],
			'processing': true,
			'serverSide': true,
			'ajax': {
				'url': 'webservices/sistema/listarTabelaPreco.php',
				"type": 'POST',
				"data": function (d) {
					d.id = $("#id").val();
					d.id_grupo = $("#id_grupo").val();
					d.id_categoria = $("#id_categoria").val();
					d.id_fabricante = $("#id_fabricante").val();
				}
			},

			'lengthChange': true,
			'pageLength': 10,
			'bAutoWidth': false,
			"lengthMenu": [[10, 25, 50, 100, 1000000], [10, 25, 50, 100, "Todos"]],

			'columns': [
				{ data: 'id' },
				{ data: 'codigo' },
				{ data: 'codigobarras' },
				{ data: 'nome' },
				{ data: 'grupo' },
				{ data: 'categoria' },
				{ data: 'estoque' },
				{ data: 'valor_custo' },
				{ data: 'percentual' },
				{ data: 'valor_venda' },
				{
					'searchable': true,
					'orderable': false,
					'render': function (data, type, full, meta) {
						return `
							<a href="javascript:void(0);" class="btn btn-sm green editar" id="${full.id}" valor="${full.valor_venda}" title="Alterar ${full.nome}"><i class="fa fa-usd"></i></a>
							<a href="javascript:void(0);" class="btn btn-sm red delete" id="${full.id}" acao="apagarProdutoTabelaPreco" title="Você deseja apagar este produto? ${full.nome}"><i class="fa fa-times"></i></a>
						`;
					}
				}
			],

			"language": {
				"sSearch": "<span>Buscar:</span> ",
				"sInfo": "Mostrando de <span>_START_</span> até <span>_END_</span> de <span>_TOTAL_</span> registros",
				"sLengthMenu": "_MENU_ <span>registros por página</span>",
				"sProcessing": "Processando...",
				"sZeroRecords": "Não foram encontrados resultados",
				"sInfoEmpty": "Mostrando de 0 até 0 de 0 registros",
				"sInfoFiltered": "(filtrado de _MAX_ registros no total)",
				"sInfoPostFix": "",
				"sDecimal": ",",
				"oPaginate": {
					"sFirst": "Primeiro",
					"sPrevious": "Anterior",
					"sNext": "Seguinte",
					"sLast": "Último"
				}
			},

			"initComplete": function () {
				TableEditable.init();
			}
		});

		$('#id').change(() => table.draw());
		$('#id_grupo').change(() => table.draw());
		$('#id_categoria').change(() => table.draw());
		$('#id_fabricante').change(() => table.draw());
	};

	//LISTAR TABELA DE PREÇOS DATATABLE
	if ($('#table_listar_tabela_preco_geral').length > 0) {
		let table = $('#table_listar_tabela_preco_geral').DataTable({
			dom:
				"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
				"<'row'<'col-sm-12 col-md-6'l>>",
			// buttons: [
			// 	'copy', 'csv', 'excel', 'pdf', 'print'
			// ],
			buttons: [
				'copy', 'csv', 'excel', 'print',
				{
					extend: 'pdfHtml5',
					orientation: 'landscape', //orientação - paisagem (landscape) or retrato (portrait)
					pageSize: 'LEGAL'
				}
			],
			'processing': true,
			'serverSide': true,
			'ajax': {
				'url': 'webservices/sistema/listarTabelaPreco.php',
				"type": 'POST',
				"data": function (d) {
					d.id = $("#id").val();
					d.id_grupo = $("#id_grupo").val();
					d.id_categoria = $("#id_categoria").val();
					d.id_fabricante = $("#id_fabricante").val();
				}
			},

			'lengthChange': true,
			'pageLength': 10,
			'bAutoWidth': false,
			"lengthMenu": [[10, 25, 50, 100, 1000000], [10, 25, 50, 100, "Todos"]],

			'columns': [
				{ data: 'id' },
				{ data: 'codigo' },
				{ data: 'codigobarras' },
				{ data: 'nome' },
				{ data: 'grupo' },
				{ data: 'categoria' },
				{ data: 'estoque' },
				{ data: 'valor_venda' }
			],

			"language": {
				"sSearch": "<span>Buscar:</span> ",
				"sInfo": "Mostrando de <span>_START_</span> até <span>_END_</span> de <span>_TOTAL_</span> registros",
				"sLengthMenu": "_MENU_ <span>registros por página</span>",
				"sProcessing": "Processando...",
				"sZeroRecords": "Não foram encontrados resultados",
				"sInfoEmpty": "Mostrando de 0 até 0 de 0 registros",
				"sInfoFiltered": "(filtrado de _MAX_ registros no total)",
				"sInfoPostFix": "",
				"sDecimal": ",",
				"oPaginate": {
					"sFirst": "Primeiro",
					"sPrevious": "Anterior",
					"sNext": "Seguinte",
					"sLast": "Último"
				}
			},

			"initComplete": function () {
				TableEditable.init();
			}
		});

		$('#id').change(() => table.draw());
		$('#id_grupo').change(() => table.draw());
		$('#id_categoria').change(() => table.draw());
		$('#id_fabricante').change(() => table.draw());
	};

	//LISTAR TABELA DE PREÇOS DATATABLE
	if ($('#table_listar_preco_produtos').length > 0) {
		let table = $('#table_listar_preco_produtos').DataTable({
			dom:
				"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
				"<'row'<'col-sm-12 col-md-6'l>>",
			// buttons: [
			// 	'copy', 'csv', 'excel', 'pdf', 'print'
			// ],
			buttons: [
				'copy', 'csv', 'excel', 'print',
				{
					extend: 'pdfHtml5',
					orientation: 'landscape', //orientação - paisagem
					pageSize: 'LEGAL'
				}
			],
			'processing': true,
			'serverSide': true,
			'ajax': {
				'url': 'webservices/sistema/listarPrecoProdutos.php',
				"type": 'POST',
				"data": function (d) {
					d.id = $("#id").val();
				}
			},

			'lengthChange': true,
			'pageLength': 30,
			'bAutoWidth': false,
			"lengthMenu": [[30, 60, 90, 120, 1000000], [30, 60, 90, 120, "Todos"]],

			'columns': [
				{ data: 'id' },
				{ data: 'codigo' },
				{ data: 'codigobarras' },
				{ data: 'codigointerno' },
				{ data: 'nome' },
				{ data: 'estoque' },
				{ data: 'valor_avista' },
				{ data: 'valor_venda' },
				{ data: 'observacao' }
			],

			"language": {
				"sSearch": "<span>Buscar:</span> ",
				"sInfo": "Mostrando de <span>_START_</span> até <span>_END_</span> de <span>_TOTAL_</span> registros",
				"sLengthMenu": "_MENU_ <span>registros por página</span>",
				"sProcessing": "Processando...",
				"sZeroRecords": "Não foram encontrados resultados",
				"sInfoEmpty": "Mostrando de 0 até 0 de 0 registros",
				"sInfoFiltered": "(filtrado de _MAX_ registros no total)",
				"sInfoPostFix": "",
				"sDecimal": ",",
				"oPaginate": {
					"sFirst": "Primeiro",
					"sPrevious": "Anterior",
					"sNext": "Seguinte",
					"sLast": "Último"
				}
			},

			"initComplete": function () {
				table.init();
			}
		});

		$('#id').change(() => table.draw());
	};

	//LISTAR VENDAS EM ABERTO DATATABLE
	if ($('#table_listar_vendas_aberto').length > 0) {
		let table = $('#table_listar_vendas_aberto').DataTable({
			dom:
				"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
				"<'row'<'col-sm-12 col-md-6'l>>",
			// buttons: [
			// 	'copy', 'csv', 'excel', 'pdf', 'print'
			// ],
			buttons: [
				'copy', 'csv', 'excel', 'print',
				{
					extend: 'pdfHtml5',
					orientation: 'landscape', //orientação - paisagem (landscape) or retrato (portrait)
					pageSize: 'LEGAL'
				}
			],
			'processing': true,
			'serverSide': true,
			'ajax': {
				'url': 'webservices/sistema/listarVendasAberto.php',
				"type": 'POST'
			},
			"order": [[0, "desc"]],
			'lengthChange': true,
			'pageLength': 25,
			'bAutoWidth': false,
			"lengthMenu": [[10, 25, 50, 100, 1000000], [10, 25, 50, 100, "Todos"]],

			'columns': [
				{ data: 'id' },
				{ data: 'data_venda' },
				{ data: 'cliente' },
				{ data: 'valor_total' },
				{ data: 'valor_desconto' },
				{ data: 'valor_despesa_acessoria' },
				{ data: 'valor_pago' },
				{ data: 'pagamentos' },
				{ data: 'usuario' },
				{
					'searchable': false,
					'orderable': false,
					'render': function (data, type, full, meta) {
						retorno = `<a href="javascript:void(0);" onclick="javascript:void window.open('recibo_orcamento.php?id=${full.id}','Imprimir Venda em aberto ${full.id}','width=360,height=700,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="Imprimir Orçamento" class="btn btn-sm grey"><i class="fa fa-file-o"></i></a>`;
						if (!full.cliente) {
							retorno += `<a href="index.php?do=vendas&acao=adicionarclientevenda&id=${full.id}&pg=2" class="btn btn-sm blue popovers" data-container="body" data-trigger="hover" data-placement="top" data-content="Para emissão de NFC-e com valor igual ou superior a R$ 10.000,00 é obrigatório a identificação do cliente." data-original-title="Vincular cliente a esta venda: ${full.id}" title="Vincular cliente a esta venda: ${full.id}"><i class="fa fa-user"></i></a>`;
						}
						if (full.id_cadastro > 0) {
							retorno += `<a href="javascript:void(0);" onclick="javascript:void window.open('pdf_pedido_orcamento.php?id=${full.id}','CÓDIGO: ${full.id}','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;" title="Imprimir Venda em aberto A4" class="btn btn-sm grey-cascade"><i class="fa fa-file-text-o"></i></a>`;
							if (full.entrega) {
								retorno += `<a href="javascript:void(0);" onclick="javascript:void window.open('pdf_romaneio.php?id=${full.id}','CÓDIGO: ${full.id}','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;" title="Ver romaneio" class="btn btn-sm yellow-gold"><i class="fa fa-truck"></i></a>`;
							}
						}
						retorno += `<a href="index.php?do=vendas&acao=finalizarvenda&id=${full.id}" class="btn btn-sm sigesis-cor-1" title="Ir para: ${full.id}"><i class="fa fa-share"></i></a>`;

						return retorno;
					}
				}
			],

			"language": {
				"sSearch": "<span>Buscar:</span> ",
				"sInfo": "Mostrando de <span>_START_</span> até <span>_END_</span> de <span>_TOTAL_</span> registros",
				"sLengthMenu": "_MENU_ <span>registros por página</span>",
				"sProcessing": "Processando...",
				"sZeroRecords": "Não foram encontrados resultados",
				"sInfoEmpty": "Mostrando de 0 até 0 de 0 registros",
				"sInfoFiltered": "(filtrado de _MAX_ registros no total)",
				"sInfoPostFix": "",
				"sDecimal": ",",
				"oPaginate": {
					"sFirst": "Primeiro",
					"sPrevious": "Anterior",
					"sNext": "Seguinte",
					"sLast": "Último"
				}
			}
		});
	};

	//LISTAR VENDAS EM ABERTO DATATABLE
	if ($('#table_listar_vendas_aberto_cancelar').length > 0) {
		let table = $('#table_listar_vendas_aberto_cancelar').DataTable({
			dom:
				"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
				"<'row'<'col-sm-12'tr>>" +
				"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
				"<'row'<'col-sm-12 col-md-6'l>>",
			// buttons: [
			// 	'copy', 'csv', 'excel', 'pdf', 'print'
			// ],
			buttons: [
				'copy', 'csv', 'excel', 'print',
				{
					extend: 'pdfHtml5',
					orientation: 'landscape', //orientação - paisagem (landscape) or retrato (portrait)
					pageSize: 'LEGAL'
				}
			],
			'processing': true,
			'serverSide': true,
			'ajax': {
				'url': 'webservices/sistema/listarVendasAberto.php',
				"type": 'POST'
			},
			"order": [[0, "desc"]],
			'lengthChange': true,
			'pageLength': 25,
			'bAutoWidth': false,
			"lengthMenu": [[10, 25, 50, 100, 1000000], [10, 25, 50, 100, "Todos"]],

			'columns': [
				{ data: 'id' },
				{ data: 'data_venda' },
				{ data: 'cliente' },
				{ data: 'valor_total' },
				{ data: 'valor_desconto' },
				{ data: 'valor_despesa_acessoria' },
				{ data: 'valor_pago' },
				{ data: 'pagamentos' },
				{ data: 'usuario' },
				{
					'searchable': false,
					'orderable': false,
					'render': function (data, type, full, meta) {
						retorno = `<a href="javascript:void(0);" onclick="javascript:void window.open('recibo_orcamento.php?id=${full.id}','Imprimir Orçamento ${full.id}','width=360,height=700,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');return false;" title="Imprimir Venda em aberto" class="btn btn-sm grey"><i class="fa fa-file-o"></i></a>`;
						if (full.id_cadastro < 1) {
							retorno += `
								<a
									href="index.php?do=vendas&acao=adicionarclientevenda&id=${full.id}&pg=2"
									class="btn btn-sm blue popovers"
									data-container="body"
									data-trigger="hover"
									data-placement="top"
									data-content="Para emissão de NFC-e com valor igual ou superior a R$ 10.000,00 é obrigatório a identificação do cliente."
									data-original-title="Vincular cliente a esta venda: ${full.id}"
									title="Vincular cliente a esta venda: ${full.id}">
									<i class="fa fa-user"></i>
								</a>
							`;
						}
						if (full.id_cadastro > 0) {
							retorno += `<a href="javascript:void(0);" onclick="javascript:void window.open('pdf_pedido_orcamento.php?id=${full.id}','CÓDIGO: ${full.id}','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;" title="Imprimir Venda em aberto A4" class="btn btn-sm grey-cascade"><i class="fa fa-file-text-o"></i></a>`;
							if (full.entrega) {
								retorno += `<a href="javascript:void(0);" onclick="javascript:void window.open('pdf_romaneio.php?id=${full.id}','CÓDIGO: ${full.id}','width=800,height=600,toolbar=0,menubar=0,location=0,status=0,scrollbars=1,resizable=1,left=0,top=0');return false;" title="Ver romaneio" class="btn btn-sm yellow-gold"><i class="fa fa-truck"></i></a>`;
							}
						}
						retorno += `<a href="index.php?do=vendas&acao=finalizarvenda&id=${full.id}" class="btn btn-sm sigesis-cor-1" title="Ir para: ${full.id}"><i class="fa fa-share"></i></a>
						<a href="javascript:void(0);" class="btn btn-sm red apagar" id="${full.id}" acao="processarCancelarVendaAberto" title="Você deseja cancelar esta venda? Código: ${full.id}"><i class="fa fa-ban"></i></a>`;

						return retorno;
					}
				}
			],

			"language": {
				"sSearch": "<span>Buscar:</span> ",
				"sInfo": "Mostrando de <span>_START_</span> até <span>_END_</span> de <span>_TOTAL_</span> registros",
				"sLengthMenu": "_MENU_ <span>registros por página</span>",
				"sProcessing": "Processando...",
				"sZeroRecords": "Não foram encontrados resultados",
				"sInfoEmpty": "Mostrando de 0 até 0 de 0 registros",
				"sInfoFiltered": "(filtrado de _MAX_ registros no total)",
				"sInfoPostFix": "",
				"sDecimal": ",",
				"oPaginate": {
					"sFirst": "Primeiro",
					"sPrevious": "Anterior",
					"sNext": "Seguinte",
					"sLast": "Último"
				}
			}
		});
	};

	//Zoom na imagem no menu de atualizações
	$('.zoom').mousemove(function (e) {
		var zoomer = e.currentTarget;
		e.offsetX ? offsetX = e.offsetX : offsetX = e.touches[0].pageX;
		e.offsetY ? offsetY = e.offsetY : offsetX = e.touches[0].pageX;
		x = offsetX / zoomer.offsetWidth * 100;
		y = offsetY / zoomer.offsetHeight * 100;
		zoomer.style.backgroundPosition = x + '% ' + y + '%';

	});


	///////////////////////
	///	INICIO NOVO PDV	///
	///////////////////////

	//Campo de desconto dentro do modal pagamento
	$('#valor_desconto_modal').keyup(function (event) {

		if (event.which == 13 || event.keyCode == 13) {
			$('#valor_acrescimo_modal').focus().select();
		} else {

			var valor = $("#valor_pagar_modal_pgto").val();
			v = valor.replace('R$ ', '').replace(',', '.');
			v = parseFloat(v).toFixed(2);
			if (isNaN(v)) {
				v = parseFloat(0).toFixed(2);
			}

			var desconto = $("#valor_desconto_modal").val();
			var d = desconto.replace('.', '');
			d = d.replace(',', '.');
			d = d.replace('R$ ', '');
			d = parseFloat(d);
			if (isNaN(d)) {
				d = parseFloat(0).toFixed(2);
			}

			$('#valor_desconto_rapida').val(parseFloat(d));

			// var acrescimo = $("#valor_acrescimo_rapida").val();
			var acrescimo = $("#valor_acrescimo_modal").val();
			var a = acrescimo.replace('.', '');
			a = a.replace(',', '.');
			a = a.replace('R$ ', '');
			a = parseFloat(a);
			if (isNaN(a)) {
				a = parseFloat(0).toFixed(2);
			}

			if (d > v) {
				alert('Desconto maior que o valor total da venda.');
				$("#valor_desconto_modal").val('R$ 0.00');
				$("#valor_desconto_porcentagem").val(0.0);
				$('#valor_desconto_modal').focus().select();
				d = parseFloat(0).toFixed(2);
			}

			var soma = 0;
			$('.valor_pago').each(function (indice, item) {
				var i = $(item).val();
				var p = parseFloat(i);
				if (!isNaN(p)) {
					soma += p;
				}
			});

			var soma_dinheiro = 0;
			$('.dinheiro').each(function (indice, item) {
				var i = $(item).val();
				var p = parseFloat(i);
				if (!isNaN(p)) {
					soma_dinheiro += p;
				}
			});

			var valor_pagar = parseFloat(v) + parseFloat(a) - parseFloat(d) - parseFloat(soma);

			if (valor_pagar < 0) {
				valor_pagar = parseFloat(0).toFixed(2);
			}
			var resultado = valor_pagar.toFixed(2);
			resultado = resultado.toString();
			resultado = 'R$ ' + resultado.replace('.', ',');
			$("#valor_pagar").text(resultado);

			$('.valor_pago_venda').val(resultado); // Quando selecionar o tipo de pagamento, o campo valor em Vendas > Nova venda terá o valor a pagar
			$('#valor_pago_modal').val(resultado);

			var soma_restante = parseFloat(soma) - parseFloat(soma_dinheiro);
			var total_pagar_dinheiro = parseFloat(v) + parseFloat(a) - parseFloat(d) - parseFloat(soma_restante);
			var troco = parseFloat(soma_dinheiro) - parseFloat(total_pagar_dinheiro);

			if (troco < 0) {
				troco = 0;
			}

			resultado = troco.toFixed(2);
			resultado = resultado.toString();
			resultado = 'R$ ' + resultado.replace('.', ',');
			$("#troco").text(resultado);

			if (troco > soma_dinheiro) {
				resultado = soma_dinheiro.toFixed(2);
				resultado = resultado.toString();
				resultado = 'R$ ' + resultado.replace('.', ',');
				$("#troco").text(resultado);
				alert('O valor do TROCO deve ser MENOR que o total pago em DINHEIRO.');
				return false;
			}

			//Desconto em porcentagem - Valor desconto -> Desconto total em porcentagem

			// let valor_desconto = $("#valor_desconto_rapida").val();
			let valor_desconto = $("#valor_desconto_modal").val();

			valor_desconto = valor_desconto.replace('.', '');
			valor_desconto = valor_desconto.replace(',', '.');
			valor_desconto = valor_desconto.replace('R$ ', '');

			let valor_total = $("#valor2").html();

			valor_total = valor_total.replace('.', '');
			valor_total = valor_total.replace(',', '.');
			valor_total = valor_total.replace('R$ ', '');

			let calc_porcent = ((valor_desconto * 100) / valor_total);
			calc_porcent = calc_porcent.toFixed(2);
			//calc_porcent = '% ' + calc_porcent.toString();
			$("#valor_desconto_porcentagem").val(calc_porcent);
			$('#valor_desconto_porcentagem_modal').val(calc_porcent);
		}

	});

	//Campo de parcelas dentro do modal pagamento
	$('#parcelas_modal').keyup(function (e) {

		if (e.which == 13 || e.keyCode == 13) {
			$('#valor_pago_modal').focus().select();
		}

	});

	//Campo de acrescimo dentro do modal pagamento
	$('#valor_acrescimo_modal').keyup(function (e) {

		if (e.which == 13 || e.keyCode == 13) {
			$('#parcelas_modal').focus().select();
		}

		var valor = $("#valor_pagar_modal_pgto").val();
		v = valor.replace('R$ ', '').replace(',', '.');
		v = parseFloat(v).toFixed(2);
		if (isNaN(v)) {
			v = parseFloat(0).toFixed(2);
		}

		var acrescimo = $("#valor_acrescimo_modal").val();

		var a = acrescimo.replace('.', '');
		a = a.replace(',', '.');
		a = a.replace('R$ ', '');
		a = parseFloat(a).toFixed(2);
		if (isNaN(a)) {
			a = parseFloat(0).toFixed(2);
		}

		$('#valor_acrescimo_rapida').val(parseFloat(a));

		var desconto = $('#valor_desconto_modal').val();
		var d = desconto.replace('.', '');
		d = d.replace(',', '.');
		d = d.replace('R$ ', '');
		d = parseFloat(d).toFixed(2);
		if (isNaN(d)) {
			d = parseFloat(0).toFixed(2);
		}

		if (parseFloat(d) > parseFloat(v)) {
			alert('Desconto maior que o valor total da venda.2');
			$("#valor_desconto_modal").val('R$ 0.00');
			$("#valor_desconto_porcentagem").val(0.0);
			$('#valor_desconto_modal').focus().select();
			d = parseFloat(0).toFixed(2);
		}

		var soma = 0;
		$('.valor_pago').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i).toFixed(2);
			if (!isNaN(p)) {
				soma += p;
			}
		});
		soma = parseFloat(soma).toFixed(2);

		var soma_dinheiro = 0;
		$('.dinheiro').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma_dinheiro += p;
			}
		});

		var valor_pagar = parseFloat(v) + parseFloat(a) - parseFloat(d) - parseFloat(soma);
		if (valor_pagar < 0) {
			valor_pagar = parseFloat(0).toFixed(2);
		}

		var resultado = valor_pagar.toFixed(2);
		resultado = resultado.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');

		$("#valor_pagar").text(resultado);

		$('.valor_pago_venda').val(resultado); // Quando selecionar o tipo de pagamento, o campo valor em Vendas > Nova venda terá o valor a pagar
		$('#valor_pago_modal').val(resultado);

		var soma_restante = parseFloat(soma) - parseFloat(soma_dinheiro);
		var total_pagar_dinheiro = parseFloat(v) + parseFloat(a) - parseFloat(d) - parseFloat(soma_restante);
		var troco = parseFloat(soma_dinheiro) - parseFloat(total_pagar_dinheiro);

		if (troco < 0) {
			troco = 0;
		}

		resultado = troco.toFixed(2);
		resultado = resultado.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#troco").text(resultado);

		if (troco > soma_dinheiro) {
			resultado = soma_dinheiro.toFixed(2);
			resultado = resultado.toString();
			resultado = 'R$ ' + resultado.replace('.', ',');
			$("#troco").text(resultado);
			alert('O valor do TROCO deve ser MENOR que o total pago em DINHEIRO.');
			return false;
		}

		//Desconto em porcentagem - Valor desconto -> Desconto total em porcentagem

		// let valor_desconto = $("#valor_desconto_rapida").val();
		let valor_desconto = $("#valor_desconto_modal").val();

		valor_desconto = valor_desconto.replace('.', '');
		valor_desconto = valor_desconto.replace(',', '.');
		valor_desconto = valor_desconto.replace('R$ ', '');
		if (valor_desconto > 0) {
			let valor_total = $("#valor2").html();
			valor_total = valor_total.replace('.', '');
			valor_total = valor_total.replace(',', '.');
			valor_total = valor_total.replace('R$ ', '');
			let calc_porcent = ((valor_desconto * 100) / valor_total);
			calc_porcent = calc_porcent.toFixed(2);
			//calc_porcent = '% ' + calc_porcent.toString();
			$("#valor_desconto_porcentagem").val(calc_porcent);
			$('#valor_desconto_porcentagem_modal').val(calc_porcent);
		}

	});

	$("#valor_desconto_porcentagem_modal").keyup(function (e) {

		if (e.which == 13 || e.keyCode == 13) {
			$('#valor_desconto_modal').focus().select();
		}

		let porcentagem = $(this).val();
		let p = porcentagem.replace("%", '');
		p = (p.replace(".", ''));
		p = parseFloat(p.replace(",", '.'));
		if (isNaN(p)) {
			p = 0;
		}

		var valor = $("#valor_pagar_modal_pgto").val();
		v = valor.replace('R$ ', '').replace(',', '.');
		v = parseFloat(v).toFixed(2);
		if (isNaN(v)) {
			v = 0;
		}

		let valor_porcentagem = (v * p) / 100;
		valor_porcentagem = Math.floor(valor_porcentagem * 100) / 100;

		$("#valor_desconto_modal").val(floatParaReal(valor_porcentagem));

		let d = valor_porcentagem;

		$('#valor_desconto_rapida').val(parseFloat(d));

		var acrescimo = $("#valor_acrescimo_modal").val();
		var a = acrescimo.replace('.', '');
		a = a.replace(',', '.');
		a = a.replace('R$ ', '');
		a = parseFloat(a);
		if (isNaN(a)) {
			a = 0;
		}

		if (d > v) {
			alert('Desconto maior que o valor total da venda.3');
			$("#valor_desconto_modal").val('R$ 0.00');
			$("#valor_desconto_porcentagem").val(0.0);
			$('#valor_desconto_modal').focus().select();
			d = 0;
			return false;
		}

		var soma = 0;
		$('.valor_pago').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma += p;
			}
		});

		var soma_dinheiro = 0;
		$('.dinheiro').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma_dinheiro += p;
			}
		});

		var valor_pagar = v + a - d - soma;
		if (valor_pagar < 0) {
			valor_pagar = 0;
		}
		var resultado = valor_pagar.toFixed(2);
		resultado = resultado.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#valor_pagar").text(resultado);

		$('.valor_pago_venda').val(resultado); // Quando selecionar o tipo de pagamento, o campo valor em Vendas > Nova venda terá o valor a pagar
		$('#valor_pago_modal').val(resultado);

		var soma_restante = soma - soma_dinheiro;
		var total_pagar_dinheiro = v + a - d - soma_restante;
		var troco = soma_dinheiro - total_pagar_dinheiro;

		if (troco < 0) {
			troco = 0;
		}

		resultado = troco.toFixed(2);
		resultado = resultado.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#troco").text(resultado);

		if (troco > soma_dinheiro) {
			resultado = soma_dinheiro.toFixed(2);
			resultado = resultado.toString();
			resultado = 'R$ ' + resultado.replace('.', ',');
			$("#troco").text(resultado);
			alert('O valor do TROCO deve ser MENOR que o total pago em DINHEIRO.');
			return false;
		}

	});

	$('#modal_pagamentos').on('shown.bs.modal', function () {
		$('#tipopagamento').focus();
	});

	$('#data_boleto_modal').hide();

	//Atalho F4 - Selecionar forma de pagamento Vendas > Nova Venda - PDV
	shortcut.add("F4", function () {

		var id_produto = $('.id_produto').val();
		var valor_pagar_pdv = $('#valor_pagar').text();
		valor_pagar_pdv = valor_pagar_pdv.replace('R$ ', '').replace('.', '').replace(',', '.');
		valor_pagar_pdv = parseFloat(valor_pagar_pdv);
		if (!id_produto) {
			alert('Favor selecionar um produto para o pagamento.');
			return false;
		} else if (valor_pagar_pdv <= 0) {
			alert('Não existe pagamento disponível para esta venda.');
			return false;
		} else {

			$('#modal_pagamentos').modal();
			// $('.valores_pagamento').removeClass("mostrar"); mudamos pois estava tirando a seleção do tipo de pagamento
			// $('.valores_pagamento').addClass("ocultar");
			$('#tipopagamento').focus();

			shortcut.add("ALT+S", function () { $('#valor_desconto_porcentagem_modal').focus(); });
			shortcut.add("ALT+D", function () { $('#valor_desconto_modal').focus(); });
			shortcut.add("ALT+A", function () { $('#valor_acrescimo_modal').focus(); });
			shortcut.add("ALT+V", function () { $('#valor_pago_modal').focus(); });
			shortcut.add("ALT+P", function () { $('#parcelas_modal').focus(); });
			shortcut.add("ALT+T", function () { $('#data_boleto_modal').focus(); });
			shortcut.add("ALT+F", function () { $('#tipopagamento').focus(); });

			$('#tipopagamento').keyup(function (event) {
				if (event.keyCode == 13) {
					ativaInformacoesPagamento();
				}
			}).click(function () {
				ativaInformacoesPagamento();
			});
		}
	});

	//Obtem valor a pagar do pdv
	function obterValorAPagarPDV() {
		var totalPagoPDV = 0;
		$('.valor_pago').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				totalPagoPDV += p;
			}
		});
		var avistaPDV = $('#tipopagamento option:selected').attr('avista');
		var pagamentosAVistaPDV = 1;
		var usarValorAVista = 0;
		$('.pagamento_avista').each(function (indice, item) {
			var i = $(item).val();
			var p = parseInt(i);
			if (!isNaN(p)) {
				pagamentosAVistaPDV *= p;
			} else {
				pagamentosAVistaPDV = 0;
			}
		});
		usarValorAVista = (pagamentosAVistaPDV == 1 && avistaPDV == 1) ? true : false;
		var aux = 0;
		var quantidade_pdv = []; //array de quantidades de produtos no formato 1.000
		$(".quant_venda").each(function () {
			//quantidade_pdv.push($(".quant_venda")[aux].value);
			var qtde = $(".quant_venda")[aux].value;
			quantidade_pdv.push(qtde.replace(',', ''));
			aux++;
		});
		aux = 0;
		var valor_produto = []; //array de valores de produtos no formato R$ 10,00
		var valor_produto_avista = []; //array de valores avista de produtos no formato 10.00
		var valor_produto_normal = []; //array de valores normal de produtos no formato 10.00
		$(".valor").each(function () {
			valor_produto.push($(".valor")[aux].value);
			valor_produto_avista.push($(".valor")[aux].getAttribute("valor_avista"));
			valor_produto_normal.push($(".valor")[aux].getAttribute("valor_normal"));
			aux++;
		});
		var soma_valor_total = 0;
		let total = 0;

		if (usarValorAVista) {
			for (i = 0; i < quantidade_pdv.length; i++) {
				var valor_lista_atual = valor_produto[i];
				valor_lista_atual = valor_lista_atual.replace('R$ ', '').replace('.', '').replace(',', '.');
				valor_lista_atual = parseFloat(valor_lista_atual);
				if ((valor_lista_atual != valor_produto_avista[i]) && (valor_lista_atual != valor_produto_normal[i])) {
					soma_valor_total += (parseFloat(valor_lista_atual).toFixed(2) * parseFloat(quantidade_pdv[i]));
					soma_valor_total = Math.round(soma_valor_total * 100) / 100;
				} else if (valor_produto_avista[i] > 0) {
					soma_valor_total += (parseFloat(valor_produto_avista[i]).toFixed(2) * parseFloat(quantidade_pdv[i]));
					soma_valor_total = Math.round(soma_valor_total * 100) / 100;
				} else {
					soma_valor_total += (parseFloat(valor_produto_normal[i]).toFixed(2) * parseFloat(quantidade_pdv[i]));
					soma_valor_total = Math.round(soma_valor_total * 100) / 100;
				}
			}
		} else {
			for (i = 0; i < quantidade_pdv.length; i++) {
				var valor_lista_atual = valor_produto[i];
				valor_lista_atual = valor_lista_atual.replace('R$ ', '').replace('.', '').replace(',', '.');
				valor_lista_atual = parseFloat(valor_lista_atual);

				if ((valor_lista_atual != valor_produto_avista[i]) && (valor_lista_atual != valor_produto_normal[i])) {
					soma_valor_total += (parseFloat(valor_lista_atual).toFixed(2) * parseFloat(quantidade_pdv[i]));
					soma_valor_total = Math.round(soma_valor_total * 100) / 100;
				} else {
					soma_valor_total += (parseFloat(valor_produto_normal[i]).toFixed(2) * parseFloat(quantidade_pdv[i]));
					soma_valor_total = Math.round(soma_valor_total * 100) / 100;
				}
			}
		}

		var resultado = soma_valor_total;
		resultado -= totalPagoPDV;
		resultado = Math.round(resultado * 100) / 100;
		resultado = resultado.toFixed(2);
		return 'R$ ' + resultado.toString().replace('.', ',');
	}

	$(document).on("click", ".edit-orcamento", function () {
		$("#id_prod_orcamento").val($(this).data("id"));
		$("#quantidade").val($(this).data("quantidade"));
	});

	// Ao clicar para escolher um tipo de pagamento
	function ativaInformacoesPagamento() {
		var id_categoria_pagamento = $('#tipopagamento option:selected').attr('id_categoria');
		var id_cadastro = $('#id_cadastro').val();
		if (id_categoria_pagamento == 9 && !id_cadastro) {
			alert("Erro! Cliente não informado para venda no crediário.");
			$('#modal_pagamentos').modal('hide');
		} else {
			// $('.valores_pagamento').removeClass("ocultar"); mudamos pois estava tirando a seleção do tipo de pagamento
			// $('.valores_pagamento').addClass("mostrar");

			if (id_categoria_pagamento == 4 || id_categoria_pagamento == 9) {
				$('#data_boleto_modal').show();
				$('#span_data_boleto_modal').show();
			}
			else {
				$('#data_boleto_modal').hide();
				$('#span_data_boleto_modal').hide();
			}

			var valorPagarPDV = obterValorAPagarPDV();
			$('#valor_pago_modal').val(valorPagarPDV);
			$('#valor_pagar_modal_pgto').val(valorPagarPDV);
			$('#valor_pago_modal').focus().select();
		}
	}

	if ($('.adicionar_pagamento_pdv').length > 0) {
		$('.adicionar_pagamento_pdv').click(function () {
			var valor_pago_pdv = $('#valor_pago_modal').val();
			let valueTipoPagamento = $('#tipopagamento')[0]
			let dataPrimeiroPagamento = document.getElementById("data_boleto_modal")

			if (dataPrimeiroPagamento.value == "" && dataPrimeiroPagamento.style.display == "block") {
				alert("Obrigatório informar a data do primeiro pagamento!")
			} else {
				if (valor_pago_pdv.replace('R$ ', '').replace('.', '').replace(',', '.') <= 0) {
					alert("Pagamento deve ser maior que 0 (zero)");
				} else if (valueTipoPagamento.value == '') {
					alert("Você deve selecionar um tipo de pagamento");
				} else {
					processarPagamentoVendaPDV(valor_pago_pdv);
					$('#modal_pagamentos').modal('hide');
				}
			}
		});
	}

	if ($('.adicionar_pagamento_pdv').length > 0) {
		$('.adicionar_pagamento_pdv').click(function () {

		})
	}

	if ($('.produto_aberto').length > 0) {
		$('.produto_aberto').dblclick(function () {
			let id_cadastro_vendas = $(this).attr('id');
			let id_venda = $(this).attr('id_venda');
			let informacoes = $(this).attr('informacoes');
			let valores_produto = informacoes.split('#');
			$('#nome_produto_aberto').val(valores_produto[0]);
			$('#valor_produto_aberto').val(valores_produto[1]);
			$('#quantidade_produto_aberto').val(valores_produto[2]);
			$('#desconto_produto_aberto').val(valores_produto[3]);
			$('#acrescimo_produto_aberto').val(valores_produto[4]);
			$('#total_produto_aberto').val(valores_produto[5]);
			$('#id_venda_aberto').val(id_venda);
			$('#id_cadastro_vendas_aberto').val(id_cadastro_vendas);
			$("#editar-produto-aberto").modal('show');
		});
	}

	function alterarValorProdutoVendaAberto() {
		let valor_produto_aberto = $('#valor_produto_aberto').val();
		let quantidade_produto_aberto = $('#quantidade_produto_aberto').val();
		let desconto_produto_aberto = $('#desconto_produto_aberto').val();
		let acrescimo_produto_aberto = $('#acrescimo_produto_aberto').val();

		valor_produto_aberto = parseFloat(valor_produto_aberto.replace('R$ ', '').replace('.', '').replace(',', '.'));
		quantidade_produto_aberto = parseFloat(quantidade_produto_aberto.replace('.', '').replace(',', '.'));
		desconto_produto_aberto = parseFloat(desconto_produto_aberto.replace('R$ ', '').replace('.', '').replace(',', '.'));
		acrescimo_produto_aberto = parseFloat(acrescimo_produto_aberto.replace('R$ ', '').replace('.', '').replace(',', '.'));

		if (isNaN(valor_produto_aberto)) valor_produto_aberto = 0;
		if (isNaN(quantidade_produto_aberto)) quantidade_produto_aberto = 0;
		if (isNaN(desconto_produto_aberto)) desconto_produto_aberto = 0;
		if (isNaN(acrescimo_produto_aberto)) acrescimo_produto_aberto = 0;

		let novo_valor_total = (valor_produto_aberto * quantidade_produto_aberto) - desconto_produto_aberto + acrescimo_produto_aberto;
		novo_valor_total = novo_valor_total.toFixed(2);
		novo_valor_total = 'R$ ' + novo_valor_total;
		novo_valor_total = novo_valor_total.replace('.', ',');
		$('#total_produto_aberto').val(novo_valor_total);
	}

	$('#valor_produto_aberto').keyup(function (event) {
		alterarValorProdutoVendaAberto();
	});
	$('#quantidade_produto_aberto').keyup(function (event) {
		alterarValorProdutoVendaAberto();
	});
	$('#desconto_produto_aberto').keyup(function (event) {
		let valor_produto_aberto = $('#valor_produto_aberto').val();
		let quantidade_produto_aberto = $('#quantidade_produto_aberto').val();
		let desconto_produto_aberto = $('#desconto_produto_aberto').val();
		let acrescimo_produto_aberto = $('#acrescimo_produto_aberto').val();
		valor_produto_aberto = parseFloat(valor_produto_aberto.replace('R$ ', '').replace('.', '').replace(',', '.'));
		quantidade_produto_aberto = parseFloat(quantidade_produto_aberto.replace('.', '').replace(',', '.'));
		desconto_produto_aberto = parseFloat(desconto_produto_aberto.replace('R$ ', '').replace('.', '').replace(',', '.'));
		acrescimo_produto_aberto = parseFloat(acrescimo_produto_aberto.replace('R$ ', '').replace('.', '').replace(',', '.'));

		if (isNaN(valor_produto_aberto)) valor_produto_aberto = 0;
		if (isNaN(quantidade_produto_aberto)) quantidade_produto_aberto = 0;
		if (isNaN(desconto_produto_aberto)) desconto_produto_aberto = 0;
		if (isNaN(acrescimo_produto_aberto)) acrescimo_produto_aberto = 0;

		if (desconto_produto_aberto > ((valor_produto_aberto * quantidade_produto_aberto) + acrescimo_produto_aberto)) {
			$('#desconto_produto_aberto').val('R$ 0,00');
			alert("Erro! Valor do desconto não pode ser maior que o valor final do produto");
		}
		alterarValorProdutoVendaAberto();

	});
	$('#acrescimo_produto_aberto').keyup(function (event) {
		alterarValorProdutoVendaAberto();
	});

	$('#valor_pago_modal').keyup(function (event) {
		if (event.keyCode == 13) {
			var valor_pago_pdv = $('#valor_pago_modal').val();
			if (valor_pago_pdv.replace('R$ ', '').replace('.', '').replace(',', '.') <= 0) {
				alert("Pagamento deve ser maior que 0 (zero)");
			} else {
				processarPagamentoVendaPDV(valor_pago_pdv);
				$('#modal_pagamentos').modal('hide');
			}
		}
	});

	//Ao escolher um tipo de pagamento o produto troca
	function atualizarValoresPDV(atualizarAVista) {

		var aux = 0;
		var quantidade_pdv = []; //array de quantidades de produtos no formato 9,999.999 (milhar com virgula e decimal com ponto)
		$(".quant_venda").each(function () {
			var qtde = $(".quant_venda")[aux].value;
			qtde = qtde.replace(',', '');
			quantidade_pdv.push(qtde);
			aux++;
		});

		aux = 0;
		var id_produto = []; //array de id de produtos no formato 123
		$(".id_produto").each(function () {
			id_produto.push($(".id_produto")[aux].value);
			aux++;
		});

		aux = 0;
		var tabelas = []; //array de id de tabelas de preco no formato 123
		$(".tabelas").each(function () {
			tabelas.push($(".tabelas")[aux].value);
			aux++;
		});

		aux = 0;
		var valor_produto = []; //array de valores de produtos no formato R$ 10,00
		var valor_avista = []; //array de valores de produtos à vista no formato 10.00
		var valor_normal = []; //array de valores de produtos normal no formato 10.00
		$(".valor").each(function () {
			valor_produto.push($(".valor")[aux].value);
			valor_avista.push($(".valor")[aux].getAttribute("valor_avista"));
			valor_normal.push($(".valor")[aux].getAttribute("valor_normal"));
			aux++;
		});

		aux = 0;
		var estoque_produto = [];
		$(".estoque_produto_pdv").each(function () {
			estoque_produto.push($(".estoque_produto_pdv")[aux].value);
			aux++;
		});

		aux = 0;
		var nome_produto = [];
		$(".nome_produto_pdv").each(function () {
			nome_produto.push($(".nome_produto_pdv")[aux].value);
			aux++;
		});

		$("#tabela_produtos").empty();

		for (var indice_produto = 0; indice_produto < id_produto.length; indice_produto++) {

			var id_produto_atualizar = id_produto[indice_produto];
			var id_tabela_atualizar = tabelas[indice_produto];
			var quantidade_atualizar = parseFloat(quantidade_pdv[indice_produto]);
			var valor_lista_atual = valor_produto[indice_produto];
			valor_lista_atual = valor_lista_atual.replace('R$ ', '');
			valor_lista_atual = valor_lista_atual.replace('.', '');
			valor_lista_atual = valor_lista_atual.replace(',', '.');
			valor_lista_atual = parseFloat(valor_lista_atual);
			var valor_venda_avista = parseFloat(valor_avista[indice_produto]);
			var valor_venda_normal = parseFloat(valor_normal[indice_produto]);

			var valor_pagar_produto = 0;
			if ((valor_lista_atual != parseFloat(valor_avista[indice_produto])) && (valor_lista_atual != parseFloat(valor_normal[indice_produto]))) {
				valor_pagar_produto = valor_lista_atual;
			} else if ((atualizarAVista == 1) && (parseFloat(valor_avista[indice_produto]) > 0)) {
				valor_pagar_produto = parseFloat(valor_avista[indice_produto]);
			} else {
				valor_pagar_produto = parseFloat(valor_normal[indice_produto]);
			}

			var idProduto = id_produto_atualizar;
			var nomeProd = nome_produto[indice_produto];
			var valorProd = valor_pagar_produto;
			var estoqueProd = estoque_produto[indice_produto];
			var quantidade = parseFloat(quantidade_atualizar);

			var total = valorProd * quantidade;

			quantidade = quantidade.toFixed(3);
			var valor_total = total.toFixed(2);
			total = valor_total;
			valor_total = valor_total.toString();
			valor_total = 'R$ ' + valor_total.replace('.', ',');

			let modalActive = $('#modal_alterar_valor_produto_venda').val() == 1 ? 1 : 0;

			var table = $('#tabela_produtos');
			table.prepend(
				`<tr style="border-collapse: collapse;">
					<td><span class="font-md">${nomeProd}</span></td>
					<td><span class="font-md"></span></td>
					<td><span class="font-md">${estoqueProd}</span></td>
					<td>
						<span class="bold theme-font">
							<input type="text" class="form-control form-filter input-sm quant_venda decimal" name="quantidade[]" value="${quantidade}" id="quantidade_produto">
						</span>
					</td>
					<td>
						<span class="bold theme-font">
							<input type="text" class="form-control form-filter input-sm moeda valor" name="valor_venda_tabela[]" valor_avista="${valor_venda_avista}" valor_normal="${valor_venda_normal}" value="${floatParaReal(valorProd)}" id="vlr_venda_produto" style="width: 90px">
						</span>
					</td>
					<td><span class="bold theme-font font-md valor_total">${valor_total}<span></td>
					<td>
						<a href="javascript:void(0);" class="btn btn_alterar_valor_produto_venda" title="Alterar valor deste produto?" style="background-color: #EECB00; color: #675800">
							<i class="fa fa-dollar"></i>
						</a>
						<a href="javascript:void(0);" class="btn red remover_produto_venda" title="Deseja remover este produto?">
							<i class="fa fa-times"></i>
						</a>
					</td>
					<input name="nome_produto_pdv[]" type="hidden" class="nome_produto_pdv" value="`+ nomeProd + `" />
					<input name="estoque_produto_pdv[]" type="hidden" class="estoque_produto_pdv" value="`+ estoqueProd + `" />
					<input name="id_produto[]" type="hidden" class="id_produto" value="`+ idProduto + `" />
					<input name="tabelas[]" type="hidden" class="tabelas" value="`+ id_tabela_atualizar + `" />
					<input type="hidden" class="total" value="`+ total + `" />
					<input type="hidden" class="estoque" value="`+ estoqueProd + `" />
				</tr>`
			);

			const styles = {
				"pointer-events": "none",
				"background-color": "#eaeaea"
			}

			if (modalActive === 1) {
				$('#vlr_venda_produto').css(styles);
			}

			if (modalActive === 0) $('.btn_alterar_valor_produto_venda').addClass('hidden');

			$('.quant_venda').maskMoney({ decimal: '.', precision: 3, symbolStay: false, allowNegative: false });
			$('#vlr_venda_produto').maskMoney({ symbol: 'R$ ', thousands: '.', decimal: ',', precision: 2, symbolStay: true, allowNegative: true });

			$('.todos_produtos').focus();

			var soma = 0;
			$('.total').each(function (indice, item) {
				var i = $(item).val();
				var v = parseFloat(i);
				vlr = v.toFixed(2);
				if (!isNaN(v)) {
					soma += parseFloat(vlr);
				}
			});
			soma = soma.toFixed(2);

			$("#valor").val(soma);
			var resultado = soma.toString();
			resultado = 'R$ ' + resultado.replace('.', ',');
			$("#valor2").text(resultado);
			$('#valor_total_modal').val(resultado);

			var desconto = $('#valor_desconto_modal').val();
			var d = desconto.replace('.', '');
			d = d.replace(',', '.');
			d = d.replace('R$ ', '');
			d = parseFloat(d);
			if (isNaN(d)) {
				d = 0;
			}

			if (d > 0) {
				$("#valor_desconto_modal").trigger({ type: 'keyup', which: 13, keyCode: 13 });
			}

			var acrescimo = $("#valor_acrescimo_modal").val();
			var a = acrescimo.replace('.', '');
			a = a.replace(',', '.');
			a = a.replace('R$ ', '');
			a = parseFloat(a);
			if (isNaN(a)) {
				a = 0;
			}

			var valor_pagar = soma + a - d;
			valor_pagar = valor_pagar.toFixed(2);
			valor_pagar = valor_pagar.toString();
			valor_pagar = valor_pagar.replace('.', ',');
			$('#valor_pagar').text('R$ ' + valor_pagar);

			var valor = $("#valor").val();
			var v = parseFloat(valor);
			if (isNaN(v)) {
				v = 0;
			}

			var desconto = $('#valor_desconto_modal').val();
			var d = desconto.replace('.', '');
			d = d.replace(',', '.');
			d = d.replace('R$ ', '');
			d = parseFloat(d);
			if (isNaN(d)) {
				d = 0;
			}
			var soma = 0;
			$('.valor_pago').each(function (indice, item) {
				var i = $(item).val();
				var p = parseFloat(i);
				if (!isNaN(p)) {
					soma += p;
				}
			});
			var soma_dinheiro = 0;
			$('.dinheiro').each(function (indice, item) {
				var i = $(item).val();
				var p = parseFloat(i);
				if (!isNaN(p)) {
					soma_dinheiro += p;
				}
			});

			var valor_pagar = v + a - d - soma;
			if (valor_pagar < 0) {
				valor_pagar = 0;
			}
			var resultado = valor_pagar.toFixed(2);
			resultado = resultado.toString();
			resultado = 'R$ ' + resultado.replace('.', ',');
			$("#valor_pagar").text(resultado);
			$('.valor_pago_venda').val(resultado);
			$('#valor_pago_modal').val(resultado);
			$('#valor_pagar_modal_pgto').val(resultado);
			$('#show_valor_pagar_modal').val(resultado);

			//coloca o foco no codigo de barras
			$('.barcode').focus().select();

		}

	}

	//Ao clicar no botao "adicionar pagamento"
	function processarPagamentoVendaPDV(valor_pago_digitado) {

		var id_pagamento = $('#tipopagamento option:selected').val();
		var pagamento = $('#tipopagamento option:selected').text().trim();
		var id_categoria = $('#tipopagamento option:selected').attr('id_categoria');
		var avista = $(' #tipopagamento option:selected').attr('avista');
		var parcela = $('#parcelas_modal').val();
		var pagamentosAVista = 1;

		$('.pagamento_avista').each(function (indice, item) {
			var i = $(item).val();
			var p = parseInt(i);
			if (!isNaN(p)) {
				pagamentosAVista *= p;
			} else {
				pagamentosAVista = 0;
			}
		});

		if (pagamentosAVista == 1 && avista == 1) {
			/* Atualizar os valores do PDV sobre os valores à vista */
			atualizarValoresPDV(1);
		} else {
			/* Atualizar os valores do PDV sobre os valores normais */
			atualizarValoresPDV(0);
		}

		if (id_categoria == 4 || id_categoria == 9) {
			let campoData = $('#data_boleto_modal');
			campoData.show();
		}

		if (parcela === "" || parcelas === 0)
			parcela = 1;

		var valor = $("#valor2").text().replace('R$ ', '').replace('.', '').replace(',', '.');
		var v = parseFloat(valor);
		if (isNaN(v)) {
			v = 0;
		}

		var valor_pago = valor_pago_digitado;
		if (valor_pago.replace('R$ ', '').replace('.', '').replace(',', '.') == 0 || valor_pago.replace('R$ ', '').replace('.', '').replace(',', '.') == "") {
			valor_pago = $("#valor_pagar").text();
		}

		var vp = valor_pago_digitado;
		vp = vp.replace('R$ ', '');
		vp = vp.replace('.', '');
		vp = vp.replace(',', '.');
		vp = parseFloat(vp);
		if (isNaN(vp)) {
			vp = 0;
		}

		// var acrescimo = $("#valor_acrescimo_rapida").val();
		var acrescimo = $('#valor_acrescimo_modal').val();
		var a = acrescimo.replace('.', '');
		a = a.replace(',', '.');
		a = a.replace('R$ ', '');
		a = parseFloat(a);
		if (isNaN(a)) {
			a = 0;
		}
		var valor_acrescimo = a.toFixed(2);
		valor_acrescimo = 'R$ ' + valor_acrescimo.toString().replace('.', ',');
		$('#valor_acrescimo_pdv').text(valor_acrescimo);

		var desconto = $("#valor_desconto_modal").val();
		var d = desconto.replace('.', '');
		d = d.replace(',', '.');
		d = d.replace('R$ ', '');
		d = parseFloat(d);
		if (isNaN(d)) {
			d = 0;
		}
		var valor_desconto = d.toFixed(2);
		valor_desconto = 'R$ ' + valor_desconto.toString().replace('.', ',');
		$('#valor_desconto_pdv').text(valor_desconto);

		if (vp <= 0 && (v + a - d > 0)) {
			$('#valor_pago_modal').focus().select();
			return false;
		}

		var dinheiro = 0;
		if (id_categoria == '1') {
			dinheiro = vp;
		}

		var data_boleto = $('#data_boleto_modal').val();

		if (!data_boleto) {
			const agora = new Date();
			let dia = agora.getDate();
			let mes = ((agora.getMonth() + 1) > 9) ? agora.getMonth() + 1 : '0' + (agora.getMonth() + 1);
			let ano = agora.getFullYear();
			data_boleto = dia + '/' + mes + '/' + ano;
		}

		var $el = $("#tabela_pagamentos");
		$el.prepend(`
			<tr>
				<td><span class="font-md">${pagamento}</span></td>
				<td><span class="font-md">${parcela}</span></td>
				<td><span class="bold theme-font font-md">`+ valor_pago + `<span></td>
				<td><a href="javascript:void(0);" class="btn red remover_pagamento" title="Deseja remover este pagamento?"><i class="fa fa-times"></i></a></td>
				<input type="hidden" name="id_pagamento[]" class="id_pagamento" value="${id_pagamento}" />
				<input type="hidden" name="dinheiro[]" class="dinheiro" value="`+ dinheiro + `" />
				<input type="hidden" name="parcela[]" class="parcela" value="${parcela}" />
				<input type="hidden" name="valor_pago[]" class="valor_pago" value="`+ vp + `" />
				<input type="hidden" name="pagamento_avista[]" class="pagamento_avista" value="`+ avista + `" />
				<input type="hidden" name="data_boleto_separado[]" class="data_boleto_separado" value="`+ data_boleto + `" />
			</tr>`
		).find("tr").first().hide();
		$el.find("tr").first().fadeIn();

		var soma = 0; //soma dos valores de todos os pagamentos no formato 10.00
		$('.valor_pago').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma += p;
			}
		});

		var valor_pago_pdv = soma.toFixed(2); //formatação em moeda (R$ 10,00) dos valores pagos
		valor_pago_pdv = 'R$ ' + valor_pago_pdv.toString().replace('.', ',');
		$('#valor_pago_pdv').text(valor_pago_pdv);

		var soma_dinheiro = 0;  //soma dos valores de todos os pagamentos em dinheiro no formato 10.00
		$('.dinheiro').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma_dinheiro += p;
			}
		});

		var valor_pagar = v + a - d - soma;
		if (valor_pagar < 0) {
			valor_pagar = 0;
		}
		var resultado = valor_pagar.toFixed(2);
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#valor_pagar").text(resultado);

		$('.valor_pago_venda').val(resultado);
		$('#valor_pago_modal').val(resultado);
		$('#valor_pagar_modal_pgto').val(resultado);

		var soma_restante = soma - soma_dinheiro;
		var total_pagar_dinheiro = v + a - d - soma_restante;
		if (total_pagar_dinheiro < 0) {
			total_pagar_dinheiro = 0;
		}
		var troco = soma_dinheiro - total_pagar_dinheiro;
		if (troco < 0) {
			troco = 0;
		}
		resultado = troco.toFixed(2);
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#troco").text(resultado);

		if (troco > soma_dinheiro) {
			resultado = soma_dinheiro.toFixed(2);
			resultado = 'R$ ' + resultado.replace('.', ',');
			$("#troco").text(resultado);
			alert('O valor do TROCO deve ser MENOR que o total pago em DINHEIRO.');
			return false;
		}

		//return false;

	}

	//Modal adicionar pagamento ao clicar
	$('.modal_adicionar_pagamento').click(function (event) {

		var id_produto = $('.id_produto').val();
		var valor_pagar_pdv = $('#valor_pagar').text();
		valor_pagar_pdv = valor_pagar_pdv.replace('R$ ', '').replace('.', '').replace(',', '.');
		valor_pagar_pdv = parseFloat(valor_pagar_pdv);

		if (!id_produto) {
			alert('Favor selecionar um produto para o pagamento.');
			return false;
		} else if (valor_pagar_pdv <= 0) {
			alert('Não existe pagamento disponível para esta venda.');
			return false;
		} else {

			shortcut.add("ALT+S", function () { $('#valor_desconto_porcentagem_modal').focus(); });
			shortcut.add("ALT+D", function () { $('#valor_desconto_modal').focus(); });
			shortcut.add("ALT+A", function () { $('#valor_acrescimo_modal').focus(); });
			shortcut.add("ALT+V", function () { $('#valor_pago_modal').focus(); });
			shortcut.add("ALT+P", function () { $('#parcelas_modal').focus(); });
			shortcut.add("ALT+T", function () { $('#data_boleto_modal').focus(); });
			shortcut.add("ALT+F", function () { $('#tipopagamento').focus(); });

			// $('.valores_pagamento').removeClass("mostrar");
			// $('.valores_pagamento').addClass("ocultar");
			$('#valor_pago_modal').focus().select();

			$('#tipopagamento').keyup(function (event) {
				if (event.keyCode == 13) {
					ativaInformacoesPagamento();
				}
			}).click(function () {
				ativaInformacoesPagamento();
			});

		}
	});


	//Valor de venda do produto - VENDAS > NOVA VENDA
	$(document).on('keyup', '#vlr_venda_produto', function () {

		var produto = $(this).parents("tr");
		var valor = produto.find(".valor").val();
		var quant_venda = produto.find(".quant_venda").val();
		var val_pagar = $("#val_pagar").html();

		v = realParaFloat(valor);
		if (v < 0) {
			alert('Valor do produto NÃO PODE ser negativo.');
			v = v * (-1);
			produto.find(".valor").val(floatParaReal(v));
		}
		if (isNaN(v)) {
			v = 0;
		}

		quant_venda = quant_venda.replace(',', '');
		q = parseFloat(quant_venda);
		if (isNaN(q) || q == 0) {
			alert('Cuidado, a quantidade do produto não pode ser zerada.');
			produto.find(".quant_venda").focus().select();
			q = 0;
		}

		if (val_pagar < 0) {
			val_pagar = 0;
			alert("O valor a pagar não pode der negativo");
		}

		var total = (v * q);
		if (((total % 1) != 0) && (!isNaN(total % 1))) {
			total.toString;
			total += '0001';
		}
		total = parseFloat(total);
		valor_total = total.toFixed(2);
		valor_total = valor_total.toString();
		valor_total = valor_total.replace('.', ',');
		produto.find(".valor_total").text('R$ ' + valor_total);
		produto.find(".total").val(total);

		var acrescimo = $("#valor_acrescimo_rapida").val();
		var a = acrescimo.replace('.', '');
		a = a.replace(',', '.');
		a = a.replace('R$ ', '');
		a = parseFloat(a);
		if (isNaN(a)) {
			a = 0;
		}

		var soma = 0;
		$('.total').each(function (indice, item) {
			var i = $(item).val();
			var v = parseFloat(i);
			vlr = v.toFixed(2);
			if (!isNaN(v)) {
				soma += parseFloat(vlr);
			}
		});

		soma = soma.toFixed(2);
		$("#valor").val(soma);
		var resultado = soma.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#valor2").text(resultado);
		$('#valor_total_modal').val(resultado);

		var desconto = $('#valor_desconto_modal').val();
		var d = desconto.replace('.', '');
		d = d.replace(',', '.');
		d = d.replace('R$ ', '');
		d = parseFloat(d);
		if (isNaN(d)) {
			d = 0;
		}

		var valor_pagar = soma + a - d;
		valor_pagar = valor_pagar.toFixed(2);
		valor_pagar = valor_pagar.toString();
		valor_pagar = valor_pagar.replace('.', ',');
		$('#valor_pagar').text('R$ ' + valor_pagar);

		var valor = $("#valor").val();
		var v = parseFloat(valor);
		if (isNaN(v)) {
			v = 0;
		}
		var desconto = $("#valor_desconto_modal").val();
		var d = desconto.replace('.', '');
		d = d.replace(',', '.');
		d = d.replace('R$ ', '');
		d = parseFloat(d);
		if (isNaN(d)) {
			d = 0;
		}

		if (d > 0) {
			$("#valor_desconto_modal").trigger({ type: 'keyup', which: 13, keyCode: 13 });
		}

		var soma = 0;
		$('.valor_pago').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma += p;
			}
		});
		var soma_dinheiro = 0;
		$('.dinheiro').each(function (indice, item) {
			var i = $(item).val();
			var p = parseFloat(i);
			if (!isNaN(p)) {
				soma_dinheiro += p;
			}
		});

		var valor_pagar = v + a - d - soma;
		if (valor_pagar < 0) {
			valor_pagar = 0;
		}
		var resultado = valor_pagar.toFixed(2);
		resultado = resultado.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#valor_pagar").text(resultado);
		$('#valor_pago_modal').val(resultado);
		$('#valor_pagar_modal_pgto').val(resultado);

		var soma_restante = soma - soma_dinheiro;
		var total_pagar_dinheiro = v + a - d - soma_restante;
		var troco = soma_dinheiro - total_pagar_dinheiro;
		if (troco < 0) {
			troco = 0;
		}
		resultado = troco.toFixed(2);
		resultado = resultado.toString();
		resultado = 'R$ ' + resultado.replace('.', ',');
		$("#troco").text(resultado);
	});

	$('#modal_produtos').on('shown.bs.modal', function () {
		$('#qtde_produto').maskMoney({ precision: 3, symbolStay: false, allowNegative: false });
		$('#qtde_produto').val('1.000')
		$('#nome_produto').focus().select();
	});


	$(document).on('keyup', '#qtde_produto', function (e) {
		if (e.which == 13 || e.keyCode == 13) {
			$('#nome_produto').focus();
		}
	});

	//Atalho F1
	function modalProdutosComF1(valor = 0) {

		$('#modal_produtos').modal();

		shortcut.add("ENTER", function () { $('.selectProd').focus(); });

		if (!$('#showProduct').empty()) $('#showProduct').removeData();
		$('#showProductInput').empty();
		$('#showQuantidadeInput').empty();

		let html_qtde = '';
		html_qtde += `<input class="form-control decimal" type="text" name="qtde_produto" id="qtde_produto" placeholder="Quant.">`;
		html_qtde += `<span class="help-block pull-left">Quantidade</span>`;
		$('#showQuantidadeInput').append(html_qtde);

		let html = '';
		let cssInput = 'style="margin-bottom: 25px"';
		let cssSpan = 'style="color: red; font-weight: bold;"';

		html += `<div ${cssInput}>`;
		html += `<input autocomplete="off" type="text" class="form-control produtos" name="nome_produto" id="nome_produto" placeholder="Digite o nome do produto, código ou código de barras" autofocus>`;
		html += `<span class="help-block pull-left">Aperte "ENTER" para focar no quadro de produtos.</span>`;
		html += `<span class="help-block pull-right infoprod" ${cssSpan}>PRODUTO NÃO ENCONTRADO NESTA TABELA.</span>`;
		html += '</div>';

		$('#showProductInput').append(html);

		$('.infoprod').hide();
		$('#nome_produto').focus();

		if (valor > 0) {
			$('#nome_produto').val(valor);
			$('#nome_produto').focus();
		}

		$('#nome_produto').on('keyup', function (e) {
			let value = $.trim($(this).val()).replace("#", "!hashtag!").replace("@", "!arroba!").replace("$", "!dollar!");
			let id_tabela = $('#id_tabela_venda').val();

			$.ajax({
				url: `webservices/listarProdutosModal.php`,
				data: 'nome_produto=' + value + '&id_tabela=' + id_tabela,
				dataType: 'json',
				success: function (data) {
					var cssSelect = 'style="height: 300px; margin-top: 40px"';
					var html1 = '';
					html1 += `<select multiple class="select2 form-control selectProd" autofocus ${cssSelect}>`;

					for (let i = 0; i < data.length; i++) {

						var id_produto = data[i].id;

						var nome = (data[i].nome);
						var valor = parseFloat(data[i].valor_venda);
						var valor_exibir = 'R$ '
						var estoque = data[i].estoque;
						var valor_avista = data[i].valor_avista;
						var cb = data[i].codigobarras;
						var codigo = data[i].codigo;
						var codigo_interno = data[i].codigo_interno;
						var unidade = data[i].unidade;
						let info_produto = unidade ? `${codigo} - ${nome} (${unidade} - ${valor_exibir}${valor}) ${codigo_interno}` : `${codigo} - ${nome} (${valor_exibir}${valor}) ${codigo_interno}`;

						html1 += `<option class="todos_produtos" value="${id_produto}" estoqueProd="${estoque}" valor_avista="${valor_avista}" valorProd="${valor}" unidade="${unidade}">`;
						html1 += ` ${info_produto} `;
						html1 += `</option>`;
					}
					html1 += `</select>`;

					if ($('#showProduct').empty()) {
						$('#showProduct').prepend(html1);
					}

					if ($('.selectProd').is(' :empty')) {
						$('.infoprod').show();
						$('#showProduct').empty();
						// $('#nome_produto').val("").focus(); // Linha de codigo que apaga o valor do campo toda vez que não tem um produto com aquele nome.
					} else $('.infoprod').hide();

					$('.selectProd').keyup(function (event) {

						var idProduto = parseFloat($('.selectProd option:selected').val());
						var nomeProd = $('.selectProd option:selected').text();
						var valorProd = parseFloat($('.selectProd option:selected').attr('valorProd'));
						var estoqueProd = $('.selectProd option:selected').attr('estoqueProd');
						var valor_avista = parseFloat($('.selectProd option:selected').attr('valor_avista'));
						var unid = ($('.selectProd option:selected').attr('unidade')) ? $('.selectProd option:selected').attr('unidade') : '';

						pagamentoAVista = false;
						controle_avista = 1;
						controle_pagamentos = 0;
						$('.pagamento_avista').each(function (indice, item) {
							var i = $(item).val();
							var p = parseInt(i);
							controle_avista *= p;
							controle_pagamentos = 1;
						});

						valor_venda_avista = valor_avista.toFixed(2);
						valor_venda_normal = valorProd.toFixed(2);

						if (controle_avista == 1 && controle_pagamentos == 1)
							pagamentoAVista = true;

						if (pagamentoAVista && valor_avista > 0) {
							valorProd = valor_avista;
						}

						var qtde_produto = $('#qtde_produto').val();
						var q = qtde_produto.replace(',', '');
						q = parseFloat(q);
						if (isNaN(q) || q == 0) {
							q = 1;
						}
						var quantidade = q.toFixed(3);

						var total = valorProd * quantidade;
						var valor_total = total.toFixed(2);

						total = valor_total;
						valor_total = valor_total.toString();
						valor_total = 'R$ ' + valor_total.replace('.', ',');

						if (event.which == 13) {

							//Este bloco não deixa um produto entrar na tabela se o registro for em branco
							if ($('.selectProd').focus() && !idProduto) {
								return false;
							}

							let modalActive = $('#modal_alterar_valor_produto_venda').val() == 1 ? 1 : 0;

							var table = $('#tabela_produtos');
							table.prepend(
								`<tr style="border-collapse: collapse;">
									<td><span class="font-md">${nomeProd}</span></td>
									<td><span class="font-md">${unid}</span></td>
									<td><span class="font-md">${estoqueProd}</span></td>
									<td>
										<span class="bold theme-font">
											<input type="text" class="form-control form-filter input-sm quant_venda decimal" name="quantidade[]" value="${quantidade}" id="quantidade_produto">
										</span>
									</td>
									<td>
										<span class="bold theme-font">
											<input type="text" class="form-control form-filter input-sm moeda valor" name="valor_venda_tabela[]" valor_avista="${valor_venda_avista}" valor_normal="${valor_venda_normal}" value="${floatParaReal(valorProd)}" id="vlr_venda_produto" style="width: 90px">
										</span>
									</td>
									<td><span class="bold theme-font font-md valor_total">${valor_total}<span></td>
									<td>
										<a href="javascript:void(0);" class="btn btn_alterar_valor_produto_venda" title="Alterar valor deste produto?" style="background-color: #EECB00; color: #675800">
											<i class="fa fa-dollar"></i>
										</a>
										<a href="javascript:void(0);" class="btn red remover_produto_venda" title="Deseja remover este produto?">
											<i class="fa fa-times"></i>
										</a>
									</td>
									<input name="nome_produto_pdv[]" type="hidden" class="nome_produto_pdv" value="`+ nomeProd + `" />
									<input name="estoque_produto_pdv[]" type="hidden" class="estoque_produto_pdv" value="`+ estoqueProd + `" />
									<input name="id_produto[]" type="hidden" class="id_produto" value="`+ idProduto + `" />
									<input type="hidden" class="total" value="`+ total + `" />
									<input type="hidden" class="estoque" value="`+ estoqueProd + `" />
									<input name="tabelas[]" type="hidden" class="tabelas" value="`+ id_tabela + `" />
								</tr>`
							).find("tr").first().hide();

							const styles = {
								"pointer-events": "none",
								"background-color": "#eaeaea"
							}

							if (modalActive === 1) {
								$('#vlr_venda_produto').css(styles);
							}

							if (modalActive === 0) $('.btn_alterar_valor_produto_venda').addClass('hidden');
							table.find("tr").first().fadeIn();

							$('.quant_venda').maskMoney({ decimal: '.', precision: 3, symbolStay: false, allowNegative: false });
							$('#vlr_venda_produto').maskMoney({ symbol: 'R$ ', thousands: '.', decimal: ',', precision: 2, symbolStay: true, allowNegative: true });

							$('.todos_produtos').focus();

							var soma = 0;
							$('.total').each(function (indice, item) {
								var i = $(item).val();
								var v = parseFloat(i);
								vlr = v.toFixed(2);
								if (!isNaN(v)) {
									soma += parseFloat(vlr);
								}
							});
							soma = soma.toFixed(2);

							$("#valor").val(soma);
							var resultado = soma.toString();
							resultado = 'R$ ' + resultado.replace('.', ',');
							$("#valor2").text(resultado);
							$('#valor_total_modal').val(resultado);


							var desconto = $('#valor_desconto_modal').val();
							var d = desconto.replace('.', '');
							d = d.replace(',', '.');
							d = d.replace('R$ ', '');
							d = parseFloat(d);
							if (isNaN(d)) {
								d = 0;
							}

							if (d > 0) {
								$("#valor_desconto_modal").trigger({ type: 'keyup', which: 13, keyCode: 13 });
							}

							var acrescimo = $("#valor_acrescimo_modal").val();
							var a = acrescimo.replace('.', '');
							a = a.replace(',', '.');
							a = a.replace('R$ ', '');
							a = parseFloat(a);
							if (isNaN(a)) {
								a = 0;
							}

							var valor_pagar = soma + a - d;
							valor_pagar = valor_pagar.toFixed(2);
							valor_pagar = valor_pagar.toString();
							valor_pagar = valor_pagar.replace('.', ',');
							$('#valor_pagar').text('R$ ' + valor_pagar);

							var valor = $("#valor").val();
							var v = parseFloat(valor);
							if (isNaN(v)) {
								v = 0;
							}

							var desconto = $('#valor_desconto_modal').val();
							var d = desconto.replace('.', '');
							d = d.replace(',', '.');
							d = d.replace('R$ ', '');
							d = parseFloat(d);
							if (isNaN(d)) {
								d = 0;
							}
							var soma = 0;
							$('.valor_pago').each(function (indice, item) {
								var i = $(item).val();
								var p = parseFloat(i);
								if (!isNaN(p)) {
									soma += p;
								}
							});
							var soma_dinheiro = 0;
							$('.dinheiro').each(function (indice, item) {
								var i = $(item).val();
								var p = parseFloat(i);
								if (!isNaN(p)) {
									soma_dinheiro += p;
								}
							});

							var valor_pagar = v + a - d - soma;
							if (valor_pagar < 0) {
								valor_pagar = 0;
							}
							var resultado = valor_pagar.toFixed(2);
							resultado = resultado.toString();
							resultado = 'R$ ' + resultado.replace('.', ',');
							$("#valor_pagar").text(resultado);

							$('.valor_pago_venda').val(resultado);
							$('#valor_pago_modal').val(resultado);
							$('#valor_pagar_modal_pgto').val(resultado);
							$('#show_valor_pagar_modal').val(resultado);

							var soma_restante = soma - soma_dinheiro;
							var total_pagar_dinheiro = v + a - d - soma_restante;
							var troco = soma_dinheiro - total_pagar_dinheiro;

							if (troco < 0) {
								troco = 0;
							}

							resultado = troco.toFixed(2);
							resultado = resultado.toString();
							resultado = 'R$ ' + resultado.replace('.', ',');
							$("#troco").text(resultado);
						}

					}).click(function () {
						var idProduto = parseFloat($('.selectProd option:selected').val());

						if (isNaN(idProduto)) idProduto = 0;

						var nomeProd = $('.selectProd option:selected').text();
						var valorProd = parseFloat($('.selectProd option:selected').attr('valorProd'));
						var estoqueProd = $('.selectProd option:selected').attr('estoqueProd');
						var valor_avista = parseFloat($('.selectProd option:selected').attr('valor_avista'));
						var unid = ($('.selectProd option:selected').attr('unidade')) ? $('.selectProd option:selected').attr('unidade') : '';

						pagamentoAVista = false;
						controle_avista = 1;
						controle_pagamentos = 0;
						$('.pagamento_avista').each(function (indice, item) {
							var i = $(item).val();
							var p = parseInt(i);
							controle_avista *= p;
							controle_pagamentos = 1;
						});

						valor_venda_avista = valor_avista.toFixed(2);
						valor_venda_normal = valorProd.toFixed(2);

						if (controle_avista == 1 && controle_pagamentos == 1)
							pagamentoAVista = true;

						if (pagamentoAVista && valor_avista > 0) {
							valorProd = valor_avista;
						}

						var qtde_produto = $('#qtde_produto').val();
						var q = qtde_produto.replace(',', '');
						q = parseFloat(q);
						if (isNaN(q) || q == 0) {
							q = 1;
						}
						var quantidade = q.toFixed(3);

						var total = valorProd * quantidade;
						var valor_total = total.toFixed(2);

						total = valor_total;
						valor_total = valor_total.toString();
						valor_total = 'R$ ' + valor_total.replace('.', ',');

						let modalActive = $('#modal_alterar_valor_produto_venda').val() == 1 ? 1 : 0;

						var table = $('#tabela_produtos');

						if (idProduto > 0) {
							table.prepend(
								`<tr style="border-collapse: collapse;">
									<td><span class="font-md">${nomeProd}</span></td>
									<td><span class="font-md">${unid}</span></td>
									<td><span class="font-md">${estoqueProd}</span></td>
									<td>
										<span class="bold theme-font">
										<input type="text" class="form-control form-filter input-sm quant_venda decimal" name="quantidade[]" value="${quantidade}" id="quantidade_produto"></span>
									</td>
									<td>
										<span class="bold theme-font">
											<input type="text" class="form-control form-filter input-sm moeda valor input-valor-normal" name="valor_venda_tabela[]" valor_avista="${valor_venda_avista}" valor_normal="${valor_venda_normal}" value="${floatParaReal(valorProd)}" id="vlr_venda_produto" style="width: 90px">
										</span>
									</td>
									<td><span class="bold theme-font font-md valor_total">${valor_total}<span></td>
									<td>
										<a href="javascript:void(0);" class="btn btn_alterar_valor_produto_venda" title="Alterar valor deste produto?" style="background-color: #EECB00; color: #675800">
											<i class="fa fa-dollar"></i>
										</a>
										<a href="javascript:void(0);" class="btn red remover_produto_venda" title="Deseja remover este produto?">
										<i class="fa fa-times"></i></a>

									</td>
									<input name="nome_produto_pdv[]" type="hidden" class="nome_produto_pdv" value="`+ nomeProd + `" />
									<input name="estoque_produto_pdv[]" type="hidden" class="estoque_produto_pdv" value="`+ estoqueProd + `" />
									<input name="id_produto[]" type="hidden" class="id_produto" value="`+ idProduto + `">
									<input type="hidden" class="total" value="`+ total + `" />
									<input type="hidden" class="estoque" value="`+ estoqueProd + `">
									<input name="tabelas[]" type="hidden" class="tabelas" value="`+ id_tabela + `">
								</tr>`
							).find("tr").first().hide();

							if (modalActive == 1) {
								let inputValorNormal = document.querySelector(".input-valor-normal")
								inputValorNormal.disabled = true
							}

							const styles = {
								"pointer-events": "none",
								"background-color": "#eaeaea"
							}

							if (modalActive === 1) {
								$('#vlr_venda_produto').css(styles);
							}

							if (modalActive === 0) $('.btn_alterar_valor_produto_venda').addClass('hidden');

							table.find("tr").first().fadeIn();
						}

						$('.quant_venda').maskMoney({ decimal: '.', precision: 3, symbolStay: false, allowNegative: false });
						$('#vlr_venda_produto').maskMoney({ symbol: 'R$ ', thousands: '.', decimal: ',', precision: 2, symbolStay: true, allowNegative: true });

						var soma = 0;
						$('.total').each(function (indice, item) {
							var i = $(item).val();
							var v = parseFloat(i);
							vlr = v.toFixed(2);
							if (!isNaN(v)) {
								soma += parseFloat(vlr);
							}
						});
						soma = soma.toFixed(2);

						$("#valor").val(soma);
						var resultado = soma.toString();
						resultado = 'R$ ' + resultado.replace('.', ',');
						$("#valor2").text(resultado);
						$('#valor_total_modal').val(resultado);

						var desconto = $("#valor_desconto_modal").val();
						var d = desconto.replace('.', '');
						d = d.replace(',', '.');
						d = d.replace('R$ ', '');
						d = parseFloat(d);
						if (isNaN(d)) {
							d = 0;
						}

						if (d > 0) {
							$("#valor_desconto_modal").trigger({ type: 'keyup', which: 13, keyCode: 13 });
						}

						var acrescimo = $("#valor_acrescimo_modal").val();
						var a = acrescimo.replace('.', '');
						a = a.replace(',', '.');
						a = a.replace('R$ ', '');
						a = parseFloat(a);
						if (isNaN(a)) {
							a = 0;
						}

						var valor_pagar = soma + a - d;
						valor_pagar = valor_pagar.toFixed(2);
						valor_pagar = valor_pagar.toString();
						valor_pagar = valor_pagar.replace('.', ',');
						$('#valor_pagar').text('R$ ' + valor_pagar);

						$('.valor_pago_venda').val(resultado);
						$('#valor_pago_modal').val(resultado);
						$('#valor_pagar_modal_pgto').val(resultado);
						$('#show_valor_pagar_modal').val(resultado);

						var valor = $("#valor").val();
						var v = parseFloat(valor);
						if (isNaN(v)) {
							v = 0;
						}

						var desconto = $('#valor_desconto_modal').val();
						var d = desconto.replace('.', '');
						d = d.replace(',', '.');
						d = d.replace('R$ ', '');
						d = parseFloat(d);
						if (isNaN(d)) {
							d = 0;
						}
						var soma = 0;
						$('.valor_pago').each(function (indice, item) {
							var i = $(item).val();
							var p = parseFloat(i);
							if (!isNaN(p)) {
								soma += p;
							}
						});
						var soma_dinheiro = 0;
						$('.dinheiro').each(function (indice, item) {
							var i = $(item).val();
							var p = parseFloat(i);
							if (!isNaN(p)) {
								soma_dinheiro += p;
							}
						});

						var valor_pagar = v + a - d - soma;
						if (valor_pagar < 0) {
							valor_pagar = 0;
						}
						var resultado = valor_pagar.toFixed(2);
						resultado = resultado.toString();
						resultado = 'R$ ' + resultado.replace('.', ',');
						$("#valor_pagar").text(resultado);
						$('#valor_pago_modal').val(resultado);
						$('#valor_pagar_modal_pgto').val(resultado);

						var soma_restante = soma - soma_dinheiro;
						var total_pagar_dinheiro = v + a - d - soma_restante;
						var troco = soma_dinheiro - total_pagar_dinheiro;

						if (troco < 0) {
							troco = 0;
						}

						resultado = troco.toFixed(2);
						resultado = resultado.toString();
						resultado = 'R$ ' + resultado.replace('.', ',');
						$("#troco").text(resultado);
					});
				}
			});
		});
	}

	//Atalho F1 - Mostra todos os produtos no modal - Vendas > Nova Venda
	shortcut.add("F1", function () {
		modalProdutosComF1(-1);
	});

	//Modal adicionar produto em VENDAS > NOVA VENDA
	$('.modal_adicionar_produto').click(function (event) {

		shortcut.add("ENTER", function () { $('.selectProd').focus(); });

		if (!$('#showProduct').empty()) $('#showProduct').removeData();
		$('#showProductInput').empty();
		$('#showQuantidadeInput').empty();

		let html_qtde = '';
		html_qtde += `<input class="form-control decimal" type="text" name="qtde_produto" id="qtde_produto" placeholder="Quant.">`;
		html_qtde += `<span class="help-block pull-left">Quantidade</span>`;
		$('#showQuantidadeInput').append(html_qtde);

		let html = '';
		let cssInput = 'style="margin-bottom: 25px"';
		let cssSpan = 'style="color: red; font-weight: bold;"';

		html += `<div ${cssInput}>`;
		html += `<input autocomplete="off" type="text" class="form-control produtos" name="nome_produto" id="nome_produto" placeholder="Digite o nome do produto, código ou código de barras" autofocus>`;
		html += `<span class="help-block pull-left">Aperte "ENTER" para focar no quadro de produtos.</span>`;
		html += `<span class="help-block pull-right infoprod" ${cssSpan}>PRODUTO NÃO ENCONTRADO NESTA TABELA.</span>`;
		html += '</div>';

		$('#showProductInput').append(html);

		$('.infoprod').hide();
		$('#nome_produto').focus();

		if (valor > 0) {
			$('#nome_produto').val(valor);
			$('#nome_produto').focus();
		}

		$('#nome_produto').on('keyup', function (e) {
			let value = $.trim($(this).val()).replace("#", "!hashtag!").replace("@", "!arroba!").replace("$", "!dollar!");
			let id_tabela = $('#id_tabela_venda').val();

			$.ajax({
				url: `webservices/listarProdutosModal.php`,
				data: 'nome_produto=' + value + '&id_tabela=' + id_tabela,
				dataType: 'json',

				success: function (data) {
					var cssSelect = 'style="height: 300px; margin-top: 40px"';
					var html1 = '';
					html1 += `<select multiple class="select2 form-control selectProd" autofocus ${cssSelect}>`;

					for (let i = 0; i < data.length; i++) {

						var id_produto = data[i].id;

						var nome = (data[i].nome);
						var valor = parseFloat(data[i].valor_venda);
						var valor_exibir = 'R$ '
						var estoque = data[i].estoque;
						var valor_avista = data[i].valor_avista;
						var cb = data[i].codigobarras;
						var codigo = data[i].codigo;
						var codigo_interno = data[i].codigo_interno;
						var unidade = data[i].unidade;
						let info_produto = unidade ? `${codigo} - ${nome} (${unidade} - ${valor_exibir}${valor}) ${codigo_interno}` : `${codigo} - ${nome} (${valor_exibir}${valor}) ${codigo_interno}`;

						html1 += `<option class="todos_produtos" value="${id_produto}" estoqueProd="${estoque}" valor_avista="${valor_avista}" valorProd="${valor}" unidade="${unidade}">`;
						html1 += ` ${info_produto} `;
						html1 += `</option>`;

					}
					html1 += `</select>`;

					if ($('#showProduct').empty()) {
						$('#showProduct').prepend(html1);
					}

					if ($('.selectProd').is(' :empty')) {
						$('.infoprod').show();
						$('#showProduct').empty();
					} else $('.infoprod').hide();

					$('.selectProd').keyup(function (event) {

						var idProduto = parseFloat($('.selectProd option:selected').val());
						var nomeProd = $('.selectProd option:selected').text();
						var valorProd = parseFloat($('.selectProd option:selected').attr('valorProd'));
						var estoqueProd = $('.selectProd option:selected').attr('estoqueProd');
						var valor_avista = parseFloat($('.selectProd option:selected').attr('valor_avista'));
						var unid = ($('.selectProd option:selected').attr('unidade')) ? $('.selectProd option:selected').attr('unidade') : '';

						pagamentoAVista = false;
						controle_avista = 1;
						controle_pagamentos = 0;
						$('.pagamento_avista').each(function (indice, item) {
							var i = $(item).val();
							var p = parseInt(i);
							controle_avista *= p;
							controle_pagamentos = 1;
						});

						valor_venda_avista = valor_avista.toFixed(2);
						valor_venda_normal = valorProd.toFixed(2);

						if (controle_avista == 1 && controle_pagamentos == 1)
							pagamentoAVista = true;

						if (pagamentoAVista && valor_avista > 0) {
							valorProd = valor_avista;
						}

						var qtde_produto = $('#qtde_produto').val();
						var q = qtde_produto.replace(',', '');
						q = parseFloat(q);
						if (isNaN(q) || q == 0) {
							q = 1;
						}
						var quantidade = q.toFixed(3);

						var total = valorProd * quantidade;
						var valor_total = total.toFixed(2);

						total = valor_total;
						valor_total = valor_total.toString();
						valor_total = 'R$ ' + valor_total.replace('.', ',');

						if (event.which == 13) {

							//Este bloco não deixa um produto entrar na tabela se o registro for em branco
							if ($('.selectProd').focus() && !idProduto) {
								return false;
							}

							let modalActive = $('#modal_alterar_valor_produto_venda').val() == 1 ? 1 : 0;

							var table = $('#tabela_produtos');
							table.prepend(
								`<tr style="border-collapse: collapse;">
									<td><span class="font-md">${nomeProd}</span></td>
									<td><span class="font-md">${unid}</span></td>
									<td><span class="font-md">${estoqueProd}</span></td>
									<td>
										<span class="bold theme-font">
											<input type="text" class="form-control form-filter input-sm quant_venda decimal" name="quantidade[]" value="${quantidade}" id="quantidade_produto">
										</span>
									</td>
									<td>
										<span class="bold theme-font">
											<input type="text" class="form-control form-filter input-sm moeda valor" name="valor_venda_tabela[]" valor_avista="${valor_venda_avista}" valor_normal="${valor_venda_normal}" value="${floatParaReal(valorProd)}" id="vlr_venda_produto" style="width: 90px">
										</span>
									</td>
									<td><span class="bold theme-font font-md valor_total">${valor_total}<span></td>
									<td>
										<a
											href="javascript:void(0);"
											class="btn btn_alterar_valor_produto_venda"
											title="Alterar valor deste produto?"
											style="background-color: #EECB00; color: #675800">
											<i class="fa fa-dollar"></i>
										</a>
										<a href="javascript:void(0);" class="btn red remover_produto_venda" title="Deseja remover este produto?">
											<i class="fa fa-times"></i>
										</a>
									</td>
									<input name="nome_produto_pdv[]" type="hidden" class="nome_produto_pdv" value="`+ nomeProd + `" />
									<input name="estoque_produto_pdv[]" type="hidden" class="estoque_produto_pdv" value="`+ estoqueProd + `" />
									<input name="id_produto[]" type="hidden" class="id_produto" value="`+ idProduto + `" />
									<input name="tabelas[]" type="hidden" class="tabelas" value="`+ id_tabela + `" />
									<input type="hidden" class="total" value="`+ total + `" />
									<input type="hidden" class="estoque" value="`+ estoqueProd + `" />
								</tr>`
							).find("tr").first().hide();

							const styles = {
								"pointer-events": "none",
								"background-color": "#eaeaea"
							}

							if (modalActive === 1) {
								$('#vlr_venda_produto').css(styles);
							}

							if (modalActive === 0) $('.btn_alterar_valor_produto_venda').addClass('hidden');
							table.find("tr").first().fadeIn();

							$('.quant_venda').maskMoney({ decimal: '.', precision: 3, symbolStay: false, allowNegative: false });
							$('#vlr_venda_produto').maskMoney({ symbol: 'R$ ', thousands: '.', decimal: ',', precision: 2, symbolStay: true, allowNegative: true });

							$('.todos_produtos').focus();

							var soma = 0;
							$('.total').each(function (indice, item) {
								var i = $(item).val();
								var v = parseFloat(i);
								vlr = v.toFixed(2);
								if (!isNaN(v)) {
									soma += parseFloat(vlr);
								}
							});
							soma = soma.toFixed(2);

							$("#valor").val(soma);
							var resultado = soma.toString();
							resultado = 'R$ ' + resultado.replace('.', ',');
							$("#valor2").text(resultado);
							$('#valor_total_modal').val(resultado);

							var desconto = $('#valor_desconto_modal').val();
							var d = desconto.replace('.', '');
							d = d.replace(',', '.');
							d = d.replace('R$ ', '');
							d = parseFloat(d);
							if (isNaN(d)) {
								d = 0;
							}

							if (d > 0) {
								$("#valor_desconto_modal").trigger({ type: 'keyup', which: 13, keyCode: 13 });
							}

							var acrescimo = $("#valor_acrescimo_modal").val();
							var a = acrescimo.replace('.', '');
							a = a.replace(',', '.');
							a = a.replace('R$ ', '');
							a = parseFloat(a);
							if (isNaN(a)) {
								a = 0;
							}

							var valor_pagar = soma + a - d;
							valor_pagar = valor_pagar.toFixed(2);
							valor_pagar = valor_pagar.toString();
							valor_pagar = valor_pagar.replace('.', ',');
							$('#valor_pagar').text('R$ ' + valor_pagar);

							var valor = $("#valor").val();
							var v = parseFloat(valor);
							if (isNaN(v)) {
								v = 0;
							}

							var desconto = $('#valor_desconto_modal').val();
							var d = desconto.replace('.', '');
							d = d.replace(',', '.');
							d = d.replace('R$ ', '');
							d = parseFloat(d);
							if (isNaN(d)) {
								d = 0;
							}
							var soma = 0;
							$('.valor_pago').each(function (indice, item) {
								var i = $(item).val();
								var p = parseFloat(i);
								if (!isNaN(p)) {
									soma += p;
								}
							});
							var soma_dinheiro = 0;
							$('.dinheiro').each(function (indice, item) {
								var i = $(item).val();
								var p = parseFloat(i);
								if (!isNaN(p)) {
									soma_dinheiro += p;
								}
							});

							var valor_pagar = v + a - d - soma;
							if (valor_pagar < 0) {
								valor_pagar = 0;
							}
							var resultado = valor_pagar.toFixed(2);
							resultado = resultado.toString();
							resultado = 'R$ ' + resultado.replace('.', ',');
							$("#valor_pagar").text(resultado);

							$('.valor_pago_venda').val(resultado);
							$('#valor_pago_modal').val(resultado);
							$('#valor_pagar_modal_pgto').val(resultado);
							$('#show_valor_pagar_modal').val(resultado);

							var soma_restante = soma - soma_dinheiro;
							var total_pagar_dinheiro = v + a - d - soma_restante;
							var troco = soma_dinheiro - total_pagar_dinheiro;

							if (troco < 0) {
								troco = 0;
							}

							resultado = troco.toFixed(2);
							resultado = resultado.toString();
							resultado = 'R$ ' + resultado.replace('.', ',');
							$("#troco").text(resultado);
						}

					}).click(function () {
						var idProduto = parseFloat($('.selectProd option:selected').val());

						if (isNaN(idProduto)) idProduto = 0;

						var nomeProd = $('.selectProd option:selected').text();
						var valorProd = parseFloat($('.selectProd option:selected').attr('valorProd'));
						var estoqueProd = $('.selectProd option:selected').attr('estoqueProd');
						var valor_avista = parseFloat($('.selectProd option:selected').attr('valor_avista'));
						var unid = ($('.selectProd option:selected').attr('unidade')) ? $('.selectProd option:selected').attr('unidade') : '';

						pagamentoAVista = false;
						controle_avista = 1;
						controle_pagamentos = 0;
						$('.pagamento_avista').each(function (indice, item) {
							var i = $(item).val();
							var p = parseInt(i);
							controle_avista *= p;
							controle_pagamentos = 1;
						});

						valor_venda_avista = valor_avista.toFixed(2);
						valor_venda_normal = valorProd.toFixed(2);

						if (controle_avista == 1 && controle_pagamentos == 1)
							pagamentoAVista = true;

						if (pagamentoAVista && valor_avista > 0) {
							valorProd = valor_avista;
						}

						var qtde_produto = $('#qtde_produto').val();
						var q = qtde_produto.replace(',', '');
						q = parseFloat(q);
						if (isNaN(q) || q == 0) {
							q = 1;
						}
						var quantidade = q.toFixed(3);

						var total = valorProd * quantidade;
						var valor_total = total.toFixed(2);

						total = valor_total;
						valor_total = valor_total.toString();
						valor_total = 'R$ ' + valor_total.replace('.', ',');

						let modalActive = $('#modal_alterar_valor_produto_venda').val() == 1 ? 1 : 0;

						var table = $('#tabela_produtos');

						if (idProduto > 0) {
							table.prepend(
								`<tr style="border-collapse: collapse;">
									<td><span class="font-md">${nomeProd}</span></td>
									<td><span class="font-md">${unid}</span></td>
									<td><span class="font-md">${estoqueProd}</span></td>
									<td>
										<span class="bold theme-font">
										<input type="text" class="form-control form-filter input-sm quant_venda decimal" name="quantidade[]" value="${quantidade}" id="quantidade_produto"></span>
									</td>
									<td>
										<span class="bold theme-font">
											<input type="text" class="form-control form-filter input-sm moeda valor input-valor-normal" name="valor_venda_tabela[]" valor_avista="${valor_venda_avista}" valor_normal="${valor_venda_normal}" value="${floatParaReal(valorProd)}" id="vlr_venda_produto" style="width: 90px">
										</span>
									</td>
									<td><span class="bold theme-font font-md valor_total">${valor_total}<span></td>
									<td>
										<a href="javascript:void(0);" class="btn btn_alterar_valor_produto_venda" title="Alterar valor deste produto?" style="background-color: #EECB00; color: #675800">
											<i class="fa fa-dollar"></i>
										</a>
										<a href="javascript:void(0);" class="btn red remover_produto_venda" title="Deseja remover este produto?">
										<i class="fa fa-times"></i></a>
									</td>
									<input name="nome_produto_pdv[]" type="hidden" class="nome_produto_pdv" value="`+ nomeProd + `" />
									<input name="estoque_produto_pdv[]" type="hidden" class="estoque_produto_pdv" value="`+ estoqueProd + `" />
									<input name="id_produto[]" type="hidden" class="id_produto" value="`+ idProduto + `">
									<input name="tabelas[]" type="hidden" class="tabelas" value="`+ id_tabela + `">
									<input type="hidden" class="total" value="`+ total + `" />
									<input type="hidden" class="estoque" value="`+ estoqueProd + `">
								</tr>`
							).find("tr").first().hide();

							if (modalActive == 1) {
								let inputValorNormal = document.querySelector(".input-valor-normal")
								inputValorNormal.disabled = true
							}

							const styles = {
								"pointer-events": "none",
								"background-color": "#eaeaea"
							}

							if (modalActive === 1) {
								$('#vlr_venda_produto').css(styles);
							}

							if (modalActive === 0) $('.btn_alterar_valor_produto_venda').addClass('hidden');
							table.find("tr").first().fadeIn();
						}

						$('.quant_venda').maskMoney({ decimal: '.', precision: 3, symbolStay: false, allowNegative: false });
						$('#vlr_venda_produto').maskMoney({ symbol: 'R$ ', thousands: '.', decimal: ',', precision: 2, symbolStay: true, allowNegative: true });

						var soma = 0;
						$('.total').each(function (indice, item) {
							var i = $(item).val();
							var v = parseFloat(i);
							vlr = v.toFixed(2);
							if (!isNaN(v)) {
								soma += parseFloat(vlr);
							}
						});
						soma = soma.toFixed(2);

						$("#valor").val(soma);
						var resultado = soma.toString();
						resultado = 'R$ ' + resultado.replace('.', ',');
						$("#valor2").text(resultado);
						$('#valor_total_modal').val(resultado);

						var desconto = $("#valor_desconto_modal").val();
						var d = desconto.replace('.', '');
						d = d.replace(',', '.');
						d = d.replace('R$ ', '');
						d = parseFloat(d);
						if (isNaN(d)) {
							d = 0;
						}

						if (d > 0) {
							$("#valor_desconto_modal").trigger({ type: 'keyup', which: 13, keyCode: 13 });
						}

						var acrescimo = $("#valor_acrescimo_modal").val();
						var a = acrescimo.replace('.', '');
						a = a.replace(',', '.');
						a = a.replace('R$ ', '');
						a = parseFloat(a);
						if (isNaN(a)) {
							a = 0;
						}

						var valor_pagar = soma + a - d;
						valor_pagar = valor_pagar.toFixed(2);
						valor_pagar = valor_pagar.toString();
						valor_pagar = valor_pagar.replace('.', ',');
						$('#valor_pagar').text('R$ ' + valor_pagar);

						$('.valor_pago_venda').val(resultado);
						$('#valor_pago_modal').val(resultado);
						$('#valor_pagar_modal_pgto').val(resultado);
						$('#show_valor_pagar_modal').val(resultado);

						var valor = $("#valor").val();
						var v = parseFloat(valor);
						if (isNaN(v)) {
							v = 0;
						}

						var desconto = $('#valor_desconto_modal').val();
						var d = desconto.replace('.', '');
						d = d.replace(',', '.');
						d = d.replace('R$ ', '');
						d = parseFloat(d);
						if (isNaN(d)) {
							d = 0;
						}
						var soma = 0;
						$('.valor_pago').each(function (indice, item) {
							var i = $(item).val();
							var p = parseFloat(i);
							if (!isNaN(p)) {
								soma += p;
							}
						});
						var soma_dinheiro = 0;
						$('.dinheiro').each(function (indice, item) {
							var i = $(item).val();
							var p = parseFloat(i);
							if (!isNaN(p)) {
								soma_dinheiro += p;
							}
						});

						var valor_pagar = v + a - d - soma;
						if (valor_pagar < 0) {
							valor_pagar = 0;
						}
						var resultado = valor_pagar.toFixed(2);
						resultado = resultado.toString();
						resultado = 'R$ ' + resultado.replace('.', ',');
						$("#valor_pagar").text(resultado);
						$('#valor_pago_modal').val(resultado);
						$('#valor_pagar_modal_pgto').val(resultado);

						var soma_restante = soma - soma_dinheiro;
						var total_pagar_dinheiro = v + a - d - soma_restante;
						var troco = soma_dinheiro - total_pagar_dinheiro;

						if (troco < 0) {
							troco = 0;
						}

						resultado = troco.toFixed(2);
						resultado = resultado.toString();
						resultado = 'R$ ' + resultado.replace('.', ',');
						$("#troco").text(resultado);
					});
				}
			});
		});
	});

	//PASSAR MOUSE POR CIMA E MOSTRAR PREÇO DO PRODUTO
	$(document).on('mouseenter', '.todos_produtos', function (e) {
		let valor = $(this).attr('valorProd');
		let html = `<input disabled type="text" value="${floatParaReal(valor)}" class="form-control" /> <span class="help-block">Preço produto</span>`;
		$('#showPriceProductInput').html(html);
	});

	//ATALHO PARA FOCAR NO CAMPO DE CODIGO DE BARRAS - VENDAS > NOVA VENDA
	shortcut.add("F2", function () {
		$('.barcode').focus();
	});

	//Função que troca de foco ao apertar o enter
	function changeInput() {
		$('.pular').keypress(function (e) {

			var tecla = (e.keyCode) ? e.keyCode : e.which;

			if (tecla == 13) {

				campo = $('.pular');
				indice = campo.index(this);

				if (campo[indice + 1] != null) {
					proximo = campo[indice + 1];
					proximo.focus();
				}
			}

		});
	}

	//ATALHO PARA ADICIONAR CLIENTE (F3)
	shortcut.add("F3", function () {
		$('#modal_cliente').modal();
		changeInput();
	});

	$('#modal_cliente').on('shown.bs.modal', function () {
		const cpf_cnpj_modal = document.getElementById('cpf_cnpj_modal');
		cpf_cnpj_modal.addEventListener('input', buscarCpfCnpj);
		$('#cadastro').focus();
	});

	//Ao apertar a tecla DELETE a forma de pagamento que estiver selecionada será removida
	shortcut.add("DELETE", function () {
		$('a.remover_pagamento').trigger('click');
	});

	$('#data_boleto_modal').on('keyup change', function () {
		let data_boleto_modal = $('#data_boleto_modal').val();
		$('#data_boleto').val(data_boleto_modal);
	});

	$('#page-header-menu-header2').hide();
	$('#top-menu-header2').hide();

	$('#menu-toggler-pdv').mouseenter(function () {
		$('#page-header-menu-header2').show(600);
	}).click(function () {
		$('#page-header-menu-header2').hide(600);
	})

	//Botao "Ir para cliente" ocultado até selecionar cliente.
	$('.cliente_ir').css('display', 'none');

	//Botao de salvar os dados do modal adicionar cliente em Vendas > Nova Venda
	if ($('.salvar_cliente_modal').length > 0) {
		$('.salvar_cliente_modal').click(function () {
			let id_cadastro = $('#id_cadastro').val();
			let nome = $('#cadastro').val();
			let cpf_cnpj = $('#cpf_cnpj_modal').val();
			let celular = $('#celular').val();
			let obs = $('#observacao_modal').val();

			let valor_pagar = $('#valor_pagar').text();
			let valor_total = $('#valor2').text();
			let vp = valor_total.replace('R$ ', '');
			vp = parseFloat(vp.replace(',', '.'));

			$('#id_cadastro_form').val(id_cadastro);
			$('#cadastro_form').val(nome);
			$('#celular_form').val(celular);
			$('#observacao_form').val(obs);
			$('#cpf_cnpj_form').val(cpf_cnpj);

			const valor_emissao = 10000;
			if (vp > valor_emissao) $('.btnvenda_nfc').attr('disabled', true);

			$('#modal_cliente').modal('hide');

			if (id_cadastro) {
				$('.btnvenda_nfc').attr('disabled', false);
			}

			$('#resposta_cliente').text(nome);

			clienteExistente(id_cadastro)

			$('.dados_cliente').removeClass('ocultar');
			$('#resposta_celular').text(celular);

		});
	}

	function clienteExistente(id_cadastro) {
		if (id_cadastro) {
			$('#cliente_novo').removeClass("mostrar");
			$('#cliente_novo').addClass("ocultar").attr('style', 'display:none');
			$('#cliente_existente').removeClass("ocultar");
			$('#cliente_existente').addClass("mostrar").attr('style', 'display:initial');
			verificaDebitosCliente(id_cadastro);
		} else {
			$('#cliente_novo').removeClass("ocultar");
			$('#cliente_novo').addClass("mostrar").attr('style', 'display:initial');
			$('#cliente_existente').removeClass("mostrar");
			$('#cliente_existente').addClass("ocultar").attr('style', 'display:none');
		}
	}

	$(document).on('keyup', '#valor_desconto_modal, #valor_desconto_porcentagem_modal', function (e) {

		if (e.which == 13 || e.keyCode == 13) {
			$('#valor_acrescimo_modal').focus().select();
			return;
		}

		let valor_original_pagamento = $('#valor_pagar_modal_pgto').val();
		let valor_d = $('#valor_desconto_modal').val();
		let vd = valor_d.replace("R$", "");
		vd = vd.replace(",", ".");

		let valor_dp = $('#valor_desconto_porcentagem_modal').val();
		let vdp = valor_dp.replace("%", "");
		vdp = vdp.replace(",", ".");

		let valor_total = $('#valor2').text();
		let vt = valor_total.replace("R$", "");
		vt = vt.replace(",", ".");

		var desconto_maximo = $("#id_tabela_venda").find(':selected').attr('desconto');

		let checkDesc = (desconto_maximo / 100) * vt;
		checkDesc = Math.floor(checkDesc * 100) / 100;

		if (vd > checkDesc) {
			alert('Erro! Desconto acima do máximo permitido para este produto. Desconto máximo: ' + floatParaReal(checkDesc));
			$('#valor_desconto_rapida').val(0);
			$('#valor_desconto_modal').val('R$ 0,00');
			$('#valor_desconto_porcentagem_modal').val('0,00');
			$('#valor_pago_modal').val(valor_original_pagamento);
			return false;
		}
	});

	//ATALHO PARA FINALIZAR VENDA (F6) - em Vendas > Nova Venda PDV
	shortcut.add("F6", function () {
		let product = $('#id_produto_venda').val();
		let id_produto = $('.id_produto').val();
		let paymentChange = $('#tipopagamento').val();
		let id_pagamento = $('.id_pagamento').val();
		let botao = document.querySelector('.btn_finalizar')
		let msg = "Erro ao FINALIZAR VENDA. <br>Não foi selecionado nenhum PRODUTO ou PAGAMENTO na venda.";

		if ($('#case-novavenda').val() == "novavenda") {
			if (product || id_produto && (paymentChange || id_pagamento)) {
				$('.btn_finalizar').trigger('click');
				botao.disabled = true /* bloqueia */
			} else {
				$.bootstrapGrowl(msg, {
					ele: "body",
					type: "danger",
					offset: {
						from: "top",
						amount: 50
					},
					align: "center",
					width: "auto",
					delay: 7000,
					stackup_spacing: 10
				});
				return false;
			}
		}
		shortcut.remove("F6");
	});

	function getCookie(name) {
		let cookie = {};
		document.cookie.split(';').forEach(function (el) {
			let [k, v] = el.split('=');
			cookie[k.trim()] = v;
		})
		return cookie[name];
	}

	// Atalho Finalizar venda com NFC - F7
	shortcut.add("F7", function () {
		if ($('#case-novavenda').val() == "novavenda") {
			$('.btnvenda_nfc').trigger('click');
			shortcut.remove("F7");
		}
	});

	function ultimaVenda() {
		/*
		var id_ultima_venda = getCookie("ultimaVenda");
		if (id_ultima_venda>0) {
			window.open('nfc.php?id='+id_ultima_venda, 'Emissão de NFC-e: '+id_ultima_venda,'width=360,height=500,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=0,top=0');
		} else {
			alert("Atenção! Houve um erro com a venda e não foi possível emitir a NFC-e. Venda encontrada: "+id_ultima_venda);
		}

		//as linhas a seguir apagam o cookie da venda
		var data = new Date(2010,0,01);
		data = data.toGMTString();
		document.cookie = 'ultimaVenda=; expires=' + data;
		*/
	}

	$('.btnvenda_nfc').click(function () {
		let id_produto = $('.id_produto').val();
		let id_pagamento = $('.id_pagamento').val();
		let id_cadastro = ($('#id_cadastro').val());
		let msg1 = "Erro ao FINALIZAR VENDA COM EMISSÃO DA NFC-e. <br>Não foi selecionado nenhum PRODUTO ou PAGAMENTO na venda.";
		let msg2 = "Erro ao FINALIZAR VENDA COM EMISSÃO DA NFC-e. <br>Verifique se foi selecionado algum PRODUTO, PAGAMENTO ou CLIENTE.<br>Se o valor da venda é maior que R$ 10.000 é necessário identificar o cliente - ATALHO (F3).";

		let valor_total = $('#valor2').text();
		let valor = valor_total.replace('R$ ', '');
		valor = parseFloat(valor.replace(',', '.'));

		const valor_emissao = 10000;

		$('#admin_form').append("<input name='venda_fiscal' type='hidden' value='1' />");

		if (valor <= valor_emissao) {
			if ((id_produto > 0) && (id_pagamento > 0)) {
				$('.btnvenda_nfc').submit();
				/*
				setTimeout(() => {
					//ultimaVenda();
				}, 4000);
				*/

			} else {
				$.bootstrapGrowl(msg1, {
					ele: "body",
					type: "danger",
					offset: {
						from: "top",
						amount: 50
					},
					align: "center",
					width: "auto",
					delay: 7000,
					stackup_spacing: 10
				});
				return false;
			}
		}

		if (valor > valor_emissao) {
			if ((id_produto > 0) && (id_pagamento > 0) && id_cadastro) {
				$('.btnvenda_nfc').submit();
				/*
				setTimeout(() => {
					//ultimaVenda();
				}, 4000);
				*/

			} else {
				$.bootstrapGrowl(msg2, {
					ele: "body",
					type: "info",
					offset: {
						from: "top",
						amount: 50
					},
					align: "center",
					width: "auto",
					delay: 7000,
					stackup_spacing: 10
				});
				return false;
			}
		}
		$('.btnvenda_nfc').hide();
	});

	//ATALHO PARA SALVAR VENDA (F8)
	shortcut.add("F8", function () {
		let product = $('#id_produto_venda').val();
		let id_produto = $('.id_produto').val();
		let paymentChange = $('#tipopagamento').val();
		let id_pagamento = $('.id_pagamento').val();

		let msg = "Erro ao SALVAR VENDA. <br>Não foi selecionado nenhum PRODUTO ou PAGAMENTO na venda.";

		if ($('#case-novavenda').val() == "novavenda") {
			if (product || id_produto && (paymentChange || id_pagamento)) {
				$('.btnsalvar').trigger('click');
			} else {
				$.bootstrapGrowl(msg, {
					ele: "body",
					type: "danger",
					offset: {
						from: "top",
						amount: 50
					},
					align: "center",
					width: "auto",
					delay: 7000,
					stackup_spacing: 10
				});
				return false;
			}
		}

	});

	//Atalho para escolher forma de pagamento como SALVAR NO CREDIARIO - F10
	shortcut.add("F10", function () {
		let id_cadastro = $('#id_cadastro').val();
		let msgSelecionarCliente = "Erro ao salvar VENDA NO CREDIÁRIO. <br> Favor selecionar um cliente.";
		let msgNoCrediario = "Erro ao salvar VENDA NO CREDIÁRIO. <br> Este cliente NÃO TEM crediário.";
		let inputValorNormal = document.querySelector(".input-valor-normal")
		inputValorNormal.disabled = false

		if ($('#case-novavenda').val() == "novavenda") {
			if (id_cadastro) {
				if ($('.btncrediario').hasClass('mostrar')) {
					$('.btncrediario').trigger('click', function () {

						$('#admin_form').append("<input name='salvar_crediario' type='hidden' value='1' />");
						$('#admin_form').submit();
					});
				} else {
					$.bootstrapGrowl(msgNoCrediario, {
						ele: "body",
						type: "danger",
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						delay: 5000,
						stackup_spacing: 10
					});
					return false;
				}
			} else {
				$.bootstrapGrowl(msgSelecionarCliente, {
					ele: "body",
					type: "info",
					offset: {
						from: "top",
						amount: 50
					},
					align: "center",
					width: "auto",
					delay: 5000,
					stackup_spacing: 10
				});
				return false;
			}
		}
	});

	///////////////////////
	///	FINAL NOVO PDV	///
	///////////////////////

	//Verifica o desconto e o acrescimo ao finalizar uma venda em Vendas > Venda em aberto
	$(document).on('keyup', '#novo_desconto, #novo_acrescimo, #desc_porcentagem', function () {
		let id_tabela = $('#id_tabela_vendas').val();

		let desconto = $('#novo_desconto').val();
		let d = desconto.replace('R$ ', '');
		d = parseFloat(d.replace(',', '.'));

		let acrescimo = $('#novo_acrescimo').val();
		let a = acrescimo.replace('R$ ', '');
		a = parseFloat(a.replace(',', '.'));

		let descontoporc = $('#desc_porcentagem').val();
		let dp = descontoporc.replace('%', '');
		dp = parseFloat(dp.replace(',', '.'));

		let valor_total = $("#valtotal_finalizavenda").html();
		valor_total = valor_total.replace('R$ ', '');
		valor_total = valor_total.replace('.', '');
		valor_total = parseFloat(valor_total.replace(',', '.'));

		$.ajax({
			method: 'POST',
			url: 'webservices/verificaDesconto.php',
			data: 'id_tabela=' + id_tabela,
			success: function (data) {
				let dados = $.parseJSON(data);
				let descTable = dados.desconto;
				let checkDesc = (descTable / 100) * valor_total;
				checkDesc = Math.round(checkDesc * 100) / 100;

				if (d > checkDesc) {
					alert('Erro! O desconto máximo permitido pela tabela selecionada é ' + floatParaReal(checkDesc));
					$('#novo_desconto').val(floatParaReal(0));
					$('#desc_porcentagem').val(floatParaReal(0));
					return false;
				}
			}
		});

	});

	let enterCep = document.getElementById("cep")
	if (enterCep != null) {
		enterCep.addEventListener("keypress", function (event) {
			if (event.key === "Enter") {
				event.preventDefault()
				$('#cepbusca').click()
			}
		})
	}

	let enterTransportadoraCep = document.getElementById("trans_cep")
	if (enterTransportadoraCep != null) {
		enterTransportadoraCep.addEventListener("keypress", function (event) {
			if (event.key === "Enter") {
				event.preventDefault()
				$('#ceptransportadora').click()
			}
		})
	}

	let enterContabilidadCep = document.getElementById("contabilidade_cep")
	if (enterContabilidadCep != null) {
		enterContabilidadCep.addEventListener("keypress", function (event) {
			if (event.key === "Enter") {
				event.preventDefault()
				$('#cepbuscacontador').click()
			}
		})
	}

	if ($('a.acaoAnaliseEstoque').length > 0) {
		$(document).on('click', 'a.acaoAnaliseEstoque', function () {
			let id = $(this).attr('id');
			let id_produto = $(this).attr('id_produto');
			let estoque_atual = parseFloat($(this).attr('estoque_atual'));
			let estoque_fisico = parseFloat($(this).attr('estoque_fisico'));
			let valor_custo = parseFloat($(this).attr('valor_custo'));

			let resultado_estoque = estoque_fisico + estoque_atual;
			let nome = $(this).attr('nome');
			let acao = $(this).attr('acao');
			let data = '';
			let label = '';
			let className = '';
			let aviso = ``;
			let title = ``;

			if (acao === "somarEstoque") {
				title = `Somar estoque do produto ${nome}`
				aviso = `
					<div class="alert alert-warning">
						Esta função irá somar o estoque fisico junto com o estoque do sistema.<br>
						Exemplo: Estoque Fisico = ${estoque_fisico} e Estoque Atual = ${estoque_atual}, então o estoque final deste produto será ${resultado_estoque}.
					</div>
				`;
				label = `Atualizar estoque`;
				className = 'btn-success';
				data = 'acaoAnaliseEstoque=1&id_produto=' + id_produto + '&estoque_atual=' + estoque_atual + '&estoque_fisico=' + estoque_fisico + '&valor_custo=' + valor_custo + '&somar=1';
			}

			if (acao === "substituirEstoque") {
				title = `Substituir estoque do produto ${nome}`
				aviso = `
					<div class="alert alert-warning">
						Esta função irá substituir o estoque atual pelo estoque fisico.<br>
						Exemplo: Estoque Fisico = ${estoque_fisico} e Estoque Atual = ${estoque_atual}, então o estoque final deste produto será ${estoque_fisico}.
					</div>
				`;
				label = `Substituir estoque`;
				className = 'btn-primary';
				data = 'acaoAnaliseEstoque=1&id_produto=' + id_produto + '&estoque_atual=' + estoque_atual + '&estoque_fisico=' + estoque_fisico + '&valor_custo=' + valor_custo + '&somar=0';
			}

			if (acao === 'manterEstoque') {
				title = "Negar atualização"
				aviso = `
					<div class="alert alert-warning">
						Esta função não irá atualizar o estoque, continuará da mesma forma.<br>
					</div>
				`;
				label = `Não atualizar`;
				className = 'btn-danger';
				data = 'acaoAnaliseEstoque=1&id_produto=' + id_produto + '&estoque_atual=' + estoque_atual + '&estoque_fisico=' + estoque_fisico + '&valor_custo=' + valor_custo + '&somar=-1';
			}

			bootbox.dialog({
				message: aviso,
				title: title,
				buttons: {
					salvar: {
						label: label,
						className: className,
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: data,
								success: function (data) {
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
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	}

	if ($('a.somarEstoqueTodosProdutos').length > 0) {
		$('a.somarEstoqueTodosProdutos').click(function () {
			var str = $("#admin_form").serialize();
			let checkboxes = $('.checkboxes');
			var id_produto = 0;
			let html = '';
			let progress = 0;
			let selectedIds = [];

			checkboxes.each(function (index, item) {
				let checked = $(item).is(':checked')
				if (checked) {
					id_produto = $(item).val();
				}
			});

			let cont = checkboxes.filter(':checked');
			let i = selectedIds.indexOf(id_produto);
			let aux = false;

			$('.containerProgressBar .progress-bar').css('animation', 'progress-animation 6s infinite');
			html += `<style> @keyframes progress-animation { 0% { width: 0%; }`;
			// for (let index = 0; index < cont.length; index++) {
			// 	progress = Math.floor(((index + 1) / cont.length) * 100);
			// 	var valueStyle = `${progress}% { width: ${progress}%; }`;
			// }
			html += `100% { width: 100%; }`;
			html += `} </style>`;
			if (id_produto > 0) {
				$('.carregando').removeClass('ocultar').css('font-size', '16px');
				$('.containerProgressBar').removeClass('ocultar');

				$('.carregando').append(html);
			}


			$.ajax({
				type: 'post',
				url: 'controller.php',
				data: 'somarEstoqueTodosProdutos=1&' + str,
				success: function (data) {
					var response = data.split("#");
					$.bootstrapGrowl(response[0], {
						ele: "body",
						type: response[1],
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						delay: 10000,
						stackup_spacing: 10
					});

					setTimeout(function () {
						window.location.href = response[3];
					}, 1000);
				}
			});

		})
	}

	if ($('a.substituirEstoqueTodosProdutos').length > 0) {
		$('a.substituirEstoqueTodosProdutos').click(function () {
			var str = $("#admin_form").serialize();
			let checkboxes = $('.checkboxes');
			var id_produto = 0;
			let html = '';
			let progress = 0;
			let selectedIds = [];

			checkboxes.each(function (index, item) {
				let checked = $(item).is(':checked')
				if (checked) {
					id_produto = $(item).val();
				}
			});

			let cont = checkboxes.filter(':checked');
			let i = selectedIds.indexOf(id_produto);
			let aux = false;

			$('.containerProgressBar .progress-bar').css('animation', 'progress-animation 6s infinite');
			html += `<style> @keyframes progress-animation { 0% { width: 0%; }`;
			// for (let index = 0; index < cont.length; index++) {
			// 	progress = Math.floor(((index + 1) / cont.length) * 100);
			// 	var valueStyle = `${progress}% { width: ${progress}%; }`;
			// }
			html += `100% { width: 100%; }`;
			html += `} </style>`;

			if (id_produto > 0) {
				$('.carregando').removeClass('ocultar').css('font-size', '16px');
				$('.containerProgressBar').removeClass('ocultar');

				$('.carregando').append(html);
			}

			$.ajax({
				type: 'post',
				url: 'controller.php',
				data: 'substituirEstoqueTodosProdutos=1&' + str,
				success: function (data) {
					var response = data.split("#");
					$.bootstrapGrowl(response[0], {
						ele: "body",
						type: response[1],
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						delay: 10000,
						stackup_spacing: 10
					});

					setTimeout(function () {
						window.location.href = response[3];
					}, 1000);
				}
			});

		})
	}

	if ($('a.manterEstoqueTodosProdutos').length > 0) {
		$('a.manterEstoqueTodosProdutos').click(function () {
			let str = $("#admin_form").serialize();
			let checkboxes = $('.checkboxes');
			var id_produto = 0;
			let html = '';
			let progress = 0;
			let selectedIds = [];

			checkboxes.each(function (index, item) {
				let checked = $(item).is(':checked')
				if (checked) {
					id_produto = $(item).val();
					selectedIds.push(id_produto);
				}
			});

			let cont = checkboxes.filter(':checked');
			let i = selectedIds.indexOf(id_produto);
			let aux = false;

			$.ajax({
				type: 'post',
				url: 'controller.php',
				data: 'manterEstoqueTodosProdutos=1&id_produto=' + id_produto + '&' + str,
				success: function (data) {

					var response = data.split("#");

					$.bootstrapGrowl(response[0], {
						ele: "body",
						type: response[1],
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						delay: 10000,
						stackup_spacing: 10
					});

					setTimeout(function () {
						window.location.href = response[3];
					}, 1000);
				},

			});

		})
	}
	$(".group-checkable").change(function () {
		var set = jQuery(this).attr("data-set");
		var checked = jQuery(this).is(":checked");
		jQuery(set).each(function () {
			if (checked) {
				this.checked = true;
				$(this).parents('tr').addClass("info");
			} else {
				this.checked = false;
				$(this).parents('tr').removeClass("info");
			}
		});
		jQuery.uniform.update(set);
	});

	$(".checkboxes").change(function () {
		var set = jQuery(this).attr("data-set");
		var checked = jQuery(this).is(":checked");
		if (checked) {
			this.checked = true;
			$(this).parents('tr').addClass("info");
		} else {
			this.checked = false;
			$(this).parents('tr').removeClass("info");
		}
		jQuery.uniform.update(set);
	});

	// Exportação de produtos no excel
	if ($('a.exportar_produtos').length > 0) {
		$('.exportar_produtos').click(function () {
			var str = $("#admin_form").serialize();
			$.ajax({
				type: 'post',
				url: 'controller.php',
				data: 'processarExportacaoProduto=1&' + str,
				success: function (data) {
					var response = data.split("#");

					if (response[1] == 'success') {
						let file = './excel/export/Exportacao_Produtos.xlsx';
						// window.open(file);
						var link = document.createElement('a');
						link.href = file;
						link.download = 'Exportacao_Produtos.xlsx';
						link.click();
					}

					$.bootstrapGrowl(response[0], {
						ele: "body",
						type: response[1], // (null, 'info', 'danger', 'success', 'warning')
						offset: {
							from: "top",
							amount: 50
						},
						align: "center",
						width: "auto",
						delay: 10000,
						stackup_spacing: 10
					});

					setTimeout(function () {
						window.location.href = response[3];
					}, 1000);
				}
			});

			return false;
		});
	};


	/*
	* Inicio orçamento de vendas
	*/

	//BOX DIALOG Apagar um registro
	if ($('a.transformarOrcamentoParaVenda').length > 0) {
		$('a.transformarOrcamentoParaVenda').click(function () {
			var id = $(this).attr('id');
			var acao = $(this).attr('acao');
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Transformar este orçamento em uma venda?";
			bootbox.dialog({
				message: aviso,
				title: "Transformar orçamento de produtos em venda",
				buttons: {
					salvar: {
						label: "Concluir orçamento",
						className: "btn-success",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'transformarOrcamentoParaVenda=' + id,
								success: function (data) {
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	//CONSULTA EXTERNA - Finalizar Venda -  Buscar valor do produto, verifica de é avista ou normal e seta valores
	$('#id_produto_vendas_orcamento').change(function () {
		var id = $(this).val();
		var aVista = $("#pagamentoAVista").val();

		if (id == '' || id == 0) return false;

		var id_tabela = $("#id_tabela_vendas").val();

		jQuery.ajax({
			type: 'post',
			url: 'webservices/buscaproduto.php',
			data: 'id=' + id + '&id_tabela=' + id_tabela,
			success: function (data) {

				var temp = data.split("#");
				if (temp.length >= 2) {
					valorAVista = parseFloat(temp[2]).toFixed(2)
					if (aVista && valorAVista > 0) {
						valor = valorAVista;
					} else {
						valor = parseFloat(temp[0]).toFixed(2);
					}
					estoque = temp[1].replace('.', ',');

					$("#valor").val(valor);
					$("#valor_venda_produto").val('R$ ' + valor.replace('.', ','));
					$("#estoque").val(estoque);

					var quantidade = $("#quantidade_finalizar_venda").val();
					q = parseFloat(quantidade);
					if (isNaN(q)) {
						q = '1,000';
						$("#quantidade_finalizar_venda").val(q);
					}

					var valor_total = valor * q;
					valor_total = valor_total.toFixed(2);
					valor_total = valor_total.toString();
					valor_total = valor_total.replace('.', ',');
					$('#valor_total_produto').val('R$ ' + valor_total);
				} else {
					alert('Não foi possível encontrar o produto nesta tabela!');
					return false;
				}
			}
		});
	});

	$('.valor_quant_prod_troca').keyup(function () {
		let valor = $(this).val();
		let v = parseFloat(valor.replace(',', '.'));
		let vlr_quant_menos_quant_trocada = parseFloat($('.vlr_quant_menos_quant_trocada').val());
		let quantidade_venda = parseFloat($(this).parent().prev().prev().prev().prev().text());
		let quantidade_trocada = parseFloat($(this).parent().prev().prev().prev().text());
		let result_quant = quantidade_venda - quantidade_trocada;


		let table = $(this).closest('table');
		let segundaColuna = table.find('tr td:nth-child(7)').text();

		if (v > result_quant) {
			alert('O valor digitado é maior que a quantidade do produto na venda');
			$(this).val(0);
		}
	});

	function verificarTextoProdutoTroca() {
		if ($('.valor_pagar_texto').hasClass('font-green-seagreen')) {
			$('.salvar-restante-crediario').addClass('ocultar')
		}
	}

	// verificarTextoProdutoTroca();

	if ($('.salvar-restante-crediario').length > 0) {
		$('.salvar-restante-crediario').click(function () {

			let id_cadastro = parseFloat($('#id_cadastro_troca').val());

			if (id_cadastro <= 0) {
				alert('Necessário adicionar um cliente.');
				return false;
			}

			if ($('.valor_pagar_texto').hasClass('font-red')) {

				let valor_utilizar = $('.valor_pagar_texto').text();
				valor_utilizar = valor_utilizar.replace('.', '');
				valor_utilizar = valor_utilizar.replace(',', '.');
				valor_utilizar = parseFloat(valor_utilizar.replace('R$', ''));

				$('#voucher_crediario').val(valor_utilizar);
			}

			var str = $("#admin_form").serialize();
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Deseja adicionar o valor a utilizar restante no crediario?";
			bootbox.dialog({
				message: aviso,
				title: "Salvar valor a utilizar no crediário",
				buttons: {
					salvar: {
						label: "Salvar",
						className: "yellow-casablanca",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'salvarValorUtilizarCrediario=1&' + str,
								success: function (data) {
									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	}

	if ($('.salvar-cliente-troca').length > 0) {
		$('.salvar-cliente-troca').click(function () {

			let cadastro = $('#cadastro').val();
			let id_cadastro = $('#id_cadastro').val();
			let cpf_cnpj = $('#cpf_cnpj').val();
			let celular = $('#celular').val();

			$('#id_cadastro_troca').val(id_cadastro);

			$('#celular_troca').val(celular);
			$('#cpf_cnpj').val(celular);
			$('#').val(celular);

			$('.cliente_troca').text(`Cliente: ${cadastro} - ${cpf_cnpj} - ${celular} `);

			if (id_cadastro) {
				$('.info_cliente_troca_produto').addClass('dados_cliente');
				$('#cliente_novo').removeClass("mostrar");
				$('#cliente_novo').addClass("ocultar").attr('style', 'display:none');
				$('#cliente_existente').removeClass("ocultar");
				$('#cliente_existente').addClass("mostrar").attr('style', 'display:initial');
				verificaDebitosCliente(id_cadastro);
			} else {
				$('#cliente_novo').removeClass("ocultar");
				$('#cliente_novo').addClass("mostrar").attr('style', 'display:initial');
				$('#cliente_existente').removeClass("mostrar");
				$('#cliente_existente').addClass("ocultar").attr('style', 'display:none');
			}

			$('#modal_cliente').hide();

		});
	}

	//BOX DIALOG Apagar um registro
	if ($('a.apagarProdutoKit').length > 0) {
		$('a.apagarProdutoKit').click(function () {

			var id_kit = $(this).attr('id');
			var id_produto_kit = $(this).attr('id_produto_kit');
			var id_produto = $(this).attr('id_produto');
			var acao = $(this).attr('acao');
			var parent = $(this).parents("tr");
			var aviso = ($(this).attr('title') !== undefined) ? $(this).attr('title') : "Você deseja apagar este registro?";

			bootbox.dialog({
				message: aviso,
				title: "Apagar registro",
				buttons: {
					salvar: {
						label: "Apagar Registro",
						className: "red",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: acao + '=' + id_kit + '&id_produto_kit=' + id_produto_kit + '&id_produto=' + id_produto,
								success: function (data) {
									parent.fadeOut(400, function () {
										parent.remove();
									});

									var response = data.split("#");
									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1")
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
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
	};

	// Botão de confirmar PIN para alterar valor de produto em NOVA VENDA
	$(document).on('click', 'a.btn_alterar_valor_produto_venda', function () {

		var item = $(this).parents("tr");

		if ($('#modal_alterar_valor_produto_venda').val() == 1) {
			$('#modal_alterar_valorvenda_por_pin').modal('show');
			$('#pinUserAlter').val('');

			$('.btn-form-alterar-valorvenda').click(function () {
				const pinUser = $('#pinUserAlter').val();
				$.ajax({
					method: 'POST',
					url: 'controller.php',
					data: 'verificarPinProdutoVenda=1&pin=' + pinUser,
					success: function (data) {
						let res = parseFloat(data.trim());
						if (res === 1) {
							$('#modal_alterar_valorvenda_por_pin').modal('hide');
							item.find('#vlr_venda_produto').css({
								"pointer-events": "auto",
								"background-color": "#fff"
							});
							item.find('#vlr_venda_produto').prop("disabled", false)
							$('.info-pin-incorreto').addClass('hidden');
						} else {
							$('.info-pin-incorreto').removeClass('hidden');
							$('#pinUserAlter').val('');
							return false;
						}
					}
				});
			});
		}

		$(document).on('keyup', '#pinUserAlter', function (event) {
			if (event.which == 13 || event.keyCode == 13) {
				const pinUser = $('#pinUserAlter').val();
				$.ajax({
					method: 'POST',
					url: 'controller.php',
					data: 'verificarPinProdutoVenda=1&pin=' + pinUser,
					success: function (data) {
						let res = parseFloat(data.trim());
						if (res === 1) {
							$('#modal_alterar_valorvenda_por_pin').modal('hide');
							item.find('#vlr_venda_produto').css({
								"pointer-events": "auto",
								"background-color": "#fff"
							});
							item.find('#vlr_venda_produto').prop("disabled", false)
							$('.info-pin-incorreto').addClass('hidden');
						} else {
							$('.info-pin-incorreto').removeClass('hidden');
							$('#pinUserAlter').val('');
							return false;
						}
					}
				});
			}
		});
	});

	$('#boleto_banco').change(function () {
		let codigo_banco = $('#boleto_banco option:selected').attr('codigo_banco');
		$('#boleto_codigo_banco').val(codigo_banco);

		if (typeof codigo_banco === "undefined") {
			$('.itensBoleto').removeClass("ocultar");
			$('.itensBoleto').removeClass("mostrar");
			$('.itensBoleto').addClass("ocultar");
		} else {
			$('.itensBoleto').removeClass("ocultar");
			$('.itensBoleto').removeClass("mostrar");
			$('.itensBoleto').addClass("mostrar");
		}
	});

	// Exportação de remessa para balança
	if ($('a.exportar_remessa_balanca').length > 0) {
		$('a.exportar_remessa_balanca').click(function () {

			let aviso = "";

			aviso = `<p>Deseja exportar produtos para a balança Toledo?</p> <br>
			<div>
				<label class="radio-inline">
					<input type="radio" name="tipo_balanca" id="mgv5v1"/> MGV5 - Versão 1
				</label>
				<label class="radio-inline">
					<input type="radio" name="tipo_balanca" id="mgv5v2"/> MGV5 - Versão 2
				</label>
				<label class="radio-inline">
					<input type="radio" name="tipo_balanca" id="mgv5v3"/> MGV5 - Versão 3
				</label>
				<label class="radio-inline">
					<input type="radio" name="tipo_balanca" id="mgv6"/> MGV6
				</label>
				<label class="radio-inline">
					<input type="radio" name="tipo_balanca" id="mgv7"/> MGV7
				</label>
			</div>`;

			bootbox.dialog({
				message: aviso,
				title: "Exportar arquivo para balança Toledo",
				buttons: {
					salvar: {
						label: "Exportar arquivo",
						className: "blue",
						callback: function () {
							jQuery.ajax({
								type: 'POST',
								url: 'controller.php',
								data: 'processarExportacaoBalanca=1&tipo=' + $('input[name="tipo_balanca"]:checked').attr('id'),
								success: function (data) {
									var response = data.split("#");

									if (response[1] == 'success') {
										let file = "./balanca_toledo/ITENSMGV.txt";
										var link = document.createElement('a');
										link.href = file;
										link.download = 'ITENSMGV.txt';
										link.click();
									}

									$.bootstrapGrowl(response[0], {
										ele: "body",
										type: response[1],
										offset: {
											from: "top",
											amount: 50
										},
										align: "center",
										width: "auto",
										delay: 10000,
										stackup_spacing: 10
									});
									if (response[2] == "1") {
										setTimeout(function () {
											window.location.href = response[3];
										}, 1000);
									}
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
	};


	//* BEGIN:CORE HANDLERS *//
	// this function handles responsive layout on screen size resize or mobile device rotate.

	// Handles header
	var handleHeader = function () {
		// handle search box expand/collapse
		$('.page-header').on('click', '.search-form', function (e) {
			$(this).addClass("open");
			$(this).find('.form-control').focus();

			$('.page-header .search-form .form-control').on('blur', function (e) {
				$(this).closest('.search-form').removeClass("open");
				$(this).unbind("blur");
			});
		});

		// handle hor menu search form on enter press
		$('.page-header').on('keypress', '.hor-menu .search-form .form-control', function (e) {
			if (e.which == 13) {
				$(this).closest('.search-form').submit();
				return false;
			}
		});

		// handle header search button click
		$('.page-header').on('mousedown', '.search-form.open .submit', function (e) {
			e.preventDefault();
			e.stopPropagation();
			$(this).closest('.search-form').submit();
		});

		// handle scrolling to top on responsive menu toggler click when header is fixed for mobile view
		$('body').on('click', '.page-header-top-fixed .page-header-top .menu-toggler', function () {
			Metronic.scrollTop();
		});
	};

	// Handles main menu
	var handleMainMenu = function () {

		// handle menu toggler icon click
		$(".page-header .menu-toggler").on("click", function (event) {
			if (Metronic.getViewPort().width < resBreakpointMd) {
				var menu = $(".page-header .page-header-menu");
				if (menu.is(":visible")) {
					menu.slideUp(300);
				} else {
					menu.slideDown(300);
				}

				if ($('body').hasClass('page-header-top-fixed')) {
					Metronic.scrollTop();
				}
			}
		});

		// handle sub dropdown menu click for mobile devices only
		$(".hor-menu .dropdown-submenu > a").on("click", function (e) {
			if (Metronic.getViewPort().width < resBreakpointMd) {
				if ($(this).next().hasClass('dropdown-menu')) {
					e.stopPropagation();
					if ($(this).parent().hasClass("open")) {
						$(this).parent().removeClass("open");
						$(this).next().hide();
					} else {
						$(this).parent().addClass("open");
						$(this).next().show();
					}
				}
			}
		});

		// handle hover dropdown menu for desktop devices only
		if (Metronic.getViewPort().width >= resBreakpointMd) {
			$('.hor-menu [data-hover="megamenu-dropdown"]').not('.hover-initialized').each(function () {
				$(this).dropdownHover();
				$(this).addClass('hover-initialized');
			});
		}

		// handle auto scroll to selected sub menu node on mobile devices
		$(document).on('click', '.hor-menu .menu-dropdown > a[data-hover="megamenu-dropdown"]', function () {
			if (Metronic.getViewPort().width < resBreakpointMd) {
				Metronic.scrollTo($(this));
			}
		});

		// hold mega menu content open on click/tap.
		$(document).on('click', '.mega-menu-dropdown .dropdown-menu, .classic-menu-dropdown .dropdown-menu', function (e) {
			e.stopPropagation();
		});

		// handle fixed mega menu(minimized)
		$(window).scroll(function () {
			var offset = 75;
			if ($('body').hasClass('page-header-menu-fixed')) {
				if ($(window).scrollTop() > offset) {
					$(".page-header-menu").addClass("fixed");
				} else {
					$(".page-header-menu").removeClass("fixed");
				}
			}

			if ($('body').hasClass('page-header-top-fixed')) {
				if ($(window).scrollTop() > offset) {
					$(".page-header-top").addClass("fixed");
				} else {
					$(".page-header-top").removeClass("fixed");
				}
			}
		});
	};

	// Handle sidebar menu links
	var handleMainMenuActiveLink = function (mode, el) {
		var url = location.hash.toLowerCase();

		var menu = $('.hor-menu');

		if (mode === 'click' || mode === 'set') {
			el = $(el);
		} else if (mode === 'match') {
			menu.find("li > a").each(function () {
				var path = $(this).attr("href").toLowerCase();
				// url match condition
				if (path.length > 1 && url.substr(1, path.length - 1) == path.substr(1)) {
					el = $(this);
					return;
				}
			});
		}

		if (!el || el.size() == 0) {
			return;
		}

		if (el.attr('href').toLowerCase() === 'javascript:;' || el.attr('href').toLowerCase() === '#') {
			return;
		}

		// disable active states
		menu.find('li.active').removeClass('active');
		menu.find('li > a > .selected').remove();
		menu.find('li.open').removeClass('open');

		el.parents('li').each(function () {
			$(this).addClass('active');

			if ($(this).parent('ul.navbar-nav').size() === 1) {
				$(this).find('> a').append('<span class="selected"></span>');
			}
		});
	};

	// Handles main menu on window resize
	var handleMainMenuOnResize = function () {
		// handle hover dropdown menu for desktop devices only
		var width = Metronic.getViewPort().width;
		var menu = $(".page-header-menu");

		if (width >= resBreakpointMd && menu.data('breakpoint') !== 'desktop') {
			// reset active states
			$('.hor-menu [data-toggle="dropdown"].active').removeClass('open');

			menu.data('breakpoint', 'desktop');
			$('.hor-menu [data-hover="megamenu-dropdown"]').not('.hover-initialized').each(function () {
				$(this).dropdownHover();
				$(this).addClass('hover-initialized');
			});
			$('.hor-menu .navbar-nav li.open').removeClass('open');
			$(".page-header-menu").css("display", "block");
		} else if (width < resBreakpointMd && menu.data('breakpoint') !== 'mobile') {
			// set active states as open
			$('.hor-menu [data-toggle="dropdown"].active').addClass('open');

			menu.data('breakpoint', 'mobile');
			// disable hover bootstrap dropdowns plugin
			$('.hor-menu [data-hover="megamenu-dropdown"].hover-initialized').each(function () {
				$(this).unbind('hover');
				$(this).parent().unbind('hover').find('.dropdown-submenu').each(function () {
					$(this).unbind('hover');
				});
				$(this).removeClass('hover-initialized');
			});
		} else if (width < resBreakpointMd) {
			//$(".page-header-menu").css("display", "none");
		}
	};

	var handleContentHeight = function () {
		var height;

		if ($('body').height() < Metronic.getViewPort().height) {
			height = Metronic.getViewPort().height -
				$('.page-header').outerHeight() -
				($('.page-container').outerHeight() - $('.page-content').outerHeight()) -
				$('.page-prefooter').outerHeight() -
				$('.page-footer').outerHeight();

			$('.page-content').css('min-height', height);
		}
	};

	// Handles the go to top button at the footer
	var handleGoTop = function () {
		var offset = 100;
		var duration = 500;

		if (navigator.userAgent.match(/iPhone|iPad|iPod/i)) {  // ios supported
			$(window).bind("touchend touchcancel touchleave", function (e) {
				if ($(this).scrollTop() > offset) {
					$('.scroll-to-top').fadeIn(duration);
				} else {
					$('.scroll-to-top').fadeOut(duration);
				}
			});
		} else {  // general
			$(window).scroll(function () {
				if ($(this).scrollTop() > offset) {
					$('.scroll-to-top').fadeIn(duration);
				} else {
					$('.scroll-to-top').fadeOut(duration);
				}
			});
		}

		$('.scroll-to-top').click(function (e) {
			e.preventDefault();
			$('html, body').animate({ scrollTop: 0 }, duration);
			return false;
		});
	};

	//* END:CORE HANDLERS *//

	return {

		// Main init methods to initialize the layout
		// IMPORTANT!!!: Do not modify the core handlers call order.

		initHeader: function () {
			handleHeader(); // handles horizontal menu
			handleMainMenu(); // handles menu toggle for mobile
			Metronic.addResizeHandler(handleMainMenuOnResize); // handle main menu on window resize

			if (Metronic.isAngularJsApp()) {
				handleMainMenuActiveLink('match'); // init sidebar active links
			}
		},

		initContent: function () {
			handleContentHeight(); // handles content height
		},

		initFooter: function () {
			handleGoTop(); //handles scroll to top functionality in the footer
		},

		init: function () {
			this.initHeader();
			this.initContent();
			this.initFooter();
		},

		setMainMenuActiveLink: function (mode, el) {
			handleMainMenuActiveLink(mode, el);
		},

		closeMainMenu: function () {
			$('.hor-menu').find('li.open').removeClass('open');

			if (Metronic.getViewPort().width < resBreakpointMd && $('.page-header-menu').is(":visible")) { // close the menu on mobile view while laoding a page
				$('.page-header .menu-toggler').click();
			}
		},

		getLayoutImgPath: function () {
			return Metronic.getAssetsPath() + layoutImgPath;
		},

		getLayoutCssPath: function () {
			return Metronic.getAssetsPath() + layoutCssPath;
		}
	};



}();
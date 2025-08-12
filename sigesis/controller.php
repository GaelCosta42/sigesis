<?php
/**
 * Controller - controle geral
 *
 */
define("_VALID_PHP", true);

require_once("init.php");
if (!$usuario->is_Todos())
    redirect_to("login.php");

?>
<?php

/* == Download Cupons Fiscal == */
if (isset($_GET['downloadCupomFiscal'])):
    if (intval($_GET['downloadCupomFiscal']) == 0 || empty($_GET['downloadCupomFiscal'])):
        die();
    endif;
    $fiscal->downloadCupomFiscal();
endif;

/* == Download Todos os XML de Entrada através da data de entrada == */
if (isset($_GET['downloadXMLEntrada'])):
    if (intval($_GET['downloadXMLEntrada']) == 0 || empty($_GET['downloadXMLEntrada'])):
        die();
    endif;
    $fiscal->downloadXMLEntrada();
endif;

/* == Download Todos os XML de Entrada através da data de entrada == */
if (isset($_GET['downloadXMLEntradaDtEmissao'])):
    if (intval($_GET['downloadXMLEntradaDtEmissao']) == 0 || empty($_GET['downloadXMLEntradaDtEmissao'])):
        die();
    endif;
    $fiscal->downloadXMLEntradaDtEmissao();
endif;

/* == Download Todos os XML de Saida == */
if (isset($_GET['downloadXMLSaida'])):
    if (intval($_GET['downloadXMLSaida']) == 0 || empty($_GET['downloadXMLSaida'])):
        die();
    endif;
    $fiscal->downloadXMLSaida();
endif;

/* == Download PDF Cupons Fiscal == */
if (isset($_GET['downloadPDFCupomFiscal'])):
    if (intval($_GET['downloadPDFCupomFiscal']) == 0 || empty($_GET['downloadPDFCupomFiscal'])):
        die();
    endif;
    $fiscal->downloadPDFCupomFiscal();
endif;

/* == Download Todos os XML de Saida == */
if (isset($_GET['downloadPDFSaida'])):
    if (intval($_GET['downloadPDFSaida']) == 0 || empty($_GET['downloadPDFSaida'])):
        die();
    endif;
    $fiscal->downloadPDFSaida();
endif;

/* == Download XML Cupons Fiscal Cancelado== */
if (isset($_GET['downloadCupomFiscalCancelado'])):
    if (intval($_GET['downloadCupomFiscalCancelado']) == 0 || empty($_GET['downloadCupomFiscalCancelado'])):
        die();
    endif;
    $fiscal->downloadCupomFiscalCancelado();
endif;

/* == Download Todos os XML de Saida Cancelado == */
if (isset($_GET['downloadXMLSaidaCancelado'])):
    if (intval($_GET['downloadXMLSaidaCancelado']) == 0 || empty($_GET['downloadXMLSaidaCancelado'])):
        die();
    endif;
    $fiscal->downloadXMLSaidaCancelado();
endif;

/* == Processar Nota Fiscal de Entrada == */
if (isset($_POST['processarNFeEntrada'])):
    if (intval($_POST['processarNFeEntrada']) == 0 || empty($_POST['processarNFeEntrada'])):
        die();
    endif;
    $arquivo->processarNFeEntrada();
endif;

/* == Processar Nota Fiscal de Saida == */
if (isset($_POST['processarNFeSaida'])):
    if (intval($_POST['processarNFeSaida']) == 0 || empty($_POST['processarNFeSaida'])):
        die();
    endif;
    $arquivo->processarNFeSaida();
endif;

/* == Processar Sintegra == */
if (isset($_POST['processarSintegra'])):
    if (intval($_POST['processarSintegra']) == 0 || empty($_POST['processarSintegra'])):
        die();
    endif;
    $arquivo->processarSintegra();
endif;

/* == Processar Arquivo Cadastro == */
if (isset($_POST['processarEmpresaLogo'])):
    if (intval($_POST['processarEmpresaLogo']) == 0 || empty($_POST['processarEmpresaLogo'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $empresa->processarEmpresaLogo();
endif;

/* == Processar Arquivo Boleto == */
if (isset($_POST['processarBoleto'])):
    if (intval($_POST['processarBoleto']) == 0 || empty($_POST['processarBoleto'])):
        die();
    endif;
    $boleto->processarBoleto();
endif;

/* == Processar Arquivo Boleto == */
if (isset($_POST['processarBoletoSicoob'])):
    if (intval($_POST['processarBoletoSicoob']) == 0 || empty($_POST['processarBoletoSicoob'])):
        die();
    endif;
    $boleto->processarBoletoSicoob();
endif;

/* == Enviar Boleto Email == */
if (isset($_POST['emailBoleto'])):
    if (intval($_POST['emailBoleto']) == 0 || empty($_POST['emailBoleto'])):
        die();
    endif;
    $boleto->emailBoleto();
endif;

/* == Confirmar Remessa == */
if (isset($_POST['confirmarRemessa'])):
    if (intval($_POST['confirmarRemessa']) == 0 || empty($_POST['confirmarRemessa'])):
        die();
    endif;

    $id_banco = post('id_banco');
    $data = array(
        'remessa' => '1',
        'data_remessa' => "NOW()"
    );
    $db->update("receita", $data, "remessa = 9 AND id_banco = $id_banco");

    if ($db->affected()) {
        print Filter::msgOk(lang('FINANCEIRO_REMESSA_OK'), "index.php?do=extrato&acao=arquivoboletos");
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Onibus == */
if (isset($_POST['processarOnibus'])):
    if (intval($_POST['processarOnibus']) == 0 || empty($_POST['processarOnibus'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $onibus->processarOnibus();
endif;

/* == Delete Onibus== */
if (isset($_POST['apagarOnibus'])):
    if (intval($_POST['apagarOnibus']) == 0 || empty($_POST['apagarOnibus'])):
        die();
    endif;

    $id = intval($_POST['apagarOnibus']);
    $db->delete("onibus", "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('ONIBUS_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Usuario Onibus == */
if (isset($_POST['processarUsuarioOnibus'])):
    if (intval($_POST['processarUsuarioOnibus']) == 0 || empty($_POST['processarUsuarioOnibus'])):
        die();
    endif;
    $onibus->processarUsuarioOnibus();
endif;

/* == Delete Usuario Onibus== */
if (isset($_POST['apagarUsuarioOnibus'])):
    if (intval($_POST['apagarUsuarioOnibus']) == 0 || empty($_POST['apagarUsuarioOnibus'])):
        die();
    endif;

    $id = intval($_POST['apagarUsuarioOnibus']);
    $db->delete("usuario_onibus", "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('ONIBUS_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Usuario == */
if (isset($_POST['processarUsuario'])):
    if (intval($_POST['processarUsuario']) == 0 || empty($_POST['processarUsuario'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $usuario->processarUsuario();
endif;

/* == Editar Usuario == */
if (isset($_POST['editarUsuario'])):
    if (intval($_POST['editarUsuario']) == 0 || empty($_POST['editarUsuario'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $usuario->editarUsuario();
endif;

/* == Delete Usuario== */
if (isset($_POST['apagarUsuario'])):
    if (intval($_POST['apagarUsuario']) == 0 || empty($_POST['apagarUsuario'])):
        die();
    endif;

    $id = intval($_POST['apagarUsuario']);
    if ($id == 1):
        print Filter::msgError(lang('MSG_ERRO_USUARIO'));
    else:
        $sql = "SELECT b.id "
            . "\n FROM bonus AS b "
            . "\n  WHERE b.id_status = 0 AND b.id_usuario = '$id' ";
        $brow = $db->first($sql);

        $sql = "SELECT d.id "
            . "\n FROM descontos AS d"
            . "\n  WHERE d.id_status = 0 AND d.id_usuario = '$id' ";
        $drow = $db->first($sql);
        if ($brow) {
            print Filter::msgAlert(lang('MSG_ERRO_BONUS'));
        } elseif ($drow) {
            print Filter::msgAlert(lang('MSG_ERRO_DESCONTO'));
        } else {
            $active = getValue("active", "usuario", "id = " . $id);
            if ($active == 'y') {
                $data = array(
                    'active' => 'n'
                );
                $db->update("usuario", $data, "id=" . $id);
            } else {
                $db->delete("usuario", "id=" . $id);
            }
            $nome = sanitize(post('title'));
            print ($db->affected()) ? Filter::msgOk(str_replace("[NOME]", $nome, lang('USUARIO_APAGAR_OK'))) : Filter::msgAlert(lang('NAOPROCESSADO'));
        }
    endif;
endif;

/* == Ativar Usuario== */
if (isset($_POST['ativarUsuario'])):
    if (intval($_POST['ativarUsuario']) == 0 || empty($_POST['ativarUsuario'])):
        die();
    endif;

    $id = intval($_POST['ativarUsuario']);
    $data = array(
        'active' => 'y'
    );
    $db->update("usuario", $data, "id=" . $id);
    $nome = sanitize(post('title'));
    print ($db->affected()) ? Filter::msgOk(str_replace("[NOME]", $nome, lang('USUARIO_ATIVADO_OK')), "index.php?do=usuario&acao=listar") : Filter::msgAlert(lang('NAOPROCESSADO'));
endif;
?>
<?php
/* == Processar Meta DRE == */
if (isset($_POST['processarMetaDRE'])):
    if (intval($_POST['processarMetaDRE']) == 0 || empty($_POST['processarMetaDRE'])):
        die();
    endif;
    $faturamento->processarMetaDRE();
endif;

/* == Delete Meta DRE == */
if (isset($_POST['apagarMetaDRE'])):
    if (intval($_POST['apagarMetaDRE']) == 0 || empty($_POST['apagarMetaDRE'])):
        die();
    endif;

    $id = intval($_POST['apagarMetaDRE']);
    $db->delete("conta_meta", "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('META_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Processar Meta == */
if (isset($_POST['processarMeta'])):
    if (intval($_POST['processarMeta']) == 0 || empty($_POST['processarMeta'])):
        die();
    endif;
    $rh->processarMeta();
endif;

/* == Delete Meta== */
if (isset($_POST['apagarMeta'])):
    if (intval($_POST['apagarMeta']) == 0 || empty($_POST['apagarMeta'])):
        die();
    endif;

    $id = intval($_POST['apagarMeta']);
    $db->delete("meta", "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('META_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>

<?php
/* == Processar Descontos == */
if (isset($_POST['processarDescontos'])):
    if (intval($_POST['processarDescontos']) == 0 || empty($_POST['processarDescontos'])):
        die();
    endif;
    $rh->processarDescontos();
endif;

/* == Delete Descontos == */
if (isset($_POST['apagarDescontos'])):
    if (intval($_POST['apagarDescontos']) == 0 || empty($_POST['apagarDescontos'])):
        die();
    endif;

    $id = intval($_POST['apagarDescontos']);
    $data = array(
        'id_status' => 2,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("descontos", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('DESCONTOS_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Bonus == */
if (isset($_POST['processarBonus'])):
    if (intval($_POST['processarBonus']) == 0 || empty($_POST['processarBonus'])):
        die();
    endif;
    $rh->processarBonus();
endif;

/* == Delete Bonus == */
if (isset($_POST['apagarBonus'])):
    if (intval($_POST['apagarBonus']) == 0 || empty($_POST['apagarBonus'])):
        die();
    endif;

    $id = intval($_POST['apagarBonus']);
    $data = array(
        'id_status' => 2,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("bonus", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('BONUS_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar INSS == */
if (isset($_POST['processarINSS'])):
    if (intval($_POST['processarINSS']) == 0 || empty($_POST['processarINSS'])):
        die();
    endif;
    $rh->processarINSS();
endif;

/* == Delete INSS == */
if (isset($_POST['apagarINSS'])):
    if (intval($_POST['apagarINSS']) == 0 || empty($_POST['apagarINSS'])):
        die();
    endif;

    $id = intval($_POST['apagarINSS']);
    $db->delete("inss", "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('INSS_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>

<?php
/* == Processar Adiantamentos == */
if (isset($_POST['processarAdiantamentos'])):
    if (intval($_POST['processarAdiantamentos']) == 0 || empty($_POST['processarAdiantamentos'])):
        die();
    endif;
    $rh->processarAdiantamentos();
endif;

/* == Delete Adiantamentos == */
if (isset($_POST['apagarAdiantamentos'])):
    if (intval($_POST['apagarAdiantamentos']) == 0 || empty($_POST['apagarAdiantamentos'])):
        die();
    endif;

    $data = $_POST['apagarAdiantamentos'];
    $db->delete("adiantamentos", "mes_ano = '" . $data . "'");
    if ($db->affected()) {
        print Filter::msgOk(lang('ADIANTAMENTO_CANCELADO_OK'), "index.php?do=rh&acao=adiantamentos&datafiltro=" . $data);
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Despesas == */
if (isset($_POST['processarDespesas'])):
    if (intval($_POST['processarDespesas']) == 0 || empty($_POST['processarDespesas'])):
        die();
    endif;
    $despesa->processarDespesas();
endif;

/* == Processar Nota Fiscal Despesas == */
if (isset($_POST['processarNotaFiscalDespesas'])):
    if (intval($_POST['processarNotaFiscalDespesas']) == 0 || empty($_POST['processarNotaFiscalDespesas'])):
        die();
    endif;
    $despesa->processarNotaFiscalDespesas();
endif;

/* == Processar Nova Despesas == */
if (isset($_POST['processarNovaDespesas'])):
    if (intval($_POST['processarNovaDespesas']) == 0 || empty($_POST['processarNovaDespesas'])):
        die();
    endif;
    $despesa->processarNovaDespesas();
endif;

/* == Agrupar Despesas == */
if (isset($_POST['agruparDespesas'])):
    if (intval($_POST['agruparDespesas']) == 0 || empty($_POST['agruparDespesas'])):
        die();
    endif;
    $despesa->agruparDespesas();
endif;

/* == Agrupar e pagar cart�es == */
if (isset($_POST['agruparPagarDespesasCartoes'])):
    if (intval($_POST['agruparPagarDespesasCartoes']) == 0 || empty($_POST['agruparPagarDespesasCartoes'])):
        die();
    endif;
    $despesa->agruparPagarDespesasCartoes();
endif;

/* == Editar Despesas == */
if (isset($_POST['editarDespesas'])):
    if (intval($_POST['editarDespesas']) == 0 || empty($_POST['editarDespesas'])):
        die();
    endif;
    $despesa->editarDespesas();
endif;

/* == Duplicar Despesas == */
if (isset($_POST['duplicarDespesas'])):
    if (intval($_POST['duplicarDespesas']) == 0 || empty($_POST['duplicarDespesas'])):
        die();
    endif;
    $despesa->duplicarDespesas();
endif;

/* == Editar Despesas Pagas == */
if (isset($_POST['editarDespesasPagas'])):
    if (intval($_POST['editarDespesasPagas']) == 0 || empty($_POST['editarDespesasPagas'])):
        die();
    endif;
    $despesa->editarDespesasPagas();
endif;

/* == Processar Pagamento Despesa == */
if (isset($_POST['processarPagamentoDespesas'])):
    if (intval($_POST['processarPagamentoDespesas']) == 0 || empty($_POST['processarPagamentoDespesas'])):
        die();
    endif;
    $id = intval($_POST['id_despesa']);
    $despesa->processarPagamentoDespesas($id);
endif;

/* == Salvar Duplicata == */
if (isset($_POST['salvarDuplicata'])):
    if (intval($_POST['salvarDuplicata']) == 0 || empty($_POST['salvarDuplicata'])):
        die();
    endif;

    $id = intval($_POST['salvarDuplicata']);
    $data = array(
        'pago' => '0',
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );

    $db->update("despesa", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('FINANCEIRO_DESPESAS_ADICIONADO_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Delete Despesas == */
if (isset($_POST['apagarDespesas'])):
    if (intval($_POST['apagarDespesas']) == 0 || empty($_POST['apagarDespesas'])):
        die();
    endif;

    $id = intval($_POST['apagarDespesas']);
    $data = array(
        'inativo' => '1',
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );

    $db->update("despesa", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('FINANCEIRO_DESPESAS_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Editar Receitas == */
if (isset($_POST['editarReceitas'])):
    if (intval($_POST['editarReceitas']) == 0 || empty($_POST['editarReceitas'])):
        die();
    endif;
    $faturamento->editarReceitas();
endif;

/* == Processar Receita Rapida == */
if (isset($_POST['processarReceitaRapida'])):
    if (intval($_POST['processarReceitaRapida']) == 0 || empty($_POST['processarReceitaRapida'])):
        die();
    endif;
    $faturamento->processarReceitaRapida();
endif;

/* == Delete Receita == */
if (isset($_POST['apagarReceita'])):
    if (intval($_POST['apagarReceita']) == 0 || empty($_POST['apagarReceita'])):
        die();
    endif;

    $id = intval($_POST['apagarReceita']);
    $data = array(
        'inativo' => '1',
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );

    $db->update("receita", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('FINANCEIRO_RECEITA_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Delete Receita == */
if (isset($_POST['apagarDespesaReceitaTransferenciaBancos'])):
    if (intval($_POST['apagarDespesaReceitaTransferenciaBancos']) == 0 || empty($_POST['apagarDespesaReceitaTransferenciaBancos'])):
        die();
    endif;

    $id_receita = intval($_POST['id_receita']);
    $id_despesa = intval($_POST['id_despesa']);

    $data = array(
        'inativo' => '1',
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("receita", $data, "id=" . $id_receita);
    $db->update("despesa", $data, "id=" . $id_despesa);
    if ($db->affected()) {
        print Filter::msgOk(lang('BANCO_TRANSFERENCIA_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Delete Receita == */
if (isset($_POST['apagarReceitaNFe'])):
    if (intval($_POST['apagarReceitaNFe']) == 0 || empty($_POST['apagarReceitaNFe'])):
        die();
    endif;

    $id = intval($_POST['apagarReceitaNFe']);
    $data = array(
        'inativo' => '1',
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("receita", $data, "id=" . $id);

    if ($db->affected()) {
        $id_nota = getValue("id_nota", "receita", "id=" . $id);
        $row_receitas = $faturamento->obterReceitasNFeDuplicatas($id_nota);
        $duplicatas = "";
        if ($row_receitas) {
            $contador = 1;
            foreach ($row_receitas as $rrow) {
                $aux_contador = str_pad($contador++, 3, '0', STR_PAD_LEFT);
                $duplicatas .= "($aux_contador, " . exibedata($rrow->data_pagamento) . ", " . moeda($rrow->valor) . ")";
            }
        }
        $data_duplicatas = array(
            'duplicatas' => $duplicatas
        );
        $db->update("nota_fiscal", $data_duplicatas, "id=" . $id_nota);

        print Filter::msgOk(lang('FINANCEIRO_RECEITA_APAGAR_OK'), 'index.php?do=notafiscal&acao=visualizar&id=' . $id_nota);
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Estornar Cheque == */
if (isset($_POST['estornarCheque'])):
    if (intval($_POST['estornarCheque']) == 0 || empty($_POST['estornarCheque'])):
        die();
    endif;

    $id = intval($_POST['estornarCheque']);
    $data = array(
        'pago' => 2,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );

    $db->update("receita", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('FINANCEIRO_ESTORNAR_OK'), 'index.php?do=faturamento&acao=cheques');
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Pagar Cheque == */
if (isset($_POST['pagarCheque'])):
    if (intval($_POST['pagarCheque']) == 0 || empty($_POST['pagarCheque'])):
        die();
    endif;
    $id = intval($_POST['id_receita']);
    if (isset($_POST['id_banco'])) {
        $id_banco = intval($_POST['id_banco']);
        $data = array(
            'id_banco' => $id_banco,
            'pago' => 1,
            'data_recebido' => "NOW()",
            'data_fiscal' => "NOW()",
            'usuario' => session('nomeusuario'),
            'data' => "NOW()"
        );

        $db->update("receita", $data, "id=" . $id);
        if ($db->affected()) {
            print Filter::msgOk(lang('FINANCEIRO_CHEQUES_OK'), 'index.php?do=faturamento&acao=cheques');
        } else {
            print Filter::msgAlert(lang('NAOPROCESSADO'));
        }
    } else {
        print Filter::msgAlert(lang('MSG_ERRO_BANCO'));
    }
endif;

/* == Pagar Financeiro == */
if (isset($_POST['pagarFinanceiro'])):
    if (intval($_POST['pagarFinanceiro']) == 0 || empty($_POST['pagarFinanceiro'])):
        die();
    endif;

    if (empty($_POST['id_banco'])) {
        print Filter::msgAlert(lang('MSG_ERRO_BANCO'));
        return false;
    }

    $id = intval($_POST['id_controle']);
    $row_receita = Core::getRowById("receita", $id);
    $id_categoria_pagamento = getValue("id_categoria", "tipo_pagamento", "id=" . $row_receita->tipo);
    $promissoria = getValue("promissoria", "receita", "id=" . $id);

    $tipo_pagamento = 0;
    if (isset($_POST['tipo_pagamento_crediario']) && !empty($_POST['tipo_pagamento_crediario'])) {
        $tipo_pagamento = intval(post('tipo_pagamento_crediario'));
    } elseif ($id_categoria_pagamento == 9) {
        print Filter::msgAlert(lang('MSG_ERRO_CREDIARIO_TIPO'));
        return false;
    }

    $novareceita = intval(post('novareceita'));
    $id_banco = post('id_banco');
    $valor_pago = converteMoeda(post('valor_pago'));
    $data_recebido = (empty($_POST['data_recebido'])) ? "NOW()" : dataMySQL(post('data_recebido'));
    if ($novareceita == 1) {
        if ($valor_pago < $row_receita->valor) {
            $valor_pagar = $row_receita->valor - $valor_pago;
            $data_nova = array(
                'id_empresa' => $row_receita->id_empresa,
                'id_nota' => $row_receita->id_nota,
                'id_conta' => $row_receita->id_conta,
                'id_banco' => $id_banco,
                'id_caixa' => $row_receita->id_caixa,
                'id_venda' => $row_receita->id_venda,
                'id_pagamento' => $row_receita->id_pagamento,
                'id_cadastro' => $row_receita->id_cadastro,
                'duplicata' => $row_receita->duplicata,
                'descricao' => $row_receita->descricao,
                'promissoria' => $row_receita->promissoria,
                'valor' => $valor_pagar,
                'valor_pago' => $valor_pagar,
                'data_pagamento' => $row_receita->data_pagamento,
                'parcela' => $row_receita->parcela,
                'tipo' => $row_receita->tipo,
                'usuario' => session('nomeusuario'),
                'data' => "NOW()"
            );
            $db->insert("receita", $data_nova);
        }
    } else {
        $valor_pago = ($valor_pago > 0) ? $valor_pago : getValue("valor", "receita", "id = " . $id);
    }

    $data = array(
        'id_banco' => $id_banco,
        'valor_pago' => $valor_pago,
        'pago' => "1",
        'data_recebido' => $data_recebido,
        'data_fiscal' => $data_recebido,
        'tipo' => ($tipo_pagamento > 0) ? $tipo_pagamento : $row_receita->tipo,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("receita", $data, "id=" . $id);
    $id_nota = getValue("id_nota", "receita", "id = " . $id);
    if ($id_nota != 0) {
        $redirecionar = "index.php?do=notafiscal&acao=visualizar&id=" . $id_nota;
    } else if ($promissoria == 1) {
        $redirecionar = "index.php?do=faturamento&acao=receber_crediario";
    } else {
        $redirecionar = "index.php?do=faturamento&acao=receber";
    }
    if ($db->affected()) {
        print Filter::msgOk(lang('FINANCEIRO_RECEITA_PAGAMENTO_OK'), $redirecionar);
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Pagar Financeiro dentro da Receita no cadastro do cliente== */
if (isset($_POST['pagarFinanceiroReceita'])):
    if (intval($_POST['pagarFinanceiroReceita']) == 0 || empty($_POST['pagarFinanceiroReceita'])):
        die();
    endif;

    if (empty($_POST['id_banco'])) {
        print Filter::msgAlert(lang('MSG_ERRO_BANCO'));
        return false;
    }

    $id_usuario = $usuario->uid;
    $id_caixa = $faturamento->verificaCaixa($id_usuario);
    if (!$id_caixa || $id_caixa <= 0) {
        print Filter::msgAlert(lang('CAIXA_VENDA_ERRO'));
        return false;
    }

    $id = intval($_POST['id_controle']);
    $row_receita = Core::getRowById("receita", $id);
    $id_categoria_pagamento = getValue("id_categoria", "tipo_pagamento", "id=" . $row_receita->tipo);

    $tipo_pagamento = 0;
    if (isset($_POST['tipo_pagamento_crediario']) && !empty($_POST['tipo_pagamento_crediario'])) {
        $tipo_pagamento = intval(post('tipo_pagamento_crediario'));
    } elseif ($id_categoria_pagamento == 9) {
        print Filter::msgAlert(lang('MSG_ERRO_CREDIARIO_TIPO'));
        return false;
    }

    $novareceita = intval(post('novareceita'));
    $id_banco = post('id_banco');
    $valor_pago = converteMoeda(post('valor_pago'));
    $data_recebido = (empty($_POST['data_recebido'])) ? "NOW()" : dataMySQL(post('data_recebido'));
    if ($novareceita == 1) {
        if ($valor_pago < $row_receita->valor) {
            $valor_pagar = $row_receita->valor - $valor_pago;
            $data_nova = array(
                'id_empresa' => $row_receita->id_empresa,
                'id_nota' => $row_receita->id_nota,
                'id_conta' => $row_receita->id_conta,
                'id_banco' => $id_banco,
                'id_caixa' => $id_caixa,
                'id_venda' => $row_receita->id_venda,
                'id_pagamento' => $row_receita->id_pagamento,
                'id_cadastro' => $row_receita->id_cadastro,
                'duplicata' => $row_receita->duplicata,
                'descricao' => $row_receita->descricao,
                'valor' => $valor_pagar,
                'valor_pago' => $valor_pagar,
                'data_pagamento' => $row_receita->data_pagamento,
                'parcela' => $row_receita->parcela,
                'tipo' => $row_receita->tipo,
                'usuario' => session('nomeusuario'),
                'data' => "NOW()"
            );
            $db->insert("receita", $data_nova);
        }
    } else {
        $valor_pago = ($valor_pago > 0) ? $valor_pago : getValue("valor", "receita", "id = " . $id);
    }

    $data = array(
        'id_banco' => $id_banco,
        'valor_pago' => $valor_pago,
        'pago' => "1",
        'id_caixa' => $id_caixa,
        'data_recebido' => $data_recebido,
        'tipo' => ($tipo_pagamento > 0) ? $tipo_pagamento : $row_receita->tipo,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("receita", $data, "id=" . $id);

    $id_cadastro = getValue("id_cadastro", "receita", "id = " . $id);
    if ($id_cadastro) {
        $redirecionar = "index.php?do=cadastro&acao=receitas&id=" . $id_cadastro;
    }
    if ($db->affected()) {
        print Filter::msgOk(lang('FINANCEIRO_RECEITA_PAGAMENTO_OK'), $redirecionar);
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }

endif;

?>
<?php
/* == Processar Centro de Custo == */
if (isset($_POST['processarCentroCusto'])):
    if (intval($_POST['processarCentroCusto']) == 0 || empty($_POST['processarCentroCusto'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $despesa->processarCentroCusto();
endif;

/* == Delete Centro de Custo == */
if (isset($_POST['apagarCentroCusto'])):
    if (intval($_POST['apagarCentroCusto']) == 0 || empty($_POST['apagarCentroCusto'])):
        die();
    endif;

    $id = intval($_POST['apagarCentroCusto']);
    $data = array(
        'inativo' => '1'
    );

    $db->update("centro_custo", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('CENTRO_CUSTO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Cadastro == */
if (isset($_POST['processarCadastro'])):
    if (intval($_POST['processarCadastro']) == 0 || empty($_POST['processarCadastro'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $cadastro->processarCadastro();
endif;

/* == Processar Endereco Cadastro == */
if (isset($_POST['processarEndereco'])):
    if (intval($_POST['processarEndereco']) == 0 || empty($_POST['processarEndereco'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $cadastro->processarEndereco();
endif;

/* == Processar Cadastro CNPJ == */
if (isset($_POST['processarCNPJReceita'])):
    if (intval($_POST['processarCNPJReceita']) == 0 || empty($_POST['processarCNPJReceita'])):
        die();
    endif;
    $cadastro->processarCNPJReceita();
endif;

/* == Delete Cadastro== */
if (isset($_POST['apagarCadastro'])):
    if (intval($_POST['apagarCadastro']) == 0 || empty($_POST['apagarCadastro'])):
        die();
    endif;

    $id = intval($_POST['apagarCadastro']);

    $sql = "SELECT  SUM(c.valor)        AS valor,
                    SUM(c.valor_pago)   AS valor_pago

            FROM    cadastro_crediario AS c

            WHERE   c.inativo = 0
            AND     c.pago <> 1
            AND     c.id_cadastro = $id ";

    $row = $db->first($sql);

    $valida = false;
    if ($row) {
        $valida = $row->valor - $row->valor_pago > 0 ? false: true;
    } else {
        $valida = true;
    }

    if ($valida) {
        $db->delete("produto_fornecedor", "id_cadastro=" . $id);
        $data = array(
            'inativo' => '1'
        );
        $db->update("cadastro", $data, "id=" . $id);

        if ($db->affected()) {
            print Filter::msgOk(lang('CADASTRO_APAGAR_OK'));
        } else {
            print Filter::msgAlert(lang('NAOPROCESSADO'));
        }
    } else {
        print Filter::msgAlert('Cliente possui débitos pendentes no crediário!');
    }
endif;

/* == Delete Contato de Cadastro == */
if (isset($_POST['apagarContato'])):
    if (intval($_POST['apagarContato']) == 0 || empty($_POST['apagarContato'])):
        die();
    endif;
    $id = intval($_POST['apagarContato']);
    $data = array(
        'inativo' => '1'
    );
    $db->update("cadastro_contato", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('CONTATO_CONTRATO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('CONTATO_CONTRATO_APAGAR_ERRO'));
    }
endif;


/* == Delete Endereco Cadastro== */
if (isset($_POST['apagarEndereco'])):
    if (intval($_POST['apagarEndereco']) == 0 || empty($_POST['apagarEndereco'])):
        die();
    endif;

    $id = intval($_POST['apagarEndereco']);
    $data = array(
        'inativo' => '1'
    );

    $db->update("cadastro_endereco", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('CADASTRO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Processar Cadastro Retorno == */
if (isset($_POST['processarCadastroRetorno'])):
    if (intval($_POST['processarCadastroRetorno']) == 0 || empty($_POST['processarCadastroRetorno'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $cadastro->processarCadastroRetorno();
endif;

/* == Processar Vendas Cadastro == */
if (isset($_POST['processarVendaCadastro'])):
    if (intval($_POST['processarVendaCadastro']) == 0 || empty($_POST['processarVendaCadastro'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id_cadastro'])) ? $_POST['id_cadastro'] : 0;
    $cadastro->processarVendaCadastro();
endif;

/* == Processar Venda Rapida == */
if (isset($_POST['processarVendaRapida'])):
    if (intval($_POST['processarVendaRapida']) == 0 || empty($_POST['processarVendaRapida'])):
        die();
    endif;
    $cadastro->processarVendaRapida();
endif;

/* == Converter/Agrupar Vendas para Emissao Fiscal em Lote == */
if (isset($_POST['converter_vendas'])):
    if (intval($_POST['converter_vendas']) == 0 || empty($_POST['converter_vendas'])):
        die();
    endif;
    $quantidade = $_POST['quantidade'];
    $valor = $_POST['valor'];
    $dataini = $_POST['datai'];
    $datafim = $_POST['dataf'];
    $categorias_selecionadas = $_POST['cat'];
    $cliente = $_POST['cli'];
    $id_unico = $_POST['id_unico'];
    $faturamento->converterVendasEmissao($quantidade,$valor,$dataini,$datafim,$categorias_selecionadas,$id_unico,$cliente);
endif;

/* == Processar Nova Venda == */
if (isset($_POST['processarNovaVenda'])):
    if (intval($_POST['processarNovaVenda']) == 0 || empty($_POST['processarNovaVenda'])):
        die();
    endif;
    if (empty($_POST['salvar_crediario'])) {
        if (empty($_POST['salvar']) && empty($_POST['salvar_orcamento'])) {
            Filter::$id = (!empty($_POST['id_venda'])) ? post('id_venda') : 0;
            $id_usuario = $usuario->uid;
            $id_caixa = $faturamento->verificaCaixa($id_usuario);

            if ($id_caixa > 0) {
                $cadastro->processarNovaVenda($id_caixa);
            } else {
                print Filter::msgAlert(lang('CAIXA_VENDA_ERRO'));
            }
        } else {
            $cadastro->processarNovaVenda();
        }
    } else {
        $id_usuario = $usuario->uid;
        $id_caixa = $faturamento->verificaCaixa($id_usuario);
        $id_cadastro = (!empty($_POST['id_cadastro'])) ? $_POST['id_cadastro'] : 0;
        if ($id_caixa > 0) {
            $cadastro->processarVendaCrediario($id_caixa, $id_cadastro);
        } else {
            print Filter::msgAlert(lang('CAIXA_VENDA_ERRO'));
        }
    }
endif;

/* == Processar Finalizar Crediario == */
if (isset($_POST['processarPagamentoCrediario'])):
    if (intval($_POST['processarPagamentoCrediario']) == 0 || empty($_POST['processarPagamentoCrediario'])):
        die();
    endif;
    Filter::$id = (!empty($_POST['id_cliente'])) ? $_POST['id_cliente'] : 0;

    $id_usuario = $usuario->uid;
    $id_caixa = $faturamento->verificaCaixa($id_usuario);
    if ($id_caixa > 0) {
        $cadastro->processarPagamentoCrediario($id_caixa);
    } else {
        print Filter::msgAlert(lang('CAIXA_VENDA_ERRO'));
    }
endif;

/* == Pagar Crediarios Clientes  == */
if (isset($_POST['processarPagamentoCrediarioClientes'])):
    if (intval($_POST['processarPagamentoCrediarioClientes']) == 0 || empty($_POST['processarPagamentoCrediarioClientes'])):
        die();
    endif;

    $id_usuario = $usuario->uid;
    $id_caixa = $faturamento->verificaCaixa($id_usuario);

    if ($id_caixa > 0) {
        $cadastro->processarPagamentoCrediarioClientes($id_caixa);
    } else {
        print Filter::msgAlert(lang('CAIXA_VENDA_ERRO'));
    }
endif;

/* == Atualizar crediarios == */
if (isset($_POST['atualizar_crediario'])):
    if (intval($_POST['atualizar_crediario']) == 0 || empty($_POST['atualizar_crediario'])):
        die();
    endif;
    $cadastro->atualizar_crediario();
endif;

/* == Apagar pagamento de crediário == */
if (isset($_POST['apagarPagamentoCrediario'])):
    $cadastro->apagarPagamentoCrediario();
endif;

/* == Processar Retorno == */
if (isset($_POST['processarCadastroRetorno'])):
    if (intval($_POST['processarCadastroRetorno']) == 0 || empty($_POST['processarCadastroRetorno'])):
        die();
    endif;
    $cadastro->processarCadastroRetorno();
endif;

/* == Processar TROCA DE PRODUTOS == */
if (isset($_POST['processarTrocaProduto'])):
    if (intval($_POST['processarTrocaProduto']) == 0 || empty($_POST['processarTrocaProduto'])):
        die();
    endif;
    if (empty($_POST['salvar'])) {
        Filter::$id = (!empty($_POST['id_venda'])) ? post('id_venda') : 0;
        $id_usuario = $usuario->uid;
        $id_caixa = $faturamento->verificaCaixa($id_usuario);

        if ($id_caixa > 0) {
            $produto->processarTrocaProduto($id_caixa);
        } else {
            print Filter::msgAlert(lang('CAIXA_VENDA_ERRO'));
        }
    } else {
        $produto->processarTrocaProduto();
    }

endif;

/* == Processar Finalizar Venda == */
if (isset($_POST['processarFinalizarVenda'])):
    if (intval($_POST['processarFinalizarVenda']) == 0 || empty($_POST['processarFinalizarVenda'])):
        die();
    endif;
    Filter::$id = (!empty($_POST['id_venda'])) ? post('id_venda') : 0;
    $id_usuario = $usuario->uid;
    $id_caixa = $faturamento->verificaCaixa($id_usuario);

    if ($id_caixa > 0) {
        $cadastro->processarFinalizarVenda($id_caixa);
    } else {
        print Filter::msgAlert(lang('CAIXA_VENDA_ERRO'));
    }

endif;

/* == Processar Venda Produto == */
if (isset($_POST['processarVendaProduto'])):
    if (intval($_POST['processarVendaProduto']) == 0 || empty($_POST['processarVendaProduto'])):
        die();
    endif;
    $cadastro->processarVendaProduto();
endif;

/* == Processar Definir novo entregar para a venda == */
if (isset($_POST['processarDefinirEntregador'])):
    if (intval($_POST['processarDefinirEntregador']) == 0 || empty($_POST['processarDefinirEntregador'])):
        die();
    endif;
    $cadastro->processarDefinirEntregador();
endif;

/* == Processar Atualização de observação e prazo de entrega na Venda de Produto == */
if (isset($_POST['processarAtualizarDadosVenda'])):
    if (intval($_POST['processarAtualizarDadosVenda']) == 0 || empty($_POST['processarAtualizarDadosVenda'])):
        die();
    endif;
    $cadastro->processarAtualizarDadosVenda();
endif;

/* == Processar Atualização de desconto na Venda de Produto == */
if (isset($_POST['processarAtualizarDescontoVenda'])):
    if (intval($_POST['processarAtualizarDescontoVenda']) == 0 || empty($_POST['processarAtualizarDescontoVenda'])):
        die();
    endif;
    $cadastro->processarAtualizarDescontoVenda();
endif;

/* == Processar Atualização de acrescimo na Venda de Produto == */
if (isset($_POST['processarAtualizarAcrescimoVenda'])):
    if (intval($_POST['processarAtualizarAcrescimoVenda']) == 0 || empty($_POST['processarAtualizarAcrescimoVenda'])):
        die();
    endif;
    $cadastro->processarAtualizarAcrescimoVenda();
endif;

/* == Processar Atualização de produtos na Venda em Aberto == */
if (isset($_POST['processarAtualizarProdutoVendaAberto'])):
    if (intval($_POST['processarAtualizarProdutoVendaAberto']) == 0 || empty($_POST['processarAtualizarProdutoVendaAberto'])):
        die();
    endif;
    $cadastro->processarAtualizarProdutoVendaAberto();
endif;

/* == Delete Venda Produto == */
if (isset($_POST['apagarVendaProduto'])):
    if (intval($_POST['apagarVendaProduto']) == 0 || empty($_POST['apagarVendaProduto'])):
        die();
    endif;

    $sucesso = 0;
    $id = intval($_POST['apagarVendaProduto']);
    $row_cadastro_vendas = Core::getRowById("cadastro_vendas", $id);
    $row_vendas = Core::getRowById("vendas", $row_cadastro_vendas->id_venda);

    $id_cadastro = $row_cadastro_vendas->id_cadastro;
    $id_venda = $row_cadastro_vendas->id_venda;
    $quantidade = $row_cadastro_vendas->quantidade;
    $id_produto = $row_cadastro_vendas->id_produto;
    $id_caixa = $row_cadastro_vendas->id_caixa;
    $id_empresa = ($row_cadastro_vendas->id_empresa) ? $row_cadastro_vendas->id_empresa : 1;

    $nomecadastro = getValue("nome", "cadastro", "id=" . $id_cadastro);
    $kit = getValue("kit", "produto", "id=" . $id_produto);
    if ($kit) {
        $nomekit = getValue("nome", "produto", "id=" . $id_produto);
        $sql = "SELECT k.id, k.id_produto, p.nome, p.estoque, k.quantidade "
			. "\n FROM produto_kit as k"
			. "\n LEFT JOIN produto as p ON p.id = k.id_produto "
			. "\n WHERE k.id_produto_kit = $id_produto AND k.materia_prima=0"
			. "\n ORDER BY p.nome ";
        $retorno_row = $db->fetch_all($sql);
        if ($retorno_row) {
            foreach ($retorno_row as $exrow) {
                $observacao = "CANCELAMENTO DE VENDA DE KIT [$nomekit] PARA CADASTRO: " . $nomecadastro;
                $quant_estoque_kit = $quantidade * $exrow->quantidade;
                $data_estoque = array(
                    'id_empresa' => $id_empresa,
                    'id_produto' => $exrow->id_produto,
                    'quantidade' => $quant_estoque_kit,
                    'tipo' => 1,
                    'motivo' => 7,
                    'observacao' => $observacao,
                    'id_ref' => $id,
                    'usuario' => session('nomeusuario'),
                    'data' => "NOW()"
                );
                $db->insert("produto_estoque", $data_estoque);
                $totalestoque = $cadastro->getEstoqueTotal($exrow->id_produto);
                $data_update = array(
                    'estoque' => $totalestoque,
                    'usuario' => session('nomeusuario'),
                    'data' => "NOW()"
                );
                $db->update("produto", $data_update, "id=" . $exrow->id_produto);
            }
        }
    }

    $observacao = "CANCELAMENTO DE VENDA (" . $id_venda . ") DE PRODUTO PARA CADASTRO: " . $nomecadastro;
    $data_estoque = array(
        'id_empresa' => $id_empresa,
        'id_produto' => $id_produto,
        'quantidade' => $quantidade,
        'tipo' => 1,
        'motivo' => 7,
        'observacao' => $observacao,
        'id_ref' => $id,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->insert("produto_estoque", $data_estoque);
    $totalestoque = $cadastro->getEstoqueTotal($id_produto);
    $data_update = array(
        'estoque' => $totalestoque,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("produto", $data_update, "id=" . $id_produto);

    $data = array(
        'inativo' => '1',
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("cadastro_vendas", $data, "id=" . $id);

    $produtos_venda = $cadastro->getProdutosAtivosDaVenda($id_venda);
    if ($produtos_venda) {
        $valor_acrescimo_venda = getValue("valor_despesa_acessoria", "vendas", "id=" . $id_venda);
        $valor_acrescimo_produto = round(($valor_acrescimo_venda / count($produtos_venda)), 2);
        $porcentagem_desconto = ($row_vendas->valor_desconto * 100) / $row_vendas->valor_total;
        foreach ($produtos_venda as $produto_venda) {
            $desconto_produto = ($porcentagem_desconto * $produto_venda->valor_total) / 100;
            $desconto_produto = round($desconto_produto, 2);
            $data_desconto = array(
                'valor_desconto' => $desconto_produto,
                'valor_despesa_acessoria' => $valor_acrescimo_produto,
                'usuario' => session('nomeusuario'),
                'data' => "NOW()"
            );
            $db->update("cadastro_vendas", $data_desconto, "id=" . $produto_venda->id);
        }
    }

    ///////////////////////////////////
    //Este trecho de codigo serve para ajustar o valor do desconto quando o mesmo for quebrado e gerar diferenca no total.
    $novo_valor_desconto = $cadastro->obterDescontosVenda($id_venda, $id_caixa, $id_empresa, $id_cadastro);
    if (round($novo_valor_desconto->vlr_desc, 2) != round($row_vendas->valor_desconto, 2)) {
        $novo_desconto = ($row_vendas->valor_desconto - round($novo_valor_desconto->vlr_desc, 2)) + $novo_valor_desconto->valor_desconto;
        $data_desconto = array('valor_desconto' => $novo_desconto);
        $db->update("cadastro_vendas", $data_desconto, "id=" . $novo_valor_desconto->id);
    }
    ///////////////////////////////////
    //Este trecho de codigo serve para ajustar o valor do acrescimo quando o mesmo for quebrado e gerar diferenca no total.
    $novo_valor_acrescimo = $cadastro->obterAcrescimoVenda($id_venda, $id_caixa, $id_empresa, $id_cadastro);
    if (round($novo_valor_acrescimo->vlr_acrescimo, 2) != round($valor_acrescimo_venda, 2)) {
        $novo_acrescimo = ($valor_acrescimo_venda - round($novo_valor_acrescimo->vlr_acrescimo, 2)) + $novo_valor_acrescimo->valor_despesa_acessoria;
        $data_acrescimo = array('valor_despesa_acessoria' => $novo_acrescimo);
        $db->update("cadastro_vendas", $data_acrescimo, "id=" . $novo_valor_acrescimo->id);
    }
    ///////////////////////////////////

    $valor_produtos_venda = $cadastro->getTotalProdutosVenda($id_venda);
    $data_update = array(
        'valor_total' => $valor_produtos_venda->valor_total,
        'valor_pago' => $valor_produtos_venda->valor_total + $valor_produtos_venda->valor_acrescimo - $valor_produtos_venda->valor_desconto,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("vendas", $data_update, "id=" . $id_venda);
    $sucesso = ($db->affected()) ? 1 : $sucesso;

    $data_update_financeiro = array(
        'valor_total_venda' => $valor_produtos_venda->valor_total + $valor_produtos_venda->valor_acrescimo - $valor_produtos_venda->valor_desconto,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("cadastro_financeiro", $data_update_financeiro, "id_venda=" . $id_venda . " AND inativo = 0");
    $sucesso = ($db->affected()) ? 1 : $sucesso;

    if ($sucesso) {
        print Filter::msgOk(lang('CADASTRO_ITEM_APAGAR_OK'), "index.php?do=vendas&acao=finalizarvenda&id=" . $id_venda);
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }

endif;

/* == Processar Financeiro  == */
if (isset($_POST['processarFinanceiro'])):
    if (intval($_POST['processarFinanceiro']) == 0 || empty($_POST['processarFinanceiro'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id_cadastro'])) ? $_POST['id_cadastro'] : 0;
    $cadastro->processarFinanceiro();

endif;

/* == Processar Financeiro  == */
if (isset($_POST['processarFinanceiroVendaAberto'])):
    if (intval($_POST['processarFinanceiroVendaAberto']) == 0 || empty($_POST['processarFinanceiroVendaAberto'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id_cadastro'])) ? $_POST['id_cadastro'] : 0;
    $cadastro->processarFinanceiroVendaAberto();

endif;

/* == Delete Financeiro Cadastro da Venda em Aberto == */
if (isset($_POST['apagarCadastroFinanceiroVendaAberta'])):
    if (intval($_POST['apagarCadastroFinanceiroVendaAberta']) == 0 || empty($_POST['apagarCadastroFinanceiroVendaAberta'])):
        die();
    endif;

    $id = intval($_POST['apagarCadastroFinanceiroVendaAberta']);
    $cadastro->apagarCadastroFinanceiroVendaAberta($id);

endif;

/* == Delete Financeiro Cadastro== */
if (isset($_POST['apagarCadastroFinanceiro'])):
    if (intval($_POST['apagarCadastroFinanceiro']) == 0 || empty($_POST['apagarCadastroFinanceiro'])):
        die();
    endif;

    $id = intval($_POST['apagarCadastroFinanceiro']);
    $id_venda = getValue("id_venda", "cadastro_financeiro", "id = " . $id);
    $data = array(
        'inativo' => '1',
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("receita", $data, "id_pagamento=" . $id);
    $db->update("cadastro_financeiro", $data, "id=" . $id);
    $total_financeiro = $cadastro->getTotalFinanceiro($id_venda);
    $valor_venda = getValue("valor_total", "vendas", "id=" . $id_venda);
    $data_vendas = array(
        'valor_pago' => $total_financeiro
    );
    if ($total_financeiro > $valor_venda) {
        $data_vendas['troco'] = $total_financeiro - $valor_venda;
    } else {
        $data_vendas['troco'] = 0;
    }

    $db->update("vendas", $data_vendas, "id=" . $id_venda);
    if ($db->affected()) {
        print Filter::msgOk(lang('CADASTRO_APAGAR_PAGAMENTO_OK'), "index.php?do=vendas&acao=finalizarvenda&id=" . $id_venda);
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Processar Cancelar Venda == */
if (isset($_POST['processarCancelarVenda'])):
    if (intval($_POST['processarCancelarVenda']) == 0 || empty($_POST['processarCancelarVenda'])):
        die();
    endif;
    $id_venda = intval($_POST['id_venda']);
    $venda_agrupamento = getValue("venda_agrupamento", "vendas", "id = " . $id_venda);

    if ($venda_agrupamento==0)
        $cadastro->processarCancelarVenda();
    else
        $cadastro->processarCancelarVendaAgrupamentoLote();
endif;

/* == Processar Cancelar Venda FISCAL== */
if (isset($_POST['processarCancelarVendaFiscal'])):
    if (intval($_POST['processarCancelarVendaFiscal']) == 0 || empty($_POST['processarCancelarVendaFiscal'])):
        die();
    endif;
    $cadastro->processarCancelarVendaFiscal();
endif;

/* == Processar vincular usuario/cliente a uma venda finalizada == */
if (isset($_POST['processarVincularClienteVenda'])):
    if (intval($_POST['processarVincularClienteVenda']) == 0 || empty($_POST['processarVincularClienteVenda'])):
        die();
    endif;
    $cadastro->processarVincularClienteVenda();
endif;

/* == Delete Venda em Aberto == */
if (isset($_POST['processarCancelarVendaAberto'])):
    if (intval($_POST['processarCancelarVendaAberto']) == 0 || empty($_POST['processarCancelarVendaAberto'])):
        die();
    endif;

    $id = intval($_POST['processarCancelarVendaAberto']);
    $cadastro->processarCancelarVendaAberto($id);
endif;

?>
<?php
/* == Processar Grupo Economico == */
if (isset($_POST['processarEconomico'])):
    if (intval($_POST['processarEconomico']) == 0 || empty($_POST['processarEconomico'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $cadastro->processarEconomico();
endif;

/* == Delete Grupo Economico == */
if (isset($_POST['apagarEconomico'])):
    if (intval($_POST['apagarEconomico']) == 0 || empty($_POST['apagarEconomico'])):
        die();
    endif;

    $id = intval($_POST['apagarEconomico']);
    $data = array(
        'inativo' => '1'
    );

    $db->update("economico", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('ECONOMICO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Origem == */
if (isset($_POST['processarOrigem'])):
    if (intval($_POST['processarOrigem']) == 0 || empty($_POST['processarOrigem'])):
        die();
    endif;
    $cadastro->processarOrigem();
endif;

/* == Definir Origem == */
if (isset($_POST['definirOrigem'])):
    if (intval($_POST['definirOrigem']) == 0 || empty($_POST['definirOrigem'])):
        die();
    endif;
    $cadastro->definirOrigem();
endif;

/* == Desativar Origem == */
if (isset($_POST['apagarOrigem'])):
    if (intval($_POST['apagarOrigem']) == 0 || empty($_POST['apagarOrigem'])):
        die();
    endif;

    $id = intval($_POST['apagarOrigem']);
    $data = array(
        'inativo' => 1
    );
    $db->update("origem", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('EDITAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;


/* == Ativar Origem == */
if (isset($_POST['ativarOrigem'])):
    if (intval($_POST['ativarOrigem']) == 0 || empty($_POST['ativarOrigem'])):
        die();
    endif;

    $id = intval($_POST['ativarOrigem']);
    $data = array(
        'inativo' => 0
    );
    $db->update("origem", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('EDITAR_OK'), "index.php?do=cadastro&acao=origem");
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Arquivo de Contatos == */
if (isset($_POST['processarArquivoContato'])):
    if (intval($_POST['processarArquivoContato']) == 0 || empty($_POST['processarArquivoContato'])):
        die();
    endif;
    $cadastro->processarArquivoContato();
endif;
?>
<?php
/* == Remover Contatos == */
if (isset($_POST['removerContatos'])):
    if (intval($_POST['removerContatos']) == 0 || empty($_POST['removerContatos'])):
        die();
    endif;
    $cadastro->removerContatos();
endif;
?>
<?php
/* == Processar Empresa == */
if (isset($_POST['processarEmpresa'])):
    if (intval($_POST['processarEmpresa']) == 0 || empty($_POST['processarEmpresa'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $empresa->processarEmpresa();
endif;

/* == Processar Empresa == */
if (isset($_POST['processarBoletoEmpresa'])):
    if (intval($_POST['processarBoletoEmpresa']) == 0 || empty($_POST['processarBoletoEmpresa'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $empresa->processarBoletoEmpresa();
endif;

/* == Delete Empresa== */
if (isset($_POST['apagarEmpresa'])):
    if (intval($_POST['apagarEmpresa']) == 0 || empty($_POST['apagarEmpresa'])):
        die();
    endif;

    $id = intval($_POST['apagarEmpresa']);

    $data = array(
        'inativo' => '1'
    );

    $db->update("empresa", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('EMPRESA_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Contato == */
if (isset($_POST['processarContato'])):
    if (intval($_POST['processarContato']) == 0 || empty($_POST['processarContato'])):
        die();
    endif;
    $cadastro->processarContato();
endif;

/* == Processar CRM == */
if (isset($_POST['processarCRM'])):
    if (intval($_POST['processarCRM']) == 0 || empty($_POST['processarCRM'])):
        die();
    endif;
    $cadastro->processarCRM();
endif;

/* == Delete CRM== */
if (isset($_POST['apagarCRM'])):
    if (intval($_POST['apagarCRM']) == 0 || empty($_POST['apagarCRM'])):
        die();
    endif;

    $id = intval($_POST['apagarCRM']);
    $data = array(
        'inativo' => '1'
    );

    $db->update("cadastro", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('CADASTRO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

?>
<?php
/* == Abrir Caixa == */
if (isset($_POST['abrirCaixa'])):
    if (intval($_POST['abrirCaixa']) == 0 || empty($_POST['abrirCaixa'])):
        die();
    endif;
    $faturamento->abrirCaixa($usuario->uid);
endif;

/* == Adicionar Valor ao Caixa == */
if (isset($_POST['adicionarValorAoCaixa'])):
    if (intval($_POST['adicionarValorAoCaixa']) == 0 || empty($_POST['adicionarValorAoCaixa'])):
        die();
    endif;
    $faturamento->adicionarValorAoCaixa($usuario->uid);
endif;

/* == Fechar Caixa == */
if (isset($_POST['fecharCaixa'])):
    if (intval($_POST['fecharCaixa']) == 0 || empty($_POST['fecharCaixa'])):
        die();
    endif;

    $id_caixa = intval($_POST['fecharCaixa']);
    $id_fechar = $usuario->uid;

    if ($id_caixa > 0) {
        $data = array(
            'id_fechar' => $id_fechar,
            'data_fechar' => "NOW()",
            'status' => '2',
            'usuario' => session('nomeusuario'),
            'data' => "NOW()"
        );
        $db->update("caixa", $data, "id=" . $id_caixa);
        if ($db->affected()) {
            print Filter::msgOk(lang('CAIXA_FECHAR_OK'), "index.php?do=caixa&acao=aberto");
        } else {
            print Filter::msgAlert(lang('NAOPROCESSADO'));
        }
    } else {
        print Filter::msgAlert(lang('CAIXA_ERRO'));
    }

endif;

/* == Validar Caixa == */
if (isset($_POST['validarCaixa'])):
    if (intval($_POST['validarCaixa']) == 0 || empty($_POST['validarCaixa'])):
        die();
    endif;

    $faturamento->processarValidarCaixa();

endif;

/* == Processar Retirar Caixa == */
if (isset($_POST['processarRetirarCaixa'])):
    if (intval($_POST['processarRetirarCaixa']) == 0 || empty($_POST['processarRetirarCaixa'])):
        die();
    endif;
    $despesa->processarRetirarCaixa();
endif;


/* == Alterar Pagamento Caixa == */
if (isset($_POST['processarAlterarCaixa'])):
    if (intval($_POST['processarAlterarCaixa']) == 0 || empty($_POST['processarAlterarCaixa'])):
        die();
    endif;
    $id = intval($_POST['id']);
    $id_caixa = intval($_POST['id_caixa']);
    $tipopagamento = intval($_POST['tipopagamento']);
    $tipo = getValue("tipo", "cadastro_financeiro", "id=" . $id);
    $valor = getValue("valor_pago", "cadastro_financeiro", "id=" . $id);
    $data_pagamento = getValue("data_pagamento", "cadastro_financeiro", "id=" . $id);
    if ($tipo == 1 and $tipopagamento > 1) {
        $data_pagamento = exibedata($data_pagamento);
        $data_parcela = explode('/', '' . $data_pagamento);
        $row_cartoes = Core::getRowById("tipo_pagamento", $tipopagamento);
        $dias = $row_cartoes->dias;
        $taxa = $row_cartoes->taxa;
        $id_banco = $row_cartoes->id_banco;

        $valor_taxa = $valor * $taxa / 100;
        $valor_cartao = $valor - $valor_taxa;
        $newData = novadata($data_parcela[1], $data_parcela[0] + ($dias), $data_parcela[2]);
        $data = array(
            'id_empresa' => session('idempresa'),
            'id_conta' => 19,
            'id_banco' => $id_banco,
            'id_caixa' => $id_caixa,
            'id_pagamento' => $id,
            'descricao' => $row_cartoes->tipo . " - ALTERACAO DE FORMA DE PAGAMENTO PARA ESTA VENDA",
            'tipo' => $tipopagamento,
            'valor' => $valor_cartao,
            'valor_pago' => $valor_cartao,
            'data_recebido' => $newData,
            'data_pagamento' => $data_pagamento,
            'pago' => '1',
            'usuario' => session('nomeusuario'),
            'data' => "NOW()"
        );
        $db->insert("receita", $data);
    } elseif ($tipo > 1 and $tipopagamento == 1) {
        $data = array(
            'descricao' => "INATIVO- ALTERACAO DE FORMA DE PAGAMENTO PARA ESTA VENDA",
            'inativo' => '1',
            'usuario' => session('nomeusuario'),
            'data' => "NOW()"
        );
        $db->update("receita", $data, "id_pagamento=" . $id);
        $data = array(
            'id_empresa' => session('idempresa'),
            'id_conta' => 19,
            'id_caixa' => $id_caixa,
            'id_pagamento' => $id,
            'descricao' => "DINHEIRO - ALTERACAO DE FORMA DE PAGAMENTO PARA ESTA VENDA",
            'tipo' => $tipopagamento,
            'valor' => $valor,
            'valor_pago' => $valor,
            'data_recebido' => $data_pagamento,
            'data_pagamento' => $data_pagamento,
            'pago' => '1',
            'usuario' => session('nomeusuario'),
            'data' => "NOW()"
        );
        $db->insert("receita", $data);
    } else {
        $row_cartoes = Core::getRowById("tipo_pagamento", $tipopagamento);
        $data = array(
            'descricao' => $row_cartoes->tipo . " - ALTERACAO DE FORMA DE PAGAMENTO PARA ESTA VENDA",
            'tipo' => $tipopagamento,
            'usuario' => session('nomeusuario'),
            'data' => "NOW()"
        );
        $db->update("receita", $data, "id_pagamento=" . $id);
    }
    $data = array(
        'tipo' => $tipopagamento,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("cadastro_financeiro", $data, "id=" . $id);

    if ($db->affected()) {
        print Filter::msgOk(lang('FINANCEIRO_PAGAMENTOS_ALTERADO_OK'), 'index.php?do=caixa&acao=pagamentos&id_caixa=' . $id_caixa);
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Banco == */
if (isset($_POST['processarBanco'])):
    if (intval($_POST['processarBanco']) == 0 || empty($_POST['processarBanco'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $faturamento->processarBanco();
endif;

/* == Delete Banco == */
if (isset($_POST['apagarBanco'])):
    if (intval($_POST['apagarBanco']) == 0 || empty($_POST['apagarBanco'])):
        die();
    endif;

    $id = intval($_POST['apagarBanco']);
    $data = array(
        'inativo' => '1'
    );

    $db->update("banco", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('BANCO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Processar Debito Banco == */
if (isset($_POST['processarDebitoBanco'])):
    if (intval($_POST['processarDebitoBanco']) == 0 || empty($_POST['processarDebitoBanco'])):
        die();
    endif;
    $despesa->processarDebitoBanco();
endif;

/* == Processar Credito Banco == */
if (isset($_POST['processarCreditoBanco'])):
    if (intval($_POST['processarCreditoBanco']) == 0 || empty($_POST['processarCreditoBanco'])):
        die();
    endif;
    $faturamento->processarCreditoBanco();
endif;

/* == Processar Transferencia Banco == */
if (isset($_POST['processarTranferenciaBanco'])):
    if (intval($_POST['processarTranferenciaBanco']) == 0 || empty($_POST['processarTranferenciaBanco'])):
        die();
    endif;
    $faturamento->processarTranferenciaBanco();
endif;

/* == processar Data Receitas == */
if (isset($_POST['processarDataReceita'])):
    if (intval($_POST['processarDataReceita']) == 0 || empty($_POST['processarDataReceita'])):
        die();
    endif;
    $faturamento->processarDataReceita();
endif;

?>
<?php
/* == Processar Tipo Pagamento == */
if (isset($_POST['processarTipoPagamento'])):
    if (intval($_POST['processarTipoPagamento']) == 0 || empty($_POST['processarTipoPagamento'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $faturamento->processarTipoPagamento();
endif;

/* == Delete Tipo Pagamento == */
if (isset($_POST['apagarTipoPagamento'])):
    if (intval($_POST['apagarTipoPagamento']) == 0 || empty($_POST['apagarTipoPagamento'])):
        die();
    endif;

    $id = intval($_POST['apagarTipoPagamento']);
    $data = array(
        'inativo' => '1'
    );

    $db->update("tipo_pagamento", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('FINANCEIRO_PAGAMENTOS_APAGADO_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Produto == */
if (isset($_POST['processarProduto'])):
    if (intval($_POST['processarProduto']) == 0 || empty($_POST['processarProduto'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $produto->processarProduto();
endif;

/* == Processar Produto Fornecedor == */
if (isset($_POST['processarProdutoFornecedor'])):
    if (intval($_POST['processarProdutoFornecedor']) == 0 || empty($_POST['processarProdutoFornecedor'])):
        die();
    endif;
    $produto->processarProdutoFornecedor();
endif;

/* == Processar Produto Kit == */
if (isset($_POST['processarProdutoKit'])):
    if (intval($_POST['processarProdutoKit']) == 0 || empty($_POST['processarProdutoKit'])):
        die();
    endif;
    $produto->processarProdutoKit();
endif;

/* == Processar Produto Atributo == */
if (isset($_POST['processarProdutoAtributo'])):
    if (intval($_POST['processarProdutoAtributo']) == 0 || empty($_POST['processarProdutoAtributo'])):
        die();
    endif;
    $produto->processarProdutoAtributo();
endif;

/* == Processar Produto Imagem == */
if (isset($_POST['processarProdutoImagem'])):
    if (intval($_POST['processarProdutoImagem']) == 0 || empty($_POST['processarProdutoImagem'])):
        die();
    endif;
    $produto->processarProdutoImagem();
endif;


/* == Reativar produto deletado == */
if (isset($_POST['reativar_produto'])):
    if (intval($_POST['reativar_produto'])==0 || empty($_POST['reativar_produto'])):
        die();
    endif;

    $id = intval($_POST['reativar_produto']);
    $produto->reativar_produto($id);

endif;

/* == Delete Produto== */
if (isset($_POST['apagarProduto'])):
    if (intval($_POST['apagarProduto']) == 0 || empty($_POST['apagarProduto'])):
        die();
    endif;

    $id = intval($_POST['apagarProduto']);

    $data_produto = array(
        'id_produto' => '0',
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("produto_fornecedor", $data_produto, "id_produto=" . $id);
    $db->delete("produto_kit", "id_produto=" . $id);
    $db->delete("produto_atributo", "id_produto=" . $id);
    $data = array(
        'inativo' => '1',
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("produto", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('PRODUTO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Delete Produto Fornecedor == */
if (isset($_POST['apagarProdutoFornecedor'])):
    if (intval($_POST['apagarProdutoFornecedor']) == 0 || empty($_POST['apagarProdutoFornecedor'])):
        die();
    endif;

    $id = intval($_POST['apagarProdutoFornecedor']);
    $row_produto_fornecedor = Core::getRowById("produto_fornecedor", $id);

    $data_produto = array(
        'id_produto' => '0',
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );

    $db->update("produto_fornecedor", $data_produto, "id=" . $id);
    $db->update("nota_fiscal_itens", $data_produto, "id_produto_fornecedor=" . $id);

    $quantidade = $produto->getQuantNota($row_produto_fornecedor->id_nota, $id);
    $quantidade_fornecedor = $produto->getQuantNotaFornecedor($id);
    $id_empresa = getValue("id_empresa", "nota_fiscal", "id=" . $row_produto_fornecedor->id_nota);
    $numero_nota = getValue("numero_nota", "nota_fiscal", "id=" . $row_produto_fornecedor->id_nota);

    $observacao = str_replace("[NUMERO_NOTA]", $numero_nota, lang('CANCELAMENTO_PRODUTOCO_FORNECEDOR'));

    $data_estoque = array(
        'id_empresa' => $id_empresa,
        'id_produto' => $row_produto_fornecedor->id_produto,
        'quantidade' => $quantidade * $quantidade_fornecedor * -1,
        'tipo' => 2,
        'motivo' => 7,
        'observacao' => $observacao,
        'inativo' => 0,
        'id_ref' => $row_produto_fornecedor->id_nota,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->insert("produto_estoque", $data_estoque);

    $estoqueAtual = getValue("estoque", "produto", "id=" . $row_produto_fornecedor->id_produto);
    $data_estoque_produto = array(
        'estoque' => $estoqueAtual - $quantidade * $quantidade_fornecedor,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("produto", $data_estoque_produto, "id=" . $row_produto_fornecedor->id_produto);

    if ($db->affected()) {
        print Filter::msgOk(lang('FORNECEDOR_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Delete Produto Kit == */
if (isset($_POST['apagarProdutoKit'])):
    if (intval($_POST['apagarProdutoKit']) == 0 || empty($_POST['apagarProdutoKit'])):
        die();
    endif;
    $id = intval($_POST['apagarProdutoKit']);
    $id_produto_kit = intval($_POST['id_produto_kit']);
    $id_produto = intval($_POST['id_produto']);

    $checkKit = $produto->getProdutokit($id_produto_kit);
    if ($checkKit) {
        foreach ($checkKit as $key) {
            if ($id_produto == $key->id_produto) {
                $valor_custo_total = round($produto->getValorCustoProdutokit($key->id_produto_kit), 2);
                $valor_custo_kit_deletado = $key->valor_custo;
                $valor_custo_atualizado = $valor_custo_total - $valor_custo_kit_deletado;

                $data_produto = array(
                    'valor_custo' => $valor_custo_atualizado,
                    'usuario' => session('nomeusuario'),
                    'data' => "NOW()"
                );
                $db->update("produto", $data_produto, "id=" . $id_produto_kit);
            }
        }
    }

    $db->delete("produto_kit", "id=" . $id);

    if ($db->affected()) {
        print Filter::msgOk(lang('PRODUTO_APAGAR_OK'), "index.php?do=produto&acao=editar&id=$id_produto_kit");
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Delete Produto Atributo == */
if (isset($_POST['apagarProdutoAtributo'])):
    if (intval($_POST['apagarProdutoAtributo']) == 0 || empty($_POST['apagarProdutoAtributo'])):
        die();
    endif;

    $id = intval($_POST['apagarProdutoAtributo']);
    $db->delete("produto_atributo", "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('PRODUTO_ATRIBUTO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Delete Produto Imagem == */
if (isset($_POST['apagarProdutoImagem'])):
    if (intval($_POST['apagarProdutoImagem']) == 0 || empty($_POST['apagarProdutoImagem'])):
        die();
    endif;

    $id = intval($_POST['apagarProdutoImagem']);
    $nome_arquivo = getValue("imagem", "produto", "id=" . $id);
    @unlink(UPLOADS . $nome_arquivo);
    $data = array(
        'imagem' => ''
    );
    $db->update("produto", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('PRODUTO_IMAGEM_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Adiconar na Grade de Vendas == */
if (isset($_POST['adicionarGrade'])):
    if (intval($_POST['adicionarGrade']) == 0 || empty($_POST['adicionarGrade'])):
        die();
    endif;
    $produto->adicionarGrade();
endif;

/* == Limpar Grade de Vendas == */
if (isset($_POST['limparGradeVendas'])):
    if (intval($_POST['limparGradeVendas']) == 0 || empty($_POST['limparGradeVendas'])):
        die();
    endif;

    $data = array(
        'grade' => 0,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );

    $db->update("produto", $data, "inativo = 0");
    if ($db->affected()) {
        print Filter::msgOk(lang('PRODUTO_AlTERADO_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Processar Grade de Vendas == */
if (isset($_POST['processarGradeVendas'])):
    if (intval($_POST['processarGradeVendas']) == 0 || empty($_POST['processarGradeVendas'])):
        die();
    endif;

    $id = intval($_POST['id']);
    $grade = intval($_POST['grade']);

    $data = array(
        'grade' => $grade,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );

    $db->update("produto", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('PRODUTO_AlTERADO_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Incluir produto na grade == */
if (isset($_POST['alterarGrade'])):
    if (intval($_POST['alterarGrade']) == 0 || empty($_POST['alterarGrade'])):
        die();
    endif;

    $id = intval($_POST['id']);
    $valor_venda = converteMoeda(post('valor_venda'));
    $id_produto = getValue('id_produto', 'produto_tabela', 'id=' . $id);
    $valor_custo = getValue('valor_custo', 'produto', 'id=' . $id_produto);
    $percentual = ($valor_venda / $valor_custo) - 1;

    $data = array(
        'valor_venda' => $valor_venda,
        'percentual' => $percentual,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );

    $db->update("produto_tabela", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('PRODUTO_AlTERADO_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Processar Tabela Pre�o == */
if (isset($_POST['processarTabelaPreco'])):
    if (intval($_POST['processarTabelaPreco']) == 0 || empty($_POST['processarTabelaPreco'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $produto->processarTabelaPreco();
endif;

/* == Buscar Produtos para Tabela Precos == */
if (isset($_POST['buscarProdutos'])):
    if (intval($_POST['buscarProdutos']) == 0 || empty($_POST['buscarProdutos'])):
        die();
    endif;
    $produto->buscarProdutos();
endif;

/* == Alterar Todos Valor Produto Tabela Precos == */
if (isset($_POST['processarAlterarTodos'])):
    if (intval($_POST['processarAlterarTodos']) == 0 || empty($_POST['processarAlterarTodos'])):
        die();
    endif;
    $produto->processarAlterarTodos();
endif;

/* == Atualizar os códigos de barras de todos os produtos que não tem códigos de barras == */
if (isset($_POST['processarAtualizarCodigosDeBarras'])):
    if (intval($_POST['processarAtualizarCodigosDeBarras']) == 0 || empty($_POST['processarAtualizarCodigosDeBarras'])):
        die();
    endif;
    $produto->processarAtualizarCodigosDeBarras();
endif;

/* == Alterar Valor Produto Tabela Precos == */
if (isset($_POST['processarAlterarValor'])):
    if (intval($_POST['processarAlterarValor']) == 0 || empty($_POST['processarAlterarValor'])):
        die();
    endif;

    $id = intval($_POST['id']);
    $valor_venda = converteMoeda(post('valor_venda'));
    $id_produto = getValue('id_produto', 'produto_tabela', 'id=' . $id);
    $valor_custo = getValue('valor_custo', 'produto', 'id=' . $id_produto);
    $valor_custo = ($valor_custo > 0) ? $valor_custo : 1;
    $percentual = (($valor_venda / $valor_custo) - 1) * 100;

    $data = array(
        'valor_venda' => $valor_venda,
        'percentual' => $percentual,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );

    $db->update("produto_tabela", $data, "id=" . $id);

    if ($db->affected()) {
        print Filter::msgOk(lang('PRODUTO_AlTERADO_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Delete Produto Tabela Precos == */
if (isset($_POST['apagarProdutoTabelaPreco'])):
    if (intval($_POST['apagarProdutoTabelaPreco']) == 0 || empty($_POST['apagarProdutoTabelaPreco'])):
        die();
    endif;

    $id = intval($_POST['apagarProdutoTabelaPreco']);
    $db->delete("produto_tabela", "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('PRODUTO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Delete Tabela Pre�os == */
if (isset($_POST['apagarTabelaPreco'])):
    if (intval($_POST['apagarTabelaPreco']) == 0 || empty($_POST['apagarTabelaPreco'])):
        die();
    endif;

    $id = intval($_POST['apagarTabelaPreco']);

    $data = array(
        'inativo' => '1'
    );

    $db->update("tabela_precos", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('TABELA_PRECO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Novo Produto Nota Fiscal == */
if (isset($_POST['novoProdutoNota'])):
    if (intval($_POST['novoProdutoNota']) == 0 || empty($_POST['novoProdutoNota'])):
        die();
    endif;
    $produto->novoProdutoNota();
endif;

/* == Editar Produto Nota Fiscal de Entrada== */
if (isset($_POST['editarProdutoNotaEntrada'])):
    if (intval($_POST['editarProdutoNotaEntrada']) == 0 || empty($_POST['editarProdutoNotaEntrada'])):
        die();
    endif;
    $produto->editarProdutoNotaEntrada();
endif;

/* == Editar CFOP da nota de Transporte == */
if (isset($_POST['editarCfopTransporte'])):
    if (intval($_POST['editarCfopTransporte']) == 0 || empty($_POST['editarCfopTransporte'])):
        die();
    endif;
    $produto->editarCfopTransporte();
endif;

/* == Todos Produto Nota Fiscal == */
if (isset($_POST['todosProdutoNota'])):
    if (intval($_POST['todosProdutoNota']) == 0 || empty($_POST['todosProdutoNota'])):
        die();
    endif;
    $produto->todosProdutoNota();
endif;

/* == Combinar Produto Nota Fiscal == */
if (isset($_POST['combinarProdutoNota'])):
    if (intval($_POST['combinarProdutoNota']) == 0 || empty($_POST['combinarProdutoNota'])):
        die();
    endif;
    $produto->combinarProdutoNota();
endif;
?>
<?php
/* == Processar Atributo == */
if (isset($_POST['processarAtributo'])):
    if (intval($_POST['processarAtributo']) == 0 || empty($_POST['processarAtributo'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $produto->processarAtributo();
endif;

/* == Delete Atributo == */
if (isset($_POST['apagarAtributo'])):
    if (intval($_POST['apagarAtributo']) == 0 || empty($_POST['apagarAtributo'])):
        die();
    endif;

    $id = intval($_POST['apagarAtributo']);
    $data = array(
        'inativo' => '1'
    );

    $db->update("atributo", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('PRODUTO_ATRIBUTO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Categoria == */
if (isset($_POST['processarCategoria'])):
    if (intval($_POST['processarCategoria']) == 0 || empty($_POST['processarCategoria'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $categoria->processarCategoria();
endif;

/* == Delete Categoria == */
if (isset($_POST['apagarCategoria'])):
    if (intval($_POST['apagarCategoria']) == 0 || empty($_POST['apagarCategoria'])):
        die();
    endif;

    $id = intval($_POST['apagarCategoria']);
    $data = array(
        'inativo' => '1'
    );

    $db->update("categoria", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('CATEGORIA_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Fabricante == */
if (isset($_POST['processarFabricante'])):
    if (intval($_POST['processarFabricante']) == 0 || empty($_POST['processarFabricante'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $fabricante->processarFabricante();
endif;

/* == Delete Fabricante == */
if (isset($_POST['apagarFabricante'])):
    if (intval($_POST['apagarFabricante']) == 0 || empty($_POST['apagarFabricante'])):
        die();
    endif;

    $id = intval($_POST['apagarFabricante']);
    $data = array(
        'inativo' => '1'
    );

    $db->update("fabricante", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('FABRICANTE_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Grupo == */
if (isset($_POST['processarGrupo'])):
    if (intval($_POST['processarGrupo']) == 0 || empty($_POST['processarGrupo'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $grupo->processarGrupo();
endif;

/* == Delete Grupo == */
if (isset($_POST['apagarGrupo'])):
    if (intval($_POST['apagarGrupo']) == 0 || empty($_POST['apagarGrupo'])):
        die();
    endif;

    $id = intval($_POST['apagarGrupo']);
    $data = array(
        'inativo' => '1'
    );

    $db->update("grupo", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('GRUPO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php

/* == Processar NotaFiscal == */
if (isset($_POST['processarNotaFiscal'])):
    if (intval($_POST['processarNotaFiscal']) == 0 || empty($_POST['processarNotaFiscal'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $produto->processarNotaFiscal();
endif;

/* == Nota Fiscal Carta de Correcao == */
if (isset($_POST['processarNotaFiscalCarta'])):
    if (intval($_POST['processarNotaFiscalCarta']) == 0 || empty($_POST['processarNotaFiscalCarta'])):
        die();
    endif;
    $produto->processarNotaFiscalCarta();
endif;

/* == Editar NotaFiscal == */
if (isset($_POST['editarNotaFiscal'])):
    if (intval($_POST['editarNotaFiscal']) == 0 || empty($_POST['editarNotaFiscal'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $produto->editarNotaFiscal();
endif;

/* == Nota Fiscal Receitas == */
if (isset($_POST['processarNotaFiscalReceita'])):
    if (intval($_POST['processarNotaFiscalReceita']) == 0 || empty($_POST['processarNotaFiscalReceita'])):
        die();
    endif;
    $faturamento->processarNotaFiscalReceita();
endif;

/* == Permitir cancelamento extemporaneo de Nota fiscal == */
if (isset($_POST['permitir_cancelar_extemporaneo'])):
    if (intval($_POST['permitir_cancelar_extemporaneo']) == 0 || empty($_POST['permitir_cancelar_extemporaneo'])):
        die();
    endif;
    $id_nf = intval($_POST['permitir_cancelar_extemporaneo']);
    $data_update = array(
        'cancelamento_extemporaneo' => 1,
        'usuario_extemporaneo' => session('nomeusuario'),
        'data_extemporaneo' => "NOW()"
    );
    $db->update("nota_fiscal", $data_update, "id=" . $id_nf);
    if ($db->affected()) {
        print Filter::msgOk(lang('ENOTAS_PERMITIDO_EXTEMPORANEO'), "index.php?do=notafiscal&acao=visualizar&id=" . $id_nf);
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }

endif;

/* == Delete Nota Fiscal == */
if (isset($_POST['apagarNotaFiscal'])):
    if (intval($_POST['apagarNotaFiscal']) == 0 || empty($_POST['apagarNotaFiscal'])):
        die();
    endif;

    $id = intval($_POST['apagarNotaFiscal']);
    $row_notafiscal = Core::getRowById("nota_fiscal", $id);
    if ((!$row_notafiscal->inativo and !$row_notafiscal->fiscal) || $row_notafiscal->status_enotas == "Inutilizada") {
        $data = array(
            'inativo' => '1'
        );
        $data_venda = array(
            'id_nota_fiscal' => '0'
        );

        $operacao_nota = getValue("operacao", "nota_fiscal", "id=" . $id);

        $db->update("despesa", $data, "id_nota=" . $id);
        $db->update("receita", $data, "id_nota=" . $id);
        $db->update("vendas", $data_venda, "id_nota_fiscal=" . $id);

        $fiscal = getValue("fiscal", "nota_fiscal", "id=" . $id);
        $observacao = str_replace("[ID_NFE]", $id, lang('CANCELAMENTO_NFE_PRODUTO'));
        $sql_produto = "SELECT id_produto, quantidade, inativo FROM nota_fiscal_itens WHERE id_nota=" . $id . " AND inativo = 0";
        $produto_row = $db->fetch_all($sql_produto);

        if ($produto_row) {
            foreach ($produto_row as $prow) {
                $id_venda = getValue("id_venda", "nota_fiscal", "id=" . $id);
                if (!$id_venda || $id_venda == 0) {
                    if ($prow->inativo == 0) {
                        $qtde_compra = getValue("quantidade_compra", "produto_fornecedor", "id_produto=" . $prow->id_produto . " AND id_nota=" . $id);
                        $qtde_compra = ($qtde_compra) ? $qtde_compra : 1;
                        $quantidade = floatval($qtde_compra) * floatval($prow->quantidade);
                        $kit = getValue("kit", "produto", "id=" . $prow->id_produto);
                        if ($kit) {
                            $nomekit = getValue("nome", "produto", "id=" . $prow->id_produto);
                            $sql_kit = "SELECT k.id, k.id_produto, p.nome, p.estoque, k.quantidade "
                                . "\n FROM produto_kit as k"
                                . "\n LEFT JOIN produto as p ON p.id = k.id_produto "
                                . "\n WHERE k.id_produto_kit = $prow->id_produto AND k.materia_prima=0"
                                . "\n ORDER BY p.nome";
                            $retorno_krow = $db->fetch_all($sql_kit);
                            if ($retorno_krow) {
                                foreach ($retorno_krow as $exrow) {
                                    $observacao_kit = str_replace("[ID_NFE]", $id, lang('CANCELAMENTO_NOTA_KIT'));
                                    $observacao_kit = str_replace("[NOME_KIT]", $nomekit, $observacao_kit);
                                    $quantidade_kit = $quantidade * $exrow->quantidade;
                                    $data_estoque = array(
                                        'id_empresa' => session('idempresa'),
                                        'id_produto' => $exrow->id_produto,
                                        'quantidade' => $quantidade_kit,
                                        'tipo' => 1,
                                        'motivo' => 7,
                                        'observacao' => $observacao_kit,
                                        'usuario' => session('nomeusuario'),
                                        'data' => "NOW()"
                                    );
                                    $db->insert("produto_estoque", $data_estoque);
                                    $totalestoque = $cadastro->getEstoqueTotal($exrow->id_produto);
                                    $data_update = array(
                                        'estoque' => $totalestoque,
                                        'usuario' => session('nomeusuario'),
                                        'data' => "NOW()"
                                    );
                                    $db->update("produto", $data_update, "id=" . $exrow->id_produto);
                                }
                            }
                        }
                        $data_estoque = array(
                            'id_empresa' => session('idempresa'),
                            'id_produto' => $prow->id_produto,
                            'quantidade' => ($operacao_nota == 1) ? $quantidade * -1 : $quantidade,
                            'tipo' => 1,
                            'motivo' => 7,
                            'observacao' => $observacao,
                            'id_ref' => $id,
                            'usuario' => session('nomeusuario'),
                            'data' => "NOW()"
                        );
                        $db->insert("produto_estoque", $data_estoque);
                        $totalestoque = $cadastro->getEstoqueTotal($prow->id_produto);
                        $data_update = array(
                            'estoque' => $totalestoque,
                            'usuario' => session('nomeusuario'),
                            'data' => "NOW()"
                        );
                        $db->update("produto", $data_update, "id=" . $prow->id_produto);
                    }
                }
            }
        }
        $db->update("nota_fiscal_itens", $data, "id_nota=" . $id);
        if ($fiscal) {
            $data_nota = array(
                'inativo' => '1'
            );
        } else {
            $numero_nota = getValue("numero_nota", "nota_fiscal", "id=" . $id);
            $data_nota = array(
                'numero_nota' => 'NULL',
                'nota_cancelada' => $numero_nota,
                'inativo' => '1'
            );
        }
        $db->update("nota_fiscal", $data_nota, "id=" . $id);
        if ($db->affected()) {
            print Filter::msgOk(lang('NOTA_APAGAR_OK'), "index.php?do=notafiscal&acao=notafiscal");
        } else {
            print Filter::msgAlert(lang('NAOPROCESSADO'));
        }
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>

<?php
/* == Processar Conta == */
if (isset($_POST['processarConta'])):
    if (intval($_POST['processarConta']) == 0 || empty($_POST['processarConta'])):
        die();
    endif;
    $faturamento->processarConta();
endif;

/* == Ocultar Conta == */
if (isset($_POST['apagarConta'])):
    if (intval($_POST['apagarConta']) == 0 || empty($_POST['apagarConta'])):
        die();
    endif;

    $id = intval($_POST['apagarConta']);
    $data = array(
        'exibir' => '0'
    );

    $db->update("conta", $data, "id=" . $id . " OR id_pai=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('FINANCEIRO_CONTA_AlTERADO_OK'), "index.php?do=faturamento&acao=plano_contas");
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Exibir Conta == */
if (isset($_POST['exibirConta'])):
    if (intval($_POST['exibirConta']) == 0 || empty($_POST['exibirConta'])):
        die();
    endif;

    $id = intval($_POST['exibirConta']);
    $data = array(
        'exibir' => '1'
    );

    $db->update("conta", $data, "id=" . $id . " OR id_pai=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('FINANCEIRO_CONTA_AlTERADO_OK'), "index.php?do=faturamento&acao=plano_contas");
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Estoque == */
if (isset($_POST['processarEstoque'])):
    if (intval($_POST['processarEstoque']) == 0 || empty($_POST['processarEstoque'])):
        die();
    endif;
    $produto->processarEstoque();
endif;

/* == Processar Transferencia Estoque == */
if (isset($_POST['processarTransferenciaEstoque'])):
    if (intval($_POST['processarTransferenciaEstoque']) == 0 || empty($_POST['processarTransferenciaEstoque'])):
        die();
    endif;
    $produto->processarTransferenciaEstoque();
endif;

/* == Delete Estoque == */
if (isset($_POST['apagarEstoque'])):
    if (intval($_POST['apagarEstoque']) == 0 || empty($_POST['apagarEstoque'])):
        die();
    endif;

    $id = intval($_POST['apagarEstoque']);
    $data = array(
        'inativo' => '1'
    );

    $db->update("produto_estoque", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('ESTOQUE_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

?>
<?php
/* == Processar Extrato de Banco == */
if (isset($_POST['processarArquivoBanco'])):
    if (intval($_POST['processarArquivoBanco']) == 0 || empty($_POST['processarArquivoBanco'])):
        die();
    endif;
    $extrato->processarArquivoBanco();
endif;
?>
<?php
/* == Processar Extrato de Cartoes == */
if (isset($_POST['processarArquivoCartoes'])):
    if (intval($_POST['processarArquivoCartoes']) == 0 || empty($_POST['processarArquivoCartoes'])):
        die();
    endif;
    $extrato->processarArquivoCartoes();
endif;
?>
<?php
/* == Processar Arquivos de Boletos == */
if (isset($_POST['processarArquivoBoletos'])):
    if (intval($_POST['processarArquivoBoletos']) == 0 || empty($_POST['processarArquivoBoletos'])):
        die();
    endif;
    $extrato->processarArquivoBoletos();
endif;
?>
<?php
/* == Conciliar Banco Sistema == */
if (isset($_POST['conciliarBanco'])):
    if (intval($_POST['conciliarBanco']) == 0 || empty($_POST['conciliarBanco'])):
        die();
    endif;
    $extrato->conciliarBanco();
endif;
?>
<?php
/* == Cancelar Conciliar Banco Sistema == */
if (isset($_POST['cancelarConciliacao'])):
    if (intval($_POST['cancelarConciliacao']) == 0 || empty($_POST['cancelarConciliacao'])):
        die();
    endif;
    $extrato->cancelarConciliacao();
endif;
?>
<?php
/* == Registrar pagamento == */
if (isset($_POST['registrarPagamento'])):
    if (intval($_POST['registrarPagamento']) == 0 || empty($_POST['registrarPagamento'])):
        die();
    endif;
    $extrato->registrarPagamento();
endif;
?>
<?php
/* == Processar Descricao Salario == */
if (isset($_POST['processarDescricao'])):
    if (intval($_POST['processarDescricao']) == 0 || empty($_POST['processarDescricao'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $salario_descricao->processarDescricao();
endif;

/* == Delete Onibus== */
if (isset($_POST['apagarDescricao'])):
    if (intval($_POST['apagarDescricao']) == 0 || empty($_POST['apagarDescricao'])):
        die();
    endif;

    $id = intval($_POST['apagarDescricao']);
    $db->delete("salario_descricao", "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('DESCRICAO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Usuario Dependente == */
if (isset($_POST['processarUsuarioDependente'])):
    if (intval($_POST['processarUsuarioDependente']) == 0 || empty($_POST['processarUsuarioDependente'])):
        die();
    endif;
    $usuario->processarUsuarioDependente();
endif;

/* == Delete Usuario Dependente == */
if (isset($_POST['apagarUsuarioDependente'])):
    if (intval($_POST['apagarUsuarioDependente']) == 0 || empty($_POST['apagarUsuarioDependente'])):
        die();
    endif;

    $id = intval($_POST['apagarUsuarioDependente']);
    $data = array(
        'inativo' => '1'
    );
    $db->update("usuario_dependente", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('DEPENDENTE_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar IRRF == */
if (isset($_POST['processarIRRF'])):
    if (intval($_POST['processarIRRF']) == 0 || empty($_POST['processarIRRF'])):
        die();
    endif;
    $rh->processarIRRF();
endif;

/* == Delete IRRF == */
if (isset($_POST['apagarIRRF'])):
    if (intval($_POST['apagarIRRF']) == 0 || empty($_POST['apagarIRRF'])):
        die();
    endif;

    $id = intval($_POST['apagarIRRF']);
    $db->delete("IRRF", "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('IRRF_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Dependente IRRF == */
if (isset($_POST['processarDependenteIRRF'])):
    if (intval($_POST['processarDependenteIRRF']) == 0 || empty($_POST['processarDependenteIRRF'])):
        die();
    endif;
    $rh->processarDependenteIRRF();
endif;

/* == Delete IRRF == */
if (isset($_POST['apagarDependenteIRRF'])):
    if (intval($_POST['apagarDependenteIRRF']) == 0 || empty($_POST['apagarDependenteIRRF'])):
        die();
    endif;

    $id = intval($_POST['apagarDependenteIRRF']);
    $db->delete("dependente", "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('IRRF_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Filhos == */
if (isset($_POST['processarFilhos'])):
    if (intval($_POST['processarFilhos']) == 0 || empty($_POST['processarFilhos'])):
        die();
    endif;
    $rh->processarFilhos();
endif;

/* == Delete INSS == */
if (isset($_POST['apagarFilhos'])):
    if (intval($_POST['apagarFilhos']) == 0 || empty($_POST['apagarFilhos'])):
        die();
    endif;

    $id = intval($_POST['apagarFilhos']);
    $db->delete("filhos", "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('FILHOS_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Salario == */
if (isset($_POST['processarSalarioMinimo'])):
    if (intval($_POST['processarSalarioMinimo']) == 0 || empty($_POST['processarSalarioMinimo'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $salario_minimo->processarSalarioMinimo();
endif;

/* == Delete Salario== */
if (isset($_POST['apagarSalarioMinimo'])):
    if (intval($_POST['apagarSalarioMinimo']) == 0 || empty($_POST['apagarSalarioMinimo'])):
        die();
    endif;

    $id = intval($_POST['apagarSalarioMinimo']);

    $db->delete("salario_minimo", "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('SALARIO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Pedido == */
if (isset($_POST['processarPedido'])):
    if (intval($_POST['processarPedido']) == 0 || empty($_POST['processarPedido'])):
        die();
    endif;
    $cotacao->processarPedido();
endif;

/* == Processar Finalizar Pedido == */
if (isset($_POST['processarFinalizarPedido'])):
    if (intval($_POST['processarFinalizarPedido']) == 0 || empty($_POST['processarFinalizarPedido'])):
        die();
    endif;
    $cotacao->processarFinalizarPedido();
endif;

/* == Processar Entrega Pedido == */
if (isset($_POST['processarEntregaPedido'])):
    if (intval($_POST['processarEntregaPedido']) == 0 || empty($_POST['processarEntregaPedido'])):
        die();
    endif;
    $cotacao->processarEntregaPedido();
endif;

/* == Processar Entrega Item Pedido == */
if (isset($_POST['processarEntregaPedidoItem'])):
    if (intval($_POST['processarEntregaPedidoItem']) == 0 || empty($_POST['processarEntregaPedidoItem'])):
        die();
    endif;
    $cotacao->processarEntregaPedidoItem();
endif;

/* == Delete Pedido == */
if (isset($_POST['apagarPedido'])):
    if (intval($_POST['apagarPedido']) == 0 || empty($_POST['apagarPedido'])):
        die();
    endif;

    $id = intval($_POST['apagarPedido']);
    $data = array(
        'inativo' => 1,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );

    $db->update("pedido", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('PEDIDO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Processar Produto Pedido == */
if (isset($_POST['processarProdutoPedido'])):
    if (intval($_POST['processarProdutoPedido']) == 0 || empty($_POST['processarProdutoPedido'])):
        die();
    endif;
    $cotacao->processarProdutoPedido();
endif;

/* == Alterar Quantidade Pedido == */
if (isset($_POST['processarAlterarPedido'])):
    if (intval($_POST['processarAlterarPedido']) == 0 || empty($_POST['processarAlterarPedido'])):
        die();
    endif;

    $id = intval($_POST['id']);

    $data = array(
        'quantidade_pedido' => converteMoeda(post('quantidade')),
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );

    $db->update("pedido_itens", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('PRODUTO_AlTERADO_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Delete Produto Pedido == */
if (isset($_POST['apagarProdutoPedido'])):
    if (intval($_POST['apagarProdutoPedido']) == 0 || empty($_POST['apagarProdutoPedido'])):
        die();
    endif;

    $id = intval($_POST['apagarProdutoPedido']);
    $data = array(
        'inativo' => 1,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );

    $db->update("pedido_itens", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('PRODUTO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Ativar Produto Pedido == */
if (isset($_POST['ativarProdutoPedido'])):
    if (intval($_POST['ativarProdutoPedido']) == 0 || empty($_POST['ativarProdutoPedido'])):
        die();
    endif;

    $id = intval($_POST['ativarProdutoPedido']);
    $data = array(
        'inativo' => 0,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );

    $db->update("pedido_itens", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('PRODUTO_ADICIONADO_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;
?>
<?php
/* == Processar Salvar Cota��o == */
if (isset($_POST['salvarCotacao'])):
    if (intval($_POST['salvarCotacao']) == 0 || empty($_POST['salvarCotacao'])):
        die();
    endif;
    $cotacao->salvarCotacao();
endif;

/* == Processar Enviar Cota��o == */
if (isset($_POST['enviarCotacao'])):
    if (intval($_POST['enviarCotacao']) == 0 || empty($_POST['enviarCotacao'])):
        die();
    endif;
    $cotacao->enviarCotacao();
endif;

/* == Processar Validar Cota��o == */
if (isset($_POST['validarCotacao'])):
    if (intval($_POST['validarCotacao']) == 0 || empty($_POST['validarCotacao'])):
        die();
    endif;
    $cotacao->validarCotacao();
endif;

/* == Processar Reabrir Cota��o == */
if (isset($_POST['reabrirCotacao'])):
    if (intval($_POST['reabrirCotacao']) == 0 || empty($_POST['reabrirCotacao'])):
        die();
    endif;
    $cotacao->reabrirCotacao();
endif;

/* == Processar Aprovar Cota��o == */
if (isset($_POST['aprovarCotacao'])):
    if (intval($_POST['aprovarCotacao']) == 0 || empty($_POST['aprovarCotacao'])):
        die();
    endif;
    $cotacao->aprovarCotacao();
endif;

/* == Processar Finalizar Cota��o == */
if (isset($_POST['finalizarCotacao'])):
    if (intval($_POST['finalizarCotacao']) == 0 || empty($_POST['finalizarCotacao'])):
        die();
    endif;
    $cotacao->finalizarCotacao();
endif;

/* == Processar Cancelar Cota��o == */
if (isset($_POST['cancelarCotacao'])):
    if (intval($_POST['cancelarCotacao']) == 0 || empty($_POST['cancelarCotacao'])):
        die();
    endif;
    $cotacao->cancelarCotacao();
endif;

/* == Processar E-mail Aprova��o Cota��o == */
if (isset($_POST['enviarEmailAprovacao'])):
    if (intval($_POST['enviarEmailAprovacao']) == 0 || empty($_POST['enviarEmailAprovacao'])):
        die();
    endif;
    $cotacao->enviarEmailAprovacao();
endif;

/* == Processar E-mail Aprova��o Fornecedor == */
if (isset($_POST['enviarEmailFornecedor'])):
    if (intval($_POST['enviarEmailFornecedor']) == 0 || empty($_POST['enviarEmailFornecedor'])):
        die();
    endif;
    $cotacao->enviarEmailFornecedor();
endif;

/* == Processar E-mail Aprova��o Loja == */
if (isset($_POST['enviarEmailLoja'])):
    if (intval($_POST['enviarEmailLoja']) == 0 || empty($_POST['enviarEmailLoja'])):
        die();
    endif;
    $cotacao->enviarEmailLoja();
endif;

/* == Adicionar Frete Cotacao == */
if (isset($_POST['adicionarFrete'])):
    if (intval($_POST['adicionarFrete']) == 0 || empty($_POST['adicionarFrete'])):
        die();
    endif;

    $id_cotacao = intval($_POST['id_cotacao']);
    $id_cadastro = intval($_POST['id_cadastro']);
    $percentual = converteMoeda(post('percentual'));
    $sql = "SELECT f.id "
        . " FROM cotacao_frete AS f"
        . " WHERE f.id_cotacao = $id_cotacao "
        . " AND f.id_cadastro = $id_cadastro ";
    $row = $db->first($sql);

    $data = array(
        'id_cotacao' => $id_cotacao,
        'id_cadastro' => $id_cadastro,
        'percentual' => $percentual
    );
    ($row) ? $db->update("cotacao_frete", $data, "id=" . $row->id) : $db->insert("cotacao_frete", $data);
    print Filter::msgOk(lang('ADICIONAR_FRETE_OK'));
endif;

/* == Alterar Quantidade Cotacao == */
if (isset($_POST['processarAlterarCotacao'])):
    if (intval($_POST['processarAlterarCotacao']) == 0 || empty($_POST['processarAlterarCotacao'])):
        die();
    endif;

    $id = intval($_POST['id']);
    $id_produto = getValue("id_produto", "cotacao_itens", "id = " . $id);
    $id_cadastro = getValue("id_cadastro", "cotacao_itens", "id = " . $id);
    $valor = converteMoeda(post('valor'));
    $quantidade = converteMoeda(post('quantidade'));
    $unidade = sanitize(post('unidade'));
    $unidade = strtoupper($unidade);
    $data = array(
        'unidade_compra' => $unidade,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("produto_fornecedor", $data, "id_produto=" . $id_produto . " AND id_cadastro=" . $id_cadastro);
    $data = array(
        'valor_unitario' => $valor,
        'quantidade_cotacao' => $quantidade,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("cotacao_itens", $data, "id=" . $id);
    print Filter::msgOk(lang('COTACAO_AlTERADO_OK'));
endif;

/* == Salvar Cotacao para fornecedor == */
if (isset($_POST['processarCotacaoFornecedor'])):
    if (intval($_POST['processarCotacaoFornecedor']) == 0 || empty($_POST['processarCotacaoFornecedor'])):
        die();
    endif;

    $id = $_POST['id_item'];
    $valor = $_POST['valor'];
    $id_cotacao = intval($_POST['id_cotacao']);
    $id_cadastro = intval($_POST['id_cadastro']);
    $data_entrega = dataMySQL(post('data_entrega'));
    foreach ($id as $key => $n) {
        $valor_unitario = converteMoeda($valor[$key]);
        $data = array(
            'data_entrega' => $data_entrega,
            'valor_unitario' => $valor_unitario,
            'data_fornecedor' => "NOW()"
        );
        $db->update("cotacao_itens", $data, "id=" . $n);
    }
    if ($db->affected()) {
        print Filter::msgOk(lang('COTACAO_AlTERADO_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Validar Item Cotacao == */
if (isset($_POST['processarValidarItem'])):
    if (intval($_POST['processarValidarItem']) == 0 || empty($_POST['processarValidarItem'])):
        die();
    endif;

    $id = intval($_POST['id']);
    $id_cotacao = intval($_POST['id_cotacao']);
    $id_produto = intval($_POST['id_produto']);
    $datav = array(
        'valido' => 0
    );
    $db->update("cotacao_itens", $datav, "id_cotacao=" . $id_cotacao . " AND id_produto=" . $id_produto);
    $data = array(
        'valido' => 1,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("cotacao_itens", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('COTACAO_AlTERADO_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Bloquear Fornecedor == */
if (isset($_POST['bloquearFornecedor'])):
    if (intval($_POST['bloquearFornecedor']) == 0 || empty($_POST['bloquearFornecedor'])):
        die();
    endif;

    $id_cadastro = intval($_POST['id_cadastro']);
    $id_cotacao = intval($_POST['id_cotacao']);

    $data = array(
        'inativo' => '2'
    );

    $db->update("cotacao_itens", $data, "id_cadastro=" . $id_cadastro . " AND id_cotacao=" . $id_cotacao);
    if ($db->affected()) {
        print Filter::msgOk(lang('FORNECEDOR_AlTERADO_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Liberar Fornecedor == */
if (isset($_POST['liberarFornecedor'])):
    if (intval($_POST['liberarFornecedor']) == 0 || empty($_POST['liberarFornecedor'])):
        die();
    endif;

    $id_cadastro = intval($_POST['id_cadastro']);
    $id_cotacao = intval($_POST['id_cotacao']);

    $data = array(
        'inativo' => '0'
    );

    $db->update("cotacao_itens", $data, "id_cadastro=" . $id_cadastro . " AND id_cotacao=" . $id_cotacao);
    if ($db->affected()) {
        print Filter::msgOk(lang('FORNECEDOR_AlTERADO_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Processar Confirmar Entrega == */
if (isset($_POST['confirmaEntrega'])):
    if (intval($_POST['confirmaEntrega']) == 0 || empty($_POST['confirmaEntrega'])):
        die();
    endif;
    $cotacao->confirmaEntrega();
endif;

/* == Processar Cancelar Entrega == */
if (isset($_POST['cancelaEntrega'])):
    if (intval($_POST['cancelaEntrega']) == 0 || empty($_POST['cancelaEntrega'])):
        die();
    endif;
    $cotacao->cancelaEntrega();
endif;
?>
<?php
/* == Processar Veiculo == */
if (isset($_POST['processarVeiculo'])):
    if (intval($_POST['processarVeiculo']) == 0 || empty($_POST['processarVeiculo'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $veiculo->processarVeiculo();
endif;

/* == Delete Veiculo== */
if (isset($_POST['apagarVeiculo'])):
    if (intval($_POST['apagarVeiculo']) == 0 || empty($_POST['apagarVeiculo'])):
        die();
    endif;

    $id = intval($_POST['apagarVeiculo']);

    $data = array(
        'inativo' => '1'
    );

    $db->update("veiculo", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('VEICULO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Processar Eventos Veiculo == */
if (isset($_POST['processarEventosVeiculo'])):
    if (intval($_POST['processarEventosVeiculo']) == 0 || empty($_POST['processarEventosVeiculo'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $veiculo->processarEventosVeiculo();
endif;

/* == Delete Evento == */
if (isset($_POST['apagarEventosVeiculo'])):
    if (intval($_POST['apagarEventosVeiculo']) == 0 || empty($_POST['apagarEventosVeiculo'])):
        die();
    endif;

    $id = intval($_POST['apagarEventosVeiculo']);

    $data = array(
        'inativo' => '1'
    );

    $db->update("veiculo_eventos", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('EVENTOSVEICULO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Processar Evento == */
if (isset($_POST['processarEvento'])):
    if (intval($_POST['processarEvento']) == 0 || empty($_POST['processarEvento'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $veiculo->processarEvento();
endif;

/* == Delete Evento == */
if (isset($_POST['apagarEvento'])):
    if (intval($_POST['apagarEvento']) == 0 || empty($_POST['apagarEvento'])):
        die();
    endif;

    $id = intval($_POST['apagarEvento']);

    $data = array(
        'inativo' => '1'
    );

    $db->update("veiculo_descricao", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('EVENTO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Processar Veiculo Diaria == */
if (isset($_POST['processarVeiculoDiaria'])):
    if (intval($_POST['processarVeiculoDiaria']) == 0 || empty($_POST['processarVeiculoDiaria'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $veiculo->processarVeiculoDiaria();
endif;

/* == Processar Ajuste Diaria == */
if (isset($_POST['processarAjusteDiaria'])):
    if (intval($_POST['processarAjusteDiaria']) == 0 || empty($_POST['processarAjusteDiaria'])):
        die();
    endif;
    $veiculo->processarAjusteDiaria();
endif;

/* == Delete Veiculo Diaria == */
if (isset($_POST['apagarVeiculoDiaria'])):
    if (intval($_POST['apagarVeiculoDiaria']) == 0 || empty($_POST['apagarVeiculoDiaria'])):
        die();
    endif;

    $id = intval($_POST['apagarVeiculoDiaria']);

    $data = array(
        'inativo' => '1'
    );

    $db->update("veiculo_diaria", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('VEICULODIARIA_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Processar Horario de Ponto Eletronico == */
if (isset($_POST['processarHorarioPonto'])):
    if (intval($_POST['processarHorarioPonto']) == 0 || empty($_POST['processarHorarioPonto'])):
        die();
    endif;
    $pontoeletronico->processarHorarioPonto();
endif;

/* == Processar Tabela de horarios de Ponto Eletronico == */
if (isset($_POST['processarTabelaPonto'])):
    if (intval($_POST['processarTabelaPonto']) == 0 || empty($_POST['processarTabelaPonto'])):
        die();
    endif;
    $pontoeletronico->processarTabelaPonto();
endif;

/* == Processar Vinculacao de Horarios de ponto na Tabela de horarios == */
if (isset($_POST['processarHorarioTabelaPonto'])):
    if (intval($_POST['processarHorarioTabelaPonto']) == 0 || empty($_POST['processarHorarioTabelaPonto'])):
        die();
    endif;
    $pontoeletronico->processarHorarioTabelaPonto();
endif;

/* == Processar Feriado == */
if (isset($_POST['processarFeriado'])):
    if (intval($_POST['processarFeriado']) == 0 || empty($_POST['processarFeriado'])):
        die();
    endif;
    $pontoeletronico->processarFeriado();
endif;

/* == Delete Feriado == */
if (isset($_POST['apagarFeriado'])):
    if (intval($_POST['apagarFeriado']) == 0 || empty($_POST['apagarFeriado'])):
        die();
    endif;

    $id = intval($_POST['apagarFeriado']);
    $db->delete("feriados", "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('FERIADO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* Processar Configuracao aplicativo n2 */
if (isset($_POST['processarConfigAplicativo'])):
    if (intval($_POST['processarConfigAplicativo']) == 0 || empty($_POST['processarConfigAplicativo'])):
        die();
    endif;
    $empresa->processarConfigAplicativo();
endif;

//Processar imagem da logo marca no aplicativo
if (isset($_POST['processarImagemLogoAplicativo'])):
    if (intval($_POST['processarImagemLogoAplicativo']) == 0 || empty($_POST['processarImagemLogoAplicativo'])):
        die();
    endif;
    $empresa->processarImagemLogoAplicativo();
endif;

//Processar imagem do popup no aplicativo
if (isset($_POST['processarImagemPopupAplicativo'])):
    if (intval($_POST['processarImagemPopupAplicativo']) == 0 || empty($_POST['processarImagemPopupAplicativo'])):
        die();
    endif;
    $empresa->processarImagemPopupAplicativo();
endif;

/* == Duplcar nota fiscal de venda ==*/
if (isset($_POST['duplicarNotaFiscal'])):
    if (intval($_POST['duplicarNotaFiscal']) == 0 || empty($_POST['duplicarNotaFiscal'])):
        die();
    endif;
    $id_nota = intval($_POST['id_nota']);
    $produto->duplicarNotaFiscal($id_nota);
endif;

//Ao chegar um pedido do APP, ela aparecerá em uma notificação.
if (isset($_GET['pedidoNotificacao'])) {
    if (!isset($_GET['pedidoNotificacao']))
        die();

    //Id da venda aberta
    $sql_pedido = " SELECT v.id
        FROM vendas as v
        WHERE v.usuario_venda = 'APP' AND v.id_vendedor = 0
        AND v.inativo = 0 AND v.status_entrega = 1
        AND (v.pago = 2 OR v.crediario = 1) AND v.venda_crediario_finalizada = 0 ";
    $row_pedido = $db->first($sql_pedido);

    //Quantidade de pedidos abertos que veio do aplicativo.
    $sql_pedido_aberto = "SELECT count(v.id) as quantidade_aberto
        FROM vendas as v
        WHERE v.usuario_venda = 'APP' AND v.id_vendedor = 0
        AND v.inativo = 0 AND v.status_entrega = 1
        AND (v.pago = 2 OR v.crediario = 1) AND v.venda_crediario_finalizada = 0 ";
    $row_pedido_aberto = $db->first($sql_pedido_aberto);

    header('Content-Type: application/json');

    if (isset($row_pedido->id) && $row_pedido->id > 0) {
        $response = [
            'code' => 200,
        ];

        $response['id_venda'] = (int) $row_pedido->id;
        $response['quantidade_vendas_abertas'] = (int) $row_pedido_aberto->quantidade_aberto;
    } else {
        $response = [
            'code' => 0,
        ];
    }
    echo json_encode($response);
}

//Processa a logomarca do cliente no pdv
if (isset($_POST['processarLogoPdv'])):
    if (intval($_POST['processarLogoPdv']) == 0 || empty($_POST['processarLogoPdv'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $empresa->processarLogoPdv();
endif;

//Processa converter Venda em NF-e
if (isset($_POST['gerarNFEvenda'])):
    if (intval($_POST['gerarNFEvenda']) == 0 || empty($_POST['gerarNFEvenda'])):
        die();
    endif;
    $id_venda = (isset($_POST['gerarNFEvenda'])) ? $_POST['gerarNFEvenda'] : 0;
    $produto->gerarNFEvenda($id_venda);
endif;

//Processa NF-e dos produtos de uma Ordem de Servico
if (isset($_POST['gerarNFeOS'])):
    if (intval($_POST['gerarNFeOS']) == 0 || empty($_POST['gerarNFeOS'])):
        die();
    endif;
    $id_os = (isset($_POST['gerarNFeOS'])) ? $_POST['gerarNFeOS'] : 0;
    $produto->gerarNFeOS($id_os);
endif;

//Processa FATURAMENTO de uma Ordem de Servico
if (isset($_POST['gerarFaturaOS'])):
    if (intval($_POST['gerarFaturaOS']) == 0 || empty($_POST['gerarFaturaOS'])):
        die();
    endif;
    $id_os = (isset($_POST['gerarFaturaOS'])) ? $_POST['gerarFaturaOS'] : 0;
    $produto->gerarFaturaOS($id_os);
endif;

//Ao chegar um pedido do APP, ela aparecerá em uma notificação.
if (isset($_GET['notificacaoAtualizacoes'])) {
    if (!isset($_GET['notificacaoAtualizacoes']))
        die();

    $row_verifica = $empresa->verificaAtualizacao();
    $r = json_decode($row_verifica, true);

    if ($r):
        $cont = 0;
        foreach ($r as $res):
            if (
                $res['categoria'] === "SIGE N1" ||
                $res['categoria'] === "SIGE G1" ||
                $res['categoria'] === "SIGE E1" ||
                $res['categoria'] === "SIGE N2"
            ):

                $cont += 1;
                header('Content-Type: application/json');

                if ($res['id'] > 0) {
                    $response = ['code' => 200,];
                    $response['id_atualizacao'] = (int) $res['id'];
                    $response['data_atualizacao'] = $res['data_atualizacao'];
                } else {
                    $response = [
                        'code' => 0,
                    ];
                }
                echo json_encode($response, JSON_PRETTY_PRINT);
            endif;
        endforeach;
    endif;
}

/* == Processar Importação de podutos == */
if (isset($_POST['processarPlanilhaProdutosImportacao'])):
    if (intval($_POST['processarPlanilhaProdutosImportacao']) == 0 || empty($_POST['processarPlanilhaProdutosImportacao'])):
        die();
    endif;
    $produto->processarPlanilhaProdutosImportacao();
endif;

/* == Processar Importação de CLIETE == */
if (isset($_POST['processarPlanilhaClienteFornecedorImportacao'])):
    if (intval($_POST['processarPlanilhaClienteFornecedorImportacao']) == 0 || empty($_POST['processarPlanilhaClienteFornecedorImportacao'])):
        die();
    endif;
    $cadastro->processarPlanilhaClienteFornecedorImportacao();
endif;

/* == Processar Taxa == */
if (isset($_POST['processarTaxa'])):
    if (intval($_POST['processarTaxa']) == 0 || empty($_POST['processarTaxa'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $produto->processarTaxa();
endif;

/* == Delete Taxa == */
if (isset($_POST['apagarTaxa'])):
    if (intval($_POST['apagarTaxa']) == 0 || empty($_POST['apagarTaxa'])):
        die();
    endif;

    $id = intval($_POST['apagarTaxa']);
    $data = array(
        'inativo' => '1',
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );

    $db->update("taxas", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('TAXAS_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Processar Bairro == */
if (isset($_POST['processarBairro'])):
    if (intval($_POST['processarBairro']) == 0 || empty($_POST['processarBairro'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $produto->processarBairro();
endif;

/* == Delete Bairro == */
if (isset($_POST['apagarBairro'])):
    if (intval($_POST['apagarBairro']) == 0 || empty($_POST['apagarBairro'])):
        die();
    endif;

    $id = intval($_POST['apagarBairro']);
    $data = array(
        'inativo' => '1',
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );

    $db->update("bairros", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('BAIRROS_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Processar Ordem de Servico == */
if (isset($_POST['processarOrdemServico'])):
    if (intval($_POST['processarOrdemServico']) == 0 || empty($_POST['processarOrdemServico'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $ordem_servico->processarOrdemServico();
endif;

/* == Processar ORCAMENTO == */
if (isset($_POST['processarOrcamento_OrdemServico'])):
    if (intval($_POST['processarOrcamento_OrdemServico']) == 0 || empty($_POST['processarOrcamento_OrdemServico'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $ordem_servico->processarOrcamento_OrdemServico();
endif;

/* == Processar VALOR do ORCAMENTO == */
if (isset($_POST['processarValorOrcamento'])):
    if (intval($_POST['processarValorOrcamento']) == 0 || empty($_POST['processarValorOrcamento'])):
        die();
    endif;
    $ordem_servico->processarValorOrcamento();
endif;

/* == Processar descricao do atendimento da Ordem de Servico == */
if (isset($_POST['processarOS_Atendimento'])):
    if (intval($_POST['processarOS_Atendimento']) == 0 || empty($_POST['processarOS_Atendimento'])):
        die();
    endif;
    $ordem_servico->processarOS_Atendimento();
endif;

/* == Concluir (finalizar) Orcamento == */
if (isset($_POST['concluirOrcamento'])):
    if (intval($_POST['concluirOrcamento']) == 0 || empty($_POST['concluirOrcamento'])):
        die();
    endif;

    $id = intval($_POST['concluirOrcamento']);
    $data = array(
        'data_orcamento' => "NOW()",
        'usuario_orcamento' => session('nomeusuario'),
        'id_status' => '3',
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );

    $db->update("ordem_servico", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('ORCAMENTO_CONCLUIR_OK'), "index.php?do=ordem_servico&acao=orcamentos");
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == FINALIZAR ORDEM DE SERVICO == */
if (isset($_POST['finalizarServico'])):
    if (intval($_POST['finalizarServico']) == 0 || empty($_POST['finalizarServico'])):
        die();
    endif;

    $id_OS = intval($_POST['finalizarServico']);
    $data = array(
        'data_execucao' => "NOW()",
        'usuario_execucao' => session('nomeusuario'),
        'id_status' => '7',
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );

    $db->update("ordem_servico", $data, "id=" . $id_OS);
    if ($db->affected()) {
        print Filter::msgOk(lang('ORDEM_SERVICO_FINALIZAR_OK'), "index.php?do=ordem_servico&acao=listar_os_finalizadas");
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == APROVAR ORCAMENTO - Cliente aprovou o orcamento e a Ordem de Servico sera autorizada == */
if (isset($_POST['aprovarOrcamento'])):
    if (intval($_POST['aprovarOrcamento']) == 0 || empty($_POST['aprovarOrcamento'])):
        die();
    endif;

    $id = intval($_POST['aprovarOrcamento']);
    $data = array(
        'id_status' => '5',
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );

    $db->update("ordem_servico", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('ORCAMENTO_APROVAR_OK'), "index.php?do=ordem_servico&acao=listar");
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Delete Orcamento == */
if (isset($_POST['cancelarOrcamento'])):
    if (intval($_POST['cancelarOrcamento']) == 0 || empty($_POST['cancelarOrcamento'])):
        die();
    endif;
    $id_orcamento = intval($_POST['id']);
    $ordem_servico->cancelarOrcamento($id_orcamento);
endif;

/* == Processar Item Ordem de Servico == */
if (isset($_POST['processarItemOrdemServico'])):
    if (intval($_POST['processarItemOrdemServico']) == 0 || empty($_POST['processarItemOrdemServico'])):
        die();
    endif;
    $ordem_servico->processarItemOrdemServico();
endif;

/* == Delete Item Ordem de Servico == */
if (isset($_POST['apagarItemOrdemServico'])):
    if (intval($_POST['apagarItemOrdemServico']) == 0 || empty($_POST['apagarItemOrdemServico'])):
        die();
    endif;

    $id = intval($_POST['apagarItemOrdemServico']);
    $data = array(
        'inativo' => '1',
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );

    $db->update("ordem_itens", $data, "id=" . $id);
    if ($db->affected()) {
        $ordem_item = Core::getRowById("ordem_itens", $id);
        $produto_orcamento = getValue("valor_produto", "ordem_servico", "id=" . $ordem_item->id_ordem);
        $valor_produto = $ordem_item->valor_total;
        $valor_produto_orcamento = $produto_orcamento - $valor_produto;
        $data_orcamento = array(
            'valor_produto' => $valor_produto_orcamento,
            'usuario' => session('nomeusuario'),
            'data' => "NOW()"
        );
        $db->update("ordem_servico", $data_orcamento, "id=" . $ordem_item->id_ordem);
        print Filter::msgOk(lang('ORCAMENTO_APAGAR_ITEM_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Processar Equipamento == */
if (isset($_POST['processarEquipamento'])):
    if (intval($_POST['processarEquipamento']) == 0 || empty($_POST['processarEquipamento'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $ordem_servico->processarEquipamento();
endif;

/* == Delete Equipamento == */
if (isset($_POST['apagarEquipamento'])):
    if (intval($_POST['apagarEquipamento']) == 0 || empty($_POST['apagarEquipamento'])):
        die();
    endif;

    $id = intval($_POST['apagarEquipamento']);
    $data = array(
        'inativo' => '1',
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );

    $db->update("equipamento", $data, "id=" . $id);
    if ($db->affected()) {
        print Filter::msgOk(lang('EQUIPAMENTO_APAGAR_OK'));
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Processar Valor Adicional da Ordem de Serviço == */
if (isset($_POST['processarValorAdicionalOrdemServico'])):
    if (intval($_POST['processarValorAdicionalOrdemServico']) == 0 || empty($_POST['processarValorAdicionalOrdemServico'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $ordem_servico->processarValorAdicionalOrdemServico();
endif;

/* == Processar Novo Valor de Produto da Ordem de Serviço == */
if (isset($_POST['processarNovoValorProdutoOrdemServico'])):
    if (intval($_POST['processarNovoValorProdutoOrdemServico']) == 0 || empty($_POST['processarNovoValorProdutoOrdemServico'])):
        die();
    endif;
    Filter::$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
    $ordem_servico->processarNovoValorProdutoOrdemServico();
endif;

/* == Delete Produto do Orcamento == */
if (isset($_POST['apagarProdutoOS'])):
    if (intval($_POST['apagarProdutoOS']) == 0 || empty($_POST['apagarProdutoOS'])):
        die();
    endif;

    $id_ordem_itens = intval($_POST['apagarProdutoOS']);
    $data_ordem_itens = array(
        'inativo' => '1',
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("ordem_itens", $data_ordem_itens, "id=" . $id_ordem_itens);
    if ($db->affected()) {
        $id_ordem_servico = getValue("id_ordem", "ordem_itens", "id=" . $id_ordem_itens);
        $row_os = Core::getRowById("ordem_servico", $id_ordem_servico);
        $valor_servico = $row_os->valor_servico;
        $valor_adicional = $row_os->valor_adicional;
        $valor_desconto = $row_os->valor_desconto;
        $novo_valor_produtos = $ordem_servico->ObterValorProdutosOrdemServico($id_ordem_servico);
        $novo_valor_total = $novo_valor_produtos + $valor_servico + $valor_adicional - $valor_desconto;

        $data_ordem_servico = array(
            'valor_produto' => $novo_valor_produtos,
            'valor_total' => $novo_valor_total,
            'usuario' => session('nomeusuario'),
            'data' => "NOW()"
        );
        $db->update("ordem_servico", $data_ordem_servico, "id=" . $id_ordem_servico);
        $message = lang('ORCAMENTO_APAGAR_PRODUTO');
        Filter::msgOk($message, "index.php?do=ordem_servico&acao=gerenciarvalororcamento&id=" . $id_ordem_servico);
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }
endif;

/* == Aceitar atualização de estoque ==*/
if (isset($_POST['acaoAnaliseEstoque'])):
    if (intval($_POST['acaoAnaliseEstoque']) == 0 || empty($_POST['acaoAnaliseEstoque'])):
        die();
    endif;

    $id_produto = !empty($_POST['id_produto']) ? intval($_POST['id_produto']) : 0;
    $estoque_atual = !empty($_POST['estoque_atual']) ? floatval($_POST['estoque_atual']) : 0;
    $estoque_fisico = !empty($_POST['estoque_fisico']) ? floatval($_POST['estoque_fisico']) : 0;
    $valor_custo = !empty($_POST['valor_custo']) ? floatval($_POST['valor_custo']) : 0;
    $somar = !empty($_POST['somar']) ? intval($_POST['somar']) : 0;
    $produto->acaoAnaliseEstoque($id_produto, $estoque_atual, $estoque_fisico, $valor_custo, $somar);

endif;

/* == Somar estoque de todos os produtos na tabela analise ==*/
if (isset($_POST['somarEstoqueTodosProdutos'])):
    if (intval($_POST['somarEstoqueTodosProdutos']) == 0 || empty($_POST['somarEstoqueTodosProdutos'])):
        die();
    endif;
    $produto->somarEstoqueTodosProdutos();
endif;

/* == Substituir estoque de todos os produtos na tabela analise ==*/
if (isset($_POST['substituirEstoqueTodosProdutos'])):
    if (intval($_POST['substituirEstoqueTodosProdutos']) == 0 || empty($_POST['substituirEstoqueTodosProdutos'])):
        die();
    endif;
    $produto->substituirEstoqueTodosProdutos();
endif;

/* == Manter estoque de todos os produtos na tabela analise ==*/
if (isset($_POST['manterEstoqueTodosProdutos'])):
    if (intval($_POST['manterEstoqueTodosProdutos']) == 0 || empty($_POST['manterEstoqueTodosProdutos'])):
        die();
    endif;

    $id_produto = $_POST['id_produto'];
    $cont = is_array($id_produto) ? count($id_produto) : 0;

    if ($cont > 0) {
        for ($i = 0; $i < $cont; $i++) {
            $db->delete("estoque_analise", "id_produto=".$id_produto[$i]);
        }

        if ($db->affected()) Filter::msgOk(lang('MSG_PRODUTO_NADA_ALTERADO'), "index.php?do=estoque&acao=comparacaoestoquefisico");
        else Filter::msgAlert(lang('NAOPROCESSADO'));
    } else {
        Filter::msgError("Nenhum produto selecionado.", "index.php?do=estoque&acao=comparacaoestoquefisico");
    }

endif;

    /* == Exportar produtos ==*/
    if (isset($_POST['processarExportacaoProduto'])):
        if (intval($_POST['processarExportacaoProduto']) == 0 || empty($_POST['processarExportacaoProduto'])):
            die();
        endif;
        $produto->processarExportacaoProduto();
    endif;

/* == Apagar orcamento de produtos (venda)== */
if (isset($_POST['apagarOrcamentoVenda'])):
    if (intval($_POST['apagarOrcamentoVenda']) == 0 || empty($_POST['apagarOrcamentoVenda'])):
        die();
    endif;

    $id = intval($_POST['apagarOrcamentoVenda']);

    $orcamento = getValue("orcamento", "vendas", "id=". $id);

    if ($orcamento == 1)
    {
        $data = array(
            'inativo' => '1',
            'usuario' => session('nomeusuario'),
            'data' => "NOW()"
        );

        $db->update("vendas", $data, "id =" . $id);
        $db->update("cadastro_vendas", $data, "id_venda =" . $id);
        $db->update("cadastro_financeiro", $data, "id_venda =" . $id);
        $db->update("receita", $data, "id_venda =" . $id);

        if ($db->affected()) {
            print Filter::msgOk(lang('APAGAR_ORCAMENTO'), "index.php?do=vendas&acao=vendasorcamento");
        } else {
            print Filter::msgAlert(lang('NAOPROCESSADO'));
        }
    } else {
        print Filter::msgError(lang('NAOPROCESSADO_CANCELARORCAMENTO'), "index.php?do=vendas&acao=vendasorcamento");
    }
endif;

/* == Processar Adicionar produto no orçamento (venda) == */
if (isset($_POST['processarAdicionarProdOrcamento'])):
    if (intval($_POST['processarAdicionarProdOrcamento']) == 0 || empty($_POST['processarAdicionarProdOrcamento'])):
        die();
    endif;
    $cadastro->processarAdicionarProdOrcamento();
endif;

/* == Processar Alteracao de quantidade do produto no orçamento (venda) == */
if (isset($_POST['processarAlterarQuantidadeProdOrcamento'])):
    if (intval($_POST['processarAlterarQuantidadeProdOrcamento']) == 0 || empty($_POST['processarAlterarQuantidadeProdOrcamento'])):
        die();
    endif;
    $cadastro->processarAlterarQuantidadeProdOrcamento();
endif;

/* == Apagar produto do orçamento (venda) == */
if (isset($_POST['apagarProdutoOrcamento'])):
    if (intval($_POST['apagarProdutoOrcamento']) == 0 || empty($_POST['apagarProdutoOrcamento'])):
        die();
    endif;

    $sucesso = 0;
    $id = intval($_POST['apagarProdutoOrcamento']);
    $row_cadastro_vendas = Core::getRowById("cadastro_vendas", $id);
    $row_vendas = Core::getRowById("vendas", $row_cadastro_vendas->id_venda);

    $id_cadastro = $row_cadastro_vendas->id_cadastro;
    $id_orcamento = $row_cadastro_vendas->id_venda;
    $quantidade = $row_cadastro_vendas->quantidade;
    $id_produto = $row_cadastro_vendas->id_produto;
    $id_caixa = $row_cadastro_vendas->id_caixa;
    $id_empresa = $row_cadastro_vendas->id_empresa;

    $nomecadastro = getValue("nome", "cadastro", "id=" . $id_cadastro);
    $kit = getValue("kit", "produto", "id=" . $id_produto);

    $data = array(
        'inativo' => '1',
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("cadastro_vendas", $data, "id=" . $id);

    $produtos_venda = $cadastro->getProdutosAtivosDaVenda($id_orcamento);
    if ($produtos_venda) {
        $valor_acrescimo_venda = getValue("valor_despesa_acessoria", "vendas", "id=" . $id_orcamento);
        $valor_acrescimo_produto = round(($valor_acrescimo_venda / count($produtos_venda)), 2);
        $porcentagem_desconto = ($row_vendas->valor_desconto * 100) / $row_vendas->valor_total;
        foreach ($produtos_venda as $produto_venda) {
            $desconto_produto = ($porcentagem_desconto * $produto_venda->valor_total) / 100;
            $desconto_produto = round($desconto_produto, 2);
            $data_desconto = array(
                'valor_desconto' => $desconto_produto,
                'valor_despesa_acessoria' => $valor_acrescimo_produto,
                'usuario' => session('nomeusuario'),
                'data' => "NOW()"
            );
            $db->update("cadastro_vendas", $data_desconto, "id=" . $produto_venda->id);
        }
    }

    ///////////////////////////////////
    //Este trecho de codigo serve para ajustar o valor do desconto quando o mesmo for quebrado e gerar diferenca no total.
    $novo_valor_desconto = $cadastro->obterDescontosVenda($id_orcamento, $id_caixa, $id_empresa, $id_cadastro);
    if (round($novo_valor_desconto->vlr_desc, 2) != round($row_vendas->valor_desconto, 2)) {
        $novo_desconto = ($row_vendas->valor_desconto - round($novo_valor_desconto->vlr_desc, 2)) + $novo_valor_desconto->valor_desconto;
        $data_desconto = array('valor_desconto' => $novo_desconto, 'usuario' => session('nomeusuario'), 'data' => "NOW()");
        $db->update("cadastro_vendas", $data_desconto, "id=" . $novo_valor_desconto->id);
    }
    ///////////////////////////////////
    //Este trecho de codigo serve para ajustar o valor do acrescimo quando o mesmo for quebrado e gerar diferenca no total.
    $novo_valor_acrescimo = $cadastro->obterAcrescimoVenda($id_orcamento, $id_caixa, $id_empresa, $id_cadastro);
    if (round($novo_valor_acrescimo->vlr_acrescimo, 2) != round($valor_acrescimo_venda, 2)) {
        $novo_acrescimo = ($valor_acrescimo_venda - round($novo_valor_acrescimo->vlr_acrescimo, 2)) + $novo_valor_acrescimo->valor_despesa_acessoria;
        $data_acrescimo = array('valor_despesa_acessoria' => $novo_acrescimo, 'usuario' => session('nomeusuario'), 'data' => "NOW()");
        $db->update("cadastro_vendas", $data_acrescimo, "id=" . $novo_valor_acrescimo->id);
    }
    ///////////////////////////////////

    $valor_produtos_venda = $cadastro->getTotalProdutosVenda($id_orcamento);
    $data_update = array(
        'valor_total' => $valor_produtos_venda->valor_total,
        'valor_pago' => $valor_produtos_venda->valor_total + $valor_produtos_venda->valor_acrescimo - $valor_produtos_venda->valor_desconto,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("vendas", $data_update, "id=" . $id_orcamento);
    $sucesso = ($db->affected()) ? 1 : $sucesso;

    $data_update_financeiro = array(
        'valor_total_venda' => $valor_produtos_venda->valor_total + $valor_produtos_venda->valor_acrescimo - $valor_produtos_venda->valor_desconto,
        'usuario' => session('nomeusuario'),
        'data' => "NOW()"
    );
    $db->update("cadastro_financeiro", $data_update_financeiro, "id_venda=" . $id_orcamento . " AND inativo = 0");

    $sucesso = ($db->affected()) ? 1 : $sucesso;

    if ($sucesso) {
        print Filter::msgOk(lang('CADASTRO_ITEM_APAGAR_OK'), "index.php?do=vendas&acao=editarvendasorcamento&id=" . $id_orcamento);
    } else {
        print Filter::msgAlert(lang('NAOPROCESSADO'));
    }

endif;

/* == Transformar orcamento de produtos para venda == */
if (isset($_POST['transformarOrcamentoParaVenda'])):
    if (intval($_POST['transformarOrcamentoParaVenda']) == 0 || empty($_POST['transformarOrcamentoParaVenda'])):
        die();
    endif;

    $id = intval($_POST['transformarOrcamentoParaVenda']);
    $cadastro->processarTransformarOrcamentoParaVenda($id);
endif;

/* == Adicionar info empresa == */
if (isset($_POST['processarInfoAtualizarCertificado'])):
    if (intval($_POST['processarInfoAtualizarCertificado']) == 0 || empty($_POST['processarInfoAtualizarCertificado'])):
        die();
    endif;

    // $id = intval($_POST['processarInfoAtualizarCertificado']);
    $empresa->processarInfoAtualizarCertificado();
endif;


/* == Função para  == */
if (isset($_POST['salvarValorUtilizarCrediario'])):
    if (intval($_POST['salvarValorUtilizarCrediario']) == 0 || empty($_POST['salvarValorUtilizarCrediario'])):
        die();
    endif;
    if (empty($_POST['salvar'])) {
        Filter::$id = (!empty($_POST['id_venda'])) ? post('id_venda') : 0;
        $id_usuario = $usuario->uid;
        $id_caixa = $faturamento->verificaCaixa($id_usuario);

        if ($id_caixa > 0) {
            $produto->salvarValorUtilizarCrediario($id_caixa);
        } else {
            print Filter::msgAlert(lang('CAIXA_VENDA_ERRO'));
        }
    } else {
        $produto->salvarValorUtilizarCrediario();
    }
endif;

/* == Verificar usuario e senha para cancelamento de produto no PDV == */
if (isset($_POST['verificarPinProdutoVenda'])):
    if (intval($_POST['verificarPinProdutoVenda']) == 0 || empty($_POST['verificarPinProdutoVenda'])):
        die();
    endif;

    $pinUser = sha1(strtolower($_POST['pin']));
    echo $usuario->getUsuariosNivelMaior($pinUser);
endif;

/* == Verificar usuario e senha para cancelamento de produto no PDV == */
if (isset($_POST['processarExportacaoBalanca'])):
    if (intval($_POST['processarExportacaoBalanca']) == 0 || empty($_POST['processarExportacaoBalanca'])):
        die();
    endif;
    $tipo = $_POST['tipo'];
    $produto->processarExportacaoBalanca($tipo);
endif;

?>
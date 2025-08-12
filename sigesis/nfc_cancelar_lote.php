<?php
	define('_VALID_PHP', true);
	header('Content-Type: text/html; charset=utf-8');	
	
	require('enotas/eNotasGW.php');
	require('init.php');	
	
	use eNotasGW\Api\Exceptions as Exceptions;

	eNotasGW::configure(array(
		'apiKey' => $enotas_apikey
	));
	
	$id_venda = intval(get('id_venda'));
	$row_vendas = Core::getRowById("vendas", $id_venda);

	$id_caixa = intval(get('id_caixa'));
	$historico = intval(get('historico'));

	$total_dinheiro = get('total_dinheiro');
		
	$dentroPrazoCancelamento = !(strtotime($row_vendas->data_emissao.' +30 minutes') < strtotime(date('Y-m-d H:i:s')));;
	if (!$dentroPrazoCancelamento){
		Filter::$msgs['prazo_cancelamento'] = lang('MSG_ERRO_CANCELAR_NFCE');
	}

	if ($id_caixa == 0 and $total_dinheiro > 0) {
		if (empty($GET['id_banco']))	
            Filter::$msgs['id_banco'] = lang('MSG_ERRO_BANCO');		  
	}

	if ($row_vendas->venda_agrupamento==0) {
		Filter::$msgs['venda_agrupamento'] = "ERRO! Falha na tentativa de concelamento da NFC-e Agrupamento/Lote";
	}

	if (empty(Filter::$msgs)) {
			
			$NFCe_cancelado = false;
			$row_empresa = Core::getRowById("empresa", $row_vendas->id_empresa);
			$id_enotas = $row_empresa->enotas;
			
			if ($core->emissor_producao) {
				$ambiente = 'Producao'; //'Producao' ou 'Homologacao'
				$id_externo = ($row_empresa->versao_emissao==0) ? 'nfc-'.$id_venda : 'nfc'.$row_empresa->versao_emissao.'-'.$id_venda;
			} else {	
				echo "</br>--- --- --- --- AMBIENTE DE HOMOLOGAÇÃO --- --- --- ---</br>";
				$ambiente = 'Homologacao'; //'Producao' ou 'Homologacao'
				$id_externo = ($row_empresa->versao_emissao==0) ? 'Hnfc-'.$id_venda : 'Hnfc'.$row_empresa->versao_emissao.'-'.$id_venda;
			}
			
			try {
				$cupom_cancelado = eNotasGW::$NFeConsumidorApi->cancelar($id_enotas, $id_externo);

				if ($cupom_cancelado === null) {
					$NFCe_cancelado = $cadastro->atualizarCupom($id_venda, $id_enotas, $id_externo);
				}
				else{
					echo lang('CADASTRO_APAGAR_VENDA_FISCAL_NOK');
				}	
			} catch (Exception $e) {
				// NF0001 - Código de cupom já status cancelado
				if ($e->errors[0]->codigo == 'NF0001') {
					echo lang('CADASTRO_APAGAR_VENDA_FISCAL_OK');
					$NFCe_cancelado = $cadastro->atualizarCupom($id_venda, $id_enotas, $id_externo);
				} else
					echo $e->getMessage();
			}

			if ($NFCe_cancelado) {

				$data_geral = array(
					'inativo' => 1,
					'usuario' => session('nomeusuario'),
					'data' => "NOW()"
				);
		
				$db->update("vendas", $data_geral, "id=".$id_venda);
		
				$data_vendas_agrupadas = array(
					'venda_agrupada' => 0,
					'fiscal' => 0
				);
		
				$db->update("vendas", $data_vendas_agrupadas, "venda_agrupada=".$id_venda);
		
				$db->update("cadastro_vendas", $data_geral, "id_venda=".$id_venda);
				$db->update("cadastro_financeiro", $data_geral, "id_venda=".$id_venda);
						
				echo lang('VENDAS_CANCELAR_AGRUPAMENTO_OK');
		
			}
		
		} else
              print Filter::msgStatus();

?>
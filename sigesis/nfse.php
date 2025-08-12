<?php
 /**
   * PDF - Nota Fiscal de Serviço - Emissao
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */ 

	define('_VALID_PHP', true);
	header('Content-Type: text/html; charset=utf-8');
	
	require('enotas_servico/eNotasGW.php');
	require('init.php');	
	
	if (!$usuario->is_Todos())
	  redirect_to('login.php');
	
	use eNotasGW\Api\Exceptions as Exceptions;

	eNotasGW::configure(array(
		'apiKey' => $enotas_apikey
	));	
	
	$id = get('id');
	$dg = get('debug');
	$debug = ($dg == 1) ? true: false;
	
	$row_notafiscal = Core::getRowById('nota_fiscal', $id);
	$row_empresa = Core::getRowById('empresa', $row_notafiscal->id_empresa);
	$row_cadastro = Core::getRowById('cadastro', $row_notafiscal->id_cadastro);
	$id_enotas = $row_empresa->enotas;
	
	$idExterno = $id;
	$ambiente = 'Producao'; //'Producao' ou 'Homologacao'
	// $idExterno = 'H'.$id;
	
	$razao_social = $row_cadastro->razao_social;
	$cpf_cnpj = limparNumero($row_cadastro->cpf_cnpj);	
	$tipoPessoa = ($row_cadastro->tipo == 1) ? 'J' : 'F';
	$enviarPorEmail = ($row_cadastro->email) ? true : false;
	if(strlen($cpf_cnpj) != 14 and $tipoPessoa == 'J')
		Filter::$msgs['cpf_cnpj'] = 'ERRO NO CNPJ DO CLIENTE (deve ter 14 digitos): '.$cpf_cnpj;
	
	if(strlen($cpf_cnpj) != 11 and $tipoPessoa == 'F')
		Filter::$msgs['cpf_cnpj'] = 'ERRO NO CPF DO CLIENTE (deve ter 11 digitos): '.$cpf_cnpj;
	
	$cep = limparNumero($row_cadastro->cep);
	if(strlen($cep) != 8)
		Filter::$msgs['cep'] = 'ERRO NO CEP DO ENDERECO DO CLIENTE (deve ter 8 digitos): '.$cep;
	
	$complemento = ($row_cadastro->complemento) ? $row_cadastro->complemento : null;
	
	if (empty(Filter::$msgs)) {	
		try
		{
			if($row_notafiscal->fiscal) {
				$pdf = eNotasGW::$NFeApi->downloadPdfPorIdExterno($id_enotas, $idExterno);			
				header('Content-Type: application/pdf');
				header('Content-Disposition: inline; filename="'.rawurlencode('nfe-'.$idExterno.'.pdf').'"');
				header('Cache-Control: private, max-age=0, must-revalidate');
				header('Pragma: public');
				echo $pdf;
			} else {		
				$array_cliente = array(
					'tipoPessoa'=> $tipoPessoa,
					'nome'=> $row_cadastro->razao_social,
					'email'=> $row_cadastro->email,
					'cpfCnpj'=> $cpf_cnpj,
					'endereco' => array(
						'uf' => $row_cadastro->estado, 
						'cidade' => $row_cadastro->cidade,
						'logradouro' => $row_cadastro->endereco, 
						'numero' => limparNumero($row_cadastro->numero), 
						'complemento' => $complemento, 
						'bairro' => $row_cadastro->bairro, 
						'cep' => $cep
					)
				);
				$issRetidoFonte = ($row_notafiscal->iss_retido) ? true : false;
				$nota_simples = array(
					'tipo' => 'NFS-e',
					'idExterno' => $idExterno,
					'ambienteEmissao' => $ambiente,  //'Producao' ou 'Homologacao'
					'cliente'=> $array_cliente,	
					'enviarPorEmail'=> $enviarPorEmail,			
					'servico' => array(
						'descricao' => $row_notafiscal->descriminacao,
						'aliquotaIss' => $row_notafiscal->iss_aliquota,
						'issRetidoFonte' => $issRetidoFonte,
						'codigoServicoMunicipio' => '14.01',
						'descricaoServicoMunicipio' => '14.01 - LUBRIFICACAO, LIMPEZA, LUSTRACAO, REVISAO, CARGA E RECARGA, CONSERTO, RESTAURACAO, BLINDAGEM, MANUTENCAO E CONSERVACAO DE MAQUINAS, VEICULOS, APARELHOS, EQUIPAMENTOS, MOTORES, ELEVADORES OU DE QUALQUER OBJETO(EXCETO PECAS E PARTES EMPREGADAS, QUE FICAM SUJEITAS AO ICMS)'
					),
					'valorTotal' => $row_notafiscal->valor_servico
				);
				if($debug) {
					echo "ID EMPRESA: $id_enotas </br>";
					echo "</br>--- INICIO NOTA NORMAL ---</br>";
					$json_nota = json_encode($nota_simples);
					echo $json_nota;
					echo "</br>--- FIM NOTA NORMAL ---</br>";
				}
				
				$enota = eNotasGW::$NFeApi->emitir($id_enotas,$nota_simples);
				sleep(15);
				$retorno = eNotasGW::$NFeApi->consultarPorIdExterno($id_enotas, $idExterno);
				if($retorno->status == 'Autorizada') {
					print_r($retorno);
					$numero_nota = $retorno->numeroRps.'-'.$retorno->serieRps;
					$data_emissao = substr(sanitize($retorno->dataCriacao), 0,10);
					if(intval($numero_nota) > 0) {
						$data = array(
							'numero_nota' => $numero_nota,
							'data_emissao' => $data_emissao,
							'fiscal' => '1',
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						$db->update("nota_fiscal", $data, "id=" . $id);
						if(!$debug) {
							redirect_to("index.php?do=notafiscal&acao=visualizar_servico&id=".$id);
						}
					}
				} else {
					sleep(15);
					echo "<b/>Consulta para buscar retorno NF-e NORMAL:</b></br>";
					$retorno = eNotasGW::$NFeApi->consultarPorIdExterno($id_enotas, $idExterno);
					echo "Envio da consulta para o ID: $id </br>";
					echo "ID da empresa: $id_enotas </br></br>";
					echo 'STATUS: ';
					echo $retorno->status;
					echo '</br>';
					echo 'MOTIVO STATUS: ';
					echo $retorno->motivoStatus;
					echo '</br>';
					if($debug) {
						echo "</br>--- RETORNO COMPLETO ---</br>";
						echo json_encode($retorno);
						echo "</br>--- FIM RETORNO ---</br>";
					}
					if($retorno->status == 'Autorizada') {
					$numero_nota = $retorno->numeroRps.'-'.$retorno->serieRps;
					$data_emissao = substr(sanitize($retorno->dataCriacao), 0,10);
					if(intval($numero_nota) > 0) {
						$data_financeiro = array(
							'fiscal' => '1',
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						$db->update("cadastro_financeiro", $data_financeiro, "id=" . $row_notafiscal->id_financeiro);
						$data = array(
							'numero_nota' => $numero_nota,
							'data_emissao' => $data_emissao,
							'fiscal' => '1',
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						$db->update("nota_fiscal", $data, "id=" . $id);
						if(!$debug) {
							redirect_to("index.php?do=notafiscal&acao=visualizar_servico&id=".$id);
						}
					}
				}
				}
			}
		}
		catch(Exceptions\invalidApiKeyException $ex) {
			echo 'Erro de autenticação: </br></br>';
			echo $ex->getMessage();
		}
		catch(Exceptions\unauthorizedException $ex) {
			echo 'Acesso negado: </br></br>';
			echo $ex->getMessage();
		}
		catch(Exceptions\apiException $ex) {
			echo '<b>Erro de validação:</b> </br></br>';
			$msg = $ex->getMessage();
			echo '<b>Mensagem:</b> </br></br>';
			// print_r($ex);
			if(validaTexto($msg, 'Autorizada')) {
				$retorno = eNotasGW::$NFeApi->consultarPorIdExterno($id_enotas, $idExterno);
				if($retorno->status == 'Autorizada') {
					$numero_nota = $retorno->numeroRps.'-'.$retorno->serieRps;
					$data_emissao = substr(sanitize($retorno->dataCriacao), 0,10);
					if(intval($numero_nota) > 0) {
						$data_financeiro = array(
							'fiscal' => '1',
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						$db->update("cadastro_financeiro", $data_financeiro, "id=" . $row_notafiscal->id_financeiro);
						$data = array(
							'numero_nota' => $numero_nota,
							'data_emissao' => $data_emissao,
							'fiscal' => '1',
							'usuario' => session('nomeusuario'),
							'data' => "NOW()"
						);
						$db->update("nota_fiscal", $data, "id=" . $id);
						if(!$debug) {
							redirect_to("index.php?do=notafiscal&acao=visualizar_servico&id=".$id);
						}
					}
				} else {
					echo "ID da empresa: $id_enotas </br></br>";
					echo "Retorno de dados da nota (consulta por id): $id</br></br>";
					echo 'STATUS: ';
					echo $retorno->status;
					echo '</br>';
					echo 'MOTIVO STATUS: ';
					echo $retorno->motivoStatus;
					echo '</br>';
					if($debug) {
						echo "</br>--- RETORNO COMPLETO ---</br>";
						echo json_encode($retorno);
						echo "</br>--- FIM RETORNO ---</br>";
					}
				}
			} else {
				echo $msg;
			}
		}
		catch(Exceptions\requestException $ex) {
			echo 'Erro na requisição web: </br></br>';
			
			echo 'Requested url: ' . $ex->requestedUrl;
			echo '</br>';
			echo 'Response Code: ' . $ex->getCode();
			echo '</br>';
			echo 'Message: ' . $ex->getMessage();
			echo '</br>';
			echo 'Response Body: ' . $ex->responseBody;
		}
	} else {
		echo 'CLIENTE: '.$razao_social;
		echo '<br/>';
		echo '<a href="index.php?do=cadastro&acao=editar&id='.$row_notafiscal->id_cadastro.'" title="EDITAR O CLIENTE" target="_blank">EDITAR O CLIENTE</a>';
		echo '<br/>';
		echo '<br/>';
		$retorno = Filter::msgStatus();
		$mensagem = explode('#', $retorno);
		print $mensagem[0];
	}
?>

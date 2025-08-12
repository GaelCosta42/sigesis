<?php
  /**
   * Classe Arquivo
   *
   */

  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe não é permitido.');

  class Arquivo
  {
      public $did = 0;
      private static $db;

      /**
       * Parceiro::__construct()
       *
       * @return
       */
      function __construct()
      {
          self::$db = Registry::get("Database");
      }

	  /**
       * Arquivo::processarNFeEntrada()
       * MODELO: 1 - SERVICO
       * MODELO: 2 - PRODUTO
       * MODELO: 3 - FATURA
       * MODELO: 4 - TRANSPORTE
       * OPERACAO: 1 - COMPRA (ENTRADA)
       * OPERACAO: 2 - VENDA (SAIDA)
       * @return
       */
      public function processarNFeEntrada()
      {
		$contar = 0;
		$id_nota = 0;
		$isValido = 0;
		foreach($_POST as $nome_campo => $valor){
			$valida = strpos($nome_campo, "tmpname");
			if($valida) {
				$arquivo = UPLOADS.$valor;
				if (file_exists($arquivo))
				{
					$dom = new DOMDocument('1.0');
					$dom_sxe = $dom->load($arquivo);
					if (!$dom_sxe) {
						print Filter::msgAlert(lang('MSG_ERRO_XML'));
						exit;
					}
					$isProduto = $dom->getElementsByTagName("infNFe");
					$isTransporte = $dom->getElementsByTagName("infCte");
					$isServico = $dom->getElementsByTagName("InfNfse");
					$isServico2 = $dom->getElementsByTagName("Nfse");

					if($isServico->length > 0) {
						foreach($dom->getElementsByTagName("InfNfse") as $compnfse)
						{
							$array_nodes = array();
							$childNodes = $compnfse->childNodes;
							lerNodes($childNodes, $array_nodes);

							$cnpj_empresa = (array_key_exists('TomadorServico_IdentificacaoTomador_CpfCnpj_Cnpj',$array_nodes)) ? $array_nodes['TomadorServico_IdentificacaoTomador_CpfCnpj_Cnpj'] : 0;
							$cnpj_empresa = (array_key_exists('TomadorServico_IdentificacaoTomador_CpfCnpj_Cpf',$array_nodes)) ? $array_nodes['TomadorServico_IdentificacaoTomador_CpfCnpj_Cpf'] : $cnpj_empresa;
							$cnpj_empresa = (array_key_exists('Tomador_IdentificacaoTomador_CpfCnpj_Cnpj',$array_nodes)) ? $array_nodes['Tomador_IdentificacaoTomador_CpfCnpj_Cnpj'] : $cnpj_empresa;
							$cnpj_empresa = (array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Tomador_IdentificacaoTomador_CpfCnpj_Cnpj',$array_nodes)) ? $array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Tomador_IdentificacaoTomador_CpfCnpj_Cnpj'] : $cnpj_empresa;
							$cnpj_empresa = (array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Tomador_IdentificacaoTomador_CpfCnpj_Cpf',$array_nodes)) ? $array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Tomador_IdentificacaoTomador_CpfCnpj_Cpf'] : $cnpj_empresa;
							$cnpj_empresa = limparCPF_CNPJ($cnpj_empresa);
							$id_empresa = checkEmpresa($cnpj_empresa);
							$complemento = (array_key_exists('PrestadorServico_Endereco_Complemento',$array_nodes)) ? $array_nodes['PrestadorServico_Endereco_Complemento'] : '';
							$inscricao = (array_key_exists('PrestadorServico_IdentificacaoPrestador_InscricaoEstadual',$array_nodes)) ? $array_nodes['PrestadorServico_IdentificacaoPrestador_InscricaoEstadual'] : '';
							$email = (array_key_exists('PrestadorServico_Contato_Email',$array_nodes)) ? $array_nodes['PrestadorServico_Contato_Email'] : '';
							$tipo = 0;
							$cpf_cnpj = 0;
							if(array_key_exists('PrestadorServico_IdentificacaoPrestador_Cnpj',$array_nodes)) {
								$cpf_cnpj = limparCPF_CNPJ($array_nodes['PrestadorServico_IdentificacaoPrestador_Cnpj']);
								$tipo = 1;
							} elseif(array_key_exists('PrestadorServico_IdentificacaoPrestador_Cpf',$array_nodes)) {
								$cpf_cnpj = limparCPF_CNPJ($array_nodes['PrestadorServico_IdentificacaoPrestador_Cpf']);
								$tipo = 2;
							} elseif(array_key_exists('PrestadorServico_IdentificacaoPrestador_CpfCnpj_Cnpj',$array_nodes)) {
								$cpf_cnpj = limparCPF_CNPJ($array_nodes['PrestadorServico_IdentificacaoPrestador_CpfCnpj_Cnpj']);
								$tipo = 1;
							}

							$empresa_cnpj = getValue('cnpj', 'empresa', "'inativo'=0");

							if($cnpj_empresa != $empresa_cnpj){
								$mensagemCNPJInvalido = str_replace("[CNPJ_EMPRESA]",$empresa_cnpj,lang('MSG_ERRO_CNPJ_NOTA_INVALIDO'));
                                Filter::msgError($mensagemCNPJInvalido,"index.php?do=xml&acao=entradas");
							} elseif($cpf_cnpj) {
								$razao_social = cleanSanitize($array_nodes['PrestadorServico_RazaoSocial']);
								$data = array(
									'id_empresa' => $id_empresa,
									'razao_social' => html_entity_decode($razao_social, ENT_QUOTES, 'UTF-8'),
									'tipo' => $tipo,
									'cpf_cnpj' => $cpf_cnpj ,
									'email' => $email,
									'cep' => sanitize($array_nodes['PrestadorServico_Endereco_Cep']),
									'endereco' => cleanSanitize($array_nodes['PrestadorServico_Endereco_Endereco']),
									'numero' => sanitize($array_nodes['PrestadorServico_Endereco_Numero']),
									'complemento' => cleanSanitize($complemento),
									'bairro' => cleanSanitize($array_nodes['PrestadorServico_Endereco_Bairro']),
									'estado' => cleanSanitize($array_nodes['PrestadorServico_Endereco_Uf']),
									'ie' => $inscricao,
									'fornecedor' => 1,
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								$id_cadastro = checkRegistro('cpf_cnpj', 'cadastro', $cpf_cnpj);
								if($id_cadastro) {
									self::$db->update("cadastro", $data, "id=".$id_cadastro);
								} else {
									$nome = sanitize($array_nodes['PrestadorServico_RazaoSocial']);
									$data['nome'] = html_entity_decode($nome, ENT_QUOTES, 'UTF-8');
									$id_cadastro = self::$db->insert("cadastro", $data);
								}
								$numero_nota = sanitize($array_nodes['Numero']);
								$chaveacesso = sanitize($array_nodes['CodigoVerificacao']);
								$isValido = checkCondicao('nota_fiscal', 'operacao=1 and numero_nota ="'.$numero_nota.'" and id_cadastro='.$id_cadastro);
								if(!$isValido and $id_empresa) {
									$contar++;
									$data_emissao = substr(sanitize($array_nodes['DataEmissao']), 0,10);
									$valor_servicos = (array_key_exists('Servico_Valores_ValorServicos',$array_nodes)) ? $array_nodes['Servico_Valores_ValorServicos'] : $array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Valores_ValorServicos'];
									$valor_nota = (array_key_exists('Servico_Valores_ValorLiquidoNfse',$array_nodes)) ? $array_nodes['Servico_Valores_ValorLiquidoNfse'] : $array_nodes['ValoresNfse_ValorLiquidoNfse'];
									$valor_iss_retido = (array_key_exists('Servico_Valores_ValorIssRetido',$array_nodes)) ? $array_nodes['Servico_Valores_ValorIssRetido'] : 0;
									$valor_desconto = (array_key_exists('Servico_Valores_DescontoCondicionado',$array_nodes)) ? $array_nodes['Servico_Valores_DescontoCondicionado'] : $array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Valores_DescontoCondicionado'];
									$valor_cofins = (array_key_exists('Servico_Valores_ValorCofins',$array_nodes)) ? $array_nodes['Servico_Valores_ValorCofins'] : $array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Valores_ValorCofins'];
									$valor_iss = (array_key_exists('Servico_Valores_ValorIss',$array_nodes)) ? $array_nodes['Servico_Valores_ValorIss'] : $array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Valores_ValorIss'];
									$descriminacao = (array_key_exists('Servico_Discriminacao',$array_nodes)) ? sanitize($array_nodes['Servico_Discriminacao']) : sanitize($array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Discriminacao']);
									$iss_retido = (array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_IssRetido',$array_nodes)) ? $array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_IssRetido'] : 0;
									if($iss_retido == 2) {
										$valor_iss_retido = (array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Valores_ValorIss',$array_nodes)) ? $array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Valores_ValorIss'] : 0;
									}
									$datanota = array(
										'id_empresa' => $id_empresa,
										'numero_nota' => $numero_nota,
										'numero' => $numero_nota,
										'id_cadastro' => $id_cadastro,
										'cpf_cnpj' => $cpf_cnpj ,
										'data_emissao' => $data_emissao,
										'data_entrada' => "NOW()",
										'chaveacesso' => $chaveacesso,
										'descriminacao' => $descriminacao,
										'valor_desconto' => $valor_desconto,
										'valor_cofins' => $valor_cofins,
										'valor_nota' => $valor_nota,
										'nome_arquivo' => $valor,
										'modelo' => 1,
										'operacao' => 1,
										'usuario' => session('nomeusuario'),
										'data' => "NOW()"
									);
									$id_nota = self::$db->insert("nota_fiscal", $datanota);

									$descricao = 'DESPESA AUTOMATICA DE NOTA FISCAL DE SERVICO - '.$numero_nota;

									$data_despesa = array(
										'id_empresa' => $id_empresa,
										'id_nota' => $id_nota,
										'id_cadastro' => $id_cadastro,
										'id_conta' => 30,
										'descricao' => $descricao,
										'nro_documento' => $numero_nota,
										'valor' => $valor_nota,
										'valor_pago' => $valor_nota,
										'data_vencimento' => $data_emissao,
										'pago' => '2',
										'fiscal' => '1',
										'usuario' => session('nomeusuario'),
										'data' => "NOW()"
									);
									$id_despesa = self::$db->insert("despesa", $data_despesa);
									$data_update = array(
										'agrupar' => $id_despesa
									);
									self::$db->update("despesa", $data_update, "id=".$id_despesa);
								}
							}
						}
						if($isValido)
						{
							Filter::msgAlert(lang('MSG_ERRO_NOTA_CADASTRADA'),"index.php?do=xml&acao=entradas");
						} elseif(!$id_empresa) {
							Filter::msgError(lang('MSG_ERRO_MATRIZ_FILIAL'),"index.php?do=xml&acao=entradas");
						}
					} elseif($isServico2->length > 0) {
						foreach($dom->getElementsByTagName("Nfse") as $compnfse)
						{
							$array_nodes = array();
							$childNodes = $compnfse->childNodes;
							lerNodes($childNodes, $array_nodes);
							$cnpj_empresa = (array_key_exists('DadosTomador_IdentificacaoTomador_CpfCnpj',$array_nodes)) ? $array_nodes['DadosTomador_IdentificacaoTomador_CpfCnpj'] : '';
							$cnpj_empresa = limparCPF_CNPJ($cnpj_empresa);
							$id_empresa = checkEmpresa($cnpj_empresa);
							$inscricao = (array_key_exists('DadosPrestador_IdentificacaoPrestador_InscricaoEstadual',$array_nodes)) ? $array_nodes['DadosPrestador_IdentificacaoPrestador_InscricaoEstadual'] : '';
							$email = (array_key_exists('DadosPrestador_Contato_Email',$array_nodes)) ? $array_nodes['DadosPrestador_Contato_Email'] : '';
							$tipo = 0;
							$cpf_cnpj = 0;

							if (array_key_exists('DadosPrestador_IdentificacaoPrestador_CpfCnpj',$array_nodes)){
								$cpf_cnpj = limparCPF_CNPJ($array_nodes['DadosPrestador_IdentificacaoPrestador_CpfCnpj']);
							}
							if (array_key_exists('DadosPrestador_IdentificacaoPrestador_IndicacaoCpfCnpj',$array_nodes)){
								$tipo = $array_nodes['DadosPrestador_IdentificacaoPrestador_IndicacaoCpfCnpj'];
							}

							$razao_social = (array_key_exists('DadosPrestador_RazaoSocial',$array_nodes)) ? $array_nodes['DadosPrestador_RazaoSocial'] : '';
							$complemento = (array_key_exists('DadosPrestador_Endereco_LogradouroComplemento',$array_nodes)) ? $array_nodes['DadosPrestador_Endereco_LogradouroComplemento'] : '';
							$cep = (array_key_exists('DadosPrestador_Endereco_Cep',$array_nodes)) ? $array_nodes['DadosPrestador_Endereco_Cep'] : '';
							$endereco = (array_key_exists('DadosPrestador_Endereco_Logradouro',$array_nodes)) ? $array_nodes['DadosPrestador_Endereco_Logradouro'] : '';
							$numero = (array_key_exists('DadosPrestador_Endereco_LogradouroNumero',$array_nodes)) ? $array_nodes['DadosPrestador_Endereco_LogradouroNumero'] : '';
							$bairro = (array_key_exists('DadosPrestador_Endereco_Bairro',$array_nodes)) ? $array_nodes['DadosPrestador_Endereco_Bairro'] : '';
							$estado = (array_key_exists('DadosPrestador_Endereco_Uf',$array_nodes)) ? $array_nodes['DadosPrestador_Endereco_Uf'] : '';

							$empresa_cnpj = getValue('cnpj', 'empresa', "'inativo'=0");

							if($cnpj_empresa != $empresa_cnpj){
								$mensagemCNPJInvalido = str_replace("[CNPJ_EMPRESA]",$empresa_cnpj,lang('MSG_ERRO_CNPJ_NOTA_INVALIDO'));
                                Filter::msgError($mensagemCNPJInvalido,"index.php?do=xml&acao=entradas");
							} elseif($cpf_cnpj) {
								$razao_social = cleanSanitize($razao_social);
								$data = array(
									'id_empresa' => $id_empresa,
									'razao_social' => html_entity_decode($razao_social, ENT_QUOTES, 'UTF-8'),
									'tipo' => $tipo,
									'cpf_cnpj' => $cpf_cnpj ,
									'email' => $email,
									'cep' => sanitize($cep),
									'endereco' => cleanSanitize($endereco),
									'numero' => sanitize($numero),
									'complemento' => cleanSanitize($complemento),
									'bairro' => cleanSanitize($bairro),
									'estado' => cleanSanitize($estado),
									'ie' => $inscricao,
									'fornecedor' => 1,
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);

								$id_cadastro = checkRegistro('cpf_cnpj', 'cadastro', $cpf_cnpj);
								if($id_cadastro) {
									self::$db->update("cadastro", $data, "id=".$id_cadastro);
								} else {
									$nome = cleanSanitize($array_nodes['DadosPrestador_RazaoSocial']);
									$data['nome'] = html_entity_decode($nome, ENT_QUOTES, 'UTF-8');
									$id_cadastro = self::$db->insert("cadastro", $data);
								}
								$numero_nota = sanitize($array_nodes['IdentificacaoNfse_Numero']);
								$chaveacesso = sanitize($array_nodes['IdentificacaoNfse_CodigoVerificacao']);
								$isValido = checkCondicao('nota_fiscal', 'operacao=1 and numero_nota ="'.$numero_nota.'" and id_cadastro='.$id_cadastro);
								if(!$isValido and $id_empresa) {
									$contar++;
									$data_emissao = substr(sanitize($array_nodes['DataEmissao']), 0,10);

									$valor_servicos = '';
									if (array_key_exists('Servico_Valores_ValorServicos',$array_nodes)){
										$valor_servicos = $array_nodes['Servico_Valores_ValorServicos'];
									} elseif (array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Valores_ValorServicos',$array_nodes)){
										$valor_servicos = $array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Valores_ValorServicos'];
									} elseif (array_key_exists('Servicos_ValorServico',$array_nodes)){
										$valor_servicos = $array_nodes['Servicos_ValorServico'];
									} elseif (array_key_exists('Valores_ValorServicos',$array_nodes)){
										$valor_servicos = $array_nodes['Valores_ValorServicos'];
									}

									$valor_nota = 0;
									if (array_key_exists('Servico_Valores_ValorLiquidoNfse',$array_nodes)){
										$valor_nota = $array_nodes['Servico_Valores_ValorLiquidoNfse'];
									} elseif (array_key_exists('ValoresNfse_ValorLiquidoNfse',$array_nodes)){
										$valor_nota = $array_nodes['ValoresNfse_ValorLiquidoNfse'];
									} elseif (array_key_exists('Servicos_ValorServico',$array_nodes)){
										$valor_nota = $array_nodes['Servicos_ValorServico'];
									}

									$valor_iss_retido = (array_key_exists('Servico_Valores_ValorIssRetido',$array_nodes)) ? $array_nodes['Servico_Valores_ValorIssRetido'] : 0;

									if (array_key_exists('Servico_Valores_DescontoCondicionado',$array_nodes)){
										$valor_desconto = $array_nodes['Servico_Valores_DescontoCondicionado'];
									}elseif (array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Valores_DescontoCondicionado',$array_nodes)){
										$valor_desconto = $array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Valores_DescontoCondicionado'];
									}elseif (array_key_exists('Valores_OutrosDescontos',$array_nodes)){
										$valor_desconto = $array_nodes['Valores_OutrosDescontos'];
									}

									if (array_key_exists('Servico_Valores_ValorCofins',$array_nodes)){
										$valor_cofins = $array_nodes['Servico_Valores_ValorCofins'];
									}elseif (array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Valores_ValorCofins',$array_nodes)){
										$valor_cofins = $array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Valores_ValorCofins'];
									}elseif (array_key_exists('Valores_ValorCofins',$array_nodes)){
										$valor_cofins = $array_nodes['Valores_ValorCofins'];
									}

									if (array_key_exists('Servico_Valores_ValorIss',$array_nodes)){
										$valor_iss = $array_nodes['Servico_Valores_ValorIss'];
									}elseif (array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Valores_ValorIss',$array_nodes)){
										$valor_iss = $array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Valores_ValorIss'];
									}elseif (array_key_exists('Valores_ValorIss',$array_nodes)){
										$valor_iss = $array_nodes['Valores_ValorIss'];
									}

									if (array_key_exists('Servico_Discriminacao',$array_nodes)){
										$descriminacao = sanitize($array_nodes['Servico_Discriminacao']);
									}elseif (array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Discriminacao',$array_nodes)){
										$descriminacao = sanitize($array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Discriminacao']);
									}elseif (array_key_exists('Servicos_Descricao',$array_nodes)){
										$descriminacao = sanitize($array_nodes['Servicos_Descricao']);
									}

									$iss_retido = 0;
									if (array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_IssRetido',$array_nodes)){
										$iss_retido = $array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_IssRetido'];
									}elseif (array_key_exists('IssRetido',$array_nodes)){
										$iss_retido = $array_nodes['IssRetido'];
									}

									if($iss_retido == 2) {
										$valor_iss_retido = (array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Valores_ValorIss',$array_nodes)) ? $array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Valores_ValorIss'] : 0;
									}

									$inf_adicionais = (array_key_exists('Observacao',$array_nodes)) ? $array_nodes['Observacao'] : '';

									$datanota = array(
										'id_empresa' => $id_empresa,
										'numero_nota' => $numero_nota,
										'numero' => $numero_nota,
										'id_cadastro' => $id_cadastro,
										'cpf_cnpj' => $cpf_cnpj ,
										'data_emissao' => $data_emissao,
										'data_entrada' => "NOW()",
										'chaveacesso' => $chaveacesso,
										'descriminacao' => $descriminacao,
										'valor_desconto' => $valor_desconto,
										'valor_cofins' => $valor_cofins,
										'valor_nota' => $valor_nota,
										'nome_arquivo' => $valor,
										'modelo' => 1,
										'operacao' => 1,
										'usuario' => session('nomeusuario'),
										'data' => "NOW()"
									);
									$id_nota = self::$db->insert("nota_fiscal", $datanota);

									$descricao = 'DESPESA AUTOMATICA DE NOTA FISCAL DE SERVICO - '.$numero_nota;

									$data_despesa = array(
										'id_empresa' => $id_empresa,
										'id_nota' => $id_nota,
										'id_cadastro' => $id_cadastro,
										'id_conta' => 30,
										'descricao' => $descricao,
										'nro_documento' => $numero_nota,
										'valor' => $valor_nota,
										'valor_pago' => $valor_nota,
										'data_vencimento' => $data_emissao,
										'pago' => '2',
										'fiscal' => '1',
										'usuario' => session('nomeusuario'),
										'data' => "NOW()"
									);
									$id_despesa = self::$db->insert("despesa", $data_despesa);
									$data_update = array(
										'agrupar' => $id_despesa
									);
									self::$db->update("despesa", $data_update, "id=".$id_despesa);
								}
							}
						}
						if($isValido)
						{
							Filter::msgAlert(lang('MSG_ERRO_NOTA_CADASTRADA'),"index.php?do=xml&acao=entradas");
						} elseif(!$id_empresa) {
							Filter::msgError(lang('MSG_ERRO_MATRIZ_FILIAL'),"index.php?do=xml&acao=entradas");
						}
					} elseif($isTransporte->length > 0) {
						foreach($dom->getElementsByTagName("infCte") as $compnfse)
						{
							$chaveacesso = $compnfse->getAttribute('Id');
							$chaveacesso = str_replace("CTe","",$chaveacesso);
							$array_nodes = array();
							$childNodes = $compnfse->childNodes;
							lerNodes($childNodes, $array_nodes);
							$cnpj_empresa = limparCPF_CNPJ($array_nodes['dest_CNPJ']);
							$id_empresa = checkEmpresa($cnpj_empresa);
							if(!$id_empresa) {
								$cnpj_empresa = limparCPF_CNPJ($array_nodes['rem_CNPJ']);
								$id_empresa = checkEmpresa($cnpj_empresa);
							}
							$tipo = 0;
							$cpf_cnpj = 0;
							if(array_key_exists('emit_CNPJ',$array_nodes)) {
								$cpf_cnpj = limparCPF_CNPJ($array_nodes['emit_CNPJ']);
								$tipo = 1;
							} else {
								$cpf_cnpj = limparCPF_CNPJ($array_nodes['emit_CPF']);
								$tipo = 2;
							}

							$empresa_cnpj = getValue('cnpj', 'empresa', "'inativo'=0");

							if($cnpj_empresa != $empresa_cnpj){
								$mensagemCNPJInvalido = str_replace("[CNPJ_EMPRESA]",$empresa_cnpj,lang('MSG_ERRO_CNPJ_NOTA_INVALIDO'));
                                Filter::msgError($mensagemCNPJInvalido,"index.php?do=xml&acao=entradas");
							} else {
								$inscricao = (array_key_exists('emit_IE',$array_nodes)) ? $array_nodes['emit_IE'] : '';
								$razao_social = cleanSanitize($array_nodes['emit_xNome']);
								$data = array(
									'id_empresa' => $id_empresa,
									'razao_social' => html_entity_decode($razao_social, ENT_QUOTES, 'UTF-8'),
									'tipo' => $tipo,
									'cpf_cnpj' => $cpf_cnpj ,
									'cep' => sanitize($array_nodes['emit_enderEmit_CEP']),
									'endereco' => cleanSanitize($array_nodes['emit_enderEmit_xLgr']),
									'numero' => sanitize($array_nodes['emit_enderEmit_nro']),
									'bairro' => cleanSanitize($array_nodes['emit_enderEmit_xBairro']),
									'cidade' => cleanSanitize($array_nodes['emit_enderEmit_xMun']),
									'estado' => cleanSanitize($array_nodes['emit_enderEmit_UF']),
									'ie' => $inscricao,
									'fornecedor' => 1,
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								$id_cadastro = checkRegistro('cpf_cnpj', 'cadastro', $cpf_cnpj);
								if($id_cadastro) {
									self::$db->update("cadastro", $data, "id=".$id_cadastro);
								} else {
									$nome = cleanSanitize($array_nodes['emit_xNome']);
									$data['nome'] = html_entity_decode($nome, ENT_QUOTES, 'UTF-8');
									$id_cadastro = self::$db->insert("cadastro", $data);
								}
								$numero_nota = sanitize($array_nodes['ide_nCT']);
								$isValido = checkCondicao('nota_fiscal', 'operacao=4 and numero_nota ="'.$numero_nota.'" and id_cadastro='.$id_cadastro);
								if(!$isValido and $id_empresa) {
									$contar++;
									$data_emissao = date('Y-m-d');
									if(array_key_exists('ide_dhEmi',$array_nodes)) {
										$data_emissao = $array_nodes['ide_dhEmi'];
									} elseif(array_key_exists('ide_dEmi',$array_nodes)) {
										$data_emissao = $array_nodes['ide_dEmi'];
									}
									$data_emissao = substr($data_emissao, 0,10);
									$valor_nota = sanitize($array_nodes['vPrest_vTPrest']);
									$inf_adicionais = (array_key_exists('compl_xObs',$array_nodes)) ? $array_nodes['compl_xObs'] : '';
									$datanota = array(
										'id_empresa' => $id_empresa,
										'numero_nota' => $numero_nota,
										'numero' => $numero_nota,
										'id_cadastro' => $id_cadastro,
										'cpf_cnpj' => $cpf_cnpj ,
										'data_emissao' => $data_emissao,
										'data_entrada' => "NOW()",
										'chaveacesso' => $chaveacesso,
										'descriminacao' => sanitize($array_nodes['ide_natOp']),
										'valor_nota' => $valor_nota,
										'inf_adicionais' => sanitize($inf_adicionais),
										'nome_arquivo' => $valor,
										'modelo' => 4,
										'operacao' => 1,
										'usuario' => session('nomeusuario'),
										'data' => "NOW()"
									);
									$id_nota = self::$db->insert("nota_fiscal", $datanota);

									$descricao = lang('NOTA_TRANSPORTE_DESPESA_AUT').$numero_nota;

									$data_despesa = array(
										'id_empresa' => $id_empresa,
										'id_nota' => $id_nota,
										'id_cadastro' => $id_cadastro,
										'id_conta' => 30,
										'descricao' => $descricao,
										'nro_documento' => $numero_nota,
										'valor' => $valor_nota,
										'valor_pago' => $valor_nota,
										'data_vencimento' => $data_emissao,
										'pago' => '2',
										'fiscal' => '1',
										'usuario' => session('nomeusuario'),
										'data' => "NOW()"
									);
									$id_despesa = self::$db->insert("despesa", $data_despesa);
								}
							}
						}
						if($isValido)
						{
							Filter::msgAlert(lang('MSG_ERRO_NOTA_CADASTRADA'),"index.php?do=xml&acao=entradas");
						} elseif(!$id_empresa) {
							Filter::msgError(lang('MSG_ERRO_MATRIZ_FILIAL'),"index.php?do=xml&acao=entradas");
						}
					} elseif($isProduto->length > 0) {
						foreach($dom->getElementsByTagName("infNFe") as $compnfse)
						{
							$chaveacesso = $compnfse->getAttribute('Id');
							$chaveacesso = str_replace("NFe","",$chaveacesso);
							$array_nodes = array();
							$childNodes = $compnfse->childNodes;
							lerNodes($childNodes, $array_nodes);
							$cnpj_empresa = (array_key_exists('dest_CNPJ',$array_nodes)) ? $array_nodes['dest_CNPJ'] : $array_nodes['dest_CPF'];
							$cnpj_empresa = limparCPF_CNPJ($cnpj_empresa);
							$id_empresa = checkEmpresa($cnpj_empresa);
							$empresa_cnpj = getValue('cnpj', 'empresa', "'inativo'=0");

							if($cnpj_empresa != $empresa_cnpj){
								$mensagemCNPJInvalido = str_replace("[CNPJ_EMPRESA]",$empresa_cnpj,lang('MSG_ERRO_CNPJ_NOTA_INVALIDO'));
                                Filter::msgError($mensagemCNPJInvalido,"index.php?do=xml&acao=entradas");
							} else {
								if(!$id_empresa) {
									$fone = (array_key_exists('dest_enderDest_fone',$array_nodes)) ? $array_nodes['dest_enderDest_fone'] : '';
									$email = (array_key_exists('dest_email',$array_nodes)) ? $array_nodes['dest_email'] : '';
									$inscricao = (array_key_exists('dest_IE',$array_nodes)) ? $array_nodes['dest_IE'] : '';
									$data_empresa = array(
										'nome' => cleanSanitize($array_nodes['dest_xNome']),
										'cnpj' => $cnpj_empresa ,
										'cep' => sanitize($array_nodes['dest_enderDest_CEP']),
										'endereco' => cleanSanitize($array_nodes['dest_enderDest_xLgr']),
										'numero' => cleanSanitize($array_nodes['dest_enderDest_nro']),
										'bairro' => cleanSanitize($array_nodes['dest_enderDest_xBairro']),
										'cidade' => cleanSanitize($array_nodes['dest_enderDest_xMun']),
										'estado' => cleanSanitize($array_nodes['dest_enderDest_UF']),
										'telefone' => $fone,
										'email' => $email,
										'inativo' => 1,
										'usuario' => session('nomeusuario'),
										'data' => "NOW()"
									);
									$id_empresa = self::$db->insert("empresa", $data_empresa);
								}
								$tipo = 0;
								$cpf_cnpj = 0;
								if(array_key_exists('emit_CNPJ',$array_nodes)) {
									$cpf_cnpj = limparCPF_CNPJ($array_nodes['emit_CNPJ']);
									$tipo = 1;
								} else {
									$cpf_cnpj = limparCPF_CNPJ($array_nodes['emit_CPF']);
									$tipo = 2;
								}
								$nome_cadastro = (array_key_exists('emit_xNome',$array_nodes)) ? cleanSanitize($array_nodes['emit_xNome']) : '';
								$fone = (array_key_exists('emit_enderEmit_fone',$array_nodes)) ? $array_nodes['emit_enderEmit_fone'] : '';
								$email = (array_key_exists('emit_email',$array_nodes)) ? $array_nodes['emit_email'] : '';
								$inscricao = (array_key_exists('emit_IE',$array_nodes)) ? $array_nodes['emit_IE'] : '';
								$inscricao_municipal = (array_key_exists('emit_IM',$array_nodes)) ? $array_nodes['emit_IM'] : '';
								$data = array(
									'id_empresa' => $id_empresa,
									'nome' => html_entity_decode($nome_cadastro, ENT_QUOTES, 'UTF-8'),
									'razao_social' => html_entity_decode($nome_cadastro, ENT_QUOTES, 'UTF-8'),
									'tipo' => $tipo,
									'cpf_cnpj' => $cpf_cnpj ,
									'cep' => sanitize($array_nodes['emit_enderEmit_CEP']),
									'endereco' => cleanSanitize($array_nodes['emit_enderEmit_xLgr']),
									'numero' => sanitize($array_nodes['emit_enderEmit_nro']),
									'bairro' => cleanSanitize($array_nodes['emit_enderEmit_xBairro']),
									'cidade' => cleanSanitize($array_nodes['emit_enderEmit_xMun']),
									'estado' => cleanSanitize($array_nodes['emit_enderEmit_UF']),
									'ie' => $inscricao,
									'im' => $inscricao_municipal,
									'telefone' => $fone,
									'email' => $email,
									'fornecedor' => 1,
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								$id_cadastro = checkRegistro('cpf_cnpj', 'cadastro', $cpf_cnpj);
								if($id_cadastro) {
									self::$db->update("cadastro", $data, "id=".$id_cadastro);
								} else {
									$nome = cleanSanitize($array_nodes['emit_xNome']);
									$data['nome'] = html_entity_decode($nome, ENT_QUOTES, 'UTF-8');
									$id_cadastro = self::$db->insert("cadastro", $data);
								}
								$numero_nota = sanitize($array_nodes['ide_nNF']);
								$serie_nota = sanitize($array_nodes['ide_serie']);
								$isValido = checkCondicao('nota_fiscal', 'operacao=1 and numero_nota ="'.$numero_nota."-".$serie_nota.'" and id_cadastro='.$id_cadastro);
								if(!$isValido and $id_empresa) {
									$contar++;
									$data_emissao = date('Y-m-d');
									if(array_key_exists('ide_dhEmi',$array_nodes)) {
										$data_emissao = $array_nodes['ide_dhEmi'];
									} elseif(array_key_exists('ide_dEmi',$array_nodes)) {
										$data_emissao = $array_nodes['ide_dEmi'];
									}
									$data_emissao = substr($data_emissao, 0,10);
									$valor_base = (array_key_exists('total_ICMSTot_vBC',$array_nodes)) ? $array_nodes['total_ICMSTot_vBC'] : 0;
									$valor_icms = (array_key_exists('total_ICMSTot_vICMS',$array_nodes)) ? $array_nodes['total_ICMSTot_vICMS'] : 0;
									$valor_base_st = (array_key_exists('total_ICMSTot_vBCST',$array_nodes)) ? $array_nodes['total_ICMSTot_vBCST'] : 0;
									$valor_st = (array_key_exists('total_ICMSTot_vST',$array_nodes)) ? $array_nodes['total_ICMSTot_vST'] : 0;
									$valor_produto = (array_key_exists('total_ICMSTot_vProd',$array_nodes)) ? $array_nodes['total_ICMSTot_vProd'] : 0;
									$valor_frete = (array_key_exists('total_ICMSTot_vFrete',$array_nodes)) ? $array_nodes['total_ICMSTot_vFrete'] : 0;
									$valor_seguro = (array_key_exists('total_ICMSTot_vSeg',$array_nodes)) ? $array_nodes['total_ICMSTot_vSeg'] : 0;
									$valor_desconto = (array_key_exists('total_ICMSTot_vDesc',$array_nodes)) ? $array_nodes['total_ICMSTot_vDesc'] : 0;
									$valor_ipi = (array_key_exists('total_ICMSTot_vIPI',$array_nodes)) ? $array_nodes['total_ICMSTot_vIPI'] : 0;
									$valor_pis = (array_key_exists('total_ICMSTot_vPIS',$array_nodes)) ? $array_nodes['total_ICMSTot_vPIS'] : 0;
									$valor_cofins = (array_key_exists('total_ICMSTot_vCOFINS',$array_nodes)) ? $array_nodes['total_ICMSTot_vCOFINS'] : 0;
									$valor_outro = (array_key_exists('total_ICMSTot_vOutro',$array_nodes)) ? $array_nodes['total_ICMSTot_vOutro'] : 0;
									$valor_nota = sanitize($array_nodes['total_ICMSTot_vNF']);
									$valor_total_trib = (array_key_exists('total_ICMSTot_vTotTrib',$array_nodes)) ? $array_nodes['total_ICMSTot_vTotTrib'] : 0;
									$inf_adicionais = (array_key_exists('infAdic_infCpl',$array_nodes)) ? $array_nodes['infAdic_infCpl'] : '';

									$duplicatas = '';
									foreach($dom->getElementsByTagName("dup") as $dup)
									{
										$array_dupnodes = array();
										$dupchildNodes = $dup->childNodes;
										lerNodes($dupchildNodes, $array_dupnodes);
										$duplicatas .= "(".$array_dupnodes['nDup'].", ".$array_dupnodes['dVenc'].", ".$array_dupnodes['vDup'].")";
									}

									$datanota = array(
										'id_empresa' => $id_empresa,
										'numero_nota' => $numero_nota.'-'.$serie_nota,
										'numero' => $numero_nota,
										'serie' => $serie_nota,
										'id_cadastro' => $id_cadastro,
										'cpf_cnpj' => $cpf_cnpj ,
										'data_emissao' => $data_emissao,
										'data_entrada' => "NOW()",
										'chaveacesso' => $chaveacesso,
										'descriminacao' => sanitize($array_nodes['ide_natOp']),
										'duplicatas' => $duplicatas,
										'valor_base_icms' => $valor_base,
										'valor_icms' => $valor_icms,
										'valor_base_st' => $valor_base_st,
										'valor_st' => $valor_st,
										'valor_produto' => $valor_produto,
										'valor_frete' => $valor_frete,
										'valor_seguro' => $valor_seguro,
										'valor_desconto' => $valor_desconto,
										'valor_ipi' => $valor_ipi,
										'valor_pis' => $valor_pis,
										'valor_cofins' => $valor_cofins,
										'valor_outro' => $valor_outro,
										'valor_nota' => $valor_nota,
										'valor_total_trib' => $valor_total_trib,
										'inf_adicionais' => sanitize($inf_adicionais),
										'nome_arquivo' => $valor,
										'modelo' => 2,
										'operacao' => 1,
										'usuario' => session('nomeusuario'),
										'data' => "NOW()"
									);
									$id_nota = self::$db->insert("nota_fiscal", $datanota);

									foreach($dom->getElementsByTagName("dup") as $dup)
									{
										$array_dupnodes = array();
										$dupchildNodes = $dup->childNodes;
										lerNodes($dupchildNodes, $array_dupnodes);
										$duplicata = $array_dupnodes['nDup'];
										$data_vencimento = $array_dupnodes['dVenc'];
										$valor = $array_dupnodes['vDup'];
										$descricao = 'DUPLICATA ['.$duplicata.'] DA NOTA FISCAL DE PRODUTO - '.$numero_nota;

										$data_despesa = array(
											'id_empresa' => $id_empresa,
											'id_nota' => $id_nota,
											'id_cadastro' => $id_cadastro,
											'id_conta' => 30,
											'duplicata' => $duplicata,
											'descricao' => $descricao,
											'nro_documento' => $numero_nota,
											'valor' => $valor,
											'valor_pago' => $valor,
											'data_vencimento' => $data_vencimento,
											'pago' => '2',
											'fiscal' => '1',
											'usuario' => session('nomeusuario'),
											'data' => "NOW()"
										);
										$id_despesa = self::$db->insert("despesa", $data_despesa);
										$data_update = array(
											'agrupar' => $id_despesa
										);
										self::$db->update("despesa", $data_update, "id=".$id_despesa);
									}

									foreach($dom->getElementsByTagName("det") as $det)
									{
										$atributo = $det->getAttribute('nItem');
										$array_detnodes = array();
										$detchildNodes = $det->childNodes;
										lerNodes($detchildNodes, $array_detnodes);
										$codigonota = sanitize($array_detnodes['prod_cProd']);
										$codigobarras = (array_key_exists('prod_cEAN',$array_detnodes)) ? $array_detnodes['prod_cEAN'] : '';
										$codigobarras = (is_numeric($codigobarras)) ? $codigobarras : '';

										$nome_produto = cleanSanitize($array_detnodes['prod_xProd']);
										if(array_key_exists('infAdProd',$array_detnodes)){
											$nome_produto .= cleanSanitize($array_detnodes['infAdProd']);
										}

										$ncm = (array_key_exists('prod_NCM',$array_detnodes)) ? $array_detnodes['prod_NCM'] : '';
										$cfop = (array_key_exists('prod_CFOP',$array_detnodes)) ? $array_detnodes['prod_CFOP'] : '';
										$cest = (array_key_exists('prod_CEST',$array_detnodes)) ? $array_detnodes['prod_CEST'] : '';
										$unidade = (array_key_exists('prod_uCom',$array_detnodes)) ? $array_detnodes['prod_uCom'] : '';
										$cod_anp = (array_key_exists('prod_comb_cProdANP',$array_detnodes)) ? $array_detnodes['prod_comb_cProdANP'] : '';
										$valor_partida = (array_key_exists('prod_comb_vPart',$array_detnodes)) ? $array_detnodes['prod_comb_vPart'] : '';
										$quantidade = $array_detnodes['prod_qCom'];
										$valor_unitario = (array_key_exists('prod_vUnCom',$array_detnodes)) ? $array_detnodes['prod_vUnCom'] : 0;
										$valor_frete = (array_key_exists('prod_vFrete',$array_detnodes)) ? $array_detnodes['prod_vFrete'] : 0;
										$valor_seguro = (array_key_exists('prod_vSeg',$array_detnodes)) ? $array_detnodes['prod_vSeg'] : 0;
										$valor_desconto = (array_key_exists('prod_vDesc',$array_detnodes)) ? $array_detnodes['prod_vDesc'] : 0;
										$valor_despesa_acessoria = (array_key_exists('prod_vOutro',$array_detnodes)) ? sanitize($array_detnodes['prod_vOutro']) : 0;
										$valor_total = sanitize($array_detnodes['prod_vProd']);
										$valor_total_trib = (array_key_exists('imposto_vTotTrib',$array_detnodes)) ? $array_detnodes['imposto_vTotTrib'] : 0;
										if(array_key_exists('imposto_ICMS_ICMS00_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMS00_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS00_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMS00_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS00_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMS00_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS00_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMS00_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS00_vICMS'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMS00_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS00_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMS00_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS00_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMS00_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS00_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMS00_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS00_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMS00_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS00_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMS00_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS00_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMS00_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS00_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMS00_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS00_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMS10_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMS10_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS10_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMS10_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS10_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMS10_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS10_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMS10_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS10_vICMS'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMS10_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS10_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMS10_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS10_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMS10_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS10_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMS10_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS10_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMS10_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS10_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMS10_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS10_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMS10_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS10_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMS10_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS10_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMS20_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMS20_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS20_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMS20_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS20_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMS20_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS20_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMS20_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS20_vICMS'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMS20_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS20_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMS20_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS20_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMS20_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS20_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMS20_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS20_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMS20_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS20_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMS20_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS20_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMS20_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS20_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMS20_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS20_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMS30_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMS30_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS30_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMS30_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS30_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMS30_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS30_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMS30_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS30_vICMS'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMS30_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS30_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMS30_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS30_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMS30_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS30_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMS30_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS30_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMS30_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS30_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMS30_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS30_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMS30_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS30_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMS30_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS30_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMS40_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMS40_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS40_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMS40_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS40_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMS40_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS40_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMS40_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS40_vICMS'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMS40_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS40_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMS40_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS40_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMS40_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS40_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMS40_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS40_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMS40_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS40_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMS40_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS40_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMS40_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS40_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMS40_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS40_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMS41_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMS41_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS41_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMS41_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS41_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMS41_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS41_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMS41_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS41_vICMS'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMS41_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS41_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMS41_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS41_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMS41_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS41_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMS41_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS41_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMS41_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS41_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMS41_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS41_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMS41_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS41_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMS41_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS41_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMS50_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMS50_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS50_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMS50_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS50_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMS50_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS50_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMS50_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS50_vICMS'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMS50_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS50_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMS50_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS50_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMS50_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS50_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMS50_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS50_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMS50_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS50_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMS50_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS50_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMS50_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS50_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMS50_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS50_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMS51_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMS51_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS51_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMS51_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS51_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMS51_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS51_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMS51_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS51_vICMS'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMS51_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS51_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMS51_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS51_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMS51_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS51_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMS51_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS51_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMS51_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS51_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMS51_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS51_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMS51_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS51_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMS51_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS51_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMS60_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMS60_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS60_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMS60_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS60_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMS60_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS60_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMS60_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS60_vICMS'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMS60_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS60_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMS60_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS60_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMS60_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS60_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMS60_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS60_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMS60_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS60_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMS60_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS60_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMS60_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS60_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMS60_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS60_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMS61_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMS61_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS61_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMS61_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS61_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMS61_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS61_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMS61_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS61_vICMS'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMS61_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS61_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMS61_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS61_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMS61_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS61_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMS61_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS61_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMS61_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS61_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMS61_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS61_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMS61_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS61_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMS61_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS61_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMS70_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMS70_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS70_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMS70_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS70_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMS70_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS70_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMS70_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS70_vICMS'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMS70_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS70_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMS70_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS70_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMS70_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS70_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMS70_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS70_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMS70_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS70_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMS70_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS70_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMS70_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS70_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMS70_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS70_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMS90_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMS90_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS90_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMS90_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS90_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMS90_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS90_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMS90_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS90_vICMS'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMS90_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS90_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMS90_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS90_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMS90_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS90_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMS90_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS90_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMS90_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS90_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMS90_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS90_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMS90_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS90_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMS90_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS90_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMSST_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMSST_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSST_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMSST_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSST_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMSST_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSST_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMSST_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSST_vICMS'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMSST_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSST_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMSST_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSST_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMSST_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSST_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMSST_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSST_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMSST_pST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSST_pST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMSST_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSST_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMSST_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSST_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMSST_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSST_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMSSN101_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN101_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMSSN101_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN101_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN101_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_vICMS'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMSSN101_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMSSN101_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMSSN101_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMSSN101_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMSSN101_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMS101_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS101_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMS101_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS101_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMS101_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS101_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMSSN101_CSOSN',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN101_CSOSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_CSOSN'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN101_pCredSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_pCredSN'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN101_vCredICMSSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_vCredICMSSN'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMSSN101_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMSSN101_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMSSN101_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMSSN101_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMSSN101_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMSSN101_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMSSN101_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMSSN101_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMSSN102_CSOSN',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN102_CSOSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_CSOSN'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN102_pCredSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_pCredSN'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN102_vCredICMSSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_vCredICMSSN'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMSSN102_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMSSN102_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMSSN102_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMSSN102_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMSSN102_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMSSN102_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMSSN102_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMSSN102_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMSSN103_CSOSN',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN103_CSOSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_CSOSN'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN103_pCredSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_pCredSN'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN103_vCredICMSSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_vCredICMSSN'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMSSN103_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMSSN103_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMSSN103_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMSSN103_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMSSN103_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMSSN103_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMSSN103_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMSSN103_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMSSN201_CSOSN',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN201_CSOSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_CSOSN'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN201_pCredSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_pCredSN'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN201_vCredICMSSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_vCredICMSSN'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMSSN201_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMSSN201_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMSSN201_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMSSN201_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMSSN201_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMSSN201_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMSSN201_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMSSN201_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMSSN202_CSOSN',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN202_CSOSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_CSOSN'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN202_pCredSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_pCredSN'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN202_vCredICMSSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_vCredICMSSN'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMSSN202_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMSSN202_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMSSN202_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMSSN202_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMSSN202_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMSSN202_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMSSN202_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMSSN202_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMSSN203_CSOSN',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN203_CSOSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_CSOSN'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN203_pCredSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_pCredSN'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN203_vCredICMSSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_vCredICMSSN'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMSSN203_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMSSN203_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMSSN203_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMSSN203_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMSSN203_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMSSN203_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMSSN203_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMSSN203_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMSSN300_CSOSN',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN300_CSOSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN300_CSOSN'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN300_pCredSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN300_pCredSN'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN300_vCredICMSSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN300_vCredICMSSN'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMSSN300_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN300_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMSSN300_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN300_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMSSN300_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN300_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMSSN300_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN300_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMSSN300_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN300_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMSSN300_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN300_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMSSN300_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN300_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMSSN300_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN300_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMSSN400_CSOSN',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN400_CSOSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_CSOSN'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN400_pCredSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_pCredSN'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN400_vCredICMSSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_vCredICMSSN'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMSSN400_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMSSN400_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMSSN400_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMSSN400_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMSSN400_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMSSN400_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMSSN400_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMSSN400_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMSSN500_CSOSN',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN500_CSOSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_CSOSN'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN500_pCredSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_pCredSN'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN500_vCredICMSSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_vCredICMSSN'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMSSN500_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMSSN500_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMSSN500_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMSSN500_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMSSN500_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMSSN500_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMSSN500_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMSSN500_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMSSN900_CSOSN',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN900_CSOSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_CSOSN'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN900_pCredSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_pCredSN'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN900_vCredICMSSN',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_vCredICMSSN'] : 0;
											$icms_mod_st = (array_key_exists('imposto_ICMS_ICMSSN900_modBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_modBCST'] : 0;
											$icms_percentual_mva_st = (array_key_exists('imposto_ICMS_ICMSSN900_pMVAST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_pMVAST'] : 0;
											$icms_st_base = (array_key_exists('imposto_ICMS_ICMSSN900_vBCST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_vBCST'] : 0;
											$icms_st_valor = (array_key_exists('imposto_ICMS_ICMSSN900_vICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_vICMSST'] : 0;
											$icms_st_percentual = (array_key_exists('imposto_ICMS_ICMSSN900_pICMSST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_pICMSST'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMSSN900_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMSSN900_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMSSN900_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMSSN102_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN102_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMSSN102_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN102_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN102_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_vICMS'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMSSN102_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMSSN102_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMSSN102_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMSSN103_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN103_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMSSN103_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN103_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN103_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_vICMS'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMSSN103_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMSSN103_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMSSN103_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMSSN201_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN201_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMSSN201_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN201_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN201_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_vICMS'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMSSN201_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMSSN201_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMSSN201_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMSSN202_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN202_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMSSN202_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN202_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN202_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_vICMS'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMSSN202_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMSSN202_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMSSN202_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMSSN203_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN203_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMSSN203_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN203_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN203_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_vICMS'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMSSN203_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMSSN203_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMSSN203_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMSSN400_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN400_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMSSN400_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN400_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN400_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_vICMS'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMSSN400_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMSSN400_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMSSN400_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMSSN500_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN500_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMSSN500_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN500_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN500_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_vICMS'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMSSN500_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMSSN500_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMSSN500_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_vBCFCPST'] : 0;

										} elseif(array_key_exists('imposto_ICMS_ICMSSN900_CST',$array_detnodes)) {
											$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN900_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_CST'] : 0;
											$icms_base = (array_key_exists('imposto_ICMS_ICMSSN900_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_vBC'] : 0;
											$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN900_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_pICMS'] : 0;
											$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN900_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_vICMS'] : 0;

											$fcp_percentual = (array_key_exists('imposto_ICMS_ICMSSN900_pFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_pFCPST'] : 0;
											$fcp_valor = (array_key_exists('imposto_ICMS_ICMSSN900_vFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_vFCPST'] : 0;
											$fcp_base = (array_key_exists('imposto_ICMS_ICMSSN900_vBCFCPST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_vBCFCPST'] : 0;

										}
										$pis_cst = (array_key_exists('imposto_PIS_PISAliq_CST',$array_detnodes)) ? $array_detnodes['imposto_PIS_PISAliq_CST'] : 0;
										$pis_base = (array_key_exists('imposto_PIS_PISAliq_vBC',$array_detnodes)) ? $array_detnodes['imposto_PIS_PISAliq_vBC'] : 0;
										$pis_percentual = (array_key_exists('imposto_PIS_PISAliq_pPIS',$array_detnodes)) ? $array_detnodes['imposto_PIS_PISAliq_pPIS'] : 0;
										$pis_valor = (array_key_exists('imposto_PIS_PISAliq_vPIS',$array_detnodes)) ? $array_detnodes['imposto_PIS_PISAliq_vPIS'] : 0;
										$cofins_cst = (array_key_exists('imposto_COFINS_COFINSAliq_CST',$array_detnodes)) ? $array_detnodes['imposto_COFINS_COFINSAliq_CST'] : 0;
										$cofins_base = (array_key_exists('imposto_COFINS_COFINSAliq_vBC',$array_detnodes)) ? $array_detnodes['imposto_COFINS_COFINSAliq_vBC'] : 0;
										$cofins_percentual = (array_key_exists('imposto_COFINS_COFINSAliq_pCOFINS',$array_detnodes)) ? $array_detnodes['imposto_COFINS_COFINSAliq_pCOFINS'] : 0;
										$cofins_valor = (array_key_exists('imposto_COFINS_COFINSAliq_vCOFINS',$array_detnodes)) ? $array_detnodes['imposto_COFINS_COFINSAliq_vCOFINS'] : 0;
										$ipi_cst = (array_key_exists('imposto_IPI_IPITrib_CST',$array_detnodes)) ? $array_detnodes['imposto_IPI_IPITrib_CST'] : 0;
										$ipi_base = (array_key_exists('imposto_IPI_IPITrib_vBC',$array_detnodes)) ? $array_detnodes['imposto_IPI_IPITrib_vBC'] : 0;
										$ipi_percentual = (array_key_exists('imposto_IPI_IPITrib_pIPI',$array_detnodes)) ? $array_detnodes['imposto_IPI_IPITrib_pIPI'] : 0;
										$ipi_valor = (array_key_exists('imposto_IPI_IPITrib_vIPI',$array_detnodes)) ? $array_detnodes['imposto_IPI_IPITrib_vIPI'] : 0;

										$id_produto_fornecedor = getValue('id', 'produto_fornecedor', 'id_cadastro="'.$id_cadastro.'" and codigonota="'.$codigonota. '" and ncm="'.$ncm. '" and nome="'.$nome_produto. '" and codigobarras="'.$codigobarras. '"');
										$valor_unitario_fornecedor = getValue('valor_unitario', 'produto_fornecedor', 'id_cadastro="'.$id_cadastro.'" and codigonota="'.$codigonota. '" and ncm="'.$ncm.'" and nome="'.$nome_produto. '" and codigobarras="'.$codigobarras. '"');
										$id_produto = getValue('id_produto', 'produto_fornecedor', 'codigonota="'.$codigonota. '" and ncm="'.$ncm. '" and nome="'.$nome_produto. '" and codigobarras="'.$codigobarras. '"');
										$quantidade_compra = 1;
										$produto_inativo = ($id_produto > 0) ? getValue("inativo","produto","id=".$id_produto) : 0;

										if($id_produto_fornecedor > 0 && !$produto_inativo){
											$quantidade_compra = getValue('quantidade_compra', 'produto_fornecedor', 'id='.$id_produto_fornecedor);
											$data_produto = array(
												'id_produto' => $id_produto,
												'id_nota' => $id_nota,
												'nome' => $nome_produto,
												'ncm' => $ncm,
												'codigobarras' => $codigobarras,
												'valor_unitario' => $valor_unitario,
												'unidade_compra' => strtoupper($unidade),
												'usuario' => session('nomeusuario'),
												'data' => "NOW()"
											);
											self::$db->update("produto_fornecedor", $data_produto, "id=".$id_produto_fornecedor);
										} else {
											$data_produto = array(
												'id_produto' => ($produto_inativo == 0) ? $id_produto : 0,
												'id_cadastro' => $id_cadastro,
												'id_nota' => $id_nota,
												'nome' => $nome_produto,
												'codigonota' => $codigonota,
												'ncm' => $ncm,
												'codigobarras' => $codigobarras,
												'valor_unitario' => $valor_unitario,
												'quantidade_compra' => 1,
												'unidade_compra' => strtoupper($unidade),
												'usuario' => session('nomeusuario'),
												'data' => "NOW()"
											);
											$id_produto_fornecedor = self::$db->insert("produto_fornecedor", $data_produto);
										}

										$cfop_entrada = getValue('cfop_entrada', 'conversao_cfop', 'cfop_fornecedor = "'.$cfop.'"');
										$valor_total -= $valor_desconto;

										$data_itens = array(
											'id_empresa' => $id_empresa,
											'id_nota' => $id_nota,
											'id_cadastro' => $id_cadastro,
											'id_produto' => ($produto_inativo == 0) ? $id_produto : 0,
											'id_produto_fornecedor' => $id_produto_fornecedor,
											'codigonota' => $codigonota,
											'codigobarras' => $codigobarras,
											'ncm' => $ncm,
											'cest' => $cest,
											'cfop' => $cfop,
											'cfop_entrada' => $cfop_entrada,
											'unidade' => $unidade,
											'quantidade' => $quantidade,
											'valor_unitario' => $valor_unitario,
											'valor_negociado_unitario' => $valor_unitario,
											'valor_desconto' => $valor_desconto,
											'valor_total' => $valor_total,
											'outrasDespesasAcessorias' => $valor_despesa_acessoria,
											'valor_negociado_total' => $valor_total,
											'icms_cst' => $icms_cst,
											'icms_base' => $icms_base,
											'icms_percentual' => $icms_percentual,
											'icms_valor' => $icms_valor,
											'icms_mod_st' => $icms_mod_st,
											'icms_percentual_mva_st' => $icms_percentual_mva_st,
											'icms_st_base' => $icms_st_base,
											'icms_st_valor' => $icms_st_valor,
											'icms_st_percentual' => $icms_st_percentual,
											'valor_frete' => $valor_frete,
											'valor_seguro' => $valor_seguro,
											'pis_cst' => $pis_cst,
											'pis_base' => $pis_base,
											'pis_percentual' => $pis_percentual,
											'pis_valor' => $pis_valor,
											'cofins_cst' => $cofins_cst,
											'cofins_base' => $cofins_base,
											'cofins_percentual' => $cofins_percentual,
											'cofins_valor' => $cofins_valor,
											'ipi_cst' => $ipi_cst,
											'ipi_base' => $ipi_base,
											'ipi_percentual' => $ipi_percentual,
											'ipi_valor' => $ipi_valor,
											'f_pobreza_base' => $fcp_base,
											'f_pobreza_percentual' => $fcp_percentual,
											'f_pobreza_valor' => $fcp_valor,
											'valor_total_trib' => $valor_total_trib,
											'cod_anp' => $cod_anp,
											'valor_partida' => $valor_partida,
											'usuario' => session('nomeusuario'),
											'data' => "NOW()"
										);
										self::$db->insert("nota_fiscal_itens", $data_itens);

										if($id_produto > 0 && !$produto_inativo) {
											$observacao = str_replace("[NUMERO_NOTA]",$numero_nota,lang('NOTA_ENTRADA_NOVO_PRODUTO_AUTOMATICA'));
											$quant_estoque = ($quantidade*$quantidade_compra);
											$data_estoque = array(
												'id_empresa' => $id_empresa,
												'id_produto' => $id_produto,
												'quantidade' => $quant_estoque,
												'tipo' => 1,
												'motivo' => 1,
												'observacao' => $observacao,
												'id_ref' => $id_nota,
												'usuario' => session('nomeusuario'),
												'data' => "NOW()"
											);
											self::$db->insert("produto_estoque", $data_estoque);

											$valor_uni_produto = $valor_unitario - ($valor_desconto / $quantidade)  + ($valor_despesa_acessoria / $quantidade) + ($icms_st_valor / $quantidade) + ($ipi_valor / $quantidade) + ($valor_frete / $quantidade);

											$totalestoque = $this->getEstoqueTotal($id_produto);
											$novo_valor_custo = round(($valor_uni_produto/$quantidade_compra),2);
											$data_update = array(
												'valor_custo' => $novo_valor_custo,
												'estoque' => $totalestoque,
												'usuario' => session('nomeusuario'),
												'data' => "NOW()"
											);
											self::$db->update("produto", $data_update, "id=" . $id_produto);

											$atualizar_valor_produto = getValue('atualizar_valor_produto', 'empresa', "'inativo'=0");
											$sql_produto_tabela = "SELECT * FROM produto_tabela WHERE id_produto = $id_produto";
											$row_produto_tabela = self::$db->fetch_all($sql_produto_tabela);

											if ($row_produto_tabela) {
												foreach ($row_produto_tabela as $rowpt) {
													$valor_atual_venda_produto = getValue("valor_venda","produto_tabela","id=" . $rowpt->id);

													if ($atualizar_valor_produto) {
														$percentual_tabela = getValue("percentual","tabela_precos","id=".$rowpt->id_tabela);
														$percentual_produto = getValue("percentual","produto_tabela","id=" . $rowpt->id);
														$percentual = ($percentual_tabela > 0) ? $percentual_tabela : $percentual_produto;
														$valor_novo_venda_produto = round(($novo_valor_custo + ($novo_valor_custo*($percentual/100))),2);

														if($valor_novo_venda_produto > $valor_atual_venda_produto){
															$data_produto_tabela = array(
																'valor_venda' => $valor_novo_venda_produto,
																'percentual' => $percentual
															);
														}else{
															$percentual_novo = (($valor_atual_venda_produto/$novo_valor_custo)-1)*100;
															$data_produto_tabela = array(
																'valor_venda' => $valor_atual_venda_produto,
																'percentual' => $percentual_novo
															);
														}
														self::$db->update("produto_tabela", $data_produto_tabela, "id=" . $rowpt->id);

														//Verificar se este produto é kit de outro.
														$checkKit = $this->verificaSeProdutoVirouKit($id_produto);
														if ($checkKit) {
															foreach ($checkKit as $key) {
																$id_produto_compoe_kit = $key->id_produto ? $key->id_produto : 0;
																if ($id_produto_compoe_kit) {
																	if ($id_produto == $id_produto_compoe_kit) {
																		$valor_custo_total = round($this->getValorCustoProdutokit($key->id_produto_kit), 2);
																		$valor_custo_atualizado = $valor_custo_total - $key->valor_custo + $novo_valor_custo;

																		$data_produto = array(
																			'valor_custo' => $valor_custo_atualizado,
																			'usuario_alteracao' => session('nomeusuario'),
																			'data_alteracao' => "NOW()",
																			'usuario' => session('nomeusuario'),
																			'data' => "NOW()"
																		);
																		self::$db->update("produto", $data_produto, "id=" . $key->id_produto_kit);

																		$retorno_row = $this->getTabelaPrecos();
																		if ($retorno_row) {
																			foreach ($retorno_row as $exrow) {
																				$percentual = getValue("percentual", "produto_tabela", "id_tabela=" . $exrow->id . " AND id_produto=" . $key->id_produto_kit);
																				$novo_valor_venda = ((1 + ((float)$percentual / 100)) * $valor_custo_atualizado);
																				$valor_venda_atual = getValue("valor_venda", "produto_tabela", "id_tabela=" . $exrow->id . " AND id_produto=" . $key->id_produto_kit);
																				$novo_percentual = ((float)$valor_venda_atual / $valor_custo_atualizado) - 1;

																				if ($atualizar_valor_produto) {
																					if ($novo_valor_venda > $valor_venda_atual) {
																						$data_tabela_novo = array(
																							'percentual' => $percentual,
																							'valor_venda' => round($novo_valor_venda, 2),
																							'usuario' => session('nomeusuario'),
																							'data' => "NOW()"
																						);
																					} else {
																						$data_tabela_novo = array(
																							'percentual' => $novo_percentual * 100,
																							'usuario' => session('nomeusuario'),
																							'data' => "NOW()"
																						);
																					}
																					self::$db->update("produto_tabela", $data_tabela_novo, "id_tabela=" . $exrow->id . " AND id_produto=" . $key->id_produto_kit);
																				} else {
																					$data_tabela_novo = array(
																						'percentual' => $novo_percentual * 100,
																						'usuario' => session('nomeusuario'),
																						'data' => "NOW()"
																					);
																					self::$db->update("produto_tabela", $data_tabela_novo, "id_tabela=" . $exrow->id . " AND id_produto=" . $key->id_produto_kit);
																				}
																			}
																		}
																	}
																}
															}
														}
													} else {
														$percentual_novo = (($valor_atual_venda_produto/$novo_valor_custo)-1)*100;
														$data_produto_tabela = array(
															'percentual' => $percentual_novo
														);
														self::$db->update("produto_tabela", $data_produto_tabela, "id=" . $rowpt->id);
													}
												}
											}
										}
									}
								}
							}
						}
						if($isValido)
						{
							Filter::msgAlert(lang('MSG_ERRO_NOTA_CADASTRADA'),"index.php?do=xml&acao=entradas");
						} elseif(!$id_empresa) {
							Filter::msgAlert(lang('MSG_ERRO_MATRIZ_FILIAL'),"index.php?do=xml&acao=entradas");
						}
					} else {
						print Filter::msgAlert(lang('MSG_ERRO_XML_NFE')."<br/>");
					}
				} else {
					Filter::msgAlert("Arquivo nao foi encontrado.<br/>");
				}
			}
		}
		if($contar > 0 ) {
			Filter::msgOk("Arquivo XML......PROCESSADO. Total de [$contar] Nota Fiscal", "index.php?do=notafiscal&acao=visualizar&id=".$id_nota);
		} else {
			Filter::msgError("Nenhuma NFe cadastrada.<br/>","index.php?do=xml&acao=entradas");
		}
      }

	  /**
       * Arquivo::processarNFeSaida()
       * MODELO: 1 - SERVICO
       * MODELO: 2 - PRODUTO
       * MODELO: 3 - FATURA
       * MODELO: 4 - TRANSPORTE
       * OPERACAO: 1 - COMPRA (ENTRADA)
       * OPERACAO: 2 - VENDA (SAIDA)
       * @return
       */
      public function processarNFeSaida()
      {
		$contar = 0;
		$id_nota = 0;
		$isValido = 0;

		$id_os = (post('OrdemServico')) ? sanitize(post('OrdemServico')) : 0;

		foreach($_POST as $nome_campo => $valor){
			$valida = strpos($nome_campo, "tmpname");
			if($valida) {
				$arquivo = UPLOADS.$valor;
				if (file_exists($arquivo))
				{
					$dom = new DOMDocument('1.0');
					$dom_sxe = $dom->load($arquivo);
					if (!$dom_sxe) {
						print Filter::msgAlert(lang('MSG_ERRO_XML'));
						exit;
					}
					$isProduto = $dom->getElementsByTagName("infNFe");
					$isServico = $dom->getElementsByTagName("InfNfse");
					if($isServico->length > 0) {
						foreach($dom->getElementsByTagName("InfNfse") as $compnfse)
						{
							$array_nodes = array();
							$childNodes = $compnfse->childNodes;
							lerNodes($childNodes, $array_nodes);
							
							$cnpj_empresa = 0;
							if (array_key_exists('PrestadorServico_IdentificacaoPrestador_Cnpj',$array_nodes))
								$cnpj_empresa = $array_nodes['PrestadorServico_IdentificacaoPrestador_Cnpj'];
							elseif (array_key_exists('PrestadorServico_IdentificacaoPrestador_CpfCnpj_Cnpj',$array_nodes))
								$cnpj_empresa = $array_nodes['PrestadorServico_IdentificacaoPrestador_CpfCnpj_Cnpj'];
							elseif (array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Prestador_CpfCnpj_Cnpj',$array_nodes))
								$cnpj_empresa = $array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Prestador_CpfCnpj_Cnpj'];
							$cnpj_empresa = limparCPF_CNPJ($cnpj_empresa);

							$id_empresa = checkEmpresa($cnpj_empresa);
							$complemento = (array_key_exists('TomadorServico_Endereco_Complemento',$array_nodes)) ? $array_nodes['TomadorServico_Endereco_Complemento'] : '';
							$inscricao = (array_key_exists('TomadorServico_IdentificacaoTomador_InscricaoEstadual',$array_nodes)) ? $array_nodes['TomadorServico_IdentificacaoTomador_InscricaoEstadual'] : '';
							$email = (array_key_exists('TomadorServico_Contato_Email',$array_nodes)) ? $array_nodes['TomadorServico_Contato_Email'] : '';
							$tipo = 0;
							$cpf_cnpj = 0;
							if(array_key_exists('TomadorServico_IdentificacaoTomador_CpfCnpj_Cnpj',$array_nodes)) {
								$cpf_cnpj = limparCPF_CNPJ($array_nodes['TomadorServico_IdentificacaoTomador_CpfCnpj_Cnpj']);
								$tipo = 1;
							} elseif(array_key_exists('TomadorServico_IdentificacaoTomador_CpfCnpj_Cpf',$array_nodes)) {
								$cpf_cnpj = limparCPF_CNPJ($array_nodes['TomadorServico_IdentificacaoTomador_CpfCnpj_Cpf']);
								$tipo = 2;
							} elseif(array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Tomador_IdentificacaoTomador_CpfCnpj_Cnpj',$array_nodes)) {
								$cpf_cnpj = limparCPF_CNPJ($array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Tomador_IdentificacaoTomador_CpfCnpj_Cnpj']);
								$tipo = 1;
							} elseif(array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Tomador_IdentificacaoTomador_CpfCnpj_Cpf',$array_nodes)) {
								$cpf_cnpj = limparCPF_CNPJ($array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Tomador_IdentificacaoTomador_CpfCnpj_Cpf']);
								$tipo = 2;
							}

							$razao_social = '';
							if (array_key_exists('TomadorServico_RazaoSocial',$array_nodes)){
								$razao_social = cleanSanitize($array_nodes['TomadorServico_RazaoSocial']);
							} elseif (array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Tomador_RazaoSocial',$array_nodes)){
								$razao_social = cleanSanitize($array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Tomador_RazaoSocial']);
							}
							$cep = '';
							if (array_key_exists('TomadorServico_Endereco_Cep',$array_nodes)){
								$cep = sanitize($array_nodes['TomadorServico_Endereco_Cep']);
							} elseif (array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Tomador_Endereco_Cep',$array_nodes)){
								$cep = sanitize($array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Tomador_Endereco_Cep']);
							}
							$endereco = '';
							if (array_key_exists('TomadorServico_Endereco_Endereco',$array_nodes)){
								$endereco = cleanSanitize($array_nodes['TomadorServico_Endereco_Endereco']);
							} elseif (array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Tomador_Endereco_Endereco',$array_nodes)){
								$endereco = cleanSanitize($array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Tomador_Endereco_Endereco']);
							}
							$numero = '';
							if (array_key_exists('TomadorServico_Endereco_Numero',$array_nodes)){
								$numero = sanitize($array_nodes['TomadorServico_Endereco_Numero']);
							} elseif (array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Tomador_Endereco_Numero',$array_nodes)){
								$numero = sanitize($array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Tomador_Endereco_Numero']);
							}
							$bairro = '';
							if (array_key_exists('TomadorServico_Endereco_Bairro',$array_nodes)){
								$bairro = cleanSanitize($array_nodes['TomadorServico_Endereco_Bairro']);
							} elseif (array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Tomador_Endereco_Bairro',$array_nodes)){
								$bairro = cleanSanitize($array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Tomador_Endereco_Bairro']);
							}
							$estado = '';
							if (array_key_exists('TomadorServico_Endereco_Uf',$array_nodes)){
								$estado = cleanSanitize($array_nodes['TomadorServico_Endereco_Uf']);
							} elseif (array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Tomador_Endereco_Uf',$array_nodes)){
								$estado = cleanSanitize($array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Tomador_Endereco_Uf']);
							}

							$data = array(
								'id_empresa' => $id_empresa,
								'razao_social' => html_entity_decode($razao_social, ENT_QUOTES, 'UTF-8'),
								'tipo' => $tipo,
								'cpf_cnpj' => $cpf_cnpj ,
								'email' => $email,
								'cep' => $cep,
								'endereco' => $endereco,
								'numero' => $numero,
								'complemento' => cleanSanitize($complemento),
								'bairro' => $bairro,
								'estado' => $estado,
								'ie' => $inscricao,
								'cliente' => 1,
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							);
							$id_cadastro = checkRegistro('cpf_cnpj', 'cadastro', $cpf_cnpj);
							if($id_cadastro) {
								self::$db->update("cadastro", $data, "id=".$id_cadastro);
							} else {
								$nome = cleanSanitize($array_nodes['TomadorServico_RazaoSocial']);
								$data['nome'] = html_entity_decode($nome, ENT_QUOTES, 'UTF-8');
								$id_cadastro = self::$db->insert("cadastro", $data);
							}

							$numero_nota = sanitize($array_nodes['Numero']);
							$chaveacesso = sanitize($array_nodes['CodigoVerificacao']);
							$isValido = checkCondicao('nota_fiscal', 'operacao=2 and numero_nota ="'.$numero_nota.'" and id_cadastro='.$id_cadastro);
							if(!$isValido and $id_empresa) {
								$contar++;
								$data_emissao = substr(sanitize($array_nodes['DataEmissao']), 0,10);

								if (array_key_exists('Servico_Valores_ValorLiquidoNfse',$array_nodes)) {
									$valor_nota = sanitize($array_nodes['Servico_Valores_ValorLiquidoNfse']);
								} elseif (array_key_exists('ValoresNfse_ValorLiquidoNfse',$array_nodes)) {
									$valor_nota = sanitize($array_nodes['ValoresNfse_ValorLiquidoNfse']);
								}

								$valor_iss_retido = (array_key_exists('Servico_Valores_ValorIssRetido',$array_nodes)) ? $array_nodes['Servico_Valores_ValorIssRetido'] : 0;
								$valor_iss = (array_key_exists('Servico_Valores_ValorIss',$array_nodes)) ? $array_nodes['Servico_Valores_ValorIss'] : 0;
								$valor_cofins = (array_key_exists('Servico_Valores_ValorCofins',$array_nodes)) ? $array_nodes['Servico_Valores_ValorCofins'] : 0;
								$valor_desconto = (array_key_exists('Servico_Valores_DescontoCondicionado',$array_nodes)) ? $array_nodes['Servico_Valores_DescontoCondicionado'] : 0;
								$descriminacao = (array_key_exists('Servico_Discriminacao',$array_nodes)) ? sanitize($array_nodes['Servico_Discriminacao']) : sanitize($array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Discriminacao']);

								$id_contrato = (!empty($_POST['id_contrato'])) ? $_POST['id_contrato'] : 0 ;

								if (array_key_exists('Servico_Valores_ValorServicos',$array_nodes)){
									$valor_total = sanitize($array_nodes['Servico_Valores_ValorServicos']);
								} elseif (array_key_exists('DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Valores_ValorServicos',$array_nodes)){
									$valor_total = sanitize($array_nodes['DeclaracaoPrestacaoServico_InfDeclaracaoPrestacaoServico_Servico_Valores_ValorServicos']);
								}

								$datanota = array(
									'id_empresa' => $id_empresa,
									'numero_nota' => $numero_nota,
									'numero' => $numero_nota,
									'id_cadastro' => $id_cadastro,
									'cpf_cnpj' => $cpf_cnpj ,
									'data_emissao' => $data_emissao,
									'data_entrada' => "NOW()",
									'chaveacesso' => $chaveacesso,
									'descriminacao' => $descriminacao,
									'valor_desconto' => $valor_desconto,
									'valor_cofins' => $valor_cofins,
									'valor_nota' => $valor_nota,
									'nome_arquivo' => $valor,
									'modelo' => 1,
									'operacao' => 2,
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								$id_nota = self::$db->insert("nota_fiscal", $datanota);

								if (self::$db->affected() && $id_os>0) {
									$data_os = array(
										'id_nota_servico' => $id_nota
									);
									self::$db->update("ordem_servico", $data_os, "id=".$id_os);
								}

								$descricao = 'RECEITA AUTOMATICA DE NOTA FISCAL DE SERVICO - '.$numero_nota;
								$data_receita = array(
									'id_empresa' => $id_empresa,
									'id_cadastro' => $id_cadastro,
									'id_nota' => $id_nota,
									'id_conta' => 19,
									'duplicata' => $numero_nota,
									'descricao' => $descricao,
									'tipo' => 3,
									'pago' => '0',
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								$vencimentos = stripTags('[', ']', $descriminacao);
								if($vencimentos){
									$vencimento_array = explode(';', $vencimentos);
									$count = count($vencimento_array);
									$i = 0;
									if($count > 1) {
										while($i < $count) {
											$v = $i+1;
											$data_receita['valor'] = converteMoeda(trim($vencimento_array[$v]));
											$data_receita['valor_pago'] = converteMoeda(trim($vencimento_array[$v]));
											$data_receita['data_pagamento'] = dataMySQL(trim($vencimento_array[$i]));
											self::$db->insert("receita", $data_receita);
											$i += 2;
										}
									} else {
										$vencimento_array = explode('/', $vencimentos);
										$count = count($vencimento_array);
										if($count == 3)
											$data_vencimento = dataMySQL(trim($vencimentos));
										else
											$data_vencimento = $data_emissao;
										$data_receita['valor'] = $valor_nota;
										$data_receita['valor_pago'] = $valor_nota;
										$data_receita['data_pagamento'] = $data_vencimento;
										self::$db->insert("receita", $data_receita);
									}
								} else {
									$data_receita['valor'] = $valor_nota;
									$data_receita['valor_pago'] = $valor_nota;
									$data_receita['data_pagamento'] = $data_emissao;
									self::$db->insert("receita", $data_receita);
								}

							}
						}
						if($isValido)
						{
							Filter::msgAlert("Nao foi gravado nenhum registro, esta NOTA FISCAL ja esta cadastrada.");
						} elseif(!$id_empresa) {
							Filter::msgError("MATRIZ ou FILIAL nao encontrada.");
						}
					} elseif($isProduto->length > 0) {
						foreach($dom->getElementsByTagName("infNFe") as $compnfse)
						{
							$chaveacesso = $compnfse->getAttribute('Id');
							$chaveacesso = str_replace("NFe","",$chaveacesso);
							$array_nodes = array();
							$childNodes = $compnfse->childNodes;
							lerNodes($childNodes, $array_nodes);

							$cnpj_empresa = limparCPF_CNPJ($array_nodes['emit_CNPJ']);
							$id_empresa = checkEmpresa($cnpj_empresa);
							$tipo = 0;
							$cpf_cnpj = 0;
							if(array_key_exists('dest_CNPJ',$array_nodes)) {
								$cpf_cnpj = limparCPF_CNPJ($array_nodes['dest_CNPJ']);
								$tipo = 1;
							} else {
								$cpf_cnpj = limparCPF_CNPJ($array_nodes['dest_CPF']);
								$tipo = 2;
							}
							$inscricao = (array_key_exists('dest_IE',$array_nodes)) ? $array_nodes['dest_IE'] : '';
							$razao_social = cleanSanitize($array_nodes['dest_xNome']);
							$data = array(
								'id_empresa' => $id_empresa,
								'razao_social' => html_entity_decode($razao_social, ENT_QUOTES, 'UTF-8'),
								'tipo' => $tipo,
								'cpf_cnpj' => $cpf_cnpj ,
								'cep' => sanitize($array_nodes['dest_enderDest_CEP']),
								'endereco' => cleanSanitize($array_nodes['dest_enderDest_xLgr']),
								'numero' => sanitize($array_nodes['dest_enderDest_nro']),
								'bairro' => cleanSanitize($array_nodes['dest_enderDest_xBairro']),
								'cidade' => cleanSanitize($array_nodes['dest_enderDest_xMun']),
								'estado' => cleanSanitize($array_nodes['dest_enderDest_UF']),
								'ie' => $inscricao,
								'cliente' => 1,
								'usuario' => session('nomeusuario'),
								'data' => "NOW()"
							);
							$id_cadastro = checkRegistro('cpf_cnpj', 'cadastro', $cpf_cnpj);
							if($id_cadastro) {
								self::$db->update("cadastro", $data, "id=".$id_cadastro);
							} else {
								$nome = cleanSanitize($array_nodes['dest_xNome']);
								$data['nome'] = html_entity_decode($nome, ENT_QUOTES, 'UTF-8');
								$id_cadastro = self::$db->insert("cadastro", $data);
							}
							$numero_nota = sanitize($array_nodes['ide_nNF']);
							$isValido = checkCondicao('nota_fiscal', 'operacao=2 and numero_nota ="'.$numero_nota.'" and id_cadastro='.$id_cadastro);
							if(!$isValido and $id_empresa) {
								$contar++;
								$data_emissao = date('Y-m-d');
								if(array_key_exists('ide_dhEmi',$array_nodes)) {
									$data_emissao = $array_nodes['ide_dhEmi'];
								} elseif(array_key_exists('ide_dEmi',$array_nodes)) {
									$data_emissao = $array_nodes['ide_dEmi'];
								}
								$data_emissao = substr($data_emissao, 0,10);
								$valor_base = (array_key_exists('total_ICMSTot_vBC',$array_nodes)) ? $array_nodes['total_ICMSTot_vBC'] : 0;
								$valor_icms = (array_key_exists('total_ICMSTot_vICMS',$array_nodes)) ? $array_nodes['total_ICMSTot_vICMS'] : 0;
								$valor_base_st = (array_key_exists('total_ICMSTot_vBCST',$array_nodes)) ? $array_nodes['total_ICMSTot_vBCST'] : 0;
								$valor_st = (array_key_exists('total_ICMSTot_vST',$array_nodes)) ? $array_nodes['total_ICMSTot_vST'] : 0;
								$valor_produto = (array_key_exists('total_ICMSTot_vProd',$array_nodes)) ? $array_nodes['total_ICMSTot_vProd'] : 0;
								$valor_frete = (array_key_exists('total_ICMSTot_vFrete',$array_nodes)) ? $array_nodes['total_ICMSTot_vFrete'] : 0;
								$valor_seguro = (array_key_exists('total_ICMSTot_vSeg',$array_nodes)) ? $array_nodes['total_ICMSTot_vSeg'] : 0;
								$valor_desconto = (array_key_exists('total_ICMSTot_vDesc',$array_nodes)) ? $array_nodes['total_ICMSTot_vDesc'] : 0;
								$valor_ipi = (array_key_exists('total_ICMSTot_vIPI',$array_nodes)) ? $array_nodes['total_ICMSTot_vIPI'] : 0;
								$valor_pis = (array_key_exists('total_ICMSTot_vPIS',$array_nodes)) ? $array_nodes['total_ICMSTot_vPIS'] : 0;
								$valor_cofins = (array_key_exists('total_ICMSTot_vCOFINS',$array_nodes)) ? $array_nodes['total_ICMSTot_vCOFINS'] : 0;
								$valor_outro = (array_key_exists('total_ICMSTot_vOutro',$array_nodes)) ? $array_nodes['total_ICMSTot_vOutro'] : 0;
								$valor_nota = sanitize($array_nodes['total_ICMSTot_vNF']);
								$valor_total_trib = (array_key_exists('total_ICMSTot_vTotTrib',$array_nodes)) ? $array_nodes['total_ICMSTot_vTotTrib'] : 0;
								$inf_adicionais = (array_key_exists('infAdic_infCpl',$array_nodes)) ? $array_nodes['infAdic_infCpl'] : '';

								$duplicatas = '';
								foreach($dom->getElementsByTagName("dup") as $dup)
								{
									$array_dupnodes = array();
									$dupchildNodes = $dup->childNodes;
									lerNodes($dupchildNodes, $array_dupnodes);
									$duplicatas .= "(".$array_dupnodes['nDup'].", ".$array_dupnodes['dVenc'].", ".$array_dupnodes['vDup'].")";
								}
								$datanota = array(
									'id_empresa' => $id_empresa,
									'numero_nota' => $numero_nota,
									'numero' => $numero_nota,
									'id_cadastro' => $id_cadastro,
									'cpf_cnpj' => $cpf_cnpj ,
									'data_emissao' => $data_emissao,
									'data_entrada' => "NOW()",
									'chaveacesso' => $chaveacesso,
									'descriminacao' => sanitize($array_nodes['ide_natOp']),
									'duplicatas' => $duplicatas,
									'valor_base_icms' => $valor_base,
									'valor_icms' => $valor_icms,
									'valor_base_st' => $valor_base_st,
									'valor_st' => $valor_st,
									'valor_produto' => $valor_produto,
									'valor_frete' => $valor_frete,
									'valor_seguro' => $valor_seguro,
									'valor_desconto' => $valor_desconto,
									'valor_ipi' => $valor_ipi,
									'valor_pis' => $valor_pis,
									'valor_cofins' => $valor_cofins,
									'valor_outro' => $valor_outro,
									'valor_nota' => $valor_nota,
									'valor_total_trib' => $valor_total_trib,
									'inf_adicionais' => sanitize($inf_adicionais),
									'nome_arquivo' => $valor,
									'modelo' => 2,
									'operacao' => 2,
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								$id_nota = self::$db->insert("nota_fiscal", $datanota);

								if (self::$db->affected() && $id_os>0) {
									$data_os = array(
										'id_nota_servico' => $id_nota
									);
									self::$db->update("ordem_servico", $data_os, "id=".$id_os);
								}

								foreach($dom->getElementsByTagName("dup") as $dup)
								{
									$array_dupnodes = array();
									$dupchildNodes = $dup->childNodes;
									lerNodes($dupchildNodes, $array_dupnodes);
									$duplicata = $array_dupnodes['nDup'];
									$data_vencimento = $array_dupnodes['dVenc'];
									$valor = $array_dupnodes['vDup'];
									$descricao = 'DUPLICATA ['.$duplicata.'] DA NOTA FISCAL DE PRODUTO - '.$numero_nota;

									$data_receita = array(
										'id_empresa' => $id_empresa,
										'id_cadastro' => $id_cadastro,
										'id_nota' => $id_nota,
										'id_conta' => 19,
										'duplicata' => $duplicata,
										'descricao' => $descricao,
										'tipo' => 3,
										'valor' => $valor,
										'valor_pago' => $valor,
										'data_pagamento' => $data_vencimento,
										'pago' => '0',
										'usuario' => session('nomeusuario'),
										'data' => "NOW()"
									);
									self::$db->insert("receita", $data_receita);
								}

								foreach($dom->getElementsByTagName("det") as $det)
								{
									$atributo = $det->getAttribute('nItem');
									$array_detnodes = array();
									$detchildNodes = $det->childNodes;
									lerNodes($detchildNodes, $array_detnodes);
									$codigonota = sanitize($array_detnodes['prod_cProd']);
									$codigobarras = (array_key_exists('prod_cEAN',$array_detnodes)) ? $array_detnodes['prod_cEAN'] : '';
									$codigobarras = (is_numeric($codigobarras)) ? $codigobarras : '';
									$nome_produto = cleanSanitize($array_detnodes['prod_xProd']);
									$ncm = (array_key_exists('prod_NCM',$array_detnodes)) ? $array_detnodes['prod_NCM'] : '';
									$cfop = (array_key_exists('prod_CFOP',$array_detnodes)) ? $array_detnodes['prod_CFOP'] : '';
									$cest = (array_key_exists('prod_CEST',$array_detnodes)) ? $array_detnodes['prod_CEST'] : '';
									$unidade = (array_key_exists('prod_uCom',$array_detnodes)) ? $array_detnodes['prod_uCom'] : '';
									$cod_anp = (array_key_exists('prod_comb_cProdANP',$array_detnodes)) ? $array_detnodes['prod_comb_cProdANP'] : '';
									$valor_partida = (array_key_exists('prod_comb_vPart',$array_detnodes)) ? $array_detnodes['prod_comb_vPart'] : '';
									$quantidade = $array_detnodes['prod_qCom'];
									$valor_unitario = (array_key_exists('prod_vUnCom',$array_detnodes)) ? $array_detnodes['prod_vUnCom'] : 0;
									$valor_desconto = (array_key_exists('prod_vDesc',$array_detnodes)) ? $array_detnodes['prod_vDesc'] : 0;
									$valor_total = sanitize($array_detnodes['prod_vProd']);
									$valor_total_trib = (array_key_exists('imposto_vTotTrib',$array_detnodes)) ? $array_detnodes['imposto_vTotTrib'] : 0;
									if(array_key_exists('imposto_ICMS_ICMS00_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMS00_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS00_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMS00_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS00_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMS00_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS00_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMS00_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS00_vICMS'] : 0;
									} elseif(array_key_exists('imposto_ICMS_ICMS10_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMS10_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS10_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMS10_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS10_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMS10_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS10_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMS10_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS10_vICMS'] : 0;
									} elseif(array_key_exists('imposto_ICMS_ICMS20_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMS20_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS20_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMS20_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS20_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMS20_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS20_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMS20_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS20_vICMS'] : 0;
									} elseif(array_key_exists('imposto_ICMS_ICMS30_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMS30_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS30_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMS30_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS30_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMS30_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS30_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMS30_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS30_vICMS'] : 0;
									} elseif(array_key_exists('imposto_ICMS_ICMS40_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMS40_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS40_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMS40_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS40_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMS40_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS40_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMS40_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS40_vICMS'] : 0;
									} elseif(array_key_exists('imposto_ICMS_ICMS41_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMS41_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS41_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMS41_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS41_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMS41_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS41_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMS41_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS41_vICMS'] : 0;
									} elseif(array_key_exists('imposto_ICMS_ICMS50_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMS50_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS50_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMS50_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS50_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMS50_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS50_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMS50_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS50_vICMS'] : 0;
									} elseif(array_key_exists('imposto_ICMS_ICMS51_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMS51_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS51_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMS51_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS51_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMS51_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS51_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMS51_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS51_vICMS'] : 0;
									} elseif(array_key_exists('imposto_ICMS_ICMS60_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMS60_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS60_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMS60_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS60_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMS60_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS60_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMS60_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS60_vICMS'] : 0;
									} elseif(array_key_exists('imposto_ICMS_ICMS61_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMS61_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS61_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMS61_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS61_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMS61_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS61_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMS61_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS61_vICMS'] : 0;
									} elseif(array_key_exists('imposto_ICMS_ICMS70_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMS70_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS70_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMS70_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS70_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMS70_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS70_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMS70_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS70_vICMS'] : 0;
									} elseif(array_key_exists('imposto_ICMS_ICMS90_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMS90_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS90_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMS90_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS90_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMS90_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS90_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMS90_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMS90_vICMS'] : 0;
									} elseif(array_key_exists('imposto_ICMS_ICMSSN101_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN101_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMSSN101_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN101_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN101_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN101_vICMS'] : 0;
									} elseif(array_key_exists('imposto_ICMS_ICMSSN102_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN102_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMSSN102_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN102_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN102_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN102_vICMS'] : 0;
									} elseif(array_key_exists('imposto_ICMS_ICMSSN103_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN103_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMSSN103_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN103_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN103_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN103_vICMS'] : 0;
									} elseif(array_key_exists('imposto_ICMS_ICMSSN201_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN201_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMSSN201_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN201_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN201_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN201_vICMS'] : 0;
									} elseif(array_key_exists('imposto_ICMS_ICMSSN202_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN202_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMSSN202_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN202_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN202_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN202_vICMS'] : 0;
									} elseif(array_key_exists('imposto_ICMS_ICMSSN203_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN203_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMSSN203_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN203_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN203_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN203_vICMS'] : 0;
									} elseif(array_key_exists('imposto_ICMS_ICMSSN400_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN400_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMSSN400_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN400_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN400_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN400_vICMS'] : 0;
									} elseif(array_key_exists('imposto_ICMS_ICMSSN500_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN500_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMSSN500_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN500_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN500_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN500_vICMS'] : 0;
									} elseif(array_key_exists('imposto_ICMS_ICMSSN900_CST',$array_detnodes)) {
										$icms_cst = (array_key_exists('imposto_ICMS_ICMSSN900_CST',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_CST'] : 0;
										$icms_base = (array_key_exists('imposto_ICMS_ICMSSN900_vBC',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_vBC'] : 0;
										$icms_percentual = (array_key_exists('imposto_ICMS_ICMSSN900_pICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_pICMS'] : 0;
										$icms_valor = (array_key_exists('imposto_ICMS_ICMSSN900_vICMS',$array_detnodes)) ? $array_detnodes['imposto_ICMS_ICMSSN900_vICMS'] : 0;
									}
									$pis_cst = (array_key_exists('imposto_PIS_PISAliq_CST',$array_detnodes)) ? $array_detnodes['imposto_PIS_PISAliq_CST'] : 0;
									$pis_base = (array_key_exists('imposto_PIS_PISAliq_vBC',$array_detnodes)) ? $array_detnodes['imposto_PIS_PISAliq_vBC'] : 0;
									$pis_percentual = (array_key_exists('imposto_PIS_PISAliq_pPIS',$array_detnodes)) ? $array_detnodes['imposto_PIS_PISAliq_pPIS'] : 0;
									$pis_valor = (array_key_exists('imposto_PIS_PISAliq_vPIS',$array_detnodes)) ? $array_detnodes['imposto_PIS_PISAliq_vPIS'] : 0;
									$cofins_cst = (array_key_exists('imposto_COFINS_COFINSAliq_CST',$array_detnodes)) ? $array_detnodes['imposto_COFINS_COFINSAliq_CST'] : 0;
									$cofins_base = (array_key_exists('imposto_COFINS_COFINSAliq_vBC',$array_detnodes)) ? $array_detnodes['imposto_COFINS_COFINSAliq_vBC'] : 0;
									$cofins_percentual = (array_key_exists('imposto_COFINS_COFINSAliq_pCOFINS',$array_detnodes)) ? $array_detnodes['imposto_COFINS_COFINSAliq_pCOFINS'] : 0;
									$cofins_valor = (array_key_exists('imposto_COFINS_COFINSAliq_vCOFINS',$array_detnodes))? $array_detnodes['imposto_COFINS_COFINSAliq_vCOFINS'] : 0;
									$ipi_cst = (array_key_exists('imposto_IPI_IPITrib_CST',$array_detnodes)) ? $array_detnodes['imposto_IPI_IPITrib_CST'] : 0;
									$ipi_base = (array_key_exists('imposto_IPI_IPITrib_vBC',$array_detnodes)) ? $array_detnodes['imposto_IPI_IPITrib_vBC'] : 0;
									$ipi_percentual = (array_key_exists('imposto_IPI_IPITrib_pIPI',$array_detnodes)) ? $array_detnodes['imposto_IPI_IPITrib_pIPI'] : 0;
									$ipi_valor = (array_key_exists('imposto_IPI_IPITrib_vIPI',$array_detnodes)) ? $array_detnodes['imposto_IPI_IPITrib_vIPI'] : 0;

									$id_produto_fornecedor = getValue('id', 'produto_fornecedor', 'id_cadastro="'.$id_cadastro.'" and codigonota="'.$codigonota. '" and ncm="'.$ncm. '"');
									$valor_unitario_fornecedor = getValue('valor_unitario', 'produto_fornecedor', 'id_cadastro="'.$id_cadastro.'" and codigonota="'.$codigonota. '" and ncm="'.$ncm. '"');
									$id_produto = getValue('id', 'produto', 'codigobarras <> "" and codigobarras="'.$codigobarras.'"');
									$id_produto = ($id_produto > 0) ? $id_produto : getValue('id_produto', 'produto_fornecedor', 'codigonota="'.$codigonota. '" and ncm="'.$ncm.'"');
									$quantidade_compra = 1;
									if($id_produto_fornecedor > 0){
										$quantidade_compra = getValue('quantidade_compra', 'produto_fornecedor', 'id='.$id_produto_fornecedor);
										$data_produto = array(
											'id_produto' => $id_produto,
											'id_nota' => $id_nota,
											'nome' => $nome_produto,
											'ncm' => $ncm,
											'codigobarras' => $codigobarras,
											'valor_unitario' => $valor_unitario,
											'unidade_compra' => strtoupper($unidade),
											'usuario' => session('nomeusuario'),
											'data' => "NOW()"
										);
										self::$db->update("produto_fornecedor", $data_produto, "id=".$id_produto_fornecedor);
									} else {
										$data_produto = array(
											'id_produto' => $id_produto,
											'id_cadastro' => $id_cadastro,
											'id_nota' => $id_nota,
											'nome' => $nome_produto,
											'codigonota' => $codigonota,
											'ncm' => $ncm,
											'codigobarras' => $codigobarras,
											'valor_unitario' => $valor_unitario,
											'quantidade_compra' => 1,
											'unidade_compra' => strtoupper($unidade),
											'usuario' => session('nomeusuario'),
											'data' => "NOW()"
										);
										$id_produto_fornecedor = self::$db->insert("produto_fornecedor", $data_produto);
									}
									$valor_total -= $valor_desconto;
									$data_itens = array(
										'id_empresa' => $id_empresa,
										'id_nota' => $id_nota,
										'id_cadastro' => $id_cadastro,
										'id_produto' => $id_produto,
										'id_produto_fornecedor' => $id_produto_fornecedor,
										'codigonota' => $codigonota,
										'codigobarras' => $codigobarras,
										'ncm' => $ncm,
										'cest' => $cest,
										'cfop' => $cfop,
										'unidade' => $unidade,
										'quantidade' => $quantidade,
										'valor_unitario' => $valor_unitario,
										'valor_negociado_unitario' => $valor_unitario,
										'valor_desconto' => $valor_desconto,
										'valor_total' => $valor_total,
										'valor_negociado_total' => $valor_total,
										'icms_cst' => $icms_cst,
										'icms_base' => $icms_base,
										'icms_percentual' => $icms_percentual,
										'icms_valor' => $icms_valor,
										'pis_cst' => $pis_cst,
										'pis_base' => $pis_base,
										'pis_percentual' => $pis_percentual,
										'pis_valor' => $pis_valor,
										'cofins_cst' => $cofins_cst,
										'cofins_base' => $cofins_base,
										'cofins_percentual' => $cofins_percentual,
										'cofins_valor' => $cofins_valor,
										'ipi_cst' => $ipi_cst,
										'ipi_base' => $ipi_base,
										'ipi_percentual' => $ipi_percentual,
										'ipi_valor' => $ipi_valor,
										'valor_total_trib' => $valor_total_trib,
										'cod_anp' => $cod_anp ,
										'valor_partida' => $valor_partida,
										'usuario' => session('nomeusuario'),
										'data' => "NOW()"
									);
									self::$db->insert("nota_fiscal_itens", $data_itens);

									$nome_cadastro = (array_key_exists('emit_xNome',$array_nodes)) ? cleanSanitize($array_nodes['emit_xNome']) : '';

									if($id_produto > 0) {
										$observacao = 'NOTA FISCAL AUTOMATICA: ['.$numero_nota.'] / FORNECEDOR: '.$nome_cadastro;
										$quant_estoque = ($quantidade*$quantidade_compra) * (-1);
										$data_estoque = array(
											'id_empresa' => $id_empresa,
											'id_produto' => $id_produto,
											'quantidade' => $quant_estoque,
											'tipo' => 2,
											'motivo' => 3,
											'observacao' => $observacao,
											'id_ref' => $id_nota,
											'usuario' => session('nomeusuario'),
											'data' => "NOW()"
										);
										self::$db->insert("produto_estoque", $data_estoque);

										$totalestoque = $this->getEstoqueTotal($id_produto);
										$data_update = array(
											'valor_custo' => $valor_unitario,
											'estoque' => $totalestoque,
											'usuario' => session('nomeusuario'),
											'data' => "NOW()"
										);
										self::$db->update("produto", $data_update, "id=" . $id_produto);
										if($valor_unitario > $valor_unitario_fornecedor) {
											$sql = "UPDATE produto_tabela SET valor_venda = ((1+(percentual/100))*$valor_unitario) WHERE id_produto = $id_produto";
											self::$db->query($sql);
										}
									}
								}
							}
						}
						if($isValido)
						{
							Filter::msgAlert("Nao foi gravado nenhum registro, esta NOTA FISCAL ja esta cadastrada.");
						} elseif(!$id_empresa) {
							Filter::msgError("MATRIZ ou FILIAL nao encontrada.");
						}
					} else {
						print Filter::msgAlert(lang('MSG_ERRO_XML_NFE'));
					}
				} else {
					Filter::msgError("Arquivo nao foi encontrado.");
				}
			}
		}
		if($contar > 0 ) {
			if ($id_os) {
				$data_os = array(
					'id_nota_servico' => $id_nota
				);
				self::$db->update("ordem_servico", $data_os, "id=".$id_produto_fornecedor);
			}

			Filter::msgOk("Arquivo XML......PROCESSADO. Total de [$contar] Nota Fiscal", "index.php?do=notafiscal&acao=visualizar&id=".$id_nota);
		} else {
			Filter::msgError("Nenhuma NFe cadastrada.<br/>");
		}
      }

	  /**
       * Arquivo::processarSintegra()
       * MODELO: 1 - SERVICO
       * MODELO: 2 - PRODUTO
       * MODELO: 3 - FATURA
       * MODELO: 4 - TRANSPORTE
       * OPERACAO: 1 - COMPRA (ENTRADA)
       * OPERACAO: 2 - VENDA (SAIDA)
       * @return
       */
      public function processarSintegra()
      {
		$sucesso = false;
		foreach($_POST as $nome_campo => $valor){
			$valida = strpos($nome_campo, "tmpname");
			if($valida) {
				$ponteiro = fopen (UPLOADS.$valor, "r");
				if ($ponteiro == false) die(lang('FORM_ERROR13'));
				$xml = '';
				while (!feof ($ponteiro)) {
					$linha = fgets($ponteiro, 4096);
					$registro = substr($linha,0,2);
					if($registro == 10) {
						$cnpj_empresa = substr($linha,2,14);
						$id_empresa = checkEmpresa($cnpj_empresa);
					}
					if($registro == 50) {
						$cpf_cnpj = substr($linha,2,14);
						$id_cadastro = checkRegistro('cpf_cnpj', 'cadastro', $cpf_cnpj);
						$numero_nota = intval(substr($linha,45,6));
						$id_nota = checkRegistro('numero_nota', 'nota_fiscal', $numero_nota);
						if(!$id_nota){
							$data_emissao = dataMySQL3(substr($linha,30,8));
							$valor_nota = intval(substr($linha,56,11)).".".intval(substr($linha,67,2));
							$datanota = array(
								'id_empresa' => $id_empresa,
								'numero_nota' => $numero_nota,
								'numero' => $numero_nota,
								'id_cadastro' => $id_cadastro,
								'cpf_cnpj' => $cpf_cnpj ,
								'data_emissao' => $data_emissao,
								'data_entrada' => "NOW()",
								'valor_nota' => $valor_nota,
								'descriminacao' => "NOTA INSERIDA PELO ARQUIVO SINTEGRA",
								'nome_arquivo' => $valor,
								'modelo' => 2,
								'operacao' => 1,
								'usuario' => "sintegra",
								'data' => "NOW()"
							);
							self::$db->insert("nota_fiscal", $datanota);
						}

					}
					if($registro == 54) {
						$numero_nota = intval(substr($linha,21,6));
						$id_nota = getValue('id', 'nota_fiscal', 'numero_nota="'.$numero_nota.'" AND usuario = "sintegra"');
						$cpf_cnpj = substr($linha,2,14);
						$id_cadastro = checkRegistro('cpf_cnpj', 'cadastro', $cpf_cnpj);
						$codigo_produto = intval(substr($linha,37,14));
						$quantidade = intval(substr($linha,51,8)).".".intval(substr($linha,59,3));
						$valor_total = intval(substr($linha,62,10)).".".intval(substr($linha,72,2));
						$valor_unitario = ($quantidade) ? $valor_total/$quantidade : 0;
						$valor_desconto = intval(substr($linha,74,10)).".".intval(substr($linha,84,2));
						$icms = intval(substr($linha,86,10)).".".intval(substr($linha,96,2));
						$ipi_produto = intval(substr($linha,110,10)).".".intval(substr($linha,120,2));
						if($id_nota) {
							$data_itens = array(
								'id_empresa' => $id_empresa,
								'id_nota' => $id_nota,
								'id_cadastro' => $id_cadastro,
								'id_produto' => $codigo_produto,
								'quantidade' => $quantidade,
								'valor_unitario' => $valor_unitario,
								'valor_desconto' => $valor_desconto,
								'valor_total' => $valor_total,
								'icms' => $icms,
								'ipi_produto' => $ipi_produto,
								'usuario' => "valida_sintegra",
								'data' => "NOW()"
							);
							self::$db->insert("nota_fiscal_itens", $data_itens);
						}
					}
					if($registro == 75) {
						$codigonota = intval(substr($linha,18,14));
						$ncm = intval(substr($linha,31,8));
						$nome = substr($linha,40,53);
						$unidade = substr($linha,93,6);
						$id_produto = getValue('id', 'produto', 'codigonota="'.$codigonota.'" and ncm="'.$ncm.'"');
						if(!$id_produto) {
							$data_produto = array(
								'nome' => cleanSanitize($nome),
								'codigo' => $codigonota,
								'ncm' => $ncm,
								'unidade' => $unidade,
								'id_cadastro' => $id_cadastro,
								'id_nota' => $id_nota,
								'usuario' => "sintegra",
								'data' => "NOW()"
							);
							$id_produto = self::$db->insert("produto", $data_produto);
						}
						$data_itens = array(
							'id_produto' => $id_produto,
							'usuario' => "sintegra",
							'data' => "NOW()"
						);
						self::$db->update("nota_fiscal_itens", $data_itens, "id_produto='$codigonota' AND usuario='valida_sintegra'");
					}
				}
				fclose ($ponteiro);
			}
			Filter::msgOk("Arquivo SINTEGRA......PROCESSADO", "index.php?do=xml&acao=sintegra");
		}
      }

	  /**
       * Produto::getEstoqueTotal()
	   *
       * @return
       */
	  public function getEstoqueTotal($id_produto)
      {
          $sql = "SELECT SUM(e.quantidade) AS total "
		  . "\n FROM produto_estoque as e"
		  . "\n WHERE e.inativo = 0 AND e.id_produto = $id_produto ";
          $row = self::$db->first($sql);

          return ($row) ? $row->total : 0;
      }

      /**
      * Retorna se esse produto é kit de outro.
      * Produto::verificaSeProdutoVirouKit()
      *
      * @return
      */
      public function verificaSeProdutoVirouKit($id_produto)
      {
          $sql = "SELECT k.id, k.id_produto_kit, k.id_produto, p.valor_custo
				  FROM produto_kit AS k
				  LEFT JOIN produto as p on p.id = k.id_produto
				  WHERE id_produto = $id_produto";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }

      /**
      * Produto::getValorCustoProdutokit()
      *
      * @return
      */
      public function getValorCustoProdutokit($id_produto)
      {
          $sql = "SELECT SUM(p.valor_custo*k.quantidade) AS valor_custo
				  FROM produto_kit as k
				  LEFT JOIN produto as p ON p.id = k.id_produto
				  WHERE k.id_produto_kit = $id_produto
				  ORDER BY p.nome ";
          $row = self::$db->first($sql);

          return ($row) ? $row->valor_custo : 0;
      }

      /**
      * Produto::getTabelaPrecos()
      *
      * @return
      */
      public function getTabelaPrecos()
      {
          $sql = "SELECT t.*
				  FROM tabela_precos as t
				  WHERE t.inativo = 0
				  ORDER BY t.tabela";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
  }
?>
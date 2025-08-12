<?php
	namespace eNotasGW\Api;

	abstract class nfeApiBase extends eNotasGWApiBase {
		protected $tipoNF;
	
		public function __construct($tipoNF, $proxy) {
			parent::__construct($proxy);
			$this->tipoNF = $tipoNF;
		}
		
		/**
		 * Emite uma Nota Fiscal
		 * 
		 * @param string $idEmpresa id da empresa para a qual a nota será emitida
		 * @param mixed $dadosNFe dados da NFe a ser emitida
		 */
		public function emitir($idEmpresa, $dadosNFe) {
			$result = $this->callOperation(array(
				'method' => 'POST',
				'path' => '/empresas/{empresaId}/{tipoNF}',
				'parameters' => array(
					'path' => array(
					  'empresaId' => $idEmpresa,
					  'tipoNF' => $this->tipoNF
					),
					'body' => $dadosNFe
				)
			));

			return $result;
		}
		
		/**
		 * Cancela uma determinada Nota Fiscal
		 * @param string $nfeId Identificador Único da Nota Fiscal
		 * @param string $idEmpresa id da empresa para a qual a nota será emitida
		 */
		public function cancelar($idEmpresa, $id) {
			$result = $this->callOperation(array(
				'method' => 'DELETE',
				'path' => '/empresas/{empresaId}/{tipoNF}/{id}',
				'parameters' => array(
					'path' => array(
					  'empresaId' => $idEmpresa,
					  'tipoNF' => $this->tipoNF,
					  'id' => $id
					)
				)
			));
			
			return $result;
		}

		/**
		 * Consulta uma Nota Fiscal pelo Identificador Único
		 * 
		 * @param string $idEmpresa id da empresa para a qual a nota será emitida
		 * @param string $nfeId Identificador Único da Nota Fiscal
		 * @return	mixed $dadosNFe	retorna os dados da nota como um array
		 */
		public function consultar($idEmpresa, $nfeId) {
			return $this->callOperation(array(
			  'path' => '/empresas/{empresaId}/{tipoNF}/{id}',
			  'parameters' => array(
					'path' => array(
						'empresaId' => $idEmpresa,
						'tipoNF' => $this->tipoNF,
						'id' => $nfeId
					)
				)
			));
		}

		/**
		* Consulta notas fiscais emitidas em um determinado período
		* 
		* @param string $idEmpresa id da empresa para a qual a nota será emitida
		* @param int $pageNumber numero da página no qual a pesquisa será feita
		* @param int $pageSize quantidade de registros por página
		* @param string $dataInicial data inicial para pesquisa
		* @param string $dataFinal data final para pesquisa
		* @return searchResult	$listaNFe retorna uma lista contendo os registros encontrados na pesquisa
		*/
		public function consultarPorPeriodo($idEmpresa, $pageNumber, $pageSize, $dataInicial, $dataFinal) {
		
			return $this->callOperation(array(
				'path' => '/empresas/{empresaId}/{tipoNF}',
				'parameters' => array(
					'path' => array(
						'empresaId' => $idEmpresa,
						'tipoNF' => $this->tipoNF
					),
					'query' => array(
						'pageNumber' => $pageNumber,
						'pageSize' => $pageSize,
						'filter' => "dataCriacao ge '{$dataInicial}' and dataCriacao le '{$dataFinal}'"
					)
				)
			));
		}

		/**
		* Consulta notas fiscais emitidas em uma empresa
		* 
		* @param string $idEmpresa id da empresa para a qual a nota será emitida
		* @param int $pageNumber numero da página no qual a pesquisa será feita
		* @param int $pageSize quantidade de registros por página
		* @param string $dataInicial data inicial para pesquisa
		* @param string $dataFinal data final para pesquisa
		* @return searchResult	$listaNFe retorna uma lista contendo os registros encontrados na pesquisa
		*/
		public function consultarPorEmpresa($idEmpresa, $pageNumber = 0, $pageSize = 150, $status = false) {
		
			return $this->callOperation(array(
				'path' => '/empresas/{empresaId}/nfes',
				'parameters' => array(
					'path' => array(
						'empresaId' => $idEmpresa,
						'tipoNF' => 'nfes'
					),
					'query' => array(
						'pageNumber' => $pageNumber,
						'pageSize' => $pageSize,
						'filter' => "status eq 'Negada' or status eq 'Autorizada' or status eq 'Cancelada'"
					) 
				)
			));
		}
		
		/**
		* Download do xml de uma Nota Fiscal identificada pelo seu Identificador Único
		* 
		* @param string $idEmpresa id da empresa para a qual a nota será emitida
		* @param string $id Identificador Único da Nota Fiscal
		* @return string xml da nota fiscal
		*/
		public function downloadXml($idEmpresa, $id) {
			return $this->callOperation(array(
				'path' => '/empresas/{empresaId}/{tipoNF}/{id}/xml',
				'decodeResponse' => false,
				'parameters' => array(
					'path' => array(
					  'empresaId' => $idEmpresa,
					  'tipoNF' => $this->tipoNF,
					  'id' => $id
					)
				)
			));
		}
		
		/**
		* Download do pdf de uma Nota Fiscal identificada pelo seu id único
		* 
		* @param string $idEmpresa id da empresa para a qual a nota será emitida
		* @param string $id Identificador Único da Nota Fiscal
		* @return os bytes do arquivo pdf
		*/
		public function downloadPdf($idEmpresa, $id) {
			return $this->callOperation(array(
				'path' => '/empresas/{empresaId}/{tipoNF}/{id}/pdf',
				'decodeResponse' => false,
				'parameters' => array(
					'path' => array(
					  'empresaId' => $idEmpresa,
					  'tipoNF' => $this->tipoNF,
					  'id' => $id
					)
				)
			));
		}
		
		/**
		* Inutiliza uma faixa de numeraço da NF-e / NFC-e
		* 
		* @param string $idEmpresa id da empresa para a qual a inutilização será realizada
		* @param mixed $dadosInutilizacao dados da inutilizacao a ser realizada
		* 
		*/
		public function inutilizarNumeracao($idEmpresa, $dadosInutilizacao) {
			$result = $this->callOperation(array(
				'method' => 'POST',
				'path' => '/empresas/{empresaId}/{tipoNF}/inutilizacao',
				'parameters' => array(
					'path' => array(
					  'empresaId' => $idEmpresa,
					  'tipoNF' => $this->tipoNF
					),
					'body' => $dadosInutilizacao
				)
			));

			return $result;
		}
		
		/**
		 * Consulta uma Inutilização pelo Identificador Único
		 * 
		 * @param string $idEmpresa id da empresa para a qual a nota será emitida
		 * @param string $idInutilizacao Identificador Único da inutilização
		 * @return mixed $dadosInutilizacao retorna os dados da inutilização como um array
		 */
		public function consultarInutilizacao($idEmpresa, $idInutilizacao) {
			return $this->callOperation(array(
			  'path' => '/empresas/{empresaId}/{tipoNF}/inutilizacao/{id}',
			  'parameters' => array(
					'path' => array(
						'empresaId' => $idEmpresa,
						'tipoNF' => $this->tipoNF,
						'id' => $idInutilizacao
					)
				)
			));
		}
		
		/**
		* Download do xml de uma Inutilização identificada pelo seu Identificador Único
		* 
		* @param string $idEmpresa id da empresa para a qual a nota será emitida
		* @param string $idInutilizacao Identificador Único da Inutilização
		* @return string xml da inutilização
		*/
		public function downloadXmlInutilizacao($idEmpresa, $idInutilizacao) {
			return $this->callOperation(array(
				'path' => '/empresas/{empresaId}/{tipoNF}/inutilizacao/{id}/xml',
				'parameters' => array(
					'path' => array(
					  'empresaId' => $idEmpresa,
					  'tipoNF' => $this->tipoNF,
					  'id' => $idInutilizacao
					)
				)
			));
		}
		
		/**
		 * Emite uma Carta de Correção de Nota Fiscal
		 * 
		 * @param string $idEmpresa id da empresa para a qual a nota será emitida
		 * @param mixed $dadosCarta dados da Carta de Correção a ser emitida
		 */
		public function cartaCorrecao($idEmpresa, $dadosCarta) {
			$result = $this->callOperation(array(
				'method' => 'POST',
				'path' => '/empresas/{empresaId}/{tipoNF}/cartaCorrecao',
				'parameters' => array(
					'path' => array(
					  'empresaId' => $idEmpresa,
					  'tipoNF' => $this->tipoNF
					),
					'body' => $dadosCarta
				)
			));

			return $result;
		}
		
		/**
		 * Consulta uma Carta de Correção de Nota Fiscal pelo Identificador Único
		 * 
		 * @param string $idEmpresa id da empresa para a qual a nota será emitida
		 * @param string $idCarta Identificador Único da Carta de Correção de Nota Fiscal
		 * @return mixed $dadosCarta retorna os dados da Carta de Correção de Nota Fiscal como um array
		 */
		public function consultarCartaCorrecao($idEmpresa, $idCarta) {
			return $this->callOperation(array(
			  'path' => '/empresas/{empresaId}/{tipoNF}/cartaCorrecao/{id}',
			  'parameters' => array(
					'path' => array(
						'empresaId' => $idEmpresa,
						'tipoNF' => $this->tipoNF,
						'id' => $idCarta
					)
				)
			));
		}
		
		/**
		* Download do xml de uma Carta de Correção identificada pelo seu Identificador Único
		* 
		* @param string $idEmpresa id da empresa para a qual a nota será emitida
		* @param string $id Identificador Único da Carta de Correção
		* @return string xml da Carta de Correção
		*/
		public function downloadXmlCartaCorrecao($idEmpresa, $id) {
			return $this->callOperation(array(
				'path' => '/empresas/{empresaId}/{tipoNF}/cartaCorrecao/{id}/xml',
				'decodeResponse' => false,
				'parameters' => array(
					'path' => array(
					  'empresaId' => $idEmpresa,
					  'tipoNF' => $this->tipoNF,
					  'id' => $id
					)
				)
			));
		}
	}
?>

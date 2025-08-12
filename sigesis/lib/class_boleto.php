<?php
  /**
   * Classe Boleto
   *
   * @package Sigesis N1
   * @author Vale Telecom
   * @copyright 2022
   * @version 3
   */
  
  if (!defined("_VALID_PHP"))
      die('Acesso direto a esta classe nao e permitido.');

class Boleto
{
      const uTable = "boleto";
      public $did = 0;
      private static $db;

		/**
		* Boleto::__construct()
		* 
		* @return
		*/
		function __construct()
		{
			self::$db = Registry::get("Database");
		}
	  
		/**
		* Boleto::digitoVerificador_cedente()
		*
		* @return
		*/
		public function digitoVerificador_cedente($numero)
		{
			$resto2 = $this->modulo_11($numero, 9, 1);
			$digito = 11 - $resto2;
			if ($digito == 10 || $digito == 11) {
				$dv = 0;
			} else {
				$dv = $digito;
			}
			return $dv;
		}
	  
		/**
		* Boleto::digitoVerificador_nossonumero()
		*
		* @return
		*/
		public function digitoVerificador_nossonumero($numero)
		{
			$resto2 = $this->modulo_11($numero, 9, 1);
			$digito = 11 - $resto2;
			if ($digito == 10 || $digito == 11) {
				$dv = 0;
			} else {
				$dv = $digito;
			}
			return $dv;
		}
	  
		/**
		* Boleto::digitoVerificador_barra()
		*
		* @return
		*/
		public function digitoVerificador_barra($numero) {
			$resto2 = $this->modulo_11($numero, 9, 1);
			if ($resto2 == 0 || $resto2 == 1 || $resto2 == 10) {
				$dv = 1;
			} else {
				$dv = 11 - $resto2;
			}
			return $dv;
		}

		// função que recebe o nosso número e retorna o seu dígito verificador CAIXA ECONOMICA
  		function dvNossoNumeroCaixa($nossoNumero){
			// o nosso número possui mais que 17 dígitos?
			if(strlen($nossoNumero) > 17){
			die("O Nosso Número não pode ter mais que 17 dígitos.");  
			}
     
			// agora vamos definir os índices de multiplicação
			$indices = "29876543298765432";
			// e aqui a soma da multiplicação coluna por coluna
			$soma = 0;
      
			// fazemos a multiplicação coluna por coluna agora
			for($i = 0; $i < strlen($nossoNumero); $i++){
			$soma = $soma + ((int)($nossoNumero[$i])) * 
				((int)($indices[$i])); 
			}
     
			// obtemos o resto da divisão da soma por onze
			$resto = $soma % 11;
			
			// subtraímos onze pelo resto da divisão
			$digito = 11 - $resto;      
      
			// atenção: Se o resultado da subtração for
			// maior que 9 (nove), o dígito será 0 (zero)
			if($digito > 9){
			$digito = 0;    
			}
      
    		return $digito;
  		}

		// função que recebe o Codigo de Barras e retorna o seu dígito verificador geral CAIXA ECONOMICA
		function dvGeralCodigoBarrasCaixa($codigoBarrasSemDv){
			// o codigo sem o dv possui mais que 43 dígitos?
			if(strlen($codigoBarrasSemDv) > 43){
				echo "$codigoBarrasSemDv <br>";
			die("O Nosso Número não pode ter mais que 43 dígitos.");  
			}
     
			// agora vamos definir os índices de multiplicação
			$indices = "4329876543298765432987654329876543298765432";
			// e aqui a soma da multiplicação coluna por coluna
			$soma = 0;
      
			// fazemos a multiplicação coluna por coluna agora
			for($i = 0; $i < strlen($codigoBarrasSemDv); $i++){
			$soma = $soma + ((int)($codigoBarrasSemDv[$i])) * 
				((int)($indices[$i])); 
			}
     
			// obtemos o resto da divisão da soma por onze
			$resto = $soma % 11;
			
			// subtraímos onze pelo resto da divisão
			$digito = 11 - $resto;      
      
			// atenção: Se o resultado da subtração for
			// maior que 9 (nove), o dígito será 0 (zero)
			if($digito>9 || $digito==0){
			$digito = 1;    
			}
      
    		return $digito;
  		}

		// função que recebe o Campo Livre e retorna o seu dígito verificador CAIXA ECONOMICA
		function dvCampoLivreModulo11Caixa($campoLivre){
			// o codigo sem o dv possui mais que 24 dígitos?
			if(strlen($campoLivre) > 24){
			die("O Nosso Número não pode ter mais que 24 dígitos.");  
			}
     
			// agora vamos definir os índices de multiplicação
			$indices = "987654329876543298765432";
			// e aqui a soma da multiplicação coluna por coluna
			$soma = 0;
      
			// fazemos a multiplicação coluna por coluna agora
			for($i = 0; $i < strlen($campoLivre); $i++){
			$soma = $soma + ((int)($campoLivre[$i])) * 
				((int)($indices[$i])); 
			}
     
			// obtemos o resto da divisão da soma por onze
			$resto = $soma % 11;
			
			// subtraímos onze pelo resto da divisão
			$digito = 11 - $resto;      
      
			// atenção: Se o resultado da subtração for
			// maior que 9 (nove), o dígito será 0 (zero)
			if($digito>9){
			$digito = 0;    
			}
      
    		return $digito;
  		}

		// função que recebe o Codigo do Cedente e retorna o seu dígito verificador CAIXA ECONOMICA
		function dvCodigoCedenteModulo11Caixa($codigoCedente){
			// o codigo do cedente possui mais que 07 dígitos?
			if(strlen($codigoCedente) > 7){
			die("O Código do Cedente não pode ter mais que 07 dígitos.");  
			}
     
			// agora vamos definir os índices de multiplicação
			$indices = "8765432";
			// e aqui a soma da multiplicação coluna por coluna
			$soma = 0;
      
			// fazemos a multiplicação coluna por coluna agora
			for($i = 0; $i < strlen($codigoCedente); $i++){
			$soma = $soma + ((int)($codigoCedente[$i])) * 
				((int)($indices[$i])); 
			}
     
			// obtemos o resto da divisão da soma por onze
			$resto = $soma % 11;
			
			// subtraímos onze pelo resto da divisão
			$digito = 11 - $resto;      
      
			// atenção: Se o resultado da subtração for
			// maior que 9 (nove), o dígito será 0 (zero)
			if($digito>9){
			$digito = 0;    
			}
      
    		return $digito;
  		}
   
 		/**
		* Boleto::formata_numero()
		*
		* @return
		*/
		public function formata_numero($numero,$loop,$insert,$tipo = "geral") 
		{
			if ($tipo == "geral") {
				$numero = str_replace(",","",$numero);
				while(strlen($numero)<$loop){
					$numero = $insert . $numero;
				}
			}
			if ($tipo == "valor") {
				/*
				retira as virgulas
				formata o numero
				preenche com zeros
				*/
				$numero = str_replace(",","",$numero);
				while(strlen($numero)<$loop){
					$numero = $insert . $numero;
				}
			}
			if ($tipo == "convenio") {
				while(strlen($numero)<$loop){
					$numero = $numero . $insert;
				}
			}
			return $numero;
		}
	  
		/**
		* Boleto::fbarcode()
		*
		* @return
		*/
		public function fbarcode($valor)
		{
			$retorno = "";
			$fino = 1 ;
			$largo = 3 ;
			$altura = 50 ;

			$barcodes[0] = "00110" ;
			$barcodes[1] = "10001" ;
			$barcodes[2] = "01001" ;
			$barcodes[3] = "11000" ;
			$barcodes[4] = "00101" ;
			$barcodes[5] = "10100" ;
			$barcodes[6] = "01100" ;
			$barcodes[7] = "00011" ;
			$barcodes[8] = "10010" ;
			$barcodes[9] = "01010" ;
			for($f1=9;$f1>=0;$f1--){
				for($f2=9;$f2>=0;$f2--){
					$f = ($f1 * 10) + $f2 ;
					$texto = "" ;
					for($i=1;$i<6;$i++){
						$texto .=  substr($barcodes[$f1],($i-1),1) . substr($barcodes[$f2],($i-1),1);
					}
					$barcodes[$f] = $texto;
				}
			}
			
			$retorno .= "<img src='assets/img_boleto/p.png' width=$fino height=$altura border=0><img src='assets/img_boleto/b.png' width=$fino height=$altura border=0><img src='assets/img_boleto/p.png' width=$fino height=$altura border=0><img src='assets/img_boleto/b.png' width=$fino height=$altura border=0><img ";
			
			$texto = $valor ;
			if((strlen($texto) % 2) <> 0){
				$texto = "0" . $texto;
			}

			// Draw dos dados
			while (strlen($texto) > 0) {
				$i = round($this->esquerda($texto,2));
				$texto = $this->direita($texto,strlen($texto)-2);
				$f = $barcodes[$i];
				for($i=1;$i<11;$i+=2){
					if (substr($f,($i-1),1) == "0") {
						$f1 = $fino ;
					}else{
						$f1 = $largo ;
					}			
					$retorno .= "src='assets/img_boleto/p.png' width=$f1 height=$altura border=0><img ";
					if (substr($f,$i,1) == "0") {
						$f2 = $fino ;
					}else{
						$f2 = $largo ;
					}
					$retorno .= "src='assets/img_boleto/b.png' width=$f2 height=$altura border=0><img ";
				}
			}			
			$retorno .= "src='assets/img_boleto/p.png' width=$largo height=$altura border=0><img src='assets/img_boleto/b.png' width=$fino height=$altura border=0><img src='assets/img_boleto/p.png' width=1 height=$altura border=0>";
			return $retorno;
		}
	  
		/**
		* Boleto::esquerda()
		*
		* @return
		*/
		public function esquerda($entra,$comp)
		{
			return substr($entra,0,$comp);
		}
	  
		/**
		* Boleto::direita()
		*
		* @return
		*/
		public function direita($entra,$comp)
		{
			return substr($entra,strlen($entra)-$comp,$comp);
		}
	  
		/**
		* Boleto::fator_vencimento()
		*
		* @return
		*/
		public function fator_vencimento($data) {
		
			$fator_vencimento = 0;
			if ($data != "") {
				$data = explode("/",$data);
				$ano = $data[2];
				$mes = $data[1];
				$dia = $data[0];
				$fator_vencimento = abs(($this->_dateToDays("1997","10","07")) - ($this->_dateToDays($ano, $mes, $dia)));
				if ($fator_vencimento>9999) $fator_vencimento-=9000;
				return($fator_vencimento);
			} else {
				return "0000";
			}
		}
	  
		/**
		* Boleto::_dateToDays()
		*
		* @return
		*/
		public function _dateToDays($year,$month,$day) 
		{
			$century = substr($year, 0, 2);
			$year = substr($year, 2, 2);
			if ($month > 2) {
				$month -= 3;
			} else {
				$month += 9;
				if ($year) {
					$year--;
				} else {
					$year = 99;
					$century --;
				}
			}
			return (floor((  146097 * $century)    /  4 ) +
					floor(( 1461 * $year)        /  4 ) +
					floor(( 153 * $month +  2) /  5 ) +
						$day +  1721119);
		}
	  
		/**
		* Boleto::modulo_10()
		* @return
		*/
		public function modulo_10($num) {
			$numtotal10 = 0;
			$fator = 2;

			// Separacao dos numeros
			for ($i = strlen($num); $i > 0; $i--) {
				// pega cada numero isoladamente
				$numeros[$i] = substr($num,$i-1,1);
				// Efetua multiplicacao do numero pelo (falor 10)
				$temp = $numeros[$i] * $fator;
				$temp0=0;
				foreach (preg_split('//',$temp,-1,PREG_SPLIT_NO_EMPTY) as $k=>$v){ $temp0+=$v; }
				$parcial10[$i] = $temp0; //$numeros[$i] * $fator;
				// monta sequencia para soma dos digitos no (modulo 10)
				$numtotal10 += $parcial10[$i];
				if ($fator == 2) {
					$fator = 1;
				} else {
					$fator = 2; // intercala fator de multiplicacao (modulo 10)
				}
			}

			// varias linhas removidas, vide funcao original
			// Calculo do modulo 10
			$resto = $numtotal10 % 10;
			$digito = 10 - $resto;
			if ($resto == 0) {
				$digito = 0;
			}

			return $digito;

		}
		
		/**
		* Boleto::modulo_10()
		* PARA BOLETOS DA SICOOB
		* @return
		*/				
		function modulo_10_sicoob($num) {
			$numtotal10 = 0;
			$fator = 2;
		 
			for ($i = strlen($num); $i > 0; $i--) {
				$numeros[$i] = substr($num,$i-1,1);
				$parcial10[$i] = (int)$numeros[$i] * $fator;
				$numtotal10 .= $parcial10[$i];
				if ($fator == 2) {
					$fator = 1;
				}
				else {
					$fator = 2; 
				}
			}
			
			$soma = 0;
			for ($i = strlen($numtotal10); $i > 0; $i--) {
				$numeros[$i] = substr($numtotal10,$i-1,1);
				$soma += $numeros[$i]; 
			}
			$resto = $soma % 10;
			$digito = 10 - $resto;
			if ($resto == 0) {
				$digito = 0;
			}

			return $digito;
		}
	  
		/**
		* Boleto::modulo_11()
		*   Autor:
		*           Pablo Costa <pablo@users.sourceforge.net>
		*
		*   Funcao:
		*    Calculo do Modulo 11 para geracao do digito verificador
		*    de boletos bancarios conforme documentos obtidos
		*    da Febraban - www.febraban.org.br
		*
		*   Entrada:
		*     $num: string numerica para a qual se deseja calcularo digito verificador;
		*     $base: valor maximo de multiplicacao [2-$base]
		*     $r: quando especificado um devolve somente o resto
		*
		*   Saida:
		*     Retorna o Digito verificador.
		*
		*   Observacoes:
		*     - Script desenvolvido sem nenhum reaproveitamento de codigo pre existente.
		*     - Assume-se que a verificacao do formato das variaveis de entrada e feita antes da execucao deste script.
		*
		* @return
		*/
		public function modulo_11($num, $base=9, $r=0)
		{
			$soma = 0;
			$fator = 2;

			/* Separacao dos numeros */
			for ($i = strlen($num); $i > 0; $i--) {
				// pega cada numero isoladamente
				$numeros[$i] = substr($num,$i-1,1);
				// Efetua multiplicacao do numero pelo falor
				$parcial[$i] = $numeros[$i] * $fator;
				// Soma dos digitos
				$soma += $parcial[$i];
				if ($fator == $base) {
					// restaura fator de multiplicacao para 2
					$fator = 1;
				}
				$fator++;
			}

			/* Calculo do modulo 11 */
			if ($r == 0) {
				$soma *= 10;
				$digito = $soma % 11;
				if ($digito == 10) {
					$digito = 0;
				}
				return $digito;
			} elseif ($r == 1){
				$resto = $soma % 11;
				return $resto;
			}
		}

		/*
		#################################################
		FUNcaO DO MoDULO 11 RETIRADA DO PHPBOLETO

		MODIFIQUEI ALGUMAS COISAS...

		ESTA FUNcaO PEGA O DiGITO VERIFICADOR:

		NOSSONUMERO
		AGENCIA
		CONTA
		CAMPO 4 DA LINHA DIGITaVEL
		#################################################
		*/				
		function modulo_11_sicoob($num, $base=9, $r=0) {
			$soma = 0;
			$fator = 2; 
			for ($i = strlen($num); $i > 0; $i--) {
				$numeros[$i] = substr($num,$i-1,1);
				$parcial[$i] = (int)$numeros[$i] * $fator;
				$soma += $parcial[$i];
				if ($fator == $base) {
					$fator = 1;
				}
				$fator++;
			}
			if ($r == 0) {
				$soma *= 10;
				$digito = $soma % 11;
				
				//corrigido
				if ($digito == 10) {
					$digito = "X";
				}

				/*
				alterado por mim, Daniel Schultz

				Vamos explicar:

				O modulo 11 so gera os digitos verificadores do nossonumero,
				agencia, conta e digito verificador com codigo de barras (aquele que fica sozinho e triste na linha digitavel)
				so que e foi um rolo...pq ele nao podia resultar em 0, e o pessoal do phpboleto se esqueceu disso...
				
				No BB, os digitos verificadores podem ser X ou 0 (zero) para agencia, conta e nosso numero,
				mas nunca pode ser X ou 0 (zero) para a linha digitavel, justamente por ser totalmente numerica.

				Quando passamos os dados para a funcao, fica assim:

				Agencia = sempre 4 digitos
				Conta = ate 8 digitos
				Nosso numero = de 1 a 17 digitos

				A unica variavel que passa 17 digitos e a da linha digitada, justamente por ter 43 caracteres

				Entao vamos definir ai embaixo o seguinte...

				se (strlen($num) == 43) { nao deixar dar digito X ou 0 }
				*/
				
				if (strlen($num) == "43") {
					//entao estamos checando a linha digitavel
					if ($digito == "0" or $digito == "X" or $digito > 9) {
							$digito = 1;
					}
				}
				return $digito;
			} 
			elseif ($r == 1){
				$resto = $soma % 11;
				return $resto;
			}
		}

		function modulo_11_caixa($num, $base=9, $r=0) {
			$soma = 0;
			$fator = 2; 
			for ($i = strlen($num); $i > 0; $i--) {
				$numeros[$i] = substr($num,$i-1,1);
				$parcial[$i] = $numeros[$i] * $fator;
				$soma += $parcial[$i];
				if ($fator == $base) {
					$fator = 1;
				}
				$fator++;
			}
			if ($r == 0) {
				$soma *= 10;
				$digito = $soma % 11;
				
				//corrigido
				if ($digito == 10) {
					$digito = "0";
				}

				if (strlen($num) == "43") {
					//entao estamos checando a linha digitavel
					if ($digito == "0" or $digito == "X" or $digito > 9) {
							$digito = 1;
					}
				}
				return $digito;
			} 
			elseif ($r == 1){
				$resto = $soma % 11;
				return $resto;
			}
		}
	
		/**
		* Boleto::modulo_11_bb()
		* PARA BOLETO DO BB
		* @return
		*/		
	function modulo_11_bb($num, $base=9, $r=0) {
		$soma = 0;
		$fator = 2; 
		for ($i = strlen($num); $i > 0; $i--) {
			$numeros[$i] = substr($num,$i-1,1);
			$parcial[$i] = $numeros[$i] * $fator;
			$soma += $parcial[$i];
			if ($fator == $base) {
				$fator = 1;
			}
			$fator++;
		}
		if ($r == 0) {
			$soma *= 10;
			$digito = $soma % 11;
			
			//corrigido
			if ($digito == 10) {
				$digito = "X";
			}

			/*
			alterado por mim, Daniel Schultz

			Vamos explicar:

			O m�dulo 11 s� gera os digitos verificadores do nossonumero,
			agencia, conta e digito verificador com codigo de barras (aquele que fica sozinho e triste na linha digit�vel)
			s� que � foi um rolo...pq ele nao podia resultar em 0, e o pessoal do phpboleto se esqueceu disso...
			
			No BB, os d�gitos verificadores podem ser X ou 0 (zero) para agencia, conta e nosso numero,
			mas nunca pode ser X ou 0 (zero) para a linha digit�vel, justamente por ser totalmente num�rica.

			Quando passamos os dados para a fun��o, fica assim:

			Agencia = sempre 4 digitos
			Conta = at� 8 d�gitos
			Nosso n�mero = de 1 a 17 digitos

			A unica vari�vel que passa 17 digitos � a da linha digitada, justamente por ter 43 caracteres

			Entao vamos definir ai embaixo o seguinte...

			se (strlen($num) == 43) { n�o deixar dar digito X ou 0 }
			*/
			
			if (strlen($num) == "43") {
				//ent�o estamos checando a linha digit�vel
				if ($digito == "0" or $digito == "X" or $digito > 9) {
						$digito = 1;
				}
			}
			return $digito;
		} 
		elseif ($r == 1){
			$resto = $soma % 11;
			return $resto;
		}
	}
	  
		/**
		* Boleto::monta_linha_digitavel()
		* PARA BOLETOS DA CAIXA
		* @return
		*/
		public function monta_linha_digitavel($codigo) {
			// Posi��o 	Conte�do
			// 1 a 3    N�mero do banco
			// 4        C�digo da Moeda - 9 para Real
			// 5        Digito verificador do C�digo de Barras
			// 6 a 9   Fator de Vencimento
			// 10 a 19 Valor (8 inteiros e 2 decimais)
			// 20 a 44 Campo Livre definido por cada banco (25 caracteres)

			// 1. Campo - composto pelo c�digo do banco, c�digo da mo�da, as cinco primeiras posi��es
			// do campo livre e DV (modulo10) deste campo
			$p1 = substr($codigo, 0, 4);
			$p2 = substr($codigo, 19, 5);
			$p3 = $this->modulo_10("$p1$p2");
			$p4 = "$p1$p2$p3";
			$p5 = substr($p4, 0, 5);
			$p6 = substr($p4, 5);
			$campo1 = "$p5.$p6";

			// 2. Campo - composto pelas posicoes 6 a 15 do campo livre
			// e livre e DV (modulo10) deste campo
			$p1 = substr($codigo, 24, 10);
			$p2 = $this->modulo_10($p1);
			$p3 = "$p1$p2";
			$p4 = substr($p3, 0, 5);
			$p5 = substr($p3, 5);
			$campo2 = "$p4.$p5";

			// 3. Campo composto pelas posicoes 16 a 25 do campo livre
			// e livre e DV (modulo10) deste campo
			$p1 = substr($codigo, 34, 10);
			$p2 = $this->modulo_10($p1);
			$p3 = "$p1$p2";
			$p4 = substr($p3, 0, 5);
			$p5 = substr($p3, 5);
			$campo3 = "$p4.$p5";

			// 4. Campo - digito verificador do codigo de barras
			$campo4 = substr($codigo, 4, 1);

			// 5. Campo composto pelo fator vencimento e valor nominal do documento, sem
			// indicacao de zeros a esquerda e sem edicao (sem ponto e virgula). Quando se
			// tratar de valor zerado, a representacao deve ser 000 (tres zeros).
			$p1 = substr($codigo, 5, 4);
			$p2 = substr($codigo, 9, 10);
			$campo5 = "$p1$p2";

			return "$campo1 $campo2 $campo3 $campo4 $campo5"; 
		}

		/**
		* Boleto::monta_linha_digitavel_caixa()
		* PARA BOLETOS DA CAIXA
		* @return
		*/
		public function monta_linha_digitavel_caixa($codigo) {
			// Posicao 	Conteudo
			// 1 a 3    Numero do banco
			// 4        Codigo da Moeda - 9 para Real
			// 5        Digito verificador do Codigo de Barras
			// 6 a 9   Fator de Vencimento
			// 10 a 19 Valor (8 inteiros e 2 decimais)
			// 20 a 44 Campo Livre definido por cada banco (25 caracteres)

			// 1. Campo - composto pelo codigo do banco, codigo da moeda, as cinco primeiras posicoes
			// do campo livre e DV (modulo10) deste campo
			$p1 = substr($codigo, 0, 4);
			$p2 = substr($codigo, 19, 5);
			$p3 = $this->modulo_10("$p1$p2");
			$p4 = "$p1$p2$p3";
			$p5 = substr($p4, 0, 5);
			$p6 = substr($p4, 5);
			$campo1 = "$p5.$p6";

			// 2. Campo - composto pelas posicoes 6 a 15 do campo livre
			// e livre e DV (modulo10) deste campo
			$p1 = substr($codigo, 24, 10);
			$p2 = $this->modulo_10($p1);
			$p3 = "$p1$p2";
			$p4 = substr($p3, 0, 5);
			$p5 = substr($p3, 5);
			$campo2 = "$p4.$p5";

			// 3. Campo composto pelas posicoes 16 a 25 do campo livre
			// e livre e DV (modulo10) deste campo
			$p1 = substr($codigo, 34, 10);
			$p2 = $this->modulo_10($p1);
			$p3 = "$p1$p2";
			$p4 = substr($p3, 0, 5);
			$p5 = substr($p3, 5);
			$campo3 = "$p4.$p5";

			// 4. Campo - digito verificador do codigo de barras
			$campo4 = substr($codigo, 4, 1);

			// 5. Campo composto pelo fator vencimento e valor nominal do documento, sem
			// indicacao de zeros a esquerda e sem edicao (sem ponto e virgula). Quando se
			// tratar de valor zerado, a representacao deve ser 000 (tres zeros).
			$p1 = substr($codigo, 5, 4);
			$p2 = substr($codigo, 9, 10);
			$campo5 = "$p1$p2";

			return "$campo1 $campo2 $campo3 $campo4 $campo5"; 
		}
		
		/**
		* Boleto::monta_linha_digitavel()
		* PARA BOLETOS DO SICOOB
		* @return
		*/				
		function monta_linha_digitavel_sicoob($linha) {
			// Posicao 	Conteudo
			// 1 a 3    Numero do banco
			// 4        Codigo da Moeda - 9 para Real
			// 5        Digito verificador do Codigo de Barras
			// 6 a 19   Valor (12 inteiros e 2 decimais)
			// 20 a 44  Campo Livre definido por cada banco

			// 1. Campo - composto pelo codigo do banco, codigo da moeda, as cinco primeiras posicoes
			// do campo livre e DV (modulo10) deste campo
			$p1 = substr($linha, 0, 4);
			$p2 = substr($linha, 19, 5);
			$p3 = $this->modulo_10_sicoob("$p1$p2");
			$p4 = "$p1$p2$p3";
			$p5 = substr($p4, 0, 5);
			$p6 = substr($p4, 5);
			$campo1 = "$p5.$p6";

			// 2. Campo - composto pelas posicoes 6 a 15 do campo livre
			// e livre e DV (modulo10) deste campo
			$p1 = substr($linha, 24, 10);
			$p2 = $this->modulo_10_sicoob($p1);
			$p3 = "$p1$p2";
			$p4 = substr($p3, 0, 5);
			$p5 = substr($p3, 5);
			$campo2 = "$p4.$p5";

			// 3. Campo composto pelas posicoes 16 a 25 do campo livre
			// e livre e DV (modulo10) deste campo
			$p1 = substr($linha, 34, 10);
			$p2 = $this->modulo_10_sicoob($p1);
			$p3 = "$p1$p2";
			$p4 = substr($p3, 0, 5);
			$p5 = substr($p3, 5);
			$campo3 = "$p4.$p5";

			// 4. Campo - digito verificador do codigo de barras
			$campo4 = substr($linha, 4, 1);

			// 5. Campo composto pelo valor nominal pelo valor nominal do documento, sem
			// indicacao de zeros a esquerda e sem edicao (sem ponto e virgula). Quando se
			// tratar de valor zerado, a representacao deve ser 000 (tres zeros).
			$campo5 = substr($linha, 5, 14);

			return "$campo1 $campo2 $campo3 $campo4 $campo5"; 
		}
		
		/**
		* Boleto::monta_linha_digitavel_santander()
		* PARA BOLETOS DO SANTANDER
		* @return
		*/
		function monta_linha_digitavel_santander($codigo) 
		{ 
			// Posicao 	Conteudo
			// 1 a 3    Numero do banco
			// 4        Codigo da Moeda - 9 para Real ou 8 - outras moedas
			// 5        Fixo "9'
			// 6 a 9    PSK - codigo cliente (4 primeiros digitos)
			// 10 a 12  Restante do PSK (3 digitos)
			// 13 a 19  7 primeiros digitos do Nosso Numero
			// 20 a 25  Restante do Nosso numero (8 digitos) - total 13 (incluindo digito verificador)
			// 26 a 26  IOS
			// 27 a 29  Tipo Modalidade Carteira
			// 30 a 30  Digito verificador do codigo de barras
			// 31 a 34  Fator de vencimento (qtdade de dias desde 07/10/1997 ate a data de vencimento)
			// 35 a 44  Valor do titulo
			
			// 1. Primeiro Grupo - composto pelo codigo do banco, codigo da moeda, Valor Fixo "9"
			// e 4 primeiros digitos do PSK (codigo do cliente) e DV (modulo10) deste campo
			$campo1 = substr($codigo,0,3) . substr($codigo,3,1) . substr($codigo,19,1) . substr($codigo,20,4);
			$campo1 = $campo1 . $this->modulo_10($campo1);
		    $campo1 = substr($campo1, 0, 5).'.'.substr($campo1, 5);


			
			// 2. Segundo Grupo - composto pelas 3 ultimas posicoes do PSK e 7 primeiros digitos do Nosso Numero
			// e DV (modulo10) deste campo
			$campo2 = substr($codigo,24,10);
			$campo2 = $campo2 . $this->modulo_10($campo2);
		    $campo2 = substr($campo2, 0, 5).'.'.substr($campo2, 5);


			// 3. Terceiro Grupo - Composto por : Restante do Nosso Numero (6 digitos), IOS, Modalidade da Carteira
			// e DV (modulo10) deste campo
			$campo3 = substr($codigo,34,10);
			$campo3 = $campo3 . $this->modulo_10($campo3);
		    $campo3 = substr($campo3, 0, 5).'.'.substr($campo3, 5);

			// 4. Campo - digito verificador do codigo de barras
			$campo4 = substr($codigo, 4, 1);
			
			// 5. Campo composto pelo fator vencimento e valor nominal do documento, sem
			// indicacao de zeros a esquerda e sem edicao (sem ponto e virgula). Quando se
			// tratar de valor zerado, a representacao deve ser 0000000000 (dez zeros).
			$campo5 = substr($codigo, 5, 4) . substr($codigo, 9, 10);
			
			return "$campo1 $campo2 $campo3 $campo4 $campo5"; 
		}
		
		/**
		* Boleto::monta_linha_digitavel_itau()
		* PARA BOLETOS DO ITAU
		* @return
		*/
		function monta_linha_digitavel_itau($codigo) 
		{
			// campo 1
			$banco    = substr($codigo,0,3);
			$moeda    = substr($codigo,3,1);
			$ccc      = substr($codigo,19,3);
			$ddnnum   = substr($codigo,22,2);
			$dv1      = $this->modulo_10($banco.$moeda.$ccc.$ddnnum);
			// campo 2
			$resnnum  = substr($codigo,24,6);
			$dac1     = substr($codigo,30,1);//modulo_10($agencia.$conta.$carteira.$nnum);
			$dddag    = substr($codigo,31,3);
			$dv2      = $this->modulo_10($resnnum.$dac1.$dddag);
			// campo 3
			$resag    = substr($codigo,34,1);
			$contadac = substr($codigo,35,6); //substr($codigo,35,5).modulo_10(substr($codigo,35,5));
			$zeros    = substr($codigo,41,3);
			$dv3      = $this->modulo_10($resag.$contadac.$zeros);
			// campo 4
			$dv4      = substr($codigo,4,1);
			// campo 5
			$fator    = substr($codigo,5,4);
			$valor    = substr($codigo,9,10);
			
			$campo1 = substr($banco.$moeda.$ccc.$ddnnum.$dv1,0,5) . '.' . substr($banco.$moeda.$ccc.$ddnnum.$dv1,5,5);
			$campo2 = substr($resnnum.$dac1.$dddag.$dv2,0,5) . '.' . substr($resnnum.$dac1.$dddag.$dv2,5,6);
			$campo3 = substr($resag.$contadac.$zeros.$dv3,0,5) . '.' . substr($resag.$contadac.$zeros.$dv3,5,6);
			$campo4 = $dv4;
			$campo5 = $fator.$valor;
			
			return "$campo1 $campo2 $campo3 $campo4 $campo5"; 
		}
	  
		/**
		* Boleto::geraCodigoBanco()
		* @return
		*/
		public function geraCodigoBanco($numero) 
		{
			$parte1 = substr($numero, 0, 3);
			$parte2 = $this->modulo_11($parte1);
			return $parte1 . "-" . $parte2;
		}

		/**
		* Boleto::geraCodigoBanco_caixa()
		* PARA BOLETO DO SICOOB
		* @return
		*/
		public function geraCodigoBanco_caixa($numero) 
		{
			$parte1 = substr($numero, 0, 3);
			$parte2 = $this->modulo_11_caixa($parte1);
			return $parte1 . "-" . $parte2;
		}
	  
		/**
		* Boleto::geraCodigoBanco_sicoob()
		* PARA BOLETO DO SICOOB
		* @return
		*/
		public function geraCodigoBanco_sicoob($numero) 
		{
			$parte1 = substr($numero, 0, 3);
			$parte2 = $this->modulo_11_sicoob($parte1);
			return $parte1 . "-" . $parte2;
		}
	  
		/**
		* Boleto::geraCodigoBanco_bb()
		* PARA BOLETO DO BB
		* @return
		*/
		public function geraCodigoBanco_bb($numero) 
		{
			$parte1 = substr($numero, 0, 3);
			$parte2 = $this->modulo_11_bb($parte1);
			return $parte1 . "-" . $parte2;
		}
	  
	  /**
       * Boleto::processarBoleto()
       * SICOOB
       * @return
       */
      public function processarBoletoSicoob()
      {	
          if (empty(Filter::$msgs)) {
				foreach($_POST as $nome_campo => $valor){
					$valida = strpos($nome_campo, "tmpname");
					if($valida) {
						$ponteiro = fopen (UPLOADS.$valor, "r");
						if ($ponteiro == false) die(lang('FORM_ERROR13'));
						while (!feof ($ponteiro)) {
							$linha = fgets($ponteiro, 9999);
							$linha = str_replace("\"","",$linha);
							$t_u_segmento = substr($linha,13,1);//Segmento T ou U
							if($t_u_segmento == 'T'){
								$t_id_nosso_numero = substr($linha,37,10);
								$t_documento = substr($linha,58,15);
								$u_dt_vencimento = substr($linha,77,4).'-'.substr($linha,75,2).'-'.substr($linha,73,2);
								$t_empresa = substr($linha,148,40);
							}
							if($t_u_segmento == 'U'){
								$t_id_nosso_numero = (int) $t_id_nosso_numero;
								$id_controle = (int)(substr($t_id_nosso_numero,0,-1));
								$id_controle -= 2200000;
								$u_v_pago = substr($linha,77,13).".".substr($linha,90,2);
								$u_v_liquido = substr($linha,92,13).".".substr($linha,105,2);
								$u_dt_ocorencia = substr($linha,141,4).'-'.substr($linha,139,2).'-'.substr($linha,137,2);
								$u_dt_credito = substr($linha,149,4).'-'.substr($linha,147,2).'-'.substr($linha,145,2);
								// echo "[$t_id_nosso_numero][$u_v_pago][$u_dt_ocorencia]";
								$v = floatval($u_v_pago);
								if ($v > 0) {
									$data = array(
										'id_controle' => $id_controle, 
										'empresa' => $t_empresa, 
										'nosso_numero' => $t_id_nosso_numero, 
										'documento' => $t_documento, 
										'data_vencimento' => $u_dt_vencimento, 
										'data_pagamento' => $u_dt_ocorencia, 
										'data_credito' => $u_dt_credito, 
										'valor_pago' => $u_v_pago, 
										'valor_liquido' => $u_v_liquido, 
										'usuario' => session('nomeusuario'),
										'data' => "NOW()"
									);
									self::$db->insert("extrato_boletos", $data);
								}
							}
						}
						fclose ($ponteiro);
					}
				}
			  $this->atualizaBoletos();
              $message = lang('ARQUIVO_BOLETO');
			  Filter::msgOk($message, "index.php?do=extrato&acao=arquivoboletos");
			} else
              print Filter::msgStatus();
		}
	  
	  /**
       * Boleto::processarBoleto()
       * BANCO DO BRASIL
       * @return
       */
      public function processarBoleto()
      {	
          if (empty(Filter::$msgs)) {
				foreach($_POST as $nome_campo => $valor){
					$valida = strpos($nome_campo, "tmpname");
					if($valida) {
						$ponteiro = fopen (UPLOADS.$valor, "r");
						if ($ponteiro == false) die(lang('FORM_ERROR13'));
						while (!feof ($ponteiro)) {
							$linha = fgets($ponteiro, 9999);
							$linha = str_replace("\"","",$linha);
							$t_u_segmento = substr($linha,13,1);//Segmento T ou U
							if($t_u_segmento == 'T'){
								$t_id_nosso_numero = substr($linha,44,10);
								$t_documento = substr($linha,58,15);
								$u_dt_vencimento = substr($linha,77,4).'-'.substr($linha,75,2).'-'.substr($linha,73,2);
								$t_empresa = substr($linha,148,40);
							}
							if($t_u_segmento == 'U'){
								$id_controle = (int) $t_id_nosso_numero;
								$t_id_nosso_numero = (int) $t_id_nosso_numero;
								$u_v_pago = substr($linha,77,13).".".substr($linha,90,2);
								$u_v_liquido = substr($linha,92,13).".".substr($linha,105,2);
								$u_dt_ocorencia = substr($linha,141,4).'-'.substr($linha,139,2).'-'.substr($linha,137,2);
								$u_dt_credito = substr($linha,149,4).'-'.substr($linha,147,2).'-'.substr($linha,145,2);
								// echo "[$t_id_nosso_numero][$u_v_pago][$u_dt_ocorencia]";
								$v = floatval($u_v_pago);
								if ($v > 0) {
									$data = array(
										'id_controle' => $id_controle, 
										'empresa' => $t_empresa, 
										'nosso_numero' => $t_id_nosso_numero, 
										'documento' => $t_documento, 
										'data_vencimento' => $u_dt_vencimento, 
										'data_pagamento' => $u_dt_ocorencia, 
										'data_credito' => $u_dt_credito, 
										'valor_pago' => $u_v_pago, 
										'valor_liquido' => $u_v_liquido, 
										'usuario' => session('nomeusuario'),
										'data' => "NOW()"
									);
									self::$db->insert("extrato_boletos", $data);
								}
							}
						}
						fclose ($ponteiro);
					}
				}
			  $this->atualizaBoletos();
              $message = lang('ARQUIVO_BOLETO');
			  Filter::msgOk($message, "index.php?do=extrato&acao=arquivoboletos");
			} else
              print Filter::msgStatus();
		}

	/**
      * Boleto::processarBoletoCaixa()
      * CAIXA ECONOMICA FEDERAL
      * @return
      */
    public function processarBoletoCaixa()
    {	
		$t_id_nosso_numero=0;
		$t_documento=0;
		$u_dt_vencimento=0;
		$t_empresa='';
        if (empty(Filter::$msgs)) {
			foreach($_POST as $nome_campo => $valor){
				$valida = strpos($nome_campo, "tmpname");
				if($valida) {
					$ponteiro = fopen (UPLOADS.$valor, "r");
					if ($ponteiro == false) die(lang('FORM_ERROR13'));
					while (!feof ($ponteiro)) {
						$linha = fgets($ponteiro, 9999);
						$linha = str_replace("\"","",$linha);
						$t_u_segmento = substr($linha,13,1);//Segmento T ou U
						if($t_u_segmento == 'T'){
							$t_id_nosso_numero = substr($linha,39,17);
							$t_documento = substr($linha,58,15);
							$u_dt_vencimento = substr($linha,77,4).'-'.substr($linha,75,2).'-'.substr($linha,73,2);
							$t_empresa = substr($linha,148,40);
						}
						else
						if($t_u_segmento == 'U' && isset($t_id_nosso_numero) && !empty($t_id_nosso_numero)){
							$id_controle = (int) $t_id_nosso_numero;
							if (substr($t_id_nosso_numero,0,5)=='14123')
								$id_controle -= 14123000000000000;
							else if (substr($t_id_nosso_numero,0,5)=='14000')
								$id_controle -= 14000000000000000;
							$t_id_nosso_numero = (int) $t_id_nosso_numero;
							$u_v_pago = substr($linha,77,13).".".substr($linha,90,2);
							$u_v_liquido = substr($linha,92,13).".".substr($linha,105,2);
							$u_dt_ocorencia = substr($linha,141,4).'-'.substr($linha,139,2).'-'.substr($linha,137,2);
							$u_dt_credito = substr($linha,149,4).'-'.substr($linha,147,2).'-'.substr($linha,145,2);
							$v = (isset($u_v_pago) && !empty($u_v_pago)) ? floatval($u_v_pago) : 0;
							if ($v > 0) {
								$data = array(
									'id_controle' => $id_controle, 
									'empresa' => $t_empresa, 
									'nosso_numero' => $t_id_nosso_numero, 
									'documento' => $t_documento, 
									'data_vencimento' => $u_dt_vencimento, 
									'data_pagamento' => $u_dt_ocorencia, 
									'data_credito' => $u_dt_credito, 
									'valor_pago' => floatval($u_v_pago), 
									'valor_liquido' => floatval($u_v_liquido), 
									'usuario' => session('nomeusuario'),
									'data' => "NOW()"
								);
								self::$db->insert("extrato_boletos", $data);
							}
						}
					}
					fclose ($ponteiro);
				}
			}
			$this->atualizaBoletos();
        	$message = lang('ARQUIVO_BOLETO');
			Filter::msgOk($message, "index.php?do=extrato&acao=arquivoboletos");
		} else
            print Filter::msgStatus();
	}
	  
	  /**
       * Boleto::processarBoletoItau()
       * ITAU
       * @return
       */
      public function processarBoletoItau()
      {	
          if (empty(Filter::$msgs)) {
				foreach($_POST as $nome_campo => $valor){
					$valida = strpos($nome_campo, "tmpname");
					if($valida) {
						$ponteiro = fopen (UPLOADS.$valor, "r");
						if ($ponteiro == false) die(lang('FORM_ERROR13'));
						while (!feof ($ponteiro)) {
							$linha = fgets($ponteiro, 9999);
							$linha = str_replace("\"","",$linha);
							$t_u_segmento = substr($linha,13,1);//Segmento T ou U
							if($t_u_segmento == 'T'){
								$t_id_nosso_numero = substr($linha,44,10);
								$t_documento = substr($linha,58,15);
								$u_dt_vencimento = substr($linha,77,4).'-'.substr($linha,75,2).'-'.substr($linha,73,2);
								$t_empresa = substr($linha,148,40);
							}
							if($t_u_segmento == 'U'){
								$t_id_nosso_numero = (int) $t_id_nosso_numero;
								$id_controle = (int)(substr($t_id_nosso_numero,0,-1));
								$u_v_pago = substr($linha,77,13).".".substr($linha,90,2);
								$u_v_liquido = substr($linha,92,13).".".substr($linha,105,2);
								$u_dt_ocorencia = substr($linha,141,4).'-'.substr($linha,139,2).'-'.substr($linha,137,2);
								$u_dt_credito = substr($linha,149,4).'-'.substr($linha,147,2).'-'.substr($linha,145,2);
								// echo "[$id_controle][$t_id_nosso_numero][$u_v_pago][$u_dt_ocorencia]";
								$v = floatval($u_v_pago);
								if ($v > 0) {
									$data = array(
										'id_controle' => $id_controle, 
										'empresa' => $t_empresa, 
										'nosso_numero' => $t_id_nosso_numero, 
										'documento' => $t_documento, 
										'data_vencimento' => $u_dt_vencimento, 
										'data_pagamento' => $u_dt_ocorencia, 
										'data_credito' => $u_dt_credito, 
										'valor_pago' => $u_v_pago, 
										'valor_liquido' => $u_v_liquido, 
										'usuario' => session('nomeusuario'),
										'data' => "NOW()"
									);
									self::$db->insert("extrato_boletos", $data);
								}
							}
						}
						fclose ($ponteiro);
					}
				}
			  $this->atualizaBoletos();
              $message = lang('ARQUIVO_BOLETO');
			  Filter::msgOk($message, "index.php?do=extrato&acao=arquivoboletos");
			} else
              print Filter::msgStatus();
		}
	  
	  /**
       * Boleto::atualizaBoletos()
       * 
       * @param 
       * @return
       */
	  public function atualizaBoletos()
      {
          $id_banco = getValue('id', 'banco', 'taxa_boleto > 0');
		  $sql = "SELECT b.id, b.id_controle, b.nosso_numero, b.documento, b.data_pagamento, b.valor_pago, b.data_credito, r.id_banco " 
		  . "\n FROM extrato_boletos as b, receita as r" 
		  . "\n WHERE r.pago = 0 and b.id_controle = r.id and r.remessa = 1";
          $row = self::$db->fetch_all($sql);
		  if($row)
		  {
			foreach ($row as $exrow)
			{
				if($exrow->data_pagamento != '0000-00-00') {
					$data_controle = array(
						'pago' => 1,
						'retorno' => 1,
						'data_retorno' => "NOW()",
						'valor_pago' => $exrow->valor_pago,
						'data_recebido' => $exrow->data_pagamento,
						'usuario' => session('nomeusuario'),
						'data' => "NOW()"
					);
					if($exrow->id_banco == 0) {
						$data_controle['id_banco'] = $id_banco;
					}
					self::$db->update("receita", $data_controle, "id=".$exrow->id_controle);
				}				
			}
		  }
      }
	  
	  /**
       * Boleto::getBoletos()
	   *
       * @return
       */
	  public function getBoletos()
      {
          $sql = "SELECT r.id, r.data_recebido, r.descricao, r.valor_pago, r.pago, b.id_controle, b.empresa, b.nosso_numero, b.documento, b.data_vencimento, b.data_pagamento, b.valor_liquido, b.data_credito as data_banco, c.nome " 
		  . "\n FROM extrato_boletos as b" 
		  . "\n LEFT JOIN receita as r ON r.id = b.id_controle and r.remessa = 1 " 
		  . "\n LEFT JOIN cadastro as c ON c.id = r.id_cadastro" 
		  . "\n ORDER BY pago, r.id DESC ";
          $row = self::$db->fetch_all($sql);

          return ($row) ? $row : 0;
      }
	  
      /**
       * Boleto::emailBoleto()
       * 
       * @return
       */
      public function emailBoleto()
      {
		  if (empty($_POST['id']))
              Filter::$msgs['id'] = lang('MSG_ERRO_BOLETO');

          if (empty(Filter::$msgs)) {
			  
			$id_pagamento = post('id');
			$todos = 1;
			$enviar = false;
			$id_cadastro = getValue("id_cadastro", "nota_fiscal", "id=".$id_pagamento);
			$row_cadastro = Core::getRowById("cadastro", $id_cadastro);
			$dataenvio = date('d/m/Y');
			if($row_cadastro->email or $row_cadastro->email2) {	
				$email = ($row_cadastro->email) ? $row_cadastro->email : $row_cadastro->email2;
				$contato = ($row_cadastro->contato) ? $row_cadastro->contato : $row_cadastro->nome;
				$row_config = Core::configuracoes();
				$site_url = $row_config->site_url;
				$site_sistema = $row_config->site_sistema;
				$site_email = $row_config->site_email;
				$site_empresa = $row_config->empresa;
				$mailer = $row_config->mailer;
				$smtp_host = $row_config->smtp_host;
				$smtp_user = $row_config->smtp_user;
				$smtp_pass = $row_config->smtp_pass;
				$smtp_port = $row_config->smtp_port;
			  
				ob_start();
				$codigo = $faturamento->getBancoBoletos($id_pagamento, $todos);
				switch ($codigo) {
					case '756':
						$enviar = true;
						require_once (BASEPATH . 'boleto_sicoob_email.php');
						break;
					case '001':
						$enviar = true;
						require_once (BASEPATH . 'boleto_bb_email.php');
						break;
					default:
						echo lang('MSG_ERRO_BOLETO_BANCO_NAO');
						break;
				}
				$html_message = ob_get_contents();
				ob_end_clean();
				if($enviar) {
					include(BASEPATH ."lib/phpmailer/class.phpmailer.php");
					include(BASEPATH ."lib/phpmailer/class.smtp.php"); // note, this is optional - gets called from main class if not already loaded

					$mail             = new PHPMailer();

					$mail->IsSMTP();
					$mail->SMTPAuth   = true;       
					$mail->SMTPSecure = "ssl";  
					$mail->Host       = $smtp_host;  
					$mail->Port       = $smtp_port;         
					$mail->Username   = $smtp_user;         
					$mail->Password   = $smtp_pass;    
						
					$mail->CharSet = 'utf-8';

					$mail->AddAddress($email,$contato);
					$mail->addReplyTo($site_email, $site_empresa);
					$mail->From       = $site_email;
					$mail->FromName   = $site_empresa;
					$mail->Subject    = lang('BOLETO_TITULO')." - ".$core->empresa;
					$msg_body = lang('BOLETO_MSG');
					$mail->AltBody    = $msg_body;
					$mail->WordWrap   = 50; // set word wrap

					$mail->IsHTML(true); // send as HTML

					$mail->MsgHTML($html_message); //Colocar a fun��o de utf8_decode faz aparecer uma "?" na mensagem enviada.
					if(!$mail->Send()) {
					  Filter::msgAlert(str_replace("[ERRO]", $mail->ErrorInfo, lang('BOLETO_NOEMAIL')));
					} else {
						$message = lang('BOLETO_EMAIL_OK');
						Filter::msgOk($message); 
					}
				}
			} else 
				Filter::msgAlert(lang('MSG_ERRO_EMAIL_CADASTRO'));
          } else
              print Filter::msgStatus();
      }
	  
	  /**
	   * getBancosBoleto()
	   * 
	   * @return
	   */
		public function getBancosBoleto () {
			$sql = " SELECT id, nome_banco, codigo_banco, arquivo_boleto FROM banco_boleto WHERE inativo = 0 ";
			$row = self::$db->fetch_all($sql);
			return ($row) ? $row : 0;
		}
      
  }
?>
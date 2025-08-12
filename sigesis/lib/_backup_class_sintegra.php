<?php

    if (!defined("_VALID_PHP"))
    die('Acesso direto a esta classe não é permitido.');

    class Sintegra {

        private $endpoint = 'https://api.enotasgw.com.br/v2/empresas/';
        private $mes_ano;
        private $inventario_fiscal;
        private $codigo_empresa;
        private $aliquota_empresa;
        private $contador;
        private $empresa;
        private $array_nfe;
        private $array_nfe_itens;
        private $array_nfc;
        private $array_estoque;
        private $registro10;
        private $registro11;
        private $registro50;
        private $countRegistro50 = 0;
        private $registro54;
        private $countRegistro54 = 0;
        private $registro61;
        private $countRegistro61 = 0;
        private $registro61R;
        private $countRegistro61R = 0;
        private $registro74;
        private $countRegistro74 = 0;
        private $registro75;
        private $countRegistro75 = 0;
        private $registro90;

        private function setEmpresa()
        {
            global $usuario;

            $this->codigo_empresa = getValue('enotas', 'empresa', 'id='.$usuario->idempresa);
            $this->aliquota_empresa = getValue('icms_normal_aliquota', 'empresa', 'id='.$usuario->idempresa);

            $url_empresa = [$this->endpoint . $this->codigo_empresa];

            $this->empresa = $this->getCurlMultiUrl($url_empresa)[0];
        }

        private function setContador()
        {
            global $usuario;
            $row_empresa = Core::getRowById('empresa', $usuario->idempresa);

            $this->contador = $row_empresa;
        }

        private function setNotaFiscal()
        {
            global $db;

            $sql_nfe = "SELECT n.id, c.cpf_cnpj, c.ie AS inscricao_estadual, n.data_entrada AS data_nota, c.estado AS uf, n.serie, n.numero,"
            . "\n sum(ni.valor_total) AS valor_total, sum(ni.icms_base) AS icms_base, sum(ni.icms_valor) AS icms_valor,"
            . "\n n.valor_outro, ni.cfop_entrada as cfop, n.cfop AS cfop_nota, ni.icms_percentual as icms_aliquota, 'T' as operacao"
            . "\n FROM nota_fiscal AS n"
            . "\n LEFT JOIN cadastro AS c ON c.id = n.id_cadastro"
            . "\n LEFT JOIN nota_fiscal_itens AS ni ON ni.id_nota = n.id"
            . "\n WHERE n.inativo = 0 AND n.operacao = 1 AND DATE_FORMAT(n.data_entrada, '%m/%Y') = '{$this->mes_ano}'"
            . "\n GROUP BY n.id, ni.cfop_entrada, ni.icms_percentual"
            . "\n UNION"
            . "\n SELECT n.id, c.cpf_cnpj, c.ie AS inscricao_estadual, n.data_emissao AS data_nota, c.estado AS uf, n.serie, n.numero,"
            . "\n sum(ni.valor_total) AS valor_total, sum(ni.icms_base) AS icms_base, sum(ni.icms_valor) AS icms_valor,"
            . "\n n.valor_outro, ni.cfop, n.cfop AS cfop_nota, ni.icms_percentual AS icms_aliquota, 'P' AS operacao"
            . "\n FROM nota_fiscal AS n"
            . "\n LEFT JOIN cadastro AS c ON c.id = n.id_cadastro"
            . "\n LEFT JOIN nota_fiscal_itens AS ni ON ni.id_nota = n.id"
            . "\n WHERE n.inativo = 0 AND n.operacao = 2 AND n.fiscal = 1 AND DATE_FORMAT(n.data_emissao, '%m/%Y') = '{$this->mes_ano}'"
            . "\n GROUP BY n.id, ni.cfop, ni.icms_percentual";
            $result_nfe = $db->fetch_all($sql_nfe);

            $this->array_nfe = $result_nfe;

            $sql_itens = "SELECT p.nome AS descricao, pf.nome AS descricao_fornecedor, IF(n.operacao = 1, ni.cfop_entrada, ni.cfop) AS cfop, n.cfop AS cfop_nota,"
            . "\n (CASE n.id WHEN @row_type THEN @row_number := @row_number + 1 ELSE @row_number := 1 AND @row_type := n.id END) AS numero_item,"
            . "\n n.cpf_cnpj, n.serie, n.numero, ni.icms_cst, ni.codigonota AS codigo, p.codigo AS codigo_produto, ni.ncm, p.ncm AS ncm_produto, ni.quantidade,"
            . "\n ni.valor_total, ni.valor_desconto, ni.unidade, p.unidade as unidade_produto, ni.icms_base, ni.icms_st_base, ni.ipi_valor, ni.icms_percentual AS icms_aliquota"
            . "\n FROM nota_fiscal_itens ni"
            . "\n INNER JOIN (SELECT @row_number := 0, @row_type := '') AS R"
            . "\n LEFT JOIN produto AS p ON p.id = ni.id_produto "
            . "\n LEFT JOIN produto_fornecedor AS pf ON pf.id = ni.id_produto_fornecedor "
            . "\n LEFT JOIN nota_fiscal AS n ON n.id = ni.id_nota "
            . "\n WHERE n.inativo = 0 AND ni.inativo = 0 AND"
            . "\n ((n.operacao = 1 AND DATE_FORMAT(n.data_entrada, '%m/%Y') = '{$this->mes_ano}') OR"
            . "\n (n.operacao = 2 AND n.fiscal = 1 AND DATE_FORMAT(n.data_emissao, '%m/%Y') = '{$this->mes_ano}')) ";
            $result_itens = $db->fetch_all($sql_itens);
            
            $this->array_nfe_itens = $result_itens;
            
        }

        private function setNotaFiscalConsumidor()
        {
            global $db;

            $sql_nfc = "SELECT v.id "
            . "\n FROM vendas as v"
            . "\n LEFT JOIN cadastro as c ON c.id = v.id_cadastro "
            . "\n LEFT JOIN empresa as e ON e.id = v.id_empresa "
            . "\n WHERE v.fiscal = 1 AND DATE_FORMAT(v.data_emissao, '%m/%Y') = '$this->mes_ano'";

            $result_nfc = $db->fetch_all($sql_nfc);

            $urls_nfc = [];
            foreach ($result_nfc as $nfc) {
                $urls_nfc[] = $this->endpoint.$this->codigo_empresa . '/nfc-e/nfc-' . $nfc->id;
            }

            $this->array_nfc = $this->getCurlMultiUrl($urls_nfc);
        }

        public function setEstoqueInventario()
        {
            global $db;

            $ano = explode('/', $this->mes_ano)[1] - 1;

            $sql_estoque = "SELECT p.codigo, p.nome, p.ncm, p.unidade, e.id_produto, SUM(e.quantidade) AS estoque, p.valor_custo" 
            . "\n FROM produto_estoque as e"
            . "\n LEFT JOIN produto as p ON p.id = e.id_produto "
            . "\n WHERE e.inativo = 0 AND YEAR(e.data) = {$ano} AND p.codigo IS NOT NULL AND p.codigo <> ''"
            . "\n GROUP BY e.id_produto"
            . "\n ORDER BY p.nome ASC";
            
            $this->array_estoque = $db->fetch_all($sql_estoque);
        }

        function getCurlMultiUrl(array $urls)
        {

            global $enotas_apikey;

            $header = [
                "Authorization: Basic $enotas_apikey",
                "accept: application/json"
            ];

            // array of curl handles
            $multiCurl = [];
            // data to be returned
            $result = [];
            // multi handle
            $mh = curl_multi_init();
            foreach ($urls as $i => $url) {
            // URL from which data will be fetched
                $fetchURL = $url;
                $multiCurl[$i] = curl_init();
                curl_setopt($multiCurl[$i], CURLOPT_URL,$fetchURL);
                curl_setopt($multiCurl[$i], CURLOPT_HTTPHEADER, $header);
                curl_setopt($multiCurl[$i], CURLOPT_RETURNTRANSFER,1);
                curl_multi_add_handle($mh, $multiCurl[$i]);
            }
            $index=null;
            do {
                curl_multi_exec($mh,$index);
            } while($index > 0);
            // get content and remove handles
            foreach($multiCurl as $k => $ch) {
                $result[$k] = json_decode(curl_multi_getcontent($ch), true);
                curl_multi_remove_handle($mh, $ch);
            }
            // close
            curl_multi_close($mh);

            return $result;
        }

        ///////////////////////////////
        // INICIO FUNÇÔES DE SUPORTE //
        ///////////////////////////////

        private function cleanNumber($numero)
        {
            $numero = number_format($numero, 2, '.', '');
            $numero = str_replace(['.'], '', $numero);
            
            return $numero;
        }

        private function cleanAmount($quantidade)
        {
            $quantidade = number_format($quantidade, 3, '.', '');
            $quantidade = str_replace('.', '', $quantidade);
            return $quantidade;
        }

        private function cleanString($string, $size = null, $reverse = false)
        {
            $string = str_replace(['(', ')', '-', ' ', '.'], '', $string);
            $string = trim(preg_replace('/\s+/', ' ', $string));
            if($size) $string = (!$reverse) ? substr($string, 0, $size) : substr($string, -$size);
            return $string;
        }

        private function cleanDate($data)
        {
            $data = date('Ymd', strtotime($data));
            return $data;
        }

        private function cleanText($text, $size = null, $reverse = false)
        {
            $text = trim($text, ' ');
            $text = trim(preg_replace('/\s+/', ' ', $text));
            if($size) $text = (!$reverse) ? substr($text, 0, $size) : substr($text, -$size);
            return $text;
        }

        private function firstDayMonth($mes_ano)
        {
            $mes_ano = explode('/', $mes_ano);
            $mes_ano = $mes_ano[1] . '-' . $mes_ano[0] . '-01';
            $mes_ano = $this->cleanDate($mes_ano);
            return $mes_ano;
        }

        private function lastDayMonth($mes_ano)
        {
            $mes_ano = explode('/', $mes_ano);
            $mes_ano = $mes_ano[1] . '-' . $mes_ano[0] . '-01';
            $mes_ano = date('Y-m-t', strtotime($mes_ano));
            $mes_ano = $this->cleanDate($mes_ano);
            return $mes_ano;
        }

        //////////////////////////////
        // INICIO SCHEMAS REGISTROS //
        //////////////////////////////

        private function schemaRegistro10()
        {
            $registro10 = '10';
            $registro10 .= $this->cleanString($this->empresa['cnpj'], 14);
            $registro10 .= str_pad($this->cleanString($this->empresa['inscricaoEstadual'], 14), 14);
            $registro10 .= str_pad($this->cleanText($this->empresa['razaoSocial'], 35), 35);
            $registro10 .= str_pad($this->cleanText($this->empresa['endereco']['cidade'], 30), 30);
            $registro10 .= $this->empresa['endereco']['uf'];
            $registro10 .= str_pad('', 10, '0', STR_PAD_LEFT);
            $registro10 .= $this->firstDayMonth($this->mes_ano);
            $registro10 .= $this->lastDayMonth($this->mes_ano);
            $registro10 .= 3; // Somente ultimo layout do manual do sintegra. $empresa['codigoDaIdentificacaoDoConvenio'];
            $registro10 .= 3; // Sem Interestaduais e ST. $empresa['codigoDaIdentificacaoDaNaturezaDasOperacoesInformadas'];
            $registro10 .= 1; // Normal, sem retificação. $empresa['codigoDaFinalidadeDoArquivoMagnetico'];
            $registro10 .= PHP_EOL;
            $this->registro10 = $registro10;
        }

        private function schemaRegistro11()
        {
            $registro11 = '11';
            $registro11 .= str_pad($this->contador->contabilidade_endereco, 34);
            $registro11 .= str_pad($this->contador->contabilidade_numero, 5, '0', STR_PAD_LEFT);
            $registro11 .= str_pad($this->contador->contabilidade_complemento, 22);
            $registro11 .= str_pad($this->contador->contabilidade_bairro, 15);
            $registro11 .= str_pad($this->cleanString($this->contador->contabilidade_cep), 8, '0', STR_PAD_LEFT);
            $registro11 .= str_pad($this->contador->contabilidade_nome_contato, 28);
            $registro11 .= str_pad($this->cleanString($this->contador->contabilidade_telefone), 12, '0', STR_PAD_LEFT);
            $registro11 .= PHP_EOL;

            $this->registro11 = $registro11;
        }

        private function schemaRegistro50()
        {
            $registro50 = '';
  
            // ENTRADA //

            foreach ($this->array_nfe as $notaFiscal) {
                $cfop = !empty($notaFiscal->cfop) ? $notaFiscal->cfop : $notaFiscal->cfop_nota;
                $registro50 .= '50';
                $registro50 .= str_pad($this->cleanString($notaFiscal->cpf_cnpj, 14), 14, '0', STR_PAD_LEFT);
                $registro50 .= (!empty($notaFiscal->inscricao_estadual)) ? str_pad($this->cleanString($notaFiscal->inscricao_estadual, 14), 14) : str_pad('ISENTO', 14);
                $registro50 .= $this->cleanDate($notaFiscal->data_nota);
                $registro50 .= $this->cleanString($notaFiscal->uf, 2);
                $registro50 .= '55'; // Modelo 55 para nota fiscal eletronica
                $registro50 .= str_pad($this->cleanString($notaFiscal->serie, 3), 3, '0', STR_PAD_LEFT);
                $registro50 .= str_pad($this->cleanString($notaFiscal->numero, 6, true), 6, '0', STR_PAD_LEFT);
                $registro50 .= str_pad($this->cleanString($cfop, 4), 4, '0', STR_PAD_LEFT);
                $registro50 .= $notaFiscal->operacao;
                $registro50 .= str_pad($this->cleanNumber($notaFiscal->valor_total), 13, '0', STR_PAD_LEFT);
                $registro50 .= str_pad($this->cleanNumber($notaFiscal->icms_base), 13, '0', STR_PAD_LEFT);
                $registro50 .= str_pad($this->cleanNumber($notaFiscal->icms_valor), 13, '0', STR_PAD_LEFT);
                $registro50 .= str_pad(0, 13, '0', STR_PAD_LEFT); // REVISAR str_pad($this->cleanNumber($notaFiscal->isentaOuNaoTributada), 13, '0', STR_PAD_LEFT);
                $registro50 .= str_pad(0, 13, '0', STR_PAD_LEFT); // REVISAR str_pad($this->cleanNumber($notaFiscal->outras), 13, '0', STR_PAD_LEFT);
                $registro50 .= str_pad($this->cleanNumber($notaFiscal->icms_aliquota), 4, '0', STR_PAD_LEFT);
                $registro50 .= 'N';
                // else if($notaFiscal->status == 'Cancelada') $registro50 .= 'S';
                // else if($notaFiscal->status == 'Negada' && substr($notaFiscal->motivoStatus, 0, 3) == "205") $registro50 .= '2';
                // else if($notaFiscal->status == 'Negada' && substr($notaFiscal->motivoStatus, 0, 3) == "206") $registro50 .= '4'; // Denegada 205, inutilizada 206
                $registro50 .= PHP_EOL;

                $this->countRegistro50++;

            }

            $this->registro50 = $registro50;
        }

        private function schemaRegistro54()
        {
            $registro54 = '';

            foreach ($this->array_nfe_itens as $item) {
                $cfop = !empty($item->cfop) ? $item->cfop : $item->cfop_nota;
                $codigo = !empty($item->codigo) ? $item->codigo : $item->codigo_produto;
                $registro54 .= '54';
                $registro54 .= str_pad($this->cleanString($item->cpf_cnpj), 14, '0', STR_PAD_LEFT);
                $registro54 .= '55'; // Modelo 55 para nota fiscal eletronica
                $registro54 .= str_pad($this->cleanString($item->serie, 3), 3, '0', STR_PAD_LEFT);
                $registro54 .= str_pad($this->cleanString($item->numero, 6, true), 6, '0', STR_PAD_LEFT);
                $registro54 .= str_pad($this->cleanString($cfop, 4), 4, '0', STR_PAD_LEFT);
                $registro54 .= str_pad($this->cleanString($item->icms_cst, 3, true), 3, '0', STR_PAD_LEFT);
                $registro54 .= str_pad($item->numero_item, 3, '0', STR_PAD_LEFT); // Revisar
                $registro54 .= str_pad($this->cleanText($codigo, 14), 14);
                $registro54 .= str_pad($this->cleanAmount($item->quantidade), 11, '0', STR_PAD_LEFT);
                $registro54 .= str_pad($this->cleanNumber($item->valor_total), 12, '0', STR_PAD_LEFT);
                $registro54 .= str_pad($this->cleanNumber($item->valor_desconto), 12, '0', STR_PAD_LEFT);
                $registro54 .= str_pad($this->cleanNumber($item->icms_base), 12, '0', STR_PAD_LEFT);
                $registro54 .= str_pad($this->cleanNumber($item->icms_st_base), 12, '0', STR_PAD_LEFT);
                $registro54 .= str_pad($this->cleanNumber($item->ipi_valor), 12, '0', STR_PAD_LEFT);
                $registro54 .= str_pad($this->cleanNumber($item->icms_aliquota), 4, '0', STR_PAD_LEFT);
                $registro54 .= PHP_EOL;

                $this->countRegistro54++;
            }

            $this->registro54 .= $registro54;
        }

        private function schemaRegistro61()
        {
            $registro61 = '';

            // Se tiver todos itens com o mesmo cfop e icms

            $nfcs_date_result = [];

            foreach ($this->array_nfc as $nfce) {
                $data = date('Y-m-d', strtotime($nfce['dataEmissao']));
                $serie = $nfce['serie'];
                $index = $data . '/' . $serie;

                if(!isset($nfcs_date_result[$index]))
                    $nfcs_date_result[$index] = [];
                
                $nfcs_date_result[$index][] = $nfce;
            }

            foreach ($nfcs_date_result as $nfcs_date) {
                $nfc_valor_total = 0;
                $nfc_valor_total_base_icms = 0;
                $nfc_valor_total_icms = 0;
                $nfc_valor_total_isenta = 0;
                $nfc_valor_total_outras = 0;

                $dataEmissao = $nfcs_date[0]['dataEmissao'];
                $serie = $nfcs_date[0]['serie'];
                $numero_min = $nfcs_date[0]['numero'];
                $numero_max = $nfcs_date[0]['numero'];

                foreach ($nfcs_date as $nfc) {
                    $nfc_valor_total            += $nfc['valorTotal'];
                    $nfc_valor_total_base_icms  += $nfc['itens'][0]['impostos']['icms']['baseCalculo'];
                    $nfc_valor_total_icms       += 0;
                    $nfc_valor_total_isenta     += 0;
                    $nfc_valor_total_outras     += 0;
                    if ($numero_min > $nfc['numero'])
                        $numero_min = $nfc['numero'];
                    if ($numero_max < $nfc['numero'])
                        $numero_max = $nfc['numero'];
                }

                $registro61 .= str_pad('61', 30);
                $registro61 .= $this->cleanDate($dataEmissao);
                $registro61 .= '65'; // Modelo 65 para cupom fiscal
                $registro61 .= str_pad($serie, 3, '0', STR_PAD_LEFT);
                $registro61 .= str_pad('', 2); // Revisar str_pad($nfc['subserie'], 2);
                $registro61 .= str_pad($numero_min, 6, '0', STR_PAD_LEFT);
                $registro61 .= str_pad($numero_max, 6, '0', STR_PAD_LEFT);
                $registro61 .= str_pad($this->cleanNumber($nfc_valor_total), 13, '0', STR_PAD_LEFT);
                $registro61 .= str_pad($this->cleanNumber($nfc_valor_total_base_icms), 13, '0', STR_PAD_LEFT);
                $registro61 .= str_pad($this->cleanNumber($nfc_valor_total_icms), 12, '0', STR_PAD_LEFT); // REVISAR str_pad($this->cleanNumber($nfc['itens'][0]['impostos']['icms']['valorDoIcms']), 13, '0', STR_PAD_LEFT);
                $registro61 .= str_pad($this->cleanNumber($nfc_valor_total_isenta), 13, '0', STR_PAD_LEFT); // REVISAR str_pad($this->cleanNumber($nfc['isentaOuNaoTributada']), 13, '0', STR_PAD_LEFT);
                $registro61 .= str_pad($this->cleanNumber($nfc_valor_total_outras), 13, '0', STR_PAD_LEFT); // REVISAR str_pad($this->cleanNumber($nfc['outras']), 13, '0', STR_PAD_LEFT);
                $registro61 .= str_pad($this->cleanNumber($this->aliquota_empresa), 4, '0', STR_PAD_LEFT); // REVISAR str_pad($nfc['itens'][0]['impostos']['icms']['aliquota'], 4, '0', STR_PAD_LEFT);
                $registro61 .= str_pad('', 1);
                $registro61 .= PHP_EOL;

                $this->countRegistro61++;
            }

            $this->registro61 = $registro61;
        }

        private function schemaRegistro61R()
        {
            $registro61R = '';

            $itens = [];

            foreach ($this->array_nfc as $nfce) {
                foreach ($nfce['itens'] as $item) {
                    $index = $item['codigo'];
                    if (isset($itens[$index])) {
                        $itens[$index]['quantidade']            += $item['quantidade'];
                        $itens[$index]['valor_total']           += $item['valorTotal'];
                        $itens[$index]['valor_total_base_icms'] += $item['impostos']['icms']['baseCalculo'];
                    } else {
                        $itens[$index] = [
                            'codigo'                => $index,
                            'quantidade'            => $item['quantidade'],
                            'valor_total'           => $item['valorTotal'],
                            'valor_total_base_icms' => $item['impostos']['icms']['baseCalculo']
                        ];
                    }
                }
            }

            foreach ($itens as $item) {
                $registro61R .= '61R';
                $registro61R .= date('mY', strtotime($nfce['dataEmissao']));
                $registro61R .= str_pad($this->cleanText($item['codigo'], 14), 14);
                $registro61R .= str_pad($this->cleanAmount($item['quantidade']), 13, '0', STR_PAD_LEFT);
                $registro61R .= str_pad($this->cleanNumber($item['valor_total']), 16, '0', STR_PAD_LEFT);
                $registro61R .= str_pad($this->cleanNumber($item['valor_total_base_icms']), 16, '0', STR_PAD_LEFT);
                $registro61R .= str_pad($this->cleanNumber($this->aliquota_empresa), 4, '0', STR_PAD_LEFT); // REVISAR str_pad($cupomFiscal['itens'][0]['impostos']['icms']['aliquota'], 4, '0', STR_PAD_LEFT);
                $registro61R .= str_pad('', 54);
                $registro61R .= PHP_EOL;

                $this->countRegistro61R++;
            }

            $this->registro61R .= $registro61R;
        }

        private function schemaRegistro74()
        {
            $registro74 = '';
            $year = explode('/', $this->mes_ano)[1];
            $last_day_year = date('Ymd', strtotime("{$year}-12-31"));

            foreach ($this->array_estoque as $estoque) {
                $registro74 .= '74';
                $registro74 .= $last_day_year;
                $registro74 .= str_pad($this->cleanText($estoque->codigo, 14), 14);
                $registro74 .= str_pad($this->cleanAmount($estoque->estoque), 13, '0', STR_PAD_LEFT);
                $registro74 .= str_pad($this->cleanNumber($estoque->valor_custo * $estoque->estoque), 13, '0', STR_PAD_LEFT);
                $registro74 .= '1'; // Revisar Codigo de posse
                $registro74 .= $this->cleanString($this->empresa['cnpj'], 14);
                $registro74 .= str_pad($this->cleanString($this->empresa['inscricaoEstadual'], 14), 14);
                $registro74 .= $this->empresa['endereco']['uf'];
                $registro74 .= str_pad('', 45);
                $registro74 .= PHP_EOL;

                $this->countRegistro74++;
            }

            $this->registro74 = $registro74;
        }

        private function schemaRegistro75()
        {
            $registro75 = '';

            $registros = [];

            foreach ($this->array_nfe_itens as $item) {
                $codigo = !empty($item->codigo) ? $item->codigo : $item->codigo_produto;
                if (!isset($registros[$codigo])) {
                    $ncm = !empty($item->ncm) ? $item->ncm : $item->ncm_produto;
                    $unidade = !empty($item->unidade) ? $item->unidade : $item->unidade_produto;
                    $registro = '';
                    $registro .= '75';
                    $registro .= $this->firstDayMonth($this->mes_ano);
                    $registro .= $this->lastDayMonth($this->mes_ano);
                    $registro .= str_pad($this->cleanText($codigo, 14), 14);
                    $registro .= str_pad($this->cleanString($ncm, 8), 8, '0', STR_PAD_LEFT);
                    $registro .= str_pad($this->cleanText($item->descricao, 53), 53);
                    $registro .= str_pad($this->cleanString($unidade, 6), 6);
                    $registro .= str_pad($this->cleanNumber($item->ipi_valor), 5, '0', STR_PAD_LEFT);
                    $registro .= str_pad($this->cleanNumber($item->icms_aliquota), 4, '0', STR_PAD_LEFT);
                    $registro .= str_pad(0, 5, '0', STR_PAD_LEFT); // REVISAR str_pad($item['reducaoDeBaseDeCalculoDoIcms'], 5, '0', STR_PAD_LEFT);
                    $registro .= str_pad($this->cleanNumber($item->icms_st_base), 13, '0', STR_PAD_LEFT);
                    $registro .= PHP_EOL;
                    $registros[$codigo] = $registro;
                }
            }

            foreach ($this->array_nfc as $nfc) {
                foreach ($nfc['itens'] as $item) {
                    $registro = '';
                    $registro .= '75';
                    $registro .= $this->firstDayMonth($this->mes_ano); // Rever Data Inicial
                    $registro .= $this->lastDayMonth($this->mes_ano); // Rever Data Final
                    $registro .= str_pad($this->cleanText($item['codigo'], 14), 14);
                    $registro .= str_pad($this->cleanString($item['ncm'], 8), 8, '0', STR_PAD_LEFT);
                    $registro .= str_pad($this->cleanText($item['descricao'], 53), 53);
                    $registro .= str_pad($this->cleanString($item['unidadeMedida'], 6), 6);
                    $registro .= str_pad(0, 5, '0', STR_PAD_LEFT); // REVISAR str_pad($this->cleanNumber($item['impostos']['percentualAproximadoTributos']['simplificado']['percentual']), 5, '0', STR_PAD_LEFT); // Revisar Aliquota IPI
                    $registro .= str_pad(0, 4, '0', STR_PAD_LEFT); // REVISAR str_pad($this->cleanNumber($item['impostos']['icms']['aliquota']), 4, '0', STR_PAD_LEFT);
                    $registro .= str_pad(0, 5, '0', STR_PAD_LEFT); // REVISAR str_pad($item['reducaoDeBaseDeCalculoDoIcms'], 5, '0', STR_PAD_LEFT);
                    $registro .= str_pad(0, 13, '0', STR_PAD_LEFT); // REVISAR str_pad($this->cleanNumber($item['impostos']['icms']['baseCalculoST']), 13, '0', STR_PAD_LEFT);
                    $registro .= PHP_EOL;
                    $registros[$item['codigo']] = $registro;
                }
            }

            if ($this->inventario_fiscal) {
                $itens = $this->array_estoque;
                foreach ($itens as $item) {
                    $registro = '';
                    $registro .= '75';
                    $registro .= $this->firstDayMonth($this->mes_ano); // Rever Data Inicial
                    $registro .= $this->lastDayMonth($this->mes_ano); // Rever Data Final
                    $registro .= str_pad($this->cleanText($item->codigo, 14), 14);
                    $registro .= str_pad($this->cleanString($item->ncm, 8), 8, '0', STR_PAD_LEFT);
                    $registro .= str_pad($this->cleanText($item->nome, 53), 53);
                    $registro .= str_pad($this->cleanString($item->unidade, 6), 6);
                    $registro .= str_pad(0, 5, '0', STR_PAD_LEFT); // REVISAR str_pad($this->cleanNumber($item['impostos']['percentualAproximadoTributos']['simplificado']['percentual']), 5, '0', STR_PAD_LEFT); // Revisar Aliquota IPI
                    $registro .= str_pad(0, 4, '0', STR_PAD_LEFT); // REVISAR str_pad($this->cleanNumber($item['impostos']['icms']['aliquota']), 4, '0', STR_PAD_LEFT);
                    $registro .= str_pad(0, 5, '0', STR_PAD_LEFT); // REVISAR str_pad($item['reducaoDeBaseDeCalculoDoIcms'], 5, '0', STR_PAD_LEFT);
                    $registro .= str_pad(0, 13, '0', STR_PAD_LEFT); // REVISAR str_pad($this->cleanNumber($item['impostos']['icms']['baseCalculoST']), 13, '0', STR_PAD_LEFT);
                    $registro .= PHP_EOL;
                    $registros[$item->codigo] = $registro;
                }
            }

            foreach ($registros as $registro) {
                $registro75 .= $registro;
                $this->countRegistro75++;
            }

            $this->registro75 = $registro75;
        }

        private function schemaRegistro90()
        {
            $registro90 = '90';
            $registro90 .= $this->empresa['cnpj'];
            $registro90 .= str_pad($this->empresa['inscricaoEstadual'], 14);
            $registro90 .= '50'; // Tipo a ser totalizado 50
            $registro90 .= str_pad($this->countRegistro50, 8, '0', STR_PAD_LEFT); 
            $registro90 .= '54'; // Tipo a ser totalizado 54
            $registro90 .= str_pad($this->countRegistro54, 8, '0', STR_PAD_LEFT); 
            $registro90 .= '61'; // Tipo a ser totalizado 61
            $registro90 .= str_pad($this->countRegistro61 + $this->countRegistro61R, 8, '0', STR_PAD_LEFT);
            if ($this->inventario_fiscal) {
                $registro90 .= '74'; // Tipo a ser totalizado 74
                $registro90 .= str_pad($this->countRegistro74, 8, '0', STR_PAD_LEFT);
            }
            $registro90 .= '75'; // Tipo a ser totalizado 75
            $registro90 .= str_pad($this->countRegistro75, 8, '0', STR_PAD_LEFT);
            $registro90 .= '99'; // Total de linhas
            $registro90 .= str_pad(
                $this->countRegistro50 + $this->countRegistro54 + $this->countRegistro61 + 
                $this->countRegistro61R + $this->countRegistro75 + 3, 
                8, '0', STR_PAD_LEFT
            );
            $registro90 = str_pad($registro90, 125);
            $registro90 .= '1';
            $this->registro90 = $registro90;
        }

        public function getSintegraDownload($mes_ano, $inventario_fiscal)
        {
            $this->mes_ano = $mes_ano;

            $this->setEmpresa();
            $this->setContador();
            $this->setNotaFiscal();
            $this->setNotaFiscalConsumidor();
            $this->setEstoqueInventario();
            $this->schemaRegistro10();
            $this->schemaRegistro11();
            $this->schemaRegistro50();
            $this->schemaRegistro54();
            $this->schemaRegistro61();
            $this->schemaRegistro61R();
            if ($inventario_fiscal)
                $this->schemaRegistro74();
            $this->schemaRegistro75();
            $this->schemaRegistro90();

            $sintegra = '';
            // $sintegra .= $this->registro10;
            // $sintegra .= $this->registro11;
            // $sintegra .= $this->registro50;
            // $sintegra .= $this->registro54;
            // $sintegra .= $this->registro61;
            // $sintegra .= $this->registro61R;
            // if ($inventario_fiscal)
            //     $sintegra .= $this->registro74;
            // $sintegra .= $this->registro75;
            // $sintegra .= $this->registro90;
            $sintegra .= str_replace(["\r\n", "\r", "\n"], "\r\n", $this->registro10);
            $sintegra .= str_replace(["\r\n", "\r", "\n"], "\r\n", $this->registro11);
            $sintegra .= str_replace(["\r\n", "\r", "\n"], "\r\n", $this->registro50);
            $sintegra .= str_replace(["\r\n", "\r", "\n"], "\r\n", $this->registro54);
            $sintegra .= str_replace(["\r\n", "\r", "\n"], "\r\n", $this->registro61);
            $sintegra .= str_replace(["\r\n", "\r", "\n"], "\r\n", $this->registro61R);
            if ($inventario_fiscal)
                $sintegra .= str_replace(["\r\n", "\r", "\n"], "\r\n", $this->registro74);
            $sintegra .= str_replace(["\r\n", "\r", "\n"], "\r\n", $this->registro75);
            $sintegra .= str_replace(["\r\n", "\r", "\n"], "\r\n", $this->registro90);
            return $sintegra;
        }
    }
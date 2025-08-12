<?php

/**
* Produtos são criados/atualizados e vão para uma tabela de analise;
* 
*/

set_time_limit(0);
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 6000);
ini_set('fastcgi_read_timeout', 6000);
ini_set('default_socket_timeout', 9000);
define('_VALID_PHP', true);
require('../../init.php');

// Motivo => Compra: 1 / Transferencia: 2 / Venda: 3 / Consumo: 4 / Perda: 5 / Ajuste: 6 / Cancelamento/Devolução de venda: 7
// Tipo => Entrada: 1 (Compra) / Saida: 2 (Venda)

function sendLogToFirebase($logBody) {
    $lambdaUrl = "https://southamerica-east1-error-logger-5e474.cloudfunctions.net/main";
    $body = array_merge($logBody, ["firestoreCollection" => "logs"]);

    $curl = curl_init($lambdaUrl);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($body));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

try {
    
    $json_string = file_get_contents('php://input');
    $json_array = json_decode($json_string,true);

    try {
        $fullUrl = $_SERVER['SCRIPT_FILENAME'];
        sendLogToFirebase(array_merge(["script_url" => $fullUrl], $json_array));
    } catch (Exception $e) {
    }
    
    $nome_usuario = '';
    $id_usuario = 0;
    $percentual = 0;
    
    $produtos = array();

    if($json_string) {
        foreach((array) $json_array as $nome_campo => $valor) {
           
            if($nome_campo === 'nome_usuario')
                $nome_usuario = $valor;
            if($nome_campo === "id_usuario")
			    $id_usuario = $valor;
            if($nome_campo === "produtos")
			    $produtos = $valor;
        }

        $sql_empresa = "SELECT id FROM empresa WHERE inativo = 0";
        $row_empresa = $db->first($sql_empresa);
        $id_empresa = ($row_empresa) ? $row_empresa->id : 0;
        
        $usuario = getValue("usuario", "usuario", "id=".$id_usuario);
        
        if ($produtos) {
            foreach ($produtos as $prow) {
                $id_app             = (isset($prow['id_app'])) ? (int) $prow['id_app'] : 0;
                $id                 = (isset($prow['id_produto'])) ? (int) $prow['id_produto'] : 0;
                $nome               = (isset($prow['nome']) && $prow['nome'] != "") ? strtoupper($prow['nome']) : null;
                $codigobarras       = (isset($prow['codigobarras']) && $prow['codigobarras'] != null || $prow['codigobarras'] != '') ? $prow['codigobarras'] : "";
                $ncm                = (isset($prow['ncm']) && $prow['ncm'] != null) ? $prow['ncm'] : 0;
                $cfop               = (isset($prow['cfop']) && $prow['cfop'] != null) ? $prow['cfop'] : 5102;
                $icms_cst           = (isset($prow['icms_cst']) && $prow['icms_cst'] != null) ? $prow['icms_cst'] : 102;
                $cest               = (isset($prow['cest']) && $prow['cest'] != null || $prow['cest'] != "") ? $prow['cest'] : '';
                $monofasico         = (isset($prow['monofasico'])) ? $prow['monofasico'] : 0;
                $estoque            = (isset($prow['estoque']) && $prow['estoque'] > 0) ? $prow['estoque'] : 0;
                $unidade            = (isset($prow['unidade']) && $prow['unidade'] != null) ? strtoupper($prow['unidade']) : 'UNID';
                $valor_venda        = (isset($prow['valor_venda']) && $prow['valor_venda'] != null || $prow['valor_venda'] > 0) ? $prow['valor_venda'] : 1;
                $valor_custo        = (isset($prow['valor_custo']) && $prow['valor_custo'] != null || $prow['valor_custo'] > 0) ? $prow['valor_custo'] : 0;
                
                $id_universal = sanitize(gerarIdUniversal());
                $id_universal = strtolower(str_replace('-', '', $id_universal));

                //Pesquisa se há produtos iguais por codigo de barras
                /* Este bloco não é necessário por enquanto, pois foi feito uma atualização em que o app compara se há um ID no produto. */
                // if($codigobarras) {
                //     $sql_codbarras = "SELECT id, codigobarras FROM produto WHERE inativo = 0 AND codigobarras = '$codigobarras' ";
                //     $row_codbarras = $db->first($sql_codbarras);
                //     $resultadoCod = ($row_codbarras) ? $row_codbarras->id."#".$row_codbarras->codigobarras : '';
        
                //     $id_produto_codbarras_sistema = explode('#', $resultadoCod)[0];
                //     $codigo_barras_sistema = explode('#', $resultadoCod)[1];
                    
                //     if($codigo_barras_sistema === $codigobarras) {
                //         $id = (int) $id_produto_codbarras_sistema;
                //     } else {
                //         $id = 0;
                //     }
                // }
                
                if($id === 0 || $id === null) { //Criar produto

                    $data_produto = [
                        "nome"              => sanitize($nome),
                        "id_universal"      => $id_universal,
                        "codigobarras"      => limparNumero($codigobarras),
                        "ncm"               => limparNumero($ncm),
                        "cfop"              => limparNumero($cfop),
                        "cest"              => limparNumero($cest),
                        "icms_cst"          => limparNumero($icms_cst),
                        "valor_custo"       => converteMoeda($valor_custo),
                        "estoque"           => (converteMoeda($estoque) > 0) ? $estoque : 1,
                        "grade"             => 1,
                        "inativo"           => 0,
                        "usuario"           => $usuario,
                        "data"              => 'NOW()',
                    ];
                    $id_produto = $db->insert("produto", $data_produto);
                    
                    $data_estoque = [
                        'id_empresa'        => $id_empresa,
                        'id_produto'        => $id_produto,
                        "valor_custo"       => converteMoeda($valor_custo),
                        'quantidade_antiga' => 0,
                        'quantidade'        => ($estoque > 0) ? $estoque : 1,
                        'tipo'              => 1, 
                        'motivo'            => 1,
                        'observacao'        => 'AJUSTE DE ESTOQUE INICIAL - APLICATIVO DE ESTOQUE: '.$usuario,
                        'usuario'           => $usuario,
                        'data'              => "NOW()"
                    ];
                    $db->insert("produto_estoque", $data_estoque);
                    
                    $retorno_row = $produto->getTabelaPrecos();
                    if($retorno_row) {
                        foreach ($retorno_row as $exrow) {
                            $data_tabela = array(
                                'id_tabela'         => $exrow->id,
                                'id_produto'        => $id_produto,
                                'percentual'        =>  $exrow->percentual,
                                'valor_venda'       => round($valor_venda,2),
                                'usuario'           => $usuario,
                                'data'              => "NOW()"
                            );
                            $db->insert("produto_tabela", $data_tabela);
                        }
                    } else {
                        $data_tabela_preco = array(
                            'tabela'        => 'PADRAO',
                            'percentual'    => $percentual*100,
                            'usuario'       => $usuario,
                            'data'          => "NOW()"
                        );
                        $id_tabela = $db->insert("tabela_precos", $data_tabela_preco);

                        $data_tabela = array(
                            'id_tabela'         => $id_tabela,
                            'id_produto'        => $id_produto,
                            // 'percentual'     => $percentual*100,
                            'valor_venda'       => $valor_venda,
                            'usuario'           => $usuario,
                            'data'              => "NOW()"
                        );
                        $db->insert("produto_tabela", $data_tabela);

                    }
                    
                    $atualizado = false;
                    
                    $result[] = array(
                        "id_app" => $id_app,
                        "id_produto" => (int) $id_produto,
                    );

                } 
                else { //Atualizar produto

                    $estoque_atual = $produto->getEstoqueTotal( $id );
                    $estoque_fisico = $estoque ;

                    // $status_estoque = (converteMoeda($estoque_fisico) == converteMoeda($estoque_atual)) ? 0 : 2;
                    
                    $data_estoque = [
                        'id_produto'        => $id,
                        'estoque_atual'     => converteMoeda($estoque_atual),
                        'estoque_fisico'    => converteMoeda($estoque_fisico),
                        "valor_custo"       => converteMoeda($valor_custo),
                        'observacao'        => "CONTAGEM DE ESTOQUE - APP (Em Análise) - Usuario: $usuario", 
                        'status'            => 2, //1- Alterado / 2- Pendente de analise / 0- Sem alteração
                        'criado_em'         => 'NOW()',
                        'atualizado_em'     => 'NOW()',
                        'usuario'           => $usuario,
                        'data'              => "NOW()"
                    ];
                    $db->insert("estoque_analise", $data_estoque);

                    $data_produto = [
                        "codigobarras"      => limparNumero($codigobarras),
                        "ncm"               => limparNumero($ncm),
                        "cfop"              => limparNumero($cfop),
                        "icms_cst"          => limparNumero($icms_cst),
                        "monofasico"        => $monofasico,
                        "usuario"           => $usuario,
                        "data"              => 'NOW()',
                    ];
                    $db->update("produto", $data_produto, "id=".$id);
                    
                    $atualizado = true;

                    $result[] = array(
                        "id_app" => $id_app,
                        "id_produto" => (int) $id,
                    );
                    
                }
            
            }
        }
        
        if ($db->affected()) {	
            $status = ($atualizado) ? "Sucesso! Produto atualizado com sucesso." : "Sucesso! Produto adicionado com sucesso.";
            $json_success = array(
                "status" => ($atualizado) ? 200 : 201 ,
                "retorno" => $status,
                "produtos_criados" => ($result)
            );
            $retorno = json_encode($json_success);
            echo $retorno;
        }
        
    } 
    else {
        $status = "Erro! JSON vazio.";
        $json_success = array(
            "status" => 400,
            "retorno" => $status
        );
        $retorno = json_encode($json_success);
        echo $retorno;
    }
         
} catch (Exception $e) {
    $status = "Erro - JSON[".$json_string."] EXCEÇÃO: ".$e->getMessage();
    $json_erro = array(
        "status" => 400,
        "retorno" => 'Error: '.$status
    );
    $retorno =  json_encode($json_erro);
    echo $retorno;
}

?>
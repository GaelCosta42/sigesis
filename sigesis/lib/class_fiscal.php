<?php

if (!defined("_VALID_PHP"))
    die('Acesso direto a esta classe não é permitido.');

use eNotasGW\Api\Exceptions as Exceptions;
    
eNotasGW::configure(array(
    'apiKey' => $enotas_apikey
));

class Fiscal {

    private static $db;
    private $idEnotas;

    public function __construct()
    {
        self::$db = Registry::get("Database");
    }

    public function getListarCupomFiscal($acao = null, $data_inicio = null, $data_final = null)
    {
        $acao = ($acao === null) 
            ? '' 
            : "AND IF(nfc.status_enotas = 'CancelamentoNegado', 'Autorizada', IF(nfc.status_enotas = 'EmProcessoDeCancelamento', 'Processando', nfc.status_enotas)) = '{$acao}'";
        
        $data_inicio = ($data_inicio === null) 
            ? date('Y-m-01')
            : date('Y-m-d', strtotime(explode('/', $data_inicio)[2].'-'.explode('/', $data_inicio)[1].'-'.explode('/', $data_inicio)[0]));
        $data_final = ($data_final === null) 
            ? date('Y-m-t')
            : date('Y-m-d 23:59:59', strtotime(explode('/', $data_final)[2].'-'.explode('/', $data_final)[1].'-'.explode('/', $data_final)[0]));

        $sql_cupons = "SELECT nfc.id, nfc.id_empresa, nfc.data_venda, nfc.data, nfc.data_emissao, nfc.status_enotas,
            IF(nfc.status_enotas = 'CancelamentoNegado', 'Autorizada', IF(nfc.status_enotas = 'EmProcessoDeCancelamento', 'Processando', nfc.status_enotas)) AS acao,
            nfc.numero, nfc.serie, nfc.chaveacesso, nfc.link_danfe, nfc.link_download_xml, nfc.link_danfe, nfc.valor_pago FROM vendas nfc
            WHERE nfc.inativo = 0 {$acao} AND ((nfc.data_emissao BETWEEN '{$data_inicio}' AND '{$data_final}')
            || (nfc.data_emissao = 0 AND nfc.data BETWEEN '{$data_inicio}' AND '{$data_final}'))";
        return self::$db->fetch_all($sql_cupons);
    }

    public function getListarCupomFiscalCancelado($acao = null, $data_inicio = null, $data_final = null)
    {
        $acao = ($acao === null) 
            ? '' 
            : "AND IF(nfc.status_enotas = 'CancelamentoNegado', 'Autorizada', IF(nfc.status_enotas = 'EmProcessoDeCancelamento', 'Processando', nfc.status_enotas)) = '{$acao}'";
        
        $data_inicio = ($data_inicio === null) 
            ? date('Y-m-01')
            : date('Y-m-d', strtotime(explode('/', $data_inicio)[2].'-'.explode('/', $data_inicio)[1].'-'.explode('/', $data_inicio)[0]));
        $data_final = ($data_final === null) 
            ? date('Y-m-t')
            : date('Y-m-d 23:59:59', strtotime(explode('/', $data_final)[2].'-'.explode('/', $data_final)[1].'-'.explode('/', $data_final)[0]));

        $sql_cupons = "SELECT nfc.id, nfc.data_venda, nfc.data, nfc.data_emissao, nfc.status_enotas,
            IF(nfc.status_enotas = 'CancelamentoNegado', 'Autorizada', IF(nfc.status_enotas = 'EmProcessoDeCancelamento', 'Processando', nfc.status_enotas)) AS acao,
            nfc.numero, nfc.serie, nfc.chaveacesso, nfc.link_danfe, nfc.link_download_xml, nfc.link_danfe, nfc.valor_pago FROM vendas nfc
            WHERE nfc.status_enotas = 'Cancelada' {$acao} AND nfc.data_emissao BETWEEN '{$data_inicio}' AND '{$data_final}'";
        return self::$db->fetch_all($sql_cupons);
    }
	
	/* CONSULTA PARA BAIXAR NOTA DE ENTRADA ATRAVÉS DA DATA DE ENTRADA */
	public function getListarNotasEntrada($data_inicio = null, $data_final = null)
    {
        $data_inicio = ($data_inicio === null) 
            ? date('Y-m-01')
            : date('Y-m-d', strtotime(explode('/', $data_inicio)[2].'-'.explode('/', $data_inicio)[1].'-'.explode('/', $data_inicio)[0]));
        $data_final = ($data_final === null) 
            ? date('Y-m-t')
            : date('Y-m-d 23:59:59', strtotime(explode('/', $data_final)[2].'-'.explode('/', $data_final)[1].'-'.explode('/', $data_final)[0]));

        $sql_cupons = "SELECT nf.id, nf.data_emissao, nf.data_entrada, nf.status_enotas,
            nf.numero, nf.serie, nf.chaveacesso, nf.nome_arquivo, nf.valor_nota FROM nota_fiscal nf
            WHERE nf.inativo = 0 AND nf.operacao = 1 AND nf.data_entrada BETWEEN '{$data_inicio}' AND '{$data_final}'";
        return self::$db->fetch_all($sql_cupons);
    }
	
	/* NOVA CONSULTA PARA BAIXAR NOTA DE ENTRADA ATRAVÉS DA DATA DE EMISSAO */
    public function getListarNotasEntradaDtEmissao($data_inicio = null, $data_final = null)
    {
        $data_inicio = ($data_inicio === null) 
            ? date('Y-m-01')
            : date('Y-m-d', strtotime(explode('/', $data_inicio)[2].'-'.explode('/', $data_inicio)[1].'-'.explode('/', $data_inicio)[0]));
        $data_final = ($data_final === null) 
            ? date('Y-m-t')
            : date('Y-m-d 23:59:59', strtotime(explode('/', $data_final)[2].'-'.explode('/', $data_final)[1].'-'.explode('/', $data_final)[0]));

        $sql_cupons = "SELECT nf.id, nf.data_emissao, nf.data_entrada, nf.status_enotas, nf.numero, nf.serie, nf.chaveacesso, nf.nome_arquivo, nf.valor_nota 
            FROM nota_fiscal nf
            WHERE nf.inativo = 0 AND nf.operacao = 1 
            AND nf.data_emissao BETWEEN '{$data_inicio}' AND '{$data_final}'
        ";
        
        return self::$db->fetch_all($sql_cupons);
    }

    public function getListarNotasSaida($acao = null, $data_inicio = null, $data_final = null)
    {
        $data_inicio = ($data_inicio === null) 
            ? date('Y-m-01')
            : date('Y-m-d', strtotime(explode('/', $data_inicio)[2].'-'.explode('/', $data_inicio)[1].'-'.explode('/', $data_inicio)[0]));
        $data_final = ($data_final === null) 
            ? date('Y-m-t')
            : date('Y-m-d 23:59:59', strtotime(explode('/', $data_final)[2].'-'.explode('/', $data_final)[1].'-'.explode('/', $data_final)[0]));

        $sql_cupons = "SELECT nf.id, nf.id_empresa, nf.data_emissao, nf.data_entrada, nf.status_enotas,
            nf.numero, nf.serie, nf.chaveacesso, nf.nome_arquivo, nf.valor_nota, nf.link_download_xml, nf.link_danfe FROM nota_fiscal nf
            WHERE nf.inativo = 0 AND nf.operacao = 2 AND nf.data_emissao BETWEEN '{$data_inicio}' AND '{$data_final}'";
        return self::$db->fetch_all($sql_cupons);
    }

    public function getListarNotasSaidaCancelado($acao = null, $data_inicio = null, $data_final = null)
    {
        $data_inicio = ($data_inicio === null) 
            ? date('Y-m-01')
            : date('Y-m-d', strtotime(explode('/', $data_inicio)[2].'-'.explode('/', $data_inicio)[1].'-'.explode('/', $data_inicio)[0]));
        $data_final = ($data_final === null) 
            ? date('Y-m-t')
            : date('Y-m-d 23:59:59', strtotime(explode('/', $data_final)[2].'-'.explode('/', $data_final)[1].'-'.explode('/', $data_final)[0]));

        $sql_cupons = "SELECT nf.id, nf.id_empresa, nf.data_emissao, nf.data_entrada, nf.status_enotas,
            nf.numero, nf.serie, nf.chaveacesso, nf.nome_arquivo, nf.valor_nota, nf.link_download_xml, nf.link_danfe FROM nota_fiscal nf
            WHERE status_enotas = 'Cancelada' AND nf.operacao = 2 AND nf.data_emissao BETWEEN '{$data_inicio}' AND '{$data_final}'";
        return self::$db->fetch_all($sql_cupons);
    }

    /* FUNÇÕES */

    public function downloadCupomFiscal_backup()
    {
        $acao = (!empty($_GET['acao'])) ? $_GET['acao'] : null;
        $data_inicio = (!empty($_GET['data_inicio'])) ? $_GET['data_inicio'] : null;
        $data_final = (!empty($_GET['data_final'])) ? $_GET['data_final'] : null;

        $pathCupons = './uploads/cupons/';
        $zipName = 'XML_Nota_Fiscal_Consumidor.zip';
        $pathZip = $pathCupons.$zipName;

        // Deleta todos arquivos dentro da pasta
        $files_to_delete = glob($pathCupons . '*');
        foreach($files_to_delete as $file_to_delete){
            if(is_file($file_to_delete)) {
                unlink($file_to_delete);
            }
        }

        $urls = [];

        $cupons = $this->getListarCupomFiscal($acao, $data_inicio, $data_final);
        foreach ($cupons as $cupom) {
            if (!empty($cupom->link_download_xml)) {
                $urls[] = [
                    'numero' => $cupom->numero,
                    'link' => $cupom->link_download_xml
                ];
            }
        }

        $multiCurl = [];
        $mh = curl_multi_init();

        foreach ($urls as $i => $url) {
        // URL from which data will be fetched
            $link = $url['link'];
            $multiCurl[$i] = curl_init();

            curl_setopt($multiCurl[$i], CURLOPT_URL, $link);
            curl_setopt($multiCurl[$i], CURLOPT_VERBOSE, 1);
            curl_setopt($multiCurl[$i], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($multiCurl[$i], CURLOPT_AUTOREFERER, false);
            curl_setopt($multiCurl[$i], CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($multiCurl[$i], CURLOPT_HEADER, 0);

            curl_multi_add_handle($mh, $multiCurl[$i]);
        }
        
        $index=null;

        do {
            curl_multi_exec($mh,$index);
        } while($index > 0);

        foreach($multiCurl as $k => $ch) {
            file_put_contents($pathCupons.$urls[$k]['numero'].'.xml', curl_multi_getcontent($ch));
            curl_multi_remove_handle($mh, $ch);
        }

        // close
        curl_multi_close($mh);

        $rootPath = realpath($pathCupons);

        // ZIP
        $zip = new ZipArchive();
        $zip->open($pathZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $filesToDelete = array();

        //@var SplFileInfo[] $files
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                $zip->addFile($filePath, $relativePath);
                $filesToDelete[] = $filePath;
            }
        }

        $zip->close();

        foreach ($filesToDelete as $file) {
            unlink($file);
        }

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename=' . $zipName);
        header('Pragma: no-cache');
        readfile($pathZip);

    }

    public function downloadCupomFiscal()
    {     
        //chamar block da tela aqui
        //print '<script type="text/javascript"> $("#overlay").css("display", "flex"); </script>';

        $acao = (!empty($_GET['acao'])) ? $_GET['acao'] : null;
        $data_inicio = (!empty($_GET['data_inicio'])) ? $_GET['data_inicio'] : null;
        $data_final = (!empty($_GET['data_final'])) ? $_GET['data_final'] : null;

        $pathCupons = './uploads/cupons/';
        $zipName = 'XML_Nota_Fiscal_Consumidor.zip';
        $pathZip = $pathCupons.$zipName;

        // Deleta todos arquivos dentro da pasta
        $files_to_delete = glob($pathCupons . '*');
        foreach($files_to_delete as $file_to_delete){
            if(is_file($file_to_delete)) {
                unlink($file_to_delete);
            }
        }

        $urls = [];
        $id_empresa = 0;

        $cupons = $this->getListarCupomFiscal($acao, $data_inicio, $data_final);
        foreach ($cupons as $cupom) {
            if (!empty($cupom->link_download_xml)) {
                $urls[] = [
                    'id' => $cupom->id,
                    'numero' => $cupom->numero,
                    'link' => $cupom->link_download_xml
                ];
                $id_empresa = ($cupom->id_empresa>0) ? $cupom->id_empresa : $id_empresa;
            }
        }
        $id_empresa = ($id_empresa>0) ? intval($id_empresa) : 1;
        $row_empresa = Core::getRowById("empresa", $id_empresa);
        $empresaIdENotas = $row_empresa->enotas;

        foreach ($urls as $i => $url) {
           
            try
            {
                $idExterno = ($row_empresa->versao_emissao==0) ? 'nfc-'.$url['id'] : 'nfc'.$row_empresa->versao_emissao.'-'.$url['id'];
                $xml = eNotasGW::$NFeConsumidorApi->downloadXml($empresaIdENotas, $idExterno);
                $xmlFileName = $pathCupons.$url['numero'].'.xml';
                file_put_contents($xmlFileName, $xml);

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
                echo 'Erro de validação: </br></br>';
                echo $ex->getMessage();
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
        }

        $rootPath = realpath($pathCupons);

        // ZIP
        $zip = new ZipArchive();
        $zip->open($pathZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $filesToDelete = array();

        //@var SplFileInfo[] $files
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                $zip->addFile($filePath, $relativePath);
                $filesToDelete[] = $filePath;
            }
        }

        $zip->close();

        foreach ($filesToDelete as $file) {
            unlink($file);
        }

        //cancelar block da tela aqui
        //echo '<script> $("#overlay").css("display", "none"); </script>';
        
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename=' . $zipName);
        header('Pragma: no-cache');
        readfile($pathZip);

    }

    public function downloadCupomFiscalCancelado()
    {
        $acao = (!empty($_GET['acao'])) ? $_GET['acao'] : null;
        $data_inicio = (!empty($_GET['data_inicio'])) ? $_GET['data_inicio'] : null;
        $data_final = (!empty($_GET['data_final'])) ? $_GET['data_final'] : null;

        $pathCupons = './uploads/cupons/';
        $zipName = 'XML_Nota_Fiscal_Consumidor_Cancelada.zip';
        $pathZip = $pathCupons.$zipName;

        // Deleta todos arquivos dentro da pasta
        $files_to_delete = glob($pathCupons . '*');
        foreach($files_to_delete as $file_to_delete){
            if(is_file($file_to_delete)) {
                unlink($file_to_delete);
            }
        }

        $urls = [];

        $cupons = $this->getListarCupomFiscalCancelado($acao, $data_inicio, $data_final);
        foreach ($cupons as $cupom) {
            if (!empty($cupom->link_download_xml)) {
                $urls[] = [
                    'numero' => $cupom->numero,
                    'link' => $cupom->link_download_xml
                ];
            }
        }

        $multiCurl = [];
        $mh = curl_multi_init();

        foreach ($urls as $i => $url) {
        // URL from which data will be fetched
            $link = $url['link'];
            $multiCurl[$i] = curl_init();

            curl_setopt($multiCurl[$i], CURLOPT_URL, $link);
            curl_setopt($multiCurl[$i], CURLOPT_VERBOSE, 1);
            curl_setopt($multiCurl[$i], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($multiCurl[$i], CURLOPT_AUTOREFERER, false);
            // curl_setopt($multiCurl[$i], CURLOPT_REFERER, "http://www.xcontest.org");
            curl_setopt($multiCurl[$i], CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($multiCurl[$i], CURLOPT_HEADER, 0);

            curl_multi_add_handle($mh, $multiCurl[$i]);
        }
        
        $index=null;

        do {
            curl_multi_exec($mh,$index);
        } while($index > 0);

        foreach($multiCurl as $k => $ch) {
            file_put_contents($pathCupons.$urls[$k]['numero'].'.xml', curl_multi_getcontent($ch));
            curl_multi_remove_handle($mh, $ch);
        }
        // close
        curl_multi_close($mh);

        $rootPath = realpath($pathCupons);

        // ZIP
        $zip = new ZipArchive();
        $zip->open($pathZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $filesToDelete = array();

        //@var SplFileInfo[] $files
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                $zip->addFile($filePath, $relativePath);
                $filesToDelete[] = $filePath;
            }
        }

        $zip->close();

        foreach ($filesToDelete as $file) {
            unlink($file);
        }

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename=' . $zipName);
        header('Pragma: no-cache');
        readfile($pathZip);

    }

    public function downloadPDFCupomFiscal()
    {
        $acao = (!empty($_GET['acao'])) ? $_GET['acao'] : null;
        $data_inicio = (!empty($_GET['data_inicio'])) ? $_GET['data_inicio'] : null;
        $data_final = (!empty($_GET['data_final'])) ? $_GET['data_final'] : null;

        $pathCupons = './uploads/cupons/';
        $zipName = 'PDF_Nota_Fiscal_Consumidor.zip';
        $pathZip = $pathCupons.$zipName;

        // Deleta todos arquivos dentro da pasta
        $files_to_delete = glob($pathCupons . '*');
        foreach($files_to_delete as $file_to_delete){
            if(is_file($file_to_delete)) {
                unlink($file_to_delete);
            }
        }

        $urls = [];

        $cupons = $this->getListarCupomFiscal($acao, $data_inicio, $data_final);
        foreach ($cupons as $cupom) {
            if (!empty($cupom->link_danfe)) {
                $urls[] = [
                    'numero' => $cupom->numero,
                    'link' => $cupom->link_danfe
                ];
            }
        }

        $multiCurl = [];
        $mh = curl_multi_init();

        foreach ($urls as $i => $url) {
        // URL from which data will be fetched
            $link = $url['link'];
            $multiCurl[$i] = curl_init();

            curl_setopt($multiCurl[$i], CURLOPT_URL, $link);
            curl_setopt($multiCurl[$i], CURLOPT_VERBOSE, 1);
            curl_setopt($multiCurl[$i], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($multiCurl[$i], CURLOPT_AUTOREFERER, false);
            // curl_setopt($multiCurl[$i], CURLOPT_REFERER, "http://www.xcontest.org");
            curl_setopt($multiCurl[$i], CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($multiCurl[$i], CURLOPT_HEADER, 0);

            curl_multi_add_handle($mh, $multiCurl[$i]);
        }
        
        $index=null;

        do {
            curl_multi_exec($mh,$index);
        } while($index > 0);

        foreach($multiCurl as $k => $ch) {
            file_put_contents($pathCupons.$urls[$k]['numero'].'.pdf', curl_multi_getcontent($ch));
            curl_multi_remove_handle($mh, $ch);
        }
        // close
        curl_multi_close($mh);

        $rootPath = realpath($pathCupons);

        // ZIP
        $zip = new ZipArchive();
        $zip->open($pathZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $filesToDelete = array();

        //@var SplFileInfo[] $files
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                $zip->addFile($filePath, $relativePath);
                $filesToDelete[] = $filePath;
            }
        }

        $zip->close();

        foreach ($filesToDelete as $file) {
            unlink($file);
        }

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename=' . $zipName);
        header('Pragma: no-cache');
        readfile($pathZip);

    }

    public function _backup_velho_downloadXMLSaida()
    {
        $acao = (!empty($_GET['acao'])) ? $_GET['acao'] : null;
        $data_inicio = (!empty($_GET['data_inicio'])) ? $_GET['data_inicio'] : null;
        $data_final = (!empty($_GET['data_final'])) ? $_GET['data_final'] : null;

        $pathCupons = './uploads/cupons/';
        $zipName = 'XML_Nota_Fiscal_Saida.zip';
        $pathZip = $pathCupons.$zipName;

        // Deleta todos arquivos dentro da pasta
        $files_to_delete = glob($pathCupons . '*');
        foreach($files_to_delete as $file_to_delete){
            if(is_file($file_to_delete)) {
                unlink($file_to_delete);
            }
        }

        $urls = [];

        $notas = $this->getListarNotasSaida($acao, $data_inicio, $data_final);
        
        foreach ($notas as $nota) {
            if (!empty($nota->link_download_xml)) {
                $urls[] = [
                    'numero' => $nota->numero,
                    'link' => $nota->link_download_xml
                ];
            }
        }

        $multiCurl = [];
        $mh = curl_multi_init();

        foreach ($urls as $i => $url) {
        // URL from which data will be fetched
            $link = $url['link'];
            $multiCurl[$i] = curl_init();

            curl_setopt($multiCurl[$i], CURLOPT_URL, $link);
            curl_setopt($multiCurl[$i], CURLOPT_VERBOSE, 1);
            curl_setopt($multiCurl[$i], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($multiCurl[$i], CURLOPT_AUTOREFERER, false);
            // curl_setopt($multiCurl[$i], CURLOPT_REFERER, "http://www.xcontest.org");
            curl_setopt($multiCurl[$i], CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($multiCurl[$i], CURLOPT_HEADER, 0);

            curl_multi_add_handle($mh, $multiCurl[$i]);
        }
        
        $index=null;

        do {
            curl_multi_exec($mh,$index);
        } while($index > 0);

        foreach($multiCurl as $k => $ch) {
            file_put_contents($pathCupons.$urls[$k]['numero'].'.xml', curl_multi_getcontent($ch));
            curl_multi_remove_handle($mh, $ch);
        }
        // close
        curl_multi_close($mh);

        $rootPath = realpath($pathCupons);

        // ZIP
        $zip = new ZipArchive();
        $zip->open($pathZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $filesToDelete = array();

        //@var SplFileInfo[] $files
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                $zip->addFile($filePath, $relativePath);
                $filesToDelete[] = $filePath;
            }
        }

        $zip->close();

        foreach ($filesToDelete as $file) {
            unlink($file);
        }

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename=' . $zipName);
        header('Pragma: no-cache');
        readfile($pathZip);

    }

    public function downloadXMLSaida()
    {
        //chamar block da tela aqui
        //print '<script type="text/javascript"> $("#overlay").css("display", "flex"); </script>';

        $acao = (!empty($_GET['acao'])) ? $_GET['acao'] : null;
        $data_inicio = (!empty($_GET['data_inicio'])) ? $_GET['data_inicio'] : null;
        $data_final = (!empty($_GET['data_final'])) ? $_GET['data_final'] : null;

        $pathCupons = './uploads/cupons/';
        $zipName = 'XML_Nota_Fiscal_Saida.zip';
        $pathZip = $pathCupons.$zipName;

        // Deleta todos arquivos dentro da pasta
        $files_to_delete = glob($pathCupons . '*');
        foreach($files_to_delete as $file_to_delete){
            if(is_file($file_to_delete)) {
                unlink($file_to_delete);
            }
        }

        $urls = [];
        $id_empresa = 1;

        $notas = $this->getListarNotasSaida($acao, $data_inicio, $data_final);
        
        foreach ($notas as $nota) {
            if (!empty($nota->link_download_xml)) {
                $urls[] = [
                    'id' => $nota->id,
                    'numero' => $nota->numero,
                    'link' => $nota->link_download_xml
                ];
                $id_empresa = $nota->id_empresa;
            }
        }
        
        $row_empresa = Core::getRowById("empresa", $id_empresa);
        $empresaIdENotas = $row_empresa->enotas;

        foreach ($urls as $i => $url) {
            try
            {
                $idExterno = ($row_empresa->versao_emissao==0) ? 'nfe-'.$url['id'] : 'nfe'.$row_empresa->versao_emissao.'-'.$url['id'];
                $xml = trim(stripslashes(eNotasGW::$NFeProdutoApi->downloadXml($empresaIdENotas, $idExterno)),'"');
                $xmlFileName = $pathCupons.$url['numero'].'.xml';
                file_put_contents($xmlFileName, $xml);
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
                echo 'Erro de validação: </br></br>';
                echo $ex->getMessage();
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
        }

        $rootPath = realpath($pathCupons);

        // ZIP
        $zip = new ZipArchive();
        $zip->open($pathZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $filesToDelete = array();

        //@var SplFileInfo[] $files
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                $zip->addFile($filePath, $relativePath);
                $filesToDelete[] = $filePath;
            }
        }

        $zip->close();

        foreach ($filesToDelete as $file) {
            unlink($file);
        }

        //cancelar block da tela aqui
        //echo '<script> $("#overlay").css("display", "none"); </script>';

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename=' . $zipName);
        header('Pragma: no-cache');
        readfile($pathZip);

    }
	
    public function downloadXMLSaidaCancelado()
    {
        $acao = (!empty($_GET['acao'])) ? $_GET['acao'] : null;
        $data_inicio = (!empty($_GET['data_inicio'])) ? $_GET['data_inicio'] : null;
        $data_final = (!empty($_GET['data_final'])) ? $_GET['data_final'] : null;

        $pathCupons = './uploads/cupons/';
        $zipName = 'XML_Nota_Fiscal_Saida_Cancelada.zip';
        $pathZip = $pathCupons.$zipName;

        // Deleta todos arquivos dentro da pasta
        $files_to_delete = glob($pathCupons . '*');
        foreach($files_to_delete as $file_to_delete){
            if(is_file($file_to_delete)) {
                unlink($file_to_delete);
            }
        }

        $urls = [];

        $notas = $this->getListarNotasSaidaCancelado($acao, $data_inicio, $data_final);
        
        foreach ($notas as $nota) {
            if (!empty($nota->link_download_xml)) {
                $urls[] = [
                    'numero' => $nota->numero,
                    'link' => $nota->link_download_xml
                ];
            }
        }

        $multiCurl = [];
        $mh = curl_multi_init();

        foreach ($urls as $i => $url) {
        // URL from which data will be fetched
            $link = $url['link'];
            $multiCurl[$i] = curl_init();

            curl_setopt($multiCurl[$i], CURLOPT_URL, $link);
            curl_setopt($multiCurl[$i], CURLOPT_VERBOSE, 1);
            curl_setopt($multiCurl[$i], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($multiCurl[$i], CURLOPT_AUTOREFERER, false);
            // curl_setopt($multiCurl[$i], CURLOPT_REFERER, "http://www.xcontest.org");
            curl_setopt($multiCurl[$i], CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($multiCurl[$i], CURLOPT_HEADER, 0);

            curl_multi_add_handle($mh, $multiCurl[$i]);
        }
        
        $index=null;

        do {
            curl_multi_exec($mh,$index);
        } while($index > 0);

        foreach($multiCurl as $k => $ch) {
            file_put_contents($pathCupons.$urls[$k]['numero'].'.xml', curl_multi_getcontent($ch));
            curl_multi_remove_handle($mh, $ch);
        }
        // close
        curl_multi_close($mh);

        $rootPath = realpath($pathCupons);

        // ZIP
        $zip = new ZipArchive();
        $zip->open($pathZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $filesToDelete = array();

        //@var SplFileInfo[] $files
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                $zip->addFile($filePath, $relativePath);
                $filesToDelete[] = $filePath;
            }
        }

        $zip->close();

        foreach ($filesToDelete as $file) {
            unlink($file);
        }

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename=' . $zipName);
        header('Pragma: no-cache');
        readfile($pathZip);
    }

    public function downloadPDFSaida()
    {
        $acao = (!empty($_GET['acao'])) ? $_GET['acao'] : null;
        $data_inicio = (!empty($_GET['data_inicio'])) ? $_GET['data_inicio'] : null;
        $data_final = (!empty($_GET['data_final'])) ? $_GET['data_final'] : null;

        $pathCupons = './uploads/cupons/';
        $zipName = 'PDF_Nota_Fiscal_Saida.zip';
        $pathZip = $pathCupons.$zipName;

        // Deleta todos arquivos dentro da pasta
        $files_to_delete = glob($pathCupons . '*');
        foreach($files_to_delete as $file_to_delete){
            if(is_file($file_to_delete)) {
                unlink($file_to_delete);
            }
        }

        $urls = [];

        $notas = $this->getListarNotasSaida($acao, $data_inicio, $data_final);
        
        foreach ($notas as $nota) {
            if (!empty($nota->link_danfe)) {
                $urls[] = [
                    'numero' => $nota->numero,
                    'link' => $nota->link_danfe
                ];
            }
        }

        $multiCurl = [];
        $mh = curl_multi_init();

        foreach ($urls as $i => $url) {
        // URL from which data will be fetched
            $link = $url['link'];
            $multiCurl[$i] = curl_init();

            curl_setopt($multiCurl[$i], CURLOPT_URL, $link);
            curl_setopt($multiCurl[$i], CURLOPT_VERBOSE, 1);
            curl_setopt($multiCurl[$i], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($multiCurl[$i], CURLOPT_AUTOREFERER, false);
            // curl_setopt($multiCurl[$i], CURLOPT_REFERER, "http://www.xcontest.org");
            curl_setopt($multiCurl[$i], CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($multiCurl[$i], CURLOPT_HEADER, 0);

            curl_multi_add_handle($mh, $multiCurl[$i]);
        }
        
        $index=null;

        do {
            curl_multi_exec($mh,$index);
        } while($index > 0);

        foreach($multiCurl as $k => $ch) {
            file_put_contents($pathCupons.$urls[$k]['numero'].'.pdf', curl_multi_getcontent($ch));
            curl_multi_remove_handle($mh, $ch);
        }
        // close
        curl_multi_close($mh);

        $rootPath = realpath($pathCupons);

        // ZIP
        $zip = new ZipArchive();
        $zip->open($pathZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $filesToDelete = array();

        //@var SplFileInfo[] $files
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                $zip->addFile($filePath, $relativePath);
                $filesToDelete[] = $filePath;
            }
        }

        $zip->close();

        foreach ($filesToDelete as $file) {
            unlink($file);
        }

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename=' . $zipName);
        header('Pragma: no-cache');
        readfile($pathZip);

    }

	public function downloadXMLEntrada()
    {
		$acao = (!empty($_GET['acao'])) ? $_GET['acao'] : null;
        $data_inicio = (!empty($_GET['data_inicio'])) ? $_GET['data_inicio'] : null;
        $data_final = (!empty($_GET['data_final'])) ? $_GET['data_final'] : null;

        $pathCupons = './uploads/cupons/';
        $pathXML = './uploads/data/';
        $zipName = 'XML_Nota_Fiscal_Entrada.zip';
        $pathZip = $pathCupons.$zipName;

        // Deleta todos arquivos dentro da pasta
        $files_to_delete = glob($pathCupons . '*');
        foreach($files_to_delete as $file_to_delete){
            if(is_file($file_to_delete)) {
                unlink($file_to_delete);
            }
        }

        $urls = [];

        $notas = $this->getListarNotasEntrada($data_inicio, $data_final);
		
        foreach ($notas as $nota) {
            if (!empty($nota->nome_arquivo)) {
				$arquivoOrigem = $pathXML.$nota->nome_arquivo;
				$arquivoDestino = $pathCupons.$nota->nome_arquivo;
                copy($arquivoOrigem,$arquivoDestino);
            }
        }

        $rootPath = realpath($pathCupons);

        // ZIP
        $zip = new ZipArchive();
        $zip->open($pathZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $filesToDelete = array();

        //@var SplFileInfo[] $files
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                $zip->addFile($filePath, $relativePath);
                $filesToDelete[] = $filePath;
            }
        }

        $zip->close();

        foreach ($filesToDelete as $file) {
            unlink($file);
        }

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename=' . $zipName);
        header('Pragma: no-cache');
        readfile($pathZip);
		
    }
	
	//data de emissao NOVO
    public function downloadXMLEntradaDtEmissao()
    {
		$acao = (!empty($_GET['acao'])) ? $_GET['acao'] : null;
        $data_inicio = (!empty($_GET['data_inicio'])) ? $_GET['data_inicio'] : null;
        $data_final = (!empty($_GET['data_final'])) ? $_GET['data_final'] : null;

        $pathCupons = './uploads/cupons/';
        $pathXML = './uploads/data/';
        $zipName = 'XML_Nota_Fiscal_Entrada_dtemissao.zip';
        $pathZip = $pathCupons.$zipName;

        // Deleta todos arquivos dentro da pasta
        $files_to_delete = glob($pathCupons . '*');
        foreach($files_to_delete as $file_to_delete){
            if(is_file($file_to_delete)) {
                unlink($file_to_delete);
            }
        }

        $urls = [];

        $notas = $this->getListarNotasEntradaDtEmissao($data_inicio, $data_final);
		
        foreach ($notas as $nota) {
            if (!empty($nota->nome_arquivo)) {
				$arquivoOrigem = $pathXML.$nota->nome_arquivo;
				$arquivoDestino = $pathCupons.$nota->nome_arquivo;
                copy($arquivoOrigem,$arquivoDestino);
            }
        }

        $rootPath = realpath($pathCupons);

        // ZIP
        $zip = new ZipArchive();
        $zip->open($pathZip, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $filesToDelete = array();

        //@var SplFileInfo[] $files
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                $zip->addFile($filePath, $relativePath);
                $filesToDelete[] = $filePath;
            }
        }

        $zip->close();

        foreach ($filesToDelete as $file) {
            unlink($file);
        }

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename=' . $zipName);
        header('Pragma: no-cache');
        readfile($pathZip);
		
    }
}
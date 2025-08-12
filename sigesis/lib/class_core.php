<?php

/**
 * Classe Core
 *
 */

if (!defined("_VALID_PHP"))
    die('Acesso direto a esta classe não é permitido.');

class Core
{

    const sTable = "configuracao";
    public $ano = null;
    public $mes = null;
    public $dia = null;
    public $origem = 0;
    public $primeira_cor = "";
    public $segunda_cor = "";
    public $terceira_cor = "";
    public $token_webhook = "";
    public $zoom = "";
    public $lat = "";
    public $lng = "";
    public $language;
    public $empresa;
    public $nome_empresa;
    public $tipo_sistema;
    public $orcamento;
    public $modulo_impressao;
    public $app_vendas;
    public $modulo_integracao;
    public $modulo_ponto;
    public $cnpj_cliente;
    public $telefone;
    public $emissor_producao;
    public $razao_social;
    public $email;
    public $responsavel;
    public $site_url;
    public $site_sistema;
    public $site_email;
    public $file_types;
    public $file_max;
    public $mailer;
    public $smtp_host;
    public $smtp_user;
    public $smtp_pass;
    public $smtp_port;
    public $langdir;
    public $aplicativo_estoque;
    public $modal_cancelar_produto_venda;
    public $modal_alterar_valor_produto_venda;

    /**
     * Core::__construct()
     * 
     * @return
     */
    function __construct()
    {
        $this->getSettings();
        $this->getLanguage();
    }

    /**
     * Core::getSettings()
     * 
     * @return
     */
    private function getSettings()
    {
        $sql = "SELECT * FROM " . self::sTable;
        $row = Registry::get("Database")->first($sql);

        $this->empresa = $row->empresa;
        $this->primeira_cor = $row->primeira_cor;
        $this->segunda_cor = $row->segunda_cor;
        $this->terceira_cor = $row->terceira_cor;
        $this->token_webhook = $row->token_webhook;
        $this->site_url = $row->site_url;
        $this->site_sistema = $row->site_sistema;
        $this->site_email = $row->site_email;
        $this->file_types = $row->file_types;
        $this->file_max = $row->file_max;
        $this->mailer = $row->mailer;
        $this->smtp_host = $row->smtp_host;
        $this->smtp_user = $row->smtp_user;
        $this->smtp_pass = $row->smtp_pass;
        $this->smtp_port = $row->smtp_port;
        $this->zoom = $row->zoom;
        $this->lat = $row->lat;
        $this->lng = $row->lng;
        $this->origem = $row->id_origem;

        $sql_empresa = "SELECT * FROM empresa WHERE inativo = 0";
        $row_empresa = Registry::get("Database")->first($sql_empresa);
        $this->tipo_sistema = $row_empresa->tipo_sistema;
        $this->orcamento = $row_empresa->orcamento;
        $this->modulo_impressao = $row_empresa->modulo_impressao;
        $this->app_vendas = $row_empresa->app_vendas;
        $this->modulo_integracao = $row_empresa->modulo_integracao;
        $this->modulo_ponto = $row_empresa->modulo_ponto;
        $this->cnpj_cliente = $row_empresa->cnpj;
        $this->telefone = $row_empresa->telefone;
        $this->emissor_producao = $row_empresa->emissor_producao;
        $this->nome_empresa = $row_empresa->nome;
        $this->razao_social = $row_empresa->razao_social;
        $this->email = $row_empresa->email;
        $this->responsavel = $row_empresa->responsavel;
        $this->aplicativo_estoque = $row_empresa->aplicativo_estoque;
        $this->modal_cancelar_produto_venda = $row_empresa->modal_cancelar_produto_venda;
        $this->modal_alterar_valor_produto_venda = $row_empresa->modal_alterar_valor_produto_venda;
    }

    /**
     * Core::configuracoes()
     * 
     * @return
     */
    public static function configuracoes()
    {
        $sql = "SELECT * FROM " . self::sTable;
        return $row = Registry::get("Database")->first($sql);
    }

    /**
     * Core::emissoaEmProducao()
     * 
     * @return
     */
    public static function emissoaEmProducao()
    {
        return ($this->emissor_producao);
    }

    /**
     * Core::is_Recibo58()
     * 
     * @return Retorna verdadeiro quando o ID_ORIGEM for igual a 58, para mudar o tamanho do recibo para 58 mm.
     */
    public function is_Recibo58()
    {
        return ($this->origem == 58);
    }

    /**
     * Core:::getLanguage()
     * 
     * @return
     */
    private function getLanguage()
    {
        $this->langdir = BASEPATH . "lang/";
        include($this->langdir . "pt-br.lang.php");
    }

    /**
     * Core::getRowById()
     * 
     * @param mixed $table
     * @param mixed $id
     * @param bool $and
     * @param bool $is_admin
     * @return
     */
    public static function getRowById($table, $id, $and = false, $is_admin = true)
    {
        $id = sanitize($id, 8, true);
        if ($and) {
            $sql = "SELECT * FROM " . (string)$table . " WHERE id = '" . Registry::get("Database")->escape((int)$id) . "' AND " . Registry::get("Database")->escape($and) . "";
        } else
            $sql = "SELECT * FROM " . (string)$table . " WHERE id = '" . Registry::get("Database")->escape((int)$id) . "'";

        $row = Registry::get("Database")->first($sql);

        if ($row) {
            return $row;
        } else {
            if ($is_admin)
                Filter::error("ID selecionado inválido na tabela [$table] - #" . $id, "Core::getRowById()");
        }
    }

    /**
     * Core::doForm()
     * 
     * @param mixed $data
     * @param string $url
     * @param integer $reset
     * @param integer $clear
     * @param string $form_id
     * @param string $msgholder
     * @return
     */
    public static function doForm($data, $form_id = "admin_form", $url = "controller.php")
    {
        $display = '<script type="text/javascript">
						$(document).ready(function() {
							$("#' . $form_id . '").submit(function(){
								var dados = $( this ).serialize();
                                var verificaModal = $("#' . $form_id . '").parents().map(function() { return this.className;  }).get().join( ", " );
                                var vinculo = "body";

                                if (verificaModal.indexOf("modal") >= 0)
                                {
                                    vinculo = "#' . $form_id . '";
                                }
                                $("#overlay").css("display", "flex");

								$.ajax({
									type: "POST",
									url: "' . $url . '",
									data: dados+ "&' . $data . '=1",
                                    error: function(e){
                                        $("#overlay").css("display", "none");
                                    },
									success: function( data )
									{
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
										if(response[2] == 1) {
											if(response[4] != 0)
												window.open("recibo.php?crediario="+response[5]+"&id="+response[4],"Recibo "+ response[4],"width=360,height=700,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=1000,top=0");
											setTimeout(function(){
												window.location.href=response[3];
											}, 1000);
										} else if(response[2] == 2) {
                                            if(response[4] != 0)
												window.open("recibo_orcamento.php?id="+response[4],"Recibo "+ response[4],"width=360,height=700,toolbar=0,menubar=0,location=0,status=0,scrollbars=0,resizable=0,left=1000,top=0");
											    setTimeout(function(){
												window.location.href=response[3];
											}, 1000);
                                        }else{
                                            $("#overlay").css("display", "none");
                                        }
									}
								});
								return false;
							});
						});
						</script>';
        print $display;
    }
}
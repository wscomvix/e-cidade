<?php
//MODULO: licitacao
//CLASSE DA ENTIDADE credenciamentotermo
class cl_credenciamentotermo
{
    // cria variaveis de erro
    public $rotulo     = null;
    public $query_sql  = null;
    public $numrows    = 0;
    public $numrows_incluir = 0;
    public $numrows_alterar = 0;
    public $numrows_excluir = 0;
    public $erro_status = null;
    public $erro_sql   = null;
    public $erro_banco = null;
    public $erro_msg   = null;
    public $erro_campo = null;
    public $pagina_retorno = null;
    // cria variaveis do arquivo
    public $l212_sequencial = 0;
    public $l212_licitacao = 0;
    public $l212_fornecedor = 0;
    public $l212_numerotermo = 0;
    public $l212_dtinicio_dia = null;
    public $l212_dtinicio_mes = null;
    public $l212_dtinicio_ano = null;
    public $l212_dtinicio = null;
    public $l212_dtfim_dia = null;
    public $l212_dtfim_mes = null;
    public $l212_dtfim_ano = null;
    public $l212_dtfim = null;
    public $l212_dtpublicacao_dia = null;
    public $l212_dtpublicacao_mes = null;
    public $l212_dtpublicacao_ano = null;
    public $l212_dtpublicacao = null;
    public $l212_veiculodepublicacao = null;
    public $l212_anousu = null;
    public $l212_observacao = null;
    public $l212_instit = null;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 l212_sequencial = int8 = Sequencial 
                 l212_licitacao = int8 = Licitacao 
                 l212_fornecedor = int8 = l212_fornecedor 
                 l212_numerotermo = int8 = N� do Termo 
                 l212_dtinicio = date = Vig�ncia 
                 l212_dtfim = date = Vig�ncia Final 
                 l212_dtpublicacao = date = Data da Publica��o 
                 l212_anousu = int4 = Ano 
                 l212_veiculodepublicacao = text = Ve�culo de Publica��o 
                 l212_observacao = text = Observa��o 
                 l212_instit = int8 = l212_instit 
                 ";

    //funcao construtor da classe
    function __construct()
    {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("credenciamentotermo");
        $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
    }

    //funcao erro
    function erro($mostra, $retorna)
    {
        if (($this->erro_status == "0") || ($mostra == true && $this->erro_status != null)) {
            echo "<script>alert(\"" . $this->erro_msg . "\");</script>";
            if ($retorna == true) {
                echo "<script>location.href='" . $this->pagina_retorno . "'</script>";
            }
        }
    }

    // funcao para atualizar campos
    function atualizacampos($exclusao = false)
    {
        if ($exclusao == false) {
            $this->l212_sequencial = ($this->l212_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["l212_sequencial"] : $this->l212_sequencial);
            $this->l212_licitacao = ($this->l212_licitacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["l212_licitacao"] : $this->l212_licitacao);
            $this->l212_fornecedor = ($this->l212_fornecedor == "" ? @$GLOBALS["HTTP_POST_VARS"]["l212_fornecedor"] : $this->l212_fornecedor);
            $this->l212_numerotermo = ($this->l212_numerotermo == "" ? @$GLOBALS["HTTP_POST_VARS"]["l212_numerotermo"] : $this->l212_numerotermo);
            if ($this->l212_dtinicio == "") {
                $this->l212_dtinicio_dia = ($this->l212_dtinicio_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["l212_dtinicio_dia"] : $this->l212_dtinicio_dia);
                $this->l212_dtinicio_mes = ($this->l212_dtinicio_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["l212_dtinicio_mes"] : $this->l212_dtinicio_mes);
                $this->l212_dtinicio_ano = ($this->l212_dtinicio_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["l212_dtinicio_ano"] : $this->l212_dtinicio_ano);
                if ($this->l212_dtinicio_dia != "") {
                    $this->l212_dtinicio = $this->l212_dtinicio_ano . "-" . $this->l212_dtinicio_mes . "-" . $this->l212_dtinicio_dia;
                }
            }
            if ($this->l212_dtfim == "") {
                $this->l212_dtfim_dia = ($this->l212_dtfim_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["l212_dtfim_dia"] : $this->l212_dtfim_dia);
                $this->l212_dtfim_mes = ($this->l212_dtfim_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["l212_dtfim_mes"] : $this->l212_dtfim_mes);
                $this->l212_dtfim_ano = ($this->l212_dtfim_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["l212_dtfim_ano"] : $this->l212_dtfim_ano);
                if ($this->l212_dtfim_dia != "") {
                    $this->l212_dtfim = $this->l212_dtfim_ano . "-" . $this->l212_dtfim_mes . "-" . $this->l212_dtfim_dia;
                }
            }
            if ($this->l212_dtpublicacao == "") {
                $this->l212_dtpublicacao_dia = ($this->l212_dtpublicacao_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["l212_dtpublicacao_dia"] : $this->l212_dtpublicacao_dia);
                $this->l212_dtpublicacao_mes = ($this->l212_dtpublicacao_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["l212_dtpublicacao_mes"] : $this->l212_dtpublicacao_mes);
                $this->l212_dtpublicacao_ano = ($this->l212_dtpublicacao_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["l212_dtpublicacao_ano"] : $this->l212_dtpublicacao_ano);
                if ($this->l212_dtpublicacao_dia != "") {
                    $this->l212_dtpublicacao = $this->l212_dtpublicacao_ano . "-" . $this->l212_dtpublicacao_mes . "-" . $this->l212_dtpublicacao_dia;
                }
            }
            $this->l212_anousu = ($this->l212_anousu == "" ? @$GLOBALS["HTTP_POST_VARS"]["l212_anousu"] : $this->l212_anousu);
            $this->l212_veiculodepublicacao = ($this->l212_veiculodepublicacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["l212_veiculodepublicacao"] : $this->l212_veiculodepublicacao);
            $this->l212_observacao = ($this->l212_observacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["l212_observacao"] : $this->l212_observacao);
            $this->l212_instit = ($this->l212_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["l212_instit"] : $this->l212_instit);
        }
    }

    // funcao para inclusao
    function incluir()
    {
        $this->atualizacampos();
        if ($this->l212_sequencial == null) {

            $result = db_query("select nextval('credenciamentotermo_l212_sequencial_seq')");
            if ($result == false) {
                $this->erro_sql = " Campo Sequencial n�o informado.";
                $this->erro_campo = "l212_sequencial";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->l212_sequencial = pg_result($result, 0, 0);
        }
        if ($this->l212_licitacao == null) {
            $this->erro_sql = " Campo Licitacao n�o informado.";
            $this->erro_campo = "l212_licitacao";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l212_fornecedor == "0") {
            $this->erro_sql = " Campo l212_fornecedor n�o informado.";
            $this->erro_campo = "l212_fornecedor";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l212_numerotermo == null) {
            $this->erro_sql = " Campo N� do Termo n�o informado.";
            $this->erro_campo = "l212_numerotermo";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l212_dtinicio == null) {
            $this->erro_sql = " Campo Vig�ncia n�o informado.";
            $this->erro_campo = "l212_dtinicio_dia";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l212_dtfim == null) {
            $this->erro_sql = " Campo Vig�ncia Final n�o informado.";
            $this->erro_campo = "l212_dtfim_dia";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l212_dtpublicacao == null) {
            $this->erro_sql = " Campo Data da Publica��o n�o informado.";
            $this->erro_campo = "l212_dtpublicacao_dia";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->l212_anousu == null) {
            $this->erro_sql = " Campo Ano n�o informado.";
            $this->erro_campo = "l212_anousu";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->l212_instit == null) {
            $this->erro_sql = " Campo instituicao n�o informado.";
            $this->erro_campo = "l212_observacao";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into credenciamentotermo(
                                       l212_sequencial 
                                      ,l212_licitacao 
                                      ,l212_fornecedor 
                                      ,l212_numerotermo 
                                      ,l212_dtinicio 
                                      ,l212_dtfim 
                                      ,l212_dtpublicacao 
                                      ,l212_veiculodepublicacao
                                      ,l212_anousu 
                                      ,l212_observacao
                                      ,l212_instit 
                       )
                values (
                                $this->l212_sequencial 
                               ,$this->l212_licitacao 
                               ,$this->l212_fornecedor 
                               ,'$this->l212_numerotermo' 
                               ," . ($this->l212_dtinicio == "null" || $this->l212_dtinicio == "" ? "null" : "'" . $this->l212_dtinicio . "'") . " 
                               ," . ($this->l212_dtfim == "null" || $this->l212_dtfim == "" ? "null" : "'" . $this->l212_dtfim . "'") . " 
                               ," . ($this->l212_dtpublicacao == "null" || $this->l212_dtpublicacao == "" ? "null" : "'" . $this->l212_dtpublicacao . "'") . " 
                               ,'$this->l212_veiculodepublicacao' 
                               ,$this->l212_anousu 
                               ,'$this->l212_observacao'
                               ,$this->l212_instit 
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql   = "credenciamentotermo () nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "credenciamentotermo j� Cadastrado";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql   = "credenciamentotermo () nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
            && ($lSessaoDesativarAccount === false))) {
        }
        return true;
    }

    // funcao para alteracao
    function alterar($l212_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " update credenciamentotermo set ";
        $virgula = "";
        if (trim($this->l212_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l212_sequencial"])) {
            $sql  .= $virgula . " l212_sequencial = $this->l212_sequencial ";
            $virgula = ",";
            if (trim($this->l212_sequencial) == null) {
                $this->erro_sql = " Campo Sequencial n�o informado.";
                $this->erro_campo = "l212_sequencial";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->l212_licitacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l212_licitacao"])) {
            $sql  .= $virgula . " l212_licitacao = $this->l212_licitacao ";
            $virgula = ",";
            if (trim($this->l212_licitacao) == null) {
                $this->erro_sql = " Campo Licitacao n�o informado.";
                $this->erro_campo = "l212_licitacao";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->l212_fornecedor) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l212_fornecedor"])) {
            $sql  .= $virgula . " l212_fornecedor = $this->l212_fornecedor ";
            $virgula = ",";
            if (trim($this->l212_fornecedor) == "0") {
                $this->erro_sql = " Campo l212_fornecedor n�o informado.";
                $this->erro_campo = "l212_fornecedor";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: Selecione o fornecedor";
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->l212_numerotermo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l212_numerotermo"])) {
            $sql  .= $virgula . " l212_numerotermo = $this->l212_numerotermo ";
            $virgula = ",";
            if (trim($this->l212_numerotermo) == null) {
                $this->erro_sql = " Campo N� do Termo n�o informado.";
                $this->erro_campo = "l212_numerotermo";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->l212_dtinicio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l212_dtinicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l212_dtinicio_dia"] != "")) {
            $sql  .= $virgula . " l212_dtinicio = '$this->l212_dtinicio' ";
            $virgula = ",";
            if (trim($this->l212_dtinicio) == null) {
                $this->erro_sql = " Campo Vig�ncia n�o informado.";
                $this->erro_campo = "l212_dtinicio_dia";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        } else {
            if (isset($GLOBALS["HTTP_POST_VARS"]["l212_dtinicio_dia"])) {
                $sql  .= $virgula . " l212_dtinicio = null ";
                $virgula = ",";
                if (trim($this->l212_dtinicio) == null) {
                    $this->erro_sql = " Campo Vig�ncia n�o informado.";
                    $this->erro_campo = "l212_dtinicio_dia";
                    $this->erro_banco = "";
                    $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                    $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                    $this->erro_status = "0";
                    return false;
                }
            }
        }
        if (trim($this->l212_dtfim) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l212_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l212_dtfim_dia"] != "")) {
            $sql  .= $virgula . " l212_dtfim = '$this->l212_dtfim' ";
            $virgula = ",";
            if (trim($this->l212_dtfim) == null) {
                $this->erro_sql = " Campo Vig�ncia Final n�o informado.";
                $this->erro_campo = "l212_dtfim_dia";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        } else {
            if (isset($GLOBALS["HTTP_POST_VARS"]["l212_dtfim_dia"])) {
                $sql  .= $virgula . " l212_dtfim = null ";
                $virgula = ",";
                if (trim($this->l212_dtfim) == null) {
                    $this->erro_sql = " Campo Vig�ncia Final n�o informado.";
                    $this->erro_campo = "l212_dtfim_dia";
                    $this->erro_banco = "";
                    $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                    $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                    $this->erro_status = "0";
                    return false;
                }
            }
        }
        if (trim($this->l212_dtpublicacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l212_dtpublicacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l212_dtpublicacao_dia"] != "")) {
            $sql  .= $virgula . " l212_dtpublicacao = '$this->l212_dtpublicacao' ";
            $virgula = ",";
            if (trim($this->l212_dtpublicacao) == null) {
                $this->erro_sql = " Campo Data da Publica��o n�o informado.";
                $this->erro_campo = "l212_dtpublicacao_dia";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        } else {
            if (isset($GLOBALS["HTTP_POST_VARS"]["l212_dtpublicacao_dia"])) {
                $sql  .= $virgula . " l212_dtpublicacao = null ";
                $virgula = ",";
                if (trim($this->l212_dtpublicacao) == null) {
                    $this->erro_sql = " Campo Data da Publica��o n�o informado.";
                    $this->erro_campo = "l212_dtpublicacao_dia";
                    $this->erro_banco = "";
                    $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                    $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                    $this->erro_status = "0";
                    return false;
                }
            }
        }
        if (trim($this->l212_veiculodepublicacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l212_veiculodepublicacao"])) {
            $sql  .= $virgula . " l212_veiculodepublicacao = '$this->l212_veiculodepublicacao' ";
            $virgula = ",";
            if (trim($this->l212_veiculodepublicacao) == null) {
                $this->erro_sql = " Campo Ve�culo de Publica��o n�o informado.";
                $this->erro_campo = "l212_veiculodepublicacao";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l212_anousu) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l212_anousu"])) {
            $sql  .= $virgula . " l212_anousu = $this->l212_anousu ";
            $virgula = ",";
            if (trim($this->l212_anousu) == null) {
                $this->erro_sql = " Campo Ano n�o informado.";
                $this->erro_campo = "l212_anousu";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l212_observacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l212_observacao"])) {
            $sql  .= $virgula . " l212_observacao = '$this->l212_observacao' ";
            $virgula = ",";
        }
        $sql .= " where ";
        $sql .= "l212_sequencial = $l212_sequencial";
        $result = db_query($sql); //echo $sql;exit;
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "credenciamentotermo nao Alterado. Alteracao Abortada.\\n";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "credenciamentotermo nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    // funcao para exclusao
    function excluir($l212_sequencial = null, $dbwhere = null)
    {

        $sql = " delete from credenciamentotermo
                    where ";
        $sql2 = "";
        if ($dbwhere == null || $dbwhere == "") {
            $sql2 = "l212_sequencial = $l212_sequencial";
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "credenciamentotermo nao Exclu�do. Exclus�o Abortada.\\n";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "credenciamentotermo nao Encontrado. Exclus�o n�o Efetuada.\\n";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = pg_affected_rows($result);
                return true;
            }
        }
    }

    // funcao do recordset
    function sql_record($sql)
    {
        $result = db_query($sql);
        if ($result == false) {
            $this->numrows    = 0;
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Erro ao selecionar os registros.";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $this->numrows = pg_numrows($result);
        if ($this->numrows == 0) {
            $this->erro_banco = "";
            $this->erro_sql   = "Record Vazio na Tabela:credenciamentotermo";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    // funcao do sql
    function sql_query($l212_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = explode("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from credenciamentotermo ";
        $sql .= " inner join liclicita on liclicita.l20_codigo = credenciamentotermo.l212_licitacao ";
        $sql .= " inner join cgm on cgm.z01_numcgm = l212_fornecedor ";
        $sql .= " inner join cflicita on l03_codigo = l20_codtipocom ";
        $sql .= " inner join pctipocompra on pc50_codcom = l03_codcom ";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($l212_sequencial != "" && $l212_sequencial != null) {
                $sql2 = " where credenciamentotermo.l212_sequencial = '$l212_sequencial'";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = explode("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    // funcao do sql
    function sql_query_file($l212_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = explode("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from credenciamentotermo ";
        $sql2 = "";
        if ($dbwhere == "") {
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = explode("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    // funcao do sql
    function sql_query_itens($l212_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = explode("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from credenciamentotermo ";
        $sql .= " INNER JOIN liclicita on liclicita.l20_codigo = credenciamentotermo.l212_licitacao ";
        $sql .= " INNER JOIN cgm on cgm.z01_numcgm = l212_fornecedor ";
        $sql .= " INNER JOIN cflicita on l03_codigo = l20_codtipocom ";
        $sql .= " INNER JOIN pctipocompra on pc50_codcom = l03_codcom ";
        $sql .= " INNER JOIN credenciamento on l205_fornecedor = cgm.z01_numcgm and l205_licitacao = l20_codigo ";
        $sql .= " INNER JOIN liclicitem ON liclicitem.l21_codliclicita = credenciamento.l205_licitacao AND liclicitem.l21_codpcprocitem = credenciamento.l205_item ";
        $sql .= " INNER JOIN pcprocitem ON pcprocitem.pc81_codprocitem = credenciamento.l205_item ";
        $sql .= " INNER JOIN pcorcamitemproc ON pcorcamitemproc.pc31_pcprocitem = pcprocitem.pc81_codprocitem ";
        $sql .= " INNER JOIN pcorcamitem ON pcorcamitem.pc22_orcamitem = pcorcamitemproc.pc31_orcamitem ";
        $sql .= " INNER JOIN pcorcamval ON pcorcamval.pc23_orcamitem = pcorcamitem.pc22_orcamitem ";
        $sql .= " INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem ";
        $sql .= " INNER JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo ";
        $sql .= " INNER JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater ";
        $sql .= " INNER JOIN solicitemunid ON solicitemunid.pc17_codigo = solicitem.pc11_codigo ";
        $sql .= " INNER JOIN matunid ON matunid.m61_codmatunid = solicitemunid.pc17_unid ";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($l212_sequencial != "" && $l212_sequencial != null) {
                $sql2 = " where credenciamentotermo.l212_sequencial = '$l212_sequencial'";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = explode("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    function sql_query_itensprecomedio($l212_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = explode("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from credenciamentotermo ";
        $sql .= " INNER JOIN liclicita on liclicita.l20_codigo = credenciamentotermo.l212_licitacao ";
        $sql .= " INNER JOIN cgm on cgm.z01_numcgm = l212_fornecedor ";
        $sql .= " INNER JOIN cflicita on l03_codigo = l20_codtipocom ";
        $sql .= " INNER JOIN pctipocompra on pc50_codcom = l03_codcom ";
        $sql .= " INNER JOIN credenciamento on l205_fornecedor = cgm.z01_numcgm and l205_licitacao = l20_codigo ";
        $sql .= " INNER JOIN liclicitem ON liclicitem.l21_codliclicita = credenciamento.l205_licitacao AND liclicitem.l21_codpcprocitem = credenciamento.l205_item ";
        $sql .= " INNER JOIN pcprocitem ON pcprocitem.pc81_codprocitem = credenciamento.l205_item ";
        $sql .= " INNER JOIN pcorcamitemproc ON pcorcamitemproc.pc31_pcprocitem = pcprocitem.pc81_codprocitem ";
        $sql .= " INNER JOIN precoreferencia ON si01_processocompra = pc81_codproc ";
        $sql .= " INNER JOIN itemprecoreferencia ON itemprecoreferencia.si02_precoreferencia = precoreferencia.si01_sequencial  AND itemprecoreferencia.si02_itemproccompra = pcorcamitemproc.pc31_orcamitem";
        $sql .= " INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem ";
        $sql .= " INNER JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo ";
        $sql .= " INNER JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater ";
        $sql .= " INNER JOIN solicitemunid ON solicitemunid.pc17_codigo = solicitem.pc11_codigo ";
        $sql .= " INNER JOIN matunid ON matunid.m61_codmatunid = solicitemunid.pc17_unid ";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($l212_sequencial != "" && $l212_sequencial != null) {
                $sql2 = " where credenciamentotermo.l212_sequencial = '$l212_sequencial'";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = explode("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
}

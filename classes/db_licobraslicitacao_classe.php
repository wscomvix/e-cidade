<?php
//MODULO: Obras
//CLASSE DA ENTIDADE licobraslicitacao
class cl_licobraslicitacao
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
    public $obr07_sequencial = 0;
    public $obr07_processo = 0;
    public $obr07_exercicio = 0;
    public $obr07_objeto = null;
    public $obr07_tipoprocesso = 0;
    public $obr07_instit = 0;
    public $obr07_modalidade = null;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 obr07_sequencial = int8 = C�d. Sequencial 
                 obr07_processo = int8 = Processo Licitat�rio 
                 obr07_exercicio = int8 = Exercicio 
                 obr07_objeto = text = Objeto 
                 obr07_tipoprocesso = int8 = Tipo Processo 
                 obr07_instit = int8 = instit 
                 obr07_modalidade = int = Modalidade
                 ";

    //funcao construtor da classe
    function __construct()
    {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("licobraslicitacao");
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
            $this->obr07_sequencial = ($this->obr07_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["obr07_sequencial"] : $this->obr07_sequencial);
            $this->obr07_processo = ($this->obr07_processo == "" ? @$GLOBALS["HTTP_POST_VARS"]["obr07_processo"] : $this->obr07_processo);
            $this->obr07_exercicio = ($this->obr07_exercicio == "" ? @$GLOBALS["HTTP_POST_VARS"]["obr07_exercicio"] : $this->obr07_exercicio);
            $this->obr07_objeto = ($this->obr07_objeto == "" ? @$GLOBALS["HTTP_POST_VARS"]["obr07_objeto"] : $this->obr07_objeto);
            $this->obr07_tipoprocesso = ($this->obr07_tipoprocesso == "" ? @$GLOBALS["HTTP_POST_VARS"]["obr07_tipoprocesso"] : $this->obr07_tipoprocesso);
            $this->obr07_instit = ($this->obr07_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["obr07_instit"] : $this->obr07_instit);
            $this->obr07_modalidade = ($this->obr07_modalidade == "" ? @$GLOBALS["HTTP_POST_VARS"]["obr07_modalidade"] : $this->obr07_modalidade);
        } else {
        }
    }

    // funcao para inclusao
    function incluir()
    {
        $this->atualizacampos();

        if ($this->validacaoNumeroModalidade() == false) return false;
        if ($this->validacaoNumeroProcesso() == false) return false;


        if ($this->obr07_sequencial == null) {
            $result = db_query("select nextval('licobraslicitacao_obr07_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: licobrasresponsaveis_obr07_sequencial_seq do campo: obr07_sequencial";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->obr07_sequencial = pg_result($result, 0, 0);
        }
        if ($this->obr07_processo == null) {
            $this->erro_sql = " Campo Processo Licitat�rio n�o informado.";
            $this->erro_campo = "obr07_processo";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->obr07_exercicio == null) {
            $this->erro_sql = " Campo Exercicio n�o informado.";
            $this->erro_campo = "obr07_exercicio";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->obr07_objeto == null) {
            $this->erro_sql = " Campo Objeto n�o informado.";
            $this->erro_campo = "obr07_objeto";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->obr07_tipoprocesso == null || $this->obr07_tipoprocesso == 0) {
            $this->erro_sql = " Campo Tipo Processo n�o informado.";
            $this->erro_campo = "obr07_tipoprocesso";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->obr07_instit == null) {
            $this->erro_sql = " Campo instit n�o informado.";
            $this->erro_campo = "obr07_instit";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->obr07_modalidade == null) {
            $this->erro_sql = " Campo Modalidade n�o informado.";
            $this->erro_campo = "obr07_modalidade";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        $sql = "insert into licobraslicitacao(
                                       obr07_sequencial 
                                      ,obr07_processo 
                                      ,obr07_exercicio 
                                      ,obr07_objeto 
                                      ,obr07_tipoprocesso 
                                      ,obr07_instit
                                      ,obr07_modalidade 
                       )
                values (
                                $this->obr07_sequencial 
                               ,$this->obr07_processo 
                               ,$this->obr07_exercicio 
                               ,'$this->obr07_objeto' 
                               ,$this->obr07_tipoprocesso 
                               ,$this->obr07_instit
                               ,$this->obr07_modalidade 
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql   = "cadastro de licitacao obras () nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "cadastro de licitacao obras j� Cadastrado";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql   = "cadastro de licitacao obras () nao Inclu�do. Inclusao Abortada.";
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
    function alterar($obr07_sequencial = null)
    {
        $this->atualizacampos();
        $rsLicobraslicitacao = db_query("select * from licobraslicitacao where obr07_modalidade = $this->obr07_modalidade and obr07_exercicio = $this->obr07_exercicio and obr07_tipoprocesso = $this->obr07_tipoprocesso and obr07_sequencial = $obr07_sequencial");
        if (pg_num_rows($rsLicobraslicitacao) < 1) {
            if ($this->validacaoNumeroModalidade() == false) return false;
        }

        $rsLicobraslicitacao = db_query("select * from licobraslicitacao where obr07_processo = $this->obr07_processo and obr07_exercicio = $this->obr07_exercicio and obr07_sequencial = $obr07_sequencial");
        if (pg_num_rows($rsLicobraslicitacao) < 1) {
            if ($this->validacaoNumeroProcesso() == false) return false;
        }

        $sql = " update licobraslicitacao set ";
        $virgula = "";
        if (trim($this->obr07_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["obr07_sequencial"])) {
            $sql  .= $virgula . " obr07_sequencial = $this->obr07_sequencial ";
            $virgula = ",";
            if (trim($this->obr07_sequencial) == null) {
                $this->erro_sql = " Campo C�d. Sequencial n�o informado.";
                $this->erro_campo = "obr07_sequencial";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->obr07_processo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["obr07_processo"])) {
            $sql  .= $virgula . " obr07_processo = $this->obr07_processo ";
            $virgula = ",";
            if (trim($this->obr07_processo) == null) {
                $this->erro_sql = " Campo Processo Licitat�rio n�o informado.";
                $this->erro_campo = "obr07_processo";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->obr07_exercicio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["obr07_exercicio"])) {
            $sql  .= $virgula . " obr07_exercicio = $this->obr07_exercicio ";
            $virgula = ",";
            if (trim($this->obr07_exercicio) == null) {
                $this->erro_sql = " Campo Exercicio n�o informado.";
                $this->erro_campo = "obr07_exercicio";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->obr07_objeto) != "" || isset($GLOBALS["HTTP_POST_VARS"]["obr07_objeto"])) {
            $sql  .= $virgula . " obr07_objeto = '$this->obr07_objeto' ";
            $virgula = ",";
            if (trim($this->obr07_objeto) == null) {
                $this->erro_sql = " Campo Objeto n�o informado.";
                $this->erro_campo = "obr07_objeto";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->obr07_tipoprocesso) != "" || isset($GLOBALS["HTTP_POST_VARS"]["obr07_tipoprocesso"]) ||  $this->obr07_tipoprocesso == 0) {
            $sql  .= $virgula . " obr07_tipoprocesso = $this->obr07_tipoprocesso ";
            $virgula = ",";
            if (trim($this->obr07_tipoprocesso) == null) {
                $this->erro_sql = " Campo Tipo Processo n�o informado.";
                $this->erro_campo = "obr07_tipoprocesso";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->obr07_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["obr07_instit"])) {
            $sql  .= $virgula . " obr07_instit = $this->obr07_instit ";
            $virgula = ",";
            if (trim($this->obr07_instit) == null) {
                $this->erro_sql = " Campo instit n�o informado.";
                $this->erro_campo = "obr07_instit";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->obr07_modalidade) != "" || isset($GLOBALS["HTTP_POST_VARS"]["obr07_modalidade"])) {
            $sql  .= $virgula . " obr07_modalidade = $this->obr07_modalidade ";
            $virgula = ",";
            if (trim($this->obr07_modalidade) == null) {
                $this->erro_sql = " Campo Modalidade n�o informado.";
                $this->erro_campo = "obr07_modalidade";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        $sql .= "obr07_sequencial = '$obr07_sequencial'";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "cadastro de licitacao obras nao Alterado. Alteracao Abortada.\\n";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "cadastro de licitacao obras nao foi Alterado. Alteracao Executada.\\n";
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
    function excluir($obr07_sequencial = null, $dbwhere = null)
    {

        $sql = " delete from licobraslicitacao
                    where ";
        $sql2 = "";
        if ($dbwhere == null || $dbwhere == "") {
            $sql2 = "obr07_sequencial = '$obr07_sequencial'";
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "cadastro de licitacao obras nao Exclu�do. Exclus�o Abortada.\\n";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "cadastro de licitacao obras nao Encontrado. Exclus�o n�o Efetuada.\\n";
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
            $this->erro_sql   = "Record Vazio na Tabela:licobraslicitacao";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    // funcao do sql
    function sql_query($obr07_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from licobraslicitacao ";
        $sql .= " inner join pctipocompratribunal on l44_sequencial = obr07_tipoprocesso";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($obr07_sequencial != "" && $obr07_sequencial != null) {
                $sql2 = " where licobraslicitacao.obr07_sequencial = '$obr07_sequencial'";
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
    function sql_query_file($obr07_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from licobraslicitacao ";
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

    function validacaoNumeroModalidade()
    {

        $rsLicobraslicitacao = db_query("select * from licobraslicitacao where obr07_modalidade = $this->obr07_modalidade and obr07_exercicio = $this->obr07_exercicio and obr07_tipoprocesso = $this->obr07_tipoprocesso");
        if (pg_num_rows($rsLicobraslicitacao) > 0) {
            $this->erro_sql = "J� existe a modalidade $this->obr07_modalidade para o ano de exerc�cio e tipo de processo informado .";
            $this->erro_campo = "obr07_modalidade";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return true;
    }

    function validacaoNumeroProcesso()
    {

        $rsLicobraslicitacao = db_query("select * from licobraslicitacao where obr07_processo = $this->obr07_processo and obr07_exercicio = $this->obr07_exercicio");
        if (pg_num_rows($rsLicobraslicitacao) > 0) {
            $this->erro_sql = "J� existe o processo licit�torio $this->obr07_processo para o ano de exerc�cio informado.";
            $this->erro_campo = "obr07_processo";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return true;
    }
}

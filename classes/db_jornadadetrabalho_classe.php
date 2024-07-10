<?php
//MODULO: pessoal
//CLASSE DA ENTIDADE jornadadetrabalho
class cl_jornadadetrabalho
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
    public $jt_sequencial = 0;
    public $jt_nome = null;
    public $jt_descricao = null;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 jt_sequencial = int4 = Sequencial
                 jt_nome = varchar(50) = Nome
                 jt_descricao = varchar(100) = Descri��o
                 ";

    //funcao construtor da classe
    function __construct()
    {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("jornadadetrabalho");
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
            $this->jt_sequencial = ($this->jt_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["jt_sequencial"] : $this->jt_sequencial);
            $this->jt_nome = ($this->jt_nome == "" ? @$GLOBALS["HTTP_POST_VARS"]["jt_nome"] : $this->jt_nome);
            $this->jt_descricao = ($this->jt_descricao == "" ? @$GLOBALS["HTTP_POST_VARS"]["jt_descricao"] : $this->jt_descricao);
        } else {
            $this->jt_sequencial = ($this->jt_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["jt_sequencial"] : $this->jt_sequencial);
        }
    }

    // funcao para inclusao
    function incluir($jt_sequencial)
    {
        $this->atualizacampos();
        if ($this->jt_nome == null) {
            $this->erro_sql = " Campo Nome n�o informado.";
            $this->erro_campo = "jt_nome";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->jt_descricao == null) {
            $this->erro_sql = " Campo Descri��o n�o informado.";
            $this->erro_campo = "jt_descricao";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($jt_sequencial == "" || $jt_sequencial == null) {
            $result = db_query("select nextval('jornadadetrabalho_jt_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql   = "Verifique o cadastro da sequencia: jornadadetrabalho_jt_sequencial_seq do campo: jt_sequencial";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->jt_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from jornadadetrabalho_jt_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $jt_sequencial)) {
                $this->erro_sql = " Campo jt_sequencial maior que �ltimo n�mero da sequencia.";
                $this->erro_banco = "Sequencia menor que este n�mero.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->jt_sequencial = $jt_sequencial;
            }
        }
        if (($this->jt_sequencial == null) || ($this->jt_sequencial == "")) {
            $this->erro_sql = " Campo jt_sequencial nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into jornadadetrabalho(
                                       jt_sequencial
                                      ,jt_nome
                                      ,jt_descricao
                       )
                values (
                                $this->jt_sequencial
                               ,'$this->jt_nome'
                               ,'$this->jt_descricao'
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql   = "jornadadetrabalho ($this->jt_sequencial) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "jornadadetrabalho j� Cadastrado";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql   = "jornadadetrabalho ($this->jt_sequencial) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->jt_sequencial;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        // $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        // if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        //     && ($lSessaoDesativarAccount === false))) {

        //     $resaco = $this->sql_record($this->sql_query_file($this->jt_sequencial));
        //     if (($resaco != false) || ($this->numrows != 0)) {

        //         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        //         $acount = pg_result($resac, 0, 0);
        //         $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        //         $resac = db_query("insert into db_acountkey values($acount,1009247,'$this->jt_sequencial','I')");
        //         $resac = db_query("insert into db_acount values($acount,1010192,1009247,'','" . AddSlashes(pg_result($resaco, 0, 'jt_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        //         $resac = db_query("insert into db_acount values($acount,1010192,1009248,'','" . AddSlashes(pg_result($resaco, 0, 'jt_nome')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        //         $resac = db_query("insert into db_acount values($acount,1010192,1009250,'','" . AddSlashes(pg_result($resaco, 0, 'jt_descricao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        //     }
        // }
        return true;
    }

    // funcao para alteracao
    function alterar($jt_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " update jornadadetrabalho set ";
        $virgula = "";
        if (trim($this->jt_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["jt_sequencial"])) {
            $sql  .= $virgula . " jt_sequencial = $this->jt_sequencial ";
            $virgula = ",";
            if (trim($this->jt_sequencial) == null) {
                $this->erro_sql = " Campo Sequencial n�o informado.";
                $this->erro_campo = "jt_sequencial";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->jt_nome) != "" || isset($GLOBALS["HTTP_POST_VARS"]["jt_nome"])) {
            $sql  .= $virgula . " jt_nome = '$this->jt_nome' ";
            $virgula = ",";
            if (trim($this->jt_nome) == null) {
                $this->erro_sql = " Campo Nome n�o informado.";
                $this->erro_campo = "jt_nome";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->jt_descricao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["jt_descricao"])) {
            $sql  .= $virgula . " jt_descricao = '$this->jt_descricao' ";
            $virgula = ",";
            if (trim($this->jt_descricao) == null) {
                $this->erro_sql = " Campo Descri��o n�o informado.";
                $this->erro_campo = "jt_descricao";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($jt_sequencial != null) {
            $sql .= " jt_sequencial = $this->jt_sequencial";
        }
        // $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        // if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        //     && ($lSessaoDesativarAccount === false))) {

        //     $resaco = $this->sql_record($this->sql_query_file($this->jt_sequencial));
        //     if ($this->numrows > 0) {

        //         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

        //             $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        //             $acount = pg_result($resac, 0, 0);
        //             $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        //             $resac = db_query("insert into db_acountkey values($acount,1009247,'$this->jt_sequencial','A')");
        //             if (isset($GLOBALS["HTTP_POST_VARS"]["jt_sequencial"]) || $this->jt_sequencial != "")
        //                 $resac = db_query("insert into db_acount values($acount,1010192,1009247,'" . AddSlashes(pg_result($resaco, $conresaco, 'jt_sequencial')) . "','$this->jt_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        //             if (isset($GLOBALS["HTTP_POST_VARS"]["jt_nome"]) || $this->jt_nome != "")
        //                 $resac = db_query("insert into db_acount values($acount,1010192,1009248,'" . AddSlashes(pg_result($resaco, $conresaco, 'jt_nome')) . "','$this->jt_nome'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        //             if (isset($GLOBALS["HTTP_POST_VARS"]["jt_descricao"]) || $this->jt_descricao != "")
        //                 $resac = db_query("insert into db_acount values($acount,1010192,1009250,'" . AddSlashes(pg_result($resaco, $conresaco, 'jt_descricao')) . "','$this->jt_descricao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        //         }
        //     }
        // }
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "jornadadetrabalho nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->jt_sequencial;
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "jornadadetrabalho nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->jt_sequencial;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $this->jt_sequencial;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    // funcao para exclusao
    function excluir($jt_sequencial = null, $dbwhere = null)
    {

        // $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        // if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        //     && ($lSessaoDesativarAccount === false))) {

        //     if ($dbwhere == null || $dbwhere == "") {

        //         $resaco = $this->sql_record($this->sql_query_file($jt_sequencial));
        //     } else {
        //         $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
        //     }
        //     if (($resaco != false) || ($this->numrows != 0)) {

        //         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

        //             $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
        //             $acount = pg_result($resac, 0, 0);
        //             $resac  = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        //             $resac  = db_query("insert into db_acountkey values($acount,1009247,'$jt_sequencial','E')");
        //             $resac  = db_query("insert into db_acount values($acount,1010192,1009247,'','" . AddSlashes(pg_result($resaco, $iresaco, 'jt_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        //             $resac  = db_query("insert into db_acount values($acount,1010192,1009248,'','" . AddSlashes(pg_result($resaco, $iresaco, 'jt_nome')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        //             $resac  = db_query("insert into db_acount values($acount,1010192,1009250,'','" . AddSlashes(pg_result($resaco, $iresaco, 'jt_descricao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        //         }
        //     }
        // }
        $sql = " delete from jornadadetrabalho
                    where ";
        $sql2 = "";
        if ($dbwhere == null || $dbwhere == "") {
            if ($jt_sequencial != "") {
                if ($sql2 != "") {
                    $sql2 .= " and ";
                }
                $sql2 .= " jt_sequencial = $jt_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "jornadadetrabalho nao Exclu�do. Exclus�o Abortada.\\n";
            $this->erro_sql .= "Valores : " . $jt_sequencial;
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "jornadadetrabalho nao Encontrado. Exclus�o n�o Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $jt_sequencial;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $jt_sequencial;
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
            $this->erro_sql   = "Record Vazio na Tabela:jornadadetrabalho";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    // funcao do sql
    function sql_query($jt_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from jornadadetrabalho ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($jt_sequencial != null) {
                $sql2 .= " where jornadadetrabalho.jt_sequencial = $jt_sequencial ";
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
    function sql_query_file($jt_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from jornadadetrabalho ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($jt_sequencial != null) {
                $sql2 .= " where jornadadetrabalho.jt_sequencial = $jt_sequencial ";
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

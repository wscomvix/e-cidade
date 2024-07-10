<?
//MODULO: sicom
//CLASSE DA ENTIDADE dipr102024
class cl_dipr102024
{
    // cria variaveis de erro
    var $rotulo = null;
    var $query_sql = null;
    var $numrows = 0;
    var $numrows_incluir = 0;
    var $numrows_alterar = 0;
    var $numrows_excluir = 0;
    var $erro_status = null;
    var $erro_sql = null;
    var $erro_banco = null;
    var $erro_msg = null;
    var $erro_campo = null;
    var $pagina_retorno = null;
    // cria variaveis do arquivo
    var $si230_sequencial = 0;
    var $si230_tiporegistro = 0;
    var $si230_tipocadastro = 0;
    var $si230_segregacaomassa = 0;
    var $si230_benefcustesouro = 0;
    var $si230_atonormativo = 0;
    var $si230_dtatonormativo = 0;
    var $si230_mes = 0;
    var $si230_instit = 0;
    var $si230_nroatonormasegremassa;
    var $si230_dtatonormasegremassa;
    var $si230_planodefatuarial;
    var $si230_atonormplanodefat;
    var $si230_dtatoplanodefat;
    // cria propriedade com as variaveis do arquivo
    var $campos = "
                 si230_sequencial = int8 = sequencial
                 si230_tiporegistro = int8 = Tipo do  registro
                 si230_tipocadastro = int8 = Tipo de Cadastro
                 si230_segregacaomassa = int8 = Segrega��o da massa
                 si230_benefcustesouro = int8 = Possui beneficio custeados com recurso do tesouro
                 si230_atonormativo = int8 = Ato normativo
                 si230_dtatonormativo = date = Data Ato Normativo
                 si230_mes = int8 = M�s
                 si230_instit = int8 = Institui��o
                 si230_nroatonormasegremassa = int8 = N�mero do ato normativo que implementou ou desfez a segrega��o da massa
                 si230_dtatonormasegremassa = date = Data do ato normativo que implementou ou desfez a segrega��o da massa
                 si230_planodefatuarial = int8 = Houve necessidade de implementar plano de equacionamento de d�ficit atuarial?
                 si230_atonormplanodefat = int8 Ato normativo que estabeleceu o plano de equacionamento do d�ficit atuarial
                 si230_dtatoplanodefat = date = Data do ato normativo que estabeleceu o plano de equacionamento do d�ficit atuarial
                 ";

    //funcao construtor da classe
    function cl_dipr102024()
    {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("dipr102024");
        $this->pagina_retorno = basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
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
            $this->si230_sequencial = ($this->si230_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si230_sequencial"] : $this->si230_sequencial);
            $this->si230_tiporegistro = ($this->si230_tiporegistro == "" ? @$GLOBALS["HTTP_POST_VARS"]["si230_tiporegistro"] : $this->si230_tiporegistro);
            $this->si230_tipocadastro = ($this->si230_tipocadastro == "" ? @$GLOBALS["HTTP_POST_VARS"]["si230_tipocadastro"] : $this->si230_tipocadastro);
            $this->si230_segregacaomassa = ($this->si230_segregacaomassa == "" ? @$GLOBALS["HTTP_POST_VARS"]["si230_segregacaomassa"] : $this->si230_segregacaomassa);
            $this->si230_benefcustesouro = ($this->si230_benefcustesouro == "" ? @$GLOBALS["HTTP_POST_VARS"]["si230_benefcustesouro"] : $this->si230_benefcustesouro);
            $this->si230_atonormativo = ($this->si230_atonormativo == "" ? @$GLOBALS["HTTP_POST_VARS"]["si230_atonormativo"] : $this->si230_atonormativo);
            $this->atualizaCampoData("si230_dtatonormativo");
            $this->si230_mes = ($this->si230_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si230_mes"] : $this->si230_mes);
            $this->si230_instit = ($this->si230_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["si230_instit"] : $this->si230_instit);
            $this->si230_nroatonormasegremassa = ($this->si230_nroatonormasegremassa == "" ? @$GLOBALS["HTTP_POST_VARS"]["si230_nroatonormasegremassa"] : $this->si230_nroatonormasegremassa);
            $this->atualizaCampoData("si230_dtatonormasegremassa");
            $this->si230_dtatonormasegremassa = ($this->si230_dtatonormasegremassa == "" ? @$GLOBALS["HTTP_POST_VARS"]["si230_dtatonormasegremassa"] : $this->si230_dtatonormasegremassa);
            $this->si230_planodefatuarial = ($this->si230_planodefatuarial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si230_planodefatuarial"] : $this->si230_planodefatuarial);
            $this->si230_atonormplanodefat = ($this->si230_atonormplanodefat == "" ? @$GLOBALS["HTTP_POST_VARS"]["si230_atonormplanodefat"] : $this->si230_atonormplanodefat);
            $this->atualizaCampoData("si230_dtatoplanodefat");
        } else {
            $this->si230_sequencial = ($this->si230_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si230_sequencial"] : $this->si230_sequencial);
        }
    }

    function atualizaCampoData($nomeCampo)
    {
        $nomeCampoDia = "{$nomeCampo}_dia";
        $nomeCampoMes = "{$nomeCampo}_mes";
        $nomeCampoAno = "{$nomeCampo}_ano";
        if ($this->$nomeCampo == "") {
            $this->$nomeCampoDia = ($this->$nomeCampoDia == "" ? @$GLOBALS["HTTP_POST_VARS"][$nomeCampoDia] : $this->$nomeCampoDia);
            $this->$nomeCampoMes = ($this->$nomeCampoMes == "" ? @$GLOBALS["HTTP_POST_VARS"][$nomeCampoMes] : $this->$nomeCampoMes);
            $this->$nomeCampoAno = ($this->$nomeCampoAno == "" ? @$GLOBALS["HTTP_POST_VARS"][$nomeCampoAno] : $this->$nomeCampoAno);
            if ($this->$nomeCampoDia != "") {
                $this->$nomeCampo = $this->$nomeCampoAno . "-" . $this->$nomeCampoMes . "-" . $this->$nomeCampoDia;
            }
        }
    }

    // funcao para inclusao
    function incluir($si230_sequencial)
    {
        $this->atualizacampos();
        if ($this->si230_atonormativo == null) {
            $this->si230_atonormativo = "0";
        }

        if ($this->si230_dtatonormativo == null) {
            $this->si230_dtatonormativo = "0";
        }

        if ($this->si230_tiporegistro == null) {
            $this->erro_sql = " Campo Tipo do registro nao Informado.";
            $this->erro_campo = "si230_tiporegistro";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
            $this->erro_status = "0";

            return false;
        }

        if ($si230_sequencial == "" || $si230_sequencial == null) {
            $result = db_query("select nextval('dipr102024_si230_sequencial_seq');");
            if ($result == false) {
                $this->erro_banco = str_replace("", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: dipr102024_si230_sequencial_seq do campo: si230_sequencial";
                $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
                $this->erro_status = "0";

                return false;
            }
            $this->si230_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from dipr102024_si230_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $si230_sequencial)) {
                $this->erro_sql = " Campo si230_sequencial maior que �ltimo n�mero da sequencia.";
                $this->erro_banco = "Sequencia menor que este n�mero.";
                $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
                $this->erro_status = "0";

                return false;
            } else {
                $this->si230_sequencial = $si230_sequencial;
            }
        }
        if (($this->si230_sequencial == null) || ($this->si230_sequencial == "")) {
            $this->erro_sql = " Campo si230_sequencial nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
            $this->erro_status = "0";

            return false;
        }
        if ($this->si230_nroatonormasegremassa == null || $this->si230_nroatonormasegremassa == "") {
            $this->si230_nroatonormasegremassa = 'null';
        }

        if ($this->si230_dtatonormasegremassa == null || $this->si230_dtatonormasegremassa == "") {
            $this->si230_dtatonormasegremassa = 'null';
        }else{
            $this->si230_dtatonormasegremassa = "'".$this->si230_dtatonormasegremassa."'";
        }

        if ($this->si230_planodefatuarial == null || $this->si230_planodefatuarial == "") {
            $this->si230_planodefatuarial = 'null';
        }

        if ($this->si230_atonormplanodefat == null || $this->si230_atonormplanodefat == "") {
            $this->si230_atonormplanodefat = 'null';
        }

        if ($this->si230_dtatoplanodefat == null || $this->si230_dtatoplanodefat == "") {
            $this->si230_dtatoplanodefat = 'null';
        }else{
            $this->si230_dtatoplanodefat = "'".$this->si230_dtatoplanodefat."'";
        }

        $sql = "insert into dipr102024(
                    si230_sequencial
                    ,si230_tiporegistro
                    ,si230_tipocadastro
                    ,si230_segregacaomassa
                    ,si230_benefcustesouro
                    ,si230_atonormativo
                    ,si230_dtatonormativo
                    ,si230_mes
                    ,si230_instit
                    ,si230_nroatonormasegremassa
                    ,si230_dtatonormasegremassa
                    ,si230_planodefatuarial
                    ,si230_atonormplanodefat
                    ,si230_dtatoplanodefat )
                values (
                    $this->si230_sequencial
                    ,$this->si230_tiporegistro
                    ,$this->si230_tipocadastro
                    ,$this->si230_segregacaomassa
                    ,$this->si230_benefcustesouro
                    ,$this->si230_atonormativo
                    ,'$this->si230_dtatonormativo'
                    ,$this->si230_mes
                    ,$this->si230_instit
                    ,$this->si230_nroatonormasegremassa
                    ,$this->si230_dtatonormasegremassa
                    ,$this->si230_planodefatuarial
                    ,$this->si230_atonormplanodefat
                    ,$this->si230_dtatoplanodefat)";

        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "dipr102024 ($this->si230_sequencial) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
                $this->erro_banco = "dipr102024 j� Cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
            } else {
                $this->erro_sql = "dipr102024 ($this->si230_sequencial) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;

            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $this->si230_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $resaco = $this->sql_record($this->sql_query_file($this->si230_sequencial));
        if (($resaco != false) || ($this->numrows != 0)) {
            $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
            $acount = pg_result($resac, 0, 0);
            $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
            $resac = db_query("insert into db_acountkey values($acount,2011145,'$this->si230_sequencial','I')");
            $resac = db_query("insert into db_acount values($acount,2010379,2011145,'','" . AddSlashes(pg_result($resaco, 0, 'si230_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,2010379,2011146,'','" . AddSlashes(pg_result($resaco, 0, 'si230_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,2010379,2011147,'','" . AddSlashes(pg_result($resaco, 0, 'si230_codorgao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,2010379,2011148,'','" . AddSlashes(pg_result($resaco, 0, 'si230_nroleiautorizacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,2010379,2011363,'','" . AddSlashes(pg_result($resaco, 0, 'si230_dtleiautorizacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,2010379,2011149,'','" . AddSlashes(pg_result($resaco, 0, 'si230_dtpublicacaoleiautorizacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,2010379,2011150,'','" . AddSlashes(pg_result($resaco, 0, 'si230_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,2010379,2011663,'','" . AddSlashes(pg_result($resaco, 0, 'si230_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,2010379,2011663,'','" . AddSlashes(pg_result($resaco, 0, 'si230_nroatonormasegremassa')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,2010379,2011663,'','" . AddSlashes(pg_result($resaco, 0, 'si230_dtatonormasegremassa')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,2010379,2011663,'','" . AddSlashes(pg_result($resaco, 0, 'si230_planodefatuarial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,2010379,2011663,'','" . AddSlashes(pg_result($resaco, 0, 'si230_atonormplanodefat')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,2010379,2011663,'','" . AddSlashes(pg_result($resaco, 0, 'si230_dtatoplanodefat')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");

        }

        return true;
    }

    // funcao para exclusao
    function excluir($si230_sequencial = null, $dbwhere = null)
    {
        if ($dbwhere == null || $dbwhere == "") {
            $resaco = $this->sql_record($this->sql_query_file($si230_sequencial));
        } else {
            $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
        }

        $sql = " delete from dipr102024
                    where ";
        $sql2 = "";
        if ($dbwhere == null || $dbwhere == "") {
            if ($si230_sequencial != "") {
                if ($sql2 != "") {
                    $sql2 .= " and ";
                }
                $sql2 .= " si230_sequencial = $si230_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }

        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("", "", @pg_last_error());
            $this->erro_sql = "dipr102024 nao Exclu�do. Exclus�o Abortada.\n";
            $this->erro_sql .= "Valores : " . $si230_sequencial;
            $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;

            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "dipr102024 nao Encontrado. Exclus�o n�o Efetuada.\n";
                $this->erro_sql .= "Valores : " . $si230_sequencial;
                $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;

                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
                $this->erro_sql .= "Valores : " . $si230_sequencial;
                $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
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
            $this->numrows = 0;
            $this->erro_banco = str_replace("", "", @pg_last_error());
            $this->erro_sql = "Erro ao selecionar os registros.";
            $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
            $this->erro_status = "0";

            return false;
        }
        $this->numrows = pg_numrows($result);
        if ($this->numrows == 0) {
            $this->erro_banco = "";
            $this->erro_sql = "Record Vazio na Tabela:dipr102024";
            $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
            $this->erro_status = "0";

            return false;
        }

        return $result;
    }

    // funcao do sql
    function sql_query($si230_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from dipr102024 ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($si230_sequencial != null) {
                $sql2 .= " where dipr102024.si230_sequencial = $si230_sequencial ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }

        return $sql;
    }

    // funcao do sql
    function sql_query_file($si230_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from dipr102024 ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($si230_sequencial != null) {
                $sql2 .= " where dipr102024.si230_sequencial = $si230_sequencial ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }

        return $sql;
    }
}

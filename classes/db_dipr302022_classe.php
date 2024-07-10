<?
//MODULO: sicom
//CLASSE DA ENTIDADE dipr302022
class cl_dipr302022
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
    var $si232_sequencial = 0;
    var $si232_tiporegistro = 0;
    var $si232_coddipr = 0;
    var $si232_segregacaomassa = 0;
    var $si232_benefcustesouro = 0;
    var $si232_atonormativo = 0;
    var $si232_exercicioato = 0;
    var $si232_mes = 0;
    var $si232_instit = 0;
    // cria propriedade com as variaveis do arquivo
    var $campos = "
        si232_sequencial = Sequencial,
        si232_tiporegistro = Tipo de Registro
        si232_codorgao = Codigo do Orgao
        si232_coddipr = Codigo DIPR
        si232_tipobasecalculo = Tipo Base de Calculo
        si232_mescompetencia = Mes Competencia
        si232_exerciciocompetencia = Exercicio Mes Competencia
        si232_tipofundo = Tipo de Fundo
        si232_remuneracaobrutafolhapag = Remuneracao Bruta
        si232_tipobasecalculocontrprevidencia = Tipo de Base de Calculo Previdencia
        si232_tipobasecalculocontrseg = Tipo de Base de Calculo Seg
        si232_valorbasecalculocontr = Valor da Base de Calculo
        si232_tipocontribuicao = Tipo de Contribuicao
        si232_aliquota = Aliquota
        si232_valorcontribdevida = Valor da Contribuicao
        si232_mes = Mes
        si232_instit = Instituicao
    ";

    //funcao construtor da classe
    function cl_dipr302022()
    {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("dipr302022");
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
            $this->si232_sequencial = ($this->si232_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si232_sequencial"] : $this->si232_sequencial);
            $this->si232_tiporegistro = ($this->si232_tiporegistro == "" ? @$GLOBALS["HTTP_POST_VARS"]["si232_tiporegistro"] : $this->si232_tiporegistro);
            $this->si232_codorgao = ($this->si232_codorgao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si232_codorgao"] : $this->si232_codorgao);
            $this->si232_coddipr = ($this->si232_coddipr == "" ? @$GLOBALS["HTTP_POST_VARS"]["si232_coddipr"] : $this->si232_coddipr);
            $this->si232_mescompetencia = ($this->si232_mescompetencia == "" ? @$GLOBALS["HTTP_POST_VARS"]["si232_mescompetencia"] : $this->si232_mescompetencia);
            $this->si232_exerciciocompetencia = ($this->si232_exerciciocompetencia == "" ? @$GLOBALS["HTTP_POST_VARS"]["si232_exerciciocompetencia"] : $this->si232_exerciciocompetencia);
            $this->si232_tipofundo = ($this->si232_tipofundo == "" ? @$GLOBALS["HTTP_POST_VARS"]["si232_tipofundo"] : $this->si232_tipofundo);
            $this->si232_tiporepasse = ($this->si232_tiporepasse == "" ? @$GLOBALS["HTTP_POST_VARS"]["si232_tiporepasse"] : $this->si232_tiporepasse);
            $this->si232_tipocontripatronal = ($this->si232_tipocontripatronal == "" ? @$GLOBALS["HTTP_POST_VARS"]["si232_tipocontripatronal"] : $this->si232_tipocontripatronal);
            $this->si232_tipocontrisegurado = ($this->si232_tipocontrisegurado == "" ? @$GLOBALS["HTTP_POST_VARS"]["si232_tipocontrisegurado"] : $this->si232_tipocontrisegurado);
            $this->si232_tipocontribuicao = ($this->si232_tipocontribuicao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si232_tipocontribuicao"] : $this->si232_tipocontribuicao);
            $this->si232_datarepasse = ($this->si232_datarepasse == "" ? @$GLOBALS["HTTP_POST_VARS"]["si232_datarepasse"] : $this->si232_datarepasse);
            $this->si232_datavencirepasse = ($this->si232_datavencirepasse == "" ? @$GLOBALS["HTTP_POST_VARS"]["si232_datavencirepasse"] : $this->si232_datavencirepasse);
            $this->si232_tipobasecalculocontrseg = ($this->si232_tipobasecalculocontrseg == "" ? @$GLOBALS["HTTP_POST_VARS"]["si232_tipobasecalculocontrseg"] : $this->si232_tipobasecalculocontrseg);
            $this->si232_valororiginal = ($this->si232_valororiginal == "" ? @$GLOBALS["HTTP_POST_VARS"]["si232_valororiginal"] : $this->si232_valororiginal);
            $this->si232_valororiginalrepassado = ($this->si232_valororiginalrepassado == "" ? @$GLOBALS["HTTP_POST_VARS"]["si232_valororiginalrepassado"] : $this->si232_valororiginalrepassado);
            $this->si232_mes = ($this->si232_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si232_mes"] : $this->si232_mes);
            $this->si232_instit = ($this->si232_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["si232_instit"] : $this->si232_instit);
        } else {
            $this->si232_sequencial = ($this->si232_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si232_sequencial"] : $this->si232_sequencial);
        }
    }

    // funcao para inclusao
    function incluir($si232_sequencial)
    {
        $this->atualizacampos();

        if ($this->si232_tiporegistro == null) {
            $this->erro_sql = " Campo Tipo do registro nao Informado.";
            $this->erro_campo = "si232_tiporegistro";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
            $this->erro_status = "0";

            return false;
        }

        if ($si232_sequencial == "" || $si232_sequencial == null) {
            $result = db_query("select nextval('dipr302022_si232_sequencial_seq');");
            if ($result == false) {
                $this->erro_banco = str_replace("", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: dipr302022_si232_sequencial_seq do campo: si232_sequencial";
                $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
                $this->erro_status = "0";

                return false;
            }
            $this->si232_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from dipr302022_si232_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $si232_sequencial)) {
                $this->erro_sql = " Campo si232_sequencial maior que �ltimo n�mero da sequencia.";
                $this->erro_banco = "Sequencia menor que este n�mero.";
                $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
                $this->erro_status = "0";

                return false;
            } else {
                $this->si232_sequencial = $si232_sequencial;
            }
        }
        if (($this->si232_sequencial == null) || ($this->si232_sequencial == "")) {
            $this->erro_sql = " Campo si232_sequencial nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
            $this->erro_status = "0";

            return false;
        }
        $sql = "insert into dipr302022(
                    si232_sequencial 
                    ,si232_tiporegistro
                    ,si232_codorgao
                    ,si232_coddipr 
                    ,si232_mescompetencia
                    ,si232_exerciciocompetencia
                    ,si232_tipofundo
                    ,si232_tiporepasse
                    ,si232_tipocontripatronal
                    ,si232_tipocontrisegurado
                    ,si232_tipocontribuicao
                    ,si232_datarepasse
                    ,si232_datavencirepasse
                    ,si232_valororiginal
                    ,si232_valororiginalrepassado
                    ,si232_mes 
                    ,si232_instit )
                values (
                    $this->si232_sequencial 
                    ,$this->si232_tiporegistro
                    ,$this->si232_codorgao
                    ,$this->si232_coddipr 
                    ,$this->si232_mescompetencia
                    ,$this->si232_exerciciocompetencia
                    ,$this->si232_tipofundo
                    ,$this->si232_tiporepasse
                    ,$this->si232_tipocontripatronal
                    ,$this->si232_tipocontrisegurado
                    ,$this->si232_tipocontribuicao
                    ,'$this->si232_datarepasse'
                    ,'$this->si232_datavencirepasse'
                    ,$this->si232_valororiginal
                    ,$this->si232_valororiginalrepassado
                    ,$this->si232_mes 
                    ,$this->si232_instit )";
         
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "dipr302022 ($this->si232_sequencial) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
                $this->erro_banco = "dipr302022 j� Cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
            } else {
                $this->erro_sql = "dipr302022 ($this->si232_sequencial) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;

            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $this->si232_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
     
        return true;
    }

    // funcao para exclusao
    function excluir($si232_sequencial = null, $dbwhere = null)
    {

        $sql = " delete from dipr302022
                    where ";
        $sql2 = "";
        if ($dbwhere == null || $dbwhere == "") {
            if ($si232_sequencial != "") {
                if ($sql2 != "") {
                    $sql2 .= " and ";
                }
                $sql2 .= " si232_sequencial = $si232_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);

        if ($result == false) {
            $this->erro_banco = str_replace("", "", @pg_last_error());
            $this->erro_sql = "dipr302022 nao Exclu�do. Exclus�o Abortada.\n";
            $this->erro_sql .= "Valores : " . $si232_sequencial;
            $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;

            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "dipr302022 nao Encontrado. Exclus�o n�o Efetuada.\n";
                $this->erro_sql .= "Valores : " . $si232_sequencial;
                $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;

                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
                $this->erro_sql .= "Valores : " . $si232_sequencial;
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
            $this->erro_sql = "Record Vazio na Tabela:dipr302022";
            $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
            $this->erro_status = "0";

            return false;
        }

        return $result;
    }

    // funcao do sql
    function sql_query($si232_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from dipr302022 ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($si232_sequencial != null) {
                $sql2 .= " where dipr302022.si232_sequencial = $si232_sequencial ";
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
    function sql_query_file($si232_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from dipr302022 ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($si232_sequencial != null) {
                $sql2 .= " where dipr302022.si232_sequencial = $si232_sequencial ";
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

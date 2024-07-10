<?
//MODULO: sicom
//CLASSE DA ENTIDADE aoc102024
class cl_aoc102024
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
  var $si38_sequencial = 0;
  var $si38_tiporegistro = 0;
  var $si38_codorgao = null;
  var $si38_nrodecreto = 0;
  var $si38_datadecreto_dia = null;
  var $si38_datadecreto_mes = null;
  var $si38_datadecreto_ano = null;
  var $si38_datadecreto = null;
  var $si38_mes = 0;
  var $si38_instit = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 si38_sequencial = int8 = sequencial
                 si38_tiporegistro = int8 = Tipo do  registro
                 si38_codorgao = varchar(2) = C�digo do �rg�o
                 si38_nrodecreto = int8 = N�mero do Decreto
                 si38_datadecreto = date = Data do Decreto
                 si38_mes = int8 = M�s
                 si38_instit = int8 = Institui��o
                 ";

  //funcao construtor da classe
  function cl_aoc102024()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("aoc102024");
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
      $this->si38_sequencial = ($this->si38_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si38_sequencial"] : $this->si38_sequencial);
      $this->si38_tiporegistro = ($this->si38_tiporegistro == "" ? @$GLOBALS["HTTP_POST_VARS"]["si38_tiporegistro"] : $this->si38_tiporegistro);
      $this->si38_codorgao = ($this->si38_codorgao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si38_codorgao"] : $this->si38_codorgao);
      $this->si38_nrodecreto = ($this->si38_nrodecreto == "" ? @$GLOBALS["HTTP_POST_VARS"]["si38_nrodecreto"] : $this->si38_nrodecreto);
      if ($this->si38_datadecreto == "") {
        $this->si38_datadecreto_dia = ($this->si38_datadecreto_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["si38_datadecreto_dia"] : $this->si38_datadecreto_dia);
        $this->si38_datadecreto_mes = ($this->si38_datadecreto_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si38_datadecreto_mes"] : $this->si38_datadecreto_mes);
        $this->si38_datadecreto_ano = ($this->si38_datadecreto_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["si38_datadecreto_ano"] : $this->si38_datadecreto_ano);
        if ($this->si38_datadecreto_dia != "") {
          $this->si38_datadecreto = $this->si38_datadecreto_ano . "-" . $this->si38_datadecreto_mes . "-" . $this->si38_datadecreto_dia;
        }
      }
      $this->si38_mes = ($this->si38_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si38_mes"] : $this->si38_mes);
      $this->si38_instit = ($this->si38_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["si38_instit"] : $this->si38_instit);
    } else {
      $this->si38_sequencial = ($this->si38_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si38_sequencial"] : $this->si38_sequencial);
    }
  }

  // funcao para inclusao
  function incluir($si38_sequencial)
  {
    $this->atualizacampos();
    if ($this->si38_tiporegistro == null) {
      $this->erro_sql = " Campo Tipo do  registro nao Informado.";
      $this->erro_campo = "si38_tiporegistro";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si38_nrodecreto == null) {
      $this->si38_nrodecreto = "0";
    }
    if ($this->si38_datadecreto == null) {
      $this->si38_datadecreto = "null";
    }
    if ($this->si38_mes == null) {
      $this->erro_sql = " Campo M�s nao Informado.";
      $this->erro_campo = "si38_mes";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si38_instit == null) {
      $this->erro_sql = " Campo Institui��o nao Informado.";
      $this->erro_campo = "si38_instit";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($si38_sequencial == "" || $si38_sequencial == null) {
      $result = db_query("select nextval('aoc102024_si38_sequencial_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("
", "", @pg_last_error());
        $this->erro_sql = "Verifique o cadastro da sequencia: aoc102024_si38_sequencial_seq do campo: si38_sequencial";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
      $this->si38_sequencial = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from aoc102024_si38_sequencial_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $si38_sequencial)) {
        $this->erro_sql = " Campo si38_sequencial maior que �ltimo n�mero da sequencia.";
        $this->erro_banco = "Sequencia menor que este n�mero.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      } else {
        $this->si38_sequencial = $si38_sequencial;
      }
    }
    if (($this->si38_sequencial == null) || ($this->si38_sequencial == "")) {
      $this->erro_sql = " Campo si38_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    $sql = "insert into aoc102024(
                                       si38_sequencial
                                      ,si38_tiporegistro
                                      ,si38_codorgao
                                      ,si38_nrodecreto
                                      ,si38_datadecreto
                                      ,si38_mes
                                      ,si38_instit
                       )
                values (
                                $this->si38_sequencial
                               ,$this->si38_tiporegistro
                               ,'$this->si38_codorgao'
                               ,'$this->si38_nrodecreto'
                               ," . ($this->si38_datadecreto == "null" || $this->si38_datadecreto == "" ? "null" : "'" . $this->si38_datadecreto . "'") . "
                               ,$this->si38_mes
                               ,$this->si38_instit
                      )";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql = "aoc102024 ($this->si38_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_banco = "aoc102024 j� Cadastrado";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      } else {
        $this->erro_sql = "aoc102024 ($this->si38_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;

      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\n";
    $this->erro_sql .= "Valores : " . $this->si38_sequencial;
    $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->si38_sequencial));
    if (($resaco != false) || ($this->numrows != 0)) {
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac, 0, 0);
      $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
      $resac = db_query("insert into db_acountkey values($acount,2009779,'$this->si38_sequencial','I')");
      $resac = db_query("insert into db_acount values($acount,2010266,2009779,'','" . AddSlashes(pg_result($resaco, 0, 'si38_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010266,2009780,'','" . AddSlashes(pg_result($resaco, 0, 'si38_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010266,2009781,'','" . AddSlashes(pg_result($resaco, 0, 'si38_codorgao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010266,2009782,'','" . AddSlashes(pg_result($resaco, 0, 'si38_nrodecreto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010266,2009783,'','" . AddSlashes(pg_result($resaco, 0, 'si38_datadecreto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010266,2009784,'','" . AddSlashes(pg_result($resaco, 0, 'si38_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010266,2011552,'','" . AddSlashes(pg_result($resaco, 0, 'si38_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
    }

    return true;
  }

  // funcao para alteracao
  function alterar($si38_sequencial = null)
  {
    $this->atualizacampos();
    $sql = " update aoc102024 set ";
    $virgula = "";
    if (trim($this->si38_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si38_sequencial"])) {
      if (trim($this->si38_sequencial) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si38_sequencial"])) {
        $this->si38_sequencial = "0";
      }
      $sql .= $virgula . " si38_sequencial = $this->si38_sequencial ";
      $virgula = ",";
    }
    if (trim($this->si38_tiporegistro) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si38_tiporegistro"])) {
      $sql .= $virgula . " si38_tiporegistro = $this->si38_tiporegistro ";
      $virgula = ",";
      if (trim($this->si38_tiporegistro) == null) {
        $this->erro_sql = " Campo Tipo do  registro nao Informado.";
        $this->erro_campo = "si38_tiporegistro";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->si38_codorgao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si38_codorgao"])) {
      $sql .= $virgula . " si38_codorgao = '$this->si38_codorgao' ";
      $virgula = ",";
    }
    if (trim($this->si38_nrodecreto) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si38_nrodecreto"])) {
      if (trim($this->si38_nrodecreto) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si38_nrodecreto"])) {
        $this->si38_nrodecreto = "0";
      }
      $sql .= $virgula . " si38_nrodecreto = $this->si38_nrodecreto ";
      $virgula = ",";
    }
    if (trim($this->si38_datadecreto) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si38_datadecreto_dia"]) && ($GLOBALS["HTTP_POST_VARS"]["si38_datadecreto_dia"] != "")) {
      $sql .= $virgula . " si38_datadecreto = '$this->si38_datadecreto' ";
      $virgula = ",";
    } else {
      if (isset($GLOBALS["HTTP_POST_VARS"]["si38_datadecreto_dia"])) {
        $sql .= $virgula . " si38_datadecreto = null ";
        $virgula = ",";
      }
    }
    if (trim($this->si38_mes) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si38_mes"])) {
      $sql .= $virgula . " si38_mes = $this->si38_mes ";
      $virgula = ",";
      if (trim($this->si38_mes) == null) {
        $this->erro_sql = " Campo M�s nao Informado.";
        $this->erro_campo = "si38_mes";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->si38_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si38_instit"])) {
      $sql .= $virgula . " si38_instit = $this->si38_instit ";
      $virgula = ",";
      if (trim($this->si38_instit) == null) {
        $this->erro_sql = " Campo Institui��o nao Informado.";
        $this->erro_campo = "si38_instit";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    $sql .= " where ";
    if ($si38_sequencial != null) {
      $sql .= " si38_sequencial = $this->si38_sequencial";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->si38_sequencial));
    if ($this->numrows > 0) {
      for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,2009779,'$this->si38_sequencial','A')");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si38_sequencial"]) || $this->si38_sequencial != "")
          $resac = db_query("insert into db_acount values($acount,2010266,2009779,'" . AddSlashes(pg_result($resaco, $conresaco, 'si38_sequencial')) . "','$this->si38_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si38_tiporegistro"]) || $this->si38_tiporegistro != "")
          $resac = db_query("insert into db_acount values($acount,2010266,2009780,'" . AddSlashes(pg_result($resaco, $conresaco, 'si38_tiporegistro')) . "','$this->si38_tiporegistro'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si38_codorgao"]) || $this->si38_codorgao != "")
          $resac = db_query("insert into db_acount values($acount,2010266,2009781,'" . AddSlashes(pg_result($resaco, $conresaco, 'si38_codorgao')) . "','$this->si38_codorgao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si38_nrodecreto"]) || $this->si38_nrodecreto != "")
          $resac = db_query("insert into db_acount values($acount,2010266,2009782,'" . AddSlashes(pg_result($resaco, $conresaco, 'si38_nrodecreto')) . "','$this->si38_nrodecreto'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si38_datadecreto"]) || $this->si38_datadecreto != "")
          $resac = db_query("insert into db_acount values($acount,2010266,2009783,'" . AddSlashes(pg_result($resaco, $conresaco, 'si38_datadecreto')) . "','$this->si38_datadecreto'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si38_mes"]) || $this->si38_mes != "")
          $resac = db_query("insert into db_acount values($acount,2010266,2009784,'" . AddSlashes(pg_result($resaco, $conresaco, 'si38_mes')) . "','$this->si38_mes'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si38_instit"]) || $this->si38_instit != "")
          $resac = db_query("insert into db_acount values($acount,2010266,2011552,'" . AddSlashes(pg_result($resaco, $conresaco, 'si38_instit')) . "','$this->si38_instit'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "aoc102024 nao Alterado. Alteracao Abortada.\n";
      $this->erro_sql .= "Valores : " . $this->si38_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "aoc102024 nao foi Alterado. Alteracao Executada.\n";
        $this->erro_sql .= "Valores : " . $this->si38_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $this->si38_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);

        return true;
      }
    }
  }

  // funcao para exclusao
  function excluir($si38_sequencial = null, $dbwhere = null)
  {
    if ($dbwhere == null || $dbwhere == "") {
      $resaco = $this->sql_record($this->sql_query_file($si38_sequencial));
    } else {
      $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
    }
    if (($resaco != false) || ($this->numrows != 0)) {
      for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,2009779,'$si38_sequencial','E')");
        $resac = db_query("insert into db_acount values($acount,2010266,2009779,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si38_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010266,2009780,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si38_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010266,2009781,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si38_codorgao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010266,2009782,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si38_nrodecreto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010266,2009783,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si38_datadecreto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010266,2009784,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si38_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010266,2011552,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si38_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $sql = " delete from aoc102024
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($si38_sequencial != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " si38_sequencial = $si38_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "aoc102024 nao Exclu�do. Exclus�o Abortada.\n";
      $this->erro_sql .= "Valores : " . $si38_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "aoc102024 nao Encontrado. Exclus�o n�o Efetuada.\n";
        $this->erro_sql .= "Valores : " . $si38_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $si38_sequencial;
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
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "Erro ao selecionar os registros.";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    $this->numrows = pg_numrows($result);
    if ($this->numrows == 0) {
      $this->erro_banco = "";
      $this->erro_sql = "Record Vazio na Tabela:aoc102024";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }

    return $result;
  }

  // funcao do sql
  function sql_query($si38_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from aoc102024 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si38_sequencial != null) {
        $sql2 .= " where aoc102024.si38_sequencial = $si38_sequencial ";
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
  function sql_query_file($si38_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from aoc102024 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si38_sequencial != null) {
        $sql2 .= " where aoc102024.si38_sequencial = $si38_sequencial ";
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

    /**
     * @SICOM AOC102024
     *
     * @param $instituicao
     * @return string
     */
    public function sqlReg10($dados)
    {
        /**
         * Seleciona as informacoes pertinentes ao AOC.
         */
        $instituicao = $dados['Instituicao'];
        $iUltimoDiaMes = date("d", mktime(0, 0, 0, $dados['Mes'] + 1, 0, db_getsession("DB_anousu")));
        $sDataInicial = db_getsession("DB_anousu") . "-{$dados['Mes']}-01";
        $sDataFinal   = db_getsession("DB_anousu") . "-{$dados['Mes']}-{$iUltimoDiaMes}";

        return "select  distinct o39_codproj as codigovinc,
                        '10' as tiporegistro,
                        si09_codorgaotce as codorgao,
                        replace(o39_numero,' ','') as nroDecreto,
                        o39_data as dataDecreto,o39_tipoproj as tipodecreto
                    from orcsuplem
                        join orcsuplemval  on o47_codsup = o46_codsup
                        join orcprojeto    on o46_codlei = o39_codproj
                        join db_config on prefeitura  = 't'
                        join orcsuplemlan on o49_codsup=o46_codsup and o49_data is not null
                        left join infocomplementaresinstit on si09_instit = {$instituicao}
                    where o39_data between  '$sDataInicial' and '$sDataFinal'
                    and o46_tiposup not in (1017)
                    order by 4";
    }
}


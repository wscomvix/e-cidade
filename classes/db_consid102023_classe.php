<?
//MODULO: sicom
//CLASSE DA ENTIDADE consid102023
class cl_consid102023
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
  var $si158_sequencial = 0;
  var $si158_tiporegistro = 0;
  var $si158_codarquivo = null;
  var $si158_exercicioreferenciaconsid = 0;
  var $si158_mesreferenciaconsid = null;
  var $si158_consideracoes = null;
  var $si158_mes = 0;
  var $si158_instit = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 si158_sequencial = int8 = sequencial 
                 si158_tiporegistro = int8 = Tipo de registro 
                 si158_codarquivo = varchar(20) = C�digo do arquivo da considera��o
                 si158_exercicioreferenciaconsid = int8 = Do arquivo a que se refere a considera��o
                 si158_mesreferenciaconsid = varchar(2) = Do arquivo a que se refere a considera��o
                 si158_consideracoes = varchar(3000) = Considera��es
                 si158_mes = int8 = M�s 
                 si158_instit = int8 = Institui��o 
                 ";

  //funcao construtor da classe
  function cl_consid102023()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("consid102023");
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
      $this->si158_sequencial = ($this->si158_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si158_sequencial"] : $this->si158_sequencial);
      $this->si158_tiporegistro = ($this->si158_tiporegistro == "" ? @$GLOBALS["HTTP_POST_VARS"]["si158_tiporegistro"] : $this->si158_tiporegistro);
      $this->si158_codarquivo = ($this->si158_codarquivo == "" ? @$GLOBALS["HTTP_POST_VARS"]["si158_codarquivo"] : $this->si158_codarquivo);
      $this->si158_exercicioreferenciaconsid = ($this->si158_exercicioreferenciaconsid == "" ? @$GLOBALS["HTTP_POST_VARS"]["si158_exercicioreferenciaconsid"] : $this->si158_exercicioreferenciaconsid);
      $this->si158_mesreferenciaconsid = ($this->si158_mesreferenciaconsid == "" ? @$GLOBALS["HTTP_POST_VARS"]["si158_mesreferenciaconsid"] : $this->si158_mesreferenciaconsid);
      $this->si158_consideracoes = ($this->si158_consideracoes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si158_consideracoes"] : $this->si158_consideracoes);
      $this->si158_mes = ($this->si158_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si158_mes"] : $this->si158_mes);
      $this->si158_instit = ($this->si158_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["si158_instit"] : $this->si158_instit);
    } else {
      $this->si158_sequencial = ($this->si158_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si158_sequencial"] : $this->si158_sequencial);
    }
  }

  // funcao para inclusao
  function incluir($si158_sequencial)
  {
    $this->atualizacampos();
    if ($this->si158_tiporegistro == null) {
      $this->erro_sql = " Campo Tipo de registro nao Informado.";
      $this->erro_campo = "si158_tiporegistro";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si158_mes == null) {
      $this->erro_sql = " Campo M�s nao Informado.";
      $this->erro_campo = "si158_mes";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si158_instit == null) {
      $this->erro_sql = " Campo Institui��o nao Informado.";
      $this->erro_campo = "si158_instit";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($si158_sequencial == "" || $si158_sequencial == null) {
      $result = db_query("select nextval('consid102023_si158_sequencial_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("
", "", @pg_last_error());
        $this->erro_sql = "Verifique o cadastro da sequencia: consid102023_si158_sequencial_seq do campo: si158_sequencial";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
      $this->si158_sequencial = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from consid102023_si158_sequencial_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $si158_sequencial)) {
        $this->erro_sql = " Campo si158_sequencial maior que �ltimo n�mero da sequencia.";
        $this->erro_banco = "Sequencia menor que este n�mero.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      } else {
        $this->si158_sequencial = $si158_sequencial;
      }
    }
    if (($this->si158_sequencial == null) || ($this->si158_sequencial == "")) {
      $this->erro_sql = " Campo si158_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    $sql = "insert into consid102023(
                                       si158_sequencial 
                                      ,si158_tiporegistro 
                                      ,si158_codarquivo
                                      ,si158_exercicioreferenciaconsid
                                      ,si158_mesreferenciaconsid
                                      ,si158_consideracoes
                                      ,si158_mes 
                                      ,si158_instit 
                       )
                values (
                                $this->si158_sequencial 
                               ,$this->si158_tiporegistro 
                               ,'$this->si158_codarquivo'
                               ,$this->si158_exercicioreferenciaconsid
                               ,'$this->si158_mesreferenciaconsid'
                               ,'$this->si158_consideracoes' 
                               ,$this->si158_mes 
                               ,$this->si158_instit 
                      )";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql = "consid102023 ($this->si158_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_banco = "consid102023 j� Cadastrado";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      } else {
        $this->erro_sql = "consid102023 ($this->si158_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;

      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\n";
    $this->erro_sql .= "Valores : " . $this->si158_sequencial;
    $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->si158_sequencial));
    if (($resaco != false) || ($this->numrows != 0)) {
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac, 0, 0);
      $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
      $resac = db_query("insert into db_acountkey values($acount,2011208,'$this->si158_sequencial','I')");
      $resac = db_query("insert into db_acount values($acount,2010387,2011208,'','" . AddSlashes(pg_result($resaco, 0, 'si158_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010387,2011378,'','" . AddSlashes(pg_result($resaco, 0, 'si158_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010387,2011209,'','" . AddSlashes(pg_result($resaco, 0, 'si158_codarquivo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010387,2011210,'','" . AddSlashes(pg_result($resaco, 0, 'si158_consideracoes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010387,2011211,'','" . AddSlashes(pg_result($resaco, 0, 'si158_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010387,2011671,'','" . AddSlashes(pg_result($resaco, 0, 'si158_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
    }

    return true;
  }

  // funcao para alteracao
  function alterar($si158_sequencial = null)
  {
    $this->atualizacampos();
    $sql = " update consid102023 set ";
    $virgula = "";
    if (trim($this->si158_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si158_sequencial"])) {
      if (trim($this->si158_sequencial) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si158_sequencial"])) {
        $this->si158_sequencial = "0";
      }
      $sql .= $virgula . " si158_sequencial = $this->si158_sequencial ";
      $virgula = ",";
    }
    if (trim($this->si158_tiporegistro) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si158_tiporegistro"])) {
      $sql .= $virgula . " si158_tiporegistro = $this->si158_tiporegistro ";
      $virgula = ",";
      if (trim($this->si158_tiporegistro) == null) {
        $this->erro_sql = " Campo Tipo de registro nao Informado.";
        $this->erro_campo = "si158_tiporegistro";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->si158_codarquivo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si158_codarquivo"])) {
      $sql .= $virgula . " si158_codarquivo = '$this->si158_codarquivo' ";
      $virgula = ",";
    }
    if (trim($this->si158_consideracoes) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si158_consideracoes"])) {
      $sql .= $virgula . " si158_consideracoes = '$this->si158_consideracoes' ";
      $virgula = ",";
    }
    if (trim($this->si158_exercicioreferenciaconsid) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si158_exercicioreferenciaconsid"])) {
      $sql .= $virgula . " si158_exercicioreferenciaconsid = '$this->si158_exercicioreferenciaconsid' ";
      $virgula = ",";
    }
    if (trim($this->si158_mesreferenciaconsid) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si158_mesreferenciaconsid"])) {
      $sql .= $virgula . " si158_mesreferenciaconsid = '$this->si158_mesreferenciaconsid' ";
      $virgula = ",";
    }
    if (trim($this->si158_consideracoes) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si158_consideracoes"])) {
      $sql .= $virgula . " si158_consideracoes = '$this->si158_consideracoes' ";
      $virgula = ",";
    }
    if (trim($this->si158_mes) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si158_mes"])) {
      $sql .= $virgula . " si158_mes = $this->si158_mes ";
      $virgula = ",";
      if (trim($this->si158_mes) == null) {
        $this->erro_sql = " Campo M�s nao Informado.";
        $this->erro_campo = "si158_mes";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->si158_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si158_instit"])) {
      $sql .= $virgula . " si158_instit = $this->si158_instit ";
      $virgula = ",";
      if (trim($this->si158_instit) == null) {
        $this->erro_sql = " Campo Institui��o nao Informado.";
        $this->erro_campo = "si158_instit";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    $sql .= " where ";
    if ($si158_sequencial != null) {
      $sql .= " si158_sequencial = $this->si158_sequencial";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->si158_sequencial));
    if ($this->numrows > 0) {
      for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,2011208,'$this->si158_sequencial','A')");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si158_sequencial"]) || $this->si158_sequencial != "")
          $resac = db_query("insert into db_acount values($acount,2010387,2011208,'" . AddSlashes(pg_result($resaco, $conresaco, 'si158_sequencial')) . "','$this->si158_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si158_tiporegistro"]) || $this->si158_tiporegistro != "")
          $resac = db_query("insert into db_acount values($acount,2010387,2011378,'" . AddSlashes(pg_result($resaco, $conresaco, 'si158_tiporegistro')) . "','$this->si158_tiporegistro'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si158_codarquivo"]) || $this->si158_codarquivo != "")
          $resac = db_query("insert into db_acount values($acount,2010387,2011209,'" . AddSlashes(pg_result($resaco, $conresaco, 'si158_codarquivo')) . "','$this->si158_codarquivo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si158_consideracoes"]) || $this->si158_consideracoes != "")
          $resac = db_query("insert into db_acount values($acount,2010387,2011210,'" . AddSlashes(pg_result($resaco, $conresaco, 'si158_consideracoes')) . "','$this->si158_consideracoes'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si158_mes"]) || $this->si158_mes != "")
          $resac = db_query("insert into db_acount values($acount,2010387,2011211,'" . AddSlashes(pg_result($resaco, $conresaco, 'si158_mes')) . "','$this->si158_mes'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si158_instit"]) || $this->si158_instit != "")
          $resac = db_query("insert into db_acount values($acount,2010387,2011671,'" . AddSlashes(pg_result($resaco, $conresaco, 'si158_instit')) . "','$this->si158_instit'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "consid102023 nao Alterado. Alteracao Abortada.\n";
      $this->erro_sql .= "Valores : " . $this->si158_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "consid102023 nao foi Alterado. Alteracao Executada.\n";
        $this->erro_sql .= "Valores : " . $this->si158_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $this->si158_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);

        return true;
      }
    }
  }

  // funcao para exclusao
  function excluir($si158_sequencial = null, $dbwhere = null)
  {
    if ($dbwhere == null || $dbwhere == "") {
      $resaco = $this->sql_record($this->sql_query_file($si158_sequencial));
    } else {
      $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
    }
    if (($resaco != false) || ($this->numrows != 0)) {
      for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,2011208,'$si158_sequencial','E')");
        $resac = db_query("insert into db_acount values($acount,2010387,2011208,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si158_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010387,2011378,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si158_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010387,2011209,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si158_codarquivo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010387,2011210,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si158_consideracoes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010387,2011211,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si158_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010387,2011671,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si158_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $sql = " delete from consid102023
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($si158_sequencial != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " si158_sequencial = $si158_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "consid102023 nao Exclu�do. Exclus�o Abortada.\n";
      $this->erro_sql .= "Valores : " . $si158_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "consid102023 nao Encontrado. Exclus�o n�o Efetuada.\n";
        $this->erro_sql .= "Valores : " . $si158_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $si158_sequencial;
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
      $this->erro_sql = "Record Vazio na Tabela:consid102023";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }

    return $result;
  }

  // funcao do sql
  function sql_query($si158_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from consid102023 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si158_sequencial != null) {
        $sql2 .= " where consid102023.si158_sequencial = $si158_sequencial ";
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
  function sql_query_file($si158_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from consid102023 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si158_sequencial != null) {
        $sql2 .= " where consid102023.si158_sequencial = $si158_sequencial ";
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

?>

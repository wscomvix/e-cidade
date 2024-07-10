<?
//MODULO: sicom
//CLASSE DA ENTIDADE parec112018
class cl_parec112018
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
  var $si23_sequencial = 0;
  var $si23_tiporegistro = 0;
  var $si23_codreduzido = 0;
  var $si23_codfontrecursos = 0;
  var $si23_vlfonte = 0;
  var $si23_reg10 = 0;
  var $si23_mes = 0;
  var $si23_instit = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 si23_sequencial = int8 = sequencial 
                 si23_tiporegistro = int8 = Tipo do  registro 
                 si23_codreduzido = int8 = C�digo  Identificador 
                 si23_codfontrecursos = int8 = C�digo da fonte 
                 si23_vlfonte = float8 = Valor acrescido 
                 si23_reg10 = int8 = reg10 
                 si23_mes = int8 = M�s 
                 si23_instit = int8 = Institui��o 
                 ";

  //funcao construtor da classe
  function cl_parec112018()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("parec112018");
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
      $this->si23_sequencial = ($this->si23_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si23_sequencial"] : $this->si23_sequencial);
      $this->si23_tiporegistro = ($this->si23_tiporegistro == "" ? @$GLOBALS["HTTP_POST_VARS"]["si23_tiporegistro"] : $this->si23_tiporegistro);
      $this->si23_codreduzido = ($this->si23_codreduzido == "" ? @$GLOBALS["HTTP_POST_VARS"]["si23_codreduzido"] : $this->si23_codreduzido);
      $this->si23_codfontrecursos = ($this->si23_codfontrecursos == "" ? @$GLOBALS["HTTP_POST_VARS"]["si23_codfontrecursos"] : $this->si23_codfontrecursos);
      $this->si23_vlfonte = ($this->si23_vlfonte == "" ? @$GLOBALS["HTTP_POST_VARS"]["si23_vlfonte"] : $this->si23_vlfonte);
      $this->si23_reg10 = ($this->si23_reg10 == "" ? @$GLOBALS["HTTP_POST_VARS"]["si23_reg10"] : $this->si23_reg10);
      $this->si23_mes = ($this->si23_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si23_mes"] : $this->si23_mes);
      $this->si23_instit = ($this->si23_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["si23_instit"] : $this->si23_instit);
    } else {
      $this->si23_sequencial = ($this->si23_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si23_sequencial"] : $this->si23_sequencial);
    }
  }

  // funcao para inclusao
  function incluir($si23_sequencial)
  {
    $this->atualizacampos();
    if ($this->si23_tiporegistro == null) {
      $this->erro_sql = " Campo Tipo do  registro nao Informado.";
      $this->erro_campo = "si23_tiporegistro";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si23_codreduzido == null) {
      $this->si23_codreduzido = "0";
    }
    if ($this->si23_codfontrecursos == null) {
      $this->si23_codfontrecursos = "0";
    }
    if ($this->si23_vlfonte == null) {
      $this->si23_vlfonte = "0";
    }
    if ($this->si23_reg10 == null) {
      $this->si23_reg10 = "0";
    }
    if ($this->si23_mes == null) {
      $this->erro_sql = " Campo M�s nao Informado.";
      $this->erro_campo = "si23_mes";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si23_instit == null) {
      $this->erro_sql = " Campo Institui��o nao Informado.";
      $this->erro_campo = "si23_instit";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($si23_sequencial == "" || $si23_sequencial == null) {
      $result = db_query("select nextval('parec112018_si23_sequencial_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("
", "", @pg_last_error());
        $this->erro_sql = "Verifique o cadastro da sequencia: parec112018_si23_sequencial_seq do campo: si23_sequencial";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
      $this->si23_sequencial = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from parec112018_si23_sequencial_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $si23_sequencial)) {
        $this->erro_sql = " Campo si23_sequencial maior que �ltimo n�mero da sequencia.";
        $this->erro_banco = "Sequencia menor que este n�mero.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      } else {
        $this->si23_sequencial = $si23_sequencial;
      }
    }
    if (($this->si23_sequencial == null) || ($this->si23_sequencial == "")) {
      $this->erro_sql = " Campo si23_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    $sql = "insert into parec112018(
                                       si23_sequencial 
                                      ,si23_tiporegistro 
                                      ,si23_codreduzido 
                                      ,si23_codfontrecursos 
                                      ,si23_vlfonte 
                                      ,si23_reg10 
                                      ,si23_mes 
                                      ,si23_instit 
                       )
                values (
                                $this->si23_sequencial 
                               ,$this->si23_tiporegistro 
                               ,$this->si23_codreduzido 
                               ,$this->si23_codfontrecursos 
                               ,$this->si23_vlfonte 
                               ,$this->si23_reg10 
                               ,$this->si23_mes 
                               ,$this->si23_instit 
                      )";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql = "parec112018 ($this->si23_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_banco = "parec112018 j� Cadastrado";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      } else {
        $this->erro_sql = "parec112018 ($this->si23_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;

      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\n";
    $this->erro_sql .= "Valores : " . $this->si23_sequencial;
    $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->si23_sequencial));
    if (($resaco != false) || ($this->numrows != 0)) {
      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
      $acount = pg_result($resac, 0, 0);
      $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
      $resac = db_query("insert into db_acountkey values($acount,2009664,'$this->si23_sequencial','I')");
      $resac = db_query("insert into db_acount values($acount,2010251,2009664,'','" . AddSlashes(pg_result($resaco, 0, 'si23_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010251,2009665,'','" . AddSlashes(pg_result($resaco, 0, 'si23_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010251,2009666,'','" . AddSlashes(pg_result($resaco, 0, 'si23_codreduzido')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010251,2009667,'','" . AddSlashes(pg_result($resaco, 0, 'si23_codfontrecursos')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010251,2009668,'','" . AddSlashes(pg_result($resaco, 0, 'si23_vlfonte')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010251,2009669,'','" . AddSlashes(pg_result($resaco, 0, 'si23_reg10')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010251,2009742,'','" . AddSlashes(pg_result($resaco, 0, 'si23_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      $resac = db_query("insert into db_acount values($acount,2010251,2011540,'','" . AddSlashes(pg_result($resaco, 0, 'si23_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
    }

    return true;
  }

  // funcao para alteracao
  function alterar($si23_sequencial = null)
  {
    $this->atualizacampos();
    $sql = " update parec112018 set ";
    $virgula = "";
    if (trim($this->si23_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si23_sequencial"])) {
      if (trim($this->si23_sequencial) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si23_sequencial"])) {
        $this->si23_sequencial = "0";
      }
      $sql .= $virgula . " si23_sequencial = $this->si23_sequencial ";
      $virgula = ",";
    }
    if (trim($this->si23_tiporegistro) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si23_tiporegistro"])) {
      $sql .= $virgula . " si23_tiporegistro = $this->si23_tiporegistro ";
      $virgula = ",";
      if (trim($this->si23_tiporegistro) == null) {
        $this->erro_sql = " Campo Tipo do  registro nao Informado.";
        $this->erro_campo = "si23_tiporegistro";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->si23_codreduzido) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si23_codreduzido"])) {
      if (trim($this->si23_codreduzido) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si23_codreduzido"])) {
        $this->si23_codreduzido = "0";
      }
      $sql .= $virgula . " si23_codreduzido = $this->si23_codreduzido ";
      $virgula = ",";
    }
    if (trim($this->si23_codfontrecursos) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si23_codfontrecursos"])) {
      if (trim($this->si23_codfontrecursos) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si23_codfontrecursos"])) {
        $this->si23_codfontrecursos = "0";
      }
      $sql .= $virgula . " si23_codfontrecursos = $this->si23_codfontrecursos ";
      $virgula = ",";
    }
    if (trim($this->si23_vlfonte) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si23_vlfonte"])) {
      if (trim($this->si23_vlfonte) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si23_vlfonte"])) {
        $this->si23_vlfonte = "0";
      }
      $sql .= $virgula . " si23_vlfonte = $this->si23_vlfonte ";
      $virgula = ",";
    }
    if (trim($this->si23_reg10) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si23_reg10"])) {
      if (trim($this->si23_reg10) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si23_reg10"])) {
        $this->si23_reg10 = "0";
      }
      $sql .= $virgula . " si23_reg10 = $this->si23_reg10 ";
      $virgula = ",";
    }
    if (trim($this->si23_mes) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si23_mes"])) {
      $sql .= $virgula . " si23_mes = $this->si23_mes ";
      $virgula = ",";
      if (trim($this->si23_mes) == null) {
        $this->erro_sql = " Campo M�s nao Informado.";
        $this->erro_campo = "si23_mes";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->si23_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si23_instit"])) {
      $sql .= $virgula . " si23_instit = $this->si23_instit ";
      $virgula = ",";
      if (trim($this->si23_instit) == null) {
        $this->erro_sql = " Campo Institui��o nao Informado.";
        $this->erro_campo = "si23_instit";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    $sql .= " where ";
    if ($si23_sequencial != null) {
      $sql .= " si23_sequencial = $this->si23_sequencial";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->si23_sequencial));
    if ($this->numrows > 0) {
      for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,2009664,'$this->si23_sequencial','A')");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si23_sequencial"]) || $this->si23_sequencial != "")
          $resac = db_query("insert into db_acount values($acount,2010251,2009664,'" . AddSlashes(pg_result($resaco, $conresaco, 'si23_sequencial')) . "','$this->si23_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si23_tiporegistro"]) || $this->si23_tiporegistro != "")
          $resac = db_query("insert into db_acount values($acount,2010251,2009665,'" . AddSlashes(pg_result($resaco, $conresaco, 'si23_tiporegistro')) . "','$this->si23_tiporegistro'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si23_codreduzido"]) || $this->si23_codreduzido != "")
          $resac = db_query("insert into db_acount values($acount,2010251,2009666,'" . AddSlashes(pg_result($resaco, $conresaco, 'si23_codreduzido')) . "','$this->si23_codreduzido'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si23_codfontrecursos"]) || $this->si23_codfontrecursos != "")
          $resac = db_query("insert into db_acount values($acount,2010251,2009667,'" . AddSlashes(pg_result($resaco, $conresaco, 'si23_codfontrecursos')) . "','$this->si23_codfontrecursos'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si23_vlfonte"]) || $this->si23_vlfonte != "")
          $resac = db_query("insert into db_acount values($acount,2010251,2009668,'" . AddSlashes(pg_result($resaco, $conresaco, 'si23_vlfonte')) . "','$this->si23_vlfonte'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si23_reg10"]) || $this->si23_reg10 != "")
          $resac = db_query("insert into db_acount values($acount,2010251,2009669,'" . AddSlashes(pg_result($resaco, $conresaco, 'si23_reg10')) . "','$this->si23_reg10'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si23_mes"]) || $this->si23_mes != "")
          $resac = db_query("insert into db_acount values($acount,2010251,2009742,'" . AddSlashes(pg_result($resaco, $conresaco, 'si23_mes')) . "','$this->si23_mes'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        if (isset($GLOBALS["HTTP_POST_VARS"]["si23_instit"]) || $this->si23_instit != "")
          $resac = db_query("insert into db_acount values($acount,2010251,2011540,'" . AddSlashes(pg_result($resaco, $conresaco, 'si23_instit')) . "','$this->si23_instit'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "parec112018 nao Alterado. Alteracao Abortada.\n";
      $this->erro_sql .= "Valores : " . $this->si23_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "parec112018 nao foi Alterado. Alteracao Executada.\n";
        $this->erro_sql .= "Valores : " . $this->si23_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $this->si23_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);

        return true;
      }
    }
  }

  // funcao para exclusao
  function excluir($si23_sequencial = null, $dbwhere = null)
  {
    if ($dbwhere == null || $dbwhere == "") {
      $resaco = $this->sql_record($this->sql_query_file($si23_sequencial));
    } else {
      $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
    }
    if (($resaco != false) || ($this->numrows != 0)) {
      for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,2009664,'$si23_sequencial','E')");
        $resac = db_query("insert into db_acount values($acount,2010251,2009664,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si23_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010251,2009665,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si23_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010251,2009666,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si23_codreduzido')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010251,2009667,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si23_codfontrecursos')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010251,2009668,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si23_vlfonte')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010251,2009669,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si23_reg10')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010251,2009742,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si23_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,2010251,2011540,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si23_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    $sql = " delete from parec112018
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($si23_sequencial != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " si23_sequencial = $si23_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "parec112018 nao Exclu�do. Exclus�o Abortada.\n";
      $this->erro_sql .= "Valores : " . $si23_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "parec112018 nao Encontrado. Exclus�o n�o Efetuada.\n";
        $this->erro_sql .= "Valores : " . $si23_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $si23_sequencial;
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
      $this->erro_sql = "Record Vazio na Tabela:parec112018";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }

    return $result;
  }

  // funcao do sql
  function sql_query($si23_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from parec112018 ";
    $sql .= "      left  join parec102018  on  parec102018.si22_sequencial = parec112018.si23_reg10";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si23_sequencial != null) {
        $sql2 .= " where parec112018.si23_sequencial = $si23_sequencial ";
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
  function sql_query_file($si23_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from parec112018 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si23_sequencial != null) {
        $sql2 .= " where parec112018.si23_sequencial = $si23_sequencial ";
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

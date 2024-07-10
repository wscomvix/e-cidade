<?
//MODULO: sicom
//CLASSE DA ENTIDADE consor402024
class cl_consor402024
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
  var $si19_sequencial = 0;
  var $si19_tiporegistro = 0;
  var $si19_cnpjconsorcio = null;
  var $si19_codfontrecursos = null;
  var $si19_vldispcaixa = 0;
  var $si19_mes = 0;
  var $si19_instit = null;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 si19_sequencial = int8 = sequencial
                 si19_tiporegistro = int8 = Tipo do registro
                 si19_cnpjconsorcio = varchar(2) = C�digo do  Cons�rcio
                 si19_codfontrecursos = int8 = C�digo da fonte de recursos
                 si19_vldispcaixa = float8 = Valor da  disponibilidade
                 si19_mes = int8 = M�s
                 si19_instit = int8 = Instit
                 ";

  //funcao construtor da classe
  function cl_consor402024()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("consor402024");
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
      $this->si19_sequencial = ($this->si19_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si19_sequencial"] : $this->si19_sequencial);
      $this->si19_tiporegistro = ($this->si19_tiporegistro == "" ? @$GLOBALS["HTTP_POST_VARS"]["si19_tiporegistro"] : $this->si19_tiporegistro);
      $this->si19_cnpjconsorcio = ($this->si19_cnpjconsorcio == "" ? @$GLOBALS["HTTP_POST_VARS"]["si19_cnpjconsorcio"] : $this->si19_cnpjconsorcio);
      $this->si19_codfontrecursos = ($this->si19_codfontrecursos == "" ? @$GLOBALS["HTTP_POST_VARS"]["si19_codfontrecursos"] : $this->si19_codfontrecursos);
      $this->si19_vldispcaixa = ($this->si19_vldispcaixa == "" ? @$GLOBALS["HTTP_POST_VARS"]["si19_vldispcaixa"] : $this->si19_vldispcaixa);
      $this->si19_mes = ($this->si19_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si19_mes"] : $this->si19_mes);
      $this->si19_instit = ($this->si19_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["si19_instit"] : $this->si19_instit);
    } else {
      $this->si19_sequencial = ($this->si19_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si19_sequencial"] : $this->si19_sequencial);
    }
  }

  // funcao para inclusao
  function incluir($si19_sequencial)
  {
    $this->atualizacampos();
    if ($this->si19_tiporegistro == null) {
      $this->erro_sql = " Campo Tipo do registro nao Informado.";
      $this->erro_campo = "si19_tiporegistro";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si19_vldispcaixa == null) {
      $this->erro_sql = " Campo Valor da  disponibilidade nao Informado.";
      $this->erro_campo = "si19_vldispcaixa";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si19_mes == null) {
      $this->erro_sql = " Campo M�s nao Informado.";
      $this->erro_campo = "si19_mes";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si19_instit == null) {
      $this->erro_sql = " Campo Instit nao Informado.";
      $this->erro_campo = "si19_instit";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }

    if ($this->si19_cnpjconsorcio == null) {
      $this->erro_sql = " Campo CNPJ nao Informado.";
      $this->erro_campo = "si19_cnpjconsorcio";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si19_codfontrecursos == null) {
      $this->erro_sql = " Campo C�digo da fonte de recursos nao Informado.";
      $this->erro_campo = "si19_codfontrecursos";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($si19_sequencial == "" || $si19_sequencial == null) {
      $result = db_query("select nextval('consor402024_si19_sequencial_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("
", "", @pg_last_error());
        $this->erro_sql = "Verifique o cadastro da sequencia: consor402024_si19_sequencial_seq do campo: si19_sequencial";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
      $this->si19_sequencial = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from consor402024_si19_sequencial_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $si19_sequencial)) {
        $this->erro_sql = " Campo si19_sequencial maior que �ltimo n�mero da sequencia.";
        $this->erro_banco = "Sequencia menor que este n�mero.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      } else {
        $this->si19_sequencial = $si19_sequencial;
      }
    }
    if (($this->si19_sequencial == null) || ($this->si19_sequencial == "")) {
      $this->erro_sql = " Campo si19_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    $sql = "insert into consor402024(
                                       si19_sequencial
                                      ,si19_tiporegistro
                                      ,si19_cnpjconsorcio
                                      ,si19_codfontrecursos
                                      ,si19_vldispcaixa
                                      ,si19_mes
                                      ,si19_instit
                       )
                values (
                                $this->si19_sequencial
                               ,$this->si19_tiporegistro
                               ,'$this->si19_cnpjconsorcio'
                               ,$this->si19_codfontrecursos
                               ,$this->si19_vldispcaixa
                               ,$this->si19_mes
                               ,$this->si19_instit
                      )";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql = "consor402024 ($this->si19_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_banco = "consor402024 j� Cadastrado";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      } else {
        $this->erro_sql = "consor402024 ($this->si19_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;

      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\n";
    $this->erro_sql .= "Valores : " . $this->si19_sequencial;
    $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->si19_sequencial));
//    if (($resaco != false) || ($this->numrows != 0)) {
//      $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
//      $acount = pg_result($resac, 0, 0);
//      $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
//      $resac = db_query("insert into db_acountkey values($acount,2009646,'$this->si19_sequencial','I')");
//      $resac = db_query("insert into db_acount values($acount,2010247,2009646,'','" . AddSlashes(pg_result($resaco, 0, 'si19_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010247,2009643,'','" . AddSlashes(pg_result($resaco, 0, 'si19_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010247,2009644,'','" . AddSlashes(pg_result($resaco, 0, 'si19_codconsorcio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010247,2009645,'','" . AddSlashes(pg_result($resaco, 0, 'si19_vldispcaixa')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      $resac = db_query("insert into db_acount values($acount,2010247,2009739,'','" . AddSlashes(pg_result($resaco, 0, 'si19_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//    }

    return true;
  }

  // funcao para alteracao
  function alterar($si19_sequencial = null)
  {
    $this->atualizacampos();
    $sql = " update consor402024 set ";
    $virgula = "";
    if (trim($this->si19_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si19_sequencial"])) {
      $sql .= $virgula . " si19_sequencial = $this->si19_sequencial ";
      $virgula = ",";
      if (trim($this->si19_sequencial) == null) {
        $this->erro_sql = " Campo sequencial nao Informado.";
        $this->erro_campo = "si19_sequencial";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->si19_tiporegistro) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si19_tiporegistro"])) {
      $sql .= $virgula . " si19_tiporegistro = $this->si19_tiporegistro ";
      $virgula = ",";
      if (trim($this->si19_tiporegistro) == null) {
        $this->erro_sql = " Campo Tipo do registro nao Informado.";
        $this->erro_campo = "si19_tiporegistro";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->si19_cnpjconsorcio) != "" && isset($GLOBALS["HTTP_POST_VARS"]["si19_cnpjconsorcio"])) {
      $sql .= $virgula . " si19_cnpjconsorcio = '$this->si19_cnpjconsorcio' ";
      $virgula = ",";
    }
    if (trim($this->si19_codfontrecursos) != "" && isset($GLOBALS["HTTP_POST_VARS"]["si19_codfontrecursos"])) {
      $sql .= $virgula . " si19_codfontrecursos = '$this->si19_codfontrecursos' ";
      $virgula = ",";
    }
    if (trim($this->si19_vldispcaixa) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si19_vldispcaixa"])) {
      $sql .= $virgula . " si19_vldispcaixa = $this->si19_vldispcaixa ";
      $virgula = ",";
      if (trim($this->si19_vldispcaixa) == null) {
        $this->erro_sql = " Campo Valor da  disponibilidade nao Informado.";
        $this->erro_campo = "si19_vldispcaixa";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->si19_mes) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si19_mes"])) {
      $sql .= $virgula . " si19_mes = $this->si19_mes ";
      $virgula = ",";
      if (trim($this->si19_mes) == null) {
        $this->erro_sql = " Campo M�s nao Informado.";
        $this->erro_campo = "si19_mes";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    $sql .= " where ";
    if ($si19_sequencial != null) {
      $sql .= " si19_sequencial = $this->si19_sequencial";
    }
    $resaco = $this->sql_record($this->sql_query_file($this->si19_sequencial));
//    if ($this->numrows > 0) {
//      for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
//        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
//        $acount = pg_result($resac, 0, 0);
//        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
//        $resac = db_query("insert into db_acountkey values($acount,2009646,'$this->si19_sequencial','A')");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si19_sequencial"]) || $this->si19_sequencial != "")
//          $resac = db_query("insert into db_acount values($acount,2010247,2009646,'" . AddSlashes(pg_result($resaco, $conresaco, 'si19_sequencial')) . "','$this->si19_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si19_tiporegistro"]) || $this->si19_tiporegistro != "")
//          $resac = db_query("insert into db_acount values($acount,2010247,2009643,'" . AddSlashes(pg_result($resaco, $conresaco, 'si19_tiporegistro')) . "','$this->si19_tiporegistro'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si19_codconsorcio"]) || $this->si19_codconsorcio != "")
//          $resac = db_query("insert into db_acount values($acount,2010247,2009644,'" . AddSlashes(pg_result($resaco, $conresaco, 'si19_codconsorcio')) . "','$this->si19_codconsorcio'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si19_vldispcaixa"]) || $this->si19_vldispcaixa != "")
//          $resac = db_query("insert into db_acount values($acount,2010247,2009645,'" . AddSlashes(pg_result($resaco, $conresaco, 'si19_vldispcaixa')) . "','$this->si19_vldispcaixa'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        if (isset($GLOBALS["HTTP_POST_VARS"]["si19_mes"]) || $this->si19_mes != "")
//          $resac = db_query("insert into db_acount values($acount,2010247,2009739,'" . AddSlashes(pg_result($resaco, $conresaco, 'si19_mes')) . "','$this->si19_mes'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      }
//    }
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "consor402024 nao Alterado. Alteracao Abortada.\n";
      $this->erro_sql .= "Valores : " . $this->si19_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "consor402024 nao foi Alterado. Alteracao Executada.\n";
        $this->erro_sql .= "Valores : " . $this->si19_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $this->si19_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);

        return true;
      }
    }
  }

  // funcao para exclusao
  function excluir($si19_sequencial = null, $dbwhere = null)
  {
    if ($dbwhere == null || $dbwhere == "") {
      $resaco = $this->sql_record($this->sql_query_file($si19_sequencial));
    } else {
      $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
    }
//    if (($resaco != false) || ($this->numrows != 0)) {
//      for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
//        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
//        $acount = pg_result($resac, 0, 0);
//        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
//        $resac = db_query("insert into db_acountkey values($acount,2009646,'$si19_sequencial','E')");
//        $resac = db_query("insert into db_acount values($acount,2010247,2009646,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si19_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010247,2009643,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si19_tiporegistro')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010247,2009644,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si19_codconsorcio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010247,2009645,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si19_vldispcaixa')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//        $resac = db_query("insert into db_acount values($acount,2010247,2009739,'','" . AddSlashes(pg_result($resaco, $iresaco, 'si19_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
//      }
//    }
    $sql = " delete from consor402024
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($si19_sequencial != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " si19_sequencial = $si19_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "consor402024 nao Exclu�do. Exclus�o Abortada.\n";
      $this->erro_sql .= "Valores : " . $si19_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "consor402024 nao Encontrado. Exclus�o n�o Efetuada.\n";
        $this->erro_sql .= "Valores : " . $si19_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $si19_sequencial;
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
      $this->erro_sql = "Record Vazio na Tabela:consor402024";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }

    return $result;
  }

  // funcao do sql
  function sql_query($si19_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from consor402024 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si19_sequencial != null) {
        $sql2 .= " where consor402024.si19_sequencial = $si19_sequencial ";
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
  function sql_query_file($si19_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from consor402024 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si19_sequencial != null) {
        $sql2 .= " where consor402024.si19_sequencial = $si19_sequencial ";
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

<?
//MODULO: configura��es
//CLASSE DA ENTIDADE manutencaoacordo
class cl_manutencaoacordo
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
  var $manutac_sequencial = 0;
  var $manutac_numeroant = null;
  var $manutac_codunidsubanterior = null;
  var $manutac_acordo = 0;

  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 manutac_sequencial = int8 = Sequencial
                 manutac_codunidsubanterior = int4 = Cod unid sub anterior
                 manutac_acordo = int8 = Numera��o
                 manutac_numeroant = varchar = numero do contrato anterior
                 ";

  //funcao construtor da classe
  function cl_manutencaoacordo()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("manutencaoacordo");
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
  function atualizacampos()
  {
    $this->manutac_sequencial = ($this->manutac_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["manutac_sequencial"] : $this->manutac_sequencial);
    $this->manutac_codunidsubanterior = ($this->manutac_codunidsubanterior == "" ? @$GLOBALS["HTTP_POST_VARS"]["manutac_codunidsubanterior"] : $this->manutac_codunidsubanterior);
    $this->manutac_acordo = ($this->manutac_acordo == "" ? @$GLOBALS["HTTP_POST_VARS"]["manutac_acordo"] : $this->manutac_acordo);
    $this->manutac_numeroant = ($this->manutac_numeroant == "" ? @$GLOBALS["HTTP_POST_VARS"]["manutac_numeroant"] : $this->manutac_numeroant);
  }

  // funcao para inclusao aqui
  function incluir($manutac_sequencial = null)
  {
    $this->atualizacampos();

    if ($manutac_sequencial == "" || $manutac_sequencial == null) {
      $result = db_query("select nextval('manutencaoacordo_manutac_sequencial_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("\n", "", @pg_last_error());
        $this->erro_sql = "Verifique o cadastro da sequencia: manutencaoacordo_manutac_sequencial_seq do campo: manutac_sequencial";
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->manutac_sequencial = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from manutencaoacordo_manutac_sequencial_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $manutac_sequencial)) {
        $this->erro_sql = " Campo manutac_sequencial maior que �ltimo n�mero da sequencia.";
        $this->erro_banco = "Sequencia menor que este n�mero.";
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      } else {
        $this->manutac_sequencial = $manutac_sequencial;
      }
    }

    $sql = "insert into manutencaoacordo(
                                 manutac_sequencial
                ,manutac_codunidsubanterior
                ,manutac_acordo
                ,manutac_numeroant
                       )
                values (
                         $this->manutac_sequencial
                ,'$this->manutac_codunidsubanterior'
                ,$this->manutac_acordo
                ,'$this->manutac_numeroant'
                      )";

    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql = "manutencaoacordo ($this->manutac_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_banco = "manutencaoacordo j� Cadastrado";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      } else {
        $this->erro_sql = "manutencaoacordo ($this->manutac_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : " . $this->manutac_sequencial;
    $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    return true;
  }

  // funcao para alteracao
  function alterar($manutac_sequencial = null)
  {
    $this->atualizacampos();

    $sql = " update manutencaoacordo set ";
    $virgula = "";

    $sql .= " where ";
    if ($manutac_sequencial != null) {
      $sql .= " manutac_sequencial = $this->manutac_sequencial";
    }

    $result = db_query($sql);

    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql = "manutencaoacordo nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : " . $this->manutac_sequencial;
      $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "manutencaoacordo nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : " . $this->manutac_sequencial;
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->manutac_sequencial;
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }

  // funcao para exclusao
  public function excluir($manutac_sequencial = null, $dbwhere = null)
  {
    $sql = " delete from manutencaoacordo
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($manutac_sequencial != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " manutac_sequencial = $manutac_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql = "manutencaoacordo nao Exclu�do. Exclus�o Abortada.\\n";
      $this->erro_sql .= "Valores : " . $manutac_sequencial;
      $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "manutencaoacordo nao Encontrado. Exclus�o n�o Efetuada.\\n";
        $this->erro_sql .= "Valores : " . $manutac_sequencial;
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $manutac_sequencial;
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = pg_affected_rows($result);
        return true;
      }
    }
  }

  // funcao do recordset
  public function sql_record($sql)
  {
    $result = db_query($sql);
    if ($result == false) {
      $this->numrows = 0;
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql = "Erro ao selecionar os registros.";
      $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    $this->numrows = pg_numrows($result);
    if ($this->numrows == 0) {
      $this->erro_banco = "";
      $this->erro_sql = "Record Vazio na Tabela:manutencaoacordo";
      $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }

  // funcao do sql

  function sql_query($manutac_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "", $groupby = null)
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
    $sql .= " from manutencaoacordo ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($manutac_sequencial != null) {
        $sql2 .= " where manutencaoacordo.manutac_sequencial = $manutac_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($groupby != null) {
      $sql .= " group by ";
      $campos_sql = split("#", $groupby);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $groupby;
    }
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
  function sql_query_file($manutac_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from manutencaoacordo ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($manutac_sequencial != null) {
        $sql2 .= " where manutencaoacordo.manutac_sequencial = $manutac_sequencial ";
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

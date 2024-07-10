<?php
//MODULO: licitacao
//CLASSE DA ENTIDADE licanexopncpdocumento
class cl_licanexopncpdocumento
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
  public $l216_sequencial = 0;
  public $l216_licanexospncp = 0;
  public $l216_documento = null;
  public $l216_nomedocumento = null;
  public $l216_tipoanexo = 0;
  // cria propriedade com as variaveis do arquivo 
  public $campos = "
                 l216_sequencial = int8 = l216_sequencial 
                 l216_licanexospncp = int8 = l216_licanexospncp 
                 l216_documento = varchar(255) = l216_documento 
                 l216_nomedocumento = varchar(255) = l216_nomedocumento 
                 l216_tipoanexo = varchar(255) = l216_tipoanexo
                 ";

  //funcao construtor da classe 
  function __construct()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("licanexopncpdocumento");
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
      $this->l216_sequencial = ($this->l216_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["l216_sequencial"] : $this->l216_sequencial);
      $this->l216_licanexospncp = ($this->l216_licanexospncp == "" ? @$GLOBALS["HTTP_POST_VARS"]["l216_licanexospncp"] : $this->l216_licanexospncp);
      $this->l216_documento = ($this->l216_documento == "" ? @$GLOBALS["HTTP_POST_VARS"]["l216_documento"] : $this->l216_documento);
      $this->l216_nomedocumento = ($this->l216_nomedocumento == "" ? @$GLOBALS["HTTP_POST_VARS"]["l216_nomedocumento"] : $this->l216_nomedocumento);
      $this->l216_tipoanexo = ($this->l216_tipoanexo == "" ? @$GLOBALS["HTTP_POST_VARS"]["l216_tipoanexo"] : $this->l216_tipoanexo);
    } else {
    }
  }

  // funcao para inclusao
  function incluir()
  {
    $this->atualizacampos();
    if ($this->l216_licanexospncp == null) {
      $this->erro_sql = " Campo l216_licanexospncp n�o informado.";
      $this->erro_campo = "l216_licanexospncp";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->l216_documento == null) {
      $this->erro_sql = " Campo l216_documento n�o informado.";
      $this->erro_campo = "l216_documento";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->l216_nomedocumento == null) {
      $this->erro_sql = " Campo l216_nomedocumento n�o informado.";
      $this->erro_campo = "l216_nomedocumento";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->l216_tipoanexo == null) {
      $this->erro_sql = " Campo l216_tipoanexo n�o informado.";
      $this->erro_campo = "l216_tipoanexo";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->l216_sequencial == "" || $this->l216_sequencial == null) {
      $result = db_query("select nextval('licanexopncpdocumento_l216_sequencial_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("\n", "", @pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: licanexopncpdocumento_l216_sequencial_seq do campo: l216_sequencial";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->l216_sequencial = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from licanexopncpdocumento_l216_sequencial_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $this->l216_sequencial)) {
        $this->erro_sql = " Campo l216_sequencial maior que �ltimo n�mero da sequencia.";
        $this->erro_banco = "Sequencia menor que este n�mero.";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      } else {
        $this->l216_sequencial = $this->l216_sequencial;
      }
    }
    if (($this->l216_sequencial == null) || ($this->l216_sequencial == "")) {
      $this->erro_sql = " Campo l216_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into licanexopncpdocumento(
                                       l216_sequencial 
                                      ,l216_licanexospncp 
                                      ,l216_documento 
                                      ,l216_nomedocumento
                                      ,l216_tipoanexo
                       )
                values (
                                $this->l216_sequencial 
                               ,$this->l216_licanexospncp 
                               ,'$this->l216_documento' 
                               ,'$this->l216_nomedocumento' 
                               ,$this->l216_tipoanexo
                      )";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql   = "licanexopncpdocumento () nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_banco = "licanexopncpdocumento j� Cadastrado";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      } else {
        $this->erro_sql   = "licanexopncpdocumento () nao Inclu�do. Inclusao Abortada.";
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
  function alterar($l216_sequencial = null)
  {
    $this->atualizacampos();
    $sql = " update licanexopncpdocumento set ";
    $virgula = "";
    if (trim($this->l216_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l216_sequencial"])) {
      $sql  .= $virgula . " l216_sequencial = $this->l216_sequencial ";
      $virgula = ",";
      if (trim($this->l216_sequencial) == null) {
        $this->erro_sql = " Campo l216_sequencial n�o informado.";
        $this->erro_campo = "l216_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->l216_licanexospncp) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l216_licanexospncp"])) {
      $sql  .= $virgula . " l216_licanexospncp = $this->l216_licanexospncp ";
      $virgula = ",";
      if (trim($this->l216_licanexospncp) == null) {
        $this->erro_sql = " Campo l216_licanexospncp n�o informado.";
        $this->erro_campo = "l216_licanexospncp";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->l216_documento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l216_documento"])) {
      $sql  .= $virgula . " l216_documento = '$this->l216_documento' ";
      $virgula = ",";
      if (trim($this->l216_documento) == null) {
        $this->erro_sql = " Campo l216_documento n�o informado.";
        $this->erro_campo = "l216_documento";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->l216_nomedocumento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l216_nomedocumento"])) {
      $sql  .= $virgula . " l216_nomedocumento = '$this->l216_nomedocumento' ";
      $virgula = ",";
      if (trim($this->l216_nomedocumento) == null) {
        $this->erro_sql = " Campo l216_nomedocumento n�o informado.";
        $this->erro_campo = "l216_nomedocumento";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->l216_tipoanexo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l216_tipoanexo"])) {
      $sql  .= $virgula . " l216_tipoanexo = '$this->l216_tipoanexo' ";
      $virgula = ",";
      if (trim($this->l216_tipoanexo) == null) {
        $this->erro_sql = " Campo l216_tipoanexo n�o informado.";
        $this->erro_campo = "l216_tipoanexo";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    $sql .= "l216_sequencial = '$l216_sequencial'";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "licanexopncpdocumento nao Alterado. Alteracao Abortada.\\n";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "licanexopncpdocumento nao foi Alterado. Alteracao Executada.\\n";
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
  function excluir($l216_sequencial = null, $dbwhere = null)
  {

    $sql = " delete from licanexopncpdocumento
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      $sql2 = "l216_sequencial = $l216_sequencial";
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "licanexopncpdocumento nao Exclu�do. Exclus�o Abortada.\\n";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "licanexopncpdocumento nao Encontrado. Exclus�o n�o Efetuada.\\n";
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
      $this->erro_sql   = "Record Vazio na Tabela:licanexopncpdocumento";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }

  // funcao do sql 
  function sql_query($oid = null, $campos = "licanexopncpdocumento.oid,*", $ordem = null, $dbwhere = "")
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
    $sql .= " from licanexopncpdocumento ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($oid != "" && $oid != null) {
        $sql2 = " where licanexopncpdocumento.oid = '$oid'";
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
  function sql_query_file($oid = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from licanexopncpdocumento ";
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

  function verificalic($licicitacao)
  {
    $sql = "select * from licanexopncpdocumento 
    inner join licanexopncp on
      licanexopncp.l215_sequencial  = licanexopncpdocumento.l216_licanexospncp
    where 
      l215_liclicita = $licicitacao";

    return $sql;
  }
}

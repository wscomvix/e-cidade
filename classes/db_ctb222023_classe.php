<?
//MODULO: sicom
//CLASSE DA ENTIDADE ctb222023
class cl_ctb222023
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
  var $si98_sequencial = 0;
  var $si98_tiporegistro = 0;
  var $si98_codreduzidomov = 0;
  var $si98_ededucaodereceita = 0;
  var $si98_identificadordeducao = 0;
  var $si98_naturezareceita = 0;
  var $si98_codfontrecursos = 0;
  var $si98_codco = 0;
  var $si98_saldocec = 0;
  var $si98_vlrreceitacont = 0;
  var $si98_mes = 0;
  var $si98_reg21 = 0;
  var $si98_instit = 0;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 si98_sequencial = int8 = sequencial 
                 si98_tiporegistro = int8 = Tipo do  registro 
                 si98_codreduzidomov = int8 = C�digo Identificador da Movimenta��o 
                 si98_ededucaodereceita = int8 = dedu��o de  receita 
                 si98_identificadordeducao = int8 = Identificador da dedu��o da receita 
                 si98_naturezareceita = int8 = Natureza da receita 
                 si98_codfontrecursos = int8 = C�digo da fonte de recursos 
                 si98_codco = varchar = C�digo de Acompanhamento da Execu��o Or�ament�ria
                 si98_saldocec = int8 = Saldo comp�e ou n�o comp�e Caixa e Equivalentes de Caixa
                 si98_vlrreceitacont = float8 = Valor  correspondente �  receita 
                 si98_mes = int8 = M�s 
                 si98_reg21 = int8 = reg21 
                 si98_instit = int8 = Institui��o 
                 ";
  
  //funcao construtor da classe
  function cl_ctb222023()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("ctb222023");
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
      $this->si98_sequencial = ($this->si98_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si98_sequencial"] : $this->si98_sequencial);
      $this->si98_tiporegistro = ($this->si98_tiporegistro == "" ? @$GLOBALS["HTTP_POST_VARS"]["si98_tiporegistro"] : $this->si98_tiporegistro);
      $this->si98_codreduzidomov = ($this->si98_codreduzidomov == "" ? @$GLOBALS["HTTP_POST_VARS"]["si98_codreduzidomov"] : $this->si98_codreduzidomov);
      $this->si98_ededucaodereceita = ($this->si98_ededucaodereceita == "" ? @$GLOBALS["HTTP_POST_VARS"]["si98_ededucaodereceita"] : $this->si98_ededucaodereceita);
      $this->si98_identificadordeducao = ($this->si98_identificadordeducao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si98_identificadordeducao"] : $this->si98_identificadordeducao);
      $this->si98_naturezareceita = ($this->si98_naturezareceita == "" ? @$GLOBALS["HTTP_POST_VARS"]["si98_naturezareceita"] : $this->si98_naturezareceita);
      $this->si98_codfontrecursos = ($this->si98_codfontrecursos == "" ? @$GLOBALS["HTTP_POST_VARS"]["si98_codfontrecursos"] : $this->si98_codfontrecursos);
      $this->si98_codco = ($this->si98_codco == "" ? @$GLOBALS["HTTP_POST_VARS"]["si98_codco"] : $this->si98_codco);
      $this->si98_saldocec = ($this->si98_saldocec == "" ? @$GLOBALS["HTTP_POST_VARS"]["si98_saldocec"] : $this->si98_saldocec);
      $this->si98_vlrreceitacont = ($this->si98_vlrreceitacont == "" ? @$GLOBALS["HTTP_POST_VARS"]["si98_vlrreceitacont"] : $this->si98_vlrreceitacont);
      $this->si98_mes = ($this->si98_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si98_mes"] : $this->si98_mes);
      $this->si98_reg21 = ($this->si98_reg21 == "" ? @$GLOBALS["HTTP_POST_VARS"]["si98_reg21"] : $this->si98_reg21);
      $this->si98_instit = ($this->si98_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["si98_instit"] : $this->si98_instit);
    } else {
      $this->si98_sequencial = ($this->si98_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si98_sequencial"] : $this->si98_sequencial);
    }
  }
  
  // funcao para inclusao
  function incluir($si98_sequencial)
  {
    $this->atualizacampos();
    if ($this->si98_tiporegistro == null) {
      $this->erro_sql = " Campo Tipo do  registro nao Informado.";
      $this->erro_campo = "si98_tiporegistro";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      
      return false;
    }
    if ($this->si98_codreduzidomov == null) {
      $this->si98_codreduzidomov = "0";
    }
    if ($this->si98_ededucaodereceita == null) {
      $this->si98_ededucaodereceita = "0";
    }
    if ($this->si98_identificadordeducao == null) {
      $this->si98_identificadordeducao = "0";
    }
    if ($this->si98_naturezareceita == null) {
      $this->si98_naturezareceita = "0";
    }
    if ($this->si98_codfontrecursos == null) {
      $this->si98_codfontrecursos = "0";
    }
    if ($this->si98_codco == null) {
      $this->si98_codco = "0000";
    }
    if ($this->si98_saldocec == null) {
      $this->si98_saldocec = "0";
    }
    if ($this->si98_vlrreceitacont == null) {
      $this->si98_vlrreceitacont = "0";
    }
    if ($this->si98_mes == null) {
      $this->erro_sql = " Campo M�s nao Informado.";
      $this->erro_campo = "si98_mes";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      
      return false;
    }
    if ($this->si98_reg21 == null) {
      $this->si98_reg21 = "0";
    }
    if ($this->si98_instit == null) {
      $this->erro_sql = " Campo Institui��o nao Informado.";
      $this->erro_campo = "si98_instit";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      
      return false;
    }
    if ($si98_sequencial == "" || $si98_sequencial == null) {
      $result = db_query("select nextval('ctb222023_si98_sequencial_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("
", "", @pg_last_error());
        $this->erro_sql = "Verifique o cadastro da sequencia: ctb222023_si98_sequencial_seq do campo: si98_sequencial";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      }
      $this->si98_sequencial = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from ctb222023_si98_sequencial_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $si98_sequencial)) {
        $this->erro_sql = " Campo si98_sequencial maior que �ltimo n�mero da sequencia.";
        $this->erro_banco = "Sequencia menor que este n�mero.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      } else {
        $this->si98_sequencial = $si98_sequencial;
      }
    }
    if (($this->si98_sequencial == null) || ($this->si98_sequencial == "")) {
      $this->erro_sql = " Campo si98_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      
      return false;
    }
    $sql = "insert into ctb222023(
                                       si98_sequencial 
                                      ,si98_tiporegistro 
                                      ,si98_codreduzidomov 
                                      ,si98_ededucaodereceita 
                                      ,si98_identificadordeducao 
                                      ,si98_naturezareceita 
                                      ,si98_codfontrecursos
                                      ,si98_codco
                                      ,si98_saldocec
                                      ,si98_vlrreceitacont 
                                      ,si98_mes 
                                      ,si98_reg21 
                                      ,si98_instit 
                       )
                values (
                                $this->si98_sequencial 
                               ,$this->si98_tiporegistro 
                               ,$this->si98_codreduzidomov 
                               ,$this->si98_ededucaodereceita 
                               ,$this->si98_identificadordeducao 
                               ,$this->si98_naturezareceita
                               ,$this->si98_codfontrecursos 
                               ,'$this->si98_codco'
                               ,$this->si98_saldocec
                               ,$this->si98_vlrreceitacont 
                               ,$this->si98_mes 
                               ,$this->si98_reg21 
                               ,$this->si98_instit 
                      )";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql = "ctb222023 ($this->si98_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_banco = "ctb222023 j� Cadastrado";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      } else {
        $this->erro_sql = "ctb222023 ($this->si98_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;
      
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\n";
    $this->erro_sql .= "Valores : " . $this->si98_sequencial;
    $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    
    return true;
  }
  
  // funcao para alteracao
  function alterar($si98_sequencial = null)
  {
    $this->atualizacampos();
    $sql = " update ctb222023 set ";
    $virgula = "";
    if (trim($this->si98_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si98_sequencial"])) {
      if (trim($this->si98_sequencial) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si98_sequencial"])) {
        $this->si98_sequencial = "0";
      }
      $sql .= $virgula . " si98_sequencial = $this->si98_sequencial ";
      $virgula = ",";
    }
    if (trim($this->si98_tiporegistro) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si98_tiporegistro"])) {
      $sql .= $virgula . " si98_tiporegistro = $this->si98_tiporegistro ";
      $virgula = ",";
      if (trim($this->si98_tiporegistro) == null) {
        $this->erro_sql = " Campo Tipo do  registro nao Informado.";
        $this->erro_campo = "si98_tiporegistro";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      }
    }
    if (trim($this->si98_codreduzidomov) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si98_codreduzidomov"])) {
      if (trim($this->si98_codreduzidomov) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si98_codreduzidomov"])) {
        $this->si98_codreduzidomov = "0";
      }
      $sql .= $virgula . " si98_codreduzidomov = $this->si98_codreduzidomov ";
      $virgula = ",";
    }
    if (trim($this->si98_ededucaodereceita) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si98_ededucaodereceita"])) {
      if (trim($this->si98_ededucaodereceita) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si98_ededucaodereceita"])) {
        $this->si98_ededucaodereceita = "0";
      }
      $sql .= $virgula . " si98_ededucaodereceita = $this->si98_ededucaodereceita ";
      $virgula = ",";
    }
    if (trim($this->si98_identificadordeducao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si98_identificadordeducao"])) {
      if (trim($this->si98_identificadordeducao) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si98_identificadordeducao"])) {
        $this->si98_identificadordeducao = "0";
      }
      $sql .= $virgula . " si98_identificadordeducao = $this->si98_identificadordeducao ";
      $virgula = ",";
    }
    if (trim($this->si98_naturezareceita) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si98_naturezareceita"])) {
      if (trim($this->si98_naturezareceita) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si98_naturezareceita"])) {
        $this->si98_naturezareceita = "0";
      }
      $sql .= $virgula . " si98_naturezareceita = $this->si98_naturezareceita ";
      $virgula = ",";
    }
    if (trim($this->si98_codfontrecursos) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si98_codfontrecursos"])) {
      if (trim($this->si98_codfontrecursos) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si98_codfontrecursos"])) {
        $this->si98_codfontrecursos = "0";
      }
      $sql .= $virgula . " si98_codfontrecursos = $this->si98_codfontrecursos ";
      $virgula = ",";
    }
    if (trim($this->si98_codco) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si98_codco"])) {
      if (trim($this->si98_codco) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si98_codco"])) {
        $this->si98_codco = "0";
      }
      $sql .= $virgula . " si98_codco = $this->si98_codco ";
      $virgula = ",";
    }
    if (trim($this->si98_saldocec) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si98_saldocec"])) {
      if (trim($this->si98_saldocec) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si98_saldocec"])) {
        $this->si98_saldocec = "0";
      }
      $sql .= $virgula . " si98_saldocec = $this->si98_saldocec ";
      $virgula = ",";
    }
    if (trim($this->si98_vlrreceitacont) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si98_vlrreceitacont"])) {
      if (trim($this->si98_vlrreceitacont) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si98_vlrreceitacont"])) {
        $this->si98_vlrreceitacont = "0";
      }
      $sql .= $virgula . " si98_vlrreceitacont = $this->si98_vlrreceitacont ";
      $virgula = ",";
    }
    if (trim($this->si98_mes) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si98_mes"])) {
      $sql .= $virgula . " si98_mes = $this->si98_mes ";
      $virgula = ",";
      if (trim($this->si98_mes) == null) {
        $this->erro_sql = " Campo M�s nao Informado.";
        $this->erro_campo = "si98_mes";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      }
    }
    if (trim($this->si98_reg21) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si98_reg21"])) {
      if (trim($this->si98_reg21) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si98_reg21"])) {
        $this->si98_reg21 = "0";
      }
      $sql .= $virgula . " si98_reg21 = $this->si98_reg21 ";
      $virgula = ",";
    }
    if (trim($this->si98_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si98_instit"])) {
      $sql .= $virgula . " si98_instit = $this->si98_instit ";
      $virgula = ",";
      if (trim($this->si98_instit) == null) {
        $this->erro_sql = " Campo Institui��o nao Informado.";
        $this->erro_campo = "si98_instit";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      }
    }
    $sql .= " where ";
    if ($si98_sequencial != null) {
      $sql .= " si98_sequencial = $this->si98_sequencial";
    }
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "ctb222023 nao Alterado. Alteracao Abortada.\n";
      $this->erro_sql .= "Valores : " . $this->si98_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "ctb222023 nao foi Alterado. Alteracao Executada.\n";
        $this->erro_sql .= "Valores : " . $this->si98_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $this->si98_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        
        return true;
      }
    }
  }
  
  // funcao para exclusao
  function excluir($si98_sequencial = null, $dbwhere = null)
  {
    if ($dbwhere == null || $dbwhere == "") {
      $resaco = $this->sql_record($this->sql_query_file($si98_sequencial));
    } else {
      $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
    }
    $sql = " delete from ctb222023
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($si98_sequencial != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " si98_sequencial = $si98_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "ctb222023 nao Exclu�do. Exclus�o Abortada.\n";
      $this->erro_sql .= "Valores : " . $si98_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "ctb222023 nao Encontrado. Exclus�o n�o Efetuada.\n";
        $this->erro_sql .= "Valores : " . $si98_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $si98_sequencial;
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
      $this->erro_sql = "Record Vazio na Tabela:ctb222023";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      
      return false;
    }
    
    return $result;
  }
  
  // funcao do sql
  function sql_query($si98_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from ctb222023 ";
    $sql .= "      left  join ctb212020  on  ctb212020.si97_sequencial = ctb222023.si98_reg21";
    $sql .= "      left  join ctb202020  on  ctb202020.si96_sequencial = ctb212020.si97_reg20";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si98_sequencial != null) {
        $sql2 .= " where ctb222023.si98_sequencial = $si98_sequencial ";
      }
    } else {
      if ($dbwhere != "") {
        $sql2 = " where $dbwhere";
      }
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
  function sql_query_file($si98_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from ctb222023 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si98_sequencial != null) {
        $sql2 .= " where ctb222023.si98_sequencial = $si98_sequencial ";
      }
    } else {
      if ($dbwhere != "") {
        $sql2 = " where $dbwhere";
      }
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

  /**
   * Consulta para geracao do Registro 22
   *
   * @param integer $ano
   * @param integer $codReduzido
   * @return $sSql
   */
  public function sql_Reg22($ano, $codReduzido)
  {
    $sSql = " SELECT 22 AS tiporegistro,
                     c74_codlan AS codreduzdio,
                     CASE
                         WHEN substr(o57_fonte,1,2) = '49' THEN 1
                         ELSE 2
                     END AS ededucaodereceita,
                     CASE
                         WHEN substr(o57_fonte,1,2) = '49' THEN substr(o57_fonte,2,2)
                         ELSE NULL
                     END AS identificadordeducao,
                     CASE
                         WHEN substr(o57_fonte,1,2) = '49' THEN substr(o57_fonte,4,8)
                         ELSE substr(o57_fonte,2,8)
                     END AS naturezaReceita,
                     c70_valor AS vlrreceitacont,
                     k81_emparlamentar,
                     o70_codigo
              FROM conlancamrec
              JOIN conlancam ON c70_codlan = c74_codlan AND c70_anousu = c74_anousu              
              JOIN conlancamcorrente ON c86_conlancam = c70_codlan
              JOIN corrente ON (c86_id, c86_data, c86_autent) = (corrente.k12_id, corrente.k12_data, corrente.k12_autent)
              LEFT JOIN corplacaixa ON (corrente.k12_id, corrente.k12_data, corrente.k12_autent) = (k82_id, k82_data, k82_autent)
              LEFT JOIN placaixarec ON k82_seqpla = k81_seqpla              
              LEFT JOIN orcreceita ON c74_codrec = o70_codrec AND o70_anousu = {$ano}
              LEFT JOIN orcfontes ON o70_codfon = o57_codfon AND o70_anousu = o57_anousu
              LEFT JOIN orctiporec ON o15_codigo = o70_codigo
              WHERE c74_codlan = {$codReduzido}";

    return $sSql;
  }
}

?>

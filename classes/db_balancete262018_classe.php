<?
//MODULO: sicom
//CLASSE DA ENTIDADE balancete262018
class cl_balancete262018
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
  var $si193_sequencial = 0;
  var $si193_tiporegistro = 0;
  var $si193_contacontabil = 0;
  var $si193_codfundo = 0;
  var $si193_tipodocumentopessoaatributosf = 0;
  var $si193_nrodocumentopessoaatributosf = null;
  var $si193_atributosf = null;
  var $si193_saldoinicialpessoaatributosf = 0;
  var $si193_naturezasaldoinicialpessoaatributosf = null;
  var $si193_totaldebitospessoaatributosf = 0;
  var $si193_totalcreditospessoaatributosf = 0;
  var $si193_saldofinalpessoaatributosf = 0;
  var $si193_naturezasaldofinalpessoaatributosf = null;
  var $si193_mes = 0;
  var $si193_instit = 0;
  var $si193_reg10 = null;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 si193_sequencial = int8 = si193_sequencial 
                 si193_tiporegistro = int8 = si193_tiporegistro 
                 si193_contacontabil = int8 = si193_contacontabil 
                 si193_codfundo = int8 = si193_codfundo 
                 si193_tipodocumentopessoaatributosf = int8 = si193_tipodocumentopessoaatributosf
                 si193_nrodocumentopessoaatributosf = varchar(14) = si193_nrodocumentopessoaatributosf
                 si193_atributosf = varchar(1) = si193_atributosf 
                 si193_saldoinicialpessoaatributosf = float8 = si193_saldoinicialpessoaatributosf 
                 si193_naturezasaldoinicialpessoaatributosf = varchar(1) = si193_naturezasaldoinicialpessoaatributosf 
                 si193_totaldebitospessoaatributosf = float8 = si193_totaldebitospessoaatributosf 
                 si193_totalcreditospessoaatributosf = float8 = si193_totalcreditospessoaatributosf 
                 si193_saldofinalpessoaatributosf = float8 = si193_saldofinalpessoaatributosf 
                 si193_naturezasaldofinalpessoaatributosf = varchar(1) = si193_naturezasaldofinalpessoaatributosf 
                 si193_mes = int8 = si193_mes 
                 si193_instit = int8 = si193_instit 
                 ";
  
  //funcao construtor da classe
  function cl_balancete262018()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("balancete262018");
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
      $this->si193_sequencial = ($this->si193_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si193_sequencial"] : $this->si193_sequencial);
      $this->si193_tiporegistro = ($this->si193_tiporegistro == "" ? @$GLOBALS["HTTP_POST_VARS"]["si193_tiporegistro"] : $this->si193_tiporegistro);
      $this->si193_contacontabil = ($this->si193_contacontabil == "" ? @$GLOBALS["HTTP_POST_VARS"]["si193_contacontabil"] : $this->si193_contacontabil);
      $this->si193_codfundo = ($this->si193_codfundo == "" ? @$GLOBALS["HTTP_POST_VARS"]["si193_codfundo"] : $this->si193_codfundo);
      $this->si193_tipodocumentopessoaatributosf = ($this->si193_tipodocumentopessoaatributosf == "" ? @$GLOBALS["HTTP_POST_VARS"]["si193_tipodocumentopessoaatributosf"] : $this->si193_tipodocumentopessoaatributosf);
      $this->si193_nrodocumentopessoaatributosf = ($this->si193_nrodocumentopessoaatributosf == "" ? @$GLOBALS["HTTP_POST_VARS"]["si193_nrodocumentopessoaatributosf"] : $this->si193_nrodocumentopessoaatributosf);
      $this->si193_atributosf = ($this->si193_atributosf == "" ? @$GLOBALS["HTTP_POST_VARS"]["si193_atributosf"] : $this->si193_atributosf);
      $this->si193_saldoinicialpessoaatributosf = ($this->si193_saldoinicialpessoaatributosf == "" ? @$GLOBALS["HTTP_POST_VARS"]["si193_saldoinicialpessoaatributosf"] : $this->si193_saldoinicialpessoaatributosf);
      $this->si193_naturezasaldoinicialpessoaatributosf = ($this->si193_naturezasaldoinicialpessoaatributosf == "" ? @$GLOBALS["HTTP_POST_VARS"]["si193_naturezasaldoinicialpessoaatributosf"] : $this->si193_naturezasaldoinicialpessoaatributosf);
      $this->si193_totaldebitospessoaatributosf = ($this->si193_totaldebitospessoaatributosf == "" ? @$GLOBALS["HTTP_POST_VARS"]["si193_totaldebitospessoaatributosf"] : $this->si193_totaldebitospessoaatributosf);
      $this->si193_totalcreditospessoaatributosf = ($this->si193_totalcreditospessoaatributosf == "" ? @$GLOBALS["HTTP_POST_VARS"]["si193_totalcreditospessoaatributosf"] : $this->si193_totalcreditospessoaatributosf);
      $this->si193_saldofinalpessoaatributosf = ($this->si193_saldofinalpessoaatributosf == "" ? @$GLOBALS["HTTP_POST_VARS"]["si193_saldofinalpessoaatributosf"] : $this->si193_saldofinalpessoaatributosf);
      $this->si193_naturezasaldofinalpessoaatributosf = ($this->si193_naturezasaldofinalpessoaatributosf == "" ? @$GLOBALS["HTTP_POST_VARS"]["si193_naturezasaldofinalpessoaatributosf"] : $this->si193_naturezasaldofinalpessoaatributosf);
      $this->si193_mes = ($this->si193_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si193_mes"] : $this->si193_mes);
      $this->si193_instit = ($this->si193_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["si193_instit"] : $this->si193_instit);
    } else {
      $this->si193_sequencial = ($this->si193_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si193_sequencial"] : $this->si193_sequencial);
    }
  }
  
  // funcao para inclusao
  function incluir($si193_sequencial)
  {
    $this->atualizacampos();
    if ($this->si193_tiporegistro == null) {
      $this->erro_sql = " Campo si193_tiporegistro n�o informado.";
      $this->erro_campo = "si193_tiporegistro";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      
      return false;
    }
    if ($this->si193_contacontabil == null) {
      $this->erro_sql = " Campo si193_contacontabil n�o informado.";
      $this->erro_campo = "si193_contacontabil";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      
      return false;
    }
    if ($this->si193_tipodocumentopessoaatributosf == null) {
      $this->erro_sql = " Campo si193_tipodocumentopessoaatributosf n�o informado.";
      $this->erro_campo = "si193_tipodocumentopessoaatributosf";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      
      return false;
    }
    if ($this->si193_nrodocumentopessoaatributosf == null) {
      $this->erro_sql = " Campo si193_nrodocumentopessoaatributosf n�o informado.";
      $this->erro_campo = "si193_nrodocumentopessoaatributosf";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      
      return false;
    }
    
    if ($this->si193_atributosf == null) {
      $this->erro_sql = " Campo si193_atributosf n�o informado.";
      $this->erro_campo = "si193_atributosf";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      
      return false;
    }
    
    if ($this->si193_saldoinicialpessoaatributosf == null) {
      $this->erro_sql = " Campo si193_saldoinicialpessoaatributosf n�o informado.";
      $this->erro_campo = "si193_saldoinicialpessoaatributosf";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      
      return false;
    }
    if ($this->si193_naturezasaldoinicialpessoaatributosf == null) {
      $this->erro_sql = " Campo si193_naturezasaldoinicialpessoaatributosf n�o informado.";
      $this->erro_campo = "si193_naturezasaldoinicialpessoaatributosf";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      
      return false;
    }
    if ($this->si193_totaldebitospessoaatributosf == null) {
      $this->erro_sql = " Campo si193_totaldebitospessoaatributosf n�o informado.";
      $this->erro_campo = "si193_totaldebitospessoaatributosf";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      
      return false;
    }
    if ($this->si193_totalcreditospessoaatributosf == null) {
      $this->erro_sql = " Campo si193_totalcreditospessoaatributosf n�o informado.";
      $this->erro_campo = "si193_totalcreditospessoaatributosf";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      
      return false;
    }
    if ($this->si193_saldofinalpessoaatributosf == null) {
      $this->erro_sql = " Campo si193_saldofinalpessoaatributosf n�o informado.";
      $this->erro_campo = "si193_saldofinalpessoaatributosf";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      
      return false;
    }
    if ($this->si193_naturezasaldofinalpessoaatributosf == null) {
      $this->erro_sql = " Campo si193_naturezasaldofinalpessoaatributosf n�o informado.";
      $this->erro_campo = "si193_naturezasaldofinalpessoaatributosf";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      
      return false;
    }
    if ($this->si193_mes == null) {
      $this->erro_sql = " Campo si193_mes n�o informado.";
      $this->erro_campo = "si193_mes";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      
      return false;
    }
    if ($this->si193_instit == null) {
      $this->erro_sql = " Campo si193_instit n�o informado.";
      $this->erro_campo = "si193_instit";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      
      return false;
    }
    
    if ($si193_sequencial == "" || $si193_sequencial == null) {
      $result = db_query("select nextval('balancete262018_si193_sequencial_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("
", "", @pg_last_error());
        $this->erro_sql = "Verifique o cadastro da sequencia: balancete262018_si193_sequencial_seq do campo: si193_sequencial";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      }
      $this->si193_sequencial = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from balancete262018_si193_sequencial_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $si193_sequencial)) {
        $this->erro_sql = " Campo si193_sequencial maior que �ltimo n�mero da sequencia.";
        $this->erro_banco = "Sequencia menor que este n�mero.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      } else {
        $this->si193_sequencial = $si193_sequencial;
      }
    }
    if (($this->si193_sequencial == null) || ($this->si193_sequencial == "")) {
      $this->erro_sql = " Campo si193_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      
      return false;
    }
    $sql = "insert into balancete262018(
                                       si193_sequencial 
                                      ,si193_tiporegistro 
                                      ,si193_contacontabil 
                                      ,si193_codfundo
                                      ,si193_tipodocumentopessoaatributosf
                                      ,si193_nrodocumentopessoaatributosf
                                      ,si193_atributosf 
                                      ,si193_saldoinicialpessoaatributosf 
                                      ,si193_naturezasaldoinicialpessoaatributosf 
                                      ,si193_totaldebitospessoaatributosf 
                                      ,si193_totalcreditospessoaatributosf 
                                      ,si193_saldofinalpessoaatributosf 
                                      ,si193_naturezasaldofinalpessoaatributosf 
                                      ,si193_mes 
                                      ,si193_instit
                                      ,si193_reg10
                       )
                values (
                                $this->si193_sequencial 
                               ,$this->si193_tiporegistro 
                               ,$this->si193_contacontabil 
                               ,'$this->si193_codfundo'
                               ,$this->si193_tipodocumentopessoaatributosf
                               ,'$this->si193_nrodocumentopessoaatributosf'
                               ,'$this->si193_atributosf' 
                               ,$this->si193_saldoinicialpessoaatributosf 
                               ,'$this->si193_naturezasaldoinicialpessoaatributosf' 
                               ,$this->si193_totaldebitospessoaatributosf 
                               ,$this->si193_totalcreditospessoaatributosf 
                               ,$this->si193_saldofinalpessoaatributosf 
                               ,'$this->si193_naturezasaldofinalpessoaatributosf' 
                               ,$this->si193_mes 
                               ,$this->si193_instit
                               ,$this->si193_reg10
                      )";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql = "balancete262018 ($this->si193_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_banco = "balancete262018 j� Cadastrado";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      } else {
        $this->erro_sql = "balancete262018 ($this->si193_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;
      
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\n";
    $this->erro_sql .= "Valores : " . $this->si193_sequencial;
    $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount) && ($lSessaoDesativarAccount === false))) {
      
      $resaco = $this->sql_record($this->sql_query_file($this->si193_sequencial));
      if (($resaco != false) || ($this->numrows != 0)) {
        
        /*$resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac,0,0);
        $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
        $resac = db_query("insert into db_acountkey values($acount,2011784,'$this->si193_sequencial','I')");
        $resac = db_query("insert into db_acount values($acount,1010197,2011784,'','".AddSlashes(pg_result($resaco,0,'si193_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1010197,2011785,'','".AddSlashes(pg_result($resaco,0,'si193_tiporegistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1010197,2011786,'','".AddSlashes(pg_result($resaco,0,'si193_contacontabil'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1010197,2011787,'','".AddSlashes(pg_result($resaco,0,'si193_atributosf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1010197,2011788,'','".AddSlashes(pg_result($resaco,0,'si193_saldoinicialpessoaatributosf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1010197,2011789,'','".AddSlashes(pg_result($resaco,0,'si193_naturezasaldoinicialpessoaatributosf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1010197,2011790,'','".AddSlashes(pg_result($resaco,0,'si193_totaldebitospessoaatributosf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1010197,2011791,'','".AddSlashes(pg_result($resaco,0,'si193_totalcreditospessoaatributosf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1010197,2011792,'','".AddSlashes(pg_result($resaco,0,'si193_saldofinalpessoaatributosf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1010197,2011793,'','".AddSlashes(pg_result($resaco,0,'si193_naturezasaldofinalpessoaatributosf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1010197,2011794,'','".AddSlashes(pg_result($resaco,0,'si193_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        $resac = db_query("insert into db_acount values($acount,1010197,2011795,'','".AddSlashes(pg_result($resaco,0,'si193_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");*/
      }
    }
    
    return true;
  }
  
  // funcao para alteracao
  function alterar($si193_sequencial = null)
  {
    $this->atualizacampos();
    $sql = " update balancete262018 set ";
    $virgula = "";
    if (trim($this->si193_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si193_sequencial"])) {
      $sql .= $virgula . " si193_sequencial = $this->si193_sequencial ";
      $virgula = ",";
      if (trim($this->si193_sequencial) == null) {
        $this->erro_sql = " Campo si193_sequencial n�o informado.";
        $this->erro_campo = "si193_sequencial";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      }
    }
    if (trim($this->si193_tiporegistro) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si193_tiporegistro"])) {
      $sql .= $virgula . " si193_tiporegistro = $this->si193_tiporegistro ";
      $virgula = ",";
      if (trim($this->si193_tiporegistro) == null) {
        $this->erro_sql = " Campo si193_tiporegistro n�o informado.";
        $this->erro_campo = "si193_tiporegistro";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      }
    }
    if (trim($this->si193_contacontabil) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si193_contacontabil"])) {
      $sql .= $virgula . " si193_contacontabil = $this->si193_contacontabil ";
      $virgula = ",";
      if (trim($this->si193_contacontabil) == null) {
        $this->erro_sql = " Campo si193_contacontabil n�o informado.";
        $this->erro_campo = "si193_contacontabil";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      }
    }
    if (trim($this->si193_codfundo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si193_codfundo"])) {
      $sql .= $virgula . " si193_codfundo = '$this->si193_codfundo' ";
      $virgula = ",";
      if (trim($this->si193_codfundo) == null) {
        $this->erro_sql = " Campo si193_codfundo n�o informado.";
        $this->erro_campo = "si193_codfundo";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      }
    }
    if (trim($this->si193_tipodocumentopessoaatributosf) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si193_tipodocumentopessoaatributosf"])) {
      $sql .= $virgula . " si193_tipodocumentopessoaatributosf = $this->si193_tipodocumentopessoaatributosf ";
      $virgula = ",";
      if (trim($this->si193_tipodocumentopessoaatributosf) == null) {
        $this->erro_sql = " Campo si193_tipodocumentopessoaatributosf n�o informado.";
        $this->erro_campo = "si193_tipodocumentopessoaatributosf";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      }
    }
    if (trim($this->si193_nrodocumentopessoaatributosf) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si193_nrodocumentopessoaatributosf"])) {
      $sql .= $virgula . " si193_nrodocumentopessoaatributosf = '$this->si193_nrodocumentopessoaatributosf' ";
      $virgula = ",";
      if (trim($this->si193_nrodocumentopessoaatributosf) == null) {
        $this->erro_sql = " Campo si193_nrodocumentopessoaatributosf n�o informado.";
        $this->erro_campo = "si193_nrodocumentopessoaatributosf";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      }
    }
    if (trim($this->si193_atributosf) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si193_atributosf"])) {
      $sql .= $virgula . " si193_atributosf = '$this->si193_atributosf' ";
      $virgula = ",";
      if (trim($this->si193_atributosf) == null) {
        $this->erro_sql = " Campo si193_atributosf n�o informado.";
        $this->erro_campo = "si193_atributosf";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      }
    }
    if (trim($this->si193_saldoinicialpessoaatributosf) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si193_saldoinicialpessoaatributosf"])) {
      $sql .= $virgula . " si193_saldoinicialpessoaatributosf = $this->si193_saldoinicialpessoaatributosf ";
      $virgula = ",";
      if (trim($this->si193_saldoinicialpessoaatributosf) == null) {
        $this->erro_sql = " Campo si193_saldoinicialpessoaatributosf n�o informado.";
        $this->erro_campo = "si193_saldoinicialpessoaatributosf";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      }
    }
    if (trim($this->si193_naturezasaldoinicialpessoaatributosf) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si193_naturezasaldoinicialpessoaatributosf"])) {
      $sql .= $virgula . " si193_naturezasaldoinicialpessoaatributosf = '$this->si193_naturezasaldoinicialpessoaatributosf' ";
      $virgula = ",";
      if (trim($this->si193_naturezasaldoinicialpessoaatributosf) == null) {
        $this->erro_sql = " Campo si193_naturezasaldoinicialpessoaatributosf n�o informado.";
        $this->erro_campo = "si193_naturezasaldoinicialpessoaatributosf";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      }
    }
    if (trim($this->si193_totaldebitospessoaatributosf) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si193_totaldebitospessoaatributosf"])) {
      $sql .= $virgula . " si193_totaldebitospessoaatributosf = $this->si193_totaldebitospessoaatributosf ";
      $virgula = ",";
      if (trim($this->si193_totaldebitospessoaatributosf) == null) {
        $this->erro_sql = " Campo si193_totaldebitospessoaatributosf n�o informado.";
        $this->erro_campo = "si193_totaldebitospessoaatributosf";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      }
    }
    if (trim($this->si193_totalcreditospessoaatributosf) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si193_totalcreditospessoaatributosf"])) {
      $sql .= $virgula . " si193_totalcreditospessoaatributosf = $this->si193_totalcreditospessoaatributosf ";
      $virgula = ",";
      if (trim($this->si193_totalcreditospessoaatributosf) == null) {
        $this->erro_sql = " Campo si193_totalcreditospessoaatributosf n�o informado.";
        $this->erro_campo = "si193_totalcreditospessoaatributosf";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      }
    }
    if (trim($this->si193_saldofinalpessoaatributosf) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si193_saldofinalpessoaatributosf"])) {
      $sql .= $virgula . " si193_saldofinalpessoaatributosf = $this->si193_saldofinalpessoaatributosf ";
      $virgula = ",";
      if (trim($this->si193_saldofinalpessoaatributosf) == null) {
        $this->erro_sql = " Campo si193_saldofinalpessoaatributosf n�o informado.";
        $this->erro_campo = "si193_saldofinalpessoaatributosf";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      }
    }
    if (trim($this->si193_naturezasaldofinalpessoaatributosf) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si193_naturezasaldofinalpessoaatributosf"])) {
      $sql .= $virgula . " si193_naturezasaldofinalpessoaatributosf = '$this->si193_naturezasaldofinalpessoaatributosf' ";
      $virgula = ",";
      if (trim($this->si193_naturezasaldofinalpessoaatributosf) == null) {
        $this->erro_sql = " Campo si193_naturezasaldofinalpessoaatributosf n�o informado.";
        $this->erro_campo = "si193_naturezasaldofinalpessoaatributosf";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      }
    }
    if (trim($this->si193_mes) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si193_mes"])) {
      $sql .= $virgula . " si193_mes = $this->si193_mes ";
      $virgula = ",";
      if (trim($this->si193_mes) == null) {
        $this->erro_sql = " Campo si193_mes n�o informado.";
        $this->erro_campo = "si193_mes";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      }
    }
    if (trim($this->si193_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si193_instit"])) {
      $sql .= $virgula . " si193_instit = $this->si193_instit ";
      $virgula = ",";
      if (trim($this->si193_instit) == null) {
        $this->erro_sql = " Campo si193_instit n�o informado.";
        $this->erro_campo = "si193_instit";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";
        
        return false;
      }
    }
    $sql .= " where ";
    if ($si193_sequencial != null) {
      $sql .= " si193_sequencial = $this->si193_sequencial";
    }
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount) && ($lSessaoDesativarAccount === false))) {
      
      $resaco = $this->sql_record($this->sql_query_file($this->si193_sequencial));
      if ($this->numrows > 0) {
        
        for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
          
          /*$resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac = db_query("insert into db_acountkey values($acount,2011784,'$this->si193_sequencial','A')");
          if(isset($GLOBALS["HTTP_POST_VARS"]["si193_sequencial"]) || $this->si193_sequencial != "")
            $resac = db_query("insert into db_acount values($acount,1010197,2011784,'".AddSlashes(pg_result($resaco,$conresaco,'si193_sequencial'))."','$this->si193_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["si193_tiporegistro"]) || $this->si193_tiporegistro != "")
            $resac = db_query("insert into db_acount values($acount,1010197,2011785,'".AddSlashes(pg_result($resaco,$conresaco,'si193_tiporegistro'))."','$this->si193_tiporegistro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["si193_contacontabil"]) || $this->si193_contacontabil != "")
            $resac = db_query("insert into db_acount values($acount,1010197,2011786,'".AddSlashes(pg_result($resaco,$conresaco,'si193_contacontabil'))."','$this->si193_contacontabil',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["si193_atributosf"]) || $this->si193_atributosf != "")
            $resac = db_query("insert into db_acount values($acount,1010197,2011787,'".AddSlashes(pg_result($resaco,$conresaco,'si193_atributosf'))."','$this->si193_atributosf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["si193_saldoinicialpessoaatributosf"]) || $this->si193_saldoinicialpessoaatributosf != "")
            $resac = db_query("insert into db_acount values($acount,1010197,2011788,'".AddSlashes(pg_result($resaco,$conresaco,'si193_saldoinicialpessoaatributosf'))."','$this->si193_saldoinicialpessoaatributosf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["si193_naturezasaldoinicialpessoaatributosf"]) || $this->si193_naturezasaldoinicialpessoaatributosf != "")
            $resac = db_query("insert into db_acount values($acount,1010197,2011789,'".AddSlashes(pg_result($resaco,$conresaco,'si193_naturezasaldoinicialpessoaatributosf'))."','$this->si193_naturezasaldoinicialpessoaatributosf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["si193_totaldebitospessoaatributosf"]) || $this->si193_totaldebitospessoaatributosf != "")
            $resac = db_query("insert into db_acount values($acount,1010197,2011790,'".AddSlashes(pg_result($resaco,$conresaco,'si193_totaldebitospessoaatributosf'))."','$this->si193_totaldebitospessoaatributosf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["si193_totalcreditospessoaatributosf"]) || $this->si193_totalcreditospessoaatributosf != "")
            $resac = db_query("insert into db_acount values($acount,1010197,2011791,'".AddSlashes(pg_result($resaco,$conresaco,'si193_totalcreditospessoaatributosf'))."','$this->si193_totalcreditospessoaatributosf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["si193_saldofinalpessoaatributosf"]) || $this->si193_saldofinalpessoaatributosf != "")
            $resac = db_query("insert into db_acount values($acount,1010197,2011792,'".AddSlashes(pg_result($resaco,$conresaco,'si193_saldofinalpessoaatributosf'))."','$this->si193_saldofinalpessoaatributosf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["si193_naturezasaldofinalpessoaatributosf"]) || $this->si193_naturezasaldofinalpessoaatributosf != "")
            $resac = db_query("insert into db_acount values($acount,1010197,2011793,'".AddSlashes(pg_result($resaco,$conresaco,'si193_naturezasaldofinalpessoaatributosf'))."','$this->si193_naturezasaldofinalpessoaatributosf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["si193_mes"]) || $this->si193_mes != "")
            $resac = db_query("insert into db_acount values($acount,1010197,2011794,'".AddSlashes(pg_result($resaco,$conresaco,'si193_mes'))."','$this->si193_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          if(isset($GLOBALS["HTTP_POST_VARS"]["si193_instit"]) || $this->si193_instit != "")
            $resac = db_query("insert into db_acount values($acount,1010197,2011795,'".AddSlashes(pg_result($resaco,$conresaco,'si193_instit'))."','$this->si193_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");*/
        }
      }
    }
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "balancete262018 nao Alterado. Alteracao Abortada.\n";
      $this->erro_sql .= "Valores : " . $this->si193_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "balancete262018 nao foi Alterado. Alteracao Executada.\n";
        $this->erro_sql .= "Valores : " . $this->si193_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $this->si193_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        
        return true;
      }
    }
  }
  
  // funcao para exclusao
  function excluir($si193_sequencial = null, $dbwhere = null)
  {
    
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount) && ($lSessaoDesativarAccount === false))) {
      
      if ($dbwhere == null || $dbwhere == "") {
        
        $resaco = $this->sql_record($this->sql_query_file($si193_sequencial));
      } else {
        $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
      }
      if (($resaco != false) || ($this->numrows != 0)) {
        
        for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
          
          /*$resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac  = db_query("insert into db_acountkey values($acount,2011784,'$si193_sequencial','E')");
          $resac  = db_query("insert into db_acount values($acount,1010197,2011784,'','".AddSlashes(pg_result($resaco,$iresaco,'si193_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,1010197,2011785,'','".AddSlashes(pg_result($resaco,$iresaco,'si193_tiporegistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,1010197,2011786,'','".AddSlashes(pg_result($resaco,$iresaco,'si193_contacontabil'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,1010197,2011787,'','".AddSlashes(pg_result($resaco,$iresaco,'si193_atributosf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,1010197,2011788,'','".AddSlashes(pg_result($resaco,$iresaco,'si193_saldoinicialpessoaatributosf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,1010197,2011789,'','".AddSlashes(pg_result($resaco,$iresaco,'si193_naturezasaldoinicialpessoaatributosf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,1010197,2011790,'','".AddSlashes(pg_result($resaco,$iresaco,'si193_totaldebitospessoaatributosf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,1010197,2011791,'','".AddSlashes(pg_result($resaco,$iresaco,'si193_totalcreditospessoaatributosf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,1010197,2011792,'','".AddSlashes(pg_result($resaco,$iresaco,'si193_saldofinalpessoaatributosf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,1010197,2011793,'','".AddSlashes(pg_result($resaco,$iresaco,'si193_naturezasaldofinalpessoaatributosf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,1010197,2011794,'','".AddSlashes(pg_result($resaco,$iresaco,'si193_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac  = db_query("insert into db_acount values($acount,1010197,2011795,'','".AddSlashes(pg_result($resaco,$iresaco,'si193_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");*/
        }
      }
    }
    $sql = " delete from balancete262018
                    where ";
    $sql2 = "";
    if ($dbwhere == null || $dbwhere == "") {
      if ($si193_sequencial != "") {
        if ($sql2 != "") {
          $sql2 .= " and ";
        }
        $sql2 .= " si193_sequencial = $si193_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "balancete262018 nao Exclu�do. Exclus�o Abortada.\n";
      $this->erro_sql .= "Valores : " . $si193_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "balancete262018 nao Encontrado. Exclus�o n�o Efetuada.\n";
        $this->erro_sql .= "Valores : " . $si193_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $si193_sequencial;
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
      $this->erro_sql = "Record Vazio na Tabela:balancete262018";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      
      return false;
    }
    
    return $result;
  }
  
  // funcao do sql
  function sql_query($si193_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from balancete262018 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si193_sequencial != null) {
        $sql2 .= " where balancete262018.si193_sequencial = $si193_sequencial ";
      }
    } else {
      if ($dbwhere != "") {
        $sql2 = " where $dbwhere";
      }
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
  function sql_query_file($si193_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from balancete262018 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si193_sequencial != null) {
        $sql2 .= " where balancete262018.si193_sequencial = $si193_sequencial ";
      }
    } else {
      if ($dbwhere != "") {
        $sql2 = " where $dbwhere";
      }
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

?>

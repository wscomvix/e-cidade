<?php
//MODULO: sicom
//CLASSE DA ENTIDADE licobras102023
class cl_licobras102023 {
  // cria variaveis de erro
  public $rotulo     = null;
  public $query_sql  = null;
  public $numrows    = 0;
  public $numrows_incluir = 0;
  public $numrows_alterar = 0;
  public $numrows_excluir = 0;
  public $erro_status= null;
  public $erro_sql   = null;
  public $erro_banco = null;
  public $erro_msg   = null;
  public $erro_campo = null;
  public $pagina_retorno = null;
  // cria variaveis do arquivo
  public $si195_sequencial = 0;
  public $si195_tiporegistro = 0;
  public $si195_codorgaoresp = null;
  public $si195_codunidadesubrespestadual = null;
  public $si195_exerciciolicitacao = 0;
  public $si195_nroprocessolicitatorio = null;
  public $si195_objeto = null;
  public $si195_codobra = 0;
  public $si195_linkobra = null;
  public $si195_nrolote = null;
  public $si195_contdeclicitacao = null;
  public $si195_codorgaorespsicom = null;
  public $si195_codunidadesubsicom = null;
  public $si195_nrocontrato = null;
  public $si195_exerciciocontrato = null;
  public $si195_dataassinatura = null;
  public $si195_vlcontrato = null;
  public $si195_undmedidaprazoexecucao = null;
  public $si195_prazoexecucao = null;
  public $si195_mes = 0;
  public $si195_instit = 0;
  // cria propriedade com as variaveis do arquivo
  public $campos = "
                 si195_sequencial = int8 = Sequencial
                 si195_tiporegistro = int8 = Tiporegistro
                 si195_codorgaoresp = text = codorgaoresp
                 si195_codunidadesubrespestadual = text = codUnidadeSubRespEstadual
                 si195_exerciciolicitacao = int4 = exercicioLicitacao
                 si195_nroprocessolicitatorio = text = nroProcessoLicitatorio
                 si195_objeto = text = objeto
                 si195_codobra = int8 = codigoobra
                 si195_linkobra = text = linkobra
                 si195_nrolote = int8 = numero do lote
                 si195_contdeclicitacao = int8 = si195_contdeclicitacao
                 si195_codorgaorespsicom = int8 = si195_codorgaorespsicom
                 si195_codunidadesubsicom = int8 = si195_codunidadesubsicom 
                 si195_nrocontrato = int8 = numero do contrato
                 si195_exerciciocontrato = int8 = execicio do contrato
                 si195_dataassinatura = date = data de assinatura
                 si195_vlcontrato = numeric = valor do contrato
                 si195_undmedidaprazoexecucao = int8 = und de execucao
                 si195_prazoexecucao = prazo de execucao
                 si195_mes = int4 = Mes
                 si195_instit = int4 = Institui��o
                 ";

  //funcao construtor da classe
  function __construct() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("licobras102023");
    $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
  }

  //funcao erro
  function erro($mostra,$retorna) {
    if (($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )) {
      echo "<script>alert(\"".$this->erro_msg."\");</script>";
      if ($retorna==true) {
        echo "<script>location.href='".$this->pagina_retorno."'</script>";
      }
    }
  }

  // funcao para atualizar campos
  function atualizacampos($exclusao=false) {
    if ($exclusao==false) {
      $this->si195_sequencial = ($this->si195_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_sequencial"]:$this->si195_sequencial);
      $this->si195_tiporegistro = ($this->si195_tiporegistro == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_tiporegistro"]:$this->si195_tiporegistro);
      $this->si195_codorgaoresp = ($this->si195_codorgaoresp == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_codorgaoresp"]:$this->si195_codorgaoresp);
      $this->si195_codunidadesubrespestadual = ($this->si195_codunidadesubrespestadual == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_codunidadesubrespestadual"]:$this->si195_codunidadesubrespestadual);
      $this->si195_exerciciolicitacao = ($this->si195_exerciciolicitacao == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_exerciciolicitacao"]:$this->si195_exerciciolicitacao);
      $this->si195_nroprocessolicitatorio = ($this->si195_nroprocessolicitatorio == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_nroprocessolicitatorio"]:$this->si195_nroprocessolicitatorio);
      $this->si195_codobra = ($this->si195_codobra == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_codobra"]:$this->si195_codobra);
      $this->si195_linkobra = ($this->si195_linkobra == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_linkobra"]:$this->si195_linkobra);
      $this->si195_nrolote = ($this->si195_nrolote == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_nrolote"]:$this->si195_nrolote);
      $this->si195_contdeclicitacao = ($this->si195_contdeclicitacao == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_contdeclicitacao"]:$this->si195_contdeclicitacao);
      $this->si195_codorgaorespsicom = ($this->si195_codorgaorespsicom == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_codorgaorespsicom"]:$this->si195_codorgaorespsicom);
      $this->si195_codunidadesubsicom = ($this->si195_codunidadesubsicom == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_codunidadesubsicom"]:$this->si195_codunidadesubsicom);
      $this->si195_nrocontrato = ($this->si195_nrocontrato == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_nrocontrato"]:$this->si195_nrocontrato);
      $this->si195_exerciciocontrato = ($this->si195_exerciciocontrato == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_exerciciocontrato"]:$this->si195_exerciciocontrato);
      $this->si195_dataassinatura = ($this->si195_dataassinatura == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_dataassinatura"]:$this->si195_dataassinatura);
      $this->si195_vlcontrato = ($this->si195_vlcontrato == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_vlcontrato"]:$this->si195_vlcontrato);
      $this->si195_undmedidaprazoexecucao = ($this->si195_undmedidaprazoexecucao == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_undmedidaprazoexecucao"]:$this->si195_undmedidaprazoexecucao);
      $this->si195_prazoexecucao = ($this->si195_prazoexecucao == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_prazoexecucao"]:$this->si195_prazoexecucao);
      $this->si195_mes = ($this->si195_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_mes"]:$this->si195_mes);
      $this->si195_instit = ($this->si195_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["si195_instit"]:$this->si195_instit);
    } else {
    }
  }

  // funcao para inclusao
  function incluir () {
    $this->atualizacampos();
    if ($this->si195_sequencial == null ) {
      $result = db_query("select nextval('licobras102023_si195_sequencial_seq')");
      if($result==false){
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: licobras102023_si195_sequencial_seq do campo: si195_sequencial";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->si195_sequencial = pg_result($result,0,0);
    }
    if ($this->si195_tiporegistro == null ) {
      $this->erro_sql = " Campo Tiporegistro n�o informado.";
      $this->erro_campo = "si195_tiporegistro";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si195_codorgaoresp == null ) {
      $this->erro_sql = " Campo codorgaoresp n�o informado.";
      $this->erro_campo = "si195_codorgaoresp";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si195_codunidadesubrespestadual == null ) {
      $this->erro_sql = " Campo codUnidadeSubRespEstadual n�o informado.";
      $this->erro_campo = "si195_codunidadesubrespestadual";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si195_exerciciolicitacao == null ) {
      $this->erro_sql = " Campo exercicioLicitacao n�o informado.";
      $this->erro_campo = "si195_exerciciolicitacao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si195_nroprocessolicitatorio == null ) {
      $this->erro_sql = " Campo nroProcessoLicitatorio n�o informado.";
      $this->erro_campo = "si195_nroprocessolicitatorio";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si195_codobra == null ) {
      $this->erro_sql = " Campo codigoobra n�o informado.";
      $this->erro_campo = "si195_codobra";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si195_linkobra == null ) {
      $this->erro_sql = " Campo linkobra n�o informado.";
      $this->erro_campo = "si195_linkobra";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($this->si195_nrolote == null ) {
      $this->erro_sql = " Campo nrolote n�o informado.";
      $this->erro_campo = "si195_nrolote";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($this->si195_nrocontrato == null ) {
      $this->erro_sql = " Contrato n�o localizado. Codigo da Obra: $this->si195_codobra";
      $this->erro_campo = "si195_nrocontrato";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($this->si195_exerciciocontrato == null ) {
      $this->erro_sql = " Campo exercicio do contrato n�o informado.";
      $this->erro_campo = "si195_exerciciocontrato";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($this->si195_dataassinatura == null ) {
      $this->erro_sql = "Contrato N� $this->si195_nrocontrato n�o assinado!";
      $this->erro_campo = "si195_dataassinatura";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($this->si195_vlcontrato == null ) {
      $this->erro_sql = "Contrato $this->si195_vlcontrato n�o informado!";
      $this->erro_campo = "si195_vlcontrato";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($this->si195_undmedidaprazoexecucao == null ) {
      $this->erro_sql = " Campo unidate prazo de execucao n�o informado.";
      $this->erro_campo = "si195_undmedidaprazoexecucao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($this->si195_prazoexecucao == null ) {
      $this->erro_sql = " Campo prazo de execucao n�o informado.";
      $this->erro_campo = "si195_prazoexecucao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($this->si195_mes == null ) {
      $this->erro_sql = " Campo Mes n�o informado.";
      $this->erro_campo = "si195_mes";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->si195_instit == null ) {
      $this->erro_sql = " Campo Institui��o n�o informado.";
      $this->erro_campo = "si195_instit";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }

    $sql = "insert into licobras102023(
                                       si195_sequencial
                                      ,si195_tiporegistro
                                      ,si195_codorgaoresp
                                      ,si195_codunidadesubrespestadual
                                      ,si195_exerciciolicitacao
                                      ,si195_nroprocessolicitatorio
                                      ,si195_nrolote
                                      ,si195_contdeclicitacao
                                      ,si195_codobra
                                      ,si195_objeto
                                      ,si195_linkobra
                                      ,si195_codorgaorespsicom
                                      ,si195_codunidadesubsicom
                                      ,si195_nrocontrato           
                                      ,si195_exerciciocontrato     
                                      ,si195_dataassinatura
                                      ,si195_vlcontrato        
                                      ,si195_undmedidaprazoexecucao
                                      ,si195_prazoexecucao         
                                      ,si195_mes
                                      ,si195_instit
                       )
                values (
                                $this->si195_sequencial
                               ,$this->si195_tiporegistro
                               ,'$this->si195_codorgaoresp'
                               ,'$this->si195_codunidadesubrespestadual'
                               ,$this->si195_exerciciolicitacao
                               ,'$this->si195_nroprocessolicitatorio'
                               ,$this->si195_nrolote
                               ,$this->si195_contdeclicitacao  
                               ,$this->si195_codobra
                               ,'$this->si195_objeto'
                               ,'$this->si195_linkobra'
                               ,$this->si195_codorgaorespsicom
                               ,$this->si195_codunidadesubsicom    
                               ,$this->si195_nrocontrato
                               ,$this->si195_exerciciocontrato
                               ,'$this->si195_dataassinatura'
                               ,$this->si195_vlcontrato
                               ,$this->si195_undmedidaprazoexecucao
                               ,$this->si195_prazoexecucao
                               ,$this->si195_mes
                               ,$this->si195_instit
                      )";
    $result = db_query($sql);
    if ($result==false) {
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if ( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ) {
        $this->erro_sql   = "cadastro de obras () nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "cadastro de obras j� Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      } else {
        $this->erro_sql   = "cadastro de obras () nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {

    }
    return true;
  }

  // funcao para alteracao
  function alterar ( $si195_sequencial=null ) {
    $this->atualizacampos();
    $sql = " update licobras102023 set ";
    $virgula = "";
    if (trim($this->si195_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_sequencial"])) {
      $sql  .= $virgula." si195_sequencial = $this->si195_sequencial ";
      $virgula = ",";
      if (trim($this->si195_sequencial) == null ) {
        $this->erro_sql = " Campo Sequencial n�o informado.";
        $this->erro_campo = "si195_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si195_tiporegistro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_tiporegistro"])) {
      $sql  .= $virgula." si195_tiporegistro = $this->si195_tiporegistro ";
      $virgula = ",";
      if (trim($this->si195_tiporegistro) == null ) {
        $this->erro_sql = " Campo Tiporegistro n�o informado.";
        $this->erro_campo = "si195_tiporegistro";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si195_codorgaoresp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_codorgaoresp"])) {
      $sql  .= $virgula." si195_codorgaoresp = '$this->si195_codorgaoresp' ";
      $virgula = ",";
      if (trim($this->si195_codorgaoresp) == null ) {
        $this->erro_sql = " Campo codorgaoresp n�o informado.";
        $this->erro_campo = "si195_codorgaoresp";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si195_codunidadesubrespestadual)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_codunidadesubrespestadual"])) {
      $sql  .= $virgula." si195_codunidadesubrespestadual = '$this->si195_codunidadesubrespestadual' ";
      $virgula = ",";
      if (trim($this->si195_codunidadesubrespestadual) == null ) {
        $this->erro_sql = " Campo codUnidadeSubRespEstadual n�o informado.";
        $this->erro_campo = "si195_codunidadesubrespestadual";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si195_exerciciolicitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_exerciciolicitacao"])) {
      $sql  .= $virgula." si195_exerciciolicitacao = $this->si195_exerciciolicitacao ";
      $virgula = ",";
      if (trim($this->si195_exerciciolicitacao) == null ) {
        $this->erro_sql = " Campo exercicioLicitacao n�o informado.";
        $this->erro_campo = "si195_exerciciolicitacao";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si195_nroprocessolicitatorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_nroprocessolicitatorio"])) {
      $sql  .= $virgula." si195_nroprocessolicitatorio = '$this->si195_nroprocessolicitatorio' ";
      $virgula = ",";
      if (trim($this->si195_nroprocessolicitatorio) == null ) {
        $this->erro_sql = " Campo nroProcessoLicitatorio n�o informado.";
        $this->erro_campo = "si195_nroprocessolicitatorio";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si195_codobra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_codobra"])) {
      $sql  .= $virgula." si195_codobra = $this->si195_codobra ";
      $virgula = ",";
      if (trim($this->si195_codobra) == null ) {
        $this->erro_sql = " Campo codigoobra n�o informado.";
        $this->erro_campo = "si195_codobra";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si195_linkobra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_linkobra"])) {
      $sql  .= $virgula." si195_linkobra = '$this->si195_linkobra' ";
      $virgula = ",";
      if (trim($this->si195_linkobra) == null ) {
        $this->erro_sql = " Campo linkobra n�o informado.";
        $this->erro_campo = "si195_linkobra";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si195_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_mes"])) {
      $sql  .= $virgula." si195_mes = $this->si195_mes ";
      $virgula = ",";
      if (trim($this->si195_mes) == null ) {
        $this->erro_sql = " Campo Mes n�o informado.";
        $this->erro_campo = "si195_mes";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->si195_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si195_instit"])) {
      $sql  .= $virgula." si195_instit = $this->si195_instit ";
      $virgula = ",";
      if (trim($this->si195_instit) == null ) {
        $this->erro_sql = " Campo Institui��o n�o informado.";
        $this->erro_campo = "si195_instit";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    $sql .= "si195_sequencial = '$si195_sequencial'";     $result = db_query($sql);
    if ($result==false) {
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "cadastro de obras nao Alterado. Alteracao Abortada.\\n";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {
      if (pg_affected_rows($result)==0) {
        $this->erro_banco = "";
        $this->erro_sql = "cadastro de obras nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }

  // funcao para exclusao
  function excluir ( $si195_sequencial=null ,$dbwhere=null) {

    $sql = " delete from licobras102023
                    where ";
    $sql2 = "";
    if ($dbwhere==null || $dbwhere =="") {
      $sql2 = "si195_sequencial = '$si195_sequencial'";
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql.$sql2);
    if ($result==false) {
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "cadastro de obras nao Exclu�do. Exclus�o Abortada.\\n";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    } else {
      if (pg_affected_rows($result)==0) {
        $this->erro_banco = "";
        $this->erro_sql = "cadastro de obras nao Encontrado. Exclus�o n�o Efetuada.\\n";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = pg_affected_rows($result);
        return true;
      }
    }
  }

  // funcao do recordset
  function sql_record($sql) {
    $result = db_query($sql);
    if ($result==false) {
      $this->numrows    = 0;
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      $this->erro_sql   = "Erro ao selecionar os registros.";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    $this->numrows = pg_numrows($result);
    if ($this->numrows==0) {
      $this->erro_banco = "";
      $this->erro_sql   = "Record Vazio na Tabela:licobras102023";
      $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }

  // funcao do sql
  function sql_query ( $si195_sequencial = null,$campos="*",$ordem=null,$dbwhere="") {
    $sql = "select ";
    if ($campos != "*" ) {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from licobras102023 ";
    $sql2 = "";
    if ($dbwhere=="") {
      if ( $si195_sequencial != "" && $si195_sequencial != null) {
        $sql2 = " where licobras102023.si195_sequencial = '$si195_sequencial'";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  // funcao do sql
  function sql_query_file ( $si195_sequencial = null,$campos="*",$ordem=null,$dbwhere="") {
    $sql = "select ";
    if ($campos != "*" ) {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from licobras102023 ";
    $sql2 = "";
    if ($dbwhere=="") {
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
?>

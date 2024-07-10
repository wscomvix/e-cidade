<?php
//MODULO: protocolo
//CLASSE DA ENTIDADE autprotslip
class cl_autprotslip {
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
  public $p108_sequencial = 0;
  public $p108_autorizado = 'f';
  public $p108_slip = 0;
  public $p108_protocolo = 0;
  public $p108_dt_cadastro_dia = null;
  public $p108_dt_cadastro_mes = null;
  public $p108_dt_cadastro_ano = null;
  public $p108_dt_cadastro = null;
  // cria propriedade com as variaveis do arquivo
  public $campos = "
                 p108_sequencial = int4 = p108_sequencial
                 p108_autorizado = bool = Autorizado
                 p108_slip = int4 = C�digo Slip
                 p108_protocolo = int4 = Protocolo
                 p108_dt_cadastro = date = Data de Cadastro
                 ";

  //funcao construtor da classe
  function __construct() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("autprotslip");
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
       $this->p108_sequencial = ($this->p108_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["p108_sequencial"]:$this->p108_sequencial);
       $this->p108_autorizado = ($this->p108_autorizado == "f"?@$GLOBALS["HTTP_POST_VARS"]["p108_autorizado"]:$this->p108_autorizado);
       $this->p108_slip = ($this->p108_slip == ""?@$GLOBALS["HTTP_POST_VARS"]["p108_slip"]:$this->p108_slip);
       $this->p108_protocolo = ($this->p108_protocolo == ""?@$GLOBALS["HTTP_POST_VARS"]["p108_protocolo"]:$this->p108_protocolo);
       if ($this->p108_dt_cadastro == "") {
         $this->p108_dt_cadastro_dia = ($this->p108_dt_cadastro_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p108_dt_cadastro_dia"]:$this->p108_dt_cadastro_dia);
         $this->p108_dt_cadastro_mes = ($this->p108_dt_cadastro_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p108_dt_cadastro_mes"]:$this->p108_dt_cadastro_mes);
         $this->p108_dt_cadastro_ano = ($this->p108_dt_cadastro_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p108_dt_cadastro_ano"]:$this->p108_dt_cadastro_ano);
         if ($this->p108_dt_cadastro_dia != "") {
            $this->p108_dt_cadastro = $this->p108_dt_cadastro_ano."-".$this->p108_dt_cadastro_mes."-".$this->p108_dt_cadastro_dia;
         }
       }
     } else {
       $this->p108_sequencial = ($this->p108_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["p108_sequencial"]:$this->p108_sequencial);
     }
   }

  // funcao para inclusao
  function incluir ($p108_sequencial) {
      $this->atualizacampos();
     if ($this->p108_autorizado == null ) {
       $this->erro_sql = " Campo Autorizado n�o informado.";
       $this->erro_campo = "p108_autorizado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->p108_slip == null ) {
       $this->erro_sql = " Campo C�digo Slip n�o informado.";
       $this->erro_campo = "p108_slip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->p108_protocolo == null ) {
       $this->erro_sql = " Campo Protocolo n�o informado.";
       $this->erro_campo = "p108_protocolo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->p108_dt_cadastro == null ) {
       $this->erro_sql = " Campo Data de Cadastro n�o informado.";
       $this->erro_campo = "p108_dt_cadastro_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($p108_sequencial == "" || $p108_sequencial == null ) {
       $result = db_query("select nextval('autprotslip_p108_sequencial_seq')");
       if ($result==false) {
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: autprotslip_p108_sequencial_seq do campo: p108_sequencial";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->p108_sequencial = pg_result($result,0,0);
     } else {
       $result = db_query("select last_value from autprotslip_p108_sequencial_seq");
       if (($result != false) && (pg_result($result,0,0) < $p108_sequencial)) {
         $this->erro_sql = " Campo p108_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       } else {
         $this->p108_sequencial = $p108_sequencial;
       }
     }
     if (($this->p108_sequencial == null) || ($this->p108_sequencial == "") ) {
       $this->erro_sql = " Campo p108_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into autprotslip(
                                       p108_sequencial
                                      ,p108_autorizado
                                      ,p108_slip
                                      ,p108_protocolo
                                      ,p108_dt_cadastro
                       )
                values (
                                $this->p108_sequencial
                               ,'$this->p108_autorizado'
                               ,$this->p108_slip
                               ,$this->p108_protocolo
                               ,".($this->p108_dt_cadastro == "null" || $this->p108_dt_cadastro == ""?"null":"'".$this->p108_dt_cadastro."'")."
                      )";
     $result = db_query($sql);
     if ($result==false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if ( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ) {
         $this->erro_sql   = "autprotslip ($this->p108_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "autprotslip j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       } else {
         $this->erro_sql   = "autprotslip ($this->p108_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p108_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->p108_sequencial  ));
       if (($resaco!=false)||($this->numrows!=0)) {

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009356,'$this->p108_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010209,1009356,'','".AddSlashes(pg_result($resaco,0,'p108_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010209,1009361,'','".AddSlashes(pg_result($resaco,0,'p108_autorizado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010209,1009357,'','".AddSlashes(pg_result($resaco,0,'p108_slip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010209,1009358,'','".AddSlashes(pg_result($resaco,0,'p108_protocolo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010209,1009359,'','".AddSlashes(pg_result($resaco,0,'p108_dt_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
    }
    return true;
  }

  // funcao para alteracao
  function alterar ($p108_sequencial=null) {
      $this->atualizacampos();
     $sql = " update autprotslip set ";
     $virgula = "";
     if (trim($this->p108_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p108_sequencial"])) {
       $sql  .= $virgula." p108_sequencial = $this->p108_sequencial ";
       $virgula = ",";
       if (trim($this->p108_sequencial) == null ) {
         $this->erro_sql = " Campo p108_sequencial n�o informado.";
         $this->erro_campo = "p108_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->p108_autorizado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p108_autorizado"])) {
       $sql  .= $virgula." p108_autorizado = '$this->p108_autorizado' ";
       $virgula = ",";
       if (trim($this->p108_autorizado) == null ) {
         $this->erro_sql = " Campo Autorizado n�o informado.";
         $this->erro_campo = "p108_autorizado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->p108_slip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p108_slip"])) {
       $sql  .= $virgula." p108_slip = $this->p108_slip ";
       $virgula = ",";
       if (trim($this->p108_slip) == null ) {
         $this->erro_sql = " Campo C�digo Slip n�o informado.";
         $this->erro_campo = "p108_slip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->p108_protocolo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p108_protocolo"])) {
       $sql  .= $virgula." p108_protocolo = $this->p108_protocolo ";
       $virgula = ",";
       if (trim($this->p108_protocolo) == null ) {
         $this->erro_sql = " Campo Protocolo n�o informado.";
         $this->erro_campo = "p108_protocolo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->p108_dt_cadastro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p108_dt_cadastro_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p108_dt_cadastro_dia"] !="") ) {
       $sql  .= $virgula." p108_dt_cadastro = '$this->p108_dt_cadastro' ";
       $virgula = ",";
       if (trim($this->p108_dt_cadastro) == null ) {
         $this->erro_sql = " Campo Data de Cadastro n�o informado.";
         $this->erro_campo = "p108_dt_cadastro_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if (isset($GLOBALS["HTTP_POST_VARS"]["p108_dt_cadastro_dia"])) {
         $sql  .= $virgula." p108_dt_cadastro = null ";
         $virgula = ",";
         if (trim($this->p108_dt_cadastro) == null ) {
           $this->erro_sql = " Campo Data de Cadastro n�o informado.";
           $this->erro_campo = "p108_dt_cadastro_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if ($p108_sequencial!=null) {
       $sql .= " p108_sequencial = $this->p108_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->p108_sequencial));
       if ($this->numrows>0) {

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1009356,'$this->p108_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p108_sequencial"]) || $this->p108_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010209,1009356,'".AddSlashes(pg_result($resaco,$conresaco,'p108_sequencial'))."','$this->p108_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p108_autorizado"]) || $this->p108_autorizado != "")
             $resac = db_query("insert into db_acount values($acount,1010209,1009361,'".AddSlashes(pg_result($resaco,$conresaco,'p108_autorizado'))."','$this->p108_autorizado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p108_slip"]) || $this->p108_slip != "")
             $resac = db_query("insert into db_acount values($acount,1010209,1009357,'".AddSlashes(pg_result($resaco,$conresaco,'p108_slip'))."','$this->p108_slip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p108_protocolo"]) || $this->p108_protocolo != "")
             $resac = db_query("insert into db_acount values($acount,1010209,1009358,'".AddSlashes(pg_result($resaco,$conresaco,'p108_protocolo'))."','$this->p108_protocolo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p108_dt_cadastro"]) || $this->p108_dt_cadastro != "")
             $resac = db_query("insert into db_acount values($acount,1010209,1009359,'".AddSlashes(pg_result($resaco,$conresaco,'p108_dt_cadastro'))."','$this->p108_dt_cadastro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if ($result==false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "autprotslip nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p108_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result)==0) {
         $this->erro_banco = "";
         $this->erro_sql = "autprotslip nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p108_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p108_sequencial;
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }

  // funcao para exclusao
  function excluir ($p108_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($p108_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1009356,'$p108_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010209,1009356,'','".AddSlashes(pg_result($resaco,$iresaco,'p108_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010209,1009361,'','".AddSlashes(pg_result($resaco,$iresaco,'p108_autorizado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010209,1009357,'','".AddSlashes(pg_result($resaco,$iresaco,'p108_slip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010209,1009358,'','".AddSlashes(pg_result($resaco,$iresaco,'p108_protocolo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010209,1009359,'','".AddSlashes(pg_result($resaco,$iresaco,'p108_dt_cadastro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from autprotslip
                    where ";
     $sql2 = "";
     if ($dbwhere==null || $dbwhere =="") {
        if ($p108_sequencial != "") {
          if ($sql2!="") {
            $sql2 .= " and ";
          }
          $sql2 .= " p108_sequencial = $p108_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result==false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "autprotslip nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p108_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result)==0) {
         $this->erro_banco = "";
         $this->erro_sql = "autprotslip nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p108_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p108_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:autprotslip";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    return $result;
  }

  // funcao do sql
  function sql_query ( $p108_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
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
     $sql .= " from autprotslip ";
     $sql .= "      inner join slip  on  slip.k17_codigo = autprotslip.p108_slip";
     $sql .= "      inner join protocolos  on  protocolos.p101_sequencial = autprotslip.p108_protocolo";
     $sql .= "      inner join db_config  on  db_config.codigo = slip.k17_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = protocolos.p101_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = protocolos.p101_coddeptoorigem and  db_depart.coddepto = protocolos.p101_coddeptodestino";
     $sql2 = "";
     if ($dbwhere=="") {
       if ($p108_sequencial!=null ) {
         $sql2 .= " where autprotslip.p108_sequencial = $p108_sequencial ";
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
  function sql_query_file ( $p108_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
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
     $sql .= " from autprotslip ";
     $sql2 = "";
     if ($dbwhere=="") {
       if ($p108_sequencial!=null ) {
         $sql2 .= " where autprotslip.p108_sequencial = $p108_sequencial ";
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
}
?>

<?php
//MODULO: sicom
//CLASSE DA ENTIDADE viap102018
class cl_viap102018 { 
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
  public $si198_sequencial = 0; 
  public $si198_tiporegistro = 0; 
  public $si198_nrocpfagentepublico = null; 
  public $si198_codmatriculapessoa = 0; 
  public $si198_codvinculopessoa = 0; 
  public $si198_mes = 0; 
  public $si198_inst = 0; 
  // cria propriedade com as variaveis do arquivo 
  public $campos = "
                 si198_sequencial = int4 = si198_sequencial 
                 si198_tiporegistro = int4 = si198_tiporegistro 
                 si198_nrocpfagentepublico = varchar(11) = si198_nrocpfagentepublico 
                 si198_codmatriculapessoa = int4 = si198_codmatriculapessoa 
                 si198_codvinculopessoa = int4 = si198_codvinculopessoa 
                 si198_mes = int4 = si198_mes 
                 si198_inst = int4 = si198_inst 
                 ";

  //funcao construtor da classe 
  function __construct() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("viap102018");
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
       $this->si198_sequencial = ($this->si198_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si198_sequencial"]:$this->si198_sequencial);
       $this->si198_tiporegistro = ($this->si198_tiporegistro == ""?@$GLOBALS["HTTP_POST_VARS"]["si198_tiporegistro"]:$this->si198_tiporegistro);
       $this->si198_nrocpfagentepublico = ($this->si198_nrocpfagentepublico == ""?@$GLOBALS["HTTP_POST_VARS"]["si198_nrocpfagentepublico"]:$this->si198_nrocpfagentepublico);
       $this->si198_codmatriculapessoa = ($this->si198_codmatriculapessoa == ""?@$GLOBALS["HTTP_POST_VARS"]["si198_codmatriculapessoa"]:$this->si198_codmatriculapessoa);
       $this->si198_codvinculopessoa = ($this->si198_codvinculopessoa == ""?@$GLOBALS["HTTP_POST_VARS"]["si198_codvinculopessoa"]:$this->si198_codvinculopessoa);
       $this->si198_mes = ($this->si198_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["si198_mes"]:$this->si198_mes);
       $this->si198_inst = ($this->si198_inst == ""?@$GLOBALS["HTTP_POST_VARS"]["si198_inst"]:$this->si198_inst);
     } else {
       $this->si198_sequencial = ($this->si198_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si198_sequencial"]:$this->si198_sequencial);
     }
   }

  // funcao para inclusao
  function incluir ($si198_sequencial) {
      $this->atualizacampos();
     if ($this->si198_tiporegistro == null ) { 
       $this->erro_sql = " Campo si198_tiporegistro n�o informado.";
       $this->erro_campo = "si198_tiporegistro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->si198_nrocpfagentepublico == null ) {
       $this->erro_sql = " Campo si198_nrocpfagentepublico n�o informado. ".$this->si198_codvinculopessoa;
       $this->erro_campo = "si198_nrocpfagentepublico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->si198_codmatriculapessoa == null ) { 
       $this->erro_sql = " Campo si198_codmatriculapessoa n�o informado.";
       $this->erro_campo = "si198_codmatriculapessoa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->si198_codvinculopessoa == null ) { 
       $this->erro_sql = " Campo si198_codvinculopessoa n�o informado.";
       $this->erro_campo = "si198_codvinculopessoa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->si198_mes == null ) { 
       $this->erro_sql = " Campo si198_mes n�o informado.";
       $this->erro_campo = "si198_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->si198_inst == null ) { 
       $this->erro_sql = " Campo si198_inst n�o informado.";
       $this->erro_campo = "si198_inst";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
      if($si198_sequencial == "" || $si198_sequencial == null ){
          $result = db_query("select nextval('viap102018_si198_sequencial_seq')");
          if($result==false){
              $this->erro_banco = str_replace("\n","",@pg_last_error());
              $this->erro_sql   = "Verifique o cadastro da sequencia: viap102018_si198_sequencial_seq do campo: si198_sequencial";
              $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
              $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
              $this->erro_status = "0";
              return false;
          }
          $this->si198_sequencial = pg_result($result,0,0);
      }else{
          $result = db_query("select last_value from viap102018_si198_sequencial_seq");
          if(($result != false) && (pg_result($result,0,0) < $si198_sequencial)){
              $this->erro_sql = " Campo si198_sequencial maior que �ltimo n�mero da sequencia.";
              $this->erro_banco = "Sequencia menor que este n�mero.";
              $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
              $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
              $this->erro_status = "0";
              return false;
          }else{
              $this->si198_sequencial = $si198_sequencial;
          }
      }
     if (($this->si198_sequencial == null) || ($this->si198_sequencial == "") ) { 
       $this->erro_sql = " Campo si198_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into viap102018(
                                       si198_sequencial 
                                      ,si198_tiporegistro 
                                      ,si198_nrocpfagentepublico 
                                      ,si198_codmatriculapessoa 
                                      ,si198_codvinculopessoa 
                                      ,si198_mes 
                                      ,si198_inst 
                       )
                values (
                                $this->si198_sequencial 
                               ,$this->si198_tiporegistro 
                               ,'$this->si198_nrocpfagentepublico' 
                               ,$this->si198_codmatriculapessoa 
                               ,$this->si198_codvinculopessoa 
                               ,$this->si198_mes 
                               ,$this->si198_inst 
                      )";
     $result = db_query($sql);
     if ($result==false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if ( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ) {
         $this->erro_sql   = "viap102018 ($this->si198_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "viap102018 j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       } else {
         $this->erro_sql   = "viap102018 ($this->si198_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->si198_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->si198_sequencial  ));
       if (($resaco!=false)||($this->numrows!=0)) {

         /*$resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009278,'$this->si198_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010198,1009278,'','".AddSlashes(pg_result($resaco,0,'si198_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010198,1009279,'','".AddSlashes(pg_result($resaco,0,'si198_tiporegistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010198,1009280,'','".AddSlashes(pg_result($resaco,0,'si198_nrocpfagentepublico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010198,1009281,'','".AddSlashes(pg_result($resaco,0,'si198_codmatriculapessoa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010198,1009282,'','".AddSlashes(pg_result($resaco,0,'si198_codvinculopessoa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010198,1009283,'','".AddSlashes(pg_result($resaco,0,'si198_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010198,1009284,'','".AddSlashes(pg_result($resaco,0,'si198_inst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");*/
       }
    }
    return true;
  }

  // funcao para alteracao
  function alterar ($si198_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update viap102018 set ";
     $virgula = "";
     if (trim($this->si198_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si198_sequencial"])) { 
       $sql  .= $virgula." si198_sequencial = $this->si198_sequencial ";
       $virgula = ",";
       if (trim($this->si198_sequencial) == null ) { 
         $this->erro_sql = " Campo si198_sequencial n�o informado.";
         $this->erro_campo = "si198_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->si198_tiporegistro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si198_tiporegistro"])) { 
       $sql  .= $virgula." si198_tiporegistro = $this->si198_tiporegistro ";
       $virgula = ",";
       if (trim($this->si198_tiporegistro) == null ) { 
         $this->erro_sql = " Campo si198_tiporegistro n�o informado.";
         $this->erro_campo = "si198_tiporegistro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->si198_nrocpfagentepublico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si198_nrocpfagentepublico"])) { 
       $sql  .= $virgula." si198_nrocpfagentepublico = '$this->si198_nrocpfagentepublico' ";
       $virgula = ",";
       if (trim($this->si198_nrocpfagentepublico) == null ) { 
         $this->erro_sql = " Campo si198_nrocpfagentepublico n�o informado.";
         $this->erro_campo = "si198_nrocpfagentepublico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->si198_codmatriculapessoa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si198_codmatriculapessoa"])) { 
       $sql  .= $virgula." si198_codmatriculapessoa = $this->si198_codmatriculapessoa ";
       $virgula = ",";
       if (trim($this->si198_codmatriculapessoa) == null ) { 
         $this->erro_sql = " Campo si198_codmatriculapessoa n�o informado.";
         $this->erro_campo = "si198_codmatriculapessoa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->si198_codvinculopessoa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si198_codvinculopessoa"])) { 
       $sql  .= $virgula." si198_codvinculopessoa = $this->si198_codvinculopessoa ";
       $virgula = ",";
       if (trim($this->si198_codvinculopessoa) == null ) { 
         $this->erro_sql = " Campo si198_codvinculopessoa n�o informado.";
         $this->erro_campo = "si198_codvinculopessoa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->si198_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si198_mes"])) { 
       $sql  .= $virgula." si198_mes = $this->si198_mes ";
       $virgula = ",";
       if (trim($this->si198_mes) == null ) { 
         $this->erro_sql = " Campo si198_mes n�o informado.";
         $this->erro_campo = "si198_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->si198_inst)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si198_inst"])) { 
       $sql  .= $virgula." si198_inst = $this->si198_inst ";
       $virgula = ",";
       if (trim($this->si198_inst) == null ) { 
         $this->erro_sql = " Campo si198_inst n�o informado.";
         $this->erro_campo = "si198_inst";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if ($si198_sequencial!=null) {
       $sql .= " si198_sequencial = $this->si198_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->si198_sequencial));
       if ($this->numrows>0) {

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++) {

           /*$resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1009278,'$this->si198_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si198_sequencial"]) || $this->si198_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010198,1009278,'".AddSlashes(pg_result($resaco,$conresaco,'si198_sequencial'))."','$this->si198_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si198_tiporegistro"]) || $this->si198_tiporegistro != "")
             $resac = db_query("insert into db_acount values($acount,1010198,1009279,'".AddSlashes(pg_result($resaco,$conresaco,'si198_tiporegistro'))."','$this->si198_tiporegistro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si198_nrocpfagentepublico"]) || $this->si198_nrocpfagentepublico != "")
             $resac = db_query("insert into db_acount values($acount,1010198,1009280,'".AddSlashes(pg_result($resaco,$conresaco,'si198_nrocpfagentepublico'))."','$this->si198_nrocpfagentepublico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si198_codmatriculapessoa"]) || $this->si198_codmatriculapessoa != "")
             $resac = db_query("insert into db_acount values($acount,1010198,1009281,'".AddSlashes(pg_result($resaco,$conresaco,'si198_codmatriculapessoa'))."','$this->si198_codmatriculapessoa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si198_codvinculopessoa"]) || $this->si198_codvinculopessoa != "")
             $resac = db_query("insert into db_acount values($acount,1010198,1009282,'".AddSlashes(pg_result($resaco,$conresaco,'si198_codvinculopessoa'))."','$this->si198_codvinculopessoa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si198_mes"]) || $this->si198_mes != "")
             $resac = db_query("insert into db_acount values($acount,1010198,1009283,'".AddSlashes(pg_result($resaco,$conresaco,'si198_mes'))."','$this->si198_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si198_inst"]) || $this->si198_inst != "")
             $resac = db_query("insert into db_acount values($acount,1010198,1009284,'".AddSlashes(pg_result($resaco,$conresaco,'si198_inst'))."','$this->si198_inst',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");*/
         }
       }
     }
     $result = db_query($sql);
     if ($result==false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "viap102018 nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->si198_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result)==0) {
         $this->erro_banco = "";
         $this->erro_sql = "viap102018 nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->si198_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->si198_sequencial;
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }

  // funcao para exclusao 
  function excluir ($si198_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($si198_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           /*$resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1009278,'$si198_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010198,1009278,'','".AddSlashes(pg_result($resaco,$iresaco,'si198_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010198,1009279,'','".AddSlashes(pg_result($resaco,$iresaco,'si198_tiporegistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010198,1009280,'','".AddSlashes(pg_result($resaco,$iresaco,'si198_nrocpfagentepublico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010198,1009281,'','".AddSlashes(pg_result($resaco,$iresaco,'si198_codmatriculapessoa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010198,1009282,'','".AddSlashes(pg_result($resaco,$iresaco,'si198_codvinculopessoa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010198,1009283,'','".AddSlashes(pg_result($resaco,$iresaco,'si198_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010198,1009284,'','".AddSlashes(pg_result($resaco,$iresaco,'si198_inst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");*/
         }
       }
     }
     $sql = " delete from viap102018
                    where ";
     $sql2 = "";
     if ($dbwhere==null || $dbwhere =="") {
        if ($si198_sequencial != "") {
          if ($sql2!="") {
            $sql2 .= " and ";
          }
          $sql2 .= " si198_sequencial = $si198_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result==false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "viap102018 nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$si198_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result)==0) {
         $this->erro_banco = "";
         $this->erro_sql = "viap102018 nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$si198_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$si198_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:viap102018";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    return $result;
  }

  // funcao do sql 
  function sql_query ( $si198_sequencial=null,$campos="*",$ordem=null,$dbwhere="") { 
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
     $sql .= " from viap102018 ";
     $sql2 = "";
     if ($dbwhere=="") {
       if ($si198_sequencial!=null ) {
         $sql2 .= " where viap102018.si198_sequencial = $si198_sequencial "; 
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
  function sql_query_file ( $si198_sequencial=null,$campos="*",$ordem=null,$dbwhere="") { 
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
     $sql .= " from viap102018 ";
     $sql2 = "";
     if ($dbwhere=="") {
       if ($si198_sequencial!=null ) {
         $sql2 .= " where viap102018.si198_sequencial = $si198_sequencial "; 
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

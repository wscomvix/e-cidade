<?php
//MODULO: sicom
//CLASSE DA ENTIDADE afast202020
class cl_afast202020 { 
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
  public $si200_sequencial = 0; 
  public $si200_tiporegistro = 0; 
  public $si200_codvinculopessoa = 0;
  public $si200_codafastamento = 0;
  public $si200_dtterminoafastamento_dia = null;
  public $si200_dtterminoafastamento_mes = null; 
  public $si200_dtterminoafastamento_ano = null; 
  public $si200_dtterminoafastamento = null; 
  public $si200_mes = 0; 
  public $si200_inst = 0; 
  // cria propriedade com as variaveis do arquivo 
  public $campos = "
                 si200_sequencial = int4 = si200_sequencial 
                 si200_tiporegistro = int4 = si200_tiporegistro 
                 si200_codvinculopessoa = int4 = si200_codvinculopessoa
                 si200_codafastamento = int4 = si200_codafastamento 
                 si200_dtterminoafastamento = date = si200_dtterminoafastamento 
                 si200_mes = int4 = si200_mes 
                 si200_inst = int4 = si200_inst 
                 ";

  //funcao construtor da classe 
  function __construct() { 
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("afast202020"); 
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
       $this->si200_sequencial = ($this->si200_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si200_sequencial"]:$this->si200_sequencial);
       $this->si200_tiporegistro = ($this->si200_tiporegistro == ""?@$GLOBALS["HTTP_POST_VARS"]["si200_tiporegistro"]:$this->si200_tiporegistro);
       $this->si200_codvinculopessoa = ($this->si200_codvinculopessoa == ""?@$GLOBALS["HTTP_POST_VARS"]["si200_codvinculopessoa"]:$this->si200_codvinculopessoa);
       $this->si200_codafastamento = ($this->si200_codafastamento == ""?@$GLOBALS["HTTP_POST_VARS"]["si200_codafastamento"]:$this->si200_codafastamento);
       if ($this->si200_dtterminoafastamento == "") {
         $this->si200_dtterminoafastamento_dia = ($this->si200_dtterminoafastamento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["si200_dtterminoafastamento_dia"]:$this->si200_dtterminoafastamento_dia);
         $this->si200_dtterminoafastamento_mes = ($this->si200_dtterminoafastamento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["si200_dtterminoafastamento_mes"]:$this->si200_dtterminoafastamento_mes);
         $this->si200_dtterminoafastamento_ano = ($this->si200_dtterminoafastamento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["si200_dtterminoafastamento_ano"]:$this->si200_dtterminoafastamento_ano);
         if ($this->si200_dtterminoafastamento_dia != "") {
            $this->si200_dtterminoafastamento = $this->si200_dtterminoafastamento_ano."-".$this->si200_dtterminoafastamento_mes."-".$this->si200_dtterminoafastamento_dia;
         }
       }
       $this->si200_mes = ($this->si200_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["si200_mes"]:$this->si200_mes);
       $this->si200_inst = ($this->si200_inst == ""?@$GLOBALS["HTTP_POST_VARS"]["si200_inst"]:$this->si200_inst);
     } else {
       $this->si200_sequencial = ($this->si200_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si200_sequencial"]:$this->si200_sequencial);
     }
   }

  // funcao para inclusao
  function incluir ($si200_sequencial) { 
      $this->atualizacampos();
     if ($this->si200_tiporegistro == null ) { 
       $this->erro_sql = " Campo si200_tiporegistro n�o informado.";
       $this->erro_campo = "si200_tiporegistro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->si200_codvinculopessoa == null ) { 
       $this->erro_sql = " Campo si200_codvinculopessoa n�o informado.";
       $this->erro_campo = "si200_codvinculopessoa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->si200_codafastamento == null ) {
         $this->erro_sql = " Campo si200_codafastamento n�o informado.";
         $this->erro_campo = "si200_codafastamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
     }
     if ($this->si200_dtterminoafastamento == null ) { 
       $this->erro_sql = " Campo si200_dtterminoafastamento n�o informado.";
       $this->erro_campo = "si200_dtterminoafastamento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->si200_mes == null ) { 
       $this->erro_sql = " Campo si200_mes n�o informado.";
       $this->erro_campo = "si200_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->si200_inst == null ) { 
       $this->erro_sql = " Campo si200_inst n�o informado.";
       $this->erro_campo = "si200_inst";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
      if($si200_sequencial == "" || $si200_sequencial == null ){
          $result = db_query("select nextval('afast202020_si200_sequencial_seq')");
          if($result==false){
              $this->erro_banco = str_replace("\n","",@pg_last_error());
              $this->erro_sql   = "Verifique o cadastro da sequencia: afast202020_si200_sequencial_seq do campo: si200_sequencial";
              $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
              $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
              $this->erro_status = "0";
              return false;
          }
          $this->si200_sequencial = pg_result($result,0,0);
      }else{
          $result = db_query("select last_value from afast202020_si200_sequencial_seq");
          if(($result != false) && (pg_result($result,0,0) < $si200_sequencial)){
              $this->erro_sql = " Campo si200_sequencial maior que �ltimo n�mero da sequencia.";
              $this->erro_banco = "Sequencia menor que este n�mero.";
              $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
              $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
              $this->erro_status = "0";
              return false;
          }else{
              $this->si200_sequencial = $si200_sequencial;
          }
      }
      if (($this->si200_sequencial == null) || ($this->si200_sequencial == "") ) {
          $this->erro_sql = " Campo si200_sequencial nao declarado.";
          $this->erro_banco = "Chave Primaria zerada.";
          $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
      }
     $sql = "insert into afast202020(
                                       si200_sequencial 
                                      ,si200_tiporegistro 
                                      ,si200_codvinculopessoa 
                                      ,si200_codafastamento
                                      ,si200_dtterminoafastamento 
                                      ,si200_mes 
                                      ,si200_inst 
                       )
                values (
                                $this->si200_sequencial 
                               ,$this->si200_tiporegistro 
                               ,$this->si200_codvinculopessoa
                               ,$this->si200_codafastamento 
                               ,".($this->si200_dtterminoafastamento == "null" || $this->si200_dtterminoafastamento == ""?"null":"'".$this->si200_dtterminoafastamento."'")." 
                               ,$this->si200_mes 
                               ,$this->si200_inst 
                      )";
     $result = db_query($sql); 
     if ($result==false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if ( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ) {
         $this->erro_sql   = "afast202020 ($this->si200_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "afast202020 j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       } else {
         $this->erro_sql   = "afast202020 ($this->si200_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->si200_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       /*$resaco = $this->sql_record($this->sql_query_file($this->si200_sequencial  ));
       if (($resaco!=false)||($this->numrows!=0)) {

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009294,'$this->si200_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010200,1009294,'','".AddSlashes(pg_result($resaco,0,'si200_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010200,1009295,'','".AddSlashes(pg_result($resaco,0,'si200_tiporegistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010200,1009296,'','".AddSlashes(pg_result($resaco,0,'si200_codvinculopessoa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010200,1009297,'','".AddSlashes(pg_result($resaco,0,'si200_dtterminoafastamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010200,1009298,'','".AddSlashes(pg_result($resaco,0,'si200_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010200,1009299,'','".AddSlashes(pg_result($resaco,0,'si200_inst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }*/
    }
    return true;
  }

  // funcao para alteracao
  function alterar ($si200_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update afast202020 set ";
     $virgula = "";
     if (trim($this->si200_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si200_sequencial"])) { 
       $sql  .= $virgula." si200_sequencial = $this->si200_sequencial ";
       $virgula = ",";
       if (trim($this->si200_sequencial) == null ) { 
         $this->erro_sql = " Campo si200_sequencial n�o informado.";
         $this->erro_campo = "si200_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->si200_tiporegistro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si200_tiporegistro"])) { 
       $sql  .= $virgula." si200_tiporegistro = $this->si200_tiporegistro ";
       $virgula = ",";
       if (trim($this->si200_tiporegistro) == null ) { 
         $this->erro_sql = " Campo si200_tiporegistro n�o informado.";
         $this->erro_campo = "si200_tiporegistro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->si200_codvinculopessoa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si200_codvinculopessoa"])) { 
       $sql  .= $virgula." si200_codvinculopessoa = $this->si200_codvinculopessoa ";
       $virgula = ",";
       if (trim($this->si200_codvinculopessoa) == null ) { 
         $this->erro_sql = " Campo si200_codvinculopessoa n�o informado.";
         $this->erro_campo = "si200_codvinculopessoa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->si200_codafastamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si200_codafastamento"])) {
         $sql  .= $virgula." si200_codafastamento = $this->si200_codafastamento ";
         $virgula = ",";
         if (trim($this->si200_codvinculopessoa) == null ) {
             $this->erro_sql = " Campo si200_codafastamento n�o informado.";
             $this->erro_campo = "si200_codafastamento";
             $this->erro_banco = "";
             $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
             $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
             $this->erro_status = "0";
             return false;
         }
     }
     if (trim($this->si200_dtterminoafastamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si200_dtterminoafastamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["si200_dtterminoafastamento_dia"] !="") ) { 
       $sql  .= $virgula." si200_dtterminoafastamento = '$this->si200_dtterminoafastamento' ";
       $virgula = ",";
       if (trim($this->si200_dtterminoafastamento) == null ) { 
         $this->erro_sql = " Campo si200_dtterminoafastamento n�o informado.";
         $this->erro_campo = "si200_dtterminoafastamento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if (isset($GLOBALS["HTTP_POST_VARS"]["si200_dtterminoafastamento_dia"])) { 
         $sql  .= $virgula." si200_dtterminoafastamento = null ";
         $virgula = ",";
         if (trim($this->si200_dtterminoafastamento) == null ) { 
           $this->erro_sql = " Campo si200_dtterminoafastamento n�o informado.";
           $this->erro_campo = "si200_dtterminoafastamento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if (trim($this->si200_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si200_mes"])) { 
       $sql  .= $virgula." si200_mes = $this->si200_mes ";
       $virgula = ",";
       if (trim($this->si200_mes) == null ) { 
         $this->erro_sql = " Campo si200_mes n�o informado.";
         $this->erro_campo = "si200_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->si200_inst)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si200_inst"])) { 
       $sql  .= $virgula." si200_inst = $this->si200_inst ";
       $virgula = ",";
       if (trim($this->si200_inst) == null ) { 
         $this->erro_sql = " Campo si200_inst n�o informado.";
         $this->erro_campo = "si200_inst";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if ($si200_sequencial!=null) {
       $sql .= " si200_sequencial = $this->si200_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->si200_sequencial));
       if ($this->numrows>0) {

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++) {

           /*$resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1009294,'$this->si200_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si200_sequencial"]) || $this->si200_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010200,1009294,'".AddSlashes(pg_result($resaco,$conresaco,'si200_sequencial'))."','$this->si200_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si200_tiporegistro"]) || $this->si200_tiporegistro != "")
             $resac = db_query("insert into db_acount values($acount,1010200,1009295,'".AddSlashes(pg_result($resaco,$conresaco,'si200_tiporegistro'))."','$this->si200_tiporegistro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si200_codvinculopessoa"]) || $this->si200_codvinculopessoa != "")
             $resac = db_query("insert into db_acount values($acount,1010200,1009296,'".AddSlashes(pg_result($resaco,$conresaco,'si200_codvinculopessoa'))."','$this->si200_codvinculopessoa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si200_dtterminoafastamento"]) || $this->si200_dtterminoafastamento != "")
             $resac = db_query("insert into db_acount values($acount,1010200,1009297,'".AddSlashes(pg_result($resaco,$conresaco,'si200_dtterminoafastamento'))."','$this->si200_dtterminoafastamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si200_mes"]) || $this->si200_mes != "")
             $resac = db_query("insert into db_acount values($acount,1010200,1009298,'".AddSlashes(pg_result($resaco,$conresaco,'si200_mes'))."','$this->si200_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si200_inst"]) || $this->si200_inst != "")
             $resac = db_query("insert into db_acount values($acount,1010200,1009299,'".AddSlashes(pg_result($resaco,$conresaco,'si200_inst'))."','$this->si200_inst',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");*/
         }
       }
     }
     $result = db_query($sql);
     if ($result==false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "afast202020 nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->si200_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result)==0) {
         $this->erro_banco = "";
         $this->erro_sql = "afast202020 nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->si200_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->si200_sequencial;
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }

  // funcao para exclusao 
  function excluir ($si200_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($si200_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           /*$resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1009294,'$si200_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010200,1009294,'','".AddSlashes(pg_result($resaco,$iresaco,'si200_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010200,1009295,'','".AddSlashes(pg_result($resaco,$iresaco,'si200_tiporegistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010200,1009296,'','".AddSlashes(pg_result($resaco,$iresaco,'si200_codvinculopessoa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010200,1009297,'','".AddSlashes(pg_result($resaco,$iresaco,'si200_dtterminoafastamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010200,1009298,'','".AddSlashes(pg_result($resaco,$iresaco,'si200_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010200,1009299,'','".AddSlashes(pg_result($resaco,$iresaco,'si200_inst'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");*/
         }
       }
     }
     $sql = " delete from afast202020
                    where ";
     $sql2 = "";
     if ($dbwhere==null || $dbwhere =="") {
        if ($si200_sequencial != "") {
          if ($sql2!="") {
            $sql2 .= " and ";
          }
          $sql2 .= " si200_sequencial = $si200_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result==false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "afast202020 nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$si200_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result)==0) {
         $this->erro_banco = "";
         $this->erro_sql = "afast202020 nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$si200_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$si200_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:afast202020";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    return $result;
  }

  // funcao do sql 
  function sql_query ( $si200_sequencial=null,$campos="*",$ordem=null,$dbwhere="") { 
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
     $sql .= " from afast202020 ";
     $sql2 = "";
     if ($dbwhere=="") {
       if ($si200_sequencial!=null ) {
         $sql2 .= " where afast202020.si200_sequencial = $si200_sequencial "; 
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
  function sql_query_file ( $si200_sequencial=null,$campos="*",$ordem=null,$dbwhere="") { 
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
     $sql .= " from afast202020 ";
     $sql2 = "";
     if ($dbwhere=="") {
       if ($si200_sequencial!=null ) {
         $sql2 .= " where afast202020.si200_sequencial = $si200_sequencial "; 
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

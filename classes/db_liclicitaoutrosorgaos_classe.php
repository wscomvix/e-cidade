<?php
//MODULO: licitacao
//CLASSE DA ENTIDADE liclicitaoutrosorgaos
class cl_liclicitaoutrosorgaos { 
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
  public $lic211_sequencial = 0; 
  public $lic211_orgao = 0; 
  public $lic211_processo = 0; 
  public $lic211_numero = 0; 
  public $lic211_anousu = 0; 
  public $lic211_tipo = 0;
  public $lic211_codorgaoresplicit = null;
  public $lic211_codunisubres = null;

  // cria propriedade com as variaveis do arquivo 
  public $campos = "
                 lic211_sequencial = int8 = Sequencial 
                 lic211_orgao = int8 = Orgao Responsavel 
                 lic211_processo = int4 = Numero do Processo 
                 lic211_numero = int4 = Numedo da Modalidade 
                 lic211_anousu = int4 = Ano da Licitacao 
                 lic211_tipo = int4 = Tipo de Licitacao
                 lic211_codorgaoresplicit = int8 = codorgaoresplicit
                 lic211_codunisubres = int8 = codunisubres 
                 ";

  //funcao construtor da classe 
  function __construct() {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("liclicitaoutrosorgaos"); 
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
       $this->lic211_sequencial = ($this->lic211_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["lic211_sequencial"]:$this->lic211_sequencial);
       $this->lic211_orgao = ($this->lic211_orgao == ""?@$GLOBALS["HTTP_POST_VARS"]["lic211_orgao"]:$this->lic211_orgao);
       $this->lic211_processo = ($this->lic211_processo == ""?@$GLOBALS["HTTP_POST_VARS"]["lic211_processo"]:$this->lic211_processo);
       $this->lic211_numero = ($this->lic211_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["lic211_numero"]:$this->lic211_numero);
       $this->lic211_anousu = ($this->lic211_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["lic211_anousu"]:$this->lic211_anousu);
       $this->lic211_tipo = ($this->lic211_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["lic211_tipo"]:$this->lic211_tipo);
       $this->lic211_codorgaoresplicit = ($this->lic211_codorgaoresplicit == ""?@$GLOBALS["HTTP_POST_VARS"]["lic211_codorgaoresplicit"]:$this->lic211_codorgaoresplicit);
       $this->lic211_codunisubres = ($this->lic211_codunisubres == ""?@$GLOBALS["HTTP_POST_VARS"]["lic211_codunisubres"]:$this->lic211_codunisubres);

    } else {
       $this->lic211_sequencial = ($this->lic211_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["lic211_sequencial"]:$this->lic211_sequencial);
     }
   }

  // funcao para inclusao
  function incluir ($lic211_sequencial) { 
      $this->atualizacampos();
     if ($this->lic211_orgao == null ) { 
       $this->erro_sql = " Campo Orgao Responsavel n�o informado.";
       $this->erro_campo = "lic211_orgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->lic211_processo == null ) { 
       $this->erro_sql = " Campo Numero do Processo n�o informado.";
       $this->erro_campo = "lic211_processo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->lic211_numero == null ) { 
       $this->erro_sql = " Campo Numedo da Modalidade n�o informado.";
       $this->erro_campo = "lic211_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->lic211_anousu == null ) { 
       $this->erro_sql = " Campo Ano da Licitacao n�o informado.";
       $this->erro_campo = "lic211_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->lic211_tipo == null ) {
       $this->erro_sql = " Campo Tipo de Licitacao n�o informado.";
       $this->erro_campo = "lic211_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($lic211_sequencial == "" || $lic211_sequencial == null ) {
       $result = db_query("select nextval('liclicitaoutrosorgaos_lic211_sequencial_seq')"); 
       if ($result==false) {
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: liclicitaoutrosorgaos_lic211_sequencial_seq do campo: lic211_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->lic211_sequencial = pg_result($result,0,0); 
     } else {
       $result = db_query("select last_value from liclicitaoutrosorgaos_lic211_sequencial_seq");
       if (($result != false) && (pg_result($result,0,0) < $lic211_sequencial)) {
         $this->erro_sql = " Campo lic211_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       } else {
         $this->lic211_sequencial = $lic211_sequencial; 
       }
     }
     if (($this->lic211_sequencial == null) || ($this->lic211_sequencial == "") ) { 
       $this->erro_sql = " Campo lic211_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if (($this->lic211_tipo == 5 || $this->lic211_tipo == 6) && ($this->lic211_codorgaoresplicit == null || $this->lic211_codorgaoresplicit == "")){
       $this->erro_sql = " Campo CodOrgaoRespLicit deve ser informado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if (($this->lic211_tipo == 5 || $this->lic211_tipo == 6) && ($this->lic211_codunisubres == null || $this->lic211_codunisubres == "")){
       $this->erro_sql = " Campo CodUnidadeSubResp deve ser informado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->lic211_tipo == 7 && ($this->lic211_codunisubres == null || $this->lic211_codunisubres == "")){
       $this->erro_sql = " Campo CodUnidadeSubResp deve ser informado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

      $sql = "insert into liclicitaoutrosorgaos(
                                       lic211_sequencial 
                                      ,lic211_orgao 
                                      ,lic211_processo 
                                      ,lic211_numero 
                                      ,lic211_anousu 
                                      ,lic211_tipo
                                      ,lic211_codorgaoresplicit
                                      ,lic211_codunisubres
                       )
                values (
                                $this->lic211_sequencial 
                               ,$this->lic211_orgao 
                               ,$this->lic211_processo 
                               ,$this->lic211_numero 
                               ,$this->lic211_anousu 
                               ,$this->lic211_tipo 
                               ,".($this->lic211_codorgaoresplicit == 'NULL' || $this->lic211_codorgaoresplicit == '' ? 'NULL' : $this->lic211_codorgaoresplicit)."
                               ,".($this->lic211_codunisubres == 'NULL' || $this->lic211_codunisubres == '' ? 'NULL' : $this->lic211_codunisubres)."
                      )";

     $result = db_query($sql);
     if ($result==false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if ( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ) {
         $this->erro_sql   = "licitacoes de outros orgaos ($this->lic211_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "licitacoes de outros orgaos j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       } else {
         $this->erro_sql   = "licitacoes de outros orgaos ($this->lic211_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->lic211_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
//     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
//     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
//       && ($lSessaoDesativarAccount === false))) {

//       $resaco = $this->sql_record($this->sql_query_file($this->lic211_sequencial  ));
//       if (($resaco!=false)||($this->numrows!=0)) {
//
//         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
//         $acount = pg_result($resac,0,0);
//         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
//         $resac = db_query("insert into db_acountkey values($acount,1009344,'$this->lic211_sequencial','I')");
//         $resac = db_query("insert into db_acount values($acount,1010207,1009344,'','".AddSlashes(pg_result($resaco,0,'lic211_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         $resac = db_query("insert into db_acount values($acount,1010207,1009345,'','".AddSlashes(pg_result($resaco,0,'lic211_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         $resac = db_query("insert into db_acount values($acount,1010207,1009346,'','".AddSlashes(pg_result($resaco,0,'lic211_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         $resac = db_query("insert into db_acount values($acount,1010207,1009347,'','".AddSlashes(pg_result($resaco,0,'lic211_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         $resac = db_query("insert into db_acount values($acount,1010207,1009348,'','".AddSlashes(pg_result($resaco,0,'lic211_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         $resac = db_query("insert into db_acount values($acount,1010207,1009349,'','".AddSlashes(pg_result($resaco,0,'lic211_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//       }
//    }
    return true;
  }

  // funcao para alteracao
  function alterar ($lic211_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update liclicitaoutrosorgaos set ";
     $virgula = "";
     if (trim($this->lic211_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["lic211_sequencial"])) { 
       $sql  .= $virgula." lic211_sequencial = $this->lic211_sequencial ";
       $virgula = ",";
       if (trim($this->lic211_sequencial) == null ) { 
         $this->erro_sql = " Campo Sequencial n�o informado.";
         $this->erro_campo = "lic211_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->lic211_orgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["lic211_orgao"])) { 
       $sql  .= $virgula." lic211_orgao = $this->lic211_orgao ";
       $virgula = ",";
       if (trim($this->lic211_orgao) == null ) { 
         $this->erro_sql = " Campo Orgao Responsavel n�o informado.";
         $this->erro_campo = "lic211_orgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->lic211_processo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["lic211_processo"])) { 
       $sql  .= $virgula." lic211_processo = $this->lic211_processo ";
       $virgula = ",";
       if (trim($this->lic211_processo) == null ) { 
         $this->erro_sql = " Campo Numero do Processo n�o informado.";
         $this->erro_campo = "lic211_processo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->lic211_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["lic211_numero"])) { 
       $sql  .= $virgula." lic211_numero = $this->lic211_numero ";
       $virgula = ",";
       if (trim($this->lic211_numero) == null ) { 
         $this->erro_sql = " Campo Numedo da Modalidade n�o informado.";
         $this->erro_campo = "lic211_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->lic211_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["lic211_anousu"])) { 
       $sql  .= $virgula." lic211_anousu = $this->lic211_anousu ";
       $virgula = ",";
       if (trim($this->lic211_anousu) == null ) { 
         $this->erro_sql = " Campo Ano da Licitacao n�o informado.";
         $this->erro_campo = "lic211_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->lic211_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["lic211_tipo"])) {
       $sql  .= $virgula." lic211_tipo = $this->lic211_tipo ";
       $virgula = ",";
       if (trim($this->lic211_tipo) == null ) {
         $this->erro_sql = " Campo Tipo de Licitacao n�o informado.";
         $this->erro_campo = "lic211_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->lic211_codorgaoresplicit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["lic211_codorgaoresplicit"]) && ($this->lic211_tipo != 7 && $this->lic211_tipo != 8 && $this->lic211_tipo != 9)) {
       $sql  .= $virgula." lic211_codorgaoresplicit = $this->lic211_codorgaoresplicit ";
       $virgula = ",";
       if (trim($this->lic211_codorgaoresplicit) == null ) {
         $this->erro_sql = " Campo CodOrgaoRespLicit n�o informado.";
         $this->erro_campo = "lic211_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->lic211_codunisubres)!="" || isset($GLOBALS["HTTP_POST_VARS"]["lic211_codunisubres"]) && ( $this->lic211_tipo != 8 && $this->lic211_tipo != 9)) {
       $sql  .= $virgula." lic211_codunisubres = $this->lic211_codunisubres ";
       $virgula = ",";
       if (trim($this->lic211_codunisubres) == null ) {
         $this->erro_sql = " Campo CodUnidadeSubResp n�o informado.";
         $this->erro_campo = "lic211_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
      if ($this->lic211_tipo == 8 || $this->lic211_tipo == 9) {
          $sql  .= $virgula." lic211_codorgaoresplicit = null ";
          $sql  .= $virgula." lic211_codunisubres = null ";
          $virgula = ",";
      }
      if ( $this->lic211_tipo == 7) {
          $sql  .= $virgula." lic211_codorgaoresplicit = null ";
          $virgula = ",";
      }

     $sql .= " where ";
     if ($lic211_sequencial!=null) {
       $sql .= " lic211_sequencial = $this->lic211_sequencial";
     }
//     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
//     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
//       && ($lSessaoDesativarAccount === false))) {
//
//       $resaco = $this->sql_record($this->sql_query_file($this->lic211_sequencial));
//       if ($this->numrows>0) {
//
//         for($conresaco=0;$conresaco<$this->numrows;$conresaco++) {
//
//           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
//           $acount = pg_result($resac,0,0);
//           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
//           $resac = db_query("insert into db_acountkey values($acount,1009344,'$this->lic211_sequencial','A')");
//           if (isset($GLOBALS["HTTP_POST_VARS"]["lic211_sequencial"]) || $this->lic211_sequencial != "")
//             $resac = db_query("insert into db_acount values($acount,1010207,1009344,'".AddSlashes(pg_result($resaco,$conresaco,'lic211_sequencial'))."','$this->lic211_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//           if (isset($GLOBALS["HTTP_POST_VARS"]["lic211_orgao"]) || $this->lic211_orgao != "")
//             $resac = db_query("insert into db_acount values($acount,1010207,1009345,'".AddSlashes(pg_result($resaco,$conresaco,'lic211_orgao'))."','$this->lic211_orgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//           if (isset($GLOBALS["HTTP_POST_VARS"]["lic211_processo"]) || $this->lic211_processo != "")
//             $resac = db_query("insert into db_acount values($acount,1010207,1009346,'".AddSlashes(pg_result($resaco,$conresaco,'lic211_processo'))."','$this->lic211_processo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//           if (isset($GLOBALS["HTTP_POST_VARS"]["lic211_numero"]) || $this->lic211_numero != "")
//             $resac = db_query("insert into db_acount values($acount,1010207,1009347,'".AddSlashes(pg_result($resaco,$conresaco,'lic211_numero'))."','$this->lic211_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//           if (isset($GLOBALS["HTTP_POST_VARS"]["lic211_anousu"]) || $this->lic211_anousu != "")
//             $resac = db_query("insert into db_acount values($acount,1010207,1009348,'".AddSlashes(pg_result($resaco,$conresaco,'lic211_anousu'))."','$this->lic211_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//           if (isset($GLOBALS["HTTP_POST_VARS"]["lic211_tipo"]) || $this->lic211_tipo != "")
//             $resac = db_query("insert into db_acount values($acount,1010207,1009349,'".AddSlashes(pg_result($resaco,$conresaco,'lic211_tipo'))."','$this->lic211_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         }
//       }
//     }
     $result = db_query($sql);
     if ($result==false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "licitacoes de outros orgaos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->lic211_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result)==0) {
         $this->erro_banco = "";
         $this->erro_sql = "licitacoes de outros orgaos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->lic211_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->lic211_sequencial;
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }

  // funcao para exclusao 
  function excluir ($lic211_sequencial=null,$dbwhere=null) { 

//     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
//     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
//       && ($lSessaoDesativarAccount === false))) {
//
//       if ($dbwhere==null || $dbwhere=="") {
//
//         $resaco = $this->sql_record($this->sql_query_file($lic211_sequencial));
//       } else {
//         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
//       }
//       if (($resaco != false) || ($this->numrows!=0)) {
//
//         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
//
//           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
//           $acount = pg_result($resac,0,0);
//           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
//           $resac  = db_query("insert into db_acountkey values($acount,1009344,'$lic211_sequencial','E')");
//           $resac  = db_query("insert into db_acount values($acount,1010207,1009344,'','".AddSlashes(pg_result($resaco,$iresaco,'lic211_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//           $resac  = db_query("insert into db_acount values($acount,1010207,1009345,'','".AddSlashes(pg_result($resaco,$iresaco,'lic211_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//           $resac  = db_query("insert into db_acount values($acount,1010207,1009346,'','".AddSlashes(pg_result($resaco,$iresaco,'lic211_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//           $resac  = db_query("insert into db_acount values($acount,1010207,1009347,'','".AddSlashes(pg_result($resaco,$iresaco,'lic211_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//           $resac  = db_query("insert into db_acount values($acount,1010207,1009348,'','".AddSlashes(pg_result($resaco,$iresaco,'lic211_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//           $resac  = db_query("insert into db_acount values($acount,1010207,1009349,'','".AddSlashes(pg_result($resaco,$iresaco,'lic211_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
//         }
//       }
//     }
     $sql = " delete from liclicitaoutrosorgaos
                    where ";
     $sql2 = "";
     if ($dbwhere==null || $dbwhere =="") {
        if ($lic211_sequencial != "") {
          if ($sql2!="") {
            $sql2 .= " and ";
          }
          $sql2 .= " lic211_sequencial = $lic211_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result==false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "licitacoes de outros orgaos nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$lic211_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result)==0) {
         $this->erro_banco = "";
         $this->erro_sql = "licitacoes de outros orgaos nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$lic211_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$lic211_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:liclicitaoutrosorgaos";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    return $result;
  }

  // funcao do sql 
  function sql_query ( $lic211_sequencial=null,$campos="*",$ordem=null,$dbwhere="") { 
     $sql = "select ";
     if ($campos != "liclicitaoutrosorgaos.*,z01_nome" ) {
       $campos_sql = explode("#", $campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++) {
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     } else {
       $sql .= $campos;
     }
     $sql .= " from liclicitaoutrosorgaos ";
     $sql .= " inner join cgm on z01_numcgm = lic211_orgao ";

      $sql2 = "";
     if ($dbwhere=="") {
       if ($lic211_sequencial!=null ) {
         $sql2 .= " where liclicitaoutrosorgaos.lic211_sequencial = $lic211_sequencial "; 
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
//    die($sql);
    return $sql;
  }

  // funcao do sql 
  function sql_query_file ( $lic211_sequencial=null,$campos="*",$ordem=null,$dbwhere="") { 
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
     $sql .= " from liclicitaoutrosorgaos ";
     $sql2 = "";
     if ($dbwhere=="") {
       if ($lic211_sequencial!=null ) {
         $sql2 .= " where liclicitaoutrosorgaos.lic211_sequencial = $lic211_sequencial "; 
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

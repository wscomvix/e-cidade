<?php
//MODULO: sicom
//CLASSE DA ENTIDADE saldotransfctb
class cl_saldotransfctb { 
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
  public $si202_seq = 0;
  public $si202_codctb = 0; 
  public $si202_codfontrecursos = 0; 
  public $si202_saldofinal = 0; 
  public $si202_anousu = 0; 
  public $si202_instit = 0; 
  // cria propriedade com as variaveis do arquivo 
  public $campos = "
                 si202_seq = int8 = Sequencial
                 si202_codctb = int8 = C�digo CTB 
                 si202_codfontrecursos = int4 = Fonte 
                 si202_saldofinal = float8 = Saldo Final 
                 si202_anousu = int4 = Ano 
                 si202_instit = int4 = Institui��o 
                 ";

  //funcao construtor da classe 
  function __construct() { 
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("saldotransfctb"); 
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
		$this->si202_seq = ($this->si202_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["si202_seq"]:$this->si202_seq);
		$this->si202_codctb = ($this->si202_codctb == ""?@$GLOBALS["HTTP_POST_VARS"]["si202_codctb"]:$this->si202_codctb);
		$this->si202_codfontrecursos = ($this->si202_codfontrecursos == ""?@$GLOBALS["HTTP_POST_VARS"]["si202_codfontrecursos"]:$this->si202_codfontrecursos);
		$this->si202_saldofinal = ($this->si202_saldofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["si202_saldofinal"]:$this->si202_saldofinal);
		$this->si202_anousu = ($this->si202_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["si202_anousu"]:$this->si202_anousu);
		$this->si202_instit = ($this->si202_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["si202_instit"]:$this->si202_instit);
     } else {
     }
   }

  // funcao para inclusao
  function incluir ($si202_seq=null) { 
      $this->atualizacampos();
	  if($si202_seq == null || $si202_seq == ""){ 
        $result = db_query("select nextval('saldotransfctb_si202_seq_seq')");
        if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: saldotransfctb_si202_seq_seq do campo: si202_seq"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->si202_seq = pg_result($result,0,0); 
      }else{
       $result = db_query("select last_value from saldotransfctb_si202_seq_seq");
       if(($result != false) && (pg_result($result,0,0) < $si202_seq)){
         $this->erro_sql = " Campo si202_seq maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->si202_seq = $si202_seq; 
       }
     }
     if ($this->si202_codctb == null ) { 
       $this->erro_sql = " Campo C�digo CTB n�o informado.";
       $this->erro_campo = "si202_codctb";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->si202_codfontrecursos == null ) { 
       $this->erro_sql = " Campo Fonte n�o informado.";
       $this->erro_campo = "si202_codfontrecursos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->si202_saldofinal == null ) { 
       $this->erro_sql = " Campo Saldo Final n�o informado.";
       $this->erro_campo = "si202_saldofinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->si202_anousu == null ) { 
       $this->erro_sql = " Campo Ano n�o informado.";
       $this->erro_campo = "si202_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->si202_instit == null ) { 
       $this->erro_sql = " Campo Institui��o n�o informado.";
       $this->erro_campo = "si202_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into saldotransfctb(
									   si202_seq
                                      ,si202_codctb 
                                      ,si202_codfontrecursos 
                                      ,si202_saldofinal 
                                      ,si202_anousu 
                                      ,si202_instit 
                       )
                values (
                                $this->si202_seq 
							   ,$this->si202_codctb 
                               ,$this->si202_codfontrecursos 
                               ,$this->si202_saldofinal 
                               ,$this->si202_anousu 
                               ,$this->si202_instit 
                      )";
     $result = db_query($sql); 
     if ($result==false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if ( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ) {
         $this->erro_sql   = "Saldos Transfer�ncia CTB () nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Saldos Transfer�ncia CTB j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       } else {
         $this->erro_sql   = "Saldos Transfer�ncia CTB () nao Inclu�do. Inclusao Abortada.";
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
  function alterar ( $si202_seq=null ) { 
      $this->atualizacampos();
     $sql = " update saldotransfctb set ";
     $virgula = "";
     if (trim($this->si202_codctb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si202_codctb"])) { 
       $sql  .= $virgula." si202_codctb = $this->si202_codctb ";
       $virgula = ",";
       if (trim($this->si202_codctb) == null ) { 
         $this->erro_sql = " Campo C�digo CTB n�o informado.";
         $this->erro_campo = "si202_codctb";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->si202_codfontrecursos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si202_codfontrecursos"])) { 
       $sql  .= $virgula." si202_codfontrecursos = $this->si202_codfontrecursos ";
       $virgula = ",";
       if (trim($this->si202_codfontrecursos) == null ) { 
         $this->erro_sql = " Campo Fonte n�o informado.";
         $this->erro_campo = "si202_codfontrecursos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->si202_saldofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si202_saldofinal"])) { 
       $sql  .= $virgula." si202_saldofinal = $this->si202_saldofinal ";
       $virgula = ",";
       if (trim($this->si202_saldofinal) == null ) { 
         $this->erro_sql = " Campo Saldo Final n�o informado.";
         $this->erro_campo = "si202_saldofinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->si202_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si202_anousu"])) { 
       $sql  .= $virgula." si202_anousu = $this->si202_anousu ";
       $virgula = ",";
       if (trim($this->si202_anousu) == null ) { 
         $this->erro_sql = " Campo Ano n�o informado.";
         $this->erro_campo = "si202_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (trim($this->si202_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si202_instit"])) { 
       $sql  .= $virgula." si202_instit = $this->si202_instit ";
       $virgula = ",";
       if (trim($this->si202_instit) == null ) { 
         $this->erro_sql = " Campo Institui��o n�o informado.";
         $this->erro_campo = "si202_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     $sql .= "si202_seq = '$si202_seq' "; 
     $result = db_query($sql);
     if ($result==false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Saldos Transfer�ncia CTB nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result)==0) {
         $this->erro_banco = "";
         $this->erro_sql = "Saldos Transfer�ncia CTB nao foi Alterado. Alteracao Executada.\\n";
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
  function excluir ( $si202_seq=null,$dbwhere=null) { 

     $sql = " delete from saldotransfctb
                    where ";
     $sql2 = "";
     if ($dbwhere==null || $dbwhere =="") {
       $sql2 = "si202_seq = '$si202_seq'";
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result==false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Saldos Transfer�ncia CTB nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result)==0) {
         $this->erro_banco = "";
         $this->erro_sql = "Saldos Transfer�ncia CTB nao Encontrado. Exclus�o n�o Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:saldotransfctb";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
    return $result;
  }

  // funcao do sql 
  function sql_query ( $si202_seq = null,$campos="*",$ordem=null,$dbwhere="") { 
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
     $sql .= " from saldotransfctb ";
     $sql2 = "";
     if ($dbwhere=="") {
       if ( $si202_seq != "" && $si202_seq != null) {
          $sql2 = " where saldotransfctb.si202_seq = '$si202_seq'";
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
	// die($sql);
    return $sql;
  }

  // funcao do sql 
  function sql_query_file ( $si202_seq = null,$campos="*",$ordem=null,$dbwhere="") { 
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
     $sql .= " from saldotransfctb ";
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

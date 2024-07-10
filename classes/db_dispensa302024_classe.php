<?
//MODULO: sicom
//CLASSE DA ENTIDADE dispensa302024
class cl_dispensa302024 {
   // cria variaveis de erro
   var $rotulo     = null;
   var $query_sql  = null;
   var $numrows    = 0;
   var $numrows_incluir = 0;
   var $numrows_alterar = 0;
   var $numrows_excluir = 0;
   var $erro_status= null;
   var $erro_sql   = null;
   var $erro_banco = null;
   var $erro_msg   = null;
   var $erro_campo = null;
   var $pagina_retorno = null;
   // cria variaveis do arquivo
   var $si203_sequencial = 0;
   var $si203_tiporegistro = null;
   var $si203_codorgaoresp = null;
   var $si203_codunidadesubresp = null;
   var $si203_exercicioprocesso = null;
   var $si203_nroprocesso = null;
   var $si203_tipoprocesso = null;
   var $si203_tipodocumento = null;
   var $si203_nrodocumento = null;
   var $si203_nrolote = null;
   var $si203_coditem = null;
   var $si203_percdesconto = null;
   var $si203_mes = null;
   var $si203_instit = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                var si203_sequencial = int = Sequencial;
                var si203_tiporegistro = int = Tipo Registro;
                var si203_codorgaoresp = int = Cod Orgao Resp;
                var si203_codunidadesubresp = varchar (8) = Cod Unidade Sub Resp;
                var si203_exercicioprocesso = int = Exercicio Processo;
                var si203_nroprocesso = varchar(16) = Nro Processo;
                var si203_tipoprocesso = int = Tipo Processo;
                var si203_tipodocumento = int = Tipo Documento;
                var si203_nrodocumento = varchar(14) = Nro Documento;
                var si203_nrolote = int = Nro Lote;
                var si203_coditem = int = Cod Item;
                var si203_percdesconto = int = Perc Desconto;
                var si203_mes = int = Mes;
                 ";
   //funcao construtor da classe
   function cl_dispensa302024() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("dispensa302024");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro
   function erro($mostra,$retorna) {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->si203_sequencial = ($this->si203_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si203_sequencial"]:$this->si203_sequencial);
       $this->si203_tiporegistro = ($this->si203_tiporegistro == ""?@$GLOBALS["HTTP_POST_VARS"]["si203_tiporegistro"]:$this->si203_tiporegistro);
       $this->si203_codorgaoresp = ($this->si203_codorgaoresp == ""?@$GLOBALS["HTTP_POST_VARS"]["si203_codorgaoresp"]:$this->si203_codorgaoresp);
       $this->si203_codunidadesubresp = ($this->si203_codunidadesubresp == ""?@$GLOBALS["HTTP_POST_VARS"]["si203_codunidadesubresp"]:$this->si203_codunidadesubresp);
       $this->si203_exercicioprocesso = ($this->si203_exercicioprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["si203_exercicioprocesso"]:$this->si203_exercicioprocesso);
       $this->si203_nroprocesso = ($this->si203_nroprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["si203_nroprocesso"]:$this->si203_nroprocesso);
       $this->si203_tipoprocesso = ($this->si203_tipoprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["si203_tipoprocesso"]:$this->si203_tipoprocesso);
       $this->si203_tipodocumento = ($this->si203_tipodocumento == ""?@$GLOBALS["HTTP_POST_VARS"]["si203_tipodocumento"]:$this->si203_tipodocumento);
       $this->si203_nrodocumento = ($this->si203_nrodocumento == ""?@$GLOBALS["HTTP_POST_VARS"]["si203_nrodocumento"]:$this->si203_nrodocumento);
       $this->si203_nrolote = ($this->si203_nrolote == ""?@$GLOBALS["HTTP_POST_VARS"]["si203_nrolote"]:$this->si203_nrolote);
       $this->si203_mes = ($this->si203_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["si203_mes"]:$this->si203_mes);
       $this->si203_instit = ($this->si203_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["si203_instit"]:$this->si203_instit);
     }else{
      $this->si203_sequencial = ($this->si203_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si203_sequencial"]:$this->si203_sequencial);
    }
   }
   // funcao para inclusao
   function incluir ($si203_sequencial){
      $this->atualizacampos();
     if($this->si203_tiporegistro == null ){
       $this->erro_sql = " Campo Tipo do  registro nao Informado.";
       $this->erro_campo = "si203_sequencial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->si203_codorgaoresp == null ){
      $this->erro_sql = " Campo Orgao Resp nao Informado.";
      $this->erro_campo = "si203_sequencial";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      $this->erro_status = "0";
      return false;
    }

     if($this->si203_codunidadesubresp == null ){
       $this->erro_sql = " Campo Cod Unidade Sub Resp nao Informado.";
       $this->erro_campo = "si203_codunidadesubresp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->si203_exercicioprocesso == null ){
       $this->erro_sql = " Campo Exercicio Processo nao Informado.";
       $this->erro_campo = "si203_exercicioprocesso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       $this->erro_status = "0";
       return false;
     }

     if($this->si203_nroprocesso == null ){
      $this->erro_sql = " Campo Nro Processo nao Informado.";
      $this->erro_campo = "si203_nroprocesso";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      $this->erro_status = "0";
      return false;
    }

    if($this->si203_tipoprocesso == null ){
      $this->erro_sql = " Campo Tipo Processo nao Informado.";
      $this->erro_campo = "si203_tipoprocesso";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      $this->erro_status = "0";
      return false;
    }

    if($this->si203_tipodocumento == null ){
      $this->erro_sql = " Campo Tipo Documento nao Informado.";
      $this->erro_campo = "si203_tipodocumento";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      $this->erro_status = "0";
      return false;
    }

    if($this->si203_nrodocumento == null ){
      $this->erro_sql = " Campo Nro Documento nao Informado.";
      $this->erro_campo = "si203_nrodocumento";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      $this->erro_status = "0";
      return false;
    }

    if($this->si203_percdesconto == null ){
      $this->erro_sql = " Campo Perc Desconto nao Informado.";
      $this->erro_campo = "si203_percdesconto";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
      $this->erro_status = "0";
      return false;
    }

     if($si203_sequencial == "" || $si203_sequencial == null ){
       $result = db_query("select nextval('dispensa302024_si203_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("
","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: dispensa302024_si203_sequencial_seq do campo: si203_sequencial";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "0";
         return false;
       }
       $this->si203_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from dispensa302024_si203_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $si203_sequencial)){
         $this->erro_sql = " Campo si203_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->si203_sequencial = $si203_sequencial;
       }
     }
     if(($this->si203_sequencial == null) || ($this->si203_sequencial == "") ){
       $this->erro_sql = " Campo si203_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into dispensa302024(
                                      si203_sequencial
                                      ,si203_tiporegistro
                                      ,si203_codorgaoresp
                                      ,si203_codunidadesubresp
                                      ,si203_exercicioprocesso
                                      ,si203_nroprocesso
                                      ,si203_tipoprocesso
                                      ,si203_tipodocumento
                                      ,si203_nrodocumento
                                      ,si203_nrolote
                                      ,si203_coditem
                                      ,si203_percdesconto
                                      ,si203_mes
                                      ,si203_instit
                       )
                values (
                                $this->si203_sequencial
                               ,$this->si203_tiporegistro
                               ,$this->si203_codorgaoresp
                               ,'$this->si203_codunidadesubresp'
                               ,$this->si203_exercicioprocesso
                               ,'$this->si203_nroprocesso'
                               ,$this->si203_tipoprocesso
                               ,$this->si203_tipodocumento
                               ,'$this->si203_nrodocumento'
                               ," . ($this->si203_nrolote == "null" || $this->si203_nrolote == "" ? "null" : "'" . $this->si203_nrolote . "'") . "
                               ," . ($this->si203_coditem == "null" || $this->si203_coditem == "" ? "null" : "'" . $this->si203_coditem . "'") . "
                               ,$this->si203_percdesconto
                               ,$this->si203_mes
                               ,$this->si203_instit
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("
","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "dispensa302024 ($this->si75_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_banco = "dispensa302024 j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       }else{
         $this->erro_sql   = "dispensa302024 ($this->si75_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\n";
         $this->erro_sql .= "Valores : ".$this->si75_sequencial;
     $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);

     return true;
   }

   // funcao para exclusao
   function excluir ($si203_sequencial=null,$dbwhere=null) {

     $sql = " delete from dispensa302024
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($si203_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " si203_sequencial = $si203_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("
","",@pg_last_error());
       $this->erro_sql   = "dispensa302024 nao Exclu�do. Exclus�o Abortada.\n";
       $this->erro_sql .= "Valores : ".$si203_sequencial;
       $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "dispensa302024 nao Encontrado. Exclus�o n�o Efetuada.\n";
         $this->erro_sql .= "Valores : ".$si203_sequencial;
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
         $this->erro_sql .= "Valores : ".$si203_sequencial;
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }

   // funcao do recordset
   function sql_record($sql) {
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("
","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:dispensa302024";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

   // funcao do sql
   function sql_query ( $si203_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from dispensa302024 ";
     $sql2 = "";
     if($dbwhere==""){
       if($si203_sequencial!=null ){
         $sql2 .= " where dispensa302024.si203_sequencial = $si203_sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql
   function sql_query_file ( $si203_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from dispensa302024 ";
     $sql2 = "";
     if($dbwhere==""){
       if($si203_sequencial!=null ){
         $sql2 .= " where dispensa302024.si203_sequencial = $si203_sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>

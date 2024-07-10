<?
//MODULO: sicom
//CLASSE DA ENTIDADE dfcdcasp902019
class cl_dfcdcasp902019 {
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
   var $si227_anousu  = 0;
   var $si227_periodo = 0;
   var $si227_instit  = 0;
   var $si227_sequencial = 0;
   var $si227_tiporegistro = 0;
   var $si227_vlfluxocaixafinanciamento = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 si227_anousu = int4 = si227_anousu
                 si227_periodo = int4 = si227_periodo
                 si227_instit = int4 = si227_instit
                 si227_sequencial = int4 = si227_sequencial
                 si227_tiporegistro = int4 = si227_tiporegistro
                 si227_vlfluxocaixafinanciamento = float8 = si227_vlfluxocaixafinanciamento
                 ";
   //funcao construtor da classe
   function cl_dfcdcasp902019() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("dfcdcasp902019");
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
       $this->si227_anousu = ($this->si227_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["si227_anousu"]:$this->si227_anousu);
       $this->si227_periodo = ($this->si227_periodo == ""?@$GLOBALS["HTTP_POST_VARS"]["si227_periodo"]:$this->si227_periodo);
       $this->si227_instit = ($this->si227_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["si227_instit"]:$this->si227_instit);
       $this->si227_sequencial = ($this->si227_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si227_sequencial"]:$this->si227_sequencial);
       $this->si227_tiporegistro = ($this->si227_tiporegistro == ""?@$GLOBALS["HTTP_POST_VARS"]["si227_tiporegistro"]:$this->si227_tiporegistro);
       $this->si227_vlfluxocaixafinanciamento = ($this->si227_vlfluxocaixafinanciamento == ""?@$GLOBALS["HTTP_POST_VARS"]["si227_vlfluxocaixafinanciamento"]:$this->si227_vlfluxocaixafinanciamento);
     }else{
       $this->si227_sequencial = ($this->si227_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si227_sequencial"]:$this->si227_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($si227_sequencial){
      $this->atualizacampos();
     if (empty($this->si227_anousu)) {
       $this->si227_anousu = db_getsession("DB_anousu");
     }
     if (empty($this->si227_periodo)) {
       $this->si227_periodo = 28;
     }
     if (empty($this->si227_instit)) {
       $this->si227_instit = db_getsession("DB_instit");
     }
     if (empty($this->si227_tiporegistro)) {
        $this->si227_tiporegistro = 90;
     }
     if (empty($this->si227_vlfluxocaixafinanciamento)) {
        $this->si227_vlfluxocaixafinanciamento = 0;
     }

     if(empty($si227_sequencial)){
       $result = db_query("select nextval('dfcdcasp902019_si227_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("
","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: dfcdcasp902019_si227_sequencial_seq do campo: si227_sequencial";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "0";
         return false;
       }
       $this->si227_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from dfcdcasp902019_si227_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $si227_sequencial)){
         $this->erro_sql = " Campo si227_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->si227_sequencial = $si227_sequencial;
       }
     }


     $sql = "insert into dfcdcasp902019(
                                       si227_anousu
                                      ,si227_periodo
                                      ,si227_instit
                                      ,si227_sequencial
                                      ,si227_tiporegistro
                                      ,si227_vlfluxocaixafinanciamento
                       )
                values (
                                {$this->si227_anousu}
                               ,{$this->si227_periodo}
                               ,{$this->si227_instit}
                               ,$this->si227_sequencial
                               ,$this->si227_tiporegistro
                               ,$this->si227_vlfluxocaixafinanciamento
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("
","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "dfcdcasp902019 ($this->si227_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_banco = "dfcdcasp902019 j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       }else{
         $this->erro_sql   = "dfcdcasp902019 ($this->si227_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\n";
         $this->erro_sql .= "Valores : ".$this->si227_sequencial;
     $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);

     return true;
   }
   // funcao para alteracao
   function alterar ($si227_sequencial=null) {
      $this->atualizacampos();
     $sql = " update dfcdcasp902019 set ";
     $virgula = "";
     if(trim($this->si227_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si227_sequencial"])){
       $sql  .= $virgula." si227_sequencial = $this->si227_sequencial ";
       $virgula = ",";
       if(trim($this->si227_sequencial) == null ){
         $this->erro_sql = " Campo si227_sequencial n�o informado.";
         $this->erro_campo = "si227_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si227_tiporegistro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si227_tiporegistro"])){
       $sql  .= $virgula." si227_tiporegistro = $this->si227_tiporegistro ";
       $virgula = ",";
       if(trim($this->si227_tiporegistro) == null ){
         $this->erro_sql = " Campo si227_tiporegistro n�o informado.";
         $this->erro_campo = "si227_tiporegistro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si227_vlfluxocaixafinanciamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si227_vlfluxocaixafinanciamento"])){
       $sql  .= $virgula." si227_vlfluxocaixafinanciamento = $this->si227_vlfluxocaixafinanciamento ";
       $virgula = ",";
       if(trim($this->si227_vlfluxocaixafinanciamento) == null ){
         $this->erro_sql = " Campo si227_vlfluxocaixafinanciamento n�o informado.";
         $this->erro_campo = "si227_vlfluxocaixafinanciamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($si227_sequencial!=null){
       $sql .= " si227_sequencial = $this->si227_sequencial";
     }

     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("
","",@pg_last_error());
       $this->erro_sql   = "dfcdcasp902019 nao Alterado. Alteracao Abortada.\n";
         $this->erro_sql .= "Valores : ".$this->si227_sequencial;
       $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "dfcdcasp902019 nao foi Alterado. Alteracao Executada.\n";
         $this->erro_sql .= "Valores : ".$this->si227_sequencial;
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\n";
         $this->erro_sql .= "Valores : ".$this->si227_sequencial;
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($si227_sequencial=null,$dbwhere=null) {

     $sql = " delete from dfcdcasp902019
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($si227_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " si227_sequencial = $si227_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("
","",@pg_last_error());
       $this->erro_sql   = "dfcdcasp902019 nao Exclu�do. Exclus�o Abortada.\n";
       $this->erro_sql .= "Valores : ".$si227_sequencial;
       $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "dfcdcasp902019 nao Encontrado. Exclus�o n�o Efetuada.\n";
         $this->erro_sql .= "Valores : ".$si227_sequencial;
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
         $this->erro_sql .= "Valores : ".$si227_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:dfcdcasp902019";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $si227_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = explode("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from dfcdcasp902019 ";
     $sql2 = "";
     if($dbwhere==""){
       if($si227_sequencial!=null ){
         $sql2 .= " where dfcdcasp902019.si227_sequencial = $si227_sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = explode("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql
   function sql_query_file ( $si227_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = explode("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from dfcdcasp902019 ";
     $sql2 = "";
     if($dbwhere==""){
       if($si227_sequencial!=null ){
         $sql2 .= " where dfcdcasp902019.si227_sequencial = $si227_sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = explode("#",$ordem);
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

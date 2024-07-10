<?
//MODULO: sicom
//CLASSE DA ENTIDADE dfcdcasp102018
class cl_dfcdcasp102018 {
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
   var $si219_anousu  = 0;
   var $si219_periodo = 0;
   var $si219_instit  = 0;
   var $si219_sequencial = 0;
   var $si219_tiporegistro = 0;
   var $si219_vlreceitaderivadaoriginaria = 0;
   var $si219_vltranscorrenterecebida = 0;
   var $si219_vloutrosingressosoperacionais = 0;
   var $si219_vltotalingressosativoperacionais = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 si219_anousu = int4 = si219_anousu
                 si219_periodo = int4 = si219_periodo
                 si219_instit = int4 = si219_instit
                 si219_sequencial = int4 = si219_sequencial
                 si219_tiporegistro = int4 = si219_tiporegistro
                 si219_vlreceitaderivadaoriginaria = float8 = si219_vlreceitaderivadaoriginaria
                 si219_vltranscorrenterecebida = float8 = si219_vltranscorrenterecebida
                 si219_vloutrosingressosoperacionais = float8 = si219_vloutrosingressosoperacionais
                 si219_vltotalingressosativoperacionais = float8 = si219_vltotalingressosativoperacionais
                 ";
   //funcao construtor da classe
   function cl_dfcdcasp102018() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("dfcdcasp102018");
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
       $this->si219_anousu = ($this->si219_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["si219_anousu"]:$this->si219_anousu);
       $this->si219_periodo = ($this->si219_periodo == ""?@$GLOBALS["HTTP_POST_VARS"]["si219_periodo"]:$this->si219_periodo);
       $this->si219_instit = ($this->si219_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["si219_instit"]:$this->si219_instit);
       $this->si219_sequencial = ($this->si219_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si219_sequencial"]:$this->si219_sequencial);
       $this->si219_tiporegistro = ($this->si219_tiporegistro == ""?@$GLOBALS["HTTP_POST_VARS"]["si219_tiporegistro"]:$this->si219_tiporegistro);
       $this->si219_vlreceitaderivadaoriginaria = ($this->si219_vlreceitaderivadaoriginaria == ""?@$GLOBALS["HTTP_POST_VARS"]["si219_vlreceitaderivadaoriginaria"]:$this->si219_vlreceitaderivadaoriginaria);
       $this->si219_vltranscorrenterecebida = ($this->si219_vltranscorrenterecebida == ""?@$GLOBALS["HTTP_POST_VARS"]["si219_vltranscorrenterecebida"]:$this->si219_vltranscorrenterecebida);
       $this->si219_vloutrosingressosoperacionais = ($this->si219_vloutrosingressosoperacionais == ""?@$GLOBALS["HTTP_POST_VARS"]["si219_vloutrosingressosoperacionais"]:$this->si219_vloutrosingressosoperacionais);
       $this->si219_vltotalingressosativoperacionais = ($this->si219_vltotalingressosativoperacionais == ""?@$GLOBALS["HTTP_POST_VARS"]["si219_vltotalingressosativoperacionais"]:$this->si219_vltotalingressosativoperacionais);
     }else{
       $this->si219_sequencial = ($this->si219_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["si219_sequencial"]:$this->si219_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($si219_sequencial){
      $this->atualizacampos();
     if ($this->si219_anousu == null ) {
       $this->si219_anousu = db_getsession("DB_anousu");
     }
     if ($this->si219_periodo == null ) {
       $this->si219_periodo = 28;
     }
     if ($this->si219_instit == null ) {
       $this->si219_instit = db_getsession("DB_instit");
     }

     if($this->si219_tiporegistro == null ){
        $this->si219_tiporegistro = 10;
     }
     if(empty($this->si219_vlreceitaderivadaoriginaria)){
        $this->si219_vlreceitaderivadaoriginaria = 0;
     }
     if(empty($this->si219_vltranscorrenterecebida)){
        $this->si219_vltranscorrenterecebida = 0;
     }
     if(empty($this->si219_vloutrosingressosoperacionais)){
        $this->si219_vloutrosingressosoperacionais = 0;
     }
     if(empty($this->si219_vltotalingressosativoperacionais)){
        $this->si219_vltotalingressosativoperacionais = 0;
     }


     if(empty($si219_sequencial)){
       $result = db_query("select nextval('dfcdcasp102018_si219_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("
","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: dfcdcasp102018_si219_sequencial_seq do campo: si219_sequencial";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "0";
         return false;
       }
       $this->si219_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from dfcdcasp102018_si219_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $si219_sequencial)){
         $this->erro_sql = " Campo si219_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->si219_sequencial = $si219_sequencial;
       }
     }


     $sql = "insert into dfcdcasp102018(
                                       si219_anousu
                                      ,si219_periodo
                                      ,si219_instit
                                      ,si219_sequencial
                                      ,si219_tiporegistro
                                      ,si219_vlreceitaderivadaoriginaria
                                      ,si219_vltranscorrenterecebida
                                      ,si219_vloutrosingressosoperacionais
                                      ,si219_vltotalingressosativoperacionais
                       )
                values (
                                {$this->si219_anousu}
                               ,{$this->si219_periodo}
                               ,{$this->si219_instit}
                               ,$this->si219_sequencial
                               ,$this->si219_tiporegistro
                               ,$this->si219_vlreceitaderivadaoriginaria
                               ,$this->si219_vltranscorrenterecebida
                               ,$this->si219_vloutrosingressosoperacionais
                               ,$this->si219_vltotalingressosativoperacionais
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("
","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "dfcdcasp102018 ($this->si219_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_banco = "dfcdcasp102018 j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       }else{
         $this->erro_sql   = "dfcdcasp102018 ($this->si219_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\n";
         $this->erro_sql .= "Valores : ".$this->si219_sequencial;
     $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);

     return true;
   }
   // funcao para alteracao
   function alterar ($si219_sequencial=null) {
      $this->atualizacampos();
     $sql = " update dfcdcasp102018 set ";
     $virgula = "";
     if(trim($this->si219_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si219_sequencial"])){
       $sql  .= $virgula." si219_sequencial = $this->si219_sequencial ";
       $virgula = ",";
       if(trim($this->si219_sequencial) == null ){
         $this->erro_sql = " Campo si219_sequencial n�o informado.";
         $this->erro_campo = "si219_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si219_tiporegistro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si219_tiporegistro"])){
       $sql  .= $virgula." si219_tiporegistro = $this->si219_tiporegistro ";
       $virgula = ",";
       if(trim($this->si219_tiporegistro) == null ){
         $this->erro_sql = " Campo si219_tiporegistro n�o informado.";
         $this->erro_campo = "si219_tiporegistro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si219_vlreceitaderivadaoriginaria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si219_vlreceitaderivadaoriginaria"])){
       $sql  .= $virgula." si219_vlreceitaderivadaoriginaria = $this->si219_vlreceitaderivadaoriginaria ";
       $virgula = ",";
       if(trim($this->si219_vlreceitaderivadaoriginaria) == null ){
         $this->erro_sql = " Campo si219_vlreceitaderivadaoriginaria n�o informado.";
         $this->erro_campo = "si219_vlreceitaderivadaoriginaria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si219_vltranscorrenterecebida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si219_vltranscorrenterecebida"])){
       $sql  .= $virgula." si219_vltranscorrenterecebida = $this->si219_vltranscorrenterecebida ";
       $virgula = ",";
       if(trim($this->si219_vltranscorrenterecebida) == null ){
         $this->erro_sql = " Campo si219_vltranscorrenterecebida n�o informado.";
         $this->erro_campo = "si219_vltranscorrenterecebida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si219_vloutrosingressosoperacionais)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si219_vloutrosingressosoperacionais"])){
       $sql  .= $virgula." si219_vloutrosingressosoperacionais = $this->si219_vloutrosingressosoperacionais ";
       $virgula = ",";
       if(trim($this->si219_vloutrosingressosoperacionais) == null ){
         $this->erro_sql = " Campo si219_vloutrosingressosoperacionais n�o informado.";
         $this->erro_campo = "si219_vloutrosingressosoperacionais";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si219_vltotalingressosativoperacionais)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si219_vltotalingressosativoperacionais"])){
       $sql  .= $virgula." si219_vltotalingressosativoperacionais = $this->si219_vltotalingressosativoperacionais ";
       $virgula = ",";
       if(trim($this->si219_vltotalingressosativoperacionais) == null ){
         $this->erro_sql = " Campo si219_vltotalingressosativoperacionais n�o informado.";
         $this->erro_campo = "si219_vltotalingressosativoperacionais";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($si219_sequencial!=null){
       $sql .= " si219_sequencial = $this->si219_sequencial";
     }

     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("
","",@pg_last_error());
       $this->erro_sql   = "dfcdcasp102018 nao Alterado. Alteracao Abortada.\n";
         $this->erro_sql .= "Valores : ".$this->si219_sequencial;
       $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "dfcdcasp102018 nao foi Alterado. Alteracao Executada.\n";
         $this->erro_sql .= "Valores : ".$this->si219_sequencial;
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\n";
         $this->erro_sql .= "Valores : ".$this->si219_sequencial;
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($si219_sequencial=null,$dbwhere=null) {

     $sql = " delete from dfcdcasp102018
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($si219_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " si219_sequencial = $si219_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("
","",@pg_last_error());
       $this->erro_sql   = "dfcdcasp102018 nao Exclu�do. Exclus�o Abortada.\n";
       $this->erro_sql .= "Valores : ".$si219_sequencial;
       $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "dfcdcasp102018 nao Encontrado. Exclus�o n�o Efetuada.\n";
         $this->erro_sql .= "Valores : ".$si219_sequencial;
         $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
         $this->erro_sql .= "Valores : ".$si219_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:dfcdcasp102018";
        $this->erro_msg   = "Usu�rio: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \n\n ".$this->erro_banco." \n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $si219_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from dfcdcasp102018 ";
     $sql2 = "";
     if($dbwhere==""){
       if($si219_sequencial!=null ){
         $sql2 .= " where dfcdcasp102018.si219_sequencial = $si219_sequencial ";
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
   function sql_query_file ( $si219_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from dfcdcasp102018 ";
     $sql2 = "";
     if($dbwhere==""){
       if($si219_sequencial!=null ){
         $sql2 .= " where dfcdcasp102018.si219_sequencial = $si219_sequencial ";
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
